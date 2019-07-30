<?php if (!defined('THINK_PATH')) exit();?>

<!DOCTYPE html>

<html>

<head>

    <meta http-equiv="content-type" content="text/html; charset=UTF-8">

    <meta charset="UTF-8">

    <title>奥宝母婴</title>

    <meta name="ios" content="" wireless2content="">

    <meta name="android" content="" wireless2content="">

    <meta name="format-detection" content="telephone=no">

    <meta name="viewport" content="minimal-ui=yes,width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <meta name="msapplication-tap-highlight" content="no">

    <meta name="apple-mobile-web-app-capable" content="yes">

    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <meta name="Keywords" content="">

    <meta name="Description" content="">

    <script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js" type="text/javascript"></script>

    <script type="text/javascript">

        //=点击展开关闭效果=

        function openShutManager(oSourceObj,oTargetObj,shutAble,oOpenTip,oShutTip){

            var sourceObj = typeof oSourceObj == "string" ? document.getElementById(oSourceObj) : oSourceObj;

            var targetObj = typeof oTargetObj == "string" ? document.getElementById(oTargetObj) : oTargetObj;

            var openTip = oOpenTip || "";

            var shutTip = oShutTip || "";

            if(targetObj.style.display!="none"){

                if(shutAble) return;

                targetObj.style.display="none";

                if(openTip  &&  shutTip){

                    sourceObj.innerHTML = shutTip;

                }

            } else {

                targetObj.style.display="block";

                if(openTip  &&  shutTip){

                    sourceObj.innerHTML = openTip;

                }

            }

        }

    </script>

    <link href="__PUBLIC__/css/pqrd/base.css" rel="stylesheet" type="text/css">

    <link href="__PUBLIC__/css/pqrd/sec.css" rel="stylesheet" type="text/css">

</head>

<body>

<div class="topBg">

    <div class="viewportWidth">

        <div class="topOutBg"><b class="topBLT"></b><b class="topBRT"></b><b class="topBLB"></b><b class="topBRB"></b>

            <div class="topOutBor"><b class="topOutBLT"></b><b class="topOutBRT"></b><b class="topOutBLB"></b><b class="topOutBRB"></b>

                <!-- 1 -->

                <div class="topCon1 clear">

                    <div class="con1 fl">

                        <a href="<?php echo (wthumb($m_product['images']['path'],800,600)); ?>" target="_self"  data-lightbox="livephoto">

                        <img src="<?php echo (thumb($m_product['images']['path'],173,173, 0, 'nophoto.gif')); ?>" style="height:100px;width:90px;cursor:pointer;display:inline-block;" />

                        </a>

                    </div>

                    <div class="con2 fr">


                        <div class="ones">

                            <div class="choseList fr"><span class="mains" style="position:relative;">

                                <a href="javascript:void(0);" onClick="openShutManager(this,'boxox',false,'<?php echo ($current_skill['category_name']); ?>','<?php echo ($current_skill['category_name']); ?>')"><?php echo ($current_skill['category_name']); ?></a>



                                <div class="contic" id="boxox" style="display:none">

                                    <?php if(is_array($m_product['skill'])): $i = 0; $__LIST__ = $m_product['skill'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p>

                                            <a href="<?php echo U('Index/namecard', 'id='.$m_product['product_id'].'&cid='.$vo['category_id']);?>"><?php echo ($vo['category_name']); ?></a>

                                        </p><?php endforeach; endif; else: echo "" ;endif; ?>



                                </div></span></div>

                            <p class="names"><?php echo ($m_product['name']); ?></p>

                        </div>

                        <ul class="twos cf">

                            <li><?php echo ($m_product['census']); ?></li>

                            <li><?php echo ($m_product['age']); ?>岁</li>

                        </ul>


                        <div class="threes" id="fuwuxingji"> <span class="title">服务星级：</span>

                            <?php if(is_array($m_product['levelimgs'])): $i = 0; $__LIST__ = $m_product['levelimgs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><img src="<?php echo ($vo); ?>" style="width:18px; margin:0 1px;"/><?php endforeach; endif; else: echo "" ;endif; ?>

                        </div>

                        <script>
                            if ('<?php echo ($current_category_id); ?>' == '6') {
                                $("#fuwuxingji").hide();
                            }
                        </script>
                    </div>

                    <div class="clear"></div>

                </div>

                <!-- \1 -->

                <div class="clear"></div>

                <!-- 2 -->

                <div class="topCon2">

                    <h2 class="tt1"><img src="__PUBLIC__/img/icon1.png" />身份认证</h2>

                    <ul class="con1 cf">

                        <li>

                            <p>


                                    <img src="__PUBLIC__/img/prImg1.png" /><span><img src="__PUBLIC__/img/rightGreen.png" />

                            </p>

                            身份认证

                        </li>



                        <li>

                            <p>


                                    <img src="__PUBLIC__/img/prImg2.png" /><span><img src="__PUBLIC__/img/rightGreen.png" />


                            </p>

                            技能鉴定</li>

                        <li>

                            <p>

                                <img src="__PUBLIC__/img/prImg3.png" /><span><img src="__PUBLIC__/img/rightGreen.png" />

                            </p>

                            保险</li>

                        <li>

                            <p>


                                    <img src="__PUBLIC__/img/prImg4.png" /><span><img src="__PUBLIC__/img/rightGreen.png" />


                            </p>

                            健康证</li>



                    </ul>

                    <h2 class="tt1"><img src="__PUBLIC__/img/icon2.png" />证书认证</h2>
                    <div class="clear"></div>
                    <div class="con2 cf">

                        <div class="con2Out">

                            <ul class="cf">

                                <?php if(is_array($m_product['certificate_pic'])): $i = 0; $__LIST__ = $m_product['certificate_pic'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>

                                        <p>

                                            <a href="<?php echo (wthumb($vo['path'],800,600)); ?>" target="_self" data-lightbox="livephoto">

                                            <img src="<?php echo (thumb($vo['path'],116,82)); ?>" style="height:60px;width:60px;cursor:pointer;display:inline-block;" /><span><img src="__PUBLIC__/img/rightGreen.png" />

                                            </span>
                                            </a>

                                        </p>
                                        <?php echo ($vo['name']); ?>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

                            </ul>

                        </div>

                    </div>


                    <?php if($m_product['health_pic']): ?><h2 class="tt1"><img src="__PUBLIC__/img/icon2.png" />健康证</h2>
                    <div class="clear"></div>
                    <div class="con2 cf">

                        <div class="con2Out">

                            <ul class="cf">

                                <?php if(is_array($m_product['health_pic'])): $i = 0; $__LIST__ = $m_product['health_pic'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>

                                        <p>

                                            <a href="<?php echo (wthumb($vo['path'],800,600)); ?>" target="_self" data-lightbox="health_pic">

                                                <img src="<?php echo (thumb($vo['path'],116,82)); ?>" style="height:60px;width:60px;cursor:pointer;display:inline-block;" /><span><img src="__PUBLIC__/img/rightGreen.png" />

                                            </span>
                                            </a>

                                        </p>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

                            </ul>

                        </div>

                    </div><?php endif; ?>
                    <?php if($m_product['backdrop_pic']): ?><h2 class="tt1"><img src="__PUBLIC__/img/icon2.png" />背景调查图片</h2>
                    <div class="clear"></div>
                    <div class="con2 cf">

                        <div class="con2Out">

                            <ul class="cf">

                                <?php if(is_array($m_product['backdrop_pic'])): $i = 0; $__LIST__ = $m_product['backdrop_pic'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>

                                        <p>

                                            <a href="<?php echo (wthumb($vo['path'],800,600)); ?>" target="_self" data-lightbox="backdrop_pic">

                                                <img src="<?php echo (thumb($vo['path'],116,82)); ?>" style="height:60px;width:60px;cursor:pointer;display:inline-block;" /><span><img src="__PUBLIC__/img/rightGreen.png" />

                                            </span>
                                            </a>

                                        </p>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

                            </ul>

                        </div>

                    </div><?php endif; ?>

                </div>

                <!-- \2 -->

            </div>

        </div>

    </div>

</div>

<div class="viewportWidth">


    <!-- 4 -->

    <div class="tbBor">

        <h2 class="tt1"><img src="__PUBLIC__/img/icon4.png" />阿姨相册<span><a href="<?php echo (wthumb($vo['path'],800,600)); ?>" target="_self"  data-lightbox="livephoto">更多</a><img src="__PUBLIC__/img/arrRight.png" /></span></h2>

        <div class="photos cf">

            <div class="con1Out">

                <ul class="cf">

                    <?php if(is_array($m_product['livephoto'])): $i = 0; $__LIST__ = $m_product['livephoto'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>

                        <a href="<?php echo (wthumb($vo['path'],800,600)); ?>" target="_self"  data-lightbox="livephoto">

                        <img src="<?php echo (thumb($vo['path'],70,70)); ?>" style="height:70px;width:70px;cursor:pointer;display:inline-block;margin-right:10px;border-radius:5px;" />

                        </a>

                    </li><?php endforeach; endif; else: echo "" ;endif; ?>

                </ul>

            </div>

        </div>

    </div>

    <!-- \4 -->

    <!-- 5 -->



    <!-- \5 -->
    <!-- 6 -->

    <div class="tbBor">

        <h2 class="tt1"><img src="__PUBLIC__/img/icon6.png" />服务项目介绍<span><a href="http://m.ourbaby.cc/Page_index_id_85.html">更多</a><img src="__PUBLIC__/img/arrRight.png" /></span></h2>

        <div class="fwxmBor">

            <div class="fwxmTt"><span><b></b><strong>新生儿护理</strong><b></b></span></div>

            <table>

                <tr>

                    <th>新生儿生活护理</th>

                    <td><ul>

                        <li>婴儿房观测及清洁</li>

                        <li>新生儿体征观测</li>

                        <li>新生儿用品消毒</li>

                        <li>阳光浴、空气浴</li>

                        <li>眼耳鼻护理</li>

                        <li>口腔护理</li>

                        <li>新生儿洗澡</li>

                        <li>新生儿抚触</li>

                        <li>穿衣舒适度检查</li>

                        <li>新生儿修整指甲</li>

                        <li>新生儿大便情况观察</li>

                        <li>新生儿小便情况观察</li>

                    </ul></td>

                </tr>

                <tr>

                    <th>新生儿生活护理</th>

                    <td><ul>

                        <li>婴儿房观测及清洁</li>

                        <li>新生儿体征观测</li>

                        <li>新生儿用品消毒</li>

                        <li>阳光浴、空气浴</li>

                        <li>眼耳鼻护理</li>

                        <li>口腔护理</li>

                        <li>新生儿洗澡</li>

                        <li>新生儿抚触</li>

                        <li>穿衣舒适度检查</li>

                        <li>新生儿修整指甲</li>

                        <li>新生儿大便情况观察</li>

                        <li>新生儿小便情况观察</li>

                    </ul></td>

                </tr>

            </table>

        </div>
        <div class="fwxmBor">

            <div class="fwxmTt"><span><b></b><strong>专业记录</strong><b></b></span></div>

            <table>

                <tr>

                    <th>专业护理</th>

                    <td><ul>

                        <li>婴儿房观测及清洁</li>

                        <li>新生儿体征观测</li>

                    </ul></td>

                </tr>

            </table>

        </div>
        <!-- title -->

    </div>
    <!-- \6 -->
    <!-- 7 -->
    <div class="tbBor">
        <h2 class="tt1"><img src="__PUBLIC__/img/icon7.png" />奥宝母婴介绍<span><a href="http://m.ourbaby.cc/Page_index_id_11.html">更多</a><img src="__PUBLIC__/img/arrRight.png" /></span></h2>

        <div class="khpjBor">

            <div class="khpjCon">奥宝母婴是北京阿姨汇科技服务有限公司旗下官网，定位于高端母婴护理服务品牌，一直梦想着为每一位宝宝营造出温馨舒适的成长环境，并让准妈妈和新妈妈们喜爱和享受到奥宝的贴心服务。奥宝母婴把每一个孩子都当成自己的宝宝，公司的英文名称——OURBABY，意指我们会像呵护自己的孩子一样关爱您的宝宝，用我们的体贴和温馨把服务做到最好。在服务项目的流程设计上，“尽心和责任”被视作品牌经营中的最重要元素。母婴护理服务是世界上最不平凡的工作，亦是最幸福的工作。尽职尽责，用我们的诚意换来万千家庭的欢乐，我们的任何一项服务都会源于这一目标，因为我们深深体会到，宝宝和妈妈的笑容，便是给我们的最佳回报。</div>

        </div>

    </div>

    <!-- \7 -->

  <!-- footer -->
  <div class="footervo">
       <div class="footervot"><strong>全国统一预约服务热线</strong>：<a href="tel:400-066-6755"><span><strong>4000666755</strong></span></a></div>
  </div>
  <!-- footer end -->

</div>

</body>

</html>

<script type="text/javascript" src="__PUBLIC__/js/startScore.js"></script>

<script>

    //显示分数

    $(".atarShow b").each(function(index, element) {

        var num=$(this).attr("tip");

        var www=num*2*8;//

        $(this).css("width",www);

    });

    $(function(){

        var oLis1=$('.topCon2 .con2 li');

        var oLisL1=$('.topCon2 .con2 li').length;

        var oLisW1=oLis1.width()*oLisL1;

        oLis1.css('width',$('.topCon2 .con2').width()*0.23);

        $('.topCon2 .con2 .con2Out,.topCon2 .con2 ul').css('width',oLisW1+20*oLisL1);

        $('.topCon2 .con2 .con2Out,.topCon2 .con2 ul').css('height',oLis1.height());



        var oLis2=$('.photos li');

        var oLisL2=$('.photos li').length;

        var oLisW2=oLis2.width()*oLisL2;

        oLis2.css('width',$('.photos').width()*0.20);

        $('.photos .con1Out,.photos .con1Out ul').css('width',oLisW2+10*oLisL2);

        $('.photos .con1Out,.photos .con1Out ul').css('height',oLis2.height());



    })

</script>
<script type="text/javascript" charset="UTF-8" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    wx.config({
        appId: "<?php echo ($sp['appId']); ?>",
        timestamp:"<?php echo ($sp['timestamp']); ?>" ,
        nonceStr: "<?php echo ($sp['nonceStr']); ?>",
        signature: "<?php echo ($sp['signature']); ?>",
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'hideOptionMenu',
            'showOptionMenu',
        ]
    });

    wx.ready(function(){
        var opt = {
            title: "奥宝母婴护理师<?php echo ($m_product['name']); ?>的名片",
            link: "<?php echo U('Index/namecard','id='.$m_product['product_id'], true, false, true);?>",
            imgUrl: "http://www.ourbaby.cc<?php echo (thumb($m_product['images']['path'],173,173, 0, 'nophoto.gif')); ?>",
            success: function(){

            },
            cancel: function(){

            },
            desc: "奥宝母婴提供专业月嫂、育儿嫂，奥宝，努力为宝宝做到最好！"
        };
        wx.onMenuShareTimeline(opt);
        wx.onMenuShareAppMessage(opt);
        wx.onMenuShareQQ(opt);
        wx.onMenuShareWeibo(opt);
    });

</script>
<link href="__PUBLIC__/lightbox/css/lightbox.css" rel="stylesheet" type="text/css">
<script src="__PUBLIC__/lightbox/js/lightbox.js" type="text/javascript"></script>