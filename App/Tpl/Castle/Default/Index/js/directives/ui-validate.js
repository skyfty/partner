/*!
 * angular-ui-validate
 * https://github.com/angular-ui/ui-validate
 * Version: 1.2.3 - 2017-05-18T08:04:45.422Z
 * License: MIT
 */


(function () { 
'use strict';
/**
 * General-purpose validator for ngModel.
 * angular.js comes with several built-in validation mechanism for input fields (ngRequired, ngPattern etc.) but using
 * an arbitrary validation function requires creation of custom directives for interact with angular's validation mechanism.
 * The ui-validate directive makes it easy to use any function(s) defined in scope as a validator function(s).
 * A validator function will trigger validation on both model and input changes.
 *
 * This utility bring 'ui-validate' directives to handle regular validations and 'ui-validate-async' for asynchronous validations.
 *
 * @example <input ui-validate=" 'myValidatorFunction($value)' ">
 * @example <input ui-validate="{ foo : '$value > anotherModel', bar : 'validateFoo($value)' }">
 * @example <input ui-validate="{ foo : '$value > anotherModel' }" ui-validate-watch=" 'anotherModel' ">
 * @example <input ui-validate="{ foo : '$value > anotherModel', bar : 'validateFoo($value)' }" ui-validate-watch=" { foo : 'anotherModel' } ">
 * @example <input ui-validate-async=" 'myAsyncValidatorFunction($value)' ">
 * @example <input ui-validate-async="{ foo: 'myAsyncValidatorFunction($value, anotherModel)' }" ui-validate-watch=" 'anotherModel' ">
 *
 * @param ui-validate {string|object literal} If strings is passed it should be a scope's function to be used as a validator.
 * If an object literal is passed a key denotes a validation error key while a value should be a validator function.
 * In both cases validator function should take a value to validate as its argument and should return true/false indicating a validation result.
 * It is possible for a validator function to return a promise, however promises are better handled by ui-validate-async.
 *
 * @param ui-validate-async {string|object literal} If strings is passed it should be a scope's function to be used as a validator.
 * If an object literal is passed a key denotes a validation error key while a value should be a validator function.
 * Async validator function should take a value to validate as its argument and should return a promise that resolves if valid and reject if not,
 * indicating a validation result.
 * ui-validate-async supports non asyncronous validators. They are wrapped into a promise. Although is recomented to use ui-validate instead, since
 * all validations declared in ui-validate-async are registered un ngModel.$asyncValidators that runs after ngModel.$validators if and only if
 * all validators in ngModel.$validators reports as valid.
 */
angular.module('ui.validate',[])
  .directive('uiValidate', ['$$uiValidateApplyWatch', '$$uiValidateApplyWatchCollection', function ($$uiValidateApplyWatch, $$uiValidateApplyWatchCollection) {

  return {
    restrict: 'A',
    require: 'ngModel',
    link: function(scope, elm, attrs, ctrl) {
      var validateFn, validateExpr = scope.$eval(attrs.uiValidate);

      if (!validateExpr) {
        return;
      }

      if (angular.isString(validateExpr)) {
        validateExpr = {
          validator: validateExpr
        };
      }

      angular.forEach(validateExpr, function(exprssn, key) {
        validateFn = function(modelValue, viewValue) {
          // $value is left for retrocompatibility
          var expression = scope.$eval(exprssn, {
            '$value': modelValue,
            '$modelValue': modelValue,
            '$viewValue': viewValue,
            '$name': ctrl.$name
          });
          // Keep support for promises for retrocompatibility
          if (angular.isObject(expression) && angular.isFunction(expression.then)) {
            expression.then(function() {
              ctrl.$setValidity(key, true);
            }, function() {
              ctrl.$setValidity(key, false);
            });
            // Return as valid for now. Validity is updated when promise resolves.
            return true;
          } else {
            return !!expression; // Transform 'undefined' to false (to avoid corrupting the NgModelController and the FormController)
          }
        };
        ctrl.$validators[key] = validateFn;
      });

      // Support for ui-validate-watch
      if (attrs.uiValidateWatch) {
        $$uiValidateApplyWatch(scope, ctrl, scope.$eval(attrs.uiValidateWatch), attrs.uiValidateWatchObjectEquality);
      }
      if (attrs.uiValidateWatchCollection) {
        $$uiValidateApplyWatchCollection(scope, ctrl, scope.$eval(attrs.uiValidateWatchCollection));
      }
    }
  };
}])
  .directive('uiValidateAsync', ['$$uiValidateApplyWatch', '$$uiValidateApplyWatchCollection', '$timeout', '$q', function ($$uiValidateApplyWatch, $$uiValidateApplyWatchCollection, $timeout, $q) {

  return {
    restrict: 'A',
    require: 'ngModel',
    link: function (scope, elm, attrs, ctrl) {
      var validateFn, validateExpr = scope.$eval(attrs.uiValidateAsync);

      if (!validateExpr){ return;}

      if (angular.isString(validateExpr)) {
        validateExpr = { validatorAsync: validateExpr };
      }

      angular.forEach(validateExpr, function (exprssn, key) {
        validateFn = function(modelValue, viewValue) {
          // $value is left for ease of use
          var expression = scope.$eval(exprssn, {
            '$value': modelValue,
            '$modelValue': modelValue,
            '$viewValue': viewValue,
            '$name': ctrl.$name
          });
          // Check if it's a promise
          if (angular.isObject(expression) && angular.isFunction(expression.then)) {
            return expression;
            // Support for validate non-async validators
          } else {
            return $q(function(resolve, reject) {
              setTimeout(function() {
                if (expression) {
                  resolve();
                } else {
                  reject();
                }
              }, 0);
            });
          }
        };
        ctrl.$asyncValidators[key] = validateFn;
      });

      // Support for ui-validate-watch
      if (attrs.uiValidateWatch){
          $$uiValidateApplyWatch( scope, ctrl, scope.$eval(attrs.uiValidateWatch), attrs.uiValidateWatchObjectEquality);
      }
      if (attrs.uiValidateWatchCollection) {
        $$uiValidateApplyWatchCollection(scope, ctrl, scope.$eval(attrs.uiValidateWatchCollection));
      }
    }
  };
}])
  .service('$$uiValidateApplyWatch', function () {
    return function (scope, ctrl, watch, objectEquality) {
      var watchCallback = function () {
        ctrl.$validate();
      };

      //string - update all validators on expression change
      if (angular.isString(watch)) {
        scope.$watch(watch, watchCallback, objectEquality);
        //array - update all validators on change of any expression
      } else if (angular.isArray(watch)) {
        angular.forEach(watch, function (expression) {
          scope.$watch(expression, watchCallback, objectEquality);
        });
        //object - update appropriate validator
      } else if (angular.isObject(watch)) {
        angular.forEach(watch, function (expression/*, validatorKey*/) {
          //value is string - look after one expression
          if (angular.isString(expression)) {
            scope.$watch(expression, watchCallback, objectEquality);
          }
          //value is array - look after all expressions in array
          if (angular.isArray(expression)) {
            angular.forEach(expression, function (intExpression) {
              scope.$watch(intExpression, watchCallback, objectEquality);
            });
          }
        });
      }
    };
  })
  .service('$$uiValidateApplyWatchCollection', function () {
    return function (scope, ctrl, watch) {
      var watchCallback = function () {
        ctrl.$validate();
      };

      //string - update all validators on expression change
      if (angular.isString(watch)) {
        scope.$watchCollection(watch, watchCallback);
        //array - update all validators on change of any expression
      } else if (angular.isArray(watch)) {
        angular.forEach(watch, function (expression) {
          scope.$watchCollection(expression, watchCallback);
        });
        //object - update appropriate validator
      } else if (angular.isObject(watch)) {
        angular.forEach(watch, function (expression/*, validatorKey*/) {
          //value is string - look after one expression
          if (angular.isString(expression)) {
            scope.$watchCollection(expression, watchCallback);
          }
          //value is array - look after all expressions in array
          if (angular.isArray(expression)) {
            angular.forEach(expression, function (intExpression) {
              scope.$watchCollection(intExpression, watchCallback);
            });
          }
        });
      }
    };
  });

}());

app.directive("onbeforesave", function($compile, $parse, $q) {
    return {
        link: function($scope, $element,$attrs){
            $scope.validate = function($data) {
                var fieldOptions = $parse($attrs.fieldOptions)($scope);
                var result = true;
                if (angular.isDefined($data)) {
                    switch(fieldOptions.form_type) {
                        case "number":{
                            result = $data.match(regexEnum.intege);
                            break;
                        }
                        case "floatnumber": {
                            result = $data.match(regexEnum.decmal3);
                            break;
                        }
                        case "mobile":
                        case "phone": {
                            result = $data.match(regexEnum.mobile);
                            break;
                        }
                        case "email": {
                            result = $data.match(regexEnum.email);
                            break;
                        }
                        case "linkaddress": {
                            result = $data.match(regexEnum.url);
                            break;
                        }
                    }
                }
                if (!result) {
                    return fieldOptions.name+"格式不正确";
                }

                if (fieldOptions.is_validate != "1")
                    return;

                if (fieldOptions.is_null != "1" && (angular.isUndefined($data) || $data == ""))
                    return fieldOptions.name+"不可以为空";

                if (fieldOptions.is_unique === "1" && angular.isDefined($data) && $data != "") {
                    var oldValue = $parse($attrs.uiViewValue)($scope);
                    if (oldValue == $data) {
                        return;
                    }
                    var d = $q.defer();
                    var data = {"clientid":fieldOptions.field};
                    data[fieldOptions.field] = $data;
                    $.ajax({
                        'type': 'get',
                        'dataType': 'json',
                        'url': fieldOptions.model + '/validate',
                        'data':data,
                        'success': function (data) {
                            data = data || {};
                            if (data.status == 1) {
                                d.resolve(fieldOptions.name+"重复");
                            } else {
                                if (angular.isFunction(fieldOptions.validate)) {
                                    d.resolve(fieldOptions.validate($data));
                                } else {
                                    d.resolve();
                                }
                            }
                        },
                        'error':function() {
                            d.resolve();
                        }
                    });
                    return d.promise;
                }
                else if (angular.isFunction(fieldOptions.validate)) {
                    return fieldOptions.validate($data);
                }
            };
        }
    };
});


app.directive("onaftersave", function($compile, $parse, $q) {
    return {
        link: function($scope, $element,$attrs){
            $scope.notifyChange = function($data) {
                var fieldOptions = $parse($attrs.fieldOptions)($scope);
                var fileChanged = fieldOptions.field + "Changed";
                if (angular.isDefined($scope[fileChanged])) {
                    $scope.$eval($scope[fileChanged]);
                }
            };
        }
    };
});
