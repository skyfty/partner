<?php 
/**
 * User Related
 * 用户相关模块
 *
 **/ 

class UserAction extends Action {
	public function _initialize(){
		$action = array(
			'permission'=>array(
                'login',
                'lostpw',
                'resetpw',
                'active',
                'weixinbinding',
                'notice',
                'verify',
                'associate',
                'submit_associate',
                'getrolelist',
                'getallrolelist',
			    'listdialog',
                'getpositionlist'
            ),
			'allow'=>array(
                'logout',
                'role_ajax_add',
                'getrolebydepartment',
                'dialoginfo',
                'edit',
                'listdialog',
                'mutilistdialog',
                'getrolelist',
                'getpositionlist',
                'weixin',
                'changecontent'
            )
		);
		B('Authenticate', $action);
	}

	//登录
	public function login() {
        $m_announcement = M('announcement');
        $where['status'] = array('eq', 1);
		$where['isshow'] = array('eq', 1);
		$this->announcement_list = $m_announcement->where($where)->order('order_id')->select();

        if (session('?name')){
            $this->redirect('index/index',array(), 0, '');
		}elseif($_POST['submit']){
            $league_id = $_POST['league']?$_POST['league']:"0";
			if((!isset($_POST['name']) || $_POST['name'] =='')||(!isset($_POST['password']) || $_POST['password'] =='')){
				alert('error', L('INVALIDATE_USER_NAME_OR_PASSWORD'), U('user/login'));
			}elseif (isset($_POST['name']) && $_POST['name'] != ''){
				$m_user = M('user');
                $where = array(
                    'name' => trim($_POST['name'])
                );

                if ($league_id != "0") {
                    $league = M("league")->where(array("code"=>$league_id))->find();
                    if (!$league) {
                        alert('error', L('INVALIDATE_USER_NAME_OR_PASSWORD'), U('user/login'));
                    }
                    $league_id=$league['league_id'];
                }
                $where['league_id'] = $league_id;

				$user = $m_user->where($where)->find();
                if (!$user) {
                    alert('error', L('INCORRECT_USER_NAME_OR_PASSWORD'));
                }

				$passt = md5(trim($_POST['password']));
				$pass =  md5($passt. $user['salt']);
				do{
					if ($user['password'] != $pass) {
						alert('error', L('INCORRECT_USER_NAME_OR_PASSWORD'));break;
					}

					if (-1 == $user['status']) {
						alert('error', L('YOU_ACCOUNT_IS_UNAUDITED'));break;
					} elseif (0 == $user['status']) {
						alert('error', L('YOU_ACCOUNT_IS_AUDITEDING'));break;
					}elseif (2 == $user['status']) {
						alert('error', L('YOU_ACCOUNT_IS_DISABLE'));break;
					}

					$role = D('RoleView')->where('user.user_id = %d', $user['user_id'])->find();
					if ($_POST['autologin'] == 'on') {
						session(array('expire'=>259200));
						cookie('user_id',$user['user_id'],259200);
						cookie('name',$user['name'],259200);
                        cookie('league_id',$user['league_id'],259200);
                        cookie('league_name',$user['league_name'],259200);
                        cookie('salt_code',md5(md5($user['user_id'] . $user['name']).$user['salt']),259200);
					}else{
						session(array('expire'=>3600));
					}

					if (!is_array($role) || empty($role)) {
						alert('error', L('HAVE_NO_POSITION'));break;
					}

					if($user['category_id'] == 1){
						session('admin', 1);
					}
					session('role_id', $role['role_id']);
					session('position_id', $role['position_id']);
					session('role_name', $role['role_name']);
					session('department_id', $role['department_id']);
					session('name', $user['name']);
					session('user_id', $user['user_id']);
                    session('league_id', $role['league_id']);
                    session('league_name', $role['league_name']);

					$m_branch = D("BranchCategoryView")->where(array("branch_category.role_id"=>$role['role_id']))->find();
					if ($m_branch) {
						session('authority', $m_branch['authority']);
						session('branch_id', $m_branch['branch_id']);
						session('restriction', $m_branch['restriction'] == "打开");
						session('shopkeeper', $m_branch['shopkeeper_role_id'] == $role['role_id']);
					}
					alert('success', L('LOGIN_SUCCESS'), U('Index/index'));
				}while(false);
			}
            $this->leagues = M("league")->where(array("status"=>1))->select();
            $this->alert = parseAlert();
            $this->display();
		}else{
            $this->leagues = M("league")->where(array("status"=>1))->select();
			$this->alert = parseAlert();
            $this->display();
		}
	}

	//密码重置
	public function resetpw(){
		$verify_code = trim($_REQUEST['verify_code']);
		$user_id = intval($_REQUEST['user_id']);
		$m_user = M('User');
		$user = $m_user->where('user_id = %d', $user_id)->find();
		
		// 手动进行令牌验证
		if (!$m_user->autoCheckToken($_POST)){		
			$this->error(L('FORM_REPEAT_SUBMIT'), U('user/login'));
		}
		if (is_array($user) && !empty($user)) {
			if ((time()-$user['lostpw_time'])>86400){
				alert('error', L('LINK_DISABLE_PLEASE_FIND_PASSWORD_AGAIN'),U('user/lostpw'));
			}elseif (md5(md5($user['lostpw_time']) . $user['salt']) == $verify_code) {
				if ($_REQUEST['password']) {
					if (!is_hefa_password($_REQUEST['password'])) {
						alert('error',"不合法的密码， 密码必须至少包含数字和密码的8位字符", $_SERVER['HTTP_REFERER']);
					}
					$password = md5(md5(trim($_REQUEST["password"])) . $user['salt']);
					$m_user->where('user_id =' . $_REQUEST['user_id'])->save(array('password'=>$password, 'lostpw_time'=>0));
					alert('success', L('EDIT_PASSWORD_SUCCESS_PLEASE_LOGIN'), U('user/login'));
				} else {
					$this->alert = parseAlert();
					$this->display();
				}
			} else{
				$this->error(L('FIND_PASSWORD_LINK_DISABLE'));
			}		
		} else {
			$this->error(L('FIND_PASSWORD_LINK_DISABLE'));
		}
	}
	
	//退出
	public function logout() {
		session(null);
		cookie('user_id',null);
		cookie('name',null);
		cookie('salt_code',null);
		$this->success(L('LOGIN_OUT_SUCCESS'), U('User/login'));
	}
	
	public function listDialog() {
		//1表示所有人  2表示下属
		if($_GET['by'] == 'task'){
			$all_or_below = C('defaultinfo.task_model') == 2 ? 1 : 0;
		}else{
			$all_or_below = $_GET['by'] == 'all' ? 1 : 0;
		}
		$d_role_view = D('RoleView');
		$where = '';
		$all_role = M('role')->cache(true)->where('user_id <> 0')->select();
		$below_role = getSubRole(session('role_id'), $all_role);
		if(!$all_or_below){
			$below_ids[] = session('role_id');
			foreach ($below_role as $key=>$value) {
				$below_ids[] = $value['role_id'];
			}
			$where = 'role.role_id in ('.implode(',', $below_ids).')';
		}
		$this->role_list = $d_role_view->cache(true)->where($where)->select();
		$this->display();
	}
	
	public function mutiListDialog(){
		//1表示所有人  2表示下属
		if($_GET['by'] == 'task'){
			$all_or_below = C('defaultinfo.task_model') == 2 ? 1 : 0;
		}else{
			$all_or_below = $_GET['by'] == 'all' ? 1 : 0;
		}
		$d_role = D('RoleView');
		$sub_role_id = getSubRoleId(false);
		$departments_list = M('roleDepartment')->cache(true)->select();
		foreach($departments_list as $k=>$v){
			$where = array();
			if(!$all_or_below)
				$where['role_id'] = array('in', $sub_role_id);
			$where['position.department_id'] =  $v['department_id'];
			$roleList = $d_role->cache(true)->where($where)->select();
			$departments_list[$k]['user'] = $roleList;
		}
		$this->departments_list = $departments_list;
		$this->display();
	}

	//修改自己的信息
	public function edit(){
		if ($this->isPost()) {
            if(!session('?admin') && session('user_id') != $_POST['user_id']){
                alert('error',L('YOU_DO_NOT_HAVE_THIS_RIGHT'),$_SERVER['HTTP_REFERER']);
            }

            if ($_POST['idcode'] && M('user')->where(array('user_id'=>array("neq", $_POST['user_id']), "idcode"=>$_POST['idcode']))->find()) {
                alert('error',"工号已经存在", $_SERVER['HTTP_REFERER']);
            }

			if(isset($_POST['password']) && $_POST['password']!=''){
				if (!is_hefa_password($_POST['password'])) {
					alert('error',"不合法的密码， 密码必须至少包含数字和密码的8位字符", $_SERVER['HTTP_REFERER']);
				}
			}

            $m_user = M('user');
			$m_role = M('role');
			$user=M('user')->where('user_id = %d', $_POST['user_id'])->find();
			if ($m_user->create()) {
				if(isset($_POST['password']) && $_POST['password']!=''){
					$m_user->password = md5(md5(trim($_POST["password"])) . $user['salt']);
				} else {
					unset($m_user->password);
				}
				$is_update = false;
                if(session('?admin')){
					$is_update = $m_role->where('user_id = %d', $_POST['user_id'])->setField('position_id', $_POST['position_id']);
				}else{
                    unset($m_user->category_id);
                    unset($m_user->name);
                }
				if($m_user->save() || $is_update){
					delete_cache_temp();
					actionLog($_POST['user_id']);
					alert('success',L('EDIT_USER_INFO_SUCCESS'),U('user/index'));
				}else{
					alert('error',L('USER_INFO_NOT_CHANGE'),$_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error',L('EDIT_USER_INFO_FAILED'),$_SERVER['HTTP_REFERER']);
			}
		}else{
			$user_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : session('user_id');
            if(!session('?admin') && session('user_id') != $user_id){
                alert('error',L('YOU_DO_NOT_HAVE_THIS_RIGHT'),$_SERVER['HTTP_REFERER']);
            }
			$d_user = D('RoleView');
			$user = $d_user->where('user.user_id = %d', $user_id)->find();
			$user['category'] = M('user_category')->where('category_id = %d', $user['category_id'])->getField('name');
			$this->categoryList = M('user_category')->select();
			$status_list = array(L('INACITVE'),L('ACITVE'),L('DISABLE'));
			$this->assign('statuslist', $status_list);
			if($user['department_id']){
				$this->position_list = M('position')->where('department_id = %d', $user['department_id'])->select();
			}
			$department_list = getSubDepartment(0, M('role_department')->select());
			$this->assign('department_list', $department_list);
			$this->user = $user;
			$this->alert = parseAlert();
			$this->display();
		}
	}
	
	public function dialogInfo(){
		$role_id = intval($_REQUEST['id']);
		$role = D('RoleView')->where('role.role_id = %d', $role_id)->cache(true)->find();
		$user = M('user')->where('user_id = %d', $role['user_id'])->cache(true)->find();
		$user[role] = $role;
		$this->user = $user;
		$this->categoryList = M('user_category')->cache(true)->select();
		$this->alert = parseAlert();
		$this->display();
	}
	
	public function changeContent(){
		if($this->isAjax()){
            $department_id = $this->_get('department');
            if($department_id == 'all'){
                $department_id = 1;
            }
			if($this->_get('name','trim') == ''){
				$data['list'] = getRoleByDepartmentId($department_id);
				$count = count($data['list']);
			}else{
				$d_role_view = D('RoleView');
				$where['user.name'] = array('like', '%'.trim($_GET['name']).'%');
                 if($department_id != 1){
                    $where['position.department_id'] = $department_id;
                }
				$list = $d_role_view->where($where)->cache(true)->select();
				$count = $d_role_view->where($where)->count();
				$data['list'] = $list;
			}
			$p = !$_REQUEST['p']||$_REQUEST['p']<=0 ? 1 : intval($_REQUEST['p']);
			$data['p'] = $p;
			$data['count'] = $count;
			$data['total'] = $count%10 > 0 ? ceil($count/10) : $count/10;
			$this->ajaxReturn($data, '', 1);
		}
	}

	public function add(){
		$m_role = M('Role');
		$m_user = D('User');
		if ($this->isPost()){
			if(isset($_POST['password']) && $_POST['password']!=''){
				if (!is_hefa_password($_POST['password'])) {
					alert('error',"不合法的密码， 密码必须至少包含数字和密码的8位字符", $_SERVER['HTTP_REFERER']);
				}
			}
			$m_user->create();

            //填写密码
            if (!isset($_POST['name']) || $_POST['name'] == '') {
                alert('error', L('INPUT_USER_NAME'), $_SERVER['HTTP_REFERER']);
            } elseif (!isset($_POST['password']) || $_POST['password'] == ''){
                alert('error', L('INPUT_PASSWORD'), $_SERVER['HTTP_REFERER']);
            } elseif (!isset($_POST['category_id']) || $_POST['category_id'] == ''){
                alert('error', L('PLEASE_SELECT_USER_CATEGORY'), $_SERVER['HTTP_REFERER']);
            } elseif (!session('?admin') && intval($_POST['category_id'])==1) {
                alert('error', L('YOU_HAVE_NO_PERMISSION_TO_ADD_ADMIN'), $_SERVER['HTTP_REFERER']);
            } elseif (!isset($_POST['position_id']) || $_POST['position_id'] == ''){
                alert('error', L('SELECT_POSITION_TO_ADD_USER'), $_SERVER['HTTP_REFERER']);
            } elseif ($m_user->where('name = "%s"', $_POST['name'])->find()){
                alert('error', L('USER_EXIST'), $_SERVER['HTTP_REFERER']);
            } elseif (!session('?admin') && intval($_POST['category_id'])==1) {
                alert('error', L('YOU_HAVE_NO_PERMISSION_TO_ADD_ADMIN'), $_SERVER['HTTP_REFERER']);
            } elseif ($_POST['idcode'] && $m_user->where("idcode=".$_POST['idcode'])->find()) {
                alert('error',"工号已经存在", $_SERVER['HTTP_REFERER']);
            }

            $m_user->status = 1;
            //为用户设置默认导航（根据系统菜单设置中的位置）
            $m_navigation = M('navigation');
            $navigation_list = $m_navigation->order('listorder asc')->select();
            $menu = array();
            foreach($navigation_list as $val){
                if($val['postion'] == 'top'){
                    $menu['top'][] = $val['id'];
                }elseif($val['postion'] == 'user'){
                    $menu['user'][] = $val['id'];
                }else{
                    $menu['more'][] = $val['id'];
                }
            }
            $navigation = serialize($menu);
            $m_user->navigation = $navigation;
            if($re_id = $m_user->add()){
                $data['position_id'] = $_POST['position_id'];
                $data['user_id'] = $re_id;

                if($role_id = $m_role->add($data)){
                    $udata = array(
                        'role_id'=>$role_id,
                        'idcode'=>sprintf("A%03d", $re_id)
                    );
                    $m_user->where('user_id = %d', $re_id)->setField($udata);
					delete_cache_temp();
					actionLog($re_id);
                    if($_POST['submit'] == L('ADD')){
                        alert('success', L('ADD_USER_SUCCESS_USER_CAN_LOGIN_NOW'), U('user/index'));
                    }else{
                        alert('success', L('ADD_USER_SUCCESS_USER_CAN_LOGIN_NOW'), U('user/add'));
                    }
                }
            }else{
                alert('error', L('ADDING FAILS CONTACT THE ADMINISTRATOR' ,array('')),$_SERVER['HTTP_REFERER']);
            }

		} else {
			$m_config = M('Config');
			$category = M('user_category');
			$m_position = M('position');
			if(!session('?admin')){
				$department_list = getSubDepartment2(session('department_id'), M('role_department')->select(), 1);
			}else{
				$department_list =  M('role_department')->select();
			}
			
			$where['department_id'] = session('department_id');
			$position_list = getSubPosition(session('position_id'), $m_position->where($where)->select());

			$position_id_array = array();
			$position_id_array[] = session('position_id');
			foreach($position_list as $k => $v){
				$position_id_array[] = $v['position_id'];
			}
			$where['position_id'] = array('in', implode(',', $position_id_array));
			$role_list = $m_position->where($where)->select();
			
			if(empty($role_list) && !session('?admin')){
				alert('error', L('YOU_HAVE_NO_PERMISSION_TO_ADD_USER'), $_SERVER['HTTP_REFERER']);
			}else{
				if(!$m_config->where('name = "smtp"')->find())
				alert('error', L('PLEASE_SET_SMTP_FIRST_TO_INVITATION_USER',array(U('setting/smtp'))));
				$this->categoryList = $category->select();
				$this->assign('department_list', $department_list);
				$this->alert = parseAlert();
				$this->display();
			}
		}
	}
	
	public function getPositionList() {
		if($_GET["id"]){
			$m_position = M('position');
			$where['department_id'] = $_GET['id'];
			$position_list = $m_position->where($where)->select();

			$position_id_array = array();
			foreach($position_list as $k => $v){
				$position_id_array[] = $v['position_id'];
			}
			if(!session('?admin')){
				$where['position_id'] = array('in', implode(',', $position_id_array));
			}
			$role_list = $m_position->where($where)->select();
			$this->ajaxReturn($role_list, L('GET_SUCCESS'), 1);
		}else{
			$this->ajaxReturn($role_list, L('SELECT_DEPARTMENT_FIRST'), 0);
		}
		
	}
	
	
	public function active() {
		$verify_code = trim($_REQUEST['verify_code']);
		$user_id = intval($_REQUEST['user_id']);
		$m_user = M('User');
		$user = $m_user->where('user_id = %d', $user_id)->find();
		if (is_array($user) && !empty($user)) {
			if (md5(md5($user['reg_time']) . $user['salt']) == $verify_code) {
				if ($_REQUEST['password']) {
					$password = md5(md5(trim($_REQUEST["password"])) . $user['salt']);
					$m_user->where('user_id =' . $_REQUEST['user_id'])->save(array('password'=>$password,'status'=>1, 'reg_time'=>time(), 'reg_ip'=>get_client_ip()));
					alert('success', L('SET_PASSWORD_SUCCESS_PLEASE_LOGIN'), U('user/login'));
				} else {
					$this->alert = parseAlert();
					$this->display();
				}
			} else {
				$this->error(L('FIND_PASSWORD_LINK_DISABLE'));
			}
		} else {
			$this->error(L('FIND_PASSWORD_LINK_DISABLE'));
		}
	}
	
	public function view(){
		if($this->isGet()){
			$user_id = isset($_GET['id']) ? $_GET['id'] : 0;
			$d_user = D('RoleView');
			$user = $d_user->where('user.user_id = %d', $user_id)->find();

			$log_ids = M('rLogUser')->where('user_id = %d', $user_id)->getField('log_id', true);
			$user['log'] = M('log')->where('log_id in (%s)', implode(',', $log_ids))->select();
			$log_count = 0;
			foreach ($user['log'] as $key=>$value) {
				$user['log'][$key]['owner'] = D('RoleView')->where('role.role_id = %d', $value['role_id'])->find();
				$log_count++;
			}
			$user['log_count'] = $log_count;
			
			$file_ids = M('rFileUser')->where('user_id = %d', $user_id)->getField('file_id', true);
			$user['file'] = M('file')->where('file_id in (%s)', implode(',', $file_ids))->select();
			$file_count = 0;
			foreach ($user['file'] as $key=>$value) {
				$user['file'][$key]['owner'] = D('RoleView')->where('role.role_id = %d', $value['role_id'])->find();
				$file_count++;
			}
			$user['file_count'] = $file_count;
			$this->categoryList = M('UserCategory')->select();
			$this->user = $user;
			$this->alert = parseAlert();
			$this->display();
		}
	}
	
	public function index(){
		if(!session('?name') || !session('?user_id')){
			redirect(U('User/login/'), 1, L('PLEASE_LOGIN_FIRSET'));
		}
		$p = isset($_GET['p']) ? intval($_GET['p']) : 1 ;
		$status = isset($_GET['status']) ? intval($_GET['status']) : 1 ;
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$d_user = D('UserView'); // 实例化User对象
		
		if(!session('?admin')) $where['role_id'] = array('in', getSubRoleId(true));
		$where['status'] = $status;
		if($id) $where['category_id'] = $id;
		
		import('@.ORG.Page');// 导入分页类
		$count = $d_user->where($where)->count();
	
		$Page = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
		$Page->parameter = "id=".$id.'&status=' . $status;
		$show  = $Page->show();// 分页显示输出
		$user_list = $d_user->order('reg_time')->where($where)->page($p.',15')->select();
		$this->assign('user_list',$user_list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		
		$category = M('user_category');
		$this->categoryList = $category->select();
		$this->alert = parseAlert();
		$this->display();
	}
	
	//查看部门信息
	public function department(){
		if(!session('?name') || !session('?user_id')){
			redirect(U('User/login/'), 0, L('PLEASE_LOGIN_FIRSET'));
		}elseif(!session('?admin')){
			alert('error',L('YOU_HAVE_NO_PERMISSION'),$_SERVER['HTTP_REFERER']);
		}
		
		$this->assign('tree_code', getSubDepartmentTreeCode(0, 1));
		$this->alert = parseAlert();
		$this->display(); 
	}
	
	//添加部门信息
	public function department_add(){
		if(!session('?name') || !session('?user_id')){
			redirect(U('User/login/'), 0, L('PLEASE_LOGIN_FIRSET'));
		}
		
		if($this->isPost()){
			$department = D('roleDepartment');
            $_POST['league_id'] = session('league_id');
            if($department->create()){
				$department->name ? '' :alert('error',L('PLEASE_INPUT_DEPARTMENT_NAME'),$_SERVER['HTTP_REFERER']);
				if($department->add()){
					delete_cache_temp();
					alert('success',L('ADD_DEPARTMENT_SUCCESS'),$_SERVER['HTTP_REFERER']);
				}else{
					alert('error',L('ADD_DEPARTMENT_FAILED_CONTACT_ADMIN'),$_SERVER['HTTP_REFERER']);
				}
			}else{
				alert('error',$department->getError(),$_SERVER['HTTP_REFERER']);
			}
		}else{
			$department = M('roleDepartment');
			$department_list = $department->where(array('league_id'=>session('league_id')))->select();
			$this->assign('departmentList', getSubDepartment(0,$department_list,''));
			$this->display();
		}
	}
	
	public function department_edit(){
		if(!session('?name') || !session('?user_id')){
			redirect(U('User/login/'), 0, L('PLEASE_LOGIN_FIRSET'));
		}
		
		if($_POST['name']){
			$department = M('roleDepartment');
			$department->create();
			if($department->save($data)){
				delete_cache_temp();
				alert('success',L('EDIT_DEPARTMENT_SUCCESS'),$_SERVER['HTTP_REFERER']);
			}else{
				alert('error',L('DATA_NOT_CHANGED_EDIT_FAILED'),$_SERVER['HTTP_REFERER']);
			}
		}elseif($_GET['id']){
			$department = M('roleDepartment');
			$this->assign('vo',$department->where('department_id=' . $_GET['id'])->find());

			$department_list = $department->where(array('league_id'=>session('league_id')))->select();
			
			foreach($department_list as $key=>$value){
				if($value['department_id'] == $_GET['id']){
					unset($department_list[$key]);
				}
				if($value['parent_id'] == $_GET['id']){
					unset($department_list[$key]);
				}
			}
			$this->assign('departmentList', getSubDepartment(0,$department_list,''));
			$this->display();
		}else{
			$this->error(L('PARAMETER_ERROR'));
		}
	}
	
	public function department_delete(){
		if(!session('?name') || !session('?user_id')){
			redirect(U('User/login/'), 0, L('PLEASE_LOGIN_FIRSET'));
		}
		$department = M('roleDepartment');
		if($_POST['dList']){
			if(in_array(6,$_POST['dList'],true)){
				$this->error(L('CAN_NOT_DELETE_THE_TOP_DEPARTMENT'));
			}else{
				foreach($_POST['dList'] as $key=>$value){
					
					$name = $department->where('department_id = %d',$value)->getField('name');
					if($department->where('parent_id=%d',$value)->select()){
						alert('error',L('DELETE_SUB_DEPARTMENT_FIRST',array($name)), $_SERVER['HTTP_REFERER']);
					}
					$m_position = M('position');
					if($m_position->where('department_id=%d',$value)->select()){
						alert('error',L('DELETE_SUB_POSITION_FIRST',array($name)), $_SERVER['HTTP_REFERER']);
					}
				}
				if($department->where('department_id in (%s)', join($_POST['dList'],','))->delete()){
					delete_cache_temp();
					alert('success', L('DELETED SUCCESSFULLY'),$_SERVER['HTTP_REFERER']);
				}else{
					$this->error(L('DELETE FAILED CONTACT THE ADMINISTRATOR'));
				}
			}
		}elseif($_GET['id']){
			if(6 == intval($_GET['id'])){
				$this->error(L('CAN_NOT_DELETE_THE_TOP_DEPARTMENT'));
			}
			$department_id = intval($_GET['id']); 
			$name = $department->where('department_id = %d', $department_id)->getField('name');
			if($department->where('parent_id=%d', $department_id)->select()){
				alert('error',L('DELETE_SUB_DEPARTMENT_FIRST',array($name)), $_SERVER['HTTP_REFERER']);
			}
			$m_position = M('position');
			if($m_position->where('department_id=%d', $department_id)->select()){
				alert('error',L('DELETE_SUB_POSITION_FIRST',array($name)), $_SERVER['HTTP_REFERER']);
			}
			if($department->where('department_id = %d', $department_id)->delete()){
				alert('success', L('DELETED SUCCESSFULLY'),$_SERVER['HTTP_REFERER']);
			}else{
				$this->error(L('DELETE FAILED CONTACT THE ADMINISTRATOR'));
			}
		}else{
			alert('error', L('SELECT_DEPARTMENT_TO_DELETE'),$_SERVER['HTTP_REFERER']);
		}
	}
	
	public function role(){
		if(!session('?name') || !session('?user_id')){
			redirect(U('User/login/'), 0, L('PLEASE_LOGIN_FIRSET'));
		}elseif(!session('?admin')){
			alert('error',L('YOU_HAVE_NO_PERMISSION'),$_SERVER['HTTP_REFERER']);
		}

        $status = $_GET['status'] ? $_GET['status']:"0,1,2";
		$branch_id = $_GET['bybr'] != "" ? $_GET['bybr']:"";
		$this->assign('tree_code', getSubPositionTreeCode(0, 1, 1, $status, $branch_id));
		$this->alert=parseAlert();
		session("index_refer_url", $_SERVER['REQUEST_URI']);
		$this->display();
	}

    public function roledialog(){
        if(!session('?name') || !session('?user_id')){
            redirect(U('User/login/'), 0, L('PLEASE_LOGIN_FIRSET'));
        }elseif(!session('?admin')){
            alert('error',L('YOU_HAVE_NO_PERMISSION'),$_SERVER['HTTP_REFERER']);
        }

        $this->assign('field_group_id', $_GET['field_group_id']);
        $this->assign('tree_code', getSubPositionTreeCode2(0, 1));
        $this->alert=parseAlert();
        $this->display();
    }
	
	public function role_ajax_add(){
		if($_POST['name']){
			$role = D('role');
			if($role->create()){
				$role->name ? '' :alert('error',L('PLEASE_INPUT_POSITION_NAME'),$_SERVER['HTTP_REFERER']);
				if($role_id = $role->add()){
					$role_list = M('role')->where(array('league_id'=>session('league_id')))->select();
					if (session('?admin')) {
						$role_list = getSubRole(0, $role_list, '');
					} else {
						$role_list = getSubRole(session('role_id'), $role_list, '');
					}
					foreach ($role_list as $key=>$value) {
						if ($value['user_id'] == 0) {
							$rs_role[] = $role_list[$key];
						}
					}
				
					$data['role_id'] = $role_id;
					$data['role_list'] = $rs_role;
					$this->ajaxReturn($data,L('SEND_SUCCESS'),1);
				}else{
					$this->ajaxReturn("",L('SEND_FAILED'),0);
				}
			}else{
				$this->ajaxReturn("",L('SEND_FAILED'),0);
			}
		}else{
			$department = M('roleDepartment');
			$department_list = $department->where(array('league_id'=>session('league_id')))->select();
			$this->assign('departmentList', getSubDepartment(0,$department_list,''));
			$role = M('role');
			$role_list = $role->where(array('league_id'=>session('league_id')))->select();
			$this->assign('roleList', getSubRole(0,$role_list,''));
			$this->display();
		}
	}
	
	public function role_add(){
		if ($this->isPost()) {
			$d_position = D('Position');
            $_POST['league_id'] = session('league_id');
            if($d_position->create()){
				$d_position->name ? '' :alert('error',L('PLEASE_INPUT_POSITION_NAME'),$_SERVER['HTTP_REFERER']);
				if($position_id = $d_position->add()){
					delete_cache_temp();
					alert('success',L('ADD_POSITION_SUCCESS'),$_SERVER['HTTP_REFERER']);
				}else{
					$this->error(L('ADDING FAILS CONTACT THE ADMINISTRATOR' ,array('')));
				}
			}else{
				$this->error(L('ADDING FAILS CONTACT THE ADMINISTRATOR' ,array('')));
			}
		} else {
			$department_list = M('RoleDepartment')->where(array('league_id'=>session('league_id')))->select();
			$position_list = M('Position')->where(array('league_id'=>session('league_id')))->select();
			$this->assign('departmentList', getSubDepartment(0,$department_list,''));
			$this->assign('positionList', getSubPosition(0,$position_list,''));
			$this->display();
		}
	}
	
	public function getRoleByDepartment(){
		if($this->isAjax()) {
			$department_id = $_GET['department_id'];
			$roleList = getRoleByDepartmentId($department_id);
			$this->ajaxReturn($roleList, '', 1); 
		}
	}
	
	public function roleEdit(){
		if($_GET['id']){
			$m_position = M('position');
			$department_list = M('RoleDepartment')->where(array('league_id'=>session('league_id')))->select();
			$position_list = $m_position->where(array('league_id'=>session('league_id')))->select();
			$this->assign('position', $m_position->where('position_id=%d', $_GET['id'])->find());
			$this->assign('departmentList', getSubDepartment(0,$department_list,''));
			$this->assign('positionList', getSubPosition(0,$position_list,''));
			$this->display();
		}else{
			$per['position_id'] = intval($_REQUEST['position_id']);
			$per['name'] = trim($_REQUEST['name']);
			$per['description'] = trim($_REQUEST['description']);
			$per['department_id'] = intval($_REQUEST['department_id']);
			$per['parent_id'] = intval($_REQUEST['parent_id']);
			$per['urge_category_ratio'] = doubleval($_REQUEST['urge_category_ratio']);

			$m_position = M('Position');
			if($m_position -> create($per)){
				if($m_position->save()){
					delete_cache_temp();
					alert('success',"修改岗位成功",$_SERVER['HTTP_REFERER']);
				}else{
					$this->error(L('ADDING FAILS CONTACT THE ADMINISTRATOR' ,array('')));
				}
			}else{
				$this->error(L('ADDING FAILS CONTACT THE ADMINISTRATOR' ,array('')));
			}
		}
	}
	

	public function role_delete(){
		$m_position = M('position');
		$d_role = D('RoleView');
		if($_POST['roleList']){
			if(in_array(1,$_POST['roleList'],true)){
				$this->error(L('CAN_NOT_DELETE_THE_TOP_PERMISSION_USER'));
			}else{
				foreach($_POST['roleList'] as $key=>$value){
					$name = $m_position->where('role_id = %d', $value)->getField('name');
					if($d_role->where('position_id = %d', $value)->select()){
						alert('error',L('HAVE_USER_ON_THIS_POSITION',array($name)), $_SERVER['HTTP_REFERER']);
					}
				}
				if($m_position->where('role_id in (%s)', join($_POST['roleList'],','))->delete()){
					delete_cache_temp();
					alert('success', L('DELETED SUCCESSFULLY'),$_SERVER['HTTP_REFERER']);
				}else{
					$this->error(L('DELETE FAILED CONTACT THE ADMINISTRATOR'));
				}
			}
		}elseif($_GET['id']){
			if(1 == intval($_GET['id'])){
				$this->error(L('CAN_NOT_DELETE_THE_TOP_PERMISSION_USER'));
			}
			if($d_role->where('position.position_id = %d', intval($_GET['id']))->select()){
				alert('error', L('HAVE_USER_ON_THIS_POSITION',array($name)), $_SERVER['HTTP_REFERER']);
			}else{
				if($m_position->where('position_id = %d', intval($_GET['id']))->delete()){
					alert('success', L('DELETED SUCCESSFULLY'),$_SERVER['HTTP_REFERER']);
				}else{
					$this->error(L('DELETE FAILED CONTACT THE ADMINISTRATOR'));
				}
			}
		}else{
			alert('error', L('SELECT_POSITION_TO_DELETE'),$_SERVER['HTTP_REFERER']);
		}
	}
	
	public function user_role_relation(){
		if(!session('?name') || !session('?user_id')){
			redirect(U('User/login/'), 0, L('PLEASE_LOGIN_FIRSET'));
		}
		//用户添加到岗位
		if($_GET['by'] == 'user_role'){
			if($_GET['id']){
				$this->user = M('User')->where('user_id = %d', $_GET['id'])->find(); //占位符操作 %d整型 %f浮点型 %s字符串 
				
				$department = M('roleDepartment');
				$department_list = $department->where(array('league_id'=>session('league_id')))->select();
				$departmentList = getSubDepartment(0, $department_list, '');				

				$role = M('Role');				
				foreach($departmentList as $key => $value) {					
					$roleList = $role->where('department_id =' . $value['department_id'])->select();
					$departmentList[$key]['roleList'] = $roleList;				
				}

				$this->assign('departmentList', $departmentList);
				$this->display('User:user_role');
			} elseif($_POST['user_id']){
				$m_user = M('user');
				$user = $m_user->where('user_id = %d' , $_POST['user_id'])->find();
				if($user['status'] == 0){
					alert('error', L('GRANT_PERMISSION_FAILED_FOR_NOT_PASS_AUDIT', array($user['name'])),$_SERVER['HTTP_REFERER']);
				} elseif($user['status'] == -1){
					alert('error', L('GRANT_PERMISSION_FAILED_FOR_NOT_PASS_AUDIT', array($user['name'])),$_SERVER['HTTP_REFERER']);
				} else {
					$role_ids = is_array($_POST['role']) ? implode(',', $_POST['role']) : '';
					$m_role = M('role');	
					$m_role->where("role_id in ('%s')", $role_ids)->setField('user_id', $_POST['user_id']);
					$m_role->where("role_id not in ('%s') and user_id=%d", $role_ids, $_POST['user_id'])->setField('user_id', '');
					
					alert('success', L('EDIT_SOMEONE_POSITION_SUCCESS', array($user['name'])),$_SERVER['HTTP_REFERER']);
				}
			}else{
				alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
			}
		//岗位添加用户
		}else if($_GET['by'] == 'role_user'){
			$role = M('role');
			if($_GET['role_id']){
				$this->role = $role->where('role_id = %d',$_GET['role_id'])->find();
				$this->userList =  M('user')->where(array('league_id'=>session('league_id')))->where('status = %d',1)->select();
				$this->display('User:role_user_add');
			}elseif($_POST['role_id']){
				$role->create();
				$m_user = M('user');
				$user = $m_user->where('user_id = %d' , $_POST['user_id'])->find();
				if (!$user['role_id']) {
					$m_user->where('user_id = %d' , $_POST['user_id'])->setField('role_id', $_POST['role_id']);
				}
				if($role->save()){
					alert('success',L('SETTING_SUCCESS'),$_SERVER['HTTP_REFERER']);
				}else{
					alert('error',L('SETTING_FAILED'),$_SERVER['HTTP_REFERER']);
				}			
			}
		}
	}

	public function notice(){
		$this->alert = parseAlert();
		$this->display();
	}

    public function submit_associate() {
        if (!$_REQUEST['src_role_id'] || !$_REQUEST['dest_role_id'] || !$_REQUEST['mode']) {
            $this->ajaxReturn("", '参数错误', 0);
        }
        $src_role_id = $_REQUEST['src_role_id'];
        $d_user = D('RoleView');
        $src_user = $user= $d_user->where('user.role_id = %d', $src_role_id)->find();
        if (!$src_user) {
            $this->ajaxReturn("", '无效的员工', 0);
        }

        $dest_role_id = $_REQUEST['dest_role_id'];
        $dest_user = $user= $d_user->where('user.role_id = %d', $dest_role_id)->find();
        if (!$dest_user) {
            $this->ajaxReturn("", '无效的员工', 0);
        }
        $mode = $_REQUEST['mode'];
        M($mode)->where("owner_role_id=".$src_role_id)->setField("owner_role_id", $dest_role_id);
        $this->ajaxReturn("", '移交成功', 1);
    }

    public function associate() {
        if (!$_REQUEST['id']) {
            alert('error',L('YOU_DO_NOT_HAVE_THIS_RIGHT'),$_SERVER['HTTP_REFERER']);
        }
        $role_id = $_REQUEST['id'];
        $this->user = $user= getUserByRoleId($role_id);

        $associate_list = array(
            "product"=>array(
                "name"=>"雇员"
            ),
            "customer"=>array(
                "name"=>"客户"
            ),
            "leads"=>array(
                "name"=>"意向"
            ),
            "business"=>array(
                "name"=>"家政订单"
            ),
            "serve"=>array(
                "name"=>"产品"
            ),
            "trade"=>array(
                "name"=>"产品订单"
            ),
            "trainorder"=>array(
                "name"=>"培训订单"
            ),
        );
        foreach($associate_list as $k=>$v) {
            $cnt = M($k)->where("owner_role_id=".$user['role_id'])->count();
            $associate_list[$k]['count'] = $cnt;
        }
        $this->associate_list = $associate_list;
        $this->display();
    }

	public function urge_position_ratio_dialog() {
		$ratio_field = M('Fields')->where(array('field_id'=>"712"))->find();
		$setting_str = '$setting=' . $ratio_field['setting'] . ';';
		eval($setting_str);

		if ($this->isAjax()) {
			$this->ratio_setting = $setting['data'];
			$this->staff_level_ratio = unserialize(M('Config')->where('name = "staff_level_ratio"')->getField('value'));;
			$this->display();
		} else {
			$ratio_setting = array();
			foreach ($setting['data'] as $v) {
				$ratio_setting[$v] = $_POST[$v];
			}
			M('Config')->where('name = "staff_level_ratio"')->save(array('value' => serialize($ratio_setting)));
			alert('success',L('SETTING_SUCCESS'),$_SERVER['HTTP_REFERER']);
		}
	}
}