<?php if (!defined('THINK_PATH')) exit();?><div class="wrapper-md" ng-controller="evaluateController">
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
                    <div class="input-group-btn">
                        <button type="button" class="btn  btn-default" ng-click="refreshLog();"><i class="fa fa-search  text"></i></button>
                    </div>
                </div>
            </ui-field-condition>
        </div>
    </div>

    <div class="row" style="padding-top: 10px">
        <div class="ui-view-detail-false">
            <ul class="list-group list-group-lg no-borders pull-in m-b-none">
                <li class="list-group-item" ng-repeat="log in logs">
                    <a href="#" class="h4 text-primary m-b-sm m-t-sm block">{{log.market_idcode}} </a>
                <span class="text-muted m-l-sm pull-right">
                    {{log.update_time| date:'yyyy-MM-dd HH:mm:ss'}}
                </span>
                    <p>{{log.category_name}},{{log.category_name}}</p>
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
    console.log("lskdfjl");
    app.controller('evaluateController', function($scope,$timeout, $filter) {
        $scope.fields = fields;
        $scope.searchCondition = {value:"",condition:"", field:""};

        $scope.refreshLog = function(){
            var data = {
                'start':$scope.currentPage,
                'length':$scope.pageLength,
            };
            data['et'] = "<?php echo ($et); ?>";
            data['market_product_evaluate.product_id'] = $scope.casModel.product_id;
            $.ajax({'url':'product_evaluate/index',
                'data':{
                    'draw':1,
                    'forces':data
                },
                'success':function(info){
                    $scope.$apply(function(){
                        for(var i in info.data){
                            info.data[i] = $filter("formatFields")($scope.fields, info.data[i]);
                        }
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

        $scope.$on('hideSection', function () {
            $timeout(function() {
                $scope.$destroy();
            });
        });
    });
</script>