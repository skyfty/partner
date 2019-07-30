
<?php 
class CommissAction extends BaseAction {
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
                'quick_create_commiss',
                'get_account_total',
                'demand',
                'send_notify_message'
            ),
			'allow'=>array(
                'getcommisslist',
                'analytics',
                'validate',
                'check',
                'remove',
                'fenpei',
                'revert',
                'changecontent',
                'commisslock',
                'check_commiss_limit',
                'excelimportdownload',
                'search',
                'logger',
                'log',
                'getcommissoriginal'
            )
		);
        if ($_REQUEST['act'] && ACTION_NAME == "index") {
            $_REQUEST['t'] = $_REQUEST['act'];
        }

        if (ACTION_NAME == "edit") {
            if (vali_permission("commiss", "salesedit") || vali_permission("commiss", "edit")) {
                $action['allow'][] = "edit";
            }
        }
        if (ACTION_NAME == "logger") {
            $_REQUEST['t'] = $_REQUEST['assort'];
        }
		B('Authenticate', $action);
	}

    public function validate() {
        $clientid = $this->_request('clientid','trim');
        $id = $this->_request('id','trim');

        if ($clientid == "telephone"){
            if (!$_REQUEST['order_classify'] && !in_array($_REQUEST['order_classify'], array("product", "customer")))
                $this->ajaxReturn("","",0);

            $where[$clientid] = array('eq',$this->_request($clientid,'trim'));
            if($id){
                $where["commiss_id"] = array('neq',$id);
            }
            $where["order_classify"] = $this->_request("order_classify",'trim');

            if (M("commiss")->where($where)->find()) {
                $this->ajaxReturn("客服模块已有该雇员/客户，不能重复添加","",1);
            } else {
                $this->ajaxReturn("","",0);
            }
        } else {
            return parent::validate();
        }
    }

    public function change_authInfo() {
        if (!isset($_GET['id'])) {
            die("Please provide a date range.");
        }

        $commiss_id = intval($this->_request('id'));
        $commiss = D('CommissView')->where('commiss.commiss_id = %d',$commiss_id)->find();
        if (!$commiss) {
            alert('error', "没有找到这个客户",$_SERVER['HTTP_REFERER']);
        }

        if($_POST['submit']){
            $this->submit_auth($commiss);
            alert('success', "修改客户账户成功", $_POST['refer_url'] ? $_POST['refer_url'] : U('commiss/index'));
        }else{
            $user_commiss = M('mUser')->where(array('model'=>"commiss",'model_id'=>$commiss_id))->find();
            if ($user_commiss) {
                $this->username = $user_commiss['username'];
            } else {
                $this->username = $commiss['telephone'];
            }
            $this->password = ($user_commiss ? $user_commiss['password'] : "234567");

            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->commiss_id = $commiss_id;
            $this->display();
        }
    }

    public function all_search_keyword($module) {
        return array("commiss.channel_role_model_keyword", "commiss.channel_role_id_keyword", "commiss.related_model_keyword");
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

        if ($_GET['lia'] == 'self') {
            $where['owner_role_id'] = session('role_id');
        } else if ($_GET['lia'] == 'belongs') {
            $where['creator_role_id'] = session('role_id');
        } else {
            if (!session('?admin') && vali_permission("branchlock", "commiss") && session('restriction') === true) {
                $where['_complex'] = $this->make_astrict_where();
            }
        }

        unset($where['commiss.order_classify']);
        if (isset($_GET['order_classify'])) {
            if (in_array($_GET['order_classify'], array('none', ''))) {
                $where['commiss.order_classify'] = array("in", array("", "none"));
            } else {
                $where['commiss.order_classify'] = $_GET['order_classify'];
            }
        }

        $params[] = "lia=" . $_GET['lia'];

        $byw = isset($_GET['byw']) ? trim($_GET['byw']) : '';
        switch ($byw) {
            case 'on' : {
                $sqlstr = ' and owner_role_id="'.session('role_id').'" and (communicate="待处理")';
                $defaultinfo = F('defaultinfo'.session('league_id'));
                $commiss_remind_limit = $defaultinfo['commiss_remind_limit'];
                if ($commiss_remind_limit) {
                    $sqlstr.=' or (communicate="跟进中" and last_log_time!="" and TO_DAYS(NOW()) - TO_DAYS(from_unixtime(last_log_time)) > '.$commiss_remind_limit.')';
                }

                $promptsql = M('prompt')->field("model_id")->where(array(
                    "model"=>"commiss",
                    "state"=>"开启",
                    "league_id"=>session('league_id'),
                    "prompt_time"=>array("lt", time())
                ))->select(false);
                $sqlstr.=" or (commiss_id in (".$promptsql."))";
                $where['_string'] .= $sqlstr;
            }
        }
        if (!empty($_GET['byw'])) {
            $params[] = "byw=".trim($_GET['byw']);
        }

        $byk = isset($_GET['byk']) ? trim($_GET['byk']) : '';
        switch ($byk) {
            case 'on' : {
                $sqlstr = ' and owner_role_id="" and (order_classify!="none") and  (communicate!="无意向")';
                $where['_string'] .= $sqlstr;
            }
        }
        if (!empty($_GET['byk'])) {
            $params[] = "byk=".trim($_GET['byk']);
        }

        $bycd = isset($_GET['bycd']) ? trim($_GET['bycd']) : '';
        switch ($bycd) {
            case 'today' :
                $where['create_time'] =  array('egt',strtotime(date('Y-m-d', time())));
                break;

            case 'fmonth' :
                $where['create_time'] =  array('between',getLastMonthDays(time()));
                break;

            case 'month' :
                $where['create_time'] = array('egt',strtotime(date('Y-m-01', time())));
                break;
        }
        if (!empty($_GET['bycd'])) {
            $params[] = "bycd=".trim($_GET['bycd']);
        }


        $byod = isset($_GET['byod']) ? trim($_GET['byod']) : '';
        switch ($byod) {
            case 'today' :
                $where['order_date'] =  array('egt',strtotime(date('Y-m-d', time())));
                break;

            case 'fmonth' :
                $where['order_date'] =  array('between',getLastMonthDays(time()));
                break;

            case 'month' :
                $where['order_date'] = array('egt',strtotime(date('Y-m-01', time())));
                break;
        }
        if (!empty($_GET['byod'])) {
            $params[] = "byod=".trim($_GET['byod']);
        }

        $branch_id = $_GET['bybr'] != "" ? $_GET['bybr']:"";
        if ($branch_id != "") {
            if ($branch_id == 0) {
                $where['commiss.branch_id'] = array("in", array("0", ""));
            } else {
                $where['commiss.branch_id'] = $branch_id;
            }
            $params[] = "bybr=" . trim($_GET['bybr']);
            $this->branch =  $branch_id;
        }

        self::show_list_index_html($where, $params, "客服表");
    }

    public function make_list_order(&$params) {
        $order = "order_date desc";
        if($_GET['desc_order']){
            $order = trim($_GET['desc_order']).' desc';
            $params[] = "desc_order=" . trim($_GET['desc_order']);
        }elseif($_GET['asc_order']){
            $order = trim($_GET['asc_order']).' asc';
            $params[] = "asc_order=" . trim($_GET['asc_order']);
        }
        return $order;
    }

    public function format_index_fields($field_array) {
        $m_module = $this->get_module_view();
        foreach($field_array as $k=>$v) {
            if ($v['form_type'] == "floatnumber" || $v['form_type'] == "number") {
                $sum_cnt = $m_module->where($this->list_where)->sum($v['field']);
                $v['link_title'] = $v['name']."合计：" . number_format($sum_cnt, 2);
            } else {
                $v['link_title'] = $v['name'];
            }
            $field_array[$k] = $v;
        }
        return $field_array;
    }

    public function replenish_list($list, $export) {
        $list = parent::replenish_list($list, $export);
        $defaultinfo = F('defaultinfo'.session('league_id'));
        $branch_role = get_branch(session("role_id"));
        $per_edit = vali_permission("commiss", "edit");
        $restriction = !session('?admin') && vali_permission("branchlock", "commiss")  && session('restriction') === true;
        foreach($list as $key => $value){
            $list[$key]['is_owner'] = session('?admin');
            if (!$list[$key]['is_owner']) {
                $list[$key]['is_owner'] = ($list[$key]['owner_role_id'] && $branch_role ? self::is_owner($list[$key], $branch_role) : true);
            }

            if ($defaultinfo['commiss_remind_limit']) {
                if ($value['communicate'] == "跟进中" && $value['last_log_time'] && $value['owner_role_id'] == session("role_id")) {
                    $list[$key]['lianluo'] = day(time() -  $value['last_log_time']) >= $defaultinfo['commiss_remind_limit'];
                }
            }

            $list[$key]['per_edit'] = $restriction ? ($list[$key]['is_owner'] || $per_edit) : true;
        }
        return $list;
    }

    public function refresh_excel_import($field_list, $data) {
        foreach ($field_list as $v){
            if ($v['field'] == "channel_role_model") {
                $channel = M("channel")->cache(true)->where("idcode='".$data[$v['field']]."'")->find();
                $datas['channel_role_model'] = $channel ? $channel['channel_id']:"";
                $channel_model = channel_model_map($channel);
                $m_channel_model = M($channel_model)->where(array("idcode"=>$data["channel_role_id"]))->find();
                $data['channel_role_id'] = $m_channel_model ? $m_channel_model[$channel_model.'_id']:"";
            }elseif($v['field'] == "owner_role_id"){
                $m_staff = D("StaffView")->where(array("idcode"=>$data["owner_role_id"]))->find();
                $data['owner_role_id'] = $m_staff ? $m_staff["role_id"]:"";
            }
        }
        $data['creator_role_id']  = session("role_id");
        $data['league_id'] = session('league_id');

        $commiss_id = $this->add_commiss_basic($data);
        if ($commiss_id) {
            $this->update_keyword($commiss_id);
            $this->log("default", $commiss_id, "新建客服", "导入新建客服成功");
        }
    }

    public function format_excel_fields($ex) {
        $where = array(
            "model"=>"commiss",
            "form_type"=>array("not in",array(
                "pic","video","file"
            )),
        );
        if (!$ex) {
            $where['field']=array("not in", array(
                "related_model",
                "astrict",
                "creator_role_id",
                "create_time",
                "idcode",
            ));
        }

        return M('Fields')->cache(true)->where($where)->order('order_id')->select();
    }

    function perfect_list_item($value, $export = false, $branchlock = false) {
        if ($value["wechat"]) {
            $value["idcode"] =  $value["idcode"].($export?"":"&nbsp;<img src='/Public/images/wxb.png'>");
        }
        if ($value["related_model_id"]) {
            $value["related_model_id"] =  $value["related_model_name"] == "product"?product_show_html($value["related_model_id"], false):customer_show_html($value["related_model_id"], false);
        }
        $value['markread'] = ($value['owner_role_id'] == session("role_id") && $value['communicate'] == "待处理");
        $value['promtp_count'] = self::prompt_count("commiss", $value['commiss_id'], time());

        return parent::perfect_list_item($value, $export, $branchlock);
    }

    public function add_commiss_basic($data) {
        if (!($commiss_id = $this->submit_add("commiss", $data))) {
            return false;
        }

        $idcode = sprintf("CO%07d", $commiss_id);
        $data = array(
            'idcode'=>$idcode,
            'slug'=>Pinyin($data['name']),
            'basic_submit_time'=>time(),
        );
        M('commiss')->where(array('commiss_id'=>$commiss_id))->setField($data);
        return $commiss_id;
    }

    function check_channel_model_info($map, $model) {
        if (!$map)return false;
        $map['_logic'] = 'or';
        $where['_complex'] = $map;
        $where["_logic"] = "and";
        return M($model)->where($where)->find();
    }



    public function comblie_check_where($telephone, $qq_number, $wechat, $model) {
        if ($telephone) {
            $where['telephone'] = $telephone;
        }
        if ($qq_number) {
            $where['qq_number'] = $qq_number;
        }
        if ($wechat) {
            $where['wechat'] = $wechat;
        }
        return $this->check_channel_model_info($where, $model);
    }


    private function update_releate_model($commiss) {
        $where = array('commiss_id'=>$commiss);
        if (!is_array($commiss)) {
            $commiss = M('commiss')->where($where)->find();
        }
        if (!$commiss['order_classify'] || !in_array($commiss['order_classify'], array("product", "customer")))
            return;
        $m_model = $this->comblie_check_where($commiss['telephone'], $commiss['qq_number'],$commiss['wechat'],$commiss['order_classify']);
        if ($m_model) {
            $keyword = array(
                $m_model['name'],
                $m_model['idcode'],
                $m_model['telephone'],
                $m_model['wechat'],
            );
            $data = array(
                "related_model_name"=>$commiss['order_classify'],
                "related_model_id"=>$m_model[$commiss['order_classify']."_id"],
                "related_model"=>"[".$m_model['idcode']."]".$m_model['name'],
                "related_model_keyword"=>implode(chr(10), $keyword),
            );
            M("commiss")->where($where)->setField($data);

            $where = array(
                $commiss['order_classify'].'_id'=>$m_model[$commiss['order_classify']."_id"]
            );
            M($commiss['order_classify'])->where($where)->setField('commiss_id', $commiss['commiss_id']);
        }
    }

    private function update_releated_astrict_role_info($commiss) {
        if (!is_array($commiss)) {
            $commiss = M('commiss')->where(array('commiss_id'=>$commiss))->find();
        }
        if ($commiss['related_model_id']) {
            $m_related_model = M($commiss['related_model_name'])->where(array($commiss['related_model_name']."_id"=>$commiss['related_model_id']))->find();
            if ($commiss['owner_role_id'] != $m_related_model['owner_role_id']) {
                $result = self::add_astrict_role_info($commiss['owner_role_id'], $commiss['related_model_name'], $commiss['related_model_id']);
                if ($result) {
                    $this->log("default", $commiss['commiss_id'], "更新日志","授权".role_html($_REQUEST['owner_role_id']));
                }
            }
        }
    }

    public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if($this->isPost()){
            if ($_POST['telephone']) {
                $where = array(
                    "telephone"=>trim($_POST['telephone']),
                    "order_classify"=>$_POST['order_classify']
                );
                if (M("commiss")->where($where)->find()) {
                    alert('error',  "客服模块已有该注册手机号码，不能重复添加", $_SERVER['HTTP_REFERER']);
                }
            }

            if ($_POST['channel_role_id_name'] == "自主注册") {
                $_POST['order_date'] = time();
            }

            if (!$_POST['channel_role_model'] && $_POST['channel_role_model_saver']) {
                $_POST['channel_role_model'] = $_POST['channel_role_model_saver'];
            }
            $_POST['league_id'] = session('league_id');
            $commiss_id = $this->add_commiss_basic($_POST);
            if (!$commiss_id) {
                alert('error',  "新建客服失败", $_SERVER['HTTP_REFERER']);
            }
            $this->update_releate_model($commiss_id);
            $this->update_releated_astrict_role_info($commiss_id);
            $this->update_keyword($commiss_id);
            $this->log("default", $commiss_id, "新建客服", "新建客服成功");
            $this->alert = parseAlert();
            alert('success', "添加客服成功", U('commiss/view', 'id='.$commiss_id));
		}else{
            $fields_group = product_field_list_html("add","commiss", array(), "all");
            unset($fields_group[79]);
            $this->fields_group = $fields_group;
            $alert = parseAlert();
            $this->alert = $alert;
            $this->display();
		}
	}

    public function delete(){
        if (!$_REQUEST['commiss_id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $commiss_ids = is_array($_REQUEST['commiss_id']) ? $_REQUEST['commiss_id'] : array($_REQUEST['commiss_id']);
        $delete_where = array('commiss_id'=>array("in", $commiss_ids));

        $commiss_delete = M('commiss')->where($delete_where)->delete();
        if(!$commiss_delete) {
            alert('error', L('DELETE_FAILED_PLEASE_CONTACT_YOUR_ADMINISTRATOR'),$_SERVER['HTTP_REFERER']);
        }
        M("commiss_subgroup")->where($delete_where)->delete();
        M("product")->where($delete_where)->setField("commiss_id", "");
        M("customer")->where($delete_where)->setField("commiss_id", "");

        $r_module = array();
        foreach ($commiss_ids as $value) {
            foreach ($r_module as $key2=>$value2) {
                $module_ids = M($value2)->where('commiss_id = %d', $value)->getField($key2 . '_id', true);
                M($value2)->where('commiss_id = %d', $value)->delete();
                if(!is_int($key2)){
                    M($key2)->where($key2 . '_id in (%s)', implode(',', $module_ids))->delete();
                }
            }
            $this->delete_files($value);
        }

        $related_module = array();
        foreach($related_module as $r) {
            $this->related_delete($commiss_ids, $r);
            if ($r == "trainorder") {
                $r = "train";
            }
            if ($r != "business") {
                M("commiss_" . $r)->where($delete_where)->delete();
            }
        }
        $this->log("default", $commiss_ids, "删除客服", "删除客服成功");
        if ($_REQUEST['refer_url']) {
            alert('success',"删除客服完成",$_REQUEST["refer_url"]);
        } else {
            alert('success',"删除客服完成",$_SERVER['HTTP_REFERER']);
        }
    }


	public function edit(){
		$commiss = D('CommissView')->where('commiss.commiss_id = %d',$this->_request('id'))->find();
		if (!$commiss) {
            alert('error',  "客服不存在", $_SERVER['HTTP_REFERER']);

        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if (!session('?admin') && session('restriction') === true && $commiss['owner_role_id']) {
            if (!self::is_owner($commiss, $branch = get_branch(session("role_id")))){
                alert('error',  "你没有权限", $_SERVER['HTTP_REFERER']);
            }
        }
        $assort = $this->_request('assort', 'trim', "basic");
        $commiss['owner'] = D('RoleView')->cache(true)->where('role.role_id = %d', $commiss['owner_role_id'])->find();

		if($this->isPost()){
            $telphome = $_POST['telephone']?$_POST['telephone']:$commiss['telephone'];
            if ($telphome) {
                $where = array(
                    "telephone"=>$telphome,
                    "order_classify"=>$_POST['order_classify'],
                    "commiss_id"=>array("neq", $commiss['commiss_id'])
                );
                if (M("commiss")->where($where)->find()) {
                    alert('error',  "客服模块已有该注册手机号码，不能重复添加", $_SERVER['HTTP_REFERER']);
                }
            }

            if ($_POST['name']) {
                $_POST['slug'] = Pinyin($this->_request("name"));
            }
            if (!$this->submit_edit($commiss['commiss_id'])) {
                alert_back('error',  "编辑客服失败");
            }

            $this->update_releate_model($commiss['commiss_id']);
            $this->update_releated_astrict_role_info($commiss['commiss_id']);
            $this->update_keyword($commiss['commiss_id']);

            $change_fields = D('CommissView')->verity_check($commiss);
            $basic_data = array(
                "basic_submit_time"=>time(),
            );
            $where = "commiss_id=".$commiss['commiss_id'];
            M("commiss")->where($where)->setField($basic_data);
            $this->add_edit_log($commiss['commiss_id'], "修改基本信息成功: ", $change_fields);

            alert('success', "编辑客服成功", U('commiss/view', 'id='.$commiss['commiss_id']));

		}else{
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();

            if (vali_permission("commiss", "salesedit")){
                $assort = "sales";
            }
            if ($assort == "basic" || vali_permission("commiss", "edit"))
                $assort = "all";

            //雇员图片
            $fields_group = product_field_list_html("edit","commiss",$commiss, $assort);
            unset($fields_group[79]);
            $this->fields_group = $fields_group;
            $this->model_id = $commiss['commiss_id'];
            $this->commiss = $commiss;
            $this->display();
		}
	}


    private function add_edit_log($commiss_id, $logcont, $change_fields) {
        foreach($change_fields as $v) {
            $logcont.=$v['name']."[".$v['oldvalue']."=>".$v['newvalue']."],";
        }
        $this->log("default", $commiss_id, "更新日志",$logcont);
    }


    public function logger() {
        $this->assort = $_GET['assort'] ? $_GET['assort']:"default";
        $this->display("Commiss:logger_".$this->assort); // 输出模板
    }

    public function log($assort, $commiss_ids, $subject, $content, $category_id = 6) {
        $log_id = 0;
        if (!is_array($commiss_ids)) {
            $commiss_ids = array($commiss_ids);
        }
        $commisss = M("commiss")->where(array('commiss_id'=>array("in", $commiss_ids)))->select();
        foreach($commisss as $v) {
            $m_log = M('Log');
            $m_log->role_id = session("role_id");
            $m_log->subject = $subject;
            $m_log->content = $content;
            $m_log->category_id = $category_id;
            $m_log->create_date =$m_log->update_date =  time();
            if ($log_id = $m_log->add()) {
                $data['commiss_id'] = $v['commiss_id'];
                $data['commiss_name'] = $v['name'];
                $data['commiss_idcode'] = $v['idcode'];
                $data['log_id'] = $log_id;
                $data['assort'] = $assort;
                $data['league_id'] = session('league_id');
                M('r_commiss_log')->add($data);
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
                "field"=>"commiss_id",
                "order"=>"commiss_id"
            ),
            array(
                "field"=>"idcode",
                "order"=>"idcode"
            ),
            array(
                "field"=>"commiss_name",
                "order"=>"commiss_name"
            ),
        );
        $m_model_name = $_GET['model']? $_GET['model']."View":"CommissView";
        $where = $this->parse_dialog_where();
        $data = make_data_list($m_model_name, $where, $data_field, array($this, "format_dialog_item"));
        $this->ajaxReturn($data,'JSON');
    }

    public function format_dialog_item($val) {
        $val["commiss_id"] = array(
            "commiss_id"=>$val['commiss_id']
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
        $assort = $_GET['assort'] ? $_GET['assort'] : "basic";
        $commiss_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $commiss_id) {
            alert('error', L('parameter_error'), U("Commiss/index"));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $where = array("commiss.commiss_id"=>$commiss_id);
        $commiss = D('CommissView')->where($where)->find();
        if (!$commiss) {
            alert('error', L('PARAMETER_ERROR'), U("Commiss/index"));
        }

        if (!session('?admin') && vali_permission("branchlock", "commiss") && session('restriction') === true) {
            $isinterest = self::is_interest($commiss, $branch = get_branch(session("role_id")), false);
            if (!$isinterest) {
                alert('error', "你没有权限访问", U("commiss/index"));
            }
        }

        if ($assort == "prompt") {
            $this->prompt_list = self::prompt_list("commiss", $commiss_id);
        }

        if ($commiss['related_model_id']) {
            $commiss["related_model_id"] =  $commiss["related_model_name"] == "product"?product_show_html($commiss["related_model_id"], false):customer_show_html($commiss["related_model_id"], false);
        }
        $this->commiss = $commiss;
        $this->assort = $assort;
        $this->commiss_id = $commiss_id;
        $this->promtp_count = self::prompt_count("commiss", $commiss['commiss_id'], time());

        if (($branch || $authority == "受限") && !in_array($commiss['commiss_id'], self::get_astrict_list())) {
            $commiss = self::fix_branch_fields(getBranchFields("commiss"), $commiss, in_array($commiss['owner_role_id'], $branch));
        }
        $this->is_owner = ($branch && $commiss['owner_role_id'] ? self::is_owner($commiss, $branch) : true);
        $this->per_product_add = vali_permission("commiss", "product_add");
        $this->per_customer_add = vali_permission("commiss", "customer_add");

        if ($this->per_product_add && $commiss['order_classify'] == "product")
        {
            $this->commiss_add_href.= "<a target='_blank' href='". U("commiss/product_add", "id=".$commiss['commiss_id'])."'>添加到雇员</a> | ";
        }
        if ($this->per_customer_add && $commiss['order_classify'] == "customer")
        {
            $this->commiss_add_href.= "<a target='_blank' href='". U("commiss/customer_add", "id=".$commiss['commiss_id'])."'>添加到客户</a> | ";
        }

        $fields_group = product_field_list_show('commiss', $commiss, "all");
        $this->fields_group =$fields_group;
        $this->refer_url= refer_url('refer_view_url');
        $this->alert = parseAlert();
        $this->display($assort."_view");
    }

    public function product_add() {
        $commiss_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $commiss_id) {
            alert('error', L('parameter_error'), U("Commiss/index"));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $where = array("commiss.commiss_id"=>$commiss_id);
        $commiss = D('CommissView')->where($where)->find();
        if (!$commiss) {
            alert('error', L('PARAMETER_ERROR'), U("Commiss/index"));
        }

        $this->redirect(U("product/add", "byc=commiss&cmodel_id=".$commiss_id));
    }

    public function customer_add() {
        $commiss_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $commiss_id) {
            alert('error', L('parameter_error'), U("Commiss/index"));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $where = array("commiss.commiss_id"=>$commiss_id);
        $commiss = D('CommissView')->where($where)->find();
        if (!$commiss) {
            alert('error', L('PARAMETER_ERROR'), U("Commiss/index"));
        }
        $this->redirect(U("customer/add", "byc=commiss&cmodel_id=".$commiss_id));
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
                "commiss.commiss_id"=>$id
            );
        }
        $where['league_id'] = session('league_id');

        $info = D('CommissView')->where($where)->find();
        $this->ajaxReturn($info,"",$info ? 1:0);
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
                $state_report_list[$s] = M('Commiss')->where($where)->count();
            }
            $this->state_report_list = $state_report_list;

            $statistics = array();
            $statistics['all_sum'] = M('Commiss')->count();
            $statistics['time_range_sum'] = M('Commiss')->where(array('create_time'=>$create_time))->count();
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
            self::default_cycle_basis_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab, "客服");
        }

        $this->parameter = implode('&', $params);
        $this->assort = $assort;
        $this->alert = parseAlert();
        $this->display($assort."_analytics".$tab);
    }


    public function reset_branch() {
        foreach(M("commiss")->select() as $v) {
            $m_branch = D("BranchCategoryView")->where(array("branch_category.role_id"=>$v['owner_role_id']))->find();
            if ($m_branch) {
                M("commiss")->where("commiss_id=".$v['commiss_id'])->setField("branch_id", $m_branch['branch_id']);
            }
        }
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
                "field"=>"commiss_show",
                "order"=>"commiss_id"
            ),
            array(
                "field"=>"subject",
                "order"=>"subject"
            ),
            array(
                "field"=>"content_show",
                "order"=>"content"
            ),
            array(
                "field"=>"operator_show",
                "order"=>"commiss_idcode"
            ),
        );

        $where = array();
        if ($_GET['id']) {
            $where['commiss.commiss_id'] = array("in", trim($_GET['id']));
        }
        if ($_GET['start_time'] || $_GET['end_time']) {
            $where['log.create_date'] =  array('between', make_time_between());
        }
        if ($_GET['assort'] == "" || $_GET['assort'] == "default") {
            $where['r_commiss_log.assort'] =  "default";
        } else {
            $where['r_commiss_log.assort'] = array("in", $_GET['assort']);
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['log.content'] =  array('like', "%".$_REQUEST['search']['value']."%");
        }

        if ($_GET['logpubshow']) {
            $where['r_commiss_log.logpubshow'] =  1;
        }

        if ($_GET['_string']) {
            $where["_string"] =trim($_GET['_string']);
        }
        $where['league_id'] = session('league_id');

        $data = make_data_list("CommissLogView", $where, $data_field, array($this, "format_commiss_log"));
        $this->ajaxReturn($data,'JSON');
    }

    public function format_commiss_log($v) {
        $v['create_date_show'] = toDate($v['create_date']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        $html = product_show_html($v, false);;
        if (!$html) {
            $html = '<span>[' .$v['commiss_idcode'].'] '.$v['commiss_name'] . '</span>&nbsp;';;
        }
        $v['commiss_show'] = $html;
        $v['content_show'] = "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>".cutString($v['content'], 43)."</a>";
        if ($v['log_type'] == 1) {
            $v['operator_show'] = '<span><a  href="javascript:void(0);" onclick="return delete_log('.$v['log_id'].');">删除</a></span> | ';;
        } else {
            $v['operator_show'] = '<span><a href="javascript:void(0);"  style="cursor:not-allowed;color:darkgrey">删除</a></span> | ';;
        }
        $v['operator_show'] .= "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>查看</a>";;

        return $v;
    }

    public function ggg() {
        foreach(M("commiss")->select() as $v) {
            $this->update_keyword($v);
        }
    }


    public function update_keyword($commiss) {
        if(!is_array($commiss)) {
            $commiss = D("Manage/CommissView")->where(array("commiss_id"=>$commiss))->find();
        }
        $data = array();
        $data = make_channel_model_keyword($commiss['channel_role_model'], $commiss['channel_role_id'], $data);
        $keyword = array();
        $data["keyword"] = implode(chr(10), $keyword);
        M("commiss")->where(array("commiss_id"=>$commiss['commiss_id']))->setField($data);
    }

    public function demand() {
        $this->display("Commiss:demand"); // 输出模板
    }
}