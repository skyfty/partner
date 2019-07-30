<?php
	class MarketProductMpayViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $market_main_list = array(
                'market_id',
                'customer_id',
                'owner_role_id'=>"market_owner_role_id",
                'idcode'=>'market_idcode',
            );
            $market_main_list['_type'] = 'RIGHT';

            $main_field = array(
                'market_id',
                'market_product_id',
                'product_id',
                'create_time',
                'real_start_time',
                'real_end_time',
                'service_duration',
                'recess_day',
                'price',
                'settle_price',
                'salary',
                'agency_scale',
                'settle_price',
                'agency',
                'service_price',
                'settle_duration',
                'service_status_id',
                'salary_settle_time',
            );
            $main_field['_type'] = 'LEFT';
            $main_field['_on'] = 'market_product.market_id = market.market_id';

            $product_field = array(
                'name'=>'product_name',
                'idcode'=>'product_idcode',
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
                    '_on'=>'market.customer_id = customer.customer_id',
                    '_type'=>'LEFT'
                ),
                'product'=>$product_field,
            );
		}


    }