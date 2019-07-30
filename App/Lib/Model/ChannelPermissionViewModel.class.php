<?php
class ChannelPermissionViewModel extends ViewModel{
	public $viewFields = array(
			'permission'=>array(
					'permission_id',
					'position_id',
					'url',
					'_type'=>'LEFT',
			),
			'position'=>array(
					'name'=>'position_name',
					'parent_id',
					'_type'=>'LEFT',
					'_on'=>'permission.position_id=position.position_id',

			),
			'role'=>array(
					'user_id',
					'role_id',
					'_type'=>'LEFT',
					'_on'=>'role.position_id=position.position_id',

			),


	);
}