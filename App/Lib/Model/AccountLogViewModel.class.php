<?php
class AccountLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'account_log'=>array(
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

                '_on'=>'account_log.log_id=log.log_id',
            )
        );
    }

}