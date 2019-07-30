
angular.module('app').directive("uiDatatableFilter", function() {
    return {
        controller: function($scope,$element,$attrs, $filter, $compile,$parse){
            var field = $parse($attrs.ngField)($scope);
            var data = {
                f:field,
                fi:$attrs.ngField,
                d:$attrs.fieldModel,
                popover:1,
                cf:1,
            };
            $element.html($compile($("#fields-template").tmpl(data))($scope));
        }
    };
});


angular.module('app').directive('uiDatatable', ['$parse','$compile','$filter', function($parse,$compile,$filter) {
        return {
            restrict: 'AC',
            link: function(scope, el, attrs) {
                var fields = $parse(attrs.uiDatatable)(scope);
                var thread = el.find("thead tr");
                var thread_th = "";
                for(var k in fields) {
                    var field = fields[k];
                    if (field['in_index'] != "1")
                        continue;
                    thread_th += "<th ng-field='fields["+k+"]' data='"+field['field']+"'>" + field['name'] + "</th>";
                }
                thread.append($compile(thread_th)(scope));

                scope.searchCols = {};
                var tfoot = el.find("tfoot tr");
                var tfoot_th = "";
                for(var k in fields) {
                    var field = fields[k];
                    if (field['in_index'] != "1")
                        continue;

                    var modelField = 'searchCols.'+ field.field;
                    scope.searchCols[field.field] = {value:"", condition:""};
                    tfoot_th += "<th ui-datatable-filter field-model='"+ modelField +"' ng-field='fields["+k+"]'></th>";
                }
                tfoot.append($compile(tfoot_th)(scope));

                var rowcheck = '<div class="input-group-btn"><label class="i-checks m-b-none"><input type="checkbox" name="${DT_RowIdName}[]"><i style="margin-right:0px;margin-top:0px"></i></label></div>';
                scope.$parent.createdRow = function( row, data, index) {
                    $('td', row).eq(0).html($compile($(rowcheck).tmpl(data))(scope));
                };

                var pageLengthModel = $parse(attrs.ngPageLength);
                scope.$watch(pageLengthModel, function(n, o){
                    if (angular.isDefined(scope.table) && n != o) {
                        scope.table.page.len( n ).draw();
                    }
                });

                if (attrs.ngOrder) {
                    var order = scope.$eval(attrs.ngOrder);

                } else {
                    var order = [ 1, "desc" ];
                }

                var table_obj = {
                    "processing": false,
                    "serverSide": true,
                    "order": order,
                    "pageLength": pageLengthModel(scope),
                    'language': def_dataTable_lang_opt,
                };

                var columns = [];
                thread.find("th").each(function(index,element){
                    var e = $(element);
                    var data = e.attr("data");
                    var field = e.attr("ng-field");

                    var columnatt = {
                        data: data,
                        render: function ( data, type, row ) {
                            var fo = $parse(field)(scope);
                            return $filter("datatableFields")(data,fo, type, row);
                        }
                    };
                    if (e.attr("searchable")) {
                        columnatt["searchable"] = e.attr("searchable") == "true";
                    }
                    if (e.attr("orderable")) {
                        columnatt["orderable"] = e.attr("orderable") == "true";
                    }
                    columns.push(columnatt);
                });
                table_obj['columns'] = columns;

                var dataurl = {
                    "url":attrs.ngDataUrl,
                    dataSrc:function( json ) {
                        var data = json.data;
                        for(var i = 0; i < data.length; ++i) {
                            data[i] = $filter("formatFields")(fields, data[i]);
                        }
                        return data;
                    }
                };

                if (attrs.ngDataSearch) {
                    dataurl["data"] = $parse(attrs.ngDataSearch)(scope);
                } else {
                    dataurl["data"] = function(d){
                        angular.forEach(d.columns, function(c){
                            if (c.searchable &&  c.data) {
                                var searchValue = $parse("searchCols." + c.data)(scope);
                                c.search.value = searchValue.value;
                                c.search.condition = searchValue.condition;
                            }
                        });
                        if (angular.isFunction(scope.datatablesearch)) {
                            scope.datatablesearch(d);
                        }
                    };
                }
                table_obj['ajax'] = dataurl;

                var ngCreateRow = scope.$parent.createdRow;
                if (attrs.ngCreateRow) {
                    ngCreateRow = $parse(attrs.ngCreateRow)(scope);
                }
                table_obj['createdRow'] = ngCreateRow;

                table_obj['initComplete'] = function(settings, json){
                    scope.table.columns().every( function () {
                        var column = this;
                        var th = $(column.footer() );
                        var modelField = $(th).attr("data-model");
                        if (modelField) {
                            scope.$watch(modelField , function(n, o){
                                if (n != o) {
                                    column.search(modelField ).draw();
                                }
                            });
                        }
                    } );
                };

                table_obj["drawCallback"]= function (settings) {
                    var info = scope.table.page.info();
                    $parse(attrs.ngPage).assign(scope, info.pages);
                };

                scope.pageChange = function($current){
                    scope.table.page(Math.max($current - 1, 0)).draw('page');
                };

                scope.table = $(el).DataTable(table_obj);
                scope.table.on( 'processing.dt', function ( e, settings, processing ) {
                    scope.$emit('butterbarEvent', { show: processing });
                } );
                el.parent().parent().parent().find(".row:first").remove();
                el.parent().parent().parent().find(".row:last").remove();


                scope.showToolbar = function(curnode, data){
                    curnode.append($("#table-row-selected-hot").tmpl(data));
                    curnode.find('#table-cell-option').toolbar({
                        content: function(){
                            return $compile($('#'+attrs.ngRowOption).tmpl(data))(scope);
                        },
                        position: 'bottom',
                        style: 'light',
                        hideOnClick: true
                    });
                };

                scope.changeRowOption = function(curcell,colIdx){
                    $(scope.table.cells().nodes()).find(".table-cell-option").remove();
                    if (colIdx.column !== 0) {
                        var curnode = $(curcell.node());
                        var data = scope.table.row(colIdx.row).data();
                        scope.showToolbar(curnode, data);;
                    }
                };

                scope.lastRowIdx = null; scope.lastColumnIdx = null;
                scope.onClickBody = function () {
                    var curcell = scope.table.cell(this);
                    var colIdx = curcell.index();
                    if ( scope.lastColumnIdx !== colIdx.column || scope.lastRowIdx !== colIdx.row ) {
                        scope.changeRowOption(curcell,colIdx);
                        scope.lastColumnIdx = colIdx.column; scope.lastRowIdx = colIdx.row;
                    }
                };
                $(el).find("tbody").on( 'click', 'td', scope.onClickBody);

                window.addEventListener("storage",function(e){
                    if (e.key == attrs.ngRefreshEvent && e.newValue) {
                        scope.table.ajax.reload();
                    }
                },false);

            }
        };
    }]);


angular.module('app').directive('uiStaticDatatable', ['$parse','$compile','$filter', function($parse,$compile,$filter) {
        return {
            restrict: 'AC',

            link: function(scope, el, attrs) {
                var fields = $parse(attrs.uiStaticDatatableFields)(scope);
                function get_field(f){
                    for(var i in fields) {
                        if (fields[i].field == f) {
                            return fields[i];
                        }
                    }
                    return null;
                };
                scope.itemChange = function(i){
                    var data = scope.table.row(i).data();
                    $parse(attrs.uiStaticDatatable).assign(scope, data);
                };
                var thread = el.find("thead tr");

                var rowcheck = '<div class="input-group-btn"><label class="i-checks m-b-none"><input onclick="angular.element(this).scope().itemChange(${i})" type="'+attrs.ngType+'" name="${d.DT_RowIdName}" value="${d.DT_ModelId}"><i style="margin-right:0px;margin-top:0px"></i></label></div>';
                scope.$parent.createdRow = function( row, data, index) {
                    $('td', row).eq(0).html($compile($(rowcheck).tmpl({d:data, i:index}))(scope));
                };
                var table_obj = {
                    "processing": true,
                    "serverSide": true,
                    "order": [
                        [ 1, "desc" ],
                    ],
                    'language': def_dataTable_lang_opt
                };

                var columns = [];
                thread.find("th").each(function(index,element){
                    var e = $(element);
                    var field = e.attr("ng-field");
                    var columnatt = {
                        "data": field,
                        render: function ( data, type, row ) {
                            var fo = get_field(field);
                            return $filter("datatableFields")(data,fo, type, row);
                        }
                    };
                    if (e.attr("searchable")) {
                        columnatt["searchable"] = e.attr("searchable") == "true";
                    }
                    if (e.attr("orderable")) {
                        columnatt["orderable"] = e.attr("orderable") == "true";
                    }
                    columns.push(columnatt);
                });
                table_obj['columns'] = columns;

                var dataurl = {
                    "url":attrs.ngDataUrl,
                    "data": function(d){
                        d.search.field = columns;
                        if (angular.isFunction(scope.datatablesearch)) {
                            scope.datatablesearch(d);
                        }
                    },
                    dataSrc:function( json ) {
                        var data = json.data;
                        for(var i = 0; i < data.length; ++i) {
                            data[i] = $filter("formatFields")(fields, data[i]);
                        }
                        return data;
                    }
                };
                table_obj['ajax'] = dataurl;
                table_obj['createdRow'] = scope.$parent.createdRow;
                scope.table = $(el).DataTable(table_obj);
            }
        };
    }]);
