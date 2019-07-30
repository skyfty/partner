<?php
class CommissLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'r_commiss_log'=>array(
                'commiss_name',
                'commiss_idcode',
                'logpubshow',
                '_type'=>'LEFT'
            ),
            'log'=>array(
                'log_id',
                'create_date',
                'subject',
                'content',
                'role_id',
                'role_id_keyword',
                '_on'=>'r_commiss_log.log_id=log.log_id',
            ),
            'commiss'=>array(
                'commiss_id',
                'idcode',
                'telephone',
                'league_id',
                '_on'=>'commiss.commiss_id=r_commiss_log.commiss_id'
            ),
        );
    }

}