<?php
class LeagueViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'league_id',
            'admin_staff_id'
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'league','is_main'=>1))->getField('field', true),$main_must_field));

        $this->viewFields = array(
            'league'=>$main_list,
        );
    }

}