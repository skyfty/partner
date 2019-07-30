<?php
class TradeCostViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'trade_id',
            'create_time',
            'update_time',
            'role_id',
            'league_id',
            'state'=>'trade_state',
            'keyword'
        );

        $main_field = M('Fields')->where(array('model'=>'trade','is_main'=>1))->getField('field', true);
        if (!$main_field) {
            $main_field = array();
        }
        $main_list = array_unique(array_merge($main_field, $main_must_field));
        $main_list['_type'] = 'LEFT';
        $main_list['_on'] = 'trade.trade_id = trade_cost.trade_id';

        $data_list = M('Fields')->where(array('model'=>'trade','is_main'=>0))->getField('field', true);
        $data_list['_on'] = 'trade_data.trade_id = trade.trade_id';
        $data_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'trade_cost'=>array(
                'status_time'=>'cost_status_time',
                'status'=>'cost_status',
                'cost_field',
                'trade_cost_id',
                '_type'=>'LEFT'
            ),
            'trade'=>$main_list,
            'trade_data'=>$data_list,
            'serve'=>array(
                'serve_id',
                'category',
                'corre',
                'idcode'=>'serve_idcode',
                'name'=>'serve_name',
                '_type'=> 'LEFT',
                '_on'=>'trade.serve_id=serve.serve_id'
            ),
            'serve_category'=>array(
                'name'=>'category_name',
                '_type'=> 'LEFT',
                '_on'=>'serve_category.serve_category_id=serve.category'
            )
        );
    }
}