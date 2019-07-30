<?PHP 
class TrainAction extends BaseAction{
	public function _initialize(){
		$action = array(
			'permission'=>array(
                'search',
                'webshow',
                'vieworder',
                'delimg',
                'deletevideo',
                'deletefile',
                'getcategory',
                'category',
                'delcategory',
                'categoryedit',
                'addcategory',
                'listdialog',
                'getcategoryselect',
                'analytics',
                'getinfo',
                'changecontent'
            ),
			'allow'=>array('')
		);
		B('Authenticate', $action);
	}

    public function show_list($where = array(), $params = array()) {

        $cur_time = time();
        if ($_REQUEST["field"] && $_REQUEST["search"]) {
            $condition = $_REQUEST['condition'];
            if (empty($condition)) {
                $condition = ($_REQUEST['field'] == "all"?'like':"is");
            }
            $params[] = "condition=" . $condition;
            $params[] = "field=" . trim($_REQUEST['field']);
            $search = empty($_REQUEST['search']) ? '' : trim($_REQUEST['search']);
            if ($search) {
                $params[] = "search=" . trim($_REQUEST['search']);
            }
            if (trim($_REQUEST['field']) == "all") {
                $field = implode("|", array_merge(all_filter_field("train"),array("train.keyword")));
            } else {
                if ($date_search = self::format_time_search("train", $_REQUEST['field'], $params)) {
                    $search = $date_search;
                    $this->search_date_field = $_REQUEST['field'];
                } else if ('state' == $_REQUEST["field"] && $search != '已取消') {
                    switch ($search) {
                        case '已开班': {
                            $qu['end_time'] = array('exp','=0 OR end_time > '.$cur_time);
                            $where['_complex'] = $qu;
                            $where['begin_time'] = array('exp','<'.$cur_time.' AND begin_time != 0 and state!="已取消"');
                            break;
                        }
                        case '已结束': {
                            $where['end_time'] = array('exp','<'.$cur_time.' and state!="已取消" and end_time!=0');
                            break;
                        }
                        case '未开班': {
                            $where['begin_time'] = array('exp','=0 and state!="已取消" or begin_time > '.$cur_time);
                            break;
                        }
                    }
                }
                $field = format_filter_field(trim($_REQUEST['field']));
            }
            $where = $this->field_where($field, $search, $condition);
        }

        $cat = $_GET['cat'] ? $_GET['cat']:0;
        $params[] = "cat=" . trim($cat);
        if ($cat != 0) {
            $bread_list = breadcrumb("train", $cat);
            array_pop($bread_list);
            $this->bread_list = $bread_list;
            $this->focus_category = M("currier_category")->where("currier_category_id=".$cat)->find();
            $allchild = cateallchild("train", $cat);
            $allchild[] = $cat;
        }

        $train_category = M("currier_category")->where("parentid=".$cat)->order("order_id asc")->select();
        if (!$train_category) {
            $train_category = M("currier_category")->where("parentid=".$this->focus_category['parentid'])->order("order_id asc")->select();
        }
        $this->currier_category = $train_category;

        if ($allchild) {
            $where['category'] = array("in", $allchild);
        }
        self::show_list_index_html($where, $params, "培训表");
    }

    public function format_state($value) {
        $cur_time = time();
        if ($value['state'] != '已取消'){
            if ($value['begin_time'] == 0 || $cur_time < $value['begin_time']) {
                $value['state'] = "未开班";
            } else if ($cur_time > $value['begin_time'] && ($cur_time < $value['end_time'] || $value['end_time'] == 0)){
                $value['state'] = "已开班";
            } else if ($cur_time > $value['end_time']) {
                $value['state'] = "已结束";
            }
        }
        return $value;
    }

    public function perfect_list_item($value, $export = false, $branchlock = false) {
        $value = self::format_state($value);

        $train_category = M("currier_category")->where("currier_category_id=".$value['category'])->find();
        $value['category'] = $train_category['name'];

        return parent::perfect_list_item($value, $export, $branchlock);
    }


    public function listDialog(){
        if ($this->isAjax() === false) {
            return $this->display("Train:listDialog");
        }

        $data_field = array(
            array(
                "field"=>"train_id",
                "order"=>"train_id"
            ),
            array(
                "field"=>"name",
                "order"=>"name"
            ),
            array(
                "field"=>"price",
                "order"=>"price"
            ),
        );
        $where = $this->parse_dialog_where();
        $this->ajaxReturn(make_data_list("TrainView", $where, $data_field, array($this, "format_dialog_item")),'JSON');
    }

    public function parse_dialog_where() {
        $where = parent::parse_dialog_where();
        if ($_REQUEST['corre']) {
            $where['corre'] = $this->_request("corre");
        }
        return $where;
    }

    public function format_dialog_item($val) {
        $val["train_id"] = array(
            "train_id"=>$val['train_id']
        );
        return $val;
    }

    public function update_train_course($train_id) {
        M('train_means')->where('train_id = %d', $train_id)->delete();

        $sum_period = 0;
        $teacherlist = $this->_request('teacher_id');
        $courselist = $this->_request('course_id');
        $train_date = $this->_request('train_date');
        $train_week = $this->_request('train_week');
        $train_timespan = $this->_request('train_timespan');
        $train_begin_time = $this->_request('train_begin_time');
        $train_end_time = $this->_request('train_end_time');
        $train_info = $this->_request('train_info');
        $train_desc = $this->_request('train_desc');

        foreach($courselist as $ck=>$cv) {
            $data = array(
                "train_id"=>$train_id,
                "course_id"=>$cv,
                "teacher_id"=>$teacherlist[$ck],
                "train_date"=>strtotime($train_date[$ck]),
                "train_week"=>$train_week[$ck],
                "train_timespan"=>$train_timespan[$ck],
                "train_begin_time"=>$train_begin_time[$ck],
                "train_end_time"=>$train_end_time[$ck],
                "train_info"=>$train_info[$ck],
                "train_desc"=>$train_desc[$ck],
            );

            if (empty($data["train_id"])  || empty($data["train_date"])) {
                continue;
            }
            M('train_means')->add($data);

            $course = D('course')->where(array("course_id"=>$cv))->find();
            if ($course) {
                $sum_period += $course['period_time'];
            }
        }

        if ($this->_request('sum_period') != "") {
            $sum_period = $this->_request('sum_period');
        }
        D('train')->where(array("train_id"=>$train_id))->setField("sum_period", $sum_period);
    }

	public function add(){
        if($this->isPost()) {
            if (!isset($_POST['name']) || $_POST['name'] == '') {
                alert_back('error', '必须设置课程名称');
            }

            $start_date = strtotime($_POST['begin_time']);
            $end_date = strtotime($_POST['end_time']);
            if ($start_date && $end_date < $start_date) {
                alert_back('error', '培训结束时间不能小于开始时间！');
            }

            $train_id = $this->submit_add();
            if ($train_id) {
                $this->update_train_course($train_id);
                $this->alert = parseAlert();
                if($_POST['refer_url']) {
                    alert('success', "新建课程成功", $_POST['refer_url']);
                }
                else{
                    $this->redirect(U("train/view", "id=".$train_id));
                }
            } else {
                $this->alert = parseAlert();
                alert('error', "新建培训失败", $_POST['refer_url']);
            }

        }else{
            $fields_group_list = product_field_list_html("add","train", array(), "basic");
            $this->fields_group = $fields_group_list;
            $this->courselist = json_encode(M('course')->select());
            $this->alert = parseAlert();
            $this->display();
        }
	}

    public function addcourse() {
        $train = D('TrainView')->where('train.train_id = %d',$this->_request('id'))->find();
        if (!$train) {
            alert('error', "没有这个课程",$_SERVER['HTTP_REFERER']);
        }
        $this->train_id = $train['train_id'];
        $this->display();
    }

    public function getteacher(){
        $course_id = $this->_request('id');
        $course_r = M('course')->where(array("course_id"=>$course_id))->select();
        if (!$course_r) {
            $this->ajaxReturn('',"无效的参数",0);
        } else {
            $course_list = $this->find_teacher($course_id);
            $this->ajaxReturn($course_list);
        }
    }

    public function getcourse() {
        $cat = $this->_request('cat');
        $course_list = M('course')->where(array("category"=>$cat))->select();
        if (!$course_list) {
            $this->ajaxReturn('',"无效的参数",0);
        } else {
            $this->ajaxReturn($course_list);
        }
    }

    function find_teacher($course_id) {
        $course_list = array();
        $teacher = D('TeacherView')->select();
        foreach($teacher as $k=>$v) {
            $course_arr = explode(chr(10),$v['course']);
            if (in_array($course_id, $course_arr)) {
                $course_list[] = $v;
            }
        }
        return $course_list;
    }

    public function gettraincategory() {
        $category_list = D('train')->group('category')->select();
        if (!$category_list) {
            $this->ajaxReturn('',"无效的参数",0);
        } else {
            $this->ajaxReturn($category_list);
        }
    }

	public function view(){
        $train_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $train_id) {
            alert('error', L('PARAMETER_ERROR'), U('train/index'));
        }

        $train = D('TrainView')->where('train.train_id = %d ', $_GET['id'])->find();
        $train['owner'] = D('RoleView')->where('role.role_id = %d', $train['role_id'])->find();

        $train = self::format_state($train);
        switch ($train['corre']) {
            case "product": {
                $train['corre'] = "雇员";
                break;
            }
            case "customer": {
                $train['corre'] = "客户";
                break;
            }
        }


        $cur_time = time();
        $train_valid_count = 0; $on_train_count = 0; $unon_train_count = 0;$comp_train_count = 0;
        $train_moeny = 0; $pay_train_moeny = 0;

        $train_count = D('TrainorderView')->where(array("train_id"=>$train_id))->count();
        $train_order = D('TrainorderView')->where(array("train_id"=>$train_id,'order_state'=>array('neq', '已撤销')))->select();
        foreach($train_order as $k=>$v) {
            if ($v['start_date'] == 0 || $cur_time < $v['start_date']) {
                $unon_train_count += 1;
            } else if ($cur_time > $v['start_date'] && ($cur_time < $v['end_date'] || $v['end_date'] == 0)){
                $on_train_count += 1;
            } else if ($cur_time > $v['end_date']) {
                $comp_train_count += 1;
            }
            $train_moeny += $v['price'];
            $pay_train_moeny += $v['pay_price'];
            $train_valid_count+=1;
        }

        $this->train_count = $train_count;
        $this->train_valid_count = $train_valid_count;

        $this->on_train_count = $on_train_count;
        $this->unon_train_count = $unon_train_count;
        $this->comp_train_count = $comp_train_count;

        $this->train_money = number_format($train_moeny, 2);
        $this->pay_train_moeny = number_format($pay_train_moeny, 2);
        $this->arrears_train_moeny = number_format($train_moeny - $pay_train_moeny, 2);

        $this->train_means = D("TrainMeansView")->where(array("train_id"=>$train_id))->order("train_date asc, train_begin_time asc")->select();
        $this->train_id = $train_id;
        $this->train = $train;
        $this->fields_group = product_field_list_show('train', $train);
        $this->alert = parseAlert();
        $this->refer_url= session("index_refer_url");
        $this->display();
	}

    public function vieworder(){
        $train_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $train_id) {
            alert('error', L('PARAMETER_ERROR'), U('train/index'));
        } else {
            $train_order_list = D('TrainorderView')->where(array('train_id'=>$train_id))->select();
            $train_count = 0;
            foreach ($train_order_list as $key=>$value) {
                $train_order_list[$key] = TrainorderAction::format_train_item($value);
                $train_count++;
            }
            $this->train_id = $train_id;
            $this->train_order_list = $train_order_list;
            $this->train_count = $train_count;
            $this->alert = parseAlert();
            $this->display();
        }
    }

	public function edit(){
        $train_id = $this->_request('id');
        $train = D('TrainView')->where('train.train_id = %d',$train_id)->find();
        if (!$train) {
            alert('error', "没有这个课程",$_SERVER['HTTP_REFERER']);
        }

        if($this->isPost()){

            if($this->submit_edit($train['train_id'])) {
                $this->update_train_course($train['train_id']);
                alert('success', "编辑课程成功", U('train/view', 'id='.$train['train_id']));
            } else {
                alert('error', "编辑课程失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            $train_means = D("TrainMeansView")
                ->where(array("train_id"=>$train_id))
                ->order("train_date asc, train_begin_time asc")->select();
            foreach($train_means as $tk=>$tv) {
                $teacher_list = $this->find_teacher($tv['course_id']);
                $train_means[$tk]['teacher_list'] = $teacher_list;
            }
            $course_list = M('course')->where(array("category"=>$train['category']))->select();
            $this->alert = parseAlert();;
            $this->train = $train;
            $this->model_id = $train['train_id'];
            $this->courselist = json_encode($course_list);
            $this->train_means = $train_means;
            $this->train_means_idx = count($train_means) + 1;
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->courselist = M('course')->select();
            $this->fields_group =  product_field_list_html("edit","train",$train);
            $this->display();
        }
	}

    public function deletemeans() {
        $means_id = $this->_request('id');
        if($means_id){
            M('trainMeans')->where('means_id = %d', $means_id)->delete();
            $this->ajaxReturn('','',1);
        }else{
            $this->ajaxReturn('',L('PARAMETER_ERROR'),0);
        }
    }

    public function delete(){
        $train_ids = $this->isPost() ? $_POST['train_id'] : $_GET['id'];
        if ($this->submit_delete($train_ids)) {
            $m_train_means = M('train_means');
            foreach ($train_ids as $value) {
                $m_train_means->where('train_id=', $value)->delete();
            }
            M("m_train")->where('mid=', $value)->delete();

            alert('success', "删除成功", U('train/index'));
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }


}
