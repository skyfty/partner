<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
*{margin:0px;padding:0px;}
/*------ Style liyingang521@163.com ---------------------------------------------------------------------*/
html button::-moz-focus-inner{border-color:transparent!important; }
input, button, textarea,select, optgroup, option {font-family:inherit;font-size:100%;font-style:inherit;font-weight:inherit;}
ul, li, dl, dt, dd, ol {display:block;list-style:none; }
fieldset, img {border:0;}
iframe{background-image:none; }
.clearth{clear:both;height:0px;line-height:0px;font-size:0px;}
/* Link ----------------------------*/
a {color:#666666;text-decoration:none;}
a:hover{color:#870000;text-decoration:underline;}
.shengbg{width:832px;height:263px;background:#fff;margin:60px auto; font-size:13px; font-weight:600; position:relative;}
.xingming{position:absolute;top:36px;left:80px;line-height:30px;height:30px;z-index:22;}
.xingbie{position:absolute;top:65px;left:80px;line-height:30px;height:30px;z-index:22;}
.minzu{position:absolute;top:65px;left:166px;line-height:30px;height:30px;z-index:22;}
.nian{position:absolute;top:95px;left:80px;line-height:30px;height:30px;z-index:22;}
.yue{position:absolute;top:95px;left:145px;line-height:30px;height:30px;z-index:22;}
.ri{position:absolute;top:95px;left:180px;line-height:30px;height:30px;z-index:22;}
.zhuzhi{position:absolute;top:131px;left:80px;line-height:20px;z-index:22; width:180px;}
.zhaopian{position:absolute;top:30px;left:255px;line-height:20px;z-index:22; width:125px; height:145px;}
.haoma{position:absolute;top:202px;left:140px;line-height:30px;z-index:22;height:30px; letter-spacing:1px;}
.jiguan{position:absolute;top:177px;left:590px;line-height:30px;z-index:22;height:30px; letter-spacing:1px;}
.youxiaoqi{position:absolute;top:209px;left:590px;line-height:30px;z-index:22;height:30px;}
.dayin{ position: fixed; top:0; left:0; text-align:center;}
</style>
</head>
<body>
<div class="shengbg">
    <img src="__PUBLIC__/img/sfbg.jpg" />
	<div class="xingming"><?php echo ($model['name']); ?></div><!-- 姓名 -->
	<div class="xingbie"><?php echo ($model['sex']); ?></div><!-- 性别 -->
	<div class="minzu"><?php echo ($model['nation']); ?></div><!-- 民族 -->
	<div class="nian"><?php echo (todate($model['birthday'],"Y")); ?></div><!-- 年 -->
	<div class="yue"><?php echo (todate($model['birthday'],"m")); ?></div><!-- 月 -->
	<div class="ri"><?php echo (todate($model['birthday'],"d")); ?></div><!-- 日 -->
	<div class="zhuzhi"><?php echo ($model['address']); ?></div><!-- 住址 -->
	<div class="zhaopian"><img src="<?php echo ($model['cardpic']['path']); ?>" width="125"/></div><!-- 照片 -->
	<div class="haoma"><?php echo ($model['cardid']); ?></div><!-- 号码 -->
	<div class="jiguan"><?php echo ($model['police']); ?></div><!-- 签发机关 -->
	<div class="youxiaoqi"><?php echo (todate($model['validstart'],"Y.m.d")); ?>-<?php echo (todate($model['validend'],"Y.m.d")); ?></div><!-- 有效期 -->
</div>
<!-- head end -->
</body>
</html>