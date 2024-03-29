<?php if (!defined('THINK_PATH')) exit();?><!-- list -->
<ul class="nav">
    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
        <span translate="aside.nav.HEADER"></span>
    </li>

    <li ui-sref-active="active">
        <a href>
            <i class="glyphicon glyphicon-stats icon text-primary-dker"></i>
            <span translate="aside.nav.DASHBOARD">我的面板</span>
        </a>
    </li>


    <li class="line dk"></li>

    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
        <span translate="aside.nav.components.COMPONENTS">导航</span>
    </li>

    <li ng-class="{active:$state.includes('app.ui')}">
        <a href class="auto">
          <span class="pull-right text-muted">
            <i class="fa fa-fw fa-angle-right text"></i>
            <i class="fa fa-fw fa-angle-down text-active"></i>
          </span>
            <b class="badge bg-info pull-right">3</b>
            <i class="glyphicon glyphicon-th"></i>
            <span>雇员管理</span>
        </a>
        <ul class="nav nav-sub dk">
            <li ui-sref-active="active">
                <a ui-sref="index.product">
                    <span>雇员列表</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="logs.product">
                    <span>日志</span>
                </a>
            </li>
        </ul>
    </li>

    <li ng-class="{active:$state.includes('app.ui')}">
        <a href class="auto">
          <span class="pull-right text-muted">
            <i class="fa fa-fw fa-angle-right text"></i>
            <i class="fa fa-fw fa-angle-down text-active"></i>
          </span>
            <b class="badge bg-info pull-right">3</b>
            <i class="glyphicon glyphicon-th"></i>
            <span>客户管理</span>
        </a>
        <ul class="nav nav-sub dk">
            <li ui-sref-active="active">
                <a ui-sref="index.customer">
                    <span>客户列表</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="logs.customer">
                    <span>日志</span>
                </a>
            </li>
        </ul>
    </li>

    <li ng-class="{active:$state.includes('app.ui')}">
        <a href class="auto">
      <span class="pull-right text-muted">
        <i class="fa fa-fw fa-angle-right text"></i>
        <i class="fa fa-fw fa-angle-down text-active"></i>
      </span>
            <i class="glyphicon glyphicon-briefcase icon"></i>
            <span translate="aside.nav.components.ui_kits.UI_KITS">调度管理</span>
        </a>
        <ul class="nav nav-sub dk">
            <li ui-sref-active="active">
                <a href>
                    <span translate="aside.nav.components.ui_kits.UI_KITS">入职总表</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="dispatch.branch">
                    <span translate="aside.nav.components.ui_kits.BUTTONS">门店调度</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="dispatch.hospatl">
                    <b class="badge bg-info pull-right">3</b>
                    <span translate="aside.nav.components.ui_kits.ICONS">医院调度列表</span>
                </a>
            </li>
        </ul>
    </li>

    <li ui-sref-active="active">
        <a ui-sref="app.market">
            <i class="glyphicon glyphicon-signal"></i>
            <span>客户服务</span>
        </a>
    </li>

    <li ng-class="{active:$state.includes('app.ui')}">
        <a href class="auto">
      <span class="pull-right text-muted">
        <i class="fa fa-fw fa-angle-right text"></i>
        <i class="fa fa-fw fa-angle-down text-active"></i>
      </span>
            <i class="glyphicon glyphicon-briefcase icon"></i>
            <span translate="aside.nav.components.ui_kits.UI_KITS">产品管理</span>
        </a>
        <ul class="nav nav-sub dk">
            <li ui-sref-active="active">
                <a href>
                    <span translate="aside.nav.components.ui_kits.UI_KITS">产品订单</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.ui.buttons">
                    <span translate="aside.nav.components.ui_kits.BUTTONS">产品列表</span>
                </a>
            </li>

            <li ui-sref-active="active">
                <a ui-sref="app.ui.grid">
                    <span translate="aside.nav.components.ui_kits.GRID">成本</span>
                </a>
            </li>
        </ul>
    </li>

    <li ng-class="{active:$state.includes('app.ui')}">
        <a href class="auto">
      <span class="pull-right text-muted">
        <i class="fa fa-fw fa-angle-right text"></i>
        <i class="fa fa-fw fa-angle-down text-active"></i>
      </span>
            <i class="glyphicon glyphicon-briefcase icon"></i>
            <span translate="aside.nav.components.ui_kits.UI_KITS">培训管理</span>
        </a>
        <ul class="nav nav-sub dk">
            <li ui-sref-active="active">
                <a href>
                    <span translate="aside.nav.components.ui_kits.UI_KITS">培训订单</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.ui.buttons">
                    <span translate="aside.nav.components.ui_kits.BUTTONS">培训列表</span>
                </a>
            </li>
        </ul>
    </li>

    <li ng-class="{active:$state.includes('app.ui')}">
        <a href class="auto">
      <span class="pull-right text-muted">
        <i class="fa fa-fw fa-angle-right text"></i>
        <i class="fa fa-fw fa-angle-down text-active"></i>
      </span>
            <b class="label bg-primary pull-right">2</b>
            <i class="glyphicon glyphicon-list"></i>
            <span translate="aside.nav.components.table.TABLE">账户管理</span>
        </a>
        <ul class="nav nav-sub dk">
            <li ui-sref-active="active">
                <a href>
                    <span translate="aside.nav.components.table.TABLE">客户服务收入确认</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.table.static">
                    <span translate="aside.nav.components.table.TABLE_STATIC">培训收入确认</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.table.datatable">
                    <span translate="aside.nav.components.table.DATATABLE">公司账户</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.table.footable">
                    <span translate="aside.nav.components.table.FOOTABLE">客户账户</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.table.grid">
                    <span>雇员账户</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.table.grid">
                    <span>员工账户</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.table.grid">
                    <span>公司流水</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.table.grid">
                    <span>日志</span>
                </a>
            </li>
        </ul>
    </li>

    <li ng-class="{active:$state.includes('app.ui')}">
        <a href class="auto">
      <span class="pull-right text-muted">
        <i class="fa fa-fw fa-angle-right text"></i>
        <i class="fa fa-fw fa-angle-down text-active"></i>
      </span>
            <i class="glyphicon glyphicon-briefcase icon"></i>
            <span translate="aside.nav.components.ui_kits.UI_KITS">床位管理</span>
        </a>
        <ul class="nav nav-sub dk">
            <li ui-sref-active="active">
                <a href>
                    <span translate="aside.nav.components.ui_kits.UI_KITS">床位管理</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.ui.buttons">
                    <span translate="aside.nav.components.ui_kits.BUTTONS">宿舍管理</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.ui.icons">
                    <b class="badge bg-info pull-right">3</b>
                    <span translate="aside.nav.components.ui_kits.ICONS">宿舍导图</span>
                </a>
            </li>
        </ul>
    </li>
    <li ui-sref-active="active">
        <a ui-sref="app.chart">
            <i class="glyphicon glyphicon-signal"></i>
            <span translate="aside.nav.components.CHART">客服管理</span>
        </a>
    </li>

    <li ng-class="{active:$state.includes('app.ui')}">
        <a href class="auto">
      <span class="pull-right text-muted">
        <i class="fa fa-fw fa-angle-right text"></i>
        <i class="fa fa-fw fa-angle-down text-active"></i>
      </span>
            <i class="glyphicon glyphicon-briefcase icon"></i>
            <span translate="aside.nav.components.ui_kits.UI_KITS">加盟商管理</span>
        </a>
        <ul class="nav nav-sub dk">
            <li ui-sref-active="active">
                <a href>
                    <span translate="aside.nav.components.ui_kits.UI_KITS">加盟商列表</span>
                </a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="app.ui.icons">
                    <b class="badge bg-info pull-right">3</b>
                    <span translate="aside.nav.components.ui_kits.ICONS">日志</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="line dk hidden-folded"></li>

    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
        <span translate="aside.nav.your_stuff.YOUR_STUFF">自己</span>
    </li>
    <li>
        <a ui-sref="app.page.profile">
            <i class="icon-user icon text-success-lter"></i>
            <b class="badge bg-success pull-right">30%</b>
            <span translate="aside.nav.your_stuff.PROFILE">配制</span>
        </a>
    </li>
    <li>
        <a ui-sref="app.docs">
            <i class="icon-question icon"></i>
            <span translate="aside.nav.your_stuff.DOCUMENTS">文档</span>
        </a>
    </li>
</ul>
<!-- / list -->