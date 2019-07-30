<?php
	class MarketChannelCustomerViewModel extends ViewModel {
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
                'customer_id',
                'name'=>'customer_name',
                'idcode'=>'customer_idcode',
            );
            $model_field = array_unique(array_merge(M('Fields')->where(array('model'=>'customer','is_main'=>1))->getField('field', true),$model_field));
            $model_field['_type'] = 'LEFT';
            $model_field['_on'] = 'customer.customer_id = market_channel.channel_model_id';


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
                'customer'=>$model_field,
                'product_category'=>array(
                    'name'=>'category_name',
                    'serve_id'=>'def_serve_id',
                    "serve_modality",
                    '_on'=>'market.category_id=product_category.category_id',
                    '_type'=>'LEFT'
                )
            );
		}

    }