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
    .knowledgecate li.active a{
        border: 1px solid #eee;
    }
</style>
<script type="text/javascript">
    $(function(){

        $("#check_all").click(function(){
            $("input[class='check_list']").prop('checked', $(this).prop("checked"));
        });
        $('#delete').click(function(){
            art.dialog.confirm('确定删除吗?', function () {
                <?php if($_SESSION['admin']== 1 and $_GET['by']== 'deleted'): ?>$("#form1").attr('action', '<?php echo U('account/completedelete');?>&t=<?php echo ($t); ?>');
                    $("#form1").submit();
                <?php else: ?>
                    $("#form1").attr('action', '<?php echo U('account/delete');?>&t=<?php echo ($t); ?>');
                    $("#form1").submit();<?php endif; ?>
            });

        });
    });

</script>

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

<style>
    .ui-autocomplete{
        width: 250px;
    }
    .ui-autocomplete li{
        width: 250px;
    }
    .ui-autocomplete li .short-search-label{
        font-size: 16px;
    }
    .ui-autocomplete li .short-search-desc{
        font-size: 11px;
        color: #497F7F;
    }
    div.view {font-size:14px;background:#F4F4F4;padding:13px 5px 3px 5px;color:#000000}
    div.view a  {font-size:12px;color:#000000;padding-right:2px}
    div.view b  {color:#000000}
</style>
<div class="container">
    <div class="page-header" style="border:none; font-size:14px;">
        <ul class="nav nav-tabs">
    <li <?php if(in_array($t,array('market', 'cultivate'))): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/market','t=market');?>">
        <img src="__PUBLIC__/img/shoukuandan.png"/> &nbsp;
        收入确认
    </a>
    </li>

    <li <?php if(($t == 'inernal')): ?>class="active"<?php endif; ?>>
    <a  href="<?php echo U('account/inernal','t=inernal');?>">
        <img src="__PUBLIC__/img/yingshoukuan.png"/>&nbsp;
        <?php echo L('INTERNAL_ACCOUNT');?>
    </a>
    </li>
    <li <?php if($t == 'customer'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/customer','t=customer');?>">
        <img src="__PUBLIC__/img/customer_icon.png"/> &nbsp;
        <?php echo L('CUSTOMER_ACCOUNT');?>
    </a>
    </li>
    <li <?php if($t == 'product'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/product','t=product');?>">
        <img src="__PUBLIC__/img/shoukuandan.png"/> &nbsp;
        <?php echo L('PRODUCT_ACCOUNT');?>
    </a>
    </li>
    <li <?php if($t == 'staff'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/staff','t=staff');?>">
        <img src="__PUBLIC__/img/shoukuandan.png"/> &nbsp;
        员工账户
    </a>
    </li>

    <li <?php if($t == 'flow'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/flow','t=flow');?>">
        <img src="__PUBLIC__/img/tongji.png"/> &nbsp;
        公司流水
    </a>
    </li>

    <li <?php if((ACTION_NAME) == "logger"): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/logger');?>">
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

    <div class="row-fluid" style="padding-top:10px">
        <div class="span12">
            <div class="pull-left">
                <a id="delete"  class="btn btn-mini btn-danger" style="margin-right: 8px;">
                    <i class="icon-remove"></i>&nbsp;删除
                </a>
            </div>
            <div class="pull-left">
                <form class="form-inline" id="searchForm"  action="<?php echo U('');?>" method="get">
    <ul class="nav pull-left">
        <?php if(session('user_id') == '1'): ?><li class="pull-left">
                <?php echo league_select_html("bylea", $league['league_id']);?>&nbsp;&nbsp;
                <script>
                    $(function() {
                        $("#bylea").change(do_search);
                    });
                </script>
            </li><?php endif; ?>
        <li class="pull-left">
            <?php echo branch_select_html("bybr", $branch, true, true, null,$league['league_id'] );?>&nbsp;&nbsp;
            <script>
                $(function() {
                    $("#bybr").change(do_search);
                });
            </script>
        </li>
        <li class="pull-left">
            <select style="width:auto;min-width: 90px" name="t" id="index_type">
                <option value="market" href="<?php echo U('Account/market', 't=market');?>">客户服务</option>
                <option  value="cultivate"  href="<?php echo U('Account/cultivate', 't=cultivate');?>">新培训</option>
            </select>&nbsp;&nbsp;
        </li>
        <li class="pull-left">
            <select style="width:auto;min-width: 90px" name="field" id="field" onchange="changeCondition()">
                <option class="all" value="all">任意字段</option>
                <option class="role" value="creator_role_id">创建人</option>
                <option class="role" value="verify_role_id">确认人</option>
                <option class="role" value="related_owner_role_id">订单责任人</option>
                <option class="floatnumber" value="money" rel="account">金额</option>
                <option class="textarea" value="description" rel="account">描述</option>
                <option class="text" value="receipt_number">收据号</option>
            </select>&nbsp;&nbsp;
        </li>
        <li id="conditionContent" class="pull-left">
            <select id="condition" style="width:auto"   name="all[condition]" onchange="changeSearch()">
                <option value="contains"><?php echo L('CONTAINS');?></option>
                <option value="not_contain"><?php echo L('NOT_CONTAIN');?></option>
                <option value="is"><?php echo L('IS');?></option>
                <option value="isnot"><?php echo L('ISNOT');?></option>
                <option value="start_with"><?php echo L('START_WITH');?></option>
                <option value="end_with"><?php echo L('END_WITH');?></option>
                <option value="is_empty"><?php echo L('IS_EMPTY');?></option>
                <option value="is_not_empty"><?php echo L('IS_NOT_EMPTY');?></option>
            </select>&nbsp;&nbsp;
        </li>
        <li id="searchContent" class="pull-left">
            <input id="search" type="text" class="input-medium search-query"  name="all[value]"/>&nbsp;
        </li>
        <li class="pull-left">
            <?php echo L('FROM');?><input style="width:90px;background-color:white;cursor:pointer;" readonly type="text" id="start_time" name="start_time" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="Wdate" value="<?php echo ($_GET['start_time']); ?>"/>
            <?php echo L('TO');?><input style="width:90px;background-color:white;cursor:pointer;" readonly type="text" id="end_time" onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})" name="end_time" class="Wdate" value="<?php echo ($_GET['end_time']); ?>" />&nbsp;
            <select id="bytime" style="width:auto" name="bytime">
                <option value="">全部</option>
                <option value="today">今日</option>
                <option value="month">本月</option>
                <option value="year">本年</option>
            </select>&nbsp;&nbsp;
            <select id="bysub" style="width:auto" name="bysub">
                <option value="">提交</option>
                <option value="c">确认</option>
                <option value="p">支付</option>
            </select>&nbsp;&nbsp;

        </li>

        <li class="pull-left">
            <input type="hidden" name="m" value="account"/>
            <input type="hidden" name="t" value="<?php echo ($t); ?>"/>
            <input type="hidden" name="a" value="<?php echo ($t); ?>"/>
            <input type="hidden" name="dire" value="<?php echo ($dire); ?>"/>
            <input type="hidden" name="act" id="act" value="search"/>
            <?php if($_GET['by']!= null): ?><input type="hidden" name="by" value="<?php echo ($_GET['by']); ?>"/><?php endif; ?>
            <?php if($_GET['type']!= null): ?><input type="hidden" name="type" value="<?php echo ($_GET['type']); ?>"/><?php endif; ?>
            <?php if($_GET['payway']!= null): ?><input type="hidden" name="payway" value="<?php echo ($_GET['payway']); ?>"/><?php endif; ?>
            <?php if($_GET['catid']!= null): ?><input type="hidden" name="catid" value="<?php echo ($_GET['catid']); ?>"/><?php endif; ?>
            <?php if($_GET['pvs']!= null): ?><input type="hidden" name="pvs" value="<?php echo ($_GET['pvs']); ?>"/><?php endif; ?>
            <?php if($_GET['lia']!= null): ?><input type="hidden" name="lia" value="<?php echo ($_GET['lia']); ?>"/><?php endif; ?>
            <?php echo search_form_default_param($parameter, $debar_search_field);?>

            <div class="btn-group" role="group">
                <button type="button" class="btn btn-mini  btn-default" id="dosearch"> <img src="__PUBLIC__/img/search.png"/></button>
                <button type="button" id="clean_search" class="btn btn-mini">全部</button>&nbsp;
            </div>
            <?php echo search_form_default_param($parameter, $debar_search_field);?>
            <script>
                $("#clean_search").click(function(){
                    var search_field_name = $("#search").attr("name");
                    var search_field_condition = $("#condition").attr("name");
                    $("#search").val('');$("input[name='"+search_field_name+"']").val('');
                    $("#condition").val('');$("input[name='"+search_field_condition+"']").val('');
                    $("#field").val('');
                    $("#wsbt").val('');
                    $("#wset").val('');
                    $("#nobd").val('');
                    $("#start_time").val('');
                    $("#end_time").val('');
                    $("#bytime").val('');
                    $("#searchForm").submit();
                });

            </script>
        </li>
    </ul>
</form>

<script>
    function do_search() {
        $("#act").val('search');
        $("#searchForm").submit();
    }
    $(function() {
        <?php if($_GET['field']!= null): ?>$("#field option[value='<?php echo ($search_field); ?>']").prop("selected", true);changeCondition();
        $("#condition option[value='<?php echo ($search_condition); ?>']").prop("selected", true);changeSearch();
        $("#search").prop('value', '<?php echo ($search_value); ?>');<?php endif; ?>
        $("#field").chosen({});
        $("#bytime option[value='<?php echo ($_GET['bytime']); ?>']").prop("selected", true);
        $("#bysub option[value='<?php echo ($_GET['bysub']); ?>']").prop("selected", true);

        $("#dosearch").click(do_search);

        $("#index_type option[value='<?php echo ($_GET['t']); ?>']").prop("selected", true);
        $("#index_type").change(function(){
            window.location.replace($("#index_type option:selected").attr("href"));
        });
        $("#search").focus();
    });

</script>
            </div>
            <div class="pull-right">
                <div class="btn-group">
                    <button class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-wrench"> </i>&nbsp;
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu  pull-right">

                        <li>
                            <?php if($per_export): ?><a href="javascript:void(0);" id="excelExport" class="link">
                                    <i class="icon-download"></i>导出到Excel
                                </a>
                                <?php else: ?>
                                <a href="javascript:void(0);"  style="color: #D0D0D0;cursor: no-drop;"  class="link">
                                    <i class="icon-download"></i>导出到Excel
                                </a><?php endif; ?>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <form id="form1" action="" method="post">
                <table class="table table-hover table-striped table_thead_fixed" id="listtable">
                    <thead>
                    <tr>
                        <th>
                            <input class="check_all" id="check_all" type="checkbox" />
                        </th>
                        <th>
                            <?php if($_GET['asc_order'] == 'money'): ?><a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order=money&t='.$t);?>">
                                    金额 &nbsp;<img src="__PUBLIC__/img/arrow_up.png">
                                </a>
                                <?php elseif($_GET['desc_order'] == 'money'): ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=money&desc_order=&t='.$t);?>">
                                    金额&nbsp;<img src="__PUBLIC__/img/arrow_down.png">
                                </a>
                                <?php else: ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'desc_order=money&asc_order=&t='.$t);?>">
                                    金额
                                </a><?php endif; ?>
                            <a onmouseover="mopen('table_header_text_money')" field="money" onmouseout="mclosetime()" number_col_filter="<?php echo U('Berth/list_col_filter_select','type=number&field_id=82&field=money');?>" number_col_filter_call="filter_berth_text_field"  href="javascript:void(0);">
                                <i class="icon-filter"></i>
                            </a>
                            <?php if(($_GET['money']['value'] != '')): ?><br/>
                                <span style="font-weight:normal"><?php echo format_table_head_text_col('money', $parameter);?></span><?php endif; ?>
                        </th>
                        <th>相关方</th>
                        <th class="flter_table_header">
                            支付方式
                            <?php if(($_GET['payway']!= '')): ?><span style="font-weight:normal">[<?php echo ($_GET['payway']); ?>]</span><?php endif; ?>

                            <a onmouseover="mopen('payway_list')" onmouseout="mclosetime()" href="javascript:void(0);">
                                <i class="icon-filter"></i>
                            </a>
                            <div  id="payway_list" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                                <a <?php if(($_GET['payway']== '')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'payway=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'payway=');?>"<?php endif; ?>>
                                全部
                                </a>
                                <?php if(is_array($payway_list)): $i = 0; $__LIST__ = $payway_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a <?php if(($_GET['payway']== $vo)): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'payway=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'payway='.$vo);?>"<?php endif; ?>>
                                    <?php echo ($vo); ?>
                                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </th>
                        <th>
                            <a>订单编号</a>
                            <a onmouseover="mopen('table_header_text_idcode')" field="idcode" onmouseout="mclosetime()" text_col_filter="<?php echo U('Berth/list_col_filter_select','type=text&field_id=637&field=idcode');?>" text_col_filter_call="filter_berth_text_field"  href="javascript:void(0);">
                                <i class="icon-filter"></i>
                            </a>
                            <?php if(($_GET['idcode']['value'] != '')): ?><br/>
                                <span style="font-weight:normal"><?php echo format_table_head_text_col('idcode', $parameter);?></span><?php endif; ?>
                        </th>
                        <th>
                            <?php if($_GET['asc_order'] == 'branch_id'): ?><a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order=branch_id&t='.$t);?>">
                                    门店 &nbsp;<img src="__PUBLIC__/img/arrow_up.png">
                                </a>
                                <?php elseif($_GET['desc_order'] == 'branch_id'): ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=branch_id&desc_order=&t='.$t);?>">
                                    门店&nbsp;<img src="__PUBLIC__/img/arrow_down.png">
                                </a>
                                <?php else: ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'desc_order=branch_id&asc_order=&t='.$t);?>">
                                    门店
                                </a><?php endif; ?>

                        </th>
                        <th class="flter_table_header">
                            服务类别
                            <?php if(($_GET['catid']!= '')): ?><span style="font-weight:normal">[<?php echo (proudct_category_map($_GET['catid'])); ?>]</span><?php endif; ?>

                            <a onmouseover="mopen('category_list')" onmouseout="mclosetime()" href="javascript:void(0);">
                                <i class="icon-filter"></i>
                            </a>

                            <div  id="category_list" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                                <a <?php if(($_GET['catid']== '')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'catid=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'catid=');?>"<?php endif; ?>>
                                全部
                                </a>
                                <?php if(is_array($category_list)): $i = 0; $__LIST__ = $category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a <?php if(($_GET['catid']== $vo['category_id'])): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'catid=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'catid='.$vo['category_id']);?>"<?php endif; ?>>
                                    <?php echo ($vo['name']); ?>
                                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </th>
                        <th class="flter_table_header">
                            <?php echo L('CREATOR_ROLE');?>
                            <?php if(($_GET['lia']!= '')): ?><span style="font-weight:normal">[<?php echo (lia_field_map($_GET['lia'])); ?>]</span><?php endif; ?>
                            <a onmouseover="mopen('creator_role')" onmouseout="mclosetime()" href="javascript:void(0);" >
                                <i class="icon-filter"></i>
                            </a>
                            <div  id="creator_role" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                                <a <?php if(($_GET['lia']== '')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'lia=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'lia=');?>"<?php endif; ?>>
                                全部
                                </a>
                                <a <?php if(($_GET['lia']== 'self')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'lia=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'lia=self');?>"<?php endif; ?>>
                                我负责的
                                </a>
                                <a <?php if(($_GET['lia']== 'foll')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'lia=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'lia=foll');?>"<?php endif; ?>>
                                下属负责的
                                </a>

                            </div>
                        </th>
                        <th class="flter_table_header">
                            审核状态
                            <?php if(($_GET['pvs']!= '')): ?><span style="font-weight:normal">[<?php echo (payment_verify_map($_GET['pvs'])); ?>]</span><?php endif; ?>
                            <a onmouseover="mopen('payment_verify')" onmouseout="mclosetime()" href="javascript:void(0);">
                                <i class="icon-filter"></i>
                            </a>
                            <div  id="payment_verify" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                                <a <?php if(($_GET['pvs']== '')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'pvs=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'pvs=');?>"<?php endif; ?>>
                                全部
                                </a>
                                <a <?php if(($_GET['pvs']== '0')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'pvs=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'pvs=0');?>"<?php endif; ?>>
                                待确认
                                </a>
                                <a <?php if(($_GET['pvs']== '1')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'pvs=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'pvs=1');?>"<?php endif; ?>>
                                已确认
                                </a>
                                <a <?php if(($_GET['pvs']== '2')): ?>href="<?php echo U('');?>&<?php echo FP($parameter, 'pvs=');?>" class="active"<?php else: ?> href="<?php echo U('');?>&<?php echo FP($parameter, 'pvs=2');?>"<?php endif; ?>>
                                无法确认
                                </a>
                            </div>
                        </th>
                        <th>
                            收据号
                            <a onmouseover="mopen('table_header_text_receipt_number')" field="receipt_number" onmouseout="mclosetime()" text_col_filter="<?php echo U('Berth/list_col_filter_select','type=text&field_id=12&field=receipt_number');?>" text_col_filter_call="filter_berth_text_field"  href="javascript:void(0);">
                                <i class="icon-filter"></i>
                            </a>
                            <?php if(($_GET['receipt_number']['value'] != '')): ?><br/>
                                <span style="font-weight:normal"><?php echo format_table_head_text_col('receipt_number', $parameter);?></span><?php endif; ?>
                        </th>
                        <th width="120px">
                            <?php if($_GET['asc_order'] == 'verify_time'): ?><a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order=verify_time&t='.$t);?>">
                                    确认时间 &nbsp;<img src="__PUBLIC__/img/arrow_up.png">
                                </a>
                                <?php elseif($_GET['desc_order'] == 'verify_time'): ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=verify_time&desc_order=&t='.$t);?>">
                                    确认时间&nbsp;<img src="__PUBLIC__/img/arrow_down.png">
                                </a>
                                <?php else: ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'desc_order=verify_time&asc_order=&t='.$t);?>">
                                    确认时间
                                </a><?php endif; ?>
                            <a onmouseover="mopen('table_header_datetime_verify_time')" field="verify_time" onmouseout="mclosetime()" date_col_filter="<?php echo U('Berth/list_col_filter_select','type=date&field_id=12&field=verify_time');?>" date_col_filter_call="filter_berth_date_field"  href="javascript:void(0);">
                                <i class="icon-filter"></i>
                            </a>
                            <?php if(($_GET['verify_time']['value'] != '')): ?><br/>
                                <span style="font-weight:normal"><?php echo format_table_head_date_col('verify_time', $parameter);?></span><?php endif; ?>
                        </th>

                        <th width="120px">
                            <?php if($_GET['asc_order'] == 'create_time'): ?><a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order=create_time&t='.$t);?>">
                                    支付时间 &nbsp;<img src="__PUBLIC__/img/arrow_up.png">
                                </a>
                                <?php elseif($_GET['desc_order'] == 'create_time'): ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=create_time&desc_order=&t='.$t);?>">
                                    支付时间&nbsp;<img src="__PUBLIC__/img/arrow_down.png">
                                </a>
                                <?php else: ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'desc_order=create_time&asc_order=&t='.$t);?>">
                                    支付时间
                                </a><?php endif; ?>
                            <a onmouseover="mopen('table_header_datetime_create_time')" field="create_time" onmouseout="mclosetime()" date_col_filter="<?php echo U('Berth/list_col_filter_select','type=date&field_id=12&field=create_time');?>" date_col_filter_call="filter_berth_date_field"  href="javascript:void(0);">
                                <i class="icon-filter"></i>
                            </a>
                            <?php if(($_GET['create_time']['value'] != '')): ?><br/>
                                <span style="font-weight:normal"><?php echo format_table_head_date_col('create_time', $parameter);?></span><?php endif; ?>
                        </th>

                        <th width="120px">
                            <?php if($_GET['asc_order'] == 'create_time'): ?><a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=&desc_order=create_time&t='.$t);?>">
                                    提交时间 &nbsp;<img src="__PUBLIC__/img/arrow_up.png">
                                </a>
                                <?php elseif($_GET['desc_order'] == 'create_time'): ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'asc_order=create_time&desc_order=&t='.$t);?>">
                                    提交时间&nbsp;<img src="__PUBLIC__/img/arrow_down.png">
                                </a>
                                <?php else: ?>
                                <a href="<?php echo U('');?>&<?php echo FP($parameter, 'desc_order=create_time&asc_order=&t='.$t);?>">
                                    提交时间
                                </a><?php endif; ?>
                            <a onmouseover="mopen('table_header_datetime_create_time')" field="create_time" onmouseout="mclosetime()" date_col_filter="<?php echo U('Berth/list_col_filter_select','type=date&field_id=12&field=create_time');?>" date_col_filter_call="filter_berth_date_field"  href="javascript:void(0);">
                                <i class="icon-filter"></i>
                            </a>
                        </th>

                        <th width="40px"  style="text-align: center"><?php echo L('OPERATING');?></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr style="background: #029BE2;color: #fff;font-size: 12px;">
                        <td colspan="14">

                            <?php if(isset($shouru_money)): ?>当前页金额总计：<?php echo (number_format($shouru_money,2)); ?>（元）<?php endif; ?>
                            <?php if(isset($shouru_sum_money)): ?>金额总计：<?php echo (number_format($shouru_sum_money,2)); ?>（元）<?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="13">
                            <?php echo ($page); ?>
                        </td>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td>
                                <?php if(is_ious($vo['clause_type_id'])): ?><input type="checkbox" disabled  data-toggle="tooltip" title="白条相关账目无法删除, 请删除对应白条"/>
                                    <?php else: ?>
                                    <input type="checkbox" class="check_list" name="account_id[]" value="<?php echo ($vo["account_id"]); ?>"/><?php endif; ?>
                            </td>
                            <td>
                                <?php echo ($vo["money"]); ?>
                            </td>
                            <td>
                                <?php echo ($vo["market"]["corre_info"]); ?>
                            </td>
                            <td>
                                <?php echo ($vo["payway"]); ?>
                            </td>
                            <td>
                                <a href="<?php echo U('market/view','id='.$vo['market']['market_id']);?>" target="_blank">
                                    <?php echo ($vo["market_idcode"]); ?>
                                </a>
                                <?php if($vo['owner_role_id'] != 0): ?>[<?php echo (role_html($vo['owner_role_id'])); ?>]<?php endif; ?>

                            </td>
                            <td>
                                <?php echo ($vo["branch_name"]); ?>
                            </td>
                            <td>
                                <?php echo ($vo["category_name"]); ?>
                            </td>
                            <td>
                                <a class="role_info" rel="<?php echo ($vo['creator_role_id']); ?>" href="javascript:void(0)">
                                    <?php echo ($vo['creator_name']); ?>
                                </a>
                            </td>
                            <td>
                                <?php echo ($vo['payment_verify_show']); ?>
                                <?php if($vo['verify_role_id'] != 0): ?>[<?php echo (role_html($vo['verify_role_id'])); ?>]<?php endif; ?>
                            </td>
                            <th>
                                <?php echo ($vo['receipt_number']); ?>
                            </th>
                            <td>
                                <?php if($vo['verify_time'] > 0): echo ($vo['verify_time_show']); endif; ?>
                            </td>
                            <td>
                                <?php if($vo['payment_time'] > 0): echo ($vo['payment_time_show']); endif; ?>
                            </td>
                            <td>
                                <?php if($vo['create_time'] > 0): echo (date("Y-m-d H:i",$vo['create_time'])); endif; ?>
                            </td>
                            <td  style="text-align: center">
                                <a href="<?php echo U('account/view','id='.$vo['account_id']);?>&t=<?php echo ($t); ?>">
                                    <i class="icon-th-large"></i>
                                </a>

                                <?php if(($vo['payment_verify'] == '0') and $vo['per_verify']): ?><a href="javascript:void(0);" role="button" ref="<?php echo ($vo['account_id']); ?>" onclick="change_payment_verify(<?php echo ($vo['account_id']); ?>);"><i class=" icon-warning-sign"></i></a><?php endif; ?>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </form>
        </div> <!-- End #tab1 -->
    </div> <!-- End #main-content -->
</div>
<script>

    function change_payment_verify(account_id) {
        art.dialog.open('<?php echo U("account/change_payment_verify", "t=".$type."&id=");?>' + account_id, {
            id: 'change_payment_verify_dialog',
            title: "审核账目",
            lock:true,
            fixed:true,
            window: 'top',
            ok: function () {
                var tipdialog = show_lock_tips('提交中...');
                var form = $(this.iframe.contentWindow.document).find('#change_payment_verify_form');
                $(form).ajaxSubmit({
                    success: function() {
                        tipdialog.close();
                        art.dialog.tips("状态修改成功");
                        window.location.reload();
                    }
                });
                this.close();
                return false;
            },
            cancel: true
        });
    }
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