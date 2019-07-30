
app.directive("uiPage",function($compile, $parse, $q) {
    return {
        link:function($scope, $element, $attrs) {
            $attrs.$observe("total", function(total){
                var options = {
                    alignment:"center",
                    bootstrapMajorVersion:3,
                    numberOfPages:"10",
                    itemTexts: defaultPageItemText,
                    onPageChanged: function(e,oldPage,newPage){
                        $scope.$apply(function(){
                            $parse($attrs.casModel).assign($scope,newPage);
                        });
                        $scope.$eval($attrs.casChange, {
                            '$current': newPage,
                            '$totalPages': $attrs.total
                        });
                    }
                };
                var currentPage = $parse($attrs.current)($scope);
                if (total > 0) {
                    var currentPage = $parse($attrs.current)($scope);
                    options["currentPage"] = (currentPage?currentPage:1);
                }
                options["totalPages"] = total;
                var html = $("<ul  class='pagination' style='margin-top:0px'></ul>");
                html.bootstrapPaginator(options);
                $element.html($compile(html)($scope));
            });
        }
    };
});