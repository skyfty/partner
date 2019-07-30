<?php
class ChannelPositionPermissionViewModel extends ViewModel{
	public $viewFields = array(
			'position'=>array(
					'name'=>'position_name',
					'parent_id',
					'department_id',
					'description',
					'_type'=>'LEFT'
			),
			'permission'=>array(
					'permission_id',
					'position_id',
					'url',
					'_type'=>'LEFT',
					'_on'=>'position.position_id=permission.position_id',
			),

			'role_department'=>array(
					'name'=>'department_name',
					'_on'=>'position.department_id=role_department.department_id'
			),
	);
}