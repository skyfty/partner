<?PHP

class CastleAction extends BaseAction{
    public function enum_field_where($srcreq, $where = array(), $module = "", &$params = array()) {
        $req = array();
        if (isset($srcreq['draw'])) {

            if (isset($srcreq['group']) && $srcreq['group']) {
                $module_group = D(ucfirst($this->module).'Group')->where(array($this->module."_group_id"=>$srcreq['group']))->find();
                if ($module_group) {
                    if ($module_group['group_type'] == 0) {
                        $req = array_merge(unserialize($module_group['content']),$where);
                    } else {
                        $module_list = M(ucfirst($this->module).'Subgroup')->where(array($this->module."_group_id"=>$srcreq['group']))->select();
                        if ($module_list) {
                            $module_ids = array();
                            foreach($module_list as $v) {
                                $module_ids[] = $v[$this->module.'_id'];
                            }
                            $req[$this->module."_id"] = array("condition"=>"in", "value"=>$module_ids);
                        }
                    }
                }
            }

            if (isset($srcreq['multiple']) && $srcreq['multiple']) {
                foreach($srcreq['multiple'] as $v) {
                    if ($v['field']) {
                        if ($v['condition']) {
                            $req[$v['field']] = array("value"=>$v['value'], "condition"=>$v['condition']);
                        } else {
                            $req[$v['field']] = $v['value'];
                        }
                    }
                }
            }

            if (isset($srcreq['columns']) && $srcreq['columns']) {
                $colums =  count($srcreq['columns']);
                for($i = 0; $i <$colums; ++$i) {
                    $field = $srcreq['columns'][$i];
                    if ($field['searchable'] == "false" || ($field['search']['value'] == "" && isset($req[$field['data']]))) {
                        continue;
                    }
                    if ($field['search']['condition']) {
                        $req[$field['data']] = array("value"=>$field['search']['value'], "condition"=>$field['search']['condition']);
                    } else {
                        $req[$field['data']] = $field['search']['value'];
                    }
                }
            }

            if (isset($srcreq['search']) && $srcreq['search']) {
                $sfield = ($srcreq['search']['field']?$srcreq['search']['field']:"all");
                if ($srcreq['search']['condition']) {
                    $req[$sfield] = array(
                        "value"=>$srcreq['search']['value'],
                        "condition"=>$srcreq['search']['condition']
                    );
                    if ($srcreq['search']['model'])  {
                        $req[$sfield]['model'] =$srcreq['search']['model'];
                    }
                } else {
                    if (is_array($sfield)) {
                        $ff = array();
                        foreach($sfield as $f) {
                            if ($f['searchable'] == "false") {
                                continue;
                            }
                            $ff[] = $this->module.".".$f['data'];
                        }
                        $req[implode("|",$ff)]["value"] = $srcreq['search']['value'];
                    } else {
                        $req[$sfield]["value"] = $srcreq['search']['value'];
                        if ($srcreq['search']['model'])  {
                            $srcreq['model'] =$srcreq['search']['model'];
                        }
                    }
                }
            }

            if (isset($srcreq['forces'])) {
                foreach($srcreq['forces'] as $field=>$value) {
                    if (isset($value['condition'])) {
                        $req[$field] = array("value"=>$value['value'], "condition"=>$value['condition']);
                        if ($value['model'])  {
                            $req[$field]['model'] =$value['model'];
                        }
                    } else {
                        $req[$field] = $value;
                    }
                }
            }
            if (isset($srcreq['query']) || $srcreq['query']) {
                $where["_string"] = $srcreq['query'];
            }
        } else {
            $req = $srcreq;
        }
        return parent::enum_field_where($req, $where, $module, $params);
    }

    public function make_list_order(&$params = array()) {
        if (isset($_GET['order']) ) {
            $field_sort = array();
            foreach($_GET['order'] as $v) {
                $field = $_GET["columns"][$v['column']];
                $field_sort[] =$field['data']." ".$v["dir"];
            }
            $order = implode(",", $field_sort);
        } else {
            $order = parent::make_list_order($params);
        }
        return $order;
    }


    function perfect_list_item($value, $export = false) {
        return $this->perfect_model_list_item($this->getmodulename(),$value);
    }

    function perfect_model_list_item($module, $value) {
        $value["DT_RowIdName"] = $module."_id";
        $value["DT_ModelId"] = $value[$value["DT_RowIdName"]];
        $value["DT_RowId"] = $value["DT_RowIdName"]."_".$value["DT_ModelId"];
        return fill_model_field($module , $value);
    }

    function return_data($list, $totalRows) {
        $data = array(
            "draw" => $_GET['draw'],
        );
        $data["recordsTotal"] = $totalRows;
        $data["recordsFiltered"] = $totalRows;
        $data["data"] = $list?$list:array();
        $this->ajaxReturn($data);
    }

    public function show_group($where, $params) {
        $this->module = strtolower(MODULE_NAME);
        $this->module_group_id = $_GET['module_group_id'];

        $where['league_id'] = session('league_id');

        $m_module = D(ucfirst($this->module).'Group');
        $list = $m_module->order($this->make_list_order($params))->where($where)->select();
        if ($list) {
            foreach($list as $k=>$v) {
                $v["group_id"] = $v[$this->module.'_group_id'];
                if ($v['content']) {
                    $fields = array();
                    foreach(unserialize($v['content']) as $k1=>$v1) {
                        $v1['field'] = $k1;
                        $fields[] = $v1;
                    }
                    $v['content'] = $fields;
                }
                $v["group_type_name"] = ($v["group_type"] == 0 ? "固定组":"条件组");
                $list[$k] = $v;
            }
        }
        $this->ajaxReturn($list);
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
            $field_array = getMainFields($this->module);
            foreach($field_array as $k=>$v) {
                $field_array[$k] = format_field($v, $this->module);
            }
            $this->field_array = $field_array;

            $templurl = "./App/Tpl/Castle/Default/Index/tpl/".($_REQUEST['group_type'] == 0 ? "group_cond.html" : "group_fixed.html");
            $this->display($templurl);
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


    function split_module_file_id($data) {
        $idis = array();
        foreach($data as $v) {
            $idis[] = $v['images_id'];
        }
        return $idis;
    }

    function upload_module_file($module_id, $module, $data) {
        $m_module_images = M($module . 'Images');
        $field_list = M('Fields')->cache(true)->where(array('model' => $module,'form_type'=>'pic'))->select();
        foreach ($field_list as $field){
            $ids = $this->split_module_file_id($data[$field['field']]);
            $where = array(
                $module."_field"=>$field['field'],
                $module."_id"=>$module_id,
                "images_id"=>array("not in", $ids)
            );
            $deletefiles = $m_module_images->where($where)->select();
            foreach($deletefiles as $df) {
                @unlink($df['path']);
            }
            $m_module_images->where($where)->delete();
        }
        return true;
    }

    public function get_module_view() {
        $this->module = strtolower(MODULE_NAME);
        if ($_GET["dataview"]) {
            return D($_GET["dataview"]);
        }
        return D(ucfirst($this->module).'View');
    }

    public function show_list($where = array(), &$params = array()) {
        $this->assign_module_list($where, $params, "");
        $this->return_data($this->list, $this->page->totalRows);
    }
}
