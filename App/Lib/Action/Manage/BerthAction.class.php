
<?php 
class BerthAction extends BaseAction {
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
                'quick_create_berth',
                'get_account_total',
                'logger',
                'mind',
            ),
			'allow'=>array(
                'getberthlist',
                'analytics',
                'validate',
                'check',
                'remove',
                'fenpei',
                'revert',
                'changecontent',
                'berthlock',
                'check_berth_limit',
                'excelimportdownload',
                'search',
                'getberthoriginal',
                'list_col_filter_select'
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

        $berth_id = intval($this->_request('id'));
        $berth = D('BerthView')->where('berth.berth_id = %d',$berth_id)->find();
        if (!$berth) {
            alert('error', "没有找到这个床位",$_SERVER['HTTP_REFERER']);
        }

        if($_POST['submit']){
            $this->submit_auth($berth);
            alert('success', "修改床位账户成功", $_POST['refer_url'] ? $_POST['refer_url'] : U('berth/index'));
        }else{
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->berth_id = $berth_id;
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

        self::show_list_index_html($where, $params, "床位表");
    }

    public function mind() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $where = array();

        $branch_id = $_GET['bybr'] != "" ? $_GET['bybr']:"";
        if ($branch_id != "") {
            $params[] = "bybr=" . trim($_GET['bybr']);
            $this->branch =  $branch_id;
            $branch_where = array("branch_id"=>$branch_id);
            if ($branch_id == 0) {
                $this->branch_name = "公司总部";

            } else {
                $this->branch_name = M("branch")->where("branch_id=".$branch_id)->getField("name");
            }
        }
        else {
            $branch_where = array();
            $this->branch_name = "全部门店";
        }


        if (session('user_id') == 1) {
            if ($_REQUEST['bylea']) {
                $branch_where['league_id'] = $_REQUEST['bylea'];
                $params[] = "bylea=".trim($_GET['bylea']);
                $this->league = M("league")->where(array('league_id'=> $branch_where['league_id']))->find();
            }
        } else {
            $branch_where['league_id'] = session('league_id');
        }


        if ($_GET['dorm_id']) {
            $where['dorm_id'] = $_GET['dorm_id'];
            $params[] = "dorm_id=" . trim($_GET['dorm_id']);
            $this->dorm =  $_GET['dorm_id'];
        }
        $mind_data = array(array("id"=>"root","isroot"=>true, "topic"=>$this->branch_name));
        foreach(M("branch")->where($branch_where)->select() as $branch) {
            $branch_data = array();
            $branch_data["id"] = 'branch_' . $branch['branch_id'];
            $branch_data["parentid"] = "root";
            $branch_data["topic"] = "<a style='color: white' target='_blank' href='" . U('branch/view', 'id=' . $branch['branch_id']) . "'>" . $branch['name'] . "</a>";

            $where['branch_id'] = $branch['branch_id'];
            foreach(M("dorm")->where($where)->select() as $dorm) {
                $dorm_data = array();
                $dorm_data["id"] = 'dorm_' . $dorm['dorm_id'];
                $dorm_data["parentid"] = $branch_data["id"];
                $dorm_data["topic"] = "<a style='color: white' target='_blank' href='" . U('dorm/view', 'id=' . $dorm['dorm_id']) . "'>" . $dorm['name'] . "</a>";

                foreach (D("BerthView")->where(array("dorm_id" => $dorm['dorm_id']))->select() as $berth) {
                    $berth_data = array();
                    $berth_data["parentid"] = $dorm_data["id"];
                    $berth_data["id"] = 'berth_' . $berth['berth_id'];
                    $berth_data["topic"] = "<a  style='color: white' target='_blank' href='" . U('berth/view', 'id=' . $berth['berth_id']) . "'>" . $berth['name'] . "</a>";

                    if ($berth['product_id'] != "") {
                        $berth_data["background-color"] = "#f1c40f";
                    } else if ($berth['status'] == "停用") {
                        $berth_data["background-color"] = "#afafd0";
                    } else {
                        $berth_data["background-color"] = "";
                    }
                    $mind_data[] = $berth_data;

                    if ($berth['place']) {
                        $berth_data["parentid"] = $berth_data["id"];
                        $berth_data["id"] = 'place_' . $berth['berth_id'];
                        $berth_data["topic"] = "<a  style='color: white' target='_blank' href='" . U('berth/view', 'id=' . $berth['berth_id']) . "'>" . $berth['place'] . "</a>";
                        if ($berth['product_id'] != "") {
                            $berth_data["background-color"] = "#f1c40f";
                        } else if ($berth['status'] == "停用") {
                            $berth_data["background-color"] = "#afafd0";
                        } else {
                            $berth_data["background-color"] = "";
                        }
                        $mind_data[] = $berth_data;
                    }

                    if ($berth['product_id']) {
                        $berth_data["background-color"] = "";
                        $berth_data["parentid"] = $berth_data["id"];
                        $berth_data["id"] = 'product_' . $berth['berth_id'];
                        $berth_data["topic"] = "<a style='color: white' target='_blank' href='" . U('product/view', 'id=' . $berth['product_id']) . "'>" . $berth['product_name'] . "</a>";
                        $mind_data[] = $berth_data;
                    }

                    if ($berth['status'] != "停用") {
                        $berth_data["background-color"] = "";
                        $berth_data["parentid"] = $berth_data["id"];
                        $berth_data["id"] = 'options_' . $berth['berth_id'];
                        if ($berth['product_id']) {
                            $berth_data["topic"] = "<a ref='" . $berth['berth_id'] . "' status='" . $berth['status'] . "' class='entry_status' href='javascript:void(0);'>退住</a>";
                        } else {
                            $berth_data["topic"] = "<a ref='" . $berth['berth_id'] . "' status='" . $berth['status'] . "' class='entry_status' href='javascript:void(0);'>入住</a>";
                        }
                        $mind_data[] = $berth_data;
                    }
                }
                $mind_data[] = $dorm_data;
            }
            $mind_data[] = $branch_data;

        }
        $this->mind_data = $mind_data;
        $this->alert = parseAlert();
        session($this->module."_index_refer_url", $_SERVER['REQUEST_URI']);
        $this->display("mind");
    }

    function perfect_list_item($value, $export = false, $branchlock = false) {
        if ($value['product_id'] =="") {
            $value['entry_time'] = "";
        }
        if ($value['status'] == "入住") {
            $berth['entry_days'] = day(time() - $value['entry_time']);
        }
        return parent::perfect_list_item($value, $export, $branchlock);
    }

    public function count_berth_amount($dorm_id) {
        return M("berth")->where(array("dorm_id"=>$dorm_id))->count();
    }


    public function add_berth_basic($dorm) {
        $_POST['branch_id'] = trim($dorm['branch_id']);
        $_POST['name'] = trim($_POST['name']);
        $_POST['league_id'] = session('league_id');

        if (!($berth_id = $this->submit_add("berth"))) {
            return false;
        }

        $idcode = sprintf("CW%07d", $berth_id);
        $data = array(
            'idcode'=>$idcode,
            'slug'=>Pinyin($this->_request("name")),
            'basic_submit_time'=>time()
        );
        M('berth')->where(array('berth_id'=>$berth_id))->setField($data);
        update_dorm_info($_POST['dorm_id']);
        $this->log($berth_id, "新建床位", "快速新建床位成功");
        return $berth_id;
    }


    public function add(){
		if($this->isPost()){
            $dorm = M("dorm")->where(array("dorm_id"=>$_POST['dorm_id']))->find();
            if (!$dorm) {
                alert('error',  "新建床位失败, 没有这个宿舍", $_SERVER['HTTP_REFERER']);
            }

            if ($this->count_berth_amount($dorm['dorm_id']) >= $dorm['berth_max']) {
                alert('error',  "新建床位失败, 这个宿舍床位已经满了", $_SERVER['HTTP_REFERER']);
            }
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            $berth_id = $this->add_berth_basic($dorm);
            if (!$berth_id) {
                alert('error',  "新建床位失败", $_SERVER['HTTP_REFERER']);
            }

            $this->log($berth_id, "新建床位", "新建床位成功");
            $this->alert = parseAlert();
            alert('success', "添加床位成功", U('berth/view', 'id='.$berth_id));
		}else{
            $this->fields_group = product_field_list_html("add","berth", array(), "basic");
            $alert = parseAlert();
            $this->alert = $alert;
            $this->display();
		}
	}

    public function delete(){
        if (!$_REQUEST['berth_id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $berth_idst = is_array($_REQUEST['berth_id']) ? $_REQUEST['berth_id'] : array($_REQUEST['berth_id']);
        $berth_ids = array();
        foreach(M('berth')->where(array('berth_id'=>array("in", $berth_idst)))->select() as $v) {
            if (!$v['product_id']) {
                $berth_ids[] = $v['berth_id'];
            }
        }
        if (count($berth_ids) > 0) {
            $delete_where = array(
                'berth_id'=>array("in", $berth_ids)
            );
            $dorm_ids = M('berth')->where($delete_where)->getField("dorm_id");

            $flow_module = array(
                'berth_subgroup'=>'berth_subgroup'
            );
            if (!$this->submit_delete($berth_ids, $flow_module)) {
                alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
            }
            update_dorm_info($dorm_ids);
            $this->log($berth_ids, "删除床位", "删除床位成功");
        }

        if (count($berth_idst) == 1 && count($berth_idst) != count($berth_ids)) {
            alert('error', "删除床位失败， 宿舍有人入住", $_SERVER['HTTP_REFERER']);
        } else {
            alert('success', "删除床位成功", $_SERVER['HTTP_REFERER']);
        }
    }


	public function edit(){
		$berth = D('BerthView')->where('berth.berth_id = %d',$this->_request('id'))->find();
		if (!$berth) {
            alert_back('error',  "床位不存在");
        }
        if (!session('?admin') && session('restriction') === true && $berth['owner_role_id']) {
            if (!self::is_owner($berth, $branch = get_branch(session("role_id")))){
                alert_back('error',  "你没有权限");
            }
        }
        $assort = $this->_request('assort', 'trim', "basic");
        $berth['owner'] = D('RoleView')->where('role.role_id = %d', $berth['owner_role_id'])->find();

		if($this->isPost()){
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            if ($_POST['status'] == "停用" && $berth['status'] == "入住") {
                alert('error', "这个床位正在使用中");
            }

            $dorm = M("dorm")->where(array("dorm_id"=>$_POST['dorm_id']))->find();
            if (!$dorm) {
                alert('error',  "编辑床位失败, 没有这个宿舍", $_SERVER['HTTP_REFERER']);
            }

            if ($_POST['dorm_id'] != $berth['dorm_id'] && $this->count_berth_amount($dorm['dorm_id']) >= $dorm['berth_max']) {
                alert('error',  "编辑床位失败, 这个宿舍床位已经满了", $_SERVER['HTTP_REFERER']);
            }

            $_POST['branch_id'] = $dorm['branch_id'];
            $_POST['name'] = trim($_POST['name']);
            $_POST['slug'] = Pinyin($this->_request("name"));
            if (!$this->submit_edit($berth['berth_id'])) {
                alert_back('error',  "编辑床位失败", $_SERVER['HTTP_REFERER']);
            }

            $change_fields = D('BerthView')->verity_check($berth);
            $basic_data = array(
                "basic_submit_time"=>time(),
            );
            $where = "berth_id=".$berth['berth_id'];

            if ($_POST['status'] == "停用") {
                $basic_data['owner_role_id'] = session("role_id");
            }

            M("berth")->where($where)->setField($basic_data);
            $this->add_edit_log($berth['berth_id'], "修改基本信息成功: ", $change_fields);

            update_dorm_info($berth['dorm_id']);
            if ($berth['dorm_id'] != $_POST['dorm_id']) {
                update_dorm_info($_POST['dorm_id']);
            }
            alert('success', "编辑床位成功", U('berth/view', 'id='.$berth['berth_id']));

		}else{
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();
            $fields_group = product_field_list_html("edit","berth",$berth, $assort);
            $this->fields_group =$fields_group;
            $this->model_id = $berth['berth_id'];
            $this->berth = $berth;
            $this->display();
		}
	}


    private function add_edit_log($berth_id, $logcont, $change_fields) {
        foreach($change_fields as $v) {
            $logcont.=$v['name']."[".$v['oldvalue']."=>".$v['newvalue']."],";
        }
        $this->log($berth_id, "更新日志",$logcont);
    }


    public function listDialog(){
        if ($this->isAjax() === false) {
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            return $this->display();
        }
        $data_field = array(
            array(
                "field"=>"berth_id",
                "order"=>"berth_id"
            ),
            array(
                "field"=>"idcode",
                "order"=>"idcode"
            ),
            array(
                "field"=>"berth_name",
                "order"=>"berth_name"
            ),
        );
        $m_model_name = $_GET['model']? $_GET['model']."View":"BerthView";
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
        $val["berth_id"] = array(
            "berth_id"=>$val['berth_id']
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
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->viewinfo("basic");
	}

    public function viewinfo($assort) {
        $berth_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $berth_id) {
            alert('error', L('parameter_error'), U("Berth/index"));
        }
        $where = array("berth.berth_id"=>$berth_id);
        $berth = D('BerthView')->where($where)->find();
        if (!$berth) {
            alert('error', L('PARAMETER_ERROR'), U("Berth/index"));
        }

        if (!session('?admin') && session('restriction') === true) {
            if (!self::is_interest($berth, $branch = get_branch(session("role_id")))) {
                alert('error', "你没有权限访问", U("Berth/index"));
            }
        }

        if ($berth['status'] == "入住") {
            $berth['entry_days'] = day(time() - $berth['entry_time']);
        }

        $this->berth = $berth;
        $this->assort = $assort;
        $this->berth_id = $berth_id;

        if (($branch || $authority == "受限") && !in_array($berth['berth_id'], self::get_astrict_list())) {
            $berth = self::fix_branch_fields(getBranchFields("berth"), $berth, in_array($berth['owner_role_id'], $branch));
        }
        $this->is_owner = ($branch && $berth['owner_role_id'] ? self::is_owner($berth, $branch) : true);

        $fields_group = product_field_list_show('berth', $berth, $assort);
        $this->fields_group =$fields_group;
        $this->refer_url= session($this->module."_index_refer_url");
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
                "berth.berth_id"=>$id
            );
        }
        $info = D('BerthView')->where($where)->find();
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
            self::default_cycle_basis_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab, "床位");
        }

        $this->parameter = implode('&', $params);
        $this->assort = $assort;
        $this->alert = parseAlert();
        $this->display($assort."_analytics".$tab);
    }


    public function logtable() {
        if ($_GET['assort'] == "entrance") {
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
                    "field"=>"product_show",
                    "order"=>"product_id"
                ),
                array(
                    "field"=>"subject",
                    "order"=>"subject"
                ),
                array(
                    "field"=>"entrance_date_show",
                    "order"=>"entrance_date"
                ),
                array(
                    "field"=>"existrce_date_show",
                    "order"=>"existrce_date"
                ),
                array(
                    "field"=>"entryday_show",
                    "order"=>"entryday"
                ),
                array(
                    "field"=>"branch_show",
                    "order"=>"branch_id"
                ),
                array(
                    "field"=>"dorm_show",
                    "order"=>"dorm_show"
                ),
                array(
                    "field"=>"berth_show",
                    "order"=>"berth_id"
                ),

                array(
                    "field"=>"content_show",
                    "order"=>"content"
                ),
            );
        } else {
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
                    "field"=>"berth_show",
                    "order"=>"berth_id"
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
        }



        $where = array();
        if ($_GET['start_time'] || $_GET['end_time']) {
            $where['log.create_date'] =  array('between', make_time_between());
        }

        if ($_GET['assort'] == "entrance") {
            $where['r_berth_log.assort'] =  "entrance";
        } else {
            $where['r_berth_log.assort'] =  array('neq', "entrance");
        }

        if ($_GET['berth_id']) {
            $where['r_berth_log.berth_id'] = $_GET['bertht_id'];
        }

        if ($_GET['create_role_id']) {
            $where['log.role_id'] = $_GET['create_role_id'];
        }
        if ($_GET['subject']) {
            $where['log.subject'] = $_GET['subject'];
        }
        if ($_GET['product_id']) {
            $where['r_berth_log.product_id'] = $_GET['product_id'];
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['log.content'] =  array('like', "%".$_REQUEST['search']['value']."%");
        }

        $branch_id = $_GET['branch_id'] != "" ? $_GET['branch_id']:"";
        if ($branch_id != "") {
            $where['dorm.branch_id'] = $branch_id;
        }
        $where['league_id'] = session('league_id');

        $data = make_data_list("BerthLogView", $where, $data_field, array($this, "format_berth_log"));
        $this->ajaxReturn($data,'JSON');
    }

    public function format_berth_log($v) {
        $v['create_date_show'] = toDate($v['create_date']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        $v['entrance_date_show'] = toDate($v['entrance_date'],"Y-m-d");
        $v['existrce_date_show'] = toDate($v['existrce_date'],"Y-m-d");
        $v['dorm_show'] = $v['dorm_name'];
        $v['berth_show'] = $v['berth_name'] ;
        $v['branch_show'] = $v['branch_name'] ;
        $v['entryday_show'] = floor (day($v['entryday'])) ;


        $v['product_show'] = product_show_html($v['product_id'], false);
        $v['content_show'] = "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>".cutString($v['content'], 43)."</a>";
        return $v;
    }

    public function entrance() {
        $berth = D('BerthView')->where('berth.berth_id = %d',$this->_request('berth_id'))->find();
        if (!$berth) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if ($this->isAjax()) {
            $this->berth = $berth;
            $this->display($this->_request('status') == "入住"?"exitry_dialog":"entry_dialog"); // 输出模板
        } else {
            $status = $this->_request('status');
            $entry_time = strtotime($_POST["entry_time"]);
            $m_data = array(
                "owner_role_id"=>session("role_id"),
            );
            $content = $this->_request('content');
            if ($status == "入住") {
                $product_id = $this->_request('product_id');;
                $m_data["status"]=$status;
                $m_data["entry_time"]=$entry_time;
                $m_data["product_id"]=$product_id;
                $this->add_entrance_log($berth, $status, $content , $m_data["product_id"], $entry_time);

            } else {
                $product_id = $berth['product_id'];
                $m_data["status"]="空闲";
                $m_data["product_id"]="";
                $m_data["entry_time"]="";
                $entryday = $entry_time - $berth['entry_time'];
                M("r_berth_log")->where(array("berth_id"=>$berth['berth_id']))->setField(array("existrce_date"=>$entry_time, "entryday"=>$entryday));
                $this->add_entrance_log($berth, $status, $content , $berth["product_id"], $berth['entry_time'], $entry_time, $entryday);
            }
            M("berth")->where(array("berth_id"=>$berth['berth_id']))->setField($m_data);
            update_dorm_info($berth['dorm_id']);

            $pdata = array(
                "entry_time"=>$m_data["entry_time"],
                "entry_state"=>($status == "入住"?"入住":"未入住"),
                "entry_berth"=>($status == "入住"?$berth['berth_id']:""),
            );
            M("product")->where(array("product_id"=>$product_id))->setField($pdata);

            alert('success', "操作完成", $_SERVER['HTTP_REFERER']);
        }
    }

    public function add_entrance_log($berth, $status, $logcont, $product_id, $entrance_date,$existrce_date="", $entryday="") {
        $this->log("entrance", $berth['berth_id'], $status,$logcont, $product_id, $entrance_date, $existrce_date,$entryday);
    }


    public function log($assort="update", $berth_id,$subject, $content, $product_id="", $entrance_date="", $existrce_date="",$entryday="") {
        $log_id = 0;
        $berths = M("berth")->where( array('berth_id'=>array("in", $berth_id)))->select();
        foreach($berths as $v) {
            $m_log = M('Log');
            $m_log->role_id = session("role_id");
            $m_log->subject = $subject;
            $m_log->content = $content;
            $m_log->category_id = 6;
            $m_log->create_date = time();
            $m_log->update_date = time();
            if ($log_id = $m_log->add()) {
                $data['berth_id'] = $v['berth_id'];
                $data['berth_name'] = $v['name'];
                $data['berth_idcode'] = $v['idcode'];
                $data['product_id'] = $product_id;
                $data['entrance_date'] = $entrance_date;
                $data['existrce_date'] = $existrce_date;
                $data['assort'] = $assort;
                $data['entryday'] = $entryday;
                $data['log_id'] = $log_id;
                $data['league_id'] = session('league_id');
                M('r_berth_log')->add($data);
            }
        }
        return $log_id;
    }

    public function logger() {
        $this->assort = $_GET['assort'] ? $_GET['assort']:"default";
        $this->display("logger_".$this->assort); // 输出模板
    }
}