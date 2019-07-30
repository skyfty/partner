<?php
class Market2AccountViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'market_account'=>array(
                'payment_time',
                'payment_verify',
                'verify_role_id',
                'verify_time',
                'flow_account_id',
                'receipt_number',
                'market_product_id',
                '_type'=>'LEFT'
            ),
            'account'=>array(
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
                'payway',
                'quarry',
                'league_id',
                '_type'=>'LEFT',
                '_on'=>'market_account.account_id=account.account_id',
            ),

            'market'=>array(
                'market_id',
                'corre',
                'idcode'=>"market_idcode",
                'money'=>"market_money",
                'level',
                'branch_id',
                'status_id',
                'settle_state',
                'demand_start_time',
                'product_count',
                'is_cancel_submit',
                'owner_role_id',
                'sum_settle_price',
                '_on'=>'account.related_id = market.market_id',
                '_type'=>'LEFT'
            ),
            'branch'=>array(
                'name'=>'branch_name',
                '_on'=>'market.branch_id=branch.branch_id',
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
                'idcode'=>"customer_idcode",
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