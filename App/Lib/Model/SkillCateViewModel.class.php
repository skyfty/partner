<?php
class SkillCateViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'product_id',
            'category_id',
            'skill_id',
            'status'
        );
        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'skill'))->getField('field', true),$main_must_field));

        $this->viewFields = array(
            'skill_data'=>$main_list,
            'product_category'=>array(
                'league_id',
                'name',
                'bconf',
                'description',
                '_on'=>'skill_data.category_id=product_category.category_id', '_type'=>'LEFT'
            )
        );
    }

}