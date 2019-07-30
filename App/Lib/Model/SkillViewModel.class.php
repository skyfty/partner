<?php
class SkillViewModel extends ViewModel{
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
            'product_category'=>array(
                'league_id',
                'name'=>'category_name',
                'bconf',
                'description',
                '_on'=>'skill_data.category_id=product_category.category_id', '_type'=>'LEFT'
            ),
            'product'=>array(
                'product_id',
                'idcode',
                'name'=>'product_name',
                'telephone'=>'product_telephone',
                'wxopenid'=>'product_wxopenid',
                'standard',
                '_on'=>'skill_data.product_id=product.product_id',
                '_type'=>'LEFT'
            ),
        );
    }


    public function verity_check($skill) {
        $new_skill = $this->where('skill_data.skill_id = %d',$skill['skill_id'])->find();
        $field_list = M('Fields')->where('model = "skill"')->order('order_id')->select();
        $exclude_fields = array(
        );
        $change_fields = array();

        foreach($field_list as $fv) {
            if (in_array($fv['field'], $exclude_fields)) {
                continue;
            }
            if ($new_skill[$fv['field']] != $skill[$fv['field']]) {
                $change_fields[] = model_field_diff($fv, $new_skill, $skill);
            }
        }
        return $change_fields;
    }

    public function updateproductcat($product_id) {
        $skill_list = $this->where(array("skill_data.product_id"=>$product_id))->select();
        foreach($skill_list as $skill) {
            $skilljson[$skill['category_id']] = $skill['category_name'];
        }
        $skill = json_encode($skilljson);

        $m_product = M('Product');
        $m_product->where(array("product_id"=>$product_id))->setField('skill', $skill);
    }


}