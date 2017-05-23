<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:64:"D:\upupw\wf_cars\public/../application/car\view\driver\list.html";i:1493795790;s:68:"D:\upupw\wf_cars\public/../application/common\view\layouts\main.html";i:1493366711;}*/ ?>
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

    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="_LAYUI_/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="__CSS__/layout.css" media="all">
    <link rel="stylesheet" type="text/css" href="__CSS__/iconfont.css" media="all">

    <!-- For site js -->
    <script type="text/javascript" src="__JS__/jquery.js"></script>
    <script type="text/javascript" src="__JS__/bootstrap.js"></script>
    <script type="text/javascript" src="_LAYUI_/layui.js"></script>

    <title><?php echo !empty($meta_title)?$meta_title.' - ' : ''; ?>_TITLE_</title>

</head>
<body>

<div class="wrap">

    <div class="container"  id="container">

        <div class="layui-layout layui-layout-back" id="layui_layout">

            <!-- 右侧主体内容开启 -->
            <form class="layui-form layui-form-pane" action="<?php echo url('list'); ?>">
    <div class="layui-form-item">
        <label class="layui-form-label">司机姓名:</label>
        <div class="layui-input-inline">
            <input type="text" name="username" class="layui-input" datatype="*" nullmsg="请填写司机姓名" errormsg="司机姓名不正确" value="<?php echo $front['real_name']; ?>"/>
        </div>

        <label class="layui-form-label">开始日期:</label>
        <div class="layui-input-inline">
             <input name="create_time_start" value="<?php echo $front['create_time_start']; ?>" class="layui-input" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
        </div>
        <label class="layui-form-label">结束日期:</label>
        <div class="layui-input-inline">
             <input name="create_time_end" value="<?php echo $front['create_time_end']; ?>" class="layui-input"  onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
        </div>
		<input type="submit" class="layui-btn" value="查询">
		<a href="<?php echo url('add'); ?>?iframe=true" class="layui-btn" >添加</a>
    </div>
</form>

<table class="layui-table" lay-even="" lay-skin="row">
    <thead>
    <tr>
		<th>ID序号</th>
        <th>姓名</th>
        <th>手机</th>
        <th>城市</th>
        <th>点评</th>
        <th>添加时间</th>
        <th style="width:50px; text-align: center">操作</th>
    </tr>
    </thead>
    <tbody>

<?php if(is_array($driverList) || $driverList instanceof \think\Collection || $driverList instanceof \think\Paginator): $key = 0; $__LIST__ = $driverList;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?>
    <tr data-key="<?php echo $key; ?>">
        <td style="width: 100px;">
            <?php echo $vo['id']; ?>
        </td>
        <td style="width: 100px;">
			<?php echo $vo['real_name']; ?>
        </td>
        <td style="width: 100px;">
            <?php echo $vo['mobilephone']; ?>
        </td>
        <td style="width: 100px;">
            <?php echo $vo['name']; ?>
        </td>
        <td style="width: 100px;">
            <?php echo $vo['remark']; ?>
        </td>
        <td style="width: 150px;">
            <?php echo $vo['create_time']; ?>
        </td>
        <td style="width: 150px;text-align: center;">
				<a class="layui-btn" href="<?php echo url('edit',array('id'=>$vo['id'])); ?>?iframe=true">
					编辑
				</a>
				<button onclick="delDriver(<?php echo $vo['id']; ?>)" class="layui-btn">删除</button>
        </td>
    </tr>
<?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
    </tbody>
</table>
<!-- 分页容器 主要是传递总数 -->
<div id="paging_0124" style="text-align:right;" data-pages="<?php echo $count; ?>"></div>

<script>
    layui.use([ 'laydate', 'form'], function() {});
    var alertMessage = function (msg,icon,type,shift,time,shade) {
        msg = msg || '提示信息缺失';
        icon = icon || 0;
        type = type || 0;
        shift = shift || 3;
        time = time || 1000;
        shade = shade || 0.372;
        var config = {icon: icon,type: type,shift: shift,time:time,shade:shade};
        layui.use(['layer'],function () {
            layer.msg(msg,config);
        });
    };

	//删除
	function delDriver(id){
		$.post("<?php echo url('delDriver'); ?>",{id:id},function(data){
			if(data.code==200){
				alertMessage(data.message,6,'',6);
				window.location.href=data.data;
			}else{
				alertMessage(data.message,5,'',6);
			}
		},"json");
	}

</script>
<!-- 分页JavaScript代码 主要是传递总数 -->
<script>
    $(function() {
        layui.use(['laypage', 'layer'], function(){
            var laypage = layui.laypage,
                element = 'paging_0124',
                currPage = $.getUrlParam('pageNumber'),
                count = $('#'+element).attr('data-pages');
				currPage = currPage > 1 ? currPage : 1;
				count = count ? count : 0;
				laypage({
					curr:currPage,
					cont: element,
					pages: count,
					skip: true,
					jump: function(obj,first){
						if(obj.curr != currPage) {
							var url = location.href;
							if(url.indexOf("?") == -1) {
								location.href = url+"?pageNumber="+obj.curr;
							}else {
								var page = $.getUrlParam('pageNumber');
								if(page) {
									location.href = url.replace("pageNumber="+page,"pageNumber="+obj.curr);
								}
								else {
									location.href = url.replace("?","?pageNumber="+obj.curr+"&");
								}
							}
						}
					}
				});
        });
    });
</script>

            <!-- 右侧主体内容结束 -->

        </div>
    </div>

</div>

<!-- 加载js文件-->
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

