<?php
class ProductSkillViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'product_id',
            'category_id',
            'skill_id',
            'webshow',
            'status'
        );
        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'skill'))->getField('field', true),$main_must_field));

        $this->viewFields = array(
            'skill_data'=>$main_list,
            'product'=>array(
                "create_time",
                "owner_role_id",
                'astrict',
                '_on'=>'product.product_id=skill_data.product_id', '_type'=>'LEFT'
            ),
            'product_category'=>array(
                'name'=>'category_name',
                'bconf',
                'league_id',
                '_on'=>'skill_data.category_id=product_category.category_id', '_type'=>'LEFT'
            )
        );
    }

}