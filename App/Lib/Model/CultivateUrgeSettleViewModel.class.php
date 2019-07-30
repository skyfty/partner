<?php
	class CultivateUrgeSettleViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
            $this->viewFields = array(
                'cultivate_urge'=>array(
                    'urge_price_settle_time',
                    'cultivate_id',
                    'urge_price',
                    'cultivate_urge_id',
                    '_type'=>'LEFT'
                ),
                'staff'=> array(
                    'staff_id',
                    'name'=>'staff_name',
                    '_type'=>'LEFT',
                    '_on'=>'user.user_id = staff.user_id'
                ),

            );
		}
    }