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

                            <a href="<?php echo U('product/eventedit','product_id='.$product['product_id'].'&assort='.$assort);?>"><?php echo L('COMPILE');?></a> |
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
                    <tr>
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
                    </tr>

                    <?php if(is_array($fields_group)): $i = 0; $__LIST__ = $fields_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i;?><tr>
        <th colspan="4">
            <?php echo ($gvo["name"]); ?>
        </th>
    </tr>
    <?php $j=0; ?>
<?php if(is_array($gvo['fields'])): $i = 0; $__LIST__ = $gvo['fields'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; $j++; ?>
    <?php if($vo['form_type'] == 'textarea' or $vo['form_type'] == 'editor' or $vo['one_row'] == '1'): if($i%2 == 0): ?><td colspan="2">&nbsp;</td>
            </tr><?php endif; ?>
        <tr>
            <td class="tdleft" width="15%">
                <span style="<?php if(($vo["color"]) == "FF0000"): ?>;font-weight:bold<?php endif; ?>">
                <?php echo ($vo["name"]); ?>:
                </span>
            </td>
            <td colspan="5" style="color:#<?php echo ($vo['color']); if(($vo["color"]) == "FF0000"): ?>;font-weight:bold<?php endif; ?>">
                <?php echo ($vo["html"]); ?>
            </td>
        </tr>
        <?php if($i%2 != 0 && count($gvo['fields']) != $j): $i++; endif; ?>
     <?php else: ?>
        <?php if($i%2 != 0): ?><tr><?php endif; ?>
        <td class="tdleft" width="15%">
                            <span style="<?php if(($vo["color"]) == "FF0000"): ?>;font-weight:bold<?php endif; ?>">
            <?php echo ($vo["name"]); ?>:</span>
        </td>
        <td width="35%">
            <span style="color:#<?php echo ($vo['color']); ?>;<?php if(($vo["color"]) == "FF0000"): ?>;font-weight:bold<?php endif; ?>">
                <?php echo ($vo["html"]); ?>
            </span>
        </td>
        <?php if($i%2 == 0): ?></tr><?php endif; ?>
        <?php if($i%2 != 0 && count($gvo['fields']) == $j): ?><td colspan="3">&nbsp;</td>
            </tr><?php endif; endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>


                    </tbody>
                </table>
                <ul class="nav nav-tabs" id="tabs">
                    <li><a href="#tabs-1"    data-toggle="tab">日历</a></li>
                    <li><a href="#tabs-2"    data-toggle="tab">表格</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tabs-1" class="tab-pane">
                        <script>
    var event_picker_option = {
        calendars: 6,
    };

    function onclickday(){}

</script>
<link rel="stylesheet" href="__PUBLIC__/datepicker/css/datepicker.css" type="text/css" />
<script src="__PUBLIC__/datepicker/js/datepicker.js" type="text/javascript"></script>
<table class="table" style="height: 250px">
    <tr>
        <td >
            <p id="event_datepicker"></p>
        </td>
    </tr>
</table>
<script src="__PUBLIC__/datepicker/js/eye.js" type="text/javascript"></script>

<script  type="text/javascript">
    function parseDate(date, format) {
        if (date.constructor == Date) {
            return new Date(date);
        }
        var parts = date.split(/\W+/);
        var against = format.split(/\W+/), d, m, y, h, min, now = new Date();
        for (var i = 0; i < parts.length; i++) {
            switch (against[i]) {
                case 'd':
                case 'e':
                    d = parseInt(parts[i],10);
                    break;
                case 'm':
                    m = parseInt(parts[i], 10)-1;
                    break;
                case 'Y':
                case 'y':
                    y = parseInt(parts[i], 10);
                    y += y > 100 ? 0 : (y < 29 ? 2000 : 1900);
                    break;
                case 'H':
                case 'I':
                case 'k':
                case 'l':
                    h = parseInt(parts[i], 10);
                    break;
                case 'P':
                case 'p':
                    if (/pm/i.test(parts[i]) && h < 12) {
                        h += 12;
                    } else if (/am/i.test(parts[i]) && h >= 12) {
                        h -= 12;
                    }
                    break;
                case 'M':
                    min = parseInt(parts[i], 10);
                    break;
            }
        }
        return new Date(
                y === undefined ? now.getFullYear() : y,
                m === undefined ? now.getMonth() : m,
                d === undefined ? now.getDate() : d,
                h === undefined ? now.getHours() : h,
                min === undefined ? now.getMinutes() : min,
                0
        );
    }

    (function($){
        var initLayout = function() {
            var def_event_picker_option = {
                flat: true,
                date: ['2009-12-28','2010-01-23'],
                format: 'Y-m-d',
                mode: 'single',
                starts: 1,
                locale: {
                    days: ["日", "一", "二", "三", "四", "五", "六", "日"],
                    daysShort: ["日", "一", "二", "三", "四", "五", "六", "日"],
                    daysMin: ["日", "一", "二", "三", "四", "五", "六", "日"],
                    months: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
                    monthsShort: ["一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二"],
                    weekMin: 'wk'
                },
                onBeforeShow: function(){
                    return true;
                },

                onInit: function(options){
                    options.onDateChange(options);
                },

                onDateChange:function(options){
                    var currentCal = Math.floor(options.calendars/2);
                    var beginDate = new Date(options.current);
                    beginDate.addMonths(-currentCal);
                    var endDate = new Date(options.current);
                    endDate.addMonths(-currentCal + options.calendars);
                    options.reqeventdate(beginDate, endDate);
                },
                onRender: function(date, in_month) {
                    for (var i = 0; i < window.eventData.length; ++i) {
                        var d = window.eventData[i];
                        if (in_month && d.begin <= date && date <= d.end) {
                            var bd = moment(d.begin).get("date"), ed = moment(d.end).get("date");
                            var bm = moment(d.begin).get("month"), em = moment(d.end).get("month");
                            var cd = moment(date).get("date");
                            var cm = moment(date).get("month");
                            var md = ((bm == cm && bd == cd) || (em == cm && ed == cd));
                            var wid = d.event.workstate_id;
                            var cls = {
                                disabled: wid=="请假"||wid=="公司培训"||wid=="司外订单"||wid == "上岗",
                            };
                            if (wid == "请假") {
                                cls['className'] = "quanquan";
                            } else if (wid == "公司培训") {
                                cls['className'] = "lvsee";
                            }else if(wid == "司外订单"){
                                cls['className'] = "busy";
                            } else{
                                cls['className'] = md ? "quanbann" : "quanoo";
                            }
                            return cls;
                        }
                    }

                    var state_css = {disabled: false,className: false};
                    if (!in_month || moment() >= date) {
                        state_css['disabled'] = true;
                    }
                    return state_css;
                },

                reqeventdate: function(beginDate, endDate) {
                    var product_id = "<?php echo ($product_id); ?>";
                    var url = '<?php echo U("Product/getevent", "product_id=".$product_id);?>';
                    url += "&start_date=" + beginDate.format("yyyy-MM-dd");
                    url += "&end_date=" + endDate.format("yyyy-MM-dd");
                    window.eventData = [];
                    $.ajax({type: "GET", url: url,
                        success: function(data){
                            if (data && data[product_id]) {
                                data[product_id].forEach(function(e){
                                    window.eventData.push({
                                        event: e,
                                        begin: new Date(e.start.replace(/-/g, "/")),
                                        end: new Date(e.end.replace(/-/g, "/"))
                                    });
                                });
                            }
                            window.event_picker.DatePickerClear();
                        }
                    });
                }
            };
            window.eventData = [];
            window.check_event = function(date) {
                for (var i = 0; i < window.eventData.length; ++i) {
                    var d = window.eventData[i];
                    if (d.begin <= date && date <= d.end) {
                        return d;
                    }
                }
            };

            window.event_picker = $('#event_datepicker').DatePicker($.extend(def_event_picker_option, event_picker_option));
        };
        EYE.register(initLayout, 'init');
    })(jQuery)
</script>



<script src="__PUBLIC__/datepicker/js/utils.js" type="text/javascript"></script>
                    </div>
                    <div id="tabs-2" class="tab-pane">
                        <table class="display event-data-tables" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th style="width: 100px">
            开始时间
        </th>
        <th style="width: 100px">
            结束时间
        </th>
        <th style="width: 100px">
            状态
        </th>
        <th>
            备注
        </th>
        <th style="width: 100px; text-align: center">
            操作
        </th>
    </tr>
    </thead>
    <tbody id="event_body">

    </tbody>
</table>
<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }
</style>

<script type="text/javascript">

    var url = '<?php echo U("Product/listevent", "product_id=".$product_id);?>';
    url += "&start_date=" + (new Date(1900,0,12)).format("yyyy-MM-dd");
    url += "&end_date=" + (new Date(3000,0,12)).format("yyyy-MM-dd");
    var event_data_tables = $('.event-data-tables').dataTable( {
        "aoColumnDefs": [
            {
                "bSortable": false,
                "aTargets": [ 4 ]
            }
        ],
        "columns": [
            { "data": "start" },
            { "data": "end" },
            { "data": "workstate_id" },
            { "data": "description" },
            { "data": "operator" }
        ],
        "idSrc": "event_id",
        "processing": true,
        "ajax": url,
        "order": [[ 2, "desc" ]],
        'language': def_dataTable_lang_opt
    } );
    $("#DataTables_Table_0_filter").html('<button  onclick="cancel_leave();" type="button" class="btn  btn-mini" style="margin-right: 15px;">改变请假状态</button><button  onclick="add_event();" type="button" class="btn  btn-mini" style="margin-right: 5px;">添加日程</button>');

    function cancel_leave() {
        if ('<?php echo ($product["leave_state"]); ?>' == "请假中") {
            alert("当前处于请假中状态");return;
        }
        art.dialog.confirm("确认改变请假状态吗？", function(){
            var cc = '<?php echo ($product["leave_state"]); ?>'=="在职"?"请假过期":"在职";
            $.ajax({
                url: '<?php echo U("product/change_leave_state","product_id=".$product["product_id"]);?>&leave_state=' + cc,
                success: function (data) {
                    alert(data);
                    window.location.reload();

                },
                cache: false
            });
        });
    }

    function add_event() {
        var dialog = art.dialog({
            id: 'N3690',
            title: "增加日程",
            lock:true,
            ok: function () {
                $("#form1").ajaxSubmit({
                    success : function(){
                        event_data_tables.api().ajax.reload();
                    }
                });
            },
            cancel:true
        });
        $.ajax({
            url: '<?php echo U("product/eventdialog","product_id=".$product["product_id"]);?>',
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });
    }

    function onclick_eventreset(event_id) {
        if (confirm("确实要改变状态吗?")) {
            $.ajax({
                url: '<?php echo U("product/event_reset","event_id=");?>' + event_id,
                success: function (data) {
                    event_data_tables.api().ajax.reload();
                },
                cache: false
            });
        }
    }

    function onclick_eventedit(event_id) {
        var dialog = art.dialog({
            id: 'N3690',
            title: "增加日程",
            lock:true,
            ok: function () {
                $("#form1").ajaxSubmit({
                    success : function(){
                        event_data_tables.api().ajax.reload();
                    }
                });
            },
            cancel:true
        });
        $.ajax({
            url: '<?php echo U("product/eventdialog","product_id=".$product["product_id"]);?>&event_id=' +event_id ,
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });
    }

    function onclick_eventdelete(event_id) {
        if (confirm("确实要删除这个日程吗?")) {
            $.ajax({
                url: '<?php echo U("product/event_delete","event_id=");?>' + event_id,
                success: function (data) {
                    event_data_tables.api().ajax.reload();
                },
                cache: false
            });
        }
    }
</script>

                    </div>
                </div>
            </div>
            <table class="table table-hover">
                <tr>
                    <th>调度日志</th>
                </tr>
            </table>
            <div id="log-tabs">
                <script>
                    function logger_data_cb(d) {
                        d._string = "type=1";
                    }
                </script>
                <table class="display" cellspacing="0" width="100%" id="logger-datatable">
    <thead>
    <tr  class="tr-td"   style="font-size: 14px">
        <th style="width: 150px">时间</th>
        <th width="140px">操作者</th>
        <th width="140px">雇员</th>
        <th width="100px">标题</th>
        <th>内容</th>
    </tr>
    </thead>
</table>
<script>
    var datatable_options = {
        ajax: {
            "data": function(d) {
                d.start_time = $('#start_time').val();
                d.end_time = $('#end_time').val();
                d.assort = "dispatch"
                <?php if($product): ?>d.product_id = "<?php echo ($product['product_id']); ?>";<?php endif; ?>

                if (typeof logger_data_cb == "function") {
                    logger_data_cb(d);
                }
            },
            "url": "<?php echo U('product/logtable');?>"
        },
        "columnDefs": [
            {
                "targets": [2,3],
                "visible": false,
                "bSortable": false,
            }
        ],
        "serverSide": true,
        "processing": true,
        "order": [[ 0, "desc" ]],
        'language': def_dataTable_lang_opt,
    }

    function delete_log(log_id) {
        art.dialog.confirm('确实要删除吗?', function () {
            $.ajax({
                url: "<?php echo U('log/log_delete', 'id=');?>" + log_id,
                success: function (data) {
                    logger_table.fnClearTable(true);
                },
                cache: false
            });
        });
    }
</script>

<script id="date-search" type="text/x-jquery-tmpl">
<div id="account-datatable-date-filter" style="padding-left:20px" class="dataTables_filter">
<div style="float:left;padding-right:7px">
    <?php echo (show_logscope_options_html($logscope_options)); ?>
</div>

<div style="float:left;padding-top:4px">起止日期：</div>
<input type="text" placeholder="开始时间" style="width:145px"   id="start_time" name="start_time" onClick="on_start_time_focus();" class="Wdate" value="<?php echo ($_GET['start_time']); ?>"/>
<input style="float:right;" placeholder="结束时间"  style="width:145px" type="text" id="end_time" onClick="on_end_time_focus();" name="end_time" class="Wdate" value="<?php echo ($_GET['end_time']); ?>" />&nbsp;
</div>
</script>

<script type="text/javascript">

    var logger_table = $('#logger-datatable').dataTable(datatable_options);
    $("#logger-datatable_length").after($("#date-search").tmpl());
    $("#logscope").chosen({
        max_selected_options: 5,
        disable_search:true,
    });

    function on_end_time_focus() {
        var mindate = $("#start_time").val();
        WdatePicker({
            dateFmt:'yyyy-MM-dd HH:mm:ss',
            minDate: mindate,
            onpicked : on_time_change,
            oncleared:on_time_change
        });
    }

    function on_start_time_focus() {
        WdatePicker({
            dateFmt:'yyyy-MM-dd HH:mm:ss',
            onpicked : on_time_change,
            oncleared:on_time_change
        });
    }

    function on_time_change() {
        logger_table.fnClearTable(true);
    }
</script>
            </div>
        </div>
    </div>
</div>
<div class="hide" id="dialog-event" title="<?php echo L('ADD_THE_SCHEDULE');?>">loading...</div>
<div class="hide" id="dialog-default-state-edit" title="默认状态编辑">loading...</div>
    <script>
        $(function() {
            $('#tabs a:first').tab('show');
        });
    </script>
<script type="text/javascript">

    //初始化图片查看插件
    $("#dialog-event").dialog({
        autoOpen: false,
        width: 800,
        maxHeight: 400,
        modal: false,
        position: ["center",100]
    });

    $("#dialog-default-state-edit").dialog({
        autoOpen: false,
        width: 800,
        maxHeight: 400,
        position: ["center",100]
    });

    $(".change_default_state").click(function(){
        $('#dialog-default-state-edit').dialog('open');
        $('#dialog-default-state-edit').load('<?php echo U("product/changedefstate","id=".$product["product_id"]);?>');
    });

    $(".more").click(function(){
        var log_id = $(this).attr('rel');
        $('#llog_'+log_id).attr('class','');
        $('#slog_'+log_id).attr('class','hide');
    });

    <?php if(in_array($product['station_state'], array('在编','请长假','请短假'))): else: ?>
    $('#workstate_id').attr("disabled", "disabled");<?php endif; ?>
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