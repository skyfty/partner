<?php
	class ProductEvaluateViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
			$main_must_field = array(
                'creator_role_id',
                'update_time',
                'product_evaluate_id'
            );
			$main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'product_evaluate','is_main'=>1))->getField('field', true),$main_must_field));
			$main_list['_type'] = 'LEFT';

            $this->viewFields = array(
				'product_evaluate'=>$main_list,
                'product'=>array(
                    'league_id',
                    'product_id',
                    'idcode',
                    'name'=>'name',
                    '_on'=>'product_evaluate.product_id=product.product_id',
                    '_type'=>'LEFT'
                )
            );
		}


        public function verity_check($product_evaluate) {
            $new_product_evaluate = $this->where('product_evaluate.product_evaluate_id = %d',$product_evaluate['market_product_evaluate_id'])->find();
            $field_list = M('Fields')->where('model = "product_evaluate"')->order('order_id')->select();
            $exclude_fields = array(
                "update_time",
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_product_evaluate[$fv['field']] != $product_evaluate[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_product_evaluate, $product_evaluate);
                }
            }
            return $change_fields;
        }
	}