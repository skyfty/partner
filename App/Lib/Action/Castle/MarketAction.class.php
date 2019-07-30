<?php
/**
*订单模块
*
**/

class MarketAction extends CastleAction{

    public function getmarketproduct() {
        $market_id = $this->_request('market_id');
        $where = array(
            "market_id"=>$market_id
        );
        $this->ajaxReturn(D("MarketProductView")->where($where)->order("real_start_time desc")->select());
    }

    public function settle_state_count($category_id,$customer_id) {
        $initial_where = array(
            'settle_state'=>918,
            'category_id'=>$category_id,
            'customer_id'=>$customer_id
        );
        $initial_where['league_id'] = session('league_id');

        return M("market")->where($initial_where)->count();
    }

	/**
	*添加订单
	*
	**/
	public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if(!$this->isPost()) {
            $this->role_branch = M("branch_category")->where("role_id=".session('role_id'))->find();
            return $this->show_add();
        }
        if (!$_POST['category_id']) {
            alert_back('error', "服务类别不可为空");
        }
        if (!$_POST['customer_id']) {
            alert_back('error', "客户不可为空");
        }
        $pc = M("product_category")->cache(true)->where("category_id=".$_POST['category_id'])->find();
        if (!$pc) {
            alert_back('error', "错误的服务类别");
        }

        $_POST['status_id'] = 1;
        $_POST['update_role_id'] = session('role_id');
        $_POST['league_id'] = session('league_id');

        if (!($market_id = $this->submit_add())) {
            alert_back('error', "新建订单失败");
        }

        $data = array(
            'idcode'=>$this->make_idcode($_POST['branch_id']),
        );;
        if ($pc['serve_modality'] == 0) {
            $settle_cnt = $this->settle_state_count($_POST['category_id'], $_POST['customer_id']);;
            $data['initial'] = ($settle_cnt > 0 ? "否":"");
        }
        M("market")->where(array('market_id'=>$market_id))->setField($data);

        $this->add_default_urge_role($market_id, $_POST['owner_role_id'], $_POST['update_role_id']);
        $this->add_default_channel($market_id, $_POST['owner_role_id']);
        $this->update_market($market_id, true);
        $this->log($market_id, "新建订单", "新建订单成功", 2);

        if (!$_POST['renewal_market_id']) {
            $this->log($market_id, "新建订单", "新建订单成功", 2);
            alert('success', "新建订单成功", U("Market/view", "id=".$market_id));
        }
        $renewal_market = D('MarketView') ->where('market.market_id = %d',$_POST['renewal_market_id'])->find();

        if ($renewal_market) {
            $this->log($market_id, "新建订单", "续约订单成功", 2);
            $market_product = self::seek_renewal_product($renewal_market);
            if ($market_product && in_array($market_product['station_state'],array('自愿离职','开除','其他未录用'))) {
                alert('error', "新建订单成功,但是续约雇员不在编, 不能续约他了", U("Market/view", "id=".$renewal_market['market_id']));
            }

            $param = array(
                "id"=>$market_id,
                "renewal_market_id"=>$renewal_market['market_id']
            );
            $this->redirect(U("market/product_renewal", $param));
        }
	}

    public function make_idcode($branch_id) {
        $brnach = M("branch")->cache(true)->where("branch_id=".$branch_id)->find();
        if ($brnach && $brnach['code']) {
            $idcode = $brnach['code'];
        } else {
            $idcode = "DF";
        }
        if ($brnach) {
            $count_where['branch_id'] =  $brnach['branch_id'];
        }
        $count_where['create_time'] =  array('gt',strtotime(date('Y-m-d', time())));
        $cnt = M("market")->where($count_where)->count();
        return $idcode . date("Ymd").sprintf("%03d", $cnt);
    }

    public function add_default_urge_role($market_id, $urge_role_id, $role_id) {
        $data = array(
            "market_id"=>$market_id,
            "urge_role_id"=>$urge_role_id,
            "update_role_id"=>$role_id,
            "creator_role_id"=>$role_id,
            "create_time"=>time(),
            "update_time"=>time(),
            "isdefault"=>1,
        );
        M("market_urge")->add($data);
        $this->log($market_id, "添加促单", "添加默认默认促单成功", 2);
    }

    public function add_default_channel($market_id, $role_id) {
        $market = D('MarketView')->where('market.market_id = %d',$market_id)->find();
        if ($market) {
            $m_customer = D("CustomerView")->where('customer.customer_id = %d',$market['customer_id'])->find();
            if ($m_customer) {
                $data = array(
                    "isdefault"=>1,
                    "market_id"=>$market_id,
                    "urge_role_id"=>$role_id,
                    "update_role_id"=>$role_id,
                    "creator_role_id"=>$role_id,
                    "create_time"=>time(),
                    "update_time"=>time(),
                    "origin"=>$m_customer['origin'],
                    "introducer"=>$m_customer['introducer'],
                    "channel_model"=>$m_customer['channel_model'],
                    "channel_model_id"=>$m_customer['channel_model_id'],
                    "channel_role_model"=>$m_customer['channel_role_model'],
                    "channel_role_id"=>$m_customer['channel_role_id'],
                    "channel_role_model_keyword"=>$m_customer['channel_role_model_keyword']?$m_customer['channel_role_model_keyword']:"",
                    "channel_role_id_keyword"=>$m_customer['channel_role_id_keyword']?$m_customer['channel_role_id_keyword']:"",
                );
                $market_channel_id = M("market_channel")->add($data);
                if ($market_channel_id) {
                    $this->update_default_channel($market_id, $m_customer, $market_channel_id);
                    $this->log($market_id, "添加渠道", "添加默认渠道成功", 2);
                }
            }
        }
    }

    public function update_default_channel($market_id, $channel_model, $channel_id = null) {
        $data = array(
            "channel_role_model"=>$channel_model['channel_role_model'],
            "channel_role_id"=>$channel_model['channel_role_id'],
            "channel_role_model_keyword"=>$channel_model['channel_role_model_keyword']?$channel_model['channel_role_model_keyword']:"",
            "channel_role_id_keyword"=>$channel_model['channel_role_id_keyword']?$channel_model['channel_role_id_keyword']:"",
        );;
        if ($channel_id) {
            $data['def_channel_id'] = $channel_id;
        }
        M("market")->where(array('market_id'=>$market_id))->setField($data);
    }

    public function quick_create_customer() {
        if ($this->commiss_check_where(null)) {
            alert('error', " 这个客户的联系方式在客服模块有登记，请联系客服指派", $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $_POST['owner_role_id'] = session('role_id');
        $_POST['league_id'] = session('league_id');
        $customer_id = A("Manage/Customer")->add_customer_basic();
        if (!$customer_id) {
            alert('error',  "新建客户失败", $_SERVER['HTTP_REFERER']);
        }
        $m_customer = D("CustomerView")->where("customer.customer_id=".$customer_id)->find();
        $this->ajaxReturn($m_customer ? $m_customer:null);
    }

    public function revoke() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $market_id = $this->_request('id');
        $market = D('MarketView')->where(array("market.market_id"=>$market_id))->find();
        if (!$market) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }

        if ($market['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }

        if ($market['sum_settle_price'] > 0) {
            alert('error', "撤销订单金额必须为0，到雇员服务信息页面修改",$_SERVER['HTTP_REFERER']);
        }
        if ($market['wait_confirm_price'] > 0) {
            alert('error', "订单存在未确认账目，到付款信息页面修改",$_SERVER['HTTP_REFERER']);
        }
        if (is_market_settle($market)) {
            alert('error', "订单已经提交结算",$_SERVER['HTTP_REFERER']);
        }
        if($market['customer_earnest']>0) {
            define("NO_AUTHORIZE_CHECK", true);
            $result = A("Manage/Account")->pay_market_account("customer", $market['customer_id'], $market['customer_earnest'], $market, 230);
            if (is_array($result)) {
                alert('error', $result['error'],$_SERVER['HTTP_REFERER']);
            }
            $this->payment_status(230, $result, $market_id, $market['customer_earnest']);
            $logcnt = "撤销客户服务导致冻结款全部解冻 "." <a href='".U("account/view", "id=".$result)."'>查看</a>";
            $this->log($market_id, "解冻客户服务冻结款", $logcnt, 2);
        }

        $market_data = array(
            "status_id"=>0,
            "settle_state"=>920,
        );
        M("market")->where(array('market_id'=>$market_id))->setField($market_data);
        M("market_product")->where(array('market_id'=>$market_id))->setField("service_status_id", 0);
        $this->update_market($market_id, false);
        $this->log($market_id, "订单撤销", "订单撤销成功", 2);
        alert('success', "订单撤销成功, 已经解冻客户定金",$_SERVER['HTTP_REFERER']);
    }

    public function seek_renewal_product($renewal_market) {
        $where = array(
            "real_end_time"=>array("neq", ""),
            "market_id"=>$renewal_market['market_id'],
        );
        $last_end_time = M("market_product")->where($where)->max('real_end_time');
        $where = array(
            "market.market_id"=>$renewal_market['market_id'],
            "market_product.real_end_time"=>array("egt", $last_end_time),
        );
        return D("MarketProductView")->where($where)->find();
    }

    public function product_renewal() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), U("market/index"));
        }
        $market_id = $this->_request('id');
        $market = D('MarketView')->where(array("market.market_id"=>$market_id))->find();
        if (!$market) {
            alert('error', L('PARAMETER_ERROR'),U("market/index"));
        }
        if ($market['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }

        $renewal_market_id = $this->_request('renewal_market_id');
        $renewal_market = D('MarketView') ->where('market.market_id = %d',$renewal_market_id)->find();
        if (!$renewal_market) {
            alert('error', L('PARAMETER_ERROR'),U("market/index"));
        }

        $market_product = self::seek_renewal_product($renewal_market);
        if ($market_product) {
            $market_product['real_start_time'] = $market_product['real_end_time'];
            $market_product['real_end_time'] = strtotime("+30 days", $market_product['real_start_time']);
        }
        $this->market = D('MarketView')->where(array("market.market_id"=>$market_id))->find();
        $this->lock_agency_scale = $this->is_lock_agency_scale($this->market);;
        $this->show_product_add($market_product);
    }

    public function show_add($market = null) {
        $fields_group = product_field_list_html($market ? "edit" : 'add','market', $market ? $market : array(), "basic");
        $this->fields_group = $fields_group;
        $this->refer_url = refer_url('refer_add_url');
        $this->alert = parseAlert();
        $this->display("Market:add");
    }

    private function is_lock_agency_scale($market) {
        $role_branch = M("branch_category")->where("role_id=".session('role_id'))->find();
        if (session('?admin') || !$role_branch) return false;
        if (!in_array($market['category_id'], array("5","9","11"))) return false;

        if (vali_permission("market", "product_add") || vali_permission("market", "product_edit")) {
            return false;
        }else{
            return true;
        }
    }

	public function edit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $market_id = $this->_request('id');
        if (0 == $market_id) {
            alert('error', L('PARAMETER_ERROR'), U('market/index'));
        }
        $market = D('MarketView') ->where('market.market_id = %d',$market_id)->find();
        if (!$market) {
            alert('error', L('THERE_IS_NO_BUSINESS_OPPORTUNITIES'),$_SERVER['HTTP_REFERER']);
        }
        if ($market['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }

        if (is_market_settle($market)) {
            alert('error', "订单已经提交结算， 不可以修改",$_SERVER['HTTP_REFERER']);
        }

        if(!$this->isPost()) {
            $this->model_id = $market['market_id'];
            $this->per_change_branch_id = (session('?admin') || vali_permission("market", "change_branch_id")) && $market['settle_state'] != 918;
            $this->per_change_owner_id = session('?admin') || vali_permission("market", "change_owner_id");
            return $this->show_edit($market);
        }

        if ($_POST['category_id']) {
            $pc = M("product_category")->where("category_id=".$_POST['category_id'])->find();
            if (!$pc) {
                alert_back('error', "错误的服务类别");
            }
            if ($pc['serve_modality'] == 0) {
                $settle_cnt = $this->settle_state_count($_POST['category_id'], $_POST['customer_id']);;
                $_POST['initial'] = ($settle_cnt > 0 ? "否":"是");
            }
        }


        $_POST['update_role_id'] = session('role_id');
        if(!$this->submit_edit($market_id)) {
            alert('error', "编辑订单失败", $_SERVER['HTTP_REFERER']);
        }
        if ($market['owner_role_id'] != $_POST['owner_role_id']) {
            $this->update_owner_role_id($market_id, $_POST['owner_role_id'], $_POST['update_role_id']);
        }

        $this->update_market($market_id, true);
        $this->add_edit_log($market_id, "修改信息成功: ", D('MarketView')->verity_check($market));
        alert('success', "编辑订单成功", U('market/view', 'id='.$market_id));
	}

    public function update_owner_role_id($market_id, $owner_role_id, $update_role_id) {
        $data = array(
            "urge_role_id"=>$owner_role_id,
            "update_role_id"=>$update_role_id,
            "update_time"=>time(),
        );
        M("market_urge")->where(array("isdefault"=>1, "market_id"=>$market_id))->setField($data);

        $where = array(
            "related"=>"market",
            "related_id"=>$market_id
        );
        M("account")->where($where)->setField("related_owner_role_id", $owner_role_id);
    }

    public function show_edit($market) {
        if (!is_array($market)){
            $market = D('MarketView') ->where('market.market_id = %d',$market)->find();
            if (!$market) {
                alert('error', L('THERE_IS_NO_BUSINESS_OPPORTUNITIES'),$_SERVER['HTTP_REFERER']);
            }
        }
        $fields_group = field_list_html_edit("market",$market, array("customer_id"));
        $this->fields_group = $fields_group;

        $this->market = $market;
        $this->refer_url=$_SERVER['HTTP_REFERER'];
        $this->alert = parseAlert();;
        $this->display("Market:edit");
    }


    public function view(){

		if (intval($this->_request('id')) <= 0) {
			alert('error', L('PARAMETER_ERROR'), U("market/index"));
		}
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $assort = $_GET['assort'] ? $_GET['assort'] : "basic";
        $market_id = $this->_request('id');

        $where = array("market.market_id"=>$market_id);
		$market = D('MarketView')->where($where)->find();
		if (!$market) {
            alert('error', L('PARAMETER_ERROR'),U("market/index"));
        }

        if (!session('?admin') && vali_permission("branchlock", "market") && session('restriction') === true) {
            $isinterest = self::is_interest($market, $branch = get_branch(session("role_id")), false);
            if (!$isinterest) {
                alert('error', "你没有权限访问", U("market/index"));
            }
        }

        $market['is_owner'] = session('?admin');
        if (!$market['is_owner']) {
            $branch_role = get_branch(session("role_id"));
            $market['is_owner'] = ($market['owner_role_id'] && $branch_role ? self::is_owner($market, $branch_role) : true);
        }
        $this->per_edit = $market['is_owner'] || vali_permission("market", "edit");
        $market['evaluate_state'] = $market['evaluate_state']?$market['evaluate_state']:"未评";

        if ($assort == "prompt") {
            $this->prompt_list = self::prompt_list("market", $market_id);
        }

        if ($assort == "account") {
            $param = array(
                "dire"=>3,
                "t"=>"customer",
                "type_id"=>230,
                "money"=>0,
                "clause_additive"=>$market['customer_id'],
                "refer_add_url"=>urlencode($_SERVER['REQUEST_URI']),
                "market_id"=>$market['market_id'],
                "lockele"=>array("clause_type_id", "market_name")
            );
            if (vali_permission("market", "account_edit")) {
                $param['restrict'] = "market";
            }
            $this->thaw_account_url = U("account/add",$param);
        }

        if ($assort == "product") {
            $where_evaluate = array(
                "market_id"=>$market['market_id'],
                "product_evaluate_state"=>array("eq", "已评")
            );
            $market_survey = M("market_survey")->where(array("market_id"=>$market['market_id']))->find();

            $this->evaluate_state = false;
            if (vali_permission("market", "survey") && M("market_product")->where($where_evaluate)->count() == $market['product_count']) {
                if ($market_survey['status']=="" || vali_permission("market", "survey_change")) {
                    $this->evaluate_state = true;
                }
            }
            if ($market_survey && $market_survey['status'] != "不符合") {
                $fields_group = product_field_list_show('market_survey', $market_survey);;
                unset($fields_group[0]['fields'][2]);
                $this->fields_group = $fields_group;
            }

            $evalwhere  = array(
                "status_id"=>"3",
                "customer_id"=>$market['customer_id'],
                "evaluate_state|survey_state"=>array("in", array("", "未评"))
            );
            $evalwhere['league_id'] = session('league_id');

            $this->wait_evallist = D("MarketView")->where($evalwhere)->select();
        }

        if ($assort == "cost") {
            $product_salary_list = D("MarketProductView")->where($where)->select();
            foreach($product_salary_list as $k=>$v) {
                $product_salary_list[$k] = self::format_product_info($v);
            }
            $this->proudct_salary_list = $product_salary_list;

            $accwhere = array(
                "related"=>"market",
                "related_id"=>$market['market_id'],
                "clause_type_id"=>array("in", array(236, 218, 253, 254)),
            );
            $this->product_salary_account_list = D("ProductAccountView")->where($accwhere)->order("create_time desc")->select();

            $channel_list = D("MarketChannelView")->where($where)->select();
            foreach($channel_list as $k=>$v) {
                $channel_list[$k] = self::format_channel_info($v);
            }
            $this->channel_list = $channel_list;
            $accwhere = array(
                "related"=>"market",
                "related_id"=>$market['market_id'],
                "clause_type_id"=>array("in", array(240, 242, 238, 223, 221,227)),
            );
            $this->channel_account_list = M("account")->where($accwhere)->order("create_time desc")->select();

            $urge_list = D("MarketUrgeView")->where($where)->select();
            $this->urge_list = $urge_list;
            $accwhere = array(
                "related"=>"market",
                "related_id"=>$market['market_id'],
                "clause_type_id"=>array("in", array(244, 226)),
            );
            $this->urge_account_list = M("account")->where($accwhere)->order("create_time desc")->select();
            $this->lock_agency_scale = $this->is_lock_agency_scale($market);;

            $rewardhere = array(
                "market.market_id"=>$market_id,
                "urge_reward_price|channel_reward_price"=>array("neq", 0),
            );
            $this->proudct_reward_list = D("MarketProductRewardView")->where($rewardhere)->order("create_time desc")->select();

            $accwhere = array(
                "related"=>"market",
                "related_id"=>$market['market_id'],
                "clause_type_id"=>array("in", array(299, 298,308, 309,312, 313)),
            );
            $this->reward_account_list = M("account")->where($accwhere)->order("create_time desc")->select();
        }

        if ($assort == "basic") {
            $curtime = time();
            $where = array(
                "serve.serve_id"=>138,
                "customer.customer_id"=>$market['customer_id'],
                "trade.begin_date"=>array("elt", $curtime),
                "trade.end_date"=>array("egt", $curtime),
            );
            $this->insurance = D("CustomerTradeView")->where($where)->find();
            if ($this->insurance) {
                $this->new_start_date = strtotime("+1 day", $this->insurance['end_date']);
            }

            if (!$market['is_owner']) {
                if ($this->is_fix_branch_field($market)) {
                    $market = self::fix_branch_fields(getBranchFields("market"), $market, in_array($market['owner_role_id'], $branch_role));
                }
            }
            $this->fields_group = product_field_list_show('market', $market, "basic");;
        }
        $this->market_over = $this->market_over_status($market);;
        $this->market_settle = $this->market_settle_status($market);

        $this->promtp_count = self::prompt_count("market", $market['market_id'], time());
        $this->market = $market;
        $this->refer_url= session("index_refer_url");
        $this->alert =  parseAlert();
        $this->assort =  $assort;
        $this->display($assort."_view");
	}

    private function market_over_status($market) {
        return ($market['status_id'] == 3 &&
            $market['deficit_price'] >= $market['sum_settle_price'] &&
            $market['wait_confirm_price']==0);
    }

    private function market_settle_status($market) {
        $market_over = $this->market_over_status($market);
        return $market_over && $market['settle_state'] < 918 && ($market['settle_state'] == 917 || is_nosettle_cate($market['category_id']));
    }

    public function related_delete($model_ids, $relateds) {
        $where = array(
            'market_id'=>array("in", $model_ids)
        );
        $related_ids = M($relateds)->where($where)->getField($relateds."_id");
        $related_delete_where = array($relateds."_id"=>array("in", $related_ids));
        $tables = array(
            $relateds,
            $relateds."_data",
            $relateds."_images",
        );
        foreach($tables as $t) {
            M($t)->where($related_delete_where)->delete();
        }
    }

    public function delete(){
        $pre_market_ids = $this->isPost() ? $_POST['market_id'] : $_GET['id'];
        if (!is_array($pre_market_ids)) {
            $pre_market_ids = array($pre_market_ids);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $market_ids = array();
        $customer_ids = array();
        $where = array(
            "market.market_id"=>array("in", $pre_market_ids)
        );
        $markets = D('MarketView')->order('market.create_time desc')->where($where)->limit(10)->select();
        foreach($markets as $k=>$market) {
            if (!session('?admin') && (is_market_settle($market) || $market['confirm_price'] > 0)) {
                continue;
            }
            $market_ids[] = $market['market_id'];
            $customer_ids[] = $market['customer_id'];
        }
        if (count($market_ids) == 0) {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
        $this->log($market_ids, "删除订单", "删除订单", 2);
        $flow_module = array(
            'Event'=>'RMarketEvent',
            'market_subgroup'=>'market_subgroup'
        );
        if (!$this->submit_delete($market_ids, $flow_module)) {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
        $related_module = array("market_product", "market_channel");
        foreach($related_module as $r) {
            $this->related_delete($market_ids, $r);
        }
        $this->delete_related_account("market", $market_ids);
        self::update_model_surplus_price(array("customer"=>$customer_ids));

        foreach($market_ids as $market_id) {
            self::delete_prompt("market", $market_id);
        }
        alert('success', "删除成功" ,U('market/index'));
    }

    public function is_fix_branch_field($value, $branchlock = false) {
        return $value['market.astrict'] == "限制" && !in_array($value['market_id'], self::get_astrict_list());
    }

    public function is_interest($mode, $branch=null, $astrict = true) {
        if (self::is_owner($mode, $branch)) {
            return true;
        }
        return session('authority') == "受限" ? in_array($mode['astrict'], array('限制','公开')) : $astrict;
    }

    public function make_astrict_where($brat = true, $branch_role=null) {
        $this->module_name = strtolower(MODULE_NAME);
        if (!$branch_role) {
            if (session("shopkeeper")) {
                $branch_role = get_branch_all_role(session("branch_id"));
            } else {
                $branch_role = get_branch(session("role_id"));
            }
        }
        $map[$this->module_name.'.owner_role_id'] = array("in", $branch_role);           //我和我下属

        if ($brat) {
            $astrict_list = self::get_astrict_list();                               //特别授权
            if ($astrict_list) {
                $map[$this->module_name .'.'.$this->module_name.'_id'] = array("in", $astrict_list);
            }

            $map['_logic'] = 'or';

            if (session("authority") == "受限") {
                $map[$this->module_name.'.astrict'] = array('in',array('限制','公开'));
            }
        }
        return $map;
    }

    public function show_list($where = array(), $params = array()) {
        $this->assign_module_list($where, $params, "客户服务表");
        $list = $this->list;
        $this->return_data($list, $this->page->totalRows);

    }

    function market_product_agency($market_id) {
        $where = array(
            "market_id"=>$market_id,
        );
        $agency_scale = M("market_product")->where($where)->getField("agency_scale", true);
        $agency_scale = array_unique($agency_scale);
        return count($agency_scale) != 1 ? "":$agency_scale[0];
    }

    function perfect_list_item($value, $export = false, $branchlock = false) {
        $value['promtp_count'] = self::prompt_count("market", $value['market_id'], time());
        $value['market_settle'] = $this->market_settle_status($value);
        $value['market_over'] = $this->market_over_status($value);;
        $value['agency_scale'] = $value['product_agency_scale'];
        return parent::perfect_list_item($value, $export, $branchlock);
    }

    public function replenish_list($list, $export) {
        $list = parent::replenish_list($list, $export);

        $branch_role = get_branch(session("role_id"));
        $per_edit = vali_permission("market", "edit");
        $restriction = !session('?admin') && vali_permission("branchlock", "market")  && session('restriction') === true;
        foreach($list as $key => $value){
            $list[$key]['is_owner'] = session('?admin');
            if (!$list[$key]['is_owner']) {
                $list[$key]['is_owner'] = ($list[$key]['owner_role_id'] && $branch_role ? self::is_owner($list[$key], $branch_role) : true);
            }
            $list[$key]['per_edit'] = $restriction ? ($list[$key]['is_owner'] || $per_edit) : true;
            if (session('?admin')) {
                $list[$key]['optstate'] = false;
            } else {
                $list[$key]['optstate'] = is_market_settle($value) || $value['confirm_price'] > 0;
            }

            $where = array(
                "market_id"=>$value['market_id'], "isdefault"=>1
            );
            $list[$key]['def_market_channel'] = M("market_channel")->where($where)->find();
            $list[$key]['service_status_id'] = convert_market_status($list[$key]['service_status_id'], $list[$key]['is_cancel_submit']);
        }
        return $list;
    }

    public function show_group() {
        $this->categoryList = M('product_category')->where(array("enable="=>1, "league_id"=>session('league_id')))->order("order_id asc")->select();
        parent::show_group();
    }

    public function changeContent(){
        $where = array();
        if ($_REQUEST["field"] && $_REQUEST['search']) {
            if (trim($_REQUEST['field']) == "all") {
                $field = implode("|", all_filter_field(array('business','customer')));
            } else {
                $field = trim($_REQUEST['field']);
            }
            $search = empty($_REQUEST['search']) ? '' : trim($_REQUEST['search']);
            $condition = empty($_REQUEST['condition']) ? 'is' : trim($_REQUEST['condition']);
            $where = field_where($field, $search, $condition);
        }
        if ($_REQUEST['query']) {
            $where["_string"] = $_REQUEST['query'];
        }
        $p = !$_REQUEST['p'] || $_REQUEST['p']<=0 ? 1 : intval($_REQUEST['p']);
        $where['league_id'] = session('league_id');

        $m_market = D('MarketView');
        $list = $m_market->group('market_id')->where($where)->Page($p.',10')->select();
        foreach($list as $key => $value){
            $list[$key]['status_name'] = M('BusinessStatus')->where('status_id = %d', $value['status_id'])->getField('name');
        }
        $count = count($m_market->group('market_id')->where($where)->select());

        $data['list'] = $list;
        $data['p'] = $p;
        $data['count'] = $count;
        $data['total'] = $count%10 > 0 ? ceil($count/10) : $count/10;
        $this->ajaxReturn($data,"",1);
    }

    public function update_keyword($market_ids) {
        if (!is_array($market_ids)) {
            $market_ids = array($market_ids);
        }
        foreach($market_ids as $market_id) {
            $market = D("MarketView")->where(array("market_id"=>$market_id))->find();
            $keyword = array();

            $keyword[] = $market['category_name'];
            $keyword[] = $market['market_idcode'];

            $m_user_role = D("UserView")->where(array("role.role_id"=>$market['owner_role_id']))->find();
            if ($m_user_role) {
                $keyword[] = $m_user_role['idcode'];
                $keyword[] = $m_user_role['name'];
                $keyword[] = $m_user_role['telephone'];
            }

            $customer = M("customer")->where(array("customer_id"=>$market['customer_id']))->find();
            if ($customer) {
                $keyword[] = $customer['idcode'];
                $keyword[] = $customer['name'];
                $keyword[] = $customer['telephone'];
            }

            $m_market_product = D("MarketProductView")->where(array("market_id"=>$market['market_id']))->select();
            foreach($m_market_product as $product) {
                $keyword[] = $product['idcode'];
                $keyword[] = $product['name'];
                $keyword[] = $product['telephone'];
            }

            $m_market_account = D("MarketAccountView")->where(array("market_id"=>$market['market_id']))->select();
            foreach($m_market_account as $account) {
                $keyword[] = $account['receipt_number'];
            }
            M("market")->where(array("market_id"=>$market['market_id']))->setField("keyword", implode(chr(10), array_unique($keyword)));
        }
    }

    public function reset_keyword() {
        foreach(M("market")->select() as $v) {
            self::update_keyword($v);
        }
    }

    public function log($market_ids, $subject, $content, $t) {
        $markets = M("market")->where( array('market_id'=>array("in", $market_ids)))->select();
        foreach($markets as $v) {
            if ($log_id = $this->addlog($subject, $content, 6)) {
                $data['market_id'] = $v['market_id'];
                $data['market_idcode'] = $v['idcode'];
                $data['log_id'] = $log_id;
                $data['type'] = $t;
                $data['league_id'] = session('league_id');
                M('r_market_log')->add($data);
            }
        }
        return $log_id;
    }

    public function update_default_urge_price($market_ids) {
        if (!is_array($market_ids)) {
            $market_ids = array($market_ids);
        }
        $markets = D('MarketView')->where(array('market.market_id'=>array("in", $market_ids)))->select();
        foreach($markets as $market) {
            $urge_price = $market['gain'];
            $branch_id = $market["branch_id"] != 0 ? $market["branch_id"] : 3;      //总店使用大望路系数
            $branch = M("branch")->where("branch_id=".$branch_id)->find();
            $urge_branch_ratio = 0;
            if (in_array($market['category_id'], array(16))) {
                $pc = M('product_category')->cache(true)->where("category_id=".$market['category_id'])->find();
                if ($pc) {
                    $urge_branch_ratio = $pc['urge_category_ratio'];
                }
            } else{
                if ($branch) {
                    if ($branch['category_config']) {
                        $category_config = unserialize($branch['category_config']);
                        $urge_agency_scale = $category_config[$market['category_id']]['urge_agency_scale'];
                        if ($urge_agency_scale) {
                            $urge_branch_ratio = $urge_agency_scale;                        }
                    }
                    if (!$urge_branch_ratio)
                    {
                        $urge_branch_ratio = $branch['urge_branch_ratio'];
                    }
                }
            }

            $urge_price *= abs(doubleval($urge_branch_ratio)) / 100;
            $data = array(
                "urge_branch_ratio"=>$urge_branch_ratio,
                "urge_price"=>$urge_price,
            );
            M("market_urge")->where(array("isdefault"=>1, "market_id"=>$market['market_id']))->setField($data);
        }
    }


    public function update_urge_price_by_branch($branch_id) {
        $where = array(
            'branch_id'=>array("in", $branch_id),
            "settle_state"=>array("not in", array("918"))
        );
        $market_ids = M('market')->where($where)->getField("market_id");
        if ($market_ids) {
            $this->update_market($market_ids, true);
        }
    }

    public function update_urge_price_by_category($category_id) {
        $where = array(
            'category_id'=>array("in", $category_id),
            "settle_state"=>array("not in", array("918"))
        );
        $where["league_id"] = session("league_id");

        $market_ids = M('market')->where($where)->getField("market_id");
        if ($market_ids) {
            $this->update_market($market_ids, true);
        }
    }

    public function update_market_state_info($market_ids) {
        if (!is_array($market_ids)) {
            $market_ids = array($market_ids);
        }
        foreach($market_ids as $market_id) {
            $data = array();
            $data['product_count'] = M("market_product")->where("market_id=".$market_id)->count();
            $data['start_time'] = M("market_product")->where(array("market_id"=>$market_id, "real_start_time"=>array("neq", 0)))->min('real_start_time');
            $empty_end_time = M("market_product")->where(array("market_id"=>$market_id, "real_end_time"=>array("eq", 0)))->find();
            if ($empty_end_time) {
                $data['end_time'] = 0;
            } else {
                $data['end_time'] = M("market_product")->where(array("market_id"=>$market_id))->max('real_end_time');
            }
            M("market")->where(array("market_id"=>$market_id))->setField($data);
            self::update_market_service_status($market_id, time());
        }
    }

    public function update_market_gain_info($market_ids) {
        if (!is_array($market_ids)) {
            $market_ids = array($market_ids);
        }

        foreach($market_ids as $market_id) {
            $data = array();

            $data['sum_price'] = M("market_product")->where("market_id=".$market_id)->sum('price');
            $data['sum_salary'] = M("market_product")->where("market_id=".$market_id)->sum('salary');
            $data['sum_agency'] = M("market_product")->where("market_id=".$market_id)->sum('agency');
            $data['sum_channel_price'] = M("market_channel")->where("market_id=".$market_id)->sum('channel_price');
            $data['sum_channel_reward_price'] = M("market_product")->where("market_id=".$market_id)->sum('channel_reward_price');
            $data['sum_urge_reward_price'] = M("market_product")->where("market_id=".$market_id)->sum('urge_reward_price');
            $data['sum_reward_price'] = $data['sum_channel_reward_price'] + $data['sum_urge_reward_price'];
            $data['gain'] = $data['sum_agency'] - $data['sum_channel_price'];

            M("market")->where(array("market_id"=>$market_id))->setField($data);
        }

    }

    public function update_market_profit_info($market_ids) {
        if (!is_array($market_ids)) {
            $market_ids = array($market_ids);
        }
        $markets = M("market")->where(array("market_id"=>array("in", $market_ids)))->select();
        foreach($markets as $market) {
            $data = array();
            $data['sum_urge_price'] = M("market_urge")->where("market_id=".$market['market_id'])->sum('urge_price');
            $data['profit'] = $market['gain'] - $data['sum_urge_price'] - $market['sum_reward_price'];
            M("market")->where(array("market_id"=>$market['market_id']))->setField($data);
        }
    }

    public function update_market_settle_info($market_ids) {
        if (!is_array($market_ids)) {
            $market_ids = array($market_ids);
        }
        foreach($market_ids as $market_id) {
            $data = array();
            $data['sum_settle_price'] = M("market_product")->where("market_id=".$market_id)->sum('settle_price');
            $data['sum_recess_day'] = M("market_product")->where("market_id=".$market_id)->sum('recess_day');
            $data['sum_service_duration'] = M("market_product")->where("market_id=".$market_id)->sum('service_duration');
            $data['sum_settle_duration'] = M("market_product")->where("market_id=".$market_id)->sum('settle_duration');
            M("market")->where(array("market_id"=>$market_id))->setField($data);
        }
    }

    public function advance_status_ergodic($curtime) {
        $where = array(
            "status_id"=>array("neq", 0),
            "settle_state"=>array("not in", array("918"))
        );

        $m_markets = M('market')->field("market_id,status_id, end_time, start_time")->where($where)->select();
        foreach($m_markets as $market) {
            self::update_market_service_status($market, $curtime);
        }
    }

    public function update_market_service_status($market, $curtime) {
        if (!is_array($market)) {
            $market = M('market')->where(array("market_id"=>$market))->find();
        }
        if ($market['status_id'] != 0) {
            $data = array();
            if (($market['end_time'] == 0 && $market['start_time'] == 0) || $curtime < $market['start_time']) {
                $data['status_id'] = 1;
            } else if($curtime >=$market['start_time'] && ($curtime <= $market['end_time'] || $market['end_time'] == 0)) {
                $data['status_id'] = 2;
            } else {
                $data['status_id'] = 3;
            }
            if ($data['status_id'] != $market['status_id']) {
                M("market")->where(array("market_id"=>$market['market_id']))->setField($data);
            }
        }
        self::update_market_product_state_info($market,  $curtime);
    }

    public function update_market_product_workstate_id($market_product, $newstate) {
        //if ($market_product['queue_workstate'] == 1)
        {
            $m_data['workstate_id'] = product_makret_workstate($market_product);
            M("product")->where("product_id=".$market_product['product_id'])->setField($m_data);
        }
    }

    public function update_market_product_state_info($market, $curtime) {
        $market_products = D("MarketProductView")->where(array("market_id"=>$market['market_id']))->select();
        foreach($market_products as $market_product) {
            $data = array();
            if ($market_product['service_status_id'] != 0 )
            {
                if (($market_product['real_start_time'] == "0" && $market_product['real_end_time'] == "0") || $curtime < $market_product['real_start_time'])
                {
                    $data['service_status_id'] = 1;
                }
                else if(($curtime >=$market_product['real_start_time'] && $curtime <= $market_product['real_end_time']) || ($curtime >= $market_product['real_start_time'] && $market_product['real_end_time'] == "0"))
                {
                    $data['service_status_id'] = 2;
                }
                else
                {
                    $data['service_status_id'] = 3;
                }
            }
            if ($market_product['service_status_id'] != $data['service_status_id']) {
                M("market_product")->where(array("market_product_id"=>$market_product['market_product_id']))->setField($data);
            }
            self::update_market_product_workstate_id($market_product, $market_product['service_status_id']!= 0 ? $data['service_status_id'] : 0);
        }
    }

    private function update_market_service_product($market_ids) {
        if (!is_array($market_ids)) {
            $market_ids = array($market_ids);
        }
        foreach($market_ids as $market_id) {
            $max_real_start_time = M("market_product")->where("market_id=".$market_id)->max("real_start_time");
            $where = array(
                "market_id"=>$market_id,
                "real_start_time"=>$max_real_start_time
            );
            $market_product = D("MarketProductView")->where($where)->find();
            if ($market_product) {
                $data = array(
                    "service_product"=>$market_product['product_id'],
                    'product_agency_scale'=>$this->market_product_agency($market_id),
                );
            } else {
                $data = array(
                    "service_product"=>""
                );
            }
            M("market")->where("market_id=".$market_id)->setField($data);
        }
    }

    public function product_update_first_market_reward($first_market, $product_id, $market_product_id) {
        $data = array(
            "urge_reward_price" => 0,
            "channel_reward_price" => 0,
        );
        if ($first_market){
            $channel_role = M("product")->field("channel_role_model,channel_role_id")->where(array("product_id"=>$product_id))->find();
            if (in_array($channel_role['channel_role_model'], array("2","3", "4"))) {
                $m_channel = M("channel")->cache(true)->where(array("channel_id" =>$channel_role['channel_role_model']))->find();
                if ($m_channel) {
                    $model = channel_model_map($m_channel);
                    if (M($model)->where(array($model."_id"=>$channel_role['channel_role_id']))->find()) {
                        $data = array(
                            "urge_reward_price" => $m_channel['urge_model_owner_reward'],
                            "channel_reward_price" => $m_channel['channel_role_reward'],
                        );
                    }
                }
            }
        }
        M("market_product")->where(array("market_product_id" => $market_product_id))->setField($data);
    }

    public function product_update_first_market($market_product_id, $market) {
        $product_id = M("market_product")->where(array("market_product_id"=>$market_product_id))->getField("product_id");
        $cc = M("market_product")->where(array("product_id"=>$product_id))->min("real_start_time");
        $market_product = M("market_product")->where("real_start_time = ".$cc)->find();
        if ($market_product) {
            M("product")->where(array("product_id"=>$market_product['product_id']))->setField("first_market_id", $market_product['market_id']);

            $first_market = $market_product['market_id'] == $market['market_id'];
            $this->product_update_first_market_reward($first_market,$market_product['product_id'], $market_product_id);
        }
    }

    public function product_add() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()) {
            if (!$_POST['market_id']) {
                alert_back('error', "参数错误");
            }
            if (!$_POST['product_id']) {
                alert_back('error', "必须选择雇员");
            }
            $mproduct = M("product")->where(array("product_id"=>$_POST['product_id']))->find();
            if (!$mproduct) {
                alert_back('error', "雇员不存在了");
            }
            if (in_array($mproduct['station_state'],array('自愿离职','开除','其他未录用'))) {
                alert_back('error', "雇员不在编, 不能续约订单");
            }
            if (!isset($_POST['real_end_time'])) {
                $_POST['real_end_time'] = 0;
            }

            if (!isset($_POST['real_start_time'])) {
                $_POST['real_start_time'] = 0;
            }
            $market = D('MarketView')->where(array("market.market_id"=>$_POST['market_id']))->find();
            if (!$market) {
                alert('error', L('PARAMETER_ERROR'),U("market/index"));
            }

            $_POST['update_role_id'] = session('role_id');
            if (!($market_product_id = $this->submit_add("market_product"))) {
                alert_back('error', "添加雇员失败");
            }
            $_POST['real_start_time'] = strtotime($_POST['real_start_time']);
            $_POST['real_end_time'] = strtotime($_POST['real_end_time']);

            $this->update_market($_POST['market_id'], true);

            $this->change_market_product_event($market_product_id, "上岗", $_POST['real_start_time'], $_POST['real_end_time']);
            if ($_POST['real_start_time'] > strtotime(date('2017-03-01'))) {
                $this->product_update_first_market($market_product_id, $market);
            }
            $this->add_edit_log($_POST['market_id'], "添加雇员成功: ", D('MarketProductView')->verity_check($market_product_id));
            alert('success', "添加雇员成功", U("Market/view", "assort=product&id=".$_POST['market_id']));
        } else {
            if (intval($this->_request('id')) <= 0) {
                alert('error', L('PARAMETER_ERROR'), U("market/index"));
            }
            $market_id = $this->_request('id');
            $market = D('MarketView')->where(array("market.market_id"=>$market_id))->find();
            if (!$market) {
                alert('error', L('PARAMETER_ERROR'),U("market/index"));
            }
            if ($market['status_id'] == '0') {
                alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
            }

            if (is_market_settle($market)) {
                alert('error', "订单已经提交结算， 不可以修改",$_SERVER['HTTP_REFERER']);
            }

            $branch = D('BranchView')->where("branch.branch_id=".($market["branch_id"] != 0 ? $market["branch_id"] : 3))->find();
            if ($branch && $branch['category_config']) {
                $category_config = unserialize($branch['category_config']);
                $market['agency_scale'] = $category_config[$market['category_id']]['agency_scale'];
                $market['sign_agency_scale'] = $category_config[$market['category_id']]['sign_agency_scale'];
            }
            $this->market = $market;
            $this->lock_agency_scale = $this->is_lock_agency_scale($market);;
            $this->show_product_add(null);
        }
    }

    public function show_product_add($market_product) {
        $fields_group = product_field_list_html($market_product ? "edit" : 'add','market_product', $market_product ? $market_product : array(), "basic");

        $this->fields_group = $fields_group;
        $this->refer_url = refer_url('refer_add_url');
        $this->alert = parseAlert();
        $this->display("Market:product_add");
    }

    public function product_edit() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_REQUEST['id']) {
            alert('error', L('PARAMETER_ERROR'), U('market/index'));
        }
        $market_product_id = $this->_request('id');
        $where = array(
            "market_product_id"=>$market_product_id
        );
        $market_product = D("MarketProductView")->where($where)->find();
        if (is_market_settle($market_product)) {
            alert('error', "订单已经提交结算， 不可以修改",$_SERVER['HTTP_REFERER']);
        }
        if ($market_product['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()){
            if (!isset($_POST['real_end_time'])) {
                $_POST['real_end_time'] = 0;
            }
            if (!isset($_POST['real_start_time'])) {
                $_POST['real_start_time'] = 0;
            }
            $mproduct = M("product")->where(array("product_id"=>$_POST['product_id']))->find();
            if (!$mproduct) {
                alert('error', "雇员不存在了", $_SERVER['HTTP_REFERER']);
            }
            if ($mproduct['product_id'] != $market_product['product_id'] && in_array($mproduct['station_state'],array('自愿离职','开除','其他未录用'))) {
                alert('error', "雇员不在编, 不能续约订单", $_SERVER['HTTP_REFERER']);
            }
            $market_id = $market_product['market_id'];
            if($this->submit_edit($market_product_id, "market_product")) {
                $this->update_market($market_id, true);
                if ($market_product['product_id'] != $mproduct['product_id']) {
                    D("Manage/MarketProductEventView")->delete_product_event($market_product_id);
                }
                $this->change_market_product_event($market_product_id,"上岗" , $_POST['real_start_time'], $_POST['real_end_time']);
                if (strtotime($_POST['real_start_time']) > strtotime(date('2017-03-01'))) {
                    $this->product_update_first_market($market_product_id, $market_product);
                }
                $this->add_edit_log($market_id, "编辑雇员成功: ", D('MarketProductView')->verity_check($market_product));
                alert('success', "编辑订单成功", U('market/view', 'assort=product&id='.$market_id));
            } else {
                alert('error', "编辑订单失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            $this->lock_agency_scale = $this->is_lock_agency_scale($market_product);;
            $this->market_product_id = $market_product_id;
            $this->market_product = $market_product;
            $this->market = $market_product;
            $this->fields_group = field_list_html_edit('market_product', $market_product);
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();;
            $this->display("Market:product_edit");
        }
    }


    public function product_delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $market = D('MarketView')->where(array("market.market_id"=>$this->_request('market_id')))->find();
        if (!$market) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if (is_market_settle($market)) {
            alert('error', "订单已经提交结算",$_SERVER['HTTP_REFERER']);
        }
        $market_product_id = $this->isPost() ? $_POST['id'] : $_GET['id'];
        $where = array(
            "market_product_id"=>$market_product_id
        );
        $market_product = D("MarketProductView")->where($where)->find();

        if ($this->submit_delete($market_product_id, array(), "market_product")) {
            self::update_market_product_workstate_id($market_product, 0);
            $this->update_market($market['market_id'], true);
            $this->delete_market_product_event($market_product_id);
            $this->product_update_first_market($market_product_id, $market);
            $this->log($market['market_id'], "删除雇员", "删除雇员成功".product_show_html($market_product['product_id'], false), 2);
            alert('success', "删除成功" ,$_SERVER['HTTP_REFERER']);
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }

    private function update_customer_commiss_info($market) {
        if (!is_array($market)) {
            $market = M('market')->where(array("market_id"=>$market))->find();
        }
        if ($market) {
            $where = array("customer_id"=>$market['customer_id']);
            $data = array(
                "market_amount"=>M("market")->where($where)->count(),
                "market_price"=> M("market")->where($where)->sum("sum_settle_price"),
            );
            M("commiss")->where(array("related_model_name"=>"customer", "related_model_id"=>$market['customer_id']))->setField($data);
            $this->update_market_statistics($market['customer_id']);
        }
    }

    public function update_market($market_id, $urge = true) {
        $this->update_market_service_product($market_id);
        $this->update_market_settle_info($market_id);
        $this->calculate_market_account($market_id);

        $this->update_surplus_price("market", $market_id);
        $this->update_market_gain_info($market_id);
        if ($urge) {
            $this->update_default_urge_price($market_id);
        }
        $this->update_market_profit_info($market_id);
        $this->update_market_state_info($market_id);
        $this->update_customer_commiss_info($market_id);
        $this->update_product_first_mark($market_id);
        $this->update_keyword($market_id);
    }

    public function update_product_first_mark($market_id) {
        $product_ids = M("market_product")->where(array("market_id"=>$market_id))->getField("product_id", true);
        $product_cnt = M("product")->where(array("product_id"=>array("in", $product_ids), "first_market_id"=>$market_id))->count();
        M("market")->where(array("market_id"=>$market_id))->setField("product_initial", $product_cnt>0?"是":"否");
    }

    public function delete_market_product_event($market_product_ids) {
        $where = array(
            "market_product_id"=>array("in", $market_product_ids)
        );
        $event_ids = M("event")->where($where)->getField("event_id");
        if ($event_ids) {
            D("Manage/MarketProductEventView")->delete_event($event_ids);
        }
    }


    public function product_list() {
        $where = array(
            "market_id"=>$_GET['id']
        );
        $this->ajaxReturn(make_datatable_list("MarketProductView", $where, array($this, "format_product_info")),'JSON');
    }

    public function format_product_info($value) {
        $value['product_level_show'] = level_show_html($value['product_id'], $value['category_id']);
        $value['product_channel'] = channel_show_html($value['channel_role_model']);
        $value['product_channel_role_id'] = channel_model_role_show_html($value['channel_role_model'], $value['channel_role_id'], false);
        $value['product_owner_role_id'] = role_show($value['owner_role_id']);
        $value['real_start_time_show'] = pregtime($value['real_start_time'], true);
        $value['real_end_time_show'] = pregtime($value['real_end_time'], true);
        $value['creator'] = D('RoleView')->where('role.role_id = %d', $value['creator_role_id'])->find();
        $value['insurance_show'] = (product_insurance_show($value['product_id']) == "是"?"已上":"未上");
        $value['service_status_id_show'] = format_market_status($value['service_status_id'], $value['is_cancel_submit']);
        $value['acc_salary'] = self::sum_product_salary($value['market_id'], $value['product_id'], $value['market_product_id']);
        $value['product_name_show'] = product_show_html($value);
        if ($value['first_market_id'] == $value['market_id']) {
            $value['product_name_show'] ='<a href="javascript:void(0);" title="首单"><i class="icon-heart"></i></a>&nbsp'.$value['product_name_show'];
        }
        $value['product_evaluate_state'] = $value['product_evaluate_state']?$value['product_evaluate_state']:"未评";

        $m_market_product_evaluate = M("market_product_evaluate")->where(array("market_product_id"=>$value['market_product_id']))->find();
        if ($m_market_product_evaluate) {
            $m_market_product_evaluate['picture'] = field_show_html("market_product_evaluate", 'picture', $m_market_product_evaluate);
            $value['evaluate_info'] = $m_market_product_evaluate;
        }
        return $value;
    }

    public function channel_edit() {
        if (!$_REQUEST['id']) {
            alert('error', L('PARAMETER_ERROR'), U('market/index'));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $market_channel_id = $this->_request('id');
        $where = array(
            "market_channel_id"=>$market_channel_id
        );
        $market_channel = D("MarketChannelView")->where($where)->find();
        if ($market_channel['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        $market = D('MarketView')->where(array("market.market_id"=>$market_channel['market_id']))->find();
        if($this->isPost()){
            $market_id = $market_channel['market_id'];
            if($this->submit_edit($market_channel_id, "market_channel")) {
                if ($market_channel['market_channel_id'] == $market['def_channel_id']) {
                    $this->update_default_channel($market_id, M("market_channel")->where($where)->find());
                }
                $this->update_market($market_id, false);
                $this->add_edit_log($market_id, "编辑渠道成功: ", $market_channel_id.": ".D('MarketChannelView')->verity_check($market_channel));
                alert('success','编辑渠道成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('market/view', 'assort=channel&id='.$market_id));
            } else {
                alert('error', "编辑渠道失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            if ($market_channel['settle_state'] == "918") {
                alert('error', "订单已经结算",$_SERVER['HTTP_REFERER']);
            }
            $this->market = $market;
            $this->market_channel_id = $market_channel_id;
            $this->market_channel = $market_channel;
            $this->fields_group = field_list_html_edit('market_channel', $market_channel);
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();;
            $this->display();
        }
    }


    public function channel_delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $market = D('MarketView')->where(array("market.market_id"=>$this->_request('market_id')))->find();
        if (!$market) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($market['settle_state'] == "918") {
            alert('error', "订单已经结算",$_SERVER['HTTP_REFERER']);
        }
        if ($market['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        $market_channel_id = $this->isPost() ? $_POST['id'] : $_GET['id'];
        $where = array(
            "market_channel_id"=>$market_channel_id
        );
        $market_ids = M("MarketChannel")->where($where)->getField("market_id");

        if ($this->submit_delete($market_channel_id, array(), "market_channel")) {
            $this->update_market($market_ids, false);
            $this->log($market_ids, "删除渠道", "删除渠道成功 => ".$market_channel_id, 2);
            alert('success', "删除成功" ,$_SERVER['HTTP_REFERER']);
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }


    public function channel_add() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if($this->isPost()) {
            $market_id = $this->_request('market_id');
            if (!$market_id) {
                alert_back('error', "参数错误");
            }
            $where = array("market.market_id"=>$market_id);
            $market = D('MarketView')->where($where)->find();
            if (!$market) {
                alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
            if ($market['settle_state'] == "918") {
                alert('error', "订单已经结算， 不可修改",$_SERVER['HTTP_REFERER']);
            }
            if (in_array($_POST['origin'], array("阿姨介绍", "客户介绍", "员工介绍")) && $_POST['introducer'] == "") {
                alert('error', "关联渠道关联人不可以为空", $_SERVER['HTTP_REFERER']);
            }
            $_POST['update_role_id'] = session('role_id');
            if (!($market_channel_id = $this->submit_add("market_channel"))) {
                alert_back('error', "新建渠道失败");
            }
            $this->update_market($market_id, false);
            $this->add_edit_log($market_id, "添加渠道成功: ", $market_channel_id.": ".D('MarketChannelView')->verity_check($market_channel_id));
            alert('success','添加渠道成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('market/view', 'assort=channel&id='.$market_id));
        } else {
            $fields_group = product_field_list_html('add','market_channel');
            $this->fields_group = $fields_group;
            $this->refer_url = refer_url('refer_add_url');
            $this->alert = parseAlert();
            $this->display("Market:channel_add");

        }
    }


    public function format_channel_info($value) {
        if ($value['channel_role_model']) {
            $channel = M("channel")->where(array("channel_id"=>$value['channel_role_model']))->find();
            $value['channel_name'] = $channel['name'];
            if ($value['channel_role_id'])
            {
                $value['channel_role_name'] = channel_model_role_show_html($channel, $value['channel_role_id'], true);
            }
        }
        $value['channel_name'] = $value['channel_name']?$value['channel_name']:"";
        $value['channel_price'] = $value['channel_price'] == 0?"":$value['channel_price'];
        $value['channel_role_name'] = $value['channel_role_name']?$value['channel_role_name']:"";
        return $value;
    }

    public function channel_list() {
        $where = array(
            "market_id"=>$_GET['id']
        );
        $this->ajaxReturn(make_datatable_list("MarketChannelView", $where, array($this, "format_channel_info")),'JSON');
    }


    public function urge_edit() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_REQUEST['id']) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $market_urge_id = $this->_request('id');
        $where = array(
            "market_urge_id"=>$market_urge_id
        );
        $market_urge = D("MarketUrgeView")->where($where)->find();
        if (!$market_urge) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        if ($market_urge['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        if($this->isPost()){
            $market_id = $market_urge['market_id'];
            if($this->submit_edit($market_urge_id, "market_urge")) {
                $this->update_market($market_id, false);
                $this->add_edit_log($market_id, "编辑促单成功: ", D('MarketUrgeView')->verity_check($market_urge));
                alert('success','编辑促单成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('market/view', 'assort=cost&id='.$market_id));
            } else {
                alert('error', "编辑促单失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            if ($market_urge['settle_state'] == "918") {
                alert('error', "订单已经结算， 不可以修改",$_SERVER['HTTP_REFERER']);
            }
            $this->market_urge_id = $market_urge_id;
            $this->market_urge = $market_urge;
            $this->fields_group = field_list_html_edit('market_urge', $market_urge);
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();;
            $this->display();
        }
    }


    public function urge_delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $market = D('MarketView')->where(array("market.market_id"=>$this->_request('market_id')))->find();
        if (!$market) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($market['settle_state'] == "918") {
            alert('error', "订单已经结算",$_SERVER['HTTP_REFERER']);
        }

        $market_urge_ids = $this->isPost() ? $_POST['id'] : $_GET['id'];
        if (!is_array($market_urge_ids)) {
            $market_urge_ids = array($market_urge_ids);
        }
        $where = array(
            "market_urge_id"=>array("in", $market_urge_ids)
        );
        $market_ids = M("MarketUrge")->where($where)->getField("market_id");

        if ($this->submit_delete($market_urge_ids, array(), "market_urge")) {
            $this->log($market['market_id'], "删除促单费", "删除促单费", 2);
            $this->update_market($market_ids, false);
            alert('success', "删除成功" ,$_SERVER['HTTP_REFERER']);
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }


    public function urge_add() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()) {
            $market_id = $this->_request('market_id');
            if (!$market_id) {
                alert_back('error', "参数错误");
            }
            $where = array("market.market_id"=>$market_id);
            $market = D('MarketView')->where($where)->find();
            if (!$market) {
                alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
            if ($market['settle_state'] == "918") {
                alert('error', "订单已经结算， 不可修改",$_SERVER['HTTP_REFERER']);
            }

            $_POST['update_role_id'] = session('role_id');
            $_POST['urge_price_settle_time'] = time();

            if (!($market_urge_id = $this->submit_add("market_urge"))) {
                alert_back('error', "新建促单失败");
            }
            $this->update_market($market_id, false);
            $this->add_edit_log($market_id, "添加促单费: ", D('MarketUrgeView')->verity_check($market_urge_id));
            alert('success','添加促单成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('market/view', 'assort=cost&id='.$market_id));
        } else {
            $this->fields_group = product_field_list_html('add','market_urge');
            $this->refer_url = refer_url('refer_add_url');
            $this->alert = parseAlert();
            $this->display("Market:urge_add");
        }
    }


    public function format_account_info($value) {
        $value['create_time_show'] =toDate($value['create_time']);
        $value['payment_time_show'] =toDate($value['payment_time']);
        $value['payment_verify_show'] =payment_verify_map($value['payment_verify']);
        $value['per_delete'] = session('?admin') || (!is_market_settle($value) && $value['status_id'] != 0 && $value['verify_time'] == 0);

        if ($value['verify_role_id'] != 0) {
            $value['verify_role_show'] =role_html($value['verify_role_id']);
            $value['verify_time_show'] =toDate($value['verify_time']);
        } else {
            $value['verify_role_show'] ="";
            $value['verify_time_show'] ="";
        }

        if ($value['clause_type_id'] == 230 || $value['clause_type_id'] == 235) {
            $value['payway'] = $value['clause_type_id'] == 230 ? "账目解冻":"账目退款";
            $value['payment_verify'] = "1";
            $value['per_delete'] = false;
            $value['flow_account_idcode'] = "";
            if ($value['clause_type_id'] == 230) {
                $value['description'] = "客户服务定金解冻,".$value['description'];
                if ($value['quarry'] == 1) {

                } else {
                    $value['money'] = $value['money'] - $value['sum_settle_price'];
                }
            } else {
                $value['description'] = "客户服务退款记录".$value['description'];
            }
            $value['money'] = number_format($value['money'] != 0 ? -$value['money']:$value['money'], 2);
            $value['payment_time_show'] =toDate($value['create_time']);
            $value['payment_verify_show'] = $value['flow_account_state'] = "已完成";
            $value['flow_account_show'] = "";
            $value['verify_role_show'] =role_html($value['creator_role_id']);;
        }else {
            if ($value['flow_account_id']) {
                $flow_account = M('account')->where(array('account_id'=>$value['flow_account_id']))->find();
                $value['flow_account'] = $flow_account;
                $value['flow_account_idcode'] = $flow_account['flowid'];
                $value['flow_account_state'] = $flow_account['state'] == 1?"已完成":"未完成";
                $value['flow_account_show'] = $value['flow_account_idcode'] . "[".$value['flow_account_state']."]";
            }else {
                $value['flow_account_show'] = $value['flow_account_state'] = ($value['payway'] != "余额冻结" ?"未完成":"");
            }
            $value['money'] = number_format($value['money'], 2);
        }
        return $value;
    }

    public function account_list() {
        $where = array(
            "market.market_id"=>$_GET['id'],
            "account.related"=>"market",
            "account.clause_type_id"=>array("in", array(216,230))
        );
        $this->ajaxReturn(make_datatable_list("MarketAccountView", $where, array($this, "format_account_info")),'JSON');
    }

    public static function relieve_account_money($market){
        $where = array(
            "account.account_type"=>'customer',
            "customer.customer_id"=>$market['customer_id'],
            "account.related"=>'market',
            "account.related_id"=>$market['market_id'],
            "account.clause_type_id"=>230,
        );
        $relieve_sum_money = D("CustomerAccountView")->where($where)->sum('account.money');
        return $relieve_sum_money ? $relieve_sum_money:0;
    }

    public static function calculate_earnest_account($market) {
        $where = array(
            "account.account_type"=>'customer',
            "customer.customer_id"=>$market['customer_id'],
            "account.related"=>'market',
            "account.related_id"=>$market['market_id'],
            "account.clause_type_id"=>229,
        );
        $freeze_sum_money = D("CustomerAccountView")->where($where)->sum('account.money');
        if (!$freeze_sum_money) {
            $freeze_sum_money = 0.0;
        }
        return $freeze_sum_money - self::relieve_account_money($market);
    }

    public static function calculate_deficit_account($market) {
        $where = array(
            "account.account_type"=>'market',
            "market_account.market_id"=>$market['market_id'],
            "account.clause_type_id"=>216,
        );
        $out_sum_money = D("MarketCalculateAccountView")->where($where)->sum('account.money');
        if (!$out_sum_money) {
            $out_sum_money = 0.0;
        }
        $where['account.clause_type_id'] = 235;
        $in_sum_money = D("MarketCalculateAccountView")->where($where)->sum('account.money');
        if (!$in_sum_money) {
            $in_sum_money = 0.0;
        }
        return $out_sum_money - $in_sum_money;
    }

    public static function calculate_market_account($market_ids) {
        if (!is_array($market_ids)) {
            $market_ids = array($market_ids);
        }
        foreach($market_ids as $market_id) {
            $market = D('MarketView')->where(array("market.market_id"=>$market_id))->find();

            $m_market_account = D("Manage/MarketCalculateAccountView");
            $confirm_price = $m_market_account->where(array('market_account.market_id'=>$market['market_id'],'account.clause_type_id'=>216, 'market_account.payment_verify'=>1))->sum("account.money");
            $wait_confirm_price = $m_market_account->where(array('market_account.market_id'=>$market['market_id'],'account.clause_type_id'=>216, 'market_account.payment_verify'=>0))->sum("account.money");

            $deficit_price = self::calculate_deficit_account($market);
            if ($market['settle_state'] == 918) {
                $surplus_price = 0;
            } else {
                $surplus_price = $market['sum_settle_price'] - $deficit_price;
            }
            $customer_earnest = self::calculate_earnest_account($market);

            $market_data = array(
                "confirm_price"=>$confirm_price,
                "wait_confirm_price"=>$wait_confirm_price,
                "deficit_price"=>$deficit_price,            //不足额
                "surplus_price"=>$surplus_price,            //多余的
                "customer_earnest"=>$customer_earnest,
            );

            if ($market['settle_state'] < 917) {
                if ($deficit_price <=0) {
                    $market_data['settle_state'] = 913;
                } else if($deficit_price > 0 && $deficit_price < $market['sum_settle_price']) {
                    $market_data['settle_state'] = 914;
                } else if (($confirm_price < $market['sum_settle_price']) || ($wait_confirm_price > 0 && $deficit_price > $market['sum_settle_price'])) {
                    $market_data['settle_state'] = 915;
                } else if ($confirm_price >= $market['sum_settle_price']) {
                    $market_data['settle_state'] = 916;
                }
            }
            M("market")->where(array('market_id'=>$market['market_id']))->setField($market_data);
        }
    }

    public function submit_settlement() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!vali_permission("market", "edit")) {
            alert('error', '您没有此权利!', $_SERVER['HTTP_REFERER']);
        }

        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $market_id = $this->_request('id');

        $where = array("market.market_id"=>$market_id);
        $market = D('MarketView')->where($where)->find();
        if (!$market) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($market['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        if (is_market_settle($market)) {
            alert('error', "订单已经提交结算",$_SERVER['HTTP_REFERER']);
        }

        if ($market['status_id'] != 3 || $market['deficit_price'] < $market['sum_settle_price'] || $market['wait_confirm_price']!=0) {
            alert('error', "订单还没有完结",U("market/view", "id=".$market_id));
        }
        $market_data = array(
            "settle_state"=>917,
            "is_cancel_submit"=>0,
        );
        M("market")->where(array('market_id'=>$market_id))->setField($market_data);
        $this->log($market_id, "订单结算", "提交结算", 2);
        alert('success', "提交完成",$_SERVER['HTTP_REFERER']);
    }

    public function cancel_submit_settlement($market_data) {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $market_id = $this->_request('id');

        $market = D('MarketView')->where(array("market.market_id"=>$market_id))->find();
        if (!$market || $market['settle_state'] != 917) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($market['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        if ($market['settle_state'] < 917) {
            alert('error', "订单还没有提交结算",$_SERVER['HTTP_REFERER']);
        }

        M("market")->where(array('market_id'=>$market_id))->setField($market_data);
        if ($_POST['content']) {
            $this->log($market_id, "结算退回", $_POST['content'], 2);
        }
        alert('success', "撤销提交完成",$_SERVER['HTTP_REFERER']);
    }

    public function retreat_submit_settlement() {
        $market_data = array(
            "settle_state"=>916,
            "is_cancel_submit"=>1,
        );
        return $this->cancel_submit_settlement($market_data);
    }

    public function revoke_submit_settlement() {
        $market_data = array(
            "settle_state"=>916,
        );
        return $this->cancel_submit_settlement($market_data);
    }


    private function sum_product_salary($market_id, $product_id, $market_product_id = null) {
        $accwhere = array(
            "related"=>"market",
            "related_id"=>$market_id,
            "clause_type_id"=>218,
            "account_type"=>"product",
            "clause_additive"=>$product_id,
        );
        if ($market_product_id) {
            $accwhere['market_product_id'] = array("in", array($market_product_id, ""));
        }
        $money = D("MarketCalculateAccountView")->where($accwhere)->sum("account.money");
        return $money ? $money : 0;
    }

    private function settlement_market_salary($market, $m_market_product) {
        define("NO_AUTHORIZE_CHECK", true);
        foreach($m_market_product as $market_product) {
            $acc_salary = $this->sum_product_salary($market['market_id'], $market_product['product_id'], $market_product['market_product_id']);;
            if ($market_product['salary'] > 0 && ($salary = $market_product['salary'] - $acc_salary) > 0) {
                $result = A("Manage/Account")->customer_pay_market_salary($salary, $market, $market_product['product_id'], 217, $market_product['job_number'], $market_product['market_product_id']);
                if (is_array($result)) {
                    throw_exception_log($result['error'], "工资结算失败");
                }
                if ($m_market_product['workstate_id'] == "面试") {
                    M("product")->where("product_id=".$market_product['market_product_id'])->setField("workstate_id", "排岗");
                }
                $logcnt = "雇员".$market_product['idcode']."完成工资结算：".$salary.", <a target='_blank' href='".U("account/view", "id=".$result)."'>查看</a>";
            } else {
                $logcnt = "工资是金额为0， 无需结算,salary:".$market_product['salary'].", ".$salary;
            }
            M("market_product")->where("market_product_id=".$market_product['market_product_id'])->setField("salary_settle_time", time());
            $this->log($market['market_id'], "工资结算成功",$logcnt, 2);
        }
    }

    private function sum_product_reward($atype, $market_id, $product_id, $market_product_id = null) {
        $accwhere = array(
            "related"=>"market",
            "related_id"=>$market_id,
            "clause_type_id"=>$atype,
            "account_type"=>"product",
            "clause_additive"=>$product_id,
        );
        if ($market_product_id) {
            $accwhere['market_product_id'] = array("in", array($market_product_id, ""));
        }
        $money = D("MarketCalculateAccountView")->where($accwhere)->sum("account.money");
        return $money ? $money : 0;
    }

    private function settlement_market_reward($market, $m_market_product) {
        define("NO_AUTHORIZE_CHECK", true);
        foreach($m_market_product as $market_product) {
            $acc = array("2"=>array(296, "product"),"3"=>array(302, "customer"), "4"=>array(310, "staff"));
            $ct = $acc[$market_product['channel_role_model']];
            if ($ct && $market_product['urge_reward_settle_time'] == 0 && $market_product['urge_reward_price'] > 0) {
                $acc_price = $this->sum_product_reward($ct[0], $market['market_id'], $market_product['channel_role_id'], $market_product['market_product_id']);;
                if (($price = $market_product['urge_reward_price'] - $acc_price) > 0) {
                    $result = A("Manage/Account")->pay_market_account($ct[1], $market_product['channel_role_id'], $price, $market, $ct[0]);
                    $logcnt = model_info_show_html($ct[1], $ct[0])."完成建档奖励结算：".$price.", <a target='_blank' href='".U("account/view", "id=".$result)."'>查看</a>";
                }
                $this->log($market['market_id'], "建档奖励结算成功",$logcnt, 2);
            }
            M("market_product")->where("market_product_id=".$market_product['market_product_id'])->setField("urge_reward_settle_time", time());

            $acc = array("2"=>array(297, "product"),"3"=>array(303, "customer"), "4"=>array(311, "staff"));
            $ct = $acc[$market_product['channel_role_model']];
            if ($ct && $market_product['channel_reward_settle_time'] == 0 && $market_product['channel_reward_price'] > 0) {
                $acc_price = $this->sum_product_reward($ct[0], $market['market_id'], $market_product['channel_role_id'], $market_product['market_product_id']);;
                if (($price = $market_product['channel_reward_price'] - $acc_price) > 0) {
                    $result = A("Manage/Account")->pay_market_account($ct[1], $market_product['channel_role_id'], $price, $market, $ct[0]);
                    $logcnt = model_info_show_html($ct[1], $ct[0]). "完成渠道奖励结算：" . $price . ", <a target='_blank' href='" . U("account/view", "id=" . $result) . "'>查看</a>";
                }
                $this->log($market['market_id'], "渠道奖励结算成功", $logcnt, 2);
            }
            M("market_product")->where("market_product_id=" . $market_product['market_product_id'])->setField("channel_reward_settle_time", time());
        }
    }

    private function settlement_market_agency($market) {
        define("NO_AUTHORIZE_CHECK", true);
        if ($market['sum_agency'] > 0) {
            $result = A("Manage/Account")->customer_pay_market_agency($market['sum_agency'], $market, 219);
            if (is_array($result)) {
                throw_exception_log($result['error'], "中介费结算失败");
            }
            $logcnt = "完成中介费结算:".$market['sum_agency'].", <a href='".U("account/view", "id=".$result)."'>查看</a>";
        } else {
            $logcnt = "中介费金额为0， 无需结算";
        }
        M("market")->where(array("market_id"=>$market['market_id']))->setField("agency_settle_time", time());
        $this->log($market['market_id'], "中介费结算成功", $logcnt, 2);
    }

    private function settlement_market_channel($market, $m_market_channel) {
        define("NO_AUTHORIZE_CHECK", true);
        $channel_models = array(
            "2"=>array("product", 224),
            "3"=>array("customer", 222),
            "4"=>array("staff", 228),
        );
        foreach($m_market_channel as $channel) {
            if (array_key_exists($channel['channel_role_model'], $channel_models)) {
                $channel_account_type = $channel_models[$channel['channel_role_model']][1];
                $channel_model = $channel_models[$channel['channel_role_model']][0];
                if ($channel['channel_role_id']) {
                    if ($channel['channel_price'] == 0) {
                        $this->log($market['market_id'], "渠道结算成功", $channel['market_channel_id']." - 渠道金额为0， 无需结算", 2);
                        M("market_channel")->where("market_channel_id=".$channel['market_channel_id'])->setField("channel_price_settle_time", time());
                        continue;
                    }
                    $result = A("Manage/Account")->pay_market_account($channel_model, $channel['channel_role_id'], $channel['channel_price'], $market, $channel_account_type);
                    if (is_array($result)) {
                        throw_exception_log($result['error'], "渠道结算失败");
                    }
                    $logcnt = "完成渠道结算 "." <a href='".U("account/view", "id=".$result)."'>查看</a>";
                    $this->log($market['market_id'], "渠道结算成功", $logcnt, 2);
                    M("market_channel")->where("market_channel_id=".$channel['market_channel_id'])->setField("channel_price_settle_time", time());
                }
            }
        }
    }

    private function settlement_market_urge($market, $m_market_urges) {
        define("NO_AUTHORIZE_CHECK", true);
        foreach($m_market_urges as $urge) {
            if ($urge['urge_price'] == 0) {
                $this->log($market['market_id'], "促单结算成功", $urge['market_urge_id']." - 促单金额为0， 无需结算", 2);
                continue;
            }

            $result = A("Manage/Account")->pay_market_account("staff", $urge['staff_id'], $urge['urge_price'], $market, 225);
            if (is_array($result)) {
                throw_exception_log($result['error'], "促单结算失败");
            }
            M("market_urge")->where("market_urge_id=".$urge['market_urge_id'])->setField("urge_price_settle_time", time());
            $logcnt = "完成促单结算,净利润：".$market['gain'].". <a href='".U("account/view", "id=".$result)."'>查看</a>";
            $this->log($market['market_id'], "促单结算成功", $logcnt, 2);
        }
    }

    private function thaw_customer_market_money($market) {
        define("NO_AUTHORIZE_CHECK", true);
        $result = A("Manage/Account")->pay_market_account("customer", $market['customer_id'], $market['customer_earnest'], $market, 230);
        if (is_array($result)) {
            throw_exception_log($result['error'], "客户账目解冻失败");
        }
        $logcnt = "完成客户服务款解冻。 <a href='".U("account/view", "id=".$result)."'>查看</a>";
        $this->log($market['market_id'], "客户账目解冻成功", $logcnt, 2);
    }

    public function ns() {
        session("role_id", N_ROLEID);
        $market = D('MarketView')->where(array("market.market_id"=>N_MARKETID))->find();
        if (!$market) {
            return;
        }
        try {
            $this->settlement_market($market);
            exit("00");
        }catch (LogException  $e) {
            exit($this->log($market['market_id'], $e->getTitle(), $e->getMessage(), 2));
        }catch(Exception $e) {
            exit($this->log($market['market_id'], "结算失败,系统错误", $e->getMessage(), 2));
        }
    }

    public function native_settlement($market) {
        $role_id = session("role_id");
        $execmd = "php '".dirname($_SERVER['SCRIPT_FILENAME'])."/mks.php' ".$market["market_id"]."  ".$role_id;
        $retstring = exec($execmd);
        if ($retstring != "00") {
            $log = M("log")->where("log_id=".$retstring)->find();
            if ($log) {
                throw_exception_log($log['subject'], $log['content']);
            }
        }
    }

    private function settlement_market($market) {
        G('settlement_marketStartTime');
        $this->log($market['market_id'], "结算订单", "开始结算订单", 2);
        if ($market['customer_earnest'] > 0) {
            $this->thaw_customer_market_money($market);
        } else {
            $this->log($market['market_id'], "客户账目解冻完成", "冻结款金额为0， 无需解冻。".$market['customer_earnest'], 2);
        }

        $where = array(
            "market.market_id"=>$market['market_id'],
        );
        $m_market_product = D("MarketProductView")->where($where)->select();
        if (!$m_market_product) {
            throw_exception_log("没有查询到任何服务雇员", "订单结算失败");
        } else {
            $this->settlement_market_salary($market, $m_market_product);
        }

        $where = array(
            "market.market_id"=>$market['market_id'],
            "market_product.urge_reward_price|market_product.channel_reward_price"=>array("neq", 0)
        );
        $m_market_product = D("MarketProductRewardView")->where($where)->select();
        if ($m_market_product) {
            $this->settlement_market_reward($market, $m_market_product);
        }

        if($market['agency_settle_time'] == 0) {
            $this->settlement_market_agency($market);
        } else {
            $this->log($market['market_id'], "中介费结算完成", "中介费已经结算过了！！", 2);
        }

        $where = array(
            "market.market_id"=>$market['market_id'],
            "channel_price_settle_time"=>0
        );
        $m_market_channel = D("MarketChannelView")->where($where)->select();
        if ($m_market_channel) {
            $this->settlement_market_channel($market, $m_market_channel);
        }else {
            $this->log($market['market_id'], "渠道费结算完成", "没有设置渠道信息", 2);
        }

        $where = array(
            "market.market_id"=>$market['market_id'],
            "urge_price_settle_time"=>0
        );
        $m_market_urges = D("MarketUrgeView")->where($where)->select();
        if ($m_market_urges) {
            $this->settlement_market_urge($market, $m_market_urges);
        } else {
            $this->log($market['market_id'], "促单费结算完成", "没有设置促单信息", 2);
        }

        M("market")->where(array('market_id'=>$market['market_id']))->setField(array( "settle_state"=>918, "settle_date"=>time()));

        if ($market['serve_modality'] == 0) {
            $settle_cnt = $this->settle_state_count($market['category_id'], $market['customer_id']);;
            if ($settle_cnt == 1) {
                M("market")->where(array('market_id'=>$market['market_id']))->setField("initial", "是");
            }
        }
        $this->update_market($market['market_id'], false);
        G('settlement_marketEndTime');
        $this->log($market['market_id'], "订单结算完成", "订单结算完成."."用时: ".G('settlement_marketStartTime','settlement_marketEndTime',6)."s", 2);
    }

    public function settlement() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $market_id = $this->_request('id');

        $market = D('MarketView')->where(array("market.market_id"=>$market_id))->find();
        if (!$market) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($market['settle_state'] == '918') {
            alert('error', "订单已经结算了",$_SERVER['HTTP_REFERER']);
        }
        if ($market['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }

        if (is_nosettle_cate($market['category_id'])) {
            if ($market['status_id'] != 3 || $market['settle_state'] < 916) {
                alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
        } else {
            if ($market['settle_state'] != 917) {
                alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
            if ($market['settle_state'] < 917) {
                alert('error', "订单还没有提交结算",$_SERVER['HTTP_REFERER']);
            }
        }

        try {
            $lock = new FileLock();
            $this->settlement_market($market);
            alert('success', "结算完成",$_SERVER['HTTP_REFERER']);
        }catch (LogException  $e) {
            $this->log($market_id, $e->getTitle(), $e->getMessage(), 2);
            alert('error', "订单结算失败: ".$e->getMessage(),$_SERVER['HTTP_REFERER']);
        }catch(Exception $e) {
            $this->log($market_id, "结算失败,系统错误", $e->getMessage(), 2);
            alert('error', "结算失败,系统错误: ".$e->getMessage(),$_SERVER['HTTP_REFERER']);
        }
    }

    public function payment_status($type_id, $account_id, $market, $usable) {
        if (!is_array($market)) {
            $where = array(
                "market.market_id"=>$market
            );
            $market = D("Manage/MarketView")->where($where)->find();
        }
        $account_type = M("account_type")->where(array("type_id"=>$type_id))->find();

        if ($market) {
            if ($account_type['type_id'] == 230) {
                define("NO_AUTHORIZE_CHECK", true);
                $result = A("Manage/Account")->pay_market_account("market", $market['market_id'], $usable, $market, 235);
                if (!is_array($result)) {
                    $logcnt = "客户服务金额解冻, 向客户退款 ".number_format($usable,2).", <a href='".U("account/view", "id=".$result)."'>查看</a>";
                    $this->log($market['market_id'], "客户服务金额解冻",$logcnt , 2);
                } else {
                    $this->log($market['market_id'], "客户服务金额解冻",$result['error'] , 2);
                }

            } else if ($account_type['type_id'] == 229){
                $_POST['payway'] = "余额冻结";
                define("NO_AUTHORIZE_CHECK", true);
                $result = A("Manage/Account")->pay_market_account("market", $market['market_id'], $usable, $market, 216);
                $data = array(
                    "payment_verify"=>1,
                );
                $data['verify_time'] = time();
                $data['verify_role_id'] = session('role_id');
                $result = M('market_account')->where(array('account_id'=>$result))->setField($data);
                if (!is_array($result)) {
                    self::calculate_market_account($market['market_id']);
                    $logcnt = "客户服务金额冻结 ".number_format($usable,2).", <a href='".U("account/view", "id=".$result)."'>查看</a>";
                    $this->log($market['market_id'], "客户服务金额冻结", $logcnt, 2);
                } else {
                    $this->log($market['market_id'], "客户服务金额解冻",$result['error'] , 2);
                }
            } else {
                $data = array(
                    "payment_time"=>time()
                );
                M("market_account")->where("account_id=".$account_id)->setField($data);

                $logtip = AccountAction::format_update_log_info($account_id, $account_type, $usable);
                $this->log($market['market_id'], "客户服务账目", $logtip, 2);
            }
            $this->update_surplus_price("market", $market['market_id']);
        }
    }

    public function customer_deposit($market, $account) {
        $customer = M("customer")->where(array("customer_id"=>$market['customer_id']))->find();
        if (!$customer) {
            return array("error"=>"错误的客户信息");
        }

        $_POST['related'] = 'market';
        $_POST['related_id'] = $market['market_id'];
        $_POST['payway'] = $account['payway'];
        $_POST['receipt_number'] = $account['receipt_number'];
        $result = A("Manage/Account")->pay_market_account("customer", $market['customer_id'], $account['money'], $market, 31);
        if (is_array($result)) {
            return $result;
        }
        $flow_account = M('account')->where(array('account_id'=>$result))->find();
        if ($flow_account['infow_account_id']) {
            M('account')->where(array('account_id'=>$flow_account['infow_account_id']))->setField("state", 1);
            M('market_account')->where(array('account_id'=>$account['account_id']))->setField("flow_account_id", $flow_account['infow_account_id']);
        }
        return M('account')->where(array('account_id'=>$account['account_id']))->setField("infow_account_id", $result) === true;
    }


    public function pv() {
        session("role_id", N_ROLEID);
        $cc = $this->payment_verify(N_ACCOUNTID);
        exit($cc != false ? "00" :"1");
    }

    public function native_payment_verify($account_id) {
        return $this->payment_verify($account_id);
        $lock = new FileLock();
        $role_id = session("role_id");
        $execmd = "php '".dirname($_SERVER['SCRIPT_FILENAME'])."/mkp.php' ".$account_id."  ".$role_id;
        $result = exec($execmd);
        return $result == "00";
    }

    public function payment_verify($account_id) {
        $account =  M('account')->where(array('account_id'=>$account_id))->find();
        if (!$account) {
            return false;
        }
        $market = M('market')->where(array("market_id"=>$account['related_id']))->find();
        if ($account['payway'] != "余额冻结") {
            $result = $this->customer_deposit($market, $account);
            if (is_array($result)) {
                $this->log($market['market_id'], "支付确认", "客户账目预存款失败, ".$result['error'], 2);
                return false;
            }
        }

        unset($_POST['receipt_number']);
        $m_account = A("Manage/Account");
        $result = $m_account->pay_market_account("customer", $market['customer_id'], $account['money'], $market, 229);
        if (!is_array($result)) {
            $m_account->account_payment_verify_update($account, 1);
        }
        return !is_array($result);
    }

    public function renewal() {
        $market_id = $this->_request('id');
        if (!$market_id) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $market = M('market')->where('market_id = %d',$market_id)->find();
        if (!$market) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $this->category_id = $market['category_id'];
        $this->renewal_market_id = $market_id;
        $this->show_add(self::reset_market_info($market));
    }

    public function reset_market_info($market) {
        $market['continueornot'] = "待定";
        $market['auto_renewal'] = "打开";
        $market['status_id'] = 1;
        $market['create_time'] = $market['update_time'] = time();
        $unfields = array(
            'initial',
            'idcode','market_id',
            'start_time','end_time',
            'agency_settle_time','wait_confirm_price','confirm_price','deficit_price','surplus_price',
            'settle_agency','settle_salary','settle_price','settle_date','settle_state',
            'sum_recess_day', 'sum_service_duration', 'sum_settle_price', 'sum_agency', 'sum_salary','sum_channel_price',
            'product_count','customer_earnest','def_channel_id', 'sum_urge_price'
        );
        foreach($unfields as $v) {
            unset($market[$v]);
        }
        return $market;
    }


    public function mission() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $market_product_id = $this->_request('id');
        $where = array(
            "market_product_id"=>$market_product_id
        );
        $market_product = D("MarketProductView")->where($where)->find();
        if (!$market_product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        $market_product['live_address'] = mb_substr(format_address_field($market_product['live_address']), 3);
        $this->market_product = $market_product;
        $this->display(); // 输出模板
    }


    public static function change_market_product_event($market_product, $workstate_id, $start_date, $end_date) {
        if (!is_array($market_product)) {
            $market_product = D("MarketProductView")->where("market_product_id=".$market_product)->find();
        }
        $description = "客户服务：".market_show_html($market_product['market_id']);
        D("Manage/MarketProductEventView")->change_market_event($market_product, $workstate_id, $start_date, $end_date, $description);
    }

    public function update_market_statistics($customer_id) {
        $where = array("customer_id"=>$customer_id);
        $market_list = M("market")->where($where)->select();

        $data = array("sicount"=>0, "sfcount"=>0, "slcount"=>0, "sncount"=>count($market_list));
        foreach($market_list as $b) {
            if (in_array($b['status_id'], array("1","4"))) {
                $data['sfcount'] += 1;
            } else if (in_array($b['status_id'], array("2","5"))) {
                $data['sicount'] += 1;
            } else if ($b['status_id'] == '3') {
                $data['slcount'] += 1;
            }
        }

        if ($data['sicount'] > 0) {
            $data['service_state'] = "服务中";
        } else if ($data['sfcount'] > 0) {
            $data['service_state'] = "服务前";
        } else if ($data['slcount'] > 0) {
            $data['service_state'] = "服务后";
        } else if ($data['sncount'] == 0) {
            $data['service_state'] = "未成单";
        }
        M("customer")->where($where)->setField($data);
    }

    public function add_edit_log($market_id, $logcont, $change_fields) {
        foreach($change_fields as $v) {
            $logcont.=$v['name']."[".$v['oldvalue']."=>".$v['newvalue']."],";
        }
        $this->log($market_id, "更新日志",$logcont, 2);
    }

    public function logtable() {
        $data_field = array(
            array(
                "field"=>"create_date_show",
                "order"=>"log_id"
            ),
            array(
                "field"=>"role_show",
                "order"=>"role_id"
            ),
        );
        if (!isset($_GET['id'])) {
            $data_field[] = array(
                "field"=>"market_show",
                "order"=>"market_idcode"
            );
        }
        $data_field[] = array(
            "field"=>"subject",
            "order"=>"subject"
        );
        $data_field[] =array(
            "field"=>"content_show",
            "order"=>"content"
        );
        $data_field[] =array(
            "field"=>"operator_show",
            "order"=>"market_idcode"
        );

        $where = array();
        if ($_GET['id']) {
            $where['market.market_id'] = array("in", trim($_GET['id']));
        }
        if ($_GET['start_time'] || $_GET['end_time']) {
            $where['log.create_date'] =  array('between', make_time_between());
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['log.content|log.subject'] =  array('like', "%".trim($_REQUEST['search']['value'])."%");
        }
        if ($_GET['create_role_id']) {
            $where['log.role_id'] = $_GET['create_role_id'];
        }
        if ($_GET['_string']) {
            $where["_string"] =trim($_GET['_string']);
        }
        $where['league_id'] = session('league_id');

        $this->ajaxReturn(make_data_list("MarketLogView", $where, $data_field, array($this, "format_market_log")),'JSON');
    }

    public function format_market_log($v) {
        $v['create_date_show'] = toDate($v['create_date'], 'Y-m-d H:i:s');
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        $html = '<span><a target="_blank" href="'.U('market/view', 'id='.$v['market_id']).'">' .$v['market_idcode'].'</a></span>&nbsp;';;
        $v['market_show'] = $html;
        $v['content_show'] = $v['content'];

        if ($v['log_type'] == 1) {
            $v['operator_show'] = '<span><a  href="javascript:void(0);" onclick="return delete_log('.$v['log_id'].');">删除</a></span> | ';;
        } else {
            $v['operator_show'] = '<span><a href="javascript:void(0);"  style="cursor:not-allowed;color:darkgrey">删除</a></span> | ';;
        }
        $v['operator_show'] .= "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>查看</a>";;

        return $v;
    }


    public function format_excel_fields($ex) {
        $where = array(
            "model"=>"market",
            "form_type"=>array("not in",array(
                "pic","video","file"
            )),
            "field_id"=>array("not in",array(
                "644","751","753"
            )),
        );
        $field_list = M('Fields')->where($where)->order('order_id')->select();

        $field_list2 = array(
            array("name"=>"客户姓名", "field"=>"customer_name"),
            array("name"=>"客户编号", "field"=>"customer_idcode"),
            array("name"=>"客户电话", "field"=>"customer_telephone")
        );
        array_splice($field_list, 8, 0, $field_list2);
        return $field_list;
    }

    public function evaluate() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->market_product_id = $this->_request('id');
        $this->market_id = $this->_request("market_id");
        $where = array(
            "market_product_id"=>$this->market_product_id
        );
        $this->market_product = $market_product = D("MarketProductView")->where($where)->find();
        if (!$this->market_product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        $market_product_evaluate = M("market_product_evaluate")->where($where)->find();
        if(!$this->isPost()) {
            $fields_group = field_list_html_edit("market_product_evaluate",$market_product_evaluate);
            $this->fields_group = $fields_group;
            $this->market_product_evaluate = $market_product_evaluate;
            $this->alert =  parseAlert();
            $this->refer_url = $_SERVER['HTTP_REFERER'];
            return $this->display("evaluate"); // 输出模板
        }

        $_POST['update_role_id'] = session('role_id');
        if ($market_product_evaluate) {
            $market_product_evaluate_id = $market_product_evaluate['market_product_evaluate_id'];
            $this->submit_edit($market_product_evaluate["market_product_evaluate_id"],"market_product_evaluate");
            $this->add_edit_log($this->market_id, "修改评价成功: ", D('MarketProductEvaluateView')->verity_check($market_product_evaluate));
        } else {
            $_POST['market_product_id'] = $this->market_product_id;
            $_POST['market_id'] = $this->market_id;
            $_POST['product_id'] = $this->market_product['product_id'];
            $market_product_evaluate_id = $this->submit_add("market_product_evaluate");
        }

        if ($market_product_evaluate_id) {
            $market_product_evaluate = M("market_product_evaluate")->where(array("market_product_evaluate_id"=>$market_product_evaluate_id))->find();
            if ($market_product_evaluate) {
                $state = $market_product_evaluate['aware'] && $market_product_evaluate['profession'] &&$market_product_evaluate['evaluate'];
                M("market_product")->where(array("market_product_id"=>$this->market_product_id))->setField("product_evaluate_state", $state?"已评":"未评");
                M("market")->where(array("market_id"=>$this->market_id))->setField("evaluate_state", $state?"已评":"未评");

                if ($market_product_evaluate['evaluate']) {
                    $ee_map = array("好评"=>1, "中评"=>0, "差评"=>-1);
                    $praise_days = $market_product['service_duration'] * $ee_map[$market_product_evaluate['evaluate']];
                    M("market_product_evaluate")->where(array("market_product_evaluate_id"=>$market_product_evaluate_id))->setField("praise_days", $praise_days);
                    $this->update_product_evaluate($this->market_product['product_id']);
                }
            }
        }
        alert('success', "修改评价成功", $_POST['refer_url']?$_POST['refer_url']:$_SERVER['HTTP_REFERER']);
    }

    public function survey() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->market_id = $market_id = $this->_request("market_id");
        $where = array(
            "market_id"=>$market_id
        );
        $this->market = M("market")->where($where)->find();
        if (!$this->market) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        $market_survey = M("market_survey")->where($where)->find();
        if(!$this->isPost()) {
            $fields_group = field_list_html_edit("market_survey",$market_survey);
            $this->fields_group = $fields_group;
            $this->market_survey = $market_survey;
            $this->alert =  parseAlert();
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            return $this->display("survey"); // 输出模板
        }

        $_POST['update_role_id'] = session('role_id');
        if ($market_survey) {
            $market_survey_id =$market_survey["market_survey_id"];
            $this->submit_edit($market_survey["market_survey_id"],"market_survey");
            $this->add_edit_log($market_id, "修改评价成功: ", D('MarketSurveyView')->verity_check($market_survey));
        } else {
            $_POST['market_id'] =$market_id;
            $market_survey_id = $this->submit_add("market_survey");
        }


        if ($market_survey_id) {
            $market_survey = M("market_survey")->where(array("market_survey_id"=>$market_survey_id))->find();
            if ($market_survey) {
                if ($market_survey['status']) {
                    $data = array(
                        "survey_state"=>$market_survey['status'],
                        "survey_time"=>$market_survey['update_time']
                    );
                    M("market")->where(array("market_id"=>$market_id))->setField($data);
                }
            }
        }
        alert('success', "修改评价成功", U('market/view', 'assort=product&id='.$market_id));
    }


    public function transfer() {
        $m_market = M("market")->select();
        foreach($m_market as $k=>$v) {
            $this->update_product_first_mark($v['market_id']);
        }
    }

    public function reward_edit() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_REQUEST['id']) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $market_product_id = $this->_request('id');
        $where = array(
            "market_product_id"=>$market_product_id
        );
        $market_product_reward = D("MarketProductRewardView")->where($where)->find();
        if (!$market_product_reward) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        if ($market_product_reward['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        if($this->isPost()){
            M("market_product")->where($where)->setField($_REQUEST['rweard_type'] == "urge"?"urge_reward_price":"channel_reward_price", $_REQUEST['price']);
            $this->update_market($market_product_reward['market_id'], false);
            alert('success','编辑奖励成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('market/view', 'assort=cost&id='.$market_product_reward['market_id']));
        }else{
            if ($market_product_reward['settle_state'] == "918") {
                alert('error', "订单已经结算， 不可以修改",$_SERVER['HTTP_REFERER']);
            }
            $this->rweard_type = $_REQUEST['rweard_type'];
            $this->price = ($this->rweard_type == "urge"?$market_product_reward['urge_reward_price']:$market_product_reward['channel_reward_price']);
            $this->market_product_id = $market_product_id;
            $this->market_product_reward = $market_product_reward;
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();;
            $this->display();
        }
    }

    public function make_owner_role_id_list_header() {

    }
}