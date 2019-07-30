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
            <a href="javascript:void(0);" class="add_log"><?php echo L('ADD_THE_LOG');?></a> |
            <?php if(($product['is_owner']) == "true"): ?><a href="<?php echo U('product/addproductcat','product_id='.$product['product_id']);?>"><?php echo L('ADD_CAT');?></a> |
            <a href="<?php echo U('product/skilledit','product_id='.$product['product_id'].'&assort='.$assort);?>"><?php echo L('COMPILE');?></a> |
            <a href="<?php echo U('product/appraiseadd','product_id='.$product['product_id'].'&assort='.$assort);?>">增加鉴定</a> |<?php endif; ?>
            <?php if(($product['is_owner']) == "true"): if($product['skill_submit_time'] == 0): ?><a>未提交</a>|
            <?php else: ?>
                <a href="javascript:void(0);" class="verify">
                    审核
                    <?php if($product['skill_verify'] == -1): ?>[审核未通过]
                    <?php elseif($product['skill_verify'] == 0): ?>
                        [待审核]
                    <?php else: ?>
                        [审核通过]<?php endif; ?>
                </a>|
                <a  href="<?php echo U('product/reset_verify','state=-1&product_id='.$product['product_id']);?>" class="reset_verify">
                    重置提交
                </a>|<?php endif; endif; ?>
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
    <?php if(is_array($fields_group)): $i = 0; $__LIST__ = $fields_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i;?><tr>
            <th colspan="4">
                <?php echo ($gvo["name"]); ?>
            </th>

        </tr>
        <?php if($gvo["field_group_id"] == '0'): ?><tr>
                <td class="tdleft" width="15%"><?php echo L('DENGJI_TIME');?></td>
                <td>
                    <?php if($product['create_time'] != 0): echo (date('Y-m-d H:i:s',$product["create_time"])); endif; ?>
                </td>
                <td class="tdleft">
                    <?php echo L('ADD_THE_INFORMATION_ON_PRODUCTS');?>
                </td>
                <td>
                    <a class="role_info" href="javascript:void(0)" rel="<?php echo ($product["owner"]["role_id"]); ?>">
                        <?php echo ($product["owner"]["user_name"]); ?>
                    </a>
                </td>
            </tr><?php endif; ?>

        <?php $j=0; ?>
        <?php if(is_array($gvo['fields'])): $i = 0; $__LIST__ = $gvo['fields'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['operating'] != '4'): $j++; ?>
                <?php if($vo['form_type'] == 'textarea' or $vo['form_type'] == 'editor' or $vo['one_row'] == '1'): if($i%2 == 0): ?><td colspan="2">&nbsp;</td>
                        </tr><?php endif; ?>
                    <tr>
                        <td class="tdleft" width="15%"><?php echo ($vo["name"]); ?>:</td>
                        <td colspan="5">
                            <?php echo ($vo["html"]); ?>
                        </td>
                    </tr>
                    <?php if($i%2 != 0 && count($gvo['fields']) != $j): $i++; endif; ?>
                    <?php else: ?>
                    <?php if($i%2 != 0): ?><tr><?php endif; ?>
                    <td class="tdleft" width="15%"><?php echo ($vo["name"]); ?>:</td>
                    <td width="35%">
                        <span style="color:#<?php echo ($vo['color']); ?>">
                            <?php echo ($vo["html"]); ?>
                        </span>
                    </td>
                    <?php if($i%2 == 0): ?></tr><?php endif; ?>

                    <?php if($i%2 != 0 && count($gvo['fields']) == $j): ?><td colspan="3">&nbsp;</td>
                        </tr><?php endif; endif; endif; endforeach; endif; else: echo "" ;endif; ?>
        <?php if($gvo["field_group_id"] == '0'): ?><tr>
                <td class="tdleft"><?php echo L('QR_CODE_CARD_INFORMATION');?></td>
                <td <?php if(C('ismobile') != 1): ?>colspan="3"<?php endif; ?> style="color:#005580;">
                <div class="pull-left">
                    <img src="<?php echo U('product/qrcode', 'product_id='.$product['product_id']);?>"/>
                </div>
                <div class="pull-left"><?php echo L('FUNCTION_DESCRIPTION');?></div>
                </td>
            </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>

    </tbody>
</table>

<table class="table">
    <tbody><tr>
        <th>技能鉴定</th>
    </tr>
    <tr>
        <td>
            <table id="datatables" class="display datatables" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th></th>
                    <th>科目名称</th>
                    <th>笔试成绩</th>
                    <th>实操成绩</th>
                    <th>是否通过</th>
                    <th>鉴定时间</th>
                    <th style="width: 70px">操作</th>
                </tr>
                </thead>
            </table>
        </td>
    </tr>
    </tbody></table>




<script id="appraise-info-tmpl" type="text/x-jquery-tmpl">
<table class="table" cellpadding="2" cellspacing="0" border="0">
    <tbody>
        <tr>
            <td class="tdleft" width="15%">科目名称</td>
            <td>${course}</td>
            <td class="tdleft" width="15%">笔试成绩</td>
            <td>${written_exam}</td>
            <td class="tdleft" width="15%">实操成绩</td>
            <td>${operate_exam}</td>
        </tr>
        <tr>
            <td class="tdleft" width="15%">是否通过</td>
            <td>${passed}</td>
            <td class="tdleft" width="15%">鉴定时间</td>
            <td  colspan="3">${appraise_time_show}</td>
        </tr>
        <tr>
            <td class="tdleft" width="15%">备注</td>
            <td colspan="5">${describe}</td>
        </tr>
        <tr>
            <td class="tdleft" width="15%">证书扫描</td>
            <td colspan="5">{{html certificate_pic.html}}</td>
        </tr>
    </tbody>
</table>
</script>

<script id="appraise-operator-tmpl" type="text/x-jquery-tmpl">
    <a style="padding-left:2px" href="<?php echo U('product/appraiseedit');?>&id=${product_appraisal_id}">编</a>
    <a style="padding-left:2px" href="<?php echo U('product/appraisedelete');?>&id=${product_appraisal_id}"  onclick="return del_confirm();">删</a>
</script>


<script>

    $(document).ready(function() {
        var table = $('#datatables').DataTable( {
            ajax: {
                "data": function(d) {
                },
                "url": "<?php echo U('product/appraise_list', 'id='.$product['product_id']);?>"
            },
            "columns": [
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ''
                },
                { "data": "course" },
                { "data": "written_exam" },
                { "data": "operate_exam" },
                { "data": "passed" },
                { "data": "appraise_time_show" },
                {
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": ''
                },
            ],
            "columnDefs": [
                {
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).html($("#appraise-operator-tmpl").tmpl(rowData));
                    },
                    "targets": 6
                },
                {
                    "bSortable": false,
                    "targets": 0

                }
            ],
            "bStateSave":true,
            'searching':false,
            "processing": true,
            "order": [[1, 'asc']],
            'language': def_dataTable_lang_opt,
        } );

        $('#datatables tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                row.child($("#appraise-info-tmpl").tmpl(row.data())).show();
                tr.addClass('shown');
            }
        } );

        $('#datatables').on( 'init.dt', function () {
            <?php if($Think.get.show_product_id): ?>$("#product_mark_<?php echo ($_GET['show_product_id']); ?>").parent().parent().find(".details-control").click();<?php endif; ?>

        }) .dataTable();

    } );

</script>


    <table class="table table-hover">
    <tr>
        <th>服务类别</th>
    </tr>

    <?php if($product["skill"] == null): ?><tr>
            <td><?php echo L('THERE_IS_NO_DATA');?> </td>
        </tr>
    <?php else: ?>
        <?php if(is_array($product["skill"])): $i = 0; $__LIST__ = $product["skill"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$skill_group_list): $mod = ($i % 2 );++$i;?><table class="table">
                <tbody>
                <?php if(is_array($skill_group_list['skill_field'])): $i = 0; $__LIST__ = $skill_group_list['skill_field'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$group_list): $mod = ($i % 2 );++$i; $j=0; ?>
                    <?php if(is_array($group_list['fields'])): $i = 0; $__LIST__ = $group_list['fields'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; $j++; ?>
                        <?php if($vo['form_type'] == 'textarea' or $vo['form_type'] == 'editor' or $vo['one_row'] == '1'): if($i%2 == 0): ?><td colspan="2">&nbsp;</td>
                                </tr><?php endif; ?>
                            <tr>
                                <td class="tdleft" width="15%"><?php echo ($vo["name"]); ?>:</td>
                                <td colspan="3"><?php echo ($vo["html"]); ?></td>
                            </tr>
                            <?php if($i%2 != 0 && count($skill_field_list['skill_field']) != $j): $i++; endif; ?>
                            <?php else: ?>
                            <?php if($i%2 != 0): ?><tr><?php endif; ?>
                            <td class="tdleft" width="15%"><?php echo ($vo["name"]); ?>:</td>
                            <td width="35%">
                                <span style="color:#<?php echo ($vo['color']); ?>">
                                <?php if($vo['form_type'] == 'p_box'): echo ($vo["html"]); ?>
                                <?php elseif(stristr('http://',$vo['default_value']) && 'http://' != $skill[$vo['field']] && '' != $product[$vo['field']]): ?>
                                    <a href="<?php echo ($skill[$vo['field']]); ?>" target="_blank"><?php echo ($vo["html"]); ?></a>
                                <?php else: ?>
                                    <?php echo ($vo["html"]); endif; ?>
                                </span>
                            </td>
                            <?php if($i%2 == 0): ?></tr><?php endif; ?>
                            <?php if($i%2 != 0 && count($skill_field_list['skill_field']) == $j): ?><td colspan="2">&nbsp;</td>
                                </tr><?php endif; endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                <tr>
                    <td colspan="6">
                    <?php if(($product["is_owner"]) == "true"): ?><a href="<?php echo U('product/editproductcat','id='.$skill_group_list['skill_id'].'&assort=skill&product_id='.$product['product_id']);?>" class="edit_cat_event">
                        <?php echo L('EDIT');?>
                    </a>&nbsp;
                    <a href="<?php echo U('product/delproductcat','id='.$skill_group_list['skill_id'].'&assort=skill&product_id='.$product['product_id']);?>" class="del_confirm">
                        <?php echo L('DELETE');?>
                    </a>&nbsp;
                    <!--<?php if($skill_group_list['status'] == 1): ?>-->
                        <!--<a href="javascript:void(0);" onclick="update_confirm(<?php echo ($skill_group_list['skill_id']); ?>, 0)">-->
                            <!--升级通过-->
                        <!--</a>&nbsp;-->
                        <!--<a href="javascript:void(0);" onclick="update_confirm(<?php echo ($skill_group_list['skill_id']); ?>, 1)">-->
                            <!--升级不通过-->
                        <!--</a>&nbsp;-->
                    <!--<?php endif; ?>--><?php endif; ?>
                    </td>
                </tr>
            </table><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody><?php endif; ?>
</table>

<div>
    <ul class="nav nav-tabs"  id="log-tabs">
        <?php if(is_array($logcat)): $i = 0; $__LIST__ = $logcat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$logc): $mod = ($i % 2 );++$i;?><li><a href="#log-<?php echo ($logc); ?>"  data-toggle="tab"><?php echo ($logc); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    <div class="tab-content">
    <?php if(is_array($logcat)): $i = 0; $__LIST__ = $logcat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$logc): $mod = ($i % 2 );++$i;?><div class="tab-pane"  id="log-<?php echo ($logc); ?>">
            <table class="table">
                <?php if($product['log'][$logc] == null): ?><tr>
                        <td><?php echo L('THERE_IS_NO_DATA');?></td>
                    </tr>
                    <?php else: ?>
                    <?php if(is_array($product['log'][$logc])): $i = 0; $__LIST__ = $product['log'][$logc];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td>
                                <?php if(!empty($vo["owner"]["user_name"])): ?><a class="role_info" rel="<?php echo ($vo["owner"]["role_id"]); ?>" href="javascript:void(0)"><?php echo ($vo["owner"]["user_name"]); ?></a><?php endif; ?> &nbsp;
                                <?php echo (date("Y-m-d  g:i:s a",$vo["create_date"])); ?> &nbsp;
                                <?php if(!empty($vo["create_date"])): ?>&nbsp;<?php endif; ?>
                                <?php if(C('ismobile') == 1): ?><br/><?php endif; ?>
                                <?php echo ($vo["subject"]); ?>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <?php if(strlen($vo['content']) > 100): ?><div id="slog_<?php echo ($vo["log_id"]); ?>">
                                        <pre><?php echo (msubstr($vo["content"],0,100)); ?>
                                        <a class="more" rel="<?php echo ($vo["log_id"]); ?>" href="javascript:void(0)"><?php echo L('READ_MORE');?></a></pre>
                                    </div>
                                    <div id="llog_<?php echo ($vo["log_id"]); ?>" class="hide">
                                        <pre><?php echo ($vo["content"]); ?></pre>
                                    </div>
                                    <?php else: ?>
                                    <pre><?php echo ($vo["content"]); ?></pre><?php endif; ?>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                <tr>
                    <td colspan="2" style="text-align: right">
                        <a target="_blank" href="<?php echo U('log/index', 'r=RLogProduct&module=product&assort='.$assort.'&type='.$logcatid[$logc]);?>">
                            更多...
                        </a>
                    </td>
                </tr>
            </table>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>

</div>
</div>
</div>
</div>

<script>
    $(function() {
        $('#log-tabs a:first').tab('show');
    });
</script>

<div id="dialog-edit-jujue-desc" class="modal fade" role="dialog"  aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×
            </button>
            <h4 class="modal-title" id="myModalLabel">
                备注
            </h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                关闭
            </button>
            <button type="submit" class="btn btn-primary" onclick="save_jujue_desc();">
                保存
            </button>
        </div>
    </div>
</div>


<script type="text/javascript">

    $(".reset_verify").click(function(){
        return confirm('确实要重置提交状态吗？');
    });
    $(".verify").click(function(){
        var dialog = art.dialog({
            id: 'N3690',
            title: "验证",
            lock:true,
            ok: function () {
                var state = $("input[name='state']:checked").val();
                if (state == 0 && $("#describe").val() == "") {
                    alert("必须说明审核不通过原因");
                    return false;
                }
                $("#myform").submit();
            },
            cancel:true
        });
        $.ajax({
            url:'<?php echo U("product/verify","module=product&assort=skill&product_id=".$product["product_id"]);?>',
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });
    });

    $(".add_log").click(function(){
        var dialog = art.dialog({
            id: 'N3690',
            title: "增加日志",
            lock:true,
            ok: function (data) {
                $("#add-log").submit();
            },
            cancel:true
        });
        $.ajax({
            url: '<?php echo U("log/add","r=RLogProduct&assort=skill&module=product&id=".$product["product_id"]);?>',
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });
    });

    $(".more").click(function(){
        var log_id = $(this).attr('rel');
        $('#llog_'+log_id).attr('class','');
        $('#slog_'+log_id).attr('class','hide');
    });

    var confirm_url = "<?php echo U('product/skill_update_task');?>&product_id=<?php echo ($product['product_id']); ?>&";
    function update_confirm(skill_id, state) {
        if (state == 1) {
            if (confirm("确认不通过升级申请吗?")) {
                $('#dialog-edit-jujue-desc').modal({
                    show: true,
                    remote: "<?php echo U('product/update_confirm_desc');?>&skill_id=" + skill_id,
                });
            }
        } else {
            if (confirm("确认通过升级申请吗?")) {
                window.location.assign(confirm_url + "&sid=" + skill_id + "&state=" + state);
            }
        }
    }
    function save_jujue_desc() {
        var desc = $("#desc").val();
        if (desc == "") {
            alert("必须注明拒绝原因");return;
        }
        var skill_id = $("#skill_id").val();
        $('#dialog-edit-jujue-desc').modal('hide');
        window.location.assign(confirm_url + "&sid=" + skill_id + "&state=1&desc=" + desc);
    }

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