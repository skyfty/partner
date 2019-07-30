<?php if (!defined('THINK_PATH')) exit();?><div class="wrapper-md" ng-controller="appraiseController">
    <div class="row">
        <div class="col-sm-1" style="padding-left:0px">
            <select class="form-control" ng-model="pageLength" ng-init="pageLength=10">
                <option value="10">10</option>
                <option value="30">30</option>
                <option value="50">50</option>
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

    <div class="row" style="padding-top: 10px">
        <div class="table-responsive">
            <table ui-datatable="fields" ng-order="[2,'asc']"  ng-refresh-event="refreshProductAppraisalList" ng-data-url="ProductAppraisal/index" ng-page="totalPages" ng-create-row="createdRow"  ng-row-option="table-row-options"  ng-page-length="pageLength" class="table table-striped hover b-t b-light" style="white-space: nowrap;">
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
        <div class="row wrapper text-center" style="padding-bottom:0px;padding-to:0px">
            <ui-page total="{{totalPages}}" cas-model="currentPage" ng-init="currentPage=0;totalPages=0;"></ui-page>
        </div>
    </div>
</div>

<script id="table-row-options" type="text/x-jquery-tmpl">
<div class="btn-toolbar btn-toolbar-light" id="user-toolbar-options">
<a target="_blank" href="#/view/appraisal/${product_id}/${product_appraisal_id}"><i class="icon-user"></i></a>
</div>
</script>

<script id="table-row-selected-hot" type="text/x-jquery-tmpl">
<a id="table-cell-option" target="_blank"  href="#/view/appraisal/${product_id}/${product_appraisal_id}" class="table-cell-option" style="margin-left: 5px"><i class="fa fa-flickr"></i></a>
</script>

<script>
    var fields = <?php echo (json_encode($field_array)); ?>;

    app.controller('appraiseController', function($scope, multipleSearch, $timeout, $q, $filter, $parse) {
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

            d['product_id'] = $scope.casModel.product_id;
        };

        $scope.$on('hideSection', function () {
            $timeout(function() {
                $scope.$destroy();
            });
        });
    });
</script>