<?php
class CultivateLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'r_cultivate_log'=>array(
                'cultivate_idcode',
                'assort',
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
                '_on'=>'r_cultivate_log.log_id=log.log_id',
            ),
            'cultivate'=>array(
                'cultivate_id',
                'idcode',
                'league_id',
                '_type'=>'LEFT',
                '_on'=>'cultivate.cultivate_id=r_cultivate_log.cultivate_id'
            )
        );
    }

}