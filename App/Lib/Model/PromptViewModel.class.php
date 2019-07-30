<?php
class PromptViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'prompt_id',
            'creator_role_id',
            'create_time',
            'update_time',
            'model',
            'model_id'
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'prompt','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';
        $data_list = M('Fields')->where(array('model'=>'prompt','is_main'=>0))->getField('field', true);
        $data_list['_on'] = 'prompt.prompt_id = prompt_data.prompt_id';
        $data_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'prompt'=>$main_list,
            'prompt_data'=>$data_list
        );
    }

}