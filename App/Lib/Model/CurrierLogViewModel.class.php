<?php
class CurrierLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'r_currier_log'=>array(
                'currier_name',
                'currier_idcode',
                'assort',
                'product_id',
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
                '_on'=>'r_currier_log.log_id=log.log_id',
            ),
            'currier'=>array(
                'currier_id',
                'idcode',
                'league_id',
                '_type'=>'LEFT',
                '_on'=>'currier.currier_id=r_currier_log.currier_id'
            ),
            'dorm'=>array(
                'name'=>"dorm_name",
                '_on'=>'currier.dorm_id=dorm.dorm_id'
            ),
        );
    }

}