<?php
class CommissGroupViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'commiss_id',
            'creator_role_id',
            'create_time',
            'update_time',
            'league_id',
            'wxopenid'=>'customer_wxopenid',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'commiss','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'commiss'=>$main_list,
            'commiss_subgroup'=>array(
                'commiss_group_id',
                '_on'=>'commiss.commiss_id=customer_subgroup.commiss_id',
                '_type'=>'LEFT'),
            'commiss_group'=>array(
                'name'=>'group_name',
                '_on'=>'commiss_group.commiss_group_id=commiss_subgroup.commiss_group_id',
                '_type'=>'LEFT'
            )
        );
    }
}