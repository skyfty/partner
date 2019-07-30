
app.directive("authority", function($parse, $compile) {
    return {
        link: function($scope, $element, $attrs) {
            var authority = $parse($attrs.authority);
            $scope.$watch(authority, function(newv, oldv) {
                if (newv === true) {
                    $element.addClass('hide');

                } else {
                    $element.removeClass('hide');
                }

            });

        }
    };
});