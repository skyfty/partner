<?php 
	class ProductViewModel extends ViewModel{
		public $viewFields;
		public function _initialize(){
			$main_must_field = array(
                'product_id',
                'creator_role_id',
                'owner_role_id',
                'is_delete',
                'delete_role_id',
                'delete_time',
                'create_time',
                'update_time',
                'skill',
                'skill_verify',
                'skill_submit_time',
                'basic_verify',
                'basic_submit_time',
                'submit_state',
                'is_verify',
                'loans',
                'actual',
                'freeze',
                'cash',
                'trade_surplus_price',
                'trainorder_surplus_price',
                'business_surplus_price',
                'market_surplus_price',
                'sum_surplus_price',
                'main_pic',
                'birthday_renind',
                'webshow',
                'astrict',
                'business_freeze',
                'defeventstate',
                'sicount',
                'sfcount',
                'slcount',
                'sncount',
                'channel_model_id',
                'channel_model',
                'telephone'=>'product_telephone',
                'wxopenid'=>'product_wxopenid',
                'wxbind',
                'name'=>'product_name',
                'idcode'=>'product_idcode',
                'queue_pos',
                'queue_branch_id',
                'queue_category_id',
                'queue_state',
                'queue_workstate',
                'queue_auth',
                'category_id',
                'catelevel',
                'commiss_id',
                'dispatch_flag',
                'onlydown',
                'league_id',
            );
            
			$main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'product','is_main'=>1))->getField('field', true),$main_must_field));
			$main_list['_type'] = 'LEFT';
			$data_list = M('Fields')->where(array('model'=>'product','is_main'=>0))->getField('field', true);
			$data_list['_on'] = 'product.product_id = product_data.product_id';
            $data_list['_type'] = 'LEFT';

            $this->viewFields = array(
				'product'=>$main_list,
				'product_data'=>$data_list
			);
		}

        public function verity_check($product, $cverity = true) {
            $new_product = $this->where('product.product_id = %d',$product['product_id'])->find();
            $field_list = M('Fields')->where('model = "product"')->order('order_id')->select();
            $exclude_fields = array(
                "create_time",
                "update_time",
                "workstate_id",
                "station_state",
                "webshow",
                "balance"
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_product[$fv['field']] != $product[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_product, $product);
                }
            }

            if ($cverity) {
                $change_assort = array("basic" =>0, "skill"=>0);
                foreach($change_fields as $fv) {
                    if ($fv['in_verify'] == 0) {
                        continue;
                    }
                    $assort = "basic";
                    $field_group_id = $fv['field_group_id'];
                    if ($field_group_id != 0) {
                        $field_group = M('fields_group')->where(array('field_group_id'=>$field_group_id))->find();
                        if ($field_group) {
                            $assort = $field_group['assort'];
                        }
                    }
                    $change_assort[$assort] += 1;
                }

                $where = array(
                    "product_id"=>$product['product_id']
                );
                $m_product = D('Product');

                $verity = $m_product->where($where)->getField('is_verify');
                $src_verity = $verity;
                if ($change_assort['basic'] > 0) {
                    $basic_verify = $m_product->where($where)->getField("basic_verify");
                    if ($basic_verify > 0) {
                        $verity -= 1;
                    }
                    $m_product->where($where)->setField("basic_verify", 0);
                }

                if ($change_assort['skill'] > 0) {
                    $skill_verify = $m_product->where($where)->getField("skill_verify");
                    if ($skill_verify > 0) {
                        $verity -= 1;
                    }
                    $m_product->where($where)->setField("skill_verify", 0);

                }
                $m_product->where($where)->setField("is_verify", 1);

                if ($src_verity != $verity) {
                    M("mProduct")->where(array('mid'=>$product['product_id']))->setField("status", 0);
                }
            }
            return $change_fields;
        }
	}