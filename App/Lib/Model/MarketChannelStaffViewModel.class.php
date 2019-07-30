<?php
	class MarketChannelStaffViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $main_field = array(
                'market_id',
                'market_channel_id',
                'channel_model_id',
                'channel_model',
                'isdefault',
            );
            $main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'market_channel','is_main'=>1))->getField('field', true),$main_field));
            $main_field['_type'] = 'LEFT';

            $model_field = array(
                'staff_id',
                'name'=>'staff_name',
                'idcode'=>'staff_idcode',
            );
            $model_field = array_unique(array_merge(M('Fields')->where(array('model'=>'staff','is_main'=>1))->getField('field', true),$model_field));
            $model_field['_type'] = 'LEFT';
            $model_field['_on'] = 'staff.staff_id = market_channel.channel_model_id';


            $this->viewFields = array(
                'market_channel'=>$main_field,
                'market'=>array(
                    'idcode'=>'market_idcode',
                    'customer_id'=>'market_customer_id',
                    'status_id',
                    'category_id',
                    'product_count',
                    'is_cancel_submit',
                    'corre',
                    'owner_role_id',
                    'league_id',
                    '_on'=>'market_channel.market_id = market.market_id',
                    '_type'=>'LEFT'
                ),
                'market_status'=>array(
                    'name'=>'status_name',
                    '_on'=>'market_status.status_id=market.status_id',
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