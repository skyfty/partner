<?php
	class CultivateChannelViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $main_field = array(
                'cultivate_id',
                'cultivate_channel_id',
                'isdefault',
            );
            $main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'cultivate_channel','is_main'=>1))->getField('field', true),$main_field));
            $main_field['_type'] = 'LEFT';


            $this->viewFields = array(
                'cultivate_channel'=>$main_field,
                'cultivate'=>array(
                    'idcode',
                    'idcode'=>'cultivate_idcode',
                    'status_id',
                    'settle_state',
                    'is_cancel_submit',
                    'owner_role_id',
                    'model'=>"corre",
                    'model',
                    'model_id',
                    'initial',
                    'league_id',
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
                'cultivate_status'=>array(
                    'name'=>'status_name',
                    '_on'=>'cultivate_status.status_id=cultivate.status_id',
                    '_type'=>'LEFT'
                )
            );
		}


        public function verity_check($cultivate_channel) {
            if (is_array($cultivate_channel)) {
                $cultivate_channel_id = $cultivate_channel['cultivate_channel_id'];
            } else {
                $cultivate_channel_id = $cultivate_channel;
            }
            $new_cultivate_channel = $this->where('cultivate_channel.cultivate_channel_id = %d',$cultivate_channel_id)->find();
            $field_list = M('Fields')->where('model = "cultivate_channel"')->order('order_id')->select();
            $exclude_fields = array(
                "create_time",
                "update_time",
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_cultivate_channel[$fv['field']] != $cultivate_channel[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_cultivate_channel, $cultivate_channel);
                }
            }
            return $change_fields;
        }

    }