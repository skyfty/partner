
app.directive("uiGroup",function($compile, $parse, $q) {
    return {
        link:function($scope, $element, $attrs) {

            var group_type_model = $parse($attrs.casModel + ".group_type");
            $scope.$watch(group_type_model, function(type) {
                if (angular.isUndefined(type))
                    return;

                var group = $parse($attrs.casModel)($scope);
                var data = {
                    "model":$attrs.casModel,
                };
                if (group.group_type == 1) {
                    data["fields"] = $attrs.casModel + ".content";
                    $element.html($compile($("#groupCondition").tmpl(data))($scope));
                } else {

                }
            }, true);

        }
    };
});