<?php

class SessionRuleBehavior extends Behavior {

    public function run(&$params){
        if (defined("SESSION_ROLE_ID") && !session('role_id')) {
            $user = M('user')->where(array('user_id' => intval(SESSION_ROLE_ID)))->find();
            if ($user) {
                $d_role = D('RoleView');
                $role = $d_role->where('user.user_id = %d', $user['user_id'])->find();
                if($user['category_id'] == 1){
                    session('admin', 1);
                }
                session('role_id', $role['role_id']);
                session('position_id', $role['position_id']);
                session('role_name', $role['role_name']);
                session('department_id', $role['department_id']);
                session('name', $user['name']);
                session('user_id', $user['user_id']);
            }
        }
    }
}