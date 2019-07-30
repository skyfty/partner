
function checkSearchForm() {
    search = $("#searchForm #search").val();
    field = $("#searchForm #field").val();
    if($("#searchForm #state").length>0){
        if($("#searchForm #state").val() == ''){
            alert(CrmLang.SELECT_REGION);return false;
        }
    }else{
        if (search == "") {
            alert(CrmLang.FILL_IN_THE_SEARCH_CONTENT);return false;
        }else if(field == ""){
			 alert(CrmLang.SELECT_FILTER_CONDITION);return false;
		}
    }
    return true;
}
$(function(){
	$('form').find('input[type="submit"]').removeAttr("disabled");
	$(document).on('click', 'input[type="submit"]', function(){
		if($(this).parent().find('.form_submit').length > 0){
			$(this).parent().find('.form_submit').val($(this).attr('value'));
		}else{
			$(this).after('<input class="form_submit" type="hidden" name="'+$(this).attr('name')+'" value="'+$(this).attr('value')+'">');
		}
		return true;
	});
	$(document).on('submit', 'form', function(){
		$(this).find('input[type="submit"]').attr("disabled",true);
		return true;
	});
});

function del_confirm() {
    if(confirm(CrmLang.CONFIRM_DELETE)){
        return true;
    }else{
        return false;
    }
}

$(function(){
	/*删除提示*/
	$('.del_confirm').click(del_confirm);

    $('.del_cat_confirm').click(function(){
        if(confirm("确认删除这个分类吗？\n删除后此分类下的项目自动移动到根类别")){
            return true;
        }else{
            return false;
        }
    });


});

String.prototype.toDate = function(format) {
    if (!format) {
        return "";
    }
    pattern = format.replace("yyyy", "(\\~1{4})").replace("yy", "(\\~1{2})")
        .replace("MM", "(\\~1{2})").replace("M", "(\\~1{1,2})")
        .replace("dd", "(\\~1{2})").replace("d", "(\\~1{1,2})").replace(/~1/g, "d");

    var returnDate;
    if (new RegExp(pattern).test(this)) {
        var yPos = format.indexOf("yyyy");
        var mPos = format.indexOf("MM");
        var dPos = format.indexOf("dd");
        if (mPos == -1) mPos = format.indexOf("M");
        if (yPos == -1) yPos = format.indexOf("yy");
        if (dPos == -1) dPos = format.indexOf("d");
        var pos = new Array(yPos + "y", mPos + "m", dPos + "d").sort();
        var data = { y: 0, m: 0, d: 0 };
        var m = this.match(pattern);
        for (var i = 1; i < m.length; i++) {

            if (i == 0) return;
            var flag = pos[i - 1].split('')[1];
            data[flag] = m[i];
        };

        if (data.y.toString().length == 2) {
            data.y = parseInt("20" + data.y);
        }
        data.m = data.m - 1;
        returnDate = new Date(data.y, data.m, data.d);
    }
    if (returnDate == null || isNaN(returnDate)) returnDate = new Date();
    return returnDate;
}

Date.prototype.format = function(fmt)
{ //author: meizz
    var o = {
        "M+" : this.getMonth()+1,                 //月份
        "d+" : this.getDate(),                    //日
        "h+" : this.getHours(),                   //小时
        "m+" : this.getMinutes(),                 //分
        "s+" : this.getSeconds(),                 //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S"  : this.getMilliseconds()             //毫秒
    };
    if(/(y+)/.test(fmt))
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    for(var k in o)
        if(new RegExp("("+ k +")").test(fmt))
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
    return fmt;
}

Array.prototype.indexOf = function(val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) return i;
    }
    return -1;
};

Array.prototype.remove = function(val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};


function format_trainorder_select(data) {
    var options = '';
    $.each(data, function(k, v){
        options += '<option value="'+v.trainorder_id+'">' + '[' + v.orderid +']'+v.train_name+'</option>';
    });
    $("#deta_clause_type_id").html('<select style="width:auto" name="trainorder_id">' + options + '</select>');
}

function format_business_select(data) {
    var options = '';
    $.each(data, function(k, v){
        options += '<option value="'+v.business_id+'">' + v.business_idcode +'</option>';
    });
    $("#deta_clause_type_id").html('<select style="width:auto" name="business_id">' + options + '</select>');
}


function change_page_list(data, ccback) {
    var changepage = "";
    if(data.data.p == 1){
        changepage = "<li><span class='current'>首页</span></li><li><span>« 上一页 </span></li>";
        if(data.data.p < data.data.total){
            changepage += "<li><a class='page' onclick='"+ccback+"(this.rel)' href='javascript:void(0)' rel='"+(data.data.p+1)+"'>下一页 »</a></li>";
        }else{
            changepage += "<li><span>下一页 »</span></li>";
        }
    }else if(data.data.p == data.data.total){
        changepage = "<li><a class='page' onclick='"+ccback+"(this.rel)' href='javascript:void(0)' rel='1'>首页</a></li><li><a class='page' onclick='"+ccback+"(this.rel)' href='javascript:void(0)' rel='"+(data.data.p-1)+"'>« 上一页</a></li><li><span>下一页 »</span></li>";
    }else{
        changepage = "<li><a class='page' onclick='"+ccback+"(this.rel)' href='javascript:void(0)' rel='1'>首页</a></li><li><a class='page' onclick='"+ccback+"(this.rel)'  href='javascript:void(0)' rel='"+(data.data.p-1)+"'>« 上一页</a></li><li><a class='page' onclick='"+ccback+"(this.rel)' href='javascript:void(0)' rel='"+(data.data.p+1)+"'>下一页 »</a></li>";
    }
    return changepage;
}


function show_datepicker(){
    $(this).DatePicker({});
}


function attach_short_search(u, i, cb, scb) {
    $(i).autocomplete({
        minLength: 1,
        delay: 0,
        source: function (request, response) {
            $.getJSON(u, request, function (data) {
                response(data);
            });
        },
        focus: function( event, ui ) {
            $(i).val( ui.item.label.replace(/^\s+|\s+$/g,"") );
            return false;
        },
        select: function( event, ui ) {
            $(i).val(ui.item.label.replace(/^\s+|\s+$/g,""));
            if (scb) scb(ui.item.value, ui.item.label);
            return false;
        }
    }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        if (cb) {
            return cb( ul, item );
        }
        return $("<li>").append( "<a>" + item.label +  "</a>" ).appendTo( ul );
    };
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


function show_wdate_picker(time_accuracy) {
    WdatePicker({dateFmt: time_accuracy});
}

function show_lock_tips(title) {
    return art.dialog({fixed: true,esc:false,drag: false,title:!1, resize: false, id: 'show_lock_tips',content: title, lock: true});
}

function show_wdate_condition_picker(time_accuracy) {
    WdatePicker({dateFmt: time_accuracy});
}