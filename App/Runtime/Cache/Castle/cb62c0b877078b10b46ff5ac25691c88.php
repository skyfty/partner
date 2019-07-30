<?php if (!defined('THINK_PATH')) exit();?><!-- hbox layout -->
<div class="hbox hbox-auto-xs bg-light"  ng-controller="productViewController" ng-init="app.settings.asideFolded = true;app.settings.asideFixed = true;app.settings.asideDock = false;app.settings.container = false;app.hideFooter=true;app.hideAside = false">
    <!-- column -->
    <div class="col w-sm b-r" ng-show="groups" >
        <div class="vbox">
            <div class="row-row">
                <div class="cell scrollable hover">
                    <div class="cell-inner">
                        <div class="list-group no-radius no-border no-bg m-b-none">
                            <a ng-repeat="group in groups" class="list-group-item m-l hover-anchor b-a no-select" ng-class="{'focus m-l-none': group.selected}" ng-click="selectGroup(group)">
                                <i ng-show="group.dirty" class="assort-field-dirty b-white"></i>
                                <span class="block m-l-n" ng-class="{'m-n': group.selected }">{{ group.name ?  group.name : 'Untitled' }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /column -->
    <!-- column -->
    <div class="col w-sm lter b-r" ng-show="assorts && assorts.length > 1" >
        <div class="vbox">
            <div class="row-row">
                <div class="cell scrollable hover">
                    <div class="cell-inner">
                        <div class="m-t-n-xxs">
                            <div class="list-group list-group-lg no-radius no-border no-bg m-b-none">
                                <a ng-repeat="assort in assorts" class="list-group-item m-l" ng-class="{'select m-l-none': assort.selected }" ng-click="selectItem(assort)">
                                    <i ng-show="assort.dirty" class="assort-field-dirty b-white"></i>
                                    <span class="block text-ellipsis m-l-n text-md" ng-class="{'m-l-none': assort.selected }">
                                        {{ assort.name }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /column -->
    <!-- column -->
    <div class="col bg-white-only">
        <div class="vbox">
            <div class="wrapper-sm b-b">
                <div class="m-t-n-xxs m-b-n-xxs m-l-xs">
                    <a class="btn btn-xs btn-primary" ng-click="updateModel()" authority="casModel.permission.update"><i class="fa fa-save"></i> 保存</a>
                    <a class="btn btn-xs btn-default" ng-click="addLog()"  ng-show="group.name=='基本信息' || group.name=='鉴定信息' && casModel.product_id"><i class="fa fa-comment-o"></i> 添加日志</a>


                    <a class="btn btn-xs btn-default" ng-click="verify()" ng-show="group.name=='基本信息' && casModel.basic_submit_time > 0"><i class="fa fa-tachometer"></i> 审核</a>
                    <a class="btn btn-xs btn-default" ng-click="verify()" ng-show="group.name=='鉴定信息' && casModel.skill_submit_time > 0"><i class="fa fa-tachometer"></i> 审核</a>
                    <a class="btn btn-xs btn-default" ng-click="addProductCatetory()"  authority="casModel.permission.addcat" ng-show="assort.name=='服务类别' && casModel.product_id"><i class="fa fa-plus"></i> 增加类别</a>


                    <a class="btn btn-xs btn-default" target="_blank" href="#/view/account/new/-1/product/{{casModel.product_id}}"  ng-show="assort.name=='账户信息' && casModel.product_id"><i class="fa fa-plus"></i> 支出</a>
                    <a class="btn btn-xs btn-default" target="_blank" href="#/view/account/new/1/product/{{casModel.product_id}}"  ng-show="assort.name=='账户信息' && casModel.product_id"><i class="fa fa-plus"></i> 收入</a>
                    <a class="btn btn-xs btn-default" target="_blank" href="#/view/account/new/-3/product/{{casModel.product_id}}"  ng-show="assort.name=='账户信息' && casModel.product_id"><i class="fa fa-plus"></i> 资金冻结</a>
                    <a class="btn btn-xs btn-default" target="_blank" href="#/view/account/new/3/product/{{casModel.product_id}}"  ng-show="assort.name=='账户信息' && casModel.product_id"><i class="fa fa-plus"></i> 资金解冻</a>

                    <a class="btn btn-xs btn-default" target="_blank" href="#/view/appraisal/{{casModel.product_id}}/new" ng-show="assort.name=='鉴定成绩' && casModel.product_id"><i class="fa fa-plus"></i> 增加鉴定 </a>
                    <a class="btn btn-xs btn-danger pull-right" authority="casModel.permission.delete" ng-show="casModel.product_id" ng-click="deleteModel()"><i class="fa fa-times"></i> 删除</a>
                </div>
            </div>
            <div class="row-row">
                <div class="cell">
                    <div class="cell-inner">
                        <div class="wrapper-sm fade-in-up">
                            <div>
                                <ul class="nav nav-tabs" ng-show="sections && sections.length > 1">
                                    <li ng-class="{'active': sec.selected }"  ng-repeat="sec in sections"><a href="javascript:void(0);" ng-click="selectSection(sec)">{{sec.label}}</a></li>
                                </ul>
                            </div>
                            <div class="ui-view-detail-{{sections && sections.length > 1}}"  ui-view-detail="casModel"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /column -->
</div>


<script>

    var fieldGroup = <?php echo (json_encode($field_group)); ?>;
    app.controller('productViewController', function($scope, $state, $filter, $stateParams, $compile,$parse,toaster,$timeout,modelinfo) {
        console.log("2222222222222222");

        fieldGroup[0]["assorts"].push({"assort":'basic',"name":'日志','sections':[{"templateUrl":"index/product_logs"}]});

        fieldGroup[2]["assorts"][0]['sections'] = [{"template":'<ui-skill-card-list cas-model="casModel.skill"/>'}];
        fieldGroup[2]["assorts"].push({ "assort":'basic',"name":'鉴定成绩','sections':[{"label":"字段","templateUrl":"index/associate/model/product_appraisal"}]});
        fieldGroup[2]["assorts"].push({ "assort":'basic',"name":'日志','sections':[{"templateUrl":"index/product_logs"}]});

        fieldGroup[3]["assorts"][0]['sections'] = [
            {"label":"概要","module":"product"},
            {"label":"日程","templateUrl":"index/product_events"},
            {"label":"调度日志","templateUrl":"index/product_logs"},
        ];

        fieldGroup[5]['assorts'][0]['sections'] = [
            {"label":"概要","module":"product"},
            {"label":"客户服务评价","templateUrl":"index/associate/model/product_evaluate/et/market"},
            {"label":"手动评价","templateUrl":"index/associate/model/product_evaluate/et/manual"},
        ];

        fieldGroup[6]["assorts"][0]['sections'] = [
            {"label":"概要","module":"product"},
            {"label":"支出","templateUrl":"index/associate/model/account/dire/-1/type/product/excfield/flowid,payway"},
            {"label":"收入","templateUrl":"index/associate/model/account/dire/1/type/product/excfield/flowid,payway"},
            {"label":"冻结","templateUrl":"index/associate/model/account/dire/-2,2/type/product/excfield/flowid,payway"}
        ];

        $scope.initGroup("product", fieldGroup);

        $scope.avatarUpdate = function($element){
            $("#avatar_file_input").click();
        };

        $scope.birthdayChanged = function(){
            var birthday = $scope.casModel.birthday;
            if (birthday) {
                $scope.casModel.zodiac = getshengxiao(birthday.getFullYear());
                $scope.casModel.constellation = getxingzuo(birthday.getMonth(), birthday.getDate());
            }
        };

        $scope.cardidChanged = function(){
            var birthday = getbirth($scope.casModel.cardid);
            $scope.casModel.birthday = moment(birthday).toDate();
            $scope.birthdayChanged();
        };

        $scope.updateModel = function(){
//            var result = $filter("validateFields")($scope.allassorts, $scope.casModel);
//            if (result && result.length > 0) {
//                angular.forEach(result, function(field){
//                    toaster.pop("error", "数据错误", field.name + "不可以为空，请设置");
//                });
//                return false;
//            }

            var data = $filter("convertAssortFields")($scope.allassorts, $scope.casModel);
            $scope.syncData(data,"product/update").then(function(product){
                $scope.casModel = $filter("formatAssortFields")($scope.allassorts, product.get());
                $scope.assignFootInfo($scope.casModel);
            })
        };

        $scope.addLog = function(){
            var params = {
                "id":$scope.casModel.product_id,
                "category_id":1,"assort":$scope.group.group, "module":"product", "r":"RLogProduct"
            };
            modelinfo.addLog(params).then(function(data){
                console.log("lskdf");
            });
        };

        $scope.verify = function(){
            var params = {
                "label":"审核",
                "assort":$scope.group.group,
                "id":$scope.casModel.product_id
            };

            if ($scope.group.group == "basic") {
                if ($scope.casModel.basic_submit_time == 0) {
                    params['state'] = "-1";
                } else {
                    params['state'] = $scope.casModel.basic_verify > 1 ? "1":$scope.casModel.basic_verify;
                }
            } else {
                if ($scope.casModel.skill_submit_time == 0) {
                    params['state'] = "-1";
                } else {
                    params['state'] = $scope.casModel.skill_verify > 1 ? "1":$scope.casModel.skill_verify;
                }
            }

            modelinfo.verify(params).then(function(data){
                if(data.error) {

                } else {
                    if ($scope.group.group == "basic") {
                        $scope.casModel.basic_verify = data;
                    } else {
                        $scope.casModel.skill_verify = data;
                    }
                }
            });
        };


        $scope.deleteModel = function(){
            if (confirm("确定要删除吗?") !== true ) {
                return;
            }

            $.ajax({'type':'get', 'dataType':'json', 'url':'product/delete',
                'data':{
                    'id':$scope.casModel.product_id
                },
                'success':function(data){
                    window.opener=null;
                    window.open('','_self');
                    window.close();
                    localStorage.setItem('refreshProductList', $scope.casModel.product_id);
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
            if ($stateParams.id != "new") {
                function response_product(product){
                    $scope.casModel = $filter("formatAssortFields")($scope.allassorts, product.get());
                    $scope.assignFootInfo($scope.casModel);
                }
                modelinfo.product_info({'product_id':$stateParams.id}).then(response_product);
            }
        });
        $scope.selectGroup($scope.groups[0]);
    });

</script>