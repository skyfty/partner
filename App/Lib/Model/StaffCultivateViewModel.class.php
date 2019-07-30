<?php
class StaffCultivateViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'cultivate_id',
            'create_time',
            'update_time',
        );

        $main_field = M('Fields')->where(array('model'=>'cultivate','is_main'=>1))->getField('field', true);
        if (!$main_field) {
            $main_field = array();
        }
        $main_list = array_unique(array_merge($main_field, $main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'cultivate'=>$main_list,
            'staff'=>array(
                'league_id',
                'staff_id',
                'idcode',
                'idcode'=>'staff_idcode',
                'telephone'=>'staff_telephone',
                'name'=>'staff_name',
                '_on'=>'staff.staff_id=cultivate.model_id'
            )
        );
    }

}