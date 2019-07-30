<?php
class BerthGroupViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'berth_id',
            'creator_role_id',
            'create_time',
            'league_id',
            'update_time',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'berth','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'berth'=>$main_list,
            'berth_subgroup'=>array(
                'berth_group_id',
                '_on'=>'berth.berth_id=customer_subgroup.berth_id',
                '_type'=>'LEFT'),
            'berth_group'=>array(
                'name'=>'group_name',
                '_on'=>'berth_group.berth_group_id=berth_subgroup.berth_group_id',
                '_type'=>'LEFT'
            )
        );
    }
}