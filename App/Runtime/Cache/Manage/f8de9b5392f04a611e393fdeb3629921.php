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
    <div class="row">
        <div class="span12">
            <?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
        </div>
        <div class="span12">
            <form action="<?php echo U('account/add');?>" method="post" id="form1" enctype="multipart/form-data">
                <input type="hidden" name="t" id="t" value="<?php echo ($t); ?>"/>
                <input type="hidden" name="refer_url" id="refer_url" value="<?php echo ($refer_url); ?>"/>
                <input type="hidden" name="account_type" id="account_type" value="<?php echo ($t); ?>"/>
                <input type="hidden" name="income_or_expenses" id="income_or_expenses" value="<?php echo ($dire); ?>"/>
                <input type="hidden" name="submit_time" value="<?php echo ($submit_time); ?>"/>
                <input type="hidden" name="restrict" id="restrict" value="<?php echo ($restrict); ?>"/>
                <input type="hidden" name="quarry" id="quarry" value="1"/>

                <table class="table table-hover">
                    <thead>
                    <tr>
                        <td  style="text-align:center;" colspan="4">
                            <input name="submit" class="btn btn-primary" type="submit" value="<?php echo L('SAVE');?>"/> &nbsp;
                            <input class="btn" type="button" onclick="on_click_return()" value="<?php echo L('RETURN');?>"/></td>

                        </td>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <td  style="text-align:center;" colspan="4">
                            <input name="submit" class="btn btn-primary" type="submit" value="<?php echo L('SAVE');?>"/> &nbsp;
                            <input class="btn" type="button" onclick="on_click_return()" value="<?php echo L('RETURN');?>"/></td>
                        </td>
                    </tr>
                    </tfoot>

                    <tbody>
                    <tr><td class="tdleft" width="15%" >信息</td><td colspan="3" id="account_info"></td></tr>
                    <tr <?php if(($clause_additive != '') || ($t == 'inernal') || ($t == 'flow')): ?>style="display:none"<?php endif; ?> >
                    <td class="tdleft" width="5%" valign="middle" id="model">
                        <?php switch($t): case "product": ?>雇员<?php break;?>
                            <?php case "customer": ?>客户<?php break;?>
                            <?php case "staff": ?>员工<?php break;?>
                            <?php case "flow": ?>流水<?php break;?>
                            <?php default: ?>公司<?php endswitch;?>
                    </td>
                    <td valign="middle" colspan="3">
                        <input type="hidden" name="clause_additive" id="clause_additive" value="<?php echo ($clause_additive); ?>">
                        <input type="text" name="clause_name" id="clause_name" value="<?php echo ($clause['name']); ?>">
                        <input type="hidden" name="clause_idcode" id="clause_idcode" value="<?php echo ($clause['idcode']); ?>">
                        <span id="clause_name_Tip" style="color:red;" class="onShow"></span>
                    </td>
                    </tr>
                    <?php if(is_array($fields_group)): $i = 0; $__LIST__ = $fields_group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gvo): $mod = ($i % 2 );++$i; $j=0; ?>
                        <?php if(is_array($gvo['fields'])): $i = 0; $__LIST__ = $gvo['fields'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; $j++; ?>
                            <?php if($vo['one_row'] == '1' or $vo['form_type'] == 'textarea' or $vo['form_type'] == 'editor' or $vo['form_type'] == 'address'): if($i%2 == 0): ?><td colspan="2">&nbsp;</td>
                                    </tr><?php endif; ?>
                                <tr>
                                    <td class="tdleft" width="15%"><?php echo ($vo["name"]); ?>:</td>
                                    <td colspan="3">
                                        <?php echo ($vo["html"]); ?>
                                        <?php if($vo["field"] == 'clause_type_id'): ?><span id="deta_ious"></span>
                                            <span id="deta_related"></span>
                                            <span id="deta_clause_type_id"></span><?php endif; ?>

                                    </td>
                                </tr>
                                <?php if($i%2 != 0 && count($gvo['fields']) != $j): $i++; endif; ?>
                            <?php else: ?>
                                <?php if($i%2 != 0): ?><tr><?php endif; ?>
                                <td class="tdleft" width="15%"><?php echo ($vo["name"]); ?>:</td>
                                <td ><?php echo ($vo["html"]); ?>
                                    <?php if($vo["field"] == 'money'): ?><span id="deta_use_settle_time"></span><?php endif; ?>
                                </td>

                                <?php if($i%2 == 0): ?></tr><?php endif; ?>
                                <?php if($i%2 != 0 && count($gvo['fields']) == $j): ?><td colspan="2">&nbsp;</td>
                                    </tr><?php endif; endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </form>
        </div> <!-- End #tab1 -->
    </div> <!-- End #main-content -->
</div>

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
    function product_callback_clause(name, idcode, item) {
        $('#clause_name').val(name);
        $('#clause_idcode').val(idcode);
        $('#clause_additive').val(item);
        refresh_account_info();
        get_account_tip($('#clause_type_id').val(), "<?php echo ($t); ?>", $("#clause_additive").val());
    }
    function product_callback_name(name, idcode, item) {
        $('#product_name').val(name);
        $('#product_id').val(item);
        refresh_account_info();
        get_account_tip($('#clause_type_id').val(), "<?php echo ($t); ?>", $("#clause_additive").val());
    }
    var product_callback = product_callback_clause;

    function select_product_native(n) {
        if (n == "product_name") {
            product_callback = product_callback_name;
        } else {
            product_callback = product_callback_clause;
        }

        art.dialog.open('<?php echo U("product/listDialog");?>', {
            id: 'N3690',
            title: "选择雇员",
            lock:true,
            fixed:true,
            window: 'top',
            width: 780,
            height: 510,
            ok: function () {
                var iframe = $(this.iframe.contentWindow.document);
                var item = iframe.find('input:radio[name="product_id"]:checked').val();
                var idcode = iframe.find('input:radio[name="product_id"]:checked').parent().next().html();
                var name = iframe.find('input:radio[name="product_id"]:checked').parent().next().next().html();
                product_callback(name, idcode, item);
            },
            cancel: true
        });
    }
    <?php if(($_GET['t']== 'product')): ?>$('#clause_name').click(select_product_native);<?php endif; ?>
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
    function on_staff_info(b) {
        $('#staff_id').val(b['staff_id']);
        $('#staff_name').val(b['name']);
        $('#clause_name').val(b.name);
        $('#clause_additive').val(b.staff_id);

        refresh_account_info();
    }

    <?php if(($_GET['t']== 'staff')): ?>$('#clause_name').click(function(){
            select_staff();
        });<?php endif; ?>
</script>

<script type="text/javascript">
    function request_trade_info(p, cb) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("trade/getInfo");?>',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    }
    function select_trade(corre, corre_id) {
        var query_id = "";
        if(corre_id){
            query_id += "&id=" + corre_id;
        }
        if(corre){
            query_id += "&corre=" + corre;
        }
        art.dialog.open( "<?php echo U('trade/listDialog');?>" + query_id, {
            id: 'select_trade_dialog',
            title: "选择产品订单",
            lock:true,
            fixed:true,
            window: 'top',
            width: 800,
            height: 510,
            ok: function () {
                var trade_id = $(this.iframe.contentWindow.document).find('input:radio[name="trade_id"]:checked').val();
                var data = {
                    'id': trade_id
                };
                request_trade_info(data, on_trade_info);
                return true;
            },
            cancel: function () {
                return true;
            }
        });
    }
</script>
<script type="text/javascript">
    function on_trade_info(b) {
        $('#trade_name').val(b.orderid);
        $('#trade_id').val(b.trade_id);
        $('#related_owner_role_id').val(b.owner_role_id);
        refresh_account_info();
        get_account_tip($('#clause_type_id').val(), "<?php echo ($t); ?>", $("#clause_additive").val());
    }

    function on_click_trade() {
        select_trade("<?php echo ($_GET['t']); ?>",$("#clause_additive").val());
    }
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
    function select_customer_native(n) {
        select_customer({}, function(b){
            if (n == "customer_name") {
                $('#customer_id').val(b['customer_id']);
                $('#customer_name').val(b['name']);
            } else {
                $('#clause_name').val(b.name);
                $('#clause_additive').val(b.customer_id);
            }
            refresh_account_info();
        });
    }

    <?php if(($_GET['t']== 'customer')): ?>$('#clause_name').click(function(){
            select_customer_native();
        });<?php endif; ?>
</script>

<script type="text/javascript">
    function request_market_info(p, cb) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("market/getmarket");?>',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    }

    function select_market(param, cb) {
        art.dialog.data("param", param ? param : {});
        art.dialog.open('<?php echo U("market/listDialog");?>', {
            id: 'select_market_dialog',
            title: "选择客户服务订单",
            lock:true,
            fixed:true,
            window: 'top',
            width: 780,
            height: 400,
            ok: function () {
                var market_id = $(this.iframe.contentWindow.document).find('input:radio[name="market_id"]:checked').val();
                var data = {
                    'id': market_id
                };
                request_market_info(data, cb?cb:on_market_info);
                return true;
            },
            cancel:true
        });
    }
</script>

<script>
    function select_product_bymarket(param, cb) {
        art.dialog.data('reqparam', param);
        art.dialog.open('<?php echo U("market/product_listDialog");?>', {
            id: 'select_product_dialog',
            title: "选择雇员",
            lock:true,
            fixed:true,
            window: 'top',
            width: 780,
            height: 400,
            ok: function () {
                var market_product_id = $(this.iframe.contentWindow.document).find('input:radio[name="market_product_id"]:checked').val();
                var product_id = $(this.iframe.contentWindow.document).find('#product_id_'+market_product_id).val();
                var data = {
                    'product_id': product_id
                };
                request_product_info(data, function(b){
                    b['market_product_id'] = market_product_id;
                    cb?cb(b):on_product_info(b);
                });
            },
            cancel:true
        });
    }

    function market_select_product() {
        var market_id = $('#market_id').val();
        var model = "Market";
        var query = "market.market_id=" + market_id;

        var clause_type_id = $("#clause_type_id").val();
        switch(clause_type_id) {
            case "224":{
                model = "MarketChannelProduct";
                query ="market_channel.channel_model='product' and market.market_id=" + market_id;
                break;
            }

            case "252":
            case "217":{
                model = "MarketProduct";
                query = "market.market_id=" + market_id;
                break;
            }
        }

        if (model == "MarketProduct") {
            select_product_bymarket({"model":model,"query":query}, function(b){
                $('#product_id').val(b['product_id']);
                $('#product_name').val(b['name']);
                $('#market_product_id').val(b['market_product_id']);
            });
        } else {
            select_product({"model":model,"query":query}, function(b){
                $('#product_id').val(b['product_id']);
                $('#product_name').val(b['name']);
            });
        }
    }

    function market_select_customer() {
        var market_id = $('#market_id').val();
        var model = "Customer";
        var query = "";

        var clause_type_id = $("#clause_type_id").val();
        switch(clause_type_id) {
            case "222":{
                model = "MarketChannelCustomer";
                query ="market_channel.channel_model='customer' and market.market_id=" + market_id;
                break;
            }
        }
        select_customer({"model":model,"query":query}, function(b){
            $('#customer_id').val(b['customer_id']);
            $('#customer_name').val(b['name']);
        });
    }

    function market_select_staff() {
        var market_id = $('#market_id').val();
        var model = "Staff";
        var query = "";

        var clause_type_id = $("#clause_type_id").val();
        switch(clause_type_id) {
            case "228":{
                model = "MarketChannelStaff";
                query ="market_channel.channel_model='staff' and market.market_id=" + market_id;
                break;
            }
            case "225":{
                model = "MarketUrge";
                query ="market.market_id=" + market_id;
                break;
            }
        }
        select_staff({"model":model,"query":query}, function(b){
            $('#staff_id').val(b['staff_id']);
            $('#staff_name').val(b['name']);
        });
    }

    function on_market_info(b) {
        $('#market_name').val(b.idcode);
        $('#market_id').val(b.market_id);
        $('#related_owner_role_id').val(b.owner_role_id);
        if (b['settle_state'] != 918) {
            $('#market_use_settle_time_span').hide();
        } else {
            $('#market_use_settle_time_span').show();
        }

        var clause_type = $('#clause_type_id option:selected').val();
        var inflow = $('#clause_type_id option:selected').attr("inflow");
        if (inflow && inflow != "inernal") {
            if (inflow == "product") {
                market_select_product(b.market_id);
            }else if (inflow == "customer"){
                if (clause_type == "236" || clause_type == "250" || clause_type == "222") {
                    $('#customer_id').val(b['customer_id']);
                    $('#customer_name').val(b['customer_name']);
                    $('#customer_name').attr("disabled", "disabled");
                } else {
                    market_select_customer(b.market_id);
                }
            }else if (inflow == "staff"){
                market_select_staff(b.market_id);
            }
        }
        refresh_account_info();
        get_account_tip($('#clause_type_id').val(), "<?php echo ($t); ?>", $("#clause_additive").val());
    }
    function on_click_market() {
        var param = {
            "no_settle_state":1
        };
        <?php if(($_GET['t']!= 'inernal')): ?>var clause_additive = $("#clause_additive").val();
            if(clause_additive){
                param['id'] = clause_additive;
            }
            var clause_type_id = $("#clause_type_id").val();
            switch(clause_type_id) {
                case "240":case "242": case "238":{
                    param['module'] = "channel";
                    param['channel_model'] = "<?php echo ($_GET['t']); ?>";
                    break;
                }
                case "244":{
                    param['module'] = "urge";
                    break;
                }
                default: {
                    param['module'] = "<?php echo ($_GET['t']); ?>";
                    break;
                }
            }<?php endif; ?>
        select_market(param);
    }

    <?php if($_GET['market_id']!= ''): ?>request_market_info({ 'id': "<?php echo ($_GET['market_id']); ?>" }, on_market_info);<?php endif; ?>
</script>




<script type="text/javascript">
    function request_cultivate_info(p, cb) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("cultivate/getInfo");?>',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    }

    function select_cultivate(param, cb) {
        art.dialog.data("reqparam", param ? param : {});
        art.dialog.open('<?php echo U("cultivate/listDialog");?>', {
            id: 'select_cultivate_dialog',
            title: "选择新培训订单",
            lock:true,
            fixed:true,
            window: 'top',
            width: 780,
            height: 400,
            ok: function () {
                var cultivate_id = $(this.iframe.contentWindow.document).find('input:radio[name="cultivate_id"]:checked').val();
                var data = {
                    'id': cultivate_id
                };
                request_cultivate_info(data, cb?cb:on_cultivate_info);
                return true;
            },
            cancel: true
        });
    }
</script>
<script>
    function cultivate_select_product() {
        var cultivate_id = $('#cultivate_id').val();
        var model = "ProductCultivate";
        var query = "cultivate.cultivate_id=" + cultivate_id;

        var clause_type_id = $("#clause_type_id").val();
        switch(clause_type_id) {
            case "279":{
                model = "CultivateChannelProduct";
                query ="cultivate_channel.channel_role_model='2' and cultivate.cultivate_id=" + cultivate_id;
                break;
            }
        }

        select_product({"model":model,"query":query}, function(b){
            $('#product_id').val(b['product_id']);
            $('#product_name').val(b['name']);
        });
    }

    function cultivate_select_customer() {
        var cultivate_id = $('#cultivate_id').val();
        var model = "CustomerCultivate";
        var query = "";

        var clause_type_id = $("#clause_type_id").val();
        switch(clause_type_id) {
            case "277":{
                model = "CultivateChannelCustomer";
                query ="cultivate_channel.channel_role_model='3' and cultivate.cultivate_id=" + cultivate_id;
                break;
            }
        }
        select_customer({"model":model,"query":query}, function(b){
            $('#customer_id').val(b['customer_id']);
            $('#customer_name').val(b['name']);
        });
    }

    function cultivate_select_staff() {
        var cultivate_id = $('#cultivate_id').val();
        var model = "StaffCultivate";
        var query = "";

        var clause_type_id = $("#clause_type_id").val();
        switch(clause_type_id) {
            case "275":{
                model = "CultivateChannelStaff";
                query ="cultivate_channel.channel_role_model='4' and cultivate.cultivate_id=" + cultivate_id;
                break;
            }
            case "265":{
                model = "CultivateUrge";
                query ="cultivate.cultivate_id=" + cultivate_id;
                break;
            }
        }
        select_staff({"model":model,"query":query}, function(b){
            $('#staff_id').val(b['staff_id']);
            $('#staff_name').val(b['name']);
        });
    }

    function on_cultivate_info(b) {
        $('#cultivate_name').val(b.idcode);
        $('#cultivate_id').val(b.cultivate_id);
        $('#related_owner_role_id').val(b.owner_role_id);
        if (b['settle_state'] != 918) {
            $('#cultivate_use_settle_time_span').hide();
        } else {
            $('#cultivate_use_settle_time_span').show();
        }

        var inflow = $('#clause_type_id option:selected').attr("inflow");
        if (inflow && inflow != "inernal") {
            if (inflow == "product") {
                cultivate_select_product(b.cultivate_id);
            }else if (inflow == "customer"){
                cultivate_select_customer(b.cultivate_id);
            }else if (inflow == "staff"){
                cultivate_select_staff(b.cultivate_id);
            }
        }
        refresh_account_info();
        get_account_tip($('#clause_type_id').val(), "<?php echo ($t); ?>", $("#clause_additive").val());
    }
    function on_click_cultivate() {
        var param = {
            "no_settle_state":1
        };
        <?php if(($_GET['t']!= 'inernal')): ?>var clause_additive = $("#clause_additive").val();
        if(clause_additive){
            param['id'] = clause_additive;
        }
        var clause_type_id = $("#clause_type_id").val();
        switch(clause_type_id) {
            case "279":case "277": case "275":{
                param['module'] = "channel";
                param['channel_model'] = "<?php echo ($_GET['t']); ?>";
                break;
            }
            case "273":{
                param['module'] = "urge";
                break;
            }
            default: {
                param['module'] = "<?php echo ($_GET['t']); ?>";
                break;
            }
        }<?php endif; ?>
        select_cultivate(param);
    }
    <?php if($_GET['cultivate_id']!= ''): ?>request_cultivate_info({ 'id': "<?php echo ($_GET['cultivate_id']); ?>" }, on_cultivate_info);<?php endif; ?>
</script>


<script>

    function get_account_tip(type_id, module, module_id) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'index.php?m=account&a=getaccounttip',
            'data':{
                'type_id': type_id,
                'module': module,
                'module_id': module_id
            },
            'success':function(data){
                if(data.status == 1 && data.data){
                    $("#money").attr('placeholder', data.data);
                } else {
                    $("#money").attr('placeholder', "");
                }
            }
        });
    }


    function check_form_validator(ii, tip) {
        $(ii).formValidator({
            empty:false,
            onFocus:" ",
        }).inputValidator({
            min:1,max:10,
            empty:false,
            onErrorMin:tip
        });
    }

    $(function() {
        $.formValidator.initConfig({
            formID: "form1",
            debug: false,
            submitOnce: true,
            onError: function (msg, obj, errorlist) {
                art.dialog.alert(msg);
            }
        });

        $("#money").formValidator({
            empty:false,
            onFocus:" ",
            onShow: " ",
            onCorrect:"<span style='color:green;'>√</span>"
        }).inputValidator({
            min:1,max:10,
            onErrorMin:"金额不能为空！",
            onErrorMax:"金额太大了"
        });
    });

</script>
<script>

    $(function(){
        <?php if($_GET['money']!= ''): ?>$("#money").val("<?php echo ($_GET['money']); ?>");<?php endif; ?>

        <?php if($_GET['type_id']!= ''): ?>$("#clause_type_id option[value='<?php echo ($_GET['type_id']); ?>']").prop("selected", true);<?php endif; ?>

        <?php if($clause_additive != ''): ?>get_account_tip($("#clause_type_id").val(), "<?php echo ($t); ?>", "<?php echo ($clause_additive); ?>");<?php endif; ?>

        <?php if(($clause_additive == '') && ($t!='inernal') && ($t!='flow')): ?>$("#clause_type_id").attr("disabled", "disabled");
        $("#money").attr("disabled", "disabled");<?php endif; ?>

    });

    function show_related_html(related, inflow) {
        if (related == "trade") {
            var htmltext = "订单号：<input type='text' name='trade_name' id='trade_name' value='' onclick='on_click_trade()'/>";
            htmltext += "<input type='hidden' id='trade_id' name='trade_id' value=''/>";
            htmltext += "<input type='hidden' id='related_owner_role_id' name='related_owner_role_id' value=''/>";
            $("#deta_related").html(htmltext);
            return true;
        }else if (related == "market") {
            var htmltext = "订单号：<input type='text' name='market_name' id='market_name' value=''  <?php if(!in_array('market_name', $_GET['lockele'])): ?>onclick='on_click_market()'<?php endif; ?>/>";
            htmltext += "<input type='hidden' id='market_id' name='market_id' value=''/>";
            htmltext += "<input type='hidden' id='related_owner_role_id' name='related_owner_role_id' value=''/>";
            if (inflow && inflow != "inernal") {
                htmltext += "<input type='hidden' id='"+inflow+"_id' name='"+inflow+"_id' value=''/>";
                var inflow_info = {"product":"雇员", "customer":"客户", "staff":"员工"};
                htmltext += "&nbsp;" + inflow_info[inflow];
                htmltext += "<input type='text' id='"+inflow+"_name' value='' onclick='market_select_"+inflow+"()'/>";
                if (inflow == "product") {
                    htmltext += "<input type='hidden' name='market_product_id' id='market_product_id' value=''/>";
                }
            }
            $("#deta_related").html(htmltext);

            check_form_validator("#market_id", "必须选择客户服务订单");
            if (inflow && inflow != "inernal") {
                check_form_validator("#" + inflow + "_id", "没有选择相关" + inflow_info[inflow]);
            }
            return true;
        }else if (related == "cultivate") {
            var htmltext = "订单号：<input type='text' name='cultivate_name' id='cultivate_name' value=''  <?php if(!in_array('cultivate_name', $_GET['lockele'])): ?>onclick='on_click_cultivate()'<?php endif; ?>/>";
            htmltext += "<input type='hidden' id='cultivate_id' name='cultivate_id' value=''/>";
            htmltext += "<input type='hidden' id='related_owner_role_id' name='related_owner_role_id' value=''/>";
            if (inflow && inflow != "inernal") {
                htmltext += "<input type='hidden' id='"+inflow+"_id' name='"+inflow+"_id' value=''/>";
                var inflow_info = {"product":"雇员", "customer":"客户", "staff":"员工"};
                htmltext += "&nbsp;" + inflow_info[inflow];
                htmltext += "<input type='text' id='"+inflow+"_name' value='' onclick='cultivate_select_"+inflow+"()'/>";
            }
            $("#deta_related").html(htmltext);

            check_form_validator("#cultivate_id", "必须选择培训订单");
            if (inflow && inflow != "inernal") {
                check_form_validator("#" + inflow + "_id", "没有选择相关" + inflow_info[inflow]);
            }
            return true;
        }  else if (related == "product") {
            var htmltext = "雇员：<input type='text' name='product_name' id='product_name' value='' onclick='select_product_native(this.name)'/>";
            htmltext += "<input type='hidden' id='product_id' name='product_id' value=''/>";
            $("#deta_related").html(htmltext);
            return true;
        } else if (related == "customer") {
            var htmltext = "客户：<input type='text' name='customer_name' id='customer_name' value='' onclick='select_customer_native(this.name)'/>";
            htmltext += "<input type='hidden' id='customer_id' name='customer_id' value=''/>";
            $("#deta_related").html(htmltext);
            return true;
        } else if (related == "staff") {
            var htmltext = "员工：<input type='text' name='staff_name' id='staff_name' value='' onclick='select_staff(this.name)'/>";
            htmltext += "<input type='hidden' id='staff_id' name='staff_id' value=''/>";
            $("#deta_related").html(htmltext);
            return true;
        } else {
            $("#deta_related").html("");;
        }
        return false;
    }

    function popup_related_dialog(related, inflow) {
        if (related == "trade") {
            on_click_trade();
        }else if (related == "market") {
            on_click_market();
        }else if (related == "cultivate") {
            on_click_cultivate();
        }else if (related == "customer") {
            select_customer_native('customer_name');
        }else if (related == "product") {
            select_product_native('product_name');
        }else if (related == "staff") {
            select_staff('staff_name');
        }
        return show_related_html(related, inflow);
    }

    function show_ious_related(related) {
        var htmltext = "<select id='related' name='related' onchange='popup_related_dialog(this.value);' style='width:90px'>";
        htmltext += "<option value=''>请选择</option>";
        htmltext += "<option value='trade'>产品订单</option>";
        htmltext += "<option value='market'>客户服务</option>";
        htmltext += "<option value='cultivate'>培训订单</option>";
        htmltext += "</select>";
        $("#deta_ious").html(htmltext);
    }

    function on_cash_inflow_model_change() {
        var related_model = $("#related").val();
        var inflow_model = $("#inflow_model").val();
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'<?php echo U("account/getclausetype");?>',
            'data':{
                'if': inflow_model,
                'rm': related_model,
                'im': "inernal",
                'id': -2
            },
            'success':function(data){
                var opt = "";
                if(data.status == 1 && data.data){
                    opt = "<select name='payway'>";
                    $.each(data.data, function(k, v){
                        opt += "<option value='"+ v.type_id+"'>" + v.name + "</option>";
                    });
                    opt += "</select>";
                }
                $("#cash_inflow_model").html(opt);
            },
            'complete':refresh_account_info
        });
    }

    function on_cash_related_change() {
        var related = $("#related").val();
        if (related == "other") {
            var htmltext = "<select style='width:90px' name='payway'>";
            htmltext += "<option value='cost'>成本</option>";
            htmltext += "<option value='fare'>费用</option>";
            htmltext += "</select><input type='hidden'  name='clause_additive' value='cash'/>";
            $("#cash_related_infow").html(htmltext);
        } else {
            var htmltext = "<select id='inflow_model' name='clause_additive' onchange='on_cash_inflow_model_change();' style='width:90px'>";
            htmltext += "<option value='product'>雇员</option>";
            htmltext += "<option value='customer'>客户</option>";
            htmltext += "<option value='staff'>员工</option>";
            htmltext += "</select><span id='cash_inflow_model'></span>";
            $("#cash_related_infow").html(htmltext);
        }
        on_cash_inflow_model_change();
    }

    function show_cash_related() {
        var htmltext = "<select id='related'  name='related' onchange='on_cash_related_change();' style='width:90px'>";
        htmltext += "<option value='other'>其他</option>";
        htmltext += "<option value='trade'>产品</option>";
        htmltext += "<option value='market'>客户服务</option>";
        htmltext += "<option value='cultivate'>新培训</option>";
        htmltext += "</select><span id='cash_related_infow'></span>";
        $("#deta_related").html(htmltext);
        on_cash_related_change();
    }

    $("#clause_type_id").change(function(){
        $("#deta_related").html("");
        $("#money").attr('placeholder', "");

        var clause_type = $('#clause_type_id option:selected');
        var inflow = "";
        var related = clause_type.attr("related");
        if (related == "") {
            related = clause_type.attr("inflow");
        } else {
            inflow = clause_type.attr("inflow");
        }

        show_related_html(related, inflow);
        if (popup_related_dialog(related, inflow)) {
            refresh_account_info();
            return;
        }

        var clause_type = clause_type.val();
        if (clause_type == "91" || clause_type == "96" || clause_type == "103" || clause_type == "107") {
            show_ious_related(related);
        } else if (clause_type == "86"){
            show_cash_related();
        } else {
            get_account_tip($('#clause_type_id').val(), "<?php echo ($t); ?>", $("#clause_additive").val());
        }
        refresh_account_info();
    });

    $(function() {
    <?php if($account_type != ''): ?>var related_model = "<?php echo ($account_type['related_model']); ?>";
        show_related_html(related_model);
        switch (related_model) {
            case "trade": {
                refresh_account_info();
                break;
            }
            case "market": {
                var data = {
                    'id': "<?php echo ($_GET['market_id']); ?>"
                };
                request_market_info(data, function(b){
                    on_market_info(b);
                    refresh_account_info();
                });
                break;
            }
            case "cultivate": {
                var data = {
                    'id': "<?php echo ($_GET['cultivate_id']); ?>"
                };
                request_cultivate_info(data, function(b){
                    on_cultivate_info(b);
                    refresh_account_info();
                });
                break;
            }
        }<?php endif; ?>
        <?php if(in_array('clause_type_id', $_GET['lockele'])): ?>$('#clause_type_id').mousedown(function(){return false;});<?php endif; ?>

        <?php if(is_array($_GET['lockele'])): foreach($_GET['lockele'] as $key=>$vo): ?>$("#<?php echo ($vo); ?>").attr("readonly", "readonly");<?php endforeach; endif; ?>
    });

    function refresh_account_info() {
        var account_info = "";
        var model = $("#model").html();
        account_info += model + " ";

        var clause_name = $('#clause_name').val();
        if (clause_name) {
            account_info += clause_name + " ";
            var clause_idcode = $('#clause_idcode').val();
            if (clause_idcode) {
                account_info += "[" + clause_idcode + "] ";
            }
        }
        account_info += "<?php echo (acction_type_desc($dire)); ?>" + " ";

        var clause_type_id = $('#clause_type_id option:selected').val();
        if (clause_type_id) {
            account_info += $('#clause_type_id option:selected').html() + " ";
        }
        account_info += $('#money').val() + " ";

        var related = $('#related option:selected').val();
        if (related) {
            account_info += $('#related option:selected').html() + " ";
        }

        var trade_name = $('#trade_name').val();
        if (trade_name) {
            account_info += trade_name + " ";
        }

        $("#account_info").html(account_info);
        if ($("#clause_additive").val() != "") {
            $("#clause_type_id").removeAttr("disabled");
            $("#money").removeAttr("disabled");
        }
    }
</script>

<?php if(($clause_additive == '') && ($t!='inernal')): ?><script>
    $(function() {
        $("#clause_additive").formValidator({
            empty: false,
            onFocus: " ",
            onCorrect: function () {
                $("#clause_type_id").removeAttr("disabled");
                $("#money").removeAttr("disabled");
                return "<span style='color:green;'>√</span>";
            }
        }).inputValidator({
            min: 1, max: 255,
            onErrorMin: function(){
                $("#clause_type_id").attr("disabled", "disabled");
                $("#money").attr("disabled", "disabled");
                return "不能为空！";
            },
            onErrorMax: ""
        });
    });
</script><?php endif; ?>
<script>
    $(function() {
        $("#clause_type_id").formValidator({
            onShow: "",
            empty:false,
            onFocus:" ",
            onCorrect:"<span style='color:green;'>√</span>",
        }).inputValidator({
            min:1,max:255,
            fun:"必须选择账目类型！",
            onErrorMin:"必须选择账目类型！",
            onErrorMax:""
        });
    });
    $(function() {refresh_account_info();});

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