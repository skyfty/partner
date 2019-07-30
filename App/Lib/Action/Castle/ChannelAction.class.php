<?PHP
class ChannelAction extends CastleAction{
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


        if($where['channel.apply_scope']) {
            $where['_string'] .= " AND FIND_IN_SET('".$where['channel.apply_scope'][1]."', apply_scope)";
            unset($where['channel.apply_scope']);
        }

        $by = isset($_GET['by']) ? trim($_GET['by']) : '';
        $params[] = "by=".$by;

        switch ($by) {
            case 'today' :
                $where['create_time'] =  array('gt',strtotime(date('Y-m-d', time()))); break;
            case 'week' :
                $where['create_time'] =  array('gt',(strtotime(date('Y-m-d')) - (date('N', time()) - 1) * 86400)); break;
            case 'month' :
                $where['create_time'] = array('gt',strtotime(date('Y-m-01', time()))); break;

        }
        $where['channel_id']=array("neq", 0);

        if ($_GET['parentid'] === "0"){
            unset($_GET['parentid']);
            $where['parentid']=array("eq", "");
        }

        self::show_list_index_html($where, $params, "渠道表");
    }

    public function get_customer_product_channel() {
        $data = array();
        $model = $_GET['model'] ? $_GET['model']:"product";
        if ($_GET['telephone']) {
            $telephone = trim($_GET['telephone']);
            if ($model == "product") {
                $m_product = M("product")->field("channel_role_id,channel_role_model, telephone")->where(array("telephone"=>$telephone))->find();
                if ($m_product) {
                    $channel = M("channel")->where(array("channel_id"=>$m_product['channel_role_model']))->find();
                    $data["product"] = array(
                        "channel_role_id"=>$m_product['channel_role_id'],
                        "channel_role_model"=>$m_product['channel_role_model'],
                        "channel_role_model_name"=>$channel['name'],
                        "channel_role_id_name"=>channel_model_role_show_html($channel, $m_product['channel_role_id'], false)
                    );
                }
            }

            if ($model == "customer") {
                $m_customer = M("customer")->field("channel_role_id,channel_role_model, telephone")->where(array("telephone" => $telephone))->find();
                if ($m_customer) {
                    $channel = M("channel")->where(array("channel_id"=>$m_customer['channel_role_model']))->find();
                    $data["customer"] = array(
                        "channel_role_id"=>$m_customer['channel_role_id'],
                        "channel_role_model"=>$m_customer['channel_role_model'],
                        "channel_role_model_name"=>$channel['name'],
                        "channel_role_id_name"=>channel_model_role_show_html($m_customer['channel_role_model'], $m_customer['channel_role_id'], false)
                    );
                }
            }

            if ($model == "commiss") {
                $m_commiss = M("commiss")->field("channel_role_id,channel_role_model, telephone")->where(array("telephone" => $telephone))->find();
                if ($m_commiss) {
                    $channel = M("channel")->where(array("channel_id"=>$m_commiss['channel_role_model']))->find();
                    $data["commiss"] = array(
                        "channel_role_id"=>$m_commiss['channel_role_id'],
                        "channel_role_model"=>$m_commiss['channel_role_model'],
                        "channel_role_model_name"=>$channel['name'],
                        "channel_role_id_name"=>channel_model_role_show_html($m_commiss['channel_role_model'], $m_commiss['channel_role_id'], false)
                    );
                }
            }
        }
        $this->ajaxReturn($data,"",1);
    }

    public function display_index_html() {
        if ($_REQUEST['act'] != "tree") {
            parent::display_index_html();
            return;
        }
        $this->display_tree_html();
    }


    public function display_tree_html() {
        $this->alert = parseAlert();
        session("index_refer_url", $_SERVER['REQUEST_URI']);
        $this->display("tree");
    }

    public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()) {
            if (!isset($_POST['name']) || $_POST['name'] == '') {
                $this ->error(L('必须设置渠道名称'));
            }
            $_POST['league_id'] = session('league_id');

            $channel_id = $this->submit_add();
            if ($channel_id) {
                $idcode = sprintf("%03d", $channel_id);
                $data = array(
                    'idcode'=>$idcode,
                );
                M('channel')->where(array('channel_id'=>$channel_id))->setField($data);
                $this->update_child_channel_ids($channel_id);
                delete_cache_temp();
                if($_POST['refer_url']) {
                    alert('success', "新建渠道成功", $_POST['refer_url']);
                }else{
                    alert('success', "新建渠道成功", U("channel/view", "id=".$channel_id));
                }
            } else {
                $this->alert = parseAlert();
                alert('error', "新建渠道失败", $_POST['refer_url']);
            }

        }else{
            $this->fields_group = product_field_list_html("add","channel", array(), "basic");
            $this->alert = parseAlert();
            $this->refer_url= refer_url('refer_add_url');
            $this->display();
        }
    }

    public function view(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $assort = $_GET['assort'] ? $_GET['assort'] : "basic";
        $channel_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $channel_id) {
            alert('error', L('PARAMETER_ERROR'), U('channel/index'));
        }

        $where = array(
            "url"=>"channel_permission/channelid/".$channel_id
        );
        $this->permission_list = D("ChannelPositionPermissionView")->where($where)->select();

        $channel = D('ChannelView')->where('channel.channel_id = %d ', $channel_id)->find();
        $channel['is_permission'] = session('?admin') || self::is_permission($channel['channel_id'], session("role_id"));
        $this->channel = $channel;
        $fields_group = product_field_list_show('channel', $channel);
        if ($channel['channel_id'] != 2) {

        }
        $this->fields_group = $fields_group;
        $this->alert = parseAlert();
        $this->refer_url= refer_url('refer_view_url');
        $this->assort =  $assort;
        $this->display($assort."_view");
    }

    public function getlist() {
        $this->ajaxReturn(M('channel')->where(array('league_id'=>session('league_id')))->select());
    }

    public function perfect_list_item($value, $export = false, $branchlock = false) {
        $value['is_permission'] =  session('?admin') ||self::is_permission($value['channel_id'], session("role_id"));
        return parent::perfect_list_item($value, $export, $branchlock);
    }

    public function edit(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $channel = D('ChannelView')->where('channel.channel_id = %d',$this->_request('id'))->find();
        if (!$channel) {
            alert('error', "没有这个渠道",$_SERVER['HTTP_REFERER']);
        }
        if (!session('?admin') && !$this->is_permission($channel['channel_id'], session("role_id"))) {
            alert('error', "你没有权限",$_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()){
            if($this->submit_edit($channel['channel_id'])) {
                if ($_POST['name'] != $channel['name']) {
                    $this->update_related_model_channel_keyword($channel);
                }
                $this->update_child_channel_ids($channel);
                delete_cache_temp();
                alert('success', "编辑渠道成功", U('channel/view', 'id='.$channel['channel_id']));
            } else {
                alert('error', "编辑渠道失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            $alert = parseAlert();
            $this->alert = $alert;
            $this->channel = $channel;
            $this->model_id = $channel['channel_id'];
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $fields_group = product_field_list_html("edit","channel",$channel);;
            if ($channel['channel_id'] != 2) {

            }
            $this->fields_group =$fields_group;
            $this->display();
        }
    }

    static function general_channel_tree($channel) {
        $text = $channel['name'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$channel['status'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$channel['model']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        $where2 = array(
            "permission.description"=>"channel_permission",
            "url"=>"channel_permission/channelid/".$channel['channel_id']
        );
        $pn = array();
        foreach(D("ChannelPermissionView")->where($where2)->select() as $pv) {
            $pn[] = $pv['position_name'];
        }
        $text.=implode(",", array_unique($pn));

        $branch_cat = array(
            "id"=>$channel['channel_id'],
            'data'=>$channel['channel_id'],
            "text"=>$text,
            "state"=>array("opened"=>true),
            "icon"=> "/Public/img/admin_img.png",
            "children"=>array()
        );
        return $branch_cat;
    }

    function enum_channel_tree($channel_id, $where, &$children = array()) {
        $where["parentid"]=$channel_id;
        foreach(M("channel")->where($where)->select() as $v) {
            $channel_info = self::general_channel_tree($v);
            self::enum_channel_tree($v['channel_id'], $where,  $channel_info['children']);
            $children[] = $channel_info;
        }
    }

    public function channel_tree(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $where = array();
        $channel_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($_GET['status'] && $_GET['status'] != "全部") {
            $where['status'] = $_GET['status'];
        }
        $where['channel_id'] = array("neq", 0);
        $where['league_id'] = session('league_id');
        $children = array();
        self::enum_channel_tree($channel_id, $where, $children);
        $this->ajaxReturn(array(array("id"=>0,'state'=>array('opened'=>true, 'locked'=>true), "icon"=> "/Public/img/admin_gohome.gif", "text"=>"渠道", "children"=>$children)));
    }

    public function update_child_channel_ids($channel) {
        if (!is_array($channel)) {
            $channel = M('channel')->where('channel_id = %d',$channel)->find();
        }
        if ($channel) {
            $channel_id = ($channel['parentid'] == "" ? $channel['channel_id'] : $channel['parentid']);
            $child_channels = array();
            foreach(M('channel')->where('parentid = %d',$channel_id)->select() as $child) {
                $child_channels[] = $child['channel_id'];
            }
            M('channel')->where('channel_id = %d',$channel_id)->setField("child_channel_ids", implode(",", $child_channels));
            delete_cache_temp();
        }
    }

    public function update_related_model_channel_keyword($channel) {
        if (!is_array($channel)) {
            $channel = M('channel')->where('channel_id = %d',$channel)->find();
        }
        if ($channel) {
            $channel_role_model_keyword = array();
            $channel_role_model_keyword[] = $channel['name'];
            $data = array(
                "channel_role_model_keyword"=>implode(chr(10), $channel_role_model_keyword),
            );
            M("product")->where("channel_role_model=".$channel['channel_id'])->setField($data);
            M("customer")->where("channel_role_model=".$channel['channel_id'])->setField($data);
            M("cultivate")->where("channel_role_model=".$channel['channel_id'])->setField($data);
            M("cultivate_channel")->where("channel_role_model=".$channel['channel_id'])->setField($data);
            M("market_channel")->where("channel_role_model=".$channel['channel_id'])->setField($data);
        }
    }

    public function listDialog(){
        if ($this->isAjax() === false) {
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
            return $this->display("Channel:listDialog");
        }

        $data_field = array(
            array(
                "field"=>"channel_id",
                "order"=>"channel_id"
            ),
            array(
                "field"=>"channel_name",
                "order"=>"channel_id"
            ),
            array(
                "field"=>"model",
                "order"=>"model"
            ),
        );
        $m_model_name = $_GET['model']? $_GET['model']."View":"ChannelView";
        $where = $this->parse_dialog_where();
        $data = make_data_list($m_model_name, $where, $data_field, array($this, "format_dialog_item"));
        $this->ajaxReturn($data,'JSON');
    }


    public function format_dialog_item($val) {
        $val["channel_id"] = array(
            "channel_id"=>$val['channel_id']
        );
        return $val;
    }

    public function parse_dialog_where() {
        $where = parent::parse_dialog_where();
        if ($_GET['showroot']) {
            $where['channel_id']=array("neq", 0);
        }
        $where['status'] = array("neq", "禁用");
        if ($_GET['model']) {
            $where['_string'] =trim($_GET['query']);
        }

        if ($_GET['module'] == "product" || $_GET['module'] == "customer") {
            if ($where['_string']) {
                $where['_string'].=" and ";
            }
            if ($_GET['module'] == "product") {
                $where['_string'] .= "FIND_IN_SET('雇员', apply_scope)";
            } else {
                $where['_string'] .= "FIND_IN_SET('客户', apply_scope)";
            }
        }
        $where['league_id'] = session('league_id');

        return $where;
    }

    public function channel_position_permission() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $channel_id = $_GET['id'];
        if (!$_GET['id'] || !$_GET['did'] || !$_GET['pid']) {
            alert('error', "服务类别不可为空",$_SERVER['HTTP_REFERER'] );
        }
        $channel = D('ChannelView')->where('channel.channel_id = %d',$channel_id)->find();
        if (!$channel) {
            alert('error', "没有这个渠道",$_SERVER['HTTP_REFERER']);
        }

        $position_id = $_GET['pid'];

        $data = array(
            "position_id"=>$position_id,
            "url"=>"channel_permission/channelid/".$channel['channel_id'],
            "description"=>"channel_permission"
        );
        if (M("permission")->where($data)->count() > 0) {
            alert('error', "这个渠道已经授权",$_SERVER['HTTP_REFERER']);
        }
        M("permission")->add($data);
        delete_cache_temp();
        alert('success', "编辑渠道成功", U('channel/view', 'assort=permission&id='.$channel['channel_id']));
    }

    public function delete_channel_position_permission() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $channel_id = $_GET['id'];
        if (!$_GET['id'] || !$_GET['perid']) {
            alert('error', "服务类别不可为空",$_SERVER['HTTP_REFERER'] );
        }
        $channel = D('ChannelView')->where('channel.channel_id = %d',$channel_id)->find();
        if (!$channel) {
            alert('error', "没有这个渠道",$_SERVER['HTTP_REFERER']);
        }
        $permission_id = $_GET['perid'];
        M("permission")->where("permission_id=".$permission_id)->delete();
        delete_cache_temp();
        alert('success', "编辑渠道成功", U('channel/view', 'assort=permission&id='.$channel['channel_id']));
    }


    function is_permission($channel_id, $role_id) {
        $where = array(
            "role.role_id"=>$role_id,
            "permission.url"=>"channel_permission/channelid/".$channel_id
        );
        return D("UserPermissionView")->cache(true)->where($where)->count() > 0;
    }
}
