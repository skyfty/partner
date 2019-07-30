<?PHP 
class PromptAction extends BaseAction{
	public function add(){
        if($this->isPost()) {
            if (!isset($_POST['subject']) || $_POST['subject'] == '') {
                $this ->error(L('必须设置名称'));
            }

            $prompt_id = $this->submit_add();
            if ($prompt_id) {
                $this->reset_model_prompt($prompt_id);
                if($_POST['refer_url']) {
                    alert('success', "新建提醒成功", $_POST['refer_url']);
                }else{
                    alert('success', "新建提醒成功", $_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->alert = parseAlert();
                alert('error', "新建提醒失败", $_POST['refer_url']);
            }
        }else{
            $this->fields_group = product_field_list_html("add","prompt", array(), "basic");;
            $this->model = $this->_request("model");
            $this->model_id = $this->_request("model_id");
            $this->alert = parseAlert();
            $this->refer_url= refer_url('refer_add_url');
            $this->display();
        }
	}

	public function view(){
        $prompt_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (0 == $prompt_id) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        } else {
            $prompt = D('PromptView')->where('prompt.prompt_id = %d ', $prompt_id)->find();
            $prompt['owner'] = D('RoleView')->where('role.role_id = %d', $prompt['creator_role_id'])->find();
            $this->prompt_id = $prompt_id;
            $this->prompt = $prompt;
            $this->fields_group = product_field_list_show('prompt', $prompt);
            $this->alert = parseAlert();
            $this->refer_url= refer_url('refer_view_url');
            $this->display();
        }
	}

	public function edit(){
        $prompt = D('PromptView')->where('prompt.prompt_id = %d',$this->_request('id'))->find();
        if (!$prompt) {
            alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
        }
        $this->prompt = $prompt;

        if($this->isPost()){
            if($this->submit_edit($prompt['prompt_id'])) {
                $this->reset_model_prompt($prompt);
                alert('success', "编辑提醒成功", U('prompt/view', 'id='.$prompt['prompt_id']));
            } else {
                alert('error', "编辑提醒失败", $_SERVER['HTTP_REFERER']);
            }
        }else{
            $this->alert = parseAlert();;
            $this->refer_url=$_SERVER['HTTP_REFERER'];
            $this->fields_group =  product_field_list_html("edit","prompt",$prompt);
            $this->display();
        }
	}

    public function reset_model_prompt($prompt) {
        if (!is_array($prompt)) {
            $prompt = M("prompt")->where('prompt_id='.$prompt)->find();
        }
        $where = array(
            'state'=>"开启",
            'prompt_time'=>array("ELT", time()),
            'model'=>$prompt['model'],
            'model_id'=>$prompt['model_id'],
        );
        $prompt_cnt = M("prompt")->where($where)->count();
        M($prompt['model'])->where($prompt['model']."_id=".$prompt['model_id'])->setField("prompt_renind", $prompt_cnt);
    }

    public function delete(){
        $prompt_id =  $_GET['id'];
        $prompt = M("prompt")->where('prompt_id='.$prompt_id)->find();
        if ($this->submit_delete($prompt_id)) {
            $this->reset_model_prompt($prompt);
            if($_REQUEST['refer_url']) {
                alert('success', "删除成功", $_REQUEST['refer_url']);
            }else{
                alert('success', "删除成功" ,U($this->module.'/index'));
            }
        } else {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
    }
}
