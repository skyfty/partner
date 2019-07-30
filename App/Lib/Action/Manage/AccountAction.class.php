<?php
class AccountAction extends BaseAction{
    public function _initialize(){
        $this->admin_role = session('?admin');
        $action = array(
            'permission'=>array(
                'getaccounttip',
                'getrelated_info',
                'stepstate',
                'timer_task',
                'settlestate',
                'settlement_flow',
                'replenish_settlement',
                'editflow',
                'termstate',
                'change_state',
                'delimg',
                'deletevideo',
                'deletefile',
                'paynotify',
                'payreturn',
                'wxpay',
                'withdraw',
                'analytics',
                'serve_pay',
                'train_pay',
                'getpaymodel_info',
                'pay',
                'settlement',
                'eee',
                'gggg',
                'account_payment_verify_update'
            ),
            'allow'=>array(
                'analytics',
                'adddialog',
                "getclausetype"
            )
        );

        if (ACTION_NAME != "analytics" && ACTION_NAME != "logger") {
            $acc_types = array('market', 'staff', 'inernal','product','customer', 'flow','cultivate');
            if (!$_REQUEST['t'] || ACTION_NAME == "index") {
                foreach($acc_types as $v) {
                    if (vali_permission("account", $v, $v)) {
                        $_REQUEST['t'] = $v;
                        $action['allow'][] = "index";
                        break;
                    }
                }
            }

            $this->type = $_REQUEST['t'] = $_REQUEST['t']?$_REQUEST['t']:"market";
            if(!in_array($this->type, $acc_types)){
                alert('error',L('PARAMETER_ERROR'),U('account/'.$this->type));
            }
            $this->assign('t',$this->type);
        }
        if ($_REQUEST['restrict']) {
            $action['permission'][]="add";
            $action['permission'][]="edit";
            $this->assign('restrict',$_REQUEST['restrict']);
        }
        if (NO_AUTHORIZE_CHECK === true)
            return;

        if (in_array($_REQUEST['restrict'], array("market", "cultivate"))) {
            if (vali_permission($_REQUEST['restrict'], "account_add")) {
                $action['permission'][]=$_REQUEST['restrict']."add";
            }
            if (vali_permission($_REQUEST['restrict'], "account_edit")) {
                $action['permission'][]=$_REQUEST['restrict']."edit";
            }
            if (vali_permission($_REQUEST['restrict'], "account_delete")) {
                $action['permission'][]="delete";
            }
        }
        B('Authenticate', $action);
    }

    public function getaccounttip() {
        $type_id = $this->_request("type_id");
        $acctype = M('account_type')->cache(true)->where(array("type_id"=>$type_id))->find();
        if (!$acctype) {
            $this->ajaxReturn("请填写金额","",  1);
        }
        $module = $this->_request("module");
        $module_id = $this->_request("module_id");
        $mrecord = M($module)->where(array($module."_id"=>$module_id))->find();
        if (!$mrecord) {
            $this->ajaxReturn("请填写金额","",  1);
        }

        if ($acctype['type_id'] == 18 || $acctype['type_id'] == 32) {
            $actual = $mrecord['actual'];
            $result = "可提现余额: ".number_format($actual, 2);
        } else if ($acctype['mold'] == -3) {
            $actual = $mrecord['actual'];
            $result = "可用余额: ".number_format($actual, 2)." 可冻结金额: ".number_format(($actual > 0 ? $actual : 0), 2);
        } elseif ($acctype['mold'] == -1) {
            $actual = number_format($mrecord['actual'], 2);
            $result = "账户余额为 ".$actual;
        }
        $this->ajaxReturn($result,"", 1);
    }

    public function getrelated_info() {
        $related_module = $this->_request("related_module");
        $module_id = $this->_request("id");
    }

    public function change_state() {
        $account_ids = is_array($_REQUEST['account_id']) ? $_REQUEST['account_id'] : array($_REQUEST['id']);
        if (count($account_ids) == 0 || !isset($_REQUEST['state'])) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $state = $this->_request("state");
        $where = array(
            'state'=>array("neq", $state),
            'account_id' => array("in", $account_ids)
        );
        $where['league_id'] = session('league_id');

        $accounts = M('account')->where($where)->select();
        foreach ($accounts as $account) {
            if ($account['related'] == "cash" && $account['related_id']) {
                $data = array(
                    'pdc_payment_state' => $this->_request("state")
                );
                M('cash')->where("pdc_id=" . $account['related_id'])->setField($data);
            }
            M('account')->where(array('account_id'=>$account['account_id']))->setField('state', $state);

            if ($state == 1 && (($account['clause_type_id'] == 123) || ($account['clause_type_id'] == 125))) {
                $infow_account = M('account')->where(array('account_id' => $account['infow_account_id']))->find();
                if ($infow_account) {
                    $model_name = $infow_account['account_type'];
                    if ($model_name == "product" || $model_name == "customer") {
                        $m_model = D("Manage/" . ucfirst($model_name) . 'View')->where(array($model_name . "_id" => $infow_account['clause_additive']))->find();
                        $param = array();
                        if ($model_name == "product") {
                            $param['appellation'] = "阿姨";
                        }
                        //send_notice(20, $model_name, $m_model, $param, 4);
                    }
                }
            }
            $this->log($account['account_id'], "改变账目状态", '状态改变为：' . ($state == 1 ? "已完成" : "未完成") . '成功');
        }
        alert('success','状态改变成功',$_SERVER['HTTP_REFERER']);
    }


    public function change_payment_verify() {
        $account_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if (0 == $account_id) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()) {
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            $this->module_payment_verify($account_id, $this->_request("payment_verify"));
            $this->ajaxReturn("OK");
        }
        else {
            $account = D(ucfirst($this->type).'AccountView')->where('account.account_id = %d',$account_id)->find();
            if (!$account) {
                alert('error', '没有这个账目', U('account/index'));
            }
            $this->account = $account;
            $this->display("Account:change_payment_verify");
        }
    }

    public function module_payment_verify($id, $payment_verify) {
        $where = array('account_id'=>$id);
        $account =  M('account')->where($where)->find();
        if (!$account) {
            return false;
        }
        $related_account = M($account['related'].'_account')->where($where)->find();
        if (!$related_account || $related_account['payment_verify'] == $payment_verify) {
            return false;
        }
        define("NO_AUTHORIZE_CHECK", true);
        $result = true;
        if (in_array($account['related'], array("market","cultivate"))) {
            if ($payment_verify == 1) {
                $result = A("Manage/".ucfirst($account['related']))->native_payment_verify($account['account_id']);
            }
        } else {
            self::account_payment_verify_update($account, $payment_verify);
        }
        $this->log($id, $result?"验证成功":"验证失败", '状态改变为：'.$payment_verify);
    }

    public function account_payment_verify_update($account, $payment_verify) {
        $data = array(
            "payment_verify"=>$payment_verify,
        );
        if ($payment_verify == 1) {
            $data['verify_time'] = time();
            $data['verify_role_id'] = session('role_id');
        } else {
            $data['verify_time'] = $data['verify_role_id'] = 0;
        }
        M($account['related'].'_account')->where(array('account_id'=>$account['account_id']))->setField($data);
        self::calculate_module_account($account['related'], $account['related_id'], $account['account_type']);
    }

    public function format_account_info($v, $export = false) {

        if ($v['infow_account_id']) {
            if ($v['inflow_model'] == "inernal") {
                $infow['show_infow'] = $infow['export_info'] = session('league_name');
            } elseif ($v['inflow_model'] == "flow"){
                $infow['show_infow'] = $infow['export_info'] = "流水";
            }else {
                $infow = D(ucfirst($v['inflow_model']).'AccountView')->where(array("account_id"=>$v['infow_account_id']))->find();;
                if ($infow) {
                    $infow_href_title = $infow[$v['inflow_model']."_name"];
                    $aname = $infow["idcode"];
                    $model_id = $infow[$v['inflow_model']."_id"];
                    $href = U($v['inflow_model']."/view", 'id='.$model_id);
                    $infow['show_infow'] = "<a href='".$href."' target='_blank'/>[".$aname."]&nbsp;".$infow_href_title."</a>";
                    $infow['export_info'] = "[".$aname."]".$infow_href_title;
                }
            }
            $v['infow'] =$infow;
        }

       if ($v['related_model'] == 'trade') {
            $v['trade'] = D("Manage/TradeAccountView")->where(array('account_id'=>$v['account_id']))->find();
            $owner_role = getUserByRoleId($v['trade']['owner_role_id']);
            if ($owner_role) {
                $v['trade']['owner_role'] = $owner_role;
                $coree = $v['trade']['corre'];
                $corre_model = D("Manage/".ucfirst($coree)."TradeAccountView")->where(array('account_id'=>$v['account_id']))->find();
                if ($corre_model) {
                    $v['trade']['corre_info'] = "<a href='".U($coree.'/view', 'id='.$corre_model[$coree.'_id'])."' target='_blank'>[".$corre_model['idcode']."]".$corre_model['name']."</a>";
                }
            }
        } else if ($v['related_model'] == 'market') {
            if ($v['account_type'] != "market") {
                $v['market'] = D("Manage/MarketRelatedAccountView")->where(array('account_id'=>$v['account_id']))->find();
            } else {
                $v['market'] = D("Manage/MarketAccountView")->where(array('account_id'=>$v['account_id']))->find();
            }
            $owner_role = getUserByRoleId($v['market']['owner_role_id']);
            if ($owner_role) {
                $v['market']['owner_role'] = $owner_role;
            }
            $owner_role = getUserByRoleId($v['market']['verify_role_id']);
            if ($owner_role) {
                $v['market']['verify_role'] = $owner_role;
            }
            $v['category_name'] = $v['market']['category_name'];
            $v['per_verify'] = vali_permission("account", "change_payment_verify", $this->type);

            $coree = $v['market']['corre'];
            $corre_model = D("Manage/MarketAccountView")->where(array('account_id'=>$v['account_id']))->find();
            if ($corre_model) {
                $v['market_infow'] = "[".$corre_model[$coree.'_idcode']."]".$corre_model[$coree.'_name'];
                $v['market']['corre_info'] = "<a href='".U($coree.'/view', 'id='.$corre_model[$coree.'_id'])."' target='_blank'>".$v['market_infow']."</a>";
            }
        } else if ($v['related_model'] == 'cultivate') {
            if ($v['account_type'] != "cultivate") {
                $v['cultivate'] = D("Manage/CultivateRelatedAccountView")->where(array('account_id'=>$v['account_id']))->find();
            } else {
                $v['cultivate'] = D("Manage/CultivateAccountView")->where(array('account_id'=>$v['account_id']))->find();
            }
            $owner_role = getUserByRoleId($v['cultivate']['owner_role_id']);
            if ($owner_role) {
                $v['cultivate']['owner_role'] = $owner_role;
            }
            $owner_role = getUserByRoleId($v['cultivate']['verify_role_id']);
            if ($owner_role) {
                $v['cultivate']['verify_role'] = $owner_role;
            }
            $v['category_name'] = $v['cultivate']['category_name'];
            $v['per_verify'] = vali_permission("account", "change_payment_verify", $this->type);

            $coree = $v['cultivate']['corre'];
            $corre_model = D("Manage/".ucfirst($coree)."CultivateAccountView")->where(array('account_id'=>$v['account_id']))->find();
            if ($corre_model) {
                $v['cultivate_infow'] = "[".$corre_model[$coree.'_idcode']."]".$corre_model[$coree.'_name'];
                $v['cultivate']['corre_info'] = "<a href='".U($coree.'/view', 'id='.$corre_model[$coree.'_id'])."' target='_blank'>".$v['cultivate_infow']."</a>";
            }
        } else if ($v['related'] && $v['related_id']) {
            $v[$v['related']] = D("Manage/".ucfirst($v['related'])."View")->where(array($v['related'].'_id'=>$v['related_id']))->find();
            $owner_role = getUserByRoleId($v[$v['related']]['owner_role_id']);
            if ($owner_role) {
                $v[$v['related']]['owner_role'] = $owner_role;
            }
            $coree = $v[$v['related']]['corre'];
            $corre_model = D("Manage/".ucfirst($coree).ucfirst($v['related'])."View")->where(array($v['related'].'_id'=>$v['related_id']))->find();
            if ($corre_model) {
                $v[$v['related']]['corre_info'] = "<a href='".U($coree.'/view', 'id='.$corre_model[$coree.'_id'])."' target='_blank'>[".$corre_model['idcode']."]".$corre_model[$coree.'_name']."</a>";
            }
        } else if ($v['related_id']) {
            $ra = M("account")->where(array('account_id'=>$v['related_id']))->find();
            if ($ra) {
                if ($ra['account_type'] == 'internal') {
                    $v['related_info'] = session('league_name');
                } else {
                    $rinfo = M($ra['account_type'])->cache(true)->where(array($ra['account_type'].'_id'=>$ra['clause_additive']))->find();
                    if ($rinfo) {
                        $v['related_info'] = "<a href='".U($ra['account_type'].'/view', 'id='.$ra['clause_additive'])."' target='_blank'>".$rinfo['idcode']."</a>";
                    }
                }
            }
        } else if ($v['related']) {
            $v['category_name'] = related_desc($v['related']);
            if ($v['clause_type_id'] == 86) {
                if (in_array($v['clause_additive'], array("product", "customer"))) {
                    $v['category_name'].= "-".self::account_module($v['clause_additive']);
                    if ($v['payway']) {
                        $payway_type = M("account_type")->cache(true)->where(array('type_id'=>$v['payway']))->find();
                        if ($payway_type) {
                            $v['category_name'].= "-".$payway_type['name'];
                        }
                    }
                } else {
                    if ($v['payway'] == "cost")$v['category_name'].= "-成本";
                    elseif($v['payway'] == "fare")$v['category_name'].= "-费用";
                }
            }
        }

        $v['iename'] = self::iedesc($v['income_or_expenses']);

        $v['state_export_tip'] = ($v['state'] == 0 ? "未完成" : "已完成");
        $v['state_tip'] = $v['state'] == 0 ? "<span class='label label-important'>".$v['state_export_tip']."</span>":$v['state_export_tip'];

        $dic = $v['income_or_expenses'];
        if (($dic == -3 || $dic == 3) || ($dic == -2 || $dic == 2)) {
            $dic *= -1;
        }
        $v['money_show'] =  number_format($dic / abs($dic) * $v['money'], 2);
        $v['payment_verify_show'] = payment_verify_map($v['payment_verify']);
        $v['verify_role_show'] = role_show($v['verify_role_id']);
        $v['verify_time_show'] = toDate($v['verify_time'], "Y-m-d H:i");
        $v['payment_time_show'] = toDate($v['payment_time'], "Y-m-d H:i");
        if ($v['branch_id'] == 0) {
            $v['branch_name'] = branch_show($v['branch_id']);
        }

        if (in_array($this->type ,array("market", "cultivate")) && $v['infow_account_id']) {
            $infow = M("account")->where("account_id=".$v['infow_account_id'])->find();
            if ($infow && $infow['infow_account_id']) {
                $infow = M("account")->where("account_id=".$infow['infow_account_id'])->find();
                if ($infow) {
                    $v['infow_flowid'] = $infow['flowid'];
                }
            }
        }

        return $v;
    }

    public function iedesc($ie) {
        $iearray = array("1"=>"收入", "-1"=>"支出", "3"=>"资金解冻", "-3"=>"资金冻结");
        return $iearray[$ie];
    }

    public function format_excel_fields($ex) {
        $field_list = array();
        if ($this->type == "market") {
            $field_list[]= array("name"=>"序号", "field"=>"BIAOGEHANGXUHAO");;
            $field_list[]= array("name"=>"支付日期", "field"=>"payment_time", "form_type"=>"datetime", "is_showtime"=>true);;
            $field_list[]= array("name"=>"提交日期", "field"=>"create_time", "form_type"=>"datetime", "is_showtime"=>true);;
            $field_list[]= array("name"=>"确认日期", "field"=>"verify_time", "form_type"=>"datetime", "is_showtime"=>true);;
            $field_list[]= array("name"=>"凭证号", "field"=>"receipt_number");;
            $field_list[]= array("name"=>"门店", "field"=>"branch_id");;
            $field_list[]= array("name"=>"收入", "field"=>"收入");;
            $field_list[]= array("name"=>"模块", "field"=>"客户服务");;
            $field_list[]= array("name"=>"服务类别", "field"=>"category_name");;
            $field_list[]= array("name"=>"相关方", "field"=>"market_infow");;
            $field_list[]= array("name"=>"描述", "field"=>"description");;
            $field_list[]= array("name"=>"收款方式", "field"=>"payway");;
            $field_list[]= array("name"=>"金额", "field"=>"money");;
            $field_list[]= array("name"=>"账户实收", "field"=>"BIAOGEHANGLIUKONG");;
            $field_list[]= array("name"=>"手续费", "field"=>"BIAOGEHANGLIUKONG");;
            $field_list[]= array("name"=>"系统流水号", "field"=>"XITONGFLOWLIUSHUIHAO", "form_type"=>"text");;
            $field_list[]= array("name"=>"订单编号", "field"=>"market_idcode");;
            $field_list[]= array("name"=>"创建人", "field"=>"creator_name");;
            $field_list[]= array("name"=>"审核状态", "field"=>"payment_verify_show");;
            $field_list[]= array("name"=>"备注", "field"=>"BIAOGEHANGLIUKONG");;

        } elseif($this->type == "cultivate") {
            $field_list[]= array("name"=>"序号", "field"=>"BIAOGEHANGXUHAO");;
            $field_list[]= array("name"=>"支付日期", "field"=>"payment_time", "form_type"=>"datetime", "is_showtime"=>true);;
            $field_list[]= array("name"=>"提交日期", "field"=>"create_time", "form_type"=>"datetime", "is_showtime"=>true);;
            $field_list[]= array("name"=>"确认日期", "field"=>"verify_time", "form_type"=>"datetime", "is_showtime"=>true);;
            $field_list[]= array("name"=>"凭证号", "field"=>"receipt_number");;
            $field_list[]= array("name"=>"门店", "field"=>"branch_id");;
            $field_list[]= array("name"=>"收入", "field"=>"收入");;
            $field_list[]= array("name"=>"模块", "field"=>"培训订单");;
            $field_list[]= array("name"=>"服务类别", "field"=>"category_name");;
            $field_list[]= array("name"=>"相关方", "field"=>"cultivate_infow");;
            $field_list[]= array("name"=>"描述", "field"=>"description");;
            $field_list[]= array("name"=>"收款方式", "field"=>"payway");;
            $field_list[]= array("name"=>"金额", "field"=>"money");;
            $field_list[]= array("name"=>"账户实收", "field"=>"BIAOGEHANGLIUKONG");;
            $field_list[]= array("name"=>"手续费", "field"=>"BIAOGEHANGLIUKONG");;
            $field_list[]= array("name"=>"系统流水号", "field"=>"XITONGFLOWLIUSHUIHAO", "form_type"=>"text");;
            $field_list[]= array("name"=>"订单编号", "field"=>"cultivate_idcode");;
            $field_list[]= array("name"=>"创建人", "field"=>"creator_name");;
            $field_list[]= array("name"=>"审核状态", "field"=>"payment_verify_show");;
            $field_list[]= array("name"=>"备注", "field"=>"BIAOGEHANGLIUKONG");;
        }else {
            $field_list = M('Fields')->cache(true)->where(array("model"=>"account"))->order('order_id')->select();
            $accounts_fields = array(348);
            if ($this->type == "product") {
                $accounts_fields[] = 337;
            } else if ($this->type == "customer") {
                $accounts_fields[] = 31;
            }
            foreach($accounts_fields as $v) {
                $field = M('Fields')->cache(true)->where(array("field_id"=>$v))->find();;
                if ($v == 348) {
                    $field['is_showtime'] = 1;
                }
                $field_list[]= $field;
            }

            $cur_fields["操作者"] = "creator_name";
            if ($this->type != "flow") {
                $cur_fields["培训|业务|产品"] = "related_model";
            } else {
                $cur_fields["状态"] = "state_export_tip";
            }
            $cur_fields["相关方"]= "infow";
            $cur_fields["收支/冻结"]= "iename";
            foreach($cur_fields as $k=>$v) {
                $field_list[]= array("name"=>$k, "field"=>$v);;
            }
        }

        return $field_list;
    }

    public function show_account($where = array()){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (session('user_id') == 1) {
            if ($_REQUEST['bylea']) {
                $where['league_id'] = $_REQUEST['bylea'];
                $params[] = "bylea=".trim($_GET['bylea']);
                $this->league = M("league")->where(array('league_id'=> $where['league_id']))->find();
            }
        } else {
            $where['league_id'] = session('league_id');
        }

        $request_url = $_SERVER['REQUEST_URI'];
        if (strpos($request_url, "excel") == false) {
            session("index_refer_url", $request_url);
        }
        $params = array();
        if ($_REQUEST["field"]) {
            $params[] = "field=".$_REQUEST['field'];
            $this->search_field = $_REQUEST["field"];
            $this->debar_search_field = array($_REQUEST["field"]."[value]", $_REQUEST["field"]."[condition]", "bytime", "bysub");
            $this->search_condition = $_REQUEST[$_REQUEST["field"]]['condition'];
            $this->search_value = $_REQUEST[$_REQUEST["field"]]['value'];
        }
        $where = self::enum_field_where($_GET, $where, "account", $params);
        unset($where['account.money'],
            $where['account.start_time'],
            $where['account.end_time'],
            $where['account.inc'],
            $where['account.related'],
            $where['account.cont'],
            $where['account.payway'],
            $where['account.fil'],
            $where['account.type'],
            $where['account.inflow'],
            $where['account.pvs'],
            $where['account.exctype'],
            $where['account.inflow_model'],
            $where['account.cont'],
            $where['account.dire'],
            $where['account.bytime']
        );

        if (isset($_GET['money'])) {
            $where['money'] = like_filed_where($_GET['money']['value'],  $_GET['money']['condition']);
            $params[] = "money[value]=".$_GET['money']['value'];
            $params[] = "money[condition]=".$_GET['money']['condition'];
        }

        if ($_GET['lia'] == 'self') {
            $where['creator_role_id'] = session('role_id');
        } else{
            if (!session('?admin') && vali_permission("branchlock", "account") && session('restriction') === true) {
                $map['account.creator_role_id'] = array("in", self::make_branch_role_where(session("role_id")));
                $where['_complex'] = $map;
            }
        }

        if (in_array($this->type,array("market", "cultivate"))) {
            $this->per_export = vali_permission("account", "export", $this->type);
            $this->per_owner = vali_permission("account", $this->type, $this->type."/owner");
            if (!session('?admin') && $this->per_owner) {
                $branch = get_branch(session("role_id"));
                $where['creator_role_id'] = array("in", $branch);
            }

            if (isset($_GET['idcode'])) {
                $where[$this->type.'.idcode'] = like_filed_where($_GET['idcode']['value'],  $_GET['idcode']['condition']);
                $params[] = "idcode[value]=".$_GET['idcode']['value'];
                $params[] = "idcode[condition]=".$_GET['idcode']['condition'];
            }

            if (isset($_GET['related_owner_role_id'])) {
                $where['related_owner_role_id'] = like_filed_where($_GET['related_owner_role_id']['value'],  $_GET['related_owner_role_id']['condition']);
                $params[] = "related_owner_role_id[value]=".$_GET['related_owner_role_id']['value'];
                $params[] = "related_owner_role_id[condition]=".$_GET['related_owner_role_id']['condition'];
            }

            if (isset($_GET['verify_time'])) {
                $where[$this->type.'_account.verify_time'] = like_datetime_field_where($_GET['verify_time']['value'],  $_GET['verify_time']['condition']);
                $params[] = "verify_time[value]=".$_GET['verify_time']['value'];
                $params[] = "verify_time[condition]=".$_GET['verify_time']['condition'];
            }
        }

        if (isset($_GET['receipt_number'])) {
            $where['receipt_number'] = like_filed_where($_GET['receipt_number']['value'],  $_GET['receipt_number']['condition']);
            $params[] = "receipt_number[value]=".$_GET['receipt_number']['value'];
            $params[] = "receipt_number[condition]=".$_GET['receipt_number']['condition'];
        }

        $where['account.account_type'] = array('eq',$this->type);

        if ($_GET['dire']) {
            $params[] = 'dire=' . $_GET['dire'];
            $inoutdire = explode(",", $_GET['dire']);
            $this->assign('dire',$this->_request('dire'));
        } else {
            $inoutdire = array(1, -1);
        }
        $where['account.income_or_expenses'] = array('in',$inoutdire);

        if ($_GET['lia']) {
            $params[] = "lia=" . $_GET['lia'];
            switch($_GET['lia']) {
                case "self": {
                    $where['creator_role_id'] = session('role_id');
                    break;
                }
                case "foll": {
                    $where['creator_role_id'] = array("in", get_branch(session("role_id")));
                    break;
                }
            }
        }

        if (isset($_GET['state'])) {
            $where['account.state'] = $_GET['state'];
            $params[] = 'state=' . $_GET['state'];
        }

        if (isset($_GET['inflow_model'])) {
            $where['account_type.inflow_model'] = $_GET['inflow_model'];
            $params[] = 'inflow_model=' . $_GET['inflow_model'];
        }

        if (isset($_GET['payway'])) {
            $payways = array($_GET['payway']);
            if ($_GET['payway'] == "微信"){
                $payways[] = "wxpay";
            }
            $where['account.payway'] = array("in", $payways);
            $params[] = 'payway=' . $_GET['payway'];
        }

        if ($_GET['by']) {
            $by = trim($_GET['by']);
            switch ($by) {
                case 'type': {
                    if ($_REQUEST['type']) {
                        $atype = $this->_request("type");
                        if ($atype == 'nobusi')    {
                            $where['account_type.type_id'] = array('not in', array(33, 38));
                        } else {
                            if ($this->_request("cont") == "exc") {
                                $where['account_type.type_id'] =  array('not in', explode(',', $atype));
                            } else {
                                $where['account_type.type_id'] =  array('in', explode(',', $atype));
                            }
                        }
                        $params[] = 'type=' . $atype;
                    }
                    break;
                }
                case 'related': {
                    if ($_REQUEST['related']) {
                        $related = $this->_request("related");
                        if ($this->_request("cont") == "exc") {
                            $where['account_type.related_model'] =  array('not in', explode(',', $related));
                        } else {
                            $where['account_type.related_model'] = array('in', explode(',', $related));
                        }
                        $params[] = 'related=' . $_REQUEST['related'];

                        if ($_REQUEST['exctype']) {
                            $where['account_type.type_id'] =  array('not in', explode(',', $_REQUEST['exctype']));
                            $params[] = 'exctype=' . $_REQUEST['exctype'];
                        }
                    }
                    break;
                }

                case 'inflow': {
                    if ($_REQUEST['inflow']) {
                        $where['account_type.inflow_model'] = $this->_request("inflow");;
                        $params[] = 'inflow=' . $_REQUEST['inflow'];

                        if ($_REQUEST['exctype']) {
                            $where['account_type.type_id'] =  array('not in', explode(',', $_REQUEST['exctype']));
                            $params[] = 'exctype=' . $_REQUEST['exctype'];
                        }
                    }
                    break;
                }
            }
            $this->assign('by',$by);
            $params[] = 'by=' . $by;
        }

        if ($_GET['bytime']) {
            $bytime = trim($_GET['bytime']);
            switch ($bytime) {
                case 'today' :
                    $_GET['start_time'] = date('Y-m-d');
                    $_GET['end_time'] = date('Y-m-d');
                    break;
                case 'month' :
                    $_GET['start_time'] = date('Y-m-01');
                    $_GET['end_time'] = date('Y-m-d', strtotime(date('Y-m-00', strtotime('+1 month'))));
                    break;
                case 'year' :
                    $_GET['start_time'] = date('Y-01-01');
                    $_GET['end_time'] = date('Y-m-d', strtotime(date('Y-01-00', strtotime('+1 year'))));
                    break;
            }
            $this->assign('bytime',$bytime);
            $params[] = 'bytime=' . $bytime;
        }

        if ($_GET['start_time']) {
            $start_time = strtotime($_GET['start_time']);
            $params[] = 'start_time=' . $_GET['start_time'];
        } elseif ($_GET['end_time']) {
            $start_time = 0;
        }
        if ($_GET['end_time']) {
            $end_time = $_GET['end_time'] ?  strtotime(date("Y-m-d 23:59:59", strtotime($_GET['end_time']))) : PHP_INT_MAX;
            $params[] = 'end_time=' . $_GET['end_time'];
        } elseif ($_GET['start_time']) {
            $end_time = PHP_INT_MAX;
        }
        if ($_GET['start_time'] || $_GET['end_time']) {
            if($_GET['bysub']) {
                $params[] = 'bysub=' . $_GET['bysub'];
            }
            switch($_GET['bysub']) {
                case "c": {
                    $where[$this->type.'_account.verify_time'] =  array('between', array($start_time,$end_time));
                    break;
                }
                case "p":{
                    $where[$this->type.'_account.payment_time'] =  array('between', array($start_time,$end_time));
                    break;
                }

                default: {
                    $where['account.create_time'] =  array('between', array($start_time,$end_time));
                    break;
                }
            }
        }

        if($_GET['fil']) {
            $params[] = "fil=" . trim($_GET['fil']);
        }
        if($_GET['cont']) {
            $params[] = "cont=" . trim($_GET['cont']);
        }

        if ($this->type == "inernal") {
            $inernal_cash_type = array();
            $inernal_cash_heji = array();
            foreach(array("trade") as $m) {
                $where2 = array('related_model'=>$m, 'inflow_model_type_id'=>-2, 'module_id'=>"inernal");
                $account_type_heji = array();
                $account_type_relatied = M("account_type")->where($where2)->order("order_id asc")->select();;
                foreach($account_type_relatied as $ak=>$av){
                    $account_type_heji[] = $av['type_id'];
                }
                $inernal_cash_type[$m] =$account_type_relatied;
                $inernal_cash_heji[$m] = implode(",", $account_type_heji);
            }
            $this->cash_type = $inernal_cash_type;
            $this->cash_heji = $inernal_cash_heji;
        }else if ($this->type == "market") {
            if(isset($_GET['pvs'])) {
                $params[] = "pvs=" . trim($_GET['pvs']);
                $where['market_account.payment_verify'] = array("in", $_GET['pvs']);
            }
            if(isset($_GET['catid'])) {
                $params[] = "catid=" . trim($_GET['catid']);
                $where['product_category.category_id'] = array("in", $_GET['catid']);
            }
            $this->category_list = M('product_category')->cache(true)->where(array("enable"=>1, "league_id"=>session('league_id')))->select();
            if (!$where['account.payway']) {
                $where['account.payway'] =  array('not in', array("余额冻结", "解冻"));
            }
            $where['account.clause_type_id'] =  array('not in', array("235"));
        }else if ($this->type == "cultivate") {
            if(isset($_GET['pvs'])) {
                $params[] = "pvs=" . trim($_GET['pvs']);
                $where['cultivate_account.payment_verify'] = array("in", $_GET['pvs']);
            }
            if(isset($_GET['catid'])) {
                $params[] = "catid=" . trim($_GET['catid']);
                $where['currier_category.currier_category_id'] = array("in", $_GET['catid']);
            }
            $this->category_list = M('currier_category')->where(array("league_id"=>session('league_id')))->cache(true)->select();
            if (!$where['account.payway']) {
                $where['account.payway'] =  array('not in', array("余额冻结", "解冻"));
            }
            $where['account.clause_type_id'] =  array('not in', array("260", "283"));
        }
        $this->payway_list = payway_list();

        $branch_id = $_GET['bybr'] != "" ? $_GET['bybr']:"";
        if ($branch_id != "") {
            if (in_array($this->type, array("market", "cultivate"))) {
                $where[$this->type.'.branch_id'] = $branch_id;
            } else {
                $role_ids = get_branch_all_role($branch_id);
                $where['account.related_owner_role_id'] = array($branch_id == 0?"not in":"in", $role_ids);
            }
            $params[] = "bybr=" . trim($_GET['bybr']);
            $this->branch =  $branch_id;
        }

        $order = $this->make_list_order($params);

        $params[] = 't=' . $this->type;
        $p = isset($_GET['p']) ? intval($_GET['p']) : 1 ;

        $this->parameter = implode('&', $params);

        $account = D(ucfirst($this->type).'AccountView');
        $count = $account->where($where)->count();

        if(trim($_GET['export']) == 'excel'){
            if ($p > 0) {
                $list = $account->order($order)->where($where)->page(max($p, 1) . ',2000')->select();
                foreach ($list as $k => $v) {
                    $list[$k] = $this->format_account_info($v, true);
                }
                $this->excelExport($list, "账目表");
            } else {
                self::show_export_dialog($count);
            }
            exit(0);
        } else if ($count) {
            if ($_GET["pl"]) {
                $page_limit = $_GET["pl"];
                $params[] = "pl=".$page_limit;
            } else {
                $page_limit = 15;
            }
            $page = self::assign_list_page($this->parameter, $count, $page_limit);

            $shouru_money = 0; $zhichu_money = 0;
            $list = $account->order($order)->where($where)->page($page->nowPage, $page->listRows)->select();

            foreach($list as $k=>$v){
                $dic = $v['income_or_expenses'];
                if ($dic == -3 || $dic == 3) {
                    $dic *= -1;
                }
                if ($dic > 0) {
                    $shouru_money += $v['money'];
                } else {
                    $zhichu_money += $v['money'];
                }
                $list[$k] = $this->format_account_info($v);
            }
            self::assign_total_accounts($inoutdire, $where, $shouru_money, $zhichu_money);
        }

        $this->shouru_type = M("account_type")->cache(true)->where(array('module_id'=>$this->type, 'mold'=>1))->select();
        $this->zhichu_type = M("account_type")->cache(true)->where(array('module_id'=>$this->type, 'mold'=>-1))->select();
        $this->field_list = getMainFields('account', $this->type != "flow" ? "payway" : null);
        $this->field_array = getIndexFields('account', $this->type != "flow" ? "payway" : null);
        $this->alert = parseAlert();

        $this->assign('list',$list);
        $this->display($this->type."index");
    }

    public function make_list_order(&$params) {
        $order = "create_time desc";
        if($_GET['desc_order']){
            $order = ($_GET['desc_order'] == "money" ? "account.money*income_or_expenses" : $_GET['desc_order']).' desc';
            $params[] = "desc_order=" . trim($_GET['desc_order']);
        }elseif($_GET['asc_order']){
            $order = ($_GET['asc_order'] == "money" ? "account.money*income_or_expenses" : $_GET['asc_order']).' asc';
            $params[] = "asc_order=" . trim($_GET['asc_order']);
        }
        return $order;
    }

    public function assign_total_accounts($inoutdire, $sum_where,$page_shouru_money, $page_zhichu_money) {
        $account = D(ucfirst($this->type).'AccountView');
        $sum_where['league_id'] = session('league_id');

        $inarray = array("收入"=>1, "解冻"=>3);
        if ($dire = array_exist($inarray, $inoutdire)) {
            $sum_where['income_or_expenses'] = $dire;
            $shouru_sum_money = $account->where($sum_where)->sum('account.money');
            $this->assign('shouru_sum_money',$shouru_sum_money);
            $this->assign('shouru_money',$page_shouru_money);
            $this->assign('shouru_tip',array_search($dire, $inarray));
        }

        $outarray = array("支出"=>-1,"冻结"=>-3);
        if ($dire = array_exist($outarray, $inoutdire)) {
            $sum_where['income_or_expenses'] = $dire;
            $zhichu_sum_money = $account->where($sum_where)->sum('account.money');
            $this->assign('zhichu_sum_money',$zhichu_sum_money);
            $this->assign('zhichu_money',$page_zhichu_money);
            $this->assign('zhichu_tip',array_search($dire, $outarray));
        }

        if (count($inoutdire) == 2) {
            if (array_exist(array( 3, -3), $inoutdire)) {
                $this->assign('balance',$zhichu_sum_money - $shouru_sum_money);
            } else {
                $this->assign('balance',$shouru_sum_money - $zhichu_sum_money);
            }
        }
    }


    public function index() {
        $this->show_account();
    }

    public function market() {
        $this->show_account();
    }

    public function inernal() {
        $this->show_account();
    }

    public function flow() {
        $this->show_account();
    }

    public function customer() {
        $this->show_account();
    }

    public function product() {
        $this->show_account();
    }

    public function staff() {
        $this->show_account();
    }

    public function cultivate() {
        $this->show_account();
    }

    public function update_param_check($clause_additive) {
        $money = trim($this->_request("money"));
        if(empty($money) || $money <= 0){
            $this->alert = parseAlert();
            alert('error','请填写正确的正数金额数字',$_SERVER['HTTP_REFERER']);
        }

        $clause_type_id = $_POST["clause_type_id"];
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$clause_type_id))->find();
        if (!$account_type) {
            $this->alert = parseAlert();
            alert('error','内部错误',$_SERVER['HTTP_REFERER']);
        }

        if ($this->type == "flow" || $this->type == 'inernal') {
            return $account_type;
        }

        $mc = M(ucfirst($this->type))->where($this->type.'_id = %d',$clause_additive)->find();
        if (!$mc) {
            $this->alert = parseAlert();
            alert('error','内部错误',$_SERVER['HTTP_REFERER']);
        }

        $typearray = array("支出"=>-1, "冻结"=>-3);
        if ($typetip = array_search($account_type['mold'], $typearray))  {
            if ($mc['actual'] < $money) {
                $param = array(
                    "dire"=>1,
                    "t"=>$this->type,
                    "type_id"=>25,
                    "clause_additive"=>$clause_additive
                );
                $tips = "账户可".$typetip."金额不足，"."立即[<a href='".U("account/add",$param)."' target='_blank'>充值</a>]";
                $this->alert = parseAlert();
                alert('error',$tips,$_SERVER['HTTP_REFERER']);
            }
        }

        if ($account_type['type_id'] == 18 || $account_type['type_id'] == 32)  {
            if ($mc['actual'] < $money) {
                $this->alert = parseAlert();
                alert('error',"账户没有足够的可提现金额",$_SERVER['HTTP_REFERER']);
            }
        } elseif ($account_type['mold'] > 1) {
            if ($account_type["inflow_model_type_id"]) {
                $balance_money = total_account_type_balance($account_type, $clause_additive);
            } else {
                $balance_money = $mc['freeze'];
            }
            if ($_POST["money"] > abs($balance_money)) {
                $this->alert = parseAlert();
                alert('error','无法'.($account_type['mold'] == '2' ? "偿还":"解冻").'此数额的账单',$_SERVER['HTTP_REFERER']);
            }
        }
        return $account_type;
    }

    public function add_infow_account($account_id, $account_type) {
        if ($account_type['related_model'] == 'trade') {
            $inmodel = $account_type['inflow_model'];
            $trade_id = $_POST["trade_id"];
            if ($inmodel != "inernal") {
                $trade = D(ucfirst($inmodel).'TradeView')->where(array('trade_id'=>$trade_id))->find();
                $clause_additive = $trade[$inmodel."_id"];
            }else {
                $clause_additive = "inernal";
            }
        } else if ($account_type['inflow_model']) {
            if ($account_type['inflow_model']  != "inernal") {
                $clause_additive = $_POST[$account_type['inflow_model']."_id"];
            } else {
                $clause_additive = "inernal";
            }
        }

        $info_account_id = 0;
        if ($clause_additive) {
            $_POST['income_or_expenses'] = -$account_type['mold'];
            self::reset_flow_post_data($account_id, $clause_additive, $account_type);

            $inflow_account_type = M('AccountType')->cache(true)->where(array('type_id'=>$account_type['inflow_model_type_id']))->find();
            $info_account_id = $this->insert_account($inflow_account_type);
            if ($info_account_id != false) {
                self::update_account_money($clause_additive, $inflow_account_type['module_id']);
            }
            $this->update_keyword($info_account_id, $inflow_account_type);
        }
        return $info_account_id;
    }

    public static function reset_flow_post_data($account_id, $clause_additive, $account_type) {
        $_POST['infow_account_id'] = $account_id;
        $_POST['clause_additive'] = $clause_additive;
        $_POST['account_type'] = $account_type['inflow_model'];
        $_POST['clause_type_id'] = $account_type['inflow_model_type_id'];
    }

    public static function reset_post_data($clause_additive, $account_type) {
        $_POST['income_or_expenses'] = $account_type['mold'];
        $_POST['clause_additive'] = $clause_additive;
        $_POST['account_type'] = $account_type['module_id'];
        $_POST['clause_type_id'] = $account_type['type_id'];
    }

    public function update_model_loans_account($model, $model_id) {
        $where = array(
            "related"=>$model,
            "related_id"=>$model_id,
            "income_or_expenses"=>array("in", array(2, -2))
        );
        $where['league_id'] = session('league_id');

        $money = M("account")->where($where)->sum('(money*income_or_expenses) / -2');
        M($model)->where(array($model."_id"=>$model_id))->setField("loans", $money ? $money : 0);
    }

    public function add_model_account_log($account_id, $account_type, $money, $module, $module_id) {
        if (in_array($account_type['mold'], array(1, -1)) && in_array($account_type['module_id'], array("product", "customer", "staff"))) {
            $logtip = $this->format_update_log_info($account_id, $account_type, $money);
            $this->accouont_log($account_id, $module, $module_id, $account_type['name'], $logtip);
        }
    }

    public static function account_module($module_id) {
        switch($module_id) {
            case "product":$mname = "雇员";break;;
            case "customer":$mname = "客户";break;;
            case "staff":$mname = "员工";break;;
            case "inernal":$mname = "公司";break;;
            case "flow":$mname = "公司";break;;
        }
        return $mname;
    }

    public static  function format_update_log_info($account_id, $account_type, $money) {
        $mname = self::account_module($account_type['module_id']);
        $mold_desc = acction_type_desc($account_type['mold']);
        $param = array(
            "id"=>$account_id,
            "t"=>$account_type['module_id'],
        );
        return $mname.$mold_desc.$account_type['name']."金额为" . $money ."元, <a target='_blank' href='".U("Account/view", $param)."'>查看</a>账目信息 =>> ". $account_id;
    }

    public function format_log_info($account_id) {
        $m_account = M("account")->where("account_id=".$account_id)->find();
        if (!$m_account) {
            return "";
        }

        $account_type = M("account_type")->cache(true)->where("type_id=".$m_account['clause_type_id'])->find();
        $mname = self::account_module($account_type['module_id']);
        $mold_desc = acction_type_desc($account_type['mold']);
        $param = array(
            "id"=>$account_id,
            "t"=>$this->type,
        );
        $content = $mname.$mold_desc.$account_type['name'];

        $m_account = $this->format_account_info($m_account);
        $corre_info = $m_account[$m_account['related_model']]['corre_info'];
        if ($corre_info) {
            $content .= $corre_info;
        }else if ($m_account['related'] && $m_account['related_id']) {
            $content .= $m_account[$m_account['related']]['corre_info'];
        }
        return $content .".<a target='_blank' href='".U("Account/view", $param)."'>查看</a>账目信息";
    }

    public function insert_related_account($account_id, $account_type) {
        if ($account_type['related_model']) {
            $related = $account_type['related_model'];
        } else if ($_POST['related']) {
            $related = $_POST['related'];
        }
        $related = ($related == "business_flow" ? "business" : $related);
        $related_id = $_POST[$related."_id"];
        if (!$related || !$related_id) {
            return;
        }

        $data = array(
            "related"=>$related,
            "related_id"=>$related_id,
            "related_owner_role_id"=>$_POST['related_owner_role_id'],
        );
        M("account")->where(array("account_id"=>$account_id))->setField($data);

        if ($account_type['related_model']) {
            $data = array(
                'account_id'=>$account_id,
                $related.'_id'=>$related_id
            );
            if ($related == "market") {
                $data['payment_time'] = $_POST['payment_time'] ? strtotime($_POST['payment_time']) : 0;
                $data['receipt_number'] = $_POST['receipt_number'] ? $_POST['receipt_number'] : "";
                $data['market_product_id'] = $_POST['market_product_id'] ? $_POST['market_product_id'] : "";
            } elseif ($related == "cultivate") {
                $data['payment_time'] = $_POST['payment_time'] ? strtotime($_POST['payment_time']) : 0;
                $data['receipt_number'] = $_POST['receipt_number'] ? $_POST['receipt_number'] : "";
            }
            M($related."_account")->add($data);
        }

        if ($account_type['module_id'] != "inernal") {
            self::add_model_account_log($account_id, $account_type, $_POST["money"], $related, $related_id);
        }

        self::calculate_module_account($related, $related_id, $account_type['module_id']);
        self::update_model_loans_account($related, $related_id);
    }

    public function calculate_module_account($related, $related_id, $module_id) {
        switch($related) {
            case "trade": {
                self::calculate_trade_account($related_id, $module_id);
                break;
            }
            case "market": {
                MarketAction::calculate_market_account($related_id);
                break;
            }
            case "cultivate": {
                CultivateAction::calculate_cultivate_account($related_id);
                break;
            }
        }
    }

    public function rehab() {
        $this->display("rehab");
    }

    public function reset_keyword() {
        $where = array();
        $id = $_GET['id'];
        if ($id) {
            $where['account_id']=$id;
        }
        $p = $_GET['p'] ? $_GET['p'] : 0;
        $limit = $_GET['limit'] ? $_GET['limit'] : 1000;
        $sum_cnt = M("account")->where($where)->count();
        $list = M("account")->order("account_id desc")->where($where)->limit($p, $limit)->select();
        foreach($list as $v) {
            $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$v['clause_type_id']))->find();
            $this->update_keyword($v['account_id'], $account_type);
        }
        $this->ajaxReturn(array("sum"=>$sum_cnt, "limit"=>$limit, "p"=>$p, "lcnt"=>count($list)));
    }


    public function reset_release() {
        $p = $_GET['p'] ? $_GET['p'] : 0;
        $limit = $_GET['limit'] ? $_GET['limit'] : 5000;
        $list = M("account")->limit($p, $limit)->select();
        $sum_cnt = M("account")->count();

        foreach($list as $v) {
            if ($v['related'] == 'trade') {
                $v['trade'] = D("Manage/TradeAccountView")->where(array('account_id'=>$v['account_id']))->find();
                M("account")->where("account_id=".$v['account_id'])->setField("related_owner_role_id", $v['trade']['owner_role_id']);

            } else if ($v['related'] == 'market') {
                if ($v['account_type'] != "market") {
                    $v['market'] = D("Manage/MarketRelatedAccountView")->where(array('account_id'=>$v['account_id']))->find();
                } else {
                    $v['market'] = D("Manage/MarketAccountView")->where(array('account_id'=>$v['account_id']))->find();
                }
                M("account")->where("account_id=".$v['account_id'])->setField("related_owner_role_id", $v['market']['owner_role_id']);


            }
        }
        $this->ajaxReturn(array("sum"=>$sum_cnt, "limit"=>$limit, "p"=>$p, "lcnt"=>count($list)));

    }

    public function update_keyword($account_id, $account_type, $keyword = array()) {
        $account = M('account')->where("account_id=".$account_id)->find();
        $keyword[] = $account_type['name'];
        $keyword[] = self::iedesc($account['income_or_expenses']);

        if ($account_type['module_id'] == "customer") {
            $customer = M("customer")->where(array("customer_id"=>$account['clause_additive']))->find();
            if ($customer) {
                $keyword[] = $customer['idcode'];
                $keyword[] = $customer['name'];
            }
        } else if ($account_type['module_id'] == "product") {
            $product = D("Manage/ProductView")->where(array("product_id"=>$account['clause_additive']))->find();
            if ($product) {
                $keyword[] = $product['idcode'];
                $keyword[] = $product['name'];
            }
        } else if ($account_type['module_id'] == "staff") {
            $staff = D("Manage/StaffView")->where(array("staff_id"=>$account['clause_additive']))->find();
            if ($staff) {
                $keyword[] = $staff['idcode'];
                $keyword[] = $staff['name'];
            }
        } else if ($account_type['module_id'] == "inernal") {
            $keyword[] =session('league_name');
        }

        if ($account['related'] == "trade" || $account_type['related_model'] == "serve" || $account_type['related_model'] == "trade") {
            if ($account['related'] == "trade") {
                $trade = D("Manage/TradeView")->where(array("trade_id"=>$account['related_id']))->find();
            } else {
                $trade = D("Manage/TradeAccountView")->where(array("account.account_id"=>$account_id))->find();
            }
            if ($trade) {
                $keyword[] = $trade['orderid'];
                $keyword[] = $trade['category_name'];
                $keyword[] = $trade['serve_name'];
                $keyword[] = $trade['serve_idcode'];
            }
            $role = getUserByRoleId($trade['owner_role_id']);
            if ($role) {
                $keyword[] = $role['user_name'];
            }
        }else if ($account['related'] == "market") {
            $market = D("Manage/MarketAccountView")->where(array("account.account_id"=>$account_id))->find();
            if ($market) {
                $keyword[] = $market['market_idcode'];
                $keyword[] = $market['category_name'];
                if ($market['receipt_number']) {
                    $keyword[] = $market['receipt_number'];
                }
                $role = getUserByRoleId($market['owner_role_id']);
                if ($role) {
                    $keyword[] = $role['user_name'];
                }
                $role = getUserByRoleId($market['verify_role_id']);
                if ($role) {
                    $keyword[] = $role['user_name'];
                    $keyword[] = $role['email'];
                }
                $keyword[] = $market['customer_name'];
                $keyword[] = $market['customer_telephone'];
                $keyword[] = $market['idcode'];
                $keyword[] = $market['market_idcode'];
                $keyword[] = $market['branch_name'];
            }
            $role = getUserByRoleId($market['creator_role_id']);
            if ($role) {
                $keyword[] = $role['user_name'];
            }
        }else if ($account['related'] == "cultivate") {
            $market = D("Manage/CultivateAccountView")->where(array("account.account_id"=>$account_id))->find();
            if ($market) {
                $keyword[] = $market['cultivate_idcode'];
                if ($market['receipt_number']) {
                    $keyword[] = $market['receipt_number'];
                }
                $role = getUserByRoleId($market['owner_role_id']);
                if ($role) {
                    $keyword[] = $role['user_name'];

                }
                $role = getUserByRoleId($market['verify_role_id']);
                if ($role) {
                    $keyword[] = $role['user_name'];

                }
                $keyword[] = $market['idcode'];
                $keyword[] = $market['cultivate_idcode'];
                $keyword[] = $market['branch_name'];
            }
            $role = getUserByRoleId($market['creator_role_id']);
            if ($role) {
                $keyword[] = $role['user_name'];

            }
        }
        $keyword[] = related_desc($account['related']);
        if ($account['receipt_number']) {
            $keyword[] = $account['receipt_number'];
        }

        M("account")->where(array("account_id"=>$account_id))->setField("keyword", implode(chr(10), array_unique($keyword)));
    }

    public function insert_account($account_type) {
        $account = M('account');
        if ($account->create() !== false) {
            $account->name = $account_type['name'];
            $role_id = session('role_id');
            $account->creator_role_id = $role_id ? $role_id : 0;
            $account->related = $account_type['related_model'] ? $account_type['related_model'] : ($_POST['related'] ? $_POST['related'] : "");
            $account->update_time = $create_time = time();
            $account->create_time =$create_time;
            $account->league_id =session('league_id');

            if($account_id = $account->add()){
                $data = array(
                    'flowid'=>sprintf("%s%07d", date("YmdHi"), $account_id),
                );
                $account->where(array('account_id'=>$account_id))->setField($data);
                $this->upadate_log("添加账目", $account_id, $account_type, $_POST["money"]);
                $this->insert_related_account($account_id, $account_type);
            }
        }
        return $account_id;
    }

    public function add_flow_account($account_id, $account_type, $clause_additive) {
        $_POST['state'] = 0;
        $_POST['income_or_expenses'] = $account_type['mold'];
        self::reset_flow_post_data($account_id, $clause_additive, $account_type);
        $account_id = $this->insert_account($account_type);
        $this->update_keyword($account_id, $account_type);
        return $account_id;
    }

    public static function set_post_account($money, $clause_additive, $account_type) {
        $_POST['money'] = $money;
        self::reset_post_data($clause_additive, $account_type);
    }

    public function cashrequest($model, $model_id, $cash_id,  $cashinfo) {
        $ctypeid = $model == "product" ? 18 : 32;
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$ctypeid))->find();
        if (!$account_type) {
            return array("error"=>"内部错误", "e_status"=>"INSIDE_ERROR");
        }
        self::set_post_account($cashinfo['pdc_amount'], $model_id, $account_type);

        $account_id = $this->update_account($model_id, $account_type);
        if (!$account_id) {
            return array("error"=>$account_type['description']."失败", "e_status"=>"INSIDE_ERROR");
        }
        $data = array(
            "description"=>"预存款提现,交易单号为: ".$cashinfo['pdc_sn']
        );
        M("account")->where("account_id=".$account_id)->setField($data);

        $cash_account = M("account")->where("account_id=".$account_id)->find();
        $description = "收款银行: ". payway_blank_name($cashinfo['pdc_bank_name'])."<br/>";
        $description .= "收款账号: ". $cashinfo['pdc_bank_no']."<br/>";
        $description .= "开户人姓名: ". $cashinfo['pdc_bank_user']."<br/>";

        $keyword = explode(chr(10), $cash_account['keyword']);
        $keyword[] = $cashinfo['pdc_sn'];
        $keyword[] = $cashinfo['pdc_bank_no'];
        $keyword[] = $cashinfo['pdc_bank_user'];

        $data = array(
            "state"=>"0",
            "payway"=>payway_name($cashinfo['pdc_bank_name']),
            "related"=>"cash",
            "related_id"=>$cash_id,
            "keyword"=>implode(chr(10), $keyword),
            "description"=>$description
        );
        return M("account")->where("account_id=".$cash_account['infow_account_id'])->setField($data) !== false;
    }


    public static function format_market_enough_tip($mold, $module, $market) {
        $param = array(
            "dire"=>1,
            "t"=>$module,
            "type_id"=>$module == "customer" ? 31 : 5,
            "clause_additive"=>$market['customer_id']
        );
        return "账户可".($mold == '-3' ? "冻结":"支取")."金额不足，"."立即[<a href='".U("account/add",$param)."' target='_blank'>充值</a>]";
    }

    public function pay_model_cost($money, $model_name, $model, $param) {
        if ($money == 0) {
            return true;
        }
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>86))->find();
        if (!$account_type) {
            return array("error"=>"内部错误", "e_status"=>"INSIDE_ERROR");
        }

        $description = "";
        $_POST['related'] = $model_name;
        $_POST[$model_name.'_id'] = $model[$model_name.'_id'];
        $_POST['related_owner_role_id'] = $model['owner_role_id'];
        foreach($param as $k=>$v) {
            $description .= $k . ": ". $v . "\r\n";
        }
        $_POST['description'] = $description;

        $clause_additive = "";
        self::set_post_account($money, $clause_additive, $account_type);

        if (!$this->update_account($clause_additive, $account_type, false)) {
            return array("error"=>$account_type['description']."失败", "e_status"=>"INSIDE_ERROR");
        }
        return true;
    }

    public function customer_pay_market_salary($money, $market, $product_id, $ctypeid, $job_number, $market_product_id) {
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$ctypeid))->find();
        if (!$account_type) {
            return array("error"=>"内部错误", "e_status"=>"INSIDE_ERROR");
        }
        $customer = M("customer")->where(array('customer_id'=>$market['customer_id']))->find();
        if ($account_type['mold'] < 0 && $customer['actual'] < $money) {
            return array("error"=>self::format_market_enough_tip($account_type['mold'], "customer", $market),"e_status"=>"NO_MONEY");
        }
        unset($_POST);
        $_POST['market_id'] = $market['market_id'];
        $_POST['product_id'] = $product_id;
        $_POST['related_owner_role_id'] = $market['owner_role_id'];
        if ($job_number) {
            $_POST['description'] = "派工单: " .$job_number;
        }
        if ($market_product_id) {
            $_POST['market_product_id'] = $market_product_id;
        }
        self::set_post_account($money, $customer['customer_id'], $account_type);

        $account_id = $this->update_account($customer['customer_id'], $account_type, false);
        if (!$account_id) {
            return array("error"=>$account_type['description']."失败", "e_status"=>"INSIDE_ERROR");
        }
        return $account_id;
    }

    public function customer_pay_market_agency($money, $market, $ctypeid) {
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$ctypeid))->find();
        if (!$account_type) {
            return array("error"=>"内部错误", "e_status"=>"INSIDE_ERROR");
        }
        $customer = M("customer")->where(array('customer_id'=>$market['customer_id']))->find();
        if ($account_type['mold'] < 0 && $customer['actual'] < $money) {
            return array("error"=>self::format_market_enough_tip($account_type['mold'], "customer", $market),"e_status"=>"NO_MONEY");
        }
        unset($_POST);
        $_POST['market_id'] = $market['market_id'];
        $_POST['related_owner_role_id'] = $market['owner_role_id'];
        self::set_post_account($money, $customer['customer_id'], $account_type);

        $account_id = $this->update_account($customer['customer_id'], $account_type, false);
        if (!$account_id) {
            return array("error"=>$account_type['description']."失败", "e_status"=>"INSIDE_ERROR");
        }
        return $account_id;
    }

    public function pay_market_account($model, $model_id, $money, $market, $ctypeid) {
        if ($money == 0) {
            return array("error"=>"不可支付金额为0", "e_status"=>"INSIDE_ERROR");
        }
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$ctypeid))->find();
        if (!$account_type) {
            return array("error"=>"内部错误", "e_status"=>"INSIDE_ERROR");
        }
        $_POST['market_id'] = $market['market_id'];
        $_POST[$model.'_id'] = $model_id;
        $_POST['related_owner_role_id'] = $market['owner_role_id'];
        self::set_post_account($money, $model_id, $account_type);

        if (!($account_id = $this->update_account($model_id, $account_type, false))) {
            return array("error"=>$account_type['description']."失败", "e_status"=>"INSIDE_ERROR");
        }
        return $account_id;
    }

    public function pay_cultivate_account($model, $model_id, $money, $cultivate, $ctypeid) {
        if ($money == 0) {
            return array("error"=>"不可支付金额为0", "e_status"=>"INSIDE_ERROR");
        }
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$ctypeid))->find();
        if (!$account_type) {
            return array("error"=>"内部错误", "e_status"=>"INSIDE_ERROR");
        }
        $_POST['cultivate_id'] = $cultivate['cultivate_id'];
        $_POST[$model.'_id'] = $model_id;
        $_POST['related_owner_role_id'] = $cultivate['owner_role_id'];
        self::set_post_account($money, $model_id, $account_type);

        if (!($account_id = $this->update_account($model_id, $account_type, false))) {
            return array("error"=>$account_type['description']."失败", "e_status"=>"INSIDE_ERROR");
        }
        return $account_id;
    }


    public function pay_cultivate_salary($money, $cultivate, $ctypeid, $job_number) {
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$ctypeid))->find();
        if (!$account_type) {
            return array("error"=>"内部错误", "e_status"=>"INSIDE_ERROR");
        }
        $m_model = M($cultivate['model'])->where(array($cultivate['model'].'_id'=>$cultivate['model_id']))->find();
        if ($account_type['mold'] < 0 && $m_model['actual'] < $money) {
            return array("error"=>self::format_market_enough_tip($account_type['mold'], $cultivate['model'], $cultivate),"e_status"=>"NO_MONEY");
        }
        unset($_POST);
        $_POST['cultivate_id'] = $cultivate['cultivate_id'];
        $_POST['related_owner_role_id'] = $cultivate['owner_role_id'];
        if ($job_number) {
            $_POST['description'] = "派工单: " .$job_number;
        }
        self::set_post_account($money, $cultivate['model_id'], $account_type);

        $account_id = $this->update_account($cultivate['model_id'], $account_type, false);
        if (!$account_id) {
            return array("error"=>$account_type['description']."失败", "e_status"=>"INSIDE_ERROR");
        }
        return $account_id;
    }

    public static function format_trade_enough_tip($mold, $module, $business) {
        $param = array(
            "dire"=>1,
            "t"=>$module,
            "type_id"=>$module == "customer" ? 31 : 5,
            "clause_additive"=>$business['customer_id']
        );
        return "账户可".($mold == '-3' ? "冻结":"支取")."金额不足，"."立即[<a href='".U("account/add",$param)."' target='_blank'>充值</a>]";
    }

    public function customer_pay_trade($money, $trade, $ctypeid) {
        if ($money == 0) {
            return true;
        }
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$ctypeid))->find();
        if (!$account_type) {
            return array("error"=>"内部错误", "e_status"=>"INSIDE_ERROR");
        }
        $customer = M("customer")->where(array('customer_id'=>$trade['customer_id']))->find();
        if ($account_type['mold'] < 0 && $customer['actual'] < $money) {
            return array("error"=>self::format_trade_enough_tip($account_type['mold'], "customer", $trade),"e_status"=>"NO_MONEY");
        }
        unset($_POST);
        $_POST['trade_id'] = $trade['trade_id'];
        self::set_post_account($money, $customer['customer_id'], $account_type);

        if (!$this->update_account($customer['customer_id'], $account_type)) {
            $supply_money = $customer['actual'] - $money;
            return array("error"=>$account_type['description']."失败", "e_status"=>"INSIDE_ERROR", "param"=>array("money"=>$supply_money));
        }
        //TradeAction::send_trade_notice($trade['trade_id'], 3);
        return true;
    }

    public function product_pay_trade($money, $trade, $ctypeid) {
        if ($money == 0) {
            return true;
        }
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$ctypeid))->find();
        if (!$account_type) {
            return array("error"=>"内部错误", "e_status"=>"INSIDE_ERROR");
        }

        $product = M("product")->where(array('product_id'=>$trade['product_id']))->find();
        if ($account_type['mold'] < 0 && $product['actual'] < $money) {
            $supply_money = $product['actual'] - $money;
            return array("error"=>self::format_trade_enough_tip($account_type['mold'], "product", $trade), "e_status"=>"NO_MONEY", "param"=>array("money"=>$supply_money));
        }
        unset($_POST);
        $_POST['trade_id'] = $trade['trade_id'];
        self::set_post_account($money, $product['product_id'], $account_type);

        if (!$this->update_account($product['product_id'], $account_type)) {
            return array("error"=>$account_type['description']."失败", "e_status"=>"INSIDE_ERROR");
        }
        return true;
    }

    public function upadate_log($subject, $account_id, $account_type, $money) {
        $logtip = self::format_update_log_info($account_id, $account_type, $money);
        $this->log($account_id, $subject, $logtip);
    }


    public function update_account($clause_additive, $account_type, $notice = true) {
        G('update_accountStartTime');
        $account_id = $this->insert_account($account_type);
        if ($account_id) {
            if (self::update_account_money($clause_additive, $account_type['module_id'])) {
                if ($notice) {
                    if ($account_type['mold'] != 3 && $account_type['mold'] != -3) {
                        //self::send_account_notice($account_type, $this->_request("money"), $clause_additive);
                    }
                }
            }

            if ($account_type["inflow_model"]) {
                $flow_account_id = 0;
                if ($account_type["inflow_model"] == "flow") {
                    $flow_account_id = $this->add_flow_account($account_id, $account_type, $clause_additive);
                }else if ($account_type["inflow_model_type_id"] > 0) {
                    $flow_account_id = $this->add_infow_account($account_id, $account_type);
                }
                if ($flow_account_id) {
                    M('account')->where(array('account_id'=>$account_id))->setField('infow_account_id', $flow_account_id);
                }
            }
            $this->update_keyword($account_id, $account_type);
            G('update_accountEndTime');
            $this->log($account_id, "更新账目完成", "用时: ".G('update_accountStartTime','update_accountEndTime',6)."s. =>>".$account_id);
        }
        return $account_id;
    }

    public function add(){
        $clause_additive = isset($_REQUEST['clause_additive']) ? trim($_REQUEST['clause_additive']) : '';
        if($this->isPost()){
            $this->refer_url = $_POST['refer_url'] ? $_POST['refer_url'] : U('account/'.$this->type, 't='.$this->type);
            if (session("submit_time") == $_POST['submit_time']) {
                alert('error','禁止重复提交,或是您提交的太频繁了', $this->refer_url);
            }
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            session("submit_time", $_POST['submit_time']);

            perfect_model_field_post("account");
            $account_type = $this->update_param_check($clause_additive);
            if (!$account_type) {
                alert('error','错误的账目类型',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
            }

            if (!($account_id = $this->update_account($clause_additive, $account_type))) {
                $this->alert = parseAlert();
                alert('error','添加账目失败',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
            }

            if ($account_type['related_model'] == 'market') {
                $market_id= $_POST["market_id"];
                if ($market_id) {
                    define("NO_AUTHORIZE_CHECK", true);
                    A("Manage/Market")->payment_status($account_type['type_id'], $account_id, $market_id, $_POST["money"]);
                }
            }else if ($account_type['related_model'] == 'cultivate') {
                $cultivate_id= $_POST["cultivate_id"];
                if ($cultivate_id) {
                    define("NO_AUTHORIZE_CHECK", true);
                    A("Manage/Cultivate")->payment_status($account_type['type_id'], $account_id, $cultivate_id, $_POST["money"]);
                }
            }
            alert('success','支付成功',$this->refer_url);

        }else{
            if (in_array($this->type, array('cultivate',"market"))) {
                if (vali_permission($this->type, "account_add") == false) {
                    alert('error', '您没有此权利!', $_SERVER['HTTP_REFERER']);
                }
            }

            $excmodel = array(
                "flow",
                'cultivate',
                "market"
            );
            $this->fields_group = field_list_html_add("account", in_array($this->type, $excmodel) ? null : "payway");
            $this->assign('dire',isset($_GET['dire']) ? intval($_GET['dire']) : 1);
            if ($_GET['type_id']) {
                $this->account_type = M("account_type")->cache(true)->where(array("type_id"=>$_GET['type_id']))->find();
            }
            if ($clause_additive) {
                $this->clause = M($this->type)->where(array($this->type."_id"=>$clause_additive))->find();
            }
            $this->clause_additive = $clause_additive;
            $this->alert = parseAlert();
            $this->refer_url= refer_url('refer_add_url');
            $this->submit_time = time();

            if (in_array($this->type, array('cultivate',"market"))) {
                $addview = $this->type."add";
            } else {
                $addview = "add";
            }
            $this->display($addview);
        }
    }

    public function cultivateadd(){
        if (session("submit_time") == $_POST['submit_time']) {
            alert('error','禁止重复提交,或是您提交的太频繁了', $this->refer_url);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        session("submit_time", $_POST['submit_time']);

        $clause_additive = isset($_REQUEST['clause_additive']) ? trim($_REQUEST['clause_additive']) : '';
        perfect_model_field_post("account");

        $money = trim($this->_request("money"));
        if(empty($money) || $money <= 0){
            $this->alert = parseAlert();
            alert('error','请填写正确的正数金额数字',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
        }

        $clause_type_id = $_POST["clause_type_id"];
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$clause_type_id))->find();
        if (!$account_type) {
            $this->alert = parseAlert();
            alert('error','内部错误',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
        }

        $m_cultivate = D("CultivateView")->where("cultivate.cultivate_id=".$clause_additive)->find();
        if (!$m_cultivate) {
            $this->alert = parseAlert();
            alert('error','内部错误',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
        }

        if ($_POST['payway'] == "余额冻结") {
            $m_model = M($m_cultivate['model'])->where($m_cultivate['model']."_id=".$m_cultivate['model_id'])->find();
            if ($m_model['actual'] < $money) {
                $this->alert = parseAlert();
                alert('error','学员可冻结余额不足',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
            }
        }

        $_POST['cultivate_id'] = $m_cultivate['cultivate_id'];
        $_POST['related_owner_role_id'] = $m_cultivate['owner_role_id'];
        $account_id = $this->update_account($clause_additive, $account_type, false);
        if (!$account_id) {
            $this->alert = parseAlert();
            alert('error','添加账目失败',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
        }

        if ($_POST['payway'] == "余额冻结") {
            $this->module_payment_verify($account_id, 1);
        }
        $this->update_surplus_price("cultivate", $m_cultivate['cultivate_id']);
        alert('success','支付成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('cultivate/view', 'assort=account&id='.$clause_additive));
    }

    public function add_market_pay_account($market_id, $money, $payway) {
        $_POST['money'] = $money;
        $_POST['payway'] = $payway;
        $_POST['account_type'] = "market";
        $_POST['clause_type_id'] = "216";
        $_POST['income_or_expenses'] = "1";
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$_POST['clause_type_id']))->find();
        if (!$account_type) {
            return false;
        }
        $m_market = D("MarketView")->where("market.market_id=".$market_id)->find();
        if (!$m_market) {
            return false;
        }
        $_POST['market_id'] = $_POST['clause_additive'] = $m_market['market_id'];
        $_POST['related_owner_role_id'] = $m_market['owner_role_id'];
        $_POST['creator_role_id'] = $m_market['owner_role_id'];
        $account_id = $this->update_account($m_market['market_id'], $account_type, false);
        if (!$account_id) {
            return false;
        }
        $this->update_surplus_price("market", $m_market['market_id']);
        return $account_id;
    }

    public function marketadd(){
        if (session("submit_time") == $_POST['submit_time']) {
            alert('error','禁止重复提交,或是您提交的太频繁了', $this->refer_url);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        session("submit_time", $_POST['submit_time']);

        $clause_additive = isset($_REQUEST['clause_additive']) ? trim($_REQUEST['clause_additive']) : '';
        perfect_model_field_post("account");

        $money = trim($this->_request("money"));
        if(empty($money) || $money <= 0){
            $this->alert = parseAlert();
            alert('error','请填写正确的正数金额数字',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
        }

        $clause_type_id = $_POST["clause_type_id"];
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$clause_type_id))->find();
        if (!$account_type) {
            $this->alert = parseAlert();
            alert('error','内部错误',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
        }

        $m_market = D("MarketView")->where("market.market_id=".$clause_additive)->find();
        if (!$m_market) {
            $this->alert = parseAlert();
            alert('error','内部错误',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
        }

        if ($_POST['payway'] == "余额冻结") {
            $m_customer = D("CustomerView")->where("customer.customer_id=".$m_market['customer_id'])->find();
            if ($m_customer['actual'] < $money) {
                $this->alert = parseAlert();
                alert('error','客户可冻结余额不足',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
            }
        }

        $_POST['market_id'] = $m_market['market_id'];
        $_POST['related_owner_role_id'] = $m_market['owner_role_id'];
        $account_id = $this->update_account($clause_additive, $account_type, false);
        if (!$account_id) {
            $this->alert = parseAlert();
            alert('error','添加账目失败',$_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
        }

        if ($_POST['payway'] == "余额冻结") {
            $this->module_payment_verify($account_id, 1);
        }
        $this->update_surplus_price("market", $m_market['market_id']);
        alert('success','支付成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('market/view', 'assort=account&id='.$clause_additive));
    }

    public function edit(){
        $account = D(ucfirst($this->type).'AccountView')->where('account.account_id = %d',$_REQUEST["id"])->find();
        if (!$account) {
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()){
            $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$account['clause_type_id']))->find();
            if (!$account_type) {
                alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            perfect_model_field_post("account");

            $m_account = D('account');
            if(!$m_account->create()) {
                alert('error',$m_account->getError(),$_SERVER['HTTP_REFERER']);
            }
            $a = $m_account->where('account_id= %d',$account['account_id'])->save();

            self::update_related_account($account['account_id'], $account_type, $account);
            self::update_account_money($account['clause_additive'], $account['module_id']);
            $this->update_keyword($account['account_id'], $account_type);

            $this->log($account['account_id'], "编辑账目", $this->format_log_info($account['account_id']));
            if($a ) {
                alert('success', "编辑账户成功", $_POST['refer_url'] ? $_POST['refer_url'] : U('account/view', 'id='.$account['account_id'].'&t='.$this->type));
            } else {
                alert('error', "编辑账户失败", $_POST['refer_url'] ? $_POST['refer_url'] : $_SERVER['HTTP_REFERER']);
            }
            self::log($account['account_id'], "修改账目", $a ? '修改账目成功':'编辑账户失败');

        } else{
            if (in_array($this->type, array("market", "cultivate")) && vali_permission($this->type, "account_edit") == false) {
                alert('error', '您没有此权利!', $_SERVER['HTTP_REFERER']);
            }
            $this->alert = parseAlert();
            $this->fields_group = field_list_html_edit("account", $account);
            $this->refer_url=$_REQUEST['refer_url'] ? $_REQUEST['refer_url']:$_SERVER['HTTP_REFERER'];
            $this->account = $account;
            $this->model_id = $account['account_id'];
            if ($this->type == "market") {
                $template = "marketedit";
            } else if ($this->type == "cultivate") {
                $template = "cultivateedit";
            } else {
                $template = "edit";
            }
            $this->display($template);
        }
    }

    public function update_related_account($account_id, $account_type, $m_related) {
        $related = $account_type['related_model'];
        $related = ($related == "business_flow" ? "business" : $related);
        if (!$m_related || !$related) {
            return;
        }
        $related_id = $m_related[$related."_id"];

        if ($account_type['related_model']) {
            $data = array();
            if ($related == "market") {
                $data['receipt_number'] = $_POST['receipt_number'];
                $data['payment_time'] = $_POST['payment_time'] ? strtotime($_POST['payment_time']) : 0;
            }
            M($related."_account")->where("account_id=".$account_id)->setField($data);
        }

        if ($account_type['module_id'] != "inernal") {
            self::add_model_account_log($account_id, $account_type, $_POST["money"], $related, $related_id);
        }
        self::calculate_module_account($related, $related_id, $account_type['module_id']);
        self::update_model_loans_account($related, $related_id);
    }

    public static function update_account_money($module_id, $module_name) {
        $hulumodel = array("inernal", "market", "cultivate");
        if (!$module_id || !$module_name || in_array($module_name,$hulumodel)) {
            return false;
        }
        $account = D("Manage/".ucfirst($module_name).'AccountView');
        $where['account.clause_additive'] = array('eq',$module_id);
        $where['account.account_type'] = $module_name;
        $where['league_id'] = session('league_id');
        //收入
        $where['account.income_or_expenses'] = 1;
        $shouru_sum_money = $account->where($where)->sum('account.money');
        if (!$shouru_sum_money) {
            $shouru_sum_money = 0.0;
        }
        //支出
        $where['account.income_or_expenses'] = -1;
        $zhichu_sum_money = $account->where($where)->sum('account.money');
        if (!$zhichu_sum_money) {
            $zhichu_sum_money = 0.0;
        }
        $balance_money = $shouru_sum_money - $zhichu_sum_money;

        //冻结
        $where['account.income_or_expenses'] = -3;
        $freeze_sum_money = $account->where($where)->sum('account.money');
        if (!$freeze_sum_money) {
            $freeze_sum_money = 0.0;
        }

        //解冻
        $where['account.income_or_expenses'] = 3;
        $relieve_sum_money = $account->where($where)->sum('account.money');
        if (!$relieve_sum_money) {
            $relieve_sum_money = 0.0;
        }
        $freeze_sum_money -= $relieve_sum_money;


        //可使用金额
        $actual = $balance_money - $freeze_sum_money;

        //可提现金额
        $cash_sum_money = $actual;
        if($cash_sum_money < 0) $cash_sum_money = 0;

        $data = array(
            "balance"=>$balance_money,
            "freeze"=>$freeze_sum_money,
            "cash"=>$cash_sum_money,
        );
        $module_name = strtolower($module_name);
        M($module_name)->where(array($module_name.'_id'=>$module_id))->setField($data);
        M()->execute('update 5k_a_'.$module_name.' set actual="'.$actual. '" where '.$module_name.'_id='.$module_id);

        return true;
    }

    public function is_active_account($account) {
        if (!in_array($account['clause_type_id'], array(5, 31,231))) {
            return false;
        }
        return $account['related'] != "" && $account['related_id'] != "";
    }

    public function view(){
        $account_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $account_id) {
            alert('error', L('PARAMETER_ERROR'), U('account/'.$this->type));
        }

        $account = D(ucfirst($this->type).'AccountView')->where('account.account_id = %d',$account_id)->find();
        if (!$account) {
            alert('error', '没有这个账目', U('account/index'));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $account = $this->format_account_info($account);
        $account['is_active'] = $this->is_active_account($account);

        if ($account['module_id'] == "flow" || $account['module_id'] == "inernal") {
            $account['show_module'] = session('league_name');
        } else {
            $model_id = $account[$account['module_id']."_id"];
            $href = U($account['module_id']."/view", 'id='.$model_id);
            $account['show_module'] = "<a href='".$href."' target='_blank'/>[".$account['idcode'].']&nbsp;'.$account[$account['module_id']."_name"]."</a>";
        }

        if ($account['infow_account_id'] && $account['inflow_model'] != "flow") {
            $infow_model = $account['inflow_model'];
            $infow = D(ucfirst($infow_model).'AccountView')->where(array("account_id"=>$account['infow_account_id']))->find();;
            if ($infow) {
                $infow_href_title = $infow[$infow_model."_name"];
                $href = U($infow_model."/view", 'id='.$infow[$infow_model."_id"]);
                $infow['show_infow'] = "<a href='".$href."' target='_blank'/>[".$infow['idcode'].']&nbsp;'.$infow_href_title."</a>";

            } else {
                $infow['infow']['name'] = session('league_name');
            }
        } else {
            $infow['show_infow'] = session('league_name');
        }
        $account['infow'] =$infow;

        $log_ids = M('AccountLog')->where(array("account_id"=>$account_id))->getField('log_id', true);
        $logs =  M('log')->where('log_id in (%s)', implode(',', $log_ids))->order("update_date desc")->select();;
        foreach($logs as $k=>$value) {
            $logs[$k]['owner'] = D('RoleView')->where('role.role_id = %d', $value['role_id'])->find();
        }
        $account['log'] = $logs;

        $this->account = $account;
        $excmodel = array(
            "flow",
            "market",
            "cultivate"
        );
        $this->fields_group = get_groupfields_list('account', "basic", in_array($this->type,$excmodel) ? null:"payway");

        if ($account['module_id'] == "flow" &&  $account['clause_type_id'] == "123") {
            $this->infow_model = D(ucfirst($account['inflow_model']).'View')->where(array($account['inflow_model']."_id"=>$account['clause_additive']))->find();
        }
        $this->per_verify = vali_permission("account", "change_payment_verify", $this->type);

        $dire = isset($_GET['dire']) ? intval($_GET['dire']) : 1;
        $this->assign('dire',$dire);
        $this->alert = parseAlert();
        $this->refer_url= refer_url('refer_view_url');

        if (in_array($this->type, array('cultivate',"market"))) {
            $template = $this->type."view";
        } else {
            $template = "view";
        }
        $this->display($template);
    }


    public static function calculate_trade_account($trade_id, $module) {
        $trade = D(ucfirst($module)."TradeView")->where(array("trade_id"=>$trade_id))->find();
        if ($trade) {
            $money = calculate_margin_account("trade", array("trade_id"=>$trade_id,"account_type"=>$module,'league_id'=>session('league_id')));
            $data = array(
                "pay_price"=>$money,
                'surplus_price'=>($trade['state'] == "已撤销" ? 0 : $trade['sum_price'] - $money)
            );
            M("trade")->where(array('trade_id'=>$trade_id))->setField($data);
            self::update_model_surplus_price(array($trade['corre']=>$trade[$trade['corre']."_id"]));
        }
    }

    public function delete_related_account($account, $account_type) {
        $related_model = $account['related'];
        $m_related = M($related_model."_account");
        $order_account = $m_related->where(array("account_id"=>$account['account_id']))->find();
        if ($order_account) {
            $m_related->where(array("account_id"=>$account['account_id']))->delete();
            if ($account_type['module_id'] != "inernal") {
                self::calculate_module_account($related_model, $order_account[$related_model."_id"], $account_type['module_id']);
                self::update_model_loans_account($related_model, $order_account[$related_model."_id"]);
            }
        }
    }

    public function delete_account_info($account, $account_type) {
        $this->log($account['account_id'], "删除账目", $this->format_log_info($account['account_id']));
        M('account')->where('account_id = %d', $account['account_id'])->delete();
        if ($account_type) {
            $this->update_account_money($account['clause_additive'], $account_type['module_id']);
            $this->delete_related_account($account, $account_type);
        }
    }

    public function delete_account($account) {
        if (!is_array($account)) {
            $account = M('account')->where('account_id = %d', $account)->find();
        }
        $account_type = M('AccountType')->cache(true)->where(array('type_id'=>$account['clause_type_id']))->find();
        $this->delete_account_info($account, $account_type);

        if ($account_type["inflow_model"]) {
            if ($account_type["inflow_model"] == "ious" || $account_type['inflow_model_type_id']) {
                $infow_account = M('account')->where('account_id = %d', $account['infow_account_id'])->find();
                if ($infow_account) {
                    $this->delete_account($infow_account);
                }
            }
        }
    }

    public function delete_accounts($account_ids) {
        foreach($account_ids as $account_id){
            $this->delete_account($account_id);
        }
    }

    public function delete(){
        if($this->isPost() || $_REQUEST['id']){
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            $account_ids = is_array($_REQUEST['account_id']) ? $_REQUEST['account_id'] : array($_REQUEST['id']);
            foreach($account_ids as $account_id){
                $account = M('account')->where('account_id = %d', $account_id)->find();
                if (!$account || $this->is_active_account($account)) {
                    return;
                }
                $this->delete_account($account);
            }
            alert('success',L('DELETE_THE_SUCCESS'),$_REQUEST['refer_url'] ? $_REQUEST['refer_url']:$_SERVER['HTTP_REFERER']);
        }
        else {
            if ($_REQUEST['refer_url']) {
                alert('error',L('PLEASE_SELECT_ACCOUNT_TO_DELETE'),$_REQUEST["refer_url"]);
            } else {
                alert('error',L('PLEASE_SELECT_ACCOUNT_TO_DELETE'),$_SERVER['HTTP_REFERER']);
            }
        }
    }


    public function accounttype(){
        $assort = $_GET['assort'] ? $_GET['assort'] : "basic";
        if ($assort == "cash") {
            $accounttype = M('account_type');
            $accounttype_list = $accounttype->cache(true)->where(array("inflow_model_type_id"=>array("eq", -2)))->order("order_id asc")->select();
            foreach($accounttype_list as $key=>$value){
                $accounttype_list[$key]['mold'] = acction_type_desc($value['mold']);
            }
            $this->assign('accounttype_list', $accounttype_list);
        } else {
            $accounttype = M('account_type');
            $where = array(
                "inflow_model_type_id"=>array("neq", -2),
            );
            if (isset($_GET['related_model'])) {
                if ($_GET['related_model'] == "other") {
                    $where['related_model'] = "";
                } else {
                    $where['related_model'] = array("in", $_GET['related_model']);
                }
            }

            $accounttype_list = $accounttype->cache(true)->where($where)->order("order_id asc")->select();

            foreach($accounttype_list as $key=>$value){
                if ($value['inflow_model_type_id']) {
                    $inflow_model_type = $accounttype->cache(true)->where(array('type_id'=>$value['inflow_model_type_id']))->find();
                    if ($inflow_model_type) {
                        $accounttype_list[$key]['inflow_model_type'] = $inflow_model_type;
                    }
                }
                $accounttype_list[$key]['mold'] = acction_type_desc($value['mold']);
            }
            $this->assign('accounttype_list', $accounttype_list);
        }
        $this->alert=parseAlert();
        $this->display("accounttype_".$assort);
    }

    public function accounttype_add(){
        if (isset($_POST['name']) && $_POST['name'] != '') {
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            $accounttype = D('AccountType');
            if ($accounttype->create()) {
                if ($accounttype->add()) {
                    delete_cache_temp();
                    alert('success', L('ADD_SUCCESSFUL'),$_SERVER['HTTP_REFERER']);
                } else {
                    alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
                }
            } else {
                alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
        }else{
            $accounttype = M('account_type');
            $accounttype_list = $accounttype->select();
            $this->assign('accounttype_list', $accounttype_list);
            $this->display();
        }
    }

    public function accounttype_delete(){
        $accounttype = M('account_type');
        $account = M('account');
        if($_POST['accounttype_list']){
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            foreach($_POST['accounttype_list'] as $value){
                if($account->where('type_id = %d',$value)->select()){
                    $name = $accounttype->where('type_id = %d',$value)->getField('name');
                    alert('error', L('UNDER_THE_CATEGORY_OF_PRODUCTS',array($name)),$_SERVER['HTTP_REFERER']);
                }
            }
            if($accounttype->where('type_id in (%s)', join($_POST['accounttype_list'],','))->delete()){
                delete_cache_temp();
                alert('success', L('DELETE_THE_SUCCESS') ,$_SERVER['HTTP_REFERER']);
            }else{
                alert('error', L('DELETE_FAILED') ,$_SERVER['HTTP_REFERER']);
            }
        }elseif($_GET['id']){
            if($account->where('type_id = %d',$_GET['id'])->select()){
                $name = $accounttype->where('type_id = %d',$_GET['id'])->getField('name');
                alert('error', L('UNDER_THE_CATEGORY_OF_PRODUCTS',array($name)),$_SERVER['HTTP_REFERER']);
            }
            if($accounttype->where('type_id = %d',$_GET['id'])->delete()){
                delete_cache_temp();
                alert('success', L('DELETE_THE_SUCCESS') ,$_SERVER['HTTP_REFERER']);
            }else{
                alert('error', L('DELETE_FAILED') ,$_SERVER['HTTP_REFERER']);
            }
        }else{
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
    }

    //编辑雇员分类信息
    public function accounttype_edit(){
        if(isset($_GET['id'])){
            $this->temp =M('account_type')->where('type_id = ' . $_GET['id'])->find();
            $this->display();
        }elseif(isset($_POST['type_id'])){
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            $accounttype = M('account_type');
            $accounttype->create();
            if($accounttype->save() !== false){
                delete_cache_temp();
                alert('success',L('MODIFY_THE_SUCCESS'),$_SERVER['HTTP_REFERER']);
            }else{
                alert('error',L('MODIFY_THE_FAILURE'),$_SERVER['HTTP_REFERER']);
            }
        }else{
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
    }

    public function accounttype_sort(){
        if(isset($_GET['postion'])){
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            $accounttype = M('account_type');
            foreach($_GET['postion'] AS $fieldpos) {
                $accounttype->where(array(
                    'type_id'=> $fieldpos['type_id'])
                )->setField('order_id', $fieldpos['order_id']);
            }
            delete_cache_temp();
            $this->ajaxReturn('1', '排序成功', 1);
        } else {
            $this->ajaxReturn('0', '排序失败', 1);
        }
    }

    public  function accounttype_state() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $is_show = $_REQUEST['is_show'] ? 0 :1;
        M('account_type')->where(array('type_id'=>$_REQUEST['id']))->setField('is_show', $is_show);
        delete_cache_temp();
        alert('success',L('MODIFY_THE_SUCCESS'),$_SERVER['HTTP_REFERER']);
    }

    //编辑雇员分类信息
    public function getclausetype(){
        $where = array();
        if ($_REQUEST['im']) {
            $where['module_id'] = $this->_request("im");
        }
        if ($_REQUEST['mo']) {
            $where['mold'] = -($this->_request("mo"));
        }
        if ($_REQUEST['if']) {
            $where['inflow_model'] = $this->_request("if");
        }
        if ($_REQUEST['id']) {
            $where['inflow_model_type_id'] = $this->_request("id");
        }
        $where['related_model'] = $this->_request("rm");

        $clausetypelist = M('account_type')->cache(true)->where($where)->order('order_id')->select();
        $this->ajaxReturn($clausetypelist, '', 1);
    }

    public function analytics(){
        $params = array();
        $assort = $_GET['assort'] ? $_GET['assort'] : "state";
        if ($_GET['assort']) {
            $params[] = "assort=".$_GET['assort'];
        }
        $time_limit = self::default_statistics_time($params);

        $tab = "";
        $this->assort = $assort;

        if ($assort == "state") {
            self::state_analytics($time_limit[0], $time_limit[1]);
        } else {
            $tab = "_".($_GET['tab'] ? $_GET['tab'] : "charts");
            if ($_GET['tab']) {
                $params[] = "tab=".$_GET['tab'];
            }

            $cycle = $_GET['cycle'] ? $_GET['cycle'] : "week";
            if ($_GET['cycle']) {
                $params[] = "cycle=".$_GET['cycle'];
            }

            $atype = $_GET['atype'] ? $_GET['atype'] : "";
            if ($_GET['atype']) {
                $params[] = "atype=".$_GET['atype'];
            }

            $ietype = $_GET['ietype'] ? $_GET['ietype'] : "";
            if ($_GET['ietype']) {
                $params[] = "ietype=".$_GET['ietype'];
            }
            if ($assort == "inernal") {
                self::inernal_analytics($time_limit[0], $time_limit[1], $cycle, $tab, $atype, $ietype);
            }else if ($assort == "product") {
                self::product_analytics($time_limit[0], $time_limit[1], $cycle, $tab, $atype, $ietype);
            }else if ($assort == "customer") {
                self::customer_analytics($time_limit[0], $time_limit[1], $cycle, $tab, $atype, $ietype);
            }
            $assort = "reckon";
        }

        $this->parameter = implode('&', $params);
        $this->alert = parseAlert();
        $this->display($assort."_analytics".$tab);
    }

    public function module_analytics_fields_list($m) {
        $account_type = array(
            "产品"=>array(
                "净支出"=>array(
                    "account_type"=>$m,
                    "related"=>array("in", array('trade')),
                    "profit"=>array(-1,1)
                )
            ),
            "白条"=>array(
                "垫付"=>array(
                    "account_type"=>$m,
                    "income_or_expenses"=>'-2',
                ),
                "偿还"=>array(
                    "account_type"=>$m,
                    "income_or_expenses"=>'2',
                ),
                "净垫付"=>array(
                    "account_type"=>$m,
                    "profit"=>array(2,-2)
                ),
            ),
            "资金"=>array(
                "冻结"=>array(
                    "account_type"=>$m,
                    "income_or_expenses"=>'-3',
                ),
                "解冻"=>array(
                    "account_type"=>$m,
                    "income_or_expenses"=>'3',
                ),
                "净冻结"=>array(
                    "account_type"=>$m,
                    "profit"=>array(3,-3)
                ),
            ),
        );
        return $account_type;
    }

    public function customer_analytics($start_time, $end_time, $cycle, $tab, $atype, $ietype) {
        $account_type = array(
            "其他"=>array(
                "净支出"=>array(
                    "account_type"=>'customer',
                    "related"=>array("not in", array('business', 'business_flow', 'trade', 'trainorder')),
                    "profit"=>array(-1,1)
                )
            )
        );
        $this->account_type = array_merge($account_type, self::module_analytics_fields_list("customer"));
        $atype = $atype ? $atype : "主业务";

        $this->ie_type = $this->account_type[$atype];
        $ietype = $ietype ? $ietype : key($this->ie_type);

        self::proportion_statistics($start_time, $end_time, $cycle, $tab, $atype, $ietype, $this->ie_type[$ietype]);
    }

    public function product_analytics($start_time, $end_time, $cycle, $tab, $atype, $ietype) {
        $account_type = array(
            "工资"=>array(
                "收入"=>array(
                    "account_type"=>'product',
                    "clause_type_id"=>6,
                    "income_or_expenses"=>'1',
                )
            ),
            "其他"=>array(
                "净支出"=>array(
                    "account_type"=>'product',
                    "related"=>array("not in", array('business', 'business_flow', 'trade', 'trainorder')),
                    "clause_type_id"=>array("not in", array('6')),
                    "profit"=>array(-1,1)
                )
            )
        );
        $this->account_type = array_merge($account_type, self::module_analytics_fields_list("product"));
        $atype = $atype ? $atype : "主业务";

        $this->ie_type = $this->account_type[$atype];
        $ietype = $ietype ? $ietype : key($this->ie_type);

        self::proportion_statistics($start_time, $end_time, $cycle, $tab, $atype, $ietype, $this->ie_type[$ietype]);
    }

    public function inernal_analytics($start_time, $end_time, $cycle, $tab, $atype, $ietype) {
        $account_type = array(
            "主业务"=>array(
                "收入"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("in", array('business', 'business_flow')),
                    "income_or_expenses"=>'1',
                ),
                "支出"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("in", array('business', 'business_flow')),
                    "income_or_expenses"=>'-1',
                ),
                "利润"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("in", array('business', 'business_flow')),
                    "profit"=>array(1,-1)
                ),
            ),
            "产品"=>array(
                "收入"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("in", array('trade')),
                    "income_or_expenses"=>'1',
                ),
                "支出"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("in", array('trade')),
                    "income_or_expenses"=>'-1',
                ),
                "利润"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("in", array('trade')),
                    "profit"=>array(1,-1)
                ),
            ),
            "培训"=>array(
                "收入"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("in", array('trainorder')),
                    "income_or_expenses"=>'1',
                ),
                "支出"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("in", array('trainorder')),
                    "income_or_expenses"=>'-1',
                ),
                "利润"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("in", array('trainorder')),
                    "profit"=>array(1,-1)
                ),
            ),
            "其他"=>array(
                "收入"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("not in", array('trainorder', 'trade', 'business', 'business_flow')),
                    "income_or_expenses"=>'1',
                ),
                "支出"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("not in", array('trainorder', 'trade', 'business', 'business_flow')),
                    "income_or_expenses"=>'-1',
                ),
                "利润"=>array(
                    "account_type"=>'inernal',
                    "related"=>array("not in", array('trainorder', 'trade', 'business', 'business_flow')),
                    "profit"=>array(1,-1)
                ),
            ),
            "管理"=>array(
                "收入"=>array(
                    "account_type"=>'inernal',
                    'account_type.type_id' =>array('in', array(43)),
                ),
                "支出"=>array(
                    "account_type"=>'inernal',
                    'clause_type_id' =>array('in', array(44)),
                ),
                "利润"=>array(
                    "account_type"=>'inernal',
                    "profit"=>array(43=>1,44=>-1)
                ),
            ),
        );
        $this->account_type = $account_type;
        $atype = $atype ? $atype : "主业务";

        $this->ie_type = $account_type[$atype];
        $ietype = $ietype ? $ietype : key($this->ie_type);

        self::proportion_statistics($start_time, $end_time, $cycle, $tab, $atype, $ietype, $this->ie_type[$ietype]);
    }

    public function proportion_statistics($start_time, $end_time, $cycle, $tab, $atype, $ietype, $iewhere) {
        $this->cycle_data = self::cycle_array($start_time, $end_time, $cycle, $tab == "_charts");

        $this->cycle_title = date("Y", $start_time)."年".$atype.$ietype."账目";
        if ($iewhere['profit']) {
            $this->cycle_create_data = self::cycle_account_profit_array($start_time, $end_time, $cycle, $iewhere);;
        } else {
            $this->cycle_create_data = self::cycle_account_array($start_time, $end_time, $cycle, $iewhere);;
        }

        $yester_start_time = strtotime('-1 year', $start_time);
        $yester_end_time = $yester_start_time + ($end_time - $start_time);

        $this->yester_cycle_title = date("Y", $yester_start_time)."年".$atype.$ietype."账目";
        if ($iewhere['profit']) {
            $this->yester_cycle_create_data = self::cycle_account_profit_array($yester_start_time, $yester_end_time, $cycle, $iewhere);;
        } else {
            $this->yester_cycle_create_data = self::cycle_account_array($yester_start_time, $yester_end_time, $cycle, $iewhere);;
        }

        self::default_cycle_basis_newly_bulking_data($tab, "账目");
    }

    public function cycle_account_profit_array($start_time, $end_time, $cycle, $iewhere) {
        $profit = $iewhere['profit'];
        unset($iewhere['profit']);

        $iewhere['income_or_expenses'] = current($profit);
        $kt = key($profit);
        if ($kt != 0) {
            $iewhere['clause_type_id'] = $kt;
        }
        $in = self::cycle_account_array($start_time, $end_time, $cycle, $iewhere);

        $iewhere['income_or_expenses'] = next($profit);;
        $kt = key($profit);
        if ($kt != 1) {
            $iewhere['clause_type_id'] = $kt;
        }
        $out = self::cycle_account_array($start_time, $end_time, $cycle, $iewhere);

        $profit = array();
        foreach($in as $k=>$m) {
            $profit[$k] = $m - $out[$k];
        }
        return $profit;
    }

    public function cycle_account_array($start_time, $end_time, $cycle, $awhere) {
        $start_time = germ_cycle($start_time, $cycle);
        while($start_time <= $end_time) {
            $time_begin = $start_time;
            $time_end = $start_time = ($cycle == "quarter" ? aquarter($time_begin, 1) : strtotime('+1 '.$cycle, $time_begin));
            $where_cycle_create['create_time'] = array(array('lt',$time_end),array('gt',$time_begin), 'and');
            $where_cycle_create = array_merge($awhere, $where_cycle_create);
            $money_sum = D("account")->where($where_cycle_create)->sum("money");
            if (!$money_sum) {
                $money_sum = 0;
            }
            $cycle_create_array[] = $money_sum;
        }
        return $cycle_create_array;
    }

    public function state_analytics($start_time, $end_time) {
        $account = M("account");

        $rel_type = array(
            "雇员"=>"product",
            "客户"=>"customer"
        );
        $this->rel_type = $rel_type;

        $all_type = array_merge(array("公司"=>"inernal"), $rel_type);
        $this->all_type = $all_type;

        $basic_accounts = array();
        $account_type = array();

        $account_type[] = "账户余额";
        foreach($all_type as $k=>$v) {
            $total_where = array(
                'income_or_expenses'=>'1',
                'account_type'=>array('eq',$v)
            );
            $intatal = $account->where($total_where)->sum('money');
            $total_where['income_or_expenses']='-1';
            $basic_accounts[$k]["账户余额"] = $intatal - $account->where($total_where)->sum('money');
        }

        $module_atype = array(
            "可提现金额"=>"cash",
            "白条余额"=>"loans",
            "冻结资金余额"=>"freeze",
        );
        foreach($module_atype as $k1=>$k2) {
            $account_type[] = $k1;
        }
        foreach($all_type as $k=>$v) {
            foreach($module_atype as $k1=>$k2) {
                $basic_accounts[$k][$k1] = M($v)->sum($k2);
            }
        }
        $this->basic_accounts = $basic_accounts;
        $this->account_type = $account_type;


        $module_atype = array(
            "主业务"=>"business",
            "产品"=>"trade",
            "培训"=>"trainorder",
        );
        $loans_accounts = array();
        foreach($rel_type as $k1=>$v1) {
            foreach($module_atype as $k2=>$v2) {
                $total_where = array(
                    'income_or_expenses'=>'-2',
                    'account_type'=>array('eq',$v1),
                    'related'=>array('eq',$v2),
                );
                $intatal = $account->where($total_where)->sum('money');
                $total_where['income_or_expenses']='2';
                $loans_accounts[$k1][$k2] = $intatal - $account->where($total_where)->sum('money');
            }
        }
        $this->loans_accounts = $loans_accounts;
        $this->loans_account_type = $module_atype;
    }

    public function log($account_id, $subject, $content, $category_id = 6) {
        if ($log_id = $this->addlog($subject, $content, $category_id)) {
            $data['account_id'] = $account_id;
            $data['log_id'] = $log_id;
            $data['league_id'] = session('league_id');
            M('account_log')->add($data);
        }
    }

    public function accouont_log($account_id, $model, $model_id, $subject, $content, $category_id = 6) {
        if ($log_id = $this->addlog($subject, $content, $category_id)) {
            $data['account_id'] = $account_id;
            $data[$model.'_id'] = $model_id;
            $data['log_id'] = $log_id;
            $data['league_id'] = session('league_id');
            M("r_".$model.'_account_log')->add($data);
        }
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
            array(
                "field"=>"subject",
                "order"=>"subject"
            ),
            array(
                "field"=>"content",
                "order"=>"content"
            ),
        );

        $where = array();
        if ($_REQUEST['start_time'] || $_REQUEST['end_time']) {
            $where['log.create_date'] =  array('between', make_time_between(false));
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['log.content'] =  array('like', "%".$_REQUEST['search']['value']."%");
        }
        $where['league_id'] = session('league_id');

        $data = make_data_list("AccountLogView", $where, $data_field, array($this, "format_account_log"));
        $this->ajaxReturn($data,'JSON');
    }


    public function format_account_log($v) {
        $v['create_date_show'] = toDate($v['create_date']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        return $v;
    }

    public function get_infow_account($account_id) {
        $account =  M('account')->where("account_id=".$account_id)->find();
        if (!$account) {
            return false;
        }
        return M('account')->where("account_id=".$account['infow_account_id'])->find();
    }

    public function get_infow_account_model($infow_account) {
        if (!is_array($infow_account)) {
            $infow_account = $this->get_infow_account($infow_account);
        }
        $where = array(
            $infow_account['account_type']."_id"=>$infow_account['clause_additive']
        );
        return D(ucfirst($infow_account['account_type'])."View")->where($where)->find();
    }
}