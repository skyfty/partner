<?php if (!defined('THINK_PATH')) exit();?><div  ng-controller="cultivateDialogController">
    <div class="modal-header">
        <h3 class="modal-title">培训列表</h3>
    </div>
    <div ui-butterbar ng-state="butterbar" class="active" style="z-index:9999"></div>
    <div class="modal-body">
        <table ui-static-datatable="$parent.items" ui-static-datatable-fields="fields"  ng-data-url="/cultivate/index" ng-type="radio" class="table table-striped b-t b-light" style="white-space: nowrap;">
            <thead>
            <tr>
                <th ng-field="cultivate_id" searchable="false" orderable="false">选择</th>
                <th ng-field="cultivate_idcode">编号</th>
                <th ng-field="category_id">类别</th>
                <th ng-field="status_id">状态</th>
                <th ng-field="settle_state">结算状态</th>
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
    app.controller('cultivateDialogController', function($scope, $state) {
        $scope.fields = <?php echo (json_encode($field_array)); ?>;
        $scope.datatablesearch = function(d){
            $.extend(d, $scope.$parent.params.r);
            d.query = $scope.$parent.params.q;
        };
    });
</script>