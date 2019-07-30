<?php
	class MarketUrgeViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $main_field = array(
                'market_id',
                'market_urge_id',
                'isdefault',
                'urge_branch_ratio',
            );
            $main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'market_urge','is_main'=>1))->getField('field', true),$main_field));
            $main_field['_type'] = 'LEFT';

            $staff_main_field = array(
                'staff_id',
                'name'=>'staff_name'
            );
            $staff_main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'staff','is_main'=>1))->getField('field', true),$staff_main_field));
            $staff_main_field['_type'] = 'LEFT';
            $staff_main_field['_on'] = 'user.user_id = staff.user_id';

            $this->viewFields = array(
                'market_urge'=>$main_field,
                'market'=>array(
                    'idcode'=>'market_idcode',
                    'customer_id',
                    'status_id',
                    'category_id',
                    'product_count',
                    'is_cancel_submit',
                    'settle_state',
                    'league_id',
                    '_on'=>'market_urge.market_id = market.market_id',
                    '_type'=>'LEFT'
                ),
                'market_status'=>array(
                    'name'=>'status_name',
                    '_on'=>'market_status.status_id=market.status_id',
                    '_type'=>'LEFT'
                ),
                'user'=>array(
                    '_on'=>'user.role_id = market_urge.urge_role_id',
                    '_type'=>'LEFT'
                ),
                'staff'=>$staff_main_field,
                'product_category'=>array(
                    'name'=>'category_name',
                    '_on'=>'market.category_id=product_category.category_id',
                    '_type'=>'LEFT'
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


        public function verity_check($market_urge) {
            if (is_array($market_urge)) {
                $market_urge_id = $market_urge['market_urge_id'];
            } else {
                $market_urge_id = $market_urge;
            }
            $new_market_urge = $this->where('market_urge.market_urge_id = %d',$market_urge_id)->find();
            $field_list = M('Fields')->where('model = "market_urge"')->order('order_id')->select();
            $exclude_fields = array(
                "create_time",
                "update_time",
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_market_urge[$fv['field']] != $market_urge[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_market_urge, $market_urge);
                }
            }
            return $change_fields;
        }

    }