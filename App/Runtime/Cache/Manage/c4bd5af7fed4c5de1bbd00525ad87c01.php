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
<style>
table tbody tr{cursor:move;}
</style>


<div class="container">
	<div class="page-header">
		<h4><?php echo L('SYSTEM_SETTING');?></h4>
	</div>
	<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
	<div class="tabbable">
        <ul class="nav nav-tabs">
    <li <?php if(ACTION_NAME == 'defaultinfo'): ?>class="active"<?php endif; ?>><a href="<?php echo U('setting/defaultInfo');?>"><?php echo L('BASIC_SYSTEM_SETTING');?></a></li>
    <li <?php if(($_GET['model'] == 'skill')): ?>class="active"<?php endif; ?>><a href="<?php echo U('product/category', 'model=skill');?>">技能类别</a></li>
    <li <?php if(($_GET['model'] == 'model')): ?>class="active"<?php endif; ?>><a href="<?php echo U('serve/category', 'model=model&assort=category');?>">产品类别</a></li>
    <li <?php if(($_GET['model'] == 'currier')): ?>class="active"<?php endif; ?>><a href="<?php echo U('currier/category', 'model=currier&assort=category');?>">培训分类</a></li>
    <?php if(session('?admin') and (session('league_id') == 0)): ?><li <?php if((ACTION_NAME == 'fields') OR (ACTION_NAME == 'product_fields')): ?>class="active"<?php endif; ?>><a href="<?php echo U('setting/fields');?>"><?php echo L('CUSTOMIZING_FIELDS_SETTING');?></a></li>
    <li <?php if((ACTION_NAME == 'accounttype')): ?>class="active"<?php endif; ?>><a href="<?php echo U('account/accounttype', 'model=account');?>">账目类型</a></li>
        <li <?php if(ACTION_NAME == 'setting'): ?>class="active"<?php endif; ?>><a href="<?php echo U('navigation/setting');?>"><?php echo L('SYSTEM_NAVIGATION_SETTING');?></a></li><?php endif; ?>
</ul>
    </div>
	<div class="row">
		<div class="span2 knowledgecate">
            
<link href="__PUBLIC__/css/jquery-accordion-menu.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/css/font-awesome.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    #nav-dropdown a{
        overflow:hidden;
        text-overflow:ellipsis;
        -o-text-overflow:ellipsis;
        white-space:nowrap;
        width:100%;
    }
    #nav-dropdown  .submenu li a{
        border-bottom:1px solid #eee;
    }
</style>

<script src="__PUBLIC__/js/jquery-accordion-menu.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#jquery-accordion-menu").jqueryAccordionMenu();
        $("#jquery-accordion-menu a.active").parent().parent().show();

    });

    $(function(){
        //顶部导航切换
        $(".nav-dropdown li").click(function(){
            $(".nav-dropdown li.active").removeClass("active")
            $(this).addClass("active");
        })
    })
</script>
<div id="jquery-accordion-menu" class="jquery-accordion-menu" style="width: 190px">
    <ul class="nav-dropdown">
        <li>
            <a href="javascript:void(0);">一般模块字段</a>
            <ul class="submenu">
                <li><a <?php if($_GET['model'] == 'customer' || $_GET['model'] == ''): ?>class="active"<?php endif; ?> href="<?php echo U('setting/fields', 'model=customer');?>"><i class="icon-chevron-right"></i><?php echo L('CUSTOMER_FIELDS_SETTING');?></a></li>
                <li><a <?php if($_GET['model'] == 'staff'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=staff');?>"><i class="icon-chevron-right"></i>员工字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'account'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=account');?>"><i class="icon-chevron-right"></i><?php echo L('ACCOUNT_FIELDS_SETTING');?></a></li>
                <li><a <?php if($_GET['model'] == 'prompt'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=prompt');?>"><i class="icon-chevron-right"></i>提醒字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'commiss'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=commiss');?>"><i class="icon-chevron-right"></i>客服字段设置</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:void(0);">雇员模块字段</a>
            <ul class="submenu">

                <li><a <?php if($_GET['model'] == 'product'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/product_fields', 'model=product');?>"><i class="icon-chevron-right"></i><?php echo L('PRODUCT_FIELDS_SETTING');?></a></li>
                <li><a <?php if($_GET['model'] == 'product_evaluate'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=product_evaluate');?>"><i class="icon-chevron-right"></i>评价字段</a></li>
                <li><a <?php if($_GET['model'] == 'product_appraisal'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=product_appraisal');?>"><i class="icon-chevron-right"></i>鉴定字段</a></li>

            </ul>
        </li>

        <li>
            <a href="javascript:void(0);">培训模块字段</a>
            <ul class="submenu">

                <li><a <?php if($_GET['model'] == 'cultivate'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=cultivate');?>"><i class="icon-chevron-right"></i>培训订单字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'currier'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/currier_fields', 'model=currier');?>"><i class="icon-chevron-right"></i>培训字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'cultivate_channel'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=cultivate_channel');?>"><i class="icon-chevron-right"></i>培训渠道字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'cultivate_urge'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=cultivate_urge');?>"><i class="icon-chevron-right"></i>培训促单字段设置</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:void(0);">产品模块字段</a>
            <ul class="submenu">

                <li><a <?php if($_GET['model'] == 'serve'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/serve_fields', 'model=serve');?>"><i class="icon-chevron-right"></i>产品字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'trade'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=trade');?>"><i class="icon-chevron-right"></i>产品订单字段设置</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:void(0);">客户服务字段</a>
            <ul class="submenu">

                <li><a <?php if($_GET['model'] == 'market'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=market');?>"><i class="icon-chevron-right"></i>服务字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'market_product'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=market_product');?>"><i class="icon-chevron-right"></i>服务雇员字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'market_channel'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=market_channel');?>"><i class="icon-chevron-right"></i>服务渠道字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'market_urge'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=market_urge');?>"><i class="icon-chevron-right"></i>服务促单字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'market_product_evaluate'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=market_product_evaluate');?>"><i class="icon-chevron-right"></i>客户评价字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'market_survey'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=market_survey');?>"><i class="icon-chevron-right"></i>回访评价字段设置</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript:void(0);">公司管理</a>
            <ul class="submenu">
                <li><a <?php if($_GET['model'] == 'branch'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=branch');?>"><i class="icon-chevron-right"></i>门店字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'dorm'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=dorm');?>"><i class="icon-chevron-right"></i>宿舍字段设置</a></li>
                <li><a <?php if($_GET['model'] == 'berth'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=berth');?>"><i class="icon-chevron-right"></i>床位字段设置</a></li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0);">加盟商模块字段</a>
            <ul class="submenu">
                <li><a <?php if($_GET['model'] == 'league'): ?>class="active"<?php endif; ?>  href="<?php echo U('setting/fields', 'model=league');?>"><i class="icon-chevron-right"></i>加盟商字段设置</a></li>
            </ul>
        </li>
    </ul>
</div>
        </div>
        <form action="<?php echo U('setting/fielddelete');?>" method="post" onSubmit="return checkForm()">
            <div class="span10">
                <p>
                    <div class="bulk-actions align-left">
                        <button type="submit" class="btn">
                            <i class="icon-remove"></i>&nbsp;<?php echo L('DELETE');?>
                        </button>
                        <button class="btn" type="button" id="sort_btn">
                            <span class="icon-file"></span>&nbsp;<?php echo L('SAVE_ORDER');?>
                        </button>
                        <div class="pull-right">
                            <button class="btn btn-default" type="button" id="fieldsgroup">
                                <i class="icon-plus"></i>&nbsp; 分组管理
                            </button>
                            <a   data-toggle="modal"  href='<?php echo U("setting/fieldadd","model=$model&assort=$assort");?>' data-target="#dialog-add-field" class="btn btn-primary" id="add">
                                <i class="icon-plus"></i>&nbsp; <?php echo L('ADD_FIELDS');?>
                            </a>
                        </div>
                    </div>
                </p>
            </div>
			<div class="span10">
                <table class="table table-hover table-striped table_thead_fixed" width="95%" border="0" cellspacing="1" cellpadding="0">
    <thead>
    <tr>
        <th width="5%">
            <input type="checkbox" name="check_all" id="check_all" class="check_all"/> &nbsp;
        </th>
        <th width="20%"><?php echo L('LABEL_NAME');?></th>
        <th width="20%"><?php echo L('FIELDS_NAME');?></th>
        <th width="20%"><?php echo L('FIELDS_TYPE');?></th>
        <th><?php echo L('OPERATING');?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <td colspan="5">
            <div class="span8"><span style="color: rgb(243, 40, 12);"><?php echo L('HINT_FIELDS');?></span></div>
        </td>
    </tr>
    </tfoot>
    <tbody>

    <?php if(is_array($fields_group)): $i = 0; $__LIST__ = $fields_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i; if($gvo["field_group_id"] == '0'): ?><thead><?php endif; ?>
        <tr group_id="<?php echo ($gvo["field_group_id"]); ?>" class="field_group_row">
            <td style="background-color: #7ab5d3" colspan="3">
                <input type="checkbox" group_id="<?php echo ($gvo["field_group_id"]); ?>" class="check_group" id="check_group_<?php echo ($vo["field_group_id"]); ?>"/> &nbsp;<?php echo ($gvo["name"]); ?>
            </td>
            <td style="background-color: #7ab5d3"></td>
            <td style="background-color: #7ab5d3"></td>
        </tr>
        <?php if($gvo["field_group_id"] == '0'): ?></thead><?php endif; ?>

        <?php if($gvo["field_group_id"] == '0'): ?><tr group_id="<?php echo ($gvo["field_group_id"]); ?>" class="field_group_row" style="display: none">
                <td  colspan="5"></td>
            </tr><?php endif; ?>

        <?php if(is_array($gvo['fields'])): $i = 0; $__LIST__ = $gvo['fields'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <td>
                    <input <?php if($vo['operating'] != 0 and $vo['operating'] != 3 ): ?>disabled<?php endif; ?> type="checkbox" group_id="<?php echo ($vo["field_group_id"]); ?>" class="list" name="field_id[]" value="<?php echo ($vo["field_id"]); ?>"/>
                </td>
                <td><?php echo ($vo["name"]); ?></td>
                <td><?php echo ($vo["field"]); ?></td>
                <td>
                    <?php if($vo['form_type'] == 'box'): echo L('SELECTED');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'editor'): echo L('THE_EDITOR');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'text'): echo L('SINGLE_LINE_TEXT');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'textarea'): echo L('MULTILINE_TEXT');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'datetime'): echo L('DATE');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'number'): echo L('NUMBER');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'floatnumber'): echo L('NUMBER');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'user'): echo L('USERS');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'address'): echo L('ADDRESS');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'phone'): echo L('TELEPHONE');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'mobile'): echo L('PHONE');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'email'): echo L('EMAIL');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'linkaddress'): echo L('LINK_ADDRESS');?>(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'pic'): ?>图片(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'video'): ?>视频(<?php echo ($vo["form_type"]); ?>)
                        <?php elseif($vo['form_type'] == 'file'): ?>文件(<?php echo ($vo["form_type"]); ?>)
                        <?php else: ?><span style="color:red;"><?php echo L('SPECIAL_FIELD_ACCESS_IS_LIMITED');?></span><?php endif; ?>
                </td>
                <td>
                    <?php if($vo['operating'] == 0 or $vo['operating'] == 1 ): ?><a  data-toggle="modal"  href='<?php echo U("setting/fieldedit","field_id=".$vo["field_id"]);?>' data-target="#dialog-edit-field"><?php echo L('EDIT');?></a>
                    <?php else: ?>
                        <s style="color:rgb(187, 180, 180);"><?php echo L('EDIT');?></s><?php endif; ?> &nbsp;

                    <?php if($vo['operating'] == 0 or $vo['operating'] == 3 ): ?><a class="delete" href="javascript:void(0)" rel="<?php echo ($vo["field_id"]); ?>"><?php echo L('DELETE');?></a>
                    <?php else: ?>
                        <s style="color:rgb(187, 180, 180);"><?php echo L('DELETE');?></s><?php endif; ?> &nbsp;

                    <?php if($vo['form_type'] != 'user' and $vo['in_index'] == 0): ?><a class="indexShow" href="javascript:void(0)" rel="<?php echo ($vo["field_id"]); ?>"><?php echo L('THE_LIST_SHOWS');?></a>
                    <?php elseif($vo['form_type'] != 'user' and $vo['in_index'] == 1): ?>
                        <a class="indexShow" href="javascript:void(0)" rel="<?php echo ($vo["field_id"]); ?>"><?php echo L('CANCEL_THE_LIST');?></a><?php endif; ?>&nbsp;

                    <?php if(($vo["model"] == 'product')): if($vo['form_type'] != 'user' and $vo['in_home'] == 0): ?><a class="homeShow" href="javascript:void(0)" rel="<?php echo ($vo["field_id"]); ?>">网站显示</a>
                    <?php elseif($vo['form_type'] != 'user' and $vo['in_home'] == 1): ?>
                        <a class="homeShow" href="javascript:void(0)" rel="<?php echo ($vo["field_id"]); ?>">取消网站显示</a><?php endif; ?>&nbsp;<?php endif; ?>

                    <?php if(($vo["model"] == 'staff')): if($vo['field'] == 'level'): ?><a class="level_urge_position_ratio" href="javascript:void(0)" rel="<?php echo ($vo["field_id"]); ?>">促单费系数</a><?php endif; ?>&nbsp;<?php endif; ?>

                    <?php if($vo['form_type'] == 'b_box'): ?><a href="<?php echo U('setting/businessstatus','model=as');?>"><?php echo L('SPECIAL_SETTING');?></a>
                    <?php elseif($vo['form_type'] == 'bs_box'): ?>
                        <a href="<?php echo U('setting/businessstatus','model=ss');?>"><?php echo L('SPECIAL_SETTING');?></a>
                    <?php elseif($vo['form_type'] == 's_box'): ?>
                        <a href="<?php echo U('setting/fields','model=skill');?>"><?php echo L('SPECIAL_SETTING');?></a>
                    <?php elseif($vo['form_type'] == 'p_box'): ?>
                        <a href="<?php echo U('product/category','model='.$_GET['model']);?>"><?php echo L('SPECIAL_SETTING');?></a>
                    <?php elseif($vo['form_type'] == 'a_box'): ?>
                        <a href="<?php echo U('account/accounttype','model='.$_GET['model']);?>"><?php echo L('SPECIAL_SETTING');?></a>
                    <?php elseif($vo['form_type'] == 'w_box'): ?>
                        <a href="<?php echo U('product/workstate','model='.$_GET['model']);?>"><?php echo L('SPECIAL_SETTING');?></a>
                    <?php elseif($vo['form_type'] == 'cer_box'): ?>
                        <a href="<?php echo U('train/certificate','model='.$_GET['model']);?>"><?php echo L('SPECIAL_SETTING');?></a>
                    <?php elseif($vo['form_type'] == 'ms_box'): ?>
                        <a href="<?php echo U('setting/marketstatus','model=ss');?>"><?php echo L('SPECIAL_SETTING');?></a>
                    <?php elseif($vo['form_type'] == 'origin_box'): ?>
                        <a href="<?php echo U('setting/origin','model=ss');?>"><?php echo L('SPECIAL_SETTING');?></a><?php endif; ?>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
</table>

            </div>
		</form>
	</div>
</div>


<form class="form-horizontal" action="<?php echo U('setting/fieldadd');?>" method="post" name="form1" id="form1">
    <div id="dialog-add-field" class="modal fade" role="dialog"  aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    添加字段
                </h4>
            </div>

            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    关闭
                </button>
                <button type="submit" class="btn btn-primary">
                    保存
                </button>
            </div>
        </div>
        <script>
            $("#dialog-add-field").on("hidden", function() {
                $(this).removeData("modal");
            });
        </script>
    </div>
</form>

<form action="<?php echo U('setting/fieldedit');?>" method="post" id="edit_form">
    <div id="dialog-edit-field" class="modal fade" role="dialog"  aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
                </button>
                <h4 class="modal-title">
                    修改字段
                </h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    关闭
                </button>
                <button type="submit" class="btn btn-primary">
                    提交更改
                </button>
            </div>
        </div>
        <script>
            $("#dialog-edit-field").on("hidden", function() {
                $(this).removeData("modal");
            });
        </script>
    </div>
</form>

<script type="text/javascript">
    $("table tbody").sortable({
        connectWith: "table tbody",
        stop: function( event, ui ) {
            $.each($(".list"), function(i, item){
                var item_tr = $(item).parent().parent();
                while(true) {
                    if (item_tr.attr('group_id') != undefined) {
                        break;
                    }
                    item_tr = item_tr.prev();
                }
                var field_group_id = item_tr.attr('group_id');
                $(item).attr('group_id', field_group_id);
            });
        }
    });

    function checkForm(){
        return confirm('确实要提交更改吗？');
    }

    $(function(){
        $("#check_all").click(function(){
            $("input[class='list']:enabled").prop('checked', $(this).prop("checked"));
            $("input[class='check_group']:enabled").prop('checked', $(this).prop("checked"));
        });

        $(".check_group").click(function(){
            var group_id = $(this).attr('group_id');
            $("input[class='list'][group_id='"+group_id+"']:enabled").prop('checked', $(this).prop("checked"));
        });

        $("#fieldsgroup").click(function(){
            window.location.assign('<?php echo U("setting/fieldsgroup","model=$model&assort=$assort");?>');
        });

        $(".delete").click(function(){
            var id = $(this).attr('rel');
            if(confirm('<?php echo L('DELETE_THE_SELECTED_FIELDS_OPERATION_CANNOT_BE_RESTORED');?>')){
                window.location.assign('<?php echo U("setting/fielddelete","field_id");?>'+id);
            }
        });
        $(".indexShow").click(function(){
            var id = $(this).attr('rel');
            window.location.assign('<?php echo U("setting/indexShow","field_id");?>'+id);
        });

        $(".homeShow").click(function(){
            var id = $(this).attr('rel');
            window.location.assign('<?php echo U("setting/homeShow","field_id");?>'+id);
        });

        $("#sort_btn").click(
            function() {
                position = [];
                $.each($(".list"), function(i, item){
                    var field_group_id= $(item).attr('group_id');
                    position.push({field_id:item.value,field_group_id: field_group_id});
                });

                $.get('<?php echo U("setting/fieldsort");?>',{postion:position}, function(data){
                    if (data.status == 1) {
                        $('.alert.alert-success').remove();
                        $(".page-header").after('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>' + data.info + '</div>');
                    } else {
                        $('.alert.alert-error').remove();
                        $(".page-header").after('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button>' + data.info + '</div>');
                    }
                }, 'json');
            }
        );

        $(".level_urge_position_ratio").click(function(){
            var dialog = art.dialog({
                id: 'N3690',
                title: "促单费系数",
                lock:true,
                ok: function () {
                    $("#urge_position_ratio_dialog_form").submit();
                },
                cancel:true
            });
            $.ajax({
                url:'<?php echo U("user/urge_position_ratio_dialog");?>',
                success: function (data) {
                    dialog.content(data);
                },
                cache: false
            });
        });
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