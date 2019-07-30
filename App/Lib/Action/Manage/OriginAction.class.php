
<?php 
class OriginAction extends BaseAction {

    public function tree(){
        $origin_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $origin_id) {
            alert('error', L('PARAMETER_ERROR'), U('origin/index'));
        }
        $origin = M('origin')->where('origin_id = %d ', $origin_id)->find();
        if (!$this->isAjax()) {
            $this->origin = $origin;
            $this->alert = parseAlert();
            $this->refer_url= refer_url('refer_view_url');
            return $this->display();
        }

        $children = array();
        self::origin_tree($origin, 0, array("origin_id"=>$origin_id), $children);
        self::nnaa_origin_tree($origin, $children);
        $this->ajaxReturn(array(array("id"=>0,'state'=>array('opened'=>true, 'locked'=>true), "icon"=> "/Public/img/admin_gohome.gif", "text"=>$origin['name'], "children"=>$children)));
    }


    function origin_tree($origin, $bc, $where = array(), &$children = array()) {
        $where["parentid"]=$bc;
        foreach(M("origin_category")->where($where)->order("order_id asc")->select() as $v) {
            $origin_cat = self::general_origin_tree($origin, $v['origin_category_id'], $v['role_id']);
            self::origin_tree($origin, $v['origin_category_id'], $where,  $origin_cat['children']);
            $children[] = $origin_cat;
        }
    }

    function nnaa_origin_tree($origin, &$children = array()) {
        $nnbrole = M("origin_category")->where(array("origin_id"=>$origin['origin_id']))->getField("origin_category_id", true);
        $nnbrole[] = 0;
        $where = array(
            "origin_id"=>$origin['origin_id'],
            "parentid"=>array("not in", $nnbrole)
        );
        foreach(M("origin_category")->where($where)->order("order_id asc")->select() as $v) {
            $children[] = self::general_origin_tree($origin, $v['origin_category_id']);;
        }
    }

    static function general_origin_tree($origin, $origin_category_id) {
        $origin_cat = array(
            "id"=>$origin_category_id,
            "text"=>$origin['name'],
            "state"=>array("opened"=>true),
            "children"=>array()
        );
        return $origin_cat;
    }


    static function origin_update($origin_id, $role_id, $owner_role_id) {
        $role = M("origin_category")->where(array("role_id"=>$role_id))->find();
        $owner_role = M("origin_category")->where(array("role_id"=>$owner_role_id, "origin_id"=>$origin_id))->find();
        if ($role) {
            $data = array(
                'parentid'=>$owner_role?$owner_role['origin_category_id']:0,
                'origin_id'=>$origin_id,
            );
            M('origin_category')->where('role_id = %d', $role_id)->setField($data);
        } else {
            $origin_data = array(
                'parentid'=>$owner_role?$owner_role['origin_category_id']:0,
                "order_id"=>0,
                "origin_id"=>$origin_id,
                "role_id"=>$role_id,
                "create_time"=>time(),
            );
            M("origin_category")->add($origin_data);
        }
    }

    public function employee_add(){
        $origin_id = isset($_REQUEST['origin_id']) ? intval($_REQUEST['origin_id']) : 0;
        if (0 == $origin_id || !$_REQUEST['role_id']) {
            $this->ajaxReturn("错误的参数");
        }
        $origin_belong = M("origin_category");
        $origin_role = $origin_belong->where(array("origin_id"=>$origin_id, "role_id"=>$_REQUEST['role_id']))->find();
        if ($origin_role){
            $this->ajaxReturn("这个员工已经在此门店里了");
        }
        $origin_role = $origin_belong->where(array("role_id"=>$_REQUEST['role_id']))->find();
        if ($origin_role) {
            $this->ajaxReturn("这个员工在另外的门店里， 请先将其移除");
        }

        $_POST['create_time'] = time();
        if($origin_belong->create() === false || !($origin_category_id = $origin_belong->add())) {
            $this->ajaxReturn("错误的参数");
        }

        $staff = D('StaffView')->where('user.role_id = %d',$_REQUEST['role_id'])->find();
        if ($staff) {
            $origin = M("origin_category")->where(array("origin_category_id"=>$this->_request("parentid")))->find();
            $data = array(
                "origin_id"=>$origin_id,
            );
            if ($origin) {
                $data['owner_role_id'] =$origin['role_id'];
            }
            M("staff")->where(array("staff_id"=>$staff['staff_id']))->setField($data);
        }

        $role_info = getUserByRoleId($_POST['role_id']);
        $role_info['node_id'] = $origin_category_id;
        $role_info['user_icon'] = "/Public/img/admin_img.png";
        $this->ajaxReturn($role_info);
    }

    public function employee_remove(){
        if (!$_REQUEST['nodeid'] || !$_REQUEST['origin_id']) {
            $this->ajaxReturn(null);
        }
        $nodeid = $this->_request("nodeid");

        $selfwhere = array("origin_category_id"=>$nodeid);
        $origin = M("origin_category")->where($selfwhere)->find();

        $bcids = M("origin_category")->where(array("parentid"=>$this->_request("nodeid")))->getField("origin_category_id", true);
        M("origin_category")->where(array("origin_category_id"=>array("in", $bcids)))->setField("parentid", $origin['parentid']);

        M("origin_category")->where($selfwhere)->delete();
        $staff = D('StaffView')->where('user.role_id = %d',$origin['role_id'])->find();
        if ($staff) {
            $data = array(
                "origin_id"=>0,
            );
            M("staff")->where(array("staff_id"=>$staff['staff_id']))->setField($data);
        }
        $this->ajaxReturn($origin);
    }

}