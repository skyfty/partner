<?php
class ProductCultivateAccountViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'cultivate_account'=>array(
                'payment_time',
                'payment_verify',
                'verify_role_id',
                'verify_time',
                'flow_account_id',
                'receipt_number',
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
                '_on'=>'cultivate_account.account_id=account.account_id',
            ),

            'cultivate'=>array(
                'cultivate_id',
                'model',
                'model'=>'corre',
                'idcode'=>"cultivate_idcode",
                'branch_id',
                'status_id',
                'settle_state',
                'is_cancel_submit',
                'owner_role_id',
                'sum_settle_price',
                '_on'=>'account.related_id = cultivate.cultivate_id',
                '_type'=>'LEFT'
            ),
            'product'=>array(
                'league_id',
                'product_id',
                'idcode'=>"product_idcode",
                'name'=>'product_name',
                'telephone'=>'product_telephone',
                '_on'=>'product.product_id=cultivate.model_id',
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
            )
        );


    }
}