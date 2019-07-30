angular.module('app')
    .directive('uiButterbar', ['$rootScope', '$anchorScroll', function($rootScope, $anchorScroll) {
        return {
            restrict: 'AC',
            template:'<span class="bar"></span>',
            link: function(scope, el, attrs) {
                el.addClass('butterbar hide');
                scope.$on('$stateChangeStart', function(event) {
                    $anchorScroll();
                    el.removeClass('hide').addClass('active');
                });
                scope.$on('$stateChangeSuccess', function( event, toState, toParams, fromState ) {
                    event.targetScope.$watch('$viewContentLoaded', function(){
                        el.addClass('hide').removeClass('active');
                    })
                });

                scope.$on('butterbarEvent', function(event, args) {
                    if (args.show == true) {
                        $anchorScroll();
                        el.removeClass('hide').addClass('active');
                    } else {
                        el.addClass('hide').removeClass('active');
                    }
                });
            }
        };
    }]);