<?php if (!defined('THINK_PATH')) exit();?><form class="form-horizontal" id="nav_edit_form"  action="<?php echo U('navigation/edit');?>" method="post">
	<div class="control-group">
		<label class="control-label"><?php echo L('HEADLINE');?></label>
		<div class="controls">
			<input class="span3" type="text" name="title" id="title" value="<?php echo ($menu["title"]); ?>"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="name"><?php echo L('NAVIGATION_POSITION');?></label>
		<div class="controls">
			<select name="postion" >
				<option value="top"<?php if($menu["postion"] == 'top'): ?>selected="selected"<?php endif; ?>><?php echo L('TOP');?></option>
				<option value="more"<?php if($menu["postion"] == 'more'): ?>selected="selected"<?php endif; ?>><?php echo L('MORE');?></option>
				<option value="user"<?php if($menu["postion"] == 'user'): ?>selected="selected"<?php endif; ?>><?php echo L('SYSTEM');?></option>
			</select>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="name"><?php echo L('THE_LINK_ADDRESS');?></label>
		<div class="controls">
			<input type="text" id="url" name="url" value="<?php echo ($menu["url"]); ?>"/>
			<input type="hidden" id="id" name="id" value="<?php echo ($menu["id"]); ?>"/>
			<input type="hidden" id="bylea" name="bylea" value="<?php echo ($_GET['bylea']); ?>"/>

		</div>
	</div>
</form>