<?php

function menu_sort($v1, $v2) {
    return $v1['listorder'] - $v2['listorder'];
}

class NavigationWidget extends Widget 
{
	public function render($data)
	{
		$user = M('User');
		$navigation = M('Navigation');
		$permission = M('Permission');
		$value = $user->where("user_id = %d", session('user_id'))->getField('navigation');
		$menu = unserialize($value);

		$where['league_id'] = session('league_id');
		$where['state'] = 1;

		$list = $navigation->where($where)->cache(true)->order("listorder desc")->select();
		$controls = $permission->where('position_id = %d', session('position_id'))->getField('url', true);
		foreach($list AS $value) {
			if(empty($value['module']) or in_array(strtolower($value['module']).'/index', $controls) or session('?admin')){
				$navigationList[$value['id']] = $value;
			}
		}
		foreach($menu AS $k=>$v) {
			foreach($v AS $kk=>$vv) {
				if (isset($navigationList[$vv])) {
					$menu[$k][$kk] = $navigationList[$vv];
					unset($navigationList[$vv]);
				} else {
					unset($menu[$k][$kk]);
				}
			}
		}
		
		foreach($navigationList AS $value) {
			$menu[$value['postion']][] = $value;
		}

        foreach($menu AS $k=>$value) {
            usort($value, 'menu_sort');
            $menu[$k] = $value;
        }

		$menu['simple'] =unserialize(M('User')->where('user_id = %d', session('user_id'))->getField('simple_menu'));
		return $this->renderFile ("index", $menu);
	}
}
