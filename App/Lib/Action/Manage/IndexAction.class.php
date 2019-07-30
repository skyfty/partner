<?php 
// 
class IndexAction extends Action {

    public function _initialize(){
        if (NO_AUTHORIZE_CHECK === true || ACTION_NAME == "exhibition")
            return;
        $action = array(
            'permission'=>array(
                'namecard'
            ),
            'allow'=>array(
                'index',
                'widget_edit',
                'widget_delete',
                'widget_add',
                'calendar',
                'sortcharts',
                'timer_task',
                'area',
                'export_pdf',
                'eee',
                'photograph',
                'exhibition',
                'timer_task',
                'area',
                'eee',
                'checknav_releat_info',
                'exhibition',
            )
        );
        B('Authenticate', $action);
    }

    public function stafflog() {
        if (!$this->isAjax()) {
            return $this->display("stafflog"); // 输出模板
        }

        $where = array();
        if ($_GET['start_time'] || $_GET['end_time']) {
            $where['create_time'] =  array('between', make_time_between());
        }
        $where['league_id'] = session('league_id');

        if ($_GET['role_id']) {
            $where['role_id'] = $_GET['role_id'];
        }
        if ($_REQUEST['search'] && $_REQUEST['search']['value']) {
            $where['request|module|module|action|act|user_idcode|user_name|ip'] =  array('like', "%".$_REQUEST['search']['value']."%");
        }
        $this->ajaxReturn(make_datatable_list("StaffLog", $where, array($this, "format_staff_log")),'JSON');
    }

    public function format_staff_log($v) {
        $v['create_time_show'] = toDate($v['create_time']);
        $owner_role = getUserByRoleId($v['role_id']);
        $v['role_show'] = $owner_role['user_name'];
        $v['request_show'] = str_cut($v['request'], 100);

        return $v;
    }

    public function checknav_releat_info() {
        $data = array();

        $where = array(
            "origin"=>array("in",array("手机注册","网页注册")),
            "owner_role_id"=>""
        );
        $data['product_count'] = M("product")->where($where)->count();
        $data['customer_count'] = M("customer")->where($where)->count();

        $data['commiss_count'] = M("commiss")->where(
            array(
                "owner_role_id"=>session("role_id"),
                "communicate"=>"待处理"
            ))->count();

        $defaultinfo = F('defaultinfo'.session('league_id'));
        $commiss_remind_limit = $defaultinfo['commiss_remind_limit'];
        if ($commiss_remind_limit) {
            $data['commiss_genjin_count'] = M("commiss")->where(
                array(
                    "owner_role_id"=>session("role_id"),
                    "communicate"=>"跟进中",
                    "_string"=>'last_log_time!="" and TO_DAYS(NOW()) - TO_DAYS(from_unixtime(last_log_time)) > '.$commiss_remind_limit
                ))->count();
        }

        $this->ajaxReturn($data);
    }

	public function index(){
		$dashboard = M('User')->where('user_id = %d', session('user_id'))->getField('dashboard');
		$widget = unserialize($dashboard);		
		$this->widget = $widget;

		$where['department'] = array('like', '%('.session('department_id').')%');
		$where['status'] = array('eq', 1);
		$this->announcement_list = M('announcement')->where($where)->order('order_id')->select();
        cookie('alert', null);
        $this->alert = parseAlert();
		$this->display();
	}

	
	/**
	 * @author 		: myron
	 * @function	: 首页日历获取任务和日程数据
	 * @return		: 任务和日程
	 **/
	public function calendar(){
		$role_id = session('role_id');
		$month_start = strtotime(date('Y-m-1',time()));	//本月开始时间
		$month_end = $month_start+(30*86400)-1;			//本月开始时间
		$date_begin = $month_start - 86400*6;			//本月1号6天前(日历上最多显示1号前六天)
		$date_end = $month_end + 86400*14;				//本月最后一天14天后(日历上最多显示月末14天后)

		//任务
		$taskData = array();
		$where['owner_role_id']  = array('like', "%,$role_id,%");
		$map['_complex'] = $where;
		$map['start_time'] = array('egt', $date_begin);
		$map['status'] = array('neq', '完成');
		$map['isclose'] = array('eq', 0);
		$task = M('task')->field('task_id, subject, create_date, start_time, end_time, "task" as type')->where($map)->order('create_date asc')->select();
		foreach($task as $k=>$v){
            $day_end = 0;
            //整个月
			for($day_begin = $date_begin; $day_begin <= $date_end; $day_begin += 86400){
                $day_end = $day_begin + 86400;
				//每一天
                if($v['start_time'] < $day_end && $v['end_time'] >= $day_begin){
					$url = U('task/index','field=subject&condition=is&act=search&search='.urlencode($v['subject']));
					$taskData[] = array(
						'title'=> '<a href="'.$url.'" target="_blank">'.$v['subject'].'</a>',
						'description'=>'',
						'datetime'=>$day_begin,
						'type'=>'task'
					);
				}
			}
		}
		
		//日程
		$eventData = array(); 
		$m_event = M('event');
		$condition['owner_role_id']  = array('eq', $role_id);
		$condition['start_date'] = array('egt', $date_begin);
		$condition['is_deleted'] = array('eq', 0);
		$condition['isclose'] = array('eq', 0);
		
		$event = $m_event->field('event_id,subject, start_date, end_date, "event" as type')->where($condition)->order('create_date desc')->select();
		foreach($event as $k=>$v){
			$j = 0;
			for($i=$date_begin;$i<=$date_end;$i+=86400){
				$j=$i+86400;
				//每一天
				if($v['start_date'] < $j && $v['end_date'] >= $i){
					$url = U('event/index','field=subject&condition=is&act=search&search='.urlencode($v['subject']));
					$eventData[] = array(
						'title'=>'<a href="'.$url.'" target="_blank">'.$v['subject'].'</a>',
						'description'=>'',
						'datetime'=>$i,
						'type'=>'event'
					);
				}
			}
		}

		$calendarData = array_merge($taskData, $eventData);
		$this->ajaxReturn($calendarData,'success',1);
	}


    public function area() {
        $module = M('Area');
        $id = intval($_REQUEST['id']);
        $level= intval($_REQUEST['level']);
        $provinceid= intval($_REQUEST['provinceid']);
        $cityid= intval($_REQUEST['cityid']);
        $areaid= intval($_REQUEST['areaid']);

        $province_str='<option value="0">选择省份</option>';
        $city_str='<option value="0">选择城市</option>';
        $area_str='<option value="0">选择区域</option>';
        $str ='';

        $r = $module->where("parentid=".$id)->select();
        foreach($r as $key=>$pro){
            $selected = ( $pro['id']==$provinceid) ? ' selected="selected" ' : '';
            $str .='<option value="'.$pro['id'].'"'.$selected.'>'.$pro['name'].'</option>';
        }
        if($level==0){
            $province_str .=$str;
        }elseif($level==1){
            $city_str .=$str;
        }elseif($level==2){
            $area_str .=$str;
        }
        $str='';
        if($provinceid){

            $rr = $module->where("parentid=".$provinceid)->select();
            foreach($rr as $key=>$pro){
                $selected = ($pro['id']==$cityid) ? ' selected="selected" ' : '';
                $str .='<option value="'.$pro['id'].'"'.$selected.'>'.$pro['name'].'</option>';
            }
            $city_str .=$str;
        }
        $str='';
        if($cityid){
            $rrr = $module->where("parentid=".$cityid)->select();
            foreach($rrr as $key=>$pro){
                $selected = ($pro['id']==$areaid) ? ' selected="selected" ' : '';
                $str .='<option value="'.$pro['id'].'"'.$selected.'>'.$pro['name'].'</option>';
            }
            $area_str .=$str;
        }

        $res=array();
        $res['data']= $rs ? 1 : 0 ;
        $res['province'] =$province_str;
        $res['city'] =$city_str;
        $res['area'] =$area_str;
        echo json_encode($res); exit;
        exit;
    }

    public function get_today_time() {
        if (!$_REQUEST['start_date']) {
            $this->ajaxReturn("", "", 0);
        }
        $cur_date = strtotime(date("Y-m-d"));
        $start_date = strtotime($this->_request("start_date"));

        if ($start_date <= $cur_date) {
            $this->ajaxReturn("", "", 0);
        }

        $where = array(
            'product_id'=>$this->_request("product_id"),
            'start_date'=>array("elt", $start_date),
            'end_date'=>array(array('gt',$start_date),array('eq',0),'or')
        );
        $this->ajaxReturn(M('event')->where($where)->find(), "", 1);
    }

    public function timer_task(){
        define("NO_AUTHORIZE_CHECK", true);
        ini_set("memory_limit","-1");
        if ($_REQUEST["time"]) {
            $cur_date = strtotime($this->_request("time"));
        } else {
            $cur_date = time();
        }
        A("Manage/Market")->advance_status_ergodic($cur_date);
        A("Manage/Cultivate")->advance_status_ergodic($cur_date);

        //调度事件任务
        self::dispatch_timing_task(strtotime(date("Y-m-d", $cur_date)));

        //调度订单任务
        //A("Manage/Business")->advance_status_ergodic($cur_date);

        file_put_contents("time_task.txt", date("Y-m-d H:i:s", time()));
        exit("ok");
    }

    public static function dispatch_timing_task($cur_begin_date) {
        $cur_time = time();
        foreach (M("product")->order("product_id asc")->select() as $product) {
            $where = array(
                'product_id' => $product['product_id'],
                'start_date' => array("elt", $cur_begin_date),
                'end_date' => array(array('egt', $cur_begin_date), array('eq', 0), 'or'),
                'workstate_id'=>array("in", array("请假","公司培训","司外订单"))
            );
            $today_event = M('event')->where($where)->find();
            $data = array();
            if ($today_event && $product['leave_state'] != "请假中") {
                $data["leave_state"] = "请假中";
            } elseif (!$today_event && $product['leave_state'] == "请假中") {
                $data["leave_state"] = "请假过期";
            }

            $data["insurance"] = product_insurance_show($product['product_id']);

            $market_product_where = array(
                "product_id"=>$product['product_id'],
                "_string"=>"TO_DAYS(from_unixtime(5k_a_market_product.real_end_time))=TO_DAYS(NOW())");
            $m_market_product= M("market_product")->where($market_product_where)->find();
            if ($m_market_product) {
                $data["onlydown"] = $m_market_product['market_product_id'];
            } else {
                $data["onlydown"] = 0;
            }
            M("product")->where(array("product_id"=>$product['product_id']))->setField($data);
        }

        if ($cur_time > strtotime(date("Y-m-d 23:40:00", time()))) {
            M("product")->where(array("dispatch_flag"=>1))->setField('dispatch_flag', 0);
        }
    }

    public function export_pdf() {
        $model = $_GET['model'];
        $id = $_GET['id'];
        if (!$model || !$id) {
            exit(0);
        }
        $export_action = $_GET["ea"] ? $_GET['ea'] : "exportprint";
        $tmpfname = tempnam("tmp", "export_pdf").".pdf";

        $export_url = $this->_server('HTTP_HOST').U($model."/".$export_action, array("id"=>$id));
        exec("wkhtmltopdf '".$export_url. "' ". $tmpfname);

        header('Content-Type: application/pdf');
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.filesize($tmpfname));
        readfile($tmpfname);
    }


    public function namecard() {
        $id = $this->_request("id");
        if (!$id) {
            $this->assign('jumpUrl',URL(''));
            $this->error ("参数错误");
        }

        $product = D("Manage/ProductView")->where(array("product_id"=>$id))->find();
        $skill_list = array();
        $skill_where = array(
            "skill_data.product_id"=>$product['product_id'],
        );
        foreach(D('Manage/SkillView')->where($skill_where)->order("skill_data.category_id asc")->select() as $v) {
            $skill_list[$v['category_id']] = $v;
            $category_id = $v['category_id'];
        }
        if ($product['sign_style']) {
            $product['sign_style'] = proudct_category_map( $product['raw_sign_style']=$product['sign_style']);
        }
        $category_id = $_REQUEST['cid']?$_REQUEST['cid']:(array_key_exists($product['raw_sign_style'],$skill_list)?$product['raw_sign_style']:$category_id);
        $this->current_category_id = $category_id;
        $this->current_skill = $skill_list[$category_id];
        $product['levelimgs'] = format_plevel($this->current_skill['level'], false);

        $product['skill'] = $skill_list;
        $product['workyear'] = year(time()- $product['yearofstart']);
        $product['age'] = birthday2age(date("Y-m-d", $product['birthday']));
        $product['images'] = M('productImages')->where('product_id = %d and is_main = 1', $product['product_id'])->find();
        $product['certificate_pic'] = M('productImages')->where( array('product_id'=>$product['product_id'],'product_field'=>'certificate_pic'))->select();
        $product['health_pic'] = M('productImages')->where( array('product_id'=>$product['product_id'],'product_field'=>'health_pic'))->select();
        $product['backdrop_pic'] = M('productImages')->where( array('product_id'=>$product['product_id'],'product_field'=>'backdrop_pic'))->select();

        $product['certificate'] = explode(chr(10),$product['certificate']);
        array_shift($product['certificate']);
        $product['livephoto'] = M('productImages')->where( array('product_id'=>$product['product_id'],'product_field'=>'photo'))->select();

        $where = array(
            "home_check"=>0,
            "product_category.category_id"=>$this->current_skill['category_id'],
            "market_product_evaluate.product_id"=>$product['product_id']
        );
        $m_market_product_evaluate = D("Manage/MarketProductEvaluateView")->where($where)->select();
        if ($m_market_product_evaluate) {
            foreach($m_market_product_evaluate as $k=>$v) {
                $m_market_product_evaluate[$k]['picture'] = M('market_product_evaluate_images')->where( array('market_product_evaluate_id'=>$v['market_product_evaluate_id'],'product_field'=>'picture'))->select();
                $m_market_product_evaluate[$k]['sumday'] = ceil(day($v['real_end_time']-$v['real_start_time']));
            }
            $product['evaluate_info'] = $m_market_product_evaluate;
        }
        $this->league = M("league")->where(array('league_id'=> $product['league_id']))->find();

        $this->m_product = $product;
        $this->display();
    }
}