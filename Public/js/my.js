function selectall(name) {
	if ($("#check_box").is(':checked')) {
		$("input[name='"+name+"']").each(function() {
			this.checked=true;
		});
	} else {
		$("input[name='"+name+"']").each(function() {
			this.checked=false;
		});
	}
}

//TAB切换
function Tabs(id,title,content,box,on,action){
	if(action){
		  $(id+' '+title).click(function(){
			  $(this).addClass(on).siblings().removeClass(on);
			  $(content+" > "+box).eq($(id+' '+title).index(this)).show().siblings().hide();
		  });
	  }else{
		  $(id+' '+title).mouseover(function(){
			  $(this).addClass(on).siblings().removeClass(on);
			  $(content+" > "+box).eq($(id+' '+title).index(this)).show().siblings().hide();
		  });
	  }
}

function openwin(id,url,title,width,height,lock,yesdo,topurl){ 
		art.dialog.open(url, {
		id:id,
		title: title,
		lock:  lock,
		width: width,
		height: height,
		cancel: true,
		ok: function(){
			var iframeWin = this.iframe.contentWindow;
    		var topWin = art.dialog.top;
				if(yesdo || topurl){
					if(yesdo){
					    yesdo.call(this,iframeWin, topWin); 
					}else{
						art.dialog.close();
					    topWin.location.href=topurl;
					}
				}else{
					var form = iframeWin.document.getElementById('dosubmit');
                    form.click();
				}
				return false;
			}
		});
}


function resetVerifyCode(){
	var timenow = new Date().getTime();
	document.getElementById('verifyImage').src='./index.php?g=Home&m=Index&a=verify#'+timenow;
}

function showpicbox(url){
	art.dialog({
		padding: 2,
		title: 'Image',
		content: '<img src="'+url+'" />',
		lock: true
	});
}


function area_change(id,level,province,city,area,provinceid,cityid,areaid){
    var datas={'level':level,'provinceid':provinceid,'cityid':cityid,'areaid':areaid};
    $.ajax({
        type:"POST",
        url: "/index.php?m=Index&a=area&id="+id,
        data: datas,
        dataType:"JSON",
        success: function(data){
            if(level==0){
                $('#'+province).html(data.province);
                $('#'+city).html(data.city);
                $('#'+area).html(data.area);
            }else if(level==1){
                $('#'+city).html(data.city);
                $('#'+area).html(data.area);
            }else if(level==2){
                $('#'+area).html(data.area);
            }
        }
    });
}

function request_verify_sms(p, cb) {
    $.ajax({
        'type':'get',
        'dataType':'json',
        'url':'?m=Index&a=verify_sms',
        'data':p,
        'success':function(data){
            if (cb) cb(data);
        }
    });
}

function click_verity_btn(mobile, o, m, n) {
    request_verify_sms({"mobile":mobile, "node":n}, function(ret){
        art.dialog.tips(ret == "OK" ? "短信发送成功， 请注意查收": "短信发送失败， 请稍后重试");
        var intercnt = m ? m :30;
        if (ret == "OK") {
            var inter  = setInterval(function(){
                if (--intercnt == 0) {
                    clearInterval(inter);
                    if (typeof o === "function") {
                        o(intercnt);
                    } else {
                        $(o).val("发送验证码");
                        $(o).removeAttr("disabled");
                    }

                } else {
                    if (typeof o === "function") {
                        o(intercnt);
                    } else {
                        $(o).val("请稍等 " + intercnt);
                    }
                }
            },1000);
            if (typeof o === "function") {
                o(intercnt);
            } else {
                $(o).attr("disabled", "disabled");
            }
        }
    });
}

function change_telephone(title, is_verify, cb) {
    art.dialog.data('is_verify', is_verify);
    art.dialog.open('?m=User&a=telephone', {
        title: title,
        close: function() {
            var data = art.dialog.data('data'); // 读取页面返回的数据
            if (data && data.telephone && cb) {
                cb(data);
            }
        },
        width:"400px",
        lock:true,
        fixed: true,
    }, true);
}


function submit_change_telephone(data) {
    $.ajax({
        'type':'post',
        'dataType':'json',
        'url':'?m=User&a=update_telephone',
        'data':data,
        'success':function(ret){
            if(ret.status == 1){
                $("#telephone").html(data.telephone);
            }
            art.dialog.tips(ret.info, (ret.status == 1 ? 1 : 3));

        }
    });
}

function submit_wxbind(data) {
    $.ajax({
        'type':'post',
        'dataType':'json',
        'url':'?m=User&a=bindwx',
        'data':{
            "telephone":data.telephone
        },
        'success':function(ret){
            if(ret.status == 1){
                $("#telephone").html(data.telephone);
            }
            art.dialog.tips(ret.info, (ret.status == 1 ? 1 : 3));
        }
    });
}

function train_pay(trainorder_id) {
    art.dialog.confirm("是否使用余额支付?", function(){
        location.href = "?m=User&a=train_pay&id=" + trainorder_id;
    });
    return false;
}

function train_cancel(trainorder_id) {
    art.dialog.confirm("是否撤销培训订单?", function(){
        location.href = "?m=User&a=train_cancel&id=" + trainorder_id;
    });
}

function serve_pay(trade_id) {
    art.dialog.confirm("是否使用余额支付?", function(){
        location.href = "?m=User&a=serve_pay&id=" + trade_id;
    });
    return false;
}

function serve_cancel(trade_id) {
    art.dialog.confirm("确定要取消订单吗?", function(){
        location.href = "?m=User&a=serve_cancel&id=" + trade_id;
    });
    return false;
}


function del_portrait_img(id){
    if(confirm('确实要删除这张图片吗？')){
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'?m=User&a=delImg',
            'data':{
                id:id,
                mn:"m_user",
                f:"portrait"
            },
            'success':function(data){
                if(data.status == 1){
                    $('#portrait_prediv').html($('<img>').attr('src',"./Public/img/nophoto.gif"));
                }else{
                    alert(data.info);
                }
            }
        });
    }
}


//删除图片
function del_img(param, field, mn){
    if (!jQuery.isNumeric(param)) {
        $(param).parent().remove();
        if (typeof(pic_change_call) == "function") {
            pic_change_call(field);
        }
        return false;
    }
    if(confirm('确实要删除这张图片吗？')){
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'?m=User&a=delImg',
            'data':{
                id:param,
                mn:mn
            },
            'success':function(data){
                if(data.status == 1){
                    $("#pic-list-" + param).remove();
                    if (typeof(pic_change_call) == "function") {
                        pic_change_call(field);
                    }
                }else{
                    alert(data.info);
                }
            }
        });
    }
}

//删除图片
function photograph(param, field, mn){
    alert("lskdf");
}


//取生肖, 参数必须是四位的年
function getshengxiao(yyyy){
    var arr=['猴','鸡','狗','猪','鼠','牛','虎','兔','龙','蛇','马','羊'];
    return /^\d{4}$/.test(yyyy)?arr[yyyy%12]:null
}

// 取星座, 参数分别是 月份和日期
function getxingzuo(month,day){
    var d=new Date(1999,month-1,day,0,0,0);
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

function getBirth(value) {
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


function Floaters() {
    this.delta=0.15;
    this.playid =null;
    this.items	= [];
    this.addItem	= function(id,x,y,content) {
        var newItem = {};
        newItem.object = document.getElementById(id);

        if(x==0){
            objw= 100;
            var body = (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
            newItem.x = x = body.scrollLeft + (body.clientWidth - objw)/2;
            newItem.y = y;
        }else{
            newItem.x = x;
            newItem.y = y;
        }

        this.items[this.items.length]		= newItem;
    }
    this.play =function(varname){
        this.playid = setInterval(varname+'.plays()',30);
    }
    this.close = function(obj){
        document.getElementById(obj).style.display='none';
        //clearInterval(this.playid);
    }
}
Floaters.prototype.plays = function(){
    //var diffY;
    //if (document.documentElement && document.documentElement.scrollTop)
    //{
    //	diffY = document.documentElement.scrollTop;
    //}
    //else if (document.body)
    //{
    //	diffY = document.body.scrollTop;
    //}else{}
    //
    //for(var i=0;i<this.items.length;i++) {
    //	var obj = this.items[i].object;
    //	var followObj_y = this.items[i].y;
    //	var total = diffY + followObj_y;
    //	if(this.items[i].x >= 0){
    //		obj.style['left'] = this.items[i].x+ 'px';
    //	}else{
    //		obj.style['right'] = Math.abs(this.items[i].x)+ 'px';
    //	}
    //	if( obj.offsetTop != total)
    //	{
    //		var oldy = (total - obj.offsetTop) * this.delta;
    //			newtop = obj.offsetTop + ( oldy>0?1:-1 ) * Math.ceil( Math.abs(oldy) );
    //		obj.style['top'] = newtop + 'px';
    //	}
    //}
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

var def_datatable_classes_opt = {
    'sLengthSelect':'data_table_select'
};


function selallcat(self) {
    var field_name = $(self).attr("field");
    $("input[name='"+field_name+"[]']").prop("checked", $(self).prop("checked"));
}