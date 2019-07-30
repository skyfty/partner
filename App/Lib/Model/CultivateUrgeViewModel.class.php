<?php
	class CultivateUrgeViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $main_field = array(
                'cultivate_id',
                'cultivate_urge_id',
                'isdefault',
                'urge_role_class',
            );
            $main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'cultivate_urge','is_main'=>1))->getField('field', true),$main_field));
            $main_field['_type'] = 'LEFT';

            $this->viewFields = array(
                'cultivate_urge'=>$main_field,
                'cultivate'=>array(
                    'idcode',
                    'idcode'=>'cultivate_idcode',
                    'status_id',
                    'is_cancel_submit',
                    'settle_state',
                    'model'=>"corre",
                    'model',
                    'model_id',
                    'idcode',
                    '_on'=>'cultivate_urge.cultivate_id = cultivate.cultivate_id',
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
                'user'=>array(
                    'staff_id',
                    '_on'=>'user.role_id = cultivate_urge.urge_role_id',
                    '_type'=>'LEFT'
                ),
            );
		}


        public function verity_check($cultivate_urge) {
            if (is_array($cultivate_urge)) {
                $cultivate_urge_id = $cultivate_urge['cultivate_urge_id'];
            } else {
                $cultivate_urge_id = $cultivate_urge;
            }
            $new_cultivate_urge = $this->where('cultivate_urge.cultivate_urge_id = %d',$cultivate_urge_id)->find();
            $field_list = M('Fields')->where('model = "cultivate_urge"')->order('order_id')->select();
            $exclude_fields = array(
                "create_time",
                "update_time",
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_cultivate_urge[$fv['field']] != $cultivate_urge[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_cultivate_urge, $cultivate_urge);
                }
            }
            return $change_fields;
        }

    }