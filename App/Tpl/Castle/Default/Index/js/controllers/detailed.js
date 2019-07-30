/**
 * Created by feiti on 2018-01-03.
 */
app.controller('detailedController', function($scope, $filter, $stateParams, $compile,FileUploader,$q) {

    $scope.initGroup = function(module, group){
        $scope.module = module;
        $scope.groups = group;
        var allassorts = [];
        angular.forEach($scope.groups, function(group) {
            if (group['assorts']) {
                allassorts = allassorts.concat(group['assorts']);
            }
        });

        $scope.allassorts = allassorts;
        $scope.assorts = allassorts;
        $scope.sections = [];
        return allassorts;
    };

    $scope.checkItem = function(obj, arr, key){
        var i=0;
        angular.forEach(arr, function(item) {
            if(item[key].indexOf( obj[key] ) == 0){
                var j = item[key].replace(obj[key], '').trim();
                if(j){
                    i = Math.max(i, parseInt(j)+1);
                }else{
                    i = 1;
                }
            }
        });
        return obj[key] + (i ? ' '+i : '');
    };

    $scope.selectGroup = function(group){
        angular.forEach($scope.groups, function(group) {
            group.selected = false;
        });
        $scope.group = group;
        $scope.group.selected = true;
        $scope.assorts = group['assorts'];

        if ($scope.assorts) {
            $scope.selectItem($scope.assorts[0]);
        } else {
            $scope.assort = null;
            $scope.sections = $scope.group['sections'];
            $scope.selectSection($scope.sections?$scope.sections[0]:[]);
        }
    };

    $scope.selectItem = function(assort){
        angular.forEach($scope.assorts, function(assort) {
            assort.selected = false;
            assort.editing = false;
        });
        $scope.assort = assort;
        $scope.assort.selected = true;
        $scope.sections = assort['sections'];
        $scope.selectSection($scope.sections ? $scope.sections[0] : []);
    };

    $scope.selectSection = function(section){
        angular.forEach($scope.sections, function(section) {
            section.selected = false;
        });
        $scope.section = section;
        if (section) {
            $scope.section.selected = true;
        }
    };

    var uploader = $scope.uploader = new FileUploader({url: '/index/upload'});
    uploader.onAfterAddingFile = function(fileItem) {
        fileItem.formData.push({"m":fileItem.m});
        fileItem.formData.push({"f":fileItem.f});
    };

    $scope.responseData = function(data, deferred){
        if (!data || data.get(0) == null || $scope.uploader.queue.length == 0) {
            $scope.$emit('butterbarEvent', { show: false });
            deferred.resolve(data);
            return;
        }
        var model = data.get(0);

        var upqueue = $scope.uploader.queue;
        for(var i in upqueue) {
            upqueue[i].formData.push({"mid":model.product_id});
        }
        $scope.uploader.onCompleteAll = function() {
            $scope.$emit('butterbarEvent', { show: false });
            deferred.resolve(data);
        };
        $scope.uploader.onProgressAll = function() {
            $scope.$emit('butterbarEvent', { show: true });
        };
        $scope.uploader.onSuccessItem = function(fileItem, response, status) {
            if (status == 200 && response) {
                model[fileItem.f] = response;
            }
        };
        $scope.uploader.uploadAll();
    };

    $scope.syncData = function(data, url) {
        var deferred = $q.defer();
        $.ajax({ 'type':'post',  'dataType':'json',
            'url':url,
            'data': data,
            'success': function(data) {
                data.get = function(idx){
                    idx = (idx ? idx : 0);
                    if (data.data && data.data.length >= idx) {
                        return data.data[idx];
                    } else {
                        return null;
                    }
                };
                $scope.responseData(data, deferred);
            },
            'error':function(){
                $scope.$emit('butterbarEvent', { show: false });
            },
            'beforeSend':function(){
                $scope.$emit('butterbarEvent', { show: true });
            }
        });
        return deferred.promise;
    };

    $scope.assignFootInfo = function(footInfo){
        $scope.footInfo = footInfo;
    };
});