<?php
class ServeViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'serve_id',
            'role_id',
            'create_time',
            'price',
            'corre',
            'idcode'=>"serve_idcode",
            'webshow',
            'league_id',
            'name'=>'serve_name'
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'serve','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';
        $data_list = M('Fields')->where(array('model'=>'serve','is_main'=>0))->getField('field', true);
        $data_list['_on'] = 'serve.serve_id = serve_data.serve_id';
        $data_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'serve'=>$main_list,
            'serve_data'=>$data_list,
            'serve_category'=>array(
                'name'=>'category_name',
                '_on'=>'serve_category.serve_category_id=serve.category'
            )
        );
    }

}