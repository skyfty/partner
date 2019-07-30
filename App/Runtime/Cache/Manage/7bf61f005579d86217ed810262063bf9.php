<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
    .navbar .nav>li>a {
        padding: 10px 6px 10px

    }
</style>

<div class="nav-collapse collapse">

	<ul class="nav top-nav" >
		<?php if(is_array($top)): $i = 0; $__LIST__ = $top;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo['module'] == 'staff') or ($vo['module'] == 'branch')): ?><li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('staff', 'branch', 'user'))): ?>active<?php endif; ?>' >
                    <a class="dropdown-toggle" href="<?php echo U('staff/index');?>" style="color: #ffffff;float:left;padding-right:2px">公司管理 </a>
                    <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if(session('?admin') or vali_permission('staff', 'index')): ?><li><a href="<?php echo U('staff/index');?>" class="dropdown-menu-item">员工管理</a></li><?php endif; ?>

                        <?php if(session('?admin') or vali_permission('branch', 'index')): ?><li><a href="<?php echo U('branch/index');?>" class="dropdown-menu-item">门店管理</a></li><?php endif; ?>

                        <?php if(session('?admin')): ?><li class="divider"></li>
                        <li><a href="<?php echo U('user/role');?>" class="dropdown-menu-item">权限分配</a></li><?php endif; ?>
                    </ul>
                </li>
            <?php elseif($vo['module'] == 'account'): ?>
                <li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('account'))): ?>active<?php endif; ?>' >
                <a class="dropdown-toggle" href="<?php echo U('account/index');?>" style="color: #ffffff;float:left;padding-right:2px">账户管理 </a>
                <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo U('account/market', 't=market');?>" class="dropdown-menu-item">客户服务收入确认</a></li>
                    <li><a href="<?php echo U('account/cultivate', 't=cultivate');?>" class="dropdown-menu-item">培训收入确认</a></li>
                    <li><a href="<?php echo U('account/inernal', 't=inernal');?>" class="dropdown-menu-item">公司账户</a></li>
                    <li><a href="<?php echo U('account/customer', 't=customer');?>" class="dropdown-menu-item">客户账户</a></li>
                    <li><a href="<?php echo U('account/product', 't=product');?>" class="dropdown-menu-item">雇员账户</a></li>
                    <li><a href="<?php echo U('account/staff', 't=staff');?>" class="dropdown-menu-item">员工账户</a></li>
                    <li><a href="<?php echo U('account/flow', 't=flow');?>" class="dropdown-menu-item">公司流水</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('account/analytics');?>" class="dropdown-menu-item">统计</a></li>
                    <li><a href="<?php echo U('account/logger');?>" class="dropdown-menu-item">日志</a></li>
                </ul>
                </li>
            <?php elseif(in_array($vo['module'],array('serve','trade'))): ?>
                <li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('serve','trade'))): ?>active<?php endif; ?>' >
                <a class="dropdown-toggle" href="<?php echo U('trade/index');?>" style="color: #ffffff;float:left;padding-right:2px">产品管理 </a>
                <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo U('trade/index');?>" class="dropdown-menu-item">产品订单</a></li>
                    <li><a href="<?php echo U('serve/index');?>" class="dropdown-menu-item">产品列表</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('trade/index', 'act=group');?>" class="dropdown-menu-item">分组</a></li>
                    <li><a href="<?php echo U('trade/cost');?>" class="dropdown-menu-item">成本</a></li>
                </ul>
                </li>
            <?php elseif(in_array($vo['module'],array('dorm','berth'))): ?>
                <li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('dorm','berth'))): ?>active<?php endif; ?>' >
                <a class="dropdown-toggle" href="<?php echo U('berth/index');?>" style="color: #ffffff;float:left;padding-right:2px">床位管理 </a>
                <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <?php if(session('?admin') or vali_permission('berth', 'index')): ?><li><a href="<?php echo U('berth/index');?>" class="dropdown-menu-item">床位管理</a></li><?php endif; ?>
                    <?php if(session('?admin') or vali_permission('dorm', 'index')): ?><li><a href="<?php echo U('dorm/index');?>" class="dropdown-menu-item">宿舍管理</a></li>
                    <li><a href="<?php echo U('berth/mind');?>" class="dropdown-menu-item">宿舍导图</a></li><?php endif; ?>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('berth/index', 'act=group');?>" class="dropdown-menu-item">分组</a></li>
                </ul>
                </li>
            <?php elseif(in_array($vo['module'],array('currier','cultivate'))): ?>
                <li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('currier','cultivate'))): ?>active<?php endif; ?>' >
                <a class="dropdown-toggle" href="<?php echo U('cultivate/index');?>" style="color: #ffffff;float:left;padding-right:2px">培训管理 </a>
                <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo U('cultivate/index');?>" class="dropdown-menu-item">培训订单</a></li>
                    <li><a href="<?php echo U('currier/index');?>" class="dropdown-menu-item">培训列表</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('cultivate/index', 'act=group');?>" class="dropdown-menu-item">分组</a></li>
                </ul>
                </li>
            <?php elseif(in_array($vo['module'],array('dispatch'))): ?>
                <li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('dispatch'))): ?>active<?php endif; ?>' >
                <a class="dropdown-toggle" href="<?php echo U('dispatch/index');?>" style="color: #ffffff;float:left;padding-right:2px">调度管理 </a>
                <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo U('dispatch/index');?>" class="dropdown-menu-item">入职总表</a></li>
                    <li><a href="<?php echo U('dispatch/index','act=branch');?>" class="dropdown-menu-item">门店调度</a></li>
                    <li><a href="<?php echo U('dispatch/index','act=hospital');?>" class="dropdown-menu-item">医院调度列表</a></li>

                </ul>
                </li>
            <?php elseif(in_array($vo['module'],array('commiss'))): ?>
                <li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('commiss'))): ?>active<?php endif; ?>' >
                <a class="dropdown-toggle" href="<?php echo U('commiss/index');?>" style="color: #ffffff;float:left;padding-right:2px">
                    客服管理
                    <div  style=" border-radius:25px;position:absolute;width:30px; height:20px;display: none; background-color:#FF0000; z-index:1000;top:30px;color:#FFFFFF;text-align:center" id="main_nav_<?php echo ($vo['id']); ?>"></div>
                    <div  style=" border-radius:25px;position:absolute;width:30px; height:20px;display: none; background-color:#FF0000; z-index:1000;top:30px;left:40px;color:#FFFFFF;text-align:center" id="main_nav_genjin_<?php echo ($vo['id']); ?>"></div>

                </a>
                <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo U('commiss/index');?>" class="dropdown-menu-item">客服管理</a></li>
                    <li><a href="<?php echo U('commiss/logger');?>" class="dropdown-menu-item">日志</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('commiss/index', 'act=group');?>" class="dropdown-menu-item">分组</a></li>
                </ul>
                </li>
            <?php elseif(in_array($vo['module'],array('market'))): ?>
                <li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('market'))): ?>active<?php endif; ?>' >
                <a class="dropdown-toggle" href="<?php echo U('market/index');?>" style="color: #ffffff;float:left;padding-right:2px">
                    客户服务
                </a>
                <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo U('market/index', 'assort=xs');?>" class="dropdown-menu-item">销售视图</a></li>
                    <li><a href="<?php echo U('market/index', 'assort=cb');?>" class="dropdown-menu-item">成本视图</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('market/index', 'act=group');?>" class="dropdown-menu-item">分组</a></li>
                    <li><a href="<?php echo U('market/logger');?>" class="dropdown-menu-item">日志</a></li>
                </ul>
                </li>

            <?php elseif(in_array($vo['module'],array('product'))): ?>
                <li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('product'))): ?>active<?php endif; ?>' >
                <a class="dropdown-toggle" href="<?php echo U('product/index');?>" style="color: #ffffff;float:left;padding-right:2px">
                    雇员管理
                </a>
                <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo U('product/index');?>" class="dropdown-menu-item">雇员列表</a></li>
                    <li><a href="<?php echo U('product/index', 'queue_category_id=6&assort=zb&act=dispatch');?>" class="dropdown-menu-item">调度</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('product/index', 'act=group');?>" class="dropdown-menu-item">分组</a></li>
                    <li><a href="<?php echo U('product/logger');?>" class="dropdown-menu-item">日志</a></li>
                </ul>
                </li>

            <?php elseif(in_array($vo['module'],array('league'))): ?>
                <?php if(session('?admin') and (session('league_id') == 0)): ?><li class='dropdown <?php if(in_array(strtolower(MODULE_NAME),array('league'))): ?>active<?php endif; ?>' >
                <a class="dropdown-toggle" href="<?php echo U('league/index');?>" style="color: #ffffff;float:left;padding-right:2px">
                    加盟商管理
                </a>
                <a  class="dropdown-toggle" data-toggle="dropdown" style="float:right;padding-right:8px;padding-left:2px;cursor: pointer" ><b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo U('league/index');?>" class="dropdown-menu-item">加盟商列表</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('league/index', 'act=group');?>" class="dropdown-menu-item">分组</a></li>
                    <li><a href="<?php echo U('league/logger');?>" class="dropdown-menu-item">日志</a></li>
                </ul>
                </li><?php endif; ?>
            <?php elseif($vo["title"] != ''): ?>
                <li
                    <?php if(strtolower(MODULE_NAME) == strtolower($vo['module'])): ?>class="active"
                    <?php else: ?>
                        <?php if((strtolower(MODULE_NAME) == 'trade') && ($vo['module'] == 'serve')): ?>class="active"<?php endif; endif; ?>>
                    <a  href="<?php echo ($vo["url"]); ?>" style="color: #ffffff">
                        <?php echo ($vo["title"]); ?>
                        <?php if(($vo['title'] == '雇员管理') || ($vo['title'] == '客户管理') || ($vo['title'] == '客服管理')): ?><div  style=" border-radius:25px;position:absolute;width:30px; height:20px;display: none; background-color:#FF0000; z-index:1000;top:30px;color:#FFFFFF;text-align:center" id="main_nav_<?php echo ($vo['id']); ?>"></div><?php endif; ?>
                    </a>
                </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>				

	</ul>
	<ul class="nav pull-right">
		<li style=" width: auto;margin-right: 5px;">
            <a  style="padding: 10px 0px;width: auto;color:#fff" href="<?php echo U('Staff/myinfo');?>">
                <i class="icon-user"></i><?php if(session('league_name')): ?>[<?php echo (session('league_name')); ?>]<?php endif; ?> <?php echo (session('name')); ?>
            </a>
        </li>
		<li>
            <a href="<?php echo U('message/index');?>" title="<?php echo L('NEW_MESSAGE');?>">
                <span id="message_tips" style="color:#fff;">
                    <i class="icon-envelope"></i>
                    <span id="message_num">0</span>
                </span>
            </a>
        </li>
        <li>
            <a href="<?php echo U('task/index', 'by=me');?>" title="<?php echo L('MY_TASK');?>">
                <span id="task_tips" style="color:#fff;">
                    <i class="icon-tasks"></i> <span id="task_num">0</span>
                </span>
            </a>
        </li>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding: 10px;color: #ffffff"><?php echo L('SYSTEM');?><b class="caret"></b></a>
			<ul class="dropdown-menu">							
				<?php if(is_array($user)): $i = 0; $__LIST__ = $user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo["title"] != ''): ?><li><a href="<?php echo ($vo["url"]); ?>"  class="dropdown-menu-item"><?php echo ($vo["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <?php if(session('?admin')): ?><li><a href="<?php echo U('channel/index');?>">渠道管理</a></li>
                    <li><a href="<?php echo U('index/stafflog');?>">操作日志</a></li><?php endif; ?>

                <li class="divider"></li>
				<li><a href="<?php echo U('user/logout');?>"  class="dropdown-menu-item"><?php echo L('EXIT');?></a></li>
			</ul>
		</li>
	</ul>
	<div class="nav_menu_tool_tips" close-status="open">
		<div class="tips_icon_close"><a href="javascript:void(0);" id="close_tips">X</a></div>
		<div class="tips_panel">
			<div class="tips_item" id="message_item">
				<span class="tips_count">0</span> 封新站内信<span class="tips_link"><a href="<?php echo U('message/index');?>">查看站内信</a></span>
			</div>
            <div class="tips_item" id="task_item">
                <span class="tips_count">0</span> 个新任务提醒<span class="tips_link"><a href="<?php echo U('task/index');?>">查看任务</a></span>
            </div>
		</div>
	</div>
</div>

<div id="message" class="hide"><p id="tips"></p></div>
<div class="hide" id="dialog-message-send" title="<?php echo L('WRITE_LETTER');?>">loading...</div>
<script type="text/javascript">

$("#dialog-message-send").dialog({
    autoOpen: false,
    modal: true,
	width: 800,
	maxHeight: 600,
	position: ["center",100]
});

var a = 1;
function fn(){
	if(a == 1){
		$('#message_tips').css({color:'#fff'});
		a = 0;
	}else{
		$('#message_tips').css({color:'#D2D2D2'});
		a = 1;
	}
}
var myInterval;

function message_tips(){
	$.get("<?php echo U('message/tips');?>", function(data){

		if((data.data['message'] != $('#message_tips #message_num').html()) && (data.data['message'] != 0)){
			$('#message_item').css({display:'block'});	//显示站内信卡片
            $('#message_tips').css({color:'#fff'});
            myInterval = setInterval(fn,1000);
			$("#message #tips").html("<audio id='ttsoundplayer'  autoplay='autoplay'><source src='Public/sound/Global.wav' type='audio/wav'></audio>");
		} else {
			$("#message #tips").html('');
			if(data.data['message'] == 0){
                $('#message_tips').css({color:'#D2D2D2'});

                $('#message_item').css({display:'none'});	//隐藏站内信卡片
				clearInterval(myInterval);
			}
		}

        //导航提醒设置颜色
        if(data.data['task'] != '0'){
            $('#task_tips').css({color:'#fff'});

        }else{
            $('#task_tips').css({color:'#D2D2D2'});
        }
        if(data.data['task_count'] != '0'){
            $('#task_item').css({display:'block'});	//显示任务卡片
        }else{
            $('#task_item').css({display:'none'});	//隐藏任务卡片
        }
        $('#task_tips #task_num').html(data.data['task_count']);

        var closeStatus = $('.nav_menu_tool_tips').attr('close-status');	//卡片提示状态
        if(data.status == 1 && (data.data.message != 0 || data.data.task_count != 0 ) && closeStatus == 'open'){
            $('.nav_menu_tool_tips').css({display:'block'});
        }else{
            $('.nav_menu_tool_tips').css({display:'none'});
        }

        //导航提醒实时写入数值
		$('#message_tips #message_num').html(data.data['message']);

		//卡片提醒实时写入数值
		$('#message_item .tips_count').html(data.data['message']);

		//根据站内信、任务、日程、合同是否存在数据来判断是否显示卡片提示
		var closeStatus = $('.nav_menu_tool_tips').attr('close-status');	//卡片提示状态
		if(data.status == 1 && data.data.message != 0 && closeStatus == 'open'){
			$('.nav_menu_tool_tips').css({display:'block'});
		}else{
			$('.nav_menu_tool_tips').css({display:'none'});
		}
	},'json');
}


$(function(){
	message_tips();
	
	$("#header_send_message").click(function(){
		$('#dialog-message-send').dialog('open');
		$('#dialog-message-send').load('<?php echo U("message/send");?>');
	});
	
	/** 点击卡片提醒关闭按钮，永久关闭任务、日程、合同，暂时关闭站内信 */
	$('#close_tips').click(function(){
		$('.nav_menu_tool_tips').attr('close-status','closed');
		$('.nav_menu_tool_tips').css({display:'none'});
	});
});
</script>