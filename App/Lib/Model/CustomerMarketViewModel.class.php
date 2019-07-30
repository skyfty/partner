<?php
class CustomerMarketViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'market_id',
            'create_time',
            'update_time',
        );

        $main_field = M('Fields')->where(array('model'=>'market','is_main'=>1))->getField('field', true);
        if (!$main_field) {
            $main_field = array();
        }
        $main_list = array_unique(array_merge($main_field, $main_must_field));
        $main_list['_type'] = 'LEFT';


        $this->viewFields = array(
            'market'=>$main_list,
            'customer'=>array(
                'league_id',
                'customer_id',
                'idcode',
                'telephone'=>'customer_telephone',
                'name'=>'customer_name',
                'wxopenid'=>'customer_wxopenid',
                'origin'=>'customer_origin',
                'introducer'=>'customer_introducer',
                '_on'=>'customer.customer_id=market.customer_id'
            )
        );
    }

}