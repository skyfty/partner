<?php 
	class TasksViewModel extends ViewModel{
		public $viewFields = array(
			'tasks'=>array(
                'league_id',
                'tasks_id',
                'subject',
                'due_date' ,
                'status',
                'priority',
                'send_email',
                'recurring',
                'description',
                '_type'=>'LEFT'
            ),
		);

        public function add_task($subject, $description, $owner_role_id, $module, $module_id, $start_time = null, $end_time = null, $priority = "一般性任务", $taskcb = array(), $creator_role_id = 0) {
            $task['status'] = "未处理";
            $task['subject'] = $subject;
            $task['priority'] = $priority;
            $task['description'] = $description;
            $task['taskcb'] = serialize($taskcb);
            $task['owner_role_id'] = $owner_role_id;
            $task['creator_role_id'] = $creator_role_id ? $creator_role_id : session("role_id");
            $task['create_date'] = $task['update_date'] =  time();
            $task['start_time'] = $start_time ? strtotime($start_time) : time();
            $task['end_time'] = $end_time ? strtotime($end_time) : time();
            $task['league_id'] = session("league_id");

            if (($task_id = M("task")->add($task)) === false) {
                return false;
            }
            return self::notify_role($task_id, $module, $module_id);
        }

        public function notify_role($task_id, $module, $id) {
            $module = $module ? $module : '';
            if($module != ''){
                switch ($module) {
                    case 'customer' : $m_r = M('RCustomerTask'); $module_id = 'customer_id'; break;
                    case 'product' : $m_r = M('RProductTask'); $module_id = 'product_id'; break;
                    case 'market' : $m_r = M('RMarketTask'); $module_id = 'market_id'; break;
                }
                if ($id && $m_r) {
                    $data[$module_id] = intval($id);
                    $data['task_id'] = $task_id;
                    $data['league_id'] = session("league_id");

                    $m_r->add($data);
                }
            }
            return $task_id;
        }
	} 