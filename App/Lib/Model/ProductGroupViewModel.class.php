<?php
class ProductGroupViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'product_id',
            'creator_role_id',
            'create_time',
            'update_time',
            'telephone'=>'product_telephone',
            'wxopenid'=>'product_wxopenid',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'product','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'product'=>$main_list,
            'workstate'=>array(
                'name'=>'workstate_name',
                '_on'=>'product.workstate_id=workstate.workstate_id',
                '_type'=>'LEFT'
            ),
            'product_subgroup'=>array(
                'product_group_id',
                '_on'=>'product.product_id=product_subgroup.product_id',
                '_type'=>'LEFT'
            ),
            'product_group'=>array(
                'name'=>'group_name',
                '_on'=>'product_group.product_group_id=product_subgroup.product_group_id',
                '_type'=>'LEFT'
            )
        );
    }
}