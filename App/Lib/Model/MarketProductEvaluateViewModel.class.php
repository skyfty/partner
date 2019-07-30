<?php
	class MarketProductEvaluateViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
			$main_must_field = array(
                'market_product_evaluate_id',
                'market_product_id',
                'creator_role_id',
                'update_time',
                'praise_days',
                'home_check'
            );
			$main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'market_product_evaluate','is_main'=>1))->getField('field', true),$main_must_field));
			$main_list['_type'] = 'LEFT';

            $this->viewFields = array(
				'market_product_evaluate'=>$main_list,
                'market'=>array(
                    'market_id',
                    'idcode'=>"market_idcode",
                    'branch_id',
                    'customer_id',
                    'league_id',
                    'owner_role_id'=>'market_owner_role_id',
                    '_on'=>'market_product_evaluate.market_id = market.market_id',
                    '_type'=>'LEFT'
                ),
                'product_category'=>array(
                    'category_id',
                    'name'=>'category_name',
                    '_on'=>'market.category_id=product_category.category_id',
                    '_type'=>'LEFT'
                ),
                'product'=>array(
                    'product_id',
                    'idcode',
                    'name'=>'name',
                    '_on'=>'market_product_evaluate.product_id=product.product_id',
                    '_type'=>'LEFT'
                ),
                'market_survey'=>array(
                    'status'=>'market_survey_status',
                    'sales_evaluate',
                    'survey_describe',
                    'sales_describe',
                    '_on'=>'market_product_evaluate.market_id=market_survey.market_id',
                    '_type'=>'LEFT'
                ),
                'market_product'=>array(
                    'salary_settle_time',
                    'real_start_time',
                    'real_end_time',
                    '_on'=>'market_product_evaluate.market_product_id=market_product.market_product_id',
                    '_type'=>'LEFT'
                ),
            );
		}


        public function verity_check($market_product_evaluate) {
            $new_market_product_evaluate = $this->where('market_product_evaluate.market_product_evaluate_id = %d',$market_product_evaluate['market_product_evaluate_id'])->find();
            $field_list = M('Fields')->where('model = "market_product_evaluate"')->order('order_id')->select();
            $exclude_fields = array(
                "update_time",
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_market_product_evaluate[$fv['field']] != $market_product_evaluate[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_market_product_evaluate, $market_product_evaluate);
                }
            }
            return $change_fields;
        }
	}