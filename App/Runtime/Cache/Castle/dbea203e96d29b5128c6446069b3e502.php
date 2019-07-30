<?php if (!defined('THINK_PATH')) exit();?>
<!-- hbox layout -->
<div class="hbox hbox-auto-xs bg-light"  ng-controller="productAppraisalViewController" ng-init="app.settings.asideFolded = true;app.settings.asideFixed = true;app.settings.asideDock = false;app.settings.container = false;app.hideFooter=true;app.hideAside = false">
    <!-- column -->
    <div class="col bg-white-only">
        <div class="vbox">
            <div class="wrapper-sm b-b">
                <div class="m-t-n-xxs m-b-n-xxs m-l-xs">
                    <a class="btn btn-xs btn-primary" ng-click="updateModel()" authority="casModel.permission.update"><i class="fa fa-save"></i> 保存</a>
                    <a class="btn btn-xs btn-danger pull-right" authority="casModel.permission.delete" ng-show="casModel.product_appraisal_id" ng-click="deleteModel()"><i class="fa fa-times"></i> 删除</a>
                </div>
            </div>
            <div class="row-row">
                <div class="cell">
                    <div class="cell-inner">
                        <div class="wrapper-lg fade-in-up">
                            <ui-model-update ng-fields="fields" cas-model="casModel"></ui-model-update>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var fieldGroup = <?php echo (json_encode($field_group)); ?>;
    app.controller('productAppraisalViewController', function($scope, $state, $filter, $stateParams, $compile,$parse,toaster,$timeout,modelinfo) {
        $scope.fields = fieldGroup;
        $scope.updateModel = function(){
            var data = $filter("convertFields")($scope.fields, $scope.casModel);
            data.product_id = $stateParams.product_id;

            $scope.syncData(data,"ProductAppraisal/update").then(function(product_appraisal){
                $scope.casModel = $filter("formatFields")($scope.fields, product_appraisal.get());
                localStorage.setItem('refreshProductAppraisalList', Math.random());
            })
        };

        $timeout(function() {
            function response_product(product){
                if ($stateParams.id != "new") {
                    modelinfo.product_appraisal({"product_appraisal_id":$stateParams.id}).then(function(product_appraisal){
                        $scope.casModel = $filter("formatFields")($scope.fields, product_appraisal.get());
                    });
                }
                $scope.assignFootInfo(product.get());
            }
            modelinfo.product_info({'product_id':$stateParams.product_id}).then(response_product);
        });
    });

</script>