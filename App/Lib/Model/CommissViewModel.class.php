<?php	class CommissViewModel extends ViewModel {        protected $viewFields;		public function _initialize(){			$main_must_field = array(                'commiss_id',                'owner_role_id',                'creator_role_id',                'delete_role_id',                'create_time',                'delete_time',                'update_time',                'is_deleted',                'last_log_time',                'related_model',                'related_model_name',                'related_model_id',                'league_id',                'related_model_keyword',            );			$main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'commiss','is_main'=>1))->getField('field', true),$main_must_field));			$this->viewFields = array('commiss'=>$main_list);		}        public function verity_check($commiss, $cverity = true) {            $new_commiss = $this->where('commiss.commiss_id = %d',$commiss['commiss_id'])->find();            $field_list = M('Fields')->where('model = "commiss"')->order('order_id')->select();            $exclude_fields = array(                "create_time",                "update_time",            );            $change_fields = array();            foreach($field_list as $fv) {                if (in_array($fv['field'], $exclude_fields)) {                    continue;                }                if ($new_commiss[$fv['field']] != $commiss[$fv['field']]) {                    $change_fields[] = model_field_diff($fv, $new_commiss, $commiss);                }            }            if ($cverity) {                $change_assort = array("basic" => 0);                foreach ($change_fields as $fv) {                    if ($fv['in_verify'] == 0) {                        continue;                    }                    $assort = "basic";                    $field_group_id = $fv['field_group_id'];                    if ($field_group_id != 0) {                        $field_group = M('fields_group')->where(array('field_group_id' => $field_group_id))->find();                        if ($field_group) {                            $assort = $field_group['assort'];                        }                    }                    $change_assort[$assort] += 1;                }                $where = array(                    "commiss_id" => $commiss['commiss_id']                );                $m_commiss = D('commiss');                if ($change_assort['basic'] > 0) {                    $m_commiss->where($where)->setField("basic_verify", 0);                }                $m_commiss->where($where)->setField("is_verify", 1);            }            return $change_fields;        }	}