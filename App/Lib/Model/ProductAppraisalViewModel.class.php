<?php
class ProductAppraisalViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'product_appraisal_id',
            'product_id',
        );
        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'product_appraisal','is_main'=>1))->getField('field', true),$main_must_field));
        $this->viewFields = array(
            'product_appraisal'=>$main_list,
        );
    }

}