<?php
class CustomerGroupViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'customer_id',
            'league_id',
            'creator_role_id',
            'create_time',
            'update_time',
            'league_id',
            'wxopenid'=>'customer_wxopenid',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'customer','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'customer'=>$main_list,
            'customer_subgroup'=>array(
                'customer_group_id',
                '_on'=>'customer.customer_id=customer_subgroup.customer_id',
                '_type'=>'LEFT'),
            'customer_group'=>array(
                'name'=>'group_name',
                '_on'=>'customer_group.customer_group_id=customer_subgroup.customer_group_id',
                '_type'=>'LEFT'
            )
        );
    }
}