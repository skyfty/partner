<?php
class MarketLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'r_market_log'=>array(
                'market_id',
                'market_idcode',
                'type'=>'log_type',
                '_type'=>'LEFT'
            ),
            'log'=>array(
                'log_id',
                'create_date',
                'subject',
                'content',
                'role_id',
                'role_id_keyword',
                '_on'=>'r_market_log.log_id=log.log_id',
                '_type'=>'LEFT'
            ),
            'market'=>array(
                'idcode',
                'customer_id',
                'status_id',
                'category_id',
                'product_count',
                'is_cancel_submit',
                'corre',
                'league_id',
                'owner_role_id',
                '_on'=>'r_market_log.market_id = market.market_id',
                '_type'=>'LEFT'
            ),
            'product_category'=>array(
                'name'=>'category_name',
                'serve_id'=>'def_serve_id',
                "serve_modality",
                '_on'=>'market.category_id=product_category.category_id',
                '_type'=>'LEFT'
            ),
            'customer'=>array(
                'customer_id',
                'idcode',
                'name'=>'customer_name',
                'telephone'=>'customer_telephone',
                'wxopenid'=>'customer_wxopenid',
                '_on'=>'customer.customer_id=market.customer_id',
                '_type'=>'LEFT'
            ),
        );
    }

}