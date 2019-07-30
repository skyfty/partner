'use strict';

angular.module('app').filter('formatAssortFields', function() {
    return function(assorts, data) {
        angular.forEach(assorts, function(assort) {
            angular.forEach(assort.fields, function(field) {
                var val = data[field['field']];
                if (!angular.isDefined(val))
                    return;
                data[field['field']] = formatField(field, val);
            });
        });
        return data;
    }
  });


angular.module('app').filter('formatFields', function() {
    return function(fields, data) {
        angular.forEach(fields, function(field) {
            var val = data[field['field']];
            if (!angular.isDefined(val))
                return;
            data[field['field']] = formatField(field, val);
        });
        return data;
    }
});

angular.module('app').filter('convertAssortFields', function() {
        return function(assorts, data) {
            angular.forEach(assorts, function(assort) {
                angular.forEach(assort.fields, function(field) {
                    var val = data[field['field']];
                    if (!angular.isDefined(val))
                        return;

                    if (field['form_type'] == "datetime" && val != 0) {
                        data[field['field']] =  moment(val).format();
                    } else if (field['form_type'] == "pic") {
                        var pics = [];
                        var piclist = data[field['field']];
                        for (var pic in piclist) {
                            if (piclist[pic].files) {
                                continue;
                            }
                            pics.push(piclist[pic])
                        }
                        data[field['field']] =  pics;
                    }
                });
            });
            return data;
        }
    });

angular.module('app').filter('convertFields', function() {
    return function(fields, data) {
        angular.forEach(fields, function(field) {
            var val = data[field['field']];
            if (!angular.isDefined(val))
                return;

            if (field['form_type'] == "datetime" && val != 0) {
                data[field['field']] =  moment(val).format();
            } else if (field['form_type'] == "pic") {
                var pics = [];
                var piclist = data[field['field']];
                for (var pic in piclist) {
                    if (piclist[pic].files) {
                        continue;
                    }
                    pics.push(piclist[pic])
                }
                data[field['field']] =  pics;
            }
        });
        return data;
    }
});


angular.module('app').filter('validateFields', function() {
    return function(assorts, data) {
        var result = [];
        for(var k1 in assorts) {
            var assort = assorts[k1];
            for(var k2 in assort.fields){
                var field = assort.fields[k2];
                if (field.is_validate != "1" || field.is_null == "0" ||(field.in_add == '0' && field.in_edit == '0'))
                    continue;

                var val = data[field['field']];
                if (!angular.isDefined(val) || val =="") {
                    result.push(field);
                }
            }
        }
        return result;
    }
});

angular.module('app').filter('showFields', function ($document, $timeout) {
    return function(data,field) {
        if (field && angular.isDefined(field) && angular.isDefined(data)) {
            if (angular.isString(field)) {
            }
            data = showField(field, data);
        }
        return data;
    }
});


angular.module('app').filter('datatableFields', function ($document, $timeout) {
    return function(data,field, type, row ) {
        if ( type === 'display') {
            if (field && angular.isDefined(field) && angular.isDefined(data)) {
                data = showField(field, data, row);
            }
            return data;
        }
        return data;
    }
});



angular.module('app').filter('formatValue', function () {
    return function(data, field, ecf) {
        if (ecf && data && data.value) {
            data = showConditionField(field, data);
        }else if(angular.isUndefined(ecf) && data){
            data = showField(field, data);
        } else  if (field.in_add == "0" && field.in_edit == "0" && !ecf) {
            data = '<span class="fa fa-info-circle"></span>';
        }  else if (field.field == "branch_id" ) {
            data = '<span class="fa fa-university"></span>';
        }else {
            switch(field.form_type) {
                case "datetime": {
                    data = '<span class="fa fa-calendar"></span>';
                    break;
                }
                case "email": {
                    data = '<span class="fa fa-envelope"></span>';
                    break;
                }
                case "mobile":
                case "phone": {
                    data = '<span class="glyphicon glyphicon-phone "></span>';
                    break;
                }
                case "linkaddress": {
                    data = '<span class="fa  fa-external-link"></span>';
                    break;
                }
                case "select": {
                    data = '<span class="fa fa-th-list"></span>';
                    break;
                }
                case "address": {
                    data = '<span class="glyphicon  glyphicon-home"></span>';
                    break;
                }
                default: {
                    data = '<span class="fa fa-pencil-square-o"></span>';
                    break;
                }
            }
        }
        return data;
    }
});


angular.module('app').filter('verifyState', function ($document, $timeout) {
    return function(state, time) {
        if (time == 0) {
            return "未提交";
        } else {
            if (state == -1) {
                return "审核未通过";
            }
            if (state == 0) {
                return "待审核";
            }
            return "审核通过";
        }

    }
});