<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo defaultinfo('name');?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content=""/>
    <link type="text/css" href="http://apps.bdimg.com/libs/bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" href="http://apps.bdimg.com/libs/bootstrap/2.3.2/css/bootstrap-responsive.min.css" rel="stylesheet">

    <link type="text/css" href='http://apps.bdimg.com/libs/qtip2/2.2.0/jquery.qtip.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="__PUBLIC__/jquery-file-upload/css/jquery.fileupload.css" type="text/css" />


    <link rel="shortcut icon" href="__PUBLIC__/ico/favicon.png"/>
    <script type="text/javascript">
        var browserInfo = {browser:"", version: ""};
        var ua = navigator.userAgent.toLowerCase();
        if (window.ActiveXObject) {
            browserInfo.browser = "IE";
            browserInfo.version = ua.match(/msie ([\d.]+)/)[1];
            if(browserInfo.version <= 7){
                if(confirm("您的ie浏览器版本过低，建议使用chorme浏览器")){}
            }
        }
    </script>

    <!--[if lt IE 9]>
    <script src="http://apps.bdimg.com/libs/respond.js/1.4.2/respond.min.js" type="text/javascript"></script>
    <![endif]-->
    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>
    <script src="http://apps.bdimg.com/libs/bootstrap/2.3.2/js/bootstrap.min.js" type="text/javascript"></script>
    <script src='__PUBLIC__/js/jquery.validate.js'></script>
    <script src="__PUBLIC__/js/5kcrm_zh-cn.js?t=20140830" type="text/javascript"></script>
    <script src='http://apps.bdimg.com/libs/qtip2/2.2.0/jquery.qtip.min.js'></script>
    <script src='__PUBLIC__/js/jquery.autoimg.min.js'></script>
    <script src='__PUBLIC__/js/jquery.form.js'></script>
    <script src='http://apps.bdimg.com/libs/jqueryui/1.9.2/jquery-ui.min.js'></script>

    <script src="__PUBLIC__/js/jquery.tmpl.min.js?t=20140830" type="text/javascript"></script>

    <script type="text/javascript" src="__PUBLIC__/js/formValidator-4.0.1.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="__PUBLIC__/js/formValidatorRegex.js?333" charset="UTF-8"></script>

    <link type="text/css" href="__PUBLIC__/datatables/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <script src="__PUBLIC__/datatables/js/jquery.dataTables.js" type="text/javascript"></script>

    <script src="__PUBLIC__/jquery-webcam/jquery.webcam.js" type="text/javascript"></script>
    <link  href="__PUBLIC__/cropper/cropper.css" rel="stylesheet">
    <script src="__PUBLIC__/cropper/cropper.js"></script>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/css/animate.min.css">

    <link  href="__PUBLIC__/qtip/jquery.qtip.min.css" rel="stylesheet">
    <script src="__PUBLIC__/qtip/jquery.qtip.min.js"></script>

    <script src="__PUBLIC__/js/images-loaded.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/moment.min.js" type="text/javascript"></script>
    <script language="javascript" type="text/javascript" src="__PUBLIC__/My97DatePicker/WdatePicker.js?2343"></script>
    <script src="__PUBLIC__/js/changeCondition.js?t=202222230" type="text/javascript"></script>
    <script src="__PUBLIC__/js/5kcrm.js?t=20120" type="text/javascript"></script>
    <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-ie6.css">
    <![endif]-->
    <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/css/ie.css">
    <![endif]-->
    <!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/css/font-awesome-ie7.min.css" />
    <![endif]-->
    <!--[if lt IE 9]>
    <link type="text/css" href="__PUBLIC__/css/jquery.ui.1.10.0.ie.css" rel="stylesheet"/>
    <script src="__PUBLIC__/js/ie8-eventlistener.js" type="text/javascript"></script>
    <![endif]-->
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <script type="text/javascript" src="__PUBLIC__/js/uploadPreview.js"></script>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/mtree/css/menu.css">
    <link rel="stylesheet" href="__PUBLIC__/datepicker/css/datepicker.css" type="text/css" />
    <script src="__PUBLIC__/datepicker/js/datepicker.js" type="text/javascript"></script>
    <script src="__PUBLIC__/datepicker/js/eye.js" type="text/javascript"></script>

    <link rel="stylesheet" href="__PUBLIC__/chosen/chosen.css">
    <script src="__PUBLIC__/chosen/chosen.jquery.js" type="text/javascript"></script>

    <script type="text/javascript" src="__PUBLIC__/js/jquery.artDialog.js?skin=default"></script>
    <script type="text/javascript" src="__PUBLIC__/js/iframeTools.js"></script>
    <script src="__PUBLIC__/js/tendina.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/nav.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/my.js" type="text/javascript"></script>

    <link type="text/css" href="__PUBLIC__/css/font-awesome.min.css?t=20140830" rel="stylesheet" />
    <link class="docs" href="__PUBLIC__/css/docs.css?t=20140230" rel="stylesheet"/>
    <style>

        .qtip-wiki{
            max-width: 700px;
        }

    </style>
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-twttr-rendered="true">
<div class="navbar navbar-inverse navbar-fixed-top" style="z-index: 1000">
	<div class="navbar-inner">
		<div class="container">
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div style="line-height: 40px;padding-right: 5px;padding-top: 2px;padding-bottom:2px" class="pull-left">
                <a href="/"><img src="__PUBLIC__/img/logomini.png"/></a>
            </div>
			    <?php echo W("Navigation");?>
		</div> 
	</div>
</div>
<div class="container">
	<!-- Docs nav ================================================== -->
	<div class="page-header">
			<h4><?php echo L('PRODUCTION_EDITOR');?></h4>
		</div>
	<div class="row">
        <div class="span12">
			<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
		</div>
        <div class="span12">
			<form id="form1" action="<?php echo U('product/edit', 'id='.$product['product_id']);?>" method="post" enctype="multipart/form-data">
			<table class="table" border="0" cellspacing="1" cellpadding="0">
                <thead>
                <tr>
                    <td style="text-align:center;" colspan="4">
                        <input name="submit" class="btn btn-primary" type="submit" value="<?php echo L('SAVE');?>"/>&nbsp;
                        <input class="btn" type="button" onclick="javascript:location.assign('<?php echo ($refer_url); ?>')" value="<?php echo L('RETURN');?>"/>
                    </td>
                </tr>
                </thead>
				<tfoot>
					<tr>
						<td style="text-align:center;" colspan="4">
                            <input name="submit" class="btn btn-primary" type="submit" value="<?php echo L('SAVE');?>"/>&nbsp;
                            <input class="btn" type="button" onclick="javascript:location.assign('<?php echo ($refer_url); ?>')" value="<?php echo L('RETURN');?>"/>
                        </td>
                    </tr>
				</tfoot>
				<tbody>
                    <tr><th  colspan="4"><?php echo L('PRODUCT_PICTURE');?></th></tr>
                    <tr>
                        <td class="tdleft" height="125">主图</td>
                        <td colspan="3" height="125">
                            <input type="hidden" name="main_images_id" value="<?php echo ($product["images"]["main"]["images_id"]); ?>"/>
                            <table  class="table table-striped">
                                <tbody>
                                <tr>
                                    <td  height="135">
                                        <input type="radio" name="main_pic" value="1" <?php if($product['main_pic'] == 1): ?>checked<?php endif; ?> />主图
                                        <div class="thumbnail cardpicthumb">
                                            <?php if(empty($product['images']['main']['path'])): ?><img id="work_pic_prev" class="cardpicthumb" />
                                            <?php else: ?>
                                                <a href="<?php echo ($product["images"]["main"]["path"]); ?>" target="_self" data-lightbox="roadtrip">
                                                    <img id="work_pic_prev" src="<?php echo ($product["images"]["main"]["path"]); ?>" class="cardpicthumb" />
                                                </a><?php endif; ?>

                                            <a href="javascript:del_img_main('<?php echo ($product["images"]["main"]["images_id"]); ?>', 'work_pic_prev');"><i class="icon-remove del_parts"></i></a>
                                            <a href="javascript:void(0);" onclick="photograph_main('work_pic');">
                                                <img class="del_parts" style="left:5px" src="./Public/img/xiangji.jpg">
                                            </a>
                                        </div>
                                        <div class="btn btn-success fileinput-button">
                                            <span>选择头像文件</span>
                                            <input type="file" name="work_pic[]" id="work_pic">
                                            <input type="hidden" name="work_pic_base64" id="work_pic_base64">
                                        </div>
                                    </td>
                                    <td  height="135">
                                        <input type="radio" name="main_pic" value="2" <?php if($product['main_pic'] != 1): ?>checked<?php endif; ?>/>主图

                                        <div class="thumbnail cardpicthumb">
                                            <?php if(empty($product['images']['cardpic']['path'])): ?><img id="card_pic_prev" class="cardpicthumb" />
                                            <?php else: ?>
                                                <img id="card_pic_prev" src="<?php echo ($product["images"]["cardpic"]["path"]); ?>" class="cardpicthumb " /><?php endif; ?>
                                            <a href="javascript:del_img_main('<?php echo ($product["images"]["cardpic"]["images_id"]); ?>', 'card_pic_prev');"><i class="icon-remove del_parts"></i></a>
                                            <a href="javascript:void(0);" onclick="photograph_main('card_pic');">
                                                <img class="del_parts" style="left:5px" src="./Public/img/xiangji.jpg">
                                            </a>
                                        </div>
                                        <div class="btn btn-success fileinput-button">
                                            <span>选择证件文件</span>
                                            <input type="file" name="card_pic[]" id="card_pic">
                                            <input type="hidden" name="card_pic_base64" id="card_pic_base64">
                                        </div>
                                    </td>
                                    <input type="hidden" name="main_pic_type" id="main_pic_type" value="s"/>
                                    <td width="70%"><?php echo L('PRODUCT_MAIN_PICTURE_TIP');?></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php if(is_array($fields_group)): $i = 0; $__LIST__ = $fields_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i;?><tr><th colspan="4"><?php echo ($gvo["name"]); ?></th></tr>
    <?php $j=0; ?>
<?php if(is_array($gvo['fields'])): $i = 0; $__LIST__ = $gvo['fields'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['operating'] != '4'): $j++; ?>
        <?php if($vo['one_row'] == '1' or $vo['form_type'] == 'textarea' or $vo['form_type'] == 'editor' or $vo['form_type'] == 'address'): if($i%2 == 0): ?><td colspan="2">&nbsp;</td>
                </tr><?php endif; ?>
            <tr>
                <td class="tdleft" width="15%"><?php echo ($vo["name"]); ?>:</td>
                <td colspan="3" id="<?php echo ($vo['model']); ?>_<?php echo ($vo['field']); ?>_<?php echo ($vo['is_main']); ?>_html"><?php echo ($vo["html"]); ?></td>
            </tr>
            <?php if($i%2 != 0 && count($gvo['fields']) != $j): $i++; endif; ?>
        <?php else: ?>
            <?php if($i%2 != 0): ?><tr><?php endif; ?>
            <td class="tdleft" width="15%">
                <?php if($vo['field'] == 'demand_end_time'): ?><button id="minus_demand_end_time" type="button" style="width: 26px;height: 24px"><i class="icon-minus"></i></button>&nbsp;<?php echo ($vo["name"]); ?>&nbsp;<button id="plus_demand_end_time" type="button"  style="width: 26px;height: 24px"><i class="icon-plus"></i></button>:
                <?php else: ?>
                    <?php echo ($vo["name"]); ?>:<?php endif; ?>
            </td>
            <td width="35%" id="<?php echo ($vo['model']); ?>_<?php echo ($vo['field']); ?>_<?php echo ($vo['is_main']); ?>_html"><?php echo ($vo["html"]); ?></td>
            <?php if($i%2 == 0): ?></tr><?php endif; ?>
            <?php if($i%2 != 0 && count($gvo['fields']) == $j): ?><td colspan="2">&nbsp;</td>
                </tr><?php endif; endif; endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>


<script type="text/javascript">

    //初始化上传图片
    $("body").on('click','input[type="file"]', function(){
        var selector = $(this).attr('id');
        var name = $(this).attr('name');
        name = name.substring(0, 4);
        var imgtype = [];
        if (name == "vid_" ){
            imgtype =["flv", "mp4", "3gp"];
        } else if (name == "pic_") {
            imgtype =["gif", "jpeg", "jpg", "bmp", "png"];
        }
        var opt = {
            Img: selector+"_prev",
            Width: 120,
            Height: 120,
            ImgType:imgtype
        };
        $("#"+selector).uploadPreview(opt);
    });
</script>

                </tbody>
			</table>
                <input name="a" type="hidden" value="edit">
                <input name="m" type="hidden" value="product">
                <input type="hidden" name="product_id" value="<?php echo ($product["product_id"]); ?>"/>
			</form>
		</div>
	</div>
</div>

<object id="rcard" type="application/x-rcard"  width="0" height="0">
    <param name="onload" value="pluginLoaded" />
</object>
<script>
    function del_img_main(param, pic_pre){
        if(param){
            if(confirm('<?php echo L("CONFIRM_DELETE");?>')){
                $.ajax({
                    'type':'get',
                    'dataType':'json',
                    'url':'<?php echo U("product/delImg");?>',
                    'data':{images_id:param},
                    'success':function(data){
                        if(data.status == 1){
                            $('#'+pic_pre).attr("src", "");
                        }else{
                            alert(data.info);
                        }
                    }
                });
            }
        }else{
            $('#'+pic_pre).attr("src", "");
        }
    }

    $(function(){
        var rcard = document.getElementById('rcard');
        if (rcard.valid) {
            if (rcard.attachEvent) {
                rcard.attachEvent("onperson", person);
            } else {
                rcard.addEventListener("person", person, false);
            }
            rcard.open();

        <?php if($readcard == '1'): ?>rcard.person(true);<?php endif; ?>
        }

        function person(p) {
            if (p && p.cardid) {
                var cardinfo = {
                    name: p.name,
                    address: p.address,
                    cardid: p.cardid,
                    police: p.police,
                    nation: p.nation,
                    sex: p.sex,
                    validstart: p.validstart.toDate("yyyyMMdd").format("yyyy-MM-dd"),
                    validend: p.validend.toDate("yyyyMMdd").format("yyyy-MM-dd"),
                    birthday: p.birthday.toDate("yyyyMMdd").format("yyyy-MM-dd"),
                    nationcode: p.nationcode,
                    msg: p.msg
                };
                for (var i in cardinfo) {
                    $("#" + i).val(cardinfo[i]);
                }
                rcard.attachfile(p.pic, function(imgbase){
                    var img = "data:image/gif;base64," + imgbase;
                    $("#card_pic_prev").attr("src", img);
                    $("#card_pic_base64").val(img);
                    $("#main_pic_type").val("s");
                });
                on_cardid_change();
            }
        }

        <?php if($per_change_owner_id != true): ?>$("#owner_role_id_name").attr("disabled", "disabled");<?php endif; ?>
    });
    function on_birthday_focus() {
        WdatePicker({onpicked : on_birthday_picked});
    }

    function on_birthday_picked() {
        reset_zodiac_constellation($("#birthday").val());
    }

    function reset_zodiac_constellation(birthday) {
        var birtinfo = birthday.split('-');
        var zodiac = getshengxiao(birtinfo[0]);
        $("#zodiac option[value='"+zodiac+"']").prop("selected", true);

        var constellation = getxingzuo(birtinfo[1], birtinfo[2]);
        $("#constellation option[value='"+constellation+"']").prop("selected", true);
    }

    function on_cardid_change() {
        var cardid = $("#cardid").val();
        if (cardid) {
            var birthday = getBirth(cardid);
            $("#birthday").val(birthday);
            reset_zodiac_constellation(birthday);
        }
    }
    $("#cardid").change(on_cardid_change)

    function channel_role_model_change() {
        $('#telephone').qtip('destroy', true);
        var telephone = $("#telephone").val();
        if (!isMobile(telephone)) {
            return;
        }

        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("product/check_commiss_info");?>',
            'data':{
                "telephone":telephone,
                "product_id":<?php echo ($product["product_id"]); ?>,
            },
            'success':function(data){
                if(data.status == 1 && data.data){
                    $('#telephone').qtip(
                    {
                        hide: 'unfocus',
                        show:true,
                        style: 'qtip-bootstrap',
                        content: '这个雇员的联系方式在客服模块有登记，请联系客服指派.客服电话: <?php echo defaultinfo("commiss_telephone");?>'
                    });
                }
            }
        });
    }

    $("#telephone").blur(function(){
        channel_role_model_change();
    });
</script>

<script id="channel_role_model_chosen_templ" type="text/x-jquery-tmpl">
<div class="chosen-container chosen-container-single" style="width: 220px;" title="" id="channel_role_model_chosen_cover">
    <a class="chosen-single">
        <span>
        ${channel_role_model_name}
        </span>
        <input type="hidden" name="channel_role_model_saver" value="${channel_role_model}">
    </a>
</div>
</script>

<?php if(!vali_permission('product', 'channeledit')): ?><script>
    $('#channel_role_id_name').attr("disabled","disabled");
    $("#channel_role_model_chosen").hide();
    var crm = $("#channel_role_model option:selected").html();
    $("#channel_role_model").after($("#channel_role_model_chosen_templ").tmpl({"channel_role_model_name":crm}));
</script><?php endif; ?>


<script src="__PUBLIC__/javascript-load-image/js/load-image.all.min.js"></script>

<style>
    .upload-btn {
        position: relative;
        top: -19px;
        height: 22px;
        width: 100%;
        opacity: 0.9;
    }
</style>


<script>
    var seq_item = 1;
    $('.pic-fileupload').change(function (e) {
        var self = this;
        $.each(e.target.files, function(f, v){
            loadImage(v,function (img) {on_load_image(self, img);},{maxWidth: 600});
        });
        $(self).parent().find('.photograph_div').html("");

    });

    function on_load_image(self, img) {
        var node = $('<div/>');
        $(img).css("height", "100px");

        node.append(img);
        $(self).next().html(node);

        $(self).parent().css("background-image", "");
        $(self).next().next().show();

        if ($(self).attr("first") == 0) {
            seq_item++;
            var field_name = $(self).attr("real");
            var html = "";
            html += '<div class="box-secondary">';
            html += '    <div class="fileinput-button" style="border-color:white;height:110px;width:130px;background-image:url(./Public/img/add.png);background-repeat:no-repeat">';
            html += '        <input id="new_pic_file_' + seq_item + '" first="0" real="' + field_name + '"  class="pic-fileupload" type="file" name="pic_'+field_name+'[]" style="height: 100px">';
            html += '        <div class="prediv thumbnail productotherlistthumb img-responsive">';
            html += '        </div>';
            html += '        <a href="javascript:void(0);" onclick="del_img(this);" style="display:none">';
            html += '            <img class="del_parts" src="./Public/img/delete.gif">';
            html += '        </a>';
            html += '        <a href="javascript:void(0);" onclick="photograph(this);">';
            html += '            <img class="del_parts" style="left:5px" src="./Public/img/xiangji.jpg">';
            html += '        </a>';
            html += '        <div class="photograph_div"></div>';
            html += '    </div>';
            html += '</div>';
            $(self).parent().parent().parent().append($(html));
            if( typeof on_picfilechange === 'function' ){
               $("#new_pic_file_" + seq_item).change(on_picfilechange);
            }
            $(self).attr("first", "1");
            reset_file_change('#new_pic_file_' + seq_item);
        }
    }

    function reset_file_change(ff) {
        $(ff).change(function (e) {
            var self = this;
            loadImage(e.target.files[0],function (img) {on_load_image(self, img);},{maxWidth: 600});
            $(self).parent().find('.photograph_div').html("");
        });
    }

    //删除图片
    function del_img(param, field, module){
        if (!jQuery.isNumeric(param)) {
            $(param).parent().parent().remove();
            return false;
        }
        if(confirm('<?php echo L("CONFIRM_DELETE");?>')){
            var data = {
                images_id:param
            };
            if (module) {
                data['module'] = module;
            }
            $.ajax({
                'type':'get',
                'dataType':'json',
                'url':'<?php echo U(MODULE_NAME."/delImg");?>',
                'data':data,
                'success':function(data){
                    if(data.status == 1){
                        $('#pic-list-'+param).remove();
                    }else{
                        alert(data.info);
                    }
                }
            });
        }
    }

    function photograph(param){
        var parent = $(param).parent();
        var self = parent.find('input');
        var photograph_save_cb = function(imgbase) {
            loadImage(imgbase,function (img) {
                on_load_image(self, img);
                parent.find('.photograph_div').html('<input type="hidden" value="'+imgbase+'" name="'+self.attr("name")+'"/>');

                var new_pic_file = self.clone();
                reset_file_change(new_pic_file);
                self.after(new_pic_file);
                self.remove();

            },{maxWidth: 600});

        };
        cropper_photograph(photograph_save_cb);
    }


    function photograph_main(picname){
        var picfile = $("#" + picname);
        var photograph_save_cb = function(imgbase) {
            loadImage(imgbase,function (img) {
                $("#" + picname + "_base64").val(imgbase);
                $("#" + picname + "_prev").attr("src", imgbase);

                var newpicfile = picfile.clone();
                $(newpicfile).change(function (e) {
                    $("#" + picname + "_base64").val("");
                });
                picfile.after(newpicfile);
                picfile.remove();

            },{maxWidth: 600});
        };
        cropper_photograph(photograph_save_cb);
    }


    function cropper_photograph( pc) {
        art.dialog.data('photocallback', pc);
        art.dialog.open("<?php echo U('Index/photograph');?>", {
            title: "拍照",
            lock:true,
            fixed: true,
            resize: false,
            padding: 0,
            width:600,
            height:450,
            button: [
                {
                    name: '确定',
                    callback: function () {
                        var cw = $(this.iframe)[0].contentWindow
                        if (cw.webcam_state == 0) {
                            cw.webcam.capture();
                            this.size(613, 463);
                        } else {
                            cw.cropper_webcam();
                            this.close();
                        }
                        return false;
                    },
                    focus: true
                },
                {
                    name: '重拍',
                    callback: function () {
                        var cw = $(this.iframe)[0].contentWindow
                        cw.restart_webcam();
                        return false;
                    }
                },
                {
                    name: '关闭',
                    callback: function () {
                        this.close();
                        return false;
                    },
                }
            ],
        }, true);
    }
</script>

<div class="hide" id="dialog-video" title="添加视频">loading...</div>
<script>
    $("#dialog-video").dialog({
        autoOpen: false,
        width: 800,
        maxHeight: 400,
        position: ["center",100]
    });

    //增加一个文件
    $('.btn_add_video').click(function(){
        var field_name = $(this).attr('field_name');
        $('#dialog-video').dialog('open');
        $('#dialog-video').load('<?php echo U(MODULE_NAME."/addvideo","id=".$_GET["id"]."&field=");?>' + field_name);
    });

    $('.btn_del_video').click(function(){
        var videoid = $(this).attr('rel');
        if(confirm('确定要删除吗?')){
            $.ajax({
                'type':'get',
                'dataType':'json',
                'url':'<?php echo U(MODULE_NAME."/deletevideo");?>',
                'data':{id:videoid},
                'success':function(data){
                    if(data.status == 1){
                        //删除图片DOM
                        $('#video-list-'+videoid).remove();
                    }else{
                        alert(data.info);
                    }
                }
            });
        }
    });
</script>


<div class="hide" id="dialog-file" title="添加文件">loading...</div>
<script>
    $("#dialog-file").dialog({
        autoOpen: false,
        width: 800,
        maxHeight: 400,
        position: ["center",100]
    });

    //增加一个文件
    $('.btn_add_file').click(function(){
        var field = $(this).attr('field_name');
        $('#dialog-file').dialog('open');
        $('#dialog-file').load('<?php echo U(MODULE_NAME."/addfile","id=".$_GET["id"]."&assort=$assort"."&field=");?>' + field);
    });

    //增加一个文件
    $('.btn_del_file').click(function(){
        var fileid = $(this).attr('rel');
        if(confirm('确定要删除吗?')){
            $.ajax({
                'type':'get',
                'dataType':'json',
                'url':'<?php echo U(MODULE_NAME."/deletefile");?>',
                'data':{
                    id:fileid
                },
                'success':function(data){
                    if(data.status == 1){
                        $('#file-list-'+fileid).remove();
                    }else{
                        alert(data.info);
                    }
                }
            });
        }
    });
</script>


<script type="text/javascript">
    function request_staff_info(p, cb) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("staff/getInfo");?>',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    }

    function select_staff(param, cb) {
        art.dialog.data('reqparam', param);
        art.dialog.open('<?php echo U("staff/listDialog");?>', {
            id: 'select_staff_dialog',
            title: "选择员工",
            lock:true,
            fixed:true,
            window: 'top',
            width: 780,
            height: 400,
            ok: function () {
                var staff_id = $(this.iframe.contentWindow.document).find('input:radio[name="staff"]:checked').val();
                var data = {
                    'id': staff_id
                };
                request_staff_info(data, cb?cb:on_staff_info);
                return true;
            },
            cancel: true
        });
    }
</script>
<script>
    function user_select_dialog(cb, param)  {
        select_staff(param, function(b){
            if (cb) {
                cb(b.name, b.role_id);
            }
        });
    }
</script>
<script>
    var role_field_name = "";
    $(function(){
        $('.role_name').click(
            function(){
                role_field_name = $(this).attr("rel");

                var functionName =role_field_name + "_role_select_dialog";
                if(typeof(window[functionName]) === "function") {
                    eval(functionName + '(\''+role_field_name+'\')');
                } else {
                    var param = {};
                    <?php if((MODULE_NAME) == "Market"): ?>if (role_field_name == "owner_role_id") {
                        param['branch_id'] = "<?php echo session('branch_id');?>";
                    }<?php endif; ?>
                    user_select_dialog(function(name, item){
                        $('#'+role_field_name+'_name').val(name);
                        $('#' + role_field_name).val(item).change();
                    }, param);
                }
            }
        );
        $('.role_name_remove').click(
            function(){
                role_field_name = $(this).attr("rel");
                $('#'+role_field_name).attr('value', '');
                $('#'+role_field_name+"_name").attr('value', "选择创建人");
            }
        );
    });
</script>

<script>

$(function() {
    $.formValidator.initConfig({
        formID: "form1",
        debug: false,
        submitOnce: true,
        onError: function (msg, obj, errorlist) {
            art.dialog.alert(msg);
        }
    });

<?php if(is_array($fields_group)): $i = 0; $__LIST__ = $fields_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i; if(is_array($gvo['fields'])): $i = 0; $__LIST__ = $gvo['fields'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['is_validate'] == 1): if($vo['form_type'] != 'box' || $vo['setting']['type'] == 'select'): ?>$("#<?php echo ($vo[field]); ?>").formValidator({
            tipID:"<?php echo ($vo[field]); ?>Tip",
            <?php if($vo['is_null'] == 1): ?>onShow:"<span style='color:red;'>必选项</span>",
                empty:false,
            <?php else: ?>
                onShow:" ",
                empty:true,<?php endif; ?>

            onFocus:" ",
            onCorrect:"<span style='color:green;'>√</span>"
            }).inputValidator({
            <?php if($vo['is_null'] == 1): ?>min:1,max:<?php echo (($vo[max_length])?($vo[max_length]):"255"); ?>,
                onshow:"<?php echo ($vo[name]); ?>不可以为空",
            <?php else: ?>
                min:0,max:<?php echo (($vo[max_length])?($vo[max_length]):"255"); ?>,<?php endif; ?>
            onErrorMin:"<?php echo ($vo[name]); ?>不可以为空",
            onErrorMax:"<?php echo ($vo[name]); ?>超出最大长度"
            });

            <?php if($vo['form_type'] == 'email'): ?>$("#<?php echo ($vo[field]); ?>").regexValidator({
                regExp:"email",
                dataType:"enum",
                onError:"<?php echo ($vo[name]); ?>格式不正确"
                });
            <?php elseif($vo['form_type'] == 'mobile'): ?>
                $("#<?php echo ($vo[field]); ?>").regexValidator({
                regExp:"mobile",
                dataType:"enum",
                onError:"<?php echo ($vo[name]); ?>格式不正确"
                });
            <?php elseif($vo['form_type'] == 'phone'): ?>
                $("#<?php echo ($vo[field]); ?>").regexValidator({
                regExp:"tel",
                dataType:"enum",
                onError:"<?php echo ($vo[name]); ?>格式不正确"
                });
            <?php elseif($vo['form_type'] == 'datetime'): ?>
                $("#<?php echo ($vo[field]); ?>").regexValidator({
                regExp:"date",
                dataType:"enum",
                onError:"<?php echo ($vo[name]); ?>格式不正确"
                });
            <?php elseif($vo['form_type'] == 'number'): ?>
                $("#<?php echo ($vo[field]); ?>").regexValidator({
                regExp:"num",
                dataType:"enum",
                onError:"<?php echo ($vo[name]); ?>格式不正确"
                });<?php endif; ?>

            <?php if($vo['is_unique'] == 1): ?>$("#<?php echo ($vo[field]); ?>").ajaxValidator({
                dataType : "json",
                type : "GET",
                async : false,
                isValid : true,
                data : {
                    id:<?php echo ($model_id); ?>
                },
                url : "<?php echo U($Think.MODULE_NAME.'/validate');?>",
                success : function(data){
                    if( data.status == 1 ) return false;
                    if( data.status == 0 ) return true;$("#form1").submit();
                    return false;
                },
                error: function(jqXHR, textStatus, errorThrown){
                    alert("服务器没有返回数据，可能服务器忙，请重试"+errorThrown);
                },
                onError : "该<?php echo ($vo['name']); ?>不可用，请更换<?php echo ($vo['name']); ?>",
                onWait : "正在对<?php echo ($vo['name']); ?>进行合法性校验，请稍候..."
                });<?php endif; ?>
        <?php else: ?>
            <?php if($vo['setting']['type'] == 'checkbox'): ?>$(":checkbox[name='<?php echo ($vo['field']); ?>[]']").formValidator({
                tipID:"<?php echo ($vo[field]); ?>Tip",
                <?php if($vo['is_null'] == 1): ?>onShow:"<span style='color:red;'>必选项</span>",
                <?php else: ?>
                    onShow:" ",<?php endif; ?>
                onFocus:" ",
                onCorrect:"<span style='color:green;'>√</span>"
                }).inputValidator({
                <?php if($vo['is_null'] == 1): ?>min:1,
                <?php else: ?>
                    min:0,<?php endif; ?>
                onError:"请选择<?php echo ($vo[name]); ?>"
                });
                <?php elseif($vo['setting']['type'] == 'select'): ?>
                $("#<?php echo ($vo[field]); ?>").formValidator({
                tipID:"<?php echo ($vo[field]); ?>Tip",
                <?php if($vo['is_null'] == 1): ?>onShow:"<span style='color:red;'>必选项</span>",
                <?php else: ?>
                    onShow:" ",<?php endif; ?>
                onFocus:" ",
                onCorrect:"<span style='color:green;'>√</span>"
                }).inputValidator({
                <?php if($vo['is_null'] == 1): ?>min:1,
                <?php else: ?>
                    min:0,<?php endif; ?>
                onError:"请选择<?php echo ($vo[name]); ?>"
                });
                <?php else: ?>
                $(":radio[name='<?php echo ($vo['field']); ?>']").formValidator({
                tipID:"<?php echo ($vo[field]); ?>Tip",
                <?php if($vo['is_null'] == 1): ?>onShow:"<span style='color:red;'>必选项</span>",
                <?php else: ?>
                    onShow:" ",<?php endif; ?>
                onFocus:" ",
                onCorrect:"<span style='color:green;'>√</span>"
                }).inputValidator({
                <?php if($vo['is_null'] == 1): ?>min:1,
                <?php else: ?>
                    min:0,<?php endif; ?>
                onError:"请选择<?php echo ($vo[name]); ?>"
                });<?php endif; endif; endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
});
</script>




<script type="text/javascript">
    function request_customer_info(p, cb) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("customer/getInfo");?>',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    }

    function select_customer(param, cb) {
        art.dialog.data('reqparam', param);
        art.dialog.open('<?php echo U("customer/listDialog");?>', {
            id: 'select_customer_dialog',
            title: "选择客户",
            lock:true,
            fixed:true,
            window: 'top',
            width: 780,
            height: 400,
            ok: function () {
                var customer_id = $(this.iframe.contentWindow.document).find('input:radio[name="customer"]:checked').val();
                var data = {
                    'id': customer_id
                };
                request_customer_info(data, cb?cb:on_customer_info);
                return true;
            },
            cancel: true
        });
    }
</script>
<script type="text/javascript">
    function on_channel_datapick_customer_info(b) {
        $('#channel_role_id_name').val(b.name);
        $('#channel_role_id_name').change();
        $('#channel_role_id').val(b.customer_id);
        if(channel_datapick_change &&typeof(channel_datapick_change)=="function"){
            channel_datapick_change();
        }
    }
</script>

<script type="text/javascript">
    function request_product_info(p, cb, cp) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("product/getproduct");?>',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data, cp);
                }
            }
        });
    }

    function request_cat_info(i, cb, cp) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("product/getcategory");?>',
            'data':{
                "id":i
            },
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data[0], cp);
                }
            }
        });
    }

    function request_skill_info(p,s, cb, cp) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("product/getskill");?>',
            'data':{
                "product_id":p,
                "category_id":s
            },
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data, cp);
                }
            }
        });
    }

    function select_product(param, cb, tpl) {
        tpl = tpl ? tpl : '<?php echo U("product/listDialog");?>';
        art.dialog.data('reqparam', param);
        art.dialog.open(tpl, {
            id: 'select_product_dialog',
            title: "选择雇员",
            lock:true,
            fixed:true,
            window: 'top',
            width: 780,
            height: 400,
            ok: function () {
                var product_id = $(this.iframe.contentWindow.document).find('input:radio[name="product_id"]:checked').val();
                var data = {
                    'product_id': product_id
                };
                request_product_info(data, cb?cb:on_product_info);
            },
            cancel:true
        });
    }
</script>




<script type="text/javascript">
    function on_channel_datapick_product_info(b) {
        $('#channel_role_id_name').val(b.name);
        $('#channel_role_id_name').change();
        $('#channel_role_id').val(b.product_id);
        if(channel_datapick_change &&typeof(channel_datapick_change)=="function"){
            channel_datapick_change();
        }
    }
</script>



<script type="text/javascript">
    function request_staff_info(p, cb) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("staff/getInfo");?>',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    }

    function select_staff(param, cb) {
        art.dialog.data('reqparam', param);
        art.dialog.open('<?php echo U("staff/listDialog");?>', {
            id: 'select_staff_dialog',
            title: "选择员工",
            lock:true,
            fixed:true,
            window: 'top',
            width: 780,
            height: 400,
            ok: function () {
                var staff_id = $(this.iframe.contentWindow.document).find('input:radio[name="staff"]:checked').val();
                var data = {
                    'id': staff_id
                };
                request_staff_info(data, cb?cb:on_staff_info);
                return true;
            },
            cancel: true
        });
    }
</script>
<script type="text/javascript">
    function on_channel_datapick_staff_info(b) {
        $('#channel_role_id_name').val(b.name);
        $('#channel_role_id_name').change();
        $('#channel_role_id').val(b.staff_id);
        if(channel_datapick_change &&typeof(channel_datapick_change)=="function"){
            channel_datapick_change();
        }
    }
</script>



<script type="text/javascript">
    function request_channel_info(p, cb) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("channel/getInfo");?>',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    }

    function select_channel(param, cb) {
        art.dialog.data('reqparam', param);
        art.dialog.open('<?php echo U("channel/listDialog");?>', {
            id: 'select_channel_dialog',
            title: "选择渠道",
            lock:true,
            fixed:true,
            window: 'top',
            width: 780,
            height: 400,
            ok: function () {
                var channel_id = $(this.iframe.contentWindow.document).find('input:radio[name="channel"]:checked').val();
                var data = {
                    'id': channel_id
                };
                request_channel_info(data, cb?cb:on_channel_info);
                return true;
            },
            cancel: true
        });
    }
</script>
<script type="text/javascript">
    function on_channel_datapick_channel_info(b) {
        $('#channel_role_id_name').val(b.name);
        $('#channel_role_id_name').change();
        $('#channel_role_id').val(b.channel_id);
        if(channel_datapick_change &&typeof(channel_datapick_change)=="function"){
            channel_datapick_change();
        }
    }
</script>

<script>
    $("#channel_role_model").change(function(){
        var model = $("#channel_role_model  option:selected").attr("model");
        if (model == "渠道库") {
            var model_value = $("#channel_role_model  option:selected").attr("value");
            var model_html = $("#channel_role_model  option:selected").html();
            $("#channel_role_id").val(model_value);
            $("#channel_role_id_name").val(model_html);
        } else {
            $("#channel_role_id").val("");
            $("#channel_role_id_name").val("");
            $("#channel_role_id_name").removeAttr("disabled");
            $("#channel_role_id_name").attr("placeholder", "请点击选择");
        }
        if(typeof(channel_datapick_change)=="function"){
            channel_datapick_change();
        }
    });

    $("#channel_role_id_name").click(function(){
        var option_select = $("#channel_role_model  option:selected");
        var child_channel_ids = option_select.attr("child_channel_ids");
        var model = option_select.attr("model");
        if (model == "客户库") {
            select_customer({}, on_channel_datapick_customer_info);
        } else if (model == "雇员库") {
            select_product({}, on_channel_datapick_product_info);
        } else if(model == "员工库") {
            select_staff({}, on_channel_datapick_staff_info);
        } else if (child_channel_ids != "" && child_channel_ids != undefined) {
            var model_value = option_select.attr("value");
            var param = {
                "model":"Channel",
                "module":"<?php echo (strtolower(MODULE_NAME)); ?>",
                "query":"parentid=" +model_value
            };
            select_channel(param, on_channel_datapick_channel_info);
        }
    });
</script>
<script type="text/javascript" src="__PUBLIC__/js/uploadPreview.js"></script>
<a href="" target="_blank" style="display: none"  id="sms_open_url_a"><span id="sms_open_url_span">send_sms</span></a>

<script>
    $(".role_info").click(function(){
        var role_id = $(this).attr('rel');
        var dialog = art.dialog({
            id: 'N3690',
            title: "<?php echo L('DIALOG_USER_INFO');?>",
            lock:true,
            fixed:true,
            ok: true
        });
        $.ajax({
            url: '<?php echo U("user/dialoginfo","id=");?>'+role_id,
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });
    });

    $(".clean_owner_role").click(function(){
        var clean_owner_role = $(this).attr("rel");
        art.dialog.confirm("确实要清除责任人吗?", function(){
            $("#"+clean_owner_role).val("");
            $("#"+clean_owner_role + "_name").val("");
        });
    });
</script>


<link href="__PUBLIC__/lightbox/css/lightbox.css" rel="stylesheet" type="text/css">
<script src="__PUBLIC__/lightbox/js/lightbox.js" type="text/javascript"></script>

<script>
    function on_click_return() {
        if (window.history.length == 1) {
            window.close();
        } else {
            history.go(-1);
        }
    }

    $(".sms_group_btn").click(function(){
        var group_id = $(this).attr("module_group_id");
        var module = $(this).attr("module");
        var param = '&by=group&model='+module+'&group_id=' + group_id;
        $("#sms_open_url_a").attr("href", "<?php echo U('sms/sendDialog');?>"+param);
        $("#sms_open_url_span").click();
    });

</script>

<script>
    $(function(){
        $('#listtable a').qtip({
            style: 'qtip-bootstrap'
        });

        $("[data-toggle='tooltip']").qtip({style: 'qtip-bootstrap'});

    });
</script>
<script type="text/javascript" src="__PUBLIC__/mtree/js/menu_min.js"></script>
<script type="text/javascript">
    $(document).ready(function (){
        $(".menu ul li").menu();
    });

</script>

<script>
    function filter_berth_date_field(api,wsbt, wset) {
        var field = api.elements.target.attr('field');
        window.location.replace("<?php echo U('');?>&" + FP("<?php echo FP($parameter);?>", field + "[condition]=tbetween&"+field+"[value][0]="+wsbt+"&"+field+"[value][1]="+wset));

    }
    $(function() {
        $("[date_col_filter]").qtip({
            content: {
                text: function (event, api) {
                    $.ajax({
                        url: api.elements.target.attr('date_col_filter')
                    })
                    .then(function (content) {
                        api.set('content.text', content);
                    });
                    return 'Loading...';
                }
            },
            events: {
                hide: function (event, api) {
                    var call = api.elements.target.attr('date_col_filter_call');
                    if (event.originalEvent.type == "click" && typeof call != "undefined") {
                        var wsbt = $(api.tooltip).find("#date_select_wsbt").val();
                        var wset = $(api.tooltip).find("#date_select_wset").val();
                        eval(call + "(api,wsbt, wset)");
                    }
                }
            },
            hide: 'unfocus',
            show:'click',
            style: 'qtip-bootstrap'
        });
    });
</script>

<script>
    function filter_berth_text_field(api,condition, value) {
        var field = api.elements.target.attr('field');
        if (value=="" && condition != "为空"  && condition != "不为空" && condition != "is_empty" && condition != "is_not_empty") {
            condition = "";
        }
        window.location.replace("<?php echo U('');?>&" + FP("<?php echo FP($parameter);?>", field + "[condition]="+condition+"&"+field+"[value]="+value));

    }
    $(function() {
        $("[text_col_filter]").qtip({
            content: {
                text: function (event, api) {
                    $.ajax({
                        url: api.elements.target.attr('text_col_filter')
                    })
                    .then(function (content) {
                        api.set('content.text', content);
                    });
                    return 'Loading...';
                }
            },
            events: {
                hide: function (event, api) {
                    var call = api.elements.target.attr('text_col_filter_call');
                    if (event.originalEvent.type == "click" && typeof call != "undefined") {
                        var condition = $(api.tooltip).find("#text_filder_dialog_form_condition").val();
                        var value = $(api.tooltip).find("#text_filder_dialog_form_value").val();
                        eval(call + "(api, condition, value)");
                    }
                }
            },
            hide: 'unfocus',
            show:'click',
            style: 'qtip-bootstrap'
        });
    });
</script>

<script>
    function filter_berth_s_box_field(api,category, catelevel) {
        window.location.replace("<?php echo U('');?>&" + FP("<?php echo FP($parameter);?>", "category_id=" +category+"&"+"catelevel="+catelevel));

    }
    $(function() {
        $("[s_box_col_filter]").qtip({
            content: {
                text: function (event, api) {
                    $.ajax({
                                url: api.elements.target.attr('s_box_col_filter')
                            })
                            .then(function (content) {
                                api.set('content.text', content);
                            });
                    return 'Loading...';
                }
            },
            events: {
                hide: function (event, api) {
                    var call = api.elements.target.attr('s_box_col_filter_call');
                    if (event.originalEvent.type == "click" && typeof call != "undefined") {
                        var category = $(api.tooltip).find("#proudct_category_select").val();
                        var catelevel = "";
                        $(api.tooltip).find("input").each(function() {
                            if ($(this).is(':checked')) {
                                catelevel += $(this).attr('value') + ",";
                            }
                        });
                        eval(call + "(api, category, catelevel)");
                    }
                }
            },
            hide: 'unfocus',
            show:'click',
            style: 'qtip-bootstrap'
        });
    });
</script>


<script>
    $(function() {
        $("[number_col_filter]").qtip({
            content: {
                text: function (event, api) {
                    $.ajax({
                        url: api.elements.target.attr('number_col_filter')
                    })
                    .then(function (content) {
                        api.set('content.text', content);
                    });
                    return 'Loading...';
                }
            },
            events: {
                hide: function (event, api) {
                    var call = api.elements.target.attr('number_col_filter_call');
                    if (event.originalEvent.type == "click" && typeof call != "undefined") {
                        var condition = $(api.tooltip).find("#number_filder_dialog_form_condition").val();
                        var value = $(api.tooltip).find("#number_filder_dialog_form_value").val();
                        eval(call + "(api,condition, value)");
                    }
                }
            },
            hide: 'unfocus',
            show:'click',
            style: 'qtip-bootstrap'
        });
    });
</script>


<script>

<?php if($list != null): ?>var childNodes_num = document.getElementById("childNodes_num");
if (childNodes_num) {
    var nodes_num = childNodes_num.children.length;
    $("#td_colspan").attr('colspan',nodes_num);
}<?php endif; ?>
</script>

<script>
    $.ajax({
        url: '<?php echo U("index/checknav_releat_info");?>',
        success: function (data) {
            if (data.product_count > 0) {
                $("#main_nav_24").show();
                $("#main_nav_24").html(data.product_count);
            } else {
                $("#main_nav_24").hide();

            }
            if (data.customer_count > 0) {
                $("#main_nav_22").show();
                $("#main_nav_22").html(data.customer_count);
            } else {
                $("#main_nav_22").hide();
            }

            if (data.commiss_count > 0) {
                $("#main_nav_42").show();
                $("#main_nav_42").html(data.commiss_count);
            } else {
                $("#main_nav_42").hide();
            }

            if (data.commiss_genjin_count > 0) {
                $("#main_nav_genjin_42").show();
                $("#main_nav_genjin_42").html(data.commiss_genjin_count);
            } else {
                $("#main_nav_genjin_42").hide();
            }
        },
        cache: false
    });
</script>
</body>
</html>