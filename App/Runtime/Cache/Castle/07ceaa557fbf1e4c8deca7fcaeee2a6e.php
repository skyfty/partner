<?php if (!defined('THINK_PATH')) exit();?><div  ng-controller="customerDialogController">
    <div class="modal-header">
        <h3 class="modal-title">客户列表</h3>
    </div>
    <div ui-butterbar ng-state="butterbar" class="active" style="z-index:9999"></div>
    <div class="modal-body">
        <table ui-static-datatable="$parent.items" ui-static-datatable-fields="fields"  ng-data-url="/customer/index" ng-type="radio" class="table table-striped b-t b-light" style="white-space: nowrap;">
            <thead>
            <tr>
                <th ng-field="staff_id" searchable="false" orderable="false">选择</th>
                <th ng-field="idcode">编号</th>
                <th ng-field="name">名称</th>
                <th ng-field="name">岗位</th>
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
    app.controller('customerDialogController', function($scope, $state) {
        $scope.fields = <?php echo (json_encode($field_array)); ?>;
        $scope.datatablesearch = function(d){
            d.query = $scope.$parent.params;
        };
    });
</script>