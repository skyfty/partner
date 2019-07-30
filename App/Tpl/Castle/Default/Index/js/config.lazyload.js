// lazyload config

angular.module('app')
    /**
   * jQuery plugin config use ui-jq directive , config the js and css files that required
   * key: function name of the jQuery plugin
   * value: array of the css js file located
   */
  .constant('JQ_CONFIG', {
      easyPieChart:   ['App/Tpl/Castle/Default/Index/vendor/jquery/charts/easypiechart/jquery.easy-pie-chart.js'],
      sparkline:      ['App/Tpl/Castle/Default/Index/vendor/jquery/charts/sparkline/jquery.sparkline.min.js'],
      plot:           ['App/Tpl/Castle/Default/Index/vendor/jquery/charts/flot/jquery.flot.min.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/charts/flot/jquery.flot.resize.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/charts/flot/jquery.flot.tooltip.min.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/charts/flot/jquery.flot.spline.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/charts/flot/jquery.flot.orderBars.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/charts/flot/jquery.flot.pie.min.js'],
      slimScroll:     ['App/Tpl/Castle/Default/Index/vendor/jquery/slimscroll/jquery.slimscroll.min.js'],
      sortable:       ['App/Tpl/Castle/Default/Index/vendor/jquery/sortable/jquery.sortable.js'],
      nestable:       ['App/Tpl/Castle/Default/Index/vendor/jquery/nestable/jquery.nestable.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/nestable/nestable.css'],
      filestyle:      ['App/Tpl/Castle/Default/Index/vendor/jquery/file/bootstrap-filestyle.min.js'],
      slider:         ['App/Tpl/Castle/Default/Index/vendor/jquery/slider/bootstrap-slider.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/slider/slider.css'],
      chosen:         ['App/Tpl/Castle/Default/Index/vendor/jquery/chosen/chosen.jquery.min.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/chosen/chosen.css'],
      TouchSpin:      ['App/Tpl/Castle/Default/Index/vendor/jquery/spinner/jquery.bootstrap-touchspin.min.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/spinner/jquery.bootstrap-touchspin.css'],
      wysiwyg:        ['App/Tpl/Castle/Default/Index/vendor/jquery/wysiwyg/bootstrap-wysiwyg.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/wysiwyg/jquery.hotkeys.js'],
      dataTable:      ['App/Tpl/Castle/Default/Index/vendor/jquery/datatables/jquery.dataTables.min.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/datatables/dataTables.bootstrap.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/datatables/dataTables.bootstrap.css'],
      vectorMap:      ['App/Tpl/Castle/Default/Index/vendor/jquery/jvectormap/jquery-jvectormap.min.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/jvectormap/jquery-jvectormap-world-mill-en.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/jvectormap/jquery-jvectormap-us-aea-en.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/jvectormap/jquery-jvectormap.css'],
      footable:       ['App/Tpl/Castle/Default/Index/vendor/jquery/footable/footable.all.min.js',
                          'App/Tpl/Castle/Default/Index/vendor/jquery/footable/footable.core.css']
      }
  )
  // oclazyload config
  .config(['$ocLazyLoadProvider', function($ocLazyLoadProvider) {
      // We configure ocLazyLoad to use the lib script.js as the async loader
      $ocLazyLoadProvider.config({
          debug:  false,
          events: true,
          modules: [
              {
                  name: 'ngGrid',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/ng-grid/ng-grid.min.js',
                      'App/Tpl/Castle/Default/Index/vendor/modules/ng-grid/ng-grid.min.css',
                      'App/Tpl/Castle/Default/Index/vendor/modules/ng-grid/theme.css'
                  ]
              },
              {
                  name: 'ui.select',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/angular-ui-select/select.min.js',
                      'App/Tpl/Castle/Default/Index/vendor/modules/angular-ui-select/select.min.css'
                  ]
              },
              {
                  name:'angularFileUpload',
                  files: [
                    'App/Tpl/Castle/Default/Index/vendor/modules/angular-file-upload/angular-file-upload.js'
                  ]
              },
              {
                  name:'ui.calendar',
                  files: ['App/Tpl/Castle/Default/Index/vendor/modules/angular-ui-calendar/calendar.js']
              },
              {
                  name: 'ngImgCrop',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/ngImgCrop/ng-img-crop.js',
                      'App/Tpl/Castle/Default/Index/vendor/modules/ngImgCrop/ng-img-crop.css'
                  ]
              },
              {
                  name: 'angularBootstrapNavTree',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/angular-bootstrap-nav-tree/abn_tree_directive.js',
                      'App/Tpl/Castle/Default/Index/vendor/modules/angular-bootstrap-nav-tree/abn_tree.css'
                  ]
              },
              {
                  name: 'toaster',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/angularjs-toaster/toaster.js',
                      'App/Tpl/Castle/Default/Index/vendor/modules/angularjs-toaster/toaster.css'
                  ]
              },
              {
                  name: 'textAngular',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/textAngular/textAngular-sanitize.min.js',
                      'App/Tpl/Castle/Default/Index/vendor/modules/textAngular/textAngular.min.js'
                  ]
              },
              {
                  name: 'vr.directives.slider',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/angular-slider/angular-slider.min.js',
                      'App/Tpl/Castle/Default/Index/vendor/modules/angular-slider/angular-slider.css'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/videogular/videogular.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.controls',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/videogular/plugins/controls.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.buffering',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/videogular/plugins/buffering.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.overlayplay',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/videogular/plugins/overlay-play.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.poster',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/videogular/plugins/poster.min.js'
                  ]
              },
              {
                  name: 'com.2fdevs.videogular.plugins.imaads',
                  files: [
                      'App/Tpl/Castle/Default/Index/vendor/modules/videogular/plugins/ima-ads.min.js'
                  ]
              }
          ]
      });
  }])
;