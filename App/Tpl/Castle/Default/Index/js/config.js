// config
var app = angular.module('app')
  .config(
    [
        'flowFactoryProvider',
        '$controllerProvider',
        '$compileProvider',
        '$filterProvider',
        '$provide',
    function (flowFactoryProvider, $controllerProvider,   $compileProvider,   $filterProvider,   $provide) {
        
        // lazy controller, directive and service
        app.controller = $controllerProvider.register;
        app.directive  = $compileProvider.directive;
        app.filter     = $filterProvider.register;
        app.factory    = $provide.factory;
        app.service    = $provide.service;
        app.constant   = $provide.constant;
        app.value      = $provide.value;

        flowFactoryProvider.defaults = {
            target: '',
            permanentErrors: [500, 501],
            maxChunkRetries: 1,
            chunkRetryInterval: 5000,
            simultaneousUploads: 1
        };
        flowFactoryProvider.on('catchAll', function (event) {
            console.log('catchAll', arguments);
        });
        // Can be used with different implementations of Flow.js
        flowFactoryProvider.factory = fustyFlowFactory;
    }
  ]);