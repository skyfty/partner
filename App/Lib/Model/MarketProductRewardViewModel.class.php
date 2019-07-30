<?php
	class MarketProductRewardViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $market_main_list = array(
                'market_id',
                'owner_role_id',
                'owner_role_id'=>"market_owner_role_id",
                'create_time',
                'update_time',
                'update_role_id',
                'settle_state',
                'idcode'=>'market_idcode',
            );
            $market_main_list['_type'] = 'RIGHT';

            $main_field = array(
                'market_id',
                'market_product_id',
                'product_id',
                'urge_reward_price',
                'urge_reward_settle_time',
                'channel_reward_price',
                'channel_reward_settle_time',
            );
            $main_field['_type'] = 'LEFT';
            $main_field['_on'] = 'market_product.market_id = market.market_id';

            $product_field = array(
                'name'=>'product_name',
                'idcode'=>'product_idcode',
                'workstate_id',
                'first_market_id',
                'channel_role_model',
                'channel_role_id',
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
                'product'=>$product_field,
            );
		}

    }