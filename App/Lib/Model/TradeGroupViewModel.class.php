<?php
class TradeGroupViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'trade_id',
            'create_time',
            'update_time',
            'loans',
            'role_id',
            'league_id',
            'state'=>'trade_state',
            'keyword'
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'trade','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'trade'=>$main_list,
            'serve'=>array(
                'serve_id',
                'category',
                'corre',
                'idcode'=>'serve_idcode',
                'name'=>'serve_name',
                '_type'=> 'LEFT',
                '_on'=>'trade.serve_id=serve.serve_id'
            ),
            'trade_subgroup'=>array(
                'trade_group_id',
                '_on'=>'trade.trade_id=trade_subgroup.trade_id',
                '_type'=>'LEFT'
            ),
            'trade_group'=>array(
                'name'=>'group_name',
                '_on'=>'trade_group.trade_group_id=trade_subgroup.trade_group_id',
                '_type'=>'LEFT'
            )
        );
    }
}