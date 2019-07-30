
angular.module('app').filter('propsFilter', function() {
    return function(items, props) {
        var out = [];

        if (angular.isArray(items)) {
            items.forEach(function(item) {
                var itemMatches = false;

                var keys = Object.keys(props);
                for (var i = 0; i < keys.length; i++) {
                    var prop = keys[i];
                    var text = props[prop].toLowerCase();
                    if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                        itemMatches = true;
                        break;
                    }
                }

                if (itemMatches) {
                    out.push(item);
                }
            });
        } else {
            // Let the output be the input untouched
            out = items;
        }

        return out;
    };
});

app.directive("uiBranch", function() {
    return {
        controller: function($scope,$element,$attrs, $filter, $compile,$parse){
            $scope.disabled = undefined;

            $scope.enable = function() {
                $scope.disabled = false;
            };

            $scope.disable = function() {
                $scope.disabled = true;
            };

            $scope.branchGroup = function (item){
                return item.type;
            };
            $scope.branch = {};

            $.ajax({'url': 'branch/index',
                'type': 'get',
                'dataType': 'json',
                'success': function (data) {
                    var branch = data.data || [];
                    branch.splice(0, 0, {"name":"总部","code":"zb"});
                    $scope.branchs = branch;
                }
            });
        }
    };
});
