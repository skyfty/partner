<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" data-ng-app="app">
<head>
    <meta charset="utf-8" />
    <title>Be Angular | Bootstrap Admin Web App with AngularJS</title>
    <meta name="description" content="app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="/App/Tpl/Castle/Default/Index/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="/App/Tpl/Castle/Default/Index/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="/App/Tpl/Castle/Default/Index/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="/App/Tpl/Castle/Default/Index/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="/App/Tpl/Castle/Default/Index/css/font.css" type="text/css" />
    <link rel="stylesheet" href="/App/Tpl/Castle/Default/Index/css/app.css" type="text/css" />
</head>
<body ng-controller="AppCtrl">
<div class="app" id="app"  ui-view="table-block"></div>

<!-- jQuery -->
<script src="/App/Tpl/Castle/Default/Index/vendor/jquery/jquery.min.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/jquery/bootstrap.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/angular/angular.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/angular/angular-animate/angular-animate.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/angular/angular-cookies/angular-cookies.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/angular/angular-resource/angular-resource.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/angular/angular-sanitize/angular-sanitize.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/angular/angular-touch/angular-touch.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/angular/angular-ui-router/angular-ui-router.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/angular/ngstorage/ngStorage.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/angular/angular-bootstrap/ui-bootstrap-tpls.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/jquery/datatables/js/jquery.dataTables.min.js"></script>
<script src="/App/Tpl/Castle/Default/Index/vendor/jquery/datatables/js/dataTables.bootstrap.js"></script>

<link rel="stylesheet" href="/App/Tpl/Castle/Default/Index/vendor/jquery/datatables/css/dataTables.bootstrap.css" type="text/css" />
<script src="/App/Tpl/Castle/Default/Index/vendor/libs/moment.min.js"></script>

<script type="text/javascript" src="__PUBLIC__/js/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/js/iframeTools.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.tmpl.min.js?t=20140830"></script>

<script>
    var model = "<?php echo ($model); ?>";
    angular.module('app', [
        'ngAnimate',
        'ngCookies',
        'ngResource',
        'ngSanitize',
        'ngTouch',
        'ngStorage',
        'ui.router',
    ]).run(['$rootScope', '$state', '$stateParams',function($rootScope,   $state,   $stateParams) {
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;
    }]).config(['$stateProvider', '$urlRouterProvider',function($stateProvider,   $urlRouterProvider) {
        $urlRouterProvider.otherwise('index');
        $stateProvider.state('index',
                {
                    url: '/index',
                    views:{
                        'table-block':{
                            templateUrl: '/App/Tpl/Castle/Default/'+model+'/listdialog.html'
                        }
                    },
                });
    }]).controller('AppCtrl', function($scope,   $localStorage,   $window ) {
        var isIE = !!navigator.userAgent.match(/MSIE/i);
        isIE && angular.element($window.document.body).addClass('ie');
        $scope.app = {
            settings: {
                themeID: 1,
            }
        };
        if ( angular.isDefined($localStorage.settings) ) {
            $scope.app.settings = $localStorage.settings;
        }
    });
</script>
<script src="/App/Tpl/Castle/Default/Index/js/services/model-info.js"></script>
<script src="/App/Tpl/Castle/Default/Index/js/directives/ui-datatable.js"></script>
<script src="/App/Tpl/Castle/Default/Index/js/condition.js"></script>

<!-- Lazy loading -->
</body>
</html>