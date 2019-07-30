
<?php 
class CultivateAction extends CastleAction {

    public function change_authInfo() {
        if (!isset($_GET['id'])) {
            die("Please provide a date range.");
        }

        $cultivate_id = intval($this->_request('id'));
        $cultivate = D('CultivateView')->where('cultivate.cultivate_id = %d',$cultivate_id)->find();
        if (!$cultivate) {
            alert('error', "没有找到这个培训订单",$_SERVER['HTTP_REFERER']);
        }

        if($_POST['submit']){
            $this->submit_auth($cultivate);
            alert('success', "修改培训订单账户成功", $_POST['refer_url'] ? $_POST['refer_url'] : U('cultivate/index'));
        }else{
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->cultivate_id = $cultivate_id;
            $this->display();
        }
    }


    public function cancel_submit_settlement($cultivate_data) {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $cultivate_id = $this->_request('id');

        $cultivate = D('CultivateView')->where(array("cultivate.cultivate_id"=>$cultivate_id))->find();
        if (!$cultivate || $cultivate['settle_state'] != 917) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['settle_state'] < 917) {
            alert('error', "订单还没有提交结算",$_SERVER['HTTP_REFERER']);
        }

        M("cultivate")->where(array('cultivate_id'=>$cultivate_id))->setField($cultivate_data);
        if ($_POST['content']) {
            $this->log($cultivate_id, "结算退回", $_POST['content'], 1, "default");
        }
        alert('success', "撤销提交完成",$_SERVER['HTTP_REFERER']);
    }


    public function retreat_submit_settlement() {
        $cultivate_data = array(
            "settle_state"=>916,
            "is_cancel_submit"=>1,
        );
        return $this->cancel_submit_settlement($cultivate_data);
    }

    public function revoke_submit_settlement() {
        $cultivate_data = array(
            "settle_state"=>916,
        );
        return $this->cancel_submit_settlement($cultivate_data);
    }


    public function perfect_group_list($list) {
        foreach($list as $k=>$v) {
            $list[$k] = $this->perfect_list_item($v);
        }
        return $list;
    }

    public function make_astrict_where($brat = true, $branch=null) {
        $map['dorm.branch_id'] = session('branch_id');
        return $map;
    }

    public function show_list($where = array(), $params = array()) {
        $this->assign_module_list($where, $params, "培训订单表");
        $this->return_data($this->list, $this->page->totalRows);
    }

    public function group_filter_fields() {
        return array(
            "name"=>$_REQUEST['name'],
            "currier_name"=>$_REQUEST['currier_name'],
            "category_name"=>$_REQUEST['category_name']
        );
    }

    private function cultivate_settle_status($cultivate) {
        $cultivate_over = $this->cultivate_over_status($cultivate);
        $settle_state = $cultivate_over && $cultivate['settle_state'] < 918 && ($cultivate['settle_state'] == 917);
        return $settle_state && in_array($cultivate['examine_state'], array(12, 10));
    }


    public function replenish_list($list, $export) {
        $list = parent::replenish_list($list, $export);

        $branch_role = get_branch(session("role_id"));
        $per_edit = vali_permission("cultivate", "edit");
        $restriction = !session('?admin') && vali_permission("branchlock", "cultivate")  && session('restriction') === true;
        foreach($list as $key => $value){
            $list[$key]['is_owner'] = session('?admin');
            if (!$list[$key]['is_owner']) {
                $list[$key]['is_owner'] = ($list[$key]['owner_role_id'] && $branch_role ? self::is_owner($list[$key], $branch_role) : true);
            }
            $list[$key]['per_edit'] = $restriction ? ($list[$key]['is_owner'] || $per_edit) : true;
            $list[$key]['settle_state'] = convert_cultivate_status($value['settle_state'], $value['is_cancel_submit']);
            if (session('?admin')) {
                $list[$key]['optstate'] = false;
            } else {
                $list[$key]['optstate'] = is_cultivate_settle($value) || $value['confirm_price'] > 0;
            }

            $where = array(
                "cultivate_id"=>$value['cultivate_id'], "isdefault"=>1
            );
            $list[$key]['def_cultivate_channel'] = M("cultivate_channel")->where($where)->find();
            $list[$key]['status_id'] = convert_cultivate_status($list[$key]['status_id'], $list[$key]['is_cancel_submit']);
        }
        return $list;
    }

    private function cultivate_over_status($cultivate) {
        return ($cultivate['status_id'] == 3 &&
            $cultivate['deficit_price'] >= $cultivate['sum_settle_price'] &&
            $cultivate['wait_confirm_price']==0);
    }

    function perfect_list_item($value, $export = false, $branchlock = false) {
        $value['cultivate_settle'] = $this->cultivate_settle_status($value);
        $value['cultivate_over'] = $this->cultivate_over_status($value);
        $value['promtp_count'] = self::prompt_count("cultivate", $value['cultivate_id'], time());
        return parent::perfect_list_item($value, $export, $branchlock);
    }

    public function add_cultivate_basic($branch_id) {
        $_POST['league_id'] = session('league_id');
        if (!($cultivate_id = $this->submit_add("cultivate"))) {
            return false;
        }
        $idcode = $this->make_idcode($branch_id);
        $data = array(
            'idcode'=>$idcode,
            'slug'=>Pinyin($this->_request("name")),
            'basic_submit_time'=>time(),
        );
        M('cultivate')->where(array('cultivate_id'=>$cultivate_id))->setField($data);
        $this->log($cultivate_id, "新建培训订单", "快速新建培训订单成功");
        return $cultivate_id;
    }

    public function get_channel($channel_id) {
        $m_channel = M('channel')->cache(true)->where('channel_id = %d',$channel_id)->find();
        if ($m_channel) {
            if ($m_channel['parentid']) {
                $m_channel_parent = M('channel')->cache(true)->where('channel_id = %d', $m_channel['parentid'])->find();
                if ($m_channel_parent) {
                    foreach($m_channel as $k=>$v) {
                        if (!$v) {
                            $m_channel[$k] = $m_channel_parent[$k];
                        }
                    }
                }
            }
        }
        return $m_channel;
    }

    public function add_default_urge_role($cultivate_id, $urge_role_id, $role_id) {
        $cultivate = M('cultivate')->where('cultivate_id = %d',$cultivate_id)->find();
        $data = array(
            "cultivate_id"=>$cultivate['cultivate_id'],
            "update_role_id"=>$role_id,
            "creator_role_id"=>$role_id,
            "create_time"=>time(),
            "update_time"=>time(),
            "isdefault"=>1,
        );
        $m_channel = $this->get_channel($cultivate['channel_role_model']);
        if ($m_channel) {
            $data["urge_owner_radio"] = ($cultivate['initial'] == "是"?$m_channel['urge_owner_first_radio']:$m_channel['urge_owner_radio']);
            $data["urge_owner_price"] = ($cultivate['initial'] == "是"?$m_channel['urge_owner_first_price']:$m_channel['urge_owner_price']);
            $data["urge_role_class"] = "建单人";
            $data["urge_role_id"] = $urge_role_id;
            M("cultivate_urge")->add($data);

            $m_model = M($cultivate['model'])->where($cultivate['model'].'_id = %d',$cultivate['model_id'])->find();
            if ($m_model) {
                if ($m_model['create_time'] < strtotime("2017-01-01")) {
                    $data["urge_owner_radio"] = 0;
                    $data["urge_owner_price"] = 0;
                } else {
                    $data["urge_owner_radio"] = ($cultivate['initial'] == "是"?$m_channel['urge_model_owner_first_radio']:$m_channel['urge_model_owner_radio']);
                    $data["urge_owner_price"] = ($cultivate['initial'] == "是"?$m_channel['urge_model_owner_first_price']:$m_channel['urge_model_owner_price']);
                }
                $data["urge_role_class"] = "建档人";
                $data["urge_role_id"] = $m_model['owner_role_id'];
                M("cultivate_urge")->add($data);
            }
        }

        $this->log($cultivate_id, "添加促单", "添加默认默认促单成功", 2);
    }

    public  function update_default_urge_role() {
        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $cultivate_id = $this->_request('id');

        $cultivate = D('CultivateView')->where(array("cultivate.cultivate_id"=>$cultivate_id))->find();
        if (!$cultivate) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        if (is_cultivate_settle($cultivate)) {
            alert('error', "订单已经结算",$_SERVER['HTTP_REFERER']);
        }
        $this->update_default_urge_price($cultivate_id);
        $this->update_cultivate($cultivate_id);
        alert('success', "刷新培训订单成功", U('cultivate/view', 'id='.$cultivate_id));
    }

    public function add_default_channel($cultivate_id, $role_id) {
        $cultivate = M('cultivate')->where('cultivate_id = %d',$cultivate_id)->find();
        if ($cultivate) {
            $m_model = M($cultivate['model'])->where($cultivate['model'].'_id = %d',$cultivate['model_id'])->find();
            if ($m_model) {
                $data = array(
                    "isdefault"=>1,
                    "cultivate_id"=>$cultivate_id,
                    "urge_role_id"=>$role_id,
                    "update_role_id"=>$role_id,
                    "creator_role_id"=>$role_id,
                    "create_time"=>time(),
                    "update_time"=>time(),
                    "channel_role_model"=>$m_model['channel_role_model'],
                    "channel_role_model_keyword" => $m_model['channel_role_model_keyword']?$m_model['channel_role_model_keyword']:"",
                    "channel_role_id"=>$m_model['channel_role_id'],
                    "channel_role_id_keyword" => $m_model['channel_role_id_keyword']?$m_model['channel_role_id_keyword']:"",
                );
                $m_channel = $this->get_channel($cultivate['channel_role_model']);
                if ($m_channel) {
                    $initial = $cultivate['initial'] == "是";
                    $data["channel_role_radio"] = ($initial ? $m_channel['channel_role_first_radio'] : $m_channel['channel_role_radio']);
                    $data["channel_role_price"] = ($initial ? $m_channel['channel_role_first_price'] : $m_channel['channel_role_price']);
                }

                $channel_id = M("cultivate_channel")->add($data);
                if ($channel_id) {
                    $data = array(
                        'def_channel_id'=>$channel_id,
                    );;
                    M("cultivate")->where(array('cultivate_id'=>$cultivate_id))->setField($data);
                    $this->log($cultivate_id, "添加渠道", "添加默认渠道成功", 2);
                }
            }
        }
    }



    private function update_commiss_info($cultivate) {
        if (!is_array($cultivate)) {
            $cultivate = M('cultivate')->where(array("cultivate_id"=>$cultivate))->find();
        }

        if ($cultivate) {
            $where = array(
                "model"=>$cultivate['model'],
                "model_id"=>$cultivate['model_id'],
                "status_id"=>array("neq", 0)
            );
            $data = array(
                "cultivate_amount"=>M("cultivate")->where($where)->count(),
                "cultivate_price"=> M("cultivate")->where($where)->sum("sum_settle_price"),
            );
            M("commiss")->where(array("related_model_name"=>$cultivate['model'],"related_model_id"=>$cultivate['model_id']))->setField($data);
        }
    }

    public function update_cultivate_channel($cultivate) {
        if (!is_array($cultivate)) {
            $cultivate = M('cultivate')->where(array("cultivate_id"=>$cultivate))->find();
        }
        $m_model = M($cultivate['model'])->where($cultivate['model'].'_id = %d',$cultivate['model_id'])->find();
        $data = array(
            "channel_role_model"=>$m_model['channel_role_model'],
            "channel_role_id"=>$m_model['channel_role_id'],
        );
        M("cultivate")->where(array('cultivate_id'=>$cultivate['cultivate_id']))->setField($data);
    }

    public function update_currier_used($cultivate) {
        if (!is_array($cultivate)) {
            $cultivate = M('cultivate')->where(array("cultivate_id"=>$cultivate))->find();
        }
        $where = array("currier_id"=>$cultivate['currier_id']);
        $data['cultivate_used'] = M("cultivate")->where($where)->count();
        M("currier")->where(array('currier_id'=>$cultivate['currier_id']))->setField($data);
    }


    private function make_idcode($branch_id) {
        $brnach = M("branch")->cache(true)->where("branch_id=".$branch_id)->find();
        if ($brnach && $brnach['code']) {
            $idcode = $brnach['code'];
        } else {
            $idcode = "ZB";
        }
        if ($brnach) {
            $count_where['branch_id'] =  $brnach['branch_id'];
        }
        $count_where['create_time'] =  array('gt',strtotime(date('Y-m-d', time())));
        $cnt = M("cultivate")->where($count_where)->count();
        return "P".$idcode . date("Ymd").sprintf("%03d", $cnt);
    }

    public function product_check($model, $model_id, $currier_id, $cultivate = null) {
        $chwhere = array(
            'model'=>$model,
            "model_id"=>$model_id,
            "currier_id"=>$currier_id,
            "status_id"=>array("neq", '0')
        );
        if ($cultivate != null) {
            $chwhere['cultivate_id'] = array("neq", $cultivate['cultivate_id']);
        }
        return $m_product_check = M("cultivate")->where($chwhere)->find();
    }


    public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->role_branch = M("branch_category")->cache(true)->where("role_id=".session('role_id'))->find();
        if($this->isPost()){
            $m_currier = M("currier")->where("currier_id=".$_POST['currier_id'])->find();
            if (!$m_currier) {
                alert('error',  "新建培训订单失败, 没有找到这个新培训项目", $_SERVER['HTTP_REFERER']);
            }

            if ($_POST['model'] == "product") {
                $m_product_check = $this->product_check($_POST['model'], $_POST['model_id'], $_POST['currier_id']);
                if ($m_product_check) {
                    $url = "<a href='".U("clutivate/view", "id".$m_product_check['cultivate_id'])."'>查看</a>";
                    alert('error', "这个雇员已经参加过此培训了".$url, $_SERVER['HTTP_REFERER']);
                }
            }

            $_POST['estimate_cost'] = $m_currier['estimate_cost'];
            if ($this->role_branch) {
                $_POST['branch_id'] = $this->role_branch['branch_id'];
            }

            $cultivate_id = $this->add_cultivate_basic($_POST['branch_id']);
            if (!$cultivate_id) {
                alert('error',  "新建培训订单失败", $_SERVER['HTTP_REFERER']);
            }
            $first_clutivate = $this->check_initial_state($_POST['model'], $_POST['model_id']);
            M("cultivate")->where(array('cultivate_id'=>$cultivate_id))->setField("initial",  $first_clutivate['cultivate_id']==$cultivate_id?"是":"否");

            $this->update_cultivate_channel($cultivate_id);
            $this->update_settle_price($cultivate_id);
            $this->add_default_channel($cultivate_id, $_POST['owner_role_id']);
            $this->update_default_channel_price($cultivate_id);
            $this->add_default_urge_role($cultivate_id, $_POST['owner_role_id'], session("role_id"));
            $this->update_default_urge_price($cultivate_id);
            $this->update_cultivate($cultivate_id);
            $this->update_currier_used($cultivate_id);
            $this->log($cultivate_id, "新建培训订单", "新建培训订单成功");
            $this->alert = parseAlert();
            alert('success', "添加培训订单成功", U('cultivate/view', 'id='.$cultivate_id));
		}else{
            $fields_group = product_field_list_html("add","cultivate", array(), "basic");
            unset($fields_group[85]);
            $this->fields_group =$fields_group;
            $alert = parseAlert();
            $this->alert = $alert;
            $this->display();
		}
	}

    public function delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if (!$_REQUEST['cultivate_id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'),$_SERVER['HTTP_REFERER']);
        }
        $cultivate_idst = is_array($_REQUEST['cultivate_id']) ? $_REQUEST['cultivate_id'] : array($_REQUEST['cultivate_id']);
        $cultivate_ids = array();
        $module_ids = array();
        foreach(M('cultivate')->where(array('cultivate_id'=>array("in", $cultivate_idst)))->select() as $v) {
            $cultivate_ids[] = $v['cultivate_id'];
            $module_ids[] = array("model"=>$v['model'],"model_id"=>$v['model_id']);
        }
        if (count($cultivate_ids) > 0) {
            $flow_module = array(
                'cultivate_subgroup'=>'cultivate_subgroup',
                'cultivate_channel'=>'cultivate_channel',
                'cultivate_urge'=>'cultivate_urge',
            );
            if (!$this->submit_delete($cultivate_ids, $flow_module)) {
                alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
            }
            $related_module = array();
            foreach($related_module as $r) {
                $this->related_delete($cultivate_ids, $r);
            }
            $this->delete_related_account("cultivate", $cultivate_ids);

            foreach($cultivate_ids as $cultivate_id) {
                self::delete_prompt("cultivate", $cultivate_id);
                $this->update_currier_used($cultivate_id);
            }

            foreach($module_ids as $model) {
                $first_clutivate = $this->check_initial_state($model['model'], $model['model_id']);
                M("cultivate")->where(array('cultivate_id'=>$first_clutivate['cultivate_id']))->setField("initial", "是");
            }

            $this->log($cultivate_ids, "删除培训订单", "删除培训订单成功");
        }

        if (count($cultivate_idst) == 1 && count($cultivate_idst) != count($cultivate_ids)) {
            alert('error', "删除培训订单失败", $_SERVER['HTTP_REFERER']);
        } else {
            alert('success', "删除培训订单成功", U('cultivate/index'));
        }
    }


	public function edit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $cultivate = D('CultivateView')->where('cultivate.cultivate_id = %d',$this->_request('id'))->find();
		if (!$cultivate) {
            alert('error',  "培训订单不存在", $_SERVER['HTTP_REFERER']);
        }
        if (!session('?admin') && session('restriction') === true && $cultivate['owner_role_id'] && $cultivate['owner_role_id'] != session("role_id")) {
            if (!self::is_owner($cultivate, $branch = get_branch(session("role_id")))){
                alert('error',  "你没有权限", $_SERVER['HTTP_REFERER']);
            }
        }
        $this->assort = $this->_request('assort', 'trim', "basic");
        $cultivate['owner'] = D('RoleView')->cache(true)->where('role.role_id = %d', $cultivate['owner_role_id'])->find();

		if($this->isPost()){
            if ($_POST['currier_id']) {
                $m_currier = M("currier")->where("currier_id=".$_POST['currier_id'])->find();
                if (!$m_currier) {
                    alert('error',  "新建培训订单失败, 没有找到这个新培训项目", $_SERVER['HTTP_REFERER']);
                }
            }

            if ($_POST['model'] == "product") {
                $m_product_check = $this->product_check($_POST['model'], $_POST['model_id'], $_POST['currier_id'], $cultivate);
                if ($m_product_check) {
                    $url = "<a href='".U("clutivate/view", "id".$m_product_check['cultivate_id'])."'>查看</a>";
                    alert('error', "这个雇员已经参加过此培训了".$url, $_SERVER['HTTP_REFERER']);
                }
            }
            $_POST['estimate_cost'] = $m_currier['estimate_cost'];

            if (!$this->submit_edit($cultivate['cultivate_id'])) {
                alert('error',  "编辑培训订单失败", $_SERVER['HTTP_REFERER']);
            }

            if ($cultivate['owner_role_id'] != $_POST['owner_role_id']) {
                $this->update_owner_role_id($cultivate['cultivate_id'], $_POST['owner_role_id']);
            }
            $this->update_cultivate_channel($cultivate['cultivate_id']);
            $this->update_settle_price($cultivate['cultivate_id']);
            $this->update_cultivate($cultivate['cultivate_id'], false, false);
            $this->add_edit_log($cultivate['cultivate_id'], "修改基本信息成功: ", D('CultivateView')->verity_check($cultivate));
            alert('success', "编辑培训订单成功", U('cultivate/view', 'assort='.$this->assort.'&id='.$cultivate['cultivate_id']));

		}else{
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();

            //雇员图片
            $fields_group = product_field_list_html("edit","cultivate",$cultivate, $this->assort);
            unset($fields_group[85]);
            $this->fields_group =$fields_group;
            $this->model_id = $cultivate['cultivate_id'];
            $this->cultivate = $cultivate;
            $this->display();
		}
	}

    public function update_owner_role_id($cultivate_id, $owner_role_id) {
        $where = array(
            "related"=>"cultivate",
            "related_id"=>$cultivate_id
        );
        M("account")->where($where)->setField("related_owner_role_id", $owner_role_id);
    }

    private function add_edit_log($cultivate_id, $logcont, $change_fields) {
        foreach($change_fields as $v) {
            $logcont.=$v['name']."[".$v['oldvalue']."=>".$v['newvalue']."],";
        }
        $this->log($cultivate_id, "更新日志",$logcont);
    }




    public function format_index_fields($field_array) {
        $m_module = $this->get_module_view();
        foreach($field_array as $k=>$v) {
            if ($v['form_type'] == "number" || $v['form_type'] == "floatnumber") {
                $sum_cnt = $m_module->where($this->list_where)->sum($v['field']);
                $v['link_title'] = $v['name']."合计：" . number_format($sum_cnt, 2);
            } else {
                $v['link_title'] = $v['name'];
            }
            $field_array[$k] = $v;
        }
        return $field_array;
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

        if ($_GET['module'] && $_GET['id']) {
            $m = trim($_GET['module']);
            if ($m == "channel") {
                $channel_model = array("product"=>2, "customer"=>3,"staff"=>4);
                $where["channel_role_model"]= $channel_model[$_GET['channel_model']];
                $where["channel_role_id"]=trim($_GET['id']);
            }elseif ($m == "urge"){
                $where["staff_id"]=trim($_GET['id']);
            } else {
                $where["model"]=$m;
                $where["model_id"]=$_GET['id'];
            }
        }
        $where['league_id'] = session('league_id');

        return $where;
    }

    public function view(){
        $assort = $_GET['assort'] ? $_GET['assort'] : "basic";
        $cultivate_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $cultivate_id) {
            alert('error', L('parameter_error'), U("Cultivate/index"));
        }
        $where = array("cultivate.cultivate_id"=>$cultivate_id);
        $cultivate = D('CultivateView')->where($where)->find();
        if (!$cultivate) {
            alert('error', L('PARAMETER_ERROR'), U("Cultivate/index"));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if (!session('?admin') && session('restriction') === true) {
            if (!self::is_interest($cultivate, $branch = get_branch(session("role_id")))) {
                alert('error', "你没有权限访问", U("Cultivate/index"));
            }
        }
        $cultivate['is_owner'] = session('?admin');
        if (!$cultivate['is_owner']) {
            $branch_role = get_branch(session("role_id"));
            $cultivate['is_owner'] = ($cultivate['owner_role_id'] && $branch_role ? self::is_owner($cultivate, $branch_role) : true);
        }
        $this->per_edit = $cultivate['is_owner'] || vali_permission("cultivate", "edit");
        if ($assort == "state") {
            $this->per_state_edit = $cultivate['is_owner'] || vali_permission("cultivate", "edit", "state");
        }
        if ($assort == "prompt") {
            $this->prompt_list = self::prompt_list("cultivate", $cultivate_id);
        }

        if ($assort == "account") {
            $pre_account_type = array(
                "product"=>261,
                "customer"=>282
            );
            $this->clause_type_id = $pre_account_type[$cultivate['model']];

            $thaw_account_type = array(
                "product"=>263,
                "customer"=>284
            );
            $param = array(
                "dire"=>3,
                "t"=>$cultivate['model'],
                "type_id"=>$thaw_account_type[$cultivate['model']],
                "money"=>0,
                "clause_additive"=>$cultivate['model_id'],
                "refer_add_url"=>urlencode($_SERVER['REQUEST_URI']),
                "cultivate_id"=>$cultivate['cultivate_id'],
                "lockele"=>array("clause_type_id", "cultivate_name")
            );
            if (vali_permission("cultivate", "account_edit")) {
                $param['restrict'] = "cultivate";
            }
            $this->thaw_account_url = U("account/add",$param);
        }

        if ($assort == "cost") {
            $accwhere = array(
                "related"=>"cultivate",
                "related_id"=>$cultivate['cultivate_id'],
                "clause_type_id"=>array("in", array(287, 281)),
            );
            $this->currier_account_list = M("account")->where($accwhere)->order("create_time desc")->select();

            $channel_list = D("CultivateChannelView")->where($where)->select();
            foreach($channel_list as $k=>$v) {
                $channel_list[$k] = self::format_channel_info($v);
            }
            $this->channel_list = $channel_list;
            $accwhere = array(
                "related"=>"cultivate",
                "related_id"=>$cultivate['cultivate_id'],
                "clause_type_id"=>array("in", array(277, 275, 279, 269, 271, 267)),
            );
            $this->channel_account_list = M("account")->where($accwhere)->order("create_time desc")->select();

            $urge_list = D("CultivateUrgeView")->where($where)->select();
            $this->urge_list = $urge_list;
            $accwhere = array(
                "related"=>"cultivate",
                "related_id"=>$cultivate['cultivate_id'],
                "clause_type_id"=>array("in", array(264, 273)),
            );
            $urge_account_list = M("account")->where($accwhere)->order("create_time desc")->select();;
            foreach($urge_account_list as $k=>$v) {

            }
            $this->urge_account_list = $urge_account_list;
        }
        if ($cultivate['model'] == "product") {
            $cultivate['model_show_html'] =product_show_html($cultivate['model_id'], false);
        } else {
            $cultivate['model_show_html'] =customer_show_html($cultivate['model_id'], false);
        }

        $this->cultivate = $cultivate;
        $this->assort = $assort;
        $this->cultivate_id = $cultivate_id;
        $this->promtp_count = self::prompt_count("cultivate", $cultivate['cultivate_id'], time());

        if (($branch || $authority == "受限") && !in_array($cultivate['cultivate_id'], self::get_astrict_list())) {
            $cultivate = self::fix_branch_fields(getBranchFields("cultivate"), $cultivate, in_array($cultivate['owner_role_id'], $branch));
        }
        $this->is_owner = ($branch && $cultivate['owner_role_id'] ? self::is_owner($cultivate, $branch) : true);
        $this->cultivate_over = $this->cultivate_over_status($cultivate);;
        $this->cultivate_settle = $this->cultivate_settle_status($cultivate);
        $fields_group = product_field_list_show('cultivate', $cultivate, $assort);

        if ($assort == "basic") {
            foreach(array("status_id", "examine_state", "settle_state") as $v) {
                $fields_group[0]['fields'][] = field_show_html("cultivate", $v, $cultivate);
            }


        }
        $this->fields_group =$fields_group;
        $this->refer_url= session($this->module."_index_refer_url");
        $this->alert =  parseAlert();
        $this->assort =  $assort;
        $this->display($assort."_view");
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
                "cultivate.cultivate_id"=>$id
            );
        }
        $info = D('CultivateView')->where($where)->find();
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
            self::default_cycle_basis_newly_statistics($time_limit[0], $time_limit[1], $cycle, $tab, "培训订单");
        }

        $this->parameter = implode('&', $params);
        $this->assort = $assort;
        $this->alert = parseAlert();
        $this->display($assort."_analytics".$tab);
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
                "field"=>"cultivate_show",
                "order"=>"cultivate_id"
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
                "order"=>"cultivate_idcode"
            ),
        );

        $where = array();
        if ($_GET['start_time'] || $_GET['end_time']) {
            $where['log.create_date'] =  array('between', make_time_between());
        }

        if ($_GET['cultivate_id']) {
            $where['r_cultivate_log.cultivate_id'] = $_GET['cultivate_id'];
        }
        if ($_GET['assort'] == "" || $_GET['assort'] == "default") {
            $where['r_cultivate_log.assort'] =  "default";
        } else {
            $where['r_cultivate_log.assort'] = array("in", $_GET['assort']);
        }
        if ($_GET['create_role_id']) {
            $where['log.role_id'] = $_GET['create_role_id'];
        }
        if ($_GET['subject']) {
            $where['log.subject'] = $_GET['subject'];
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['log.content'] =  array('like', "%".$_REQUEST['search']['value']."%");
        }

        if ($_GET['_string']) {
            $where["_string"] =trim($_GET['_string']);
        }
        $where['league_id'] = session('league_id');

        $data = make_data_list("CultivateLogView", $where, $data_field, array($this, "format_cultivate_log"));
        $this->ajaxReturn($data,'JSON');
    }

    public function format_cultivate_log($v) {
        $v['create_date_show'] = toDate($v['create_date']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        $html = '<span><a target="_blank" href="'.U('cultivate/view', 'id='.$v['cultivate_id']).'">' .$v['cultivate_idcode'].'</a></span>&nbsp;';;
        $v['cultivate_show'] = $html;
        $v['content_show'] = "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>".cutString($v['content'], 43)."</a>";
        if ($v['log_type'] == 1) {
            $v['operator_show'] = '<span><a  href="javascript:void(0);" onclick="return delete_log('.$v['log_id'].');">删除</a></span> | ';;
        } else {
            $v['operator_show'] = '<span><a href="javascript:void(0);"  style="cursor:not-allowed;color:darkgrey">删除</a></span> | ';;
        }
        $v['operator_show'] .= "<a target='_blank' href='".U('log/view', 'id='.$v['log_id'])."'>查看</a>";;
        return $v;
    }


    public function log($cultivate_ids, $subject, $content, $t = 2, $assort = "update") {
        $cultivates = M("cultivate")->where( array('cultivate_id'=>array("in", $cultivate_ids)))->select();
        foreach($cultivates as $v) {
            if ($log_id = $this->addlog($subject, $content, 6)) {
                $data['cultivate_id'] = $v['cultivate_id'];
                $data['cultivate_idcode'] = $v['idcode'];
                $data['log_id'] = $log_id;
                $data['type'] = $t;
                $data['assort'] = $assort;
                $data['league_id'] = session('league_id');
                M('r_cultivate_log')->add($data);
            }
        }
        return $log_id;
    }

    public function logger() {
        $this->assort = $_GET['assort'] ? $_GET['assort']:"default";
        $this->display("logger_".$this->assort); // 输出模板
    }

    public function channel_refresh() {
        if (!$_REQUEST['cultivate_id']) {
            alert('error', L('PARAMETER_ERROR'), U('cultivate/index'));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $cultivate = D('CultivateView')->where(array("cultivate.cultivate_id"=>$_REQUEST['cultivate_id']))->find();
        if (!$cultivate) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['settle_state'] == "918") {
            alert('error', "订单已经结算",$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }

        M("cultivate_channel")->where(array("cultivate_id"=>$cultivate['cultivate_id'], "isdefault"=>1))->delete();
        M("cultivate_urge")->where(array("cultivate_id"=>$cultivate['cultivate_id'], "isdefault"=>1))->delete();

        $this->update_cultivate_channel($cultivate['cultivate_id']);
        $this->add_default_channel($cultivate['cultivate_id'], $cultivate['owner_role_id']);
        $this->update_default_channel_price($cultivate['cultivate_id']);
        $this->add_default_urge_role($cultivate['cultivate_id'], $cultivate['owner_role_id'], session("role_id"));
        $this->update_default_urge_price($cultivate['cultivate_id']);
        $this->calculate_cultivate_account($cultivate['cultivate_id']);
        $this->update_surplus_price("cultivate", $cultivate['cultivate_id']);
        $this->update_cultivate_profit_info($cultivate['cultivate_id']);
        $this->update_cultivate_state_info($cultivate['cultivate_id']);
        $this->update_commiss_info($cultivate['cultivate_id']);
        $this->update_keyword($cultivate['cultivate_id']);
        alert('success','刷新渠道成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('cultivate/view', 'assort=channel&id='.$cultivate['cultivate_id']));

    }

    public function channel_edit() {
        if (!$_REQUEST['id']) {
            alert('error', L('PARAMETER_ERROR'), U('cultivate/index'));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $cultivate_channel_id = $this->_request('id');
        $where = array(
            "cultivate_channel_id"=>$cultivate_channel_id
        );
        $cultivate_channel = D("CultivateChannelView")->where($where)->find();
        if ($cultivate_channel['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate_channel['settle_state'] == "918") {
            alert('error', "订单已经结算",$_SERVER['HTTP_REFERER']);
        }
        if($this->isPost()){
            $cultivate_id = $cultivate_channel['cultivate_id'];
            if($this->submit_edit($cultivate_channel_id, "cultivate_channel")) {
                $this->update_cultivate($cultivate_id, false, false);
                $this->add_edit_log($cultivate_id, "编辑渠道成功: ", $cultivate_channel_id.": ".D('CultivateChannelView')->verity_check($cultivate_channel));
                alert('success','编辑渠道成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('cultivate/view', 'assort=channel&id='.$cultivate_id));
            } else {
                alert('error', "编辑渠道失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            if ($cultivate_channel['settle_state'] == "918") {
                alert('error', "订单已经结算",$_SERVER['HTTP_REFERER']);
            }
            $this->cultivate_channel_id = $cultivate_channel_id;
            $this->cultivate_channel = $cultivate_channel;
            $this->cultivate = $cultivate_channel;
            $this->fields_group = field_list_html_edit('cultivate_channel', $cultivate_channel);
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();;
            $this->display();
        }
    }


    public function channel_delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $cultivate = D('CultivateView')->where(array("cultivate.cultivate_id"=>$this->_request('cultivate_id')))->find();
        if (!$cultivate) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['settle_state'] == "918") {
            alert('error', "订单已经结算",$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        $cultivate_channel_id = $this->isPost() ? $_POST['id'] : $_GET['id'];
        $where = array(
            "cultivate_channel_id"=>$cultivate_channel_id
        );
        $cultivate_ids = M("CultivateChannel")->where($where)->getField("cultivate_id");

        if ($this->submit_delete($cultivate_channel_id, array(), "cultivate_channel")) {
            $this->update_cultivate($cultivate_ids, false, false);
            $this->log($cultivate_ids, "删除渠道", "删除渠道成功 => ".$cultivate_channel_id);
            alert('success', "删除成功" ,$_SERVER['HTTP_REFERER']);
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }

    public function update_cultivate_profit_info($cultivate_ids) {
        if (!is_array($cultivate_ids)) {
            $cultivate_ids = array($cultivate_ids);
        }
        $cultivates = M("cultivate")->where(array("cultivate_id"=>array("in", $cultivate_ids)))->select();
        foreach($cultivates as $cultivate) {
            $data = array();
            $data['sum_urge_price'] = M("cultivate_urge")->where("cultivate_id=".$cultivate['cultivate_id'])->sum('urge_price');
            $data['profit'] = $cultivate['gain'] - $data['sum_urge_price'];
            M("cultivate")->where(array("cultivate_id"=>$cultivate['cultivate_id']))->setField($data);
        }
    }


    public function update_cultivate_state_info($cultivate_ids) {
        if (!is_array($cultivate_ids)) {
            $cultivate_ids = array($cultivate_ids);
        }
        foreach($cultivate_ids as $cultivate_id) {
            $data = array();
            self::update_cultivate_service_status($cultivate_id, time());
        }
    }

    public function update_cultivate_service_status($cultivate, $curtime) {
        if (!is_array($cultivate)) {
            $cultivate = M('cultivate')->where(array("cultivate_id"=>$cultivate))->find();
        }
        $data = array();
        if ($cultivate['status_id'] != 0) {
            if (($cultivate['end_time'] == 0 && $cultivate['start_time'] == 0) || $curtime < $cultivate['start_time']) {
                $data['status_id'] = 1;
            } else if($curtime >=$cultivate['start_time'] && ($curtime <= $cultivate['end_time'] || $cultivate['end_time'] == 0)) {
                $data['status_id'] = 2;
            } else {
                $data['status_id'] = 3;
            }
        }

        if ($cultivate['examine_state'] != 10) {
            if (($cultivate['examine_time'] == 0) || $curtime <= $cultivate['examine_time']) {
                $data['examine_state'] = 11;
            } else if($curtime > $cultivate['examine_time']) {
                $data['examine_state'] = 12;
            }
        }
        if ($cultivate['cert_state'] != 20) {
            if ($cultivate['getcert_time'] != 0 && $curtime >=$cultivate['getcert_time']){
                $data['cert_state'] = 24;
            } else {
                if ($cultivate['cert_number']) {
                    if ($curtime < $cultivate['getcert_time'] || $cultivate['getcert_time'] == 0) {
                        $data['cert_state'] = 23;
                    }
                } else {
                    if ($cultivate['applycert_time'] && $curtime > $cultivate['applycert_time']) {
                        $data['cert_state'] = 22;
                    } else {
                        $data['cert_state'] = 21;
                    }
                }
            }
        }
        M("cultivate")->where(array("cultivate_id"=>$cultivate['cultivate_id']))->setField($data);

        self::update_module_clutivate_state($cultivate, $data['status_id']);
    }

    public function update_module_clutivate_state($cultivate, $newstate) {
        if ($newstate == "2" && $newstate != $cultivate['status_id']) {
            $data = array(
                "cultivate_status"=>"在培训",
            );
            M($cultivate['model'])->where($cultivate['model']."_id=".$cultivate['model_id'])->setField($data);

        } else {
            $where = array(
                "model_id"=>$cultivate['model_id'],
                "status_id"=>2
            );
            $where['league_id'] = session('league_id');

            if ($cultivate['status_id'] == "2" && M("cultivate")->where($where)->count() > 0) {
                return;
            }

            $data = array(
                "cultivate_status"=>"未培训",
            );
            $where['status_id']=1;
            if (M("cultivate")->where($where)->count() > 0) {
                $data['cultivate_status'] = "待培训";
            } else {
                $where['status_id']=3;
                if (M("cultivate")->where($where)->count() > 0) {
                    $data['cultivate_status'] = "已完成";
                }
            }
            M($cultivate['model'])->where($cultivate['model']."_id=".$cultivate['model_id'])->setField($data);
        }
    }

    public function update_settle_price($cultivate){
        if (!is_array($cultivate)) {
            $cultivate = M('cultivate')->where(array("cultivate_id"=>$cultivate))->find();
        }
        $gain = $cultivate['sum_settle_price'] - $cultivate['estimate_cost'];
        $data = array(
            "gain"=>$gain
        );
        M("cultivate")->where(array("cultivate_id"=>$cultivate['cultivate_id']))->setField($data);
    }

    public function update_cultivate($cultivate_id) {
        $this->calculate_cultivate_account($cultivate_id);
        $this->update_surplus_price("cultivate", $cultivate_id);
        $this->update_cultivate_profit_info($cultivate_id);
        $this->update_cultivate_state_info($cultivate_id);
        $this->update_commiss_info($cultivate_id);
        $this->update_keyword($cultivate_id);
    }

    public function reset_keyword() {
        foreach(D("CultivateView")->select() as $v) {
            $this->update_keyword($v);
        }
    }

    public function update_keyword($cultivate) {
        if(!is_array($cultivate)) {
            $cultivate = D("CultivateView")->where(array("cultivate.cultivate_id"=>$cultivate))->find();
        }
        $keyword = array();

        $keyword[] = $cultivate['category_name'];
        $keyword[] = $cultivate['idcode'];
        $keyword[] = $cultivate['currier_name'];
        $keyword[] = $cultivate['currier_idcode'];
        $keyword[] = $cultivate['channel_role_model_keyword'];
        $keyword[] = $cultivate['channel_role_id_keyword'];

        $m_user_role = D("UserView")->where(array("role.role_id"=>$cultivate['owner_role_id']))->find();
        if ($m_user_role) {
            $keyword[] = $m_user_role['idcode'];
            $keyword[] = $m_user_role['name'];
            $keyword[] = $m_user_role['telephone'];
        }

        $m_model = M($cultivate['model'])->where(array($cultivate['model']."_id"=>$cultivate['model_id']))->find();
        if ($m_model) {
            $keyword[] = $m_model['idcode'];
            $keyword[] = $m_model['name'];
            $keyword[] = $m_model['telephone'];
        }

        $m_market_account = D("CultivateAccountView")->where(array("cultivate_id"=>$cultivate['cultivate_id']))->select();
        foreach($m_market_account as $account) {
            $keyword[] = $account['receipt_number'];
        }

        $data = array(
            "keyword"=>implode(chr(10), $keyword)
        );
        $data = make_channel_model_keyword($cultivate['channel_role_model'], $cultivate['channel_role_id'], $data);
        $m_model = M($cultivate['model'])->where(array($cultivate['model']."_id"=>$cultivate['model_id']))->find();
        if ($m_model) {
            $keyword2 = array();
            $keyword2[] = $m_model['idcode'];
            $keyword2[] = $m_model['name'];
            $keyword2[] = $m_model['telephone'];
            $data['model_id_keyword'] =implode(chr(10), $keyword2);
        }
        M("cultivate")->where(array("cultivate_id"=>$cultivate['cultivate_id']))->setField($data);
    }


    public function update_default_urge_price($cultivate_ids) {
        if (!is_array($cultivate_ids)) {
            $cultivate_ids = array($cultivate_ids);
        }
        $cultivates = M("cultivate")->where(array("cultivate_id"=>array("in", $cultivate_ids)))->select();
        foreach($cultivates as $cultivate) {
            $where = array(
                "isdefault"=>1,
                "cultivate_id"=>$cultivate['cultivate_id'],
            );
            $m_cultivate_urges = M("cultivate_urge")->where($where)->select();
            foreach($m_cultivate_urges as $cultivate_urge) {
                $urge_price = $cultivate['gain'] * abs(doubleval($cultivate_urge["urge_owner_radio"])) / 100 + $cultivate_urge["urge_owner_price"];
                $urge_price = max($urge_price, 0);
                M("cultivate_urge")->where(array("cultivate_urge_id"=>$cultivate_urge['cultivate_urge_id']))->setField("urge_price", $urge_price);
            }
        }
    }

    public function update_default_channel_price($cultivate_ids) {
        if (!is_array($cultivate_ids)) {
            $cultivate_ids = array($cultivate_ids);
        }
        $cultivates = M("cultivate")->where(array("cultivate_id"=>array("in", $cultivate_ids)))->select();
        foreach($cultivates as $cultivate) {
            $where = array(
                "isdefault"=>1,
                "cultivate_id"=>$cultivate['cultivate_id'],
            );
            $m_cultivate_channel = M("cultivate_channel")->where($where)->select();
            foreach($m_cultivate_channel as $cultivate_channel) {
                $channel_price = $cultivate['gain'] * abs(doubleval($cultivate_channel['channel_role_radio'])) / 100 + $cultivate_channel["channel_role_price"];
                M("cultivate_channel")->where(array("cultivate_channel_id"=>$cultivate_channel['cultivate_channel_id']))->setField("channel_price", $channel_price);
            }
        }
    }


    public function channel_add() {
        $cultivate_id = $this->_request('cultivate_id');
        if (!$cultivate_id) {
            alert_back('error', "参数错误");
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $where = array("cultivate.cultivate_id"=>$cultivate_id);
        $cultivate = D('CultivateView')->where($where)->find();
        if (!$cultivate) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['settle_state'] == "918") {
            alert('error', "订单已经结算， 不可修改",$_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()) {
            $_POST['update_role_id'] = session('role_id');
            if (!($cultivate_channel_id = $this->submit_add("cultivate_channel"))) {
                alert_back('error', "新建渠道失败");
            }
            $this->update_cultivate($cultivate_id);
            $this->log($cultivate_id, "添加渠道", "添加渠道成功 => ".$cultivate_channel_id);
            alert('success','添加渠道成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('cultivate/view', 'assort=channel&id='.$cultivate_id));
        } else {
            $fields_group = product_field_list_html('add','cultivate_channel');
            $this->fields_group = $fields_group;
            $this->cultivate = $cultivate;
            $this->refer_url = refer_url('refer_add_url');
            $this->alert = parseAlert();
            $this->display("Cultivate:channel_add");

        }
    }


    public function format_channel_info($value) {
        if ($value['channel_role_model']) {
            $channel = M("channel")->cache(true)->where(array("channel_id"=>$value['channel_role_model']))->find();
            $value['channel_name'] = $channel['name'];
            if ($value['channel_role_id'])
            {
                $value['channel_role_name'] = channel_model_role_show_html($channel, $value['channel_role_id'], true);
            }
        }

        $value['channel_price'] = $value['channel_price'] == 0?"":$value['channel_price'];
        $value['channel_role_name'] = $value['channel_role_name']?$value['channel_role_name']:"";
        return $value;
    }

    public function channel_list() {
        $where = array(
            "cultivate_id"=>$_GET['id']
        );
        $this->ajaxReturn(make_datatable_list("CultivateChannelView", $where, array($this, "format_channel_info")),'JSON');
    }


    public function urge_edit() {
        if (!$_REQUEST['id']) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $cultivate_urge_id = $this->_request('id');
        $where = array(
            "cultivate_urge_id"=>$cultivate_urge_id
        );
        $cultivate_urge = D("CultivateUrgeView")->where($where)->find();
        if (!$cultivate_urge) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        if ($cultivate_urge['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        if($this->isPost()){
            $cultivate_id = $cultivate_urge['cultivate_id'];
            if($this->submit_edit($cultivate_urge_id, "cultivate_urge")) {
                $this->update_cultivate($cultivate_id);
                $this->add_edit_log($cultivate_id, "编辑促单成功: ", D('CultivateUrgeView')->verity_check($cultivate_urge));
                alert('success','编辑促单成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('cultivate/view', 'assort=cost&id='.$cultivate_id));
            } else {
                alert('error', "编辑促单失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            if ($cultivate_urge['settle_state'] == "918") {
                alert('error', "订单已经结算， 不可以修改",$_SERVER['HTTP_REFERER']);
            }
            $this->cultivate_urge_id = $cultivate_urge_id;
            $this->cultivate_urge = $cultivate_urge;
            $this->fields_group = field_list_html_edit('cultivate_urge', $cultivate_urge);
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->alert = parseAlert();;
            $this->display();
        }
    }


    public function urge_delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $cultivate = D('CultivateView')->where(array("cultivate.cultivate_id"=>$this->_request('cultivate_id')))->find();
        if (!$cultivate) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['settle_state'] == "918") {
            alert('error', "订单已经结算",$_SERVER['HTTP_REFERER']);
        }

        $cultivate_urge_ids = $this->isPost() ? $_POST['id'] : $_GET['id'];
        if (!is_array($cultivate_urge_ids)) {
            $cultivate_urge_ids = array($cultivate_urge_ids);
        }
        $where = array(
            "cultivate_urge_id"=>array("in", $cultivate_urge_ids)
        );
        $cultivate_ids = M("CultivateUrge")->where($where)->getField("cultivate_id");

        if ($this->submit_delete($cultivate_urge_ids, array(), "cultivate_urge")) {
            $this->log($cultivate['cultivate_id'], "删除促单费", "删除促单费");
            $this->update_cultivate($cultivate_ids);
            alert('success', "删除成功" ,$_SERVER['HTTP_REFERER']);
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }


    public function urge_add() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if($this->isPost()) {
            $cultivate_id = $this->_request('cultivate_id');
            if (!$cultivate_id) {
                alert_back('error', "参数错误");
            }
            $where = array("cultivate.cultivate_id"=>$cultivate_id);
            $cultivate = D('CultivateView')->where($where)->find();
            if (!$cultivate) {
                alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
            if ($cultivate['settle_state'] == "918") {
                alert('error', "订单已经结算， 不可修改",$_SERVER['HTTP_REFERER']);
            }

            $_POST['update_role_id'] = session('role_id');
            if (!($cultivate_urge_id = $this->submit_add("cultivate_urge"))) {
                alert_back('error', "新建促单失败");
            }
            $this->update_cultivate($cultivate_id);
            $logcnt = "促单人：".role_show($_POST['urge_role_id']). ", 金额: ". $_POST['urge_price'];
            $this->log($cultivate_id, "添加促单费", "添加促单费:".$logcnt);
            alert('success','添加促单成功',$_POST['refer_url'] ? $_POST['refer_url'] : U('cultivate/view', 'assort=cost&id='.$cultivate_id));
        } else {
            $this->fields_group = product_field_list_html('add','cultivate_urge');
            $this->refer_url = refer_url('refer_add_url');
            $this->alert = parseAlert();
            $this->display("Cultivate:urge_add");
        }
    }

    public function native_payment_verify($account_id) {
        return $this->payment_verify($account_id);
    }


    public function pay_deposit($cultivate, $account) {
        $m_model = M($cultivate['model'])->where(array($cultivate['model']."_id"=>$cultivate['model_id']))->find();
        if (!$m_model) {
            return array("error"=>"错误的客户信息");
        }

        $deposit_acc_type = array(
            "product"=>5,
            "customer"=>31
        );
        $_POST['related'] = 'cultivate';
        $_POST['related_id'] = $cultivate['cultivate_id'];
        $_POST['payway'] = $account['payway'];
        $_POST['receipt_number'] = $account['receipt_number'];
        $result = A("Manage/Account")->pay_cultivate_account($cultivate['model'], $cultivate['model_id'], $account['money'], $cultivate, $deposit_acc_type[$cultivate['model']]);
        if (is_array($result)) {
            return $result;
        }
        $flow_account = M('account')->where(array('account_id'=>$result))->find();
        if ($flow_account['infow_account_id']) {
            M('account')->where(array('account_id'=>$flow_account['infow_account_id']))->setField("state", 1);
            M('cultivate_account')->where(array('account_id'=>$account['account_id']))->setField("flow_account_id", $flow_account['infow_account_id']);
        }
        return M('account')->where(array('account_id'=>$account['account_id']))->setField("infow_account_id", $result) === true;
    }

    public function payment_verify($account_id) {
        $account =  M('account')->where(array('account_id'=>$account_id))->find();
        if (!$account) {
            return false;
        }
        $cultivate = M('cultivate')->where(array("cultivate_id"=>$account['related_id']))->find();
        if ($account['payway'] != "余额冻结") {
            $result = $this->pay_deposit($cultivate, $account);
            if (is_array($result)) {
                $this->log($cultivate['cultivate_id'], "支付确认", "雇员账目预存款失败, ".$result['error']);
                return false;
            }
        }

        $acc_type = array(
            "product"=>262,
            "customer"=>285
        );
        unset($_POST['receipt_number']);
        $m_account = A("Manage/Account");
        $result = $m_account->pay_cultivate_account($cultivate['model'], $cultivate['model_id'], $account['money'], $cultivate, $acc_type[$cultivate['model']]);
        if (!is_array($result)) {
            $m_account->account_payment_verify_update($account, 1);
        }
        return !is_array($result);
    }


    public static function calculate_cultivate_account($cultivate_ids) {
        if (!is_array($cultivate_ids)) {
            $cultivate_ids = array($cultivate_ids);
        }
        foreach($cultivate_ids as $cultivate_id) {
            $cultivate = D('CultivateView')->where(array("cultivate.cultivate_id"=>$cultivate_id))->find();

            $m_cultivate_account = D("Manage/CultivateAccountView");
            $acc_type = array(
                "product"=>261,
                "customer"=>282
            );
            $confirm_price = $m_cultivate_account->where(array(
                'cultivate.cultivate_id'=>$cultivate['cultivate_id'],
                'account.clause_type_id'=>$acc_type[$cultivate['model']],
                'payment_verify'=>1))->sum("account.money");
            $wait_confirm_price = $m_cultivate_account->where(array(
                'cultivate.cultivate_id'=>$cultivate['cultivate_id'],
                'account.clause_type_id'=>$acc_type[$cultivate['model']],
                'payment_verify'=>0))->sum("account.money");

            $deficit_price = self::calculate_deficit_account($cultivate);
            if ($cultivate['settle_state'] == 918) {
                $surplus_price = 0;
            } else {
                $surplus_price = $cultivate['sum_settle_price'] - $deficit_price;
            }
            $model_earnest = self::calculate_earnest_account($cultivate);
            $sum_channel_price = M("cultivate_channel")->where(array('cultivate_id'=>$cultivate['cultivate_id']))->sum("channel_price");
            $urge_price_model_owner = M("cultivate_urge")->where(array('urge_role_class'=>'建档人','cultivate_id'=>$cultivate['cultivate_id']))->sum("urge_price");
            $urge_price_cultivate_owner = M("cultivate_urge")->where(array('urge_role_class'=>'建单人','cultivate_id'=>$cultivate['cultivate_id']))->sum("urge_price");

            $cultivate_data = array(
                "confirm_price"=>$confirm_price,
                "wait_confirm_price"=>$wait_confirm_price,
                "deficit_price"=>$deficit_price,            //不足额
                "surplus_price"=>$surplus_price,            //多余的
                "model_earnest"=>$model_earnest,
                "sum_channel_price"=>$sum_channel_price,
                "urge_price_model_owner"=>$urge_price_model_owner,
                "urge_price_cultivate_owner"=>$urge_price_cultivate_owner,
            );

            if ($cultivate['settle_state'] < 917) {
                if ($deficit_price <=0) {
                    $cultivate_data['settle_state'] = 913;
                } else if($deficit_price > 0 && $deficit_price < $cultivate['sum_settle_price']) {
                    $cultivate_data['settle_state'] = 914;
                } else if (($confirm_price < $cultivate['sum_settle_price']) || ($wait_confirm_price > 0 && $deficit_price > $cultivate['sum_settle_price'])) {
                    $cultivate_data['settle_state'] = 915;
                } else if ($confirm_price >= $cultivate['sum_settle_price']) {
                    $cultivate_data['settle_state'] = 916;
                }
            }
            M("cultivate")->where(array('cultivate_id'=>$cultivate['cultivate_id']))->setField($cultivate_data);
        }
    }


    public static function calculate_deficit_account($cultivate) {
        $acc_type = array(
            "product"=>261,
            "customer"=>282
        );
        $where = array(
            "account.account_type"=>'cultivate',
            "cultivate.cultivate_id"=>$cultivate['cultivate_id'],
            "account.clause_type_id"=>$acc_type[$cultivate['model']],
        );
        $out_sum_money = D("CultivateAccountView")->where($where)->sum('account.money');
        if (!$out_sum_money) {
            $out_sum_money = 0.0;
        }

        $acc_type = array(
            "product"=>260,
            "customer"=>283
        );
        $where['account.clause_type_id'] = $acc_type[$cultivate['model']];
        $in_sum_money = D("CultivateAccountView")->where($where)->sum('account.money');
        if (!$in_sum_money) {
            $in_sum_money = 0.0;
        }
        return $out_sum_money - $in_sum_money;
    }

    public static function relieve_account_money($cultivate){
        $acc_type = array(
            "product"=>263,
            "customer"=>284
        );
        $where = array(
            "account.account_type"=>$cultivate['model'],
            "product.product_id"=>$cultivate['model_id'],
            "account.related"=>'cultivate',
            "account.related_id"=>$cultivate['cultivate_id'],
            "account.clause_type_id"=>$acc_type[$cultivate['model']],
        );
        $relieve_sum_money = D(ucfirst($cultivate['model'])."AccountView")->where($where)->sum('account.money');
        return $relieve_sum_money ? $relieve_sum_money:0;
    }

    public static function calculate_earnest_account($cultivate) {
        $acc_type = array(
            "product"=>262,
            "customer"=>285
        );
        $where = array(
            "account.account_type"=>$cultivate['model'],
            "product.product_id"=>$cultivate['model_id'],
            "account.related"=>'cultivate',
            "account.related_id"=>$cultivate['cultivate_id'],
            "account.clause_type_id"=>$acc_type[$cultivate['model']],
        );
        $freeze_sum_money = D(ucfirst($cultivate['model'])."AccountView")->where($where)->sum('account.money');
        if (!$freeze_sum_money) {
            $freeze_sum_money = 0.0;
        }
        return $freeze_sum_money - self::relieve_account_money($cultivate);
    }

    public function payment_status($type_id, $account_id, $cultivate, $usable) {
        if (!is_array($cultivate)) {
            $where = array(
                "cultivate.cultivate_id"=>$cultivate
            );
            $cultivate = D("Manage/CultivateView")->where($where)->find();
        }
        $account_type = M("account_type")->cache(true)->where(array("type_id"=>$type_id))->find();

        if ($cultivate) {
            $acc_type = array(
                "jie"=>array(
                    "product"=>263,
                    "customer"=>284
                ),
                "dong"=>array(
                    "product"=>262,
                    "customer"=>285
                ),
                "tui"=>array(
                    "product"=>260,
                    "customer"=>283
                ),
                "fu"=>array(
                    "product"=>261,
                    "customer"=>282
                ),
            );
            if ($account_type['type_id'] == $acc_type['jie'][$cultivate['model']]) {
                define("NO_AUTHORIZE_CHECK", true);
                $result = A("Manage/Account")->pay_cultivate_account("cultivate", $cultivate['cultivate_id'], $usable, $cultivate, $acc_type['tui'][$cultivate['model']]);
                if (!is_array($result)) {
                    $logcnt = "培训订单金额解冻, 向雇员退款 ".number_format($usable,2).", <a href='".U("account/view", "id=".$result)."'>查看</a>";
                    $this->log($cultivate['cultivate_id'], "培训订单金额解冻",$logcnt);
                } else {
                    $this->log($cultivate['cultivate_id'], "培训订单金额解冻",$result['error']);
                }

            } else if ($account_type['type_id'] == $acc_type['dong'][$cultivate['model']]){
                $_POST['payway'] = "余额冻结";
                define("NO_AUTHORIZE_CHECK", true);
                $result = A("Manage/Account")->pay_cultivate_account("cultivate", $cultivate['cultivate_id'], $usable, $cultivate, $acc_type['fu'][$cultivate['model']]);
                $data = array(
                    "payment_verify"=>1,
                );
                $data['verify_time'] = time();
                $data['verify_role_id'] = session('role_id');
                $result = M('cultivate_account')->where(array('account_id'=>$result))->setField($data);
                if (!is_array($result)) {
                    self::calculate_cultivate_account($cultivate['cultivate_id']);
                    $logcnt = "培训订单金额冻结 ".number_format($usable,2).", <a href='".U("account/view", "id=".$result)."'>查看</a>";
                    $this->log($cultivate['cultivate_id'], "培训订单金额冻结", $logcnt);
                } else {
                    $this->log($cultivate['cultivate_id'], "培训订单金额解冻",$result['error']);
                }
            } else {
                $data = array(
                    "payment_time"=>time()
                );
                M("cultivate_account")->where("account_id=".$account_id)->setField($data);

                $logtip = AccountAction::format_update_log_info($account_id, $account_type, $usable);
                $this->log($cultivate['cultivate_id'], "培训订单账目", $logtip);
            }
            $this->update_surplus_price("cultivate", $cultivate['cultivate_id']);
        }
    }


    public function format_account_info($value) {
        $value['create_time_show'] =toDate($value['create_time']);
        $value['payment_time_show'] =toDate($value['payment_time']);
        $value['payment_verify_show'] =payment_verify_map($value['payment_verify']);
        $value['per_delete'] = session('?admin') || (!is_cultivate_settle($value) && $value['status_id'] != 0 && $value['verify_time'] == 0);

        if ($value['verify_role_id'] != 0) {
            $value['verify_role_show'] =role_html($value['verify_role_id']);
            $value['verify_time_show'] =toDate($value['verify_time']);
        } else {
            $value['verify_role_show'] ="";
            $value['verify_time_show'] ="";
        }

        if (in_array($value['clause_type_id'], array(263, 260, 284, 283))) {
            $value['payway'] = in_array($value['clause_type_id'], array(263, 284)) ? "账目解冻":"账目退款";
            $value['payment_verify'] = "1";
            $value['per_delete'] = false;
            $value['flow_account_idcode'] = "";
            if (in_array($value['clause_type_id'], array(263, 284))) {
                $value['description'] = "培训订单定金解冻,".$value['description'];
                if ($value['quarry'] == 1) {

                } else {
                    $value['money'] = $value['money'] - $value['sum_settle_price'];
                }
            } else {
                $value['description'] = "培训订单退款记录".$value['description'];
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
            "cultivate.cultivate_id"=>$_GET['id'],
            "account.related"=>"cultivate",
            "account.clause_type_id"=>array("in", array(261,263, 284, 282))
        );
        $this->ajaxReturn(make_datatable_list("CultivateAccountView", $where, array($this, "format_account_info")),'JSON');
    }

    public function submit_settlement() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if (!vali_permission("cultivate", "edit/basic")) {
            alert('error', '您没有此权利!', $_SERVER['HTTP_REFERER']);
        }

        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $cultivate_id = $this->_request('id');

        $where = array("cultivate.cultivate_id"=>$cultivate_id);
        $cultivate = D('CultivateView')->where($where)->find();
        if (!$cultivate) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }
        if (is_cultivate_settle($cultivate)) {
            alert('error', "订单已经提交结算",$_SERVER['HTTP_REFERER']);
        }

        if ($cultivate['status_id'] != 3 || $cultivate['deficit_price'] < $cultivate['sum_settle_price'] || $cultivate['wait_confirm_price']!=0) {
            alert('error', "订单还没有完结",U("cultivate/view", "id=".$cultivate_id));
        }
        $cultivate_data = array(
            "settle_state"=>917,
            "is_cancel_submit"=>0,
        );
        M("cultivate")->where(array('cultivate_id'=>$cultivate_id))->setField($cultivate_data);
        $this->log($cultivate_id, "订单结算", "提交结算");
        alert('success', "提交完成",$_SERVER['HTTP_REFERER']);
    }


    private function thaw_product_cultivate_money($cultivate) {
        define("NO_AUTHORIZE_CHECK", true);
        $acc_type = array(
            "product"=>263,
            "customer"=>284
        );
        $result = A("Manage/Account")->pay_cultivate_account($cultivate['model'], $cultivate['model_id'], $cultivate['model_earnest'], $cultivate, $acc_type[$cultivate['model']]);
        if (is_array($result)) {
            throw_exception_log($result['error'], "雇员账目解冻失败");
        }
        $logcnt = "完成培训订单款解冻。 <a href='".U("account/view", "id=".$result)."'>查看</a>";
        $this->log($cultivate['cultivate_id'], "雇员账目解冻成功", $logcnt);
    }


    private function sum_model_salary($cultivate) {
        $acc_type = array(
            "product"=>280,
            "customer"=>286
        );
        $accwhere = array(
            "related"=>"cultivate",
            "related_id"=>$cultivate['cultivate_id'],
            "clause_type_id"=>$acc_type[$cultivate['model']],
            "account_type"=>$cultivate['model'],
            "clause_additive"=>$cultivate['model_id'],
        );
        $money = D("CultivateAccountView")->where($accwhere)->sum("account.money");
        return $money ? $money : 0;
    }

    private function settlement_cultivate_salary($cultivate) {
        define("NO_AUTHORIZE_CHECK", true);
        $acc_salary = $this->sum_model_salary($cultivate);
        if ($cultivate['sum_settle_price'] > 0 && ($salary = $cultivate['sum_settle_price'] - $acc_salary) > 0) {
            $acc_type = array(
                "product"=>281,
                "customer"=>287
            );
            $result = A("Manage/Account")->pay_cultivate_salary($salary, $cultivate, $acc_type[$cultivate['model']], $cultivate['job_number']);
            if (is_array($result)) {
                throw_exception_log($result['error'], "新培训费结算失败");
            }
            $logcnt = "完成新培训费结算：".$salary.", <a target='_blank' href='".U("account/view", "id=".$result)."'>查看</a>";
        } else {
            $logcnt = "新培训费金额为0， 无需结算,salary:".$cultivate['salary'].", ".$salary;
        }
        M("cultivate")->where("cultivate_id=".$cultivate['cultivate_id'])->setField("salary_settle_time", time());
        $this->log($cultivate['cultivate_id'], "新培训费结算成功",$logcnt);
    }

    private function settlement_cultivate_channel($cultivate, $m_cultivate_channel) {
        define("NO_AUTHORIZE_CHECK", true);
        $channel_models = array(
            "2"=>array("product", 268),
            "3"=>array("customer", 270),
            "4"=>array("staff", 266),
        );
        foreach($m_cultivate_channel as $channel) {
            if (array_key_exists($channel['channel_role_model'], $channel_models)) {
                $channel_account_type = $channel_models[$channel['channel_role_model']][1];
                $channel_model = $channel_models[$channel['channel_role_model']][0];
                if ($channel['channel_role_id']) {
                    if ($channel['channel_price'] == 0) {
                        $this->log($cultivate['cultivate_id'], "渠道结算成功", $channel['cultivate_channel_id']." - 渠道金额为0， 无需结算");
                        M("cultivate_channel")->where("cultivate_channel_id=".$channel['cultivate_channel_id'])->setField("channel_price_settle_time", time());
                        continue;
                    }
                    $result = A("Manage/Account")->pay_cultivate_account($channel_model, $channel['channel_role_id'], $channel['channel_price'], $cultivate, $channel_account_type);
                    if (is_array($result)) {
                        throw_exception_log($result['error'], "渠道结算失败");
                    }
                    $logcnt = "完成渠道结算 "." <a href='".U("account/view", "id=".$result)."'>查看</a>";
                    $this->log($cultivate['cultivate_id'], "渠道结算成功", $logcnt);
                    M("cultivate_channel")->where("cultivate_channel_id=".$channel['cultivate_channel_id'])->setField("channel_price_settle_time", time());
                }
            }
        }
    }


    private function settlement_cultivate_urge($cultivate, $m_cultivate_urges) {
        define("NO_AUTHORIZE_CHECK", true);
        foreach($m_cultivate_urges as $urge) {
            if ($urge['urge_price'] == 0) {
                $this->log($cultivate['cultivate_id'], "促单结算成功", $urge['cultivate_urge_id']." - 促单金额为0， 无需结算");
                continue;
            }

            $result = A("Manage/Account")->pay_cultivate_account("staff", $urge['staff_id'], $urge['urge_price'], $cultivate, 265);
            if (is_array($result)) {
                throw_exception_log($result['error'], "促单结算失败");
            }
            M("cultivate_urge")->where("cultivate_urge_id=".$urge['cultivate_urge_id'])->setField("urge_price_settle_time", time());
            $logcnt = "完成促单结算,净利润：".$cultivate['gain'].". <a href='".U("account/view", "id=".$result)."'>查看</a>";
            $this->log($cultivate['cultivate_id'], "促单结算成功", $logcnt);
        }
    }


    private function check_initial_state($model, $model_id) {
        $initial_where = array(
            'model'=>$model,
            'model_id'=>$model_id,
            'status_id'=>array("neq", 0)
        );
        $min_create_time = M("cultivate")->where($initial_where)->min("create_time");
        return  M("cultivate")->where(array("create_time"=>$min_create_time))->find();
    }

    private function settlement_cultivate($cultivate) {
        G('settlement_cultivateStartTime');
        $this->log($cultivate['cultivate_id'], "结算订单", "开始结算订单");
        if ($cultivate['model_earnest'] > 0) {
            $this->thaw_product_cultivate_money($cultivate);
        } else {
            $this->log($cultivate['cultivate_id'], "雇员账目解冻完成", "冻结款金额为0， 无需解冻。".$cultivate['model_earnest']);
        }

        $this->settlement_cultivate_salary($cultivate);

        $where = array(
            "cultivate.cultivate_id"=>$cultivate['cultivate_id'],
            "cultivate_channel.channel_price_settle_time"=>0
        );
        $m_cultivate_channel = D("CultivateChannelView")->where($where)->select();
        if ($m_cultivate_channel) {
            $this->settlement_cultivate_channel($cultivate, $m_cultivate_channel);
        }else {
            $this->log($cultivate['cultivate_id'], "渠道费结算完成", "没有设置渠道信息");
        }

        $where = array(
            "cultivate.cultivate_id"=>$cultivate['cultivate_id'],
            "cultivate_urge.urge_price_settle_time"=>0
        );
        $m_cultivate_urges = D("CultivateUrgeView")->where($where)->select();
        if ($m_cultivate_urges) {
            $this->settlement_cultivate_urge($cultivate, $m_cultivate_urges);
        } else {
            $this->log($cultivate['cultivate_id'], "促单费结算完成", "没有设置促单信息");
        }

        M("cultivate")->where(array('cultivate_id'=>$cultivate['cultivate_id']))->setField(array( "settle_state"=>918, "settle_date"=>time()));

        $this->update_cultivate($cultivate['cultivate_id']);
        G('settlement_cultivateEndTime');
        $this->log($cultivate['cultivate_id'], "订单结算完成", "订单结算完成."."用时: ".G('settlement_cultivateStartTime','settlement_cultivateEndTime',6)."s");
    }

    public function settlement() {
        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $cultivate_id = $this->_request('id');

        $cultivate = D('CultivateView')->where(array("cultivate.cultivate_id"=>$cultivate_id))->find();
        if (!$cultivate) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['settle_state'] == '918') {
            alert('error', "订单已经结算了",$_SERVER['HTTP_REFERER']);
        }
        if ($cultivate['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }

        if (is_nosettle_cate($cultivate['category_id'])) {
            if ($cultivate['status_id'] != 3 || $cultivate['settle_state'] < 916) {
                alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
        } else {
            if ($cultivate['settle_state'] != 917) {
                alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
            if ($cultivate['settle_state'] < 917) {
                alert('error', "订单还没有提交结算",$_SERVER['HTTP_REFERER']);
            }
        }

        try {
            $lock = new FileLock();
            $this->settlement_cultivate($cultivate);
            alert('success', "结算完成",$_SERVER['HTTP_REFERER']);
        }catch (LogException  $e) {
            $this->log($cultivate_id, $e->getTitle(), $e->getMessage());
            alert('error', "订单结算失败: ".$e->getMessage(),$_SERVER['HTTP_REFERER']);
        }catch(Exception $e) {
            $this->log($cultivate_id, "结算失败,系统错误", $e->getMessage());
            alert('error', "结算失败,系统错误: ".$e->getMessage(),$_SERVER['HTTP_REFERER']);
        }
    }

    public function revoke() {
        if (intval($this->_request('id')) <= 0) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $cultivate_id = $this->_request('id');
        $cultivate = D('CultivateView')->where(array("cultivate.cultivate_id"=>$cultivate_id))->find();
        if (!$cultivate) {
            alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }

        if ($cultivate['status_id'] == '0') {
            alert('error', "订单已经撤销",$_SERVER['HTTP_REFERER']);
        }

        if ($cultivate['wait_confirm_price'] > 0) {
            alert('error', "订单存在未确认账目，到付款信息页面修改",$_SERVER['HTTP_REFERER']);
        }
        if (is_cultivate_settle($cultivate)) {
            alert('error', "订单已经提交结算",$_SERVER['HTTP_REFERER']);
        }
        if($cultivate['model_earnest']>0) {
            define("NO_AUTHORIZE_CHECK", true);
            $acc_type = array(
                "product"=>263,
                "customer"=>286
            );
            $result = A("Manage/Account")->pay_cultivate_account($cultivate['model'], $cultivate['model_id'], $cultivate['model_earnest'], $cultivate, $acc_type[$cultivate['model']]);
            if (is_array($result)) {
                alert('error', $result['error'],$_SERVER['HTTP_REFERER']);
            }
            $this->payment_status($acc_type[$cultivate['model']], $result, $cultivate_id, $cultivate['model_earnest']);
            $logcnt = "撤销培训订单导致冻结款全部解冻 "." <a href='".U("account/view", "id=".$result)."'>查看</a>";
            $this->log($cultivate_id, "解冻培训订单冻结款", $logcnt);
        }

        $cultivate_data = array(
            "status_id"=>0,
            "settle_state"=>920,
            "initial"=>"否"
        );
        M("cultivate")->where(array('cultivate_id'=>$cultivate_id))->setField($cultivate_data);

        $first_clutivate = $this->check_initial_state($cultivate['model'], $cultivate['model_id']);
        M("cultivate")->where(array('cultivate_id'=>$first_clutivate['cultivate_id']))->setField("initial", "是");

        $this->update_cultivate($cultivate_id);
        $this->log($cultivate_id, "订单撤销", "订单撤销成功");
        alert('success', "订单撤销成功, 已经解冻定金",$_SERVER['HTTP_REFERER']);
    }


    public function advance_status_ergodic($curtime) {
        $where = array(
            "status_id"=>array("neq", 0),
            "settle_state"=>array("not in", array("918"))
        );
        $fields = "cultivate_id,status_id,end_time, start_time, examine_state,examine_time,cert_state,getcert_time,cert_number,applycert_time";
        $m_cultivates = M('cultivate')->field($fields)->where($where)->select();
        foreach($m_cultivates as $cultivate) {
            self::update_cultivate_service_status($cultivate, $curtime);
        }
    }

    public function submit_group($module_group_id = null) {
        unset($_REQUEST['group_name'], $_REQUEST['undefined'], $_REQUEST['refer_url'], $_REQUEST['group_type']);
        if ($this->_post('group_type') == 0) {
            $data['content'] = serialize($_REQUEST);
        }
        $data['name']  = $this->_post('group_name'); //字段名称
        $data['creator_role_id'] = session('role_id');
        $data['create_time'] = time();
        $data['group_type'] = $this->_post('group_type');
        $data['currier_name'] = $this->_post('currier_name');
        $data['category_name'] = $this->_post('category_name');
        return $this->update_group($module_group_id, $data);
    }

    public function format_excel_fields($ex) {
        $where = array(
            "model"=>"cultivate",
            "form_type"=>array("not in",array(
                "pic","video","file"
            )),
        );
        $field_list = M('Fields')->where($where)->order('order_id')->select();
        $field_list2 = array(
            array("name"=>"学员登记时间", "field"=>"cultivate_model_create_time"),
        );
        array_splice($field_list, 14, 0, $field_list2);
        return $field_list;
    }

}