'use strict';

angular.module('app', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngTouch',
    'ngStorage',
    'ui.router',
    'ui.bootstrap',
    'ui.load',
    'ui.jq',
    'ui.validate',
    'oc.lazyLoad',
    'xeditable',
    "checklist-model",
    'flow',
]);

function appRunCallback(editableOptions, $rootScope,   $state,   $stateParams) {
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;
    editableOptions.theme = 'bs3';
}

function isSmartDevice( $window ){
    var ua = $window['navigator']['userAgent'] || $window['navigator']['vendor'] || $window['opera'];
    return (/iPhone|iPod|iPad|Silk|Android|BlackBerry|Opera Mini|IEMobile/).test(ua);
}


//取生肖, 参数必须是四位的年
function getshengxiao(yyyy){
    var arr=['猴','鸡','狗','猪','鼠','牛','虎','兔','龙','蛇','马','羊'];
    return /^\d{4}$/.test(yyyy)?arr[yyyy%12]:null
}

// 取星座, 参数分别是 月份和日期
function getxingzuo(month,day){
    var d=new Date(1999,month,day,0,0,0);
    var arr=[];
    arr.push(["摩羯座",new Date(1999, 0, 1,0,0,0)])
    arr.push(["水瓶座",new Date(1999, 0,20,0,0,0)])
    arr.push(["双鱼座",new Date(1999, 1,19,0,0,0)])
    arr.push(["白羊座",new Date(1999, 2,21,0,0,0)])
    arr.push(["金牛座",new Date(1999, 3,21,0,0,0)])
    arr.push(["双子座",new Date(1999, 4,21,0,0,0)])
    arr.push(["巨蟹座",new Date(1999, 5,22,0,0,0)])
    arr.push(["狮子座",new Date(1999, 6,23,0,0,0)])
    arr.push(["处女座",new Date(1999, 7,23,0,0,0)])
    arr.push(["天秤座",new Date(1999, 8,23,0,0,0)])
    arr.push(["天蝎座",new Date(1999, 9,23,0,0,0)])
    arr.push(["射手座",new Date(1999,10,22,0,0,0)])
    arr.push(["摩羯座",new Date(1999,11,22,0,0,0)])
    for(var i=arr.length-1;i>=0;i--){
        if (d>=arr[i][1]) {
            return arr[i][0];
        }
    }
}

function getbirth(value) {
    if (!value) {
        return "";
    }
    var year = "1900";
    var month = "1";
    var day = "1";
    if (value.length == 15) {
        year = "19" + value.substr(6, 2);
        month = value.substr(8, 2);
        day = value.substr(10, 2);
    } else if (value.length == 18) {
        year = value.substr(6, 4);
        month = value.substr(10, 2);
        day = value.substr(12, 2);
    } else {
        return "";
    }
    var newDate = new Date(year, month - 1, day);
    if (newDate.toString() == "NaN") {
        return "";
    }
    else {

        return year + "-" + month + "-" + day;
    }
}


var SPECIAL_CHARS_REGEXP = /([\:\-\_]+(.))/g;
var MOZ_HACK_REGEXP = /^moz([A-Z])/;
function camelCase(name) {
    return name.
        replace(SPECIAL_CHARS_REGEXP, function(_, separator, letter, offset) {
            return offset ? letter.toUpperCase() : letter;
        }).
        replace(MOZ_HACK_REGEXP, 'Moz$1');
}

function formatField(field, val, model) {
    var data = val;
    if (field['form_type'] == "datetime") {
        if (val != 0) {
            data =  moment.unix(val).toDate();
        } else {
            data = null;
        }
    }else if (field['form_type'] == "number" ) {
        data =  parseInt(val);
    }else if (field['form_type'] == "floatnumber") {
        data =  parseFloat(val).toFixed(2);
    }else if (field['form_type'] == "p_box") {
        if (data) {
            var newdata = data;
            if (!angular.isArray(newdata)) {
                newdata = data.split(",");
            }
            var setting = [];
            for(var k1 in newdata) {
                for(var k2 in field.data) {
                    var val = newdata[k1].category_id?newdata[k1].category_id:newdata[k1];
                    if (val == field.data[k2].category_id) {
                        setting.push(field.data[k2]);
                    }
                }
            }
            data = setting;
        }
    }else if (field['form_type'] == "pic") {
        data = data || [];
    }
    return data;
}

var condition_map = {
    gt:"大于",
    lt:"小于",
    eq:"等于",
    neq:"不等于",
    between:"在之间",
    not_between:"不在之间",
    ltb:"早于起始时间",
    gtb:"晚于起始时间",
    lte:"早于结束时间",
    gte:"晚于结束时间",
    contains:"包含",
    not_contain:"不包含",
    is:"是",
    isnot:"不是",
    start_with:"开始于",
    end_with:"结束于",
    is_empty:"为空",
    is_not_empty:"不为空"
}

function showConditionField(field, data, model) {
    var cond = "";
    var value = showField(field, data.value,model);
    if (data.cond) {
        cond = condition_map[data.cond];
    }
    return cond + value;
}

function showField(field, data, model) {
    if (field['form_type'] == "branch" || field['field'] == "branch_id") {
        if (data && data.branch_id > 0) {
            data = data.name;
        } else {
            data = "公司总部";
        }
    }else if (field['form_type'] == "datetime" && data) {
        if (field['range'] == '1' && angular.isArray(data) && data.length == 2) {
            if (data[0] && data[1]) {
                data =  moment(data[0]).format("YYYY-MM-DD") + "至" + moment(data[1]).format("YYYY-MM-DD");
            }
        } else {
            data =  moment(data).format("YYYY-MM-DD " + (field['is_showtime'] == '1'?"HH:mm":""));
        }

    }else if (field['form_type'] == "address" && angular.isArray(data) && data.length >2) {
        data = format_area(data);
    }else if (field['form_type'] == "p_box") {
        if (data) {
            var newdata = data;
            if (!angular.isArray(newdata)) {
                newdata = [data];
            }
            var setting = [];
            for(var k in newdata) {
                for(var k2 in field.data) {
                    var val = newdata[k].category_id?newdata[k].category_id:newdata[k];
                    if (field.data[k2].category_id == val) {
                        setting.push(field.data[k2].name);
                    }
                }
            }
            if (setting && setting.length > 0)
                data = setting.join(",");
        }
    }else if (field['form_type'] == "a_box") {
        if (data) {
            data = field.data[data].name;
        }
    }else if (field['form_type'] == "s_box") {
        if (data) {
            var newdata = data;
            if (!angular.isArray(newdata)) {
                newdata = [data];
            }
            var setting = [];
            for(var k in newdata) {
                setting.push(newdata[k].name);
            }
            if (setting && setting.length > 0)
                data = setting.join(",");
        }
    }else if (field['form_type'] == "user") {
        if (data) {
            data = data.staff_name;
        }
    }else if (field['form_type'] == "select") {
        var newdata = data;
        if (!angular.isArray(newdata)) {
            newdata = [data];
        }
        var setting = [];
        for(var k in newdata) {
            for(var k2 in field.data) {
                var val = newdata[k].value?newdata[k].value:newdata[k];
                if (field.data[k2].value == val) {
                    setting.push(field.data[k2].text);
                }
            }
        }
        if (setting && setting.length > 0)
            data = setting.join(",");
    }else if (field['form_type'] == "related") {
        if (data) {
            data = data.idcode;
        }
    }else if (field['field'] == "model_id") {
        if (data) {
            data = "<a  target='_blank' href='#/view/"+model.model+"/"+data[model.model + "_id"]+"'>"+data.name + "["+data.idcode+"]"+"</a>";
        }
    }else {
        if ($.inArray(field['field'],  ["product_id","customer_id", "staff_id"]) != -1) {
            if (data) {
                var field_name_arr = field['field'].split('_');
                var model_name = field_name_arr[0];
                data = "<a  target='_blank' href='#/view/"+model_name+"/"+data[model_name+"_id"] +"'>"+data.name + "["+data.idcode+"]"+"</a>";
            }
        }else if($.inArray(field['field'], ["trade_id"]) != -1){
            if (data) {
                data = "<a  target='_blank' href='#/view/trade/"+data["trade_id"] +"'>"+data.orderid + "["+data.serve_name+"]"+"</a>";
            }
        }else if($.inArray(field['field'], ["currier_id", "market_id", "cultivate_id"]) != -1){
            if (data) {
                var field_name_arr = field['field'].split('_');
                var model_name = field_name_arr[0];
                data = "<a  target='_blank' href='#/view/"+model_name+"/"+data[model_name+"_id"] +"'>"+data.idcode+"</a>";
            }
        }else if($.inArray(field['field'], ["corre_id"]) != -1){
            if (data) {
                data = "<a  target='_blank' href='#/view/"+data['corre']+"/"+data["corre_id"] +"'>"+data.name + "["+data.idcode+"]"+"</a>";
            }
        } else {
            var form_type_name_fields = [
                "a_box", "infow","ms_box","m_box",
                "channel_role_model_box", "channel_role_id_box",
                "cultivate_status_box", "cultivate_status_box", "cultivate_examine_state_box", "cultivate_cert_state_box"
            ];
            if ($.inArray(field['form_type'], form_type_name_fields) != -1) {
                if (data) {
                    data = data.name;
                }
            }
        }
    }
    return data || "";
}

function upperFirst(str) {
    return str.replace(/\b\w+\b/g, function(word) {
        return word.substring(0,1).toUpperCase( ) +  word.substring(1);
    });
}

var def_dataTable_lang_opt = {
    'emptyTable': '没有数据',
    'loadingRecords': '加载中...',
    'lengthMenu': '每页 _MENU_ 条',
    'zeroRecords': '没有数据',
    'paginate': {
        'first':      '第一页',
        'last':       '最后一页',
        'next':       '',
        'previous':   ''
    },
    'info': '第 _PAGE_ 页 / 总 _PAGES_ 页',
    'infoEmpty': '没有数据',
    'search':'',
    "sSearchPlaceholder": "搜索内容...",
};

function defaultPageItemText(type, page) {
    switch (type) {
        case "first":
            return "首页";
        case "prev":
            return "上一页";
        case "next":
            return "下一页";
        case "last":
            return "末页";
        case "page":
            return page;
    }
}