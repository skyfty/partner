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
        <h4><a name="tab"><?php echo L('PRODUCT_DETAILS');?></a></h4>
    </div>
    <div class="row">
        <div class="span12">
            <?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
            <div class="tabbable">
                <ul class="nav nav-tabs">
    <li <?php if($_GET['assort'] == 'basic' || $_GET['assort'] == ''): ?>class="active"<?php endif; ?> >
    <a href="<?php echo U('product/view', 'id='.$product_id); if(($_GET['visitor']) == "trash"): ?>&visitor=trash<?php endif; ?>">基本信息</a>
    </li>

    <?php if(vali_permission('product','cultivateview')): ?><li <?php if($_GET['assort'] == 'cultivate'): ?>class="active"<?php endif; ?>>
        <a href="<?php echo U('product/cultivateview', 'id='.$product_id.'&assort=cultivate'); if(($_GET['visitor']) == "trash"): ?>&visitor=cultivate<?php endif; ?>">培训订单</a>
        </li><?php endif; ?>

    <li <?php if($_GET['assort'] == 'skill'): ?>class="active"<?php endif; ?>>
    <a href="<?php echo U('product/skillview', 'id='.$product_id.'&assort=skill'); if(($_GET['visitor']) == "trash"): ?>&visitor=trash<?php endif; ?>">鉴定信息</a>
    </li>

    <li <?php if($_GET['assort'] == 'event'): ?>class="active"<?php endif; ?> >
    <a href="<?php echo U('product/eventview', 'id='.$product_id.'&assort=event'); if(($_GET['visitor']) == "trash"): ?>&visitor=trash<?php endif; ?>">调度信息</a>
    </li>

    <?php if(vali_permission('product','marketview')): ?><li <?php if($_GET['assort'] == 'market'): ?>class="active"<?php endif; ?>>
        <a href="<?php echo U('product/marketview', 'id='.$product_id.'&assort=market'); if(($_GET['visitor']) == "trash"): ?>&visitor=market<?php endif; ?>">客户服务</a>
        </li><?php endif; ?>
    <?php if(vali_permission('product','evaluatemarket_view')): ?><li <?php if($_GET['assort'] == 'evaluate'): ?>class="active"<?php endif; ?>>
        <a href="<?php echo U('product/evaluatemarket_view', 'id='.$product_id.'&assort=evaluate'); if(($_GET['visitor']) == "trash"): ?>&visitor=evaluate<?php endif; ?>">评价</a>
        </li><?php endif; ?>
    <?php if(vali_permission('product','accountview')): ?><li <?php if($_GET['assort'] == 'account'): ?>class="active"<?php endif; ?> >
        <a href="<?php echo U('product/accountview', 'id='.$product_id.'&assort=account'); if(($_GET['visitor']) == "trash"): ?>&visitor=trash<?php endif; ?>">账户信息</a>
        </li><?php endif; ?>

    <?php if(vali_permission('product','tradeview')): ?><li <?php if($_GET['assort'] == 'trade'): ?>class="active"<?php endif; ?> >
        <a href="<?php echo U('product/tradeview', 'id='.$product_id.'&assort=trade'); if(($_GET['visitor']) == "trash"): ?>&visitor=trash<?php endif; ?>">缴费信息</a>
        </li><?php endif; ?>

    <?php if(vali_permission('product','berthview')): ?><li <?php if($_GET['assort'] == 'berth'): ?>class="active"<?php endif; ?>>
        <a href="<?php echo U('product/berthview', 'id='.$product_id.'&assort=berth'); if(($_GET['visitor']) == "trash"): ?>&visitor=berth<?php endif; ?>">住宿日志</a>
        </li><?php endif; ?>

    <?php if(vali_permission('product','leadsview')): ?><li <?php if($_GET['assort'] == 'leads'): ?>class="active"<?php endif; ?> >
    <a href="<?php echo U('product/leadsview', 'id='.$product_id.'&assort=leads'); if(($_GET['visitor']) == "trash"): ?>&visitor=trash<?php endif; ?>">面试信息</a>
    </li><?php endif; ?>


</ul>
            </div>
            <div class="tab-content">
                <table class="table">
                    <thead>
                    <tr>
                        <td colspan="4">
                            <p style="font-size: 14px;">
                            </p>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="6">
                            <table  class="table"  style="border: 0px;margin-bottom:0px">
    <tbody>
    <tr>
        <td style="width: 250px">
            <?php if(!empty($product['images']['main'])): ?><div class="box-secondary">
                    <a href="<?php echo ($product["images"]["main"]["path"]); ?>" data-lightbox="roadtrip">
                        <img src="<?php echo ($product["images"]["main"]["path"]); ?>" class="thumbnail cardpicthumb">
                    </a>
                </div><?php endif; ?>
            <?php if(!empty($product['images']['cardpic'])): ?><a href="<?php echo ($product["images"]["cardpic"]["path"]); ?>" data-lightbox="roadtrip">
                    <img src="<?php echo ($product["images"]["cardpic"]["path"]); ?>" class="thumbnail cardpicthumb">
                </a><?php endif; ?>
        </td>
        <td>
            <table class="table">
                <tr>
                    <td class="tdleft" width="16%">雇员名称:</td>
                    <td width="16%"><?php echo ($product['name']); ?></td>
                    <td class="tdleft" width="16%">工号:</td>
                    <td width="16%">
                        <?php echo ($product['idcode']); ?>
                    </td>
                    <td class="tdleft" width="16%">性别:</td>
                    <td width="16%"><?php echo ($product['sex']); ?></td>
                </tr>
                <tr>
                    <td class="tdleft" width="16%">手机号码:</td>
                    <td width="16%">
                        <?php echo ($product['telephone']); ?>
                    </td>
                    <td class="tdleft" width="16%">出生日期:</td>
                    <td width="16%">
                        <?php echo (todate($product["birthday"],'Y-m-d')); ?>
                    </td>
                    <td class="tdleft" width="16%">籍贯:</td>
                    <td width="16%">
                        <?php echo ($product["census"]); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tdleft" width="16%">岗位状态:</td>
                    <td width="16%"><?php echo ($product["station_state"]); ?></td>
                    <td class="tdleft" width="16%">工作状态:</td>
                    <td width="16%"><?php echo ($product["workstate_name"]); ?></td>
                    <td class="tdleft" width="16%">培训状态:</td>
                    <td width="16%"><?php echo ($product["cultivate_status"]); ?></td>
                </tr>
                <?php if(is_array($product["skill"])): $i = 0; $__LIST__ = $product["skill"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$svo): $mod = ($i % 2 );++$i;?><tr>
                        <td class="tdleft" width="16%">技能</td>
                        <td width="16%"><?php echo ($svo['category_name']); ?></td>
                        <td class="tdleft" width="16%">级别</td>
                        <td width="16%"><?php echo ($svo['level']); ?></td>
                        <td class="tdleft" width="16%">价格</td>
                        <td width="16%"><?php echo ($svo['salary']); ?></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
        </td>
    </tr>
    </tbody>
</table>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <!--  -->

                <table  class="display" cellspacing="0" width="100%" id="trade-data-tables">
                    <thead>
                    <tr>
                        <?php if(is_array($field_array)): $i = 0; $__LIST__ = $field_array;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><th>
                                <?php echo ($vo["name"]); ?>
                            </th><?php endforeach; endif; else: echo "" ;endif; ?>
                        <th style="width:30px;"><?php echo L('OPERATING');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($product["trade"])): $i = 0; $__LIST__ = $product["trade"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
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

                            <td style="text-align:center">
                                <a href="<?php echo U('trade/view','id='.$vo['trade_id']);?>" target="_blank"  title="查看详细">
                                    <i class="icon-th-large"></i>
                                </a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <style>
                    th, td { white-space: nowrap; }
                    div.dataTables_wrapper {
                        width: 1155px;
                        margin: 0 auto;
                    }
                </style>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#trade-data-tables').dataTable( {
                            "scrollX": true,
                            "order": [[ 0, "desc" ]],
                            'language': def_dataTable_lang_opt
                        } );
                    });
                </script>
            </div>
        </div>
    </div>
</div>

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