<?php
class ProductAccountViewModel extends ViewModel{
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
            'related_owner_role_id',
            'infow_account_id',
            'league_id',
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
            'product'=>array(
                'name'=>'product_name',
                'telephone'=>'product_telephone',
                'wxopenid'=>'product_wxopenid',
                'idcode',
                'league_id',
                'product_id',
                'wechat',
                '_on'=>'product.product_id=account.clause_additive',
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