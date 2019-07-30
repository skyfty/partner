<?php
class CustomerAccountViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_list = array(
            'account_id',
            'account_type',
            'clause_type_id',
            'clause_additive',
            'income_or_expenses',
            'name',
            'money',
            'state',
            'creator_role_id',
            'description',
            'create_time',
            'related',
            'related_id',
            'league_id',
            'related_owner_role_id',
            'infow_account_id',
            'receipt_number',


        );
        $filed_list = M('Fields')->where(array('model'=>'account','is_main'=>1))->getField('field', true);
        if ($filed_list) {
            $main_list = array_unique(array_merge($filed_list,$main_list));
        }
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'account'=>$main_list,
            'account_type'=>array(
                'name'=>'clause_name',
                'deposit',
                'related_model',
                'module_id',
                'inflow_model',
                'inflow_model_type_id',
                '_on'=>'account.clause_type_id=account_type.type_id',
                '_type'=>'LEFT'),
            'customer'=>array(
                'name'=>'customer_name',
                'wxopenid'=>'customer_wxopenid',
                'idcode',
                'customer_id',
                'league_id',
                'wechat',
                '_on'=>'customer.customer_id=account.clause_additive',
                '_type'=>'LEFT'),
            'role'=>array(
                '_on'=>'account.creator_role_id=role.role_id',
                '_type'=>'LEFT'),
            'user'=>array(
                'name'=>'creator_name',
                '_on'=>'role.user_id = user.user_id')
        );


    }
}