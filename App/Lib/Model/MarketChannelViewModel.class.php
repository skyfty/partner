<?php
	class MarketChannelViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $main_field = array(
                'market_id',
                'market_channel_id',
                'channel_role_model',
                'channel_role_id',
                'channel_model_id',
                'channel_model',
                'isdefault',
            );
            $main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'market_channel','is_main'=>1))->getField('field', true),$main_field));
            $main_field['_type'] = 'LEFT';


            $this->viewFields = array(
                'market_channel'=>$main_field,
                'market'=>array(
                    'idcode'=>'market_idcode',
                    'customer_id',
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
                'product_category'=>array(
                    'name'=>'category_name',
                    'serve_id'=>'def_serve_id',
                    "serve_modality",
                    '_on'=>'market.category_id=product_category.category_id',
                    '_type'=>'LEFT'
                )
            );
		}


        public function verity_check($market_channel) {
            if (is_array($market_channel)) {
                $market_channel_id = $market_channel['market_channel_id'];
            } else {
                $market_channel_id = $market_channel;
            }
            $new_market_channel = $this->where('market_channel.market_channel_id = %d',$market_channel_id)->find();
            $field_list = M('Fields')->where('model = "market_channel"')->order('order_id')->select();
            $exclude_fields = array(
                "create_time",
                "update_time",
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_market_channel[$fv['field']] != $market_channel[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_market_channel, $market_channel);
                }
            }
            return $change_fields;
        }

    }