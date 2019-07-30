<?PHP

class BaseAction extends Action{
    public function validate() {
        $this->module = strtolower(MODULE_NAME);

        if($this->isAjax()){
            if(!$this->_request('clientid','trim') || !$this->_request($this->_request('clientid','trim'),'trim')){
                $this->ajaxReturn("","",3);
            }
            $field = M('Fields')->where('model = "'.ucfirst($this->module).'" and field = "'.$this->_request('clientid','trim').'"')->find();
            $m_module = $field['is_main'] ? D(ucfirst($this->module)) : D(ucfirst($this->module).'Data');
            $where[$this->_request('clientid','trim')] = array('eq',$this->_request($this->_request('clientid','trim'),'trim'));
            if($this->_request('id','intval',0)){
                $where[$m_module->getpk()] = array('neq',$this->_request('id','intval',0));
            }
            $where['league_id'] = session('league_id');

            if($this->_request('clientid','trim')) {
                if ($m_module->where($where)->find()) {
                    $this->ajaxReturn("","",1);
                } else {
                    $this->ajaxReturn("","",0);
                }
            }else{
                $this->ajaxReturn("","",0);
            }
        }
    }

    public function check() {
        $this->module = strtolower(MODULE_NAME);

        import("@.ORG.SplitWord");
        $sp = new SplitWord();
        $m_module = M(ucfirst($this->module));
        $useless_words = array(L('COMPANY'),L('LIMITED'),L('DI'),L('LIMITED_COMPANY'));
        if ($this->isAjax()) {
            $split_result = $sp->SplitRMM($_POST['name']);
            if(!is_utf8($split_result)) {
                $split_result = iconv("GB2312//IGNORE", "UTF-8", $split_result) ;
            }
            $result_array = explode(' ',trim($split_result));
            if(count($result_array) < 2){
                $this->ajaxReturn(0,'',0);
                die;
            }
            foreach($result_array as $k=>$v){
                if(in_array($v,$useless_words)) unset($result_array[$k]);
            }
            $name_list = $m_module->getField('name', true);
            $seach_array = array();
            foreach($name_list as $k=>$v){
                $search = 0;
                foreach($result_array as $k2=>$v2){
                    if(strpos($v, $v2) > -1){
                        $v = str_replace("$v2","<span style='color:red;'>$v2</span>", $v, $count);
                        $search += $count;
                    }
                }
                if($search > 2) $seach_array[$k] = array('value'=>$v,'search'=>$search);
            }
            $seach_sort_result = array_sort($seach_array,'search','desc');
            if(empty($seach_sort_result)){
                $this->ajaxReturn(0,L('ABLE_ADD'),0);
            }else{
                $this->ajaxReturn($seach_sort_result,L('CUSTOMER_IS_CREATED'),1);
            }
        }
    }

    public function field_where($field, $search, $condition) {
        $this->module = strtolower(MODULE_NAME);
        if ($field == "") {
            return array("_string"=>"1 ");
        }
        $field = format_filter_field($field);

        if($field == "subgroup" || $field == $this->module.".subgroup"){
            $m_group = M($this->module."_group")->where(array("name"=>array("like",$search."%")))->find();
            if ($m_group) {
                $query = M($this->module."_subgroup")->field($this->module."_id")->where($this->module."_group_id=".$m_group[$this->module.'_group_id'])->select(false);
                $where = array(
                    "_string"=>"1 AND ".$this->module.".".$this->module."_id"." in ".$query
                );
                return $where;
            }
        }
        if (in_array($field,array(
            "channel_role_model",
            "commiss.channel_role_model",
            "product.channel_role_model",
            "customer.channel_role_model",
            "cultivate.channel_role_model"))) {
            $field = "channel_role_model_keyword";
        }elseif(in_array($field,array(
            "channel_role_id",
            "commiss.channel_role_id",
            "product.channel_role_id",
            "customer.channel_role_id",
            "cultivate.channel_role_id"))){
            $field = "channel_role_id_keyword";
        }elseif(in_array($field,array("cultivate.model_id"))){
            $field = "model_id_keyword|model_id";
        }elseif(in_array($field,array("log.role_id"))){
            $field = "log.role_id_keyword";
            if ($condition == "is")$condition = "contains";else $condition="not_contain";
        }
        return field_where($field, $search, $condition, $this->module);
    }

    public function make_fields() {
        $this->module = strtolower(MODULE_NAME);
        if (trim($_REQUEST['field']) == "all") {
            $field = implode("|", all_filter_field($this->module));
        } else {
            $field = trim($_REQUEST['field']);
        }
        return $field;
    }

    public function make_search_fields_where(&$params = array()) {
        $this->module = strtolower(MODULE_NAME);
        $field = trim($_REQUEST['field']);
        if ($field) {
            $params[] = "field=" . $field;
        }

        $condition = $_REQUEST['condition'];
        if (empty($condition)) {
            $condition = ($field == "all"?'like':"is");
        }
        $params[] = "condition=" . $condition;

        $search = isset($_REQUEST['search']) ? trim($_REQUEST['search']): '';
        $params[] = "search=" . $search;

        if ($field == "census" && $search == "" && $_REQUEST['search_census'] != "") {
            $search_census = $this->_request('search_census');
            $params[] = "search_census=" . $search_census;
            $search = census_retrieve($search_census);
            $condition = "in";
        }

        if ($field == "all") {
            $field_list = array_merge(all_filter_field($this->module),array($this->module.".keyword")) ;
            $field = implode("|", $field_list);
        }else {
            if ($date_search = self::format_time_search($this->module, $field, $params)) {
                $search = $date_search;
                $this->search_date_field = $field;
            }
        }
        return $this->field_where($field, $search, $condition);
    }

    public function format_module_list($list) {
        foreach($list as $k=>$v){
            $list[$k] = $this->perfect_list_item($v, false);
        }
        return $list;
    }

    public function index(){
        $request_url = $_SERVER['REQUEST_URI'];
        if (strpos($request_url, "excel") == false) {
            session("index_refer_url", $request_url);
        }
        $this->module_name = strtolower(MODULE_NAME);

        $params = array();$where = array("_string"=>"1 ");
        if ($_REQUEST['act'] == "group") {
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST, $_REQUEST['act']);
            $params[] = "act=".$_REQUEST['act'];
            $this->show_group($where, $params);
        } else if ($_REQUEST['act'] == "preexport"){
            unset($_REQUEST['act'], $_REQUEST['t']);
            $_REQUEST['m'] = strtolower(MODULE_NAME);
            $_REQUEST['a'] = "index";
            $_REQUEST['g'] = GROUP_NAME;
            $_REQUEST['SESSION_ROLE_ID'] = session("role_id");
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST, $_REQUEST['act']);
            $randfile = "exportcache/".uniqid("excel");
            file_put_contents(dirname($_SERVER['SCRIPT_FILENAME']).'/'.$randfile, serialize($_REQUEST));
            redirect("http://b.aobaomuying.cn:9501?type=export&pf=".$randfile);
        }else {
            if ($_REQUEST["field"]) {
                $params[] = "field=".$_REQUEST['field'];
                $this->search_field = $_REQUEST["field"];
                $this->debar_search_field = array($_REQUEST["field"]."[value]", $_REQUEST["field"]."[condition]",'bylea');
                $this->search_condition = $_REQUEST[$_REQUEST["field"]]['condition'];
                $this->search_value = $_REQUEST[$_REQUEST["field"]]['value'];
            }
            $this->is_groupsearch = in_array($_REQUEST['act'], array("groupsearch", "advsearch"));
            if ($this->is_groupsearch) {
                $params[] = "act=".$_REQUEST['act'];
            }
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST, $_REQUEST['act']);
            if ($_REQUEST['act'] == "groupsearch") {
                $this->groupsearch($where, $params);
            }else {
                $this->dosearch($where, $params);
            }
        }
    }

    public function group_filter_fields() {
        return null;
    }

    public function show_group($where, $params) {
        $this->module = strtolower(MODULE_NAME);
        $this->module_group_id = $_GET['module_group_id'];

        $module_group = strtolower($this->module)."_group";
        foreach($this->group_filter_fields() as $k=>$v) {
            if (is_array($v)) {
                if (in_array($v['condition'],array("is_empty", "is_not_empty")) || $v['value'] != "") {
                    $params[] = $k."[value]=".$v['value'];
                    if ($v['condition'] != "") {
                        $params[] = $k."[condition]=".$v['condition'];
                    }
                }
            }
            if(in_array($v['condition'],array("is_empty", "is_not_empty")) || (isset($v['value']) && $v['value'] != "")){
                $where = array_merge(field_where($k, $v['value'],$v['condition'],$module_group), $where);
            }
        }

        $this->parameter = implode('&', $params);
        $where['league_id'] = session('league_id');
        $count = D(ucfirst($this->module).'Group')->where($where)->count();
        if ($count) {
            $page = self::assign_list_page($this->parameter, $count);
            $this->list = D(ucfirst($this->module).'Group')->where($where)->Page($page->nowPage, $page->listRows)->select();// 赋值数据集
        }
        $this->alert=parseAlert();
        $this->display("group"); // 输出模板
    }

    public function show_list($where = array(), $params = array()) {
        $this->module = strtolower(MODULE_NAME);
        $order = self::make_list_order($params);
        $where['league_id'] = session('league_id');
        $this->parameter = implode('&', $params);
        $module_view = D(ucfirst($this->module).'View');
        $count = $module_view->where($where)->count();// 查询满足要求的总记录数

        if ($count) {
            $page = self::assign_list_page($this->parameter, $count);
            $list = $module_view->where($where)->order($order)->Page($page->nowPage, $page->listRows)->select();
            $module_list = $this->format_module_list($list);
            $this->assign('list',$module_list);// 赋值数据集
        }
        self::display_index_html();
    }

    public function assign_list_page($parameter, $count, $pc=15) {
        import('@.ORG.Page');// 导入分页类
        $Page = new Page($count,$pc);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->parameter = $parameter;
        $this->assign('page',$Page->show());// 赋值分页输出
        return $Page;
    }

    public function get_module_view() {
        return D(ucfirst($this->module).'View');
    }

    public function make_list_field() {
        return true;
    }

    public function assign_module_list($where, $params, $ename) {
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : MODULE_NAME;

        if ($_GET['draw']) {
            if ($_GET['length']) {
                $_GET["pl"] = $_GET['length'];
            }
            if ($_GET['start']) {
                $_GET["p"] = ($_GET['start']/$_GET['length'])+1;
            }
        }

        if ($_GET["pl"]) {
            $page_limit = $_GET["pl"];
            $params[] = "pl=".$page_limit;
        } else {
            $page_limit = 15;
        }

        $this->list_where = $where;
        $m_module = $this->get_module_view();
        $count = $m_module->where($where)->count();

        $m_module->field($this->make_list_field())->order($this->make_list_order($params))->where($where);
        if(trim($_GET['export']) == 'excel'){
            if ($_GET['p'] > 0) {
                $this->excelExport($this->replenish_list($m_module->page(max($_GET['p'], 1).',2000')->select(), true), $ename);
            } else {
                $this->show_export_dialog($count);
            }
            exit(0);
        }else {
            $this->parameter = implode('&', $params);
            $page = self::assign_list_page($this->parameter, $count, $page_limit);

            $list = $m_module->page($page->nowPage, $page->listRows)->select();
            if ($list) {
                $this->list = $this->replenish_list($list, false);
            }
        }
    }

    public function is_fix_branch_field($value, $branchlock) {
        $this->module = strtolower(MODULE_NAME);
        return !in_array($value[$this->module.'_id'],  self::get_astrict_list());
    }

    public function replenish_list($list, $export = false) {
        $branchlock = vali_permission("branchlock", $this->module);
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : MODULE_NAME;
        if (session('?admin') || (!$branchlock && !session('authority'))) {           //管理员或没有在门店
            foreach ($list as $k => $v) {
                $list[$k] = $this->perfect_list_item($v, $export, $branchlock);
            }
        } else {
            $owernbranch = get_branch(session("role_id"));$index_fields = getIndexFields($this->module);
            foreach ($list as $k => $v) {
                $v = $this->perfect_list_item($v, $export, $branchlock);
                if ($this->is_fix_branch_field($v, $branchlock)) {
                    $v = self::fix_branch_fields($index_fields, $v, in_array($v['owner_role_id'], $owernbranch));
                }
                $list[$k] = $v;
            }
        }

        return $list;
    }

    function perfect_list_item($value, $export = false) {
        return self::replenish_list_item($value);
    }

    static function replenish_list_item($value) {
        $role = D('RoleView')->cache(true)->where('role.role_id = %d', $value['role_id'])->find();
        $value['role_id'] = $role['user_name'];

        if ($value['creator_role_id']) {
            $value['creator'] = D('RoleView')->cache(true)->where('role.role_id = %d', $value['creator_role_id'])->find();
        }

        if ($value['owner_role_id']) {
            $value['owner'] = D('RoleView')->cache(true)->where('role.role_id = %d', $value['owner_role_id'])->find();
        }

        if ($value['branch_id']) {
            $value['branch'] = D('Manage/BranchView')->cache("branch_view_".$value['branch_id'])->where('branch.branch_id = %d ', $value['branch_id'])->find();
        }

        return $value;
    }

    public function display_index_html() {
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $this->field_array = $this->format_index_fields(getIndexFields($this->module));
        $this->field_list = getMainFields($this->module);
        $this->alert = parseAlert();

        session($this->module."_index_refer_url", $_SERVER['REQUEST_URI']);
        $this->display("index");
    }

    public function format_index_fields($field_array) {
        foreach($field_array as $k=>$v) {
            $field_array[$k]['link_title'] = $v['name'];
        }
        return $field_array;
    }

    public function show_list_index_html($where, $params, $mname) {
        $this->assign_module_list($where, $params, $mname);
        $this->display_index_html();

    }

    function submit_edit($module_id, $module = "", $data = "") {
        $this->module = $module ? $module : strtolower(MODULE_NAME);
        $data = perfect_model_field_post($this->module);

        $m_module = M($this->module);
        if(!$m_module->create($data)) {
            return false;
        }
        $m_module->update_time = time();

        $m_module_data = M($this->module.'_data');
        if($m_module_data->create($data)===false) {
            return false;
        }

        $where = array($this->module."_id"=>$module_id);
        if($m_module->where($where)->save() === false) {
            return false;
        }

        $cnt = $m_module_data->where($where)->count();
        if (!$cnt) {
            $m_module_data->__set($this->module."_id", $module_id);
            $m_module_data->add();
        } else {
            $m_module_data->where($where)->save();
        }
        return $this->upload_module_file($module_id, $this->module, $data);
    }

    function submit_add($module = "", $data = "") {
        $this->module = $module ? $module : strtolower(MODULE_NAME);
        $data = perfect_model_field($this->module, $data?$data:$_POST);

        $m_module = M($this->module);
        $m_module_data = M($this->module.'_data');
        if($m_module->create($data) === false || $m_module_data->create($data)===false) {
            return false;
        }
        $m_module->create_time = $m_module->update_time = time();
        $m_module->role_id = $m_module->creator_role_id = $_POST['role_id'] ? $_POST['role_id'] : session('role_id');

        $module_id = $m_module->add();
        if (!$module_id) {
            return false;
        }
        $m_module_data->__set($this->module."_id", $module_id);
        $m_module_data->add();
        return $this->upload_module_file($module_id, $this->module) ? $module_id : false;
    }

    public function delete_prompt($model, $model_id) {
        $where = array(
            "model"=>$model,
            "model_id"=>$model_id
        );
        $prompt_ids = M("prompt")->where($where)->getField("prompt_id", true);
        M("prompt")->where('prompt_id in (%s)', $prompt_ids)->delete();
        M("prompt_data")->where('prompt_id in (%s)', $prompt_ids)->delete();
    }

    public function submit_delete($module_ids, $flowmodule = array(), $module = "") {
        if (!$module_ids) {
            return false;
        }
        $this->module = $module ? $module : strtolower(MODULE_NAME);
        if (!is_array($module_ids)) {
            $module_ids = array($module_ids);
        }
        $module_id_list = implode(',', $module_ids);

        $m_module = M($this->module);
        $m_module_data = M($this->module.'_data');

        $m_module->where($this->module.'_id in (%s)', $module_id_list)->delete();
        $m_module_data->where($this->module.'_id in (%s)', $module_id_list)->delete();
        foreach ($module_ids as $value) {
            $this->delete_files($value, $module);

            foreach ($flowmodule as $key2=>$value2) {
                if(!is_int($key2)){
                    $module_ids = M($value2)->where($this->module.'_id = %d', $value)->getField($key2 . '_id',true);
                    M($key2)->where($key2 . '_id in (%s)', implode(',', $module_ids))->delete();
                }
                M($value2)->where($this->module.'_id = %d', $value)->delete();
            }
        }
        return true;
    }

    public function delete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $module_ids = $this->isPost() ? $_POST[$this->module.'_id'] : $_GET['id'];
        if ($this->submit_delete($module_ids)) {
            if($_REQUEST['refer_url']) {
                alert('success', "删除成功", $_REQUEST['refer_url']);
            }else{
                alert('success', "删除成功" ,U($this->module.'/index'));
            }
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }

    public function delete_accounts($account_ids) {
        $where = array('account_id'=>array("in", $account_ids));
        M('account')->where($where)->delete();
        M('account_data')->where($where)->delete();
        $log_ids = M('account_log')->where($where)->getField('log_id', true);
        if ($log_ids) {
            M('log')->where(array("log_id"=>array("in", $log_ids)))->delete();
        }
    }

    public function delete_related_account($relateds, $related_ids) {
        if (!$related_ids) {
            return;
        }
        $where = array($relateds."_id"=>array("in", $related_ids));
        $account_ids = M($relateds."_account")->where($where)->getField('account_id', true);

        M($relateds."_account")->where($where)->delete();
        $this->delete_accounts($account_ids);
    }

    public function related_delete($model_ids, $relateds) {
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $where = array(
            $this->module.'_id'=>array("in", $model_ids)
        );
        $related_ids = M($this->module."_".$relateds)->where($where)->getField($relateds."_id");
        $related_delete_where = array($relateds."_id"=>array("in", $related_ids));
        $tables = array(
            $relateds,
            $relateds."_data",
            $relateds."_images",
            $relateds."_log",
            $relateds."_subgroup",
            $relateds."_video",
        );
        foreach($tables as $t) {
            M($t)->where($related_delete_where)->delete();
        }
        $this->delete_related_account($relateds, $related_ids);
    }

    public function submit_recover($module_ids) {
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        if (!is_array($module_ids)) {
            $module_ids = array($module_ids);
        }
        $data = array(
            "is_delete"=>0,
            "delete_role_id"=>session('role_id'),
            "delete_time"=>time()
        );
        M($this->module)->where(array($this->module."_id"=>array("in", $module_ids)))->setField($data);
    }

    public function recover() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $module_ids = $this->isPost() ? $_POST[$this->module.'_id'] : $_GET['id'];
        $this->submit_recover($module_ids);
        alert('success', "恢复成功");
    }

    function save_module_pic_file($key, $module_id, $module, $name, $savepath, $savename, $size = 0) {
        $this->module = $module ? $module : strtolower(MODULE_NAME);
        $m_module_images = M($this->module . 'Images');
        $img_data[$this->module . '_id'] = $module_id;
        $img_data['name'] = $name;
        $img_data['save_name'] = $savename;
        $img_data['size'] = sprintf("%.2f", $size / 1024);
        $img_data['path'] = $savepath . $savename;
        $img_data['create_time'] = time();
        $img_data['listorder'] = intval($m_module_images->max('listorder')) + 1;
        $img_data[$this->module . '_field'] = ($key == 'work_pic' || $key=='card_pic') ? $key : substr_replace($key, "", 0, 4);
        if ($key == 'work_pic') {
            //主图
            $img_data['is_main'] = 1;
            $where = array(
                'is_main' => 1,
                $this->module . '_id' => $module_id);

            $main_img = $m_module_images->where($where)->find();
            if ($main_img) {
                $m_module_images->where($where)->save($img_data);
            } else {
                $m_module_images->add($img_data);
            }
        } else if ($key == 'card_pic') {
            $img_data['is_main'] = 2;
            $where = array(
                'is_main' => 2,
                $this->module . '_id' => $module_id);
            $main_img = $m_module_images->where($where)->find();

            if ($main_img) {
                $m_module_images->where($where)->save($img_data);
            } else {
                $m_module_images->add($img_data);
            }
        }else{
            //副图
            $img_data['is_main'] = 0;
            $m_module_images->add($img_data);
        }
    }

    function upload_module_file($module_id, $module = "", $data = array()) {
        $this->module = $module ? $module : strtolower(MODULE_NAME);
        $dirname = UPLOAD_PATH . date('Ym', time()).'/'.date('d', time()).'/';
        if (!is_dir($dirname) && !mkdir($dirname, 0777, true)) {
            return false;
        }

        import('@.ORG.UploadFile');

        //上传雇员主图和副图至服务器
        //如果有文件上传 上传附件
        //导入上传类
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 2000000000;
        //设置附件上传目录
        $upload->savePath = $dirname;

        if($upload->upload()) {// 上传错误提示错误信息
            $info =  $upload->getUploadFileInfo();
            //写入数据库
            foreach($info as $iv){
                $key = $iv['key'];
                $field_type = substr($key, 0, 4);

                if ($field_type == "pic_" || $key == 'work_pic' || $key=='card_pic') {
                    $this->save_module_pic_file($key, $module_id,$module, $iv['showname']?$iv['showname']:$iv['name'], $iv['savepath'], $iv['savename'], $iv['size']);
                } else if ($field_type == "vid_") {
                    $m_module_video = M($this->module.'Video');
                    $data['name'] = $iv['name'];
                    $data[$this->module.'_id'] = $module_id;
                    $data['file_path'] = $iv['savepath'].$iv['savename'];
                    $data['role_id'] = session('role_id');
                    $data['size'] = $iv['size'];
                    $data['create_date'] = time();
                    $data[$this->module.'_field'] = substr_replace($key, "", 0, 4);
                    $data['listorder'] = intval($m_module_video->max('listorder'))+1;
                    $m_module_video->add($data);
                } else if ($field_type == "fil_") {
                    $m_file = M('file');
                    $data['name'] = $iv['name'];
                    $data['module'] = $this->module;
                    $data['module_id'] = $module_id;
                    $data['file_path'] = $iv['savepath'].$iv['savename'];
                    $data['role_id'] = session('role_id');
                    $data['size'] = $iv['size'];
                    $data['create_date'] = time();
                    $data['field'] = substr_replace($key, "", 0, 4);
                    $m_file->add($data);
                }
            }

        }


        foreach($_POST as $key=>$value){
            $pos = strpos($key, "pic_");
            if ($pos === false || $pos != 0) {
                continue;
            }
            foreach($value as $k2=>$v2) {
                $picinfo = update_base64_pic($v2);
                if ($picinfo) {
                    $this->save_module_pic_file($key, $module_id,$module, $picinfo['name'], $picinfo['savepath'], $picinfo['savename']);
                }
            }
        }

        return true;
    }

    function delete_files($module_id, $module = null) {
        $this->module = $module ? $module : strtolower(MODULE_NAME);
        $m_module_images = M($this->module.'Images');
        $images_files = $m_module_images->where($this->module.'_id = %d', $module_id)->select();
        foreach($images_files as $files){
            @unlink($files['path']);
        }
        $m_module_images->where($this->module.'_id = %d', $module_id)->delete();

        $m_module_video = M($this->module.'Video');
        $video_files = $m_module_video->where($this->module.'_id = %d', $module_id)->select();
        foreach($video_files as $files){
            @unlink($files['file_path']);
        }
        $m_module_video->where($this->module.'_id = %d', $module_id)->delete();


        $m_file = M('file');
        $files = $m_file->where('module_id = %d', $module_id)->select();
        foreach($files as $files){
            @unlink($files['file_path']);
        }
        $m_file->where('module_id = %d', $module_id)->delete();
    }

    //删除图片
    public function delImg(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $images_id = $_GET['images_id'];
        if($images_id){
            $m_module_images = M($this->module.'Images');
            $images_path = $m_module_images->where('images_id = %d', $images_id)->getField('path');
            $result = $m_module_images->where('images_id = %d', $images_id)->delete();
            if($result){
                @unlink($images_path);
                $this->ajaxReturn('','',1);
            }
        }else{
            $this->ajaxReturn('',L('PARAMETER_ERROR'),0);
        }
    }


    //图片排序
    public function sortImg(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $images_files = $_POST['images_arr'];
        $imagesArr = explode(',', $images_files);
        if($imagesArr){
            $m_module_images = M($this->module.'Images');
            //拖动后的listorder
            $original_listorder = $m_module_images->where('images_id in (%s)',$images_files)->getField('listorder',true);
            sort($original_listorder);//按顺序排列

            //交换顺序
            foreach($imagesArr as $k=>$v){
                $m_module_images->where('images_id = %d',$v)->setField('listorder',$original_listorder[$k]);
            }
            $this->ajaxReturn('success', '排序成功！', 1);
        }
    }

    public function deletevideo() {
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $video_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $video_id){
            $this->ajaxReturn('',"无效的参数",0);
        }else{
            role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

            $m_module_video = M($this->module.'Video');
            $file = $m_module_video->where('video_id = %d', $video_id)->find();
            $video_path = $file['file_path'];
            if($m_module_video->where('video_id = %d', $video_id)->delete()){
                @unlink($video_path);
                $this->ajaxReturn('','',1);
            }
        }
    }

    public function viewvideo() {
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $this->file_path = $_REQUEST['path'];
        $this->video_type = "video/" . strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        $this->name = $_REQUEST['name'];
        $this->display("Public:viewvideo");
    }

    public function addvideo() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        if($_POST['submit']){
            $field_name = $this->_request("field", "trim");
            if (array_sum($_FILES[$field_name]['size'])) {
                //如果有文件上传 上传附件
                import('@.ORG.UploadFile');
                //导入上传类
                $upload = new UploadFile();
                //设置附件上传目录
                $dirname = UPLOAD_PATH . date('Ym', time()).'/'.date('d', time()).'/';

                if (!is_dir($dirname) && !mkdir($dirname, 0777, true)) {
                    $this->error(L('ATTACHMENTS TO UPLOAD DIRECTORY CANNOT WRITE'));
                }
                $upload->savePath = $dirname;

                if(!$upload->upload()) {// 上传错误提示错误信息
                    alert('error', $upload->getErrorMsg(), $_SERVER['HTTP_REFERER']);
                }else{// 上传成功 获取上传文件信息
                    $info =  $upload->getUploadFileInfo();
                }
            }

            $m_module_video = M($this->module.'Video');
            $module_id = $_POST['id'];
            foreach($info as $key=>$value){
                $data['name'] = $value['name'];
                $data[$this->module.'_id'] = $module_id;
                $data['file_path'] = $value['savepath'].$value['savename'];
                $data['role_id'] = $_POST['role_id'];
                $data['size'] = $value['size'];
                $data['create_date'] = time();
                $data[$this->module.'_field'] = $value['key'];
                $m_module_video->add($data);
            }
            alert('success','添加视频成功', $_SERVER['HTTP_REFERER']);

        }elseif($_GET['id']){
            $this->serve_id = $_GET['id'];
            $this->assort = $_GET['assort'];
            $this->field = $_GET['field'];
            $this->display("Public:addvideo");
        } else {
            alert('error', L("WATING_FOR_SERVER_CALL_BACK"), $_SERVER['HTTP_REFERER']);
        }
    }

    public function addfile() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        if($_POST['submit']){
            $field = $this->_request("field", "trim");
            if (array_sum($_FILES[$field]['size'])) {
                //如果有文件上传 上传附件
                import('@.ORG.UploadFile');
                //导入上传类
                $upload = new UploadFile();
                //设置附件上传目录
                $dirname = UPLOAD_PATH . date('Ym', time()).'/'.date('d', time()).'/';

                if (!is_dir($dirname) && !mkdir($dirname, 0777, true)) {
                    $this->error(L('ATTACHMENTS TO UPLOAD DIRECTORY CANNOT WRITE'));
                }
                $upload->savePath = $dirname;

                if(!$upload->upload()) {// 上传错误提示错误信息
                    alert('error', $upload->getErrorMsg(), $_SERVER['HTTP_REFERER']);
                }else{// 上传成功 获取上传文件信息
                    $info =  $upload->getUploadFileInfo();
                }
            }

            $m_file = M('file');
            $module_id = $_POST['id'];
            foreach($info as $key=>$value){
                $data['name'] = $value['name'];
                $data['module_id'] = $module_id;
                $data['file_path'] = $value['savepath'].$value['savename'];
                $data['role_id'] = $_POST['role_id'];
                $data['size'] = $value['size'];
                $data['create_date'] = time();
                $data['field'] = $value['key'];
                $data['module'] = "serve";
                $m_file->add($data);
            }
            alert('success',"添加文件成功", $_SERVER['HTTP_REFERER']);

        }elseif($_GET['id']){
            $this->id = $_GET['id'];
            $this->assort = $_GET['assort'];
            $this->field = $_GET['field'];
            $this->display("Public:addfile");
        } else {
            alert('error', L("WATING_FOR_SERVER_CALL_BACK"), $_SERVER['HTTP_REFERER']);
        }
    }

    public function deletefile() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $_REQUEST['module'] ? $_REQUEST['module'] : strtolower(MODULE_NAME);
        $field_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $field_id){
            $this->ajaxReturn('',L('PARAMETER_ERROR'),0);
        }else{
            $m_file = M('file');
            $file = $m_file->where('file_id = %d', $field_id)->find();
            $file_path = $file['file_path'];
            if($m_file->where('file_id = %d', $field_id)->delete()){
                @unlink($file_path);
                $this->ajaxReturn('','',1);
            }
        }
    }

    public function format_time_search($m, $field, &$params) {
        $field_date = M('Fields')->cache(true)->where(array( "model"=>$m, "field"=>$field ))->find();
        if (!$field_date || $field_date['form_type'] != "datetime"){
            return false;
        }

        $bt = trim($_REQUEST['search_bt']);
        $params[] = "search_bt=" . $bt;

        $et = trim($_REQUEST['search_et']);
        $params[] = "search_et=" . $et;

        $search[] = $bt ? strtotime($bt):-PHP_INT_MAX;
        if ($field_date['is_showtime']) {
            $search[] = $et ? strtotime($et):PHP_INT_MAX;
        } else {
            $search[] = $et ? strtotime(date("Y-m-d 23:59:59",strtotime($et))):PHP_INT_MAX;
        }
        return $search;
    }


    public function changeContent(){
        $this->module = strtolower(MODULE_NAME);
        $where = array("is_delete"=>0);
        if ($_REQUEST["field"]) {
            $where = array_merge(self::make_search_fields_where(), $where);
        }
        $where['league_id'] = session('league_id');

        $p = !$_REQUEST['p'] || $_REQUEST['p']<=0 ? 1 : intval($_REQUEST['p']);
        $d_module = D(ucfirst($this->module).'View');
        $count = $d_module->where($where)->count();// 查询满足要求的总记录数
        if ($count) {
            $list = $d_module->where($where)->Page($p.',7')->select();
            $data['list'] = $this->format_dialog_list($list);;
        }

        $data['p'] = $p;
        $data['count'] = $count;
        $data['total'] = $count%7 > 0 ? ceil($count/7) : $count/7;
        $this->ajaxReturn($data,"",1);
    }


    public function listDialog(){
        $this->module = strtolower(MODULE_NAME);
        $this->showListDialog(ucfirst($this->module).'View', array(), array($this, "format_dialog_item"));
    }

    public function showListDialog($model_view, $data_field, $fmt) {
        $this->t = $_REQUEST['t'] ? $_REQUEST['t'] : "";
        if ($this->isAjax() === false) {
            return $this->display(ucfirst($this->module).":listDialog");
        }
        $where = $this->parse_list_dialog_where($this->t);
        $this->ajaxReturn(make_data_list($model_view, $where, $data_field, array($this, $fmt)),'JSON');
    }

    public function format_dialog_item($val) {
        return $val;
    }

    public function parse_list_dialog_where($t) {
        $where = array();
        return $where;
    }

    public function getInfo() {
        $this->module = strtolower(MODULE_NAME);
        $id = $this->_request('id');
        $info = D(ucfirst($this->module).'View')->where($this->module.'.'.$this->module.'_id = %d',$id)->find();
        $this->ajaxReturn($info,"",$info ? 1:0);
    }

    public function category($module = "") {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $module?$module:strtolower(MODULE_NAME);
        $module_category = array();
        foreach(M($this->module."_category")->order("parentid asc, order_id asc")->where(array("league_id"=>session('league_id')))->select() as $cat) {
            $cat['str_manage'] = '<a data-toggle="modal" data-target="#dialog-edit" href="'.U($this->module."/categoryedit", "id=".$cat[$this->module.'_category_id']).'">编辑</a> | <a  class="del_cat_confirm" href="'.U($this->module."/delcategory", "id=".$cat[$this->module.'_category_id']).'">删除</a>';
            $module_category[$cat[$this->module.'_category_id']] = $cat;
        }
        $this->module_category = $module_category;

        import ( '@.ORG.Tree' );
        $tree = new Tree ($module_category);
        $str  = "<tr>
				    <td align='center' style='width:50px'>
				        <input name='mo[]' style='width:30px' type='text' value='\$order_id'/>
				        <input name='mi[]' style='width:30px' type='hidden' value='\$".$this->module."_category_id'/>
				    </td>
                    <td >\$spacer\$name &nbsp;</td>
                    <td style='text-align:right'>\$str_manage</td>
                </tr>";
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $this->categorys = $tree->get_tree(0, $str);

        $tree = new Tree ($module_category);
        $str  = "<option value='\$".$this->module."_category_id'>\$spacer\$name &nbsp;</option>";
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $this->root_category = $tree->get_tree(0, $str);

        $this->alert = parseAlert();
        $this->display();
    }

    public function category_order($module = "") {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $module?$module:strtolower(MODULE_NAME);
        foreach($_POST['mi'] as $k=>$v) {
            $order_id = $_POST['mo'][$k];
            $where = array($this->module."_category_id"=>$v);
            $where['league_id'] = session('league_id');

            M($this->module."_category")->where($where)->setField("order_id", $order_id);
        }
        alert('success', "排序完成", $_SERVER['HTTP_REFERER']);
    }

    public function addcategory($module = "") {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $module?$module:strtolower(MODULE_NAME);

        $name = $this->_request('name');
        if (!$name) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $_POST['league_id'] = session('league_id');

        $module_category = M($this->module."_category");
        if($module_category->create() === false ) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }

        $module_id = $module_category->add();
        if (!$module_id) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        delete_cache_temp();
        alert('success', "编辑产品失败", $_SERVER['HTTP_REFERER']);
    }

    public function categoryedit($module = "") {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $module?$module:strtolower(MODULE_NAME);
        if(isset($_GET['id'])){
            $this->module_category =M($this->module.'_category')->where($this->module.'_category_id = ' . $_GET['id'])->find();

            $allchild = cateallchild($this->module, $this->module_category[$this->module.'_category_id']);
            $allchild[] = $this->module_category[$this->module.'_category_id'];
            $category = array();
            foreach(M($this->module."_category")->order("parentid asc, order_id asc")->select() as $cat) {
                if (in_array($cat[$this->module.'_category_id'], $allchild)) {
                    $cat['disabled'] = 'disabled';
                } else if ($cat[$this->module.'_category_id'] === $this->module_category['parentid']) {
                    $cat['selected'] = 'selected';
                } else {
                    $cat['disabled'] = '';
                }
                $category[$cat[$this->module.'_category_id']] = $cat;
            }

            import ( '@.ORG.Tree' );
            $tree = new Tree ($category);
            $str  = "<option value='\$".$this->module."_category_id' \$disabled \$selected>\$spacer\$name &nbsp;</option>";
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            $this->root_category = $tree->get_tree(0, $str);

            $this->display("Public:categoryedit");
        }elseif(isset($_POST[$this->module.'_category_id'])){
            $module_category = M($this->module.'_category');
            $module_category->create();
            if($module_category->save() !== false){
                delete_cache_temp();
                alert('success',"修改成功",$_SERVER['HTTP_REFERER']);
            }else{
                alert('error',"修改失败",$_SERVER['HTTP_REFERER']);
            }
        }else{
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
    }

    public function delcategory($module = "") {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = $module?$module:strtolower(MODULE_NAME);
        if (!$_REQUEST['id']) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $id = $this->_request('id');

        $arr = array($id);
        $module_category = M($this->module.'_category');
        $child_cat = $module_category->where(array("parentid"=>$id))->select();
        foreach($child_cat as $v) {
            $arr[] = $v[$this->module.'_category_id'];
        }
        M($this->module)->where(array('category'=>array("in", $arr)))->setField("category", 0);
        $module_category->where(array($this->module.'_category_id'=>array("in", $arr)))->delete();
        delete_cache_temp();
        alert('success', "删除分类成功", $_SERVER['HTTP_REFERER']);
    }

    public function getcategory($module = "") {
        $this->module = $module?$module:strtolower(MODULE_NAME);
        $module_category = M($this->module.'_category')->where(array("parentid="=>$this->_request("id"), "league_id"=>session('league_id')))->select();
        $this->ajaxReturn($module_category, "", 1);
    }

    public function getcategoryselect($module = "") {
        $this->module = $module?$module:strtolower(MODULE_NAME);
        $selecthtml = make_serve_category_select($this->module, $this->_request("id"));
        $this->ajaxReturn($selecthtml, "", 1);
    }

    public function groupadd() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = strtolower(MODULE_NAME);
        if($this->isPost()){
            if(($module_group_id = $this->submit_group()) !== false){
                $urlparam = array(
                    "module_group_id"=>$module_group_id,
                    "group_type"=>$this->_post('group_type'),
                );
                alert('success',"添加分组成功", U(ucfirst($this->module).($this->_post('group_type') == 0 ? "/groupedit": "/groupstance"), $urlparam));
            }else{
                alert('error',"修改分组失败",$_SERVER['HTTP_REFERER']);
            }
        }else{
            $this->alert = parseAlert();
            $this->field_list = self::format_search_fields_list($this->module);
            $this->display(($_REQUEST['group_type'] == 0 ? "grouppreadd" : "groupfixedadd"));
        }
    }

    public function submit_group($module_group_id = null) {
        $this->module = strtolower(MODULE_NAME);
        unset($_REQUEST['group_name'], $_REQUEST['undefined'], $_REQUEST['refer_url'], $_REQUEST['group_type']);

        if ($this->_post('group_type') == 0) {
            $data['content'] = serialize($_REQUEST);
        }
        $data['name']  = $this->_post('group_name'); //字段名称
        $data['creator_role_id'] = session('role_id');
        $data['create_time'] = time();
        $data['group_type'] = $this->_post('group_type');
        $data['league_id'] = session('league_id');

        return $this->update_group($module_group_id, $data);
    }

    public function update_group($module_group_id = null, $data = null) {
        $this->module = strtolower(MODULE_NAME);
        $module_group = D(ucfirst($this->module).'Group');
        if ($module_group_id) {
            $module_group_id = $module_group->where(array($this->module.'_group_id'=>$module_group_id))->save($data);
        } else {
            $module_group_id = $module_group->add($data);
        }
        return $module_group_id;
    }

    public function groupedit() {
        $this->module = strtolower(MODULE_NAME);
        if (!$_REQUEST['module_group_id']) {
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $module_group_id = $this->_request("module_group_id");
        if($this->isPost()) {
            if($this->submit_group($module_group_id) !== false){
                $urlparam = array(
                    "module_group_id"=>$module_group_id,
                    "group_type"=>$this->_post('group_type'),
                );
                alert('success',"修改分组成功", U(ucfirst($this->module).($this->_post('group_type') == 0 ? "/groupedit": "/groupstance"), $urlparam));
            }else{
                alert('error',"修改分组失败",$_SERVER['HTTP_REFERER']);
            }

        } else {
            $module_group = D(ucfirst($this->module).'Group')->where(array($this->module."_group_id"=>$module_group_id))->find();
            if (!$module_group) {
                alert('error', '分组不存在',$_SERVER['HTTP_REFERER']);
            }
            $this->module_group = $module_group;
            $this->field_list = self::format_search_fields_list($this->module);
            $_GET = unserialize($module_group['content']);
            $this->alert = parseAlert();
            $this->display("groupedit");
        }
    }

    public function groupdelete(){
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = strtolower(MODULE_NAME);
        if($this->isPost()){
            $module_group_id = is_array($_POST['module_group_id']) ? implode(',', $_POST['module_group_id']) : '';
            if ('' == $module_group_id) {
                alert('error', L('NOT CHOOSE ANY'), $_SERVER['HTTP_REFERER']);
            }
            M(ucfirst($this->module).'Group')->where(array($this->module.'_group_id'=>array('in',$module_group_id)))->delete();
            M(ucfirst($this->module).'Subgroup')->where(array($this->module.'_group_id'=>array('in',$module_group_id)))->delete();
            alert('success',"删除分组成功",$_SERVER['HTTP_REFERER']);
        }else{
            $module_group_id = $this->_get('module_group_id','intval',0);
            if($module_group_id == 0) {
                alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            }
            M(ucfirst($this->module).'Group')->where(array($this->module.'_group_id'=>$module_group_id))->delete();
            M(ucfirst($this->module).'Subgroup')->where(array($this->module.'_group_id'=>$module_group_id))->delete();
            alert('success',L('DELETE PRODUCT GROUP SUCCESS'),$_SERVER['HTTP_REFERER']);
        }
    }

    public function groupstance() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = strtolower(MODULE_NAME);
        $module_group_id = $_REQUEST['module_group_id'];
        if (!$module_group_id) {
            alert('error', L('NOT CHOOSE ANY'), $_SERVER['HTTP_REFERER']);
        }

        $module_group = D(ucfirst($this->module).'Group')->where(array($this->module."_group_id"=>$module_group_id))->find();
        if (!$module_group) {
            alert('error', '没有这个分组',$_SERVER['HTTP_REFERER']);
        }
        $count = D(ucfirst($this->module).'GroupView')->where(array($this->module."_subgroup.".$this->module."_group_id"=>$module_group_id))->count();
        if($count) {
            $params[] = "module_group_id=" . $module_group_id;

            if ($_GET["pl"]) {
                $page_limit = $_GET["pl"];
                $params[] = "pl=".$page_limit;
            } else {
                $page_limit = 15;
            }
            $this->parameter = implode('&', $params);
            $page = self::assign_list_page($this->parameter, $count, $page_limit);

            $where = array(
                $this->module."_subgroup.".$this->module."_group_id"=>$module_group_id
            );
            $list = D(ucfirst($this->module).'GroupView')->where($where)->page($page->nowPage, $page->listRows)->select();
            $list = $this->perfect_group_list($list);
            $this->assign('list',$list);// 赋值数据集
        }
        $this->module_group = $module_group;
        $this->module_group_id = $module_group_id;
        $this->alert = parseAlert();
        $this->display();
    }

    public function perfect_group_list($list) {
        return $list;
    }

    public function allgroupdialog() {
        $this->module = strtolower(MODULE_NAME);
        $this->list = M(ucfirst($this->module).'Group')->where(array('group_type'=>1))->select();
        $this->display();
    }

    public function removegroupstance() {
        $this->module = strtolower(MODULE_NAME);
        $module_group_id = $_REQUEST['module_group_id'];
        if ('' == $module_group_id) {
            alert('error', L('NOT CHOOSE ANY'), $_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $where[$this->module.'_group_id'] = $module_group_id;
        $module_id = is_array($_REQUEST['module_id']) ? implode(',', $_REQUEST['module_id']) : $_REQUEST['module_id'];

        if($this->isPost()){
            $where[$this->module.'_id'] = array('in',$module_id);
            M(ucfirst($this->module).'Subgroup')->where($where)->delete();
        }else{
            $where[$this->module."_id"] = $module_id;
            M(ucfirst($this->module).'Subgroup')->where($where)->delete();
        }
        if (is_array($_REQUEST['module_id'])) {
            foreach($_REQUEST['module_id'] as $v) {
                $this->update_keyword($v);
            }
        } else {
            $this->update_keyword($_REQUEST['module_id']);
        }

        if ($this->isAjax()) {
            $this->ajaxReturn("OK","",1);
        } else {
            alert('success','移除组成功',$_SERVER['HTTP_REFERER']);
        }
    }

    public function update_keyword($product) {
    }

    public function addgroupstance() {
        $this->module = strtolower(MODULE_NAME);
        $module_group_id = $_REQUEST['module_group_id'];
        $module_id = $_REQUEST['module_id'];
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        if (is_array($module_id)) {
            foreach($module_id as $k=>$v) {
                $module_group = M(ucfirst($this->module).'Subgroup')->where(array($this->module."_group_id"=>$module_group_id,$this->module."_id"=>$v))->find();
                if (!$module_group) {
                    $id = M(ucfirst($this->module).'Subgroup')->add(array($this->module."_group_id"=>$module_group_id,$this->module."_id"=>$v));
                    $sids[] = $id;
                }
                $this->update_keyword($v);
            }

            $this->ajaxReturn($module_id,"",1);
        } else {
            $module_group = M(ucfirst($this->module).'Subgroup')->where(array($this->module."_group_id"=>$module_group_id,$this->module."_id"=>$module_id))->find();
            if ($module_group) {
                $this->ajaxReturn("","这个已经在分组中了",0);
            } else {
                $id = M(ucfirst($this->module).'Subgroup')->add(array($this->module."_group_id"=>$module_group_id,$this->module."_id"=>$module_id));
                $this->update_keyword($module_id);
                $this->ajaxReturn($id,"",1);
            }
        }
    }


    public function enum_field_where($req, $where, $module = "", &$params = array()) {
        if (!$module) {
            $module = strtolower(MODULE_NAME);;
        }
        $ext_array = array(
            '_',
            'act',
            'cat',
            'content',
            'p',
            't',
            'condition',
            'search',
            'field',
            'module_group_id',
            'group_type',
            'search_bt',
            'search_et',
            'by',
            'bys',
            'byd',
            'bysd',
            'bycd',
            'byod',
            'byw',
            'byk',
            'byt',
            'byv',
            'bts',
            'bylea',
            'bytime',
            'bysub',
            'dire',
            'sid',
            'sids',
            'asid',
            'ssid',
            'asids',
            'ssids',
            'csids',
            'csid',
            'esids',
            'esid',
            'mvv',
            'pl',
            'search_census',
            'export',
            'desc_order',
            'asc_order',
            'lia',
            'wsbt',
            'wset',
            'nobd',
            'skill',
            'bybr',
            'assort',
            '_URL_',
            'start',
            'length',
            'login_role_id',
            'catelevel',
            'draw',
            'columns'
        );

        foreach($req as $k=>$v){
            if(in_array($k, $ext_array)){
                continue;
            }

            if (is_array($v) && $v['model']) {
                $f = M('Fields')->cache(true)->where(array('model' => $v['model'], "field"=>$k))->find();
            } else {
                $f = M('Fields')->cache(true)->where(array('model' => $module, "field"=>$k))->find();
            }

            if ($f['form_type'] == "address") {
                $address = "";
                if ($v['value']['state']) {
                    $address .= $v['value']['state'].chr(10);
                    $params[] = $k."[value][state]=".$v['value']['state'];
                }
                if ($v['value']['city']) {
                    $address .= $v['value']['city'].chr(10);
                    $params[] = $k."[value][city]=".$v['value']['city'];
                }
                if ($v['value']['area']) {
                    $address .= $v['value']['area'].chr(10);
                    $params[] = $k."[value][area]=".$v['value']['area'];
                }
                if ($v['value']['street']) {
                    $address .= $v['value']['street'];
                    $params[] = $k."[value][street]=".$v['value']['street'];
                }
                $v['value'] = $address;

            } else if ($f['form_type'] == "datetime" || in_array($k, array("onstation_time", "holiday_time"))) {
                if ($v['value'][0] == "" && $v['value'][1] == "") {
                    $v['value'] = "";
                } else {
                    $params[] = $k."[condition]=tbetween";

                    $params[] = $k."[value][0]=".$v['value'][0];
                    if ($v['value'][0] == "") {
                        $v['value'][0] = 0;
                    } elseif(!is_numeric($v['value'][1])) {
                        $v['value'][0] = strtotime($v['value'][0]);
                    }

                    $params[] = $k."[value][1]=".$v['value'][1];
                    if ($v['value'][1] == "") {
                        $v['value'][1] = PHP_INT_MAX;
                    } elseif(!is_numeric($v['value'][1])) {
                        $v['value'][1] = strtotime(date("Y-m-d 23:59:59",strtotime($v['value'][1])));
                    }
                }
                $this->search_date_field = $k;

            } elseif ($k != "all") {
                if (is_array($v)) {
                    if (in_array($v['condition'],array("is_empty", "is_not_empty")) || $v['value'] != "") {
                        $params[] = $k."[value]=".$v['value'];
                        if ($v['condition'] != "") {
                            $params[] = $k."[condition]=".$v['condition'];
                        }
                    }
                } else if ($v != "") {
                    $params[] = $k."=".$v;
                }
            }

            if(is_array($v)){
                if(in_array($v['condition'],array("is_empty", "is_not_empty")) || (isset($v['value']) && $v['value'] != "")){
                    if ($k == "all") {
                        $wherefield = implode("|", array_merge(all_filter_field($module),array($module.".keyword"), $this->all_search_keyword($module)));
                        $params[] = $k."[value]=".$v['value'];
                        if ($v['condition'] != "") {
                            $params[] = $k."[condition]=".$v['condition'];
                        }
                    }
                    else
                    {
                        $wherefield = $f ? (($v['model']?$v['model']:$module).".".$k): (($v['model']?($v['model']."."):"").$k);
                    }
                    $where = array_merge($this->field_where($wherefield, $v['value'],$v['condition']), $where);

                }
            }else if (strpos($k,".") !== false && $v != ""){
                $where = array_merge($this->field_where($k, $v, ''), $where);
            }else if ($v != ""){
                $where = array_merge($this->field_where(((is_array($v['model']) && $v['model'])?$v['model']:$module).".".$k, $v, ''), $where);
            }
        }
        unset($where['skill']);
        return $where;
    }

    public function all_search_keyword($module) {
        return array();
    }

    public function make_list_order(&$params) {
        $order = "create_time desc";
        if($_GET['desc_order']){
            $order = trim($_GET['desc_order']).' desc';
            $params[] = "desc_order=" . trim($_GET['desc_order']);
        }elseif($_GET['asc_order']){
            $order = trim($_GET['asc_order']).' asc';
            $params[] = "asc_order=" . trim($_GET['asc_order']);
        }
        return $order;
    }

    function list_module($req, $where = array(), $params = array()) {
        $this->module = $this->getmodulename();
        $where = $this->enum_field_where($req, $where, $this->module, $params);
        $this->show_list($where, $params);
    }

    function getmodulename() {
        return strtolower(MODULE_NAME);
    }

    public function format_search_fields_list($module) {
        $field_list = M('Fields')->where(array('model'=>$module,'is_main'=>1))->cache(true)->order('order_id')->select();
        foreach($field_list as $k=>$v) {
            if ($v['form_type'] == "box") {
                eval('$setting=' . $v['setting'] . ';');
                foreach($setting['data'] as $boxv) {
                    $field_list[$k]['boxdata'][$boxv] = $boxv;
                }
            } else if ($v['form_type'] == "user") {
                $idArray = getAllRoleId();
                $roleList = array();
                foreach($idArray as $roleId){
                    $roleList[$roleId] = getUserByRoleId($roleId);
                }
                $field_list[$k]['boxdata'] = $roleList;

            } else if ($v['form_type'] == "s_box") {
                foreach(M('product_category')->where(array('league_id'=>session('league_id')))->order('order_id')->select() as $wv) {
                    $field_list[$k]['boxdata'][$wv['category_id']] = $wv['name'];
                }
            }
        }
        return $field_list;
    }

    public function search(){
        $this->module = strtolower(MODULE_NAME);
        $this->field_list = self::format_search_fields_list($this->module);
        $this->alert = parseAlert();
        $this->display("Public:search");
    }

    public function format_request_param($req, $param) {
        foreach($req as $k=>$v) {
            if ($v['value']) {
                if ($v['condition']) {
                    $param[] = $k."[condition]=".$v['condition'];
                }
                $param[] = $k."[value]=".$v['value'];
            }
        }
        return $param;
    }

    public function dosearch($where = array(), $param = array()) {
        $this->module = strtolower(MODULE_NAME);
        $this->list_module($_GET, $where, $param);
    }

    public function groupsearch($where = array(), $param = array()) {
        $this->module = strtolower(MODULE_NAME);
        session($this->module."_index_refer_url", $_SERVER['REQUEST_URI']);

        if (!isset($_REQUEST['module_group_id'])) {
            return $this->dosearch($where, $param);
        }

        $module_group_id = $this->_request("module_group_id");
        $module_group = D(ucfirst($this->module).'Group')->where(array($this->module."_group_id"=>$module_group_id))->find();
        if (!$module_group) {
            alert('error', "没有这个分组",$_SERVER['HTTP_REFERER']);
        }
        $module_group['type_name'] = $module_group['group_type'] == 0 ? "条件分组" : "固定分组";
        $param[] = "module_group_id=".$module_group_id;
        $param[] = "group_type=".$module_group['group_type'];
        $param[] = "act=groupsearch";

        $this->module_group = $module_group;
        if ($module_group['group_type'] == 0) {
            $_GET = array_merge(unserialize($module_group['content']),$_GET);
            $this->dosearch($where, $param);
        } else {
            $module_list = M(ucfirst($this->module).'Subgroup')->where(array($this->module."_group_id"=>$module_group_id))->select();
            if (!$module_list) {
                $this->alert = parseAlert();
                alert('info',"本组没有任何记录",$_SERVER['HTTP_REFERER']);
            }

            $module_ids = array();
            foreach($module_list as $v) {
                $module_ids[] = $v[$this->module.'_id'];
            }
            $this->list_module(array($this->module."_id"=>array("condition"=>"in", "value"=>$module_ids)), $where, $param);
        }
    }

    public function cycle_money_array($start_time, $end_time, $cycle) {
        $this->module = strtolower(MODULE_NAME);
        $start_time = germ_cycle($start_time, $cycle);
        while($start_time <= $end_time) {
            $time_begin = $start_time;
            $time_end = $start_time = ($cycle == "quarter" ? aquarter($time_begin, 1) : strtotime('+1 '.$cycle, $time_begin));
            $where_cycle_create['create_time'] = array(array('lt',$time_end),array('gt',$time_begin), 'and');
            $cnt = M($this->module)->where($where_cycle_create)->sum("pay_price");
            $cycle_create_array[] = $cnt == null ? 0 : $cnt;
        }
        return $cycle_create_array;
    }

    public function default_cycle_money_bulking_data($tab, $cycle_title) {
        if ($tab == "_charts") {
            $this->charts_title = "新增".$cycle_title."周期数据";
            $this->cycle_count = implode(',', $this->cycle_data);

            $this->cycle_create_count = implode(',', $this->cycle_create_data);
            $this->cycle_money_count = implode(',', $this->cycle_money_data);

        } else {
            $bulking_data = array();
            $year_bulking_data = array();

            $fcnt = 0;
            $fmeony = 0;

            foreach($this->cycle_create_data as $nk=>$this_cnt) {
                $this_money = $this->cycle_money_data[$nk];

                $fcnt_inc = $this_cnt - $fcnt;
                $fcnt_money = $this_money - $fmeony;

                $year_bulking_data[] = array(
                    "inc"=>$fcnt_inc,
                    "money"=>$fcnt_money,
                    "incscale"=>($fcnt_inc / 100) * 100,
                    "moneyscale"=>($fcnt_money / 100) * 100,
                );
            }
            $this->bulking_data = $bulking_data;
            $this->year_bulking_data = $year_bulking_data;
        }
    }


    public function default_cycle_basis_money_bulking_data($tab, $cycle_title) {
        if ($tab == "_charts") {
            $this->charts_title = "新增".$cycle_title."周期数据";
            $this->cycle_count = implode(',', $this->cycle_data);

            $this->cycle_create_count = implode(',', $this->cycle_create_data);
            $this->cycle_money_count = implode(',', $this->cycle_money_data);

            $this->yester_cycle_create_count = implode(',', $this->yester_cycle_create_data);
            $this->yester_cycle_money_count = implode(',', $this->yester_cycle_money_data);

        } else {
            $bulking_data = array();
            $year_bulking_data = array();
            $yester_bulking_data = array();

            $fcnt = 0;
            $fmeony = 0;

            $yester_fcnt = 0;
            $yester_fmoney = 0;

            foreach($this->cycle_create_data as $nk=>$this_cnt) {
                $this_money = $this->cycle_money_data[$nk];

                $yester_cnt = $this->yester_cycle_create_data[$nk];
                $yester_money = $this->yester_cycle_money_data[$nk];

                $fcnt_inc = $this_cnt - $fcnt;
                $fcnt_money = $this_money - $fmeony;

                $year_bulking_data[] = array(
                    "inc"=>$fcnt_inc,
                    "money"=>$fcnt_money,
                    "incscale"=>($fcnt_inc / 100) * 100,
                    "moneyscale"=>($fcnt_money / 100) * 100,
                );

                $yester_fcnt_inc = $yester_cnt - $yester_fcnt;
                $yester_fcnt_money = $yester_money - $yester_fmoney;
                $yester_bulking_data[] = array(
                    "inc"=>$yester_fcnt_inc,
                    "money"=>$yester_fcnt_money,
                    "incscale"=>($yester_fcnt_inc / 100) * 100,
                    "moneyscale"=>($yester_fcnt_money / 100) * 100,
                );

                $inc = $this_cnt - $yester_cnt;
                $money = $this_money - $yester_money;

                $incscale = ($inc / 100) * 100;
                $moneyscale = ($money / 100) * 100;

                $bulking_data[] = array(
                    "inc"=>$inc,
                    "money"=>$money,
                    "incscale"=>$incscale,
                    "moneyscale"=>$moneyscale,
                );
                $fcnt = $this_cnt;
                $fmeony = $this_money;

                $yester_fcnt = $yester_cnt;
                $yester_fmoney = $yester_money;
            }
            $this->bulking_data = $bulking_data;
            $this->year_bulking_data = $year_bulking_data;
            $this->yester_bulking_data = $yester_bulking_data;
        }
    }

    public function default_cycle_money_statistics($start_time, $end_time, $cycle, $tab, $cycle_title) {
        $this->cycle_data = $this->cycle_array($start_time, $end_time, $cycle, $tab == "_charts");

        $this->cycle_title = date("Y", $start_time)."年".$cycle_title;
        $this->cycle_create_data = $this->cycle_date_array($start_time, $end_time, $cycle);

        $this->cycle_money_title = date("Y", $start_time)."年".$cycle_title."销售";
        $this->cycle_money_data = $this->cycle_money_array($start_time, $end_time, $cycle);

        $yester_start_time = strtotime('-1 year', $start_time);
        $yester_end_time = $yester_start_time + ($end_time - $start_time);

        $this->yester_cycle_title = date("Y", $yester_start_time)."年".$cycle_title;
        $this->yester_cycle_create_data = $this->cycle_date_array($yester_start_time, $yester_end_time, $cycle);

        $this->yester_cycle_money_title = date("Y", $yester_start_time)."年".$cycle_title."销售";
        $this->yester_cycle_money_data = $this->cycle_money_array($yester_start_time, $yester_end_time, $cycle);

        $this->default_cycle_basis_money_bulking_datadefault_cycle_basis_money_bulking_data($tab, $cycle_title);
    }


    public function cycle_date_array($start_time, $end_time, $cycle) {
        $this->module = strtolower(MODULE_NAME);
        $start_time = germ_cycle($start_time, $cycle);
        while($start_time <= $end_time) {
            $time_begin = $start_time;
            $time_end = $start_time = ($cycle == "quarter" ? aquarter($time_begin, 1) : strtotime('+1 '.$cycle, $time_begin));
            $where_cycle_create['create_time'] = array(array('lt',$time_end),array('gt',$time_begin), 'and');
            $cnt = M($this->module)->where($where_cycle_create)->count();;
            $cycle_create_array[] = $cnt == null ? 0 : $cnt;
        }
        return $cycle_create_array;
    }

    public function cycle_array($start_time, $end_time, $cycle, $isc) {
        $sp = $isc ? "'" : "";
        $start_time = germ_cycle($start_time, $cycle);
        while($start_time <= $end_time) {
            switch ($cycle) {
                case "week": {
                    $cycle_array[] = $sp.date('W', $start_time)."周".$sp;
                    break;
                }
                case "month": {
                    $cycle_array[] = $sp.date('n', $start_time)."月".$sp;
                    break;
                }
                case "quarter": {
                    $cycle_array[] = $sp.aseason($start_time)."季度".$sp;
                    break;
                }
                case "year": {
                    $cycle_array[] = $sp.date('Y', $start_time)."年".$sp;
                    break;
                }
            }
            $start_time = ($cycle == "quarter" ? aquarter($start_time, 1) : strtotime('+1 '.$cycle, $start_time));
        }
        return $cycle_array;
    }

    public function default_cycle_basis_newly_bulking_data($tab, $cycle_title) {
        if ($tab == "_charts") {
            $this->charts_title = "新增".$cycle_title."周期数据";
            $this->yester_cycle_create_count = implode(',', $this->yester_cycle_create_data);
            $this->cycle_create_count = implode(',', $this->cycle_create_data);
            $this->cycle_count = implode(',', $this->cycle_data);
        } else {
            $bulking_data = array();
            $year_bulking_data = array();
            $yester_bulking_data = array();

            $fcnt = 0;
            $yester_fcnt = 0;
            foreach($this->cycle_create_data as $nk=>$this_cnt) {
                $yester_cnt = $this->yester_cycle_create_data[$nk];

                $fcnt_inc = $this_cnt - $fcnt;
                $year_bulking_data[] = array(
                    "inc"=>$fcnt_inc,
                    "incscale"=>($fcnt_inc / 100) * 100,
                );

                $yester_fcnt_inc = $yester_cnt - $yester_fcnt;
                $yester_bulking_data[] = array(
                    "inc"=>$yester_fcnt_inc,
                    "incscale"=>($yester_fcnt_inc / 100) * 100,
                );

                $inc = $this_cnt - $yester_cnt;
                $incscale = ($inc / 100) * 100;

                $bulking_data[] = array(
                    "inc"=>$inc,
                    "incscale"=>$incscale,
                );
                $fcnt = $this_cnt;
                $yester_fcnt = $yester_cnt;
            }
            $this->bulking_data = $bulking_data;
            $this->year_bulking_data = $year_bulking_data;
            $this->yester_bulking_data = $yester_bulking_data;
        }
    }

    public function default_cycle_basis_newly_statistics($start_time, $end_time, $cycle, $tab, $cycle_title) {
        $this->cycle_data = $this->cycle_array($start_time, $end_time, $cycle, $tab == "_charts");
        $this->cycle_title = date("Y", $start_time)."年新增".$cycle_title;
        $this->cycle_create_data = $this->cycle_date_array($start_time, $end_time, $cycle);

        $yester_start_time = strtotime('-1 year', $start_time);
        $yester_end_time = strtotime('-1 month', $start_time);
        $this->yester_cycle_title = date("Y", $yester_start_time)."年新增".$cycle_title;
        $this->yester_cycle_create_data = $this->cycle_date_array($yester_start_time, $yester_end_time, $cycle);

        $this->default_cycle_basis_newly_bulking_data($tab, $cycle_title);
    }

    public function cat_cycle_date_array_by_cat($start_time_base, $end_time, $cycle) {
        $pc = M("product_category")->where(array('league_id'=>session('league_id'), 'enable'=>1))->select();
        foreach($pc as $v) {
            $cat_count_charts[$v['name']] = $this->cat_cycle_date_array_by_catid($start_time_base, $end_time, $cycle, $v['category_id']);
        }
        return $cat_count_charts;
    }

    public function default_cat_cycle_newly_statistics($start_time, $end_time, $cycle, $tab) {
        $this->cycle_title = date("Y", $start_time)."年新增";

        if ($tab == "_charts") {
            $this->charts_title = "新增周期数据";
            $this->cycle_create_data = $this->cat_cycle_date_array_by_cat($start_time, $end_time, $cycle);
            $cycle_create_count = array();
            foreach($this->cycle_create_data as $k=>$v) {
                $cycle_create_count[$k] = implode(',', $v);
            }
            $this->cycle_create_count = $cycle_create_count;
            $this->cycle_data = $this->cycle_array($start_time, $end_time, $cycle, true);
            $this->cycle_count = implode(',', $this->cycle_data);
        }elseif ($tab == "_catcharts") {
            $this->cycle_data = $this->cycle_array($start_time, $end_time, $cycle, true);
            $this->cat_array = M("product_category")->where(array('league_id'=>session('league_id'), 'enable'=>1))->select();
            $catid = $_GET['acat'] ? $_GET['acat'] : 2;
            $this->catid_cycle_newly_statistics($start_time, $end_time, $cycle, $catid);
        } else {
            $this->cycle_data =$this->cycle_array($start_time, $end_time, $cycle, false);
            $this->cycle_newly_tables($start_time, $end_time, $cycle, $_GET['acat']);
        }
    }

    public function cycle_newly_tables($start_time, $end_time, $cycle, $catid) {
        $this->cat_array = M("product_category")->where(array('league_id'=>session('league_id'), 'enable'=>1))->select();

        $where = array("enable"=>1);
        if ($catid) {
            $where['category_id'] = $catid;
        }
        $cat_name_array = array();
        foreach(M("product_category")->where($where)->select() as $v) {
            $cat_name_array[] = $v['name'];
        }
        $this->cat_name_array = $cat_name_array;

        $this->cycle_create_data = $this->cat_cycle_date_array_by_cycle($start_time, $end_time, $cycle, $catid);

        $yester_start_time = strtotime('-1 year', $start_time);
        $yester_end_time = $yester_start_time + ($end_time - $start_time);
        $this->yester_cycle_title = date("Y", $yester_start_time)."年新增";
        $this->yester_cycle_create_data = $this->cat_cycle_date_array_by_cycle($yester_start_time, $yester_end_time, $cycle, $catid);

        $bulking_data = array();
        $year_bulking_data = array();
        $yester_bulking_data = array();

        foreach($this->cycle_create_data as $nk=>$this_cat) {
            $fcnt = 0;
            $yester_fcnt = 0;

            $cat_bulking_data = array();
            $cat_year_bulking_data = array();
            $cat_yester_bulking_data = array();

            $yester_cat = $this->yester_cycle_create_data[$nk];
            foreach($this_cat as $ck=>$this_cnt) {
                $fcnt_inc = $this_cnt - $fcnt;
                $cat_year_bulking_data[$ck] = array(
                    "inc"=>$fcnt_inc,
                    "incscale"=>($fcnt_inc / 100) * 100,
                );

                $yester_fcnt_inc = $yester_cat[$ck] - $yester_fcnt;
                $cat_yester_bulking_data[$ck] = array(
                    "inc"=>$yester_fcnt_inc,
                    "incscale"=>($yester_fcnt_inc / 100) * 100,
                );

                $inc = $this_cnt - $yester_cat[$ck];
                $incscale = ($inc / 100) * 100;
                $cat_bulking_data[$ck] = array(
                    "inc"=>$inc,
                    "incscale"=>$incscale,
                );

                $fcnt = $this_cnt;
                $yester_fcnt = $yester_cat[$ck];
            }
            $bulking_data[] = $cat_bulking_data;
            $year_bulking_data[] = $cat_year_bulking_data;
            $yester_bulking_data[] = $cat_yester_bulking_data;
        }
        $this->bulking_data = $bulking_data;
        $this->year_bulking_data = $year_bulking_data;
        $this->yester_bulking_data = $yester_bulking_data;
    }

    public function catid_cycle_newly_statistics($start_time, $end_time, $cycle, $catid) {
        $pc = M("product_category")->where("category_id=".$catid)->find();

        $this->cycle_title = date("Y", $start_time)."年新增".$pc['name'];
        $this->charts_title = "新增".$pc['name']."周期数据";
        $this->cycle_create_data = $this->cat_cycle_date_array_by_catid($start_time, $end_time, $cycle, $catid);

        $yester_start_time = strtotime('-1 year', $start_time);
        $yester_end_time = $yester_start_time + ($end_time - $start_time);
        $this->yester_cycle_title = date("Y", $yester_start_time)."年新增".$pc['name'];
        $this->yester_cycle_create_data = $this->cat_cycle_date_array_by_catid($yester_start_time, $yester_end_time, $cycle, $catid);

        $this->charts_title = "新增".$pc['name']."周期数据";
        $this->yester_cycle_create_count = implode(',', $this->yester_cycle_create_data);
        $this->cycle_create_count = implode(',', $this->cycle_create_data);
        $this->cycle_count = implode(',', $this->cycle_data);
    }

    public function default_statistics_time(&$params) {
        $cycle = $_GET['cycle'] ? $_GET['cycle'] : "week";
        if ($_GET['start_time']) {
            $params[] = "start_time=" . $_GET['start_time'];
            $start_time = strtotime(date('Y-m-d', strtotime($_GET['start_time'])));
        } else {
            switch ($cycle) {
                case "week": {
                    $start_time = strtotime("-1 month");
                    break;
                }
                case "year":
                case "month": {
                $start_time = strtotime(date('Y-1-1', time()));
                //$start_time = date('Y-m-d', time());

                break;
                }
                case "quarter": {
                    $start_time = strtotime(date('Y-1-1'));
                    break;
                }
            }
        }

        if ($_GET['end_time']) {
            $params[] = "end_time=" . $_GET['end_time'];
            $end_time = strtotime(date('Y-m-d 23:59:59', strtotime($_GET['end_time'])));
        } else {
            $end_time = strtotime(date('Y-12-31 23:59:59', time()));
        }
        return array($start_time, $end_time);
    }


    public function getprocess() {
        define("WORD_DIR", dirname($_SERVER['SCRIPT_FILENAME']));
        $file_path = WORD_DIR.$_POST['fu'].".pff";
        $this->ajaxReturn(json_decode(file_get_contents($file_path)));
    }

    public function excelimport() {
        $this->module_name = strtolower(MODULE_NAME);
        if(!$this->isPost()) {
            $this->display("Public:excelimport");
            return;
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $role_id = session("role_id");
        $execmd = "php '".dirname($_SERVER['SCRIPT_FILENAME'])."/nli.php' '".$_POST['fu']."' ".$this->module_name." ".$role_id;
        exec($execmd);
        $this->ajaxReturn(array("total"=>0, "loaded"=>0));
    }

    public function nli() {
        define("WORD_DIR", dirname($_SERVER['SCRIPT_FILENAME']));
        $file_path = trim(WORD_DIR.N_EXCEL_IMPORT_FILE,".");
        session("role_id", N_ROLEID);
        $this->limit_import($file_path, $this->format_excel_fields(false));
    }

    public function limit_import($file_name, $field_list) {
        import("ORG.PHPExcel.PHPExcel");
        import("Org.Util.PHPExcel.IOFactory");
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
        $sheet = $objPHPExcel->getSheet(0);
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        foreach($field_list as $field_key=>$field){
            //从A列读取数据
            for($k='A';$k<=$highestColumn;$k++){
                // 读取单元格
                $headval = $objPHPExcel->getActiveSheet()->getCell("$k"."1")->getValue();
                if (in_array($headval, array($field['field'],$field['name']))) {
                    $field_list[$field_key]["_excel_col_"] = $k;
                }
            }
        }

        $product_file = $file_name.".pff";
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        file_put_contents($product_file, json_encode(array("total"=>$highestRow, "loaded"=>0)));
        for($j=2;$j<=$highestRow;$j++){
            $data = array();
            foreach($field_list as $field_key=>$field){
                if (!isset($field['_excel_col_']))
                    continue;
                $cellval = $objPHPExcel->getActiveSheet()->getCell($field['_excel_col_']."$j")->getValue();;
                if ($cellval) {
                    $data[$field['field']] = $cellval;
                }
            }
            $this->refresh_excel_import($field_list, $data);
            file_put_contents($product_file, json_encode(array("total"=>$highestRow, "loaded"=>$j)));
        }
        return array("total"=>$highestRow, "loaded"=>$highestRow);
    }

    public function refresh_excel_import($field_list, $data) {
    }


    public function limit_export($list, $field_list, $title) {
        import("ORG.PHPExcel.PHPExcel");
        $objPHPExcel = new PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        $objProps->setCreator("ayihui");
        $objProps->setLastModifiedBy("ayihui");
        $objProps->setTitle("ayihui employee");
        $objProps->setSubject("ayihui employee data");
        $objProps->setDescription("ayihui employee data");
        $objProps->setKeywords("ayihui employee");
        $objProps->setCategory("ayihui");
        $objPHPExcel->setActiveSheetIndex(0);
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->setTitle($title);
        $ascii = 65;
        $cv = '';
        $i = 1;
        $j = 0;
        foreach($field_list as $field){
            $cellname = $cv.chr($ascii);
            $cellname .= $i;
            $objActSheet->setCellValue($cellname, $field['name']);
            $ascii++;
            if($ascii == 91){
                $ascii = 65;
                $cv = chr(($j++) + 65);
            }
        }

        foreach ($list as $v) {
            $i += 1;
            $ascii = 65;
            $cv = '';
            $j = 0;
            foreach($field_list as $field){
                $cellname = $cv.chr($ascii);
                $cellname .= $i;

                if ($field['field'] == 'customer_id') {
                    $customer_id = $v[$field['field']];
                    if($customer_id){
                        $customer = M('customer')->field("idcode, name")->where('customer_id = %d', $customer_id)->find();
                        if ($customer) {
                            $objActSheet->setCellValue($cellname, $customer['idcode']."-".$customer['name']);
                        }
                    }
                }
                elseif (in_array($field['field'],array('product_id','service_product'))) {
                    $product_id = $v[$field['field']];
                    if($product_id){
                        $product = M('product')->field("idcode, name")->where('product_id = %d', $product_id)->find();
                        if ($product) {
                            $objActSheet->setCellValue($cellname, $product['idcode'].'-'.$product['name']);
                        }
                    }
                }
                elseif ($field['field'] == 'serve_id') {
                    $serve_id = $v[$field['field']];
                    if($serve_id){
                        $serve = M('serve')->field("idcode, name")->where('serve_id = %d', $serve_id)->find();
                        if ($serve) {
                            $objActSheet->setCellValue($cellname, $serve['idcode'].'-'.$serve['name']);
                        }
                    }
                }
                elseif ($field['field'] == 'currier_id'){
                    $currier_id = $v[$field['field']];
                    if($currier_id){
                        $currier = M('currier')->field("name")->where('currier_id = %d', $currier_id)->find();
                        if ($currier) {
                            $objActSheet->setCellValue($cellname, $currier['name']);
                        }
                    }
                }
                elseif ($field['field'] == 'queue_pos'){
                    if ($v['workstate_id']== '排岗' &&  $v['queue_pos'] != '9999999'){
                        $objActSheet->setCellValue($cellname, $v['queue_pos']);
                    }

                }
                elseif (in_array($field['field'],array('queue_category_id'))){
                    $val = proudct_category_map($v[$field['field']]);
                    if ($val) {
                        $objActSheet->setCellValue($cellname, $val);

                    }
                }
                elseif ($field['model'] == 'cultivate' && $field['field'] == 'category_id'){
                    $objActSheet->setCellValue($cellname, $v['category_name']);
                }
                elseif ($field['model'] == 'cultivate' && $field['form_type'] == 'currier_model_id'){
                    $m_model = M($v['model'])->field("idcode, name")->where($v['model'].'_id = %d', $v['model_id'])->find();
                    if ($m_model) {
                        $objActSheet->setCellValue($cellname, $m_model['idcode'].'-'.$m_model['name']);
                    }
                }
                elseif ($field['model'] == 'cultivate' && $field['form_type'] == 'currier_model'){
                    $setting_str = '$setting=' . $field['setting'] . ';';
                    eval($setting_str);
                    $objActSheet->setCellValue($cellname, $setting['data'][$v[$field['field']]]);
                }
                else if (in_array($field['field'],  array("role_id","queue_role_id",
                        "creator_role_id",
                        "owner_role_id",
                        "owner_role_id",
                        "model_owner_role_id"))) {
                    $role_id = $v[$field['field']];
                    $role = D('RoleView')->field("user_name")->cache(true)->where('role.role_id = %d', $role_id)->find();
                    if ($role) {
                        $objActSheet->setCellValue($cellname, $role['user_name']);
                    }
                }
                elseif($field['field'] == 'sign_style')  {
                    if ($v['sign_style']) {
                        $objActSheet->setCellValue($cellname, $v['sign_style']);
                    }

                }
                elseif($field['field'] == 'related_model')
                {
                    $str = "";
                    if ($v['trade']) {
                        $str = "[".$v['trade']['orderid']."]";
                        $str .= $v['trade']['owner_role']['user_name'];
                    }
                    $objActSheet->setCellValue($cellname, $str);
                }
                elseif(in_array($field['field'], array('branch_id','queue_branch_id'))) {
                    $str = branch_show($v[$field['field']]);
                    $objActSheet->setCellValue($cellname, $str);
                }
                elseif($field['field'] == 'cultivate_model_create_time') {
                    $m_model = M($v['model'])->where(array($v['model']."_id"=>$v['model_id']))->find();;
                    $time = pregtime($m_model["create_time"], $field['is_showtime']);
                    $objActSheet->setCellValue($cellname, $time);
                }
                else if($field['form_type'] == 'bc_box' || $field['form_type'] == 'p_box'){
                    $objActSheet->setCellValue($cellname, $v['category_name']);
                }
                else if($field['form_type'] == 'm_box' || $field['form_type'] == 'ms_box'){
                    $status = M('MarketStatus')->cache(true)->where(array('status_id'=>$v[$field['field']]))->find();
                    $objActSheet->setCellValue($cellname, $status['name']);
                }
                else if(in_array($field['form_type'], array(
                    "cultivate_cert_state_box",
                    "cultivate_examine_state_box",
                    "cultivate_settle_state_box",
                    "cultivate_status_box"))){
                    $status = M('CultivateStatus')->cache(true)->where(array('status_id'=>$v[$field['field']]))->find();
                    $objActSheet->setCellValue($cellname, $status['name']);
                }
                else if($field['form_type'] == 'datetime'){
                    if($v[$field['field']] == 0 ){
                        $objActSheet->setCellValue($cellname, '');
                    }else{
                        $time = pregtime($v[$field['field']], $field['is_showtime']);
                        $objActSheet->setCellValue($cellname, $time);
                    }
                }
                elseif($field['form_type'] == 'number' || $field['form_type'] == 'floatnumber' || $field['form_type'] == 'phone' || $field['form_type'] == 'mobile' || ($field['form_type'] == 'text' && is_numeric($v[$field['field']]))){
                    $cc = ' '.$v[$field['field']];
                    $objActSheet->setCellValue($cellname, $cc);
                }
                elseif($field['form_type'] == 'address'){
                    $addr = $this->format_address_fields($v[$field['field']]);
                    $objActSheet->setCellValue($cellname, $addr[0] . $addr[1] . $addr[2] . $addr[3]);
                }
                elseif($field['form_type'] == 'bc_box' || in_array($field['field'], array("queue_category_id"))){
                    $str = '';
                    $category_list = M('product_category')->field("category_id,name")->cache(true)->where(array('league_id'=>session('league_id'), 'enable'=>1))->select();
                    $data_arr = explode(chr(10), $v[$field['field']]);
                    foreach ($category_list as $v2) {
                        if (in_array($v2['category_id'], $data_arr)) {
                            $str .= $v2['name'] . ',';
                        }
                    }
                    $objActSheet->setCellValue($cellname, $str);
                }
                elseif($field['form_type'] == 'a_box'){
                    $str = '';
                    $account_type = M('AccountType')->field("type_id,name")->cache(true)->order('order_id')->select();
                    foreach ($account_type as $v2) {
                        if ($v2['type_id'] == $v[$field['field']]) {
                            $str = $v2['name'];
                            break;
                        }
                    }
                    $objActSheet->setCellValue($cellname, $str);

                }
                elseif($field['form_type'] == 'box'){
                    $b = $v[$field['field']];
                    $str = $b ? implode(",", explode(chr(10),$b)) : "";
                    $objActSheet->setCellValue($cellname, $str);

                }
                elseif($field['form_type'] == 'channel_role_model_box'){
                    $str = '';
                    $channel = M('channel')->field("idcode,name")->cache(true)->where('channel_id = %d', $v[$field['field']])->find();
                    if ($channel) {
                        $str = "[".$channel['idcode']."]".$channel['name'];
                    }
                    $objActSheet->setCellValue($cellname, $str);

                }
                elseif($field['form_type'] == 'channel_role_id_box'){
                    $str = '';
                    if ( $v[$field['field']]) {
                        $str = channel_model_role_show_html($v["channel_role_model"], $v[$field['field']]);
                    }
                    $objActSheet->setCellValue($cellname, $str);

                }
                elseif($field['field'] == 'infow') {
                    $str = $v[$field['field']]['export_info'];
                    $objActSheet->setCellValue($cellname, $str);

                }
                else
                {
                    if ($field['field'] == "BIAOGEHANGXUHAO") {
                        $objActSheet->setCellValue($cellname, ($i-1)."");
                    }
                    elseif($field['field'] == "BIAOGEHANGLIUKONG"){
                        $objActSheet->setCellValue($cellname, "");
                    }
                    elseif (isset($v[$field['field']])){
                        $objActSheet->setCellValue($cellname, $v[$field['field']]);
                    }
                    elseif($field['field'] == "XITONGLIUSHUIHAO"){
                        $objActSheet->setCellValue($cellname, $v["payment_verify"] == '1' ? " ".$v["flowid"]:"");
                    }
                    elseif($field['field'] == "XITONGFLOWLIUSHUIHAO"){
                        $objActSheet->setCellValue($cellname, $v["payment_verify"] == '1' ? " ".$v["infow_flowid"]:"");
                    }
                    elseif($field['field'] == "XITONGFLOWLIUSHUIHAO"){
                        $objActSheet->setCellValue($cellname, $v["payment_verify"] == '1' ? " ".$v["infow_flowid"]:"");
                    }
                    elseif($field['field'] == "CULTIVASTE_AMOUNT"){
                        $objActSheet->setCellValue($cellname, $v["payment_verify"] == '1' ? " ".$v["infow_flowid"]:"");
                    }
                    elseif($field['field'] == "PRODUCT_SKILL_LEVEL"){
                        if ($v['queue_category_id'] && $v['catelevel']) {
                            $catelevel = $v['catelevel'];
                            $cc = explode(",",$v['catelevel']);
                            if ($cc) {
                                foreach($cc as $c2) {
                                    $c3 = explode("=", $c2);
                                    if ($v['queue_category_id'] == $c3[0]) {
                                        $catelevel = $c3[1];
                                        break;
                                    }
                                }
                            }
                            $objActSheet->setCellValue($cellname, $catelevel);
                        }
                    }
                    else {
                        $objActSheet->setCellValue($cellname,  $field['field']);
                    }
                }
                $ascii++;
                if($ascii == 91){
                    $ascii = 65;
                    $cv = chr(($j++) + 65);
                }
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        ob_end_clean();
        $file_name = "exportcache//ayihui_employee_".date('Y-m-d',mktime())."_".time().".xls";
        $objWriter->save($file_name);
        if (defined("SWOOLE_CALL")) {
            exit(json_encode(array("Location"=>"http://b.aobaomuying.cn/".$file_name)));
        } else {
            redirect("http://b.aobaomuying.cn/".$file_name);
        }
    }

    public function show_export_dialog($count) {
        $this->ef = $this->_request("ef");
        $this->export_query_url = $_SERVER['REQUEST_URI'];
        $export_lists = array();
        for($i = 1; $i <= ceil($count / 2000); ++$i) {
            $export_lists[] = $this->export_query_url . "&&p=".$i;
        }
        $this->export_list = $export_lists;
        $this->display("Public:excelexport");
    }

    public function format_excel_fields($ex) {
        $this->module = strtolower(MODULE_NAME);
        $where = array(
            "model"=>$this->module,
            "form_type"=>array("not in",array(
                "pic","video","file"
            )),
        );
        return M('Fields')->where($where)->order('order_id')->select();
    }

    public function excelExport($list=false, $title){
        $this->module = strtolower(MODULE_NAME);
        C('OUTPUT_ENCODE', false);
        if(!is_array($list)){
            $list = D(ucfirst($this->module).'View')->select();
        }
        self::limit_export($list, $this->format_excel_fields(true), $title);
    }


    public function addlog($subject, $content, $category_id = 6) {
        $m_log = M('Log');
        $m_log->role_id = session("role_id");
        $m_log->subject = $subject;
        $m_log->content = $content;
        $m_log->category_id = $category_id;
        $m_log->create_date = time();
        $m_log->update_date = time();
        $m_log->league_id = session('league_id');

        return $log_id = $m_log->add();
    }


    public function astrict() {
        $this->module_name = strtolower(MODULE_NAME);
        if (!$_REQUEST['id']) {
            alert('error', "参数错误" ,$_SERVER['HTTP_REFERER']);
        }
        $this->model_id = $this->_request("id");
        $m_model = M($this->module_name)->where(array($this->module_name."_id"=>$this->model_id))->find();
        if (!$m_model) {
            alert('error', "参数错误" ,$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $owernbranch = get_branch(session("role_id"));
        if (!session('?admin') && !in_array($m_model['owner_role_id'], $owernbranch) && !in_array($m_model['creator_role_id'], $owernbranch)) {
            alert('error', "您没有权限操作" ,$_SERVER['HTTP_REFERER']);
        }
        $this->user_list = D("AstrictUserView")->where(array("model"=>$this->module_name, "model_id"=>$this->model_id))->select();
        $this->alert = parseAlert();
        $this->display("Public:def_astrict");
    }

    public function remove_astrict_role() {
        $this->module_name = strtolower(MODULE_NAME);
        $astrict_ids = $_REQUEST['astrict_id'];
        if (!$astrict_ids) {
            alert('error', "参数错误" ,$_SERVER['HTTP_REFERER']);
        }
        if (!is_array($astrict_ids)) {
            $astrict_ids = array($astrict_ids);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        M("astrict")->where(array("astrict_id"=>array("in", $astrict_ids)))->delete();
        alert('success', "授权成功" ,$_SERVER['HTTP_REFERER']);
    }

    public function add_astrict_role() {
        $this->module_name = strtolower(MODULE_NAME);
        if (!$_REQUEST['id'] || !$_REQUEST['role_id']) {
            $this->error("参数错误");
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $result = self::add_astrict_role_info($_REQUEST['role_id'], $this->module_name, $_REQUEST['id']);
        $result ? $this->success("授权成功") : $this->error("授权失败");
    }

    public function add_astrict_role_info($role_id, $model, $model_id) {
        if (!$role_id)
            return false;
        $where = array(
            "role_id"=>$role_id,
            "model"=>$model,
            "model_id"=>$model_id,
        );
        $astricat = M("astrict")->where($where)->find();
        if ($astricat) {
            return true;
        }
        $where['create_time'] = time();
        return M("astrict")->add($where);
    }

    public function get_astrict_list() {
        $this->module_name = strtolower(MODULE_NAME);
        static $astrict_list = null;
        if (!$astrict_list) {
            $where = array(
                "role_id"=>session("role_id"), "model"=>$this->module_name
            );
            $astrict_list = M("astrict")->where($where)->getField("model_id", true);
        }
        return $astrict_list;
    }

    public function make_astrict_where($brat = true, $branch=null) {
        $this->module_name = strtolower(MODULE_NAME);
        if (!$branch) {
            $branch = get_branch(session("role_id"));
        }
        $map[$this->module_name.'.owner_role_id'] = array("in", $branch);

        if ($brat) {
            $astrict_list = self::get_astrict_list();
            if ($astrict_list) {
                $map[$this->module_name .'.'.$this->module_name.'_id'] = array("in", $astrict_list);
            }
            $map[$this->module_name.'.astrict'] = array('in',array('限制','公开'));
            $map['_logic'] = 'or';
        }
        return $map;
    }


    public function make_astrict_owner_where($brat = true) {
        $this->module_name = strtolower(MODULE_NAME);
        $map[$this->module_name.'.owner_role_id'] = session("role_id");
        if ($brat) {
            $astrict_list = self::get_astrict_list();
            if ($astrict_list) {
                $map[$this->module_name .'.'.$this->module_name.'_id'] = array("in", $astrict_list);
            }
            $map[$this->module_name.'.astrict'] = array('in',array('限制','公开'));
            $map['_logic'] = 'or';
        }
        return $map;
    }

    public function make_branch_role_where($role_id=null) {
        $role_branch = M("branch_category")->where("role_id=".$role_id)->find();
        if (!$role_branch) {
            return null;
        }
        $role_ids = get_branch_all_role($role_branch['branch_id']);
        return $role_ids;
    }

    public function is_interest($mode, $branch=null, $astrict = true) {
        if (self::is_owner($mode, $branch)) {
            return true;
        }
        return session('authority') == "所属" ? in_array($mode['astrict'], array('限制','公开')) : $astrict;
    }

    public function is_owner($mode, $branch=null) {
        $this->module_name = strtolower(MODULE_NAME);
        if (session('?admin') || in_array($mode[$this->module_name.'_id'], self::get_astrict_list())) {
            return true;
        }
        if (!$branch) {
            $branch = get_branch(session("role_id"));
        }
        return in_array($mode['owner_role_id'], $branch) || in_array($mode['creator_role_id'], $branch);
    }


    public function fix_branch_fields($fields, $mode, $isself) {
        foreach($fields as $f) {
            if ($f['is_branch'] && !$isself && $mode[$f['field']]) {
                $mode[$f['field']] = "***";
            }
        }
        return $mode;
    }

    public function cardinfo() {
        $this->module_name = strtolower(MODULE_NAME);
        if (!isset($_GET['id'])) {
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        $id = $this->_request("id");
        $model = D(ucfirst($this->module_name).'View')->where($this->module_name.'.'.$this->module_name.'_id = %d', $id)->find();
        if (!$model) {
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $model['address'] = str_replace(chr(10), '', $model['address']);
        $model['address'] = str_replace('市辖区', '', $model['address']);
        $model['address'] = str_replace('市辖县', '', $model['address']);

        $where = array(
            $this->module_name."_id"=>$id,
            $this->module_name."_field"=>"",
            "is_main"=>2
        );
        $model['cardpic'] = M($this->module_name.'Images')->where($where)->find();

        $this->model_id = $id;
        $this->model = $model;
        $this->display("Public:cardinfo");
    }

    public function prompt_list($model, $model_id) {
        $this->module_name = strtolower(MODULE_NAME);
        $curtime = time();
        $prompt_list = D('PromptView')->where(array("model"=>$model, "model_id"=>$model_id))->order("state desc, create_time desc")->select();
        foreach($prompt_list as $key => $value){
            $prompt_list[$key]['creator'] = D('RoleView')->where('role.role_id = %d', $value['creator_role_id'])->find();
            if ($value['state'] == "开启") {
                $prompt_list[$key]['prompt_state'] = ($curtime >= $value['prompt_time']);
            }
        }
        return $prompt_list;
    }

    public function prompt_count($model, $model_id, $curtime) {
        $this->module_name = strtolower(MODULE_NAME);
        $where = array(
            "model"=>$model,
            "model_id"=>$model_id,
            "state"=>"开启",
            "prompt_time"=>array("lt", $curtime)
        );
        return D('PromptView')->where($where)->count();
    }


    public function update_correlation_channel_introducer($mn, $model) {
        $where = array(
            "related_model_name" => $mn,
            "related_model_id" => $model[$mn . "_id"],
        );
        $data = array(
            "channel_role_model" => $model['channel_role_model'],
            "channel_role_model_keyword" => $model['channel_role_model_keyword']?$model['channel_role_model_keyword']:"",
            "channel_role_id" => $model['channel_role_id'],
            "channel_role_id_keyword" => $model['channel_role_id_keyword']?$model['channel_role_id_keyword']:"",
        );
        M("commiss")->where($where)->setField($data);
    }

    public static function update_model_surplus_price($module) {
        foreach($module as $module_name=>$module_ids) {
            if (!is_array($module_ids)) {
                $module_ids = array($module_ids);
            }
            foreach($module_ids as $module_id) {
                $trade_surplus_price = D(ucfirst($module_name).'TradeView')->where($module_name.'_trade.'.$module_name.'_id = %d', $module_id)->sum("surplus_price");
                $cultivate_surplus_price = M('cultivate')->where(array('model_id'=>$module_id, "model"=>$module_name, "status_id"=>array("neq",0)))->sum("surplus_price");
                $cultivate_sum_price = M('cultivate')->where(array('model_id'=>$module_id, "model"=>$module_name, "status_id"=>array("neq",0)))->sum("sum_settle_price");

                if ($module_name == "customer") {
                    $market_surplus_price = D('MarketView')->where($module_name.'.'.$module_name.'_id = %d', $module_id)->sum("surplus_price");
                }
                $sum_surplus_price = $trade_surplus_price  + $market_surplus_price + $cultivate_surplus_price;
                M()->execute('update 5k_a_'.$module_name.' set market_surplus_price="'.$market_surplus_price.'",cultivate_sum_price="'.$cultivate_sum_price.'",cultivate_surplus_price="'.$cultivate_surplus_price.'", trade_surplus_price="'.$trade_surplus_price.'", sum_surplus_price="'.$sum_surplus_price.'" where '.$module_name.'_id='.$module_id);
            }
         }
    }

    public function update_surplus_price($module, $module_ids) {
        if (!is_array($module_ids)) {
            $module_ids = array($module_ids);
        }
        $where = array(
            $module.'.'.$module.'_id'=>array("in", $module_ids)
        );
        foreach(D(ucfirst($module).'View')->where($where)->select() as $k=>$m) {
            $corre = D(ucfirst($m['corre']).ucfirst($module)."View")->where(array($module."_id"=>$m[$module.'_id']))->find();;
            if ($corre) {
                self::update_model_surplus_price(array($m['corre']=>$corre[$m['corre'].'_id']));
            }
        }
    }

    public function set_cost_status($id, $field) {
        $this->module_name = strtolower(MODULE_NAME);
        $cost_where = array(
            $this->module_name."_id"=>$id,
            "cost_field"=>$field
        );
        $data =  array("status"=>1, "status_time"=>time(), "create_time"=>time());
        $m_cost = M($this->module_name."_cost")->where($cost_where)->find();
        if ($m_cost) {
            M($this->module_name."_cost")->where($cost_where)->setField($data);
        } else {
            M($this->module_name."_cost")->add(array_merge($cost_where,$data));
        }
        return true;
    }

    public function cost_submit() {
        $this->module_name = strtolower(MODULE_NAME);
        $id = $_REQUEST['id'];
        $field = $_REQUEST['field'];
        if (!$id || $$field) {
            alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        self::set_cost_status($id, $field);
        alert('success', "成本提交成功" ,$_SERVER['HTTP_REFERER']);
    }


    public function cost() {
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);
        $this->module = strtolower(MODULE_NAME);
        $params = array();
        if ($_GET['search']) {
            $params[] = "search=" . $_GET['search'];
            $where = field_where($this->module.".idcode", $_GET['search'], $_GET['condition']);
        }
        $where["cost_status"]=array("gt", 0);

        if ($_GET['by']) {
            $params[] = "by=" . $_GET['by'];
            switch($_GET['by']) {
                case "su": {
                    $where['cost_status'] = 1;
                    break;
                }
                case "sc": {
                    $where['cost_status'] = array("gt", 1);
                    break;
                }
            }
        }

        if ($_GET['bf']) {
            $params[] = "bf=" . $_GET['bf'];
            $where['cost_field'] = trim($_GET['bf']);
        }

        $this->module = strtolower(MODULE_NAME);
        $order = self::make_list_order($params);

        $this->parameter = implode('&', $params);
        $d_model_cost = D(ucfirst($this->module).'CostView');
        $count = $d_model_cost->where($where)->count();

        if ($count) {
            $page = self::assign_list_page($this->parameter, $count);
            $list = $d_model_cost->where($where)->order($order)->Page($page->nowPage, $page->listRows)->select();

            $this->assign('list',$this->format_module_list($list));// 赋值数据集
        }
        $this->cost_catetory = cost_filed_map($this->module);
        $this->alert = parseAlert();
        $this->display("cost");
    }

    public function format_cost_cash_desc($v) {
        $owner = D('RoleView')->where('role.role_id = %d', $v['owner_role_id'])->find();
        $desc_param = array(
            "责任老师"=>$owner['user_name']
        );
        if ($v['product_id']) {
            $desc_param['雇员'] = "<a href='".U("Product/view", "id=".$v['product_id'])."'>[".$v['product_idcode']."]".$v['product_name']."</a>";
        }
        if ($v['product_id']) {
            $desc_param['客户'] = "<a href='".U("customer/view", "id=".$v['customer_id'])."'>[".$v['customer_idcode']."]".$v['customer_name']."</a>";
        }
        if ($v['cost_field'] == "introducerfee" && $v['introducer']) {
            $desc_param['渠道介绍人'] = $v['introducer'];
        }
        return $desc_param;
    }


    public function cost_cash() {
        $this->module = strtolower(MODULE_NAME);
        if (!$_REQUEST['ids']) {
            alert('error',  L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $model_cost_ids = $_REQUEST['ids'];
        $d_model_cost = D(ucfirst($this->module).'CostView');
        $m_model_cost = $d_model_cost->where(array($this->module."_cost_id"=>array("in", $model_cost_ids)))->select();
        foreach($m_model_cost as $k=>$v) {
            $desc_param = $this->format_cost_cash_desc($v);
            $result = A("Manage/Account")->pay_model_cost($v[$v['cost_field']], $this->module,$v, $desc_param);
            if ($result) {
                $data = array(
                    "status"=>time(),
                    "status_time"=>time(),
                );
                M($this->module."_cost")->where(array($this->module."_cost_id"=>$v[$this->module."_cost_id"]))->setField($data);
            }
        }
        alert('success', "成本提现成功",$_SERVER['HTTP_REFERER']);
    }

    public function cost_revoke() {
        $this->module = strtolower(MODULE_NAME);
        if (!$_REQUEST['ids']) {
            alert('error',  L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $model_cost_ids = is_array($_REQUEST['ids']) ? $_REQUEST['ids'] : array($_REQUEST['ids']);
        $data = array(
            "status"=>0,
            "status_time"=>time(),
        );
        M($this->module."_cost")->where(array($this->module."_cost_id"=>array("in", $model_cost_ids)))->setField($data);
        alert('success', "成本撤销成功",$_SERVER['HTTP_REFERER']);
    }

    public function parse_dialog_where($model) {
        $this->module = $model?strtolower($model):strtolower(MODULE_NAME);
        $where = array();
        if ($_GET["search"]['value']) {
            foreach(getMainFields($this->module) as $fk => $fv) {
                $field_list[] = $this->module.".".$fv['field'];
            }
            $field = implode("|", $field_list);
            $where[$field] = array('like','%'.$_GET["search"]['value'].'%');
        }
        return $where;
    }

    public function add_origin_field() {
        $this->module = strtolower(MODULE_NAME);
        $name = $this->_request('name','trim');
        if(!$name){
            $this->ajaxReturn("","",3);
        }
        $origin_field = M('Fields')->where(array('field_id'=>"9"))->find();
        eval('$setting=' . $origin_field['setting'] . ';');
        $setting['data'][] = $name;

        $boxtype = $setting['type'];
        $setting_str = 'array(';
        $setting_str .= "'type'=>'$boxtype','data'=>array(";
        $i = 0;
        $s = array();
        foreach($setting['data'] as $v){
            $v = trim($v);
            if($v != '' && !in_array($v ,$s)){
                $i++;
                $setting_str .= "$i=>'$v',";
                $s[] = $v;
            }
        }
        $setting_str = substr($setting_str,0,strlen($setting_str) -1 ) .'))';
        M('Fields')->where(array('field_id'=>"9"))->setField("setting", $setting_str);
        $this->ajaxReturn($name,"",1);
    }


    public function account_list() {
        $this->module = strtolower(MODULE_NAME);

        $module_name = ucfirst($this->module).'AccountView';
        $data_field = array(
            array(
                "field"=>"clause_name",
                "order"=>"clause_name"
            ),
            array(
                "field"=>"money_show",
                "order"=>"money"
            ),
            array(
                "field"=>"creator_name",
                "order"=>"creator_role_id"
            ),
            array(
                "field"=>"inflow_model_show",
                "order"=>"account_id"
            ),
            array(
                "field"=>"related_model_show",
                "order"=>"account_id"
            ),
            array(
                "field"=>"create_time_show",
                "order"=>"create_time"
            ),
            array(
                "field"=>"description",
                "order"=>"description"
            ),
            array(
                "field"=>"account_id",
                "order"=>"account_id"
            ),
        );
        $where = $this->account_list_where($_GET['acat'] ? $_GET['acat'] : "0", $_GET['clause_additive'] ? $_GET['clause_additive'] : "");
        $data = make_data_list($module_name, $where, $data_field, array($this, "format_account_info"));
        $data['sum_money'] = $this->account_total($_GET['acat'] ? $_GET['acat'] : "0", $_GET['clause_additive'] ? $_GET['clause_additive'] : "");;
        $this->ajaxReturn($data,'JSON');
    }

    public function account_total($acat, $clause_additive) {
        $where = $this->account_list_where($acat, $clause_additive);
        $module_name = ucfirst($this->module).'AccountView';
        $m_account_total = D($module_name)->field($this->make_account_total_field())->where($where)->find();
        return $m_account_total ? $m_account_total['account_total']:0;
    }

    public function get_account_total() {
        $accounts_totals = $this->account_total($_GET['acat'] ? $_GET['acat'] : "0", $_GET['clause_additive'] ? $_GET['clause_additive'] : "");
        $this->ajaxReturn(array("account_total"=>$accounts_totals));
    }

    public function make_account_total_field() {
$fields = <<<STR
        sum(money * (case income_or_expenses when 3 then -1 when -3 then 1 when 2 then -1 when -2 then 1 else income_or_expenses end)) as account_total
STR;
        return $fields;
    }


    public function account_list_where($acat, $clause_additive) {
        $this->module = strtolower(MODULE_NAME);
        $where = array(
            'clause_additive'=>$clause_additive,
            'account_type'=>array('eq',$this->module)
        );
        if ($acat != 0) {
            $where['income_or_expenses'] = array("in", explode(",", $acat));
        }else {
            $where['income_or_expenses'] = array("not in", array(2,-2, 3, -3));
        }
        if ($_GET['start_time'] || $_GET['end_time']) {
            $where['account.create_time'] =  array('between', make_time_between());
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['account.keyword|account.description'] =  array('like', "%".$_REQUEST['search']['value']."%");
        }
        $where['league_id'] = session("league_id");

        return $where;
    }

    public function format_account_info($v) {
        $v['inflow_model_show'] = "";
        if ($v['infow_account_id']) {
            if ($v['inflow_model'] == "inernal" || $v['inflow_model'] == "flow") {
                $v['inflow_model_show'] = session('league_name');
            } else {
                $infow = D(ucfirst($v['inflow_model']).'AccountView')->where(array("account_id"=>$v['infow_account_id']))->find();;
                if ($infow) {
                    $aname = "[".$infow["idcode"]."]".$infow[$v['inflow_model'].'_name'];
                    $href = U($v['inflow_model']."/view", 'id='.$infow[$v['inflow_model']."_id"]);
                    $v['inflow_model_show'] = "<a href='".$href."' target='_blank'/>".$aname."</a>";;
                }
            }
        }

         if ($v['related_model'] == 'trade') {
            $trade = D("TradeAccountView")->where(array('account_id' => $v['account_id']))->find();
            $v['related_model_show'] = "<a href=".U('trade/view','id='.$trade['trade_id'])." target='_blank'>".$trade['orderid']."</a>";
        } else if ($v['related_model'] == 'market') {
            $market = D("MarketAccountView")->where(array('account_id' => $v['account_id']))->find();
            $v['related_model_show'] = "<a href=".U('market/view','assort=cost&id='.$market['market_id'])." target='_blank'>".$market['market_idcode']."</a>";
        } else if ($v['related_model'] == 'cultivate') {
            $cultivate = D("CultivateAccountView")->where(array('account_id' => $v['account_id']))->find();
            $v['related_model_show'] = "<a href=".U('cultivate/view','assort=cost&id='.$cultivate['cultivate_id'])." target='_blank'>".$cultivate['cultivate_idcode']."</a>";
        } else {
            $v['related_model_show'] = "";
        }

        if ($v['related'] && $v['related_id']) {
            $v[$v['related']] = D(ucfirst($v['related'])."View")->where(array($v['related'].'_id'=>$v['related_id']))->find();
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
        }

        $v['create_time_show'] =  toDate($v['create_time']);

        switch($v['income_or_expenses']) {
            case "3":$v['clause_name'] = "解冻".$v['clause_name'];break;
            case "-3":$v['clause_name'] = "冻结".$v['clause_name'];break;
        }

        $dic = $v['income_or_expenses'];
        if (($dic == -3 || $dic == 3) || ($dic == -2 || $dic == 2)) {
            $dic *= -1;
        }
        $v['money_show'] =  number_format($dic / abs($dic) * $v['money'], 2);
        return $v;
    }

    public function format_account_list($module_id, $acat) {
        $this->module = strtolower(MODULE_NAME);
        $account_where = array(
            'clause_additive'=>$module_id,
            'account_type'=> array('eq',$this->module)
        );
        $account_where['league_id'] = session("league_id");

        if ($acat != 0) {
            $account_where['income_or_expenses'] = array("in", explode(",", $acat));
        }else {
            $account_where['income_or_expenses'] = array("not in", array(2,-2, 3, -3));
        }
        $accounts = D(ucfirst($this->module).'AccountView')->where($account_where)->order("create_time desc")->select();

        $account_sum = 0;
        foreach($accounts as $k=>$v) {
            if ($v['infow_account_id']) {
                if ($v['inflow_model'] == "inernal" || $v['inflow_model'] == "flow") {
                    $infow['show_infow'] = session('league_name');
                } else {
                    $infow = D(ucfirst($v['inflow_model']).'AccountView')->where(array("account_id"=>$v['infow_account_id']))->find();;
                    if ($infow) {
                        $aname = "[".$infow["idcode"]."]".$infow[$v['inflow_model'].'_name'];
                        $href = U($v['inflow_model']."/view", 'id='.$infow[$v['inflow_model']."_id"]);
                        $infow['show_infow'] = "<a href='".$href."' target='_blank'/>".$aname."</a>";
                    }
                }
                $accounts[$k]['infow'] =$infow;
            }

            if ($v['related_model'] == 'trade') {
                $accounts[$k]['trade'] = D("TradeAccountView")->where(array('account_id' => $v['account_id']))->find();
            } else if ($v['related_model'] == 'market') {
                $accounts[$k]['market'] = D("MarketAccountView")->where(array('account_id' => $v['account_id']))->find();
            }

            if ($v['related'] && $v['related_id']) {
                $accounts[$k][$v['related']] = D(ucfirst($v['related'])."View")->where(array($v['related'].'_id'=>$v['related_id']))->find();
            } else if ($v['related_id']) {
                $ra = M("account")->where(array('account_id'=>$v['related_id']))->find();
                if ($ra) {
                    if ($ra['account_type'] == 'internal') {
                        $list[$k]['related_info'] = session('league_name');
                    } else {
                        $rinfo = M($ra['account_type'])->cache(true)->where(array($ra['account_type'].'_id'=>$ra['clause_additive']))->find();
                        if ($rinfo) {
                            $accounts[$k]['related_info'] = "<a href='".U($ra['account_type'].'/view', 'id='.$ra['clause_additive'])."' target='_blank'>".$rinfo['idcode']."</a>";
                        }
                    }
                }
            }

            $dic = $v['income_or_expenses'];
            if (($dic == -3 || $dic == 3) || ($dic == -2 || $dic == 2)) {
                $dic *= -1;
            }
            if ($dic > 0) {
                $account_sum += $v['money'];
            } elseif($dic < 0)  {
                $account_sum -= $v['money'];
            }
            $accounts[$k]['money_show'] =  number_format($dic / abs($dic) * $v['money'], 2);
        }
        $this->accounts_totals = number_format($account_sum, 2);;
        $this->accounts_list = $accounts;
    }


    public static function sort_queue_pos($product_id, $queue_category_id, $queue_branch_id=0,$state = "nl", $adjpos = 0) {
        $where = array();
        $where['queue_state'] = 1;
        $where['queue_branch_id'] = $queue_branch_id;
        $where['queue_category_id'] = $queue_category_id;
        $where['workstate_id'] = "排岗";
        $where['league_id'] = session('league_id');

        $found = false;
        $m_products = M("product")->field("queue_pos,product_id")->where($where)->order("queue_pos asc")->select();
        foreach($m_products as $k=>$v) {
            if ($v['product_id'] == $product_id) {
                $found = true;
                array_splice($m_products, $k, 1);
                if ($state == "nf") {
                    array_unshift($m_products, $v);
                } else if ($state == "nl") {
                    array_splice($m_products, count($m_products), 0, array($v));
                } else if ($adjpos) {
                    if ($state == "f") {
                        $adjpos = max($k - $adjpos, 0);
                    } else {
                        $adjpos = min($k + $adjpos, count($m_products));
                    }
                    array_splice($m_products, $adjpos, 0, array($v));
                }
                break;
            }
        }

        $where['queue_state'] = 0;
        $m_products_0 = M("product")->field("queue_pos,product_id")->where($where)->order("queue_pos asc")->select();
        foreach($m_products_0 as $k=>$v) {
            if ($v['product_id'] == $product_id) {
                array_splice($m_products_0, $k, 1);
                if ($state == "nf") {
                    array_unshift($m_products_0, $v);
                } else if ($state == "nl") {
                    array_splice($m_products_0, count($m_products_0), 0, array($v));
                } else if ($adjpos) {
                    if ($state == "f") {
                        $adjpos = max($k - $adjpos, 0);
                    } else {
                        $adjpos = min($k + $adjpos, count($m_products));
                    }
                    array_splice($m_products_0, $adjpos, 0, array($v));
                }
                break;
            }
        }
        $m_products = array_merge($m_products?$m_products:array(), $m_products_0?$m_products_0:array());

        foreach($m_products as $k=>$v) {
            $m_data = array(
                "queue_pos"=>$k,
            );
            M("product")->where(array("product_id"=>$v['product_id']))->setField($m_data);
        }
    }

    public function list_col_filter_select() {
        $this->field = trim($_REQUEST['field']);
        $this->type = trim($_REQUEST['type']);
        $this->field_id = trim($_REQUEST['field_id']);
        if ($this->field_id) {
            $this->field_info = M("fields")->where("field_id=".$this->field_id)->find();
        }
        $this->display("Public:list_col_filter_".$this->type."_select"); // 输出模板
    }


    public function commiss_check_where($m_model) {
        if ($_REQUEST['telephone'] && ($m_model == NULL || $m_model['telephone'] != $_REQUEST['telephone'])) {
            $where['telephone'] = $_REQUEST['telephone'];
        }
        if ($_REQUEST['qq_number'] && ($m_model == NULL ||  $m_model['qq_number'] != $_REQUEST['qq_number'])) {
            $where['qq_number'] = $_REQUEST['qq_number'];
        }
        if ($_REQUEST['wechat']  && ($m_model == NULL || $m_model['wechat'] != $_REQUEST['wechat'] )) {
            $where['wechat'] = $_REQUEST['wechat'];
        }

        return check_channel_model_info($where, $m_model ? $m_model['commiss_id']:null);
    }

    public function update_product_evaluate($product_id) {
        $m_product = M("product")->field("init_praise_days,init_aware,init_profession")->where(array("product_id"=>$product_id))->find();
        if ($m_product) {
            $data = array(
                "total_praise_days"=>M("market_product_evaluate")->where(array("product_id"=>$product_id))->sum("praise_days") + $m_product['init_praise_days'],
                "year_praise_days"=>M("market_product_evaluate")->where(array('update_time' => array('gt',strtotime(date('Y-01-01', time()))),"product_id"=>$product_id))->sum("praise_days"),
            );
            $product_evaluate = M("market_product_evaluate")->field(
                "sum(aware) as total_aware, count(market_product_evaluate_id) as market_product_evaluate_cnt,sum(profession) as total_profession, count(market_product_evaluate_id) as market_product_evaluate_cnt")->where(array("product_id"=>$product_id))->find();
            $data['aware'] = ($m_product['init_aware'] + $product_evaluate['total_aware']) / ($product_evaluate['market_product_evaluate_cnt'] + 1);;
            $data['profession'] = ($m_product['init_profession'] + $product_evaluate['total_profession']) / ($product_evaluate['market_product_evaluate_cnt'] + 1);;

            M("product")->where(array("product_id"=>$product_id))->setField($data);
        }
    }


    public function make_workstate_where($where, &$params, &$debar_search_field) {
        if ($_GET['wsbt']) {
            $params[] = "wsbt=" . $_GET['wsbt'];
            $debar_search_field[] = 'wsbt';
        }
        if ($_GET['wset']) {
            $params[] = "wset=" . $_GET['wset'];
            $debar_search_field[] = 'wset';
        }
        if ($_GET['nobd']) {
            $params[] = "nobd=" . $_GET['nobd'];
            $debar_search_field[] = 'nobd';
        }

        if ($_GET['wset'] || $_GET['wsbt']) {
            $wset = strtotime($_GET['wset']);
            $wsbt = strtotime($_GET['wsbt']);
            $timewhere = array(
                '_string'=>"((start_date>=$wsbt and start_date<=$wset) or (end_date>=$wsbt and end_date<=$wset) or (start_date<=$wsbt and end_date>=$wset)) and workstate_id in ('请假','公司培训','司外订单','上岗') and isclose=0"
            );
            $timewhere['league_id'] = session("league_id");

            $nobd = trim($_GET['nobd']);
            $product_ids = array();
            if ($nobd && $nobd > 0) {
                foreach(D("event")->where($timewhere)->select() as $k=>$v) {
                    $start_date = max($wsbt, $v['start_date']);
                    $end_date = min($wset, $v['end_date']);
                    if (day($end_date - $start_date) + 1 != $nobd) {
                        $product_ids[] = $v['product_id'];
                    }
                }
                $timewhere["_string"] = (count($product_ids) != 0 ? "product_id in(".implode(",", $product_ids).")":"");
            }
            if ($timewhere["_string"]) {
                $product_ids = M("event")->where($timewhere)->getField("product_id", true);
                if ($product_ids && count($product_ids) > 0) {
                    $where['_string'] .= " AND (product.product_id not in (".implode(",",$product_ids)."))";
                }
            }
        }
        return $where;
    }


    public function checkevent($product_id, $start_date, $end_date) {
        $range_start = parseDateTime($start_date);
        $range_end = parseDateTime($end_date);
        $timezone = null;
        if (isset($_REQUEST['timezone'])) {
            $timezone = new DateTimeZone($_REQUEST['timezone']);
        }
        return D('ProductEventView')->get_events($product_id, $range_start, $range_end, $timezone);
    }


    public function check_holiday_time($product_id, &$value) {
        $events = $this->checkevent($product_id, "2014-01-01", "2020-01-01");
        $value['onstation_time'] = $value['holiday_time'] = $value['idle_time'] = 0;

        if ($events && $events[$product_id]) {

            $onstation_time_begin = strtotime($_GET['onstation_time']['value'][0]?$_GET['onstation_time']['value'][0]:"2014-01-01");
            $onstation_time_end = strtotime($_GET['onstation_time']['value'][1]?$_GET['onstation_time']['value'][1]:"2020-01-01");

            $holiday_time_begin = strtotime($_GET['holiday_time']['value'][0]?$_GET['holiday_time']['value'][0]:"2014-01-01");
            $holiday_time_end = strtotime($_GET['holiday_time']['value'][1]?$_GET['holiday_time']['value'][1]:"2020-01-01");

            if ($_GET['idle_time']['value'][0]) {
                $idle_time_begin = strtotime(date("Y-m-d 00:00:00", strtotime($_GET['idle_time']['value'][0])));
            } else {
                $idle_time_begin = "";
            }
            if ($_GET['idle_time']['value'][1]) {
                $idle_time_end = strtotime(date("Y-m-d 23:59:59", strtotime($_GET['idle_time']['value'][1])));
            } else {
                $idle_time_end = "";
            }

            $busy_map = array();
            $product_event = $events[$product_id];
            foreach($product_event as $k=>$v) {
                $start_time = strtotime($v['start']);
                $end_time = strtotime($v['end']);
                if ($v['workstate_id'] == "上岗" && $start_time <= $onstation_time_end && $end_time >= $onstation_time_begin) {
                    $split_start_time = max($onstation_time_begin, $start_time);
                    $split_end_time = min($onstation_time_end, $end_time);
                    $value['onstation_time'] += day($split_end_time-$split_start_time) + 0.5;

                    if ($idle_time_begin && $idle_time_end && (is_midle($start_time, $end_time, $idle_time_begin, $idle_time_end))) {
                        $split_start_time = max($idle_time_begin, $start_time);
                        $split_end_time = min($idle_time_end, $end_time);

                        $datetime1 = $split_start_time;
                        while(true) {
                            $busy_map[date("Y-m-d", $datetime1)] = 1;
                            if (($datetime1 = strtotime("+1 day", $datetime1)) > $split_end_time) {
                                break;
                            }
                        }
                    }

                } else if (in_array($v['workstate_id'],array("请假","公司培训","司外订单")) && $start_time <= $holiday_time_end && $end_time >= $holiday_time_begin) {
                    $split_start_time = max($holiday_time_begin, $start_time);
                    $split_end_time = min($holiday_time_end, $end_time);
                    $value['holiday_time'] += day($split_end_time-$split_start_time) + 1;

                    if ($idle_time_begin && $idle_time_end && (is_midle($start_time, $end_time, $idle_time_begin, $idle_time_end))) {
                        $split_start_time = max($idle_time_begin, $start_time);
                        $split_end_time = min($idle_time_end, $end_time);

                        $datetime1 = $split_start_time;
                        while(true) {
                            $busy_map[date("Y-m-d", $datetime1)] = 1;
                            if (($datetime1 = strtotime("+1 day", $datetime1)) > $split_end_time) {
                                break;
                            }
                        }
                    }
                }
            }

            if ($idle_time_begin && $idle_time_end) {
                $datetime1 = new DateTime(date("Y-m-d 00:00:00", $idle_time_begin));
                $datetime2 = new DateTime(date("Y-m-d 23:59:59", $idle_time_end));
                $idle_days = $datetime1->diff($datetime2)->format('%a') + 1;
                $value['idle_time'] = $idle_days - count($busy_map);
            }
        }
    }

    public function format_address_fields($v) {
        $address_array = explode(chr(10), $v);
        $state = $address_array[0];
        if ($state) {
            $a = M('Area')->cache(true)->where(array("id"=>$state))->find();
            if ($a) {
                $state = $a['name'];
            }
        }
        $city = $address_array[1];
        if ($city) {
            $a = M('Area')->cache(true)->where(array("id"=>$city))->find();
            if ($a) {
                $city = $a['name'];
            }
        }
        $area = $address_array[2];
        if ($area) {
            $a = M('Area')->cache(true)->where(array("id"=>$area))->find();
            if ($a) {
                $area = $a['name'];
            }
        }
        return array($state,$city,$area, $address_array[3]);
    }

    public function profect_data_view($v) {
        $this->module = strtolower(MODULE_NAME);
        $field_list = M('Fields')->where(array("model"=>$this->module))->cache(true)->select();
        foreach($field_list as $f) {
            switch ($f['form_type']) {
                case 'address' : {
                    if (isset($v[$f['field']])) {
                        $v[$f['field']."_format"] = $this->format_address_fields($v[$f['field']]);
                    }
                }break;
                default: {

                }break;
            }
        }
        return $v;
    }

}
