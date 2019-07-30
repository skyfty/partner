<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php if(isset($message)): echo L('SUCCESSFUL_OPERATION'); else: echo L('OPERATION_FAILED'); endif; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="<?php echo L('AUTHOR');?>">
      <link type="text/css" href="http://apps.bdimg.com/libs/bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet" />
      <link type="text/css" href="http://apps.bdimg.com/libs/bootstrap/2.3.2/css/bootstrap-responsive.min.css" rel="stylesheet">

      <link rel="shortcut icon" href="__PUBLIC__/ico/favicon.png">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 400px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }
	  .wukong {
	    margin-top:30px;
		padding-top:10px;
		border-top:1px solid #e5e5e5;		
	  }
    </style>
    <script type="text/javascript">
        var browserInfo = {browser:"", version: ""};
        var ua = navigator.userAgent.toLowerCase();
        if (window.ActiveXObject) {
            browserInfo.browser = "IE";
            browserInfo.version = ua.match(/msie ([\d.]+)/)[1];
            if(browserInfo.version <= 7){
                if(confirm("您的ie浏览器版本过低，建议使用chorme浏览器，\n点击【确定】转到下载页面")){}
                location.href = 'http://www.google.cn/intl/zh-CN/chrome/browser/';
            }
        }
    </script>
  </head>
  <body>
    <div class="container">
      <form action="" method="post" class="form-signin">
		<fieldset>
			<legend><h3><?php if(isset($message)): echo L('SUCCESSFUL_OPERATION'); else: echo L('OPERATION_FAILED'); endif; ?></h3></legend>		
			
			<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
			<?php if(empty($alert)): if(isset($message)): ?><div class="alert alert-success"><?php echo ($message); ?></div>
			<?php else: ?>
			<div class="alert alert-error"><?php echo ($error); ?></div><?php endif; endif; ?>
			<p class="jump">
                <?php echo L('JUMP',array($jumpUrl,$waitSecond));?>
            </p> 

      </form>
    </div>

    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>
    <script src="http://apps.bdimg.com/libs/bootstrap/2.3.2/js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>


<script language='javascript' type='text/javascript'>
    var secs =<?php echo ($waitSecond); ?>; //倒计时的秒数
    function Load(){
        for(var i=secs;i>=0;i--) {
            window.setTimeout('doUpdate(' + i + ')', (secs-i) * 1000);
        }
    }
    function doUpdate(num) {
        document.getElementById('wait').innerHTML = num;
        if(num == 0) {
            window.location.assign("<?php echo ($jumpUrl); ?>");
        }
    }
</script>
<script language="javascript">
    Load();
</script>
<script language="JavaScript">
    javascript:window.history.forward(1);
</script>