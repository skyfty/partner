
app.directive("uiCondition",function($compile, $parse, $q) {
    return {
        link:function($scope, $element, $attrs) {
            var field = $parse($attrs.ngField);
            $scope.$watch(field, function(f) {
                if (angular.isDefined(f)) {
                    var html = $("#condition-fields-template").tmpl({f:f, d:$attrs.casModel});
                    if (f.form_type == "date" || f.form_type == "datetime") {
                        var inputdar = html.find(".input-daterange");
                        if (angular.isDefined($attrs.popover)) {
                            inputdar.width(250);
                        }
                        inputdar.datepicker({});
                    } else if (f.form_type == "select") {
                        var inputdar = html.find(".selectpicker");
                        inputdar.selectpicker({noneSelectedText:"选择一些选项..."});
                    }
                    $element.html($compile(html)($scope));
                } else {
                    var html = $("#condition-fields-template").tmpl({f:{"form_type":"text", "field":"all"}, d:$attrs.casModel});
                    $element.html($compile(html)($scope));

                }
            });
        }
    };
});

app.directive("uiFieldCondition", function($compile, $parse, $q) {
    return {
        link:function($scope, $element, $attrs) {
            var fields = $parse($attrs.ngFields)($scope);
            $scope.fieldfocus = {form_type:"text",field:"all"};

            var formhtml = '<div class="form-group"><div class="input-group"><span class="input-group-btn">{{html selecthtml}}</span><ui-condition multiple="1" style="width: 100%" class="input-group" cas-model="${model}" ng-field="fieldfocus"></ui-condition></div></div>';
            var selecthtml ='<select ng-model="'+$attrs.casModel+'.field" onchange="angular.element(this).scope().onFieldChange(this)" class="form-control"  style="width: auto">';

            if ($attrs.ngDefault) {
                selecthtml += '<option value="">' + $attrs.ngDefault + '</option>';
            }

            for (var i = 0; i < fields.length;++i){
                if ($.inArray(fields[i].form_type,['pic','file']) != '-1') {
                    continue;
                }
                selecthtml += '<option value="'+i+'">' + fields[i].name + '</option>';
            }
            selecthtml +="</select>";

            $scope.onFieldChange = function(ele) {
                var sel = $(ele).val();
                $scope.$apply(function(){
                    $parse($attrs.casModel + ".value").assign($scope, "");
                    $scope.fieldfocus = fields[sel];
                });
            };
            var html = $(formhtml).tmpl({
                selecthtml:selecthtml, model:$attrs.casModel
            });
            var holder = $element.find($attrs.holder);
            if (holder.length == 0) {
                $element.html($compile(html)($scope));
            } else {
                holder.replaceWith($compile(html)($scope));
            }
        }
    };
});

app.directive("uiMultipleSearch", function() {
    return {
        controller: function($scope,$element,$attrs, $filter, $compile,$parse){

            $element.html($compile("lsdkjfl")($scope));
        }
    };
});
