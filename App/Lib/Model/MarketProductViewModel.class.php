<?php
	class MarketProductViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){

            $market_main_must_field = array(
                'market_id',
                'customer_id',
                'creator_role_id',
                'owner_role_id',
                'owner_role_id'=>"market_owner_role_id",
                'create_time',
                'update_time',
                'update_role_id',
                'settle_state',
                'agency_settle_time',
                'product_count',
                'is_cancel_submit',
                'league_id',
                'idcode',
                'idcode'=>'market_idcode',
            );
            $market_main_list = array_merge(M('Fields')->where(array('model'=>'market','is_main'=>1))->getField('field', true), $market_main_must_field);
            $market_main_list['_type'] = 'RIGHT';

            $main_field = array(
                'market_id',
                'market_product_id',
                'product_id',
                'salary_settle_time',
                'org_workstate_id',
                'org_queue_branch_id',
                'org_queue_category_id',
                'org_queue_category_id',
            );
            $main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'market_product','is_main'=>1))->getField('field', true),$main_field));
            $main_field['_type'] = 'LEFT';
            $main_field['_on'] = 'market_product.market_id = market.market_id';

            $product_field = array(
                'name'=>'product_name',
                'idcode'=>'product_idcode',
                'census',
                'workstate_id',
                'back_workstate_id',
                'queue_pos',
                'queue_workstate',
                'queue_branch_id',
                'queue_category_id',
                'queue_branch_id',
                'first_market_id'
            );
            $product_field['_type'] = 'LEFT';
            $product_field['_on'] = 'product.product_id = market_product.product_id';


            $this->viewFields = array(
                'market'=>$market_main_list,
                'market_product'=>$main_field,
                'market_status'=>array(
                    'name'=>'status_name',
                    '_on'=>'market_status.status_id=market.status_id',
                    '_type'=>'LEFT'
                ),
                'customer'=>array(
                    'name'=>'customer_name',
                    'idcode'=>'customer_idcode',
                    'telephone'=>'customer_telephone',
                    'wxopenid'=>'customer_wxopenid',
                    'origin',
                    'live_address',
                    'introducer',
                    '_on'=>'market.customer_id = customer.customer_id',
                    '_type'=>'LEFT'
                ),
                'customer_data'=>array(
                    'sex'=>'customer_sex',
                    'route',
                    'other_contact',
                    'appellation'=>'customer_appellation',
                    '_on'=>'customer.customer_id = customer_data.customer_id',
                    '_type'=>'LEFT'
                ),
                'product'=>$product_field,
                'product_category'=>array(
                    'name'=>'category_name',
                    'serve_id'=>'def_serve_id',
                    "serve_modality",
                    '_on'=>'market.category_id=product_category.category_id',
                    '_type'=>'LEFT'
                )
            );
		}

        public function verity_check($market_product) {
            if (is_array($market_product)) {
                $market_product_id = $market_product['market_product_id'];
            } else {
                $market_product_id = $market_product;
            }
            $new_market_product = $this->where('market_product.market_product_id = %d',$market_product_id)->find();
            $field_list = M('Fields')->where('model = "market_product"')->order('order_id')->select();
            $exclude_fields = array(
                "create_time",
                "update_time",
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_market_product[$fv['field']] != $market_product[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_market_product, $market_product);
                }
            }
            return $change_fields;
        }

    }