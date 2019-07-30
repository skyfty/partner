'use strict';

angular.module('app').service('modelinfo', ['$document', '$timeout','$modal','$q', function ($document, $timeout,$modal,$q) {

    this.model_info = function(url, p){
        var deferred = $q.defer();
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':url,
            'data':{"forces":p,draw:"1"},
            'success':function(data){
                data.get = function(idx){
                    idx = (idx ? idx : 0);
                    if (data.data && data.data.length >= idx) {
                        return data.data[idx];
                    } else {
                        return null;
                    }
                };
                deferred.resolve(data);
            }
        });
        return deferred.promise;
    };
    this.product_info = function(p) {
        return this.model_info('product/index', p);
    };

    this.cat_info = function(i, cb, cp) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'product/getcategory',
            'data':{
                "id":i
            },
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data[0], cp);
                }
            }
        });
    };

    this.skill_info = function(p,s, cb, cp) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'product/getskill',
            'data':{
                "product_id":p,
                "category_id":s
            },
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data, cp);
                }
            }
        });
    };

    this.select_product =function(params) {
        var modalInstance = $modal.open({
            templateUrl: 'index/product/tpl/product_dialog',
            controller:  ['$scope', '$modalInstance', 'items', function($scope, $modalInstance, items) {
                $scope.params = params;
                $scope.items = items;
                $scope.ok = function () {
                    $modalInstance.close($scope.items);
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            }],
            size: "lg",
            resolve: {
                items: function () {
                    return {};
                }
            }
        });
        return modalInstance.result;
    };

    this.staff_info =function (p, cb) {
        var self = this;
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'staff/getInfo',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    };

    this.select_staff =function(params) {
        var modalInstance = $modal.open({
            templateUrl: 'staffDialog.html',
            controller:  ['$scope', '$modalInstance', 'items', function($scope, $modalInstance, items) {
                $scope.params = params;
                $scope.items = items;
                $scope.ok = function () {
                    $modalInstance.close($scope.items);
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            }],
            size: "lg",
            resolve: {
                items: function () {
                    return {};
                }
            }
        });
        return modalInstance.result;
    };

    this.customer_info = function(p, cb) {
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'customer/getInfo',
            'data':p,
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    };

    this.select_customer=function (params) {
        var modalInstance = $modal.open({
            templateUrl: 'index/customer/tpl/customer_dialog',
            controller:  ['$scope', '$modalInstance', 'items', function($scope, $modalInstance, items) {
                $scope.params = params;
                $scope.items = items;
                $scope.ok = function () {
                    $modalInstance.close($scope.items);
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            }],
            size: "lg",
            resolve: {
                items: function () {
                    return {};
                }
            }
        });
        return modalInstance.result;
    };

    this.select_channel_related = function(channel,cb){
        var self = this;
        switch(channel.model) {
            case "渠道库":{
                break;
            }
            case "客户库":{
                return self.select_customer([]);
            }
            case "雇员库":{
                return self.select_product([]);
            }
            case "员工库":{
                return self.select_staff([]);
            }
        }
    };

    this.select_channel = function(channelModelId, cb){
        $.ajax({
            'type':'get',
            'dataType':'json',
            'url':'Channel/getInfo',
            'data':{"id":channelModelId},
            'success':function(data){
                if(data.status == 1){
                    if (cb) cb(data.data);
                }
            }
        });
    };

    this.select_branch=function (params) {
        var modalInstance = $modal.open({
            templateUrl: 'branchDialog.html',
            controller:  ['$scope', '$modalInstance', 'items', function($scope, $modalInstance, items) {
                $scope.params = params;
                $scope.items = items;
                $scope.ok = function () {
                    $modalInstance.close($scope.items);
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            }],
            size: "lg",
            resolve: {
                items: function () {
                    return {};
                }
            }
        });
        return modalInstance.result;
    };


    this.select_market=function (params) {
        var modalInstance = $modal.open({
            templateUrl: 'index/market/tpl/market_dialog',
            controller:  ['$scope', '$modalInstance', 'items', function($scope, $modalInstance, items) {
                $scope.params = params;
                $scope.items = items;
                $scope.ok = function () {
                    $modalInstance.close($scope.items);
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            }],
            size: "lg",
            resolve: {
                items: function () {
                    return {};
                }
            }
        });
        return modalInstance.result;
    };



    this.select_cultivate=function (params) {
        var modalInstance = $modal.open({
            templateUrl: 'index/cultivate/tpl/cultivate_dialog',
            controller:  ['$scope', '$modalInstance', 'items', function($scope, $modalInstance, items) {
                $scope.params = params;
                $scope.items = items;
                $scope.ok = function () {
                    $modalInstance.close($scope.items);
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            }],
            size: "lg",
            resolve: {
                items: function () {
                    return {};
                }
            }
        });
        return modalInstance.result;
    };

    this.select_trade=function (params) {
        var modalInstance = $modal.open({
            templateUrl: 'index/trade/tpl/trade_dialog',
            controller:  ['$scope', '$modalInstance', 'items', function($scope, $modalInstance, items) {
                $scope.params = params;
                $scope.items = items;
                $scope.ok = function () {
                    $modalInstance.close($scope.items);
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            }],
            size: "lg",
            resolve: {
                items: function () {
                    return {};
                }
            }
        });
        return modalInstance.result;
    };

    this.addLog=function (params) {
        var modalInstance = $modal.open({
            templateUrl: 'addLog.html',
            controller:  ['$scope', '$modalInstance','items', function($scope, $modalInstance, items) {
                $scope.params = params;
                $scope.items = items;
                $scope.ok = function () {
                    var data = $.extend(params, $scope.items);
                    $.ajax({ 'type':'post',  'dataType':'json', 'url':"log/add",
                        'data': data,
                        'success': function(data) {
                            $modalInstance.close(data);
                        },
                        'error':function(){
                            $modalInstance.close(data);
                        },
                        'beforeSend':function(){
                            $scope.$emit('butterbarEvent', { show: true });
                        },
                        'complete':function(){
                            $scope.$emit('butterbarEvent', { show: false });
                        }
                    });
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            }],
            resolve: {
                items: function () {
                    return {};
                }
            }
        });
        return modalInstance.result;
    };

    this.verify=function (params) {
        var modalInstance = $modal.open({
            templateUrl: 'verify.html',
            controller:  ['$scope', '$modalInstance','items', function($scope, $modalInstance, items) {
                $scope.params = params;
                $scope.items = items;
                $scope.ok = function () {
                    var data = $.extend(params, $scope.items);
                    $.ajax({ 'type':'post',  'dataType':'json',
                        'url':"product/doverify",
                        'data': data,
                        'success': function(data) {
                            $modalInstance.close(data);
                        },
                        'error':function(){
                            $modalInstance.close(data);
                        },
                        'beforeSend':function(){
                            $scope.$emit('butterbarEvent', { show: true });
                        },
                        'complete':function(){
                            $scope.$emit('butterbarEvent', { show: false });
                        }
                    });
                };
                $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                };
            }],
            resolve: {
                items: function () {
                    return {};
                }
            }
        });
        return modalInstance.result;
    };

    this.product_appraisal = function(p) {
        return this.model_info('ProductAppraisal/index', p);
    };
}]);