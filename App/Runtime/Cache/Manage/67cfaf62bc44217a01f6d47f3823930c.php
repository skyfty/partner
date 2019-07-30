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
                        <td  colspan="4">
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
                <table class="table">
                    <tr>
                        <td colspan="4" >
                            <a href="<?php echo U('account/index', 'search='.$product['product_id']).'&field=clause_additive&t=product&act=search';?>" target="_blank"><?php echo L('CHECK');?></a> &nbsp;
                            <a href="<?php echo U('account/add', 'clause_additive='.$product['product_id']).'&t=product&dire=-1';?>&refer_add_url=<?php echo (urlencode($_SERVER['REQUEST_URI'])); ?>"><?php echo L('ADD'); echo L('ACCOUNT_ZHICHU');?></a> &nbsp;
                            <a href="<?php echo U('account/add', 'clause_additive='.$product['product_id']).'&t=product&dire=1';?>&refer_add_url=<?php echo (urlencode($_SERVER['REQUEST_URI'])); ?>"><?php echo L('ADD'); echo L('ACCOUNT_SHOURU');?></a> &nbsp;
                            <a href="<?php echo U('account/add', 'clause_additive='.$product['product_id']).'&t=product&dire=-3';?>&refer_add_url=<?php echo (urlencode($_SERVER['REQUEST_URI'])); ?>">资金冻结</a> &nbsp;
                            <a href="<?php echo U('account/add', 'clause_additive='.$product['product_id']).'&t=product&dire=3';?>&refer_add_url=<?php echo (urlencode($_SERVER['REQUEST_URI'])); ?>">资金解冻</a> &nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td width="20%" class="tdleft">余额</td><td><?php echo ($product["balance"]); ?></td>
                        <td width="20%" class="tdleft">冻结金额</td><td><?php echo ($product["freeze"]); ?></td>
                    </tr>
                    <tr>
                        <td width="20%" class="tdleft">可支付余额</td><td><?php echo ($product["actual"]); ?></td>
                        <td width="20%" class="tdleft">可提现余额</td><td><?php echo ($product["cash"]); ?></td>
                    </tr>
                    <tr>
                        <td width="20%" class="tdleft">总计未付金额</td><td><?php echo ($product["sum_surplus_price"]); ?></td>
                    </tr>
                    <tr>
                        <td width="20%" class="tdleft">产品未付金额</td><td><?php echo ($product["trade_surplus_price"]); ?></td>
                        <td width="20%" class="tdleft">客户服务未付金额</td><td colspan="3"><?php echo ($product["market_surplus_price"]); ?></td>
                    </tr>
                    <tr>
                        <td width="20%" class="tdleft">培训订单未付金额</td><td><?php echo ($product["cultivate_surplus_price"]); ?></td>
                        <td width="20%" class="tdleft">培训订单总金额</td><td><?php echo ($product["cultivate_sum_price"]); ?></td>
                    </tr>
                </table>

                <div>
                    <ul class="nav nav-tabs">
<?php if(is_array($accountcat)): foreach($accountcat as $key=>$vo): ?><li <?php if($acat == $key): ?>class="active"<?php endif; ?> >
    <a href="<?php echo U('', 'acat='.$key.'&assort='.$assort.'&id='.$_GET['id']);?>"><?php echo ($vo); ?></a>
    </li><?php endforeach; endif; ?>
</ul>
<div class="tab-content">
    <table  class="display datatables" cellspacing="0" width="100%" id="account_list">
        <thead>
        <tr>
            <th>
                类型
            </th>
            <th>
                金额
            </th>
            <th>
                操作人
            </th>
            <th>相关方</th>
            <th style="text-align: center">
                订单(培训&nbsp;|&nbsp;业务&nbsp;|&nbsp;产品|新培训)
            </th>
            <th>
                时间
            </th>
            <th>
                备注
            </th>
            <th>
                操作
            </th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th style="text-align: right">合计:</th>
            <th colspan="7" style="text-align: left"><span  id="accounts_totals"><?php echo ($accounts_totals); ?></span></th>
        </tr>
        </tfoot>
    </table>
</div>

<script id="account-operator-tmpl" type="text/x-jquery-tmpl">
<a target="_blank" href="<?php echo U('account/view');?>&t=<?php echo (strtolower(MODULE_NAME)); ?>&id=${account_id}&refer_view_url=<?php echo (urlencode($_SERVER['REQUEST_URI'])); ?>">
查看
</a>
</script>


<script id="date-search" type="text/x-jquery-tmpl">
<div id="account-datatable-date-filter" style="padding-left:20px" class="dataTables_filter">
<div style="float:left;padding-top:4px">起止日期：</div>
<input type="text" placeholder="开始时间"   id="start_time" name="start_time" onClick="on_start_time_focus();" class="Wdate" value="<?php echo ($_GET['start_time']); ?>"/>
<input style="float:right;" placeholder="结束时间"  type="text" id="end_time" onClick="on_end_time_focus();" name="end_time" class="Wdate" value="<?php echo ($_GET['end_time']); ?>" />&nbsp;
</div>
</script>

<script>
    var ajaxdata =  function(d) {
        d.clause_additive = "<?php echo ($clause_additive); ?>";
        d.acat = "<?php echo ($acat); ?>";
        d.start_time = $('#start_time').val();
        d.end_time = $('#end_time').val();
        return d;
    };
    var account_list = $('#account_list').dataTable( {
        ajax: {
            "data": ajaxdata,
            "url": "<?php echo U($Think.MODULE_NAME.'/account_list');?>",
            dataFilter: function(data){
                var json = jQuery.parseJSON( data );
                $("#accounts_totals").html(json.sum_money);
                return JSON.stringify( json );
            }
        },
        "columnDefs": [
            {
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).html($("#account-operator-tmpl").tmpl({"account_id":cellData}));
                },
                "targets": 7,
                "bSortable": false,
            }
        ],
        "scrollX": true,
        "serverSide": true,
        "processing": true,
        "order": [[5, 'desc']],
        'language': def_dataTable_lang_opt
    });
    $("#account_list_length").after($("#date-search").tmpl());

    function on_end_time_focus() {
        var mindate = $("#start_time").val();
        WdatePicker({
            dateFmt:'yyyy-MM-dd',
            minDate: mindate,
            onpicked : on_time_change,
            oncleared:on_time_change
        });
    }

    function on_start_time_focus() {
        WdatePicker({
            dateFmt:'yyyy-MM-dd',
            onpicked : on_time_change,
            oncleared:on_time_change
        });
    }

    function on_time_change() {
        account_list.fnClearTable(true);
        $.ajax({
            url: "<?php echo U($Think.MODULE_NAME.'/get_account_total');?>",
            data: ajaxdata({}),
            success: function(d){
            }
        });
    }

</script>


                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#account-tabs a:first').tab('show');
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