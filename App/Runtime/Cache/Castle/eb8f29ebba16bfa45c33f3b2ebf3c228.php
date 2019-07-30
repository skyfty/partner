<?php if (!defined('THINK_PATH')) exit();?><!-- hbox layout -->
<div ng-controller="accountAssociateController">
    <div class="row wrapper">
        <div class="col-sm-1" style="padding-left:0px">
            <select class="form-control" ng-model="datatablePageLength" ng-init="datatablePageLength=10">
                <option value="10">10</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="-1">全部</option>
            </select>
        </div>

        <div  class="col-sm-11" style="padding-right:0px">
            <ui-field-condition ng-fields="fields" cas-model="searchCondition" ng-default="任意字段" holder="holder">
                <div class="input-group" style="width: 100%;">
                    <holder/>
                    <div class="input-group-btn dropdown" dropdown>
                        <button type="button" class="btn  btn-default" ng-click="table.ajax.reload();"><i class="fa fa-search  text"></i></button>
                        <button type="button" class="btn btn-default" dropdown-toggle><span class="caret"></span></button>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="javascript:void(0);" ng-click="multipleSearch();">高级搜索</a></li>
                        </ul>
                    </div>
                </div>
            </ui-field-condition>
        </div>
    </div>
    <div class="row wrapper">
        <div class="table-responsive">
            <table ui-datatable="fields" ng-data-url="account/index" ng-page="totalPages" ng-create-row="createdRow" ng-row-option="table-row-options" ng-page-length="datatablePageLength" class="table table-striped hover b-t b-light" style="white-space: nowrap;">
                <thead>
                <tr>
                </tr>
                </thead>
                <tfoot>
                <tr>
                </tr>
                </tfoot>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row wrapper text-center" style="padding-bottom:0px;padding-to:0px">
        <ui-page total="{{totalPages}}" cas-model="currentPage" ng-init="currentPage=0;totalPages=0;" cas-change="pageChange($current);"></ui-page>
    </div>
</div>

<script id="table-row-options" type="text/x-jquery-tmpl">
<div class="btn-toolbar btn-toolbar-light" id="user-toolbar-options">
    <a target="_blank" href="#/view/cultivate/${cultivate_id}"><i class="icon-user"></i></a
</div>
</script>


<script id="table-row-selected-hot" type="text/x-jquery-tmpl">
<a id="table-cell-option" onclick="angular.element(this).scope().viewProduct(${product_id})" class="table-cell-option" style="margin-left: 5px"><i class="fa fa-flickr"></i></a>
</script>

<script>
    var fields = <?php echo (json_encode($field_array)); ?>;
    console.log("222222222");
    app.controller('accountAssociateController', function($scope, multipleSearch) {
        $scope.fields = fields;
        $scope.searchCondition = {value:"",condition:"", field:""};
        $scope.searchMultipleCondition = [];
        $scope.multipleSearch = function(){
            multipleSearch.open(fields,$scope.searchMultipleCondition).then( function(items){
                $scope.searchMultipleCondition = items?items:[];
                $scope.table.ajax.reload();
            });
        };

        $scope.createdRow = function( row, data, index) {
        };

        $scope.datatablesearch = function(d){
            if ($scope.searchCondition) {
                d['search']['value'] = $scope.searchCondition.value;
                d['search']['condition'] = $scope.searchCondition.condition;
                if ($scope.searchCondition.field) {
                    d['search']['field'] = fields[$scope.searchCondition.field].field;
                }
            }

            if ($scope.searchMultipleCondition.length > 0) {
                var mutiple = [];
                angular.forEach($scope.searchMultipleCondition, function(mc){
                    var nmc = angular.copy(mc);
                    nmc.field = fields[nmc.field].field;
                    mutiple.push(nmc);
                });
                d.multiple = mutiple;
            }

            d['type'] = '<?php echo ($type); ?>';
            d['dire'] = '<?php echo ($dire); ?>';
            d['clause_additive'] = $scope.related_model_id;
        };
    });
</script>