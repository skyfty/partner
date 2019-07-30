<?php
class MarketRelatedAccountViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_list = array(
            'league_id',
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
            'market_account'=>array(
                'payment_time',
                'payment_verify',
                'verify_role_id',
                'verify_time',
                'flow_account_id',
                '_on'=>'account.account_id=market_account.account_id',
                '_type'=>'LEFT'
            ),
            'market'=>array(
                'market_id',
                'corre',
                'idcode'=>"market_idcode",
                'money'=>"market_money",
                'level',
                'branch_id',
                'demand_start_time',
                'product_count',
                'is_cancel_submit',
                'owner_role_id',
                '_on'=>'account.related_id = market.market_id',
                '_type'=>'LEFT'
            ),

            'account_type'=>array(
                'name'=>'clause_name',
                'deposit',
                'related_model',
                'module_id',
                'inflow_model',
                'inflow_model_type_id',
                '_on'=>'account.clause_type_id=account_type.type_id',
                '_type'=>'LEFT'
            ),

            'product_category'=>array(
                'category_id',
                'name'=>'category_name',
                '_on'=>'market.category_id=product_category.category_id',
                '_type'=>'LEFT'
            ),
            'customer'=>array(
                'customer_id',
                'idcode',
                'name'=>'customer_name',
                'telephone'=>'customer_telephone',
                'wxopenid'=>'customer_wxopenid',
                '_on'=>'customer.customer_id=market.customer_id',
                '_type'=>'LEFT'
            ),
            'role'=>array(
                '_on'=>'account.creator_role_id=role.role_id',
                '_type'=>'LEFT'),
            'user'=>array(
                'name'=>'creator_name',
                '_on'=>'role.user_id = user.user_id')
        );


    }
}