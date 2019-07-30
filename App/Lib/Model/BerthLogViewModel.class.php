<?php
class BerthLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'r_berth_log'=>array(
                'berth_name',
                'berth_idcode',
                'entrance_date',
                'existrce_date',
                'assort',
                'product_id',
                'entryday',
                'league_id',
                '_type'=>'LEFT'
            ),
            'log'=>array(
                'log_id',
                'create_date',
                'subject',
                'content',
                'role_id',
                'role_id_keyword',
                '_type'=>'LEFT',
                '_on'=>'r_berth_log.log_id=log.log_id',
            ),
            'berth'=>array(
                'berth_id',
                'idcode',
                'league_id',
                '_type'=>'LEFT',
                '_on'=>'r_berth_log.berth_id=berth.berth_id'
            ),
            'dorm'=>array(
                'branch_id',
                'name'=>"dorm_name",
                '_type'=>'LEFT',
                '_on'=>'berth.dorm_id=dorm.dorm_id'
            ),
            'branch'=>array(
                'name'=>"branch_name",
                '_on'=>'dorm.branch_id=branch.branch_id'
            ),
        );
    }

}