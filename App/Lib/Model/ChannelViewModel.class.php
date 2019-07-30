<?php
class ChannelViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'channel_id',
            'name'=>'channel_name',
            'channel_role_radio_ext',
            'league_id',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'channel','is_main'=>1))->getField('field', true),$main_must_field));

        $this->viewFields = array(
            'channel'=>$main_list,
        );
    }

}