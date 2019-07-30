<?PHP 
class ServeAction extends BaseAction{
	public function _initialize(){
		$action = array(
			'permission'=>array(
                'search',
                'getcategory',
                'category',
                'delcategory',
                'categoryedit',
                'addcategory',
                'getcategoryselect',
                'delimg',
                'deletevideo',
                'deletefile',
                'validate',
                'getinfo',
                'trade',
                'analytics',
                'webshow'
            )
		);
		B('Authenticate', $action);
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

        $cat = $_GET['cat'] ? $_GET['cat']:0;
        if ($cat != 0) {
            $bread_list = breadcrumb("serve", $cat,$where['league_id']);
            array_pop($bread_list);
            $this->bread_list = $bread_list;
            $this->focus_category = M("serve_category")->where(array("serve_category_id="=>$cat, "league_id"=>$where['league_id']))->find();
            $allchild = cateallchild("serve",$cat,$where['league_id']);
            $allchild[] = $cat;
        }
        $params[] = "cat=" . trim($cat);

        $serve_category_list = M("serve_category")->where(array("parentid="=>$cat, "league_id"=>$where['league_id']))->order("order_id asc")->select();
        if (!$serve_category_list) {
            $serve_category_list = M("serve_category")->where(array("parentid="=>$this->focus_category['parentid'], "league_id"=>$where['league_id']))->order("order_id asc")->select();
        }

        $serve_category = array();
        foreach($serve_category_list as $cat) {
            $serve_category[$cat['serve_category_id']] = $cat;
        }
        $this->serve_category = $serve_category;

        if ($allchild) {
            $where['category'] = array("in", $allchild);
        }
        self::show_list_index_html($where, $params, "产品表");
    }

    public function perfect_list_item($value, $export = false, $branchlock = false) {
        $serve_category = M("serve_category")->where("serve_category_id=".$value['category'])->find();
        $value['category'] = $serve_category['name'];
        return parent::perfect_list_item($value, $export, $branchlock);
    }

	public function add(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        if($this->isPost()) {
            if (!isset($_POST['name']) || $_POST['name'] == '') {
                alert_back('error', '必须设置产品名称');
            }
            $_POST['league_id'] = session('league_id');

            $serve_id = $this->submit_add();
            if (!$serve_id) {
                $this->alert = parseAlert();
                alert_back('error', "新建产品失败");
            }
            alert('success', "新建产品成功", U("serve/view", "id=".$serve_id));

        }else{
            $this->refer_url= refer_url('refer_add_url');
            $this->fields_group = product_field_list_html("add","serve", array(), "basic");;
            $this->alert = parseAlert();
            $this->display();
        }
	}

	public function view(){
        $serve_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $serve_id) {
            alert('error', L('PARAMETER_ERROR'), U('serve/index'));
        }
        $where = array("serve.serve_id"=>$serve_id);
        $serve = D('ServeView')->where($where)->find();
        if (!$serve) {
            alert('error', "没有这个产品",U("serve/index"));
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        switch ($serve['corre']) {
            case "product": {
                $serve['corre'] = "雇员";
                break;
            }
            case "customer": {
                $serve['corre'] = "客户";
                break;
            }
        }

        $this->serve = $serve;
        $this->fields_group = product_field_list_show('serve', $serve);
        $this->alert = parseAlert();
        $this->refer_url= refer_url('refer_view_url');
        $this->display();
	}

    public function trade(){
        $serve_id = $this->_request('id');
        if (0 == $serve_id) {
            alert('error', L('PARAMETER_ERROR'), U('serve/index'));
        }
        $serve = D('ServeView')->where('serve.serve_id = %d',$serve_id)->find();
        if (!$serve) {
            alert('error', "没有这个产品",$_SERVER['HTTP_REFERER']);
        }
        $this->serve = $serve;
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $trade_list = D('TradeView')->where('serve.serve_id = %d',$serve_id)->order("create_time desc")->select();
        foreach($trade_list as $k=>$v) {
            $trade_list[$k] = TradeViewModel::format_trade_state(TradeAction::perfect_list_item($v, false, false));
        }
        $this->trade_list = $trade_list;
        $this->field_array = getIndexFields("trade", "serve_id");
        $this->alert = parseAlert();
        $this->refer_url= session("index_refer_url");
        $this->display();
    }

	public function edit(){
        $serve_id = $this->_request('id');
        if (0 == $serve_id) {
            alert('error', L('PARAMETER_ERROR'), U('serve/index'));
        }
        $serve = D('ServeView')->where('serve.serve_id = %d',$serve_id)->find();
        if (!$serve) {
            alert('error', "没有这个产品",$_SERVER['HTTP_REFERER']);
        }
        $this->serve = $serve;
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if($this->isPost()){
            if($this->submit_edit($serve_id)) {
                alert('success', "编辑产品成功", U('serve/view', 'id='.$serve_id));
            } else {
                alert('error', "编辑产品失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            $this->model_id = $serve['serve_id'];
            $this->alert = parseAlert();;
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->fields_group =  product_field_list_html("edit","serve",$serve);
            $this->display();
        }
	}

    public function listDialog(){
        if ($this->isAjax() === false) {
            return $this->display("Serve:listDialog");
        }

        $data_field = array(
            array(
                "field"=>"serve_id",
                "order"=>"serve_id"
            ),
            array(
                "field"=>"idcode",
                "order"=>"idcode"
            ),
            array(
                "field"=>"serve_name",
                "order"=>"serve_name"
            ),
            array(
                "field"=>"price",
                "order"=>"price"
            ),
        );
        $where = $this->parse_dialog_where();
        $this->ajaxReturn(make_data_list("ServeView", $where, $data_field, array($this, "format_dialog_item")),'JSON');
    }

    public function parse_dialog_where() {
        $where = parent::parse_dialog_where();
        if ($_REQUEST['corre']) {
            $where['corre'] = trim($_REQUEST['corre']);
        }
        $where['league_id'] = session('league_id');
        return $where;
    }

    public function format_dialog_item($val) {
        $val["serve_id"] = array(
            "serve_id"=>$val['serve_id']
        );
        return $val;
    }
}
