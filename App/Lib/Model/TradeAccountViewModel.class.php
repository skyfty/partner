<?php
class TradeAccountViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'trade_account'=>array(
                'trade_id',
                'account_id'
            ),
            'account'=>array(
                'money',
                'create_time',
                '_on'=>'trade_account.account_id=account.account_id',
                '_type'=>'LEFT'
            ),
            'trade'=>array(
                'orderid',
                'price',
                'pay_price',
                'is_delete',
                'owner_role_id',
                'league_id',
                '_on'=>'trade_account.trade_id=trade.trade_id',
                '_type'=>'LEFT'
            ),
            'serve'=>array(
                'corre',
                'idcode',
                'name',
                '_on'=>'trade.serve_id=serve.serve_id',
                '_type'=>'LEFT'
            ),
            'serve_category'=>array(
                'name'=>'category_name',
                '_on'=>'serve_category.serve_category_id=serve.category'
            )
        );
    }
}