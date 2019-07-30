/**
 * Created by sky on 17/12/26.
 */

app.controller('datatableFilterController', ['$scope','$state', '$modal', '$log','$timeout', function($scope, $state, $modal, $log,$timeout) {
    $scope.filterHide = true;
    $scope.toggle = function () {
        $scope.filterHide = !$scope.filterHide;
        $timeout(function () {
            $('#filter').chosen({
                placeholder_text_multiple:"选择过滤项...",
                display_selected_options:false,
                display_disabled_options:false,
            });
        }, 0);
    };
    $scope.dff = {};

    $scope.datatablesearch = function(d){
        console.log("lskfd");
    };
}]);
