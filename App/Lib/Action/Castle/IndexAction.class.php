<?php 
// 
class IndexAction extends Action {
	public function index(){
        $this->default_index = session('role_id')?"/index/dashboard-v1":"/access/signin";
        $this->display();
	}

    public function login() {
        $where['status'] = array('eq', 1);
        $where['isshow'] = array('eq', 1);
        if (session('role_id')){
            $this->ajaxReturn(array("user"=>array("name"=>session('name'))));return;
        }
        if((!isset($_POST['name']) || $_POST['name'] =='')||(!isset($_POST['password']) || $_POST['password'] =='')){
            $this->ajaxReturn(array("error"=>L('INVALIDATE_USER_NAME_OR_PASSWORD')));return;
        }

        $league_id = $_POST['league']?$_POST['league']:"0";
        $m_user = M('user');
        $where = array(
            'name' => trim($_POST['name'])
        );
        $where['league_id'] = $league_id;

        $user = $m_user->where($where)->find();
        if (!$user) {
            $this->ajaxReturn(array("error"=>L('INCORRECT_USER_NAME_OR_PASSWORD')));return;
        }

        $passt = md5(trim($_POST['password']));
        $pass =  md5($passt. $user['salt']);

        if ($user['password'] != $pass) {
            $this->ajaxReturn(array("error"=>L('INCORRECT_USER_NAME_OR_PASSWORD')));return;
        }

        if (-1 == $user['status']) {
            $this->ajaxReturn(array("error"=>L('YOU_ACCOUNT_IS_UNAUDITED')));return;

        } elseif (0 == $user['status']) {
            $this->ajaxReturn(array("error"=>L('YOU_ACCOUNT_IS_AUDITEDING')));return;

        }elseif (2 == $user['status']) {
            $this->ajaxReturn(array("error"=>L('YOU_ACCOUNT_IS_DISABLE')));return;
        }

        $role = D('RoleView')->where('user.user_id = %d', $user['user_id'])->find();
        if ($_POST['autologin'] == 'on') {
            session(array('expire'=>259200));
            cookie('user_id',$user['user_id'],259200);
            cookie('name',$user['name'],259200);
            cookie('league_id',$user['league_id'],259200);
            cookie('league_name',$user['league_name'],259200);
            cookie('salt_code',md5(md5($user['user_id'] . $user['name']).$user['salt']),259200);
        }else{
            session(array('expire'=>3600));
        }

        if($user['category_id'] == 1){
            session('admin', 1);
        }
        session('role_id', $role['role_id']);
        session('position_id', $role['position_id']);
        session('role_name', $role['role_name']);
        session('department_id', $role['department_id']);
        session('name', $user['name']);
        session('user_id', $user['user_id']);
        session('league_id', $role['league_id']);
        session('league_name', $role['league_name']);

        $m_branch = D("BranchCategoryView")->where(array("branch_category.role_id"=>$role['role_id']))->find();
        if ($m_branch) {
            session('authority', $m_branch['authority']);
            session('branch_id', $m_branch['branch_id']);
            session('restriction', $m_branch['restriction'] == "打开");
            session('shopkeeper', $m_branch['shopkeeper_role_id'] == $role['role_id']);
        }
        $this->ajaxReturn(array("user"=>array("name"=>session('name'))));return;
    }

    public function mainnav() {
        $this->display("tpl/blocks/nav");
    }

    public function associate() {
        $model = trim($_REQUEST['model']);
        if ($_REQUEST['excfield']) {
            $excfield = explode(",", $_REQUEST['excfield']);
        }
        $field_array = getModelFields($model, $_REQUEST['where'] ? $_REQUEST['where']:array(),$excfield?$excfield:array());
        foreach($field_array as $k=>$v) {
            $v = format_field($v, $model);;
            $v["mData"] = $v['field'];
            if (in_array($v['field'], array("idcode")))
                $v["group_ls"] = 1;

            $field_array[$k] = $v;
        }

        if ($model == "account") {
            $field_array[] = array("field"=>"create_time", "form_type"=>"datetime","in_index"=>1, "name"=>"时间");
            $field_array[] = array("field"=>"creator_role_id", "form_type"=>"user","in_index"=>1, "name"=>"操作人");
            $field_array[] = array("field"=>"infow", "form_type"=>"infow","in_index"=>1, "name"=>"相关方");
            $field_array[] = array("field"=>"related", "form_type"=>"related","in_index"=>1, "name"=>"订单");
            $field_array[] = array("field"=>"description", "form_type"=>"text","in_index"=>1, "name"=>"备注");
        }elseif ($model == "trade") {

        }
        $this->field_array = $field_array;
        $this->assign($_GET);
        $this->display("tpl/".$model."_associate");
    }


    public function cultivate() {
        $field_array = getModelFields("cultivate", array());
        foreach($field_array as $k=>$v) {
            $v = format_field($v, "cultivate");;
            $v["mData"] = $v['field'];
            if (in_array($v['field'], array("idcode")))
                $v["group_ls"] = 1;

            $field_array[$k] = $v;
        }
        $this->field_array = $field_array;
        $this->display("tpl/".($_GET['tpl']?$_GET['tpl']:"cultivate_index"));
    }


    public function market() {
        $field_array = getModelFields("market", array());
        foreach($field_array as $k=>$v) {
            $v = format_field($v, "market");;
            $v["mData"] = $v['field'];
            if (in_array($v['field'], array("idcode")))
                $v["group_ls"] = 1;

            $field_array[$k] = $v;
        }
        $this->field_array = $field_array;
        $this->display("tpl/".($_GET['tpl']?$_GET['tpl']:"market_index"));
    }



    public function trade() {
        $field_array = getModelFields("trade", array());
        foreach($field_array as $k=>$v) {
            $v = format_field($v, "trade");;
            $v["mData"] = $v['field'];
            $field_array[$k] = $v;
        }
        $field_array[] = array("field"=>"corre_id", "form_type"=>"text","in_index"=>1, "name"=>"相关方");
        $this->field_array = $field_array;
        $this->display("tpl/".($_GET['tpl']?$_GET['tpl']:"trade_index"));
    }

    public function product() {
        $field_array = getModelFields("product", array());
        foreach($field_array as $k=>$v) {
            $v = format_field($v, "product");;
            $v["mData"] = $v['field'];
            if (in_array($v['field'], array("idcode")))
                $v["group_ls"] = 1;

            $field_array[$k] = $v;
        }
        $this->field_array = $field_array;
        $this->display("tpl/".($_GET['tpl']?$_GET['tpl']:"product_index"));

    }

    public function product_view() {
        $field_group = array(
            array("group"=>"basic","name"=> '基本信息'),
            array("group"=>"cultivate","name"=>'培训订单',"sections"=>array(array("label"=>"字段","templateUrl"=>"index/associate/model/cultivate"))),
            array("group"=>"skill","name"=> '鉴定信息'),
            array("group"=>"event","name"=> '调度信息'),
            array("group"=>"market","name"=> '客户服务',"sections"=>array(array("label"=>"字段","templateUrl"=>"index/associate/model/market/excfield/service_product"))),
            array("group"=>"evaluate","name"=> '评价'),
            array("group"=>"account","name"=> '账户信息'),
            array("group"=>"trade","name"=> '缴费信息',"sections"=>array(array("label"=>"字段","templateUrl"=>"index/associate/model/trade"))),
            array("group"=>"berth","name"=> '住宿日志',"sections"=>array(array("label"=>"字段","templateUrl"=>"index/associate/model/berth"))),
            array("group"=>"leads","name"=> '面试信息',"sections"=>array(array("label"=>"字段","templateUrl"=>"index/associate/model/leads"))),
        );
        foreach($field_group as $k=>$v) {
            $assorts = M('FieldsGroup')->where(array("model"=>"product","assort"=>$v['group']))->cache(true)->order('order_id ASC')->select();;
            if ($v['group'] == "basic") {
                array_unshift($assorts, array('field_group_id'=>'0','name'=>'基本信息','operating'=>'1',"model"=>"product"));
            }
            foreach($assorts as $k1=>$v1) {
                $where =array(
                    "model"=>$v1['model'],
                    "field_group_id"=>$v1['field_group_id'],
                    "operating"=>array("neq", 4),
                );
                $field_list = M('Fields')->where($where)->cache(true)->order('order_id')->select();
                if ($field_list) {
                    $assorts[$k1]['fields'] = $field_list;
                }
            }
            $field_group[$k]["assorts"] = $assorts;
        }
        $field_group[2]["assorts"][0]['name'] = "服务类别";
        $field_group[2]["assorts"][0]['fields'] = M('Fields')->where(array("model"=>"skill","operating"=>array("neq", 4)))->cache(true)->order('order_id')->select();

        $field_group[6]["assorts"] = array($field_group[0]['assorts'][7]);
        unset($field_group[0]['assorts'][7]);

        $this->field_group = format_group_fields_list("product", $field_group);
        $this->display("tpl/product_view");
    }


    public function product_logs() {
        $field_array = array();
        $field_array[] = array("field"=>"create_date", "form_type"=>"datetime","in_index"=>1, "name"=>"创建时间");
        $field_array[] = array("field"=>"role_id", "model"=>"log","form_type"=>"user","in_index"=>1, "name"=>"创建者");
        $field_array[] = array("field"=>"subject", "model"=>"log","form_type"=>"text","in_index"=>1, "name"=>"标题");
        $field_array[] = array("field"=>"content", "model"=>"log","form_type"=>"text","in_index"=>1, "name"=>"内容");
        $field_array[] = array("field"=>"category_name", "model"=>"product_category","form_type"=>"text","in_index"=>1, "name"=>"类别");

        $this->field_array = $field_array;
        $this->assign($_GET);
        $this->display("tpl/product_logs");
    }

    public function product_events() {
        $field_array = array();
        $field_array[] = array("field"=>"create_date", "form_type"=>"datetime","in_index"=>1, "name"=>"创建时间");

        $this->field_array = $field_array;
        $this->assign($_GET);
        $this->display("tpl/product_events");
    }

    public function assign_field_groups($model) {
        $where =array(
            "model"=>$model,
            "operating"=>array("neq", 4),
        );
        $field_list = M('Fields')->where($where)->cache(true)->order('order_id')->select();
        return format_fields_list($model, $field_list);;
    }

    public function product_appraisal() {
        $this->field_group = $this->assign_field_groups("product_appraisal");
        $this->assign($_GET);
        $this->display("tpl/product_appraisal");
    }

    public function customer() {
        $field_array = getModelFields("customer", array());
        foreach($field_array as $k=>$v) {
            $v = format_field($v, "customer");;
            $v["mData"] = $v['field'];
            if (in_array($v['field'], array("idcode")))
                $v["group_ls"] = 1;

            $field_array[$k] = $v;
        }
        $this->field_array = $field_array;
        $this->display("tpl/".($_GET['tpl']?$_GET['tpl']:"customer_index"));

    }

    public function account_view() {
        $this->field_group = $this->assign_field_groups("account");
        $this->assign($_GET);
        $this->display("tpl/account_view");
    }

    public function upload() {
        if (!$_REQUEST['m'] || !$_REQUEST['mid'] || !$_REQUEST['f'] || empty( $_FILES )) {
            return $this->ajaxReturn(null);
        }
        $module = trim($_REQUEST['m']);
        $module_id = trim($_REQUEST['mid']);
        $field = trim($_REQUEST['f']);
        $ismain = isset($_REQUEST['im']) ? $_REQUEST['im'] : 0;

        import('@.ORG.UploadFile');
        $upload = new UploadFile();
        $upload->maxSize = 2000000000;
        $upload->savePath = UPLOAD_PATH . date('Ym', time()).'/'.date('d', time()).'/';;

        $m_module_images = M($module . 'Images');

        if($upload->upload()) {
            $info =  $upload->getUploadFileInfo();
            foreach($info as $iv){
                $img_data[$module . '_id'] = $module_id;
                $img_data['name'] = $iv['showname']?$iv['showname']:$iv['name'];
                $img_data['save_name'] = $iv['savename'];
                $img_data['size'] = sprintf("%.2f", $iv['size'] / 1024);
                $img_data['path'] = $iv['savepath'] . $iv['savename'];
                $img_data['create_time'] = time();
                $img_data['listorder'] = intval($m_module_images->max('listorder')) + 1;
                $img_data[$module . '_field'] = $field;
                $img_data['is_main'] = $ismain;
                $m_module_images->add($img_data);
            }
        }
        $where = array(
            $module . "_id" => $module_id,
            $module . "_field" => $field,
            "is_main" => 0
        );
        $this->ajaxReturn($m_module_images->where($where)->order('listorder asc')->select());
    }

}