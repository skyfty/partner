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
    <div class="page-header">
        <h4><?php echo L('SYSTEM_SETTINGS');?></h4>
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

    <div class="row-fluid">
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
        <form action="<?php echo U('account/accounttype_delete');?>" method="post">
            <div class="tabbable span10" style="padding-left:30px">
                <ul class="nav nav-tabs">
    <li  <?php if($_GET['assort'] == 'basic' || $_GET['assort'] == '' && $_GET['related_model'] == ''): ?>class="active"<?php endif; ?> >
    <a href="<?php echo U('account/accounttype', 'model=account&assort=basic');?>">全部账目</a>
    </li>
    <li <?php if($_GET['assort'] == 'cash'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/accounttype', 'model=account&assort=cash');?>">公司提现账目
    </a>

    <li <?php if($_GET['related_model'] == 'trade'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/accounttype', 'model=account&related_model=trade');?>">产品订单账目
    </a>
    <li <?php if($_GET['related_model'] == 'market'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/accounttype', 'model=account&related_model=market');?>">客户服务账目
    </a>
    <li <?php if($_GET['related_model'] == 'cultivate'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/accounttype', 'model=account&related_model=cultivate');?>">培训账目
    </a>
    <li <?php if($_GET['related_model'] == 'other'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('account/accounttype', 'model=account&related_model=other');?>">其他账目
    </a>
</ul>
            </div>
            <div class="span10">
                <p>
                <div class="bulk-actions align-left">
                    <input type="submit" class="btn del_confirm" value="<?php echo L('DELETE');?>"/>
                    <button class="btn" type="button" id="sort_btn">
                        <span class="icon-fildel_confirme"></span>&nbsp;<?php echo L('SAVE_ORDER');?>
                    </button>

                    <div class="pull-right">
                        <a class="btn btn-primary" id="add_accounttype" onclick="return accounttype_add();">
                            <?php echo L('ADD_ACCOUNT_CATEGORIES');?>
                        </a>
                    </div>
                </div>
                </p>
            </div>
            <div class="span10">
                <table class="table table-hover table-striped table_thead_fixed" width="95%" border="0" cellspacing="1" cellpadding="0">
                    <thead>
                    <tr>
                        <th width="10%">
                            <input type="checkbox" name="check_all" id="check_all" class="check_all"/> &nbsp;
                        </th>
                        <th width="15%"><?php echo L('CLASSIFICATION_OF');?></th>
                        <th width="7%">类别</th>
                        <th width="10%">类型</th>
                        <th width="10%">关联模型</th>
                        <th width="10%">相关方</th>
                        <th width="10%">相关方类型</th>
                        <th><?php echo L('DESCRIPTION');?></th>
                        <th width="10%">状态</th>
                        <th width="5%"><?php echo L('OPERATION');?></th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <?php echo ($page); ?>
                        </td>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php if(is_array($accounttype_list)): $i = 0; $__LIST__ = $accounttype_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td>
                                <input type="checkbox" class="list" order_id="<?php echo ($vo["order_id"]); ?>"  name="accounttype_list[]" value="<?php echo ($vo["type_id"]); ?>"/>
                            </td>
                            <td><?php echo ($vo["name"]); ?></td>
                            <td>
                                <?php switch($vo["module_id"]): case "product": ?>雇员<?php break;?>
                                    <?php case "customer": ?>客户<?php break;?>
                                    <?php case "staff": ?>员工<?php break;?>
                                    <?php case "flow": ?>流水<?php break;?>
                                    <?php case "market": ?>客户服务<?php break;?>
                                    <?php default: ?>公司<?php endswitch;?>
                            </td>
                            <td><?php echo ($vo["mold"]); ?></td>
                            <td>
                                <?php switch($vo["related_model"]): case "cultivate": ?>新培训<?php break;?>
                                    <?php case "trade": ?>产品订单<?php break;?>
                                    <?php case "market": ?>客户服务<?php break; endswitch;?>
                            </td>
                            <td>
                                <?php switch($vo["inflow_model"]): case "product": ?>雇员<?php break;?>
                                    <?php case "customer": ?>客户<?php break;?>
                                    <?php case "inernal": ?>{'league_name'|session}<?php break;?>
                                    <?php case "staff": ?>员工<?php break;?>
                                    <?php case "flow": ?>流水<?php break;?>
                                    <?php case "market": ?>客户服务<?php break;?>
                                    <?php case "cultivate": ?>培训<?php break; endswitch;?>
                            </td>
                            <td>
                                <?php echo ($vo["inflow_model_type"]["name"]); ?>
                            </td>

                            <td>
                                <?php echo ($vo["description"]); ?>
                            </td>
                            <td>
                                <a href="<?php echo U('account/accounttype_state', 'id='.$vo['type_id'].'&is_show='.$vo['is_show']);?>"><?php if($vo['is_show'] == 1): ?>显示<?php else: ?>隐藏<?php endif; ?></a>
                            </td>
                            <td>
                                <a href="<?php echo U('account/accounttype_edit', 'id='.$vo['type_id']);?>" onclick="return accounttype_edit(this);"><?php echo L('COMPILE');?></a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div> <!-- End #main-content -->
        </form>
    </div>
</div>
<script type="text/javascript">
    function on_click_accounttype_ok() {
        var tipdialog = show_lock_tips('提交中...');
        var form = $(this.iframe.contentWindow.document).find('#accounttype_form');
        $(form).ajaxSubmit({
            success: function() {
                tipdialog.close();
                art.dialog.alert("提交成功");
                window.location.reload();
            }
        });
        this.close();
        return false;
    }

    function accounttype_add() {
        art.dialog.open('<?php echo U("account/accounttype_add", "assort=".$_GET["assort"]);?>', {
            id: 'accounttype_add_dialog',
            title: "添加账目类别",
            lock:true,
            fixed:true,
            window: 'top',
            width:700,
            height:330,
            ok: on_click_accounttype_ok,
            cancel: true
        });
        return false;
    }

    function accounttype_edit(a) {
        var url = $(a).attr("href");
        art.dialog.open(url, {
            id: 'accounttype_edit_dialog',
            title: "修改账目类别",
            lock:true,
            fixed:true,
            window: 'top',
            width:700,
            height:330,
            ok: on_click_accounttype_ok,
            cancel: true
        });
        return false;
    }

    $(function(){
        $("#check_all").click(function(){
            $("input[class='list']").prop('checked', $(this).prop("checked"));
        });


        $("table tbody").sortable({
            connectWith: "table tbody",
            stop: function( event, ui ) {
                $.each($(".list"), function(i, item){
                    $(item).attr('order_id', i);
                });
            }
        });

        $("#sort_btn").click(
            function() {
                var position = [];
                $.each($(".list"), function(i, item){
                    var order_id= $(item).attr('order_id');
                    position.push({type_id:item.value,order_id: order_id});
                });

                $.get('<?php echo U("account/accounttype_sort");?>',{postion:position}, function(data){
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