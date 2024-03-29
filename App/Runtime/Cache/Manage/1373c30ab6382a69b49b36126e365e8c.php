<?php if (!defined('THINK_PATH')) exit();?><!-- 公告 -->		
<div class="span6">
	<div class="dash-border">
		<div class="dash-title">
			<img src="__PUBLIC__/img/index_notice.png" style="width:17.5px;" />&nbsp;&nbsp;<?php echo L('SYSTEM_OF_ANNOUNCEMENT');?>
			<a href="<?php echo U('announcement/index');?>" class="dash-swtich">切换到公告列表 >></a>
		</div>
		<div class="cut-line"></div>
		<div class="content-item" id="announcement_item">
			<div class="content-main" id="announcement_main">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#announcement_main').html('<span class="error_msg">拼命加载中...</span>')
	$.ajax({
		type: "get",
		url: "<?php echo U('announcement/getAnnouncement');?>",    
		dataType: "json",
		success: function(result){
			if(result.data != null || result.data != ''){
				$('#announcement_main').remove();
				var nowtime = Math.round(new Date().getTime()/1000);//将js时间戳转换为unix时间戳
				
				$.each(result.data, function(item,val) {
					var list_html = '';
					list_html += '<div class="content-main">';
					list_html += (item+1)+'、<a href="<?php echo U("announcement/view","id=");?>'+val.announcement_id+'" id="show_announcement" style="color:#'+val.color+'">'+val.title+'</a>';
					if((nowtime - val.update_time) < (86400*7)){
						list_html += '&nbsp;<img src="__PUBLIC__/img/new.gif">';
					}
					list_html += '</div>';
					var update_time = new Date(val.update_time*1000);
					list_html += '<div class="content-time">'+update_time.getFullYear()+'-'+(update_time.getMonth()+1)+'-'+update_time.getDate()+'</div>';
					$('#announcement_item').append(list_html);
				});
			}else{
				$('#announcement_main').html('<span class="error_msg">---暂无数据---</span>');
			}
		},
		error: function(errorMsg){
			$('#announcement_main').html('<span class="error_msg">获取信息失败...</span>');
		}
	});
</script>
<!-- 公告 END-->