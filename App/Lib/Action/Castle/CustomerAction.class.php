
<?php
class CustomerAction extends CastleAction {

    public function change_authInfo() {
        if (!isset($_GET['id'])) {
            die("Please provide a date range.");
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $customer_id = intval($this->_request('id'));
        $customer = D('CustomerView')->where('customer.customer_id = %d',$customer_id)->find();
        if (!$customer) {
            alert('error', "没有找到这个客户",$_SERVER['HTTP_REFERER']);
        }

        if($_POST['submit']){
            $this->submit_auth($customer);
            alert('success', "修改客户账户成功", $_POST['refer_url'] ? $_POST['refer_url'] : U('product/index'));
        }else{
            $user_customer = M('mUser')->where(array('model'=>"customer",'model_id'=>$customer_id))->find();
            if ($user_customer) {
                $this->username = $user_customer['username'];
            } else {
                $this->username = $customer['telephone'];
            }
            $this->password = ($user_customer ? $user_customer['password'] : "234567");

            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->customer_id = $customer_id;
            $this->display();
        }
    }


    public function is_fix_branch_field($value, $branchlock) {
        $branch_id = session('branch_id');
        if ($branch_id == $value['branch_id'])
            return false;
        $where = array("customer_id"=>$value['customer_id'], "branch_id"=>$branch_id);
        if (M("market")->where($where)->count() > 0)
            return false;
        return $branchlock && !in_array($value['customer_id'],  self::get_astrict_list());
    }

    public function all_search_keyword($module) {
        return array("customer.channel_role_model_keyword", "customer.channel_role_id_keyword", "customer.slug");
    }


    public function show_list($where = array(), $params = array()) {
        $this->assign_module_list($where, $params, "客户表");
        $list = $this->list;
        $this->return_data($list, $this->page->totalRows);
    }

    function format_verify_state($value) {
        if ($value['submit_state'] == 0) {
            $value["is_verify"] = "未提交";
        } else {
            if (($value["basic_submit_time"] > 0 && $value["basic_verify"] == 0)) {
                $value["is_verify"] = "待审核";
            } elseif($value["basic_verify"] == -1) {
                $value["is_verify"] = "审核未通过";
            }else{
                $value["is_verify"] = "审核通过";
            }
        }
        return $value;
    }

    function perfect_list_item($value, $export = false, $branchlock = false) {
        $value["balance"] = number_format($value['balance'], 2);
        $value = $this->format_verify_state($value);
        return parent::perfect_list_item($value, $export, $branchlock);
    }

    public function add_customer_basic() {
        $_POST['league_id'] = session('league_id');
        $_POST['name'] = trim($_POST['name']);
        $_POST['service_state'] = "未成单";
        if (!($customer_id = $this->submit_add("customer"))) {
            return false;
        }

        $idcode = sprintf("KH%07d", $customer_id);
        $data = array(
            'idcode'=>$idcode,
            'slug'=>Pinyin($this->_request("name")),
            'submit_state'=>0x1,
            'basic_submit_time'=>time(),
        );
        $m_branch = D("BranchCategoryView")->cache(true)->where(array("branch_category.role_id"=>$_POST['owner_role_id']))->find();
        if ($m_branch) {
            $data['branch_id'] = $m_branch['branch_id'];
        }
        M('Customer')->where(array('customer_id'=>$customer_id))->setField($data);
        $this->log($customer_id, "新建客户", "快速新建客户成功");
        return $customer_id;
    }


    private function update_commiss_info($customer, $commiss_id, $oldcustomer = null) {
        if (!is_array($customer)) {
            $customer = M("customer")->where("customer_id=".$customer)->find();
        }
        $keyword = array(
            $customer['name'],
            $customer['idcode'],
            $customer['telephone'],
            $customer['wechat'],
        );
        $data = array(
            "related_model_name"=>"customer",
            "related_model_id"=>$customer['customer_id'],
            "related_model"=>"[".$customer['idcode']."]".$customer['name'],
            "related_model_keyword"=>implode(chr(10), $keyword)
        );
        if ($oldcustomer) {
            $logcnt = "";
            if ($oldcustomer['telephone'] != $customer['telephone'])
            {
                $data['telephone'] = $customer['telephone'];
                $logcnt .= "客户：".product_show_html($customer)."手机号变更".$oldcustomer['telephone']."=>".$customer['telephone'];
            }
            if ($oldcustomer['telephone'] != $customer['telephone'])
            {
                $data['wechat'] = $customer['wechat'];
                $logcnt .= "客户：".product_show_html($customer)."微信变更".$oldcustomer['wechat']."=>".$customer['wechat'];
            }
            if ($oldcustomer['qq_number'] != $customer['qq_number'])
            {
                $data['qq_number'] = $customer['qq_number'];
                $logcnt .= "客户：".product_show_html($customer)."QQ变更".$oldcustomer['wechat']."=>".$customer['wechat'];
            }
            if ($oldcustomer['name'] != $customer['name'])
            {
                $data['name'] = $customer['name'];
                $logcnt .= "客户：".product_show_html($customer)."姓名".$oldcustomer['name']."=>".$customer['name'];
            }
            if ($logcnt) {
                A("Manage/Commiss")->log("cbg", $commiss_id,"客户信息变更", $logcnt);
            }
        }
        M("commiss")->where(array("commiss_id"=>$commiss_id))->setField($data);
    }

    public function check_commiss_info() {
        if ($_REQUEST['customer_id']) {
            $customer = M('customer')->where(array('customer_id'=>$this->_request('customer_id')))->find();
        }
        if ($commiss = $this->commiss_check_where($customer)) {
            $this->ajaxReturn($commiss,"",1);
        } else{
            $this->ajaxReturn(null,"",1);
        }
    }

    public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()){
            if ($_POST['byc'] != "commiss") {
                if ($this->commiss_check_where(null)) {
                    alert('error', " 这个客户的联系方式在客服模块有登记，请联系客服指派", $_SERVER['HTTP_REFERER']);
                }
            }
            $customer_id = $this->add_customer_basic();
            if (!$customer_id) {
                alert('error',  "新建客户失败", $_SERVER['HTTP_REFERER']);
            }

            if ($_REQUEST['card_pic_base64'])  {
                $picinfo = update_base64_pic($_REQUEST['card_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("card_pic", $customer_id, "customer", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }
            if ($_REQUEST['work_pic_base64'])  {
                $picinfo = update_base64_pic($_REQUEST['work_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("work_pic", $customer_id, "customer", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }
            if ($_POST['byc'] == "commiss" && $_POST['cmodel_id']) {
                $this->update_commiss_info($customer_id, $_POST['cmodel_id']);
            }
            $this->update_keyword($customer_id);
            $this->log($customer_id, "新建客户", "新建客户成功");
            $this->alert = parseAlert();
            alert('success', L('ADD_CUSTOMER_SUCCESS'), U('customer/view', 'id='.$customer_id));
		}else{
            $module_data = array();
            if ($_GET['byc'] == "commiss") {
                $m_commiss = M("commiss")->where(array("commiss_id"=>$_GET['cmodel_id']))->find();
                if ($m_commiss) {
                    foreach(array('name', 'channel_role_model', "channel_role_id", "telephone", "wechat", "qq_number") as $cm) {
                        $module_data[$cm] = $m_commiss[$cm];
                    }
                }
                $this->byc = $_GET['byc'];
                $this->cmodel_id = $_GET['cmodel_id'];
            }

            $this->fields_group = product_field_list_html("add","customer", $module_data, "basic");
            $alert = parseAlert();
            $this->alert = $alert;
            $this->display();
		}
	}

    public function delete(){
        if (!$_REQUEST['customer_id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $customer_ids = is_array($_REQUEST['customer_id']) ? $_REQUEST['customer_id'] : array($_REQUEST['customer_id']);
        $delete_where = array('customer_id'=>array("in", $customer_ids));

        $customer_delete = M('customer')->where($delete_where)->delete();
        $customer_data_delete = M('customer_data')->where($delete_where)->delete();
        if(!$customer_delete || !$customer_data_delete) {
            alert('error', L('DELETE_FAILED_PLEASE_CONTACT_YOUR_ADMINISTRATOR'),$_SERVER['HTTP_REFERER']);
        }
        M("customer_subgroup")->where($delete_where)->delete();
        M("m_user")->where(array('model_id'=>array("in", $customer_ids), 'model'=>"customer"))->delete();
        M("commiss")->where(array("related_model_name"=>"customer", "related_model_id"=>array("in", $customer_ids)))->setField(array("related_model"=>"","related_model_name"=>"","related_model_id"=>"","related_model_keyword"=>""));

        $account_where = array(
            'clause_additive'=>array("in", $customer_ids),
            'account_type'=>'customer'
        );
        $account_ids = M('account')->where($account_where)->getField('account_id', true);
        $this->delete_accounts($account_ids);

        $r_module = array();
        foreach ($customer_ids as $value) {
            foreach ($r_module as $key2=>$value2) {
                $module_ids = M($value2)->where('customer_id = %d', $value)->getField($key2 . '_id', true);
                M($value2)->where('customer_id = %d', $value)->delete();
                if(!is_int($key2)){
                    M($key2)->where($key2 . '_id in (%s)', implode(',', $module_ids))->delete();
                }
            }
            $this->delete_files($value);
        }

        $related_module = array("trade", "trainorder", "business", "leads");
        foreach($related_module as $r) {
            $this->related_delete($customer_ids, $r);
            if ($r == "trainorder") {
                $r = "train";
            }
            if ($r != "business") {
                M("customer_" . $r)->where($delete_where)->delete();
            }
        }
        $this->log($customer_ids, "删除客户", "删除客户成功");
        alert('success', "删除客户完成", U("customer/index"));
    }

    public function update_relead_model_keyword($newcustomer) {

        $model_keyword = array();
        $model_keyword[] = $newcustomer['idcode'];
        $model_keyword[] = $newcustomer['name'];
        $model_keyword[] = $newcustomer['telephone'];
        M("cultivate")->where(array("model"=>"customer", "model_id"=>$newcustomer['customer_id']))->setField("model_id_keyword", implode(chr(10), $model_keyword));

    }

	public function edit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $findwhere = array('customer.customer_id'=>$this->_request('id'));
        $customer = D('CustomerView')->where($findwhere)->find();
		if (!$customer) {
            alert_back('error',  L('CUSTOMER_DOES_NOT_EXIST!'));
        }
        if (!session('?admin') && session('restriction') === true && $customer['owner_role_id']) {
            if (!self::is_owner($customer, $branch = get_branch(session("role_id")))){
                alert_back('error',  "你没有权限");
            }
        }
        $assort = $this->_request('assort', 'trim', "basic");
        $customer['owner'] = D('RoleView')->where('role.role_id = %d', $customer['owner_role_id'])->find();

		if($this->isPost()){
            if ($this->commiss_check_where($customer)) {
                alert('error', " 这个客户的联系方式在客服模块有登记，请联系客服指派", $_SERVER['HTTP_REFERER']);
            }

            $_POST['name'] = trim($_POST['name']);
            $_POST['slug'] = Pinyin($this->_request("name"));
            if (!$this->submit_edit($customer['customer_id'])) {
                alert_back('error',  "编辑客户失败");
            }

            if ($_REQUEST['card_pic_base64']) {
                $picinfo = update_base64_pic($_REQUEST['card_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("card_pic", $customer['customer_id'], "customer", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }
            if ($_REQUEST['work_pic_base64'])  {
                $picinfo = update_base64_pic($_REQUEST['work_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("card_pic", $customer['customer_id'], "customer", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }
            $change_fields = D('CustomerView')->verity_check($customer);
            $basic_data = array(
                "basic_submit_time"=>time(),
                "submit_state"=>0x1
            );
            $m_branch = D("BranchCategoryView")->cache(true)->where(array("branch_category.role_id"=>$_POST['owner_role_id']))->find();
            if ($m_branch) {
                $basic_data['branch_id'] = $m_branch['branch_id'];
            }
            $where = "customer_id=".$customer['customer_id'];
            M("customer")->where($where)->setField($basic_data);

            $this->update_keyword($customer['customer_id']);

            $newcustomer = D('CustomerView')->where($findwhere)->find();
            if ($newcustomer['commiss_id']) {
                $this->update_commiss_info($newcustomer, $newcustomer['commiss_id'], $customer);
            }
            if ($newcustomer['channel_role_model'] != $customer['channel_role_model'] || $newcustomer['channel_role_id'] != $customer['channel_role_id']) {
                $this->update_correlation_channel_introducer("customer", $newcustomer);
            }
            $this->update_relead_model_keyword($newcustomer);
            $this->add_edit_log($customer['customer_id'], "修改基本信息成功: ", $change_fields);

            alert('success', "编辑客户成功", U('customer/view', 'id='.$customer['customer_id']));

		}else{
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();

            //雇员图片
            $m_customer_images = M('customerImages');
            $customer['images']['main'] = $m_customer_images->where('customer_id = %d and is_main = 1', $customer['customer_id'])->find();
            $customer['images']['cardpic'] = $m_customer_images->where(array("customer_id"=>$customer['customer_id'],"is_main"=>2))->find();
            $fields_group = product_field_list_html("edit","customer",$customer, $assort);
            unset($fields_group[48]);
            $this->fields_group =$fields_group;
            $this->model_id = $customer['customer_id'];
            $this->customer = $customer;
            $this->display();
		}
	}

    public function verify() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $customer= M('Customer')->where('customer_id = %d',$this->_request('customer_id'))->find();
        if (!$customer) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $this->customer = $customer;
        $this->state = $customer["basic_verify"];
        $this->display(); // 输出模板
    }

    public function doverify() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $findwhere = array(
            'customer_id'=>$this->_request('customer_id')
        );
        $customer = D('CustomerView')->where($findwhere)->find();
        if (!$customer) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $state = $this->_request('state', 'trim', "0");
        $desc = $this->_request('describe', 'trim', "");

        if ($state == -1) {
            $data = array(
                "basic_verify"=>0,
                "basic_submit_time"=>0,
                "submit_state"=>0
            );
            M('Customer')->where($findwhere)->setField($data);
        } else {
            M('Customer')->where($findwhere)->setField("basic_verify", ($state == 0 ? -1 : time()));
        }
        $logid = $this->log($customer['customer_id'], "验证日志", $desc, 5);

        if ($state == 0) {
            $where = array(
                "model"=>"customer",
                "model_id"=>$customer['customer_id']
            );
            M("m_user")->where($where)->setField("basic_verify_log", $logid);
        }
        alert('success',L('审核状态编辑成功', array(L('LOG'))),$_SERVER['HTTP_REFERER']);
    }


    private function add_edit_log($customer_id, $logcont, $change_fields) {
        foreach($change_fields as $v) {
            $logcont.=$v['name']."[".$v['oldvalue']."=>".$v['newvalue']."],";
        }
        $this->log($customer_id, "更新日志",$logcont);
    }

    public function log($customer_ids, $subject, $content, $category_id = 6) {
        $log_id = 0;
        $customers = M("customer")->where( array('customer_id'=>array("in", $customer_ids)))->select();
        foreach($customers as $v) {
            $m_log = M('Log');
            $m_log->role_id = session("role_id");
            $m_log->subject = $subject;
            $m_log->content = $content;
            $m_log->category_id = $category_id;
            $m_log->create_date = time();
            $m_log->update_date = time();
            if ($log_id = $m_log->add()) {
                $data['customer_id'] = $v['customer_id'];
                $data['customer_name'] = $v['name'];
                $data['customer_idcode'] = $v['idcode'];
                $data['log_id'] = $log_id;
                $data['league_id'] = session('league_id');
                M('r_customer_log')->add($data);
            }
        }
        return $log_id;
    }

    public function mission() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $customer = D('CustomerView')->where('customer.customer_id = %d', $this->_request('id'))->find();
        if (!$customer) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $customer['live_address'] = mb_substr(format_address_field($customer['live_address']), 3);
        $this->customer = $customer;
        $this->display(); // 输出模板
    }


    public function view(){
        $this->viewinfo("basic");
	}

    public function tradeview() {
        $this->viewinfo('trade');
    }

    public function marketview() {
        $this->viewinfo('market');
    }

    public function cultivateview(){
        $this->viewinfo("cultivate");
    }


    public function viewinfo($assort) {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $customer_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $customer_id) {
            alert('error', L('parameter_error'), U("Customer/index"));
        }
        $where = array("customer.customer_id"=>$customer_id);
        $customer = D('CustomerView')->where($where)->find();
        if (!$customer) {
            alert('error', L('PARAMETER_ERROR'), U("Customer/index"));
        }

        if (!session('?admin') && session('restriction') === true) {
            if (!self::is_interest($customer, $branch = get_branch(session("role_id")))) {
                alert('error', "你没有权限访问", U("Customer/index"));
            }
        }

        if ($assort == "account") {
            $this->clause_additive = $customer['customer_id'];
            $accountcat = array(
                0=>"全部",
                "-1"=>"支出",
                "1"=>"收入",
                "3,-3"=>"冻结"
            );
            $this->accountcat = $accountcat;
            $this->acat = $_GET['acat'] ? $_GET['acat'] : "0";
            $this->accounts_totals = $this->account_total($this->acat, $customer['customer_id']);

            $customer['loans'] = number_format($customer['loans'], 2);
            $customer['balance'] = number_format($customer['balance'], 2);
            $customer['freeze'] = number_format($customer['freeze'], 2);
            $customer['actual'] = number_format($customer['actual'], 2);
            $customer['cash'] = number_format($customer['cash'], 2);
            $customer['trade_surplus_price'] = number_format($customer['trade_surplus_price'], 2);
            $customer['market_surplus_price'] = number_format($customer['market_surplus_price'], 2);
            $customer['sum_surplus_price'] = number_format($customer['sum_surplus_price'], 2);
        }

        if ($assort == "market") {
            $market_list = D('MarketView')->where('market.customer_id = %d', $customer['customer_id'])->order("market_id desc")->select();
            $customer['market_count'] = count($market_list);
            $customer['market'] = MarketAction::replenish_market_list($market_list);
            $model_fileds = getFields(array(
                "637",
                "642",
                "657",
                "654",
                "751",
                "762",
                "753",
                "655",
                "656",
                "646",
                "658",
                "659",
                "688",
                "649",
                "689",
                "690",
                "749",
                "750",
                "661",
            ), false);
            $this->field_array = $this->format_index_fields($model_fileds);
        }

        if ($assort == "cultivate") {
            $cultivate_list = D('CultivateView')->where(array("model_id"=>$customer['customer_id'], "model"=>"customer"))->select();
            $product['cultivate_count'] = count($cultivate_list);
            $product['cultivate'] =  CultivateAction::replenish_cultivate_list($cultivate_list);
            $model_fileds = getFields(array(
                "849",
                "866",
                "933",
                "871",
                "953",
                "877",
                "868",
                "869",
                "878",
            ), false);
            $this->field_array = $this->format_index_fields($model_fileds);
        }

        if ($assort == "trade") {
            $trade_order = D('CustomerTradeView')->where('customer_trade.customer_id = %d', $customer_id)->select();
            foreach($trade_order as $k=>$v) {
                $trade_order[$k] = TradeAction::format_trade_item($v);
            }
            $customer['trade'] =$trade_order;
            $this->field_array = $this->format_index_fields(getIndexFields('trade'));
        }

        $m_customer_images = M('customerImages');
        $customer['images']['main'] = $m_customer_images->where('customer_id = %d and is_main = 1', $customer['customer_id'])->find();
        $customer['images']['cardpic'] = $m_customer_images->where(array("customer_id"=>$customer['customer_id'],'is_main'=>2))->find();

        $customer['is_owner'] = session('?admin');
        if (!$customer['is_owner']) {
            $branch_role = get_branch(session("role_id"));
            $branchlock = vali_permission("branchlock", "customer");
            $customer['is_owner'] =  ($branchlock && $customer['owner_role_id'] && $branch_role ? self::is_owner($customer, $branch_role) : true);
            if ($this->is_fix_branch_field($customer, $branchlock)) {
                $customer = self::fix_branch_fields(getBranchFields("customer"), $customer, in_array($customer['owner_role_id'], $branch_role));
            }
        }

        $this->customer = $customer;
        $this->assort = $assort;
        $this->customer_id = $customer_id;

        $fields_group = product_field_list_show('customer', $customer, $assort);
        unset($fields_group[48]);
        $this->fields_group =$fields_group;
        $this->refer_url= session("index_refer_url");

        $this->alert = parseAlert();
        $this->display();
    }

    public function astrict() {
        if (!$_REQUEST['id']) {
            alert('error', "参数错误" ,$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $customer_id = $this->_request("id");
        $customer = M("customer")->where(array("customer_id"=>$customer_id))->find();
        if (!$customer) {
            alert('error', "参数错误" ,$_SERVER['HTTP_REFERER']);
        }
        $this->model_id = $customer_id;

        $branch = get_branch(session("role_id"));
        if (!session('?admin') && ($branch && !self::is_owner($customer, $branch) && $customer['owner_role_id'])) {
            alert('error', "您没有权限操作" ,$_SERVER['HTTP_REFERER']);
        }
        $this->user_list = D("AstrictUserView")->where(array("model"=>"customer", "model_id"=>$customer_id))->select();
        $this->display("Public:def_astrict");
    }

    public function reset_calculate_sum_account() {
        foreach(M("customer")->select() as $v) {
            self::update_model_surplus_price(array("customer"=>$v['customer_id']));
        }
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
                "customer.customer_id"=>$id
            );
        }
        $info = D('CustomerView')->where($where)->find();
        if ($info['owner_role_id']) {
            $info['owner_role_name'] = D("StaffView")->where(array("role.role_id"=>$info['owner_role_id']))->getField("name");
        }
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
                $state_report_list[$s] = M('Customer')->where($where)->count();
            }
            $this->state_report_list = $state_report_list;

            $statistics = array();
            $statistics['all_sum'] = M('Customer')->count();
            $statistics['time_range_sum'] = M('Customer')->where(array('create_time'=>$create_time))->count();
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
        foreach(M("customer")->select() as $v) {
            $m_branch = D("BranchCategoryView")->where(array("branch_category.role_id"=>$v['owner_role_id']))->find();
            if ($m_branch) {
                M("customer")->where("customer_id=".$v['customer_id'])->setField("branch_id", $m_branch['branch_id']);
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
                "field"=>"customer_show",
                "order"=>"customer_id"
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

        $data = make_data_list("CustomerLogView", $where, $data_field, array($this, "format_customer_log"));
        $this->ajaxReturn($data,'JSON');
    }

    public function format_customer_log($v) {
        $v['create_date_show'] = toDate($v['create_date']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        $html = product_show_html($v, false);;
        if (!$html) {
            $html = '<span>[' .$v['customer_idcode'].'] '.$v['customer_name'] . '</span>&nbsp;';;
        }
        $v['customer_show'] = $html;
        $v['content_show'] = "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>".cutString($v['content'], 43)."</a>";

        return $v;
    }

    public function format_account_info($v) {
        $v = parent::format_account_info($v);
        if ($v['clause_type_id'] == 31 && $v['related'] == "market" && $v['receipt_number']) {
            $v['description'].=" 收据号: ".$v['receipt_number'];
        }
        return $v;
    }



    public function reset_keyword() {
        foreach(M("customer")->select() as $v) {
            $this->update_keyword($v);
        }
    }

    public function update_keyword($customer) {
        if(!is_array($customer)) {
            $customer = D("Manage/CustomerView")->where(array("customer_id"=>$customer))->find();
        }
        $keyword = array();

        $data = array(
            "keyword"=>implode(chr(10), $keyword)
        );
        $data = make_channel_model_keyword($customer['channel_role_model'], $customer['channel_role_id'], $data);
        M("customer")->where(array("customer_id"=>$customer['customer_id']))->setField($data);
    }

}