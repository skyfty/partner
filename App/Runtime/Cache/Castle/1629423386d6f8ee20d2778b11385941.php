<?php if (!defined('THINK_PATH')) exit();?><!-- hbox layout -->
<div class="hbox hbox-auto-xs bg-light"  ng-controller="accountViewController" ng-init="app.settings.asideFolded = true;app.settings.asideFixed = true;app.settings.asideDock = false;app.settings.container = false;app.hideFooter=true;app.hideAside = false">
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
    console.log("sldjflds");
    app.controller('accountViewController', function($scope, $state, $filter, $stateParams, $compile,$parse,toaster,$timeout,modelinfo) {
        var inflow_info = {"product":"雇员", "customer":"客户", "staff":"员工"};

        function showMarketRelatedHtml(ff, related, inflow) {
            ff.form_type = "market";
            ff.field = "market_id";
            ff.name = "客户服务订单";
            ff.onActive =  function() {
                var req = "MarketView";
                switch($stateParams.type) {
                    case "product": {
                        req = "MarketProductView";
                        break;
                    }
                    case "channel": {
                        req = "MarketChannelView";
                        break;
                    }
                    case "urge": {
                        req = "MarketUrgeView";
                        break;
                    }
                }

                var query = "market.settle_state = 918";
                switch($scope.casModel.clause_type_id) {
                    case "240":case "242": case "238":{
                    if($stateParams.clause_additive){
                        req = "MarketChannelView";
                        query += " and " + "market_channel.channel_model='"+$stateParams.type + "' and market_channel.channel_model_id=" + $stateParams.clause_additive;
                    }
                    break;
                }
                    case "244":{
                        if($stateParams.clause_additive){
                            query += " and " + "staff_id="+$stateParams.clause_additive;
                        }
                        break;
                    }
                    default: {
                        if($stateParams.clause_additive){
                            query += " and " + $stateParams.type + "." + $stateParams.type + "_id="+$stateParams.clause_additive;
                        }
                        break;
                    }
                }
                return {r:{"dataview":req}, q:query};
            };
            ff.onSelected = function(){
                var clause_type = $scope.casModel.clause_type_id;
                if (inflow && inflow != "inernal") {
                    if (inflow == "customer"){
                        if (clause_type == "236" || clause_type == "250" || clause_type == "222") {
                            $scope.casModel.customer_id= $scope.casModel.market_id.customer_id;
                        }
                    }
                }
            };
            $scope.fields.splice(2, 0, angular.copy(ff));

            if (inflow && inflow != "inernal") {
                ff.form_type = inflow;
                ff.field = inflow + "_id";
                ff.name = inflow_info[inflow];
                ff.onActive =  function() {
                    var clause_type = $scope.casModel.clause_type_id;
                    var model = "",query = "";
                    var market_id = $scope.casModel.market_id.market_id;
                    switch(inflow) {
                        case "customer": {
                            model = "Customer";
                            switch(clause_type) {
                                case "222":{
                                    model = "MarketChannelCustomer";
                                    query ="market_channel.channel_model='customer' and market.market_id=" + market_id;
                                    break;
                                }
                            }
                            break;
                        }
                        case "product": {
                            model = "Market";
                            query = "market.market_id=" + market_id;

                            switch(clause_type) {
                                case "224":{
                                    model = "MarketChannelProduct";
                                    query ="market_channel.channel_model='product' and market.market_id=" + market_id;
                                    break;
                                }

                                case "252":
                                case "217":{
                                    model = "MarketProduct";
                                    query = "market.market_id=" + market_id;
                                    break;
                                }
                            }
                            break;
                        }
                        case "staff": {
                            model = "Staff";
                            switch(clause_type) {
                                case "228":{
                                    model = "MarketChannelStaff";
                                    query ="market_channel.channel_model='staff' and market.market_id=" + market_id;
                                    break;
                                }
                                case "225":{
                                    model = "MarketUrge";
                                    query ="market.market_id=" + market_id;
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    return {r:{"dataview":model}, q:query};
                };
                $scope.fields.splice(3, 0, angular.copy(ff));

                if (inflow == "product") {
                    ff.form_type = "market_product";
                    ff.field = "market_product_id";
                    ff.name = inflow_info[inflow];
                    ff.onActive =  function() {

                    };
                    $scope.fields.splice(4, 0, angular.copy(ff));
                }
            }
        }

        function showTradeRelatedHtml(ff, related, inflow) {
            ff.form_type = "trade";
            ff.field = "trade_id";
            ff.name = "产品订单";
            ff.onActive =  function() {
                var query;
                var TradeView = "TradeView";
                switch($stateParams.type) {
                    case "customer": {
                        TradeView = "CustomerTradeView";
                        query = "customer.customer_id=" + $stateParams.clause_additive;
                        break;
                    }
                    case "product": {
                        TradeView = "ProductTradeView";
                        query = "product.product_id=" + $stateParams.clause_additive;
                        break;
                    }
                    case "staff": {
                        TradeView = "StaffTradeView";
                        query = "staff.staff_id=" + $stateParams.clause_additive;
                        break;
                    }
                }

                return {r:{"dataview":TradeView}, q:query};
            };
            $scope.fields.splice(2, 0, angular.copy(ff));
        }

        function showCultivateRelatedHtml(ff, related, inflow) {
            ff.form_type = "cultivate";
            ff.field = "cultivate_id";
            ff.name = "培训订单";
            ff.onActive =  function() {
                var req = "CultivateView";
                switch($stateParams.type) {
                    case "channel": {
                        req = "CultivateChannelView";
                        break;
                    }
                    case "urge": {
                        req = "CultivateUrgeView";
                        break;
                    }
                }

                var query = "cultivate.settle_state = 918";
                switch($scope.casModel.clause_type_id) {
                    case "279":case "277": case "275":{
                        if($stateParams.clause_additive){
                            query += " and " + "cultivate_channel.channel_role_model='"+$stateParams.type + "' and cultivate_channel.channel_model_id=" + $stateParams.clause_additive;
                        }
                        break;
                    }
                    case "273":{
                        if($stateParams.clause_additive){
                            req = "CultivateUrgeView";
                            query += " and " + "staff_id="+$stateParams.clause_additive;
                        }
                        break;
                    }
                    default: {
                        if($stateParams.clause_additive){
                            query += " and cultivate.model='" + $stateParams.type + "' and cultivate.model_id="+$stateParams.clause_additive;
                        }
                        break;
                    }
                }
                return {r:{"dataview":req}, q:query};
            };
            $scope.fields.splice(2, 0, angular.copy(ff));

            if (inflow && inflow != "inernal") {
                ff.form_type = inflow;
                ff.field = inflow + "_id";
                ff.name = inflow_info[inflow];
                ff.onActive =  function() {
                    var clause_type = $scope.casModel.clause_type_id;
                    var model = "",query = "";
                    var cultivate_id = $scope.casModel.cultivate_id.cultivate_id;
                    switch(inflow) {
                        case "customer": {
                            model = "CustomerCultivate";
                            switch(clause_type) {
                                case "277":{
                                    model = "CultivateChannelCustomer";
                                    query ="cultivate_channel.channel_role_model='3' and cultivate.cultivate_id=" + cultivate_id;
                                    break;
                                }
                            }
                            break;
                        }
                        case "product": {
                            model = "ProductCultivate";
                            query = "cultivate.cultivate_id=" +cultivate_id;

                            switch(clause_type) {
                                case "279":{
                                    model = "CultivateChannelProduct";
                                    query ="cultivate_channel.channel_role_model='2' and cultivate.cultivate_id=" + cultivate_id;
                                    break;
                                }
                            }
                            break;
                        }
                        case "staff": {
                            model = "StaffCultivate";
                            switch(clause_type) {
                                case "275":{
                                    model = "CultivateChannelStaff";
                                    query ="cultivate_channel.channel_role_model='4' and cultivate.cultivate_id=" + cultivate_id;
                                    break;
                                }
                                case "265":{
                                    model = "CultivateUrge";
                                    query ="cultivate.cultivate_id=" + cultivate_id;
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    return {r:{"dataview":model}, q:query};
                };
                $scope.fields.splice(3, 0, angular.copy(ff));
            }
        }

        function showRelatedHtml(related, inflow) {
            var ff = {
                model:"account",
                in_verify:"1",
                is_null:"0"
            };
            switch (related) {
                case "market": {
                    showMarketRelatedHtml(ff, related, inflow);
                    break;
                }
                case "trade": {
                    showTradeRelatedHtml(ff, related, inflow);
                    break;
                }
                case "cultivate": {
                    showCultivateRelatedHtml(ff, related, inflow);
                    break;
                }
            }
        }

        $scope.$watch("casModel.clause_type_id", function(v){
            if ($scope.casModel) {
                $scope.casModel = {clause_type_id:$scope.casModel.clause_type_id};
            }
            $scope.fields = angular.copy(fieldGroup);
            if (angular.isDefined(v)){
                var clause_type = $scope.fields[1].data[v];
                var related = (clause_type.related_model == ""?clause_type.inflow_model:clause_type.related_model);
                var inflow = clause_type.inflow_model;
                showRelatedHtml(related, inflow);
            }
        });

        $timeout(function() {
            function response_product(product){
                if ($stateParams.id != "new") {
                }
                $scope.assignFootInfo(product.get());
            }
            modelinfo.product_info({'product_id':$stateParams.clause_additive}).then(response_product);
        });
    });

</script>