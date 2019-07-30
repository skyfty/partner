<?php
class TradeViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'trade_id',
            'create_time',
            'update_time',
            'loans',
            'is_delete',
            'role_id',
            'league_id',
            'expire_renind',
            'keyword'
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


    public static function format_trade_state($value) {
        $cur_time = time();
        if ($value['state'] != '已撤销'){
            if ($value['pay_price'] == 0) {
                $value['state'] = "待付款";
            } else if ($value['pay_price'] > 0 && ($value['begin_date'] == 0 || $value['begin_date'] > $cur_time)) {
                $value['state'] = "待开始";
            } else if ($value['pay_price'] > 0 && $value['begin_date'] > 0 && $cur_time > $value['begin_date'] && ($cur_time < $value['end_date'] || $value['end_date'] == 0)){
                $value['state'] = "进行中";
            } else if ($value['pay_price'] > 0 && $value['end_date'] >0 && $cur_time > $value['end_date']) {
                $value['state'] = "已结束";
            }
        }
        return $value;
    }


}