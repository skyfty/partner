<?php

class ProductAppraisalAction extends CastleAction {

    function getmodulename() {
        return "product_appraisal";
    }

    public function update(){
        if ($product_appraisal_id = $_POST['product_appraisal_id']) {
            $product_appraisal_id = $this->_request("product_appraisal_id");
            if (!$product_appraisal_id) {
                $this->ajaxReturn(array("error"=>'参数错误'));
            }
        } else {
            $product_id = $this->_request("product_id");
            if (!$product_id) {
                $this->ajaxReturn(array("error"=>'参数错误'));
            }
        }
        if ($product_appraisal_id) {
            $product_appraisal = M('product_appraisal')->where('product_appraisal_id = %d',$product_appraisal_id)->find();
            if (!$product_appraisal) {
                $this->ajaxReturn(array("error"=>'参数错误'));
            }
            if (!$this->submit_edit($product_appraisal_id, "product_appraisal")) {
                $this->ajaxReturn(array("error"=>'编辑鉴定失败'));
            }
        } else {
            if (!($product_appraisal_id = $this->submit_add("product_appraisal"))) {
                $this->ajaxReturn(array("error"=>'参数错误'));
            }
        }
        $this->show_list(array("product_appraisal_id"=>$product_appraisal_id));
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
            $order = "appraise_time desc";
        }
        return $order;
    }

    public function delete(){
        if (!$_REQUEST['id']) {
            alert('error', L('YOU_HAVE_NOT_CHOOSE_ANY_CONTENT'),$_SERVER['HTTP_REFERER']);
        }
        role_log(MODULE_NAME, ACTION_NAME, $_REQUEST);

        $product_appraisal_id_idst = is_array($_REQUEST['id']) ? $_REQUEST['id'] : array($_REQUEST['id']);

        if (!$this->submit_delete($product_appraisal_id_idst, array(), "product_appraisal")) {
            alert('error', "请选择项目" ,$_SERVER['HTTP_REFERER']);
        }
        alert('success', "删除鉴定成功", $_SERVER['HTTP_REFERER']);
    }
}
