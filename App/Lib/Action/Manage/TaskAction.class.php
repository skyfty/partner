<?php
class TaskAction extends BaseAction{
	public function _initialize(){
		$action = array(
			'permission'=>array(
                'tips',
                'edit',
                'add',
                'delete',
            ),
			'allow'=>array('close','revert','open','changecontent','analytics')
		);
		B('Authenticate', $action);
	}

	public function add(){
		if ($this->isPost()) {
            if(!$_POST['subject']) {
                alert('error', L('NEED_TASK_TITLE'),  $_SERVER['HTTP_REFERER']);
            }

            $task_id = D('TasksView')->add_task(
                $_POST['subject'],
                $_POST['description'],
                $_POST['owner_role_id'],
                $_POST['module'], $_POST['module_id'],
                $_POST['start_time'], $_POST['end_time'],
                $_POST['priority']
            );
            if ($task_id === false) {
                alert('error', L('FAILED_ADD'),  $_SERVER['HTTP_REFERER']);
            }

            $refer_url = $_POST['refer_url'];
            if($_POST['submit'] == L('SAVE')) {
                if($refer_url){
                    alert('success', L('SUCCESS_ADD'), $refer_url);
                }else{
                    alert('success', L('SUCCESS_ADD'), U('task/index'));
                }
            } elseif($_POST['submit'] == L('SAVE AND NEW')) {
                alert('success', L('SUCCESS_ADD'), U('task/add'));
            } else {
                if($refer_url){
                    alert('success', L('SUCCESS_ADD'), $refer_url);
                }else{
                    alert('success', L('SUCCESS_ADD'), U('task/index'));
                }
            }
		} elseif($_GET['r'] && $_GET['module'] && $_GET['id']) {
			$this->r = $_GET['r'];
			$this->module = $_GET['module'];
			$this->id = $_GET['id'];
			$this->refer_url = $_SERVER['HTTP_REFERER'];
			$this->display('Task:add_dialog');
		}  else {
			$this->alert = parseAlert();
			$this->display();
		}
	}

	public function edit(){
		$task_id = $_POST['task_id'] ? intval($_POST['task_id']) : intval($_GET['id']);
		$task = M('Task')->where('task_id = %d', $task_id)->find();
		if(empty($task)){
			$this->error(L('PARAMETER_ERROR'));
		}
		if($_POST['owner_name']){
			$d_task = D('Task');
            $orgtask = $d_task->where("task_id=".$task_id)->find();

			$d_task->create();
            $task['start_time'] = isset($_POST['start_time']) ? strtotime($_POST['start_time']) : time();
            $task['end_time'] = isset($_POST['end_time']) ? strtotime($_POST['end_time']) : time();
            $d_task->update_time = time();

			$is_updated = false;
			$module = isset($_POST['module']) ? $_POST['module'] : '';
			if ($module != '') {
				switch ($module) {
					case 'customer' : $m_r = M('RCustomerTask'); $module_id = 'customer_id'; break;
					case 'product' : $m_r = M('RProductTask'); $module_id = 'product_id'; break;
					case 'market' : $m_r = M('RMarketTask'); $module_id = 'market_id'; break;
				}
				if (!$_POST['module_id']) {
                    $this -> error(L('SELECT_CORRESPOND_OPTION'));
                }
                if (!$m_r->where('task_id = %d and '.$module.'_id = %d', $task_id, intval($_POST['module_id']))->find()) {
                    $r_module = array(
                        'Market'=>'RMarketTask',
                        'Customer'=>'RCustomerTask',
                        'Product'=>'RProductTask',
                    );
                    foreach ($r_module as $value) {
                        $r_m = M($value);
                        $r_m->where('task_id = %d', $task_id)->delete();
                    }
                    $data[$module_id] = intval($_POST['module_id']);
                    $data['task_id'] = $task_id;
                    $rs = $m_r->add($data);
                    if ($rs<=0) {
                        alert('error', L('RELATED_FAILED'), $_SERVER['HTTP_REFERER']);
                    }
                    $is_updated = true;
                }
			}

			if ($d_task->save()) {
                $is_updated = true;
                $status = $this->_request("status");
                if($orgtask['status'] != $status) {
                    $taskcb = $orgtask['taskcb'];
                    if ($taskcb && ($taskcb = unserialize($taskcb))){
                        if ($taskcb[$status]) {
                            sendRequest($taskcb[$status], array("desc"=>$this->_request("jujuedesc")));
                        }
                    }
                    $creator = getUserByRoleId(session('role_id'));
                    $email_content = $creator['user_name']."改变任务 <a href='".U("task/view", "id=".$task_id)."'>".$task['subject']."</a> 状态为".$status;
                    sendMessage($task['creator_role_id'], $email_content);
                }
            }
			if($is_updated){
				alert('success', L('MODIFY_TASK_SUCCESS'), U('task/view', 'id='.$task_id));
			}else{
				alert('error', L('DATA_DID_NOT_CHANGE_MODIFY_FAILED'), $_SERVER['HTTP_REFERER']);
			}
		}elseif($_GET['id']){
			if($task['isclose'] == 1){
				alert('error',L('TASK_HAS_BEEN_CLOSED_CAN_NOT_MODIFY'),$_SERVER['HTTP_REFERER']);
			}
			if(is_array($task)){
				$task['owner_name'] = D('RoleView')->where('role.role_id in (%s)', '0'.$task['owner_role_id'].'0')->select();
				$task['creator'] = getUserByRoleId($task['creator_role_id']);
				$task['about_roles_id'] = D('RoleView')->where('role.role_id in (%s)', '0'.$task['about_roles'].'0')->select();
				$r_module = array('Business'=>'RBusinessTask', 'Customer'=>'RCustomerTask', 'Product'=>'RProductTask');
				foreach ($r_module as $key=>$value) {
					$r_m = M($value);
					
					if($module_id = $r_m->where('task_id = %d', trim($_GET['id']))->getField($key . '_id')){
						if($key == 'Leads') {
							$leads = M($key)->where($key.'_id = %d', $module_id)->find();
							$name = $leads['first_name'].$leads['last_name']. ' ' . $leads['company'];
						} else {
							$name = M($key)->where($key.'_id = %d', $module_id)->getField('name');
						}
						$module = M($key)->where($key.'_id = %d', $module_id)->find();
						$task['module']=array('module_name'=>$key,'name'=>$name,'module_id'=>$module_id);
						break;
					}
				}
				
				$this->task = $task;
				$this->alert = parseAlert();
				$this->display();
			} else {
				alert('error', L('TASK_NOT_EXIST'),$_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->error(L('PARAMETER_ERROR'));
		}
	}

	public function delete(){
		$m_task = M('Task');
		$r_module = array('Log'=>'RLogTask', 'File'=>'RFileTask', 'RMarketTask', 'RCustomerTask', 'RProductTask');
		if($this->isPost()){
			$task_ids = is_array($_POST['task_id']) ? implode(',', $_POST['task_id']) : '';
			if ('' == $task_ids) {
				alert('error', L('NOT CHOOSE ANY'),$_SERVER['HTTP_REFERER']);
			}

            if($m_task->where('task_id in (%s)', $task_ids)->delete()){
                foreach ($_POST['task_id'] as $value) {
                    foreach ($r_module as $key2=>$value2) {
                        $module_ids = M($value2)->where('task_id = %d', $value)->getField($key2 . '_id', true);
                        M($value2)->where('task_id = %d', $value) -> delete();
                        if(!is_int($key2)){
                            M($key2)->where($key2 . '_id in (%s)', implode(',', $module_ids))->delete();
                        }
                    }
                }
                alert('success', L('DELETED SUCCESSFULLY'),U('Task/index','by=deleted'));
            } else {
                alert('error', L('DELETE FAILED CONTACT THE ADMINISTRATOR'), $_SERVER['HTTP_REFERER']);
            }

		} elseif ($_GET['id']) {
			$task = $m_task->where('task_id = %d', $_GET['id'])->find();
			if (is_array($task)) {
                if($m_task->where('task_id = %d', $_GET['id'])->delete()){
                    foreach ($r_module as $key2=>$value2) {
                        $module_ids = M($value2)->where('task_id = %d', $_GET['id'])->getField($key2 . '_id', true);
                        M($value2)->where('task_id = %d', $_GET['id']) -> delete();
                        if(!is_int($key2)){
                            M($key2)->where($key2 . '_id in (%s)', implode(',', $module_ids))->delete();
                        }
                    }
                    if($_GET['redirect']){
                        alert('success', L('DELETED SUCCESSFULLY'),$_SERVER['HTTP_REFERER']);
                    } else {
                        alert('success', L('DELETED SUCCESSFULLY'), $_SERVER['HTTP_REFERER']);
                    }
                }else{
                    alert('error', L('DELETE FAILED CONTACT THE ADMINISTRATOR'), $_SERVER['HTTP_REFERER']);
                }
			} else {
				alert('error', L('TASK_NOT_EXIST'), $_SERVER['HTTP_REFERER']);
			}			
		} else {
			alert('error', L('SELECT_TASK_TO_DELETE'),$_SERVER['HTTP_REFERER']);
		}
	}
	
	public function index(){
		$m_user = M('user');
		$last_read_time_js = $m_user->where('role_id = %d', session('role_id'))->getField('last_read_time');
		$last_read_time = json_decode($last_read_time_js, true);
		$last_read_time['task'] = time();
		$m_user->where('role_id = %d', session('role_id'))->setField('last_read_time',json_encode($last_read_time));
		
		$by = isset($_GET['by']) ? trim($_GET['by']) : '';
		$p = isset($_GET['p']) ? intval($_GET['p']) : 1 ;
		$m_task = M('Task');
		$all_ids = getSubRoleId();
		$where = array();
        $where['league_id'] = session("league_id");

		$params = array();
		$order = "create_date desc";
		if($_GET['desc_order']){
			$order = trim($_GET['desc_order']).' desc';
		}elseif($_GET['asc_order']){
			$order = trim($_GET['asc_order']).' asc';
		}
		
		switch ($by) {
			case 'create' : $where['creator_role_id'] = session('role_id');break;
			case 's1' : $where['status'] = '接受';  break;
			case 's2' : $where['status'] = '完成';  break;
			case 's3' : $where['status'] = '拒绝';  break;
            case 's4' : $where['status'] = '未处理';  break;
            case 'sd' : $where['priority'] = '一般性任务';  break;
            case 'jj' : $where['priority'] = '紧急任务';  break;
            case 'closed' : $where['isclose'] = '1';  break;
            case 'today' :
				$where['end_time'] =  array('between',array(strtotime(date('Y-m-d')) -1 ,strtotime(date('Y-m-d')) + 86400));
				break;
			case 'week' : 
				$week = (date('w') == 0)?7:date('w');
				$where['end_time'] =  array('between',array(strtotime(date('Y-m-d')) - ($week-1) * 86400 -1 ,strtotime(date('Y-m-d')) + (8-$week) * 86400));
				break;
			case 'month' : 
				$next_year = date('Y')+1;
				$next_month = date('m')+1;
				$month_time = date('m') ==12 ? strtotime($next_year.'-01-01') : strtotime(date('Y').'-'.$next_month.'-01');
				$where['end_time'] = array('between',array(strtotime(date('Y-m-01')) -1 ,$month_time));
				break;
			case 'add' : $order = 'create_date desc';  break;
			case 'update' : $order = 'update_date desc';  break;
			case 'me' : $where['_string'] = 'about_roles like "%,'.session('role_id').',%" OR owner_role_id like "%,'.session('role_id').',%"'; break;
			default :  $where['_string'] = 'creator_role_id in ('.implode(',', $all_ids).')  OR about_roles like "%,'.session('role_id').',%" OR owner_role_id like "%,'.session('role_id').',%"'; break;
		}

		if (!isset($where['_string'])  && !isset($where['creator_role_id'])){
			$where['_string'] = ' about_roles like "%,'.session('role_id').',%" OR owner_role_id like "%,'.session('role_id').',%" OR creator_role_id in ('.implode(',', $all_ids).') ';
		}
		if ($_REQUEST["field"]) {
			$field = trim($_REQUEST['field']) == 'all' ? 'subject|status|priority|description|due_date' : $_REQUEST['field'];
			$search = empty($_REQUEST['search']) ? '' : trim($_REQUEST['search']);
			$condition = empty($_REQUEST['condition']) ? 'is' : trim($_REQUEST['condition']);
			if	('due_date' == $field || $field == 'update_date' || $field == 'create_date') {
				$search = is_numeric($search)?$search:strtotime($search);
			}
			switch ($condition) {
				case "is" : if($field == 'owner_role_id'){
								$where[$field] = array('like','%,'.$search.',%');
							}else{
								$where[$field] = array('eq',$search);
							}
							break;
				case "isnot" :  $where[$field] = array('neq',$search);break;
				case "contains" :  $where[$field] = array('like','%'.$search.'%');break;
				case "not_contain" :  $where[$field] = array('notlike','%'.$search.'%');break;
				case "start_with" :  $where[$field] = array('like',$search.'%');break;
				case "end_with" :  $where[$field] = array('like','%'.$search);break;
				case "is_empty" :  $where[$field] = array('eq','');break;
				case "is_not_empty" :  $where[$field] = array('neq','');break;
				case "gt" :  $where[$field] = array('gt',$search);break;
				case "egt" :  $where[$field] = array('egt',$search);break;
				case "lt" :  $where[$field] = array('lt',$search);break;
				case "elt" :  $where[$field] = array('elt',$search);break;
				case "eq" : $where[$field] = array('eq',$search);break;
				case "neq" : $where[$field] = array('neq',$search);break;
				case "between" : $where[$field] = array('between',array($search-1,$search+86400));break;
				case "nbetween" : $where[$field] = array('not between',array($search,$search+86399));break;
				case "tgt" :  $where[$field] = array('gt',$search+86400);break;
				default : $where[$field] = array('eq',$search);
			}
			$params = array('field='.$field, 'condition='.$condition, 'search='.trim($_REQUEST['search']));
		}
		
		$order = empty($order) ? 'due_date asc' : $order;
		$task_list = $m_task->where($where)->order($order)->page($p.',15')->select();
		$count = $m_task->where($where)->count();
		
		import("@.ORG.Page");
		$Page = new Page($count,15);
		if (!empty($_GET['by'])) {
			$params[]=   "by=".trim($_GET['by']);
		}
		
		$this->parameter = implode('&', $params);
		if ($_GET['desc_order']) {
			$params[] = "desc_order=" . trim($_GET['desc_order']);
		} elseif($_GET['asc_order']){
			$params[] = "asc_order=" . trim($_GET['asc_order']);
		}
		
		$Page->parameter = implode('&', $params);
		$this->assign('page', $Page->show());
		
		foreach ($task_list as $key=>$value) {
			$task_list[$key]['owner'] = D('RoleView')->where('role.role_id in (%s)', '0'.$task_list[$key]['owner_role_id'].'0')->select();
			$task_list[$key]['creator'] = getUserByUserId($value['creator_role_id']);
            $role_trunk = get_trunk(session('role_id'));
            array_shift($role_trunk);
            $task_list[$key]['leader'] = in_array($value['creator_role_id'],$role_trunk);
			$r_module = array('Market'=>'RMarketTask', 'Customer'=>'RCustomerTask', 'Product'=>'RProductTask');
			foreach ($r_module as $k=>$v) {
				$r_m = M($v);
				if($module_id = $r_m->where('task_id = %d', $value['task_id'])->getField($k . '_id')){
					switch ($k){
						case 'Product' : $module_name= L('PRODUCT');
                            $name = M($k)->where($k.'_id = %d', $module_id)->getField('name');
                            $name_str = msubstr($name,0,20,'utf-8',false);
							$name = '<a href="index.php?m=product&a=view&id='.$module_id.'" title="'.$name.'">'.$name_str.'</a>';
							break;
						case 'Market' : $module_name= "客户服务";
                            $name = M($k)->where($k.'_id = %d', $module_id)->getField('idcode');
                            $name_str = msubstr($name,0,20,'utf-8',false);
							$name = '<a href="index.php?m=market&a=view&id='.$module_id.'" title="'.$name.'">'.$name_str.'</a>';
						break;
						case 'Customer' : $module_name= L('CUSTOMER');
                            $name = M($k)->where($k.'_id = %d', $module_id)->getField('name');
                            $name_str = msubstr($name,0,20,'utf-8',false);
							$name = '<a href="index.php?m=customer&a=view&id='.$module_id.'" title="'.$name.'">'.$name_str.'</a>';
						break;
					}
					$task_list[$key]['module']=array('module'=>$k,'module_name'=>$module_name,'name'=>$name,'module_id'=>$module_id);
					break;
				}
			}
			$due_time = $task_list[$key]['due_date'];
			if($due_time){
				$tomorrow_time = strtotime(date('Y-m-d', time()))+86400;
				$diff_days = ($due_time-$tomorrow_time)%86400>0 ? intval(($due_time-$tomorrow_time)/86400)+1 : intval(($due_time-$tomorrow_time)/86400);
				$task_list[$key]['diff_days'] = $diff_days;
			}
		}
		
		$this->task_list = $task_list;
		$this->alert = parseAlert();
		$this->display();
	}
	
	public function view() {
		$task_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		//if($task_id && !check_permission($task_id, 'task')) alert('error',L('HAVE NOT PRIVILEGES'),U('task/index')); 
		if (0 == $task_id) {
			alert('error', L('PARAMETER_ERROR'), U('task/index'));
		} else {

			$m_task = M('Task');
			$task = $m_task->where('task_id = %d',$task_id)->find();
			$owner_role_id = in_array(session('role_id'),explode(',',$task['owner_role_id']));
			$about_roles = in_array(session('role_id'),explode(',',$task['about_roles']));
			$res = in_array($task['creator_role_id'],getSubRoleId(false));
			if($owner_role_id || $about_roles || $res || session('?admin')){
				$task['owner'] = D('RoleView')->where('role.role_id in (%s)', '0'.$task['owner_role_id'].'0')->select();
				$task['creator'] = getUserByRoleId($task['creator_role_id']);
				$task['about_roles'] = D('RoleView')->where('role.role_id in (%s)', '0'.$task['about_roles'].'0')->select();
                $task['leader'] = in_array($task['creator_role_id'],get_trunk(session('role_id')));

                $r_module = array('Market'=>'RMarketTask', 'Contacts'=>'RContactsTask', 'Customer'=>'RCustomerTask', 'Product'=>'RProductTask','Leads'=>'RLeadsTask');
				foreach ($r_module as $key=>$value) {
					$r_m = M($value);
					if($module_id = $r_m->where('task_id = %d', $task_id)->getField($key . '_id')){			
						if($key == 'Leads') {
							$leads = M($key)->where($key.'_id = %d', $module_id)->find();
							$name = $leads['first_name'].$leads['last_name'].$leads['saltname'].' ' . $leads['company'];
						} else {
							$name = M($key)->where($key.'_id = %d', $module_id)->getField('name');
						}
						switch ($key){
							case 'Product' : $module_name= L('PRODUCT'); break;
							case 'Market' : $module_name= "客户服务"; break;
							case 'Customer' : $module_name= L('CUSTOMER'); break;
						}
						$task['module']=array('module'=>$key,'module_name'=>$module_name,'name'=>$name,'module_id'=>$module_id);
						break;
					}
				}
			
				$log_ids = M('rLogTask')->where('task_id = %d', $task_id)->getField('log_id', true);
				$task['log'] = M('log')->where('log_id in (%s)', implode(',', $log_ids))->select();
				$log_count = 0;
				foreach ($task['log'] as $key=>$value) {
					$task['log'][$key]['owner'] = D('RoleView')->where('role.role_id = %d', $value['role_id'])->find();
					$file_ids = M('rFileLog')->where('log_id = %d', $value['log_id'])->getField('file_id', true);
					$task['log'][$key]['files'] = M('file')->where(array('file_id'=>array('in',$file_ids)))->select();
					foreach($task['log'][$key]['files'] as $fk=>$fv){
						$task['log'][$key]['files'][$fk]['subName'] = mb_substr($fv['name'],0,30,'utf-8');
					}
					if ($key%2==0) $task['log'][$key]['style'] = 'warning';
					else $task['log'][$key]['style'] = 'info';
					$log_count ++;
				}
				$task['log_count'] = $log_count;
				
				if (in_array($task['owner_role_id'], getSubRoleId(false))) {
					if(!($task['comment_role_id'] > 0)){
						$this->comment_role_id = session('role_id');
					}
				}
				
				$this->comment_list = D('CommentView')->where('module = "task" and module_id = %d', $task['task_id'])->order('comment.create_time desc')->select();
				$this->task = $task;
				$this->alert = parseAlert();
				$this->display();
			}else{
				alert('error',L('HAVE NOT PRIVILEGES'),U('task/index')); 
			}
		}
	}
	
	public function close(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0; 
		if ($id >= 0) {
			$m_task = M('task');
			$task = $m_task->where('task_id = %d',$id)->find();
			if ((is_array($task) && !empty($task)) || session('?admin')) {
				if($m_task->where('task_id = %d', $id)->setField('isclose', 1)){
                    if ($task['taskcb'] && ($taskcb = unserialize($task['taskcb']))){
                        if ($taskcb["关闭"]) {
                            sendRequest($taskcb["关闭"]);
                        }
                    }
					alert('success', L('CLOSED_SUCCESS'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('FAIL_TO_CLOSE_TASK'), $_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error', L('HAVE_NO_RIGHTS_TO_CLOSE_TASK'), $_SERVER['HTTP_REFERER']);
			}
		}else{
			alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
		}
	}
	
	/**
	*开启任务
	*
	**/
	public function open(){
		$id = isset($_GET['id']) ? $_GET['id'] : 0; 
		if ($id >= 0) {
			$m_task = M('task');
			$task = $m_task->where('task_id = %d and creator_role_id = %d',$id,session('role_id'))->find();
			if ((is_array($task) && !empty($task))|| session('?admin')) {
				if($m_task->where('task_id = %d', $id)->setField('isclose', 0)){
					alert('success', L('OPEN_SUCCESS'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('OPEN_FAILURE'), $_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error', L('DO NOT HAVE PRIVILEGES'), $_SERVER['HTTP_REFERER']);
			}
		}else{
			alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
		}
	}
	
	public function listDialog(){
		$m_task = M('task');
		$all_ids = getSubRoleId();
		$where['_string'] = 'creator_role_id in ('.implode(',', $all_ids).')  OR about_roles like "%,'.session('role_id').',%" OR owner_role_id like "%,'.session('role_id').',%"';
		$where['is_deleted'] = 0;
		$where['isclose'] = 0;
        $where['league_id'] = session("league_id");

        $list = $m_task->where($where)->order('due_date desc')->limit('10')->select();
		foreach ($list as $key=>$value) {
			$list[$key]['owner'] = D('RoleView')->where('role.role_id in (%s)', '0'.$value['owner_role_id'].'0')->select();
			$list[$key]['creator'] = getUserByRoleId($value['creator_role_id']);
			$list[$key]['deletor'] = getUserByRoleId($value['delete_role_id']);
			//关联模块
			$r_module = array('Business'=>'RMarketTask', 'Contacts'=>'RContactsTask', 'Customer'=>'RCustomerTask', 'Product'=>'RProductTask','Leads'=>'RLeadsTask');
			foreach ($r_module as $k=>$v) {
				$r_m = M($v);
				if($module_id = $r_m->where('task_id = %d', $value['task_id'])->getField($k . '_id')){			
					$name = M($k)->where($k.'_id = %d', $module_id)->getField('name');
					$is_deleted = M($k)->where($k.'_id = %d', $module_id)->getField('is_deleted');
					$name_str = msubstr($name,0,20,'utf-8',false);
					$name_str .= $is_deleted == 1 ? '<font color="red">('.L("DELETED").')</font>' : '';
					switch ($k){
						case 'Product' : $module_name= L('PRODUCT'); 
							$name = '<a target="_blank" href="index.php?m=product&a=view&id='.$module_id.'" title="'.$name.'">'.$name_str.'</a>';
							break;
						case 'Market' : $module_name= "客户服务";
							$name = '<a target="_blank" href="index.php?m=market&a=view&id='.$module_id.'" title="'.$name.'">'.$name_str.'</a>';
						break;
						case 'Customer' : $module_name= L('CUSTOMER'); 
							$name = '<a target="_blank" href="index.php?m=customer&a=view&id='.$module_id.'" title="'.$name.'">'.$name_str.'</a>';
						break;
					}
					$list[$key]['module']=array('module'=>$k,'module_name'=>$module_name,'name'=>$name,'module_id'=>$module_id);
					break;
				}
			}
		}
		$this->task_list = $list;
		$count = $m_task->where($where)->count();
		$this->total = $count%10 > 0 ? ceil($count/10) : $count/10;
		$this->count_num = $count;
		$this->display();
	}
	
	public function changecontent(){
		$by = isset($_GET['by']) ? trim($_GET['by']) : '';
		$p = isset($_GET['p']) ? intval($_GET['p']) : 1 ;
		$m_task = M('Task');
		$all_ids = getSubRoleId();
		$where = array();
		$params = array();
		$order = "";
		$where['is_deleted'] = 0;
		$where['isclose'] = 0;
		$where['_string'] = 'creator_role_id in ('.implode(',', $all_ids).')  OR about_roles like "%,'.session('role_id').',%" OR owner_role_id like "%,'.session('role_id').',%"';
        $where['league_id'] = session("league_id");

        if ($_REQUEST["field"]) {
			$field = trim($_REQUEST['field']) == 'all' ? 'subject|status|priority|description|due_date' : $_REQUEST['field'];
			$search = empty($_REQUEST['search']) ? '' : trim($_REQUEST['search']);
			$condition = empty($_REQUEST['condition']) ? 'is' : trim($_REQUEST['condition']);
			if	('due_date' == $field || $field == 'update_date' || $field == 'create_date') {
				$search = is_numeric($search)?$search:strtotime($search);
			}
			switch ($condition) {
				case "is" : $where[$field] = array('eq',$search);break;
				case "isnot" :  $where[$field] = array('neq',$search);break;
				case "contains" :  $where[$field] = array('like','%'.$search.'%');break;
				case "not_contain" :  $where[$field] = array('notlike','%'.$search.'%');break;
				case "start_with" :  $where[$field] = array('like',$search.'%');break;
				case "end_with" :  $where[$field] = array('like','%'.$search);break;
				case "is_empty" :  $where[$field] = array('eq','');break;
				case "is_not_empty" :  $where[$field] = array('neq','');break;
				case "gt" :  $where[$field] = array('gt',$search);break;
				case "egt" :  $where[$field] = array('egt',$search);break;
				case "lt" :  $where[$field] = array('lt',$search);break;
				case "elt" :  $where[$field] = array('elt',$search);break;
				case "eq" : $where[$field] = array('eq',$search);break;
				case "neq" : $where[$field] = array('neq',$search);break;
				case "between" : $where[$field] = array('between',array($search-1,$search+86400));break;
				case "nbetween" : $where[$field] = array('not between',array($search,$search+86399));break;
				case "tgt" :  $where[$field] = array('gt',$search+86400);break;
				default :	if($field == 'owner_role_id'){
								$where[$field] = array('like','%,'.$search.',%');
							}else{
								$where[$field] = array('eq',$search);
							}
							break;
			}
			$params = array('field='.$field, 'condition='.$condition, 'search='.trim($_REQUEST['search']));
		}
		$p = !$_REQUEST['p']||$_REQUEST['p']<=0 ? 1 : intval($_REQUEST['p']);
		$order = empty($order) ? 'due_date asc' : $order;
		$task_list = $m_task->where($where)->order($order)->page($p.',15')->select();
		$count = $m_task->where($where)->count();
		
		foreach ($task_list as $key=>$value) {
			$task_list[$key]['owner'] = D('RoleView')->where('role.role_id in (%s)', '0'.$value['owner_role_id'].'0')->select();
			$task_list[$key]['creator'] = getUserByRoleId($value['creator_role_id']);
			$task_list[$key]['deletor'] = getUserByRoleId($value['delete_role_id']);
			//关联模块
			$r_module = array('Business'=>'RBusinessTask', 'Contacts'=>'RContactsTask', 'Customer'=>'RCustomerTask', 'Product'=>'RProductTask','Leads'=>'RLeadsTask');
			foreach ($r_module as $k=>$v) {
				$r_m = M($v);
				if($module_id = $r_m->where('task_id = %d', $value['task_id'])->getField($k . '_id')){			
					
					$name = M($k)->where($k.'_id = %d', $module_id)->getField('name');
					$is_deleted = M($k)->where($k.'_id = %d', $module_id)->getField('is_deleted');
					$name_str = msubstr($name,0,20,'utf-8',false);
					$name_str .= $is_deleted == 1 ? '<font color="red">('.L("DELETED").')</font>' : '';
					switch ($k){
						case 'Product' : $module_name= L('PRODUCT'); 
							$name = '<a href="index.php?m=product&a=view&id='.$module_id.'" title="'.$name.'">'.$name_str.'</a>';
							break;
						case 'Market' : $module_name= "客户服务";
							$name = '<a href="index.php?m=market&a=view&id='.$module_id.'" title="'.$name.'">'.$name_str.'</a>';
						break;
						case 'Customer' : $module_name= L('CUSTOMER'); 
							$name = '<a href="index.php?m=customer&a=view&id='.$module_id.'" title="'.$name.'">'.$name_str.'</a>';
						break;
					}
					$task_list[$key]['module']=array('module'=>$k,'module_name'=>$module_name,'name'=>$name,'module_id'=>$module_id);
					break;
				}
			}
			$due_time = $task_list[$key]['due_date'];
			if($due_time){
				$tomorrow_time = strtotime(date('Y-m-d', time()))+86400;
				$diff_days = ($due_time-$tomorrow_time)%86400>0 ? intval(($due_time-$tomorrow_time)/86400)+1 : intval(($due_time-$tomorrow_time)/86400);
				$task_list[$key]['diff_days'] = $diff_days;
			}
		}
		
		$data['list'] = $task_list;
		$data['p'] = $p;
		$data['count'] = $count;
		$data['total'] = $count%10 > 0 ? ceil($count/10) : $count/10;
		$this->ajaxReturn($data,"",1);
	}

	public function analytics(){
		$m_task = M('Task');
		if($_GET['role']) {
			$role_id = intval($_GET['role']);
		}else{
			$role_id = 'all';
		}
		if($_GET['department'] && $_GET['department'] != 'all'){
			$department_id = intval($_GET['department']);
		}else{
			$department_id = D('RoleView')->where('role.role_id = %d', session('role_id'))->getField('department_id');
		}
		if($_GET['start_time']) $start_time = strtotime($_GET['start_time']);
		$end_time = $_GET['end_time'] ?  strtotime($_GET['end_time']) : time();
		if($role_id == "all") {
			$roleList = getRoleByDepartmentId($department_id);
			$role_id_array = array();
			foreach($roleList as $v){
				$role_id_array[] = '%,'.$v['role_id'].',%';
			}
			$where_completion['owner_role_id'] = array('like',$role_id_array,'or');
		}else{
			$where_completion['owner_role_id'] = array('like','%,'.$role_id.',%');
		}
		if($start_time){
			$where_create_time = array(array('lt',$end_time),array('gt',$start_time), 'and');
			$where_completion['create_time'] = $where_create_time;
		}else{
			$where_completion['create_time'] = array('lt',$end_time);
		}
		
		$completion_count_array = array();
		$statusList = array(L('NOT_START'), L('DELAY'), L('ONGOING'), L('COMPLETE'));
		$where_completion['is_deleted'] = 0;
		$where_completion['isclose'] = 0;
		foreach($statusList as $v){
			$where_completion['status'] = $v;
			$target_count = $m_task ->where($where_completion)->count();
			$completion_count_array[] = '['.'"'.$v.'",'.$target_count.']';
		}
		$this->completion_count = implode(',', $completion_count_array);
		
		$role_id_array = array();
		if($role_id == "all"){
			if($department_id != "all"){
				$roleList = getRoleByDepartmentId($department_id);
				foreach($roleList as $v){
					$role_id_array[] = $v['role_id'];
				}
			}else{
				$role_id_array = getSubRoleId();
			}
		}else{
			$role_id_array[] = $role_id;
		}
		if($start_time){
			$create_time= array(array('lt',$end_time),array('gt',$start_time), 'and');
		}else{
			$create_time = array('lt',$end_time);
		}
		
		$own_count_total = 0;
		$new_count_total = 0;
		$late_count_total = 0;
		$deal_count_total = 0;
		$success_count_total = 0;
		$busi_customer_array = M('Business')->getField('customer_id', true);
		$busi_customer_id=implode(',', $busi_customer_array);
		foreach($role_id_array as $v){
			$user = getUserByRoleId($v);
			$owner_role_id = array('like', '%,'.$v.',%');
			$own_count = $m_task->where(array('is_deleted'=>0,'isclose'=>0, 'owner_role_id'=>$owner_role_id, 'create_date'=>$create_time))->count();
			$new_count = $m_task->where(array('is_deleted'=>0,'isclose'=>0,'status'=>L('NOT_START'), 'owner_role_id'=>$owner_role_id, 'create_date'=>$create_time))->count();
			$late_count = $m_task->where(array('is_deleted'=>0,'isclose'=>0,'status'=>L('DELAY'), 'owner_role_id'=>$owner_role_id, 'create_date'=>$create_time))->count();
			$deal_count = $m_task->where(array('is_deleted'=>0,'isclose'=>0,'status'=>L('ONGOING'), 'owner_role_id'=>$owner_role_id, 'create_date'=>$create_time))->count();
			$success_count =  $m_task->where(array('is_deleted'=>0,'isclose'=>0,'status'=>L('COMPLETE'), 'owner_role_id'=>$owner_role_id, 'create_date'=>$create_time))->count();
			
			$reportList[] = array("user"=>$user,"new_count"=>$new_count,"late_count"=>$late_count,"own_count"=>$own_count,"success_count"=>$success_count,"deal_count"=>$deal_count);
			$late_count_total += $late_count;
			$own_count_total += $own_count;
			$success_count_total += $success_count;
			$deal_count_total += $deal_count;
			$new_count_total += $new_count;
		}
		$this->total_report = array("new_count"=>$new_count_total,"late_count"=>$late_count_total, "own_count"=>$own_count_total, "success_count"=>$success_count_total, "deal_count"=>$deal_count_total);
		$this->reportList = $reportList;
		
		$idArray = getSubRoleId();
		$roleList = array();
		foreach($idArray as $roleId){				
			$roleList[$roleId] = getUserByRoleId($roleId);
		}
		$this->roleList = $roleList;
		
		$departments = M('roleDepartment')->select();
		$department_id = D('RoleView')->where('role.role_id = %d', session('role_id'))->getField('department_id');
		$departmentList[] = M('roleDepartment')->where('department_id = %d', $department_id)->find();$departmentList = array_merge($departmentList, getSubDepartment($department_id,$departments,''));
		$this->assign('departmentList', $departmentList);
		$this->display();
	}
	
	public function tips(){
		$m_task = M('Task');
        $where = array(
            'owner_role_id'=>session('role_id'),
            'status'=>array("eq", "接受")
        );
		$num = $m_task->where($where)->count();
		$this->ajaxReturn($num,"",1);
	}

}