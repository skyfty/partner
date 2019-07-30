<?php
class DormGroupViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'dorm_id',
            'creator_role_id',
            'create_time',
            'update_time',
            'league_id',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'dorm','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'dorm'=>$main_list,
            'dorm_subgroup'=>array(
                'dorm_group_id',
                '_on'=>'dorm.dorm_id=customer_subgroup.dorm_id',
                '_type'=>'LEFT'),
            'dorm_group'=>array(
                'name'=>'group_name',
                '_on'=>'dorm_group.dorm_group_id=dorm_subgroup.dorm_group_id',
                '_type'=>'LEFT'
            )
        );
    }
}