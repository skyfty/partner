
<?php 
class StaffAction extends BaseAction {
	public function _initialize(){
        if (NO_AUTHORIZE_CHECK === true)
            return;
		$action = array(
			'permission'=>array(
                "mission",
                "myinfo",
                "repassword",
                "change_authInfo",
                "getinfo",
                "repassword",
                "getinfobyrole",
                'listdialog',
                'exportprint',
                'get_account_total'
            ),
			'allow'=>array(
                "mission",
                'validate',
            )
		);

        $this->is_admin = session('?admin');
        if (in_array(ACTION_NAME, array("edit", "view", "accountview", "myinfo"))) {
            $staff = D('StaffView')->where('staff.staff_id = %d',$this->_request('id'))->find();
            if ($staff) {
                if ($staff['user_id'] == session("user_id")) {
                    $action['permission'][] = ACTION_NAME;
                }
                $this->self_staff = ($staff['user_id'] == session("user_id"));
            }
        }
		B('Authenticate', $action);
	}

    public function change_authInfo() {
        if (!isset($_GET['id'])) {
            die("Please provide a date range.");
        }

        $staff_id = intval($this->_request('id'));
        $staff = D('StaffView')->where('staff.staff_id = %d',$staff_id)->find();
        if (!$staff) {
            alert('error', "没有找到这个客户",$_SERVER['HTTP_REFERER']);
        }

        if($_POST['submit']){
            $this->submit_auth($staff);
            alert('success', "修改客户账户成功", $_POST['refer_url'] ? $_POST['refer_url'] : U('product/index'));
        }else{
            $user_staff = M('mUser')->where(array('model'=>"staff",'model_id'=>$staff_id))->find();
            if ($user_staff) {
                $this->username = $user_staff['username'];
            } else {
                $this->username = $staff['telephone'];
            }
            $this->password = ($user_staff ? $user_staff['password'] : "234567");

            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->staff_id = $staff_id;
            $this->display();
        }
    }

    public function all_search_keyword($module) {
        return array("staff.slug");
    }

    public function show_list($where = array(), $params = array()) {
        if (session('user_id') == 1) {
            if ($_REQUEST['bylea']) {
                $where['league_id'] = $_REQUEST['bylea'];
                $params[] = "bylea=".trim($_GET['bylea']);
                $this->league = M("league")->where(array('league_id'=> $where['league_id']))->find();
            }
        } else {
            $where['league_id'] = session('league_id');
        }

        if (!session('?admin') && session('restriction') === true) {
            if ($_GET['lia'] == 'belongs' || session('authority') == "所属") {
                $where['_complex'] = self::make_astrict_where();
            } elseif ($_GET['lia'] == 'self') {
                $where['owner_role_id'] = session('role_id');
            }
        }

        $this->per_export = vali_permission("staff", "export");
        $this->is_personnel = session('?admin') || in_array(session("role_id"), getPositionRole(26));

        if ($_GET['lia']) {
            $params[] = "lia=" . $_GET['lia'];
        }

        $by = isset($_GET['by']) ? trim($_GET['by']) : '';
        $params[] = "by=".$by;

        switch ($by) {
            case 'today' :
                $where['create_time'] =  array('gt',strtotime(date('Y-m-d', time()))); break;
            case 'week' :
                $where['create_time'] =  array('gt',(strtotime(date('Y-m-d')) - (date('N', time()) - 1) * 86400)); break;
            case 'month' :
                $where['create_time'] = array('gt',strtotime(date('Y-m-01', time()))); break;

        }

        $branch_id = $_GET['bybr'] != "" ? $_GET['bybr']:"";
        if ($branch_id != "") {
            $where['staff.branch_id'] = array("in", $branch_id);
            $params[] = "bybr=" . trim($_GET['bybr']);
            $this->branch =  $branch_id;
        }

        if ($_GET['user_state']) {
            unset($where['user_state'], $where['staff.user_state']);
            if ($_GET['user_state'] == "全部") {
                $_GET['user_state'] = "";
            }
            $this->user_state = $_GET['user_state'];
        } else {
            $this->user_state =  "已激活";
        }
        if ($this->user_state != "") {
            $where['user_state'] = $this->user_state;
        }

        self::show_list_index_html($where, $params, "员工表");
    }

    public function make_list_order(&$params) {
        $order = "staff_id asc";
        if($_GET['desc_order']){
            $order = trim($_GET['desc_order']).' desc';
            $params[] = "desc_order=" . trim($_GET['desc_order']);
        }elseif($_GET['asc_order']){
            $order = trim($_GET['asc_order']).' asc';
            $params[] = "asc_order=" . trim($_GET['asc_order']);
        }
        return $order;
    }

    public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()){
            $_POST['name'] = trim($_POST['name']);
            $_POST['league_id'] = session('league_id');

            if (!($staff_id = $this->submit_add())) {
                alert('error',  "新建客户失败", $_SERVER['HTTP_REFERER']);
            }

            if ($_REQUEST['card_pic_base64'])  {
                $picinfo = update_base64_pic($_REQUEST['card_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("card_pic", $staff_id, "staff", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }
            if ($_REQUEST['work_pic_base64'])  {
                $picinfo = update_base64_pic($_REQUEST['work_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("work_pic", $staff_id, "staff", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }

            $_POST["password"] = md5(md5(trim("abc123456")));
            $role_user_id = $this->add_role_user($staff_id, 2, session('league_id'));
            if ($role_user_id) {
                $idcode = sprintf("A%03d", $role_user_id);
                $data = array(
                    'idcode'=>$idcode,
                    'slug'=>Pinyin($this->_request("name")),
                    'user_id'=>$role_user_id
                );
                M('Staff')->where(array('staff_id'=>$staff_id))->setField($data);
            }

            $this->log($staff_id, "增加员工", "增加员工成功");
            $this->alert = parseAlert();
            alert('success',"增加员工成功" , U('staff/view', 'id='.$staff_id));
		}else{
            $this->fields_group = product_field_list_html("add","staff", array(), "basic");

            $this->alert = parseAlert();;
            $this->display();
		}
	}

    private function user_state_map($v) {

        switch($v) {
            case "未激活":return 0;
            case "已激活":return 1;
            case "停用":return 2;
            default: return 0;
        }
    }

    public function add_role_user($staff_id,$category_id, $league_id= 0){
        $m_user = M('user');
        $m_user->create();
        $m_user->status = 1;
        $m_user->sex = ($_POST['sex'] == "男"?1:0);
        $m_user->category_id = $category_id;
        $m_user->staff_id = $staff_id;

        $navigation_list = M('navigation')->order('listorder asc')->select();
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
        if(!($re_id = $m_user->add())){
            return false;
        }

        $data['position_id'] = $_POST['position_id'];
        $data['user_id'] = $re_id;
        $data['league_id'] = $league_id;
        if(!($role_id = M('Role')->add($data))){
            return false;
        }
        $idcode = sprintf("A%03d", $re_id);
        $udata = array(
            'role_id'=>$role_id,
            'staff_id'=>$staff_id,
            'idcode'=>$idcode,
            'league_id'=>$league_id
        );
        $m_user->where('user_id = %d', $re_id)->setField($udata);

        if ($_REQUEST['branch_id']) {
            BranchAction::employee_update($_REQUEST['branch_id'], $role_id, $_REQUEST['owner_role_id']);
        }
        return $re_id;
    }

    public function delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_REQUEST['staff_id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'));
        }
        $staff_ids = is_array($_REQUEST['staff_id']) ? $_REQUEST['staff_id'] : array($_REQUEST['staff_id']);
        $delete_where = array('staff_id'=>array("in", $staff_ids));

        $role_ids = M("user")->where(array("staff_id", array("in", $staff_ids)))->getField("role_id");

        $staff_delete = M('staff')->where($delete_where)->delete();
        $staff_data_delete = M('staff_data')->where($delete_where)->delete();
        if(!$staff_delete || !$staff_data_delete) {
            alert('error', L('DELETE_FAILED_PLEASE_CONTACT_YOUR_ADMINISTRATOR'),$_SERVER['HTTP_REFERER']);
        }
        M("staff_subgroup")->where($delete_where)->delete();

        $account_where = array(
            'clause_additive'=>array("in", $staff_ids),
            'account_type'=>'staff'
        );
        $account_ids = M('account')->where($account_where)->getField('account_id', true);
        $this->delete_accounts($account_ids);

        M('branch_category')->where(array("role_id"=>array("in", $role_ids)))->delete();

        $r_module = array();
        foreach ($staff_ids as $value) {
            foreach ($r_module as $key2=>$value2) {
                $module_ids = M($value2)->where('staff_id = %d', $value)->getField($key2 . '_id', true);
                M($value2)->where('staff_id = %d', $value)->delete();
                if(!is_int($key2)){
                    M($key2)->where($key2 . '_id in (%s)', implode(',', $module_ids))->delete();
                }
            }
            $this->delete_files($value);
        }
        $this->log($staff_ids, "删除员工", "删除员工完成");
        alert('success', "删除员工完成", U("staff/index"));
    }


	public function edit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $findwhere = array('staff.staff_id'=>$this->_request('id'));
        $staff = D('StaffView')->where($findwhere)->find();
		if (!$staff) {
            alert_back('error',  L('CUSTOMER_DOES_NOT_EXIST!'));
        }
        if ($staff['user_id'] != session("user_id") && !session('?admin') && session('restriction') === true) {
            if (!self::is_owner($staff, $branch = get_branch(session("role_id")))){
                alert_back('error',  "你没有权限");
            }
        }
        $assort = $this->_request('assort', 'trim', "basic");

		if($this->isPost()){
            $_POST['name'] = trim($_POST['name']);
            $_POST['slug'] = Pinyin($this->_request("name"));
            if (!$this->submit_edit($staff['staff_id'])) {
                alert_back('error',  "编辑客户失败");
            }

            if ($_REQUEST['card_pic_base64']) {
                $picinfo = update_base64_pic($_REQUEST['card_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("card_pic", $staff['staff_id'], "staff", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }
            if ($_REQUEST['work_pic_base64'])  {
                $picinfo = update_base64_pic($_REQUEST['work_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("card_pic", $staff['staff_id'], "staff", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }
            $this->edit_role_user($staff['user_id']);
            $this->add_edit_log($staff['staff_id'], "编辑员工成功: ", D('StaffView')->verity_check($staff));

            alert('success', "编辑员工成功", U('staff/view', 'id='.$staff['staff_id']));

		}else{
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();

            //雇员图片
            $m_staff_images = M('staffImages');
            $staff['images']['main'] = $m_staff_images->where('staff_id = %d and is_main = 1', $staff['staff_id'])->find();
            $staff['images']['cardpic'] = $m_staff_images->where(array("staff_id"=>$staff['staff_id'],"is_main"=>2))->find();
            $fields_group = product_field_list_html("edit","staff",$staff, $assort);
            unset($fields_group[48]);
            $this->fields_group =$fields_group;
            $this->urge_position_ratio = json_encode($staff['urge_position_ratio']);
            $this->staff = $staff;
            $this->model_id = $staff['staff_id'];
            $this->display();
		}
	}

    private function edit_role_user($user_id){
        $staff = D('StaffView')->where(array("staff.user_id"=>$user_id))->find();
        $data = array(
            "sex"=>($staff['sex'] == "男"?1:0),
            "status"=>$this->user_state_map($staff['user_state']),
            "name"=>$staff['name'],
            "idcode"=>$staff['idcode'],
            "telephone"=>$staff['telephone'],
            "email"=>$staff['email'],
        );
        M('user')->where('user_id = %d', $user_id)->setField($data);
        M('role')->where('user_id = %d', $user_id)->setField('position_id', $staff['position_id']);
        if ($staff['branch_id']) {
            BranchAction::employee_update($staff['branch_id'], $staff['role_id'], $staff['owner_role_id']);
        } else {
            M('branch_category')->where(array("role_id"=>array("in", $staff['role_id'])))->delete();
        }
        return true;
    }

    public function log($staff_ids, $subject, $content, $category_id = 6) {
        if (!is_array($staff_ids)) {
            $staff_ids = array($staff_ids);
        }
        $log_id = 0;
        $staffs = M("staff")->where( array('staff_id'=>array("in", $staff_ids)))->select();
        foreach($staffs as $v) {
            $m_log = M('Log');
            $m_log->role_id = session("role_id");
            $m_log->subject = $subject;
            $m_log->content = $content;
            $m_log->category_id = $category_id;
            $m_log->create_date = time();
            $m_log->update_date = time();
            if ($log_id = $m_log->add()) {
                $data['staff_id'] = $v['staff_id'];
                $data['staff_idcode'] = $v['idcode'];
                $data['log_id'] = $log_id;
                $data['league_id'] = session('league_id');
                M('RLogStaff')->add($data);
            }
        }
        return $log_id;
    }

    public function mission() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $staff = D('StaffView')->where('staff.staff_id = %d', $this->_request('id'))->find();
        if (!$staff) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $staff['live_address'] = mb_substr(format_address_field($staff['live_address']), 3);
        $this->staff = $staff;
        $this->display(); // 输出模板
    }


    public function listDialog(){
        if ($this->isAjax() === false) {
            return $this->display();
        }

        $data_field = array(
            array(
                "field"=>"staff_id",
                "order"=>"staff_id"
            ),
            array(
                "field"=>"idcode",
                "order"=>"idcode"
            ),
            array(
                "field"=>"staff_name",
                "order"=>"staff_name"
            ),
            array(
                "field"=>"branch_name",
                "order"=>"branch_id"
            ),
            array(
                "field"=>"department_name",
                "order"=>"department_id"
            ),
            array(
                "field"=>"role_name",
                "order"=>"position_id"
            )
        );

        $m_model_name = $_GET['model']? $_GET['model']."View":"StaffView";
        $where = $this->parse_dialog_where();
        $this->ajaxReturn(make_data_list($m_model_name, $where, $data_field, array($this, "format_dialog_item")),'JSON');
    }

    public function format_index_fields($field_array) {
        foreach($field_array as $k=>$v) {
            if ($v['field'] == "balance") {
                $sum_cnt = M("staff")->sum($v['field']);
                $v['link_title'] = $v['name']."合计：" . number_format($sum_cnt, 2)."元";
            } else {
                $v['link_title'] = $v['name'];
            }
            $field_array[$k] = $v;
        }
        return $field_array;
    }


    public function format_dialog_item($val) {
        $val["staff_id"] = array(
            "staff_id"=>$val['staff_id']
        );
        $val['branch_name'] = $val['branch_name'] ? $val['branch_name']:"公司总部";

        return $val;
    }

    public function parse_dialog_where() {
        $where = parent::parse_dialog_where();
        if ($_GET['model']) {
            $where['_string'] =trim($_GET['query']);
        }
            $where['user_state'] = array("neq", "停用");

        if ($_GET['branch_id']) {
            $where['branch_id'] = array("in", trim($_GET['branch_id']));
        }

        if ($_GET['permission']) {
            $cc = M("permission")->cache(true)->where(array("url"=>array("in",$_GET['permission'] )))->field("position_id")->select(false);
            $where['_string'] .= "position.position_id in (".$cc.")";
        }
        $where['league_id'] = session('league_id');

        return $where;
    }

    public function accountview() {
        $staff_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->viewinfo("account", $staff_id);
    }

    public function view(){
        $staff_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->viewinfo("basic", $staff_id);
	}

    public function examineview(){
        $staff_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->viewinfo("examine",$staff_id);
    }

    public function viewuserid(){
        if (!$_GET['id']) {
            alert('error', L('parameter_error'));
        }
        $staff = D('StaffView')->where('staff.user_id = %d',$_GET['id'])->find();
        if (!$staff) {
            alert_back('error',  L('CUSTOMER_DOES_NOT_EXIST!'));
        }
        $this->viewinfo("basic", $staff['staff_id']);
    }

    public function viewinfo($assort, $staff_id) {
        if (0 == $staff_id) {
            alert('error', L('parameter_error'), U("Staff/index"));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $where = array("staff.staff_id"=>$staff_id);
        $staff = D('StaffView')->where($where)->find();
        if (!$staff) {
            alert('error', L('PARAMETER_ERROR'), U("Staff/index"));
        }

        if ($staff['user_id'] != session("user_id") && !session('?admin') && session('restriction') === true) {
            $authority = session('authority');
            if ($authority && !self::is_interest($staff, $branch = get_branch(session("role_id")))) {
                alert('error', "你没有权限访问", U("Staff/index"));
            }
        }

        if ($assort == "account") {
            $this->clause_additive = $staff['staff_id'];
            $accountcat = array(
                0=>"全部",
                "-1"=>"支出",
                "1"=>"收入",
            );
            $this->accountcat = $accountcat;
            $this->acat = $_GET['acat'] ? $_GET['acat'] : "0";
            $this->accounts_totals = $this->account_total($this->acat, $staff['staff_id']);
        }

        $m_staff_images = M('staffImages');
        $staff['images']['main'] = $m_staff_images->where('staff_id = %d and is_main = 1', $staff['staff_id'])->find();
        $staff['images']['cardpic'] = $m_staff_images->where(array("staff_id"=>$staff['staff_id'],'is_main'=>2))->find();

        $this->staff = $staff;
        $this->assort = $assort;
        $this->staff_id = $staff_id;

        if (($branch || $authority == "受限") && !in_array($staff['staff_id'], self::get_astrict_list())) {
            $staff = self::fix_branch_fields(getBranchFields("staff"), $staff, in_array($staff['owner_role_id'], $branch));
        }
        $this->is_owner = ($branch ? self::is_owner($staff, $branch) : true);
        $this->is_personnel = session('?admin') || in_array(session("role_id"), getPositionRole(26)) || vali_permission('staff','edit');
        $this->is_perrepassword = session('?admin') || $this->self_staff || vali_permission("staff", "repassword");

        $fields_group = product_field_list_show('staff', $staff, $assort);
        unset($fields_group[48], $fields_group[75]);
        $this->fields_group =$fields_group;
        $this->refer_url= session("index_refer_url");
        $this->alert = parseAlert();
        $this->display($assort."view");
    }

    public function getInfo() {
        $id = $this->_request('id');
        $where = array();
        if ($id) {
            $where['staff.staff_id']=$id;
        }
        if ($_REQUEST['role_id']) {
            $where['user.role_id']=$_REQUEST['role_id'];
        }
        $where['league_id'] = session('league_id');

        $info = D('StaffView')->where($where)->find();
        $this->ajaxReturn($info,"",$info ? 1:0);
    }


    public function getInfoByRole() {
        $id = $this->_request('id');
        $info = D('StaffView')->where('user.role_id = %d',$id)->find();
        $this->ajaxReturn($info);
    }

    public function analytics(){
        $params = array();
        $assort = $_GET['assort'] ? $_GET['assort'] : "state";
        if ($_GET['assort']) {
            $params[] = "assort=".$_GET['assort'];
        }
        $time_limit = self::default_statistics_time($params);

        if ($assort == "state") {
            $create_time= array(array('elt',$time_limit[1]),array('egt',$time_limit[0]), 'and');

            foreach(array("未成单", "服务前","服务中","服务后") as $s) {
                $where = array(
                    'service_state'=>$s,
                    'create_time'=>$create_time
                );
                $state_report_list[$s] = M('Staff')->where($where)->count();
            }
            $this->state_report_list = $state_report_list;

            $statistics = array();
            $statistics['all_sum'] = M('Staff')->count();
            $statistics['time_range_sum'] = M('Staff')->where(array('create_time'=>$create_time))->count();
            $this->statistics = $statistics;
        }

        if ($assort == "newly") {
            $tab = "_".($_GET['tab'] ? $_GET['tab'] : "charts");
            if ($_GET['tab']) {
                $params[] = "tab=".$_GET['tab'];
            }

            $cycle = $_GET['cycle'] ? $_GET['cycle'] : "week";
            if ($_GET['cycle']) {
                $params[] = "cycle=".$_GET['cycle'];
            }
            self::default_cycle_basis_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab, "客户");
        }

        $this->parameter = implode('&', $params);
        $this->assort = $assort;
        $this->alert = parseAlert();
        $this->display($assort."_analytics".$tab);
    }


    public function reset_branch() {
        foreach(M("staff")->select() as $v) {
            $m_branch = D("BranchCategoryView")->cache(true)->where(array("branch_category.role_id"=>$v['owner_role_id']))->find();
            if ($m_branch) {
                M("staff")->where("staff_id=".$v['staff_id'])->setField("branch_id", $m_branch['branch_id']);
            }
        }
    }

    public function exportprint() {
        $staff_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if (0 == $staff_id) {
            exit(0);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $staff = D('StaffView')->where('staff.staff_id = %d',$staff_id)->find();

        //雇员图片
        $m_staff_images = M('staffImages');
        $staff['images']['main'] = $m_staff_images->where('staff_id = %d and is_main = 1', $staff_id)->find();

        $pic_output_fields = array();
        $cardid_pic_output_fields = array();
        $basic_output_fields = array();
        foreach(product_field_list_show('staff', $staff, "basic", "print") as $k=>$gvo) {
            $field_print_cnt = 0;
            foreach($gvo['fields'] as $kvo=>$vo) {
                if ($vo['operating'] != '4' && $vo['in_print']){
                    if ($vo['form_type'] == "pic") {
                        if ($vo['field'] == "IDcard") {
                            $cardid_pic_output_fields = $vo;;
                        } else {
                            $pic_output_fields[$vo['field']] = self::format_export_pic($vo);
                        }
                        unset($gvo['fields'][$kvo]);
                    } else {
                        $field_print_cnt++;
                    }
                } else {
                    unset($gvo['fields'][$kvo]);
                }
            }
            if ($field_print_cnt) {
                $basic_output_fields[$k] = $gvo;
            }
        }
        $this->cardid_pic_output_fields = $cardid_pic_output_fields;
        $this->basic_output_fields = $basic_output_fields;
        $this->pic_output_fields = $pic_output_fields;
        $this->staff = $staff;
        $this->log($staff['staff_id'], "导出员工信息", "导出员工信息");
        $this->display();
    }

    public function repassword() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $staff = D('StaffView')->where('staff.staff_id = %d',$this->_request('id'))->find();
        if (!$staff) {
            alert_back('error',  L('CUSTOMER_DOES_NOT_EXIST!'));
        }

        if (!session('?admin') && $staff['user_id'] != session("user_id")) {
            alert('success', "无法修改密码， 请联系管理员", U('staff/view', 'id='.$staff['staff_id']));
        }

        if($this->isPost()){
            if(isset($_POST['password']) && $_POST['password']!=''){
                if (!is_hefa_password($_POST['password'])) {
                    alert('error',"不合法的密码， 密码必须至少包含数字和密码的8位字符", $_SERVER['HTTP_REFERER']);
                }
                $m_user = M('User');
                $user = $m_user->where('user_id = %d', $staff['user_id'])->find();
                $password = md5(md5(trim($_REQUEST["password"])) . $user['salt']);
                $m_user->where('user_id =' . $staff['user_id'])->save(array('password'=>$password, 'lostpw_time'=>0));
                $this->log($staff['staff_id'], "密码修改", "密码修改成功");
            }
            alert('success', "密码修改成功", U('staff/view', 'id='.$staff['staff_id']));
        }else{
            $this->staff = $staff;
            $this->display();
        }
    }

    public function myinfo() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $user_id =  session('user_id');
        $staff = D('StaffView')->where('staff.user_id = %d',$user_id)->find();
        if (!$staff) {
            alert_back('error',  L('CUSTOMER_DOES_NOT_EXIST!'));
        }
        $this->self_staff = ($staff['user_id'] == session("user_id"));
        $this->viewinfo("basic", $staff['staff_id']);
    }

    public function logtable() {
        $data_field = array(
            array(
                "field"=>"create_date_show",
                "order"=>"create_date"
            ),
            array(
                "field"=>"role_show",
                "order"=>"role_id"
            ),
            array(
                "field"=>"staff_show",
                "order"=>"staff_id"
            ),
            array(
                "field"=>"subject",
                "order"=>"subject"
            ),
            array(
                "field"=>"content_show",
                "order"=>"content"
            ),
        );

        $where = array();
        if ($_GET['start_time'] || $_GET['end_time']) {
            $where['log.create_date'] =  array('between', make_time_between());
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['log.content'] =  array('like', "%".$_REQUEST['search']['value']."%");
        }
        $where['league_id'] = session('league_id');

        $data = make_data_list("StaffLogView", $where, $data_field, array($this, "format_staff_log"));
        $this->ajaxReturn($data,'JSON');
    }

    public function format_staff_log($v) {
        $v['create_date_show'] = toDate($v['create_date']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        $html = staff_show_html($v);;
        if (!$html) {
            $html = '<span>[' .$v['staff_idcode'].'] '.$v['staff_name'] . '</span>&nbsp;';;
        }
        $v['staff_show'] = $html;
        $v['content_show'] = "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>".cutString($v['content'], 43)."</a>";

        return $v;
    }

    private function add_edit_log($staff_id, $logcont, $change_fields) {
        foreach($change_fields as $v) {
            $logcont.=$v['name']."[".$v['oldvalue']."=>".$v['newvalue']."],";
        }
        $this->log($staff_id, "更新日志",$logcont);
    }

    public function evaluate_list() {
        $where = array(
            "staff_id"=>$_GET['id']
        );
        $this->ajaxReturn(make_datatable_list("MarketStaffEvaluateView", $where, array($this, "format_evaluate_market_info")),'JSON');
    }

    public function format_evaluate_market_info($value) {
        $value['evaluate_time_show'] = pregtime($value['update_time'], true);
        $value['market_owner_role_id_show'] = role_show($value['market_owner_role_id']);
        $value['market_idcode_show'] = market_show_html($value, "product");

        return $value;
    }
}