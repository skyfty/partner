<?PHP
class BranchAction extends BaseAction{
    public function _initialize(){
        if (NO_AUTHORIZE_CHECK === true)
            return;
        $action = array(
            'permission'=>array(
                "employee_remove_shopkeeper",
                "change_category_config",
                "get_category_config",
                'employee',
                'employee_remove',
                'employee_shopkeeper',
                'employee_remove_shopkeeper',
                'employee_add',
                'employee_move',

            ),
            'allow'=>array(
            )
        );
        B('Authenticate', $action);
    }

    public function add(){
        if($this->isPost()) {
            if (!isset($_POST['name']) || $_POST['name'] == '') {
                $this ->error(L('必须设置门店名称'));
            }
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

            $_POST['league_id'] = session('league_id');
            $branch_id = $this->submit_add();
            if ($branch_id) {
                delete_cache_temp();
                if($_POST['refer_url']) {
                    alert('success', "新建门店成功", $_POST['refer_url']);
                }else{
                    alert('success', "新建门店成功", U("branch/view", "id=".$branch_id));
                }
            } else {
                $this->alert = parseAlert();
                alert('error', "新建课程失败", $_POST['refer_url']);
            }

        }else{
            $this->fields_group = product_field_list_html("add","branch", array(), "basic");
            $this->alert = parseAlert();
            $this->refer_url= refer_url('refer_add_url');
            $this->display();
        }
    }

    public function view(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $branch_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $branch_id) {
            alert('error', L('PARAMETER_ERROR'), U('branch/index'));
        }
        $branch = D('BranchView')->where('branch.branch_id = %d ', $_GET['id'])->find();
        $branch['shopkeeper'] = D('RoleView')->where('role.role_id = %d', $branch['shopkeeper_role_id'])->find();
        $this->is_shopkeeper = (session('?admin') || ($branch['shopkeeper_role_id'] == session('role_id')));
        $this->branch = $branch;
        $this->fields_group = product_field_list_show('branch', $branch);
        $this->alert = parseAlert();
        $this->refer_url= refer_url('refer_view_url');
        $this->display();
    }

    public function getlist() {
        $this->ajaxReturn(M('branch')->where(array('league_id'=>session('league_id')))->cache(true)->select());
    }

    public function perfect_list_item($value, $export = false, $branchlock = false) {
        $value['shopkeeper'] = D('RoleView')->where('role.role_id = %d', $value['shopkeeper_role_id'])->find();

        return parent::perfect_list_item($value, $export, $branchlock);
    }

    static function employee_update($branch_id, $role_id, $owner_role_id) {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $role = M("branch_category")->where(array("role_id"=>$role_id))->find();
        $owner_role = M("branch_category")->where(array("role_id"=>$owner_role_id, "branch_id"=>$branch_id))->find();
        if ($role) {
            $data = array(
                'parentid'=>$owner_role?$owner_role['branch_category_id']:0,
                'branch_id'=>$branch_id,
            );
            M('branch_category')->where('role_id = %d', $role_id)->setField($data);
        } else {
            $branch_data = array(
                'parentid'=>$owner_role?$owner_role['branch_category_id']:0,
                "order_id"=>0,
                "branch_id"=>$branch_id,
                "role_id"=>$role_id,
                "create_time"=>time(),
            );
            M("branch_category")->add($branch_data);
        }
        delete_cache_temp();
    }

    public function employee_add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $branch_id = isset($_REQUEST['branch_id']) ? intval($_REQUEST['branch_id']) : 0;
        if (0 == $branch_id || !$_REQUEST['role_id']) {
            $this->ajaxReturn("错误的参数");
        }
        $branch_belong = M("branch_category");
        $branch_role = $branch_belong->where(array("branch_id"=>$branch_id, "role_id"=>$_REQUEST['role_id']))->find();
        if ($branch_role){
            $this->ajaxReturn("这个员工已经在此门店里了");
        }
        $branch_role = $branch_belong->where(array("role_id"=>$_REQUEST['role_id']))->find();
        if ($branch_role) {
            $this->ajaxReturn("这个员工在另外的门店里， 请先将其移除");
        }

        $_POST['create_time'] = time();
        if($branch_belong->create() === false || !($branch_category_id = $branch_belong->add())) {
            $this->ajaxReturn("错误的参数");
        }
        delete_cache_temp();

        $staff = D('StaffView')->where('user.role_id = %d',$_REQUEST['role_id'])->find();
        if ($staff) {
            $branch = M("branch_category")->where(array("branch_category_id"=>$this->_request("parentid")))->find();
            $data = array(
                "branch_id"=>$branch_id,
            );
            if ($branch) {
                $data['owner_role_id'] =$branch['role_id'];
            }
            M("staff")->where(array("staff_id"=>$staff['staff_id']))->setField($data);
        }

        $role_info = getUserByRoleId($_POST['role_id']);
        $role_info['node_id'] = $branch_category_id;
        $role_info['user_icon'] = "/Public/img/admin_img.png";
        $this->ajaxReturn($role_info);
    }

    public function employee_remove(){
        if (!$_REQUEST['nodeid'] || !$_REQUEST['branch_id']) {
            $this->ajaxReturn(null);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $nodeid = $this->_request("nodeid");

        $selfwhere = array("branch_category_id"=>$nodeid);
        $branch = M("branch_category")->where($selfwhere)->find();
        $parrent_branch = M("branch_category")->where(array("branch_category_id"=>$branch['parentid']))->find();

        $bcrole_ids = M("branch_category")->where(array("parentid"=>$nodeid))->getField("role_id", true);
        $self_staff = D('StaffView')->where(array('user.role_id'=>array("in", $bcrole_ids)))->getField("staff_id", true);
        M("staff")->where(array("staff_id"=>array("in", $self_staff)))->setField(array('owner_role_id' =>$parrent_branch ? $parrent_branch['role_id']:0));

        $bcids = M("branch_category")->where(array("parentid"=>$this->_request("nodeid")))->getField("branch_category_id", true);
        M("branch_category")->where(array("branch_category_id"=>array("in", $bcids)))->setField("parentid", $branch['parentid']);

        M("branch_category")->where($selfwhere)->delete();
        delete_cache_temp();

        $staff = D('StaffView')->where('user.role_id = %d',$branch['role_id'])->find();
        if ($staff) {
            $data = array(
                "branch_id"=>0,
            );
            M("staff")->where(array("staff_id"=>$staff['staff_id']))->setField($data);
        }
        $this->ajaxReturn($branch);
    }


    public function employee_shopkeeper(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_REQUEST['nodeid'] || !$_REQUEST['branch_id']) {
            $this->ajaxReturn(null);
        }
        $branch = M("branch_category")->where(array("branch_category_id"=>$this->_request("nodeid")))->find();
        if(!$branch) {
            $this->ajaxReturn(null);
        }
        M("branch")->where(array("branch_id"=>$this->_request("branch_id")))->setField("shopkeeper_role_id", $branch['role_id']);
        delete_cache_temp();
        $this->ajaxReturn($branch);
    }


    public function employee_remove_shopkeeper(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_REQUEST['nodeid'] || !$_REQUEST['branch_id']) {
            $this->ajaxReturn(null);
        }
        $branch = M("branch_category")->where(array("branch_category_id"=>$this->_request("nodeid")))->find();
        if(!$branch) {
            $this->ajaxReturn(null);
        }
        M("branch")->where(array("branch_id"=>$this->_request("branch_id")))->setField("shopkeeper_role_id", "");
        delete_cache_temp();
        $this->ajaxReturn($branch);
    }


    public function employee_move(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $nodeid = $this->_request("nodeid");
        if (!$nodeid) {
            $this->ajaxReturn(null);
        }
        $parentid = $this->_request("parent");

        $parrent_branch = M("branch_category")->where(array("branch_category_id"=>$parentid))->find();
        $self_branch = M("branch_category")->where(array("branch_category_id"=>$nodeid))->find();
        $self_staff = D('StaffView')->where('user.role_id = %d',$self_branch['role_id'])->find();

        M("branch_category")->where(array("branch_category_id"=>$nodeid))->setField("parentid", $this->_request("parent"));

        $data['owner_role_id'] =$parrent_branch ? $parrent_branch['role_id']:0;
        M("staff")->where(array("staff_id"=>$self_staff['staff_id']))->setField($data);
        delete_cache_temp();
        $this->ajaxReturn($nodeid);
    }

    function employee_tree($branch, $bc, $where = array(), &$children = array()) {
        $where["parentid"]=$bc;
        $where['league_id'] = session('league_id');
        foreach(M("branch_category")->where($where)->order("order_id asc")->select() as $v) {
            $branch_cat = self::general_employee_tree($branch, $v['branch_category_id'], $v['role_id']);
            self::employee_tree($branch, $v['branch_category_id'], $where,  $branch_cat['children']);
            $children[] = $branch_cat;
        }
    }

    function nnaa_employee_tree($branch, &$children = array()) {
        $nnbrole = M("branch_category")->where(array("branch_id"=>$branch['branch_id']))->getField("branch_category_id", true);
        $nnbrole[] = 0;
        $where = array(
            "branch_id"=>$branch['branch_id'],
            "parentid"=>array("not in", $nnbrole)
        );
        foreach(M("branch_category")->where($where)->order("order_id asc")->select() as $v) {
            $children[] = self::general_employee_tree($branch, $v['branch_category_id'],$v['role_id']);;
        }
    }

    static function general_employee_tree($branch, $branch_category_id, $role_id) {
        $role_info = getUserByRoleId($role_id);
        $branch_cat = array(
            "id"=>$branch_category_id,
            'data'=>$role_info['role_id'],
            "text"=>$role_info['user_name'],
            "state"=>array("opened"=>true),
            "icon"=> ($branch['shopkeeper_role_id'] == $role_info['role_id'] ? "/Public/img/admin_img.png":"/Public/img/user_img.png"),
            "children"=>array()
        );
        return $branch_cat;
    }


    public function employee_dialog(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->employee();
    }

    public function employee(){
        $branch_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $branch_id) {
            alert('error', L('PARAMETER_ERROR'), U('branch/index'));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $branch = D('BranchView')->where('branch.branch_id = %d ', $branch_id)->find();
        if (!$this->isAjax()) {
            $this->branch = $branch;
            $this->is_shopkeeper = (session('?admin') || ($branch['shopkeeper_role_id'] == session('role_id')));
            $this->alert = parseAlert();
            $this->refer_url= refer_url('refer_view_url');
            return $this->display();
        }

        $children = array();
        self::employee_tree($branch, 0, array("branch_id"=>$branch_id), $children);
        self::nnaa_employee_tree($branch, $children);
        $this->ajaxReturn(array(array("id"=>0,'state'=>array('opened'=>true, 'locked'=>true), "icon"=> "/Public/img/admin_gohome.gif", "text"=>$branch['name'], "children"=>$children)));
    }

    public function edit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $branch = D('BranchView')->where('branch.branch_id = %d',$this->_request('id'))->find();
        if (!$branch) {
            alert('error', "没有这个门店",$_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()){
            if($this->submit_edit($branch['branch_id'])) {
                if ($_POST['urge_branch_ratio'] != $branch['urge_branch_ratio']) {
                    A("Market")->update_urge_price_by_branch($branch['branch_id']);
                }
                delete_cache_temp();
                alert('success', "编辑门店成功", U('branch/view', 'id='.$branch['branch_id']));
            } else {
                alert('error', "编辑门店失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            $alert = parseAlert();
            $this->alert = $alert;
            $this->branch = $branch;
            $this->model_id = $branch['branch_id'];
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->fields_group =  product_field_list_html("edit","branch",$branch);
            $this->display();
        }
    }


    public function listDialog(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $where = array();
        if (!session('?admin')) {
            $m_branch = D("BranchCategoryView")->where(array("branch_category.role_id"=>session('role_id')))->find();
            if ($m_branch) {
                $where = array("branch_id"=>$m_branch['branch_id']);
            }
        }
        $where['league_id'] = session('league_id');

        $branch_employees = array();
        foreach(D('BranchView')->where($where)->select() as $k=>$branch) {
            $children = array();
            self::employee_tree($branch, 0, array("branch_id"=>$branch['branch_id']), $children);
            $branch_employees[] = array(
                "id"=>"branch_".$branch['branch_id'],
                'state'=>array(
                    'opened'=>true,
                    'locked'=>true
                ),
                "icon"=> "/Public/img/admin_gohome.gif",
                "text"=>$branch['name'],
                "children"=>$children
            );
        }
        $this->branch_employees = json_encode($branch_employees);
        $this->display("listDialog");
    }

    function change_category_config() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $branch = D('BranchView')->where('branch.branch_id = %d',$this->_request('id'))->find();
        if (!$branch) {
            alert('error', "没有这个门店",$_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()){
            $category_config = array();
            foreach (M('product_category')->where(array("enable="=>1, "league_id"=>session('league_id')))->field("name, category_id")->select() as $v2) {
                $v2['agency_scale'] = $_POST['agency_scale_'.$v2['category_id']];
                $v2['sign_agency_scale'] = $_POST['sign_agency_scale_'.$v2['category_id']];
                $v2['urge_agency_scale'] = $_POST['urge_scale_'.$v2['category_id']];
                $category_config[$v2['category_id']] = $v2;
            }
            M("branch")->where("branch_id=".$branch['branch_id'])->setField("category_config",serialize($category_config));
            delete_cache_temp();
            alert('success', "编辑门店成功", $_SERVER['HTTP_REFERER']);
        }else{
            $category_config = array();
            foreach (M('product_category')->where(array("enable="=>1, "league_id"=>session('league_id')))->select() as $v2) {
                $category_config[$v2['category_id']] = $v2;
            }
            unset($category_config[4]);

            if ($branch['category_config']) {
                $category_config_tmp = unserialize($branch['category_config']);
                foreach($category_config as $k=>$v) {
                    if ($category_config_tmp[$k]) {
                        $category_config[$k] = $category_config_tmp[$k];
                    }
                }
                $this->category_config = $category_config;
            }
            $this->branch = $branch;
            $this->display("category_config_dialog");
        }
    }

    function get_category_config() {
        $branch = D('BranchView')->cache(true)->where('branch.branch_id = %d',$this->_request('id'))->find();
        if (!$branch || !$branch["category_config"]) {
            $this->ajaxReturn(null);
        }
        $this->ajaxReturn(unserialize($branch["category_config"]));
    }


    public function show_list($where = array(), $params = array()) {
        $this->module = strtolower(MODULE_NAME);
        $order = self::make_list_order($params);

        if (session('user_id') == 1) {
            if ($_REQUEST['bylea']) {
                $where['league_id'] = $_REQUEST['bylea'];
                $params[] = "bylea=".trim($_GET['bylea']);
                $this->league = M("league")->where(array('league_id'=> $where['league_id']))->find();
            }
        } else {
            $where['league_id'] = session('league_id');
        }


        $this->parameter = implode('&', $params);
        $module_view = D(ucfirst($this->module).'View');
        $count = $module_view->where($where)->count();// 查询满足要求的总记录数

        if ($count) {
            $page = self::assign_list_page($this->parameter, $count);
            $list = $module_view->where($where)->order($order)->Page($page->nowPage, $page->listRows)->select();
            $module_list = $this->format_module_list($list);
            $this->assign('list',$module_list);// 赋值数据集
        }
        self::display_index_html();
    }

}
