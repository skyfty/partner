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
<body data-spy="scroll" data-target=".bs-docs-sidebar" data-twttr-rendered="true" style="background-color: white">
<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <div></div>
            <table class="table table-hover" id="staff_dialog_table">
                <thead>
                <tr>
                    <th style="width: 10px">&nbsp;</th>
                    <th>编号</th>
                    <th>名称</th>
                    <th>门店</th>
                    <th>岗位</th>
                    <th>职务</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script id="staff-checkbox" type="text/x-jquery-tmpl">
<input type="radio" name="staff" value="${staff_id}" />
</script>

<script>
    var reqparam = art.dialog.data('reqparam');
    var urlajax = {
        "url": "<?php echo U('Staff/listDialog');?>"
    };
    if (reqparam) {
        urlajax['data'] = reqparam;
    }
    $('#staff_dialog_table').dataTable({
        ajax:urlajax,
        "columnDefs": [
            {
                "bSortable": false,
                "targets": 0,
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).html($('#staff-checkbox').tmpl(cellData));
                }
            }
        ],
        "pageLength": 7,
        "scrollX": true,
        "serverSide": true,
        "processing": true,
        "order": [[ 1, "desc" ]],
        'language': def_dataTable_lang_opt
    });
</script>
</body>