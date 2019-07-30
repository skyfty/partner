<?php
	class MarketStaffEvaluateViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
			$main_must_field = array(
                'update_time',
                'status'=>'market_survey_status',

            );
			$main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'market_survey','is_main'=>1))->getField('field', true),$main_must_field));
			$main_list['_type'] = 'LEFT';

            $this->viewFields = array(
				'market_survey'=>$main_list,
                'market'=>array(
                    'league_id',
                    'market_id',
                    'idcode'=>"market_idcode",
                    'branch_id',
                    'owner_role_id'=>'market_owner_role_id',
                    '_on'=>'market_survey.market_id = market.market_id',
                    '_type'=>'LEFT'
                ),
                'product_category'=>array(
                    'category_id',
                    'name'=>'category_name',
                    '_on'=>'market.category_id=product_category.category_id',
                    '_type'=>'LEFT'
                ),
				'role'=>array(
					'_on'=>'market.owner_role_id=role.role_id',
					'_type'=>'LEFT'),
				'user'=>array(
					'name'=>'creator_name',
					'_type'=>'LEFT',
					'_on'=>'role.user_id = user.user_id'),
                'staff'=>array(
                    'name'=>'staff_name',
                    'idcode',
                    'staff_id',
                    '_on'=>'user.user_id=staff.user_id'
				),
            );
		}

	}