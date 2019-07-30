<?php
class ProductCultivateViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'cultivate_id',
            'create_time',
            'update_time',
        );

        $main_field = M('Fields')->where(array('model'=>'cultivate','is_main'=>1))->getField('field', true);
        if (!$main_field) {
            $main_field = array();
        }
        $main_list = array_unique(array_merge($main_field, $main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'cultivate'=>$main_list,
            'product'=>array(
                'league_id',
                'product_id',
                'idcode',
                'nation',
                'workstate_id',
                'idcode'=>'product_idcode',
                'telephone'=>'product_telephone',
                'name'=>'product_name',
                '_on'=>'product.product_id=cultivate.model_id'
            )
        );
    }

}