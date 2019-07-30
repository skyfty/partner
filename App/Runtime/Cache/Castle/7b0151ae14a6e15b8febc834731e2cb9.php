<?php if (!defined('THINK_PATH')) exit();?><div class="wrapper-md" ng-controller="logviewController">
    <div class="row">
        <div class="col-sm-3">
        </div>
        <div class="col-sm-1">
            <select class="form-control" ng-model="datatablePageLength" ng-init="datatablePageLength=10">
                <option value="10">10</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="-1">全部</option>
            </select>
        </div>
        <div  class="col-sm-8">
            <div class="input-group">
                <ui-field-condition ng-fields="fields" ng-model="searchCondition" ng-default="任意字段"/>
            </div>
        </div>
    </div>
    <div class="row">
        <ul class="list-group list-group-lg no-borders pull-in m-b-none">
            <li class="list-group-item">
                <a href="#" class="h4 text-primary m-b-sm m-t-sm block">Morbi id neque quam. Aliquam sollicitudin venenatis ipsum ac </a>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi id neque quam. Aliquam sollicitudin egestas dui nec, fermentum diam. Vivamus vel tincidunt libero, vitae elementum ligula venenatis ipsum ac feugiat. Vestibulum ullamcorper sodales nisi nec condimentum</p>
                <p><span class="label bg-primary pos-rlt m-r inline wrapper-xs"><i class="arrow right arrow-primary"></i> Tags:</span> <a href class="m-r-sm">admin</a> <a href>app</a>
                </p>
            </li>
        </ul>
        <div class="row wrapper text-center" style="padding-bottom:0px;padding-to:0px">
            <ui-page total="{{totalPages}}" ng-model="currentPage" ng-init="currentPage=0;totalPages=0;" ng-change="pageChange($current);"></ui-page>
        </div>
    </div>
</div>

<script>
    console.log("lskf");

    app.controller('logviewController', function($scope,$timeout) {
        $scope.fields = fields;
        $scope.searchCondition = {value:"",condition:"", field:""};
        
        $scope.pageChange = function(current){
            $.ajax({'url':'product/logs',
                'data':{
                    'id':12
                },
                'success':function(info){
                    $scope.$apply(function(){
                        $scope.logs = info.logs;
                        $scope.totalPages = 12;
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
            $scope.pageChange(0);
        });
    });
</script>