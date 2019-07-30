/**
 * Created by feiti on 2018-01-03.
 */
app.controller('groupController', function($scope, $filter, $stateParams, $compile,modelinfo) {
    $scope.createGroup = function(){
        var group = {
            name: '新分组',
            group_type:1,
            content:[],
            editable:1,
            nameEditable:0,
            typeEditable:0,
            create_time: Date.now()
        };
        $scope.groups.push(group);
        $scope.selectGroup(group);
    };

    $scope.selectGroup = function(group){
        angular.forEach($scope.groups, function(group) {
            group.selected = false;
        });
        if ($scope.$parent.group != group) {
            $scope.$parent.group = group;
            $scope.$parent.group.selected = true;
        } else {
            $scope.$parent.group = null;
        }
    };

    $scope.editGroupName = function(group){
        if(group && group.selected && group.editable){
            group.nameEditable = true;
        }
    };
    $scope.editGroupType = function(group){
        if(group && group.selected && group.editable){
            group.typeEditable = true;
        }
    };
    $scope.editGroup = function(group) {
        angular.forEach($scope.groups, function(group) {
            group.editable = false;
        });
        group.editable = true;
    };

});