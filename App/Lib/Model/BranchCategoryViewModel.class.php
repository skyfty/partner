<?php
class BranchCategoryViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'branch_id',
            'shopkeeper_role_id'
        );
        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'branch','is_main'=>1))->getField('field', true),$main_must_field));

        $this->viewFields = array(
            'branch'=>$main_list,
            'branch_category'=>array(
                'branch_category_id',
                'parentid',
                'role_id',
                '_on'=>'branch_category.branch_id =branch.branch_id',
                '_type'=>'LEFT'
            ),
        );
    }

}