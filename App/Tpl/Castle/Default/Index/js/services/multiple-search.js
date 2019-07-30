'use strict';

angular.module('app').service('multipleSearch', ['$document','$modal', function ($document, $modal) {
    this.open = function(fields, items) {
        var modalInstance = $modal.open({
            templateUrl: 'multipleSearch.html',
            controller:  ['$scope', '$modalInstance', 'items', function($scope, $modalInstance, items) {
                $scope.fields = fields;
                $scope.items = items;
                $scope.ok = function () {
                    $modalInstance.close($scope.items);
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
                $scope.deleteItem = function (k) {
                    $scope.items.splice(k,1);
                };
                $scope.addCondition = function(){
                    items.push({});
                }
            }],
            size: "lg",
            resolve: {
                items: function () {
                    return items || [];
                },
                fields:function(){
                    return fields;
                }
            }
        });
        return modalInstance.result;
    };

}]);