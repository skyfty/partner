
app.directive("uiViewDetail", function($parse, $compile) {
    return {
        template : function(){
            return "数据装载中...";
        },
        link: function($scope, $element, $attrs) {
            $scope.showFields = function(){
                var assort = $parse("assort")($scope);
                if (assort) {
                    if (angular.isDefined(assort)) {
                        angular.forEach(assort.fields, function(field) {
                            field.disabled = (field.in_add == 0 && field.in_edit ==0);
                        });
                    }
                    var html = $('#group-fields-template').tmpl({s:assort, d:$attrs.uiViewDetail, af:"assort.fields"});
                    var field_html = html.find("[field-options]");
                    field_html.attr("onbeforesave", "validate($data)").attr("onaftersave", "notifyChange($data)");
                    $element.html($compile(html)($scope));
                } else {
                    $element.html($compile("<span>empty</span>")($scope));
                }
            };

            $scope.$watch("section", function(section){
                if (section && angular.isDefined(section.templateUrl)) {
                    $scope.$broadcast("hideSection");
                    $element.html($compile("<div associate='"+section.templateUrl+"' related='"+$attrs.uiViewDetail+"' module='module'></div>")($scope));
                }else if (section && angular.isDefined(section.template)){
                    $element.html($compile(section.template)($scope));
                }else if (section && angular.isDefined(section.templateId)){
                    $element.html($compile($('#' + section.templateId).tmpl($attrs))($scope));
                }else {
                    $scope.$broadcast("hideSection");
                    $scope.showFields();
                }
            });
        }
    };
});


app.directive("uiModelUpdate", function($parse, $compile) {
    return {
        link: function($scope, $element, $attrs) {
            $scope.$watch($attrs.ngFields, function(data){
                var fields = $parse($attrs.ngFields)($scope);
                angular.forEach(fields, function(field) {
                    field.disabled = (field.in_add == 0 && field.in_edit ==0);
                });

                var html = $('#group-fields-template').tmpl({s:{fields:fields}, d:$attrs.casModel, af:$attrs.ngFields});
                var field_html = html.find("[field-options]");
                field_html.attr("onbeforesave", "validate($data)").attr("onaftersave", "notifyChange($data)");
                var holder = $element.find($attrs.holder);
                if (holder.length == 0) {
                    $element.html($compile(html)($scope));
                } else {
                    holder.replaceWith($compile(html)($scope));
                }
            }, true);
        }
    };
});



app.directive("uiViewValue", function() {
    return {
        scope: true,
        controller: function($scope, $attrs, $element, $parse, $filter, $compile,$timeout) {
            $scope.$on('hideSection',function(){
                $timeout(function() {$scope.$destroy();});
            });
            $scope.$watch($attrs.uiViewValue, function(data){
                var field = $parse($attrs.fieldOptions)($scope);
                if (field) {
                    data = $filter("formatValue")(data, field, $attrs.editableConditionField);
                }
                $element.html($compile('<span>'+data+'</span>')($scope));
            });
        }
    };
});

app.directive("uiPictureList", function($parse, $compile) {
    return {
        template : function(){
            return "请选择图片或拍照上传";
        },
        link: function($scope, $element, $attrs) {
            $scope.pictureNew = function(e){
                var field = $parse(angular.element(e).attr("field-options"))($scope);
                var picModel = angular.element(e).attr("field-model");
                var ele = $("[ui-pic-list-id='"+field.field_id+"']");
                var data = {
                    model:"temp",
                    field:field,
                    picModel:$attrs.fieldModel,
                };
                var pictempl = $('#edit-picture-list-fields-template').tmpl(data);
                var fileinput = pictempl.find("input");

                fileinput.change(function () {
                    var self = this;
                    var ngModel = $parse(picModel)($scope);
                    if (angular.isUndefined(ngModel)) {
                        $parse(picModel).assign($scope,[self]);
                    } else {
                        ngModel.push(self);
                    }
                });
                ele.append($compile(pictempl)($scope));
                fileinput.click();
            };

            $scope.pictureDelete = function(e, picmodel){
                var picscope = angular.element(e).scope();
                picscope.$apply(function(){
                    var file = $parse(angular.element(e).attr("field-model"))(picscope);
                    if (file.files) {
                        for(var f in picscope.uploader.queue) {
                            if (picscope.uploader.queue[f]._file == file.files[0]) {
                                picscope.uploader.queue[f].remove();
                                break;
                            }
                        }
                    }
                    var ngModel = $parse(picmodel)(picscope);
                    ngModel.splice(ngModel.indexOf(file), 1);
                });
            };
            $element.html($compile($('#picture-list-fields-template').tmpl($attrs))($scope));
        }
    };
});

app.directive("uiPicture", function($parse, $compile) {
    return {
        template : function(){
            return "";
        },
        link: function($scope, $element, $attrs) {
            var model = $parse($attrs.fieldModel)($scope);
            if (angular.isDefined(model)) {
                if (model.files) {
                    var field = $parse($attrs.fieldOptions)($scope);
                    var data = {
                        field:field,
                        model:$attrs.fieldModel,
                        picModel:$attrs.picModel
                    };
                    var picture = $('#edit-picture-list-fields-template').tmpl(data);
                    picture.find(".filediv").html(model);
                    $.each(model.files, function(f, v){
                        loadImage(v,function (img) {
                            $(img).css({"height":"70px","width":"70px"});
                            $(img).addClass("thumbnail productotherlistthumb img-responsive");
                            picture.find(".prediv").html(img);
                        },{maxWidth: 600});
                    });
                    picture.show();
                    $element.html(picture);
                } else {
                    var data = {
                        model:$attrs.fieldModel,
                        pic:model,
                        picModel:$attrs.picModel
                    };
                    $element.html($compile($('#picture-fields-template').tmpl(data))($scope));
                }
            }
        }
    };
});

app.directive("uiAvatar", function($filter, $compile) {
    return {
        template : function(){
            return '<div class="thumb-avatar avatar inline"><img ng-src=""></div>';
        },
        link: function($scope, $element, $attrs) {
            var opthtml =
                '<div>'+
                    '<div class="thumb-avatar avatar inline">'+
                    '<img src="/Public/img/p0.jpg" ng-src="{{'+$attrs.ngModel + ".path"+'}}">'+
                    '<div class="prediv thumbnail" style="border-color:white;position:absolute; left:0; top:0; width:100%; height:100%;display: none"></div>' +
                    '</div>'+
                    '<div>'+
                    '<input uploader="uploader" options="'+$attrs.options+'" nv-file-select="" type="file" style="display: none" />' +
                    '<a style="margin-right:5px" href="javascript:void(0);" ng-click="avatarUpdate(this)"><i class="fa fa-upload"></i></a>'+
                    '<a style="margin-right:5px" href><img class="del_parts" src="./Public/img/delete.gif" /></a>'+
                    '</div>' +
                    '</div>';
            var pictempl = $(opthtml).tmpl();
            var fileinput = pictempl.find("input");
            var prediv = pictempl.find(".prediv");
            fileinput.change(function (e) {
                var self = this;
                $.each(e.target.files, function(f, v){
                    loadImage(v,function (img) {
                        $(img).css({"height":"120px","width":"102px"});
                        prediv.html(img).show();
                    },{maxWidth: 600});
                });
            });
            $scope.avatarUpdate = function(e){
                fileinput.click();
            };
            $scope.pictureDelete = function(ele){
                $(ele).parent().parent().remove();
            };
            $element.html($compile(pictempl)($scope));
        }
    };
});


app.directive("uiStaffSelect", function($parse) {
    return {
        link: function($scope, $element, $attrs, $timeout) {
            $scope.$on('hideSection',function(){
                $timeout(function() {$scope.$destroy();});
            });
            var model = $parse($attrs.editableModelId);
            $scope.$watch(model, function(data) {
                if (angular.isDefined(data) && data) {
                    $element.html('<span>'+data.name+'</span>');
                } else {
                    $element.html('<span class="fa fa-pencil-square-o"></span>');
                }
            });
        }
    };
});

app.directive("uiChannelRoleModelSelect", function($parse) {
    return {
        scope: true,
        controller: function($scope, $attrs, $element, $timeout) {
            $scope.$on('hideSection',function(){
                $timeout(function() {$scope.$destroy();});
            });
            var channelRoleModel = $attrs.uiChannelRoleModelSelect;
            var channelModel = $parse(channelRoleModel);
            $scope.$watch(channelModel, function(channel) {
                if (angular.isDefined(channel) && channel) {
                    $element.html('<span><span>'+channel.name+'</span></span>');
                } else {
                    $element.html('<span><span class="fa fa-pencil-square-o"></span></span>');
                }
            });
        }
    };
});

app.directive("uiChannelRoleIdSelect", function($parse) {
    return {
        scope: true,
        controller: function($scope, $attrs, $element, $timeout) {
            var model = $parse($attrs.editableChannelRoleIdBox);
            $scope.$on('hideSection',function(){
                $timeout(function() {$scope.$destroy();});
            });
            $scope.$watch(model, function(channel) {
                if (angular.isDefined(channel) && channel) {
                    $element.html('<span>'+channel.name+'</span>');
                    if (channel.model == "渠道库") {
                        $attrs.editDisabled = "!!true";
                    } else {
                        $attrs.editDisabled = null;
                    }
                } else {
                    $attrs.editDisabled = null;
                    $element.html('<span><span class="fa fa-pencil-square-o"></span></span>');
                }
            });
        }
    };
});

app.directive("uiSkillCardList", function() {
    return {
        scope: true,
        controller: function($scope, $attrs, $element, $parse, $filter, $compile,$timeout) {
            var model = $parse($attrs.casModel);
            $scope.$parent.removeSkill = function(s) {
                var skill = model($scope);
                for(var i=0; i<skill.length; i++) {
                    if (skill[i].skill_id == s.skill_id) {
                        skill.splice(i, 1);
                        break;
                    }
                }
            };

            $scope.$parent.addProductCatetory = function(){
                var skill = model($scope);
                if (!skill) {
                    model.assign($scope, skill = []);
                }
                skill.push({dirty:true});
            };

            $scope.$parent.filterProductCategory = function(c){
                var skill = model($scope);
                var skilllist = [];
                var pcdate = $scope.assort.fields[0].data;
                angular.forEach(pcdate, function(pc){
                    if (pc.category_id == c) {
                        skilllist.push(pc);
                        return;
                    }

                    var b = true;
                    for(var i in skill) {
                        if (skill[i].category_id == pc.category_id) {
                            b = false;
                            break;
                        }
                    }
                    if (b) {
                        skilllist.push(pc);
                    }
                });

                return skilllist;
            };

            $scope.$watch(model, function(data) {
                var html = $('#skill-template').tmpl({assort:$scope.assort,model:$attrs.casModel, af:"assort.fields"});
                $element.html($compile(html)($scope));
            });

            $scope.$on('hideSection',function(){
                $timeout(function() {$scope.$destroy();});
            });
        }
    };
});
