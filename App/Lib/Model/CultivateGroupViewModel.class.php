<?php
class CultivateGroupViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $main_must_field = array(
            'cultivate_id',
            'creator_role_id',
            'create_time',
            'update_time',
            'league_id',
        );

        $main_list = array_unique(array_merge(M('Fields')->where(array('model'=>'cultivate','is_main'=>1))->getField('field', true),$main_must_field));
        $main_list['_type'] = 'LEFT';

        $this->viewFields = array(
            'cultivate'=>$main_list,
            'currier'=>array(
                'name'=>'currier_name',
                "idcode"=>'currier_idcode',
                '_on'=>'cultivate.currier_id=currier.currier_id',
                '_type'=>'LEFT'
            ),
            'cultivate_subgroup'=>array(
                'cultivate_group_id',
                '_on'=>'cultivate.cultivate_id=cultivate_subgroup.cultivate_id',
                '_type'=>'LEFT'),
            'cultivate_group'=>array(
                'name'=>'group_name',
                '_on'=>'cultivate_group.cultivate_group_id=cultivate_subgroup.cultivate_group_id',
                '_type'=>'LEFT'
            )
        );
    }
}