<?php 
	class UserPermissionViewModel extends ViewModel{
		public $viewFields = array(
			'user'=>array(
				'user_id',
				'staff_id',
				'_type'=>'LEFT'
			),
			'role'=>array(
				'user_id',
				'role_id',
				'position_id',
				'_on'=>'user.user_id=role.user_id',
				'_type'=>'LEFT'
			),
			'permission'=>array(
				'url',
				'_on'=>'role.position_id=permission.position_id',
				'_type'=>'LEFT'
			),
		);
	}