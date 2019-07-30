<?php 
/**
 * User Related
 * 用户相关模块
 *
 **/ 

class RoleAction extends Action {
	public function listDialog() {
        $status = isset($_GET['status']) ? intval($_GET['status']) : 1 ;
        $where['status'] = $status;
        $d_user = D('UserView'); // 实例化User对象
        $this->list = $d_user->where($where)->Page('0,7')->select();
        $count =  $d_user->where($where)->count();
		$this->count = $count;
		$this->total = $count%7 > 0 ? ceil($count/7) : $count/7;
		$this->display("User:rolelistDialog");
	}

	
	public function changeContent(){
        $where = array("status"=>1);
        if ($_REQUEST["field"]) {
            $where['user.name'] = array('like', '%'.trim($_GET['field']).'%');
        }
        $p = !$_REQUEST['p'] || $_REQUEST['p']<=0 ? 1 : intval($_REQUEST['p']);
        $d_user = D('UserView');
        $count = $d_user->where($where)->count();// 查询满足要求的总记录数
        if ($count) {
            $list = $d_user->where($where)->Page($p.',7')->select();
            $data['list'] = $list;;
        }

        $data['p'] = $p;
        $data['count'] = $count;
        $data['total'] = $count%7 > 0 ? ceil($count/7) : $count/7;
        $this->ajaxReturn($data,"",1);
	}
}