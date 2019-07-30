function changeCondition(field, condition, conditionContent, searchContent, search){
    if (!field) field = "field";
    if (!conditionContent) conditionContent = "conditionContent";
    if (!searchContent) searchContent = "searchContent";
    if (!search) search = "search";
    if (!condition) condition = "condition";


    var a = $("#"+field+" option:selected").attr('class');
    var b = $("#"+field+" option:selected").val();
    var c = $("#"+field+" option:selected").attr('rel');

    if (b == 'subgroup') {
        $("#"+conditionContent).html('');
        $("#"+searchContent).html('<input id="'+search+'" type="text" class="input-medium search-query" name="search"/>&nbsp;');
    }else if(b == 'branch_id') {
        $.ajax({
            type:'get',
            url:'index.php?m=Branch&a=getlist',
            async:false,
            success:function(data){
                options = '';
                $.each(data, function(k, v){
                    options += '<option value="'+v.branch_id+'">'+v.name+'</option>';
                });
                $("#"+searchContent).html('<select id="'+search+'" style="width:auto" name="search">' + options + '</select>&nbsp;');
                $("#"+conditionContent).html('');
            },
            dataType:'json'
        });
    } else if(a == 'number' || a == 'floatnumber') {
        $("#"+conditionContent).html('<select id="'+condition+'" style="width:auto" name="'+b+'[condition]" onchange="changeSearch()">'
        +'<option value="gt">  '+CrmLang.GT+'  </option>'
        +'<option value="lt">  '+CrmLang.LT+'  </option>'
        +'<option value="eq">  '+CrmLang.EQ+'  </option>'
        +'<option value="neq">  '+CrmLang.NEQ+'  </option>'
        +'</select>&nbsp; <script>$(function(){$("#'+condition+'").chosen({disable_search_threshold:10})});</script>');
        $("#"+searchContent).html('<input id="'+search+'" type="text" class="input-medium search-query" name="'+b+'[value]"/>&nbsp;');
    } else if ((a == 'word') || (a == 'text') || (a == 'textarea') || (a == 'editor') || (a == 'mobile') || (a == 'email')) {
        $("#"+conditionContent).html('<select id="'+condition+'" style="width:auto" name="'+b+'[condition]" onchange="changeSearch()">'
        +'<option value="contains">'+CrmLang.CONTAINS+'</option>'
        +'<option value="not_contain">'+CrmLang.NOT_CONTAIN+'</option>'
        +'<option value="is">'+CrmLang.IS+'</option>'
        +'<option value="isnot">'+CrmLang.ISNOT+'</option>'
        +'<option value="start_with">'+CrmLang.START_WITH+'</option>'
        +'<option value="end_with">'+CrmLang.END_WITH+'</option>'
        +'<option value="is_empty">'+CrmLang.IS_EMPTY+'</option>'
        +'<option value="is_not_empty">'+CrmLang.IS_NOT_EMPTY+'</option></select>&nbsp;<script>$(function(){$("#'+condition+'").chosen({disable_search_threshold:10})});</script>');
        $("#"+searchContent).html('<input id="'+search+'" type="text" class="input-medium search-query" name="'+b+'[value]"/>&nbsp;');
    } else if (a == 'date' || a== 'datetime') {
        var is_showtime = $("#"+field+" option:selected").attr('is_showtime');
        var picker_begin_option = is_showtime?"{ alwaysUseStartDate:true, startDate:'%y-%M-%d 00:00:00', dateFmt: 'yyyy-MM-dd HH:mm' }":"{dateFmt: 'yyyy-MM-dd'}";
        var picker_end_option = is_showtime?"{ alwaysUseStartDate:true, startDate:'%y-%M-%d 23:59:59', dateFmt: 'yyyy-MM-dd HH:mm' }":"{dateFmt: 'yyyy-MM-dd'}";

        $("#"+conditionContent).html('<input type="hidden" name="'+b+'[condition]" id="'+condition+'" value="tbetween"/>');
        var search_html = "";
        search_html += '<input id="search_bt" readonly  type="text" style="width:90px;background-color: white;cursor:default;" placeholder="开始时间" class="input-medium search-query" name="'+b+'[value][0]" onFocus="WdatePicker('+picker_begin_option+')"/>-';
        search_html += '<input id="search_et" readonly type="text" style="width:90px;background-color: white;cursor:default;" placeholder="结束时间" class="input-medium search-query" name="'+b+'[value][1]" onFocus="WdatePicker('+picker_end_option+')"/>&nbsp;';
        $("#"+searchContent).html(search_html);
    } else if (a == 'bool') {
        $("#"+conditionContent).html('<select id="'+condition+'" style="width:auto" name="'+b+'[condition]" onchange="changeSearch()">'
        +'<option value="1">'+CrmLang.IS+'</option>'
        +'<option value="0">'+CrmLang.ISNOT+'</option>'
        +'</select>&nbsp;<script>$(function(){$("#'+condition+'").chosen({disable_search_threshold:10})});</script>');
        $("#"+searchContent).html('<input id="'+search+'" type="text" class="input-medium search-query"  name="'+b+'[value]"/>&nbsp;');
    } else if (a == 'sex') {
        $("#"+searchContent).html('<select id="'+search+'" style="width:auto"  name="'+b+'[value]">'
        +'<option value="1">'+CrmLang.MAN+'</option>'
        +'<option value="0">'+CrmLang.WOMAN+'</option>'
        +'</select>&nbsp;<script>$(function(){$("#'+search+'").chosen({disable_search_threshold:10})});</script>');
        $("#"+conditionContent).html('<input type="hidden" name="'+b+'[condition]" id="'+condition+'" value="eq"/>');
    } else if (a == 'role' || a == 'user') {
        $.ajax({
            type:'get',
            url:'index.php?m=user&a=getAllRoleList',
            async:false,
            success:function(data){
                options = '';
                $.each(data.data, function(k, v){
                    options += '<option value="'+v.role_id+'">'+v.user_name+' ['+v.department_name+'-'+v.role_name+']</option>';
                });
                $("#"+searchContent).html('<select id="'+search+'" style="width:auto"  name="'+b+'[value]">' + options + '</select>&nbsp;<script>$(function(){$("#'+search+'").chosen({})});</script>');
                $("#"+conditionContent).html('<input type="hidden" name="'+b+'[condition]" id="'+condition+'" value="eq"/>');
            },
            dataType:'json'
        });
    } else if(a=='all') {
        $("#"+conditionContent).html('<select id="'+condition+'" style="width:auto"  name="'+b+'[condition]" onchange="changeSearch()">'
        +'<option value="contains">'+CrmLang.CONTAINS+'</option>'
        +'<option value="is">'+CrmLang.IS+'</option>'
        +'<option value="start_with">'+CrmLang.START_WITH+'</option>'
        +'<option value="end_with">'+CrmLang.END_WITH+'</option>'
        +'<option value="is_empty">'+CrmLang.IS_EMPTY+'</option>'
        +'</select>&nbsp;<script>$(function(){$("#'+condition+'").chosen({disable_search_threshold:10})});</script>');
        $("#"+searchContent).html('<input id="'+search+'" type="text" class="input-medium search-query"  name="'+b+'[value]"/>&nbsp;');
    }
    else if (a == 'as_box') {
        var options = '<option value="1">已完成</option><option value="0">未完成</option>';
        $("#"+searchContent).html('<select id="'+search+'" style="width:auto"   name="'+b+'[value]">' + options + '</select>&nbsp;<script>$(function(){$("#'+search+'").chosen({})});</script>');
        $("#"+conditionContent).html('<input type="hidden" name="'+b+'[condition]" id="'+condition+'" value="eq"/>');
    }
    else if (b == 'state' && c == 'trade') {
        var options = '<option value="待开始">待开始</option><option value="已结束">已结束</option><option value="进行中">进行中</option><option value="待付款">待付款</option><option value="已撤销">已撤销</option>';
        $("#"+searchContent).html('<select id="'+search+'" style="width:auto"   name="'+b+'[value]">' + options + '</select>&nbsp;<script>$(function(){$("#'+search+'").chosen({})});</script>');
        $("#"+conditionContent).html('<input type="hidden" name="'+b+'[condition]" id="'+condition+'" value="eq"/>');
    }else if (b == 'census') {
        var options = '<option value="">全部</option><option value="南方">南方</option><option value="北方">北方</option>';
        var html = '<select id="'+search+'_census" name="search_census" sf="' +search+ '" style="width:auto" onchange="on_census_chnage(this);">' + options + '</select>&nbsp;';
        html += '<select id="'+search+'" style="width:auto"   name="'+b+'[value]"></select>&nbsp;';
        $("#"+searchContent).html(html);
        $("#"+conditionContent).html('<input type="hidden" name="'+b+'[condition]" id="'+condition+'" value="eq"/>');
        on_census_chnage($("#" + search + "_census"));
    }
    else if (a == 'box') {
        $.ajax({
            type:'get',
            url:'index.php?m=setting&a=boxfield&model='+c+'&field='+b,
            async:false,
            success:function(data){
                options = '';
                $.each(data.data, function(k, v){
                    options += '<option value="'+v+'">'+v+'</option>';
                });
                $("#"+searchContent).html('<select id="'+search+'" style="width:auto"   name="'+b+'[value]">' + options + '</select>&nbsp;<script>$(function(){$("#'+search+'").chosen({})});</script>');
                if(data.info == 'checkbox'){
                    $("#"+conditionContent).html('<input type="hidden"  name="'+b+'[condition]" value="contains">');
                }else{
                    $("#"+conditionContent).html('<input type="hidden" name="'+b+'[condition]" id="'+condition+'" value="eq"/>');
                }
            },
            dataType:'json'
        });
    }else if (a == 'a_box') {
        $.ajax({
            type:'get',
            url:'index.php?m=account&a=getclausetype',
            async:false,
            success:function(data){
                options = '';
                $.each(data.data, function(k, v){
                    options += '<option value="'+v.type_id+'">'+v.name+'</option>';
                });
                $("#"+searchContent).html('<select id="'+search+'" style="width:auto"   name="'+b+'[value]">' + options + '</select>&nbsp;<script>$(function(){$("#'+search+'").chosen({})});</script>');
                $("#"+conditionContent).html('<input type="hidden" name="'+b+'[condition]" id="'+condition+'" value="eq"/>');
            },
            dataType:'json'
        });
    }else if (a == 's_box') {
        $.ajax({
            type:'get',
            url:'index.php?m=product&a=getcategory',
            async:false,
            success:function(data){
                show_categoory_field_condition(data, searchContent, conditionContent, search);
            },
            dataType:'json'
        });
    }
    else {
        $("#"+conditionContent).html('<select id="'+condition+'" style="width:auto" name="'+b+'[condition]" onchange="changeSearch()">'
            +'<option value="is">'+CrmLang.IS+'</option>'
            +'<option value="isnot">'+CrmLang.ISNOT+'</option>'
            +'<option value="is_empty">'+CrmLang.IS_EMPTY+'</option>'
            +'<option value="is_not_empty">'+CrmLang.IS_NOT_EMPTY+'</option></select>&nbsp;<script>$(function(){$("#'+condition+'").chosen({disable_search_threshold:10})});</script>');
        $("#"+searchContent).html('<input id="'+search+'" type="text" class="input-medium search-query" name="'+b+'[value]"/>&nbsp;');
    }
}


function changeSearch() {
    var a = $("#field option:selected").attr('class');
    var b = $("#condition option:selected").val();
    if(b == 'is_empty' || b == 'is_not_empty') {
        $("#searchContent").html('');
    } else {
        if(a == "date") {
            var search_html = "";
            search_html += '<input id="search_bt"  type="text" style="width:80px" placeholder="开始时间" class="input-medium search-query"  name="'+b+'[value][0]" onFocus="WdatePicker()"/>-';
            search_html += '<input id="search_et"  type="text" style="width:80px" placeholder="结束时间" class="input-medium search-query"  name="'+b+'[value][1]" onFocus="WdatePicker()"/>&nbsp;';
            $("#searchContent").html(search_html);
        }  else if (a == "number" || a == "word") {
            $("#searchContent").html('<input id="search" type="text" class="input-medium search-query"   name="'+b+'[value]"/>&nbsp;');
        }
    }
}


function on_census_chnage(cf) {
    var search_field = $(cf).attr("sf");
    $.ajax({
        type:'get',
        url:'index.php?m=setting&a=census_fields&census='+$(cf).val(),
        async:false,
        success:function(data){
            var options = '<option value="">全部</option>';
            $.each(data.data, function(k, v){
                options += '<option value="'+v+'">'+v+'</option>';
            });
            $("#" + search_field).html(options);
        },
        dataType:'json'
    });
}