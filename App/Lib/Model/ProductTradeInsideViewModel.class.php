<?php
class ProductTradeInsideViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'trade'=>array(
                'trade_id',
                'begin_date',
                'end_date',
                'state',
                'league_id',
            ),
            'serve'=>array(
                'category',
                '_type'=>'LEFT',
                '_on'=>'trade.serve_id=serve.serve_id'
            ),
            'product_trade'=>array(
                'product_id',
                '_type'=>'LEFT',
                '_on'=>'product_trade.trade_id=trade.trade_id'
            )
        );
    }

}