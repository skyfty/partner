<?php
class CustomerLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'r_customer_log'=>array(
                'customer_name',
                'customer_idcode',
                '_type'=>'LEFT'
            ),
            'log'=>array(
                'log_id',
                'create_date',
                'subject',
                'content',
                'role_id',
                'role_id_keyword',
                '_on'=>'r_customer_log.log_id=log.log_id',
            ),
            'customer'=>array(
                'league_id',
                'customer_id',
                'idcode',
                'telephone',
                '_on'=>'customer.customer_id=r_customer_log.customer_id'
            ),
        );
    }

}