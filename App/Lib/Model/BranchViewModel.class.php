<?php
class BranchViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'branch_id',
            'league_id',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'branch','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'branch'=>$main_list,
        );
    }

}