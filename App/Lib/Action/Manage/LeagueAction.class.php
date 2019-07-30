<?PHP
class LeagueAction extends BaseAction{
    public function _initialize(){
        if (NO_AUTHORIZE_CHECK === true)
            return;
        if (!session('?admin') || session('league_id') > 0)
            alert('error', "你没有权限","/index.php");
    }

    public function show_list($where = array(), $params = array()) {
        $this->module = "league";
        $order = self::make_list_order($params);
        $this->parameter = implode('&', $params);
        $module_view = D('LeagueView');
        $count = $module_view->where($where)->count();// 查询满足要求的总记录数

        if ($count) {
            $page = self::assign_list_page($this->parameter, $count);
            $list = $module_view->where($where)->order($order)->Page($page->nowPage, $page->listRows)->select();
            $module_list = $this->format_module_list($list);
            $this->assign('list',$module_list);// 赋值数据集
        }
        self::display_index_html();
    }


    public function delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $module_ids = $this->isPost() ? $_POST[$this->module.'_id'] : $_GET['id'];
        if ($this->submit_delete($module_ids)) {
            if (!is_array($module_ids)) {
                $module_ids = array($module_ids);
            }
            $module_id_list = implode(',', $module_ids);
            M("navigation")->where('league_id in (%s)', $module_id_list)->delete();

            if($_REQUEST['refer_url']) {
                alert('success', "删除成功", $_REQUEST['refer_url']);
            }else{
                alert('success', "删除成功" ,U($this->module.'/index'));
            }
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }

    public function add(){
        if($this->isPost()) {
            if (!isset($_POST['name']) || $_POST['name'] == '') {
                $this ->error(L('必须设置加盟商名称'));
            }
            $_POST['name'] = trim($_POST['name']);
            $_POST['position_id'] = 1;

            $league_id = $this->submit_add();
            if ($league_id) {
                $_POST['name'] = "admin";
                $data = array(
                    "create_time"=>time(),
                    "update_time"=>time(),
                    "creator_role_id"=>session("role_id"),
                    "astrict"=>"私有",
                    "league_id"=>$league_id,
                    "branch_id"=>0,
                    "name"=>$_POST['name'],
                    "user_state"=>"已激活",
                    "slug"=>"admin",
                    "owner_role_id"=>"0",
                );
                $staff_id = M("staff")->add($data);

                $_POST["password"] = md5(md5(trim($_POST["password"])));
                $role_user_id = A("Manage/Staff")->add_role_user($staff_id, 1, $league_id);
                if ($role_user_id) {
                    $idcode = sprintf("A%03d", $role_user_id);
                    $data = array(
                        'idcode'=>$idcode,
                        'slug'=>Pinyin("admin"),
                        'user_id'=>$role_user_id
                    );
                    M('Staff')->where(array('staff_id'=>$staff_id))->setField($data);
                }
                M("league")->where("league_id=".$league_id)->setField("admin_staff_id", $staff_id);

                $navlist = M("navigation")->where(array("league_id"=>0))->select();
                foreach($navlist as $k=>$v) {
                    if ($v['module'] == "league") {
                        unset($navlist[$k]);
                        continue;
                    }
                    unset($navlist[$k]['id']);
                    $navlist[$k]['league_id'] = $league_id;
                }
                M("navigation")->addAll($navlist);
                delete_cache_temp();

                if($_POST['refer_url']) {
                    alert('success', "新建加盟商成功", $_POST['refer_url']);
                }else{
                    alert('success', "新建加盟商成功", U("league/view", "id=".$league_id));
                }
            } else {
                $this->alert = parseAlert();
                alert('error', "新建加盟商失败", $_POST['refer_url']);
            }

        }else{
            $this->fields_group = product_field_list_html("add","league", array(), "basic");
            $this->alert = parseAlert();
            $this->refer_url= refer_url('refer_add_url');
            $this->display();
        }
    }

    public function view(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $league_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $league_id) {
            alert('error', L('PARAMETER_ERROR'), U('league/index'));
        }
        $league = M('league')->where('league_id = %d ', $_GET['id'])->find();
        $this->league = $league;
        $this->fields_group = product_field_list_show('league', $league);
        $this->alert = parseAlert();
        $this->refer_url= refer_url('refer_view_url');
        $this->display();
    }

    public function getlist() {
        $this->ajaxReturn(M('league')->cache(true)->select());
    }

    public function perfect_list_item($value, $export = false, $leaguelock = false) {
        return parent::perfect_list_item($value, $export, $leaguelock);
    }


    public function edit(){
        $league = M('league')->where('league_id = %d',$this->_request('id'))->find();
        if (!$league) {
            alert('error', "没有这个加盟商",$_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()){
            $password_change = $_POST['password'] != $league['password'];
            if($this->submit_edit($league['league_id'])) {
                if ($password_change) {
                    $m_user = M('User');
                    $user = $m_user->where('staff_id = %d', $league['admin_staff_id'])->find();
                    $password = md5(md5(trim($_REQUEST["password"])) . $user['salt']);
                    $m_user->where('user_id =' . $user['user_id'])->setField(array('password'=>$password, 'lostpw_time'=>0));
                }
                delete_cache_temp();
                alert('success', "编辑加盟商成功", U('league/view', 'id='.$league['league_id']));
            } else {
                alert('error', "编辑加盟商失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            $alert = parseAlert();
            $this->alert = $alert;
            $this->league = $league;
            $this->model_id = $league['league_id'];
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->fields_group =  product_field_list_html("edit","league",$league);
            $this->display();
        }
    }


    public function listDialog(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $this->display("listDialog");
    }

}
