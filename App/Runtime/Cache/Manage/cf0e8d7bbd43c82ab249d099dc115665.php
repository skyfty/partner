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
    <link type="text/css" href="http://apps.bdimg.com/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" />

    <link class="docs" href="__PUBLIC__/css/docs.css?t=20140830" rel="stylesheet"/>

    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>
    <script src="http://apps.bdimg.com/libs/bootstrap/2.3.2/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="http://apps.bdimg.com/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/jquery.tmpl.min.js?t=20140830" type="text/javascript"></script>
    <script src="__PUBLIC__/js/5kcrm_zh-cn.js?t=20140830" type="text/javascript"></script>

    <script src="__PUBLIC__/js/5kcrm.js?t=20140830" type="text/javascript"></script>
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

    <link  href="__PUBLIC__/cropper/cropper.css" rel="stylesheet">
    <script src="__PUBLIC__/cropper/cropper.js"></script>

    <link  href="__PUBLIC__/qtip/jquery.qtip.min.css" rel="stylesheet">
    <script src="__PUBLIC__/qtip/jquery.qtip.min.js"></script>

    <script type="text/javascript" src="__PUBLIC__/js/jquery.artDialog.js?skin=default"></script>
    <script type="text/javascript" src="__PUBLIC__/js/iframeTools.js"></script>
    <script type="text/javascript" src="__PUBLIC__/js/formValidatorRegex.js" charset="UTF-8"></script>

    <link type="text/css" href="__PUBLIC__/datatables/css/jquery.dataTables.min.css" rel="stylesheet"/>
    <script src="__PUBLIC__/datatables/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/my.js" type="text/javascript"></script>
</head>
<body style="background-color: white">
<div class="container">
    <div class="row">
        <div class="span12">
            <table class="table table-hover" id="trade_dialog_table">
                <thead>
                <tr>
                    <th style="width: 10px">&nbsp;</th>
                    <th>订单编号</th>
                    <th>产品名称</th>
                    <th>相关方</th>
                    <th>总金额</th>
                    <th>未付金额</th>
                    <th>白条金额</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script id="trade-checkbox" type="text/x-jquery-tmpl">
<input type="radio" name="trade_id" value="${trade_id}" />
</script>

<script id="trade-corre-info" type="text/x-jquery-tmpl">
<a href="${corre_link}" target="_blank">
    [${idcode}]${name}
</a>
</script>

<script>
    $('#trade_dialog_table').dataTable({
        ajax: {
            "url": "<?php echo U('trade/listDialog', 'corre='.$_GET['corre'].'&id='.$_GET['id']);?>"
        },
        "columnDefs": [
            {
                "bSortable": false,
                "targets": 0,
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).html($('#trade-checkbox').tmpl(cellData));
                }
            },
            {
                "bSortable": false,
                "targets": 3,
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).html($('#trade-corre-info').tmpl(cellData));
                }
            }
        ],
        "scrollX": true,
        "serverSide": true,
        "processing": true,
        "order": [[ 1, "desc" ]],
        'language': def_dataTable_lang_opt
    });
</script>
</body>