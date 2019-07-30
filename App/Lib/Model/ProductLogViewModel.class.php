<?php
class ProductLogViewModel extends ViewModel{
    public $viewFields;
    public function _initialize(){
        $this->viewFields = array(
            'r_log_product'=>array(
                'product_name',
                'product_idcode',
                'assort',
                'branch_id',
                '_type'=>'LEFT'
            ),
            'log'=>array(
                'log_id',
                'create_date',
                'subject',
                'content',
                'role_id',
                'role_id_keyword',
                '_on'=>'r_log_product.log_id=log.log_id',
            ),
            'product'=>array(
                'league_id',
                'product_id',
                'idcode',
                'name'=>'name',
                '_on'=>'r_log_product.product_id=product.product_id',
                '_type'=>'LEFT'
            ),
            'product_category'=>array(
                'name'=>'category_name',
                '_on'=>'product.category_id=product_category.category_id',
                '_type'=>'LEFT'
            )
        );
    }

}