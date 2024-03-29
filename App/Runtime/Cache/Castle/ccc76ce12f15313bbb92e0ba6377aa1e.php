<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" data-ng-app="app">
<head>
    <meta charset="utf-8" />
    <title>Be Angular | Bootstrap Admin Web App with AngularJS</title>
    <meta name="description" content="app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/css/simple-line-icons.css" type="text/css" />
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/css/font.css" type="text/css" />
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/css/app.css" type="text/css" />

    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/vendor/jquery/datatables/css/dataTables.bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/vendor/jquery/toolbar/jquery.toolbar.css" type="text/css" />
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/vendor/jquery/fileupload/css/jquery.fileupload.css" type="text/css" />
    <link rel="stylesheet" href='App/Tpl/Castle/Default/Index/vendor/modules/angularjs-toaster/toaster.css'  type="text/css">
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/vendor/modules/angular-xeditable/css/xeditable.css" type="text/css" />
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/vendor/jquery/lightbox/css/lightbox.css" type="text/css">
    <link rel="stylesheet" href='App/Tpl/Castle/Default/Index/vendor/jquery/chosen/chosen.css' type="text/css">
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/vendor/jquery/datepicker/css/bootstrap-datepicker3.css" type="text/css">
    <link rel="stylesheet" href="App/Tpl/Castle/Default/Index/vendor/jquery/bspselect/css/bootstrap-select.css" type="text/css">

</head>


<body ng-controller="AppCtrl">
<div class="app" id="app" ng-class="{'app-header-fixed':app.settings.headerFixed, 'app-aside-fixed':app.settings.asideFixed, 'app-aside-folded':app.settings.asideFolded, 'app-aside-dock':app.settings.asideDock, 'container':app.settings.container}" ui-view></div>


<!-- jQuery -->
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/jquery.min.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/bootstrap.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/bootstrap-paginator.min.js"></script>

<!-- Angular -->
<script src="App/Tpl/Castle/Default/Index/vendor/angular/angular.js"></script>


<script src="App/Tpl/Castle/Default/Index/vendor/angular/angular-animate/angular-animate.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/angular/angular-cookies/angular-cookies.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/angular/angular-resource/angular-resource.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/angular/angular-sanitize/angular-sanitize.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/angular/angular-touch/angular-touch.js"></script>
<!-- Vendor -->
<script src="App/Tpl/Castle/Default/Index/vendor/angular/angular-ui-router/angular-ui-router.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/angular/ngstorage/ngStorage.js"></script>

<!-- bootstrap -->
<script src="App/Tpl/Castle/Default/Index/vendor/angular/angular-bootstrap/ui-bootstrap-tpls.js"></script>
<!-- lazyload -->
<script src="App/Tpl/Castle/Default/Index/vendor/angular/oclazyload/ocLazyLoad.js"></script>

<script src="App/Tpl/Castle/Default/Index/vendor/jquery/datatables/js/jquery.dataTables.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/datatables/js/dataTables.bootstrap.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/toolbar/jquery.toolbar.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/modules/angular-ui-checklist/checklist.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/libs/moment.min.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/modules/ng-flow/ng-flow-standalone.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/modules/angular-file-upload/angular-file-upload.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/fusty-flow/fusty-flow-factory.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/fusty-flow/fusty-flow.js"></script>
<script src='App/Tpl/Castle/Default/Index/vendor/modules/angularjs-toaster/toaster.js'></script>
<script src="App/Tpl/Castle/Default/Index/vendor/modules/angular-xeditable/js/xeditable.js"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/lightbox/js/lightbox.js" type="text/javascript"></script>
<script src='App/Tpl/Castle/Default/Index/vendor/jquery/chosen/chosen.jquery.min.js' type="text/javascript"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="App/Tpl/Castle/Default/Index/vendor/jquery/bspselect/js/bootstrap-select.js" type="text/javascript"></script>


<link  href="__PUBLIC__/cropper/cropper.css" rel="stylesheet">
<script type="text/javascript" src="__PUBLIC__/cropper/cropper.js"></script>

<script type="text/javascript" src="__PUBLIC__/javascript-load-image/js/load-image.all.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/jquery-webcam/jquery.webcam.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.artDialog.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/js/iframeTools.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/uploadPreview.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.tmpl.min.js?t=20140830"></script>
<script type="text/javascript" src="__PUBLIC__/js/formValidatorRegex.js?t=20140830"></script>

<!-- App -->
<script src="App/Tpl/Castle/Default/Index/js/app.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/config.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/config.lazyload.js"></script>

<script>
    'use strict';

    /**
     * Config for the router
     */
    function initStateProvider($stateProvider,   $urlRouterProvider) {
        $urlRouterProvider.otherwise('<?php echo ($default_index); ?>');
        $stateProvider
                .state('index', {
                    abstract: true,
                    url: '/index',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/index.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function( $ocLazyLoad){
                                return $ocLazyLoad.load([
                                    'App/Tpl/Castle/Default/Index/js/controllers/filter.js',
                                    'App/Tpl/Castle/Default/Index/js/controllers/group.js',
                                    'ui.select',
                                    'toaster'
                                ]);
                            }]
                    }
                })
                .state('index.product', {
                    url: '/product',
                    templateUrl: 'index/product'
                })
                .state('index.customer', {
                    url: '/customer',
                    templateUrl: 'index/customer'
                })
                .state('logs', {
                    abstract: true,
                    url: '/logs',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/logs.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function( $ocLazyLoad){
                                return $ocLazyLoad.load([
                                    'App/Tpl/Castle/Default/Index/js/controllers/filter.js',
                                    'ui.select',
                                    'toaster'
                                ]);
                            }]
                    }
                })
                .state('logs.product', {
                    url: '/product',
                    templateUrl: 'index/product_logs'
                })
                .state('logs.customer', {
                    url: '/customer',
                    templateUrl: 'index/customer_logs'
                })
                .state('view', {
                    url: '/view',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/view.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function( $ocLazyLoad){
                                return $ocLazyLoad.load([
                                    'angularFileUpload',
                                    'toaster',
                                    'App/Tpl/Castle/Default/Index/js/controllers/detailed.js'
                                ]);
                            }]
                    },
                    controller: 'detailedController'
                })
                .state('view.product', {
                    url: '/product/:id',
                    views: {
                        "":{templateUrl: 'index/product_view'},
                        "footer": {templateUrl: 'App/Tpl/Castle/Default/Index/tpl/product_footview.html'}
                    },
                    params:{id:null},
                    resolve: {
                        deps: ['$ocLazyLoad', 'uiLoad',
                            function( $ocLazyLoad, uiLoad ){
                                return uiLoad.load(
                                        ['App/Tpl/Castle/Default/Index/vendor/jquery/fullcalendar/fullcalendar.css',
                                            'App/Tpl/Castle/Default/Index/vendor/jquery/fullcalendar/theme.css',
                                            'App/Tpl/Castle/Default/Index/vendor/jquery/jquery-ui-1.10.3.custom.min.js',
                                            'App/Tpl/Castle/Default/Index/vendor/jquery/fullcalendar/fullcalendar.min.js',
                                            'App/Tpl/Castle/Default/Index/js/controllers/calendar.js']
                                ).then(
                                        function(){
                                            return $ocLazyLoad.load('ui.calendar');
                                        }
                                )
                            }]
                    }
                })
                .state('view.appraisal', {
                    url: '/appraisal/:product_id/:id',
                    views: {
                        "":{templateUrl: 'index/product_appraisal'},
                        "footer": {templateUrl: 'App/Tpl/Castle/Default/Index/tpl/product_footview.html'}
                    },
                    params:{
                        id:null,
                        product_id:null
                    }
                })
                .state('view.customer', {
                    url: '/customer/:id',
                    views: {
                        "":{templateUrl: 'index/customer_view'},
                        "footer": {templateUrl: 'App/Tpl/Castle/Default/Index/tpl/customer_footview.html'}
                    },
                    params:{id:null},
                    resolve: {
                        deps: ['$ocLazyLoad', 'uiLoad',
                            function( $ocLazyLoad, uiLoad ){
                                return uiLoad.load(
                                        ['App/Tpl/Castle/Default/Index/vendor/jquery/fullcalendar/fullcalendar.css',
                                            'App/Tpl/Castle/Default/Index/vendor/jquery/fullcalendar/theme.css',
                                            'App/Tpl/Castle/Default/Index/vendor/jquery/jquery-ui-1.10.3.custom.min.js',
                                            'App/Tpl/Castle/Default/Index/vendor/jquery/fullcalendar/fullcalendar.min.js',
                                            'App/Tpl/Castle/Default/Index/js/controllers/calendar.js']
                                ).then(
                                        function(){
                                            return $ocLazyLoad.load('ui.calendar');
                                        }
                                )
                            }]
                    }
                })
                .state('view.account', {
                    url: '/account/:id/:dire/:type/:clause_additive',
                    views: {
                        "":{templateUrl: function(params){
                            return 'index/account_view/dire/' + params.dire + "/type/" + params.type;
                        }},
                        "footer": {templateUrl: function(params){
                            return 'App/Tpl/Castle/Default/Index/tpl/'+params['type']+'_footview.html';
                        }}
                    },
                    params:{
                        id:null, type:null, dire:null, clause_additive:null
                    }
                })
                .state('access', {
                    url: '/access',
                    template: '<div ui-view class="fade-in-right-big smooth"></div>'
                })
                .state('access.signin', {
                    url: '/signin',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/signin.html',
                    resolve: {
                        deps: ['uiLoad',
                            function( uiLoad ){
                                return uiLoad.load( ['App/Tpl/Castle/Default/Index/js/controllers/signin.js'] );
                            }]
                    }
                })
                .state('access.signup', {
                    url: '/signup',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/signup.html',
                    resolve: {
                        deps: ['uiLoad',
                            function( uiLoad ){
                                return uiLoad.load( ['App/Tpl/Castle/Default/Index/js/controllers/signup.js'] );
                            }]
                    }
                })
                .state('access.forgotpwd', {
                    url: '/forgotpwd',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/forgotpwd.html'
                })
                .state('access.404', {
                    url: '/404',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/404.html'
                })
                .state('index.page', {
                    url: '/page',
                    template: '<div ui-view class="fade-in-down"></div>'
                })
                .state('index.docs', {
                    url: '/docs',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/docs.html'
                })
                .state('lockme', {
                    url: '/lockme',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/page_lockme.html'
                })
                .state('index.dashboard-v1', {
                    url: '/dashboard-v1',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/dashboard_v1.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function( $ocLazyLoad ){
                                return $ocLazyLoad.load(['App/Tpl/Castle/Default/Index/js/controllers/chart.js']);
                            }]
                    }
                })
                .state('index.dashboard-v2', {
                    url: '/dashboard-v2',
                    templateUrl: 'App/Tpl/Castle/Default/Index/tpl/dashboard_v2.html',
                    resolve: {
                        deps: ['$ocLazyLoad',
                            function( $ocLazyLoad ){
                                return $ocLazyLoad.load(['App/Tpl/Castle/Default/Index/js/controllers/chart.js']);
                            }]
                    }
                })

    }
    angular.module('app').run(['editableOptions','$rootScope', '$state', '$stateParams',appRunCallback]).config(['$stateProvider', '$urlRouterProvider',initStateProvider]);

    function AppCtrlController($scope,   $localStorage,   $window ) {
        // add 'ie' classes to html
        var isIE = !!navigator.userAgent.match(/MSIE/i);
        isIE && angular.element($window.document.body).addClass('ie');
        isSmartDevice( $window ) && angular.element($window.document.body).addClass('smart');

        // config
        $scope.app = {
            name: 'ayihui',
            version: '1.3.3',
            // for chart colors
            color: {
                primary: '#7266ba',
                info:    '#23b7e5',
                success: '#27c24c',
                warning: '#fad733',
                danger:  '#f05050',
                light:   '#e8eff0',
                dark:    '#3a3f51',
                black:   '#1c2b36'
            },
            settings: {
                themeID: 1,
                navbarHeaderColor: 'bg-black',
                navbarCollapseColor: 'bg-white-only',
                asideColor: 'bg-black',
                headerFixed: true,
                asideFixed: false,
                asideFolded: false,
                asideDock: false,
                container: false
            }
        };

        // save settings to local storage
        if ( angular.isDefined($localStorage.settings) ) {
            $scope.app.settings = $localStorage.settings;
        } else {
            $localStorage.settings = $scope.app.settings;
        }
        $scope.$watch('app.settings', function(){
            if( $scope.app.settings.asideDock  &&  $scope.app.settings.asideFixed ){
                $scope.app.settings.headerFixed = true;
            }
            $localStorage.settings = $scope.app.settings;
        }, true);
    }
    angular.module('app').controller('AppCtrl', AppCtrlController);

</script>

<script src="App/Tpl/Castle/Default/Index/js/services/ui-load.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/services/model-info.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/services/multiple-search.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/filters/field-format.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/filters/form-input.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/setnganimate.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/authority.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/associate.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-butterbar.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-focus.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-datatable.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-fullscreen.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-jq.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-module.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-nav.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-scroll.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-shift.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-toggleclass.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-validate.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-branch.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-view.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-condition.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-group.js"></script>
<script src="App/Tpl/Castle/Default/Index/js/directives/ui-page.js"></script>

<script src="App/Tpl/Castle/Default/Index/js/area.js"></script>

<div class="text-center" ng-include="'App/Tpl/Castle/Default/Index/tpl/template.html'"></div>

<!-- Lazy loading -->
</body>
</html>