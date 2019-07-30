<?php if (!defined('THINK_PATH')) exit();?><div  ng-controller="productIndexController" class="hbox hbox-auto-xs bg-light " ng-init="filterHide=true;group=null;groupEdit=false;groupState=false;app.settings.asideFolded = true;app.settings.asideFixed = true;app.settings.asideDock = false;app.settings.container = false;app.hideFooter=true;app.hideAside = false">

    <div class="col w-lg lt b-r" ng-show="groupState"  ng-controller="groupController">
        <div class="vbox">
            <div class="wrapper">
                <a href class="pull-right btn btn-sm btn-info m-t-n-xs" ng-click="createGroup()">新建</a>
                <div class="h4">分组</div>
            </div>
            <div class="wrapper b-t m-t-xxs">
                <div class="input-group">
                    <span class="input-group-addon input-sm"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control input-sm" placeholder="搜索..." ng-model="query">
                </div>
            </div>
            <div class="row-row">
                <div class="cell scrollable hover">
                    <div class="cell-inner">
                        <div class="padder">
                            <div class="list-group">
                                <a ng-repeat="group in groups | filter:query" class="list-group-item b-l-{{group.group_type==0?'info':'dark'}} b-l-3x hover-anchor" ng-class="{'hover': group.selected }" ng-click="selectGroup(group)">
                                    <span ng-click='editGroup(group)' class="pull-right text-muted hover-action"><i class="fa fa-edit"></i></span>
                                    <span class="block text-ellipsis" ng-dblclick="editGroupName(group)">{{ group.name ? group.name : '新分组' }}</span>
                                    <input type="text" class="form-control pos-abt" ng-show="group.nameEditable" ng-blur="group.nameEditable = false" ng-model="group.name" style="top:3px;left:2px;width:98%" ui-focus="group.nameEditable">
                                    <small class="text-muted">{{ group.create_time | fromUnix:'YYYY-MM-DD' }}</small>
                                    <small class="text-muted pull-right "   ng-dblclick="editGroupType(group)">{{ group.group_type | groupTypeName }}</small>
                                    <select ng-show="group.typeEditable" ng-model="group.group_type"  ng-blur="group.typeEditable = false" class="form-control input-sm pos-abt" style="top:23px;left:150px;width:90px" ui-focus="group.typeEditable">
                                        <option value="0">固定组</option>
                                        <option value="1">条件组</option>
                                    </select>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col bg-white-only" ng-show="groupEdit">
        <ui-group cas-model="group" model-name="product" fixed-fields="fields"></ui-group>
    </div>

    <div class="col bg-white-only" ng-hide="groupEdit">
        <div class="wrapper-md">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div>
                        <a ng-click="groupState = !groupState" style="padding-left: 0px" class="btn btn-link pull-left m-t-n-xs" tooltip="分组">
                            <i class="fa  fa-group"></i>
                        </a>
                        <a ng-click="setMultipleCondition();" ng-show="searchMultipleCondition.length > 0" style="padding-left: 0px;color: orange" class="btn btn-link pull-left m-t-n-xs" tooltip="高级搜索生效中">
                            <i class="fa  fa-cubes"></i>
                        </a>
                        <a class="btn btn-link pull-right m-t-n-xs m-r-n-sm" ng-click="toggle();">
                            <i class="glyphicon glyphicon-filter"></i>
                        </a>
                        <a class="btn btn-link pull-right m-t-n-xs m-r-n-sm" href="#/view/product/new" target="_blank">
                            <i class="glyphicon  glyphicon-plus"></i>
                        </a>

                        <span>雇员列表</span>
                    </div>
                </div>

                <div class="row wrapper" id="filter-options" ng-hide="filterHide">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <select multiple style="width: 100%;" id="filter" ng-model="filterCondition">
                                <optgroup label="负责人">
                                    <option value="AK">我负责</option>
                                    <option value="HI">我管辖</option>
                                </optgroup>
                                <optgroup label="创建时间">
                                    <option value="CA">今日创建</option>
                                    <option value="NV">本周新建</option>
                                    <option value="OR">本月新建</option>
                                </optgroup>
                                <optgroup label="待审核">
                                    <option value="AZ">基本信息待审核</option>
                                    <option value="CO">专业信息待审核</option>

                                </optgroup>
                                <optgroup label="通过审核">
                                    <option value="AZ">基本信息通过审核</option>
                                    <option value="CO">专业信息通过审核</option>

                                </optgroup>
                                <optgroup label="未通过审核">
                                    <option value="AZ">基本信息未通过审核</option>
                                    <option value="CO">专业信息未通过审核</option>

                                </optgroup>
                                <optgroup label="未提交">
                                    <option value="AZ">基本信息未提交</option>
                                    <option value="CO">专业信息未提交</option>

                                </optgroup>
                                <optgroup label="岗位状态">
                                    <?php $_result=model_select_field_list(68);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fvo): $mod = ($i % 2 );++$i;?><option value="AZ"><?php echo ($fvo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </optgroup>
                                <optgroup label="工作状态">
                                    <?php $_result=product_workstate_list();if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fvo): $mod = ($i % 2 );++$i;?><option value="AZ"><?php echo ($fvo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </optgroup>
                                <optgroup label="培训状态">
                                    <?php $_result=model_select_field_list(1004);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fvo): $mod = ($i % 2 );++$i;?><option value="AZ"><?php echo ($fvo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </optgroup>
                            </select>
                            <span class="input-group-btn">
                              <button ng-click="branch.selected = undefined" class="btn btn-default">
                                  <span class="glyphicon glyphicon-trash"></span>
                              </button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row wrapper" style="padding-bottom:0px">
                    <div class="col-sm-1">
                        <select class="form-control" ng-model="datatablePageLength" ng-init="datatablePageLength=10">
                            <option value="10">10</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="-1">全部</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <div class="input-group"  ui-branch>
                                <ui-select ng-model="branch.selected" theme="bootstrap" ng-disabled="disabled" style="min-width: 100px;">
                                    <ui-select-match placeholder="选择门店...">{{branch.selected.name}}</ui-select-match>
                                    <ui-select-choices group-by="branchGroup" repeat="branch in branchs | propsFilter: {name: $select.search}">
                                        <div ng-bind-html="branch.name | highlight: $select.search"></div>
                                        <small>
                                            代号: <span ng-bind-html="''+branch.code | highlight: $select.search"></span>
                                        </small>
                                    </ui-select-choices>
                                </ui-select>
                                <span class="input-group-btn">
                                  <button ng-click="branch.selected = undefined" class="btn btn-default">
                                      <span class="glyphicon glyphicon-trash"></span>
                                  </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div  class="col-sm-9">
                        <ui-field-condition ng-fields="fields" cas-model="searchCondition" ng-default="任意字段" holder="holder">
                            <div class="input-group" style="width: 100%;">
                                <holder/>
                                <div class="input-group-btn dropdown" dropdown>
                                    <button type="button" class="btn  btn-default" ng-click="table.ajax.reload();"><i class="fa fa-search  text"></i></button>
                                    <button type="button" class="btn btn-default" dropdown-toggle><span class="caret"></span></button>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);" ng-click="multipleSearch();">高级搜索</a></li>
                                        <li class="divider"></li>
                                        <li><a href>导出到Excel</a></li>
                                    </ul>
                                </div><!-- /btn-group -->
                            </div>
                        </ui-field-condition>
                    </div>
                </div>
                <div class="row wrapper" style="padding-bottom:0px">
                    <div class="table-responsive">
                        <table ui-datatable="fields" ng-refresh-event="refreshProductList" ng-data-url="product/index" ng-page="totalPages" ng-row-option="table-row-options" ng-page-length="datatablePageLength" class="table table-striped hover b-t b-light" style="white-space: nowrap;">
                            <thead>
                            <tr>
                                <th data="product_id" searchable="false" orderable="false">
                                    <div class="input-group-btn dropdown" dropdown>
                                        <label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i style="margin-right:0px;margin-top:0px"></i></label>
                                        <button type="button" class="btn btn-default btn-xs" style="height:20px" dropdown-toggle><span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href>全部</a></li>
                                            <li><a href>删除</a></li>
                                            <li><a href>添加到组</a></li>
                                            <li class="divider"></li>
                                            <li><a href>重置提交</a></li>
                                        </ul>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th></th>
                            </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row wrapper text-center" style="padding-bottom:0px;padding-to:0px">
                    <ui-page total="{{totalPages}}" cas-model="currentPage" ng-init="currentPage=0;totalPages=0;" cas-change="pageChange($current);"></ui-page>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="table-row-options" type="text/x-jquery-tmpl">
<div class="btn-toolbar btn-toolbar-light" id="user-toolbar-options">
    <a target="_blank" href="#/view/product/${product_id}"><i class="icon-user"></i></a>
    <a href="#"><i class="glyphicon glyphicon-edit"></i></a>
    <a href="#"><i class="glyphicon glyphicon-trash"></i></a>
    <a href="#"><i class="glyphicon glyphicon-share-alt"></i></a>
    <a href="#"><i class="icon-ban"></i></a>
</div>
</script>

<script id="table-row-selected-hot" type="text/x-jquery-tmpl">
<a id="table-cell-option" target="_blank" href="#/view/product/${product_id}" class="table-cell-option" style="margin-left: 5px"><i class="fa fa-flickr"></i></a>
</script>

<script>
    var fields = <?php echo (json_encode($field_array)); ?>;

    app.controller('productIndexController', function($scope, $state, $compile,$timeout, multipleSearch) {
        $scope.fields = fields;
        $scope.searchCondition = {value:"",condition:"", field:""};
        $scope.searchMultipleCondition = [];
        $scope.filterCondition = [];

        $scope.multipleSearch = function(){
            multipleSearch.open(fields,$scope.searchMultipleCondition).then($scope.setMultipleCondition);
        };
        $scope.setMultipleCondition = function(items){
            $scope.searchMultipleCondition = items?items:[];
            $scope.table.ajax.reload();
        };

        $scope.toggle = function () {
            $scope.filterHide = !$scope.filterHide;
            $timeout(function () {
                $('#filter').chosen({
                    placeholder_text_multiple:"选择过滤项...", display_selected_options:false, display_disabled_options:false
                });
            }, 0);
        };

        $scope.datatablesearch = function(d){
            if ($scope.group) {
                d['group'] = $scope.group.group_id;
            }

            if ($scope.filterCondition.length > 0) {
                d.filter = $scope.filterCondition;
            }

            if ($scope.searchMultipleCondition.length > 0) {
                var mutiple = [];
                angular.forEach($scope.searchMultipleCondition, function(mc){
                    var nmc = angular.copy(mc);
                    nmc.field = fields[nmc.field].field;
                    mutiple.push(nmc);
                });
                d.multiple = mutiple;
            }

            if ($scope.searchCondition) {
                d['search']['value'] = $scope.searchCondition.value;
                d['search']['condition'] = $scope.searchCondition.condition;
                if ($scope.searchCondition.field) {
                    d['search']['field'] = fields[$scope.searchCondition.field].field;
                }
            }

        };


        $scope.$watch("groupState",function(newValue){
            if (newValue) {
                $.ajax({'dataType': 'json','url': 'product/index/act/group',
                    'success': function (groups) {
                        if (groups) {
                            $scope.$apply(function(){
                                $scope.groups = groups;
                            });
                        }
                    },
                    'beforeSend':function(){
                        $scope.$emit('butterbarEvent', { show: true });
                    },
                    'complete':function(){
                        $scope.$emit('butterbarEvent', { show: false });
                    }
                });
            }
        });

        $scope.$watch("group",function(group){
            if (group == null || !group.editable) {
                $scope.table.ajax.reload();
            }
        });

        $scope.$watch("group.editable",function(state){
            $scope.groupEdit = state;
        });

        $scope.saveGroup = function(group){
            console.log("lskdf");
        };

        $scope.deleteGroup = function(group){
            $scope.groups.splice($scope.groups.indexOf(group), 1);
            $scope.group = null;
        };
    });
</script>