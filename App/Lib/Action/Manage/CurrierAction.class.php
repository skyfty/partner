
<?php 
class CurrierAction extends TrainAction {
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
                'logger',
            ),
			'allow'=>array(
                'getcurrierlist',
                'analytics',
                'validate',
                'check',
                'remove',
                'fenpei',
                'revert',
                'changecontent',
                'currierlock',
                'check_currier_limit',
                'excelimportdownload',
                'search',
                'getcurrieroriginal'
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

        $currier_id = intval($this->_request('id'));
        $currier = D('CurrierView')->where('currier.currier_id = %d',$currier_id)->find();
        if (!$currier) {
            alert('error', "没有找到这个培训项目",$_SERVER['HTTP_REFERER']);
        }

        if($_POST['submit']){
            $this->submit_auth($currier);
            alert('success', "修改培训项目账户成功", $_POST['refer_url'] ? $_POST['refer_url'] : U('currier/index'));
        }else{
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->currier_id = $currier_id;
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

        if ($_GET['category_id']) {
            $lw = like_filed_where($_GET['category_id']['value'], $_GET['category_id']['condition']);
            $cc = M("currier_category")->where(array("name"=>$lw))->field("currier_category_id")->select(false);
            $where['_string'] .= " AND currier.category_id in (".$cc.")";
            unset($where['category_id'], $where['currier.category_id']);
        }

        self::show_list_index_html($where, $params, "培训项目表");
    }


    function perfect_list_item($value, $export = false, $branchlock = false) {

        return parent::perfect_list_item($value, $export, $branchlock);
    }

    public function add_currier_basic() {
        $_POST['name'] = trim($_POST['name']);
        $_POST['league_id'] = session('league_id');

        if (!($currier_id = $this->submit_add("currier"))) {
            return false;
        }

        $idcode = sprintf("CW%07d", $currier_id);
        $data = array(
            'idcode'=>$idcode,
            'slug'=>Pinyin($this->_request("name")),
            'basic_submit_time'=>time(),
        );
        M('currier')->where(array('currier_id'=>$currier_id))->setField($data);
        return $currier_id;
    }


    public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if($this->isPost()){
            $currier_id = $this->add_currier_basic();
            if (!$currier_id) {
                alert('error',  "新建培训项目失败", $_SERVER['HTTP_REFERER']);
            }
            $this->log($currier_id, "新建培训项目", "新建培训项目成功");
            $this->alert = parseAlert();
            alert('success', "添加培训项目成功", U('currier/view', 'id='.$currier_id));
		}else{
            $this->fields_group = product_field_list_html("add","currier", array(), "basic");
            $alert = parseAlert();
            $this->alert = $alert;
            $this->display();
		}
	}

    public function delete(){
        if (!$_REQUEST['currier_id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $currier_idst = is_array($_REQUEST['currier_id']) ? $_REQUEST['currier_id'] : array($_REQUEST['currier_id']);
        $currier_ids = array();
        foreach(M('currier')->where(array('currier_id'=>array("in", $currier_idst)))->select() as $v) {
            if ($v['cultivate_used'] == 0) {
                $currier_ids[] = $v['currier_id'];
            }
        }
        if (count($currier_ids) > 0) {
            $flow_module = array(
                'currier_subgroup'=>'currier_subgroup'
            );
            if (!$this->submit_delete($currier_ids, $flow_module)) {
                alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
            }
            $this->log($currier_ids, "删除培训项目", "删除培训项目成功");
        }

        if (count($currier_idst) == 1 && count($currier_idst) != count($currier_ids)) {
            alert('error', "删除培训项目失败， 有关联的新培训订单", $_SERVER['HTTP_REFERER']);
        } else {
            alert('success', "删除培训项目成功", $_SERVER['HTTP_REFERER']);
        }
    }


	public function edit(){
		$currier = D('CurrierView')->where('currier.currier_id = %d',$this->_request('id'))->find();
		if (!$currier) {
            alert_back('error',  "培训项目不存在");
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if (!session('?admin') && session('restriction') === true && $currier['owner_role_id']) {
            if (!self::is_owner($currier, $branch = get_branch(session("role_id")))){
                alert_back('error',  "你没有权限");
            }
        }
        $assort = $this->_request('assort', 'trim', "basic");

		if($this->isPost()){
            $_POST['name'] = trim($_POST['name']);
            $_POST['slug'] = Pinyin($this->_request("name"));
            if (!$this->submit_edit($currier['currier_id'])) {
                alert_back('error',  "编辑培训项目失败", $_SERVER['HTTP_REFERER']);
            }

            $change_fields = D('CurrierView')->verity_check($currier);
            $basic_data = array(
            );
            $where = "currier_id=".$currier['currier_id'];

            M("currier")->where($where)->setField($basic_data);
            $this->add_edit_log($currier['currier_id'], "修改基本信息成功: ", $change_fields);

            alert('success', "编辑培训项目成功", U('currier/view', 'id='.$currier['currier_id']));

		}else{
            $currier['owner'] = D('RoleView')->where('role.role_id = %d', $currier['owner_role_id'])->find();

            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();

            $fields_group = product_field_list_html("edit","currier",$currier, $assort);
            $this->fields_group =$fields_group;
            $this->model_id = $currier['currier_id'];
            $this->currier = $currier;
            $this->display();
		}
	}


    private function add_edit_log($currier_id, $logcont, $change_fields) {
        foreach($change_fields as $v) {
            $logcont.=$v['name']."[".$v['oldvalue']."=>".$v['newvalue']."],";
        }
        $this->log($currier_id, "更新日志",$logcont);
    }

    public function listDialog(){
        if ($this->isAjax() === false) {
            return $this->display();
        }

        $data_field = array(
            array(
                "field"=>"currier_id",
                "order"=>"currier_id"
            ),
            array(
                "field"=>"idcode",
                "order"=>"idcode"
            ),
            array(
                "field"=>"currier_name",
                "order"=>"currier_name"
            ),
        );
        $m_model_name = $_GET['model']? $_GET['model']."View":"CurrierView";
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
        $val["currier_id"] = array(
            "currier_id"=>$val['currier_id']
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
        } else if ($_GET['status']) {
            $where['status'] = $_GET['status'];
        }
        $where['league_id'] = session('league_id');
        return $where;
    }

    public function view(){
        $this->viewinfo("basic");
	}

    public function viewinfo($assort) {
        $currier_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $currier_id) {
            alert('error', L('parameter_error'), U("Currier/index"));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $where = array("currier.currier_id"=>$currier_id);
        $currier = D('CurrierView')->where($where)->find();
        if (!$currier) {
            alert('error', L('PARAMETER_ERROR'), U("Currier/index"));
        }

        if (!session('?admin') && session('restriction') === true) {
            if (!self::is_interest($currier, $branch = get_branch(session("role_id")))) {
                alert('error', "你没有权限访问", U("Currier/index"));
            }
        }

        $this->currier = $currier;
        $this->assort = $assort;
        $this->currier_id = $currier_id;

        if (($branch || $authority == "受限") && !in_array($currier['currier_id'], self::get_astrict_list())) {
            $currier = self::fix_branch_fields(getBranchFields("currier"), $currier, in_array($currier['owner_role_id'], $branch));
        }
        $this->is_owner = ($branch && $currier['owner_role_id'] ? self::is_owner($currier, $branch) : true);

        $fields_group = product_field_list_show('currier', $currier, $assort);
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
                "currier.currier_id"=>$id
            );
        }
        $info = D('CurrierView')->where($where)->find();
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
            self::default_cycle_basis_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab, "培训项目");
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
                    "field"=>"currier_show",
                    "order"=>"currier_id"
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

        if ($_GET['assort'] == "entrance") {
            $where['r_currier_log.assort'] =  "entrance";
        } else {
            $where['r_currier_log.assort'] =  array('neq', "entrance");
        }

        if ($_GET['currier_id']) {
            $where['r_currier_log.currier_id'] = $_GET['curriert_id'];
        }

        if ($_GET['create_role_id']) {
            $where['log.role_id'] = $_GET['create_role_id'];
        }
        if ($_GET['subject']) {
            $where['log.subject'] = $_GET['subject'];
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['log.content'] =  array('like', "%".$_REQUEST['search']['value']."%");
        }
        $where['league_id'] = session('league_id');

        $data = make_data_list("CurrierLogView", $where, $data_field, array($this, "format_currier_log"));
        $this->ajaxReturn($data,'JSON');
    }

    public function format_currier_log($v) {
        $v['create_date_show'] = toDate($v['create_date']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];

        $v['content_show'] = "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>".cutString($v['content'], 43)."</a>";
        return $v;
    }


    public function log($assort="update", $currier_id,$subject, $content) {
        $log_id = 0;
        $curriers = M("currier")->where( array('currier_id'=>array("in", $currier_id)))->select();
        foreach($curriers as $v) {
            $m_log = M('Log');
            $m_log->role_id = session("role_id");
            $m_log->subject = $subject;
            $m_log->content = $content;
            $m_log->category_id = 6;
            $m_log->create_date = time();
            $m_log->update_date = time();
            if ($log_id = $m_log->add()) {
                $data['currier_id'] = $v['currier_id'];
                $data['currier_name'] = $v['name'];
                $data['currier_idcode'] = $v['idcode'];
                $data['assort'] = $assort;
                $data['log_id'] = $log_id;
                $data['league_id'] = session('league_id');
                M('r_currier_log')->add($data);
            }
        }
        return $log_id;
    }

    public function logger() {
        $this->assort = $_GET['assort'] ? $_GET['assort']:"default";
        $this->display("logger_".$this->assort); // 输出模板
    }

    public function category($module = "") {
        parent::category("currier");
    }


    public function category_order($module = "") {
        parent::category_order("currier");

    }

    public function addcategory($module = "") {
        parent::addcategory("currier");
    }

    public function categoryedit($module = "") {
        parent::categoryedit("currier");

    }

    public function delcategory($module = "") {
        parent::delcategory("currier");
    }

    public function getcategory($module = "") {
        parent::getcategory("currier");

    }

    public function getcategoryselect($module = "") {
        parent::getcategoryselect("currier");
    }
}