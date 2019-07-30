<?PHP
class DispatchAction extends BaseAction{
    public function _initialize(){

        $action = array(
            'permission'=>array(
                'delimg',
                'changecontent',
                'listdialog',
                'getinfo',
                'deletevideo',
                'deletefile',
                'getmodulename',
                'edit_workstate',
                'adjuect_queue_category',
                'adjuect_branch_queue',
                'adjuect_queue_pos',
                'exit_branch_queue',
                'setflag'
            ),
            'allow'=>array('')
        );


        if (ACTION_NAME == "index") {
            $rztable = vali_permission("dispatch", "index");
            $zbtable = vali_permission("dispatch", "index", "branch/zb");
            $mdtable = vali_permission("dispatch", "index", "branch/md");
            $yiyuantable = vali_permission("dispatch", "index", "hospital");

            if ($_GET['act'] == "branch") {
                if ($_GET['assort'] == "zb" && $zbtable) {
                    $action['permission'][] = "index";
                }

                if ($_GET['assort'] == "md" && $mdtable) {
                    $action['permission'][] = "index";
                }

                if (!isset($_GET['assort'])) {
                    if ($zbtable) {
                        $_GET['assort'] = "zb";
                    } else if ($mdtable){
                        $_GET['assort'] = "md";
                    }
                }

            } else if($_GET['act'] == "hospital") {
                if ($yiyuantable) {
                    $action['permission'][] = "index";
                }
            }
        }

        if (ACTION_NAME == "adjuect_queue_pos") {
            if (vali_permission("dispatch", "pos_branch_queue", "zb"))
                $action['permission'][] = "adjuect_queue_pos";
            if (vali_permission("dispatch", "pos_branch_queue", "hospital"))
                $action['permission'][] = "adjuect_queue_pos";
        }

        if (ACTION_NAME == "pause_queue") {
            if (vali_permission("dispatch", "pause_branch_queue", "zb"))
                $action['permission'][] = "pause_queue";
            if (vali_permission("dispatch", "pause_branch_queue", "hospital"))
                $action['permission'][] = "pause_queue";
        }

        if (ACTION_NAME == "exit_branch_queue") {
            if (vali_permission("dispatch", "exit_branch_queue", "zb"))
                $action['permission'][] = "exit_branch_queue";
            if (vali_permission("dispatch", "exit_branch_queue", "md"))
                $action['permission'][] = "exit_branch_queue";
            if (vali_permission("dispatch", "exit_branch_queue", "hospital"))
                $action['permission'][] = "exit_branch_queue";
        }

        if (ACTION_NAME == "edit_queue_describe") {
            if (vali_permission("dispatch", "edit_queue_describe", "index"))
                $action['permission'][] = "edit_queue_describe";
            if (vali_permission("dispatch", "edit_queue_describe", "zb"))
                $action['permission'][] = "edit_queue_describe";
            if (vali_permission("dispatch", "edit_queue_describe", "md"))
                $action['permission'][] = "edit_queue_describe";
            if (vali_permission("dispatch", "edit_queue_describe", "hospital"))
                $action['permission'][] = "edit_queue_describe";
        }

        if (NO_AUTHORIZE_CHECK === true)
            return;
        B('Authenticate', $action);
    }


    function getmodulename() {
        return "product";
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

        $this->act = $act = $_GET['act']?$_GET['act']:"index";
        if (!empty($_GET['act'])) {
            $params[] = "act=".trim($_GET['act']);
        }
        $debar_search_field = array();

        if (!session('?admin') && $_GET['lia']) {
            if ($_GET['lia'] == 'self') {
                $where['owner_role_id'] = session('role_id');
            }
        }

        if (!empty($_GET['assort'])) {
            $params[] = "assort=".trim($_GET['assort']);
        }
        $this->assort = isset($_GET['assort']) ? trim($_GET['assort']) :($act == "branch"?'zb':'');

        if ($_GET['queue_branch_id'] && $_GET['queue_branch_id'] !== "") {
            $this->queue_branch_id =  $_GET['queue_branch_id'];
            $where['product.queue_branch_id'] = $this->queue_branch_id;
        } elseif ($act == "branch") {
            if ($this->assort == "md") {
                $where['product.queue_branch_id'] = array("gt", 0);
            } else {
                $where['product.queue_branch_id'] = "0";
            }
        } else if ($act == "hospital") {
            $where['product.queue_branch_id'] = array("in", branch_hospital_id());
        }

        $debar_search_field[] = "queue_branch_id";

        $queue_branch_where = array();
        if ($act == "hospital") {
            $queue_branch_where['type'] = "医院";
        }
        $this->queue_branch_where = $queue_branch_where;

        if ($act != "index") {
            $this->list_header_station_state = model_select_field_list(68);
            $queue_category_id = $_GET['queue_category_id'];
            if (isset($_GET['queue_category_id'])) {
                $params[] = "queue_category_id=" . $queue_category_id;
            }
            if ($queue_category_id != "all") {
                if (isset($_GET['search'])) {
                    if (isset($queue_category_id)) {
                        $where['product.queue_category_id'] = array("in", $queue_category_id);
                    }
                    $this->queue_category_id= isset($queue_category_id) ? $queue_category_id : 0;
                } else {
                    $this->queue_category_id= isset($queue_category_id) ? $queue_category_id : ($act == "hospital"?6:2);
                    $where['product.queue_category_id'] = $this->queue_category_id;
                }
            } else {
                unset($where['queue_category_id'],$where['product.queue_category_id']);
            }
        } else {
            $this->list_header_station_state = array("其他录用", "签约",  "试用",  "无底薪签约");
        }

        if ($_GET['workstate_id']) {
            $workstate_id = $_GET['workstate_id'];
            $where['product.workstate_id'] =$workstate_id;
            $params[] = "workstate_id=" . trim($workstate_id);
        }

        if ($_GET['category_id']) {
            $category_id = $_GET['category_id'];
            if ($category_id != -1) {
                $where['_string'] .= " AND (FIND_IN_SET('".$category_id."',category_id) )";
            } else {
                $where['product.skill'] = "";
            }
            unset($where['product.category_id']);
            $this->category_id =  $category_id;
            $params[] = "category_id=" . trim($category_id);
        } else {
            if ($act == "index") {
                $where['product.category_id']=array("neq", '');
            }
        }
        if ($_GET['station_state']) {
            $where['product.station_state'] =$_GET['station_state'];
            $params[] = "station_state=" . trim($_GET['station_state']);
        } else {
            $where['product.station_state']=array("not in", array('自愿离职','开除','其他未录用', ''));
        }

        $branch_id = $_GET['bybr'] != "" ? $_GET['bybr']: "";
        if ($branch_id == "" && ($this->assort == "md" || $this->act == "hospital")) {
            $branch_id = session('branch_id');
        }
        if ($branch_id != "") {
            if ($this->assort == "md" || $this->act == "hospital") {
                $where['product.queue_branch_id'] = array("in", $branch_id);
            } else {
                $where['product.branch_id'] = array("in", $branch_id);
            }
            $params[] = "bybr=" . trim($_GET['bybr']);
            $this->branch =  $branch_id;
        }
        unset($where['product.onstation_time'],$where['product.holiday_time'],$where['product.idle_time']);

        if ($_GET['leave_state']) {
            if ($_GET['leave_state'] == "在职") {
                unset($where['product.leave_state']);
                $leave_state_where = array(
                    "product.leave_state"=>"在职",
                    '_logic'=>"OR",
                    '_complex'=> array(
                        "product.onlydown"=>array('neq','0'),
                        "product.leave_state"=>array('not in',array('请假中','请假过期')),
                    )
                );
                $where['_complex'] = $leave_state_where;
            } else if ($_GET['leave_state'] == "刚下户") {
                unset($where['product.leave_state']);
                $where['product.leave_state'] = array("eq","在职");$where['product.onlydown'] = array("neq",0);
            }
        }

        $where = $this->make_workstate_where($where, $params, $debar_search_field);

        if ($_GET['sign_style']) {
            $this->sign_style = trim($_GET['sign_style']);
        }

        $this->debar_search_field = array_merge($this->debar_search_field, $debar_search_field);
        $this->categoryList = M('product_category')->cache(true)->where(array('league_id'=>session('league_id'), 'enable'=>1))->order("order_id asc")->select();
        self::show_list_index_html($where, $params, "雇员表");
    }


    public function make_list_field() {
$fields = <<<STR
        ALL_VIEW_FIELD,
        CASE
            WHEN workstate_id='排岗' THEN 1
            WHEN workstate_id='面试' THEN 2
            WHEN workstate_id='上岗' THEN 3
            WHEN workstate_id='待岗' THEN 4
            ELSE 5
        END AS workstate_id_order,
        CASE
            WHEN leave_state='在职' AND onlydown!=0 THEN '刚下户'
            ELSE leave_state
        END AS leave_state
STR;
        return $fields;
    }

    function get_module_view() {
        return D("ProductView");
    }

    public function make_list_order(&$params) {
        if (in_array($_GET['act'], array("branch", "hospital"))) {
            if($_GET['act'] == "hospital"){
                $order = "workstate_id_order asc,queue_pos asc";
            }elseif ($_GET['assort'] == "zb") {
                $order = "queue_pos asc";
            }else {
                $order = "workstate_id_order asc,queue_pos asc";
            }
        } else {
            $order = "product_id desc";
        }
        if($_GET['desc_order']){
            $order = trim($_GET['desc_order']).' desc';
            $params[] = "desc_order=" . trim($_GET['desc_order']);
        }elseif($_GET['asc_order']){
            $order = trim($_GET['asc_order']).' asc';
            $params[] = "asc_order=" . trim($_GET['asc_order']);
        }
        return $order;
    }

    private function get_list_fields() {
        $filed_ids= array();

        $filed_ids[] = "86";
        $filed_ids[] = "547";
        $filed_ids[] = "515";

        if ($this->act == "index") {
            $filed_ids[] = "88";
        }
        $filed_ids[] = "22";
        $filed_ids[] = "775";

        $filed_ids[] = "767";
        $filed_ids[] = "122";
        $filed_ids[] = "85";
        $filed_ids[] = "60";
        $filed_ids[] = "823";
        $filed_ids[] = "68";
        $filed_ids[] = "1024";
        $filed_ids[] = "1022";
        $filed_ids[] = "1025";
        $filed_ids[] = "1023";
        $filed_ids[] = "1026";
        $filed_ids[] = "1027";
        $filed_ids[] = "1031";

        if ($this->act == "branch" && $_GET['queue_category_id'] != "all" || $this->act == "hospital" ) {
            $filed_ids[] = "1030";
        }
        $filed_ids[] = "540";
        $filed_ids[] = "526";

        if ($this->assort == "zb"){
            $filed_ids[] = "769";
        } else {
            $filed_ids[] = "768";
            $filed_ids[] = "770";
        }
        $filed_ids[] = "774";
        $filed_ids[] = "797";
        if ($this->act == "index") {
            $filed_ids[] = "1030";
        }
        return $filed_ids;
    }
    public function format_excel_fields($ex) {
        $filed_ids= $this->get_list_fields();
        $where = array(
            "field_id"=>array("in",$filed_ids),
        );
        return M('Fields')->where($where)->order('order_id')->select();
    }

    public function display_index_html() {
        $filed_ids= $this->get_list_fields();
        $where = array("enable"=>1);
        $where['league_id'] = session('league_id');

        if ($this->act !="hospital") {
            $where['category_id'] = array("in", array(2,3));
        } else {
            $where['category_id'] = array("in", array(6, 14));
        }
        $this->queue_category = M('product_category')->cache(true)->where($where)->order("order_id asc")->select();

        $this->field_array = $this->format_index_fields(getFields($filed_ids));
        $this->field_list = $this->field_array;
        $this->alert = parseAlert();
        $this->display($this->act);
    }


    function perfect_list_item($value, $export = false, $branchlock = false) {
        $value = parent::perfect_list_item($value, $export, $branchlock);
        $value['is_owner'] = session('?admin');
        if (!$value['is_owner'] && $value['owner_role_id'] && session("branch_id") != $value['queue_branch_id']) {
            $value['is_owner'] = self::is_fix_branch_field($value, $branchlock);
        }
        $value["canqueue"] = self::is_can_adjuect_queue($value);
        $value["skill"] = product_skill_show($value['skill']);
        $value['workstate_name'] = $value['workstate_id'];
        $value["queue_branch_id_show"] = queue_branch_html($value['queue_branch_id']);
        $value["category_id_array"] = explode(",", $value["category_id"]);
        $value["branch_category"] = in_array('2', $value["category_id_array"]) || in_array('3', $value["category_id_array"]);
        $value["hospital_category"] = in_array('6', $value["category_id_array"]) || in_array('14', $value["category_id_array"]);
        if ($value['queue_over_time'] > 0) {
            $value['new_market'] = $this->format_newly_market($value, $export);
        }


        if ($value['sign_style']) {
            $value['sign_style'] = proudct_category_map( $value['raw_sign_style']=$value['sign_style']);
        }
        if ($this->sign_style) {
            if ($value['catelevel']) {
                foreach(explode(",", $value['catelevel']) as $v) {
                    $cc = explode("=", $v);
                    if ($value['raw_sign_style'] == $cc[0]) {
                        $value['sign_style'] = $value['sign_style']." - " .$cc[1];
                    }
                }
            }
        }

        $this->check_holiday_time($value['product_id'], $value);

        return $value;
    }

    private function format_newly_market($value, $export = false) {
        $subsql = M("market_product")->where("product_id=".$value['product_id'])->field("max(create_time)")->select(false);
        $where = array(
            "_string"=>"market_product.create_time=".$subsql,
        );
        $new_market = D('Manage/MarketProductSimpleView')->where($where)->find();
        if ($export) {
            return $new_market ? $new_market['market_idcode']." - [".role_show($new_market['market_owner_role_id'])."]":null;
        } else {
            return $new_market ? "<a href=".U("market/view", "id=".$new_market['market_id']).">".$new_market['market_idcode']."</a> - [".role_html($new_market['market_owner_role_id'])."]":null;
        }
    }

    public function adjuect_queue_pos() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = M('product')->where('product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        if ($this->isAjax()) {
            $this->product = $product;
            $this->display("adjuect_queue_pos"); // 输出模板
        } else {
            $state = $this->_request('state');
            $adjpos = $_REQUEST["pos_val_".$state] ? $_REQUEST["pos_val_".$state]:0;
            self::sort_queue_pos($product['product_id'], $product['queue_category_id'], $product['queue_branch_id'], $state, $adjpos);
            $this->add_dispatch_log($product['product_id'],"0", $this->queue_pos_log_cnt($state, $adjpos));
            alert('success', "操作完成", $_SERVER['HTTP_REFERER']);
        }
    }

    public function pause_queue() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = D('ProductView')->where('product.product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $m_data = array(
            "queue_state"=>($product['queue_state'] == 1 ? 0 : 1)
        );
        if ($m_data['queue_state'] == 1) {
            $m_data['queue_over_time'] = time();
        } else {
            $m_data['queue_pos'] = "9999999";
        }
        M("product")->where(array("product_id"=>$product['product_id']))->setField($m_data);
        self::sort_queue_pos($product['product_id'], $product['queue_category_id'], $product['queue_branch_id'], "nl");
        alert('success', "操作完成", $_SERVER['HTTP_REFERER']);
    }

    public function setflag() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = D('ProductView')->where('product.product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $m_data = array(
            "dispatch_flag"=>$this->_request('flag')
        );
        M("product")->where(array("product_id"=>$product['product_id']))->setField($m_data);
        alert('success', "操作完成", $_SERVER['HTTP_REFERER']);
    }

    public function queue_pos_log_cnt($state, $adjpos) {
        if ($state == "nf")
            return "调整位置到最前";
        if ($state = "f")
            return "前调".$adjpos."位置";
        if ($state == "nl")
            return "调整位置到最后";
        if ($state = "l")
            return "后调".$adjpos."位置";
    }

    public static function is_can_adjuect_queue($product) {
        return in_array($product["station_state"],array('其他录用',"签约", "试用",  "无底薪签约"));
    }

    public function exit_branch_queue() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = D('ProductView')->where('product.product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        if ($this->isAjax()) {
            $this->product = $product;
            $this->display("adjuect_branch_queue"); // 输出模板
        } else {
            $m_data = array(
                "queue_state"=>"1",
                "queue_branch_id"=>$_GET['queue_branch_id'],
                "queue_role_id"=>session("role_id"),
            );
            $m_data['queue_describe'] = "";
            $m_data['workstate_id'] = product_makret_workstate($product);
            if ($m_data['workstate_id'] != "排岗") {
                $m_data['queue_pos'] = "9999999";
            }

            if (in_array($product['queue_branch_id'],branch_hospital_id())) {
                $this->add_dispatch_log($product['product_id'],$product['queue_branch_id'],"调出医院排队 ");
            } elseif ($product['queue_branch_id'] > 0) {
                $this->add_dispatch_log($product['product_id'],$product['queue_branch_id'],"调出门店: ".branch_show($product['queue_branch_id']));
            } elseif ($product['queue_branch_id'] == 0) {
                $this->add_dispatch_log($product['product_id'],$product['queue_branch_id'],"调出总部排队 ");
            }
            M("product")->where(array("product_id"=>$product['product_id']))->setField($m_data);
            alert('success', "操作完成", $_SERVER['HTTP_REFERER']);
        }
    }


    public function adjuect_branch_queue() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = D('ProductView')->where('product.product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        if ($this->isAjax()) {
            $this->product = $product;
            return $this->display("adjuect_branch_queue"); // 输出模板
        }

        $queue_branch_id = $this->_request('queue_branch_id');
        $m_data = array(
            "queue_branch_id"=>$queue_branch_id,
            "queue_role_id"=>session("role_id"),
        );
        $workstate = $this->_request('workstate_id');
        if ($queue_branch_id == "0" || in_array($queue_branch_id, branch_hospital_id())) {
            if (in_array($product['workstate_id'], array("待岗","面试")) && $workstate == "排岗" || $product['workstate_id'] == "空闲" || $product['workstate_id'] == "") {
                $m_data['workstate_id'] = product_makret_workstate($product);
            }
        } else{
            if ($queue_branch_id == "-1") {
                if ($product['queue_branch_id'] > 0) {
                    if ($product['workstate_id'] == "面试") {
                        $m_data['workstate_id'] = product_makret_workstate($product);
                    }
                }elseif (in_array($product['queue_branch_id'],branch_hospital_id())) {
                    if (in_array($product['workstate_id'], array("面试",'排岗'))) {
                        $m_data['workstate_id'] = product_makret_workstate($product);
                    }
                } else {
                    if ($product['workstate_id'] == "排岗") {
                        $m_data['workstate_id'] = product_makret_workstate($product);
                    }
                }
            } else if ($product['workstate_id'] == "排岗" || $product['workstate_id'] == "待岗") {
                $m_data['workstate_id'] = product_makret_workstate($product);
            }
        }

        $queue_category_id = $this->_request('queue_category_id');
        if ($queue_category_id) {
            $m_data['queue_category_id'] = $queue_category_id;
        } else {
            $queue_category_id = $product['queue_category_id'];
        }
        if ($queue_branch_id=="0") {
            $this->add_dispatch_log($product['product_id'],"0","加入排岗 => ".proudct_category_map($queue_category_id));
        } else if ($queue_branch_id=="-1") {
            $m_data['queue_describe'] = "";
            if ($product['queue_branch_id'] > 0) {
                $this->add_dispatch_log($product['product_id'],$product['queue_branch_id'],"调出门店: ".branch_show($product['queue_branch_id'])." => ".branch_show($queue_branch_id));
            } else {
                $this->add_dispatch_log($product['product_id'],"0","退出排岗");
            }
        }elseif(in_array($queue_branch_id,branch_hospital_id())){
            $this->add_dispatch_log($product['product_id'],"0","加入医院排港");
        }else {
            $this->add_dispatch_log($product['product_id'],"0","调度到门店 => ".branch_show($queue_branch_id));
        }
        if ($m_data['workstate_id'] != "排岗") {
            $m_data['queue_pos'] = "9999999";
        } else {
            $m_data['queue_workstate'] = 0;
        }
        $m_data["queue_over_time"] = time();

        M("product")->where(array("product_id"=>$product['product_id']))->setField($m_data);
        if ($queue_branch_id != "-1") {
            self::sort_queue_pos($product['product_id'], $queue_category_id, $queue_branch_id);
        }
        alert('success', "操作完成", $_SERVER['HTTP_REFERER']);
    }

    public function edit_queue_describe() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = D('ProductView')->where('product.product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        if ($this->isAjax()) {
            $this->product = $product;
            $this->display("edit_queue_describe"); // 输出模板
        } else {
            $m_data = array(
                "queue_describe"=>$this->_request('queue_describe'),
            );
            if ($_REQUEST['quiz_count']) {
                $m_data['quiz_count'] = $this->_request('quiz_count');
            }
            M("product")->where(array("product_id"=>$product['product_id']))->setField($m_data);
            $this->add_dispatch_log($product['product_id'], $product['queue_branch_id'], "修改备注: ".$m_data['queue_describe']);
            alert('success', "操作完成", $_SERVER['HTTP_REFERER']);
        }
    }

    public function adjuect_queue_category() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = D('ProductView')->where('product.product_id = %d',$_GET['product_id'])->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'));
            exit("");
        }
        if (!in_array($product["station_state"],array("其他录用", "签约",  "试用",  "无底薪签约"))) {
            alert('error', "操作没有完成, 雇员岗岗位状态不对。");
            exit("");
        }
        $this->queue_branch_id = $_GET["queue_branch_id"];
        if ($this->queue_branch_id == 0)  {
            $cccate = array("2","3");
        } else {
            $cccate = array("6", "14");
        }

        $type = array();
        foreach(explode(",", $product['category_id']) as $v) {
            if (in_array($v, $cccate))
                $type[] = $v;
        }
        $this->type = $type;
        $this->product = $product;
        $this->display("adjuect_queue_category"); // 输出模板
    }

    public function edit_workstate() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = D('ProductView')->where('product.product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        if ($this->isAjax()) {
            $this->workstate_id = product_workstate_list_opt($product, false);
            $this->product = $product;
            $this->display("edit_workstate"); // 输出模板
        } else {
            $m_data = array(
                "workstate_id"=>$this->_request('workstate_id'),
            );
            if ($m_data['workstate_id'] == "") {
                $m_data['workstate_id'] = product_makret_workstate($product);
                $m_data['queue_workstate'] = 1;
            } else {
                $m_data['queue_workstate'] = 0;
            }
            if ($m_data['workstate_id'] != "排岗") {
                $m_data['queue_pos'] = "9999999";
            }

            M("product")->where(array("product_id"=>$product['product_id']))->setField($m_data);

            $this->add_dispatch_log($product['product_id'], $product['queue_branch_id'], "修改备注: ".$m_data['queue_describe']);
            alert('success', "操作完成", $_SERVER['HTTP_REFERER']);
        }
    }

    public function add_dispatch_log($product_id, $branch_id, $logcont) {
        $this->log("dispatch", $product_id, "调度日志",$logcont,6, $branch_id);
    }

    public function log($assort, $product_ids, $subject, $content, $category_id = 6,$branch_id = "") {
        $log_id = 0;
        $products = M("product")->where( array('product_id'=>array("in", $product_ids)))->select();
        foreach($products as $v) {
            $m_log = M('Log');
            $m_log->role_id = session("role_id");
            $m_log->subject = $subject;
            $m_log->content = $content;
            $m_log->category_id = $category_id;
            $m_log->create_date = time();
            $m_log->update_date = time();
            if ($log_id = $m_log->add()) {
                $data['product_id'] = $v['product_id'];
                $data['product_name'] = $v['name'];
                $data['product_idcode'] = $v['idcode'];
                $data['log_id'] = $log_id;
                $data['assort'] = $assort;
                $data['branch_id'] = $branch_id;
                $data['league_id'] = session('league_id');
                M('RLogProduct')->add($data);
            }
        }
        return $log_id;
    }
}
