<?PHP 
class TradeAction extends CastleAction{

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
        $value['corre_id'] = M($value['corre'])->field(array("idcode", "name", $value['corre']."_id"=>"corre_id"))->where(array($value['corre']."_id"=>$value['corre_id']))->find();;
        if ($value['state'] != '已撤销'){
            $value['state'] = $value['trade_state'];
        }
        return parent::perfect_list_item($value, $export, $branchlock);
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
        $this->assign_module_list($where, $params, "产品订单表");
        $this->return_data($this->list, $this->page->totalRows);
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
}
