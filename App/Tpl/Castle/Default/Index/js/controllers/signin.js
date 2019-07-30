'use strict';

/* Controllers */
// signin controller
app.controller('SigninFormController', ['$scope', '$http', '$state', function($scope, $http, $state) {
    $scope.user = {};
    $scope.authError = null;
    $scope.login = function() {
        $scope.authError = null;
        // Try to login
        $.post('/Index/login',
            {
                name: $scope.user.name,
                password: $scope.user.password
            },
            function(data) {
                if ( data.user ) {
                    $state.go('index.dashboard-v1');
                }else{
                    $scope.authError = data;
                }
            }
        ).error(function() {
            $scope.authError = 'Server Error';
        });
    };
}])
;