
app.directive("associate", function() {
    return {
        templateUrl:function($element, $attrs){
            return $attrs.associate;
        },
        controller: function($scope,$element,$attrs, $filter, $compile,$parse){
            $scope.related = $parse($attrs.related)($scope);
            $scope.related_model = $parse($attrs.module)($scope);
            $scope.related_model_id = $scope.related[$scope.related_model + "_id"];
        }
    };
});
