<?php

class ProductAction extends BaseAction {
	public function _initialize(){
        $action = array(
			'permission'=>array(
                'change_leave_state',
                'search',
                'qrcode',
                'cardinfo',
                'doverify',
                'reset_verify',
                'webshow',
                'group',
                'listdialogsearch',
                'changetrainorderstate',
                'set_evaluate_show_state',
                'addproductcat',
                'delproductcat',
                'editproductcat',
                'groupstance',
                'groupsearch',
                'dosearch',
                'removegroupstance',
                'addgroupstance',
                'allgroupdialog',
                'combination',
                'cardinfo',
                'getskill',
                'getability',
                'getproduct',
                'getcategory',
                'delimg',
                'astrict',
                'deletevideo',
                'deletefile',
                'senddialog',
                'exportprint',
                'analytics',
                'edit_skill',
                'add_skill',
                'delete_skill',
                'delete_skill',
                'view_openid',
                'reset_blank',
                'healthy_expire_task',
                'skill_update_task',
                'reset_submit_state',
                'eventdialog',
                'event_reset',
                'event_delete',
                'getcategorylevelfield',
                'logger',
                'get_account_total',
                'listevent',
                'check_commiss_info',
                'export_pdf',
                'evaluateedit',
                'appraiseadd',
                'appraise_list',
                'appraiseedit',
                'appraisedelete',
                'getevent',
                'evaluate_list',
                'evaluateview'
            ),

			'allow'=>array('listdialog',
                'getworkstate',
                'changecontent',
                'adddialog',
                'editdialog',
                'mdelete',
                'listdialog',
                'validate',
                'check',
                'delimg',
                'sortimg',
                'mutildialog',
                'addtrain',
                'recover',
                'add_dispatch_log'
            )
		);

        if ($_REQUEST['act'] || ACTION_NAME == "index") {
            $_REQUEST['t'] = $_REQUEST['act'];
        }
        if (NO_AUTHORIZE_CHECK === true)
            return;
		B('Authenticate', $action);
	}

    public function verify() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = D('ProductView')->where('product.product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        $assort = $this->_request('assort', 'trim', "basic");;
        if (!$assort) {
            $assort = "basic";
        }
        $this->assort =$assort;
        $this->product = $product;
        $this->state = $product[$assort."_verify"];
        $this->display(); // 输出模板
    }

    public function reset_verify() {
        $this->doverify();
    }

    public function doverify() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $findwhere = array(
            "product_id"=>$this->_request('product_id')
        );
        $product =M('product')->where($findwhere)->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $assort = $this->_request('assort', 'trim', "");
        if ($assort == "") {
            $assort = "basic";
        }
        $state = $this->_request('state', 'trim', "0");
        if ($state == -1) {
            $data = array(
                $assort . "_verify"=>0,
                $assort . "_submit_time"=>0,
                "submit_state"=>($product['submit_state'] & ($assort=="basic"?0x2:0x1))
            );
            M('product')->where($findwhere)->setField($data);
        } else {
            $m_product_verify = D('productVerify');
            $where = array(
                "product_id"=>$product['product_id'],
                "assort"=>$assort
            );
            $data = $where;
            $data['verify_time'] = time();
            $data['state'] = $state;
            $data['role_id'] = session('role_id');

            $verifyitem = $m_product_verify->where($where)->find();
            if (!$verifyitem) {
                $rid = $m_product_verify->add($data);
            } else {
                if ($m_product_verify->create($data)) {
                    if ($m_product_verify->where(array("verify_id"=>$verifyitem['verify_id']))->save()) {
                        $rid = $verifyitem['verify_id'];
                    }
                }
            }
            if ($data['state'] == 0) {
                $rid = -1;
            }
            M('product')->where($findwhere)->setField($assort . "_verify", $rid);
        }
        $desc = $this->_request('describe', 'trim', "");;

        $product = D('ProductView')->where($findwhere)->find();
        $this->log($assort, $product['product_id'], "验证日志", $desc, 5);

        alert('success',L('审核状态编辑成功', array(L('LOG'))),$_SERVER['HTTP_REFERER']);
    }

    public function show_group() {
        $p = isset($_GET['p'])?$_GET['p']:1;
        $category = M('product_category');


        $product_group = D('ProductGroup')->where(array('league_id'=>session('league_id')))->Page($p.',15')->select();
        $count = count(D('ProductGroup')->where(array('league_id'=>session('league_id')))->select());

        import('@.ORG.Page');// 导入分页类
        $Page = new Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数

        $show = $Page->show();// 分页显示输出
        $this->product_group = $product_group;
        $this->product_group_id = $_GET['product_group_id'];

        $this->categoryList = $category->cache(true)->where(array("enable="=>1, "league_id"=>session('league_id')))->order("order_id asc")->select();
        $this->assign('list',$product_group);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出

        $this->alert=parseAlert();
        $this->display("group"); // 输出模板
    }


    public function field_where($field, $search, $condition) {
        $where = array();
        if ('insurance' == $field) {
            $inwhere = array(
                "category"=>8,
            );
            $inwhere['_string'] = " trade.state!='已撤销' and UNIX_TIMESTAMP() > begin_date and ( UNIX_TIMESTAMP()<end_date or end_date =0 ) ";
            $insurance_query = D("ProductTradeInsideView")->where($inwhere)->field("product_id")->select(false);
            $where['_string'] = "product.product_id ".($search == "是" || $condition=="is"  ? "in" : "not in"). $insurance_query;
        }elseif('product.is_verify' == $field || 'is_verify' == $field){
            if ($search == -1) {
                $cond['product.skill_verify'] = array('eq', -1);
                $cond['_logic'] = 'and';
                $cond['product.basic_verify'] = array('eq', -1);
                $where['_complex'] = $cond;
            } elseif ($search == 0) {
                $cond['_string'] = "(skill_verify!=-1 and basic_verify=0) or (skill_verify=0 and basic_verify!=-1)";
                $where['_complex'] = $cond;
            } else {
                $cond['product.skill_verify'] = array('gt', 0);
                $cond['_logic'] = 'and';
                $cond['product.basic_verify'] = array('gt',0);
                $where['_complex'] = $cond;
            }
        } else {
            $where =  parent::field_where($field, $search, $condition);
        }
        return $where;
    }

    function format_verify_state($value) {
        if ($value['submit_state'] == 0) {
            $value["is_verify"] = "未提交";
        } else {
            if($value["skill_verify"] == -1 || $value["basic_verify"] == -1) {
                $value["is_verify"] = "审核未通过";
            }elseif (($value["skill_submit_time"] > 0 && $value["skill_verify"] == 0) || ($value["basic_submit_time"] > 0 && $value["basic_verify"] == 0)) {
                $value["is_verify"] = "待审核";
            }  elseif(($value['submit_state'] & (0x1|0x2)) != (0x1|0x2)) {
                $value["is_verify"] = "部分审核通过";
            } else{
                $value["is_verify"] = "审核通过";
            }
        }
        return $value;
    }

    public function is_fix_branch_field($value, $branchlock) {
        if ($value['is_owner'] === true)
            return false;

        $branch_id = session('branch_id');
        if ($branch_id == $value['branch_id'])
            return false;

        if (!$branchlock)
            return false;

        $branch = get_branch(session("role_id"));
        if ($branch && self::is_owner($value, $branch))
            return false;

        $where = array("market_product.product_id"=>$value['product_id'], "market.branch_id"=>$branch_id);
        return D("MarketProductBranchView")->where($where)->count() == 0;
    }

    function perfect_list_item($value, $export = false, $branchlock = false) {
        $value = parent::perfect_list_item($value, $export, $branchlock);
        $value['is_owner'] = session('?admin');
        if (!$value['is_owner'] && $value['owner_role_id'] && session("branch_id") != $value['queue_branch_id']) {
            $value['is_owner'] = self::is_fix_branch_field($value, $branchlock);
        }

        $value = $this->format_verify_state($value);
        $value["skill"] = product_skill_show($value['skill']);

        $value["balance"] = number_format($value['balance'] + $value['discount'], 2);
        $value['workstate_name'] = $value['workstate_id'];
        if ($value['queue_branch_id'] > 0) {
            $value['queue_branch'] = D('Manage/BranchView')->cache(true)->where('branch.branch_id = %d ', $value['queue_branch_id'])->find();
            if ($value['workstate_id'] == "面试") {
                $value['workstate_name'] = $value['workstate_name']." - ".$value['queue_branch']['name'];
            }
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

        if ($value['queue_role_id'] > 0) {
            $value['queue'] =  D('RoleView')->cache(true)->where('role.role_id = %d', $value['queue_role_id'])->find();
        }
        $this->check_holiday_time($value['product_id'], $value);

        return $value;
    }


    public function all_search_keyword($module) {
        return array("product.channel_role_model_keyword", "product.channel_role_id_keyword", "product.slug");
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

        if (!session('?admin') && $_GET['lia']) {
            if ($_GET['lia'] == 'belongs') {
                $where['_complex'] = self::make_astrict_where();
            } elseif ($_GET['lia'] == 'self') {
                $where['owner_role_id'] = session('role_id');
            }
        }
        $debar_search_field  = array();

        if (!empty($_GET['assort'])) {
            $params[] = "assort=".trim($_GET['assort']);
        }

        if ($_GET['lia']) {
            $params[] = "lia=" . $_GET['lia'];
        }
        $by = isset($_GET['by']) ? trim($_GET['by']) : '';
        $where['is_delete'] = ($by == "trash" ? 1 : 0);
        if (!$this->is_groupsearch && $by != "trash") {
            $where['submit_state'] = array('neq', 0);
        }

        $byd = isset($_GET['byd']) ? trim($_GET['byd']) : '';
        switch ($byd) {
            case 'today' :
                $where['create_time'] =  array('gt',strtotime(date('Y-m-d', time())));
                break;

            case 'week' :
                $where['create_time'] =  array('gt',(strtotime(date('Y-m-d')) - (date('N', time()) - 1) * 86400));
                break;

            case 'month' :
                $where['create_time'] = array('gt',strtotime(date('Y-m-01', time())));
                break;
        }
        if (!empty($_GET['byd'])) {
            $params[] = "byd=".trim($_GET['byd']);
        }

        if (!empty($_GET['bys'])) {
            $params[] = "bys=".trim($_GET['bys']);
        }

        $byv = isset($_GET['byv']) ? trim($_GET['byv']) : '';
        switch ($byv) {
            case 'yv' :
                $cond['skill_verify'] = array('gt', 0);
                $cond['_logic'] = 'and';
                $cond['basic_verify'] = array('gt',0);
                $where['_complex'] = $cond;
                break;

            case 'yvb' :
                $where['basic_verify'] = array('gt', 0);
                break;

            case 'yvp' :
                $where['skill_verify'] = array('gt', 0);
                break;

            case 'nv' :
                $cond['skill_verify'] = array('eq', -1);
                $cond['_logic'] = 'or';
                $cond['basic_verify'] = array('eq', -1);
                $where['_complex'] = $cond;
                break;

            case 'nvb' :
                $where['basic_verify'] = array('eq', -1);
                break;

            case 'nvp' :
                $where['skill_verify'] = array('eq', -1);
                break;

            case 'cv' :
                $cond['_string'] = "(skill_verify!=-1 and basic_verify=0) or (skill_verify=0 and basic_verify!=-1)";
                $where['_complex'] = $cond;
                break;

            case 'cvb' :
                $cond['skill_verify'] = array('neq', -1);
                $cond['_logic'] = 'and';
                $cond['basic_verify'] = array('eq', 0);
                $where['_complex'] = $cond;
                break;

            case 'cvp' :
                $cond['skill_verify'] = array('eq', 0);
                $cond['_logic'] = 'and';
                $cond['basic_verify'] = array('neq', -1);
                $where['_complex'] = $cond;

                break;

            case 'sbi' :
                $where['submit_state'] = array('eq', 0);
                break;

            case "si": {
                $where['submit_state'] = array('in', array(0, 0x2));
                break;
            }
            case "bi": {
                $where['submit_state'] = array('in', array(0, 0x1));
                break;
            }
        }
        if (!empty($_GET['byv'])) {
            $params[] = "byv=".trim($_GET['byv']);
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

            if ($_GET['catelevel']) {
                $catelevel = array();
                foreach(explode(",",$_GET['catelevel']) as $cl) {
                    $catelevel[] = "(FIND_IN_SET('".$category_id."=".$cl."',catelevel) )";
                }
                $where['_string'] .= " AND ". implode(" OR ", $catelevel);
            }
        }

        if (trim($_GET['by'])) {
            $params[] = "by=" . $_GET['by'];
        }
        $branch_id = $_GET['bybr'] != "" ? $_GET['bybr']:"";

        if ($branch_id != "") {
            $where['product.branch_id'] = array("in", $branch_id);
            $params[] = "bybr=" . trim($_GET['bybr']);
            $this->branch =  $branch_id;
        }
        unset($where['product.onstation_time'],$where['product.holiday_time'],$where['product.idle_time']);

        $where = $this->make_workstate_where($where, $params, $debar_search_field);

        if ($_GET['sign_style']) {
            $this->sign_style = trim($_GET['sign_style']);
        }
        $this->debar_search_field = array_merge($this->debar_search_field, $debar_search_field);
        $this->categoryList = M('product_category')->cache(true)->where(array('league_id'=>session('league_id'), 'enable'=>1))->order("order_id asc")->select();

        self::show_list_index_html($where, $params, "雇员表");
    }


    public function make_list_order(&$params) {
        if ($_GET['act'] == "dispatch") {
            if ($_GET['assort'] == "zb" || $_GET['assort'] == "") {
                $order = "queue_pos asc";
            } else {
                $order = "queue_pos asc";
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

    public function update_relead_model_keyword($newproduct) {
        $model_keyword = array();
        $model_keyword[] = $newproduct['idcode'];
        $model_keyword[] = $newproduct['name'];
        $model_keyword[] = $newproduct['telephone'];
        M("cultivate")->where(array("model"=>"product", "model_id"=>$newproduct['product_id']))->setField("model_id_keyword", implode(chr(10), $model_keyword));

    }

    public function check_commiss_info() {
        if ($_REQUEST['product_id']) {
            $product = D('ProductView')->where(array('product.product_id'=>$this->_request('product_id')))->find();
        }
        if ($commiss = $this->commiss_check_where($product)) {
            $this->ajaxReturn($commiss,"",1);
        } else{
            $this->ajaxReturn(null,"",1);
        }
    }

    public function edit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $findwhere = array('product.product_id'=>$this->_request('product_id'));
        $product = D('ProductView')->where($findwhere)->find();
        if (!$product) {
            alert('error',  L('THERE_IS_NO_PRODUCT'));
        }
        $assort = $this->_request('assort', 'trim', "basic");

        if($this->isPost()){
            if ($this->commiss_check_where($product)) {
                alert('error', " 这个雇员的联系方式在客服模块有登记，请联系客服指派.客服电话: ".defaultinfo('commiss_telephone'), $_SERVER['HTTP_REFERER']);
            }

            $_POST['slug'] = Pinyin($this->_request("name"));
            $_POST['name'] = trim($_POST['name']);

            if ($this->submit_edit($product['product_id'])) {
                if ($_REQUEST['card_pic_base64'])  {
                    $picinfo = update_base64_pic($_REQUEST['card_pic_base64']);
                    if ($picinfo) {
                        $this->save_module_pic_file("card_pic", $product['product_id'], "product", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                    }
                }
                if ($_REQUEST['work_pic_base64'])  {
                    $picinfo = update_base64_pic($_REQUEST['work_pic_base64']);
                    if ($picinfo) {
                        $this->save_module_pic_file("work_pic", $product['product_id'], "product", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                    }
                }

                $change_fields = D('ProductView')->verity_check($product);
                $where = "product_id=".$product['product_id'];
                if ($assort == "basic") {
                    $basic_data = array(
                        "basic_submit_time"=>time(),
                        "submit_state"=>($product['submit_state'] | 0x1)
                    );
                    M("product")->where($where)->setField($basic_data);
                }


                $this->update_keyword($product['product_id']);

                $newproduct = D('ProductView')->where($findwhere)->find();
                if ($newproduct['commiss_id']) {
                    $this->update_commiss_info($newproduct, $newproduct['commiss_id'], $product);
                }
                if ($newproduct['channel_role_model'] != $findwhere['channel_role_model'] || $newproduct['channel_role_id'] != $findwhere['channel_role_id']) {
                    $this->update_correlation_channel_introducer("product", $newproduct);
                }
                $this->update_relead_model_keyword($newproduct);

                $this->add_edit_log($assort, $product['product_id'], "修改基本信息成功。", $change_fields);
                alert('success', L('PRODUCT_EDIT_SUCCESS'), U('product/view', 'id='.$product['product_id']));
            } else {
                alert('error', L('PRODUCT_EDIT_FAILED'), $_SERVER['HTTP_REFERER']);
            }
        } else {
            //雇员图片
            $m_product_images = M('productImages');
            $product['images']['main'] = $m_product_images->where('product_id = %d and is_main = 1', $product['product_id'])->find();
            $product['images']['cardpic'] = $m_product_images->where(array("product_id"=>$product['product_id'],"is_main"=>2))->find();

            $fields_group = product_field_list_html("edit","product",$product, $assort);;
            unset($fields_group[34]);
            $this->fields_group = $fields_group;
            $this->product = $product;
            $this->model_id = $product['product_id'];
            $this->per_change_owner_id = session('?admin') || vali_permission("product", "change_owner_id");
            $this->per_change_origin = self::is_owner_permission($product);
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();;
            $this->display();
        }
    }

    private function is_owner_permission($product) {
        if (session('?admin') || !$product['owner_role_id']) {
            return true;
        }
        $branch = get_branch(session("role_id"));
        if (!$branch) {
            return true;
        }
        return self::is_owner($product, $branch);
    }


    private function add_edit_log($assort, $product_id, $logcont, $change_fields) {
        foreach($change_fields as $v) {
            $logcont.=$v['name']."[".$v['oldvalue']."=>".$v['newvalue']."],";
        }
        $this->log($assort, $product_id, "更新日志",$logcont);
    }


    public function skilledit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $findwhere = array('product.product_id'=>$this->_request('product_id'));
        $product = D('ProductView')->where($findwhere)->find();
		if (!$product) {
            alert_back('error',  L('THERE_IS_NO_PRODUCT'));
		}
        $assort = $this->_request('assort', 'trim', "skill");

        if($this->isPost()){

            foreach($_FILES['pic_certificate_pic']['name'] as $k=>$v) {
                $v2= str_replace(".", "_", $v);
                if ($_POST[$v2] && $_POST[$v2] != "undefined") {
                    $_FILES['pic_certificate_pic']['showname'][$k] = $_POST[$v2];
                }
            }

            if (!$this->submit_edit($product['product_id'])) {
                alert_back('error',  L('PRODUCT_EDIT_FAILED'));
            }
            if ($assort == "skill") {
                $skill_data = array(
                    "skill_submit_time"=>time(),
                    "submit_state"=>($product['submit_state'] | 0x2)
                );
                M("product")->where("product_id=".$product['product_id'])->setField($skill_data);
            }
            $this->add_edit_log($assort, $product['product_id'], "修改专业信息成功。", D('ProductView')->verity_check($product, false));
            alert('success', L('PRODUCT_EDIT_SUCCESS'), U('product/skillview', 'assort=skill&id='.$product['product_id']));

		}else{
            $this->assort = $assort;
            $this->product = $product;
            $fields_group = product_field_list_html("edit","product",$product, $assort);
            unset($fields_group[69]);

            $this->fields_group = $fields_group;
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();;
            $this->display();
        }
	}

    public function product_market_count($product_id) {
        $where = array(
            "market_product.product_id"=>$product_id,
            "market.settle_state"=>array("in", array(913, 914,915,916,917)),
        );
        return D("MarketProductView")->where($where)->count();
    }

    public function eventedit(){
        $product = D('ProductView')->where('product.product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $assort = $this->_request('assort', 'trim', "");
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if($this->isPost()){
            if (isset($_POST['station_state']) && $_POST['station_state'] != $product['station_state']) {
                if(in_array($_REQUEST['station_state'],array('自愿离职','开除','其他未录用'))) {
                    if ($this->product_market_count($product['product_id']) > 0) {
                        alert('error', "雇员有未结算的订单", $_SERVER['HTTP_REFERER']);
                    }
                    $_POST['queue_branch_id'] = -1;
                }
                $_POST['workstate_id'] = in_array($_POST['station_state'],array('自愿离职','开除','其他未录用'))?"空闲":"";
            }

            if ($this->submit_edit($product['product_id'])) {
                $this->add_edit_log($assort, $product['product_id'], "修改日程成功。", D('ProductView')->verity_check($product, false));
                alert('success', L('PRODUCT_EDIT_SUCCESS'), U('product/eventview', 'assort=event&id='.$product['product_id']));
            } else {
                alert('error', L('PRODUCT_EDIT_FAILED'), $_SERVER['HTTP_REFERER']);
            }
        }else{
            $this->settle_state_count = $this->product_market_count($product['product_id']);;
            $this->assort = $assort;
            $this->alert = parseAlert();;
            $this->product = $product;
            $this->fields_group = product_field_list_html("edit","product",$product, $assort);;
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->display();
        }
    }

    private function update_commiss_info($newproduct, $commiss_id, $oldproduct = null) {
        if (!is_array($newproduct)) {
            $newproduct = M("product")->where("product_id=".$newproduct)->find();
        }
        $keyword = array(
            $newproduct['name'],
            $newproduct['idcode'],
            $newproduct['telephone'],
            $newproduct['wechat'],
        );
        $data = array(
            "related_model_name"=>"product",
            "related_model_id"=>$newproduct['product_id'],
            "related_model"=>"[".$newproduct['idcode']."]".$newproduct['name'],
            "related_model_keyword"=>implode(chr(10), $keyword),
        );
        if ($oldproduct) {
            $logcnt = "";
            if ($oldproduct['telephone'] != $newproduct['telephone'])
            {
                $data['telephone'] = $newproduct['telephone'];
                $logcnt .= "雇员：".product_show_html($newproduct)."手机号变更".$oldproduct['telephone']."=>".$newproduct['telephone'];
            }
            if ($oldproduct['wechat'] != $newproduct['wechat'])
            {
                $data['wechat'] = $newproduct['wechat'];
                $logcnt .= "雇员：".product_show_html($newproduct)."微信变更".$oldproduct['wechat']."=>".$newproduct['wechat'];
            }
            if ($oldproduct['qq_number'] != $newproduct['qq_number'])
            {
                $data['qq_number'] = $newproduct['qq_number'];
                $logcnt .= "雇员：".product_show_html($newproduct)."QQ变更".$oldproduct['wechat']."=>".$newproduct['wechat'];
            }
            if ($oldproduct['name'] != $newproduct['name'])
            {
                $data['name'] = $newproduct['name'];
                $logcnt .= "雇员：".product_show_html($newproduct)."名字变更".$oldproduct['wechat']."=>".$newproduct['wechat'];
            }
            if ($logcnt) {
                A("Manage/Commiss")->log("pbg", $commiss_id,"雇员信息变更", $logcnt);
            }
        }
        M("commiss")->where(array("commiss_id"=>$commiss_id))->setField($data);
    }

	public function add() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()) {
            if (!$_POST['telephone'] && !$_POST['qq_number'] && !$_POST['wechat'])
                alert_back('error',  "电话号码， 微信或QQ必须至少填写一项");

            if ($_POST['byc'] != "commiss") {
                if ($this->commiss_check_where(null)) {
                    alert('error', " 这个雇员的联系方式在客服模块有登记，请联系客服指派.客服电话: ".defaultinfo('commiss_telephone'), $_SERVER['HTTP_REFERER']);
                }
            }
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            $_POST['league_id'] = session('league_id');

            if (!($product_id = $this->submit_add())) {
                alert_back('error',  '新建雇员失败！');
            }

            $idcode = sprintf("GY%07d", $product_id);
            $data = array(
                'idcode'=>$idcode,
                'submit_state'=>0x1,
                'basic_submit_time'=>time(),
            );
            if ($_POST['name']) {
                $data['slug'] = Pinyin($_POST['name']);
            }
            if ($_POST['byc'] == "commiss" && $_POST['cmodel_id']) {
                $data['commiss_id'] = $_POST['cmodel_id'];
            }
            if (session("role_id") == "119") {
                $data['branch_id'] = session('branch_id');
            }
            M('product')->where(array('product_id'=>$product_id))->setField($data);

            if ($_POST['card_pic_base64'])  {
                $picinfo = update_base64_pic($_POST['card_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("card_pic", $product_id, "product", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }
            if ($_POST['work_pic_base64'])  {
                $picinfo = update_base64_pic($_POST['work_pic_base64']);
                if ($picinfo) {
                    $this->save_module_pic_file("work_pic", $product_id, "product", $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }

            if ($_POST['byc'] == "commiss" && $_POST['cmodel_id']) {
                $this->update_commiss_info($product_id, $_POST['cmodel_id']);
            }
            $this->update_keyword($product_id);

            $this->alert = parseAlert();
            $this->log("", $product_id, "新建雇员", "新建雇员成功");
            alert('success', "新建雇员成功", U('product/view', 'id='.$product_id));

		}else{
            $this->readcard = isset($_GET['rcard']) ? 1 : 0;

            $module_data = array();
            if ($_GET['byc'] == "commiss") {
                $m_commiss = M("commiss")->where(array("commiss_id"=>$_GET['cmodel_id']))->find();
                if ($m_commiss) {
                    foreach(array('name', 'channel_role_model', "channel_role_id", "telephone", "wechat", "qq_number") as $cm) {
                        $module_data[$cm] = $m_commiss[$cm];
                    }
                }
                $this->cmodel_id = $_GET['cmodel_id'];
                $this->byc = $_GET['byc'];
            }

            $fields_group = product_field_list_html("add","product", $module_data, "basic");
            unset($fields_group[34],$fields_group[62],$fields_group[84]);
            $this->fields_group = $fields_group;
            $this->alert = parseAlert();
            $this->display();
        }
    }

    public function change_skill_veriy_state($product_id) {
        $m_product = M('product');
        $verity = $m_product->where('product_id= %d',$product_id)->getField('is_verify');
        $srcverity = $verity;
        $skill_verify = $m_product->where('product_id= %d',$product_id)->getField("skill_verify");
        if ($skill_verify > 0) {
            $verity -= 1;
        }
        $m_product->where('product_id= %d',$product_id)->setField("skill_verify", 0);
        $m_product->where('product_id= %d',$product_id)->setField("is_verify", $verity);
        if ($srcverity != $verity) {
            M("mProduct")->where(array('mid'=>$product_id))->setField("status", 0);
        }
    }


    public function update_product_category($product) {
        if (!is_array($product)) {
            $product = M("product")->where("product_id=".$product)->find();
        }
        if ($product) {
            $catelevel = "";
            $category = "";

            if ($product['skill']) {
                $vv = array();
                $vvc = array();
                foreach(json_decode($product['skill']) as $k2=>$v2) {
                    $vv[] = $k2;
                    $m_skill_data = M("skill_data")->where(array("product_id"=>$product['product_id'], "category_id"=>$k2))->find();
                    if ($m_skill_data) {
                        $vvc[] = $k2."=".$m_skill_data['level'];
                    }
                }
                $category = implode(",", $vv);
                $catelevel = implode(",", $vvc);
            }
            $data = array(
                "category_id"=>$category,
                "catelevel"=>$catelevel
            );
            M("product")->where("product_id=".$product['product_id'])->setField($data);
        }
    }


    public function submit_productcat($skill_id, $catid, $product_id = null) {
        perfect_model_field_post("skill");
        $m_skill_data = D('SkillData');
        if ($m_skill_data->create() === false) {
            return false;
        }
        if ($skill_id) {
            if ($m_skill_data->where('skill_id=' . $skill_id)->save() === false) {
                $skill_id = false;
            }
        } else {
            $m_skill_data->product_id = $product_id;
            $skill_id = $m_skill_data->add();
        }
        if ($skill_id === false) {
            return false;
        }
        $skilldata = M('SkillData')->where(array('skill_id'=>$skill_id))->find();

        D('SkillView')->updateproductcat($skilldata['product_id']);
        $m_skill_data->verity_check($skilldata);

        $srccatdata = M('product_category')->where('category_id = ' . $skilldata['category_id'])->find();
        $catdata = M('product_category')->where('category_id = ' . $catid)->find();
        if ($skilldata['category_id'] != $this->_request('category_id')) {
            $logc = "修改原雇员类别" . $catdata['name'] . "为" . $srccatdata['name'] . "成功。";
        } else {
            $logc = "修改雇员类别" . $catdata['name'] . "成功。";
        }
        if ($skilldata['level'] != $this->_request('level')) {
            $logc .= "级别由" . $skilldata['level'] . "修改为" . $this->_request('level');
        }
        $this->log("skill", $skilldata['product_id'], "修改雇员类别", $logc, 7);
        return true;
    }

    public function editproductcat() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $skill_id = $this->_request('id');
        $skilldata = M('SkillData')->where('skill_id = %d',$skill_id)->find();
        if (!$skilldata) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $this->product_id = $skilldata['product_id'];

        if($this->isPost()){
            $catid = $_REQUEST['category_id'];
            if (M('SkillData')->where(array('skill_id'=>array('neq', $skill_id),'product_id'=>$skilldata['product_id'],'category_id'=>$catid))->find()) {
                alert('error', L('员工类别不能重复增加'),$_SERVER['HTTP_REFERER']);
            }
            if($this->submit_productcat($skill_id, $catid)){
                $catedata = array("linchuang_score", "yuesao_score", "yuersao_score");
                foreach($catedata as $v) {
                    if (isset($_POST[$v])) {
                        M("product_data")->where(array("product_id"=>$this->product_id))->setField($v, $_POST[$v]);
                        break;
                    }
                }
                $this->update_product_category($this->product_id);
                $this->add_edit_log("skill", $skilldata['product_id'], "修改基本信息成功。", D('SkillView')->verity_check($skilldata));
                alert('success', L('PRODUCT_CAT_EDIT_SUCCESS'), $_REQUEST['refer_url']);
            }else{
                $this->error($skilldata->getError());
            }
        }else{
            $this->product = D('ProductView')->where(array('product.product_id'=>$this->product_id))->find();
            $this->produt_level = json_encode(plevel());
            $this->bconf = json_encode(F("business"));
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();
            $this->skill = $skilldata;
            $fields_group = field_list_html("edit","skill",$skilldata);;
            if ($skilldata['category_id'] == 6) {
                $fields_group[0]['fields'][] = field_html("product", "linchuang_score","edit", $this->product);
            }else if ($skilldata['category_id'] == 2) {
                $fields_group[0]['fields'][] = field_html("product", "yuesao_score","edit", $this->product);
            }else if ($skilldata['category_id'] == 3) {
                $fields_group[0]['fields'][] = field_html("product", "yuersao_score","edit", $this->product);
            }

            $this->fields_group = $fields_group;;
            $this->display();
         }
    }

    public function addproductcat() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if(!isset($_REQUEST['product_id'])){
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $this->product_id = $_REQUEST['product_id'];

        if($this->isPost()) {
            $catid = $this->_request('category_id');
            $where = array(
                'product_id'=>$this->_request('product_id'),
                'category_id'=>$catid
            );
            if (M('SkillData')->where($where)->find()) {
                alert('error', '员工类别不能重复增加', $_SERVER['HTTP_REFERER']);
            }
            $_POST['league_id'] = session('league_id');

            if($this->submit_productcat(0, $catid, $this->product_id)) {
                $this->update_product_category($this->product_id);
                alert('success', L('ADD_PRODUCT_CAT_SUCCESS'), $_SERVER['HTTP_REFERER']);
            } else {
                alert('error', "添加类别失败", $_SERVER['HTTP_REFERER']);
            }
        }
        $this->product = D('ProductView')->where(array('product.product_id'=>$this->product_id))->find();
        $this->produt_level = json_encode(plevel());
        $this->bconf = json_encode(F("business"));
        $this->refer_url=$_SERVER['HTTP_REFERER'];
        $this->exist_skill = D("SkillView")->where(array('product_id'=>$this->product_id))->select();
        $this->fields_group = field_list_html("add","skill");
        $this->alert = parseAlert();
        $this->display();
    }

    public function submit_delproductcat($skilldata) {
        M('skill_data')->where(array('skill_id' => $skilldata['skill_id']))->delete();
        D('Manage/SkillView')->updateproductcat($skilldata['product_id']);
        $this->update_product_category($skilldata['product_id']);

        $catdata = M('product_category')->where('category_id = ' . $skilldata['category_id'])->find();

        $m_product = M("product");
        $verity = $m_product->where('product_id= %d',$skilldata['product_id'])->getField('is_verify');
        $src_verity = $verity;
        $skill_verify = $m_product->where('product_id= %d',$skilldata['product_id'])->getField("skill_verify");
        if ($skill_verify > 0) {
            $verity -= 1;
        }
        $m_product->where('product_id= %d',$skilldata['product_id'])->setField("skill_verify", 0);
        $m_product->where('product_id= %d',$skilldata['product_id'])->setField("is_verify", $verity);
        if ($src_verity != $verity) {
            M("mProduct")->where(array('mid'=>$skilldata['product_id']))->setField("status", 0);
        }

        $logc = "删除类别". $catdata['name'];
        $this->log("skill", $skilldata['product_id'], "删除类别", $logc, 7);
    }


    public function delproductcat(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $skill_id = $this->_request('id');
        $skilldata = D('SkillData')->where('skill_id = %d',$skill_id)->find();
        if (!$skilldata) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $this->submit_delproductcat($skilldata);
        alert('success', L('DELETE_THE_SUCCESS'), $_SERVER['HTTP_REFERER']);
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

    public function viewinfo($assort) {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $product_id) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }

        $where = array("product.product_id"=>$product_id);
        $product = D('ProductView')->where($where)->find();
        if (!$product) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }

        if ($_REQUEST['visitor'] != "trash" && $product['is_delete'] == '1') {
            $url = $_SERVER['HTTP_REFERER'];
            if (MODULE_NAME == "product") {
                $product_view = array(
                    "view",
                    "skillview",
                    "accountview",
                    "eventview",
                    "leadsview",
                    "tradeview");
                if (in_array(ACTION_NAME, $product_view)) {
                    $url = U("product/index");
                }
            }
            alert('error', "雇员已经被删除", $url);
        }
        $product['owner'] = D('RoleView')->where('role.role_id = %d', $product['creator_role_id'])->find();
        $product['defeventstate'] = M('workstate')->where('workstate_id = %d', $product['defeventstate'])->find();

        $product['workstate_name'] = $product['workstate_id'];
        if (in_array($product['station_state'],array('自愿离职','开除','其他未录用', ''))) {
            $product["workstate_id"] = "";
        } elseif($product['queue_branch_id'] > 0 && $product['workstate_id'] == "面试") {
            $queue_branch = D('Manage/BranchView')->cache(true)->where('branch.branch_id = %d ', $product['queue_branch_id'])->find();
            $product['workstate_name'] = $product['workstate_name']." - ".$queue_branch['name'];
        }

        $skill = D('SkillView')->where('skill_data.product_id = %d',$product_id)->select();
        $skill_list = array();
        foreach($skill as $k=>$v) {
            $v['skill_field'] = allfield_list_show("skill",$v);
            if ($v['category_id'] == 6) {
                $v['skill_field'][0]['fields'][] = field_show_html("product", "linchuang_score", $product);
            }else if ($v['category_id'] == 2) {
                $v['skill_field'][0]['fields'][] = field_show_html("product", "yuesao_score", $product);
            }else if ($v['category_id'] == 3) {
                $v['skill_field'][0]['fields'][] = field_show_html("product", "yuersao_score", $product);
            }
            $skill_list[] = $v;
        }
        $product['skill'] = $skill_list;


        if ($assort == "account") {
            $this->clause_additive = $product['product_id'];
            $accountcat = array(
                0=>"全部",
                "-1"=>"支出",
                "1"=>"收入",
                "3,-3"=>"冻结"
            );
            $this->accountcat = $accountcat;
            $this->acat = $_GET['acat'] ? $_GET['acat'] : "0";
            $this->accounts_totals = $this->account_total($this->acat, $product['product_id']);

            $product['balance'] = number_format($product['balance'], 2);
            $product['freeze'] = number_format($product['freeze'], 2);
            $product['actual'] = number_format($product['actual'], 2);
            $product['loans'] = number_format($product['loans'], 2);
            $product['cash'] = number_format($product['cash'], 2);
            $product['trade_surplus_price'] = number_format($product['trade_surplus_price'], 2);
            $product['sum_surplus_price'] = number_format($product['sum_surplus_price'], 2);
        }

        $logcat = array(
            6=>"默认",
            5=>"审核",
            7=>"级别",
            1=>"日志",
        );
        $logwhere = array(
            "product_id"=>$product_id,
            "assort"=>$assort
        );

        $loglist = array();
        $log_ids = M('rLogProduct')->where($logwhere)->getField('log_id', true);
        $logr = M('log')->where('log_id in (%s)', implode(',', $log_ids))->order("update_date desc")->select();
        foreach ($logr as $key=>$value) {
            $catname = $logcat[$value['category_id']];
            if (!$catname) {
                $catname = "默认";
            }
            $loginfo = $value;
            $loginfo['catname'] = $catname;
            $loginfo['owner'] = D('RoleView')->cache(true)->where('role.role_id = %d', $value['role_id'])->find();
            $loglist[$catname][] = $loginfo;
        }
        foreach ($loglist as $key=>$value) {
            $loglist[$key] = array_slice($value, 0, 5);
        }
        $product['log'] = $loglist;
        $this->logcat = $logcat;
        $this->logcatid = array_flip($logcat);

        if ($assort == "market") {
            $market_list = D('MarketProductView')->where('market_product.product_id = %d', $product_id)->select();
            $product['market_count'] = count($market_list);
            $product['market'] =  MarketAction::replenish_market_list($market_list);
            $model_fileds = getFields(array(
                "637",
                "642",
                "742",
                "654",
                "740",
                "664",
                "674",
                "666",
                "667",
            ), false);
            $this->field_array = $this->format_index_fields($model_fileds);
        }

        if ($assort == "cultivate") {
            $cultivate_list = D('CultivateView')->where(array("model_id"=>$product_id, "model"=>"product"))->select();
            $product['cultivate_count'] = count($cultivate_list);
            $product['cultivate'] =  CultivateAction::replenish_cultivate_list($cultivate_list);
            $model_fileds = getFields(array(
                "849",
                "848",
                "868",
                "869",
                "893",
                "870",
                "878",
                "871",
                "877",
                "881",
                "882",
                "883",
                "945",
                "884",
            ), false);
            $this->field_array = $this->format_index_fields($model_fileds);
        }

        if ($assort == "trade") {
            $trade_order = D('ProductTradeView')->where('product_trade.product_id = %d', $product_id)->select();
            foreach($trade_order as $k=>$v) {
                $trade_order[$k] = TradeAction::format_trade_item($v);
            }
            $product['trade'] = $trade_order;
            $this->field_array = $this->format_index_fields(getIndexFields('trade'));
        }

        //雇员图片
        $m_product_images = M('productImages');
        $product['images']['main'] = $m_product_images->where('product_id = %d and is_main = 1', $product_id)->find();
        $product['images']['cardpic'] = $m_product_images->where(array("product_id"=>$product_id,'is_main'=>2))->find();

        $product['is_owner'] = session('?admin');
        if (!$product['is_owner']) {
            $branch_role = get_branch(session("role_id"));
            $branchlock = vali_permission("branchlock", "product");

            $product['is_owner'] =  ($branchlock && $product['owner_role_id'] && $branch_role ? self::is_owner($product, $branch_role) : true);
            if (session("branch_id") != $product['queue_branch_id'] && $this->is_fix_branch_field($product, $branchlock)) {
                $product = self::fix_branch_fields(getBranchFields("product"), $product, in_array($product['owner_role_id'], $branch_role));
            }
        }

        $this->product = $product;
        $this->product_id = $product_id;
        $this->assort = $assort;
        $fields_group = product_field_list_show('product', $product, $assort);
        $tempbasic = $fields_group[0];
        $fields_group[0] = $fields_group[18];
        $fields_group[18] = $tempbasic;
        unset($fields_group[34], $fields_group[69]);
        $this->fields_group = $fields_group;
        $this->refer_url= session("index_refer_url");
        $this->alert = parseAlert();
        $this->display();
    }

    public function astrict() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_REQUEST['id']) {
            alert('error', "参数错误" ,$_SERVER['HTTP_REFERER']);
        }

        $product_id = $this->_request("id");
        $product = M("product")->where(array("product_id"=>$product_id))->find();
        if (!$product) {
            alert('error', "参数错误" ,$_SERVER['HTTP_REFERER']);
        }
        $this->model_id = $product_id;

        $branch = get_branch(session("role_id"));
        if (!session('?admin') && ($branch && !self::is_owner($product, $branch) && $product['owner_role_id'])) {
            alert('error', "您没有权限操作" ,$_SERVER['HTTP_REFERER']);
        }
        $this->user_list = D("AstrictUserView")->where(array("model"=>"product", "model_id"=>$product_id))->select();
        $this->display("Public:def_astrict");
    }

	public function view(){
        $this->viewinfo("basic");
	}

    public function skillview() {
        $this->viewinfo('skill');
    }

    public function berthview() {
        $this->viewinfo('berth');
    }

    public function tradeview() {
        $this->viewinfo('trade');
    }

    public function accountview() {
        $this->viewinfo("account");
    }

    public function eventview() {
        $this->viewinfo('event');
    }

    public function marketview(){
        $this->viewinfo("market");
    }

    public function cultivateview(){
        $this->viewinfo("cultivate");
    }

    public function evaluateview(){
        $this->viewinfo("evaluate");
    }


    public function evaluatemarket_view(){
        $this->viewinfo("evaluate");
    }

    public function leadsview() {
        $this->viewinfo("leads");
    }

    public function completely_delete() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product_ids = $_REQUEST['product_id'] ?$_REQUEST['product_id'] : array($_REQUEST['product_id']);
        if ('' == $product_ids) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT') ,$_SERVER['HTTP_REFERER']);
        }
        $delete_where = array('product_id'=>array("in", $product_ids));

        $product_delete = M('product')->where($delete_where)->delete();
        $product_data_delete = M('product_data')->where($delete_where)->delete();
        if(!$product_delete || !$product_data_delete) {
            alert('error', L('DELETE_FAILED_PLEASE_CONTACT_YOUR_ADMINISTRATOR'),$_SERVER['HTTP_REFERER']);
        }
        M("product_subgroup")->where($delete_where)->delete();
        M('skill_data')->where($delete_where)->delete();
        M('productVerify')->where($delete_where)->delete();
        M("leads_record")->where($delete_where)->delete();
        M("commiss")->where(array("related_model_name"=>"product", "related_model_id"=>array("in", $product_ids)))->setField(array("related_model"=>"","related_model_name"=>"","related_model_id"=>"","related_model_keyword"=>""));

        $account_where = array(
            'clause_additive'=>array("in", $product_ids),
            'account_type'=>'product'
        );
        $account_ids = M('account')->where($account_where)->getField('account_id', true);
        $this->delete_accounts($account_ids);

        $r_module = array('event'=>'r_product_event','task'=>'r_product_task');
        foreach ($product_ids as $value) {
            foreach ($r_module as $key2=>$value2) {
                $module_ids = M($value2)->where('product_id = %d', $value)->getField($key2 . '_id', true);
                M($value2)->where('product_id = %d', $value)->delete();
                if(!is_int($key2)){
                    M($key2)->where($key2 . '_id in (%s)', implode(',', $module_ids))->delete();
                }
            }
            $this->delete_files($value);
        }

        $related_module = array("trade");
        foreach($related_module as $r) {
            $this->related_delete($product_ids, $r);
            if ($r == "trainorder") {
                $r = "train";
            }
            if ($r != "business") {
                M("product_" . $r)->where($delete_where)->delete();
            }
        }
        $this->log("", $product_ids, "删除日志", "从垃圾箱清除雇员成功");
        alert('success', L('DELETE_THE_SUCCESS'), $_SESSION['index_refer_url'] ? $_SESSION['index_refer_url']:U('product/index'));
    }

    public function delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_REQUEST['product_id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'));
        }
        $product_ids = is_array($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array($_REQUEST['product_id']);
        $data = array(
            "is_delete"=>1,
            "delete_time"=>time(),
            "delete_role_id"=>session("role_id")
        );
        M("product")->where(array('product_id'=>array("in", $product_ids)))->setField($data);
        $this->log("", $product_ids, "删除日志", "移动雇员到垃圾箱成功");

        M("m_product")->where(array('mid'=>array("in", $product_ids)))->delete();
        M("m_user")->where(array('model_id'=>array("in", $product_ids), 'model'=>"product"))->delete();

        $product_idcodes = M('product')->where(array('product_id'=>array("in", $product_ids)))->getField("idcode", true);
        if ($product_idcodes) {
            $this->wx_user_enable($product_idcodes, false);
        }
        alert('success', L('DELETE_THE_SUCCESS'), $_SESSION['index_refer_url'] ? $_SESSION['index_refer_url']:U('product/index'));
    }

    public function recover() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_REQUEST['product_id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'));
        }
        $product_ids = is_array($_REQUEST['product_id']) ? $_REQUEST['product_id'] : array($_REQUEST['product_id']);
        $this->submit_recover($product_ids);
        $product_idcodes = M('product')->where(array('product_id'=>array("in", $product_ids)))->getField("idcode", true);
        if ($product_idcodes) {
            $this->wx_user_enable($product_idcodes, true);
        }
        $this->log("", $product_ids, "恢复日志", "从垃圾箱恢复雇员成功");
        alert('success', "恢复成功",$_SERVER['HTTP_REFERER']);
    }

    public function format_dispatch_export_fields($ex) {
        $field_list = array();

        if ($_GET['assort'] == "zb") {
            $field_list[]= array("name"=>"排队序号", "field"=>"queue_pos");;
            $field_list[]= array("name"=>"雇员编号", "field"=>"idcode");;
            $field_list[]= array("name"=>"雇员姓名", "field"=>"name");;
            $field_list[]= array("name"=>"级别", "field"=>"PRODUCT_SKILL_LEVEL");;
            $field_list[]= array("name"=>"籍贯", "field"=>"census");;
            $field_list[]= array("name"=>"排队时间", "field"=>"queue_over_time", "form_type"=>"datetime", "is_showtime"=>true);;
            $field_list[]= array("name"=>"岗位类别", "field"=>"queue_category_id");;
            $field_list[]= array("name"=>"销售老师", "field"=>"BIAOGEHANGLIUKONG");;
            $field_list[]= array("name"=>"调度备注", "field"=>"queue_describe");;

        } else {
            $field_list[]= array("name"=>"排队序号", "field"=>"queue_pos");;
            $field_list[]= array("name"=>"雇员编号", "field"=>"idcode");;
            $field_list[]= array("name"=>"雇员姓名", "field"=>"name");;
            $field_list[]= array("name"=>"级别", "field"=>"PRODUCT_SKILL_LEVEL");;
            $field_list[]= array("name"=>"籍贯", "field"=>"census");;
            $field_list[]= array("name"=>"到店日期", "field"=>"queue_over_time", "form_type"=>"datetime", "is_showtime"=>true);;
            $field_list[]= array("name"=>"上户日期", "field"=>"BIAOGEHANGLIUKONG");;
            $field_list[]= array("name"=>"销售老师", "field"=>"BIAOGEHANGLIUKONG");;
            $field_list[]= array("name"=>"调度备注", "field"=>"queue_describe");;
        }

        return $field_list;
    }

    public function format_excel_fields($ex) {
        $where = array(
            "model"=>"product",
            'form_type'=>array("not in",array(
                "pic","video","file"
            )),
            "field_id"=>array("not in",array(
                "467","106",
            ))
        );
        $field_list = M('Fields')->cache(true)->where($where)->order('order_id')->select();
        return $field_list;
    }

    public function listDialog(){
        if ($this->isAjax() === false) {
            return $this->display();
        }

        $data_field = array(
            array(
                "field"=>"product_id",
                "order"=>"product_id"
            ),
            array(
                "field"=>"product_idcode",
                "order"=>"product_id"
            ),
            array(
                "field"=>"product_name",
                "order"=>"product_name"
            ),
            array(
                "field"=>"census",
                "order"=>"census"
            ),
            array(
                "field"=>"workstate_name",
                "order"=>"workstate_id"
            ),
        );

        $m_model_name = $_GET['model']? $_GET['model']."View":"ProductView";
        $where = $this->parse_dialog_where();
        $this->ajaxReturn(make_data_list($m_model_name, $where, $data_field, array($this, "format_dialog_item")),'JSON');
    }

    public function market_scettp_per(){
        if ($this->isAjax() === false) {
            return $this->display("listmulitdialog");
        }
        if ($_GET['set']) {
            M("product")->where(array("product_id"=>array("in", $_GET['set'])))->setField("queue_auth", 1);
        }
        if ($_GET['unset']) {
            M("product")->where(array("product_id"=>array("in", $_GET['unset'])))->setField("queue_auth", 0);
        }
        $this->ajaxReturn("OK",'JSON');
    }


    public function format_dialog_item($val) {
        $val["product_id"] = array(
            "product_id"=>$val['product_id'],
            "queue_auth"=>$val['queue_auth']
        );

        $val['workstate_name'] = $val['workstate_id'];
        if ($val['queue_branch_id'] > 0 && $val['workstate_id'] == "面试") {
            $val['queue_branch'] = M('branch')->cache(true)->where('branch_id = %d ', $val['queue_branch_id'])->find();
            $val['workstate_name'] = $val['workstate_name']." - ".$val['queue_branch']['name'];
        }
        return $val;
    }

    public function parse_dialog_where($model) {
        $where = parent::parse_dialog_where($model);
        $where['product.is_delete'] = 0;
        if ($_GET['category_id']) {
            if ($_GET['category_id'] != -1) {
                $category_id_arr = $_GET['category_id'];
                if (!is_array($category_id_arr)) {
                    $category_id_arr = array($category_id_arr);
                }
                $sqllis = array();
                foreach($category_id_arr as $v) {
                    $sqllis[] = "(FIND_IN_SET('".$v."',category_id) )";
                }
                $where['_string'] = implode(" OR ", $sqllis);
            } else {
                $where['product.skill'] = "";
            }
        }
        if ($_GET['station_state']) {
            $where['station_state'] = array("in", is_array($_GET['station_state'])?$_GET['station_state']:array($_GET['station_state']));
        }
        if ($_GET['queue_state']) {
            $where['queue_state'] =trim($_GET['queue_state']);
        }
        if ($_GET['workstate_id']) {
            $where['workstate_id'] = array("in", is_array($_GET['workstate_id'])?$_GET['workstate_id']:array($_GET['workstate_id']));
        }
        if ($_GET['model']) {
            $where['_string'] =trim($_GET['query']);
        }
        $where['league_id'] = session('league_id');

        return $where;
    }


    public function getproduct(){
        if (!isset($_GET['product_id'])) {
            $this->ajaxReturn(null,"",0);
        }
        $product_id = $this->_request("product_id");
        $product = D('product')->where(array('product_id'=>$product_id))->find();
        if ($product) {
            $skill_info = D('SkillView')->where(array('skill_data.product_id'=>$product_id))->select();
            foreach($skill_info as $k=>$v) {
                $product['skill_info'][$v['category_id']] = $v;
            }
        }
        $product['insurance'] = product_insurance_show($product['product_id']);
        if ($product['owner_role_id']) {
            $product['owner_role_name'] = M("user")->cache(true)->where(array("role_id"=>$product['owner_role_id']))->getField("name");
        }
        $this->ajaxReturn($product,"",$product ? 1 : 0);
    }

    public function getskill(){
        if (!isset($_GET['product_id'])) {
            $this->ajaxReturn(null,"",0);
        }
        if (!isset($_GET['category_id'])) {
            $this->ajaxReturn(null,"",0);
        }
        $skill_info = M('skill_data')->where(array('product_id'=>$_GET['product_id'],'category_id'=>$_GET['category_id']))->find();
        $this->ajaxReturn($skill_info,"",$skill_info ? 1: 0);
    }

    public function getcategory(){
        $where = array("enable"=>1, "league_id"=>session('league_id'));
        if ($_GET['id']) {
            $where['category_id'] = $this->_request("id");
        }
        $category_list = M('product_category')->cache(true)->where($where)->select();
        foreach($category_list as $k=>$v) {
            $category_list[$k]['bconf'] = unserialize($v['bconf']);
        }
        $this->ajaxReturn($category_list, '', 1);
    }

    public function getcategorylevelfield() {
        $this->ajaxReturn(field_html("skill", "level"), '', 1);
    }

    public function getability() {
        if (!isset($_GET['product_id'])) {
            $this->ajaxReturn(null,"",0);
        }
        if (!isset($_GET['category_id'])) {
            $this->ajaxReturn(null,"",0);
        }

        $skill_info = M('skill_data')->where(array(
            'product_id'=>$_GET['product_id'],
            'category_id'=>$_GET['category_id']))->find();

        $skill_info["ability"] = explode(chr(10),$skill_info["ability"]);
        $skill_info["experience"] = explode(chr(10),$skill_info["experience"]);

        $category = M('product_category')->cache(true)->where(array('category_id'=>$this->_request("category_id")))->find();

        if ($category) {
            foreach(explode(chr(10),$category["ability"]) as $k=>$v) {
                if ($v) {
                    $ability[$k] = array($v, in_array($v, $skill_info["ability"]) ? "1" : "0");
                }
            }
            $category["ability"] = $ability;

            foreach(explode(chr(10),$category["experience"]) as $k=>$v) {
                if ($v) {
                    $experience[$k] = array($v, in_array($v, $skill_info["experience"]) ? "1" : "0");
                }
            }
            $category["experience"] = $experience;
            $category["bconf"] = unserialize($category["bconf"]);
        }
        $this->ajaxReturn($category, '', 1);
    }

    public function category_sort(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if(isset($_GET['postion'])){
            $product_category = M('product_category');
            foreach($_GET['postion'] AS $k=>$pos) {
                $product_category->where(array('category_id'=>$pos))->setField('order_id', $k);
            }
            $this->ajaxReturn('1', "排序成功", 1);
        } else {
            $this->ajaxReturn('0', "排序失败", 1);
        }
    }

    public function category(){
		$category_list = M('product_category')->cache(true)->where(array("enable"=>1, "league_id"=>session('league_id')))->order("order_id asc")->select();
		foreach($category_list as $key=>$value){
			$product = M('product');
			$count = $product->where(array("category_id"=>$value['category_id'], "league_id"=>session('league_id')))->count();
            if ($value['serve_id']) {
                $category_list[$key]['def_serve'] = M("serve")->where("serve_id=".$value['serve_id'])->find();
            }
            $category_list[$key]['count'] = $count;
			$category_list[$key]['list'] = $product->where(array("category_id"=>$value['category_id'], "league_id"=>session('league_id')))->select();
		}
		$this->alert=parseAlert();
		$this->assign('category_list', $category_list);
		$this->display();
	}
	
	public function category_add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (isset($_POST['name']) && $_POST['name'] != '') {
            $_POST['league_id'] = session('league_id');
			$category = M('product_category');
			if ($category->create()) {
				if ($category->add()) {
                    delete_cache_temp();
					alert('success', L('ADD_SUCCESSFUL'),$_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
				}				
			} else {	
				alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
			}
		}else{
			$category = M('product_category');			
			$category_list = $category->where(array("enable"=>1, "league_id"=>session('league_id')))->select();
			$this->assign('category_list', getSubCategory(0, $category_list, ''));
			$this->display();
		}
	}
	
	public function category_delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product_category = M('Product_category');
		$product = M('product');
		if($_POST['category_list']){
			foreach($_POST['category_list'] as $value){
				if($product->where('category_id = %d',$value)->select()){
					$name = $product_category->where('category_id = %d',$value)->getField('name');
					alert('error', L('UNDER_THE_CATEGORY_OF_PRODUCTS',array($name)),$_SERVER['HTTP_REFERER']);
				}
				if($product_category->where('parent_id = %d',$value)->select()){
					$name = $product_category->where('category_id = %d',$value)->getField('name');
					alert('error', L('UNDER_THE_CATEGORY_OF_CHILD_CATEGORIES',array($name)),$_SERVER['HTTP_REFERER']);
				}
			}
			if($product_category->where('category_id in (%s)', join($_POST['category_list'],','))->delete()){
                delete_cache_temp();
				alert('success', L('CATEGORY_WAS_REMOVED_SUCCESSFULLY') ,$_SERVER['HTTP_REFERER']);
			}else{
				alert('error', L('CATEGORY_WAS_REMOVED_FAILED') ,$_SERVER['HTTP_REFERER']);
			}
		}elseif($_GET['id']){
			if($product->where('category_id = %d',$_GET['id'])->select()){
				$name = $product_category->where('category_id = %d',$_GET['id'])->getField('name');
				alert('error', L('UNDER_THE_CATEGORY_OF_PRODUCTS',array($name)),$_SERVER['HTTP_REFERER']);
			}
			if($product_category->where('parent_id = %d',$_GET['id'])->select()){
                $name = $product_category->where('category_id = %d',$_GET['id'])->getField('name');
                alert('error', L('UNDER_THE_CATEGORY_OF_CHILD_CATEGORIES',array($name)),$_SERVER['HTTP_REFERER']);
            }
            if($product_category->where('category_id = %d',$_GET['id'])->delete()){
                delete_cache_temp();
				alert('success', L('CATEGORY_WAS_REMOVED_SUCCESSFULLY') ,$_SERVER['HTTP_REFERER']);
			}else{
				alert('error', L('CATEGORY_WAS_REMOVED_FAILED') ,$_SERVER['HTTP_REFERER']);
			}
		}else{
			alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
		}	
	}
	
	//编辑雇员分类信息
	public function category_edit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $catepct = array("n", "1", "2", "3", "4", "5", "g", "s");
		if($_GET['id']){
			$product_category = M('product_category');			
			$category_list = $product_category->cache(true)->where(array("enable"=>1, "league_id"=>session('league_id')))->select();

			$this->assign('category_list', getSubCategory(0, $category_list, ''));
			$product_category = M('product_category');
			$categoryList = $product_category->where(array("enable"=>1, "league_id"=>session('league_id')))->select();	//读取分类列表 加载下拉框
			foreach($categoryList as $key=>$value){
				if($value['category_id'] == $_GET['id']){
					unset($categoryList[$key]);
				}
			}
			$this->category_list = $categoryList;
            $this->catepct = $catepct;
			$this->temp =$product_category->cache(true)->where('category_id = ' . $_GET['id'])->find();
            $this->bconf = unserialize($this->temp["bconf"]);
            $this->fields_group = field_list_html_edit("product_category", $this->temp);
            $this->refer_url = U("product/category", "model=skill");
			$this->display();
		}elseif($_POST['category_id']){
            $data = array();
            foreach($catepct as $v) {
                $fields = array(
                    "customer_earnest_type",
                    "customer_earnest_scale",
                    "customer_earnest_fasten",
                    "customer_earnest_limit",
                    "deposit",
                    "salary",
                    'agency_gather',
                    "recess_day",
                    "agency_scale",
                    "freeze_type",
                    "freeze_scale",
                    "freeze_fasten",
                );
                foreach($fields as $v2) {
                    $data[$v][$v2] = $_REQUEST[$v2."_".$v];
                }
            }
            $_POST["bconf"] = serialize($data);

			$product_category = M('product_category');	
			$product_category->create();
			$product_category->save();
            delete_cache_temp();
            alert('success',L('MODIFY_THE_CATEGORY_INFORMATION_SUCCESSFULLY'),U("product/category", "model=skill"));

        }else{
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
		}
	}
	
	//图片排序
	public function sortImg(){
		$images_files = $_POST['images_arr'];
		$imagesArr = explode(',', $images_files);
		if($imagesArr){
			$m_product_images = M('productImages');
			//拖动后的listorder
			$original_listorder = $m_product_images->where('images_id in (%s)',$images_files)->getField('listorder',true);
			sort($original_listorder);//按顺序排列
			
			//交换顺序
			foreach($imagesArr as $k=>$v){
				$m_product_images->where('images_id = %d',$v)->setField('listorder',$original_listorder[$k]);
			}
			$this->ajaxReturn('success', '排序成功！', 1);
		}
	}

    public function qrcode(){
        $product_id = intval($_GET['product_id']);
        $png_temp_dir = UPLOAD_PATH.'/qrpng/';
        $filename = $png_temp_dir.$product_id.'.png';
        if (!is_dir($png_temp_dir) && !mkdir($png_temp_dir, 0777, true)) { echo 3;$this->error('二维码保存目录不可写'); }

        $qrOpt = "http://c.ourbaby.cc/index.php?m=Index&a=namecard&id=".$product_id;
        import("@.ORG.QRCode.qrlib");
        QRcode::png($qrOpt, $filename, 'M', 4, 2);
        header('Content-type: image/png');
        header("Content-Disposition: attachment; filename=".$product_id.'.png');
        echo file_get_contents($filename);
        unlink($filename);

    }

    public function eventpanel() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (!$_GET['product_id']) {
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        $product_id = intval($_GET['product_id']);
        $product = D('ProductView')->where('product.product_id = %d', $product_id)->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $this->product_id = $product_id;
        $this->product = $product;
        $this->alert = parseAlert();
        $this->display();
    }

    public function getevent() {
        if (!$_REQUEST['product_id'] || !$_GET['start_date'] || !$_GET['end_date']) {
            $this->ajaxReturn(null, "", 0);
        }
        $product_id = intval($_REQUEST['product_id']);
        $this->ajaxReturn($this->checkevent($product_id, $_REQUEST['start_date'], $_REQUEST['end_date']));
    }

    public function event_reset() {
        if (!$_REQUEST['event_id']) {
            $this->ajaxReturn(null, "JSON");
        }
        $event = M('event')->where(array("event_id"=>$_REQUEST['event_id']))->find();
        if ($event) {
            D("EventView")->reset_event($event['event_id'], ($event['isclose'] == 1 ? 0 : 1));
        }
        $this->ajaxReturn($event, "JSON");
    }

    public function event_delete() {
        if (!$_REQUEST['event_id']) {
            $this->ajaxReturn(null, "JSON");
        }
        D("EventView")->delete_event($_REQUEST['event_id']);
        $this->ajaxReturn("OK", "JSON");
    }

    public function listevent() {
        if (!$_REQUEST['product_id'] || !$_REQUEST['start_date'] || !$_REQUEST['end_date']) {
            $this->ajaxReturn(null, "JSON");
        }
        $product_id = intval($_REQUEST['product_id']);
        $events = $this->checkevent($product_id, $_REQUEST['start_date'], $_REQUEST['end_date']);
        $product_event = $events[$product_id];
        foreach($product_event as $k=>$v) {
            if ($v["workstate_id"] == "上岗") {
                $product_event[$k]["operator"] = "";
            } else {
                $product_event[$k]["operator"] = "<a onclick='onclick_eventedit(".$v["event_id"].");'  href='javascript:void(0);'>编辑</a>";
                $product_event[$k]["operator"] .= "<a onclick='onclick_eventdelete(".$v["event_id"].");'  href='javascript:void(0);'>&nbsp删除</a>";
            }
        }
        $this->ajaxReturn(array("data"=>$product_event), "JSON");
    }

    public function addevent(){
        if (!$_REQUEST['product_id']) {
            alert('error',"没有指定雇员", $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if($this->isPost()){
            if(!$_REQUEST['workstate_id']){
                alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
            }
            $workstate_id = trim($_REQUEST['workstate_id']);
            $event_id = trim($_REQUEST['event_id']);

            $start_date = strtotime($_REQUEST['start_date']);
            $end_date = ($_REQUEST['end_date'] ? strtotime($_REQUEST['end_date']) : 0);
            if ($event_id) {
                if ($workstate_id == "5") {
                    M("event")->where(array("event_id"=>$event_id))->delete();
                    M("r_product_event")->where(array("event_id"=>$event_id))->delete();
                } else {
                    D("EventView")->change_event($event_id, $workstate_id, $start_date, $end_date);
                }
            } elseif ($workstate_id != "5") {
                $event_id = D("EventView")->add_event($workstate_id, $start_date, $end_date);
                if ($event_id) {
                    D("EventView")->related_event($event_id, $_REQUEST['product_id']);
                }
            }
            $this->log("", $_REQUEST['product_id'], "添加日程成功", "");
            alert('success', "添加日程成功", $_SERVER['HTTP_REFERER']);
        }else{
            if ($_REQUEST['workstate_id']) {
                $this->workstate_id = $this->_request("workstate_id");
            }
            if ($_REQUEST['event_id']) {
                $this->event_id = $this->_request("event_id");
            }
            $this->product_id = $this->_request("product_id");
            $this->start_date = $this->_request("start_date");
            $this->workstate = M('workstate')->where("operator=0")->cache(true)->select();
            $this->alert = parseAlert();
            $this->display();
        }
    }

    public function eventdialog() {
        if (!$_REQUEST['product_id']) {
            $this->ajaxReturn(null, "", 0);
        }
        $event_id = trim($_REQUEST['event_id']);
        $product_id = $this->_request("product_id");

        if($this->isPost()){
            $start_date = strtotime($_REQUEST['start_date']);
            $end_date = strtotime($_REQUEST['end_date']);
            $description = trim($_REQUEST['description']);
            $workstate_id = trim($_REQUEST['workstate_id']);

            if ($event_id) {
                D("EventView")->change_event($event_id, $workstate_id, $start_date, $end_date, $description);
            } else{
                $event_id = D("EventView")->add_event($workstate_id, $start_date, $end_date, $description);
                if ($event_id) {
                    D("EventView")->related_event($event_id, $product_id);
                }
            }
            $this->update_leave_state($product_id);

            $event = M("event")->where(array("event_id"=>$event_id))->find();
            $this->ajaxReturn($event);
        }else{
            if ($event_id) {
                $this->event = M("event")->where(array("event_id"=>$event_id))->find();
            }
            $this->product_id = $product_id;
            $this->display();
        }
    }

    public function change_leave_state() {
        if (!$_REQUEST['product_id'] || !$_REQUEST['leave_state']) {
            $this->ajaxReturn("错误的参数");
        }
        $product_id = $this->_request("product_id");
        M("product")->where(array("product_id"=>$product_id))->setField("leave_state",$_REQUEST['leave_state']);
        $this->ajaxReturn("操作成功");
    }

    private function update_leave_state($product_id) {
        $cur_time = strtotime(date("Y-m-d", time()));
        $where = array(
            'product_id' => $product_id,
            'start_date' => array("elt", $cur_time),
            'end_date' => array(array('egt', $cur_time), array('eq', 0), 'or'),
            'workstate_id'=>array("in", array("请假","公司培训","司外订单"))
        );
        M("product")->where(array("product_id"=>$product_id))->setField("leave_state", M('event')->where($where)->find()?"请假中":"在职");
    }

    public function change_authInfo() {
        if (!isset($_GET['product_id'])) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $product_id = intval($this->_request('product_id'));
        $product = D('ProductView')->where('product.product_id = %d',$product_id)->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        if($_POST['submit']){
            $this->submit_auth($product);
            if($_POST['refer_url']) {
                alert('success', L('EDIT_PRODUCT_AUTH_SUCCESS'), $_POST['refer_url']);
            }
            else{
                alert('success', L('EDIT_PRODUCT_AUTH_SUCCESS'), U('product/index'));
            }

        }else{
            $user_product = M('mUser')->where(array('model'=>"product",'model_id'=>$this->_request('product_id')))->find();
            if ($user_product) {
                $this->username = $user_product['username'];
            } else {
                $this->username = $product['telephone'];
            }
            $this->password = ($user_product ? $user_product['password'] : "234567");

            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->product_id = $product_id;
            $this->display();
        }
    }


    public function changedefstate() {
        $product_id = $this->_request('id');
        $product = D('Product')->where(array("product_id"=>$product_id))->find();
        if (!$product) {
            alert('error', L("WATING_FOR_SERVER_CALL_BACK"), $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if($_POST['submit']){
            $defeventstate = $_POST['defeventstate'];
            $data = array(
                "defeventstate" =>$defeventstate,
            );

            D('Product')->where(array("product_id"=>$product_id))->setField($data);
            $this->log("", $product_id, "改变状态成功", "");
            alert('success',L('改变状态成功'), $_SERVER['HTTP_REFERER']);

        }elseif($_GET['id']){
            $this->product_id = $product_id;
            $this->defeventstate = $product['defeventstate'];
            $this->workstate = M('workstate')->where("operator=0")->select();
            $this->display();
        } else {
            alert('error', L("WATING_FOR_SERVER_CALL_BACK"), $_SERVER['HTTP_REFERER']);
        }
    }

    public function format_export_pic($vo) {
        $html = "";
        foreach ($vo['piclist'] as $pick => $picv) {
            $html .='<span class="box-secondary"> <img src="' . $picv['path'] . '" width=100px height=100px class="thumbnail cardpicthumb" alt="'.$picv['name'].'"></span>';
        }
        $vo['html'] = $html;
        return $vo;
    }

    public function exportprint() {
        $product_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        if (0 == $product_id) {
            exit(0);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $product = D('ProductView')->where('product.product_id = %d',$product_id)->find();
        $product['owner'] = D('RoleView')->cache(true)->where('role.role_id = %d', $product['creator_role_id'])->find();

        //雇员图片
        $m_product_images = M('productImages');
        $product['images']['main'] = $m_product_images->where('product_id = %d and is_main = 1', $product_id)->find();

        if (in_array($product['station_state'],array('自愿离职','开除','其他未录用', ''))) {
            $product["workstate_name"] = "";
            $product["workstate_id"] = "";
        }

        $skill_list = array();
        foreach(D('SkillView')->where('skill_data.product_id = %d',$product_id)->select() as $k=>$v) {
            $v['skill_field'] = allfield_list_show("skill",$v);
            $skill_list[] = $v;
        }
        $product['skill'] = $skill_list;

        $pic_output_fields = array();
        $cardid_pic_output_fields = array();
        $basic_output_fields = array();
        foreach(product_field_list_show('product', $product, "basic", "print") as $k=>$gvo) {
            $field_print_cnt = 0;
            foreach($gvo['fields'] as $kvo=>$vo) {
                if ($vo['operating'] != '4' && $vo['in_print']){
                    if ($vo['form_type'] == "pic") {
                        if ($vo['field'] == "IDcard") {
                            $cardid_pic_output_fields = $vo;;
                        } else {
                            $pic_output_fields[$vo['field']] = self::format_export_pic($vo);
                        }
                        unset($gvo['fields'][$kvo]);
                    } else {
                        $field_print_cnt++;
                    }
                } else {
                    unset($gvo['fields'][$kvo]);
                }
            }
            if ($field_print_cnt) {
                $basic_output_fields[$k] = $gvo;
            }
        }
        $this->cardid_pic_output_fields = $cardid_pic_output_fields;
        $this->basic_output_fields = $basic_output_fields;

        foreach(product_field_list_show('product', $product, "skill", "print") as $k=>$gvo) {
            $field_print_cnt = 0;
            foreach($gvo['fields'] as $kvo=>$vo) {
                if ($vo['operating'] != '4' && $vo['in_print']){
                    $field_print_cnt++;
                    if ($vo['form_type'] == "pic") {
                        $pic_output_fields[$vo['field']] = self::format_export_pic($vo);
                        unset($gvo['fields'][$kvo]);
                    } else {
                        $field_print_cnt++;
                    }
                } else {
                    unset($gvo['fields'][$kvo]);
                }
            }
            if ($field_print_cnt) {
                $skill_output_fields[$k] = $gvo;
            }
        }
        $pic_output_fields["health_pic"] = self::format_export_pic(field_show_html('product',"health_pic", $product));

        $this->skill_output_fields = $skill_output_fields;
        $this->pic_output_fields = $pic_output_fields;
        $this->product = $product;
        $this->log("", $product['product_id'], "导出雇员信息", "");
        $this->display();
    }

    public function analytics() {
        $params = array();
        $assort = $_GET['assort'] ? $_GET['assort'] : "state";
        if ($_GET['assort']) {
            $params[] = "assort=" . $_GET['assort'];
        }
        $time_limit = self::default_statistics_time($params);

        if ($assort == "state") {
            $create_time= array(array('elt',$time_limit[1]),array('egt',$time_limit[0]), 'and');

            $where = array(
                '_string'=>"skill_verify=-1 or basic_verify=-1",
                'create_time'=>$create_time
            );
            $verify_state_report_list["审核未通过"] = M('Product')->where($where)->count();
            $where['_string'] = "skill_verify=0 or basic_verify=0";
            $verify_state_report_list["待审核"] = M('Product')->where($where)->count();
            $where['_string'] = "skill_verify>0 and basic_verify>0";
            $verify_state_report_list["审核通过"] = M('Product')->where($where)->count();
            $this->verify_state_report_list = $verify_state_report_list;

            foreach(array("其他录用", "签约", "试用", "自愿离职", "开除", "其他未录用",  "无底薪签约") as $s) {
                $where = array(
                    'station_state'=>$s,
                    'create_time'=>$create_time
                );
                $station_state_report_list[$s] = M('Product')->where($where)->count();
            }
            $this->station_state_report_list = $station_state_report_list;

            foreach(array("上岗"=>"4", "空闲"=>"5","预约未上户"=>"3", "休息"=>"6") as $k=>$s) {
                $where = array(
                    'workstate_id'=>$s,
                    'create_time'=>$create_time
                );
                $workstate_state_report_list[$k] = M('Product')->where($where)->count();
            }
            $this->workstate_state_report_list = $workstate_state_report_list;

            foreach(array("待培训", "在培训","已完成") as $s) {
                $where = array(
                    'train_state'=>$s,
                    'create_time'=>$create_time
                );
                $train_state_state_report_list[$s] = M('Product')->where($where)->count();
            }
            $this->train_state_state_report_list = $train_state_state_report_list;

            $pc = M("product_category")->where(array('league_id'=>session('league_id'), 'enable'=>1))->select();
            foreach($pc as $v) {
                $cat_count_report_list[$v['name']] = M("skill_data")->where(array("category_id"=>$v['category_id']))->count();
            }
            $this->cat_count_report_list = $cat_count_report_list;
            $this->cat_count_report_list_cnt = count($cat_count_report_list) + 1;

            $statistics = array();
            $statistics['all_sum'] = M('Product')->count();
            $statistics['time_range_sum'] = M('Product')->where(array('create_time'=>$create_time))->count();
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
            self::default_cycle_basis_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab, "雇员");
        }

        if ($assort == "catnewly") {
            $tab = "_".($_GET['tab'] ? $_GET['tab'] : "charts");
            if ($_GET['tab']) {
                $params[] = "tab=".$_GET['tab'];
            }

            $cycle = $_GET['cycle'] ? $_GET['cycle'] : "week";
            if ($_GET['cycle']) {
                $params[] = "cycle=".$_GET['cycle'];
            }

            self::default_cat_cycle_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab);
        }

        $this->parameter = implode('&', $params);
        $this->assort = $assort;
        $this->alert = parseAlert();
        $this->display($assort."_analytics".$tab);
    }

    public function cat_cycle_date_array_by_catid($start_time_base, $end_time, $cycle, $catid) {
        $cat_data_array = array();
        $start_time = germ_cycle($start_time_base, $cycle);
        while($start_time <= $end_time) {
            $time_begin = $start_time;
            $time_end = $start_time = ($cycle == "quarter" ? aquarter($time_begin, 1) : strtotime('+1 '.$cycle, $time_begin));

            $where_cycle_create['create_time'] = array(array('lt',$time_end),array('gt',$time_begin), 'and');
            $where_cycle_create['skill_data.category_id'] = $catid;
            $cat_data_array[] = D("ProductSkillView")->where($where_cycle_create)->count();
        }
        return $cat_data_array;
    }


    public function cat_cycle_date_array_by_cycle($start_time_base, $end_time, $cycle, $catid) {
        $where = array("enable"=>1);
        if ($catid) {
            $where['category_id'] = $catid;
        }
        $pc = M("product_category")->where($where)->select();
        $start_time = germ_cycle($start_time_base, $cycle);
        while($start_time <= $end_time) {
            $time_begin = $start_time;
            $time_end = $start_time = ($cycle == "quarter" ? aquarter($time_begin, 1) : strtotime('+1 '.$cycle, $time_begin));
            $cat_cnt_array = array();
            foreach($pc as $v) {
                $where_cycle_create['create_time'] = array(array('lt',$time_end),array('gt',$time_begin), 'and');
                $where_cycle_create['skill_data.category_id'] = $v['category_id'];
                $cat_cnt_array[$v['name']] = D("ProductSkillView")->where($where_cycle_create)->count();
            }
            $cat_count_charts[] = $cat_cnt_array;
        }
        return $cat_count_charts;
    }

    public function update_keyword($product) {
        if(!is_array($product)) {
            $product = M("product")->where(array("product_id"=>$product))->find();
        }
        $keyword = array();

        $data = array(
            "keyword"=>implode(chr(10), $keyword)
        );
        $data = make_channel_model_keyword($product['channel_role_model'], $product['channel_role_id'], $data);
        M("product")->where(array("product_id"=>$product['product_id']))->setField($data);
    }


    public function reset_keyword() {
        foreach(M("product")->select() as $v) {
            $this->update_keyword($v);
        }
    }

    public function reset_role() {
        foreach(M("product")->select() as $v) {
            $data = array(
                "cultivate_status"=>"未培训",
            );

            $where = array(
                "model_id"=>$v['product_id'],
                "status_id"=>2
            );
            if (M("cultivate")->where($where)->count() > 0) {
                $data['cultivate_status'] = "在培训";
            } else {
                $where['status_id']=1;
                if (M("cultivate")->where($where)->count() > 0) {
                    $data['cultivate_status'] = "待培训";
                } else {
                    $where['status_id']=3;
                    if (M("cultivate")->where($where)->count() > 0) {
                        $data['cultivate_status'] = "已完成";
                    }
                }
            }

            M("product")->where("product_id=".$v['product_id'])->setField($data);
        }

        echo("slkdf");


    }


    public function skill_update_task() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $state = $this->_request("state");
        $product_id = $this->_request("product_id");
        if ($product_id) {
            $product = D('ProductView')->where('product.product_id = %d',$product_id)->find();
            if ($product) {
                $desc = $this->_request("desc");
                $param = array();
                if ($state == 1 && $desc) {
                    $param = array("desc"=>$desc);
                }
                M("skill_data")->where(array("skill_id"=>$_REQUEST['sid']))->setField("status",$state);
                send_notice($state == 0 ? 72 : 73, "product", $product, $param, 4);
            }
        }
        alert('success', "升级申请提交成功", $_SERVER['HTTP_REFERER']);
    }

    public function healthy_expire_task() {
        $product_id = $this->_request("id");
        if ($product_id) {
            $product = D('ProductView')->where('product.product_id = %d',$product_id)->find();
            if ($product) {
                $state = $this->_request("s");
                $desc = $this->_request("desc");
                $param = array();
                if ($state == 0 && $desc) {
                    $param = array("desc"=>$desc);
                }
                send_notice($state == 1 ? 72 : 73, "product", $product, $param, 4);
            }
        }
    }

    public function reset_submit_state() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product_ids = $this->_request("ids");
        if (!$product_ids) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $data = array(
            "basic_submit_time"=>0,
            "skill_submit_time"=>0,
            "submit_state"=>0,
            "basic_verify"=>-1,
            "skill_verify"=>-1,
            "is_verify"=>0,
        );
        M("product")->where(array("product_id"=>array("in", $product_ids)))->setField($data);
       alert('success', "操作完成", $_SERVER['HTTP_REFERER']);
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
                "field"=>"product_show",
                "order"=>"product_id"
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

        if ($_GET['assort'] == "dispatch") {
            $where['r_log_product.assort'] =  "dispatch";
        } else {
            $where['r_log_product.assort'] =  array('neq', "dispatch");
        }
        if ($_GET['assort'] == "dispatch") {
            if ($_GET['branch'] == "0" || $_GET['branch'] == "") {
                $where['r_log_product.branch_id'] = "0";
            } else {
                $where['r_log_product.branch_id'] = $_GET['branch'];
            }
        }

        if ($_GET['product_id']) {
            $where['r_log_product.product_id'] = $_GET['product_id'];
        }

        if ($_GET['create_role_id']) {
            $where['log.role_id'] = $_GET['create_role_id'];
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['log.content'] =  array('like', "%".$_REQUEST['search']['value']."%");
        }
        $where['league_id'] = session('league_id');

        $data = make_data_list("ProductLogView", $where, $data_field, array($this, "format_product_log"));
        $this->ajaxReturn($data,'JSON');
    }

    public function format_product_log($v) {
        $v['create_date_show'] = toDate($v['create_date']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        $html = product_show_html($v, false);;
        if (!$html) {
            $html = '<span>[' .$v['product_idcode'].'] '.$v['product_name'] . '</span>&nbsp;';;
        }
        $v['product_show'] = $html;
        $v['content_show'] = "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>".cutString($v['content'], 35)."</a>";

        return $v;
    }

    public function logger() {
        $this->assort = $_GET['assort'] ? $_GET['assort']:"default";
        $this->display("logger_".$this->assort); // 输出模板
    }

    public function evaluate_list() {
        if ($_GET['et'] == "market") {
            $where = array(
                "product_id"=>$_GET['id'],
                'salary_settle_time'=>array("neq", "")
            );
            $this->ajaxReturn(make_datatable_list("MarketProductEvaluateView", $where, array($this, "format_evaluate_market_info")),'JSON');
        } else {
            $where = array(
                "product_id"=>$_GET['id']
            );
            $this->ajaxReturn(make_datatable_list("ProductEvaluateView", $where, array($this, "format_evaluate_info")),'JSON');
        }
    }

    public function evaluate_add() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()) {
            $product_id = $_REQUEST['product_id'];
            $_POST['creator_role_id'] = session("role_id");
            $this->submit_add("product_evaluate");
            $this->update_product_evaluate_cent($product_id);
            alert('success', "新建雇员成功", U('product/evaluateview', 'assort=evaluate&id='.$product_id));

        }else{
            $fields_group = product_field_list_html("add","product_evaluate");
            $this->fields_group = $fields_group;
            $this->alert = parseAlert();
            $this->display();
        }
    }

    public function evaluate_edit() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $findwhere = array('product_evaluate_id'=>$this->_request('product_evaluate_id'));
        $m_product_evaluate = M('product_evaluate')->where($findwhere)->find();
        if (!$m_product_evaluate) {
            alert('error',  L('THERE_IS_NO_PRODUCT'));
        }

        if($this->isPost()) {
            $this->submit_edit($m_product_evaluate['product_evaluate_id'], "product_evaluate");
            $this->update_product_evaluate_cent($m_product_evaluate['product_id']);
            $this->update_product_evaluate($m_product_evaluate['product_id']);
            alert('success', "编辑雇员评分成功", U('product/evaluateview', 'assort=evaluate&id='.$m_product_evaluate['product_id']));

        }else{
            $fields_group = product_field_list_html("edit","product_evaluate", $m_product_evaluate);
            $this->product_evaluate = $m_product_evaluate;
            $this->fields_group = $fields_group;
            $this->alert = parseAlert();
            $this->display();
        }
    }

    public function evaluate_delete() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $where = array(
            "product_evaluate_id"=>$_REQUEST['product_evaluate_id']
        );
        $product_evaluate_ids = M("product_evaluate")->where($where)->getField("product_evaluate_id");

        if ($this->submit_delete($product_evaluate_ids, array(), "product_evaluate")) {
            alert('success', "删除成功" ,$_SERVER['HTTP_REFERER']);
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }


    public function evaluateedit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product = D('ProductView')->where('product.product_id = %d',$this->_request('product_id'))->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }
        $assort = $this->_request('assort', 'trim', "");

        if($this->isPost()){
            if ($this->submit_edit($product['product_id'])) {
                $this->update_product_evaluate_cent($product['product_id']);
                $this->update_product_evaluate($product['product_id']);
                $this->add_edit_log($assort, $product['product_id'], "修改评价成功。", D('ProductView')->verity_check($product, false));
                alert('success', L('PRODUCT_EDIT_SUCCESS'), U('product/evaluatemarket_view', 'assort=evaluate&id='.$product['product_id']));
            } else {
                alert('error', L('PRODUCT_EDIT_FAILED'), $_SERVER['HTTP_REFERER']);
            }
        }else{
            $this->assort = $assort;
            $this->alert = parseAlert();;
            $this->product = $product;
            $this->fields_group = product_field_list_html("edit","product",$product, $assort);;
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->display();
        }
    }


    public function format_evaluate_info($value) {
        $value['evaluate_time_show'] = pregtime($value['update_time'], true);
        $value['owner_role_id_show'] = role_show($value['creator_role_id']);
        $value['vouchers'] = field_show_html("product_evaluate", 'vouchers', $value);;
        return $value;
    }

    public function format_evaluate_market_info($value) {
        $value['evaluate_time_show'] = pregtime($value['update_time'], true);
        $value['market_owner_role_id_show'] = role_show($value['market_owner_role_id']);
        $value['market_idcode_show'] = market_show_html($value, "product", $value['product_id']);
        $value['market_home_check_show'] ="<input ".($value['home_check']!=0?"checked='checked'":'')." class='market_home_check_show' ref='".$value['market_product_evaluate_id']."' onclick='on_change_market_home_check(this)' type=\"checkbox\" value=\"1\" />";

        return $value;
    }

    public function set_evaluate_show_state() {

        M("market_product_evaluate")->where(array("market_product_evaluate_id"=>$_GET['id']))->setField("home_check", $_GET['state']);
    }

    public function update_product_evaluate_cent($product_id) {
        $data = array(
            "total_examine"=> M("product_evaluate")->where(array("product_id"=>$product_id))->sum("examine_regu") + 100,
            "year_examine"=>M("product_evaluate")->where(array('update_time' => array('gt',strtotime(date('Y-01-01', time()))),"product_id"=>$product_id))->sum("examine_regu") + 20,
        );
        M("product")->where(array("product_id"=>$product_id))->setField($data);
    }

    public function appraiseadd(){
        if (vali_permission("product", "skilledit") == false) {
            alert('error', '您没有此权利!', $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product_id = $this->_request("product_id");
        if (!$product_id) {
            alert('error',  "参数错误", $_SERVER['HTTP_REFERER']);
        }
        $product = D('ProductView')->where('product.product_id = %d',$product_id)->find();
        if (!$product) {
            alert('error', L('THERE_IS_NO_PRODUCT'),$_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()){
            if (!($product_appraisal_id = $this->submit_add("product_appraisal"))) {
                alert('error',  "参数错误", $_SERVER['HTTP_REFERER']);
                return false;
            }
            alert('success', "添加鉴定成功", U('product/skillview', 'assort=skill&id='.$product_id));
        }else{
            $this->fields_group = product_field_list_html("add","product_appraisal", array(), "basic");
            $this->alert =  parseAlert();
            $this->product = $product;
            $this->display();
        }
    }

    public function appraiseedit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product_appraisal_id = $this->_request("id");
        if (!$product_appraisal_id) {
            alert('error',  "参数错误", $_SERVER['HTTP_REFERER']);
        }
        $product_appraisal = M('product_appraisal')->where('product_appraisal_id = %d',$product_appraisal_id)->find();
        if (!$product_appraisal) {
            alert('error',  "参数错误", $_SERVER['HTTP_REFERER']);
        }
        if($this->isPost()){
            if (!$this->submit_edit($product_appraisal_id, "product_appraisal")) {
                alert_back('error',  "编辑鉴定失败", $_SERVER['HTTP_REFERER']);
            }
            alert('success', "编辑鉴定成功", U('product/skillview', 'assort=skill&id='.$product_appraisal['product_id']));
        }else{
            $this->alert = parseAlert();
            $fields_group = product_field_list_html("edit","product_appraisal",$product_appraisal);
            $this->fields_group =$fields_group;
            $this->product_appraisal = $product_appraisal;
            $this->display();
        }
    }

    public function appraisedelete(){
        if (!$_REQUEST['id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $product_appraisal_id_idst = is_array($_REQUEST['id']) ? $_REQUEST['id'] : array($_REQUEST['id']);

        if (!$this->submit_delete($product_appraisal_id_idst, array(), "product_appraisal")) {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
        alert('success', "删除鉴定成功", $_SERVER['HTTP_REFERER']);
    }


    public function appraise_list() {
        $where = array(
            "product_id"=>$_GET['id']
        );
        $this->ajaxReturn(make_datatable_list("ProductAppraisalView", $where, array($this, "format_appraise_info")),'JSON');
    }

    public function format_appraise_info($value) {
        $value['appraise_time_show'] = pregtime($value['appraise_time'], true);
        $value['certificate_pic'] = field_show_html("product_appraisal", 'certificate_pic', $value);
        return $value;
    }
}
