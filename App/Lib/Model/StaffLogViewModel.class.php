<?php
class StaffLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'r_log_staff'=>array(
                'staff_name',
                'staff_idcode',
                '_type'=>'LEFT'
            ),
            'log'=>array(
                'log_id',
                'create_date',
                'subject',
                'content',
                'role_id',
                'role_id_keyword',
                '_on'=>'r_log_staff.log_id=log.log_id',
            ),
            'staff'=>array(
                'league_id',
                'staff_id',
                'idcode',
                'telephone',
                '_on'=>'staff.staff_id=r_log_staff.staff_id'
            ),
        );
    }

}