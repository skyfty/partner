<?php
/**
 * Created by PhpStorm.
 * User: sky
 * Date: 18/1/13
 * Time: 上午12:14
 */


function format_field($field, $module) {
    if ($field['field'] == 'blank_name') {
        $blank = array();
        foreach(black_name() as $k=>$v) {
            $blank[] = array("text"=>$v,"value"=>$k);
        }
        $field['data'] = $blank;
        $field['form_type'] = "select";

    }  else {
        switch ($field['form_type']) {
            case 'box' : {
                $setting_str = '$setting=' . $field['setting'] . ';';
                eval($setting_str);
                $setting_data = array();
                foreach ($setting['data'] as $v2) {
                    $setting_data[] = array("value" => $v2, "text" => $v2);
                }
                if ($setting['type'] == "checkbox") {
                    $field['multiple'] = "multiple";
                }
                $field['disable_search'] = true;
                $field['data'] = $setting_data;
                $field['form_type'] = "select";
                break;
            }
            case 'p_box': {
                $pcat = array();
                foreach (M('product_category')->field("category_id, serve_modality, name")->cache(true)->select() as $v2) {
                    $pcat[$v2['category_id']] = $v2;
                }
                $field['data'] = $pcat;
                $field['disable_search'] = true;
                break;
            }
            case 'a_box': {
                $type = $_REQUEST['type'];
                $dire = $_REQUEST['dire'];
                $where = array(
                    'module_id'=>$type,
                    'mold' => $dire,
                    'operator'=>array("neq", '2'),
                    'is_show'=>1
                );
                if (!vali_permission("account", "add", $type."_ea")) {
                    $where['_string'] = "(mold=-1 OR mold=-2 OR mold=-3) OR ((inflow_model='' OR inflow_model='flow' OR inflow_model='ious') AND (mold=1 OR mold=2 OR mold=3) AND (inflow_model!='cash'))";
                } else {
                    $where['inflow_model_type_id'] = array("neq", -2);
                }
                $setting_data = array();
                $account_type = M('AccountType')->cache(true)->where($where)->order('order_id')->select();
                foreach ($account_type as $v2) {
                    $setting_data[$v2['type_id']] = $v2;
                }
                $field['data'] = $setting_data;
                break;
            }

            case "channel_role_model_box":{
                $setting_data = array();
                foreach (get_channel_role_model($module) as $v2) {
                    $setting_data[$v2['channel_id']] = $v2;
                }
                $field['data'] = $setting_data;
                break;
            }
            default:
                break;
        }
    }
    return $field;

}

function format_group_fields_list($module, $fields_group) {
    foreach($fields_group as $gk=>$group) {
        foreach($group['assorts'] as $ak=>$assort) {
            $fields_group[$gk]['assorts'][$ak]['fields'] = format_fields_list($module, $assort['fields']);
        }
    }
    return $fields_group;
}

function format_fields_list($module, $fields) {
    foreach ($fields as $fk => $field) {
        $fields[$fk] = format_field($field, $module);
    }
    return $fields;
}

function fill_permission($d_module, $module_fields) {
    $permission = array(
        "delete"=>false,
    );
    $d_module["permission"] = $permission;
    return $d_module;
}

function fill_field($field, $module, &$d_module){
    if ($field['field'] == 'customer_id' && $d_module[$field['field']]) {
        $d_module[$field['field']] = M('customer')->field("customer_id, name, idcode")->where('customer_id = %d', $d_module[$field['field']])->find();
    } elseif ($field['field'] == 'product_id' && $d_module[$field['field']]) {
        $d_module[$field['field']] = M('product')->field("product_id, name, idcode")->where('product_id = %d', $d_module[$field['field']])->find();
    } elseif ($field['field'] == 'serve_id' && $d_module[$field['field']]) {
        $d_module[$field['field']] = M('serve')->field("serve_id, name, idcode")->where('serve_id = %d', $d_module[$field['field']])->find();
    } elseif ($field['field'] == 'subgroup' && $d_module[$field['field']]) {
        $module_group = array();
        $where = array(
            $module."_subgroup.".$module."_id"=>$d_module[$module."_id"]
        );
        $where['league_id'] = session('league_id');
        foreach(D(ucfirst($module)."GroupView")->where($where)->select() as $field) {
            $module_group[] = $field;
        }
        $d_module[$field['field']] = $module_group;

    }elseif ($field['field'] == 'currier_id' && $d_module[$field['field']]) {
        $d_module[$field['field']] = M('currier')->field("currier_id, name, idcode")->where('currier_id = %d', $d_module[$field['field']])->find();
    }elseif ($module == 'cultivate' && $field['form_type'] == 'currier_model_id' && $d_module['model_id'] && $d_module['model']) {
        $model_id = intval($d_module['model_id']);
        $model = $d_module['model'];
        $d_module[$field['field']] = M($model)->field(array($model."_id", "name", "idcode"))->where($model . '_id = %d', $model_id)->find();
    }
    elseif (($module == "cultivate" && $field['field'] == 'model_owner_role_id')) {
        $d_module[$field['field']] = D('StaffRoleView')->where('user.role_id = %d', $d_module[$field['field']])->find();
    }elseif ($field['field'] == 'position_id') {
        $d_module[$field['field']] = M('position')->cache(true)->where("position_id=".$d_module[$field['field']])->find();
    }elseif ($field['field'] == 'department_id') {
        $where['league_id'] = session('league_id');
        $where['department_id'] = $d_module[$field['field']];
        $d_module[$field['field']] = M('roleDepartment')->cache(true)->where($where)->find();
    }elseif (($field['form_type'] == "branch" || $field['field'] == 'branch_id') && $d_module[$field['field']]) {
        $d_module[$field['field']] = M('branch')->cache(true)->field("name, branch_id")->where('branch_id = %d ', $d_module[$field['field']])->find();
    }else {
        switch ($field['form_type']) {
            case 'address': {
                if($d_module[$field['field']]){
                    $d_module[$field['field']] =  explode(chr(10), $d_module[$field['field']]);
                }
                break;
            }
            case 'box': {
                if($d_module[$field['field']]){
                    $d_module[$field['field']] =  explode(chr(10), $d_module[$field['field']]);
                }
                break;
            }
            case 'channel_role_model_box':
                if($d_module[$field['field']]){
                    $d_module[$field['field']] = M('channel')->cache(true)->where('channel_id = %d', $d_module[$field['field']])->find();
                }
                break;

            case 'channel_role_id_box':
                if($d_module[$field['field']] && $d_module[$field['field']] !== 0){
                    $d_module[$field['field']] = channel_model_role(channel_model_map($d_module['channel_role_model']), $d_module[$field['field']]);
                }
                break;
            case 'pic': {
                $where = array($module . "_id" => $d_module[$module . "_id"], $module . "_field" => $field['field'], "is_main" => 0);
                $d_module[$field['field']] = M($module . 'Images')->where($where)->order('listorder asc')->select();
                break;
            }
            case 'a_box': {
                if (isset($d_module[$field['field']])) {
                    $account_type = M('AccountType')->cache(true)->order('order_id')->select();
                    foreach ($account_type as $field2) {
                        if ($field2['type_id'] == $d_module[$field['field']]) {
                            $d_module[$field['field']] = $field2;
                            break;
                        }
                    }
                }
                break;
            }
            case 's_box': {
                if (isset($d_module[$field['field']])) {
                    $skill = D('SkillCateView')->field("category_id,salary,level,agency_scale,skill_id,status,name")->where('product_id = %d',$d_module["product_id"])->select();;
                    $d_module[$field['field']] = $skill;
                }
                break;
            }
            case 'p_box': {
                if (isset($d_module[$field['field']])) {
                    $catarray = explode(",", $d_module[$field['field']]);
                    $pwhere = array("category_id"=>array("in", $catarray));
                    $d_module[$field['field']] = M('product_category')->field("category_id, serve_modality, name")->where($pwhere)->cache(true)->select();
                }
                break;
            }
            case 'ms_box':
            case 'm_box': {
                if (isset($d_module[$field['field']])) {
                    $status = M('MarketStatus')->cache(true)->where(array('status_id' => $d_module[$field['field']]))->find();
                    if ($d_module[$field['field']] == 916 && $d_module["is_cancel_submit"] == 1) {
                        $status['name'] = "结算退回";
                    }
                    $d_module[$field['field']] = $status;
                    break;
                }
            }

            case 'cultivate_cert_state_box':
            case 'cultivate_examine_state_box':
            case 'cultivate_status_box':
            case 'cultivate_settle_state_box':
                if (isset($d_module[$field['field']]))  {
                    $status = M('CultivateStatus')->cache(true)->where(array('status_id'=>$d_module[$field['field']]))->find();
                    if ($d_module[$field['field']] == 916 && $d_module["is_cancel_submit"] == 1) {
                        $status['name'] = "结算退回";
                    }
                    $d_module[$field['field']] = $status;
                }
                break;

            case 'berth':
                if (isset($d_module[$field['field']]))  {
                    $d_module[$field['field']] =  M('berth')->cache(true)->where('berth_id = %d', $d_module[$field['field']])->find();;
                }
                break;

            case 'dorm':
                if (isset($d_module[$field['field']]))  {
                    $d_module[$field['field']] =   M('dorm')->where('dorm_id = %d', $d_module[$field['field']])->find();
                }
                break;
            case 'user':
                if (isset($d_module[$field['field']]))  {
                    $d_module[$field['field']] = D('StaffRoleView')->where('user.role_id = %d', $d_module[$field['field']])->find();
                }
                break;

            default:{
                break;
            }
        }
    }
}

function fill_fields($module_fields, $module, &$d_module){
    foreach ($module_fields as $v) {
        fill_field($v, $module, $d_module);
    }
}


function fill_model_field($module,$d_module){
    $module_fields = getModelFields($module, array());
    $d_module = fill_permission($d_module, $module_fields);
    fill_fields($module_fields, $module, $d_module);
    return $d_module;
}

function perfect_model_field($model, $data) {
    $field_list = M('Fields')->cache(true)->where(array('model' => $model))->select();
    foreach ($field_list as $field){
        if ($field['form_type'] != 'pic' && $field['form_type'] != 'video' && !$data[$field['field']]) {
            continue;
        }
        if (($field['form_type'] == "branch" || $field['field'] == 'branch_id') && $data[$field['field']]) {
            $data[$field['field']] = $data[$field['field']]['branch_id'];;
        }else {
            switch ($field['form_type']) {
                case 'address':
                    if ($data[$field['field']]) {
                        $data[$field['field']] = implode(chr(10), $data[$field['field']]);
                    }
                    break;
                case 'datetime':
                    $data[$field['field']] = strtotime($data[$field['field']]);
                    break;
                case 'box':
                    eval('$field_type = ' . $field['setting'] . ';');
                    if ($field_type['type'] == 'checkbox') {
                        if ($field['field'] == "apply_scope") {
                            $data[$field['field']] = implode(",", $data[$field['field']]);
                        } else {
                            $data[$field['field']] = implode(chr(10), $data[$field['field']]);
                        }
                    }
                    break;
                case 'co_box': {
                    $b = array_filter($data[$field['field']]);
                    $data[$field['field']] = !empty($b) ? implode(chr(10), $b) : '';
                    break;
                }
                case "channel_role_model_box": {
                    if (is_array($data[$field['field']])) {
                        $data[$field['field']] = $data[$field['field']]['channel_id'];
                    }
                    break;
                }
                case "channel_role_id_box": {
                    if (is_array($data[$field['field']])) {
                        $channel_model = channel_model_role_info($data['channel_role_model']);
                        if ($channel_model) {
                            $data[$field['field']] = $data[$field['field']][$channel_model . '_id'];
                        }
                    }
                    break;
                }
                case "user": {
                    if (is_array($data[$field['field']])) {
                        $data[$field['field']] = $data[$field['field']]['role_id'];
                    }
                    break;
                }
            }
        }
    }
    return $data;
}