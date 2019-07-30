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

	<link rel="stylesheet" href="__PUBLIC__/treeview/jquery.treeview.css" type="text/css">
	<script type="text/javascript" src="__PUBLIC__/treeview/jquery.treeview.js"></script>
	<script type="text/javascript" src="__PUBLIC__/treeview/jquery.treeview.edit.js"></script>
	
	<script type="text/javascript">
		$(function() {
			$("#browser").treeview();
			$("#add").click(function() {
				var branches = $("<li><span class='folder'>New Sublist</span><ul>" +
					"<li><span class='file'>Item1</span></li>" +
					"<li><span class='file'>Item2</span></li></ul></li>").appendTo("#browser");
				$("#browser").treeview({
					add: branches
				});
				branches = $("<li class='closed'><span class='folder'>New Sublist</span><ul><li><span class='file'>Item1</span></li><li><span class='file'>Item2</span></li></ul></li>").prependTo("#folder21");
				$("#browser").treeview({
					add: branches
				});
			});
		})
	</script>
	
	<style type="text/css">
.ztree li span.button.add {margin-left:2px; margin-right: -1px; background-position:-144px 0; vertical-align:top; *vertical-align:middle}
	</style>
	<div class="container">
		<div class="page-header">
			<h4><?php echo L('STRUCTURE');?> -
				<small>
				<a href="<?php echo U('user/department');?>" <?php if(ACTION_NAME == 'department' or ACTION_NAME == 'role'): ?>class="active"<?php endif; ?>><?php echo L('STRUCTURE');?></a>
                </small>
				<?php echo branch_select_html("bybr", $_GET['bybr'], true);?>&nbsp;&nbsp;
				<script>
					$(function() {
						$("#bybr").change(function(){
							var val = $(this).val();
							window.location.replace("<?php echo U('user/role', 'status='.$_GET['status'].'&bybr=');?>" + val);
						});
					});
				</script>
			</h4>
		</div>
		<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
		
		<div class="row">
			<form action="<?php echo U('user/department_delete');?>" method="post" onsubmit="return confirm('<?php echo L('CONFIRM_DELETE');?>')">
			<div class="span12">
				<div class="bulk-actions align-left">
					<a href="<?php echo U('user/department');?>" <?php if(ACTION_NAME == 'department'): ?>class="active"<?php endif; ?>>
                    <?php echo L('DEPARTMENT_TREE');?></a> |
					<a href="<?php echo U('user/role');?>" <?php if(ACTION_NAME == 'role'): ?>class="active"<?php endif; ?>>
                    <?php echo L(SUPERIOR_SUBORDINATE_TREE);?></a>
                    [<a href="<?php echo U('user/role', 'status=1&bybr='.$_GET['bybr']);?>" <?php if($_GET['status'] == '1'): ?>class="active"<?php endif; ?>>
					在职</a>]
                    [<a href="<?php echo U('user/role', 'status=0,2&bybr='.$_GET['bybr']);?>" <?php if($_GET['status'] == '0,2'): ?>class="active"<?php endif; ?>>
					不在职</a>]

					<div class="pull-right">
						<?php if(session('?admin')): ?><a id="add_department" class="btn btn-primary">
                                <i class="icon-plus"></i>&nbsp; <?php echo L('ADD_DEPARTMENT');?></a>&nbsp;
							<a id="add_role" class="btn btn-primary">
                                <i class="icon-plus"></i>&nbsp; <?php echo L('ADD_POSITION');?></a><?php endif; ?>
					</div>
				</div>
			</div>
			<div class="span12">
				<?php echo ($tree_code); ?>
			</div> 
		</div>
		</form>
	</div>


<script type="text/javascript">
    $("#add_department").click(function(){
        var dialog = art.dialog({
            id: 'N3690',
            title: "<?php echo L('ADD_DEPARTMENT');?>",
            lock:true,
            ok: function () {
                $("#department_add").submit();
            },
            cancel:true
        });
        $.ajax({
            url:'<?php echo U("user/department_add");?>',
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });

    });


    $("#add_role").click(function(){
        var dialog = art.dialog({
            id: 'N3690',
            title: "<?php echo L('ADD_POSITION');?>",
            lock:true,
            ok: function () {
                $("#role_add").submit();
            },
            cancel:true
        });
        $.ajax({
            url:'<?php echo U("user/role_add");?>',
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });

    });


    $(".position_edit").click(function(){
        var id = $(this).attr('rel');
        var dialog = art.dialog({
            id: 'N3690',
            title: "授权",
            lock:true,
            ok: function () {
                $("#roleedit").submit();
            },
            cancel:true
        });
        $.ajax({
            url:'<?php echo U("user/roleedit","id=");?>'+id,
            success: function (data) {
                dialog.content(data);
            },
            cache: false
        });
    });

    $(".position_delete").click(function(){
        var self = this;
        art.dialog.confirm("<?php echo L('CONFIRM_TO_DELETE_POSITION');?>", function () {
            id = $(self).attr('rel');
            window.location="<?php echo U('user/role_delete','id=');?>"+id;
        });

    });

</script>
<script type="text/javascript">

	
	$(function(){
		$(".treeview .file").hover(function(){
			rel = $(this).attr('rel');
			$('#control_file' + rel).show();
		},function(){
			rel = $(this).attr('rel');
			$('#control_file' + rel).hide();
		});
		$(".treeview .folder").hover(function(){
			rel = $(this).attr('rel');
			$('#control_folder' + rel).show();
		},function(){
			rel = $(this).attr('rel');
			$('#control_folder' + rel).hide();
		});
		$("#check_all").click(function(){
			$("input[class='check_list']").prop('checked', $(this).prop("checked"));
		});

		$(".permission").click(function(){
            id = $(this).attr('rel');
            art.dialog.open('<?php echo U("permission/user_authorize","position_id=");?>'+id, {
                id: 'N3690',
                title: "授权",
                lock:true,
                fixed:true,
                width: 1030,
				height:400,
                ok: function () {
                    var iframe = $(this.iframe.contentWindow.document);
					var perlist = [];
					iframe.find('input:checkbox:checked').each(function(key,val){
						perlist.push($(val).val())
					});
					if(perlist && perlist.length > 0){
						var perlist_str = perlist.join(',') ;
					}else{
						var perlist_str = '' ;
					}
					$.get('<?php echo U("permission/user_authorize");?>',{perlist:perlist_str, auth:1,position_id:id}, function(data){
                        art.dialog.tips(data.data);

                    }, 'json');
                },
                cancel: true
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