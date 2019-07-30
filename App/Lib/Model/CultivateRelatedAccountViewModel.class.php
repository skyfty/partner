<?php
class CultivateRelatedAccountViewModel extends ViewModel{
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
            'infow_account_id'
        );
        $filed_list = M('Fields')->where(array('model'=>'account','is_main'=>1))->getField('field', true);
        if ($filed_list) {
            $main_list = array_unique(array_merge($filed_list,$main_list));
        }
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'account'=>$main_list,
            'cultivate_account'=>array(
                'payment_time',
                'payment_verify',
                'verify_role_id',
                'verify_time',
                'flow_account_id',
                '_on'=>'account.account_id=cultivate_account.account_id',
                '_type'=>'LEFT'
            ),
            'cultivate'=>array(
                'cultivate_id',
                'settle_state',
                'idcode',
                'idcode'=>"cultivate_idcode",
                'model'=>"corre",
                'model',
                'model_id',
                'branch_id',
                'is_cancel_submit',
                'owner_role_id',
                'league_id',
                '_on'=>'account.related_id = cultivate.cultivate_id',
                '_type'=>'LEFT'
            ),
            'currier'=>array(
                'name'=>'currier_name',
                "idcode"=>'currier_idcode',
                "category_id"=>'category_id',
                '_on'=>'cultivate.currier_id=currier.currier_id',
                '_type'=>'LEFT'
            ),
            'currier_category'=>array(
                'currier_category_id',
                'name'=>'category_name',
                '_on'=>'currier.category_id=currier_category.currier_category_id',
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
            'role'=>array(
                '_on'=>'account.creator_role_id=role.role_id',
                '_type'=>'LEFT'),
            'user'=>array(
                'name'=>'creator_name',
                '_on'=>'role.user_id = user.user_id')
        );


    }
}