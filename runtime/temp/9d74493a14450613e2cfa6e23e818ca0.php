<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"D:\upupw\wf_cars\public/../application/car\view\takecarorder\list.html";i:1493795791;s:68:"D:\upupw\wf_cars\public/../application/common\view\layouts\main.html";i:1493366711;}*/ ?>
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
        <label class="layui-form-label">车牌号:</label>
        <div class="layui-input-inline">
            <input type="text" name="numberPlate" value="<?php echo $front['numberPlate']; ?>" class="layui-input">
        </div>
        <label class="layui-form-label">置业顾问:</label>
        <div class="layui-input-inline">
            <input type="text" name="managerName" value="<?php echo $front['managerName']; ?>" class="layui-input">
        </div>
        <label class="layui-form-label" lay-search>司机</label>
        <div class="layui-input-inline">
            <select name="driverId">
                <option value="">请选择司机</option>
				<?php if(is_array($driver) || $driver instanceof \think\Collection || $driver instanceof \think\Paginator): $i = 0; $__LIST__ = $driver;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                 <option value="<?php echo $vo['id']; ?>" <?php if($vo['id'] == $front['driverId']): ?>selected<?php endif; ?> ><?php echo $vo['real_name']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <label class="layui-form-label">部门</label>
        <div class="layui-input-inline">
            <select name="departmentid" lay-search>
                <option value="">请选择部门</option>
				<?php if(is_array($department) || $department instanceof \think\Collection || $department instanceof \think\Paginator): $i = 0; $__LIST__ = $department;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					<option value="<?php echo $vo['id']; ?>" <?php if($vo['id'] == $front['departmentid']): ?>selected<?php endif; ?> ><?php echo $vo['name']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <label class="layui-form-label" lay-search>状态</label>
        <div class="layui-input-inline">
            <select name="orderstatus">
                <option value="">请选择状态</option>
				 <option value="ordered" <?php if($front['orderstatus'] == 'ordered'): ?>selected<?php endif; ?>>抢单中</option>
				 <option value="success" <?php if($front['orderstatus'] == 'success'): ?>selected<?php endif; ?>>成功匹配</option>
				 <option value="drivering" <?php if($front['orderstatus'] == 'drivering'): ?>selected<?php endif; ?>>订单进行中</option>
				 <option value="over" <?php if($front['orderstatus'] == 'over'): ?>selected<?php endif; ?>>订单完成</option>
				 <option value="cancel" <?php if($front['orderstatus'] == 'cancel'): ?>selected<?php endif; ?>>行程取消</option>
            </select>
        </div>
        <label class="layui-form-label" lay-search>出车类型</label>
        <div class="layui-input-inline">
            <select name="ordertype">
				 <option value="">请选择状态</option>
				 <option value="come" <?php if($front['ordertype'] == 'come'): ?>selected<?php endif; ?>>去程</option>
				 <option value="back" <?php if($front['ordertype'] == 'back'): ?>selected<?php endif; ?>>返程</option>
            </select>
        </div>
        <label class="layui-form-label">开始日期:</label>
          <div class="layui-input-inline">
               <input name="create_time_start" value="<?php echo $front['create_time_start']; ?>" class="layui-input" placeholder="开始日期" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
          </div>
        <label class="layui-form-label">结束日期:</label>
          <div class="layui-input-inline">
               <input name="create_time_end" value="<?php echo $front['create_time_end']; ?>" class="layui-input" placeholder="结束日期" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
          </div>

       <div class="layui-input-inline">
           <input type="submit" class="layui-btn" value="搜索"/>
       </div>

    </div>
</form>

<table class="layui-table" lay-skin="row" lay-even="">
    <thead>
    <tr>
        <th>订单编号</th>
        <th>类型</th>
        <th>部门办事处/置业顾问</th>
        <th>预看楼盘</th>
        <th>人数</th>
        <th>路程距离</th>
        <th>订单日期</th>
        <th>状态</th>
        <th>是否还车</th>
        <th style="width:50px; text-align: center">操作</th>
    </tr>
    </thead>
    <tbody>
	<?php if(is_array($TakeCarOrderList) || $TakeCarOrderList instanceof \think\Collection || $TakeCarOrderList instanceof \think\Paginator): $key = 0; $__LIST__ = $TakeCarOrderList;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?>
			<tr data-key="<?php echo $key; ?>">
				<td style="width: 100px;">
					<?php echo $vo['order_num']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['order_type_cn']; ?>
				</td>
				<td style="width: 150px;">
					部门：<?php echo $vo['dep_name']; ?><br/>
					业务员：<?php echo $vo['mang_name']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['build_name']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['customerNum']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['driver_mileage']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['start_time']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['order_status_cn']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['order_type_cn']; ?>
				</td>
				<td style="width: 100px;text-align:center;">

					<?php if($vo['order_status'] == 'drivering'): ?>
						<a href="javascript:void(0);">
						  <a  href="<?php echo url('Showrecord',array('id'=>$vo['id'])); ?>?iframe=true" class="layui-btn layui-btn-mini">
						   <i class="fa fa-search"></i>查看实时记录
						 </a>
						</a>
					<?php endif; if($vo['order_status'] == 'success'): ?>
						<a href="javascript:void(0);">
						  <a  href="<?php echo url('Showrecord',array('id'=>$vo['id'])); ?>?iframe=true" class="layui-btn layui-btn-mini">
						   <i class="fa fa-search"></i>查看实时记录
						 </a>
						</a>
					<?php endif; if($vo['order_status'] == 'over'): ?>
						<a href="javascript:void(0);">
						  <a  href="<?php echo url('Showrecord',array('id'=>$vo['id'])); ?>?iframe=true" class="layui-btn layui-btn-mini">
						   <i class="fa fa-search"></i>查看行驶记录
						 </a>
						</a>
					<?php endif; ?>


				</td>
			</tr>
	<?php endforeach; endif; else: echo "暂时没有数据" ;endif; ?>
    </tbody>
</table>

<!-- 分页容器 主要是传递总数 -->
<div id="paging_0124" style="text-align:right;" data-pages="<?php echo $count; ?>"></div>

<script>
    layui.use([ 'laydate', 'form'], function() {});
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

