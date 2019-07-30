<?php

require 'commonfield.php';

function deldir($dir) {
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                @unlink($fullpath);
            } else {
                @deldir($fullpath);
            }
        }
    }
    closedir($dh);
}


function like_datetime_field_where($search, $condition) {
    if ($search[0] == "" && $search[1] == "") {
		$search = "";
    } else {
        if ($search[0] == "") {
			$search[0] = 0;
        } elseif(!is_numeric($search[1])) {
			$search[0] = strtotime($search[0]);
        }

        if ($search[1] == "") {
			$search[1] = PHP_INT_MAX;
        } elseif(!is_numeric($search[1])) {
			$search[1] = strtotime(date("Y-m-d 23:59:59",strtotime($search[1])));
        }
    }
    return like_filed_where($search, $condition);
}

function like_filed_where($search,$condition='') {
	$where = "";
	switch ($condition) {
		case "is" :
			$where = array('eq', $search);
			break;
		case "isnot" :
			$where = array('neq', $search);
			break;
		case "contains" :
			$where = array('like', '%' . $search . '%');
			break;
		case "not_contain" :
			$where = array('notlike', '%' . $search . '%');
			break;
		case "start_with" :
			$where = array('like', $search . '%');
			break;
		case "not_start_with" :
			$where = array('notlike', $search . '%');
			break;
		case "end_with" :
			$where = array('like', '%' . $search);
			break;
		case "tbetween" :
			$where = array(array('neq',''), array('egt',$search[0]), array('elt',$search[1]), 'and');
			break;
        case "eq" : $where = array('eq',$search);break;
        case "neq" : $where = array('neq',$search);break;
	}
	return $where;
}

//高级搜索生成where条件
function field_where($field,$search,$condition='', $module = ""){
    if ($search == "none" && $condition=='') {
        $condition = "is_empty";
    }
	$like_where = like_filed_where($search,$condition);
	if ($like_where) {
		$where[$field] = $like_where;
	} else {
		switch ($condition) {
			case "is_empty" :  {
				if (in_array($field, array("creator_role_id", "owner_role_id", $module.".creator_role_id", $module.".owner_role_id"))) {
					$where[$field] = array('in',array('', 0));
				} else {
					$where[$field] = array('eq','');
				}
				break;
			}
			case "is_not_empty" :  {
				if (in_array($field, array("creator_role_id", "owner_role_id", $module.".creator_role_id", $module.".owner_role_id"))) {
					$where[$field] = array('not in',array('', 0));
				} else {
					$where[$field] = array('neq','');
				}
				break;
			}
			case "gt" :  $where[$field] = array('gt',$search);break;
			case "egt" :  $where[$field] = array('egt',$search);break;
			case "lt" :  $where[$field] = array('lt',$search);break;
			case "elt" :  $where[$field] = array('elt',$search);break;
			case "eq" : $where[$field] = array('eq',$search);break;
			case "neq" : $where[$field] = array('neq',$search);break;
			case "between" : $where[$field] = array('between',array($search-1,$search+86400));break;
			case "nbetween" : $where[$field] = array('not between',array($search,$search+86399));break;
			case "tgt" :  $where[$field] = array('gt',$search+86400);break;
			case "in" : $where[$field] = array('in',$search);break;
			case "not_in" : $where[$field] = array('not in',$search);break;
			case "tbetween" :
				$where[$field]  = array(array('neq',''), array('egt',$search[0]), array('elt',$search[1]), 'and');
				break;
			default : $where[$field] = array('eq',$search);
		}
	}
	$where["_string"] = "1 ";
	return $where;
}

function getModelFields($model, $where, $excfield = null, $cache = false) {
    if(!$model) return false;
    if (!is_array($model)) {
        $model = array($model);
    }

    $where['model'] = array("in", $model);
    if ($excfield) {
        if (!is_array($excfield)) {
            $excfield = array($excfield);
        }
        $where['field'] = array("not in", $excfield);
    }
    $model_fields = M('Fields')->where($where)->cache($cache)->order('order_id ASC')->select();
    return $model_fields;
}


function getFields($field_ids, $order = true) {
	if (!is_array($field_ids)) {
		$field_ids = array($field_ids);
	}
	$where['field_id'] = array("in", $field_ids);
	if ($order) {
		$model_fields = M('Fields')->cache(true)->where($where)->order('order_id ASC')->select();
	} else {
		foreach($field_ids as $k=>$v) {
			$model_fields[]= M('Fields')->cache(true)->where(array("field_id"=>$v))->find();
		}
	}
	return $model_fields;
}

//方法说明	获取首页需要显示的列名字符串
function getIndexFields($model, $excfield = null, $cache = false){
	$where['in_index'] = 1;
    return getModelFields($model, $where, $excfield, $cache);
}

//方法说明	获取首页需要显示的列名字符串
function getBranchFields($model, $excfield = null){
    $where['is_branch'] = 1;
    return getModelFields($model, $where, $excfield);
}

//获取主表字段 用于搜索
function getMainFields($model, $excfield = null, $cache = false){
	$where['is_main'] = 1;
	$where['operating'] = array("neq", 4);
	return getModelFields($model, $where, $excfield, $cache);
}
/**记录操作日志
 * $id 操作对象id
 * $param_name 参数
 * $text 附加信息
 * 2013-10-23
 **/
function actionLog($id,$param_name='',$text=''){
    $role_id = session('role_id');
    $user = M('user')->where(array('user_id'=>session('user_id')))->find();
    $category = $user['category_id'] == 1 ? L('ADMIN') : L('USER');
    $data['role_id'] = $role_id;
    $data['module_name'] = strtolower(MODULE_NAME);
    $data['action_name'] = strtolower(ACTION_NAME);
	if(!empty($param_name)){
		$data['param_name'] = strtolower($param_name);
	}
    $data['create_time'] = time();
    $data['action_id'] = $id;
    $data['content'] = L('ACTIONLOG',array($category,$user['name'],date('Y-m-d H:i:s'),L(ACTION_NAME),$id,L(MODULE_NAME),$text));
    $actionLog = M('actionLog');
    $actionLog->create($data);
    if($actionLog->add()) return true;
    return false;
}
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}
function msubstrn($str, $start=0, $length) {
    return msubstr($str, $start, $length, "utf-8", false);
}

function getSubCategory($category_id, $category, $separate) {
	$array = array();
	foreach($category AS $value) {
		if ($category_id == $value['parent_id']) {
			$array[] = array('category_id' => $value['category_id'], 'name' => $separate.$value['name'],'description'=>$value['description']);
			$array = array_merge($array, getSubCategory($value['category_id'], $category, $separate.'--'));
		}
	}
	return $array;
}

// 不包括自己所在部门
function getSubDepartment($department_id, $department, $separate, $no_separater) {
	$array = array();
	if($no_separater){
		foreach($department AS $value) {
			if ($department_id == $value['parent_id']) {
				$array[] = array('department_id' => $value['department_id'], 'name' => $separate.$value['name'],'description'=>$value['description']);
				$array = array_merge($array, getSubDepartment($value['department_id'], $department, $separate, 1));
			}
		}
	}else{
		foreach($department AS $value) {
			if ($department_id == $value['parent_id']) {
				$array[] = array('department_id' => $value['department_id'], 'name' => $separate.$value['name'],'description'=>$value['description']);
				$array = array_merge($array, getSubDepartment($value['department_id'], $department, $separate.'--'));
			}
		}
	}
	return $array;
}

//包括自己所在部门
function getSubDepartment2($department_id, $department, $first=0) {
	$array = array();
	$m_department =  M('role_department');
	if($first == 1){
		$depart = $m_department->where('department_id = %d', session('department_id'))->find();
		$array[] = array('department_id'=>$depart['department_id'],'name'=>$depart['name'], 'description'=>$depart['description']);
	}
	foreach($department AS $value) {
		if ($department_id == $value['parent_id']) {
			$array[] = array('department_id' => $value['department_id'], 'name' => $separate.$value['name'],'description'=>$value['description']);
			$array = array_merge($array, getSubDepartment2($value['department_id'], $department, '--'));
		}
	}
	return $array;
}

function getSubDepartmentTreeCode($department_id, $first=0) {
	$string = "";

	$department_list = M('roleDepartment')->where(array('league_id'=>session('league_id'),'parent_id'=>$department_id))->select();
	$position_list = M('position')->where(array('league_id'=>session('league_id'),'department_id'=>$department_id))->select();

	if ($department_list || $position_list) {
		if ($first) {
			$string = '<ul id="browser" class="filetree">';
		} else {
			$string = "<ul>";
		}


		foreach($position_list AS $value) {
			$string .= "<li><span rel='".$value['position_id']."' class='file'>".$value['name']." &nbsp; <span class='control' id='control_file".$value['position_id']."'><a class='position_edit' rel=".$value['position_id']." href='javascript:void(0)'>".L('EDIT')."</a> &nbsp; <a class='position_delete' rel=".$value['position_id']." href='javascript:void(0)'>".L('DELETE')."</a> </span> </span></li>";
		}
		foreach($department_list AS $value) {
			if($first){
				$string .= "<li><span rel='".$value['department_id']."' class='folder'>".$value['name']." &nbsp; <span class='control' id='control_folder".$value['department_id']."'><a class='department_edit' rel=".$value['department_id']." href='javascript:void(0)'>".L('EDIT')."</a> &nbsp; <a class='department_delete' rel=".$value['department_id']." href='javascript:void(0)'>".L('DELETE')."</a> </span></span>".getSubDepartmentTreeCode($value['department_id'])."</li>";
			} else {
				$string .= "<li class='closed'><span rel='".$value['department_id']."' class='folder'>".$value['name']." &nbsp; <span class='control' id='control_folder".$value['department_id']."'><a class='department_edit' rel=".$value['department_id']." href='javascript:void(0)'>".L('EDIT')."</a> &nbsp; <a class='department_delete' rel=".$value['department_id']." href='javascript:void(0)'>".L('DELETE')."</a> </span></span>".getSubDepartmentTreeCode($value['department_id'])."</li>";
			}

		}
		$string .= "</ul>";
	}

	return $string;
}
//type == 1获取授权完整树形图
//type == 2获取选择授权树形图
function getSubPositionTreeCode($position_id, $first=0,  $type=1, $status="0,1", $branch="") {
	$string = "";
	$position_list = M('position')->where(array('league_id'=>session('league_id'),'parent_id'=>$position_id))->select();

	if ($position_list) {
		if ($first) {
			if($type == 1)
				$string = '<ul id="browser" class="filetree">';
			else
				$string = '<ul class="filetree">';
		} else {
			$string = "<ul>";
		}
		if ($branch != "") {
			if ($branch == 0) {
				$branch_list = get_branch_all_role();
			} else {
				$branch_list = get_branch_all_role($branch);
			}
		}
		foreach($position_list AS $value) {
			$department_name = M('RoleDepartment')->where('department_id = %d', $value['department_id'])->getField('name');

            $where = array(
                "position.position_id"=>$value['position_id'],
                "status"=>array("in", $status)
            );

			$user_list = D('RoleView')->where($where)->select();
			$user_str = '';
			foreach($user_list as $v){
				if ($branch_list)
				{
					$inbranch = in_array($v["role_id"], $branch_list);
					if (($branch == 0 )) {
						if ($inbranch) {
							continue;
						}
					} elseif ( !$inbranch){
						continue;
					}
				}
				if($v['status'] == '0'){
					$username = $v['user_name'].'-未激活';
				}elseif($v['status'] == '2'){
					$username = '<del>'.$v['user_name'].'</del>';
				}else{
					$username = $v['user_name'];
				}
				$user_str .= '<a style="color: #000000;" href="'.U('staff/viewuserid','id='.$v['user_id']).'">'.$username.'、</a>';
			}
			if($user_str) $user_str = '('.$user_str.')';

			if($type == 1){
				$link_str = " <span class='control' id='control_file".$value['position_id']."'> <a class='position_edit' rel=".$value['position_id']." href='javascript:void(0)'>".L('EDIT')."</a> &nbsp; <a class='permission' rel=".$value['position_id']." href='javascript:void(0)'>".L('AUTHORIZE')."</a> &nbsp; <a class='position_delete' rel=".$value['position_id']." href='javascript:void(0)'>".L('DELETE')."</a></span>";
			}else{
				//$link_str = " <span class='control' id='control_file".$value['position_id']."'> <a class='allow_permission_id' rel=".$value['position_id']." href='javascript:void(0)'>".'选择'."</a> ";
				$link_str = " <span class='control' id='control_file".$value['position_id']."'> <input class='allow_permission_id' type='radio' name='parent_id' rel=".$value['position_id']." href='javascript:void(0)'>";
			}

			$string .= "<li style='list-style-type: none;'><span rel='".$value['position_id']."' class='file'>".$value['name']." - $department_name"." &nbsp; ".$user_str." &nbsp;".$link_str."</span>".getSubPositionTreeCode($value['position_id'], 0, $type, $status, $branch)."</li>";

		}
		$string .= "</ul>";
	}

	return $string;
}

function getSubPositionTreeCode2($position_id, $first=0,  $type=1) {
    $string = "";
    $position_list = M('position')->where('parent_id = %d', $position_id)->select();

    if ($position_list) {
        if ($first) {
            if($type == 1)
                $string = '<ul id="browser" class="filetree">';
            else
                $string = '<ul class="filetree">';
        } else {
            $string = "<ul>";
        }
        foreach($position_list AS $value) {
            $department_name = M('RoleDepartment')->where('department_id = %d', $value['department_id'])->getField('name');
            $user_list = D('RoleView')->where('position.position_id = %d', $value['position_id'])->select();
            $user_str = '';
            foreach($user_list as $v){
                if($v['status'] == '0'){
                    $username = $v['user_name'].'-未激活';
                }elseif($v['status'] == '2'){
                    $username = '<del>'.$v['user_name'].'</del>';
                }else{
                    $username = $v['user_name'];
                }
                $user_str .= '<a style="color: #000000;" href="'.U('user/view','id='.$v['user_id']).'" target="_blank">'.$username.'、</a>';
            }
            if($user_str) $user_str = '('.$user_str.')';

            if($type == 1){
                $link_str = " <span class='control' id='control_file".$value['position_id']."'> <a class='permission' rel=".$value['position_id']." href='javascript:void(0)'>".L('AUTHORIZE')."</a> </span>";
            }else{
                $link_str = " <span class='control' id='control_file".$value['position_id']."'> <input class='allow_permission_id' type='radio' name='parent_id' rel=".$value['position_id']." href='javascript:void(0)'>";
            }

            $string .= "<li style='list-style-type: none;'><span rel='".$value['position_id']."' class='file'>".$value['name']." - $department_name"." &nbsp; ".$user_str." &nbsp;".$link_str."</span>".getSubPositionTreeCode2($value['position_id'], 0, $type)."</li>";

        }
        $string .= "</ul>";
    }

    return $string;
}


function getSubRoleId($self = true){
	$all_role = M('role')->where(array('league_id'=>session('league_id'), "user_id"=>array("neq", 0)))->select();
	$below_role = getSubRole(session('role_id'), $all_role);
	$below_ids = array();
	if ($self) {
		$below_ids[] = session('role_id');
	}
	foreach ($below_role as $key=>$value) {
		$below_ids[] = $value['role_id'];
	}
	return $below_ids;
}

function getAllRoleId($self = true){
    $below_role = M('role')->where(array('league_id'=>session('league_id'), "user_id"=>array("neq", 0)))->select();
    $below_ids = array();
    if ($self) {
        $below_ids[] = session('role_id');
    }
    foreach ($below_role as $key=>$value) {
        $below_ids[] = $value['role_id'];
    }
    return $below_ids;
}



//原获取职位列表方法
function getSubRole($role_id, $role_list, $separate) {
	$d_role = D('RoleView');
	if($d_role->where('role.role_id = %d', $role_id)->find()){
		$position_id = $d_role->where('role.role_id = %d', $role_id)->getField('position_id');
	}else{
		$position_id  = 0;
	}
	$sub_position = getPositionSub($position_id ,true);
	foreach($sub_position AS $position_id) {
		$son_role = $d_role->where('role.position_id = %d', $position_id['position_id'])->select();
		foreach($son_role as $val){
			$array[] = array('role_id' => $val['role_id'],'user_id' => $val['user_id'], 'parent_id' => $val['parent_id'], 'name' => $separate . $val['department_name'] . ' | ' . $val['role_name']);
		}
	}
	return $array;
}
//原获取下级职位列表方法
function getPositionSub($position_id ,$sub = false){
    $where['league_id'] = session('league_id');
    $where['parent_id'] = $position_id;
	$sub_position = M('position')->cache(true)->where($where)->select();
	$array = $sub_position;
	if($sub){
		foreach($sub_position as $value){
			$son_position = getPositionSub($value['position_id'] ,$sub);
			if(!empty($son_position)){
				$array = array_merge($array, $son_position);
			}
		}
	}
	return $array;
}

function getPositionRole($position_id){
	static $role_ids = null;
	if (!$role_ids) {
        $where['league_id'] = session('league_id');
        $where['position_id'] = $position_id;
        $role_ids =M('role')->where($where)->getField("role_id", true);
	}
	return $role_ids;
}

function getSubPosition($position_id, $position, $separate) {
	$array = array();
	foreach($position AS $key=> $value) {
		if ($position_id == $value['parent_id']) {
			$m_department = M('RoleDepartment');
            $where['league_id'] = session('league_id');
            $where['department_id'] = $value['department_id'];
			$department_name = $m_department->where($where)->getField('name');
			$array[] = array('position_id' => $value['position_id'], 'name' => $separate . $department_name . ' | ' . $value['name'],'description'=>$value['description']);
			$array = array_merge($array, getSubPosition($value['position_id'], $position, $separate.' -- '));
		}
	}
	return $array;
}


//通过部门id获取该部门员工
function getRoleByDepartmentId($department_id){
	$id_array = array($department_id);
    $where['league_id'] = session('league_id');
    $departments = M('roleDepartment')->where($where)->select();
	$roleList = D('RoleView')->where('position.department_id = %d and role.role_id in (%s)', $department_id, implode(',', getSubRoleId()))->select();
	foreach($departments AS $value) {
		if ($department_id == $value['parent_id']) {
			$id_array[] = $value['department_id'];
			$role_list = getRoleByDepartmentId($value['department_id']);
			if(!empty($role_list)){
				$roleList = array_merge($roleList, $role_list);
			}
		}
	}
	return $roleList;
}
/**
 * Warning提示信息
 * @param string $type 提示类型 默认支持success, error, info
 * @param string $msg 提示信息
 * @param string $url 跳转的URL地址
 * @return void
 */
function alert($type='info', $msg='', $url='') {
    //多行URL地址支持
    $url        = str_replace(array("\n", "\r"), '', $url);
	$alert = unserialize(stripslashes(cookie('alert')));
    if (!empty($msg)) {
        $alert[$type][] = $msg;
		cookie('alert', serialize($alert));
	}
    if (!empty($url)) {
		if (!headers_sent()) {
			// redirect
			header('Location: ' . $url);
			exit();
		} else {
			$str    = "<meta http-equiv='Refresh' content='0;URL={$url}'>";
			exit($str);
		}
	}

	return $alert;
}

function alert_back($type='info', $msg='') {
    alert($type, $msg);
    header('Cache-control: private, must-revalidate');
    exit("<script>location.href = 'javascript:history.go(-1);'</script>");
}

function parseAlert() {
	$alert = unserialize(stripslashes(cookie('alert')));
	cookie('alert', null);

	return $alert;
}

function getUserByRoleId($role_id){
	return  D('RoleView')->cache(true)->where('role.role_id = %d', $role_id)->find();
}


function getUserByUserId($user_id){
    return  D('RoleView')->cache(true)->where('role.user_id = %d', $user_id)->find();
}

function sendRequest($url, $params = array() , $headers = array()) {
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	if (!empty($params)) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	}
	if (!empty($headers)) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$txt = curl_exec($ch);
    curl_close($ch);
    return stripos($txt, "success:") == 0;
}

//success:14302135791233

//$sysMessage=0 为系统消息
function sendMessage($id,$content,$sysMessage=0,$weixin = 0){
	if(!$id) return false;
	if(!$content) return false;
	$m_message = M('message');
	if($sysMessage == 0) $data['from_role_id'] = session('role_id');
	$data['to_role_id'] = $id;
	$data['content'] = $content;
	$data['read_time'] = 0;
	$data['send_time'] = time();
	return $m_message->add($data);
}


function is_utf8($liehuo_net){
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$liehuo_net) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$liehuo_net) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$liehuo_net) == true)
	{
		return true;
	}
	else
	{
		return false;
	}
}

//验重二维数组排序  $arr 数组 $keys比较的键值
function array_sort($arr,$keys,$type='asc'){
	$keysvalue = $new_array = array();
	foreach ($arr as $k=>$v){
		$keysvalue[$k] = $v[$keys];
	}
	if($type == 'asc'){
		asort($keysvalue);
	}else{
		arsort($keysvalue);
	}
	reset($keysvalue);
	$i = 0;
	foreach ($keysvalue as $k=>$v){
		if($i < 8 && $arr[$k][search] > 0){
			$new_array[] = $arr[$k]['value'];
			$i++;
		}

	}
	return $new_array;
}

function get_cost_html($model_name, $model, $mfield, $value, $view) {
    $const_where = array(
        $model_name."_id"=>$model[$model_name.'_id'],
        "cost_field"=>$mfield
    );
    $m_const = M($model_name."_cost")->where($const_where)->find();
    if ($m_const && $m_const['status'] != 0) {
        switch($m_const['status']) {
            case "1": {
                return '<span>' . $value . '</span>&nbsp;[已提交]';
                break;
            }
            default: {
                return '<span>' . $value . '</span>&nbsp;[已提取]';
                break;
            }
        }
    } else if ($view && $value > 0) {
        return '<span>' . $value . '</span>&nbsp;[<a href="'.U($model_name."/cost_submit", "mname=".$model_name."&id=".$model[$model_name.'_id']."&field=".$mfield).'" class="cost_submit">提交</a>]';

    }
    return false;
}

function get_groupfields_list($module, $assort = "basic", $excfield = null) {
    $where = array('model'=>$module);
    if ($assort == "basic") {
        $fields_group_list['0'] = array(
            'field_group_id'=>'0',
            'name'=>L("DEFAULT_FILED_GROUP_NAME"),
            'operating'=>'1',
        );
        $where = array(
            'model'=>$module,
            'field_group_id'=>0
        );
        if ($excfield) {
            if (!is_array($excfield)) {
                $excfield = array($excfield);
            }
            $where['field'] = array("not in", $excfield);
        }
        $fields_group_list['0']['fields'] = M('fields')->cache(true)->where($where)->order('order_id ASC')->select();
    }
    $where['assort'] = $assort;

    $fields_group = M('FieldsGroup')->where($where)->cache(true)->order('order_id ASC')->select();
    foreach($fields_group as $key=>$group) {
        $group_id = $group['field_group_id'];
        $where = array(
            'model'=>$module,
            'field_group_id'=>$group_id
        );
        if ($excfield) {
            if (!is_array($excfield)) {
                $excfield = array($excfield);
            }
            $where['field'] = array("not in", $excfield);
        }
        $field_list = M('Fields')->where($where)->cache(true)->order('order_id')->select();
        $fields_group_list[$group_id] = $group;
        $fields_group_list[$group_id]['fields'] = $field_list;
    }
    return $fields_group_list;
}


function field_show_html($module="", $field="", $d_module=array()) {
    $field_group = field_list_show($module, $d_module, "", $field, "all");
    if ($field_group) {
        foreach($field_group as $v) {
            if ($v['fields']) {
                $field_group = $v['fields'][0];
                break;
            }
        }
    }
    return $field_group;
}

function allfield_list_show($module="",$d_module=array(),$app="", $field="", $ac = "show"){
    return  field_list_show($module, $d_module, $app, $field, "basic", $ac);
}

function product_field_list_show($module="",$d_module=array(), $assort = "basic", $ac = "show") {
    return field_list_show($module, $d_module, "", "", $assort,$ac);
}

function get_field_group_list($module, $assort = "basic") {
	$where = array('model'=>$module);
	if ($assort == "" || $assort == "basic" || $assort == "all") {
		$fields_group_list['0'] = array(
				'field_group_id'=>'0',
				'name'=>L("DEFAULT_FILED_GROUP_NAME"),
				'operating'=>'1',
		);
		$fields_group_list['0']['fields'] = M('fields')->cache(true)->where(array('model'=>$module,'field_group_id'=>0))->order('order_id ASC')->select();
	}
	if ($assort != "all") {
		$where['assort'] = $assort;
	}

	foreach(M('FieldsGroup')->where($where)->cache(true)->order('order_id ASC')->select() as $k=>$group) {
		$fields_group_list[$group['field_group_id']] = $group;
	}
	return $fields_group_list;
}

//自定义字段html输出     $field为特殊验重字段   $d_module=($ModuelView)
function field_list_show($module="",$d_module=array(),$app="", $field="", $assort = "basic", $ac = "show"){
	$fields_group_list = get_field_group_list($module, $assort);
    foreach($fields_group_list as $group_id=>$group) {
        if ($field != "") {
            $field_list = M('Fields')->cache(true)->where('model = "' . $module . '" and field = "' . $field . '" and field_group_id="'.$group_id.'"')->order('order_id')->select();
        } else {
            $field_list = M('Fields')->cache(true)->where('model = "' . $module . '" and field_group_id="'.$group_id.'"')->order('order_id')->select();
        }

        foreach ($field_list as $k => $v) {
            $value = $d_module[$v['field']] ? $d_module[$v['field']] : '';

            if ($v['field'] == 'customer_id') {
                if($d_module['customer_id']){
                    $customer_id = intval($d_module['customer_id']);
                }else{
                    $customer_id = intval($_GET['customer_id']);
                }
				if($customer_id) {
					$field_list[$k]['html'] = customer_show_html($customer_id);
				}
            } elseif ($v['field'] == 'product_id') {
                if($d_module['product_id']){
                    $product_id = intval($d_module['product_id']);
                }else{
                    $product_id = intval($_GET['product_id']);
                }
                if($product_id){
					$field_list[$k]['html'] = product_show_html($product_id);
				}
            }
            elseif ($v['field'] == 'serve_id') {
                if($d_module['serve_id']){
                    $serve_id = intval($d_module['serve_id']);
                }else{
                    $serve_id = intval($_GET['serve_id']);
                }

                if($serve_id){
                    $serve = M('serve')->where('serve_id = %d', $serve_id)->find();
                }

                if($serve){
                    $field_list[$k]['html'] = '<span><a href="'.U('serve/view', 'id='.$serve_id).'" target="_blank">'.$serve['idcode']." - ".$serve['name'] . '</a></span>';;
                }
            }
            elseif ($v['field'] == 'subgroup') {
                $module_group = array();
                $where = array(
                    $module."_subgroup.".$module."_id"=>$d_module[$module."_id"]
                );
                $where['league_id'] = session('league_id');

                foreach(D(ucfirst($module)."GroupView")->where($where)->select() as $v) {
                    $module_group[] = "<a href='".U($module."/index","act=groupsearch&group_type=1&module_group_id=".$v[$module.'_group_id'])."'>".$v['group_name']."</a>";
                }
                $field_list[$k]['html'] = implode(",", $module_group);
            }elseif (($module == "product" && $v['field'] == 'insurance')) {
                $field_list[$k]['html'] = product_insurance_show($d_module['product_id']);
            }elseif ($v['field'] == 'blank_name') {
                $field_list[$k]['html'] = black_name($d_module['blank_name']);
            }elseif ($v['field'] == 'astrict') {
                $field_list[$k]['html'] = '<span>' . $value . '</span>&nbsp;&nbsp;';
				if ($d_module['is_owner']) {
					$field_list[$k]['html'] .= '<a target="_blank" href="'.U($module."/astrict", "id=".$d_module[$module.'_id']).'">[授权]</a>';
				}
            }elseif ($v['field'] == 'payway') {
                $field_list[$k]['html'] = '<span>' . payway_name($value) . '</span>';
            }
            elseif ($v['field'] == 'currier_id') {
                if($d_module['currier_id']){
                    $currier_id = intval($d_module['currier_id']);
                }else{
                    $currier_id = intval($_GET['currier_id']);
                }

                if($currier_id){
                    $currier = M('currier')->where('currier_id = %d', $currier_id)->find();
                }

                if($currier){
                    $field_list[$k]['html'] = currier_show_html($currier);
                }
            }
			elseif (($module == 'cultivate' && $v['form_type'] == 'currier_model')) {
				if ($d_module['model']) {
					$model = $d_module['model'];
				} else {
					$model = $_GET['model'];
				}
				if ($model) {
					$setting_str = '$setting=' . $v['setting'] . ';';
					eval($setting_str);
					$field_list[$k]['html'] = '<span>'.$setting['data'][$model].'</span>';;
				}
			}
			elseif ( in_array($v['form_type'], array("se_box", "order_classify", "currier_model", 'currier_crad'))) {
				if ($d_module[$v['field']]) {
					$model = $d_module[$v['field']];
				} else {
					$model = $_GET[$v['field']];
				}
				if ($model) {
					$setting_str = '$setting=' . $v['setting'] . ';';
					eval($setting_str);
					$field_list[$k]['html'] = '<span>'.$setting['data'][$model].'</span>';;
				}
			}
			elseif ($module == 'cultivate' && $v['form_type'] == 'currier_model_id') {
				if ($d_module['model_id']) {
					$model_id = intval($d_module['model_id']);
				} else {
					$model_id = intval($_GET['model_id']);
				}
				if ($d_module['model']) {
					$model = $d_module['model'];
				} else {
					$model = $_GET['model'];
				}

				if ($model_id && $model) {
					$m_cultivate_model = M($model)->where($model . '_id = %d', $model_id)->find();
				}

				if ($m_cultivate_model) {
					$field_list[$k]['html'] = '<span><a href="'.U($model.'/view', 'id='.$model_id).'" target="_blank">'.$m_cultivate_model['idcode']." - ".$m_cultivate_model['name'] . '</a></span>';;
				}
			}
            elseif (($module == "cultivate" && $v['field'] == 'model_owner_role_id')) {
                $field_list[$k]['html'] = role_html($value);
                if ($value && !is_cultivate_settle($d_module)) {
                    $field_list[$k]['html'].="&nbsp; <a href='".U("cultivate/update_default_urge_role",'id='.$d_module['cultivate_id'])."' ref='".$value."' class='cultivate_model_owner_role_id'>刷新</a>";
                }
            }
			elseif ($module == 'commiss' && $v['field'] == 'owner_role_id') {
				$field_list[$k]['html'] = role_html($value);
			}
			elseif ($module == 'cultivate' && $v['field'] == 'category_id') {
					$field_list[$k]['html'] = '<span>'.$d_module['category_name']. '</span>';;
			}
			elseif ($v['field'] == 'branch_id') {
				$field_list[$k]['html'] = branch_html($value);
            }elseif ($v['field'] == 'queue_branch_id') {
				if ($value != "-1") {
					$field_list[$k]['html'] = branch_html($value);
				}
			}
			elseif ($v['field'] == 'position_id') {
				$field_list[$k]['html'] = position_html($value);;
			}
			elseif ($v['field'] == 'department_id') {
				$field_list[$k]['html'] = department_html($value);;
			}
            elseif ($v['field'] == 'category_config') {
                $field_list[$k]['html'] = category_config_html($d_module[$v['field']]);;
            }
            elseif (cost_filed_map($module, $v['field'])) {
                $html = get_cost_html($module, $d_module, $v['field'], $value, true);
                if ($html) {
                    $field_list[$k]['html'] = $html;
                }
            }
            else {
                switch ($v['form_type']) {
                    case 'textarea' :
                        $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        break;
                    case 'mobile' :
                        if ($value) {
                            if (is_hide_field($value)) {
                                $field_list[$k]['html'] = '<span>' . $value . '</span>';
                            } else {
                                $field_list[$k]['html'] = '<span>' . $value . '</span>';
                            }
                        }
                        break;
                    case 'box' :
                        $field_list[$k]['html'] = '<span>' . $d_module[$v['field']] . '</span>';
                        break;

                    case 'editor' :
                        $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        break;
                    case 'datetime' :
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else{
                            $field_list[$k]['html'] = '<span>' . pregtime($value, $v['is_showtime']) . '</span>';
                        }
                        break;
                    case 'number' :
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {
                            $field_list[$k]['html'] = '<span>' . number_format($value ? $value:0) . '</span>';
                        }
                        break;
                    case 'floatnumber' :
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {
                            $field_list[$k]['html'] = '<span>' . number_format($value ? $value:0, 2) . '</span>';
                        }
                        break;
                    case 'linkaddress' :
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {
                            $field_list[$k]['html'] = '<a href="$value" target="_blank">' . $value . '</a>';
                        }
                        break;
                    case 'address':
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {
                            $field_list[$k]['html'] = '<span>' . format_address_field($value) . '</span>';
                        }
                        break;
                    case 'p_box':
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {
                            foreach (M('product_category')->cache(true)->where(array('league_id'=>session('league_id'), 'enable'=>1))->select() as $v2) {
                                if ($v2['category_id'] == $value) {
                                    $field_list[$k]['html'] = '<span>' . $v2['name'] . '</span>';
                                    break;
                                }
                            }
                        }

                        break;
                    case 'bc_box' :
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {
                            $str = product_category_show($value);
                            $field_list[$k]['html'] = $str . ' <span id="' . $v['field'] . 'Tip" style="color:red;"></span>&nbsp; ' . $input_tips;
                        }
                        break;
					case 'channnel_box':
					case 'channel_role_model_box':
						if($value && $value !== 0){
							$channel = M('channel')->cache(true)->where('channel_id = %d', $value)->find();
						}
						if($channel){
							$field_list[$k]['html'] =  '<span><a target="_blank" href="'.U('channel/view', 'id='.$channel['channel_id']).'">' . $channel['name']. '</a></span>';
						}
						break;

					case 'channel_role_id_box':
						if($value && $value !== 0){
							$field_list[$k]['html'] = '<span>'.channel_model_role_show_html($d_module["channel_role_model"], $value, true).'</span>';
						}
						break;

					case 'file': {
                        $where = array(
                            "module_id" => $d_module[$module . "_id"],
                            "module" => $module,
                            "field" => $v['field'],
                        );
                        $filelist = M('file')->where($where)->select();
                        $html = '<table class="table"><tbody><tr><td>文件名</td><td>添加者</td><td>添加时间</td></tr>';
                        foreach ($filelist as $filek => $filev) {
                            $role = D('RoleView')->where('role.role_id = %d', $filev['role_id'])->find();
                            $html .= '<tr><td>';
                            $html .= '<a target="_blank" href="' . $filev["file_path"] . '">' . $filev['name'] . '</a>';
                            $html .= '</td><td>';
                            $html .= $role['user_name'];
                            $html .= '</td><td>';
                            $html .= date("Y-m-d g:i:s a", $filev["create_date"]);
                            $html .= '</td></tr>';
                        }
                        $html .= '</tbody></table>';
                        $field_list[$k]['html'] = $html;
                        break;
                    }
                    case 'pic': {
                        $html = "";
                        $where = array(
                            $module ."_id" => $d_module[$module . "_id"],
                            $module ."_field" => $v['field'],
                            "is_main" => 0
                        );
                        $piclist = M($module .'Images')->where($where)->order('listorder asc')->select();

                        $thumb_width = ($ac == "show" ? 117 : 247);
                        $thumb_height = ($ac == "show" ? 117 : 247);

                        foreach ($piclist as $pick => $picv) {
                            $html .= '<div class="box-secondary" rel="' . $picv['images_id'] . '">';
                            $html .='    <a href="' . $picv['path'] . '" target="_self"  data-lightbox="group-1">';
                            $html .='        <img src="' . thumb($picv['path'], $thumb_width, $thumb_height) . '" class="thumbnail productotherlistthumb img-responsive" alt="'.$picv['name'].'">';
                            $html .='    </a>';
							if ($v['is_pic_name'] == 1) {
								$html .='    <a>'.$picv['name'].'</a>';
							}
                            $html .=' </div>';
                        }
                        $field_list[$k]['html'] = $html;
                        $field_list[$k]['piclist'] = $piclist;
                        break;
                    }
                    case 'video': {
                        $where = array(
                            $module ."_id" => $d_module[$module . "_id"],
                            $module ."_field" => $v['field'],
                        );
                        $videolist = M($module .'Video')->where($where)->order('listorder asc')->select();
                        $html = '<table class="table"><tbody><tr><td>文件名</td><td>添加者</td><td>添加时间</td></tr>';
                        foreach ($videolist as $videok => $videov) {
                            $role = D('RoleView')->where('role.role_id = %d', $videov['role_id'])->find();
                            $html .= '<tr><td>';
                            $html .= '<a target="_blank" href="' . U($module ."/viewvideo", "path=" . $videov["file_path"]) . '">' . $videov['name'] . '</a>';
                            $html .= '</td><td>';
                            $html .= $role['user_name'];
                            $html .= '</td><td>';
                            $html .= date("Y-m-d g:i:s a", $videov["create_date"]);
                            $html .= '</td></tr>';
                        }
                        $html .= '</tbody></table>';
                        $field_list[$k]['html'] = $html;
                        break;
                    }
                    case 'a_box':
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {
                            $account_type = M('AccountType')->cache(true)->order('order_id')->select();
                            foreach ($account_type as $v2) {
                                if ($v2['type_id'] == $value) {
                                    $field_list[$k]['html'] = '<span>' . $v2['name'] . '</span>';
                                    break;
                                }
                            }
                        }

                        break;

					case 'ms_box':
                    case 'm_box':
						if (is_hide_field($value)) {
							$field_list[$k]['html'] = '<span>' . $value . '</span>';
						} else {
							$field_list[$k]['html'] = '<span>' . format_market_status($value, $d_module["is_cancel_submit"]) . '</span>';
						}
						break;

					case 'cultivate_cert_state_box':
					case 'cultivate_examine_state_box':
					case 'cultivate_status_box':
					case 'cultivate_settle_state_box':
					if (is_hide_field($value)) {
							$field_list[$k]['html'] = '<span>' . $value . '</span>';
						} else {
							$field_list[$k]['html'] = '<span>' . format_cultivate_status($value, $d_module["is_cancel_submit"]) . '</span>';
						}
						break;

                    case 'tr_cate' :
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {
                            $field_list[$k]['html'] = format_module_category("currier", $d_module[$v['field']]);

                        }
                        break;

                    case 'sv_cate' :
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {
                            $field_list[$k]['html'] = format_module_category("serve", $d_module[$v['field']]);

                        }
                        break;
                    case 'berth':
						if (is_hide_field($value)) {
							$field_list[$k]['html'] = '<span>' . $value . '</span>';
						}
						else {
							$field_list[$k]['html'] = berth_show_html($value);
						}
                        break;

					case 'dorm':
						if($value){
							$dorm = M('dorm')->where('dorm_id = %d', $value)->find();
						}

						if($dorm){
							if (is_hide_field($value)) {
								$field_list[$k]['html'] = '<span>' . $value . '</span>';
							}
							else {
								$field_list[$k]['html'] = '<span><a href="'.U('dorm/view', 'id='.$value).'" target="_blank">'.$dorm['name'] . '</a></span>';;
							}
						}
						break;

                    default:{
                        if (is_hide_field($value)) {
                            $field_list[$k]['html'] = '<span>' . $value . '</span>';
                        } else {

							$role_fileds = array(
									"role_id",
									"creator_role_id",
									"owner_role_id",
									"shopkeeper_role_id",
									"model_owner_role_id",
									'queue_role_id',
							);
                            if (in_array($v['field'],$role_fileds)) {
								$field_list[$k]['html'] = role_html($value);
                            } elseif ($v['field'] == 'create_time' || $v['field'] == 'update_time') {
                            } else {
                                $field_list[$k]['html'] = '<span>' . $value . '</span>';
                            }
                        }
                        break;
                    }
                }
            }
        }
        $fields_group_list[$group_id]['fields'] = $field_list;
    }

    foreach($fields_group_list as $k=>$gvo) {
        foreach($gvo['fields'] as $kvo=>$vo) {
            if ($vo['operating'] == '4'){
                unset($fields_group_list[$k]['fields'][$kvo]);
            }
        }
		if (count($fields_group_list[$k]['fields']) == 0) {
			unset($fields_group_list[$k]);
		}
    }
    return $fields_group_list;
}

function field_html($module="", $field="", $type="add", $d_module=array()) {
    $field_group = field_list_html($type, $module, $d_module, "", $field);
    if ($field_group) {
        $field_group = $field_group[0]['fields'][0];
    }
    return $field_group;
}

function field_html_show($module="", $field="", $type="add", $d_module=array()) {
	$field_group = field_html($module,$field, $type, $d_module);
	return $field_group['html'];
}

function product_field_list_html($type="add",$module="",$d_module=array(), $assort="basic", $inadd = 1){
    return field_list_html($type, $module, $d_module, "", "", $assort, $inadd);
}

function field_list_html_add($module="", $excfield = null,$field_group_id="", $inadd = 1) {
    return field_list_html("add", $module, array(), $field_group_id, "", "basic", $excfield, $inadd);
}

function field_list_html_edit($module="",$d_module=array(), $excfield = null,$field_group_id="", $inadd = 1) {
    return field_list_html("edit", $module, $d_module, $field_group_id, "", "basic", $excfield, $inadd);
}

function ious_account_type() {
    return array('174', '175','176','177','178','179','180','181','171','173');
}

function is_ious($t) {
    return in_array($t, ious_account_type());
}

function payway_name($bc) {
    switch($bc) {
        case "wxpay":return "微信支付";
        case "alipay":return "支付宝";
        case "blank":return "网上银行";
        case "cash":return "现金";
        case "other":return "其他";
        default:{
			if (is_numeric($bc) || $bc == "fare")
				return "";
			return $bc;
		}
    }
}

function payway_blank_name($bc) {
    switch($bc) {
        case "wxpay":return "微信支付";
        case "alipay":return "支付宝";
        case "cash":return "现金";
        default:return black_name($bc);
    }
}

function black_name($bc = null) {
    if ($bc === "") {
        return $bc;
    }
    $blank_list = array(
        "CMB"=>"招商银行",
        "ICBC"=>"工商银行",
        "ABC"=>"农业银行",
        "BOC"=>"中国银行",
        "CCB"=>"建设银行",
        "COMM"=>"交通银行",
        "GDB"=>"广发银行",
        "HXB"=>"华夏银行",
        "CITIC"=>"中信银行",
        "CIB"=>"兴业银行",
        "CMBC"=>"民生银行",
        "SPDB"=>"浦发银行",
        "CEB"=>"光大银行",
        "POSTGC"=>"中国邮政储蓄银行",
        'SPABANK'=>'平安银行',
        'BJBANK'=>'北京银行',
    );
    return $bc != null ? $blank_list[$bc] : $blank_list;
}

function blank_select($bc) {
    $black_select = '<select name="blank_name" id="blank_name"  placeholder="请选择银行"><option value="">请选择银行</option>';
    foreach(black_name() as $k=>$v) {
        $black_select.= '<option value="'.$k.'"';
        if ($bc == $k) {
            $black_select.= ' selected ';
        }
        $black_select.= '>'.$v.'</option>';
    }
    $black_select.= '</select>';
    return $black_select;
}


function position_select($field, $val=null, $department_id = null) {
    $position_select = '<select name="'.$field.'" id="'.$field.'"  placeholder="请选择岗位"><option value="">请选择岗位</option>';
    if (!$department_id) {
        $department_id = M('position')->where("position=".$val)->getField("department_id");
    }
    $position_list = M('position')->where("department_id=".$department_id)->select();
    foreach($position_list as $k=>$v) {
        $position_select.= '<option value="'.$v['position_id'].'"';
        if ($val == $v['position_id']) {
            $position_select.= ' selected ';
        }
        $position_select.= '>'.$v['name'].'</option>';
    }
    $position_select.= '</select>';
    return $position_select;
}

function position_html($val) {
	$position = M('position')->cache(true)->where("position_id=".$val)->find();
	if ($position) {
		return '<span>' . $position['name'] . '</span>';
	}
	return "";
}


function position_list($department_id = null) {
	$where = array();
    $where['league_id'] = session('league_id');
	if ($department_id) {
		$where['department_id'] = $department_id;
	}
	return M('position')->cache(true)->where($where)->select();
}


function department_html($val) {
    $where['league_id'] = session('league_id');
    $where['department_id'] = $val;
    $department = M('roleDepartment')->cache(true)->where($where)->find();
	if ($department) {
		return '<span>' . $department['name'] . '</span>';
	}
	return "";
}

function department_list() {
    $where['league_id'] = session('league_id');
    return M('roleDepartment')->cache(true)->where($where)->select();
}

function role_html($val) {
	$role = D('Manage/RoleView')->cache(true)->where('role.role_id = %d', $val)->find();
	if ($role) {
		return '<span><a class="role_info" href="javascript:void(0);" rel="'.$val.'">' . $role['user_name'] . '</a></span>';
	}
	return "";
}

function role_show($val) {
	$role = D('Manage/RoleView')->cache(true)->where('role.role_id = %d', $val)->find();
	if ($role) {
		return $role['user_name'];
	}
	return "";
}

function market_show_html($market, $assort = "", $product_id="") {
    if(!is_array($market)){
        $market = M('market')->where('market_id = %d', $market)->find();
    }
    if (!$market) {
        return "";
    }

    $param = array(
        "id"=>$market['market_id']
    );
    if ($assort) {
        $param['assort'] = $assort;
    }
    if ($product_id) {
        $param['show_product_id'] = $product_id;
    }
    $html = '<span><a href="'.U('market/view', $param).'" target="_blank">' .($market['market_idcode']?$market['market_idcode']:$market['idcode']).'</a></span>&nbsp;';;

    return $html;
}

function product_show_html($product, $show_sms = true, $assort = "") {
	if(!is_array($product)){
		$product = M('product')->where('product_id = %d', $product)->find();
	}
	if (!$product) {
		return "";
	}
	$param = array(
		"id"=>$product['product_id'],
	);
	if ($assort != "") {
		$param['assort'] = $assort;
	}
	$html = '<span><a href="'.U('product/'.$assort.'view', $param).'" target="_blank">[' .$product['idcode'].'] '.$product['name'] . '</a></span>&nbsp;';;

	return $html;
}

function market_service_product_html($market_id, $product_id) {
    $product = M('product')->where('product_id = %d', $product_id)->find();
    if (!$product) {
        return "";
    }
    return '<span><a class="market_service_product" market_id="'.$market_id.'" href="'.U('product/view', 'id='.$product['product_id']).'" target="_blank">[' .$product['idcode'].']'.$product['name'] . '</a></span>&nbsp;';;
}

function customer_show_html($customer, $show_sms = true) {
	if(!is_array($customer)){
		$customer = M('customer')->where('customer_id = %d', $customer)->find();
	}
	$html = '<a href="'.U('customer/view', 'id='.$customer['customer_id']).'" target="_blank">[' .$customer['idcode'].'] '.$customer['name'] . '</a>&nbsp';;;
	return $html;
}

function model_info_show_html($name, $id, $assort = "") {
	$m_model = M($name)->where($name.'_id = %d', $id)->find();
	$param = array(
		"id"=>$m_model[$name.'_id']
	);
	if ($assort != "") {
		$param['assort'] = $assort;
	}
	$html = '<a href="'.U($name.'/'.$assort.'view', $param).'" target="_blank">[' .$m_model['idcode'].'] '.$m_model['name'] . '</a>&nbsp';;;
	return $html;
}

function staff_show_html($staff) {
	if (!is_array($staff)) {
		$staff = D("StaffView")->where("staff_id=".$staff)->find();
	}
	$html = '<span><a href="'.U('staff/view', 'id='.$staff['staff_id']).'" target="_blank">[' .$staff['idcode'].'] '.$staff['name'] . '</span>&nbsp;';;
	return $html;
}

function staff_role_show_html($role_id) {
	$staff = D("StaffView")->where("user.role_id=".$role_id)->find();
	if (!$staff)return "";
	$html = '<span><a href="'.U('staff/view', 'id='.$staff['staff_id']).'" target="_blank">[' .$staff['idcode'].'] '.$staff['name'] . '</span>&nbsp;';;
	return $html;
}

function department_select($field, $val = null) {
    $department_select = '<select name="'.$field.'" id="'.$field.'"  placeholder="请选择部门"><option value="">请选择部门</option>';
    $where['league_id'] = session('league_id');
    $department_list = M('role_department')->where($where)->cache(true)->select();
    foreach($department_list as $k=>$v) {
        $department_select.= '<option value="'.$v['department_id'].'"';
        if ($val == $v['department_id']) {
            $department_select.= ' selected ';
        }
        $department_select.= '>'.$v['name'].'</option>';
    }
    $department_select.= '</select>';
    return $department_select;
}


function payway_list() {
    $field = M('Fields')->field("field_id, setting")->cache(true)->where(array('field_id'=>"352"))->find();
    $setting_str = '$setting=' . $field['setting'] . ';';
    eval($setting_str);
    return $setting['data'];
}


function proudct_category_select($field, $val = null, $ao = false, $inc = null) {
	$str = $ao ?'<option value="">'.$ao.'</option>':'';
	$where = array("enable"=>1);
	if ($inc) {
		$where['category_id'] = array("in", $inc);
	}
    $where['league_id'] = session('league_id');

    foreach (M('product_category')->field("category_id, serve_modality, name")->cache(true)->where($where)->select() as $v2) {
		$checked = '';
		if ($v2['category_id'] == $val) {
			$checked = 'selected="selected"';
		}
		$str .= "<option $checked value=" . $v2['category_id'] . " serve_modality=".$v2['serve_modality'].">" . $v2['name'] . "</option>";

	}
	return '<select id="' . $field . '" name="' . $field . '" style="width:auto">' . $str . '</select>';
}

function branch_html($val) {
	if ($val != 0) {
		$branch = M('branch')->cache(true)->field("name, branch_id")->where('branch_id = %d ', $val)->find();
		if ($branch) {
			return  '<span><a href="'.U('branch/view', 'id='.$val).'">' . $branch['name'] . '</a></span>';
		}
	}
	return  '<span>公司总部</span>';
}

function branch_html_by_role($role_id) {
	$branch = D('Manage/BranchCategoryView')->where('branch_category.role_id = %d ', $role_id)->find();
	if ($branch) {
		return  '<span><a href="'.U('branch/view', 'id='.$branch['branch_id']).'">' . $branch['name'] . '</a></span>';
	}
	return  '<span>公司总部</span>';
}

function branch_show($val) {
	if ($val != 0) {
		$branch = M('branch')->field("name, branch_id")->cache(true)->where('branch_id = %d ', $val)->find();
		if ($branch) {
			return $branch['name'];
		}
	}
	return  '公司总部';
}

function branch_select($branch_field, $val = null, $classn = "", $showall = false, $showzb = true, $excet_branch=null, $where = array(), $branch_field_id = null, $league=null) {
    $branch_select = '<select ui-jq="chosen" class="'.$classn.'" name="'.$branch_field.'" id="'.($branch_field_id?$branch_field_id:$branch_field).'"  placeholder="请选择门店" data-placeholder="请选择门店">';
	if ($showall) {
		$branch_select.= '<option value="" '.($val==""?"selected":"").'>全部门店</option>';
	}
    if ($showzb) {
       // $branch_select.= '<option value="0"'.($val=="0"?"selected":"").'>公司总部</option>';
    }

    if ($excet_branch) {
        $where['branch_id']=array("not in", is_array($excet_branch)?$excet_branch:array($excet_branch));
    }
    $where['league_id'] = $league?$league:session('league_id');

    $branch = M('branch')->where($where)->field("name, branch_id")->order("order_id DESC")->cache(true)->select();
    foreach($branch as $k=>$v) {
        $branch_select.= '<option value="'.$v['branch_id'].'"';
        if ($val == $v['branch_id']) {
            $branch_select.= ' selected ';
        }
        $branch_select.= '>'.$v['name'].'</option>';
    }
    $branch_select.= '</select>';
    return $branch_select;
}

function list_branch($showall = false, $showzb = true,$excet_branch=null, $where = array(), $league=null) {
    if ($excet_branch) {
        $where['branch_id']=array("not in", is_array($excet_branch)?$excet_branch:array($excet_branch));
    }
    $where['league_id'] = $league?$league:session('league_id');
    $m_branch = M('branch')->where($where)->field("name,name as text, branch_id, branch_id as value")->order("order_id DESC")->cache(true)->select();
    if ($showall) {
        array_unshift($m_branch, array("text"=>"全部门店", "value"=>""));
    }
    if ($showzb) {
        array_unshift($m_branch, array("text"=>"公司总部", "value"=>"0"));
    }
    return $m_branch;
}


function branch_hospital_select_html($branch_field, $val = null, $showall = false, $showzb = true, $excet_branch=null, $branch_field_id = null, $league=null) {
    $branch_select = branch_select($branch_field, $val, "chosen-select", $showall, $showzb, $excet_branch, array("type"=>"医院"), $branch_field_id, $league);
    $branch_select.= '<script>$(".chosen-select").chosen({});</script>';
    return $branch_select;
}

function branch_select_html($branch_field, $val = null, $showall = false, $showzb = true, $excet_branch=null, $league=null) {
    $branch_select = branch_select($branch_field, $val, "chosen-select", $showall, $showzb, $excet_branch, array(), null,  $league);
    $branch_select.= '<script>$(".chosen-select").chosen({});</script>';
    return $branch_select;
}

function branch_select_html_castle($branch_field, $val = null, $showall = false, $showzb = true, $excet_branch=null, $league=null) {
    $branch_select = branch_select($branch_field, $val, "input-sm form-control", $showall, $showzb, $excet_branch, array(), null,  $league);
    return $branch_select;
}

function league_select($league_field, $val = null, $classn = "", $where = array()) {
    $league_select = '<select class="'.$classn.'"  style="width:110px" name="'.$league_field.'" id="'.($league_field).'"  placeholder="请选择加盟商" data-placeholder="请选择加盟商">';
    $league_select.= '<option value="" '.($val==""?"selected":"").'>全部加盟商</option>';

    $league = M('league')->where($where)->field("name, league_id")->cache(true)->select();
    foreach($league as $k=>$v) {
        $league_select.= '<option value="'.$v['league_id'].'"';
        if ($val == $v['league_id']) {
            $league_select.= ' selected ';
        }
        $league_select.= '>'.$v['name'].'</option>';
    }
    $league_select.= '</select>';
    return $league_select;
}

function league_select_html($league_field, $val = null) {
    $league_select = league_select($league_field, $val, "chosen-select");
    $league_select.= '<script>$(".chosen-select").chosen({});</script>';
    return $league_select;
}

function branch_hospital_id() {
    $where = array("type"=>"医院");
    $where['league_id'] = session('league_id');
    return M('branch')->where($where)->cache(true)->getField("branch_id", true);
}

function branch_only_select_html($branch_field, $val = null, $showall = false, $showzb = true, $excet_branch=null) {
	$branch_select = branch_select($branch_field, $val, "chosen-select", $showall, $showzb, $excet_branch, array("type"=>array("in",array("门店","医院"))));
	$branch_select.= '<script>$(".chosen-select").chosen({});</script>';
	return $branch_select;
}

function branch_nohospital_id() {
    $where = array("type"=>array("neq","医院"));
    $where['league_id'] = session('league_id');
    return M('branch')->where($where)->cache(true)->getField("branch_id", true);
}
function dorm_select($dorm_field, $val = null, $branch=null, $classn = "", $showall = false) {
	$dorm_select = '<select class="'.$classn.'"  style="width:100px" name="'.$dorm_field.'" id="'.$dorm_field.'"  placeholder="请选择宿舍" data-placeholder="请选择宿舍">';
	if ($showall) {
		$dorm_select.= '<option value="" '.($val==""?"selected":"").'>全部宿舍</option>';
	}
	if ($branch) {
		$where = array("branch_id"=>$branch);
	} else {
		$where = array();
	}
    $where['league_id'] = session('league_id');

    $dorm = D('Manage/DormView')->cache(true)->where($where)->select();
	foreach($dorm as $k=>$v) {
		$dorm_select.= '<option value="'.$v['dorm_id'].'"';
		if ($val == $v['dorm_id']) {
			$dorm_select.= ' selected ';
		}
		$dorm_select.= '>'.$v['name'].'</option>';
	}
	$dorm_select.= '</select>';
	return $dorm_select;
}


function dorm_select_html($dorm_field, $val = null, $branch=null,$showall = false) {
	$dorm_select = dorm_select($dorm_field, $val, $branch, "chosen-select", $showall);
	$dorm_select.= '<script>$(".chosen-select").chosen({});</script>';
	return $dorm_select;
}

function show_logscope_options_html($options, $showall = true) {
	if (count($options) == 0)
		return "";
	$logoptions_select = '<select class="chosen-select" name="logscope" id="logscope" multiple onchange="on_time_change();" data-placeholder="选择范围">';

	foreach($options as $k=>$v) {
		$logoptions_select.= '<option value="'.$k.'"';
        if ($showall) {
            $logoptions_select.= ' selected';
        }
		$logoptions_select.= '>'.$v.'</option>';
	}
	$logoptions_select.= '</select>';
	return $logoptions_select;
}

function owner_staff_list($m) {
    $where['league_id'] = session('league_id');
    $cc = M($m)->field("owner_role_id")->where($where)->group("owner_role_id")->select(false);
	return D('Manage/StaffView')->where("role_id in (".$cc.")")->select();
}


function cultivate_owner_staff_list( $branch_id, $asids, $ssids) {
	$where = array();
	if (isset($branch_id)) {
		$where["branch_id"]=$branch_id?$branch_id:0;
	}
	if($ssids) {
		$where['settle_state'] = array("in", $ssids);
	}

	if($asids) {
		$where['status_id'] = array("in", $asids);
	} else {
		$where['status_id'] = array("neq", '0');
	}
    $where['league_id'] = session('league_id');

    $cc = M("cultivate")->field("owner_role_id")->group("owner_role_id")->where($where)->select(false);
	return D('Manage/StaffView')->where("role_id in (".$cc.")")->select();
}

function market_owner_staff_list($cat, $branch_id, $asids, $ssids) {
	$where = array();
	if ($cat) {
		$where["category_id"]=$cat;
	}
	if (isset($branch_id)) {
		$where["branch_id"]=$branch_id?$branch_id:0;
	}
	if($ssids) {
		$where['settle_state'] = array("in", $ssids);
	}

	if($asids) {
		$where['status_id'] = array("in", $asids);
	} else {
		$where['status_id'] = array("neq", '0');
	}
    $where['league_id'] = session('league_id');

    $cc = M("market")->field("owner_role_id")->group("owner_role_id")->where($where)->select(false);
	return D('Manage/StaffRoleView')->field("role_id,name")->where("role_id in (".$cc.")")->select();
}

function product_owner_staff_list($cat, $branch_id) {
	$where = array();
	if ($cat) {
        $where['_string'] = "(FIND_IN_SET('".$cat."',category_id) )";
	}
	if (isset($branch_id)) {
		$where["branch_id"]=$branch_id?$branch_id:0;
	}
    $where['league_id'] = session('league_id');

    $cc = M("product")->field("owner_role_id")->group("owner_role_id")->where($where)->select(false);
	return D('Manage/StaffView')->where("role_id in (".$cc.")")->select();
}


function product_queue_staff_list($cat) {
	$where = array();
	if ($cat) {
		$where['_string'] = "(FIND_IN_SET('".$cat."',category_id) )";
	}
    $where['league_id'] = session('league_id');
    $cc = M("product")->field("queue_role_id")->group("queue_role_id")->where($where)->select(false);
	return D('Manage/StaffView')->where("role_id in (".$cc.")")->select();
}

function customer_owner_staff_list($branch_id) {
    $where = array();
    if (isset($branch_id)) {
        $where["branch_id"]=$branch_id?$branch_id:0;
    }
    $where['league_id'] = session('league_id');

    $cc = M("customer")->field("owner_role_id")->group("owner_role_id")->where($where)->select(false);
    return D('Manage/StaffView')->where("role_id in (".$cc.")")->select();
}

function staff_select($staff_field, $val = null, $classn = "", $showall = false) {
	$staff_select = '<select class="'.$classn.'"  style="width:auto" name="'.$staff_field.'" id="'.$staff_field.'"  placeholder="请选择门店" data-placeholder="请选择门店">';
    $where['league_id'] = session('league_id');

    foreach(D('Manage/StaffView')->where($where)->select() as $k=>$v) {
		$staff_select.= '<option value="'.$v['role_id'].'"';
		if ($val == $v['role_id']) {
			$staff_select.= ' selected ';
		}
		$staff_select.= '>'.$v['name'].'</option>';
	}
	$staff_select.= '</select>';
	return $staff_select;
}

function staff_select_html($staff_field, $val = null, $showall = false) {
	$staff_select = staff_select($staff_field, $val, "staff_chosen-select", $showall);
	$staff_select.= '<script>$(".staff_chosen-select").chosen({});</script>';
	return $staff_select;
}

function user_state_list() {
	$field = M('Fields')->cache(true)->where(array('field_id'=>"720"))->find();
	$setting_str = '$setting=' . $field['setting'] . ';';
	eval($setting_str);
	return $setting['data'];
}

function product_insurance_show($product_id) {
	$where = array(
		"category"=>8,
		"product.product_id"=>$product_id,
	);
	$where['_string'] = " trade.state!='已撤销' and UNIX_TIMESTAMP() > begin_date and ( UNIX_TIMESTAMP()<end_date or end_date =0 ) ";
	return (D("ProductTradeView")->where($where)->count() >= 1 ? "是" : "否");
}

function product_category_show($value) {

    $str = "";
    $data_arr = explode(chr(10), $value);
    foreach (M('product_category')->cache(true)->where(array('league_id'=>session('league_id'), 'enable'=>1))->select() as $v2) {
        if (in_array($v2['category_id'], $data_arr)) {
            $str .= '<span>' . $v2['name'] . '</span>';
        }
    }
    return $str;
}

function product_skill_show($value) {
    $skillarr =array();
    foreach(json_decode($value) as $sv) {
        $skillarr[] = $sv;
    }
    return implode(",", $skillarr);
}

function category_config_html($val) {
    $html ="<a href='javascript:void(0);' onclick='on_change_category_config();'>修改</a>&nbsp;&nbsp;<br/>";
    $attr = unserialize($val);
    if ($attr) {
        $html .="中介费比例: <br/>&nbsp;&nbsp;&nbsp;&nbsp;";
        foreach ($attr as $v2) {
            if ($v2['agency_scale']) {
                $html .= $v2['name'].": ".$v2['agency_scale'].", ";
            }
        }

		$html .="<br/>签约中介费比例: <br/>&nbsp;&nbsp;&nbsp;&nbsp;";
		foreach ($attr as $v2) {
			if ($v2['sign_agency_scale']) {
				$html .= $v2['name'].": ".$v2['sign_agency_scale'].", ";
			}
		}

        $html .="<br/>促单费系数: <br/>&nbsp;&nbsp;&nbsp;&nbsp;";
        foreach ($attr as $v2) {
            if ($v2['urge_agency_scale']) {
                $html .= $v2['name'].": ".$v2['urge_agency_scale'].", ";
            }
        }
    }
    return $html;
}

function field_list_html($type="add",$module="",$d_module=array(),$field_group_id="", $field="", $assort="basic", $excfield = null, $inadd = 1){
    if (!$field_group_id && ($assort == "basic" || $assort == "all")) {
        $fields_group_list['0'] = array('field_group_id'=>'0','name'=>'基本信息','operating'=>'1');
    }
    $where = array('model'=>$module);

    if ($field_group_id) {
        if (!is_array($field_group_id)) {
            $field_group_id = array($field_group_id);
        }
        $where['field_group_id'] = array("in", $field_group_id);
    } else if ($assort != "all"){
        $where['assort'] = $assort;
    }

    $fields_group = M('FieldsGroup')->cache(true)->where($where)->order('order_id ASC')->select();
    foreach($fields_group as $group) {
        $fields_group_list[$group['field_group_id']] = $group;
    }

    foreach($fields_group_list as $group_id=>$group) {
        if ($field != "") {
            $field_list = M('Fields')->cache(true)->where(array('model'=>$module,'field'=>$field))->order('order_id')->select();
        } else {
            $where = array('model'=>$module,'field_group_id'=>$group_id);
            if ($type == "add") {
                $where['in_add'] = 1;
            } else {
                $where['in_edit'] = 1;
            }
            if ($excfield) {
                if (!is_array($excfield)) {
                    $excfield = array($excfield);
                }
                $where['field'] = array("not in", $excfield);
            }
            $field_list = M('Fields')->cache(true)->where($where)->order('order_id')->select();
        }

        foreach ($field_list as $k => $v) {
            if (trim($v['input_tips'])) {
                $input_tips = ' &nbsp; <span id="'.$v['field'].'_input_tips" style="color:#005580;">(注:' . $v['input_tips'] . ')</span>';
            } else {
                $input_tips = '';
            }
            if ('add' == $type) {
                $value = $d_module[$v['field']] ? $d_module[$v['field']] : $v['default_value'];
            } elseif ('edit' == $type && !empty($d_module)) {
                $value = $d_module[$v['field']] ? $d_module[$v['field']] : '';
            }

            if ($v['field'] == 'customer_id') {
                if($d_module['customer_id']){
                    $customer_id = intval($d_module['customer_id']);
                }else{
                    $customer_id = intval($_GET['customer_id']);
                }

                if($customer_id){
                    $customer = M('customer')->where('customer_id = %d', $customer_id)->find();
                }
                if($customer_id){
                    $field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="customer_id" value="'.$customer['customer_id'].'"/><input  type="text" name="customer_name" id="customer_name" value="'.$customer['name'].'"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                }else{
                    $field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="customer_id"/><input  type="text" name="customer_name" id="customer_name"> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                }
            } elseif ($v['field'] == 'product_id') {
                if($d_module['product_id']){
                    $product_id = intval($d_module['product_id']);
                }else{
                    $product_id = intval($_GET['product_id']);
                }

                if($product_id){
                    $product = M('product')->where('product_id = %d', $product_id)->find();
                }

                if($product_id){
                    $field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="product_id" value="'.$product['product_id'].'"/><input  type="text" name="product_name" id="product_name" value="'.$product['name'].'"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                }else{
                    $field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="product_id"/><input  type="text" name="product_name" id="product_name"> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                }
            } elseif ($v['field'] == 'serve_id') {
                if($d_module['serve_id']){
                    $serve_id = intval($d_module['serve_id']);
                }else{
                    $serve_id = intval($_GET['serve_id']);
                }

                if($serve_id){
                    $serve = M('serve')->where('serve_id = %d', $serve_id)->find();
                }

                if($serve_id){
                    $field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="serve_id" value="'.$serve['serve_id'].'"/><input  type="text" name="serve_name" id="serve_name" value="'.$serve['name'].'"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                }else{
                    $field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="serve_id"/><input  type="text" name="serve_name" id="serve_name"> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                }
            }  elseif ($v['field'] == 'dorm_id') {
				if($d_module['dorm_id']){
					$dorm_id = intval($d_module['dorm_id']);
				}else{
					$dorm_id = intval($_GET['dorm_id']);
				}

				if($dorm_id){
					$dorm = M('dorm')->where('dorm_id = %d', $dorm_id)->find();
				}

				if($dorm_id){
					$field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="dorm_id" value="'.$dorm['dorm_id'].'"/><input  type="text" name="dorm_name" id="dorm_name" value="'.$dorm['name'].'"/>  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
				}else{
					$field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="dorm_id"/><input  type="text" name="dorm_name" id="dorm_name">  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
				}

			}
			elseif ($module == 'cultivate' && $v['field'] == 'model_id') {
				if($d_module['model_id']){
					$model_id = $d_module['model_id'];
				}else{
					$model_id = $_GET['model_id'];
				}
				if($d_module['model']){
					$model = $d_module['model'];
				}else{
					$model = $_GET['model'];
				}

				if($model_id && $model){
					$m_cultivate_model = M($model)->where($model.'_id = %d', $model_id)->find();
				}

				if($m_cultivate_model){
					$field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="model_id" value="'.$model_id.'"/><input  type="text" id="cultivate_model_name" value="'.$m_cultivate_model['name'].'"/>  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>'. $input_tips;
				}else{
					$field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="model_id" value="'.$model_id.'"/><input  type="text" id="cultivate_model_name">  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
				}

			}
            elseif ($v['field'] == 'currier_id') {
                if($d_module['currier_id']){
                    $currier_id = intval($d_module['currier_id']);
                }else{
                    $currier_id = intval($_GET['currier_id']);
                }

                if($currier_id){
                    $currier = M('currier')->where('currier_id = %d', $currier_id)->find();
                }

                if($currier){
                    $field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="currier_id" value="'.$currier['currier_id'].'"/><input  type="text" name="currier_name" id="currier_name" value="'.$currier['name'].'"/>  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                }else{
                    $field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="currier_id"/><input  type="text" name="currier_name" id="currier_name">  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>'. $input_tips;
                }

            }
            elseif ($v['field'] == 'origin') {
				$origin_field_id = in_array($module, array("market_channel"))?9:$v['field_id'];
                $origin_field = M('Fields')->cache(true)->where(array('field_id'=>$origin_field_id))->find();
                $setting_str = '$setting=' . $origin_field['setting'] . ';';
                eval($setting_str);
                $field_list[$k]['setting'] = $setting;
				$str = '';
				$str .= "<option value=''>--" . L('PLEASE CHOOSE') . "--</option>";
				foreach ($setting['data'] as $v2) {
					$str .= "<option value='$v2'";
					$str .= $d_module[$v['field']] == $v2 ? ' selected="selected"' : '';
					$str .= ">$v2</option>";
				}
				$field_list[$k]['html'] .= '<select class="weui_select" id="' . $v['field'] . '" name="' . $v['field'] . '">' . $str . '</select> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
				$field_list[$k]['html'] .='<input type="hidden" name="channel_model" id="channel_model" value="'.$d_module['channel_model'].'"/>';
				$field_list[$k]['html'] .='<input type="hidden" name="channel_model_id" id="channel_model_id" value="'.$d_module['channel_model_id'].'"/>';
            }
            elseif ($v['field'] == 'blank_name') {
                $field_list[$k]['html'] = blank_select($d_module['blank_name']) . ' &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
            } elseif (in_array($v['field'], array(
							'authority',
							'astrict',
							'agency_gather',
							'meddle_state',
							'sellingfeepayornot',
							'fujianifornot',
							'continueornot', 'currier_crad')
					) || in_array($v['field_id'], array('208','298','565'))) {
                $setting_str = '$setting=' . $v['setting'] . ';';
                eval($setting_str);
                $field_list[$k]['setting'] = $setting;
                $str = '';
                foreach ($setting['data'] as $v2) {
                    $str .= "<option value='$v2'";
                    $str .= $d_module[$v['field']] == $v2 ? ' selected="selected"' : '';
                    $str .= ">$v2</option>";
                }
                $field_list[$k]['html'] = '<select id="' . $v['field'] . '" name="' . $v['field'] . '">' . $str . '</select> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
            }
            elseif ($v['field'] == 'branch_id') {
                $field_list[$k]['html'] = branch_select($v['field'], $value) . ' &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;

            }
            elseif ($module == "staff" && $v['field'] == 'department_id') {
                $field_list[$k]['html'] = department_select($v['field'], $value) . ' &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
            }
            elseif ($module == "commiss" && $v['field'] == 'owner_role_id') {
                $role_id = $value ? $value :  session('role_id');
                $role = D('Manage/RoleView')->cache(true)->where('role.role_id = %d', $role_id)->find();
                $role_name = ($role ? $role['user_name'] : session('name'));
                $html ='<input type="hidden" id="'.$v['field'].'" name="'.$v['field'].'" value="' . $role_id . '"/>';
                $html .= '<input type="text" id="'.$v['field'].'_name" rel="'.$v['field'].'" class="role_name" value="' . $role_name . '"/>';
                $clean_role = ' <a href="javascript:void(0);" onclick="clean_owner_role();" class="clean_owner_role" rel="'.$v['field'].'">清除责任人</a>';
                $field_list[$k]['html'] =$html . $clean_role.$input_tips;
            }
            elseif ($module == "staff" && $v['field'] == 'position_id') {
                $field_list[$k]['html'] = position_select($v['field'], $value, $d_module['department_id']) . ' &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;

            }
            elseif (in_array($v['field'],array(
                'sales_cost',
                'introducerfee',
            ))) {
                $html = get_cost_html($module, $d_module, $v['field'], $value, false);
                if ($html) {
                    $field_list[$k]['html'] = $html;
                } else {
                    $field_list[$k]['html'] = '<input type="text"  id="' . $v['field'] . '" name="' . $v['field'] . '" maxlength="' . $v['maxlength'] . '" value="' . $value . '"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                }
            }
            else {
                switch ($v['form_type']) {
                    case 'textarea' :
                        $field_list[$k]['html'] = '<textarea style="width:80%" rows="6" class="span6" id="' . $v['field'] . '" name="' . $v['field'] . '" >' . $value . '</textarea> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        break;
                    case 'bc_box' :
                        $str = '';
                        $i = '';
                        $data_arr = explode(chr(10), $value);
                        foreach (M('product_category')->cache(true)->where(array('league_id'=>session('league_id'), 'enable'=>1))->select() as $v2) {
                            $str .= " &nbsp; <input type='checkbox' name='" . $v['field'] . "[]' id='" . $v['field'] . $i . "' value='" . $v2['category_id'] . "'";
                            if (in_array($v2['category_id'], $data_arr)) {
                                $str .= ' checked="checked"';
                            }
                            $str .= '/>&nbsp;' . $v2['name'];
                            $i++;
                        }
                        $field_list[$k]['html'] = $str . ' <span id="' . $v['field'] . 'Tip" style="color:red;"></span>&nbsp; ' . $input_tips;
                        break;
                    case 'se_box' :
					case 'order_classify':
					case 'currier_model' :
                        $setting_str = '$setting=' . $v['setting'] . ';';
                        eval($setting_str);
                        $field_list[$k]['setting'] = $setting;
                        if ($setting['type'] == 'select') {
                            $str = "<option value=''>--" . L('PLEASE CHOOSE') . "--</option>";
                            foreach ($setting['data'] as $k2=>$v2) {
                                $str .= "<option value='$k2'";
                                $str .= $d_module[$v['field']] == $k2 ? ' selected="selected"' : '';
                                $str .= ">$v2</option>";
                            }
                            $field_list[$k]['html'] = '<select id="' . $v['field'] . '" name="' . $v['field'] . '">' . $str . '</select> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        }
                        break;

					case 'cultivate_cert_state_box':
					case 'cultivate_examine_state_box':
                    case 'cultivate_state_box':
                    case 'w_box':
                        $setting_str = '$setting=' . $v['setting'] . ';';
						eval($setting_str);
						$field_list[$k]['setting'] = $setting;
						if ($setting['type'] == 'select') {
							$str = "<option value=''>--自动状态--</option>";
							foreach ($setting['data'] as $k2=>$v2) {
								$str .= "<option value='$k2'";
								$str .= $d_module[$v['field']] == $k2 ? ' selected="selected"' : '';
								$str .= ">$v2</option>";
							}
							$field_list[$k]['html'] = '<select id="' . $v['field'] . '" name="' . $v['field'] . '">' . $str . '</select> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
						}
						break;

                    case 'sv_cate' :
                        $cat = $d_module[$v['field']];
                        $selecthtml = make_serve_category_select("serve", $cat ? $cat : 0, session("league_id"));
                        $field_list[$k]['html'] .= '<span id="sv_cate">'.$selecthtml.'</span><input type="hidden" name="' . $v['field'] . '" id="'.$v['field'].'" value="'.$cat.'"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        break;


                    case 'tr_cate' :
                        $cat = $d_module[$v['field']];
                        $selecthtml = make_serve_category_select("currier", $cat ? $cat : 0, session("league_id"));
                        $field_list[$k]['html'] .= '<span id="tr_cate">'.$selecthtml.'</span><input type="hidden" name="' . $v['field'] . '" id="'.$v['field'].'" value="'.$cat.'"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        break;


                    case 'box' :
                        $setting_str = '$setting=' . $v['setting'] . ';';
                        eval($setting_str);
                        $field_list[$k]['setting'] = $setting;
                        if ($setting['type'] == 'select') {
                            $str = '';
                            $str .= "<option value=''>--" . L('PLEASE CHOOSE') . "--</option>";
                            foreach ($setting['data'] as $v2) {
                                $str .= "<option value='$v2'";
                                $str .= $d_module[$v['field']] == $v2 ? ' selected="selected"' : '';
                                $str .= ">$v2</option>";
                            }
                            $field_list[$k]['html'] = '<select class="weui_select" id="' . $v['field'] . '" name="' . $v['field'] . '">' . $str . '</select> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                            break;
                        } elseif ($setting['type'] == 'radio') {
                            $str = '';
                            $i = '';
                            foreach ($setting['data'] as $v2) {
                                $str .= " &nbsp; <input type='radio' name='" . $v['field'] . "' id='" . $v['field'] . $i . "' value='$v2'";
                                $str .= $d_module[$v['field']] == $v2 ? ' checked="checked"' : '';
                                $str .= "/>&nbsp; $v2";
                                $i++;
                            }
                            $field_list[$k]['html'] = $str . '  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>&nbsp; ' . $input_tips;
                            break;
                        } elseif ($setting['type'] == 'checkbox') {
							$i = '';
							$str = "<input style='display:none' type='checkbox' name='" . $v['field'] . "[]' id='" . $v['field']  . "_span' value=''  checked='checked'/>";
                            foreach ($setting['data'] as $v2) {
                                $str .= " &nbsp; <input type='checkbox' name='" . $v['field'] . "[]' id='" . $v['field'] . $i . "' value='$v2'";
                                $coval = $d_module[$v['field']];
								if ($v['field'] == "apply_scope") {
									$coxlist = explode(",",$coval);
								} else {
									$coxlist = explode(chr(10), $coval);
								}
                                if ($coval == $v2 || in_array($v2, $coxlist)) {
                                    $str .= ' checked="checked"';
                                }
                                $str .= '/>&nbsp;' . $v2;
                                $i++;
                            }
                            $field_list[$k]['html'] = $str . ' <span id="' . $v['field'] . 'Tip" style="color:red;"></span>&nbsp; ' . $input_tips;
                            break;
                        }
                        break;
                    case 'editor' :
                        $upload_url = U('file/editor');
                        $fileManagerJson = U('file/manager');
                        $field_list[$k]['html'] = '<script type="text/javascript">
                        var editor;
                        KindEditor.ready(function(K) {
                            editor = K.create(\'textarea[name="' . $v['field'] . '"]\', {
                                uploadJson:"' . $upload_url . '",
                                allowFileManager : true,
                                loadStyleMode : false,
                                fileManagerJson: "' . $fileManagerJson . '"
                            });
                        });
                        </script>
                        <textarea name="' . $v['field'] . '" id="' . $v['field'] . '" style="width: 800px; height: 350px;">' . $value . '</textarea> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        break;
                    case 'datetime' :
                        if ($v['is_showtime'] == 1) {
                            $time_accuracy = 'yyyy-MM-dd HH:mm';
                        } else {
                            $time_accuracy = 'yyyy-MM-dd';
                        }
                        $field_list[$k]['html'] = '<input style="background:white;cursor:pointer;" readonly="readonly" accuracy="'.($v['is_showtime'] == 1?"time":"date").'"  onFocus="if (typeof(on_'. $v['field'] .'_focus) !=\'undefined\')on_'. $v['field'] .'_focus(\''.$time_accuracy.'\',this);else show_wdate_picker(\'' . $time_accuracy . '\',this);" name="' . $v['field'] . '" class="Wdate" id="' . $v['field'] . '" type="text" value="' . pregtime($value, $v['is_showtime']) . '"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        break;
                    case 'number' :
                        $field_list[$k]['html'] = '<input type="text"  id="' . $v['field'] . '" name="' . $v['field'] . '" maxlength="' . $v['maxlength'] . '" value="' . $value . '"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        break;
                    case 'floatnumber' :
                        $field_list[$k]['html'] = '<input type="text" id="' . $v['field'] . '" name="' . $v['field'] . '" value="' . number_format($value, 2, '.', '') . '"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        break;
                    case 'pic': {
                        $html = '<div id="pic_'.$v['field'].'_container">';
                        if ('add' != $type) {
                            $m_module_images = M($module . 'Images');
                            $where = array(
                                $module . "_id" => $d_module[$module . "_id"],
                                $module . "_field" => $v['field'],
                                "is_main" => 0
                            );
                            $piclist = $m_module_images->where($where)->order('listorder asc')->select();
                            foreach ($piclist as $pick => $picv) {
                                $html .= '<div class="box-secondary" id="pic-list-' . $picv['images_id'] . '" style="margin:3px 3px">';
                                $html .= '  <a href="' . $picv['path'] . '" target="_self" data-lightbox="group-'.$v['field'].'">';
                                $html .= '      <img src="' . thumb($picv['path'], 117, 117) . '" class="thumbnail productotherlistthumb img-responsive" style="width:117px;height:110px" alt="' . $picv['name'] . '">';
                                $html .=        '<a href="javascript:void(0);" onclick="del_img(' . $picv['images_id'] . ',\''.$v['field'].'\',\''.$module.'\')">';
                                $html .= '            <img class="del_parts" src="./Public/img/delete.gif">';
                                $html .=        '</a>';
								$html .=    '</a>';
                                $html .= '</div>';
                            }
                        }
                        $html .= "</div>";

                        $html .= '<div class="box-secondary"  style="margin:3px 3px" id="pic_'.$v['field'].'_add_btn">';
                        $html .= '    <div class="fileinput-button" style="border-color:white;height:110px;width:130px;background-image:url(./Public/img/add.png);background-repeat:no-repeat">';
                        $html .= '        <input first="0" real="'.$v['field'].'"  class="pic-fileupload" type="file" name="pic_'.$v['field'].'[]" style="height: 70px;padding-top:40px">';
                        $html .= '        <div class="prediv thumbnail productotherlistthumb img-responsive">';
                        $html .= '        </div>';
                        $html .= '        <a href="javascript:void(0);" onclick="del_img(this,\''.$v['field'].'\',\''.$module.'\');" style="display:none">';
                        $html .= '            <img class="del_parts" src="./Public/img/delete.gif">';
                        $html .= '        </a>';
						$html .= '        <a href="javascript:void(0);" onclick="photograph(this);">';
						$html .= '            <img class="del_parts" style="left:5px" src="./Public/img/xiangji.jpg">';
						$html .= '        </a>';
						$html .= '    	  <div class="photograph_div"></div>';
                        $html .= '    </div>';
                        $html .= '</div>';
                        $field_list[$k]['html'] = $html;
                        break;
                    }
                    case 'file': {
                        $html = "";
                        if ('add' != $type) {
                            $m_file = M("file");
                            $where = array(
                                "module" => $module,
                                "field" => $v['field'],
                                "module_id" => $d_module[$module . "_id"]
                            );

                            $html = '<table class="table"><tbody><tr><td>&nbsp;</td><td>文件名</td><td>添加者</td><td>添加时间</td></tr>';
                            $filelist = $m_file->where($where)->select();
                            foreach ($filelist as $filek => $filev) {
                                $role = D('RoleView')->where('role.role_id = %d', $filev['role_id'])->find();
                                $html .= '<tr id="file-list-' . $filev['file_id'] . '"><td class="tdleft">';
                                $html .= '<a rel="' . $filev['file_id'] . '" href="javascript:void(0);" class="btn_del_file">删除</a>';
                                $html .= '</td><td>';
                                $html .= '<a target="_blank" href="' . $filev["file_path"] . '">' . $filev['name'] . '</a>';
                                $html .= '</td><td>';
                                $html .= $role['user_name'];
                                $html .= '</td><td>';
                                $html .= date("Y-m-d g:i:s a", $filev["create_date"]);
                                $html .= '</td></tr>';
                            }
                            $html .= '</tbody></table>';
                        }
                        $html .= '<div class="' . $field_list[$k]['field'] . '"> <a class="btn btn-primary pull-right btn_add_file" field_name="' . $field_list[$k]['field'] . '" href="javascript:void(0);"> <i class="icon-plus"></i>&nbsp;&nbsp;新增</a> </div>';
                        $field_list[$k]['html'] = $html;
                        break;
                    }
                    case 'video': {
                        $html = "";
                        if ('add' != $type) {
                            $m_product_video = M($module . 'Video');
                            $where = array(
                                $module . "_id" => $d_module[$module . "_id"],
                                $module . "_field" => $v['field'],
                                "is_main" => 0
                            );

                            $html = '<table class="table"><tbody><tr><td>&nbsp;</td><td>文件名</td><td>添加者</td><td>添加时间</td></tr>';
                            $videolist = $m_product_video->where($where)->order('listorder asc')->select();
                            foreach ($videolist as $videok => $videov) {
                                $role = D('RoleView')->where('role.role_id = %d', $videov['role_id'])->find();
                                $html .= '<tr id="video-list-' . $videov['video_id'] . '"><td class="tdleft">';
                                $html .= '<a rel="' . $videov['video_id'] . '" href="javascript:void(0);" class="btn_del_video">删除</a>';
                                $html .= '</td><td>';
                                $html .= '<a target="_blank" href="' . U($module . "/viewvideo", "path=" . $videov["file_path"]) . '">' . $videov['name'] . '</a>';
                                $html .= '</td><td>';
                                $html .= $role['user_name'];
                                $html .= '</td><td>';
                                $html .= date("Y-m-d g:i:s a", $videov["create_date"]);
                                $html .= '</td></tr>';
                            }
                            $html .= '</tbody></table>';
                        }
                        $html .= '<div class="' . $field_list[$k]['field'] . '"> <a class="btn btn-primary pull-right btn_add_video" field_name="' . $field_list[$k]['field'] . '" href="javascript:void(0);"> <i class="icon-plus"></i>&nbsp;&nbsp;新增</a> </div>';
                        $field_list[$k]['html'] = $html;
                        break;
                    }
                    case 'address':
                        $field_list[$k]['html'] = format_address_edit_field($v['field'], $value, $type) . $input_tips;;
                        break;
                    case 'p_box':
						$html = proudct_category_select($v['field'], $value, ('add' == $type?"请选择":false));
                        $field_list[$k]['html'] = $html.' &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        break;
					case "p_box_level":
						$str = '';
						foreach(plevel() as $k2=>$v2) {
							$checked = '';
							if ($k2 == $value) {
								$checked = 'selected="selected"';
							}
							$str .= "<option $checked value=" . $k2 . ">" . $k2 . "</option>";

						}
						$field_list[$k]['html'] = '<select id="' . $v['field'] . '" name="' . $v['field'] . '">' . $str . '</select> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;

						break;

                    case 'a_box':
                        $t = trim($_REQUEST['t']);
                        $where = array(
                            'module_id' => $t,
                            'mold' => $_REQUEST['dire'],
                            'operator'=>array("neq", '2'),
							'is_show'=>1,
							'type_id'=>array("not in", ious_account_type())
                        );
                        if (!vali_permission("account", "add", $t."_ea")) {
                            $where['_string'] = "(mold=-1 OR mold=-2 OR mold=-3) OR ((inflow_model='' OR inflow_model='flow' OR inflow_model='ious') AND (mold=1 OR mold=2 OR mold=3) AND (inflow_model!='cash'))";
                        } else {
                            $where['mold'] = $_REQUEST['dire'];
							$where['inflow_model_type_id'] = array("neq", -2);
						}

                        $account_type = M('AccountType')->cache(true)->where($where)->order('order_id')->select();
                        $str = '<option value="">请选择...</option>';
                        foreach ($account_type as $v2) {
                            $checked = '';
                            if ($v2['type_id'] == $value) {
                                $checked = 'selected="selected"';
                            }
                            $str .= "<option $checked value='" . $v2['type_id'] . "' inflow='".$v2['inflow_model']."' related='";
                            if ($v2['related_model']) {
                                $str .= $v2['related_model'];
                            }
                            $str .= "'>" . $v2['name'] . "</option>";
                        }
						$html = '<select id="' . $v['field'] . '" name="' . $v['field'] . '">' . $str . '</select> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;;
						//$html.='<script>$("#'.$v['field'].'").chosen({});</script>';
						$field_list[$k]['html'] =$html;
                        break;
					case 'channnel_box':
						if($value){
							$channel = M('channel')->cache(true)->where('channel_id = %d', $value)->find();
						}
						if($channel){
							$field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="'.$v['field'].'" value="'.$channel['channel_id'].'"/><input  type="text" name="channel_name" id="channel_name" value="'.$channel['name'].'"/>' . $input_tips;
						}else{
							$field_list[$k]['html'] = '<input type="hidden" name="'.$v['field'].'" id="'.$v['field'].'"/><input  type="text" name="channel_name" id="channel_name">' . $input_tips;
						}
						break;

					case 'channel_role_model_box':
						$field_list[$k]['html'] = channel_role_model_box_html($module, $v['field'], $value) . $input_tips;
						break;

					case 'channel_role_id_box':
						$field_list[$k]['html'] = channel_role_id_box_html($module, $d_module, $v['field'], $value) . $input_tips;
						break;


                    default:
                        if ($v['form_type'] == "user") {
							if (($module == "staff" && $v['field'] == 'leader') || ($module == "cultivate" && $v['field'] == 'model_owner_role_id')) {
								$role_id = $value ? $value :  "";
							} else {
								$role_id = $value ? $value :  session('role_id');
							}
							$role = D('Manage/RoleView')->cache(true)->where('role.role_id = %d', $role_id)->find();
							if (($module == "staff" && $v['field'] == 'leader') || ($module == "cultivate" && $v['field'] == 'model_owner_role_id')) {
								$role_name = ($role ? $role['user_name'] : "");
							} else {
								$role_name = ($role ? $role['user_name'] : session('name'));
							}

                            if ($v['field'] == "shopkeeper_role_id") {
                                $html = '<span><a class="role_info" href="javascript:void(0);" rel="'.$value.'">' . $role_name . '</a></span>';
                            } else {
                                $html ='<input type="hidden" id="'.$v['field'].'" name="'.$v['field'].'" value="' . $role_id . '"/>';
                                $html .= '<input type="text" id="'.$v['field'].'_name" rel="'.$v['field'].'" class="role_name" value="' . $role_name . '"/>';
                            }

                            $field_list[$k]['html'] =$html . $input_tips;
                        } else if ($v['field'] == 'create_time' || $v['field'] == 'update_time') {
                        } else {
                            $field_list[$k]['html'] = '<input  class="weui_input"  type="text" id="' . $v['field'] . '" name="' . $v['field'] . '" maxlength="' . $v['maxlength'] . '" value="' . $value . '"/> &nbsp;  <span id="' . $v['field'] . 'Tip" style="color:red;"></span>' . $input_tips;
                        }
                        break;
                }
            }
        }
        $fields_group_list[$group_id]['fields'] = $field_list;
    }

    foreach($fields_group_list as $k=>$gvo) {
        foreach($gvo['fields'] as $kvo=>$vo) {
            if ($vo['operating'] == '4'){
                unset($fields_group_list[$k]['fields'][$kvo]);
            }
        }
    }
	return $fields_group_list;
}


function fmtgf($f) {
    return $f."[value]";
}

function channel_role_model_box_html($module, $field, $value) {
	$user_permission = true;
	if (!session('?admin')) {
		$where2 = array(
			"role.role_id"=>session("role_id"),
			"permission.description"=>"channel_permission",
		);
		if ($value) {
			$where2['url'] = "channel_permission/channelid/".$value;
		}
		$user_permission = D("ChannelPermissionView")->where($where2)->count() > 0;
	}

	if ($user_permission) {
		$where = array(
			"parentid"=>""
		);
		if (!session('?admin')) {
			$where2 = array(
					"role.role_id"=>session("role_id"),
					"permission.description"=>"channel_permission",
			);
			$sub_query = D("ChannelPermissionView")->cache(true)->field("substring(url , 30) as channel_id")->where($where2)->select(false);
			$where['_string'] = "channel_id in ".$sub_query;
		}

		if ($module == "product" || $module == "customer") {
			if ($where['_string']) {
				$where['_string'].=" and ";
			}
			if ($module == "product") {
				$where['_string'] .= "FIND_IN_SET('雇员', apply_scope)";
			} else {
				$where['_string'] .= "FIND_IN_SET('客户', apply_scope)";
			}
		}
		$where['channel_id']=array("neq", 0);
        $where['league_id'] = session('league_id');

		$m_channel = M('channel')->cache(true)->where($where)->select();
		$str = '<option value="">未定义</option>';
		foreach ($m_channel as $v2) {
			$checked = '';
			if ($v2['channel_id'] == $value) {
				$checked .= ' selected="selected" ';
			}
			if ($v2['status'] == "禁用") {
				$checked .= ' disabled="disabled" ';
			}
			$child_channel_ids = '';
			if ($v2['child_channel_ids']) {
				$child_channel_ids = 'child_channel_ids="'.$v2['child_channel_ids'].'"';
			}
			$str .= "<option model='".$v2['model']."' $child_channel_ids $checked value='" . $v2['channel_id'] . "'>" . $v2['name'] . "</option>";
		}
		$html = '<select id="' . $field . '" name="' .$field . '">' . $str . '</select> &nbsp;  <span id="' . $field . 'Tip" style="color:red;"></span>';;
		//if ($value) {
			$html.='<script>$("#'.$field.'").chosen({});</script>';
		//}
		return $html;
	} else {
		$where = array(
			"channel_id"=>$value
		);
		$m_channel = M('channel')->cache(true)->where($where)->find();
		if ($m_channel) {
			return '<input   name="'.$field.'" type="hidden" value="'.$value.'"/>'.'<input class="weui_input" type="text" disabled="disabled" value="'.$m_channel['name'].'"/>';
		}
		return '<input class="weui_input" type="text" disabled="disabled" value=""/>';
	}
}


function get_channel_role_model($module) {
    $user_permission = true;
    if (!session('?admin')) {
        $where2 = array(
            "role.role_id"=>session("role_id"),
            "permission.description"=>"channel_permission",
        );
        $user_permission = D("ChannelPermissionView")->where($where2)->count() > 0;
    }
    if (!$user_permission) {
        return null;
    }

    $where = array(
        "parentid"=>""
    );
    if (!session('?admin')) {
        $where2 = array(
            "role.role_id"=>session("role_id"),
            "permission.description"=>"channel_permission",
        );
        $sub_query = D("ChannelPermissionView")->cache(true)->field("substring(url , 30) as channel_id")->where($where2)->select(false);
        $where['_string'] = "channel_id in ".$sub_query;
    }

    if ($module == "product" || $module == "customer") {
        if ($where['_string']) {
            $where['_string'].=" and ";
        }
        if ($module == "product") {
            $where['_string'] .= "FIND_IN_SET('雇员', apply_scope)";
        } else {
            $where['_string'] .= "FIND_IN_SET('客户', apply_scope)";
        }
    }
    $where['channel_id']=array("neq", 0);
    $where['league_id'] = session('league_id');
    return  M('channel')->cache(true)->field("channel_id,name,channel_id as value, name as text,model,apply_scope")->where($where)->select();
}

function channel_role_id_box_html($module, $d_module, $field, $value) {
	$user_permission = true;
	if (!session('?admin')) {
		$where2 = array(
			"role.role_id"=>session("role_id"),
			"permission.description"=>"channel_permission",
		);
		if ($d_module["channel_role_model"]) {
			$where2['url'] = "channel_permission/channelid/".$d_module["channel_role_model"];
		}
		$user_permission = D("ChannelPermissionView")->where($where2)->count() > 0;
	}

	if ($user_permission) {
		if($value){
			$html = '<input type="hidden" name="'.$field.'" id="'.$field.'" value="'.$value.'"/><input  type="text" name="channel_role_id_name" id="channel_role_id_name" value="'.channel_model_role_show_html($d_module["channel_role_model"], $value).'"/>&nbsp;  <span id="channel_role_id_nameTip" style="color:red;"></span>';
		}else{
			$html = '<input type="hidden" name="'.$field.'" id="'.$field.'"/><input  type="text" name="channel_role_id_name" id="channel_role_id_name">&nbsp;  <span id="channel_role_id_nameTip" style="color:red;"></span>';
		}
	}  else {
		$html = '<input  name="'.$field.'" type="hidden" value="'.$value.'"/>'.'<input  type="text" disabled="disabled" value="'.channel_model_role_show_html($d_module["channel_role_model"], $value).'"/>&nbsp;  <span id="channel_role_id_nameTip" style="color:red;"></span>';
	}
	return $html;
}

function is_email($email)
{
	return strlen($email) > 8 && preg_match("/^[-_+.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+([a-z]{2,4})|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email);
}
function is_phone($phone)
{
	return strlen(trim($phone)) == 11 && preg_match("/^1[3|5|8][0-9]{9}$/i", trim($phone));
}
function pregtime($timestamp, $showtime){
	if($timestamp){
        if ($showtime) {
            $f = 'Y-m-d H:i';
        } else {
            $f = 'Y-m-d';
        }
		return date($f,$timestamp);
	}else{
		return '';
	}
}


function vali_permission($m, $a, $p = null){
	if (NO_AUTHORIZE_CHECK === true)
		return true;
	$allow = $params['allow'];

	if (session('?admin')) {
		return true;
	}
	if (in_array($a, $allow)) {
		return true;
	} else {
		switch ($a) {
			case "listdialog" : $a = 'index'; break;
			case "adddialog" : $a = 'add'; break;
			case "excelimport" : $a = 'add'; break;
			case "excelexport" : $a = 'view'; break;
			case "cares" :  $a = 'index'; break;
			case "caresview" :  $a = 'view'; break;
			case "caresedit" :  $a = 'edit'; break;
			case "caresdelete" :   $a = 'delete'; break;
			case "caresadd" :  $a = 'add'; break;
			case "receive" : $a = 'add'; break;
			case "role_add" : $a = 'add';break;
			case "sendsms" : $a = 'marketing';break;
			case "sendemail" : $a = 'marketing';break;
		}
		$url = strtolower($m).'/'.strtolower($a);
        if ($p) {
            $url .= "/" . trim(strtolower($p));
        }

        $where = array(
            "url"=>$url,
            "position_id"=>session('position_id'),
            "league_id"=>session('league_id'),
        );
		$ask_per = M('permission')->cache(true)->where($where)->find();
		if (is_array($ask_per) && !empty($ask_per)) {
			return true;
		} else {
			return false;
		}
	}
}



/**
 * author : myrom
 * function : 截取字符长度，如果超过字符长度，后面追加...
 * @str : 要截取的字符串  $len : 要截取的长度
 **/
function cutString($str='', $len='15'){
	if(empty($str) || empty($len)) return false;
	$pre_content = strip_tags($str);
	$pre_content_len = mb_strlen($pre_content,'utf-8');
	if($pre_content_len <= $len){
		return $pre_content;
	}else{
		$pre_content = mb_substr($pre_content,0,$len,'utf-8');
		return $pre_content.' . . .';
	}
}

/**
 * author : myron
 * function : 在AuthenticateBehavior中判断是否AJAX请求，如果是AJAx请求且在弹窗页没有权限，直接显示无权限
 **/
function isAjaxRequest() {
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
		if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])){
			return true;
		}
	}
	if(!empty($_POST[C('VAR_AJAX_SUBMIT')]) || !empty($_GET[C('VAR_AJAX_SUBMIT')])){
		// 判断Ajax方式提交
		return true;
	}
	return false;
}

function census_map() {
    $census = array(
        "北方"=>array(
            '北京',
            '甘肃',
            '河北',
            '河南',
            '黑龙江',
            '吉林',
            '辽宁',
            '内蒙古',
            '宁夏',
            '青海',
            '山东',
            '山西',
            '陕西',
            '天津',
            '西藏',
            '新疆',
        ),
        "南方"=>array(
            '广东',
            '广西',
            '云南',
            '贵州',
            '湖北',
            '湖南',
            '江西',
            '浙江',
            '福建',
            '安徽',
            '上海',
            '四川',
            '重庆',
            '港澳台',
            '海南',
            '江苏',
        )
    );
    return $census;
}

function census_retrieve($c) {
    $census = census_map();
    return ($c != "北方" && $c != "南方") ? array_merge($census["北方"], $census["南方"]) : $census[$c];
}

function parseDateTime($string, $timezone=null) {
    $date = new DateTime(
        $string,
        $timezone ? $timezone : new DateTimeZone('UTC')
    );
    if ($timezone) {
        $date->setTimezone($timezone);
    }
    return $date;
}

function stripTime($datetime) {
    return new DateTime($datetime->format('Y-m-d'));
}

function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

function dir_path($path) {
	$path = str_replace('\\', '/', $path);
	if(substr($path, -1) != '/') $path = $path.'/';
	return $path;
}

function dir_list($path, $exts = '', $list= array()) {
	$path = dir_path($path);
	$files = glob($path.'*');
	foreach($files as $v) {
		$fileext = fileext($v);
		if (!$exts || preg_match("/\.($exts)/i", $v)) {
			$list[] = $v;
			if (is_dir($v)) {
				$list = dir_list($v, $exts, $list);
			}
		}
	}
	return $list;
}

function dir_tree($dir, $parentid = 0, $dirs = array()) {
	if ($parentid == 0) $id = 0;
	$list = glob($dir.'*');
	foreach($list as $v) {
		if (is_dir($v)) {
            $id++;
			$dirs[$id] = array('id'=>$id,'parentid'=>$parentid, 'name'=>basename($v), 'dir'=>$v.'/');
			$dirs = dir_tree($v.'/', $id, $dirs);
		}
	}
	return $dirs;
}


function dir_delete($dir) {
	//$dir = dir_path($dir);
	if (!is_dir($dir)) return FALSE;
	$handle = opendir($dir); //打开目录
	while(($file = readdir($handle)) !== false) {
	        if($file == '.' || $file == '..')continue;
			$d = $dir.DIRECTORY_SEPARATOR.$file;
	        is_dir($d) ? dir_delete($d) : @unlink($d);
	}
	closedir($handle);
	return @rmdir($dir);
}

function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time ) || $time==null) {
		return '&nbsp;';
	}
    if (is_hide_field($time)) {
        return $time;
    }
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
}

function string2array($info) {
    if($info == '') return array();
    $info=stripcslashes($info);
    eval("\$r = $info;");
    return $r;
}


/**
	 +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
	 +----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
	 +----------------------------------------------------------
 * @return string
	 +----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '') {
	$str = '';
	switch ($type) {
		case 0 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		case 1 :
			$chars = str_repeat ( '0123456789', 3 );
			break;
		case 2 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
			break;
		case 3 :
			$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		default :
			// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
			$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
			break;
	}
	if ($len > 10) { //位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat ( $chars, $len ) : str_repeat ( $chars, 5 );
	}
	if ($type != 4) {
		$chars = str_shuffle ( $chars );
		$str = substr ( $chars, 0, $len );
	} else {
		// 中文随机字
		for($i = 0; $i < $len; $i ++) {
			$str .= msubstr ( $chars, floor ( mt_rand ( 0, mb_strlen ( $chars, 'utf-8' ) - 1 ) ), 1 );
		}
	}
	return $str;
}
function sysmd5($str,$key='',$type='sha1'){
	return hash ( $type, $str);
}


/**
* @param string $string 原文或者密文
* @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
* @param string $key 密钥
* @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
* @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
*
* @example
*
*  $a = authcode('abc', 'ENCODE', 'key');
*  $b = authcode($a, 'DECODE', 'key');  // $b(abc)
*
*  $a = authcode('abc', 'ENCODE', 'key', 3600);
*  $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
*/
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
{
	$ckey_length = 4;
	// 随机密钥长度 取值 0-32;
	// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
	// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
	// 当此值为 0 时，则不产生随机密钥


	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++)
	{
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++)
	{
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++)
	{
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE')
	{
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
		{
			return substr($result, 26);
		}
		else
		{
			return '';
		}
	}
	else
	{
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}



//字符串截取
function str_cut($sourcestr,$cutlength,$suffix='...')
{
	$str_length = strlen($sourcestr);
	if($str_length <= $cutlength) {
		return $sourcestr;
	}
	$returnstr='';
	$n = $i = $noc = 0;
	while($n < $str_length) {
			$t = ord($sourcestr[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$i = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$i = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$i = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$i = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$i = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$i = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $cutlength) {
				break;
			}
	}
	if($noc > $cutlength) {
			$n -= $i;
	}
	$returnstr = substr($sourcestr, 0, $n);


	if ( substr($sourcestr, $n, 6)){
          $returnstr = $returnstr . $suffix;//超过长度时在尾处加上省略号
      }
	return $returnstr;
}

function IP($ip='',$file='UTFWry.dat') {
	import("@.ORG.IpLocation");
	$iplocation = new IpLocation($file);
	$location = $iplocation->getlocation($ip);
	return $location;
}

function byte_format($input, $dec=0)
{
  $prefix_arr = array("B", "K", "M", "G", "T");
  $value = round($input, $dec);
  $i=0;
  while ($value>1024)
  {
     $value /= 1024;
     $i++;
  }
  $return_str = round($value, $dec).$prefix_arr[$i];
  return $return_str;
}


function thumb($f, $tw=300, $th=300,$autocat=0, $nopic = 'nopic.jpg',$t='', $mark= false, $force = false){
	if(strstr($f,'://')) return $f;
	if(empty($f)) return __ROOT__.'/Public/img/'.$nopic;
	$f= str_replace(__ROOT__,'',$f);
	$f = ltrim($f, "/");


	$temp = array(1=>'gif', 2=>'jpeg', 3=>'png', 4=>'jpg');
	list($fw, $fh, $tmp) = getimagesize($f);
	if(empty($t)){
		if(($fw>$tw && $fh>$th) || $force){
			$pathinfo = pathinfo($f);
			//$t = $pathinfo['dirname'].'/thumb_'.$tw.'_'.$th.'_'.$pathinfo['basename'];
            $t = './thumb/thumb_'.$tw.'_'.$th.'_'.$pathinfo['basename'];
            if(is_file($t)){
				return  __ROOT__.substr($t,1);
			}
		}else{
			return  __ROOT__.substr($f,1);
		}
	}

	if(!$temp[$tmp]){	return false; }

	if($autocat){
		if($fw/$tw > $fh/$th){
		$fw = $tw * ($fh/$th);
		}else{
		$fh = $th * ($fw/$tw);
		}
	}else{
		 $scale = min($tw/$fw, $th/$fh); // 计算缩放比例
        if($scale>=1) {
            // 超过原图大小不再缩略
            $tw   =  $fw;
            $th  =  $fh;
        }else{
            // 缩略图尺寸
            $tw  = (int)($fw*$scale);
            $th = (int)($fh*$scale);
        }
	}

	$tmp = $temp[$tmp];
	$infunc = "imagecreatefrom$tmp";
	$outfunc = "image$tmp";
	$fimg = $infunc($f);

	if($tmp != 'gif' && function_exists('imagecreatetruecolor')){
		$timg = imagecreatetruecolor($tw, $th);
	}else{
		$timg = imagecreate($tw, $th);
	}
	if(function_exists('imagecopyresampled')) {
        imagecopyresampled($timg, $fimg, 0,0, 0,0, $tw,$th, $fw,$fh);
    }
	else{
        imagecopyresized($timg, $fimg, 0,0, 0,0, $tw,$th, $fw,$fh);
    }

	if($tmp=='gif' || $tmp=='png') {
		$background_color  =  imagecolorallocate($timg,  0, 255, 0);  //  指派一个绿色
		imagecolortransparent($timg, $background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
	}
	$outfunc($timg, $t, 100);
	imagedestroy($timg);
	imagedestroy($fimg);
    if ($mark) {
        import("@.ORG.Image");
        Image::watermark($t,'',F('mConfig_cn'));
    }
	return  __ROOT__.substr($t,1);
}


function wthumb($f, $tw=300, $th=300 ,$autocat=0, $nopic = 'nopic.jpg',$t=''){
	return thumb($f, $tw, $th,$autocat, $nopic,$t, true, true);
}

function Pinyin($_String) {
$_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
   "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
   "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
   "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
   "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
   "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
   "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
   "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
   "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
   "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
   "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
   "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
   "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
   "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
   "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
   "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
$_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
   "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
   "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
   "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
   "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
   "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
   "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
   "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
   "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
   "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
   "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
   "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
   "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
   "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
   "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
   "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
   "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
   "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
   "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
   "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
   "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
   "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
   "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
   "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
   "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
   "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
   "|-10270|-10262|-10260|-10256|-10254";
$_TDataKey   = explode('|', $_DataKey);
$_TDataValue = explode('|', $_DataValue);
$_Data =  array_combine($_TDataKey, $_TDataValue);
arsort($_Data);
reset($_Data);
$_String= auto_charset($_String,'utf-8','gbk');
$_Res = '';
for($i=0; $i<strlen($_String); $i++) {
      $_P = ord(substr($_String, $i, 1));
      if($_P>160) { $_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536; }
      $_Res .= _Pinyin($_P, $_Data);
}
return preg_replace("/[^a-z0-9]*/", '', $_Res);
}

// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from='gbk', $to='utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}

function _Pinyin($_Num, $_Data) {
   if    ($_Num>0      && $_Num<160   ) return chr($_Num);
   elseif($_Num<-20319 || $_Num>-10247) return '';
   else {
        foreach($_Data as $k=>$v){ if($v<=$_Num) break; }
        return $k;
   }
}

function upload_module_pic($module, $module_id, $module_field, $key, $name, $savename, $savepath) {
    $m_module_images = M($module . 'Images');
    $img_data[$module . '_id'] = $module_id;
    $img_data['name'] = $name;
    $img_data['save_name'] = $savename;
    $img_data['path'] = $savepath . $savename;
    $img_data['create_time'] = time();
    $img_data['listorder'] = intval($m_module_images->max('listorder')) + 1;
    $img_data[$module . '_field'] = $module_field;
    if ($key == 'work_pic') {
        //主图
        $img_data['is_main'] = 1;
        $where = array(
            'is_main' => 1,
            $module . '_id' => $module_id);

        $main_img = $m_module_images->where($where)->find();
        if ($main_img) {
            $m_module_images->where($where)->save($img_data);
            return $main_img['images_id'];
        } else {
            return $m_module_images->add($img_data);
        }
    } else if ($key == 'card_pic') {
        $img_data['is_main'] = 2;
        $where = array(
            'is_main' => 2,
            $module . '_id' => $module_id);
        $main_img = $m_module_images->where($where)->find();

        if ($main_img) {
            $m_module_images->where($where)->save($img_data);
            return $main_img['images_id'];
        } else {
            return $m_module_images->add($img_data);
        }
    }else{
        //副图
        $img_data['is_main'] = 0;
        return $m_module_images->add($img_data);
    }
}

function upload_module_file($module, $module_id) {
    $dirname = UPLOAD_PATH . date('Ym', time()).'/'.date('d', time()).'/';
    if (!is_dir($dirname) && !mkdir($dirname, 0777, true)) {
        return false;
    }
	if (!is_writable($dirname)) {
		$dirname = UPLOAD_PATH . date('Ym', time()).'/'.date('d', time()).'_'.date('d', time()).'/';
		mkdir($dirname, 0777, true);
	}

    import('@.ORG.UploadFile');

    //上传雇员主图和副图至服务器
    //如果有文件上传 上传附件
    //导入上传类
    $upload = new UploadFile();
    //设置上传文件大小
    $upload->maxSize = 20000000;
    //设置附件上传目录
    $upload->savePath = $dirname;

    if($upload->upload()) {// 上传错误提示错误信息
        $info =  $upload->getUploadFileInfo();

        //写入数据库
        foreach($info as $iv){
            $key = $iv['key'];
            $field_type = substr($key, 0, 4);

            if ($field_type == "pic_" || $key == 'work_pic' || $key=='card_pic') {
                $module_field = ($key == 'work_pic' || $key=='card_pic') ? $key : substr_replace($key, "", 0, 4);
                upload_module_pic($module, $module_id, $module_field, $key, $iv['name'], $iv['savename'], $iv['savepath']);
            } else if ($field_type == "vid_") {
                $m_module_video = M($module.'Video');
                $data['name'] = $iv['name'];
                $data[$module.'_id'] = $module_id;
                $data['file_path'] = $iv['savepath'].$iv['savename'];
                $data['role_id'] = session('role_id');
                $data['size'] = $iv['size'];
                $data['create_date'] = time();
                $data[$module.'_field'] = substr_replace($key, "", 0, 4);
                $data['listorder'] = intval($m_module_video->max('listorder'))+1;
                $m_module_video->add($data);
            } else if ($field_type == "fil_") {
                $m_file = M('file');
                $data['name'] = $iv['name'];
                $data['module'] = $module;
                $data['module_id'] = $module_id;
                $data['file_path'] = $iv['savepath'].$iv['savename'];
                $data['role_id'] = session('role_id');
                $data['size'] = $iv['size'];
                $data['create_date'] = time();
                $data['field'] = substr_replace($key, "", 0, 4);
                $m_file->add($data);
            }
        }
    }

    return true;
}

function FP($p1, $p2) {
    $pr = array();
    foreach(explode('&', $p1) as $p1v) {
        $p2l = explode('=', $p1v);
        $pr[$p2l[0]] = $p2l[1];
    }
    foreach(explode('&', $p2) as $p2v) {
        $p2l = explode('=', $p2v);
        if (!isset($p2l[1])) {
            unset($pr[$p2l[0]]);
        } else {
            $pr[$p2l[0]] = $p2l[1];
        }
    }

    $result = array();
    foreach($pr as $rk=>$rv) {
        if ($rv != '') {
            $result[] = $rk."=".$rv;
        }
    }
    return implode("&", $result);
}

function all_filter_field($moarr, $otherfield = array()) {
    if (!is_array($moarr)) {
        $moarr = array($moarr);
    }
    $exit_field = array(
        'datetime',
        'user',
        'p_box',
    );
    $field_list = array();
    foreach($moarr as $v) {
        foreach(getMainFields($v) as $fv) {
            if (in_array($fv['form_type'],$exit_field)) {
                continue;
            }
            $field_list[] = $v.'.'.$fv['field'];
        }
    }
    return array_merge($field_list, $otherfield);
}


function format_filter_field($field) {
    switch($field) {
        case "product_id": {
            $field = implode("|", array("product.idcode", "product.name"));
            break;
        }
        case "customer_id": {
            $field = implode("|", array("customer.idcode", "customer.name"));
            break;
        }
        case "cultivate.currier_id":
        case "currier_id": {
        	$field = implode("|", array("currier.idcode", "currier.name"));
            break;
        }
		case "cultivate.category_id":{
			$field = implode("|", array("currier_category.name"));
			break;
		}
		case "product.name":{
			$field = implode("|", array("product.name", "product.slug"));
			break;
		}
		case "customer.name":{
			$field = implode("|", array("customer.name", "customer.slug"));
			break;
		}
		case "user.name":{
			$field = implode("|", array("user.name", "user.slug"));
			break;
		}

    }
    return $field;
}

function perfect_model_field_post($model) {
	$_POST = perfect_model_field($model, $_POST);
    return $_POST;
}

function refer_url($ru, $m = "") {
    if (isset($_REQUEST[$ru])) {
        return urldecode($_REQUEST[$ru]);
    } elseif ($_REQUEST['ret_ref'] == 1) {
        return $_SERVER['HTTP_REFERER'];
    } else {
        return session("index_refer_url");
    }
}

function update_base64_pic( $pic_base64) {
	$pic_base64_pos = strpos($pic_base64, ",");
	if ($pic_base64_pos !== false) {
		$pic_base64 = substr($pic_base64, $pic_base64_pos + 1);
	}
    $imgbuf = base64_decode($pic_base64);
    $tmpfile = tempnam(sys_get_temp_dir(),"filename");
    file_put_contents($tmpfile, $imgbuf);
    $dirname = UPLOAD_PATH . date('Ym', time()).'/'.date('d', time()).'/';
    if (!is_dir($dirname)) {
        mkdir($dirname, 0777, true);
    }

    import('@.ORG.Image');
	$im = Image::imageCreateFromBMP($tmpfile);
	if ($im) {
		$savename = uniqid()."_".time(NULL) . ".png";
		$savepath = $dirname.$savename;;
		$index = imagecolorat($im, 0, 0);
		imagecolortransparent($im, $index);
		imagepng($im, $savepath);
	} else {
		$savename = uniqid()."_".time(NULL) . ".jpg";
		$savepath = $dirname.$savename;;
		$image = imagecreatefrompng($tmpfile);
		imagejpeg($image, $savepath, 100);
		imagedestroy($image);
	}
	return array("name"=>$savename, "savepath"=>$dirname, "savename"=>$savename);
}

function day($time_stamp) {
    return $time_stamp / 60 / 60 / 24;;
}

function hour($time_stamp) {
    return $time_stamp / 60 / 60;
}


function year($time_stamp) {
	return ceil($time_stamp / 60 / 60 / 24 / 356);
}

function export_pdf($file_name, $content) {
    Vendor('tcpdf.tcpdf');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(5, PDF_MARGIN_TOP, 5);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('stsongstdlight', '', 10);
    $pdf->AddPage();
    $pdf->writeHTML($content, true, false, true, false, '');
    $pdf->Output($file_name, 'I');
}

function format_module_category($module, $v) {
    $category = array();
    foreach(breadcrumb($module, $v) as $v) {
        $category[] = $v['name'];
    }
    return implode($category, " - ");
}

function catetree($module,$bc, $where = array(), &$child_tree = array()) {
    $where["parentid"]=$bc;
    $where["league_id"]=session('league_id');
    $child = array();
    foreach(M($module."_category")->cache(true)->where($where)->order("order_id asc")->select() as $v) {
        $child[] = $v;
        catetree($module, $v[$module.'_category_id'], $where, $child_tree);
    }
    $child_tree[$bc]['childs'] = $child;
    return $child_tree;
}


function breadcrumb($module, $bc, &$bclist = array(), $league_id = null) {
    $league_id = $league_id?$league_id:session('league_id');
    $module_category = M($module."_category")->cache(true)->where(array($module."_category_id="=>$bc, "league_id"=>$league_id))->find();
    if (!$module_category) {
        return $bclist;
    }
    array_unshift($bclist, $module_category);
    if ($module_category['parentid'] != 0) {
        breadcrumb($module, $module_category['parentid'], $bclist, $league_id);
    }
    return $bclist;
}

function cateallchild($module,$bc, $where = array(), &$child = array(), $league_id = null) {
    $league_id = $league_id?$league_id:session('league_id');
    $where["parentid"]=$bc;
    $where["league_id"]=$league_id;

    $module_category = M($module."_category")->cache(true)->where($where)->order("order_id asc")->select();
    foreach($module_category as $v) {
        $child[] = $v[$module.'_category_id'];
        cateallchild($module, $v[$module.'_category_id'], $where, $child, $league_id);
    }
    return $child;
}


function breadcrumb_id($module, $bc, &$bclist = array(), $league_id = null) {
    $league_id = $league_id?$league_id:session('league_id');
    $module_category = M($module."_category")->cache(true)->where(array($module."_category_id="=>$bc, "league_id"=>$league_id))->find();
    if (!$module_category) {
        return $bclist;
    }
    array_unshift($bclist, $module_category[$module.'_category_id']);
    if ($module_category['parentid'] != 0) {
        breadcrumb_id($module, $module_category['parentid'], $bclist, $league_id);
    }
    return $bclist;
}

function make_child_cate_select($module, $cat, $selcat,$league_id = null) {
    $optionhtml = "";
    $league_id = $league_id?$league_id:session('league_id');
    $module_category = M($module."_category")->cache(true)->where(array("parentid"=>$cat, "league_id"=>$league_id))->order("order_id asc")->select();
    if (!$module_category) {
        return $optionhtml;
    }

    $selecthtml = '<select serve_'.$module.'_id="'.$cat.'"  id="'.$module.'_category_' . $cat . '" class="'.$module.'_category" onchange="on_'.$module.'_category_change(this.value);">';
    $optionhtml .= "<option value=''>请选择</option>";
    foreach ($module_category as $cv) {
        $optionhtml .= "<option value='".$cv[$module.'_category_id']."'";
        $optionhtml .= $selcat == $cv[$module.'_category_id'] ? ' selected="selected"' : '';
        $optionhtml .= ">".$cv['name']."</option>";
    }
    return $selecthtml. $optionhtml . '</select>';
}

function make_serve_category_select($module, $cat, $league_id = null) {
    $cathtml = "";
    $bclist = array();
    $pcat = breadcrumb_id($module, $cat,$bclist, $league_id);
    array_unshift($pcat, 0);
    $rpcat = $pcat;
    array_shift($rpcat);

    foreach($pcat as $k=>$pv) {
        $cathtml .= make_child_cate_select($module, $pv, $rpcat[$k], $league_id);
    }
    return $cathtml;
}

function aweek($sd){
    $w = date("w", $sd);
    $dn = $w ? $w - 1 : 6;
    return strtotime(date("Y-m-d",$sd)." -".$dn." days");
}

function aseason($sd) {
    return ceil((date('n', $sd))/3);
}

function aquarter($sd, $s = 0) {
    $season = aseason($sd)+$s;
    return strtotime(date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))));
}

function germ_cycle($sd, $c) {
    switch ($c) {
        case "week": {
            $sd = aweek($sd);
            break;
        }
        case "month": {
            $sd = strtotime(date('Y-m', $sd));
            break;
        }
        case "quarter": {
            $sd = aquarter($sd);
            break;
        }
        case "year": {
            $sd = strtotime(date('Y-1-1', $sd));
            break;
        }
    }
    return $sd;
}

function select_setting_key_show_html($val,$field_id) {
	$setting = array();
	$fd = M("fields")->cache(true)->where("field_id=".$field_id)->find();
	if ($fd && $fd['setting']) {
		$setting_str = '$setting=' . $fd['setting'] . ';';
		eval($setting_str);
	}
	return $setting['data'][$val];
}

function format_address_area_field($state, $city, $area, $street) {
    $address = "";

    if ($state) {
        $a = M('Area')->cache(true)->where(array("id"=>$state))->find();
        if ($a) {
            $address .= $a['name'];

        }
    }

    if ($city) {
        $a = M('Area')->cache(true)->where(array("id"=>$city))->find();
        if ($a) {
            $address .= $a['name'];
        }
    }

    if ($area) {
        $a = M('Area')->cache(true)->where(array("id"=>$area))->find();
        if ($a) {
            $address .= $a['name'];
        }
    }
    return $address .$street;
}

function format_address_field($value, $s = "") {
    if ($s == ""){
        $s = chr(10);
    }
    $address_array = explode($s, $value);
    return format_address_area_field($address_array[0], $address_array[1], $address_array[2], $address_array[3]);
}


function format_address_edit_field($field, $value = "", $type = "add", $c="") {
    if ($c== "") $c = chr(10);
    if ('add' == $type) {
        $state = "0";
        $city = "0";
        $area = "0";
        $street = "";
    } else {
        $address_array = explode($c, $value);
        $state = $address_array[0];
        $city = $address_array[1];
        $area = $address_array[2];
        $street = $address_array[3];
    }
    $state_field = $field . "['state']";
    $state_field_id = $field . "_state";

    $city_field = $field . "['city']";
    $city_field_id = $field . "_city";

    $area_field = $field . "['area']";
    $area_field_id = $field . "_area";

    $street_field = $field . "['street']";
    $street_field_id = $field . "_street";

    $html = "<select name=\"" . $state_field . "\" id=\"" . $state_field_id . "\" onchange='area_change(this.value, 1,\"" . $state_field_id . "\",\"" . $city_field_id . "\",\"" . $area_field_id . "\");' class='ddlbox'  style='width:90px'>";
    $html .= "<option value='0'>选择省份</option></select>";
    $html .= "<select name=\"" . $city_field . "\" id=\"" . $city_field_id . "\" onchange='area_change(this.value, 2,\"" . $state_field_id . "\",\"" . $city_field_id . "\",\"" . $area_field_id . "\");' class='ddlbox'   style='width:120px'>";
    $html .= "<option value='0'>选择城市</option></select>";
    $html .= "<select name=\"" . $area_field . "\" id=\"" . $area_field_id . "\" onchange='area_change(this.value, 3,\"" . $state_field_id . "\",\"" . $city_field_id . "\",\"" . $area_field_id . "\");' class='ddlbox'   style='width:90px'>";
    $html .= "<option value='0'>选择地区</option></select>";
    $html .= "<input id=".$street_field_id." name=" . $street_field . " type='text' class='inputoo' value='$street'    style='width:180px'/>";
    $html .= "<script>area_change(0, 0,'$state_field_id','$city_field_id','$area_field_id','$state','$city','$area');</script>";
    return $html;
}


function array_exist($ia, $oa) {
    foreach($ia as $ik=>$iv) {
        if (in_array($iv, $oa)) {
            return $iv;
        }
    }
    return false;
}

function level_show_html($product_id, $category_id) {
	$pvwhere = array(
			"skill_data.category_id"=>$category_id,
			"skill_data.product_id"=>$product_id
	);
	$value['skill'] = D("SkillView")->where($pvwhere)->find();
	return $value['skill']['level'];
}

function plevel($l = "") {
    $level = array(
        "未定级"=>"n",
        "一星"=>"1",
        "二星"=>"2",
        "三星"=>"3",
        "四星"=>"4",
        "五星"=>"5",
        "金牌"=>"g"
    );
    if ($l) {
        return $level[$l];
    }
    return $level;
}

function plevelcheckbox($cc = array()) {
    $html = "";
    foreach(plevel() as $k=>$v) {
        $html .= "<input plevel='catelevel' type='checkbox' name='catelevel[]' value='".$k."' ";
        if ($cc && in_array($k, $cc)) {
            $html .= "checked='checked'";
        }
        $html .= "/>".$k;
    }
    return $html;
}

function acction_type_desc($t = "") {
    $atype = array(
        '-1'=>"支出",
        '1'=>'收入',
        '-3'=>'资金冻结',
        '3'=>'资金解冻',
    );
    return $t ? $atype[$t]:$atype;
}


function dire_type_desc($t = "") {
    $atype = array(
        '-1'=>"现金流出",
        '1'=>'现金流入'
    );
    return $t !=""? $atype[$t]:$atype;
}



function account_state_desc($t = "") {
    $atype = array(
        '0'=>"未完成",
        '1'=>'已完成'
    );
    return $t!="" ? $atype[$t]:$atype;
}

function related_desc($related) {
    switch($related) {
        case "trade": {
            return "产品";
        }
        case "other": {
            return "其他";
        }
    }
    return "";
}

function make_logs_list($model_view, $where, $fmt) {
	$data = array();
	$m_module = D($model_view);
	$count = $m_module->where($where)->count();
	if ($count > 0) {
		$m_module = $m_module->where($where);
		if ($_GET['length']) {
			$start = $_GET['start'] ? $_GET['start'] : 0;
			$length = $_GET['length'] ? $_GET['length'] : 5;
			$m_module = $m_module->limit($start, $length);
		}
		if ($_GET['order']) {
			$m_module = $m_module->order($_GET['order']);
		}
		foreach($m_module->select() as $k=>$v){
			$data[] = call_user_func($fmt,$v);
		}
	}
	return array("recordsFiltered"=>$count, "recordsTotal"=>$count, "data"=>$data);
}

function make_data_list($model_view, $where, $data_field, $fmt) {
    $data = array();
    $m_module = D($model_view);
    $count = $m_module->where($where)->count();
    if ($count > 0) {
        $m_module = $m_module->where($where);
        if ($_GET['length']) {
            $start = $_GET['start'] ? $_GET['start'] : 0;
            $length = $_GET['length'] ? $_GET['length'] : 5;
            $m_module = $m_module->limit($start, $length);
        }
        if ($_GET['order']) {
            $order_column = $_GET['order'][0]["column"];
            $order_dir = $_GET['order'][0]["dir"];
			if ($data_field[$order_column]['order']) {
				$order = $data_field[$order_column]['order']." ".$order_dir;
			} else {
				$order = $data_field[$order_column]['data']." ".$order_dir;
			}
            $m_module = $m_module->order($order);
        }

		$datalist = $m_module->select();
		if ($data_field) {
			foreach($datalist as $k=>$v){
				$acinfo = call_user_func($fmt,$v);
				$data_field_list = array();
				foreach($data_field as $fk=>$fv) {
					$data_field_list[$fk] = $acinfo[$fv['field']?$fv['field']:$fv['data']];
				}
				$data[] = $data_field_list;
			}
		} else {
			foreach($datalist as $k=>$v){
				$data[] = call_user_func($fmt,$v);
			}
		}
    }
    return array("recordsFiltered"=>$count, "recordsTotal"=>$count, "data"=>$data);
}

function make_datatable_list($model_view, $where, $fmt = array()) {
	$data = array();
	$m_module = D($model_view);
	$count = $m_module->where($where)->count();
	if ($count > 0) {
		$m_module = $m_module->where($where);
		if ($_GET['length']) {
			$start = $_GET['start'] ? $_GET['start'] : 0;
			$length = $_GET['length'] ? $_GET['length'] : 5;
			$m_module = $m_module->limit($start, $length);
		}
		if ($_GET['order']) {
			$order = $_GET['columns'][$_GET['order'][0]["column"]]['data']." ".$_GET['order'][0]["dir"];
			$m_module = $m_module->order($order);
		}

		$datalist = $m_module->select();
		foreach($datalist as $k=>$v){
			$data[] = call_user_func($fmt,$v);
		}
	}
	return array("recordsFiltered"=>$count, "recordsTotal"=>$count, "data"=>$data);
}

function make_time_between($cc = true) {
    if ($_REQUEST['start_time'] != "") {
        $start_time = strtotime($_REQUEST['start_time']);
    } elseif ($_REQUEST['end_time'] != "") {
        $start_time = 0;
    }
    if ($_REQUEST['end_time'] != "") {
		if ($cc) {
			$end_ee = strtotime(date("Y-m-d 23:59:59", strtotime($_REQUEST['end_time'])));

		} else {
			$end_ee = strtotime($_REQUEST['end_time']);
		}
        $end_time = $_REQUEST['end_time'] ? $end_ee  : PHP_INT_MAX;
    } elseif ($_REQUEST['start_time'] != "") {
        $end_time = PHP_INT_MAX;
    }
    return array($start_time, $end_time);
}

function calculate_margin_account($module, $where, $mdel = 1) {
    $where["income_or_expenses"] = $mdel;
    $money = D("Manage/".ucfirst($module)."AccountView")->where($where)->sum("account.money");
    $where["income_or_expenses"] = -$mdel;
    return abs($money - D("Manage/".ucfirst($module)."AccountView")->where($where)->sum("account.money"));
}


function total_account_type_money($typeid, $clause_additive) {
    $where = array(
        "clause_type_id"=>$typeid,
        "clause_additive"=>$clause_additive,
    );
    $where['league_id'] = session('league_id');
    return M("account")->where($where)->sum("money");
}

function total_account_type_balance($account_type, $clause_additive) {
    if ($account_type['inflow_model_type_id']) {
        $out = total_account_type_money($account_type['inflow_model_type_id'], $clause_additive);
    }
    $in = total_account_type_money($account_type['type_id'], $clause_additive);
    return $out - $in;
}

function enum_branch($bc, &$child = array()) {
    $where['league_id'] = session('league_id');
    $where['parentid'] = $bc;
    foreach(M("branch_category")->cache(true)->where($where)->select() as $v) {
        $child[] = $v['role_id'];
        enum_branch($v['branch_category_id'], $child);
    }
    return $child;
}

function get_branch($role_id) {
    static $branch_child = null;
    if (!$branch_child) {
        $branch_child = array();
        $role_branch = M("branch_category")->cache(true)->where("role_id=".$role_id)->find();
        if ($role_branch) {
            $branch_child[] = $role_branch["role_id"];
            enum_branch($role_branch['branch_category_id'], $branch_child);
        }
		else{
			$branch_child[] = $role_id;

		}
    }
    return $branch_child;
}

function get_branch_all_role($bc = "") {
	$where = array();
	if ($bc) {
		$where['branch_id'] = $bc;
	}
    $where['league_id'] = session('league_id');
    return M("branch_category")->cache(true)->where($where)->getField("role_id", true);
}

function enum_trunk($bc, &$parent = array()) {
    $m_branch = M("branch_category")->cache(true)->where("branch_category_id=".$bc)->find();
    if ($m_branch) {
        $parent[] = $m_branch['role_id'];
        if ($m_branch['parentid']) {
            enum_branch($m_branch['parentid'], $parent);
        }
    }
    return $parent;
}

function get_trunk($role_id) {
    static $parent_trunk = null;
    if (!$parent_trunk) {
        $parent_trunk = array();
        $role_branch = M("branch_category")->cache(true)->where("role_id=".$role_id)->find();
        if ($role_branch) {
            $parent_trunk[] = $role_branch['role_id'];
            if ($role_branch['parentid']) {
                enum_trunk($role_branch['parentid'], $parent_trunk);
            }
        }
    }
    return $parent_trunk;
}

function is_hide_field($v) {
    return $v === "***";
}


function make_sort_ulli(&$children, $category_id = -1) {
    foreach($children as $k=>&$v) {
        if ($v['id'] == $category_id) {
            $children[$k]['state'] = array("opened"=>true, "selected"=>true);
        }
        make_sort_ulli($v['children'], $category_id);
    }
    return $children;
}

function make_trade_state_where($state, $where) {
    switch ($state) {
        case 'df' :
        case '待付款':
            $where['_string'] = " trade.state!='已撤销' and trade.pay_price=0  ";
            break;

        case '待开始':
        case 'dk' :
            $where['_string'] = " trade.state!='已撤销' and trade.pay_price>0 and (begin_date=0 or UNIX_TIMESTAMP()<begin_date)";
            break;

        case 'jx' :
        case '进行中':
            $where['_string'] = " trade.state!='已撤销' and trade.pay_price>0 and trade.begin_date >0  and UNIX_TIMESTAMP() > trade.begin_date and ( UNIX_TIMESTAMP()<trade.end_date or trade.end_date=0 ) ";
            break;

        case 'js' :
        case '已结束':
            $where['_string'] = " trade.state!='已撤销' and trade.pay_price>0  and UNIX_TIMESTAMP() > end_date and (end_date !=0 ) ";
            break;
    }
    return $where;
}

function cost_filed_map($model, $cost_field  = null){
    $field_map = array(
        "business"=>array(
            "sales_cost"=>"促单费",
            "introducerfee"=>"渠道费"
        ),
        "trade"=>array(
            "salesfee"=>"促单费",
            "cost"=>"操作费",
            "introducerfee"=>"渠道费",
        ),
        "trainorder"=>array(
            "sellsum"=>"促单费",
            "certificate_cost"=>"证书费",
            "teaching_materials_cost"=>"教材费",
            "teach_cost"=>"教师费",
        )
    );
    return $cost_field ? $field_map[$model][$cost_field] : $field_map[$model];
}

function cost_status_map($status) {
    return $status == 0 ? "未提交" : ($status==1?"已提交":"已提现");
}

function payment_verify_map($status) {
    return $status == 0 ? "待确认" : ($status==1?"确认":"无法确认");
}

function format_market_status($status_id, $is_cancel_submit) {
	$status = convert_market_status($status_id, $is_cancel_submit);
	return $status['name'];
}


function convert_market_status($status_id,$is_cancel_submit = 0) {
	$status = M('MarketStatus')->cache(true)->where(array('status_id'=>$status_id))->find();
	if ($status_id == 916 && $is_cancel_submit == 1) {
		$status['name'] = "结算退回";
	}
	return $status;
}

function in_market_status($status_id, $sss) {
	$status_ids = M('MarketStatus')->cache(true)->where(array("model"=>$sss))->getField("status_id");
	return in_array($status_id, $status_ids);
}

function proudct_category_map($val = null) {
	$category = M('product_category')->cache(true)->cache(true)->where("category_id=".$val)->find();
	return $category ? $category['name'] : "";

}

function train_category_map($val = null) {
	$category = M('currier_category')->cache(true)->where("currier_category_id=".$val)->find();
	return $category ? $category['name'] : "";

}

function lia_field_map($val = null) {
	if ($val == "self") {
		return "我负责的";
	}
	if ($val == "foll") {
		return "下属负责的";
	}
	return "";

}



function is_market_settle($v) {
	return in_array($v['settle_state'], array("917", "918"));
}

function is_cultivate_settle($v) {
	return in_array($v['settle_state'], array("917", "918"));
}
function format_cultivate_status($status_id,$is_cancel_submit = 0) {
	$status = convert_cultivate_status($status_id,$is_cancel_submit);
	return $status['name'];
}

function convert_cultivate_status($status_id,$is_cancel_submit = 0) {
	$status = M('CultivateStatus')->cache(true)->where(array('status_id'=>$status_id))->find();
	if ($status_id == 916 && $is_cancel_submit == 1) {
		$status['name'] = "结算退回";
	}
	return $status;
}

function search_form_default_param($parameter, $other_exist = array()) {
	$exist_fields = array_merge(array(
		"field","condition","search","act","pl","group_type","by","bybr","search_bt","search_et"
	), $other_exist?$other_exist:array());
	$html = "";
	foreach(explode("&",$parameter) as $v) {
		$p = explode("=",$v);
		if (in_array($p[0], $exist_fields)) {
			continue;
		}
		if ($p[1] != null && $p[1] != "") {
			$html .= "<input type=\"hidden\" name=\"".$p[0]."\" value=\"".$p[1]."\"/>";
		}
	}
	return $html;
}


function idcode_format($vo, $vm) {
	$stdidcode = $vm['model']."_".$vm['field'];
	if (isset($vo[$stdidcode])) {
		return $vo[$stdidcode];
	}
	return $vo[$vm['field']];
}

function is_hefa_password($pass) {
	if (strlen($pass) < 8)
		return false;
	if (strpbrk($pass, "0123456789") === false) {
		return false;
	}
	return strpbrk($pass, "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
}


function model_field_diff($fv, $n, $o) {
    if ($fv['form_type'] == "user") {
        $new_role = D("UserView")->where("role.role_id=".$n[$fv['field']])->find();
        if ($new_role) {
            $fv['newvalue'] = $new_role['name'];
        }
        $old_role = D("UserView")->where("role.role_id=".$o[$fv['field']])->find();
        if ($old_role) {
            $fv['oldvalue'] = $old_role['name'];
        }
    } else if($fv['form_type'] == "datetime"){
        $fv['oldvalue'] = toDate($o[$fv['field']]);
        $fv['newvalue'] = toDate($n[$fv['field']]);
    } else if($fv['form_type'] == "address"){
        $fv['oldvalue'] = format_address_field($o[$fv['field']]);
        $fv['newvalue'] = format_address_field($n[$fv['field']]);
    }else {
        $fv['oldvalue'] = $o[$fv['field']];
        $fv['newvalue'] = $n[$fv['field']];
    }
    return $fv;
}

function is_nosettle_cate($cat) {
	return in_array($cat, array(5, 6, 9, 11));
}


function getLastMonthDays($timestamp){
    $firstday=date('Y-m-01 0:0:0',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
    $lastday=date('Y-m-d 23:59:59',strtotime("$firstday +1 month -1 day"));
    return array(strtotime($firstday),strtotime($lastday));
}

function create_time_desc($td) {
	switch($td) {
		case "today": return "今日新建";
		case "week": return "本周新建";
		case "fmonth": return "上月新建";
		case "month": return "本月新建";
	}
	return "";
}


function settle_time_desc($td) {
	switch($td) {
		case "today": return "今日结算";
		case "week": return "本周结算";
		case "fmonth": return "上月结算";
		case "month": return "本月结算";
	}
	return "";
}

function verify_state_desc($td, $l) {
    if (in_array($td, $l)) {
        switch($td) {
            case "nvb":case "bi":case "yvb":case "cvb":return "基本信息";
            case "nvp":case "si":case "yvp":case "cvp":return "专业信息";

        }
    }

    return "";
}

/**
 * 自定义异常处理
 * @param string $msg 异常消息
 * @param string $type 异常类型 默认为ThinkException
 * @param integer $code 异常代码 默认为0
 * @return void
 */
function throw_exception_log($msg, $title) {
	throw new LogException($msg, $title, 2);
}



function model_select_field_list($field_id) {
	$origin_field = M('Fields')->cache(true)->where(array('field_id'=>$field_id))->find();
	if (!$origin_field || !$origin_field['setting'])
		return array();
	$setting_str = '$setting=' . $origin_field['setting'] . ';';
	eval($setting_str);
	return $setting['data'];
}


function customer_origin_list() {
	return model_select_field_list(9);
}

function dorm_owner_staff_list() {
	$where = array();
    $where['league_id'] = session('league_id');
    $cc = M("dorm")->field("owner_role_id")->group("owner_role_id")->where($where)->select(false);
	return D('Manage/StaffView')->where("role_id in (".$cc.")")->select();
}

function berth_owner_staff_list($berth_id) {
	$where = array();
	if (isset($berth_id)) {
		$where["berth_id"]=$berth_id?$berth_id:0;
	}
    $where['league_id'] = session('league_id');

    $cc = M("berth")->field("owner_role_id")->group("owner_role_id")->where($where)->select(false);
	return D('Manage/StaffView')->where("role_id in (".$cc.")")->select();
}

function commiss_owner_staff_list() {
    $where = array();
    $where['league_id'] = session('league_id');

    $cc = M("commiss")->field("owner_role_id")->group("owner_role_id")->where($where)->select(false);
    return D('Manage/StaffView')->where("role_id in (".$cc.")")->select();
}


function format_table_head_date_col($field,$parameter) {
	$html = "";
	if ($_GET[$field]["value"][0]) {
		$html.= $_GET[$field]["value"][0];
	}
	if ($_GET[$field]["value"][1]) {
		$html.= "至".$_GET[$field]["value"][1];
	}
	if ($html != "") {
		$html.= "<a title='清除过滤条件' style='color:red' href='".U("",FP($parameter, $field."[condition]=&".$field."[value][0]=&".$field."[value][1]="))."'<i class=\"icon-remove\"></i></a>";
	}
	return $html;
}

function condition_show($condition) {
	$listc = array("contains"=>"包含","not_contain"=>"不包含","is"=>"是","isnot"=>"不是","start_with"=>"开始字符","end_with"=>"结束字符","is_empty"=>"为空","is_not_empty"=>"不为空","gt"=>"大于","lt"=>"小于","eq"=>"等于","neq"=>"不等于");
	return $listc[$condition];
}


function format_table_head_text_col($field,$parameter) {
	$html = "";
	if ($_GET[$field]["condition"]) {
		$html.= condition_show($_GET[$field]["condition"]);
	}
	if ($_GET[$field]["value"]) {
		$html.= $_GET[$field]["value"];
	}

	if ($html != "") {
		$html.= "<a title='清除过滤条件' style='color:red' href='".U("",FP($parameter, $field."[condition]=&".$field."[value]="))."'<i class=\"icon-remove\"></i></a>";
	}
	return $html;
}


function show_table_head_clear($field,$parameter) {
	return "<a title='清除过滤条件' style='color:red' href='".U("",FP($parameter, $field."="))."'<i class=\"icon-remove\"></i></a>";
}

function make_channel_model_keyword($channel_role_model, $channel_role_id, $data) {
	$channel = M("channel")->cache(true)->where(array("channel_id"=>$channel_role_model))->find();
	if ($channel) {
		$channel_role_model_keyword = array();
		$channel_role_model_keyword[] = $channel['name'];
		$data["channel_role_model_keyword"] = implode(chr(10), $channel_role_model_keyword);

		$channel_role_id_keyword = array();
		$channel_role = channel_model_role(channel_model_map($channel), $channel_role_id);
		if ($channel_role) {
			$channel_role_id_keyword[] = $channel_role['name'];
			$channel_role_id_keyword[] = $channel_role['idcode'];
			$channel_role_id_keyword[] = $channel_role['telephone'];
		} elseif ($channel_role_id) {
			$channel_role_id_keyword[] = $channel_role_id;
		}
		$data["channel_role_id_keyword"] = implode(chr(10), $channel_role_id_keyword);
	}
	return $data;
}


function make_channel_model($model) {
    $channel_role = null;
    $channel = M("channel")->cache(true)->where(array("channel_id"=>$model['channel_role_model']))->find();
    if ($channel) {
        $channel_role = channel_model_role(channel_model_map($channel), $model['channel_role_id']);
    }
    return $channel_role;
}


function make_channel_model_show($model) {
    $model_show = "";
    $channel = M("channel")->cache(true)->where(array("channel_id"=>$model['channel_role_model']))->find();
    if ($channel) {
        $channel_role = channel_model_role(channel_model_map($channel), $model['channel_role_id']);
        if ($channel_role) {
            $model_show = "[".$channel_role['idcode']."]".$channel_role['name'];
        }
    }
    return $model_show;
}

function update_dorm_info($dorm_ids) {
	if (!is_array($dorm_ids)) {
		$dorm_ids = array($dorm_ids);
	}

	foreach($dorm_ids as $k=>$dorm_id) {
		$where = array("dorm_id"=>$dorm_id);
        $where['league_id'] = session('league_id');
		$dorm = M("dorm")->where($where)->find();

		$data = array();
		$where['status'] = "入住";
		$data['berth_used'] = M("berth")->where($where)->count();
		$where['status'] = "停用";
		$data['berth_disabled'] = M("berth")->where($where)->count();
		$data['berth_idle'] = $dorm['berth_max'] - $data['berth_used'] - $data['berth_disabled'];
		M("dorm")->where(array("dorm_id"=>$dorm_id))->setField($data);
	}
}


function branch_dorm_list($branch_id) {
	$where = array();
	if (isset($branch_id)) {
		$where["branch_id"]=$branch_id?$branch_id:0;
	}
    $where['league_id'] = session('league_id');
	return M('dorm')->where($where)->select();
}


function dorm_show_html($dorm_id) {
	$where = array("dorm_id"=>$dorm_id);
    $where['league_id'] = session('league_id');
	return M('dorm')->where($where)->getField("name");
}


function channel_show_html($channel_id) {
	$where = array("channel_id"=>$channel_id);
    $where['league_id'] = session('league_id');
	return M('channel')->where($where)->cache(true)->getField("name");
}

function channel_model_map($channel) {
    $channel_model_arr = array("渠道库"=>"channel","雇员库"=>"product","客户库"=>"customer","员工库"=>"staff");
    return $channel_model_arr[$channel['model']];
}

function channel_model_role($channel_model, $channel_role_id) {
    return M($channel_model)->field(array("idcode","name",$channel_model."_id"))->where(array($channel_model."_id"=>$channel_role_id))->find();
}

function channel_model_role_info($channel) {
    if (!is_array($channel)) {
        $channel = M("channel")->cache(true)->where(array("channel_id"=>$channel))->find();
    }
    return channel_model_map($channel);
}

function channel_model_role_id_info($channel, $channel_role_id) {
    $channel_model = channel_model_role_info($channel);
    return channel_model_role($channel_model, $channel_role_id);
}

function channel_model_role_show_html($channel, $channel_role_id, $href=false) {
    if (!$channel) {
        return $channel_role_id;
    }
    $channel_model = channel_model_role_info($channel);
    if (!$channel_model) {
        return $channel_role_id;
    }
    $channel_role = channel_model_role($channel_model, $channel_role_id);
	if (!$channel_role) return $channel_role_id;
	if ($href) {
		$channel_role_id = "<a target='_blank' href='".U($channel_model."/view", "id=".$channel_role_id)."'>[".$channel_role['idcode']."]".$channel_role['name']."</a>";
	} else {
		$channel_role_id = $channel_role['name'];
	}
	return $channel_role_id;
}

function check_channel_model_info($map, $commiss_id = null) {
	if (!$map)return false;
	$map['_logic'] = 'or';
	$where['_complex'] = $map;

	if ($commiss_id) {
		$where['commiss_id'] = array("neq", $commiss_id);
	}
	$where["_logic"] = "and";
    $where['league_id'] = session('league_id');

	return M('commiss')->where($where)->find();
}


function owner_role_staff_list($m) {
    $where['league_id'] = session('league_id');
	$cc = M($m)->field("owner_role_id")->where($where)->group("owner_role_id")->select(false);
	return D('Manage/StaffView')->where("role_id in (".$cc.")")->select();
}

function berth_show_html($berth) {
	if(!is_array($berth)){
		$berth = M('berth')->cache(true)->where('berth_id = %d', $berth)->find();
	}
	return '<span><a href="'.U('berth/view', 'id='.$berth['berth_id']).'" target="_blank">'.$berth['name'] . '</a></span>';;
}

function format_field_show_html($module="", $field="", $d_module=array()) {
	$v = field_show_html($module, $field, $d_module);
	return $v['html'];
}

function is_field_show($form_type) {
	return in_array($form_type,array(
			'currier_model',
			'currier_model_id'
	));
}

function currier_show_html($currier) {
	if(!is_array($currier)){
		$currier = M('currier')->where('currier_id = %d', $currier)->find();
	}
	return '<span><a href="'.U('currier/view', 'id='.$currier['currier_id']).'" target="_blank">['.$currier['idcode']."] ".$currier['name'] . '</a></span>';;
}

function delete_cache_temp() {
	deldir(RUNTIME_PATH."Temp");
}

function role_log($m, $a, $req, $act = "") {
    $role_id = session('role_id');
    $data = array(
        "module"=>$m,
        "action"=>$a,
        "act"=>$act,
        "role_id"=>$role_id,
        "create_time"=>time()
    );

    $user = M('user')->where(array('user_id'=>session('user_id')))->find();
    if ($user) {
        $data['user_name'] = $user['name'];
        $data['user_idcode'] = $user['idcode'];
    }

    if ($req) {
        $data['request'] = json_encode($req);
    }

    $data['ip'] = get_client_ip();
   // $data['location'] = IP($data['ip']);

    M("staff_log")->add($data);
}

function product_workstate_list() {
    $state_list = model_select_field_list(60);
    $state_list[] = "排岗";
    $state_list[] = "待岗";
    $state_list[] = "上岗";
    return $state_list;

}

function product_workstate_list_opt($product, $showmshi = false) {
    $field = M('Fields')->field("field_id, setting")->cache(true)->where(array('field_id'=>"60"))->find();
    $setting_str = '$setting=' . $field['setting'] . ';';
    eval($setting_str);
    $str = "<option value=''>--自动设置--</option>";
    foreach ($setting['data'] as $v2) {
        if ($v2 == "面试" && !$showmshi)
            continue;
        $str .= "<option value='$v2'";
        $str .= $product['workstate_id'] == $v2 ? ' selected="selected"' : '';
        $str .= ">$v2</option>";
    }
     return '<select class="weui_select" id="workstate_id" name="workstate_id">' . $str . '</select>';
}

function product_makret_workstate($product) {
    $service_state = M("market_product")->where(array("product_id"=>$product['product_id'], 'service_status_id'=>2))->count();
	if ($service_state > 0) {
        $workstate_id = "上岗";
    } else {
       // $idle_service_state = M("market_product")->where( array("product_id"=>$product['product_id'], 'service_status_id'=>array("neq", array(0))))->count();
        //if ($idle_service_state == 0) {
            $workstate_id = "待岗";
        //} else {
		//	$workstate_id = $product['workstate_id'];
		//}
    }
    return $workstate_id;
}


function queue_branch_html($val) {
    if ($val > 0) {
        $branch = M('branch')->cache(true)->field("name, branch_id")->where('branch_id = %d ', $val)->find();
        if ($branch) {
            return  '<span style="color: #08c"><a href="'.U('branch/view', 'id='.$val).'">' . $branch['name'] . '</a></span>';
        }
    }
    if ($val == 0) return  '<span style="color: #08c">总部调度</span>';
    if ($val == -1) return  '<span style="color: #08c">入职总表</span>';
}

function queue_branch_list($where = null) {
    $where['league_id'] = session('league_id');
    $branch = M('branch')->where($where?$where:array())->cache(true)->field("name, branch_id")->select();

    if ($where == null) {
        $branch[] = array("branch_id"=>0,"name"=>"总部调度");
        $branch[] = array("branch_id"=>-1,"name"=>"入职总表");
    }

    return $branch;
}

function queue_branch_list_eq($b1, $b2) {
	return isset($b1) && $b1 === $b2;
}

function ex_isset($v) {
	return isset($v) && $v !== "";
}

function is_midle($b1, $e1, $b2, $e2) {
    return ($b1 >= $b2 && $b1 <=$e2) || ($e1 >= $b2 && $e1 <=$e2) || ($b2 >= $b1 && $b2 <=$e1) || ($e2 >= $b1 && $e2 <=$e1);
}

function defaultinfo($key) {
    $defaultinfo = F('defaultinfo'.session('league_id'));
    return $defaultinfo[$key];
}


function format_plevel($level, $img = true) {
	if ($img) {
		$html = "";
		if ($level == "金牌") {
			$html.="<img src='/Public/img/003.png'>";
		} else {
			$starcnt = array("一星"=>1, "二星"=>2, "三星"=>3, "四星"=>4, "五星"=>5);
			for($i = 0; $i < $starcnt[$level]; ++$i) {
				$html.="<img src='/Public/img/star-on.png'>";
			}
		}
		return $html;
	} else {
		$html = array();
		if ($level == "金牌") {
			$html[] = '/Public/img/003.png';
		} else {
			$starcnt = array("一星"=>1, "二星"=>2, "三星"=>3, "四星"=>4, "五星"=>5);
			for($i = 0; $i < $starcnt[$level]; ++$i) {
				$html[] = '/Public/img/starOn-.png';
			}
			for(; $i < 5; ++$i) {
				$html[] = '/Public/img/starOff-.png';
			}
		}
		return $html;
	}
}

function format_bscope($cnt) {
	$html = "";

	for($i = 0; $i < $cnt; ++$i) {
		$html.="<img src='/Public/img/xing.png'>";
	}
	return $html;
}

function birthday2age($birthday) {
	$age = date('Y', time()) - date('Y', strtotime($birthday)) - 1;
	if (date('m', time()) == date('m', strtotime($birthday))){
		if (date('d', time()) > date('d', strtotime($birthday))){
			$age++;
		}
	}elseif (date('m', time()) > date('m', strtotime($birthday))){
		$age++;
	}
	return $age;
}
