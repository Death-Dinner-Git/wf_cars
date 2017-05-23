<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:67:"D:\upupw\wf_cars\public/../application/manage\view\index\index.html";i:1493799047;s:69:"D:\upupw\wf_cars\public/../application/common\view\layouts\index.html";i:1493782922;s:70:"D:\upupw\wf_cars\public/../application/common\view\layouts\header.html";i:1493793430;s:71:"D:\upupw\wf_cars\public/../application/common\view\layouts\sidebar.html";i:1492422866;s:70:"D:\upupw\wf_cars\public/../application/common\view\layouts\footer.html";i:1492422866;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="author" content="">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="url" content="">

    <!-- Favicon -->
    <link rel="shortcut icon" href="_SHORTCUT_" type="image/x-icon">

    <!-- For site css -->

    <script type="text/javascript" src="__JS__/jquery.js"></script>
    <script type="text/javascript" src="__JS__/bootstrap.js"></script>
    <script type="text/javascript" src="_LAYUI_/layui.js"></script>

    <!-- Fake Loader start -->
    <link rel="stylesheet" href="_PLUGINS_/fakeloader/css/fakeloader.css">
    <script src="_PLUGINS_/fakeloader/js/fakeloader.js"></script>
    <script>
        var _spinner2 = {
            timeToHide:1200,
            bgColor:"#1abc9c",
            spinner:"spinner2",
            zIndex:'20170407',
            loadCss:{
                top:'46%',
                left:'50%'
            }
        };
        $(document).ready(function(){
            $(".fakeloader").fakeLoader(_spinner2);
        });
    </script>
    <!-- Fake Loader end -->

    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="_LAYUI_/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="__CSS__/layout.css" media="all">
    <link rel="stylesheet" type="text/css" href="__CSS__/iconfont.css" media="all">

    <title><?php echo !empty($meta_title)?$meta_title.' - ' : ''; ?>_TITLE_</title>

</head>
<body>

<div class="wrap">

    <div class="container"  id="container">

        <div class="layui-layout layui-layout-back" id="layui_layout">

            <!-- 顶部区域开启 -->
            <div class="layui-header header header-theme-one">

    <!-- 顶级右边开始 -->
    <div class="dinner-site-resize">
        <div class="dinner-side-toggle">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </div>
        <div class="dinner-side-full" id="dinner-side-full">
            <i class="fa fa-arrows-alt" aria-hidden="true"></i>
        </div>
    </div>
    <!-- 顶级右边结束 -->

    </textarea>

    <div class="layui-main dinner-header-body">

        <!-- 顶级左侧logo区域开启 -->
        <div class="dinner-logo-box">
            <a class="dinner-logo-title" href="javascript:void(0);" title="出车系统"><span style="">Car Out</span></a>
            <a class="logo" href="javascript:void(0);" title="logo"><img src="/static/images/logo.png" alt=""></a>
        </div>
        <!-- 顶级左侧logo区域结束 -->

        <!-- 顶级中间区域导航开启 -->
        <div class="layui-dinner-menu">
            <!--<ul class="layui-nav clearfix">-->
                <!--<li class="layui-nav-item layui-this">-->
                    <!--<a href="javascirpt:;"><i class="fa fa-home"></i>模块1</a>-->
                <!--</li>-->
            <!--</ul>-->
        </div>
        <!-- 顶级中间区域导航结束 -->

        <!-- 顶级右侧区域导航开启 -->
        <ul class="layui-nav dinner-header-item">
            <li class="layui-nav-item first">
                <a href="javascript:;">
                    <img src="/static/images/default-user.png" class="userimg">
                    <cite>Dinner</cite>
                    <span class="layui-nav-more"></span>
                </a>
                <dl class="layui-nav-child">
                    <dd>
                        <a href="">操作1</a>
                    </dd>
                    <dd>
                        <a href="">操作2</a>
                    </dd>
                </dl>
            </li>
            <!--<li class="layui-nav-item">-->
                <!--<a href="javascript:;" id="lock">-->
                    <!--<i class="fa fa-lock" aria-hidden="true" style="padding-right: 3px;padding-left: 1px;"></i> 锁屏 (Alt+L)-->
                <!--</a>-->
            <!--</li>-->
            <li class="layui-nav-item">
                <a href="/manage/login/logout.html">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                    退出</a>
            </li>
        </ul>
        <!-- 顶级右侧区域导航结束 -->

    </div>
</div>
            <!-- 顶级区域结束 -->

            <!-- 左侧侧边导航开始 -->
            <div class="layui-side layui-side-bg layui-dinner-side" id="dinner-side">
    <div class="layui-side-scroll dinner-side-body" id="dinner-nav-side" lay-filter="side">
        <div id="dinner-nav-side-header">
            <div class="user-photo" id="user-profile" style="position: absolute;">
                <a class="img" title="我的头像" >
                    <img src="/static/images/default-user.png" class="user-images"></a>
                <p>你好！Dinner, 欢迎登录</p>
            </div>
        </div>
        <!-- 左侧菜单 -->
        <div><?php echo \app\common\components\MenuHelper::generateMenu(1) ?></div>
    </div>
</div>
            <!-- 左侧侧边导航结束 -->

            <!-- 右侧主体内容开启 -->
            <div class="layui-body" id="dinner-body" style="bottom: 0;border-left: solid 2px #1AA094;">
                <div class="layui-tab layui-tab-card dinner-tab-box" id="dinner-tab" lay-filter="switch-tab" lay-allowclose="true" lay-separator=" | ">
                    <ul class="layui-tab-title">
                        <li class="layui-this"  id="dinner-home">
                            <i class="fa fa-dashboard" aria-hidden="true"></i>
                            <cite>控制面板</cite>
                        </li>
                    </ul>
                    <div class="layui-tab-content" style="min-height: 800px; ">
                        <div class="layui-tab-item layui-show">
                            <iframe class="dinner-iframe" data-id='0' frameborder="0" scrolling="no" src=""></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 右侧主体内容结束 -->

            <!-- 底部区域开启 -->
            <div class="layui-footer layui-dinner-foot" id="dinner-footer">
    <div class="layui-main dinner-footer-body">
        <div style="display: flex;">
            <p style="margin: auto;"><span><?php echo date('Y'); ?> &copy;</span> Write by CAR,<a href="http://www.car.cc">CAR</a>. 版权所有</p>
        </div>
    </div>
</div>
            <!-- 底部区域结束 -->

            <!-- 其他区域开启 -->
            <div class="site-tree-mobile layui-hide">
                <i class="layui-icon">&#xe602;</i>
            </div>
            <div class="site-mobile-shade"></div>
            <!-- 其他区域结束 -->

        </div>
    </div>

</div>

<!-- fakeLoader start -->
<div class="fakeloader"></div>
<!-- fakeLoader end -->

<!--锁屏模板 start-->
<!--<script type="text/template" id="lock-temp">-->
    <!--<div class="dinner-header-lock" id="lock-box">-->
        <!--<div class="dinner-header-lock-img">-->
            <!--<img src="/static/images/default-user.png"/>-->
        <!--</div>-->
        <!--<div class="dinner-header-lock-name" id="lockUserName">Dinner</div>-->
        <!--<input type="text" class="dinner-header-lock-input" value="输入密码解锁.." name="lockPwd" id="lockPwd" />-->
        <!--<button class="layui-btn layui-btn-small" id="unlock">解锁</button>-->
    <!--</div>-->
<!--</script>-->
<!--锁屏模板 end -->

<!-- 菜单控件 -->
<!-- <div class="dinner-tab-menu">
	<span class="layui-btn dinner-test">刷新</span>
</div> -->

<!-- iframe框架刷新操作 -->
<!-- <div id="refresh_iframe" class="layui-btn refresh_iframe">刷新</div> -->

<!-- 加载js文件-->
<!-- 侧栏菜单 -->
<script type="text/javascript" src="__JS__/navbar.js"></script>
<!-- 重定义layui 的tab -->
<script type="text/javascript" src="__JS__/tab.js"></script>
<!--  布局控制 -->
<script type="text/javascript" src="__JS__/layout.js"></script>
<!--  全局 -->
<script type="text/javascript" src="__JS__/site.js"></script>
<script>
    layui.use('layer', function() {
        var $ = layui.jquery,
            layer = layui.layer;
    });
</script>

</body>
</html>

