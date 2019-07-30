<?PHP 
class TradeAction extends BaseAction{
	public function _initialize(){
		$action = array(
			'permission'=>array(
                'search',
                'listDialog',
                'delimg',
                'deletevideo',
                'deletefile',
                'getinfo',
                'dosearch',
                'removegroupstance',
                'addgroupstance',
                'allgroupdialog',
                'analytics',
            ),
			'allow'=>array('')
		);
        if ($_REQUEST['act'] || ACTION_NAME == "index") {
            $_REQUEST['t'] = $_REQUEST['act'];
        }
		B('Authenticate', $action);
	}

    public function format_excel_fields($ex) {
        $field_list = M('Fields')->where(array("model"=>"trade"))->order('order_id')->select();
        $cur_fields = array(
            "相关方姓名"=>"infow_name",
            "相关方编号"=>"infow_idcode",
            "岗位状态"=>"infow_station_state",
            "身份证"=>"infow_cardid",
        );
        if ($_GET['cat'] == 8) {
            $cur_fields['相关方身份证'] = "cardid";
            $cur_fields['服务类别'] = "skill";
        }
        foreach($cur_fields as $k=>$v) {
            $field_list[]= array("name"=>$k, "field"=>$v, "form_type"=>"text");;
        }
        return $field_list;
    }

    public function perfect_list_item($value, $export = false, $branchlock = false) {
        $value = self::format_trade_item($value, $export);
        return parent::perfect_list_item($value, $export, $branchlock);
    }

    public static function format_trade_item($value, $export = false) {
        $viewname = ucfirst($value['corre'])."TradeView";
        $corre = D($viewname)->where(array("trade_id"=>$value['trade_id']))->find();;
        if ($corre) {
            $value['infow_name'] = $corre[$value['corre'].'_name'];
            $value['infow_idcode'] = $corre['idcode'];
            $value['infow_cardid'] = $corre['cardid'];
            if ($value['corre'] == "product" && $corre['station_state']) {
                $value['infow_station_state'] = $corre['station_state'];
            } else {
                $value['infow_station_state'] = "";
            }
            $value['infow']['export_info'] = "[".$value['infow_idcode']."]".$value['infow_name'];
            $value['corre_info'] = "<a href='".U($value['corre']."/tradeview", "assort=trade&id=".$corre[$value['corre']."_id"])."' target='_blank'/>[".$corre['idcode']."]".$corre[$value['corre'].'_name']."</a>";
            if ($value['infow_station_state']) {
                $value['corre_info'] .= "-<span>".$value['infow_station_state']."</span>";
            }
            $value['corre_info'] .= "</a>";
        }
        if ($value['state'] != '已撤销'){
            $value['state'] = $value['trade_state'];
        }

        if ($export && $_GET['cat'] == 8) {
            $value['cardid'] = $corre['cardid'];
            $value["skill"] = product_skill_show($corre['skill']);
        }
        return $value;
    }

    public function reset_keyword() {
        $list = D('TradeView')->select();
        foreach ($list as $v)  {
            $m_branch = D("BranchCategoryView")->where(array("branch_category.role_id"=>$v['owner_role_id']))->find();
            if ($m_branch) {
                M("trade")->where(array("trade_id"=>$v['trade_id']))->setField("branch_id", $m_branch['branch_id']);
            }
        }
    }


    public function show_group() {
        $p = isset($_GET['p'])?$_GET['p']:1;

        import('@.ORG.Page');// 导入分页类
        $Page = new Page(D('TradeGroup')->count(),15);// 实例化分页类 传入总记录数和每页显示的记录数
        $this->assign('page',$Page->show());// 赋值分页输出

        $this->assign_trade_category(0);
        $this->trade_group_id = $_GET['trade_group_id'];
        $this->assign('list',D('TradeGroup')->Page($p.',15')->select());// 赋值数据集

        $this->alert=parseAlert();
        $this->display("group"); // 输出模板
    }

    public function field_where($field, $search, $condition) {
        $where = array();
        if (in_array($field, array('state', 'trade.state')) && $search != '已撤销') {
            $where = self::make_state_where($search, $where);
        } else {
            $where =  parent::field_where($field, $search, $condition);
        }
        return $where;
    }

    public function make_state_where($state, $where) {
        switch ($state) {
            case 'df' :
            case '待付款':
                $where['_string'] = " trade.state!='已撤销' and trade.pay_price=0  ";
                break;

            case '待开始':
            case 'dk' :
                $where['_string'] = " trade.state!='已撤销' and trade.pay_price>0 and (begin_date=0 or UNIX_TIMESTAMP()<begin_date)";
                break;

            case 'jx' :
            case '进行中':
                $where['_string'] = " trade.state!='已撤销' and trade.pay_price>0 and trade.begin_date >0  and UNIX_TIMESTAMP() > trade.begin_date and ( UNIX_TIMESTAMP()<trade.end_date or trade.end_date=0 ) ";
                break;

            case 'js' :
            case '已结束':
                $where['_string'] = " trade.state!='已撤销' and trade.pay_price>0  and UNIX_TIMESTAMP() > end_date and (end_date !=0 ) ";
                break;
        }
        return $where;
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
            } else {
                $where['_complex'] = self::make_astrict_where(false);
            }
        }
        if ($_GET['lia']) {
            $params[] = "lia=" . $_GET['lia'];
        }

        if ($_GET['by']) {
            $by = trim($_GET['by']);
            switch ($by) {
                case 'today' :
                    $where['create_time'] = array('gt', strtotime(date('Y-m-d', time())));
                    break;
                case 'fmonth' :
                    $where['create_time'] =  array('between',getLastMonthDays(time()));
                    break;
                case 'month' :
                    $where['create_time'] = array('gt', strtotime(date('Y-m-01', time())));
                    break;

                case 'cx' :
                    $where['state'] = array('eq', "已撤销");
                    break;
                default: {
                    $where = make_trade_state_where($by, $where);
                    break;
                }
            }
            $params[] = 'by=' . $by;
        } else {
            $where['_string'] = 'trade.state!="已撤销" and trade.pay_price>=0 ';
        }

        $branch_id = $_GET['bybr'] != "" ? $_GET['bybr']:"";
        if ($branch_id != "") {
            $where['trade.branch_id'] = $branch_id;
            $params[] = "bybr=" . trim($_GET['bybr']);
            $this->branch =  $branch_id;
        }

        $cat = $_GET['cat'] ? $_GET['cat']:0;
        $params[] = "cat=" . trim($cat);
        $allchild = $this->assign_trade_category($cat,$where['league_id']);
        if ($allchild) {
            $where['category'] = array("in", $allchild);
        }
        self::show_list_index_html($where, $params, "产品订单表");
    }

    public function make_list_field() {
$fields = <<<STR
        ALL_VIEW_FIELD,
        CASE
        WHEN trade.state!='已撤销' and trade.pay_price>=0 and (trade.begin_date=0 or UNIX_TIMESTAMP() < trade.begin_date) THEN '待开始'
        WHEN trade.state!='已撤销' and trade.pay_price>=0 and trade.begin_date>0 and UNIX_TIMESTAMP()>trade.begin_date and (UNIX_TIMESTAMP()<trade.end_date or trade.end_date=0 ) THEN '进行中'
        WHEN trade.state!='已撤销' and trade.pay_price>=0 and UNIX_TIMESTAMP() > trade.end_date and trade.end_date !=0 THEN '已结束'
        ELSE ''
        END AS trade_state
STR;
        return $fields;
    }


    public function make_list_order(&$params) {
        $order = "trade_id desc";
        if($_GET['desc_order']){
            if ($_GET['desc_order'] == "state") {
                $order = 'trade_state desc';
            } else {
                $order = trim($_GET['desc_order']).' desc';
            }
            $params[] = "desc_order=" . trim($_GET['desc_order']);
        }elseif($_GET['asc_order']){
            if ($_GET['asc_order'] == "state") {
                $order = 'trade_state asc';
            } else {
                $order = trim($_GET['asc_order']).' asc';
            }
            $params[] = "asc_order=" . trim($_GET['asc_order']);
        }
        return $order;
    }

    public function assign_trade_category($cat, $league_id = null) {
        $league_id = $league_id?$league_id:session('league_id');

        if ($cat != 0) {
            $bread_list = breadcrumb("serve", $cat,$league_id);
            array_pop($bread_list);
            $this->bread_list = $bread_list;
            $this->focus_category = M("serve_category")->where(array("serve_category_id="=>$cat, "league_id"=>$league_id))->find();
            $allchild = cateallchild("serve",$cat,$league_id);
            $allchild[] = $cat;
        }

        $serve_category = M("serve_category")->where(array("parentid="=>$cat, "league_id"=>$league_id))->order("order_id asc")->select();
        if (!$serve_category) {
            $serve_category = M("serve_category")->where( array("parentid="=>$this->focus_category['parentid'], "league_id"=>$league_id))->order("order_id asc")->select();
        }
        $this->serve_category = $serve_category;
        return $allchild;
    }


    public function update_keyword($trade) {
        if (!is_array($trade)) {
            $trade = D('TradeView')->where('trade.trade_id = %d',$trade)->find();
        }
        $keyword = array();
        $keyword[] = $trade['serve_idcode'];
        $keyword[] = $trade['serve_name'];
        $keyword[] = $trade['category_name'];

        $viewname = ucfirst($trade['corre'])."TradeView";
        $corre = D($viewname)->where(array("trade_id"=>$trade['trade_id']))->find();;
        if ($corre) {
            $keyword[] = $corre['idcode'];
            $keyword[] = trim($corre[$trade['corre'].'_name']);
            $keyword[] = $corre[$trade['corre'].'_telephone'];

        }
        M("trade")->where(array("trade_id"=>$trade['trade_id']))->setField("keyword", implode('\n', $keyword));
    }

    private function check_customer_insurance($customer_id, $begin_date, $end_date) {
        $begin_date = strtotime($begin_date);
        $end_date = strtotime($end_date);
        $map['_string'] = sprintf(
            "(begin_date between %s and %s) or (end_date between %s and %s) or (begin_date < %s and end_date > %s)  ",
            $begin_date, $end_date,
            $begin_date, $end_date,
            $begin_date, $end_date
        );
        $where = array(
            "serve.serve_id"=>138,
            "customer.customer_id"=>$customer_id,
            "_complex"=>$map
        );
        return D("CustomerTradeView")->where($where)->find();
    }

    private function check_product_insurance($product_id, $begin_date, $end_date, $serve) {
        $begin_date = strtotime($begin_date);
        $end_date = strtotime($end_date);
        $map['_string'] = sprintf(
            "(begin_date between %s and %s) or (end_date between %s and %s) or (begin_date < %s and end_date > %s)  ",
            $begin_date, $end_date,
            $begin_date, $end_date,
            $begin_date, $end_date
        );
        $where = array(
            "serve.serve_id"=>$serve['serve_id'],
            "product.product_id"=>$product_id,
            "_complex"=>$map
        );
        return D("ProductTradeView")->where($where)->find();
    }

    private function update_proudct_commiss_info($trade) {
        if (!is_array($trade)) {
            $trade = D("ProductTradeView")->where(array("trade_id"=>$trade))->find();
        }
        if ($trade) {
            $where = array("product_trade.proudct_id"=>$trade['proudct_id']);
            $data = array(
                "serve_price"=> D("ProductTradeView")->where($where)->sum("sum_price"),
            );
            M("commiss")->where(array("related_model_name"=>"proudct", "related_model_id"=>$trade['proudct_id']))->setField($data);
        }
    }

	public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()) {
            if (!isset($_POST['serve_name']) || $_POST['serve_name'] == '') {
                alert_back('error', '必须设置产品名称');
            }
            $_POST['role_id'] = session("role_id");

            $m_branch = D("BranchCategoryView")->where(array("branch_category.role_id"=>$_POST['owner_role_id']))->find();
            if ($m_branch) {
                $_POST['branch_id'] = $m_branch['branch_id'];
            }
            $_POST['league_id'] = session('league_id');

            $insurance = $this->check_customer_insurance($_POST['customer_id'], $_POST['begin_date'], $_POST['end_date']);
            if ($insurance != null) {
                $errhref = "<a href='".U("trade/view", "id=".$insurance['trade_id'])."'>查看</a>";
                alert_back('error', "新建产品订单失败, 这个客户在此时间段已经有管理费订单,".$errhref, $_POST['refer_url']);
            }

            $serve = D('ServeView')->where(array("serve.serve_id"=>$_POST['serve_id']))->find();
            if ($serve['category'] == 8) {
                $insurance = $this->check_product_insurance($_POST['product_id'], $_POST['begin_date'], $_POST['end_date'], $serve);
                if ($insurance != null) {
                    $errhref = "<a href='".U("trade/view", "id=".$insurance['trade_id'])."'>查看</a>";
                    alert_back('error', "新建产品订单失败, 这个雇员在此时间段已经有保险费订单,".$errhref, $_POST['refer_url']);
                }
            }

            $_POST['surplus_price'] = $_POST['sum_price'];

            $trade_id = $this->submit_add();
            if (!$trade_id) {
                $this->alert = parseAlert();
                alert('error', "新建产品订单失败", $_POST['refer_url']);
            }
            $corre_name = $this->_request("corre");
            $corre_trade = M(ucfirst($corre_name)."_trade");
            if ($corre_trade->create() !== false) {
                $corre_trade->trade_id = $trade_id;
                $corre_trade->add();
            }
            $model_trade = D(ucfirst($corre_name)."TradeView")->where(array("trade_id"=>$trade_id))->find();
            $data = array(
                "orderid"=>sprintf("CD%07d",$trade_id),
                'origin'=>$model_trade[$corre_name.'_origin'],
                'introducer'=>$model_trade[$corre_name.'_introducer'],
            );
            M('trade')->where(array('trade_id'=>$trade_id))->setField($data);

            self::update_surplus_price("trade", $trade_id);
            $this->update_proudct_commiss_info($model_trade);
            self::update_keyword($trade_id);

            if($_POST['refer_url']) {
                alert('success', "新建产品订单成功", $_POST['refer_url']);
            }else{
                alert('success', "新建产品订单成功", U("trade/view", "id=".$trade_id));
            }
        }else{
            $this->refer_url= refer_url('refer_add_url');

            $fields_group = product_field_list_html("add","trade", array(), "basic");;
            if (!vali_permission("trade", "cost")) {
                unset($fields_group['51']);
            }
            $this->fields_group = $fields_group;
            $this->alert = parseAlert();
            $this->display();
        }
	}

	public function view(){
        $trade_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $trade_id) {
            alert('error', L('PARAMETER_ERROR'), U("Trade/index"));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $where = array("trade.trade_id"=>$trade_id);

        $trade = D('TradeView')->field($this->make_list_field())->where($where)->find();
        if (!$trade) {
            alert('error', L('PARAMETER_ERROR'), U("Trade/index"));
        }

        if (!session('?admin') && session('restriction') === true) {
            $authority = session('authority');
            if ($authority && !self::is_interest($trade, $branch = get_branch(session("role_id")), false)) {
                alert('error', "你没有权限访问", U("Trade/index"));
            }
        }
        if ($trade['state'] != '已撤销'){
            $trade['state'] = $trade['trade_state'];
        }
        $trade['owner'] = D('RoleView')->where('role.role_id = %d', $trade['role_id'])->find();
        $trade[$trade['corre']] = D(ucfirst($trade['corre'])."TradeView")->where(array("trade_id"=>$trade_id))->find();

        $logwhere = array(
            "trade_id"=>$trade_id,
        );
        $log_ids = M('r_trade_account_log')->where($logwhere)->getField('log_id', true);
        $trade['log'] = M('log')->where('log_id in (%s)', implode(',', $log_ids))->order("update_date desc")->select();;

        $this->trade = $trade;
        if ($branch && !in_array($trade['trade_id'], self::get_astrict_list())) {
            $trade = self::fix_branch_fields(getBranchFields("trade"), $trade, in_array($trade['owner_role_id'], $branch));
        }
        $fields_group = product_field_list_show('trade', $trade);
        if (!vali_permission("trade", "cost")) {
            unset($fields_group['51']);
        }
        $this->fields_group = $fields_group;
        $this->alert = parseAlert();
        $this->refer_url= refer_url('refer_view_url');
        $this->display();
	}

	public function edit(){
        $trade_id = $this->_request('id');
        if (0 == $trade_id) {
            alert('error', L('PARAMETER_ERROR'), U('serve/index'));
        }
        $trade = D('TradeView')->where('trade.trade_id = %d',$trade_id)->find();
        if (!$trade) {
            alert('error', "没有这个产品",$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if($this->isPost()){
            $m_branch = D("BranchCategoryView")->where(array("branch_category.role_id"=>$_POST['owner_role_id']))->find();
            if ($m_branch) {
                $_POST['branch_id'] = $m_branch['branch_id'];
            }
            $_POST['surplus_price'] = ($_REQUEST['state'] == "已撤销" ? 0 : $_POST['sum_price'] - $trade['pay_price']);
            if($this->submit_edit($trade_id)) {
                self::update_surplus_price("trade", $trade_id);
                self::update_proudct_commiss_info($trade_id);
                self::update_keyword($trade_id);
                alert('success', "编辑产品订单成功", U('trade/view', 'id='.$trade_id));
            } else {
                alert('error', "编辑产品订单失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            $this->alert = parseAlert();;
            $this->trade = $trade;
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->model_id = $trade['trade_id'];

            $fields_group =  product_field_list_html("edit","trade",$trade);
            if (!vali_permission("trade", "cost")) {
                unset($fields_group['51']);
            }
            $this->fields_group =  $fields_group;
            $this->display();
        }
	}

    public function delete(){
        $trade_ids = $this->isPost() ? $_POST['trade_id'] : $_GET['id'];
        if (!is_array($trade_ids)) {
            $trade_ids = array($trade_ids);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $trade_corre_model = array();
        $trades = D("TradeView")->where('trade.trade_id in (%s)', $trade_ids)->select();
        foreach($trades as $v) {
            $corre_ids = M($v['corre']."_trade")->where('trade_id in (%s)', $trade_ids)->getField($v['corre']."_id");
            $trade_corre_model[$v['corre']] = array($trade_corre_model[$v['corre']], $corre_ids);
            M($v['corre']."_trade")->where('trade_id in (%s)', $trade_ids)->delete();
        }

        $flow_module = array(
            'trade_cost',
        );
        if ($this->submit_delete($trade_ids, $flow_module)) {
            self::update_model_surplus_price($trade_corre_model);
            alert('success', "删除成功" ,U('trade/index'));
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }

    public function listDialog(){
        if ($this->isAjax() === false) {
            return $this->display("Trade:listDialog");
        }

        $data_field = array(
            array(
                "field"=>"trade_id",
                "order"=>"trade_id"
            ),
            array(
                "field"=>"orderid",
                "order"=>"orderid"
            ),
            array(
                "field"=>"serve_name",
                "order"=>"serve_name"
            ),
            array(
                "field"=>"corre_info",
                "order"=>"trade_id"
            ),
            array(
                "field"=>"sum_price_show",
                "order"=>"sum_price"
            ),
            array(
                "field"=>"surplus_price_show",
                "order"=>"surplus_price"
            ),
            array(
                "field"=>"loans_show",
                "order"=>"loans"
            ),
        );
        $module_view = "TradeView";
        if ($_GET['corre'] && $_GET['corre'] != "inernal") {
            $module_view = ucfirst($_GET['corre']).$module_view;
        }
        $where = $this->parse_dialog_where();
        $this->ajaxReturn(make_data_list($module_view, $where, $data_field, array($this, "format_dialog_item")),'JSON');
    }

    public function parse_dialog_where() {
        $where = parent::parse_dialog_where();
        if ($_GET['corre'] && $_GET['corre'] != "inernal") {
            if ($_GET['id']) {
                $where[$_GET['corre'].'_id'] = $this->_request("id");
            }
        }
        $where['league_id'] = session('league_id');
        return $where;
    }

    public function format_dialog_item($val) {
        $val["sum_price_show"] = number_format($val["sum_price"], 2);
        $val["surplus_price_show"] = number_format($val["surplus_price"], 2);
        $val["loans_show"] = number_format($val["loans"], 2);

        $where = array(
            "trade.trade_id"=>$val['trade_id']
        );
        $correinfo = D(ucfirst($val['corre'])."TradeView")->where($where)->find();
        if ($correinfo) {
            $val["corre_info"] = array(
                "corre_id"=>$correinfo[$val['corre'].'_id'],
                'idcode'=>$correinfo['idcode'],
                'name'=>$correinfo[$val['corre'].'_name'],
                'corre_link'=>U($val['corre'].'/view', 'id='.$val['corre_info']['corre_id'])
            );
        }
        $val["trade_id"] = array(
            "trade_id"=>$val['trade_id']
        );
        return $val;
    }

    public function analytics(){
        $params = array();
        $assort = $_GET['assort'] ? $_GET['assort'] : "state";
        if ($_GET['assort']) {
            $params[] = "assort=".$_GET['assort'];
        }
        $time_limit = self::default_statistics_time($params);

        $state_where = array(
            "已结束"=>" trade.state!='已撤销' and UNIX_TIMESTAMP() > end_date and (end_date !=0 ) ",
            "已撤销"=>" trade.state='已撤销'",
            "待开始"=>" trade.state!='已撤销' and begin_date=0 or UNIX_TIMESTAMP() < begin_date ",
            "进行中"=>" trade.state!='已撤销' and UNIX_TIMESTAMP() > begin_date and ( UNIX_TIMESTAMP()<end_date or end_date =0 ) "
        );

        if ($assort == "state") {
            $create_time= array(array('elt',$time_limit[1]),array('egt',$time_limit[0]), 'and');

            foreach($state_where as $k=>$s) {
                $where = array(
                    '_string'=>$s,
                    'create_time'=>$create_time
                );
                $state_report_analy['count'][$k] = D('TradeView')->where($where)->count();
                if (!$state_report_analy['count'][$k]) {
                    $state_report_analy['count'][$k] = 0;
                }
                $state_report_list[$k]['count'] = $state_report_analy['count'][$k];

                $state_report_analy['money'][$k] = D('TradeView')->where($where)->sum("pay_price");
                if (!$state_report_analy['money'][$k]) {
                    $state_report_analy['money'][$k] = 0;
                }
                $state_report_list[$k]['money'] = $state_report_analy['money'][$k];
            }

            $this->state_report_list = $state_report_list;
            $this->state_report_analy = $state_report_analy;
            $this->state_report_list_cnt = count($state_report_list) + 2;;

            $pc = M("serve_category")->select();
            foreach($pc as $v) {
                $cat_report_analy['count'][$v['name']] = D('TradeView')->where(array("category"=>$v['serve_category_id']))->count();
                if (!$cat_report_analy['count'][$v['name']]) {
                    $cat_report_analy['count'][$v['name']] = 0;
                }
                $cat_report_list[$v['name']]['count'] = $cat_report_analy['count'][$v['name']];

                $cat_report_analy['money'][$v['name']] = D('TradeView')->where(array("category"=>$v['serve_category_id']))->sum("pay_price");
                if (!$cat_report_analy['money'][$v['name']]) {
                    $cat_report_analy['money'][$v['name']] = 0;
                }
                $cat_report_list[$v['name']]['money'] = $cat_report_analy['money'][$v['name']];
            }
            $this->cat_report_list = $cat_report_list;
            $this->cat_report_analy = $cat_report_analy;
            $this->cat_report_list_cnt = count($cat_report_list) + 2;

            $statistics = array();
            $statistics['all_sum'] = M('trade')->count();
            $statistics['time_range_sum'] = M('trade')->where(array('create_time'=>$create_time))->count();
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
            self::default_cycle_money_statistics($time_limit[0], $time_limit[1], $cycle, $tab, "产品订单");
        }

        if ($assort == "statenewly") {
            $tab = "_".($_GET['tab'] ? $_GET['tab'] : "charts");
            if ($_GET['tab']) {
                $params[] = "tab=".$_GET['tab'];
            }

            $cycle = $_GET['cycle'] ? $_GET['cycle'] : "week";
            if ($_GET['cycle']) {
                $params[] = "cycle=".$_GET['cycle'];
            }

            $where = array();
            if ($_GET['cat']) {
                $params[] = "cat=".$_GET['cat'];
                $allchild = cateallchild("serve",$_GET['cat']);
                $allchild[] = $_GET['cat'];
                $where['category'] = array("in", $allchild);
            }
            $this->serve_category = M("serve_category")->select();

            $astate = $_GET['astate'] ? $_GET['astate'] : "进行中";
            $where["_string"] = $state_where[$astate];
            $params[] = "astate=".$_GET['astate'];
            $this->state_array = $state_where;

            self::cycle_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab, $where);
        }

        if ($assort == "basisstatenewly") {
            $tab = "_".($_GET['tab'] ? $_GET['tab'] : "charts");
            if ($_GET['tab']) {
                $params[] = "tab=".$_GET['tab'];
            }

            $cycle = $_GET['cycle'] ? $_GET['cycle'] : "week";
            if ($_GET['cycle']) {
                $params[] = "cycle=".$_GET['cycle'];
            }

            $where = array();
            if ($_GET['cat']) {
                $params[] = "cat=".$_GET['cat'];
                $allchild = cateallchild("serve",$_GET['cat']);
                $allchild[] = $_GET['cat'];
                $where['category'] = array("in", $allchild);
            }
            $this->serve_category = M("serve_category")->select();

            $astate = $_GET['astate'] ? $_GET['astate'] : "进行中";
            $where["_string"] = $state_where[$astate];
            $params[] = "astate=".$_GET['astate'];
            $this->state_array = $state_where;

            self::cycle_basis_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab, $where);
        }

        $this->parameter = implode('&', $params);
        $this->assort = $assort;
        $this->alert = parseAlert();
        $this->display($assort."_analytics".$tab);
    }

    public function cycle_newly_statistics($start_time, $end_time, $cycle, $tab, $awhere) {
        $this->cycle_data = self::cycle_array($start_time, $end_time, $cycle, $tab == "_charts");

        $this->cycle_title = date("Y", $start_time)."年产品订单";
        $this->cycle_create_data = self::cycle_date_array($start_time, $end_time, $cycle, $awhere);

        $this->cycle_money_title = date("Y", $start_time)."年产品订单销售";
        $this->cycle_money_data = self::cycle_money_array($start_time, $end_time, $cycle, $awhere);

        self::default_cycle_money_bulking_data($tab, "产品订单");
    }

    public function cycle_basis_newly_statistics($start_time, $end_time, $cycle, $tab, $awhere) {
        $this->cycle_data = self::cycle_array($start_time, $end_time, $cycle, $tab == "_charts");

        $this->cycle_title = date("Y", $start_time)."年产品订单";
        $this->cycle_create_data = self::cycle_date_array($start_time, $end_time, $cycle, $awhere);

        $this->cycle_money_title = date("Y", $start_time)."年产品订单销售";
        $this->cycle_money_data = self::cycle_money_array($start_time, $end_time, $cycle, $awhere);

        $yester_start_time = strtotime('-1 year', $start_time);
        $yester_end_time = $yester_start_time + ($end_time - $start_time);

        $this->yester_cycle_title = date("Y", $yester_start_time)."年产品订单";
        $this->yester_cycle_create_data = self::cycle_date_array($yester_start_time, $yester_end_time, $cycle, $awhere);

        $this->yester_cycle_money_title = date("Y", $yester_start_time)."年产品订单销售";
        $this->yester_cycle_money_data = self::cycle_money_array($yester_start_time, $yester_end_time, $cycle, $awhere);

        self::default_cycle_basis_money_bulking_data($tab, "产品订单");
    }


    public function cycle_date_array($start_time, $end_time, $cycle, $awhere) {
        $this->module = strtolower(MODULE_NAME);
        $start_time = germ_cycle($start_time, $cycle);
        while($start_time <= $end_time) {
            $time_begin = $start_time;
            $time_end = $start_time = ($cycle == "quarter" ? aquarter($time_begin, 1) : strtotime('+1 '.$cycle, $time_begin));
            $where_cycle_create['trade.create_time'] = array(array('lt',$time_end),array('gt',$time_begin), 'and');
            $where_cycle_create = array_merge($awhere, $where_cycle_create);
            $cnt = D("TradeView")->where($where_cycle_create)->count();
            $cycle_create_array[] = $cnt ? $cnt : 0;
        }
        return $cycle_create_array;
    }

    public function cycle_money_array($start_time, $end_time, $cycle, $awhere) {
        $this->module = strtolower(MODULE_NAME);
        $start_time = germ_cycle($start_time, $cycle);
        while($start_time <= $end_time) {
            $time_begin = $start_time;
            $time_end = $start_time = ($cycle == "quarter" ? aquarter($time_begin, 1) : strtotime('+1 '.$cycle, $time_begin));
            $where_cycle_create['trade.create_time'] = array(array('lt',$time_end),array('gt',$time_begin), 'and');
            $where_cycle_create = array_merge($awhere, $where_cycle_create);
            $sum = D("TradeView")->where($where_cycle_create)->sum("pay_price");;
            $cycle_create_array[] =$sum ? $sum : 0;
        }
        return $cycle_create_array;
    }
}
