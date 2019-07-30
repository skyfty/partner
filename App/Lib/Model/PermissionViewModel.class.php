<?php 
class PermissionViewModel extends ViewModel{

	public $viewFields = array(
			'permission'=>array(
				'url',
				'_on'=>'position.position_id=permission.position_id',
				'_type'=>'LEFT',
			),
			'position'=>array(
				'name'=>'role_name',
				'_type'=>'LEFT',
				'_on'=>'permission.position_id=position.position_id',
			),
			'role'=>array(
				'user_id',
				'role_id',
				'position_id',
				'_type'=>'LEFT',
				'_on'=>'position.position_id=role.position_id',
			)
	);
}