<?php
class MarketGroupViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'market_id',
            'creator_role_id',
            'create_time',
            'update_time',
            'league_id',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'market','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'market'=>$main_list,
            'market_status'=>array(
                'name'=>'status_name',
                '_on'=>'market_status.status_id=market.status_id',
                '_type'=>'LEFT'
            ),
            'market_subgroup'=>array(
                'market_group_id',
                '_on'=>'market.market_id=market_subgroup.market_id', '_type'=>'LEFT'
            ),
            'market_group'=>array(
                'name'=>'group_name',
                '_on'=>'market_group.market_group_id=market_subgroup.market_group_id',
                '_type'=>'LEFT'
            )
        );
    }
}