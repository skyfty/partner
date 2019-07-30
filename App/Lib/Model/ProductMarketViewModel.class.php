<?php
class ProductMarketViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){

        $market_main_list = M('Fields')->where(array('model'=>'market','is_main'=>1))->getField('field', true);
        $market_main_list['_type'] = 'LEFT';

        $main_field = array(
            'market_id',
            'market_product_id',
            'product_id',
            'salary_settle_time',
            'org_workstate_id',
            'org_queue_branch_id',
            'org_queue_category_id',
            'org_queue_category_id',
        );
        $main_field = array_unique(array_merge(M('Fields')->where(array('model'=>'market_product','is_main'=>1))->getField('field', true),$main_field));
        $main_field['_type'] = 'LEFT';
        $main_field['_on'] = 'market_product.market_id = market.market_id';

        $this->viewFields = array(
            'market'=>$market_main_list,
            'market_product'=>$main_field
        );
    }

}