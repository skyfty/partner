<?php
class CurrierGroupViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'currier_id',
            'creator_role_id',
            'create_time',
            'update_time',
            'league_id',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'currier','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'currier'=>$main_list,
            'currier_subgroup'=>array(
                'currier_group_id',
                '_on'=>'currier.currier_id=customer_subgroup.currier_id',
                '_type'=>'LEFT'),
            'currier_group'=>array(
                'name'=>'group_name',
                '_on'=>'currier_group.currier_group_id=currier_subgroup.currier_group_id',
                '_type'=>'LEFT'
            )
        );
    }
}