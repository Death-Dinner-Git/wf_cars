<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:61:"D:\upupw\wf_cars\public/../application/car\view\out\list.html";i:1493804827;s:68:"D:\upupw\wf_cars\public/../application/common\view\layouts\main.html";i:1493366711;}*/ ?>
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
            <script type="text/javascript">

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


	function near(id){
	var self = $("#"+id);
	var build = self.attr("build");
	if(build==null||build==''){
		alertMessage("请点击安排看房完善楼盘信息",5,'',6);
		return;
	}
	var manager = self.attr("manager");
	if(manager==null||manager==''){
		alertMessage("请点击安排看房完善顾问信息",5,'',6);
		return;
	}
	var startCity = self.attr("startCity");
	if(startCity==null||startCity==''){
		alertMessage("请点击安排看房完善起始地信息",5,'',6);
		return;
	}
	var startLat = self.attr("startLat");
	if(startLat==''){
		alertMessage("请点击安排看房完善起始地信息",5,'',6);
		return;
	}
	window.location.href="/car/out/nearcars/id/"+id+".html?iframe=true";
}

//删除out
function del(id){
	$.post("<?php echo url('delOut'); ?>",{id:id},function(data){
		if(data.code==200){
			alertMessage(data.message,6,'',6);
		}else{
			alertMessage(data.message,5,'',6);
		}
	},"json");
}

//删除outorder
function delOutOrder(id){
	$.post("<?php echo url('deloutOrder'); ?>",{id:id},function(data){
		if(data.code==200){
			alertMessage(data.message,6,'',6);
		}else{
			alertMessage(data.message,5,'',6);
		}
	},"json");
}

</script>

<form class="layui-form layui-form-pane" action="<?php echo url('list'); ?>">
    <div class="layui-form-item">

        <label class="layui-form-label">车牌号:</label>
        <div class="layui-input-inline">
            <input type="text" name="numberPlate" class="layui-input" value="<?php echo $front['numberPlate']; ?>">
        </div>

        <label class="layui-form-label">置业顾问:</label>
        <div class="layui-input-inline">
            <input type="text" name="managerName"  class="layui-input" value="<?php echo $front['managerName']; ?>">
        </div>

        <label class="layui-form-label">城市</label>
        <div class="layui-input-inline">
            <select name="cityid" lay-search>
                <option value="">请选择城市</option>
				<?php if(is_array($city) || $city instanceof \think\Collection || $city instanceof \think\Paginator): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					<option value="<?php echo $vo['id']; ?>" <?php if($vo['id'] == $front['cityid']): ?>selected<?php endif; ?> ><?php echo $vo['name']; ?></option>
				<?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
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

        <label class="layui-form-label" lay-search>状态</label>
        <div class="layui-input-inline">
            <select name="status">
                <option value="">请选择状态</option>
                <option value="1" <?php if($front['status'] == '1'): ?>selected<?php endif; ?>>未匹配</option>
				<option value="2" <?php if($front['status'] == '2'): ?>selected<?php endif; ?>>成功匹配</option>
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
        <th>车牌号/司机</th>
        <th>部门办事处/置业顾问</th>
        <th>排车状态</th>
        <th>出发地/接客点/用途</th>
        <th>出车收车时间</th>
        <th>公里数</th>
        <th>人数</th>
        <th>油费</th>
        <th>司机签到</th>
        <th>预看楼盘</th>
        <th style="width:50px; text-align: center">操作</th>
    </tr>
    </thead>
    <tbody>
		<?php if(is_array($OutList) || $OutList instanceof \think\Collection || $OutList instanceof \think\Paginator): $key = 0; $__LIST__ = $OutList;if( count($__LIST__)==0 ) : echo "暂时没有数据" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?>
			<tr data-key="<?php echo $key; ?>">
				<td style="width: 100px;">
					<div>车:<?php echo $vo['number_plate']; ?></div>
					<div>司:<?php echo $vo['driver_name']; ?></div>
				</td>
				<td style="width: 100px;">
					<div>部:<?php echo $vo['department_name']; ?></div>
					<div>人:<?php echo $vo['manager_name']; ?></div>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['order_status_cn']; ?>
				</td>
				<td style="width: 100px;">
					<div>出发地:<?php echo $vo['start_address']; ?></div>
					<div>接客地:<?php echo $vo['end_address']; ?></div>
					<div>用途:<?php echo $vo['used_id']; ?></div>
				</td>
				<td style="width: 150px;">
					<div>出:<?php echo $vo['out_car_time']; ?></div>
					<div>收:<?php echo $vo['back_date']; ?></div>
				</td>
				<td style="width: 100px;">
					<div>出:<?php echo $vo['from_mileage']; ?></div>
					<div>收:<?php echo $vo['back_mileage']; ?></div>
					<div>公里:<?php echo $vo['sign_mileage']; ?></div>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['customer_num']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['oil_cost']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['sign_time']; ?>
				</td>
				<td style="width: 100px;">
					<?php echo $vo['build_name']; ?>
				</td>
				<td style="width: 150px;text-align: center;">
					<?php if($vo['order_status'] == ''): ?>
						<a href="javascript:void(0);">
						  <a  href="<?php echo url('lookbuild',array('id'=>$vo['orderid'])); ?>?iframe=true" class="layui-btn layui-btn-mini">
						   <i class="fa fa-search"></i>安排看房
						  </a>
						</a>
						<a href="javascript:void(0);">
						  <button onclick="near(<?php echo $vo['outid']; ?>)" id="<?php echo $vo['outid']; ?>" build="<?php echo $vo['build_id']; ?>" manager="<?php echo $vo['manager_id']; ?>" startcity="<?php echo $vo['start_city_id']; ?>" endcity="<?php echo $vo['end_city_id']; ?>" startlat="<?php echo $vo['start_lat']; ?>" class="layui-btn layui-btn-mini">
						   <i class="fa fa-search"></i>附近车辆
						  </button>
						</a>
						<a href="javascript:void(0);">
						  <button onclick="del(<?php echo $vo['outid']; ?>)" class="layui-btn layui-btn-mini">
						   <i class="fa fa-search"></i>删除
						  </button>
						</a>
					<?php endif; if($vo['order_status'] == 'success'): ?>
						<a href="javascript:void(0);">
							<button onclick="showUrl('查看行驶记录','<?php echo url('takeOrderShow',array('id'=>$vo['orderid'])); ?>?iframe=true','72%','88%','2',true,true,'LAY_LOAD_<?php echo $vo['orderid']; ?>')" class="layui-btn layui-btn-mini">
								<i class="fa fa-search"></i>
								查看行驶记录
							</button>
						</a>
						<a href="javascript:void(0);">
							<button onclick="showUrl('编辑','<?php echo url('editOrder',array('id'=>$vo['orderid'])); ?>?iframe=true','72%','88%','2')" class="layui-btn layui-btn-mini">
								<i class="fa fa-pencil"></i>
								编辑
							</button>
						</a>
						<a href="javascript:void(0);">
						  <button onclick="delOutOrder(<?php echo $vo['orderid']; ?>)" class="layui-btn layui-btn-mini">
						   <i class="fa fa-remove"></i>删除
						  </button>
						</a>

					<?php endif; if($vo['order_status'] == 'drivering'): ?>
						<a href="javascript:void(0);">
							<button onclick="showUrl('查看行驶记录','<?php echo url('takeOrderShow',array('id'=>$vo['orderid'])); ?>?iframe=true','72%','88%','2',true,true,'LAY_LOAD_<?php echo $vo['orderid']; ?>')" class="layui-btn layui-btn-mini">
								<i class="fa fa-search"></i>
								查看行驶记录
							</button>
						</a>
					<?php endif; if($vo['order_status'] == 'over'): ?>
						<a href="javascript:void(0);">
							<button onclick="showUrl('查看行驶记录','<?php echo url('takeOrderShow',array('id'=>$vo['orderid'])); ?>?iframe=true','72%','88%','2',true,true,'LAY_LOAD_<?php echo $vo['orderid']; ?>')" class="layui-btn layui-btn-mini">
								<i class="fa fa-search"></i>
								查看行驶记录
							</button>
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

