<?php
class MarketCalculateAccountViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'market_account'=>array(
                'payment_time',
                'payment_verify',
                'market_product_id',
                '_type'=>'LEFT'
            ),
            'account'=>array(
                'account_id',
                'account_type',
                'money',
                'league_id',
                '_type'=>'LEFT',
                '_on'=>'market_account.account_id=account.account_id',
            )
        );


    }
}