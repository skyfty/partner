<?php if (!defined('THINK_PATH')) exit();?><div class="wrapper-md" ng-controller="logController">
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
                        <button type="button" class="btn  btn-default" ng-click="refreshLog();"><i class="fa fa-search  text"></i></button>
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
        <div>
            <ul class="nav nav-tabs">
                <li  ng-repeat="lc in logClass" ng-class="{'active': lc.active }"><a href="javascript:void(0);" ng-click="selectClass(lc)">{{lc.label}}</a></li>
            </ul>
        </div>
        <div class="ui-view-detail-true">
            <ul class="list-group list-group-lg no-borders pull-in m-b-none">
                <li class="list-group-item" ng-repeat="log in logs">
                    <a class="h4 text-primary m-b-sm m-t-sm block">{{log.subject}} </a>
                <span class="text-muted m-l-sm pull-right">
                    {{log.create_date| date:'yyyy-MM-dd HH:mm:ss'}}
                </span>
                    <p>{{log.content}}</p>
                    <p><span class="label bg-primary pos-rlt m-r inline wrapper-xs"><i class="arrow right arrow-primary"></i> Tags:</span>
                        <a  class="m-r-sm" href ng-repeat="tag in log.tags">{{tag}}</a>
                    </p>
                </li>
            </ul>
        </div>
        <div class="row wrapper text-center" style="padding-bottom:0px;padding-to:0px">
            <ui-page total="{{totalPages}}" cas-model="currentPage" ng-init="currentPage=0;totalPages=0;" cas-change="refreshLog();"></ui-page>
        </div>
    </div>
</div>

<script>
    var fields = <?php echo (json_encode($field_array)); ?>;
    console.log("lskdjflsdf");
    app.controller('logController', function($scope,$timeout,multipleSearch) {
        $scope.logClass = [
            {"label":"默认", "category_id":"6","active":true},
            {"label":"审核", "category_id":"5", "active":false},
            {"label":"级别", "category_id":"7", "active":false},
            {"label":"日志", "category_id":"1", "active":false}
        ];
        $scope.fields = fields;
        $scope.searchCondition = {value:"",condition:"", field:""};
        $scope.selectLogClass = $scope.logClass[0];
        $scope.searchMultipleCondition = [];

        $scope.multipleSearch = function(){
            multipleSearch.open(fields,$scope.searchMultipleCondition).then(function(items){
                $scope.searchMultipleCondition = items?items:[];
                $scope.refreshLog();
            });
        };

        $scope.selectClass = function(c){
            angular.forEach($scope.logClass, function(c){
                c.active = false;
            });
            c.active = true;
            $scope.selectLogClass = c;
            $scope.refreshLog();
        };

        $scope.refreshLog = function(){
            if (!$scope.casModel || !$scope.casModel.product_id) {
               return;
            }

            var d = {
                'start':$scope.currentPage,
                'length':$scope.pageLength,
                'order':"create_date desc",
                'search':{},
                'draw':1,
                'forces':{
                    'product_id':{'value':$scope.casModel.product_id,'model':"product"},
                }
            };

            if ($scope.searchCondition) {
                d['search']['value'] = $scope.searchCondition.value;
                d['search']['condition'] = $scope.searchCondition.condition;
                if ($scope.searchCondition.field) {
                    d['search']['field'] = fields[$scope.searchCondition.field].field;
                    if (fields[$scope.searchCondition.field].model) {
                        d['search']['model'] = fields[$scope.searchCondition.field].model;
                    }
                }
            }

            if ($scope.searchMultipleCondition.length > 0) {
                var mutiple = [];
                angular.forEach($scope.searchMultipleCondition, function(mc){
                    var nmc = angular.copy(mc);
                    nmc.field = fields[nmc.field].field;
                    if (fields[nmc.field].model) {
                        nmc['model'] = fields[nmc.field].model;
                    }
                    mutiple.push(nmc);
                });
                d.multiple = mutiple;
            }
            d['category_id'] = $scope.selectLogClass? $scope.selectLogClass.category_id:"6";
            d['assort'] = $scope.group.group;

            $.ajax({'url':'product/logs',
                'data':d,
                'success':function(info){
                    $scope.$apply(function(){
                        $scope.logs = info.data;
                        $scope.totalPages = Math.round(info.recordsTotal / $scope.pageLength);
                    });
                },
                'beforeSend':function(){
                    $scope.$emit('butterbarEvent', { show: true });
                },
                'complete':function(){
                    $scope.$emit('butterbarEvent', { show: false });
                }
            });
        };
        $timeout(function() {
            $scope.refreshLog();
        });
    });
</script>