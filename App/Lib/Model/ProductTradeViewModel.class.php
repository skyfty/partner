<?php
class ProductTradeViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'trade_id',
            'league_id',
            'create_time',
            'update_time',
        );

        $main_field = M('Fields')->where(array('model'=>'trade','is_main'=>1))->getField('field', true);
        if (!$main_field) {
            $main_field = array();
        }
        $main_list = array_unique(array_merge($main_field, $main_must_field));
        $main_list['_type'] = 'LEFT';
        $data_list = M('Fields')->where(array('model'=>'trade','is_main'=>0))->getField('field', true);
        $data_list['_on'] = 'trade_data.trade_id = trade.trade_id';
        $data_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'trade'=>$main_list,
            'trade_data'=>$data_list,
            'serve'=>array(
                'serve_id',
                'category',
                'corre',
                'idcode'=>'serve_idcode',
                'name'=>'serve_name',
                '_on'=>'trade.serve_id=serve.serve_id'
            ),
            'product_trade'=>array(
                'product_id'=>"corre_id",
                '_type'=>'LEFT',
                '_on'=>'product_trade.trade_id=trade.trade_id'
            ),
            'product'=>array(
                'product_id',
                'station_state',
                'name'=>'corre_name',
                'idcode'=>'corre_idcode',
                'telephone'=>'corre_telephone',
                '_on'=>'product.product_id=product_trade.product_id'
            ),
            'serve_category'=>array(
                'name'=>'category_name',
                '_type'=> 'LEFT',
                '_on'=>'serve_category.serve_category_id=serve.category'
            )
        );
    }

}