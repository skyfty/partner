<?php
	class CultivateChannelCustomerViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $main_field = array(
                'cultivate_id',
                'cultivate_channel_id',
                'channel_model_id',
                'channel_model',
                'isdefault',
            );
            $main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'cultivate_channel','is_main'=>1))->getField('field', true),$main_field));
            $main_field['_type'] = 'LEFT';

            $model_field = array(
                'customer_id',
                'name'=>'customer_name',
                'idcode'=>'customer_idcode',
            );
            $model_field = array_unique(array_merge(M('Fields')->where(array('model'=>'customer','is_main'=>1))->getField('field', true),$model_field));
            $model_field['_type'] = 'LEFT';
            $model_field['_on'] = 'customer.customer_id = cultivate_channel.channel_model_id';


            $this->viewFields = array(
                'cultivate_channel'=>$main_field,
                'cultivate'=>array(
                    'model'=>"corre",
                    'model',
                    'model_id',
                    'settle_state',
                    'idcode',
                    'idcode'=>'cultivate_idcode',
                    'customer_id'=>'cultivate_customer_id',
                    'status_id',
                    'is_cancel_submit',
                    'league_id',
                    'owner_role_id',
                    '_on'=>'cultivate_channel.cultivate_id = cultivate.cultivate_id',
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
                'customer'=>$model_field
            );
		}

    }