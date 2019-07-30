<?php
	class MarketSurveyViewModel extends ViewModel {
        public $viewFields;
		public function _initialize(){
			$main_must_field = array(
                'market_survey_id',
                'creator_role_id',
                'update_time',
            );
			$main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'market_survey','is_main'=>1))->getField('field', true),$main_must_field));
			$main_list['_type'] = 'LEFT';


            $this->viewFields = array(
				'market_product_survey'=>$main_list,
            );
		}


        public function verity_check($market_survey) {
            $new_market_survey = $this->where('market_survey.market_survey_id = %d',$market_survey['market_survey_id'])->find();
            $field_list = M('Fields')->where('model = "market_survey"')->order('order_id')->select();
            $exclude_fields = array(
                "update_time",
            );
            $change_fields = array();

            foreach($field_list as $fv) {
                if (in_array($fv['field'], $exclude_fields)) {
                    continue;
                }
                if ($new_market_survey[$fv['field']] != $market_survey[$fv['field']]) {
                    $change_fields[] = model_field_diff($fv, $new_market_survey, $market_survey);
                }
            }
            return $change_fields;
        }
	}