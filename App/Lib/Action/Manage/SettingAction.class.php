<?php

class SettingAction extends Action{
	public function _initialize(){
		$action = array(
			'permission'=>array('clearcache'),
			'allow'=>array('close',
                'getbusinessstatuslist',
                'getleadsstatuslist',
                'getindustrylist',
                'getsourcelist',
                'boxfield',
                'mapdialog',
                'census_fields',
                'getsmstemplate',
                'smstemplate',
                'certificate_fields'
            )
		);
		B('Authenticate',$action);
	}
	
	public function index(){
		$this->redirect('setting/defaultInfo');
	}
	public function openDebug(){
		$file_path = CONF_PATH.'app_debug.php';
		$result = file_put_contents($file_path, "<?php \n\r define ('APP_DEBUG',true);");
		if($result){
			$this->ajaxReturn(1,'',1);	
		}else{
			$this->ajaxReturn(1,'',2);
		}
	}
	public function closeDebug(){
		$file_path = CONF_PATH.'app_debug.php';
		$result = file_put_contents($file_path, "<?php \n\r define ('APP_DEBUG',false);");
		if($result){
			$this->ajaxReturn(1,'',1);
		}else{
			$this->ajaxReturn(1,'',2);
		}
	}
	public function clearCache(){
		if($this->clear_Cache()){
			$this->ajaxReturn(1,'',1);
		}else{
			$this->ajaxReturn(1,'',0);
		}
		
	}
	protected function clear_Cache(){
		deldir(RUNTIME_PATH);
		return true;
	}

    public function assign_list_page($parameter, $count, $pc=15) {
        import('@.ORG.Page');// 导入分页类
        $Page = new Page($count,$pc);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->parameter = $parameter;
        $this->assign('page',$Page->show());// 赋值分页输出
        return $Page;
    }

	public function industry(){
		$m_status = M('InfoIndustry');
		$this->industryList = $m_status->order('order_id')->select();
		$this->alert=parseAlert();
		$this->display();
	}
	
	public function industryAdd(){
		if ($this->isPost()) {
			$m_status = M('InfoIndustry');
			if($m_status->create()){
				if ($m_status->add()) {
					alert('success', L('SUCCESSFULLY ADDED'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('THE STATE NAME ALREADY EXISTS'), $_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error', L('ADD FAILED'), $_SERVER['HTTP_REFERER']);
			}
		} else {
			$this->alert=parseAlert();
			$this->display();
		}
	}
	
	public function industryEdit(){
		$m_industry = M('InfoIndustry');
		if ($this->isGet()) {
			$industry_id = intval(trim($_GET['id']));
			$this->industry = $m_industry->where('industry_id = %d', $industry_id)->find();
			$this->display();
		} else {
			if ($m_industry->create()) {
				if ($m_industry->save()) {
					alert('success', L('SUCCESSFULLY EDIT'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('DATA UNCHANGED'), $_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error', L('EDIT FAILED'), $_SERVER['HTTP_REFERER']);
			}
		}
	}
	
	public function industryDelete(){
		if ($_POST['industry_id']) {
			$id_array = $_POST['industry_id'];
			if (M('customer')->where('industry_id in (%s)', implode(',', $id_array))->select() || M('leads')->where('industry_id in (%s)', implode(',', $id_array))->select()) {
				alert('error', L('DELETE FAILED STATUS'), $_SERVER['HTTP_REFERER']);
			} else {
				if (M('InfoIndustry')->where('industry_id in (%s)', implode(',', $id_array))->delete()) {
					alert('success', L('SUCCESSFULLY DELETE'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('EDIT FAILED'), $_SERVER['HTTP_REFERER']);
				}
			}
		} elseif($_POST['old_id']){
			$old_id = intval($_POST['old_id']);
			$new_id = intval($_POST['new_id']);
			if (M('InfoIndustry')->where('industry_id = %d', $old_id)->delete()) {
				M('Leads')->where('industry_id = %d', $old_id)->setField('industry_id', $new_id);
				M('Customer')->where('industry_id = %d', $old_id)->setField('industry_id', $new_id);
				alert('success', L('SUCCESSFULLY DELETE'), $_SERVER['HTTP_REFERER']);
			} else {
				alert('error', L('EDIT FAILED'), $_SERVER['HTTP_REFERER']);
			}
		} else {
			$old_id = intval(trim($_GET['id']));
			$this->old_id = $old_id;
			$this->industryList = M('InfoIndustry')->where('industry_id <> %d', $old_id)->select();
			$this->display();
		}
	}
	
	public function industrySort(){
		if ($this->isGet()) {
			$m_industry = M('InfoIndustry');
			$a = 0;
			foreach (explode(',', $_GET['postion']) as $v) {
				$a++;
				$m_industry->where('industry_id = %d', $v)->setField('order_id',$a);
			}
			$this->ajaxReturn('1', L('SUCCESSFULLY EDIT'), 1);
		} else {
			$this->ajaxReturn('0', L('EDIT FAILED'), 1);
		}
	}


    public function marketStatus(){
        $m_status = M('MarketStatus');
        $this->statusList = $m_status->where(array("model"=>$this->_request("model")))->order('order_id')->select();
        $this->alert=parseAlert();
        $this->display();
    }



    public function marketStatusAdd(){
        if ($this->isPost()) {
            $_POST['model'] = "ss";
            $m_status = M('MarketStatus');
            if($m_status->create()){
                if ($m_status->add()) {
                    alert('success', L('SUCCESSFULLY ADDED'), $_SERVER['HTTP_REFERER']);
                } else {
                    alert('error', L('THE STATE NAME ALREADY EXISTS'), $_SERVER['HTTP_REFERER']);
                }
            } else {
                alert('error', L('ADD FAILED'), $_SERVER['HTTP_REFERER']);
            }
        } else {
            $this->alert=parseAlert();
            $this->display();
        }
    }

    public function marketStatusEdit(){
        $m_status = M('MarketStatus');
        if ($this->isGet()) {
            $status_id = intval(trim($_GET['id']));
            $this->status = $m_status->where('status_id = %d', $status_id)->find();
            $this->display();
        } else {
            if ($m_status->create()) {
                if ($m_status->save()) {
                    alert('success', L('SUCCESSFULLY EDIT'), $_SERVER['HTTP_REFERER']);
                } else {
                    alert('error', L('DATA UNCHANGED'), $_SERVER['HTTP_REFERER']);
                }
            } else {
                alert('error', L('EDIT FAILED'), $_SERVER['HTTP_REFERER']);
            }
        }
    }


    public function marketStatusSort(){
        if ($this->isGet()) {
            $status = M('MarketStatus');
            $a = 0;
            foreach (explode(',', $_GET['postion']) as $v) {
                $a++;
                $status->where('status_id = %d', $v)->setField('order_id',$a);
            }
            $this->ajaxReturn('1', L('SUCCESSFULLY EDIT'), 1);
        } else {
            $this->ajaxReturn('0', L('EDIT FAILED'), 1);
        }
    }


	public function leadsStatus(){
		$m_status = M('leadsStatus');
		$this->statusList = $m_status->order('order_id')->select();
		$this->alert=parseAlert();
		$this->display();
	}
	
	public function leadsStatusAdd(){
		if ($this->isPost()) {
			$m_status = M('leadsStatus');
			if($m_status->create()){
				if ($m_status->add()) {
					alert('success', L('SUCCESSFULLY ADDED'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('THE STATE NAME ALREADY EXISTS'), $_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error', L('ADD FAILED'), $_SERVER['HTTP_REFERER']);
			}
		} else {
			$this->alert=parseAlert();
			$this->display();
		}
	}
	
	public function leadsStatusEdit(){
		$m_status = M('leadsStatus');
		if ($this->isGet()) {
			$status_id = intval(trim($_GET['id']));
			$this->status = $m_status->where('status_id = %d', $status_id)->find();
			$this->display();
		} else {
			if ($m_status->create()) {
				if ($m_status->save()) {
					alert('success', L('SUCCESSFULLY EDIT'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('DATA UNCHANGED'), $_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error', L('EDIT FAILED'), $_SERVER['HTTP_REFERER']);
			}
		}
	}
	
	public function leadsStatusDelete(){
		if ($_POST['status_id']) {
			$id_array = $_POST['status_id'];
			if (M('leads')->where('status_id in (%s)', implode(',', $id_array))->select()) {
				alert('error', L('DELETE FAILED STATUS'), $_SERVER['HTTP_REFERER']);
			} else {
				if (M('leadsStatus')->where('status_id in (%s)', implode(',', $id_array))->delete()) {
					alert('success', L('SUCCESSFULLY DELETE'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('DELETE FAILED'), $_SERVER['HTTP_REFERER']);
				}
			}
		} elseif($_POST['old_id']){
			$old_id = intval($_POST['old_id']);
			$new_id = intval($_POST['new_id']);
			if (M('leadsStatus')->where('status_id = %d', $old_id)->delete()) {
				M('leads')->where('status_id = %d', $old_id)->setField('status_id', $new_id);
				alert('success', L('SUCCESSFULLY DELETE'), $_SERVER['HTTP_REFERER']);
			} else {
				alert('error', L('DELETE FAILED'), $_SERVER['HTTP_REFERER']);
			}
		} else {
			$old_id = intval(trim($_GET['id']));
			$this->old_id = $old_id;
			$this->statusList = M('leadsStatus')->where('status_id <> %d', $old_id)->select();
			$this->display();
		}
	}

	public function leadsStatusSort(){
		if ($this->isGet()) {
			$status = M('leadsStatus');
			$a = 0;
			foreach (explode(',', $_GET['postion']) as $v) {
				$a++;
				$status->where('status_id = %d', $v)->setField('order_id',$a);
			}
			$this->ajaxReturn('1', L('SUCCESSFULLY EDIT'), 1);
		} else {
			$this->ajaxReturn('0', L('EDIT FAILED'), 1);
		}
	}

	public function defaultinfo(){
        $league_id = session('league_id');
		if($this->isGet()){
			$defaultinfo = M('Config')->where(array('name'=>"defaultinfo",'league_id'=>$league_id))->getField('value');
			$this->defaultinfo = unserialize($defaultinfo);
            $this->alert = parseAlert();
			$this->display();
		}elseif($this->isPost()){

			$data['name'] = trim($_POST['name']);
			if ($data['name'] == "") {
				alert('error',L('THE SYSTEM NAME CAN NOT BE EMPTY'),U('setting/defaultinfo'));
			}
			$data['description'] = trim($_POST['description']);
			$data['allow_file_type'] = !empty($_POST['allow_file_type']) ? trim($_POST['allow_file_type']) : 'pdf,doc,jpg,jpeg,png,gif,txt,doc,xls,zip,docx';
            $data['commiss_remind_limit'] = !empty($_POST['commiss_remind_limit']) ? trim($_POST['commiss_remind_limit']) : '0';
            $data['commiss_telephone'] = !empty($_POST['commiss_telephone']) ? trim($_POST['commiss_telephone']) : '010-61504100';
            $data['remind_bt'] = strtotime($_POST['remind_bt']);
            $data['remind_et'] = strtotime($_POST['remind_et']);
            $m_config = M('Config');
			$defaultinfo = $m_config->where(array('name'=>"defaultinfo",'league_id'=>$league_id))->find();
			if($defaultinfo){
				if($m_config->where(array('name'=>"defaultinfo",'league_id'=>$league_id))->save(array('value'=>serialize($data)))){
					F('defaultinfo'.$league_id,$data);
					$result_defaultinfo = true;
				} else {
					$result_defaultinfo = false;
				}
			} else {
				if($m_config->add(array('value'=>serialize($data), 'name'=>"defaultinfo",'league_id'=>$league_id))){
					F('defaultinfo'.$league_id,$data);
					$result_defaultinfo = true;
				}else{
					$result_defaultinfo = false;
				}
			}
			if($result_defaultinfo ){
				alert('success',L('SUCCESSFULLY SET AND SAVED'),U('setting/defaultinfo'));
			} else {
				alert('error',L('DATA UNCHANGED'),U('setting/defaultinfo'));
			}
		}
	}

    public function market(){
        if($this->isGet()){
            $marketinfo = M('Config')->where('name = "market"')->getField('value');
            $this->marketinfo = unserialize($marketinfo);
            $this->alert = parseAlert();
            $this->display();
        }elseif($this->isPost()){
            $data['s_urge_position_ratio'] = empty($_POST['s_urge_position_ratio']) ? "0":trim($_POST['s_urge_position_ratio']);
            $data['ns_urge_position_ratio'] = empty($_POST['ns_urge_position_ratio']) ? "0":trim($_POST['ns_urge_position_ratio']);
            $data['bfz_urge_position_ratio'] = empty($_POST['bfz_urge_position_ratio']) ? "0":trim($_POST['bfz_urge_position_ratio']);
            $data['fz_urge_position_ratio'] = empty($_POST['fz_urge_position_ratio']) ? "0":trim($_POST['fz_urge_position_ratio']);
            $data['ls_urge_position_ratio'] = empty($_POST['ls_urge_position_ratio']) ? "0":trim($_POST['ls_urge_position_ratio']);

            $m_config = M('Config');
            $marketinfo = $m_config->where('name = "market"')->find();
            if($marketinfo){
                if($m_config->where('name = "market"')->save(array('value'=>serialize($data)))){
                    F('market',$data);
                    $result_marketinfo = true;
                } else {
                    $result_marketinfo = false;
                }
            } else {
                if($m_config->add(array('value'=>serialize($data), 'name'=>'market'))){
                    F('market',$data);
                    $result_marketinfo = true;
                }else{
                    $result_marketinfo = false;
                }
            }
            if($result_marketinfo ){
                alert('success',L('SUCCESSFULLY SET AND SAVED'),U('setting/market'));
            } else {
                alert('error',L('DATA UNCHANGED'),U('setting/market'));
            }
        }
    }

	public function getIndustryList(){
		$statusList = M('InfoIndustry')->order('order_id')->select();
		$this->ajaxReturn($statusList, '', 1);
	}
	
	public function fields(){
		$model = $this->_get('model','trim','customer');
        $where = array('model'=>$model);
        if (!isset($_GET['assort']) || $_GET['assort'] == 'basic') {
            $fields_group_list['0'] = array(
                'field_group_id'=>'0',
                'name'=>L("DEFAULT_FILED_GROUP_NAME"),
                'operating'=>'1',
            );
            $fields_group_list['0']['fields'] = M('fields')->where(array('model'=>$model,'field_group_id'=>0))->order('order_id ASC')->select();
        }

        $fields_group = M('FieldsGroup')->where($where)->order('order_id ASC')->select();
        foreach($fields_group as $key=>$group) {
            $fields = M('fields')->where(array('model'=>$model,'field_group_id'=>$group['field_group_id']))->order('order_id ASC')->select();
            $fields_group_list[$group['field_group_id']] = $group;
            $fields_group_list[$group['field_group_id']]['fields'] = $fields;
        }
        $this->assign('fields_group',$fields_group_list);
        $this->assign('model',$model);
        $this->assign('assort',$_GET['assort']);
        $this->alert=parseAlert();
        $this->display();
	}

    public function show_special_fields($module) {
        $model = $this->_get('model','trim',$module);
        $assort = $this->_get('assort','trim','basic');
        $where = array('model'=>$model);
        if ($_GET['assort'] == "" || $_GET['assort'] == 'basic') {
            $fields_group_list['0'] = array(
                'field_group_id'=>'0',
                'name'=>L("DEFAULT_FILED_GROUP_NAME"),
                'operating'=>'1',
            );
            $fields_group_list['0']['fields'] = M('fields')->where(array('model'=>$model,'field_group_id'=>0))->order('order_id ASC')->select();
        }
        $where['assort'] = $assort;

        $fields_group = M('FieldsGroup')->where($where)->order('order_id ASC')->select();
        foreach($fields_group as $key=>$group) {
            $fields = M('fields')->where(array('model'=>$model,'field_group_id'=>$group['field_group_id']))->order('order_id ASC')->select();
            $fields_group_list[$group['field_group_id']] = $group;
            $fields_group_list[$group['field_group_id']]['fields'] = $fields;
        }

        $this->assign('assort',$assort);
        $this->assign('model',$model);
        $this->assign('fields_group',$fields_group_list);
        $this->alert=parseAlert();
        $this->display();
    }

    public function product_fields() {
        self::show_special_fields('product');
    }

    public function serve_fields() {
        self::show_special_fields('serve');
    }

    public function train_fields() {
        self::show_special_fields('train');
    }

    public function currier_fields() {
        self::show_special_fields('currier');
    }


    public function fieldsgroup() {
        $assort = $this->_get('assort','trim','basic');
        $model = $this->_get('model','trim','product');
        $where = array(
            'model'=>$model,
            'assort'=>$assort,
        );
        $fields_group = M('FieldsGroup')->where($where)->order('order_id ASC')->select();
        $this->assign('model',$model);
        $this->assign('assort',$assort);
        $this->assign('fields_group',$fields_group);
        $this->alert=parseAlert();
        $this->display();
    }

    public function fieldgroupsort(){
        if(isset($_GET['postion'])){
            $fields_group = D('fields_group');
            foreach(explode(',', $_GET['postion']) AS $k=>$v) {
                $fields_group->where(array('field_group_id'=> $v))->setField('order_id',$k);
            }
            delete_cache_temp();
            $this->ajaxReturn('1', L('SUCCESSFULLY EDIT'), 1);
        } else {
            $this->ajaxReturn('0', L('EDIT FAILED'), 1);
        }
    }

    public function fieldgroupdelete(){
        $fields_group = M('FieldsGroup');
        if($this->isPost()){
            $field_group_id = is_array($_POST['field_group_id']) ? implode(',', $_POST['field_group_id']) : '';
            if ('' == $field_group_id) {
                alert('error', L('NOT CHOOSE ANY'), $_SERVER['HTTP_REFERER']);
                die;
            } else {
                $where['field_group_id'] = array('in',$field_group_id);
                $where['operating'] = array('not in', array(3,0));

                $field_group_info = $fields_group->where($where)->select();
                if($field_group_info){
                    alert('error', L('SYSTEM FIXED FIELDS GROUP DELETE PROHIBITED'), $_SERVER['HTTP_REFERER']);
                }else{
                    $field_group_infos = $fields_group->where(array('field_group_id'=>array('in',$field_group_id)))->select();
                    foreach($field_group_infos as $field_group_info){
                        D('Fields')->where(array('field_group_id'=>$field_group_info['field_group_id']))->setField('field_group_id', 0);
                        $fields_group->where(array('field_group_id'=>$field_group_info['field_group_id']))->delete();
                    }
                    delete_cache_temp();
                    alert('success',L('DELETE CUSTOM FIELD GROUP SUCCESS'),$_SERVER['HTTP_REFERER']);
                }
            }
        }else{
            $field_group_id = $this->_get('field_group_id','intval',0);
            if($field_group_id == 0) alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
            $field_group_info = $fields_group->where(array('field_group_id'=>$field_group_id))->find();
            if($field_group_info['operating'] != 0) alert('error',L('SYSTEM FIXED FIELDS GROUP DELETE PROHIBITED'),$_SERVER['HTTP_REFERER']);

            if($fields_group->where(array('field_group_id'=>$field_group_id))->delete() !== false){
                D('Fields')->where(array('field_group_id'=>$field_group_id))->setField('field_group_id', 0);
                delete_cache_temp();
                alert('success',L('DELETE CUSTOM FIELD GROUP SUCCESS'),$_SERVER['HTTP_REFERER']);
            }else{
                alert('error',L('FAILED TO DELETE CUSTOM FIELDS GROUP'),$_SERVER['HTTP_REFERER']);
            }
        }

    }
    public function fieldgroupadd(){
        $field_group = M('FieldsGroup');
        if($this->isPost()){
            $data['model']         = $this->_post('model'); //模块名称
            $data['name']         = $this->_post('name'); //字段名称
            $data['assort']         = $this->_request('assort','trim','basic');
            if($field_group->where(array('name'=>$data['name'],'model'=>array(array('eq',$data['model']),array('eq',''),'OR')))->find()){
                alert('error',L('THE FIELD GROUP NAME ALREADY EXISTS'),$_SERVER['HTTP_REFERER']);
            }

            $field_group_model = D('FieldsGroup');
            if($field_group_model->add($data) !== false){
                delete_cache_temp();
                alert('success',L('ADD CUSTOM FIELD GROUP SUCCESS'),$_SERVER['HTTP_REFERER']);
            }else{
                if($error = $field_group_model->getError()){
                    alert('error',$error,$_SERVER['HTTP_REFERER']);
                }else{
                    alert('error',L('ADDING CUSTOM FIELDS GROUP TO FAIL'),$_SERVER['HTTP_REFERER']);
                }
            }
        }else{
            $this->assign('assort',$this->_get('assort','trim','basic'));
            $this->assign('model',$this->_get('model','trim','product'));
            $this->alert = parseAlert();
            $this->display();
        }
    }

    public function fieldgroupedit(){
        $field_group = M('FieldsGroup');
        $field_group_id = $this->_request('field_group_id','intval',0);
        if($field_group_id == 0) alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
        $field_group_info = $field_group->where(array('field_group_id'=>$field_group_id))->find();
        if($field_group_info['operating'] == 2)   {
            alert('error',L('SYSTEM FIXED FIELD GROUP PROHIBIT MODIFICATION'),$_SERVER['HTTP_REFERER']);;
        }
        if($this->isPost()){
            $data['model']         = $field_group_info['model']; //模块名称
            $data['name']         = $field_group_info['operating'] == 0 ? $this->_post('name') : $field_group_info['name']; //字段名称

            if($field_group->where(array('name'=>$data['name'],'model'=>array(array('eq',$data['model']),array('eq',''),'OR'),'field_group_id'=>array('neq',$field_group_id)))->find()){
                alert('error',L('THE FIELD GROUP NAME ALREADY EXISTS'),$_SERVER['HTTP_REFERER']);
            }
            if($field_group->where(array('field_group_id'=>$field_group_id))->setField('name', $data['name']) !== false){
                delete_cache_temp();
                alert('success',L('MODIFY CUSTOM FIELD GROUP SUCCESS'), $_SERVER['HTTP_REFERER']);
            }else{
                if($error = $field_group->getError()){
                    alert('error',$error,$_SERVER['HTTP_REFERER']);
                }else{
                    alert('error',L('FAILED TO MODIFY CUSTOM FIELDS GROUP'),$_SERVER['HTTP_REFERER']);
                }
            }
        }else{
            $this->assign('field_group_info',$field_group_info);
            $this->assign('models',array('customer'=>L('CUSTOMER'),'business'=>L('BUSINESS')));
            $this->alert = parseAlert();
            $this->display();
        }
    }

	
	public function indexShow(){
		$field = M('fields');
		$field_id = $this->_request('field_id','intval',0);
		if($field_id == 0) alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
		$field_info = $field->where(array('field_id'=>$field_id))->find();
		if($field_info['in_index']) {
			if($field ->where('field_id = %d', $field_id)->setField('in_index', 0)){
				alert('success', L('SUCCESSFULLY EDIT'), $_SERVER['HTTP_REFERER']);
			}else{
				alert('error', L('EDIT FAILED'), $_SERVER['HTTP_REFERER']);
			}
		}else{
			if($field ->where('field_id = %d', $field_id)->setField('in_index', 1)){
				alert('success', L('SUCCESSFULLY EDIT'), $_SERVER['HTTP_REFERER']);
			}else{
				alert('error', L('EDIT FAILED'), $_SERVER['HTTP_REFERER']);
			}
		}
	}
	
	public function fieldAdd(){
		$field = M('fields');
		if($this->isPost()){
			$field_model = D('Field');
			$data['model']         = $this->_post('model'); //模块名称
			$data['field']         = $this->_post('field'); //字段名称
			$data['form_type']     = $this->_post('form_type'); //字段类型
			$data['default_value'] = $this->_post('default_value');  //默认值
			$data['max_length']    = $this->_post('max_length');
			$data['is_main']       = $this->_post('is_main');
            $data['field_group_id']       = $this->_post('field_group_id');
            $data['is_showtime']       = $this->_post('is_showtime');
            $data['in_verify']       = $this->_post('in_verify');
            $data['once_verify']       = $this->_post('once_verify');  //默认值
            $data['is_branch']       = $this->_post('is_branch');  //默认值
            $data['assort']       = $this->_post('assort');  //默认值
            if (isset($_POST['viewid'])) {
                $_POST['viewid'] = implode(" ",$_POST['viewid']);
            }

            if($field->where(array('field'=>$data['field'],'model'=>array('eq',$data['model'])))->find()){
				alert('error',L('THE FIELD NAME ALREADY EXISTS'),$_SERVER['HTTP_REFERER']);
			}
			if($field_model->add($data) !== false){
				$field->create();
                $from_type = $this->_post('form_type');
				if($from_type == 'box'){
					$setting = $this->_post('setting');
					$field->setting = 'array(';
					$field->setting .= "'type'=>'$setting[boxtype]','data'=>array(";
					$i = 0;
					$options = explode(chr(10),$setting['options']);
					$s = array();
					foreach($options as $v){
						$v = trim(str_replace(chr(13),'',$v));
						if($v != '' && !in_array($v ,$s)){
							$i++;
							$field->setting .= "$i=>'$v',";
							$s[] = $v;
						}
					}
					
					$field->setting = substr($field->setting,0,strlen($field->setting) -1 ) .'))';
				} else if ($from_type == "pic" || $from_type == "video" || $from_type == "file") {
                    $field->one_row = 1;
                }
                $field->add();
                delete_cache_temp();

				alert('success',L('ADD CUSTOM FIELD SUCCESS'),$_SERVER['HTTP_REFERER']);
			}else{
				if($error = $field_model->getError()){
					alert('error',$error,$_SERVER['HTTP_REFERER']);
				}else{
					alert('error',L('ADDING CUSTOM FIELDS TO FAIL'),$_SERVER['HTTP_REFERER']);
				}
			}
		}else{

            $assort = $this->_get('assort','trim','basic');
            if ($assort == "") {
                $assort = "basic";
            }
            $module = $this->_get('model','trim','customer');
            $grouplist = D("fieldsGroup")->where(array("model"=>$module))->select();
            $this->assign('grouplist',$grouplist);

            $groupassortlist = D("fieldsGroup")->where(array("model"=>$module))->group("assort")->select();
            $this->assign('groupassortlist',$groupassortlist);

            $this->assign('assort',$assort);
            $this->assign('model',$module);
			$this->alert = parseAlert();
			$this->display();
		}
	}
	public function fieldEdit(){

        $field = M('fields');
		$field_id = $this->_request('field_id','intval',0);
		if($field_id == 0) alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
		$field_info = $field->where(array('field_id'=>$field_id))->find();
		if($field_info['operating'] == 2) {
            alert('error',L('SYSTEM FIXED FIELD PROHIBIT MODIFICATION'),$_SERVER['HTTP_REFERER']);;
        }
		if($this->isPost()){
			$field_model = D('Field');
			$data['model']         = $field_info['model']; //模块名称
			$data['field']         = $field_info['operating'] == 0 ? $this->_post('field') : $field_info['field']; //字段名称
			$data['field_old']     = $field_info['field']; //字段名称
			$data['form_type']     = $field_info['form_type']; //字段类型
			$data['default_value'] = $this->_post('default_value');  //默认值
			$data['max_length']    = $this->_post('max_length');
			$data['is_main']       = $field_info['is_main'];
            $data['is_showtime']       = $this->_post('is_showtime');  //默认值
            $data['in_verify']       = $this->_post('in_verify');  //默认值
            $data['once_verify']       = $this->_post('once_verify');  //默认值
            $data['is_branch']       = $this->_post('is_branch');  //默认值
            $data['assort']       = $this->_post('assort');  //默认值

            if (isset($_POST['viewid'])) {
                $_POST['viewid'] = implode(" ",$_POST['viewid']);
            }

			if($field_model->save($data) !== false){
				$field->create();
				if($field_info['form_type'] == 'box'){
					eval('$field_info["setting"] = '.$field_info["setting"].';');
					$boxtype = $field_info['setting']['type'];
					$setting = $this->_post('setting');
					$field->setting = 'array(';
					$field->setting .= "'type'=>'$boxtype','data'=>array(";
					$i = 0;
					$options = explode(chr(10),$setting['options']);
					$s = array();
					foreach($options as $v){
						$v = trim(str_replace(chr(13),'',$v));
						if($v != '' && !in_array($v ,$s)){
							$i++;
							$field->setting .= "$i=>'$v',";
							$s[] = $v;
						}
					}
					$field->setting = substr($field->setting,0,strlen($field->setting) -1 ) .'))';
				}
                else if($field_info['form_type'] == 'pic' || $field_info['form_type'] == 'video' || $field_info['form_type'] == 'file') {
                    $field->one_row = 1;
                }
                $field->save();
                delete_cache_temp();
                alert('success',L('MODIFY CUSTOM FIELD SUCCESS'), $_SERVER['HTTP_REFERER']);
			}else{
				if($error = $field_model->getError()){
					alert('error',$error,$_SERVER['HTTP_REFERER']);
				}else{
					alert('error',L('FAILED TO MODIFY CUSTOM FIELDS'),$_SERVER['HTTP_REFERER']);
				}
			}
		}else{

            if ($field_info['viewid']) {
                $field_info['viewid'] = explode(" ",$field_info['viewid']);
            }

			if($field_info['form_type'] == 'box'){
				eval('$field_info["setting"] = '.$field_info["setting"].';');
				$field_info['form_type_name'] = L('OPTIONS');
				$field_info["setting"]['options'] = implode(chr(10),$field_info["setting"]['data']);
			}else if($field_info['form_type'] == 'editor'){
				$field_info['form_type_name'] = L('EDITOR');
			}else if($field_info['form_type'] == 'text'){
				$field_info['form_type_name'] = L('TEXT');
			}else if($field_info['form_type'] == 'textarea'){
				$field_info['form_type_name'] = L('TEXTAREA');
			}else if($field_info['form_type'] == 'datetime'){
				$field_info['form_type_name'] = L('DATETIME');
			}else if($field_info['form_type'] == 'number'){
				$field_info['form_type_name'] = L('NUMBER');
			}else if($field_info['form_type'] == 'floatnumber'){
				$field_info['form_type_name'] = L('FLOATNUMBER');
			}else if($field_info['form_type'] == 'address'){
				$field_info['form_type_name'] = L('ADDRESS');
			}else if($field_info['form_type'] == 'phone'){
				$field_info['form_type_name'] = L('PHONE');
			}else if($field_info['form_type'] == 'mobile'){
				$field_info['form_type_name'] = L('MOBILE');
			}else if($field_info['form_type'] == 'email'){
				$field_info['form_type_name'] = L('EMAIL');
			}else if($field_info['form_type'] == 'linkaddress'){
                $field_info['form_type_name'] = L('LINK_ADDRESS');
            }else if($field_info['form_type'] == 'pic'){
                $field_info['form_type_name'] = '图片';
            }else if($field_info['form_type'] == 'video'){
                $field_info['form_type_name'] = '视频';
            }else if($field_info['form_type'] == 'file'){
                $field_info['form_type_name'] = '文件';
            }

            $module = $field_info['model'];
            $where = array("model"=>$module);
            $group = D("fieldsGroup")->where(array("field_group_id"=>$field_info['field_group_id']))->find();
            if ($group) {
                $assort = $group['assort'];
                //$where['assort'] = $assort;
            }
            $grouplist = D("fieldsGroup")->where($where)->select();
            $this->assign('grouplist',$grouplist);

            $groupassortlist = D("fieldsGroup")->where(array("model"=>$module))->group("assort")->select();
            $this->assign('groupassortlist',$groupassortlist);
            $this->assign('assort',$assort);

			$this->assign('fields',$field_info);
			$this->assign('models',array('customer'=>L('CUSTOMER'),'business'=>L('BUSINESS')));
			$this->alert = parseAlert();
			$this->display();
		}
	}
	public function fieldDelete(){
		$field = M('fields');
		if($this->isPost()){
			$field_id = is_array($_POST['field_id']) ? implode(',', $_POST['field_id']) : '';
			if ('' == $field_id) {
				alert('error', L('NOT CHOOSE ANY'), $_SERVER['HTTP_REFERER']);
				die;
			} else {
				$where['field_id'] = array('in',$field_id);
				$where['operating'] = array('not in', array(3,0));
				
				$field_info = $field->where($where)->select();
				if($field_info){
					alert('error', L('SYSTEM FIXED FIELDS DELETE PROHIBITED'), $_SERVER['HTTP_REFERER']);
				}else{
					$field_infos = $field->where(array('field_id'=>array('in',$field_id)))->select();
					foreach($field_infos as $field_info){
						$field_model = D('Field');
						$data['model']         = $field_info['model']; //模块名称
						$data['field']         = $field_info['field']; //字段名称
						$data['is_main']       = $field_info['is_main'];
						$field_model->delete($data);
						$field->where(array('field_id'=>$field_info['field_id']))->delete();
					}
                    delete_cache_temp();
                    alert('success',L('DELETE CUSTOM FIELD SUCCESS'),$_SERVER['HTTP_REFERER']);
				}
			}
		}else{
			$field_id = $this->_get('field_id','intval',0);
			if($field_id == 0) alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
			$field_info = $field->where(array('field_id'=>$field_id))->find();
			if($field_info['operating'] != 0) alert('error',L('SYSTEM FIXED FIELDS DELETE PROHIBITED'),$_SERVER['HTTP_REFERER']);
			$field_model = D('Field');
			$data['model']         = $field_info['model']; //模块名称
			$data['field']         = $field_info['field']; //字段名称
			$data['is_main']       = $field_info['is_main'];
			if($field_model->delete($data) !== false){
				$field->where(array('field_id'=>$field_id))->delete();
                delete_cache_temp();
                alert('success',L('DELETE CUSTOM FIELD SUCCESS'),$_SERVER['HTTP_REFERER']);
			}else{
				alert('error',L('FAILED TO DELETE CUSTOM FIELDS'),$_SERVER['HTTP_REFERER']);
			}
		}
		
	}
	public function fieldsort(){	
		if(isset($_GET['postion'])){
			$fields = M('fields');
			foreach($_GET['postion'] AS $k=>$fieldpos) {
				$data = array(
                    'field_id'=> $fieldpos['field_id'],
                    'field_group_id'=> $fieldpos['field_group_id'],
                    'order_id'=>$k);
				$fields->save($data);
			}
            delete_cache_temp();
			$this->ajaxReturn('1', L('SUCCESSFULLY EDIT'), 1);
		} else {
			$this->ajaxReturn('0', L('EDIT FAILED'), 1);
		}
	}
	public function boxField(){
		$field_list = M('Fields')->where(array('model'=>$this->_get('model'),'field'=>$this->_get('field')))->getField('setting');
		eval('$field_list = '.$field_list .';');
		$this->ajaxReturn($field_list['data'], $field_list['type'], 1);
	}


    public function census_fields() {
        $this->ajaxReturn(census_retrieve($this->_get('census')), $this->_get('census'), 1);
    }
}