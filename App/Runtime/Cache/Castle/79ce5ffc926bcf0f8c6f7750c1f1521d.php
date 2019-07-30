<?php if (!defined('THINK_PATH')) exit();?><div ng-controller="calendarController">
  <div class="hbox hbox-auto-xs hbox-auto-sm">
    <div class="col wrapper-md">
      <div class="clearfix m-b">
        <button type="button" tooltip="Double click on calendar day to create event" class="btn btn-sm btn-primary btn-addon" ng-click="addEvent()">
          <i class="fa fa-plus"></i>添加日程
        </button>
        <button type="button" tooltip="Double click on calendar day to create event" class="btn btn-sm btn-primary btn-addon" ng-click="addEvent()">
          改变请假状态
        </button>
        <div class="pull-right">
          <button type="button" class="btn btn-sm btn-default btn-addon pull-right m-l-xs" ui-toggle-class="show" target="#aside">
            <i class="fa fa-bars"></i> 列表
          </button>

          <button type="button" class="btn btn-sm btn-default" ng-click="today()">今天</button>
          <div class="btn-group m-l-xs">
            <button class="btn btn-sm btn-default" ng-click="changeView('agendaDay')">天</button>
            <button class="btn btn-sm btn-default" ng-click="changeView('agendaWeek')">周</button>
            <button class="btn btn-sm btn-default" ng-click="changeView('month')">月</button>
          </div>
        </div>

      </div>
      <div class="pos-rlt">
        <div class="fc-overlay">
          <div class="panel bg-white b-a pos-rlt">
            <span class="arrow"></span>
            <div class="h4 font-thin m-b-sm">{{event.title}}</div>
            <div class="line b-b b-light"></div>
            <div><i class="icon-calendar text-muted m-r-xs"></i> {{event.start | date:'medium'}}</div>
            <div class="ng-hide" ng-show='event.end'><i class="icon-clock text-muted m-r-xs"></i> {{event.end | date:'medium'}}</div>
            <div class="ng-hide" ng-show='event.location'><i class="icon-pointer text-muted m-r-xs"></i> {{event.location}}</div>
            <div class="m-t-sm">{{event.info}}</div>
            <div class="m-t-sm">{{event.url}}</div>
          </div>
        </div>
        <div class="calendar" ng-model="eventSources" config="uiConfig.calendar" ui-calendar="uiConfig.calendar"></div>
      </div>
    </div>
    <div class="col w-md w-auto-xs bg-light dk bg-auto b-l hide" id="aside">
      <div class="wrapper">
        <div ng-repeat="e in events" class="bg-white-only r r-2x m-b-xs wrapper-sm {{e.className[0]}}">          
          <input ng-model="e.title" class="form-control m-t-n-xs no-border no-padder no-bg">
          <a class="pull-right text-xs text-muted" ng-click="remove($index)"><i class="fa fa-trash-o"></i></a>
          <div class="text-xs text-muted">{{e.start | date:"MMM dd"}} - {{e.end | date:"MMM dd"}}</div>
        </div>
      </div>
    </div>
  </div>
</div>