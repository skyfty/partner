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

    @media screen and (min-width: 1200px){
        .container {
            width: 1170px;
        }
    }

    @media screen and (min-width: 1280px) {
        .container {
            width: 1280px;
        }

    }


    @media screen and (min-width: 1380px) {
        .container {
            width: 1380px;
        }

    }

    @media screen and (min-width: 1440px) {
        .container {
            width: 1410px;
        }
    }

    @media screen and (min-width: 1500px) {
        .container {
            width: 1470px;
        }
    }

    @media screen and (min-width: 1600px) {
        .container {
            width: 1550px;
        }
    }

    @media screen and (min-width: 1900px) {
        .container {
            width: 1850px;
        }
    }
    @media screen and (min-width: 2048px) {
        .container {
            width: 2000px;
        }
    }
    @media screen and (min-width: 2560px) {
        .container {
            width: 2500px;
        }
    }

</style>


<div class="container">
	<!-- Docs nav ================================================== -->
	<div class="page-header">
        <ul class="nav nav-tabs">
    <li <?php if((ACTION_NAME) == "index"): if(($_GET['act']) != "group"): ?>class="active"<?php endif; endif; ?>>
        <a href="<?php echo U('customer/index');?>">
            <img src="__PUBLIC__/img/customer_icon.png"/>&nbsp; <?php echo L('CUSTOMER');?>
            <?php if($module_group != null): ?>[<?php echo ($module_group['type_name']); ?>： <?php echo ($module_group['name']); ?><i class="icon-remove remove-group"  style="color: red"></i>]<?php endif; ?>
        </a>
    </li>
    <li <?php if(($_GET['act']) == "group"): ?>class="active"<?php endif; ?>>
        <a href="<?php echo U('customer/index');?>&<?php echo FP($parameter, 'act=group');?>">
            <img src="__PUBLIC__/img/customer_source_icon.png"/> &nbsp;分组
        </a>
    </li>

    <li <?php if((ACTION_NAME) == "logger"): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('customer/logger');?>">
        <img src="__PUBLIC__/img/my_log.png"/> &nbsp;
        日志
    </a>
    </li>
</ul>
	</div>
	<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
	<p class="view">
		<img src=" __PUBLIC__/img/by_owner.png"/>
        <a href="<?php echo U('');?>&<?php echo FP($parameter, 'by=&lia=');?>" <?php if($_GET['lia']== null): ?>class="active"<?php endif; ?>>
        <?php echo L('ALL');?>
        </a>
        <a href="<?php echo U('');?>&<?php echo FP($parameter, 'lia=self');?>" <?php if($_GET['lia']== 'self'): ?>class="active"<?php endif; ?>>
        我负责的
        </a>
        <a href="<?php echo U('');?>&<?php echo FP($parameter, 'lia=belongs');?>" <?php if($_GET['lia']== 'belongs'): ?>class="active"<?php endif; ?>>
        我管辖的
        </a>
        <img src="__PUBLIC__/img/by_time.png"/>
		<a <?php if($_GET['byd']== 'today'): ?>class="active"  href="<?php echo U('');?>&<?php echo FP($parameter, 'byd=');?> <?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'byd=today');?>"<?php endif; ?>><?php echo L('TODAY_IS_NEW');?></a> |
		<a <?php if($_GET['byd']== 'week'): ?>class="active"  href="<?php echo U('');?>&<?php echo FP($parameter, 'byd=');?>"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'byd=week');?>"<?php endif; ?>><?php echo L('THIS_WEEK_THE_NEW');?></a> |
		<a <?php if($_GET['byd']== 'month'): ?>class="active" href="<?php echo U('');?>&<?php echo FP($parameter, 'byd=');?>" <?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'byd=month');?>"<?php endif; ?>><?php echo L('THIS_MONTH_THE_NEW');?></a>&nbsp;

        <img src="__PUBLIC__/img/by_status.png"/>
        <a <?php if($_GET['byv']== 'cv'): ?>class="active"  href="<?php echo U('');?>&<?php echo FP($parameter, 'byv=');?>"<?php else: ?>  href="<?php echo U('');?>&<?php echo FP($parameter, 'byv=cv');?>"<?php endif; ?>>待审核</a> |
        <a <?php if($_GET['byv']== 'yv'): ?>class="active" href="<?php echo U('');?>&<?php echo FP($parameter, 'byv=');?>"  <?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'byv=yv');?>"<?php endif; ?> >通过审核</a> |
        <a <?php if($_GET['byv']== 'nv'): ?>class="active"  href="<?php echo U('');?>&<?php echo FP($parameter, 'byv=');?>" <?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'byv=nv');?>"<?php endif; ?>>未通过审核</a> |
        <a <?php if($_GET['byv']== 'sbi'): ?>class="active"  href="<?php echo U('');?>&<?php echo FP($parameter, 'byv=');?>" <?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'byv=sbi');?>"<?php endif; ?>>未提交</a>

        <img src="__PUBLIC__/img/by_status.png"/>
        <a <?php if($_GET['service_state']== '未成单'): ?>class="active"  href="<?php echo U('');?>&<?php echo FP($parameter, 'service_state=');?>"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'service_state=未成单');?>"<?php endif; ?>>未成单</a> |
        <a <?php if($_GET['service_state']== '服务前'): ?>class="active"  href="<?php echo U('');?>&<?php echo FP($parameter, 'service_state=');?>"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'service_state=服务前');?>"<?php endif; ?> >服务前</a> |
        <a <?php if($_GET['service_state']== '服务中'): ?>class="active"  href="<?php echo U('');?>&<?php echo FP($parameter, 'service_state=');?>"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'service_state=服务中');?>"<?php endif; ?>>服务中</a> |
        <a  <?php if($_GET['service_state']== '服务后'): ?>class="active" href="<?php echo U('');?>&<?php echo FP($parameter, 'service_state=');?>"<?php else: ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'service_state=服务后');?>"<?php endif; ?>>服务后</a>
    </p>
    <div class="row-fluid">
		<div class="span12">
            <div class="pull-left">
            <form class="form-inline" id="searchForm" action="index.php" method="get">
            <ul class="nav pull-left">
                <li class="pull-left">
                    <button  id="delete"  type="button" class="btn btn-mini  btn-danger" style="margin-right: 5px;"><i class="icon-remove"></i><?php echo L('DELETE');?></button >
                </li>
                <li class="pull-left">
                    <?php if(($_GET['customer_group_id']!= null) and ($_GET['group_type']== '1')): ?><div class="btn-group">
                            <button type="button" class="btn btn-mini btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">组管理<span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a id="add_group" href="javascript:void(0);">添加到组</a></li>
                                <li><a id="remove_group" href="javascript:void(0);">从本组移除</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <button  id="add_group" type="button"  class="btn btn-mini" style="margin-right: 5px;"><img src="__PUBLIC__/img/customer_source_icon.png"/>添加到组</button ><?php endif; ?>
                </li>
                <?php if(session('user_id') == '1'): ?><li class="pull-left">
                        <?php echo league_select_html("bylea", $league['league_id']);?>&nbsp;&nbsp;
                        <script>
                            $(function() {
                                $("#bylea").change(def_short_search_select);
                            });
                        </script>
                    </li><?php endif; ?>
                <li class="pull-left">
                    <?php echo branch_select_html("bybr", $branch,true, true, null, $league['league_id']);?>&nbsp;&nbsp;
                    <script>
                        $(function() {
                            $("#bybr").change(def_short_search_select);
                        });
                    </script>
                </li>
				<li class="pull-left">
                    <ul class="nav pull-left">
                        <li class="pull-left" >
                            <select style="width:150px" id="field" onchange="changeCondition()" name="field">
    <option class="text" value="all"><?php echo L('ANY_FIELD');?></option>
    <?php if(is_array($field_list)): $i = 0; $__LIST__ = $field_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; if($v['form_type'] == 'datetime'): ?><option is_showtime="<?php echo ($v['is_showtime']); ?>" class="<?php echo ($v['form_type']); ?>" value="<?php echo ($v[field]); ?>" rel="<?php echo ($module_name); ?>"><?php echo ($v[name]); ?></option>
            <?php else: ?>
            <option class="<?php echo ($v['form_type']); ?>" value="<?php echo ($v[field]); ?>" rel="<?php echo ($module_name); ?>"><?php echo ($v[name]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
</select>&nbsp;&nbsp;

                        </li>
                        <li id="conditionContent" class="pull-left">
    &nbsp;<select id="condition" style="width:auto"   name="all[condition]" onchange="changeSearch()">
        <option value="contains"><?php echo L('INCLUDE');?></option>
        <option value="is"><?php echo L('YES');?></option>
        <option value="start_with"><?php echo L('BEGINNING_CHARACTER');?></option>
        <option value="end_with"><?php echo L('TERMINATION_CHARACTER');?></option>
        <option value="is_empty"><?php echo L('MANDATORY');?></option>
    </select>
    <script>
        $(function() {
            $("#condition").chosen({});
        });
    </script>
</li>
<li id="searchContent" class="pull-left">
    <input id="search" type="text"  name="all[value]" placeholder="搜索关键字"  onkeypress="if(event.keyCode==13) {def_short_search_select();return false;}"/>
</li>
<li id="static_condition" class="pull-left"></li>
<li class="pull-left">
    <?php if($_GET['cat']!= null): ?><input type="hidden" name="cat" value="<?php echo ($_GET['cat']); ?>"/><?php endif; ?>
    <input type="hidden" name="m" value="<?php echo (strtolower(MODULE_NAME)); ?>"/>
    <input type="hidden" name="a" value="<?php echo (ACTION_NAME); ?>"/>
    <input type="hidden" name="act" id="act" value="<?php echo ($_GET['act']); ?>"/>

    <?php if($_GET['by']!= null): ?><input type="hidden" name="by" value="<?php echo ($_GET['by']); ?>"/><?php endif; ?>
    <?php if($_GET['module_group_id']!= null): ?><input type="hidden" name="module_group_id" value="<?php echo ($_GET['module_group_id']); ?>"/><?php endif; ?>
    <?php if($_GET['group_type']!= null): ?><input type="hidden" name="group_type" value="<?php echo ($_GET['group_type']); ?>"/><?php endif; ?>

    <?php if($_GET['pl']!= null): ?><input type="hidden" name="pl" value="<?php echo ($_GET['pl']); ?>"/><?php endif; ?>

    <?php echo search_form_default_param($parameter, $debar_search_field);?>

    <div class="btn-group" role="group">
        <button type="submit" id="dosearch" class="btn btn-mini btn-default">
            <img src="__PUBLIC__/img/search.png"/>
        </button>
        <button type="button" id="clean_search" class="btn btn-mini btn-default" style=";margin-right: 5px;">全部</button>
    </div>
</li>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script>
    function def_short_search_select( val, label) {
        $("#searchForm").submit();
    }
    $(function() {
        $("#clean_search").click(function(){
            var search_field_name = $("#search").attr("name");
            var search_field_condition = $("#condition").attr("name");
            $("#search").val('');$("input[name='"+search_field_name+"']").val('');
            $("#condition").val('');$("input[name='"+search_field_condition+"']").val('');
            $("#field").val('');
            $("#wsbt").val('');
            $("#wset").val('');
            $("#nobd").val('');
            $("#searchForm").submit();
        });

        $("#dosearch").click(function(){
            $("#searchForm").submit();
        });

        $("#field option[value='<?php echo ($search_field); ?>']").prop("selected", true);changeCondition();$("#field").chosen({});
        $("#condition option[value='<?php echo ($search_condition); ?>']").prop("selected", true);changeSearch();

        <?php if($search_date_field): ?>$("#search_bt").prop('value', '<?php echo ($search_value[0]); ?>');
            $("#search_et").prop('value', '<?php echo ($search_value[1]); ?>');
        <?php elseif($Think.get.field): ?>
            $("#search").prop('value', '<?php echo ($search_value); ?>');
            $("#search").focus();
            var cb = typeof(short_search_format) == "function" ? short_search_format : null;
            var scb = typeof(short_search_select) == "function" ? short_search_select : def_short_search_select;<?php endif; ?>

        <?php if($Think.get.search_census): ?>$("#search_census").prop('value', '<?php echo ($_GET['search_census']); ?>');<?php endif; ?>
    });
</script>
                    </ul>
				</li>
			</ul>
            </form>
            </div>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="<?php echo U('Customer/add');?>" class="btn btn-mini btn-primary">
                        <i class="icon-plus"></i>&nbsp; 添加
                    </a>
                    <button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu  pull-right">
                        <li>
                            <a href="javascript:void(0);" id="advanced_dosearch" class="link">
                                <?php echo L('ADVANCED_SEARCH');?>
                            </a>
                        </li>
                        <?php if(vali_permission('customer', 'export')): ?><li>
                            <a href="javascript:void(0);" id="excelExport" class="link">
                                </i>导出到Excel
                            </a>
                        </li><?php endif; ?>
                    </ul>
                </div>
			</div>
		</div>
    </div>

    <div class="row-fluid">
        <div class="span12">
			<form id="form1" class="form-inline" action="" method="post">
				<input type="hidden" name="owner_role" id="hidden_owner_id" value="0"/>
				<input type="hidden" name="message_alert" id="hidden_message" value="0"/>
				<input type="hidden" name="sms_alert" id="hidden_sms" value="0"/>
				<input type="hidden" name="email_alert" id="hidden_email" value="0"/>
				<input type="hidden" name="operating_type" id="operating_type" value=""/>
                <table class="table table-hover table-striped table-condensed table_thead_fixed" id="listtable">
					<thead>
						<tr id="childNodes_num">
							<th><input type="checkbox" id="check_all"/></th>
                            <?php if(is_array($field_array)): $i = 0; $__LIST__ = $field_array;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><th  class="flter_table_header">
        <?php if($vo['field'] == 'owner_role_id'): if($_GET['asc_order'] == $vo['field']): ?><a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order='.$vo['field']);?>"  title="<?php echo ($vo["link_title"]); ?>">
        <?php echo ($vo["name"]); ?> &nbsp;<img src="__PUBLIC__/img/arrow_up.png">
    </a>
<?php elseif($_GET['desc_order'] == $vo['field']): ?>
    <a href="<?php echo U('');?>&<?php echo FP($parameter, 'desc_order=&asc_order='.$vo['field']);?>"  title="<?php echo ($vo["link_title"]); ?>">
        <?php echo ($vo["name"]); ?>&nbsp;<img src="__PUBLIC__/img/arrow_down.png">
    </a>
<?php else: ?>
    <a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order='.$vo['field']);?>"  title="<?php echo ($vo["link_title"]); ?>">
        <?php echo ($vo["name"]); ?>
    </a><?php endif; ?>

            <a onmouseover="mopen('owner_role_id')" onmouseout="mclosetime()"  href="javascript:void(0);" id="owner_role_id_filter">
                <i class="icon-filter"></i>
            </a>
            <?php if(($_GET['owner_role_id']!= '')): ?><br/><span style="font-weight:normal"><?php echo (role_show($_GET['owner_role_id'])); ?></span><?php endif; ?>
            <div id="owner_role_id" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                <a <?php if(($_GET['owner_role_id']== '')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'owner_role_id=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'owner_role_id=');?>"<?php endif; ?>>
                全部
                </a>
                <?php $_result=customer_owner_staff_list($branch);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a <?php if(($_GET['owner_role_id']== $vo['role_id'])): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'owner_role_id=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'owner_role_id='.$vo['role_id']);?>"<?php endif; ?>>
                    <?php echo ($vo['name']); ?>
                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        <?php elseif($vo['field'] == 'origin'): ?>
            <?php if($_GET['asc_order'] == $vo['field']): ?><a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order='.$vo['field']);?>"  title="<?php echo ($vo["link_title"]); ?>">
        <?php echo ($vo["name"]); ?> &nbsp;<img src="__PUBLIC__/img/arrow_up.png">
    </a>
<?php elseif($_GET['desc_order'] == $vo['field']): ?>
    <a href="<?php echo U('');?>&<?php echo FP($parameter, 'desc_order=&asc_order='.$vo['field']);?>"  title="<?php echo ($vo["link_title"]); ?>">
        <?php echo ($vo["name"]); ?>&nbsp;<img src="__PUBLIC__/img/arrow_down.png">
    </a>
<?php else: ?>
    <a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order='.$vo['field']);?>"  title="<?php echo ($vo["link_title"]); ?>">
        <?php echo ($vo["name"]); ?>
    </a><?php endif; ?>


            <a onmouseover="mopen('origin')" onmouseout="mclosetime()" href="javascript:void(0);">
                <i class="icon-filter"></i>
            </a>
            <?php if(($_GET['origin']!= '')): ?><br/><span style="font-weight:normal"><?php echo ($_GET['origin']); ?></span><?php endif; ?>
            <div  id="origin" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                <a <?php if(($_GET['origin']== '')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'origin=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'origin=');?>"<?php endif; ?>>
                全部
                </a>
                <?php $_result=customer_origin_list();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a <?php if(($_GET['origin']== $vo)): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'origin=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'origin='.$vo);?>"<?php endif; ?>>
                    <?php echo ($vo); ?>
                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>

        <?php else: ?>
            <?php if($_GET['asc_order'] == $vo['field']): ?><a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order='.$vo['field']);?>"  title="<?php echo ($vo["link_title"]); ?>">
        <?php echo ($vo["name"]); ?> &nbsp;<img src="__PUBLIC__/img/arrow_up.png">
    </a>
<?php elseif($_GET['desc_order'] == $vo['field']): ?>
    <a href="<?php echo U('');?>&<?php echo FP($parameter, 'desc_order=&asc_order='.$vo['field']);?>"  title="<?php echo ($vo["link_title"]); ?>">
        <?php echo ($vo["name"]); ?>&nbsp;<img src="__PUBLIC__/img/arrow_down.png">
    </a>
<?php else: ?>
    <a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order='.$vo['field']);?>"  title="<?php echo ($vo["link_title"]); ?>">
        <?php echo ($vo["name"]); ?>
    </a><?php endif; ?>

            <?php if($vo['form_type'] == 'datetime'): ?><a onmouseover="mopen('table_header_datetime_<?php echo ($vo['field']); ?>')" field="<?php echo ($vo['field']); ?>" onmouseout="mclosetime()" date_col_filter="<?php echo U('Berth/list_col_filter_select','type=date&field_id='.$vo['field_id'].'&field='.$vo['field']);?>" date_col_filter_call="filter_berth_date_field"  href="javascript:void(0);">
    <i class="icon-filter"></i>
</a>
    <?php if(($_GET[$vo['field']]['value'] != '')): ?><br/>
    <span style="font-weight:normal"><?php echo format_table_head_date_col($vo['field'], $parameter);?></span><?php endif; endif; ?>

<?php if($vo['form_type'] == 'box'): ?><a onmouseover="mopen('list_head_<?php echo ($vo['field']); ?>')" onmouseout="mclosetime()"  href="javascript:void(0);" id="list_head_<?php echo ($vo['field']); ?>_filter">
    <i class="icon-filter"></i>
</a>
<?php if(($_GET[$vo['field']]['value'] != '')): ?><br/><span style="font-weight:normal"><?php echo ($_GET[$vo['field']]); ?></span><?php endif; ?>
<div id="list_head_<?php echo ($vo['field']); ?>" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
    <a <?php if(($_GET[$vo['field']] == '')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, $vo['field'].'=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, $vo['field'].'=');?>"<?php endif; ?>>
    全部
    </a>
    <?php $_result=model_select_field_list($vo['field_id']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fvo): $mod = ($i % 2 );++$i;?><a <?php if(($_GET[$vo['field']] == $fvo)): ?>href="<?php echo U('');?>&<?php echo FP($parameter, $vo['field'].'=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, $vo['field'].'='.$fvo);?>"<?php endif; ?>>
        <?php echo ($fvo); ?>
        </a><?php endforeach; endif; else: echo "" ;endif; ?>
</div><?php endif; ?>

<?php if($vo['form_type'] == 'tr_cate'): ?><a onmouseover="mopen('table_header_tr_cate_<?php echo ($vo['field']); ?>')" field="<?php echo ($vo['field']); ?>" onmouseout="mclosetime()" text_col_filter="<?php echo U('Berth/list_col_filter_select','type=text&field_id='.$vo['field_id'].'&field='.$vo['field']);?>" text_col_filter_call="filter_berth_text_field"  href="javascript:void(0);">
        <i class="icon-filter"></i>
    </a>
    <?php if(($_GET[$vo['field']]['value'] != '')): ?><br/>
    <span style="font-weight:normal"><?php echo format_table_head_text_col($vo['field'], $parameter);?></span><?php endif; endif; ?>

<?php if($vo['field'] == 'branch_id'): elseif(in_array($vo['form_type'], array('text', 'telephone', 'mobile'))): ?>
    <a onmouseover="mopen('table_header_text_<?php echo ($vo['field']); ?>')" field="<?php echo ($vo['field']); ?>" onmouseout="mclosetime()" text_col_filter="<?php echo U('Berth/list_col_filter_select','type=text&field_id='.$vo['field_id'].'&field='.$vo['field']);?>" text_col_filter_call="filter_berth_text_field"  href="javascript:void(0);">
        <i class="icon-filter"></i>
    </a>
    <?php if(($_GET[$vo['field']]['value'] != '')): ?><br/>
    <span style="font-weight:normal"><?php echo format_table_head_text_col($vo['field'], $parameter);?></span><?php endif; ?>
<?php elseif(($vo['form_type'] == 'channel_role_id_box') or ($vo['form_type'] == 'channel_role_model_box')): ?>
    <a onmouseover="mopen('table_header_text_<?php echo ($vo['field']); ?>')" field="<?php echo ($vo['field']); ?>" onmouseout="mclosetime()" text_col_filter="<?php echo U('Berth/list_col_filter_select','type=text&field_id='.$vo['field_id'].'&field='.$vo['field']);?>" text_col_filter_call="filter_berth_text_field"  href="javascript:void(0);">
        <i class="icon-filter"></i>
    </a>
    <?php if(($_GET[$vo['field']]['value'] != '')): ?><br/>
    <span style="font-weight:normal"><?php echo format_table_head_text_col($vo['field'], $parameter);?></span><?php endif; ?>
<?php elseif($vo['form_type'] == 'number' or $vo['form_type'] == 'floatnumber'): ?>
    <a onmouseover="mopen('table_header_number_<?php echo ($vo['field']); ?>')" field="<?php echo ($vo['field']); ?>" onmouseout="mclosetime()" number_col_filter="<?php echo U('Berth/list_col_filter_select','type=number&field_id='.$vo['field_id'].'&field='.$vo['field']);?>" number_col_filter_call="filter_berth_text_field"  href="javascript:void(0);">
        <i class="icon-filter"></i>
    </a>
    <?php if(($_GET[$vo['field']]['value'] != '')): ?><br/>
    <span style="font-weight:normal"><?php echo format_table_head_text_col($vo['field'], $parameter);?></span><?php endif; endif; endif; ?>
    </th><?php endforeach; endif; else: echo "" ;endif; ?>
							<th style="width:90px;text-align: center" ><?php echo L('OPERATION');?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td id="td_colspan"><?php echo ($page); ?></td>
						</tr>
					</tfoot>
					<tbody>
						<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
								<td>
									<input name="customer_id[]" class="check_list" type="checkbox" value="<?php echo ($vo["customer_id"]); ?>"/>
								</td>
                                <?php if(is_array($field_array)): $i = 0; $__LIST__ = $field_array;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><td>
    <span style="color:#<?php echo ($v['color']); ?>">
    <?php if(($v['field'] == 'product_id') and ($vo['product_id']!='')): ?><a href="<?php echo U('product/view', 'id='.$vo['product_id']);?>" target="_blank" title="[<?php echo ($vo['product_idcode']); ?>]<?php echo ($vo['product_name']); ?>">
            [<?php echo ($vo['product_idcode']); ?>]<?php echo (msubstrn($vo['product_name'],0,6)); ?>
        </a>
    <?php elseif($v['field'] == 'customer_id'): ?>
        <a href="<?php echo U('customer/view', 'id='.$vo['customer_id']);?>" target="_blank" title="[<?php echo ($vo['customer_idcode']); ?>]<?php echo ($vo['customer_name']); ?>">
            [<?php echo ($vo['customer_idcode']); ?>]<?php echo (msubstrn($vo['customer_name'],0,6)); ?>
        </a>
    <?php elseif($v['field'] == 'staff_id'): ?>
        <a href="<?php echo U('staff/view', 'id='.$vo['staff_id']);?>" target="_blank" title="[<?php echo ($vo['staff_idcode']); ?>]<?php echo ($vo['staff_name']); ?>">
            [<?php echo ($vo['staff_idcode']); ?>]<?php echo (msubstrn($vo['staff_name'],0,6)); ?>
        </a>
    <?php elseif($v['field'] == 'create_time'): ?>
        <?php echo (todate($vo[$v['field']] ,'Y-m-d H:i')); ?>
    <?php elseif(($v['field'] == 'idcode') or ($v['field'] == 'orderid')): ?>
        <a href="<?php echo U($v['model'].'/view', 'id='.$vo[$v['model'].'_id']);?>" target="_blank">
            <?php echo (idcode_format($vo,$v)); ?>
        </a>
    <?php elseif(($v['model'] == 'commiss') && ($v['field'] == 'owner_role_id')): ?>
        <?php if($vo[$v['field']]): if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
            <?php echo (role_html($vo[$v['field']])); endif; endif; ?>
    <?php elseif($v['field'] == 'queue_describe'): ?>
        <a href="<?php echo U('train/view', 'id='.$vo['train_id']);?>" target="_blank"   title="<?php echo ($vo["queue_describe"]); ?>">
            <?php echo (cutstring($vo['queue_describe'],20)); ?>
        </a>
    <?php elseif($v['field'] == 'queue_branch_id'): ?>
        <?php if($vo['queue_branch_id_show'] ): echo ($vo['queue_branch_id_show']); ?>
        <?php else: ?>
            <span style="color: #08c"><?php echo ($vo['branch']['name']); ?></span><?php endif; ?>
    <?php elseif($v['field'] == 'train_id'): ?>
        <a href="<?php echo U('train/view', 'id='.$vo['train_id']);?>" target="_blank">
            <?php echo ($vo['train_name']); ?>
        </a>
    <?php elseif($v['field'] == 'dorm_id'): ?>
        <a href="<?php echo U('dorm/view', 'id='.$vo['dorm_id']);?>" target="_blank">
            <?php echo ($vo['dorm_name']); ?>
        </a>
    <?php elseif($v['field'] == 'currier_id'): ?>
        <a href="<?php echo U('currier/view', 'id='.$vo['currier_id']);?>" target="_blank">
            [<?php echo ($vo['currier_idcode']); ?>]<?php echo ($vo['currier_name']); ?>
        </a>
    <?php elseif(($v['field'] == 'queue_pos')): ?>
        <?php if(($vo['workstate_id'] == '排岗') and ($vo['queue_pos'] != '9999999')): echo ($vo[$v['field']]); endif; ?>
    <?php elseif(($v['form_type'] == 'channnel_box') or ($v['form_type'] == 'channel_role_model_box')): ?>
        <?php if(($vo[$v['field']] != 0)): ?><a href="<?php echo U('channnel/view', 'id='.$vo['channnel_id']);?>" target="_blank">
            <?php echo (channel_show_html($vo[$v['field']])); ?>
        </a><?php endif; ?>
    <?php elseif(($v['form_type'] == 'channel_role_id_box')): ?>
        <?php if(($vo[$v['field']] != 0)): echo channel_model_role_show_html($vo['channel_role_model'],$vo[$v['field']], true); endif; ?>
    <?php elseif($v['form_type'] == 'datetime'): ?>
        <?php if(($vo[$v['field']] != '0') and ($vo[$v['field']] != '')): if($v['is_showtime'] == '1'): echo (todate($vo[$v['field']] , 'Y-m-d  H:i')); ?>
            <?php else: ?>
                <?php echo (todate($vo[$v['field']] , 'Y-m-d')); endif; endif; ?>
    <?php elseif($v['form_type'] == 'mobile'): ?>
        <?php echo ($vo[$v['field']]); ?>&nbsp;
    <?php elseif($v['form_type'] == 'linkaddress'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
        <?php else: ?>
            <a href="<?php echo ($vo[$v['field']]); ?>" target="_blank"><?php echo ($vo[$v['field']]); ?></a><?php endif; ?>
    <?php elseif($v['form_type'] == 'berth'): ?>
        <?php echo (berth_show_html($vo[$v['field']])); ?>
    <?php elseif($v['field'] == 'status_id'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
            <?php echo ($vo['status_name']); endif; ?>
    <?php elseif($v['field'] == 'settle_state'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
        <?php else: ?>
        <?php echo ($vo['settle_status_name']); endif; ?>
    <?php elseif($v['field'] == 'shopkeeper_role_id'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
        <?php else: ?>
            <a class="role_info" rel="<?php echo ($vo['shopkeeper_role_id']); ?>" href="javascript:void(0)">
            <?php echo ($vo["shopkeeper"]["user_name"]); ?>
        </a><?php endif; ?>

    <?php elseif($v['form_type'] == 'user'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
            <?php echo (role_html($vo[$v['field']])); endif; ?>
     <?php elseif($v['field'] == 'queue_role_id'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
        <a class="role_info" rel="<?php echo ($vo['queue_role_id']); ?>" href="javascript:void(0)">
            <?php echo ($vo["queue"]["user_name"]); ?>
        </a><?php endif; ?>
    <?php elseif($v['field'] == 'category_id'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
        <?php echo ($vo["category_name"]); endif; ?>
    <?php elseif($v['field'] == 'queue_category_id'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
        <?php echo (proudct_category_map($vo["queue_category_id"])); endif; ?>
    <?php elseif($v['field'] == 'serve_id'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
        <?php else: ?>
            <a href="<?php echo U('serve/view', 'id='.$vo['serve_id']);?>" target="_blank">
                <?php echo ($vo['serve_idcode']); ?> - <?php echo ($vo['serve_name']); ?>
            </a><?php endif; ?>
    <?php elseif($v['form_type'] == 'se_box'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
        <?php else: ?>
            <?php switch($vo["corre"]): case "product": ?>雇员<?php break;?>
                <?php case "customer": ?>客户<?php break; endswitch; endif; ?>
    <?php elseif($v['form_type'] == 'order_classify'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
        <?php else: ?>
            <?php switch($vo["order_classify"]): case "product": ?>培训销售<?php break;?>
                <?php case "customer": ?>客户销售<?php break;?>
                <?php case "league": ?>加盟意向<?php break;?>
                <?php case "other": ?>其他<?php break;?>
                <?php default: ?>无<?php endswitch; endif; ?>
    <?php elseif(in_array($v['form_type'], array('cultivate_cert_state_box','cultivate_examine_state_box'))): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
        <?php echo (format_cultivate_status($vo[$v['field']])); endif; ?>
    <?php elseif($v['field'] == 'workstate_id'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
        <?php echo ($vo['workstate_name']); endif; ?>
    <?php elseif(($v['field'] == 'branch_id')): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
        <?php if($vo[$v['field']]): echo ($vo['branch']['name']); ?>
        <?php else: ?>
            公司总部<?php endif; endif; ?>
    <?php elseif($v['field'] == 'department_id'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
        <?php if($vo['department_id']): echo (department_html($vo['department_id'])); endif; endif; ?>
    <?php elseif($v['field'] == 'service_product'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
            <?php echo market_service_product_html($vo['market_id'],$vo['service_product']);; endif; ?>
    <?php elseif($v['field'] == 'position_id'): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
            <?php else: ?>
        <?php if($vo['position_id']): echo (position_html($vo['position_id'])); endif; endif; ?>
    <?php elseif(is_field_show($v['form_type'])): ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
        <?php else: ?>
            <?php echo format_field_show_html($v['model'], $v['field'], $vo); endif; ?>
    <?php else: ?>
        <?php if(is_hide_field($vo[$v['field']])): echo ($vo[$v['field']]); ?>
         <?php else: ?>
            <?php echo ($vo[$v['field']]); endif; endif; ?>
    </span>
</td><?php endforeach; endif; else: echo "" ;endif; ?>
								<td   style="text-align: center">
                                    <a href="<?php echo U('customer/view', 'id='.$vo['customer_id']);?>" title="查看详情">
                                        <i class="icon-th-large"></i>
                                    </a>
								</td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>

<script>

    $("#add_group").click(function(){
        var dialog = art.dialog({
            id: 'N3690',
            title: "选择组",
            lock:true,
            fixed:true,
            window: 'top',

            ok: function () {
                var select_module = [];
                var selgroup = $('input:radio[name=<?php echo ($module_name); ?>_group_id]:checked').val();
                $("input[class='check_list']").each(function() {
                    if (true == $(this).prop("checked")) {
                        select_module.push($(this).val());
                    }
                });

                if (select_module.length == 0) {
                    alert("请选择项目");
                } else {
                    $.ajax({
                        type: 'get',
                        url: "<?php echo U($module_name.'/addgroupstance');?>",
                        data:{
                            module_group_id:selgroup,
                            module_id:select_module
                        },
                        success: function (data) {
                            if (data) {
                                art.dialog.tips('设置成功！已经保存在服务器');

                            }
                        },
                        dataType:'json'
                    });
                }
            },
            cancel:true
        });
        $.ajax({
            url: '<?php echo U($module_name."/allgroupdialog");?>',
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });
    });

    $("#remove_group").click(function(){
        art.dialog.confirm('确实要从这个组移出吗?', function () {
            var select_module = [];
            $("input[class='check_list']").each(function() {
                if (true == $(this).prop("checked")) {
                    select_module.push($(this).val());
                }
            });
            $.ajax({
                type: 'post',
                url: "<?php echo U($module_name.'/removegroupstance');?>",
                data:{
                    module_group_id:"<?php echo ($_GET['module_group_id']); ?>",
                    module_id:select_module
                },
                beforeSend:function(XMLHttpRequest){
                    art.dialog.tips('数据正在提交..');
                },
                success: function (data) {
                    if (data) {
                        window.location.reload();
                    }
                },
                complete:function(XMLHttpRequest, textStatus){
                    art.dialog.tips('成功！已经保存在服务器');
                },
                dataType:'json'
            });
        });
    });

</script>


<a href="" id="excelexport_link_hide" style="visibility: hidden" target="_blank"><span id="excelexport_link_hide_span">EXPORT</span></a>
<script>
    $("#excelExport").click(function(){
//        art.dialog.confirm("导出数据过多会的话， 会等待相当长时间", function(){
//            var srcact = $("#act").val();
//            $("#act").val("preexport");
//            var url = '<?php echo U("");?>&<?php echo FP($parameter, "export=excel");?>&' + $('#searchForm').formSerialize();
//            $("#excelexport_link_hide").attr("href", url);
//            $("#excelexport_link_hide_span").click();
//            $("#act").val(srcact);
//        });

        art.dialog.confirm("导出数据过多会的话， 会分开多个连接下载", function(){
            var dialog = art.dialog({
                id: 'N3690',
                title: "导出EXCEL",
                lock:true,
                ok: function () {
                    ExportAll();
                },
                cancel:true,
            });
            var url = '<?php echo U("");?>&<?php echo FP($parameter, "p=-1&export=excel");?>&' + $('#searchForm').formSerialize();
            $.ajax({
                url: url,
                success: function (data) {
                    dialog.content(data);
                },
                cache: false
            });
        });

    });
    $("#excelImport").click(function(){
        var url = '<?php echo U($module_name."/excelimport");?>&<?php echo FP($parameter);?>';
        art.dialog.open(url, {
            id: 'N3690',
            title: "导出EXCEL",
            lock:true,
            width:"700px",
            ok: false,
            cancel: true
        });
    });
</script>


<script>
    function advsearch(url) {
        var dialog = art.dialog({
            id: 'N3690',
            title: "高级搜索",
            lock:true,
            ok: function () {
                $("#adv_search_form").submit();
            },
            cancel:true,
        });
        dialog.size('20em', 60);
        $.ajax({
            url: url,
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });
    }
</script>


<script>
    $("#advanced_dosearch").click(function(){
        advsearch('<?php echo U("customer/search");?>');
    });
</script>

<script type="text/javascript">

function changeContent(){
	a = $("#select1  option:selected").val();
	window.location.href="<?php echo U('customer/index', 'by=');?>"+a;
}
$(function(){

    $("#check_all").click(function(){
		$("input[class='check_list']").prop('checked', $(this).prop("checked"));
	});

	$('#delete').click(function(){
		if(confirm('<?php echo L('Confirm_to_delete');?>')){
            $("#form1").attr('action', '<?php echo U("customer/delete");?>');
            $("#form1").submit();
		}
	});
	$('#remove').click(function(){
		if(confirm('<?php echo L('CONFIRMED_IN_THE_CUSTOMER_POOL');?>')){
			$("#form1").attr('action', '<?php echo U("customer/remove");?>');
			$('#operating_type').attr('value', 'remove');
			$("#form1").submit();
		}
	});

    function showSmsDialog(id_array) {
        var customer_ids = id_array.join(",");
        $("#sms_open_url_a").attr("href", "<?php echo U('sms/sendDialog', 'by=model&model=customer&ids=');?>"+customer_ids);
        $("#sms_open_url_span").click();
    }

	$("#check_send").click(function(){
		var id_array = new Array();
		$("input[class='check_list']:checked").each(function(){  
			id_array.push($(this).val());
		});
        if (id_array.length == 0) {
            alert("没有选择任何客户");
        } else {
            showSmsDialog(id_array);
        }
	});

	$("#page_send").click(function(){
		var id_array = new Array();
		$("input[class='check_list']").each(function(){
			id_array.push($(this).val());
		});
        showSmsDialog(id_array);
	});

    $("#all_send").click(function(){
        $("#sms_open_url_a").attr("href", "<?php echo U('sms/sendDialog', 'by=model&model=customer');?>");
        $("#sms_open_url_span").click();
    });

});
</script>

<object id="rcard" type="application/x-rcard"  width="0" height="0">
    <param name="onload" value="pluginLoaded" />
</object>
<script>
    $(function(){
        function person_search(data, cardinfo) {
            if (data) {
                if (data.length == 1) {
                    customer_url = "<?php echo U('customer/view','id=');?>" + data[0].customer_id;
                } else {
                    customer_url = "<?php echo U('customer/index','field=cardid&condition=is&act=search&search=');?>" + cardinfo.cardid;
                }
                window.location.href=customer_url;
            } else {
                var param = "&rcard";
                window.location.href="<?php echo U('customer/add');?>" + param;
            }
        }

        function person(p) {
            if (p) {
                var cardinfo = {
                    name: p.name,
                    address: p.address,
                    cardid: p.cardid,
                    police: p.police,
                    pic: p.pic,
                    birthday: p.birthday,
                    nation: p.nation,
                    sex: p.sex,
                    validstart: p.validstart,
                    validend: p.validend,
                    nationcode: p.nationcode,
                    msg: p.msg
                };
                $.ajax({
                    type: 'get',
                    url: "<?php echo U('customer/changeContent', 'field=cardid&search=');?>" + cardinfo.cardid,
                    async: false,
                    success: function (data) {
                        if (data && data.data) {
                            person_search(data.data.list, cardinfo);
                        }
                    },
                    dataType:'json'
                });
            }
        }

        var rcard = document.getElementById('rcard');
        if (rcard.valid) {
            if (rcard.attachEvent) {
                rcard.attachEvent("onperson", person);
            } else {
                rcard.addEventListener("person", person, false);
            }
            rcard.open();
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