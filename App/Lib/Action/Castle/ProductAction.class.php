<?php

class ProductAction extends CastleAction {

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
            "product_id"=>$this->_request('id')
        );
        $product =M('product')->where($findwhere)->find();
        if (!$product) {
            $this->ajaxReturn(array("error"=>L('THERE_IS_NO_PRODUCT')));
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
            } else {
                $state = $rid;
            }
            M('product')->where($findwhere)->setField($assort . "_verify", $rid);
        }
        $desc = $this->_request('describe', 'trim', "");;

        $product = D('ProductView')->where($findwhere)->find();
        $this->log($assort, $product['product_id'], "验证日志", $desc, 5);
        $this->ajaxReturn($state);
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


    function perfect_list_item($value, $export = false) {
        $value['defeventstate'] = M('workstate')->where('workstate_id = %d', $value['defeventstate'])->find();
        $value['workstate_name'] = $value['workstate_id'];
        if (in_array($value['station_state'],array('自愿离职','开除','其他未录用', ''))) {
            $value["workstate_id"] = "";
        } elseif($value['queue_branch_id'] > 0 && $value['workstate_id'] == "面试") {
            $queue_branch = M('branch')->cache(true)->where('branch_id = %d ', $value['queue_branch_id'])->find();
            $value['workstate_name'] = $value['workstate_name']." - ".$queue_branch['name'];
        }
        $value['images']['main'] = M('productImages')->where('product_id = %d and is_main = 1', $value['product_id'])->find();
        return parent::perfect_list_item($value);
    }

    public function all_search_keyword($module) {
        $search = array("product.channel_role_model_keyword", "product.channel_role_id_keyword", "product.slug");;
        if ($module == "log") {
            $search[] = "log.role_id_keyword";
            $search[] = "log.about_roles_name";
            $search[] = "log.subject";
            $search[] = "log.content";
        }
        return $search;
    }

    public function show_list($where = array(), &$params = array()) {
        $this->assign_module_list($where, $params, "雇员表");
        $this->return_data($this->list, $this->page->totalRows);
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


    public function update(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $_POST['name'] = trim($_POST['name']);
        $_POST['slug'] = Pinyin($_POST['name']);

        if ($product_id = $_POST['product_id']) {
            $findwhere = array('product.product_id'=>$product_id);
            $product = D('ProductView')->where($findwhere)->find();
            if (!$product) {
                $this->ajaxReturn(array("error"=>L('THERE_IS_NO_PRODUCT')));
            }
            if ($this->commiss_check_where($product)) {
                $this->ajaxReturn(array("error"=>" 这个雇员的联系方式在客服模块有登记，请联系客服指派.客服电话: 010-61504100"));
            }
            if (!$this->submit_edit($product['product_id'])) {
                $this->ajaxReturn(array("error"=>L('THERE_IS_NO_PRODUCT')));
            }
            $this->add_edit_log("basic", $product['product_id'], "修改基本信息成功。", D('ProductView')->verity_check($product));

            $this->update_skill_data($product['product_id'], $_POST['skill']);

            $newproduct = D('ProductView')->where($findwhere)->find();
            if ($newproduct['commiss_id']) {
                $this->update_commiss_info($newproduct, $newproduct['commiss_id'], $product);
            }
            if ($newproduct['channel_role_model'] != $findwhere['channel_role_model'] || $newproduct['channel_role_id'] != $findwhere['channel_role_id']) {
                $this->update_correlation_channel_introducer("product", $newproduct);
            }
            $this->update_relead_model_keyword($newproduct);

        } else {
            if ($_POST['byc'] != "commiss") {
                if ($this->commiss_check_where(null)) {
                    $this->ajaxReturn(array("error"=>" 这个雇员的联系方式在客服模块有登记，请联系客服指派.客服电话: 010-61504100"));
                }
            }
            $_POST['league_id'] = session('league_id');
            $_POST['submit_state'] = 0x1;
            $_POST['basic_submit_time'] = time();

            if ($_POST['byc'] == "commiss" && $_POST['cmodel_id']) {
                $_POST['commiss_id'] = $_POST['cmodel_id'];
            }
            if (session("role_id") == "119") {
                $_POST['branch_id'] = session('branch_id');
            }

            $product_id = $this->submit_add(0);
            if (!$product_id) {
                $this->ajaxReturn(array("error"=>L('THERE_IS_NO_PRODUCT')));
            }
            $data['idcode'] = sprintf("GY%07d", $product_id);
            M('product')->where(array('product_id'=>$product_id))->setField($data);

            if ($_POST['byc'] == "commiss" && $_POST['cmodel_id']) {
                $this->update_commiss_info($product_id, $_POST['cmodel_id']);
            }
        }
        $this->update_keyword($product_id);
        $this->show_list(array("product_id"=>$product_id));
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

    public function update_product_category_cache($product) {
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

    public function update_product_category($skill_id, $category_id, $data, $product_id = null) {
        $data = perfect_model_field("skill", $data);
        $m_skill_data = D('SkillData');
        if ($m_skill_data->create($data) === false) {
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
        $skilldata = M('skill_data')->where(array('skill_id'=>$skill_id))->find();
        $m_skill_data->verity_check($skilldata);

        $srccatdata = M('product_category')->cache(true)->where('category_id = ' . $skilldata['category_id'])->find();
        $catdata = M('product_category')->cache(true)->where('category_id = ' . $category_id)->find();
        if ($skilldata['category_id'] != $category_id) {
            $logc = "修改原雇员类别" . $catdata['name'] . "为" . $srccatdata['name'] . "成功。";
        } else {
            $logc = "修改雇员类别" . $catdata['name'] . "成功。";
        }
        if ($skilldata['level'] != $data['level']) {
            $logc .= "级别由" . $skilldata['level'] . "修改为" . $data['level'];
        }
        $this->log("skill", $skilldata['product_id'], "修改雇员类别", $logc, 7);
        return true;
    }

    public function update_skill_data($product_id, $data) {
        $skill_ids = M('skill_data')->field("skill_id, category_id")->where(array('product_id'=>$product_id))->select();
        foreach($data as $skill) {
            $skill_id = $skill['skill_id'];
            $where = array('product_id'=>$product_id,'category_id'=>$skill['category_id']);
            if ($skill_id) {
                $where['skill_id']=array('neq', $skill_id);

                $skill_key = false;
                foreach($skill_ids as $k=>$sid) {
                    if ($sid['skill_id'] == $skill_id){
                        $skill_key = $k;
                        break;
                    }
                }
                if ($skill_key !== false) array_splice($skill_ids, $skill_key, 1);
            }
            if (M('skill_data')->where($where)->find()) continue;
            $this->update_product_category($skill_id, $skill['category_id'], $skill, $product_id);
        }

        foreach($skill_ids as $skill_id) {
            M('skill_data')->where(array('skill_id' => $skill_id['skill_id']))->delete();
            $catdata = M('product_category')->cache(true)->where('category_id = ' . $skill_id['category_id'])->find();
            $this->log("skill", $product_id, "删除类别", "删除类别". $catdata['name'], 7);
        }
        D('SkillView')->updateproductcat($product_id);

        $m_product = M("product");
        $verity = $m_product->where('product_id= %d',$product_id)->getField('is_verify');
        $skill_verify = $m_product->where('product_id= %d',$product_id)->getField("skill_verify");
        if ($skill_verify > 0) {
            $verity -= 1;
        }
        $m_product->where('product_id= %d',$product_id)->setField("skill_verify", 0);
        $m_product->where('product_id= %d',$product_id)->setField("is_verify", $verity);
        $this->update_product_category_cache($product_id);
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

    public function delete() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $product_ids = $_REQUEST['id'] ?$_REQUEST['id'] : array($_REQUEST['id']);
        if ('' == $product_ids) {
            $this->ajaxReturn(L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'));
        }
        $delete_where = array('product_id'=>array("in", $product_ids));

        $product_delete = M('product')->where($delete_where)->delete();
        $product_data_delete = M('product_data')->where($delete_where)->delete();
        if(!$product_delete || !$product_data_delete) {
            $this->ajaxReturn(L('DELETED DELETE_FAILED_PLEASE_CONTACT_YOUR_ADMINISTRATOR'));
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
        $this->ajaxReturn(L('DELETED SUCCESSFULLY'));
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
            $category = D('ProductCategory');
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

        $qrOpt = "http://www.aobaomuying.cn/Index_namecard_id_".$product_id.".html";
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
        if (!$_REQUEST['product_id']) {
            $this->ajaxReturn(null, "", 0);
        }
        $product_id = intval($_REQUEST['product_id']);
        $this->ajaxReturn($this->checkevent($product_id, "2014-01-01", "2020-01-01"));
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
        if (!$_REQUEST['product_id']) {
            $this->ajaxReturn(null, "JSON");
        }
        $product_id = intval($_REQUEST['product_id']);
        $events = $this->checkevent($product_id, "2014-01-01", "2020-01-01");
        $product_event = $events[$product_id];
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

    public function evaluate() {
        $data_field = array();
        if (isset($_GET['columns']) && $_GET['columns']) {
            $colums =  count($_GET['columns']);
            for($i = 0; $i <$colums; ++$i) {
                $data_field[] = $_GET['columns'][$i];
            }
        }

        if ($_GET['et'] == "market") {
            $where = array(
                "product_id"=>$_GET['product_id'],
                'salary_settle_time'=>array("neq", "")
            );
            $where = $this->enum_field_where($_GET, $where, "");
            $where['league_id'] = session('league_id');
            $this->ajaxReturn(make_data_list("MarketProductEvaluateView", $where, $data_field, array($this, "format_evaluate_market_info")),'JSON');
        } else {
            $where = array(
                "product_id"=>$_GET['product_id'],
            );
            $where = $this->enum_field_where($_GET, $where, "");
            $where['league_id'] = session('league_id');
            $this->ajaxReturn(make_data_list("ProductEvaluateView", $where, $data_field, array($this, "format_evaluate_info")),'JSON');
        }
    }

    public function format_evaluate_info($value) {
        $value['vouchers'] = field_show_html("product_evaluate", 'vouchers', $value);;
        return $value;
    }

    public function format_evaluate_market_info($value) {
        return $value;
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

    public function logs($where = array()) {
        $where = $this->enum_field_where($_GET, $where, "log");
        $where['league_id'] = session('league_id');
        $where['assort'] = $_GET['assort'];
        $where['log.category_id'] = $_GET['category_id'];
        $this->ajaxReturn(make_logs_list("ProductLogView", $where, array($this, "format_log")),'JSON');
    }

    public function format_log($v) {
        $v['role_id'] = D('StaffRoleView')->where('user.role_id = %d', $v['role_id'])->find();
        $v['tags'] = array(
            $v['role_id']['staff_name'],
            $v['category_name'],
            $v['subject'],
        );
        return $v;
    }


}
