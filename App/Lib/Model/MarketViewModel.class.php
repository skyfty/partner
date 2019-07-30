<?php
	class MarketViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
			$main_must_field = array(
                'market_id',
                'customer_id',
                'creator_role_id',
                'owner_role_id',
                'create_time',
                'update_time',
                'update_role_id',
                'settle_state',
				'agency_settle_time',
				'product_count',
				'corre',
                'league_id',
				'def_channel_id',
				'is_cancel_submit',
				'product_agency_scale',
				'idcode'=>'market_idcode',
            );
			$main_list = array_unique(array_merge(M('Fields')->cache(true)->where(array('model'=>'market','is_main'=>1))->getField('field', true),$main_must_field));
			$main_list['_type'] = 'LEFT';

            $this->viewFields = array(
				'market'=>$main_list,
				'product_category'=>array(
					'category_id',
					'name'=>'category_name',
                    "serve_modality",
					"urge_category_ratio",
					'_on'=>'market.category_id=product_category.category_id',
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
				'market_status'=>array(
					'name'=>'status_name',
					'_on'=>'market_status.status_id=market.status_id',
					'_type'=>'LEFT'
				)
            );
		}


        public function verity_check($market) {
            $new_market = $this->where('market.market_id = %d',$market['market_id'])->find();
            $field_list = M('Fields')->where('model = "market"')->order('order_id')->select();
            $exclude_fields = array(
                "create_time",
                "update_time",
                "workstate_id",
                "station_state",
                "webshow",
                "balance"
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_market[$fv['field']] != $market[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_market, $market);
                }
            }
            return $change_fields;
        }
	}