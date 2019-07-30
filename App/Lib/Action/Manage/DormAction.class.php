
<?php 
class DormAction extends BaseAction {
	public function _initialize(){
        if (NO_AUTHORIZE_CHECK === true)
            return;
		$action = array(
			'permission'=>array(
                'search',
                'send_sms',
                'change_authInfo',
                'listdialog',
                'group',
                'getinfo',
                'delimg',
                'trainview',
                'tradeview',
                'deletevideo',
                'deletefile',
                'allstancegroupdialog',
                'removegroupstance',
                'addgroupstance',
                'sendDialog',
                'analytics',
                'mission',
                'quick_create_dorm',
                'get_account_total'
            ),
			'allow'=>array(
                'getdormlist',
                'analytics',
                'validate',
                'check',
                'remove',
                'fenpei',
                'revert',
                'changecontent',
                'dormlock',
                'check_dorm_limit',
                'excelimportdownload',
                'search',
                'getdormoriginal'
            )
		);
        if ($_REQUEST['act'] && ACTION_NAME == "index") {
            $_REQUEST['t'] = $_REQUEST['act'];
        }
		B('Authenticate', $action);
	}

    public function change_authInfo() {
        if (!isset($_GET['id'])) {
            die("Please provide a date range.");
        }

        $dorm_id = intval($this->_request('id'));
        $dorm = D('DormView')->where('dorm.dorm_id = %d',$dorm_id)->find();
        if (!$dorm) {
            alert('error', "没有找到这个宿舍",$_SERVER['HTTP_REFERER']);
        }

        if($_POST['submit']){
            $this->submit_auth($dorm);
            alert('success', "修改宿舍账户成功", $_POST['refer_url'] ? $_POST['refer_url'] : U('dorm/index'));
        }else{

            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->dorm_id = $dorm_id;
            $this->display();
        }
    }


    public function make_astrict_where($brat = true, $branch=null) {
        $map['dorm.branch_id'] = session('branch_id');
        return $map;
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
            if ($_GET['lia'] == 'self') {
                $where['owner_role_id'] = session('role_id');
            } else if(vali_permission("branchlock", "dorm")) {
                $where['_complex'] = $this->make_astrict_where();
            }
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
            $where['dorm.branch_id'] = $branch_id;
            $params[] = "bybr=" . trim($_GET['bybr']);
            $this->branch =  $branch_id;
        }

        self::show_list_index_html($where, $params, "宿舍表");
    }



    function perfect_list_item($value, $export = false, $branchlock = false) {
        return parent::perfect_list_item($value, $export, $branchlock);
    }

    public function add_dorm_basic() {
        $_POST['name'] = trim($_POST['name']);
        $_POST['league_id'] = session('league_id');

        if (!($dorm_id = $this->submit_add("dorm"))) {
            return false;
        }

        $idcode = sprintf("SS%07d", $dorm_id);
        $data = array(
            'idcode'=>$idcode,
            'slug'=>Pinyin($this->_request("name")),
            'basic_submit_time'=>time(),
        );

        M('dorm')->where(array('dorm_id'=>$dorm_id))->setField($data);
        update_dorm_info($dorm_id);

        $this->log($dorm_id, "新建宿舍", "快速新建宿舍成功");
        return $dorm_id;
    }



    public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()){

            $where['status'] = "入住";
            $data['berth_used'] = M("berth")->where($where)->count();

            $dorm_id = $this->add_dorm_basic();
            if (!$dorm_id) {
                alert('error',  "新建宿舍失败", $_SERVER['HTTP_REFERER']);
            }

            $this->log($dorm_id, "新建宿舍", "新建宿舍成功");
            $this->alert = parseAlert();
            alert('success', "添加宿舍成功", U('dorm/view', 'id='.$dorm_id));
		}else{
            $this->fields_group = product_field_list_html("add","dorm", array(), "basic");
            $alert = parseAlert();
            $this->alert = $alert;
            $this->display();
		}
	}

    public function delete(){
        if (!$_REQUEST['dorm_id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'), $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $dorm_idst = is_array($_REQUEST['dorm_id']) ? $_REQUEST['dorm_id'] : array($_REQUEST['dorm_id']);
        $dorm_ids = array();
        foreach(M('dorm')->where(array('dorm_id'=>array("in", $dorm_idst)))->select() as $v) {
            if ($v['berth_used'] == 0) {
                $dorm_ids[] = $v['dorm_id'];
            }
        }
        if (count($dorm_ids) > 0) {
            $berth_ids = M('berth')->where(array('dorm_id'=>array("in", $dorm_ids)))->getField("berth_id", true);
            $flow_module = array(
                'dorm_subgroup'=>'dorm_subgroup'
            );
            if (!$this->submit_delete($dorm_ids, $flow_module)) {
                alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
            }

            define("NO_AUTHORIZE_CHECK", true);
            $flow_module = array(
                'berth_subgroup'=>'berth_subgroup'
            );
            A("Manage/Berth")->submit_delete($berth_ids, $flow_module);

            update_dorm_info($dorm_ids);
            $this->log($dorm_ids, "删除宿舍", "删除宿舍成功");
        }

        if (count($dorm_idst) == 1 && count($dorm_idst) != count($dorm_ids)) {
            alert('error', "删除宿舍失败， 宿舍有人入住", $_SERVER['HTTP_REFERER']);
        } else {
            alert('success', "删除宿舍成功", $_SERVER['HTTP_REFERER']);
        }
    }


	public function edit(){
		$dorm = D('DormView')->where('dorm.dorm_id = %d',$this->_request('id'))->find();
		if (!$dorm) {
            alert_back('error',  "宿舍不存在");
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if (!session('?admin') && session('restriction') === true && $dorm['owner_role_id']) {
            if (!self::is_owner($dorm, $branch = get_branch(session("role_id")))){
                alert_back('error',  "你没有权限");
            }
        }
        $assort = $this->_request('assort', 'trim', "basic");
        $dorm['owner'] = D('RoleView')->where('role.role_id = %d', $dorm['owner_role_id'])->find();

		if($this->isPost()){
            $_POST['name'] = trim($_POST['name']);
            $_POST['slug'] = Pinyin($this->_request("name"));
            if (!$this->submit_edit($dorm['dorm_id'])) {
                alert_back('error',  "编辑宿舍失败");
            }

            $change_fields = D('DormView')->verity_check($dorm);
            $basic_data = array(
                "basic_submit_time"=>time(),
            );

            $where = "dorm_id=".$dorm['dorm_id'];
            M("dorm")->where($where)->setField($basic_data);
            $this->add_edit_log($dorm['dorm_id'], "修改基本信息成功: ", $change_fields);
            update_dorm_info($dorm['dorm_id']);

            alert('success', "编辑宿舍成功", U('dorm/view', 'id='.$dorm['dorm_id']));

		}else{
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();

            //雇员图片
            $fields_group = product_field_list_html("edit","dorm",$dorm, $assort);
            $this->fields_group =$fields_group;
            $this->model_id = $dorm['dorm_id'];
            $this->dorm = $dorm;
            $this->display();
		}
	}


    private function add_edit_log($dorm_id, $logcont, $change_fields) {
        foreach($change_fields as $v) {
            $logcont.=$v['name']."[".$v['oldvalue']."=>".$v['newvalue']."],";
        }
        $this->log($dorm_id, "更新日志",$logcont);
    }

    public function log($dorm_ids, $subject, $content, $category_id = 6) {
        $log_id = 0;
        $dorms = M("dorm")->where( array('dorm_id'=>array("in", $dorm_ids)))->select();
        foreach($dorms as $v) {
            $m_log = M('Log');
            $m_log->role_id = session("role_id");
            $m_log->subject = $subject;
            $m_log->content = $content;
            $m_log->category_id = $category_id;
            $m_log->create_date = time();
            $m_log->update_date = time();
            if ($log_id = $m_log->add()) {
                $data['dorm_id'] = $v['dorm_id'];
                $data['dorm_name'] = $v['name'];
                $data['dorm_idcode'] = $v['idcode'];
                $data['log_id'] = $log_id;
                $data['league_id'] = session('league_id');
                M('r_dorm_log')->add($data);
            }
        }
        return $log_id;
    }

    public function listDialog(){
        if ($this->isAjax() === false) {
            return $this->display();
        }

        $data_field = array(
            array(
                "field"=>"dorm_id",
                "order"=>"dorm_id"
            ),
            array(
                "field"=>"idcode",
                "order"=>"idcode"
            ),
            array(
                "field"=>"dorm_name",
                "order"=>"dorm_name"
            ),
        );
        $m_model_name = $_GET['model']? $_GET['model']."View":"DormView";
        $where = $this->parse_dialog_where();
        $this->ajaxReturn(make_data_list($m_model_name, $where, $data_field, array($this, "format_dialog_item")),'JSON');
    }


    public function format_index_fields($field_array) {
        $m_module = $this->get_module_view();
        foreach($field_array as $k=>$v) {
            if ($v['form_type'] == "number") {
                $sum_cnt = $m_module->where($this->list_where)->sum($v['field']);
                $v['link_title'] = $v['name']."合计：" . number_format($sum_cnt, 2);
            } else {
                $v['link_title'] = $v['name'];
            }
            $field_array[$k] = $v;
        }
        return $field_array;
    }

    public function format_dialog_item($val) {
        $val["dorm_id"] = array(
            "dorm_id"=>$val['dorm_id']
        );
        return $val;
    }

    public function parse_dialog_where() {
        $where = parent::parse_dialog_where();
        if ($_GET['model']) {
            $where['_string'] =trim($_GET['query']);
        } else if ($_GET['lia']) {
            if ($_GET['lia'] == 'self') {
                $where['owner_role_id'] = session('role_id');
            } elseif (session('branch_id')) {
                $where['_complex'] = self::make_astrict_where(false);
            }
        }
        $where['league_id'] = session('league_id');

        return $where;
    }

    public function view(){
        $this->viewinfo("basic");
	}

    public function viewinfo($assort) {
        $dorm_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $dorm_id) {
            alert('error', L('parameter_error'), U("Dorm/index"));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $where = array("dorm.dorm_id"=>$dorm_id);
        $dorm = D('DormView')->where($where)->find();
        if (!$dorm) {
            alert('error', L('PARAMETER_ERROR'), U("Dorm/index"));
        }

        if (!session('?admin') && session('restriction') === true) {
            if (!self::is_interest($dorm, $branch = get_branch(session("role_id")))) {
                alert('error', "你没有权限访问", U("Dorm/index"));
            }
        }

        $this->dorm = $dorm;
        $this->assort = $assort;
        $this->dorm_id = $dorm_id;

        if (($branch || $authority == "受限") && !in_array($dorm['dorm_id'], self::get_astrict_list())) {
            $dorm = self::fix_branch_fields(getBranchFields("dorm"), $dorm, in_array($dorm['owner_role_id'], $branch));
        }
        $this->is_owner = ($branch && $dorm['owner_role_id'] ? self::is_owner($dorm, $branch) : true);

        $fields_group = product_field_list_show('dorm', $dorm, $assort);
        $this->fields_group =$fields_group;
        $this->refer_url= session("index_refer_url");

        $this->alert = parseAlert();
        $this->display();
    }


    public function getInfo() {
        if ($_REQUEST['by'] && $_REQUEST[$_REQUEST['by']]) {
            $by = $this->_request('by');
            $where = array(
                $by=>$this->_request($by)
            );
        } else {
            $id = $this->_request('id');
            $where = array(
                "dorm.dorm_id"=>$id
            );
        }
        $info = D('DormView')->where($where)->find();
        $this->ajaxReturn($info,"",$info ? 1:0);
    }


    public function analytics(){
        $params = array();
        $assort = $_GET['assort'] ? $_GET['assort'] : "state";
        if ($_GET['assort']) {
            $params[] = "assort=".$_GET['assort'];
        }
        $time_limit = self::default_statistics_time($params);

        if ($assort == "newly") {
            $tab = "_".($_GET['tab'] ? $_GET['tab'] : "charts");
            if ($_GET['tab']) {
                $params[] = "tab=".$_GET['tab'];
            }

            $cycle = $_GET['cycle'] ? $_GET['cycle'] : "week";
            if ($_GET['cycle']) {
                $params[] = "cycle=".$_GET['cycle'];
            }
            self::default_cycle_basis_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab, "宿舍");
        }

        $this->parameter = implode('&', $params);
        $this->assort = $assort;
        $this->alert = parseAlert();
        $this->display($assort."_analytics".$tab);
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
                "field"=>"dorm_show",
                "order"=>"dorm_id"
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

        $data = make_data_list("DormLogView", $where, $data_field, array($this, "format_dorm_log"));
        $this->ajaxReturn($data,'JSON');
    }

    public function format_dorm_log($v) {
        $v['create_date_show'] = toDate($v['create_date']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        $html = product_show_html($v, false);;
        if (!$html) {
            $html = '<span>[' .$v['dorm_idcode'].'] '.$v['dorm_name'] . '</span>&nbsp;';;
        }
        $v['dorm_show'] = $html;
        $v['content_show'] = "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>".cutString($v['content'], 43)."</a>";

        return $v;
    }

}