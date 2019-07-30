<?php
class CustomerCultivateViewModel extends ViewModel{
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
            'customer'=>array(
                'customer_id',
                'idcode',
                'league_id',
                'nation',
                'idcode'=>'customer_idcode',
                'telephone'=>'customer_telephone',
                'name'=>'customer_name',
                'wxopenid'=>'customer_wxopenid',
                'origin'=>'customer_origin',
                'introducer'=>'customer_introducer',
                '_on'=>'customer.customer_id=cultivate.model_id'
            )
        );
    }

}