<?php if (!defined('THINK_PATH')) exit();?><div  ng-controller="tradeDialogController">
    <div class="modal-header">
        <h3 class="modal-title">产品列表</h3>
    </div>
    <div ui-butterbar ng-state="butterbar" class="active" style="z-index:9999"></div>
    <div class="modal-body">
        <table ui-static-datatable="$parent.items" ui-static-datatable-fields="fields"  ng-data-url="/trade/index" ng-type="radio" class="table table-striped b-t b-light" style="white-space: nowrap;">
            <thead>
            <tr>
                <th ng-field="trade_id" searchable="false" orderable="false">选择</th>
                <th ng-field="orderid">编号</th>
                <th ng-field="serve_name">产品名称</th>
                <th ng-field="corre_id">相关方</th>
                <th ng-field="sum_price">总金额</th>
                <th ng-field="surplus_price">未付金额</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button class="btn btn-default" ng-click="cancel()">取消</button>
        <button class="btn btn-primary" ng-click="ok()">确定</button>
    </div>
</div>
<script>
    app.controller('tradeDialogController', function($scope, $state) {
        $scope.fields = <?php echo (json_encode($field_array)); ?>;
        $scope.datatablesearch = function(d){
            $.extend(d, $scope.$parent.params.r);
            d.query = $scope.$parent.params.q;
        };
    });
</script>