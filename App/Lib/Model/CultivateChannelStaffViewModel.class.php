<?php
	class CultivateChannelStaffViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $main_field = array(
                'cultivate_id',
                'cultivate_channel_id',
                'channel_model_id',
                'channel_model',
                'isdefault',
            );
            $main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'cultivate_channel','is_main'=>1))->getField('field', true),$main_field));
            $main_field['_type'] = 'LEFT';

            $model_field = array(
                'staff_id',
                'name'=>'staff_name',
                'idcode'=>'staff_idcode',
            );
            $model_field = array_unique(array_merge(M('Fields')->where(array('model'=>'staff','is_main'=>1))->getField('field', true),$model_field));
            $model_field['_type'] = 'LEFT';
            $model_field['_on'] = 'staff.staff_id = cultivate_channel.channel_model_id';


            $this->viewFields = array(
                'cultivate_channel'=>$main_field,
                'cultivate'=>array(
                    'idcode',
                    'idcode'=>'cultivate_idcode',
                    'customer_id'=>'cultivate_customer_id',
                    'status_id',
                    'settle_state',
                    'category_id',
                    'product_count',
                    'is_cancel_submit',
                    'model'=>"corre",
                    'model',
                    'model_id',
                    'owner_role_id',
                    'league_id',
                    '_on'=>'cultivate_channel.cultivate_id = cultivate.cultivate_id',
                    '_type'=>'LEFT'
                ),
                'currier'=>array(
                    'name'=>'currier_name',
                    "idcode"=>'currier_idcode',
                    "category_id"=>'category_id',
                    '_on'=>'cultivate.currier_id=currier.currier_id',
                    '_type'=>'LEFT'
                ),
                'currier_category'=>array(
                    'currier_category_id',
                    'name'=>'category_name',
                    '_on'=>'currier.category_id=currier_category.currier_category_id',
                    '_type'=>'LEFT'
                ),
                'cultivate_status'=>array(
                    'name'=>'status_name',
                    '_on'=>'cultivate_status.status_id=cultivate.status_id',
                    '_type'=>'LEFT'
                ),
                'staff'=>$model_field,
                'user'=>array(
                    '_on'=>'staff.user_id=user.user_id', '_type'=>'LEFT'
                ),
                'user_category'=>array(
                    'name'=>'category_name',
                    'category_id',
                    '_on'=>'user.category_id=user_category.category_id', '_type'=>'LEFT'
                ),
                'position'=>array(
                    'name'=>'role_name',
                    'parent_id',
                    'department_id',
                    'description',
                    '_on'=>'position.position_id=staff.position_id',
                    '_type'=>'LEFT'
                ),
                'role_department'=>array(
                    'name'=>'department_name',
                    '_on'=>'role_department.department_id=position.department_id'
                )
            );
		}

    }