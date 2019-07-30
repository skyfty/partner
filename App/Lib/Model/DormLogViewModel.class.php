<?php
class DormLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'r_dorm_log'=>array(
                'dorm_name',
                'dorm_idcode',
                '_type'=>'LEFT'
            ),
            'log'=>array(
                'log_id',
                'create_date',
                'subject',
                'content',
                'role_id',
                'role_id_keyword',
                '_on'=>'r_dorm_log.log_id=log.log_id',
            ),
            'dorm'=>array(
                'dorm_id',
                'idcode',
                'league_id',
                'telephone',
                '_on'=>'dorm.dorm_id=r_dorm_log.dorm_id'
            ),
        );
    }

}