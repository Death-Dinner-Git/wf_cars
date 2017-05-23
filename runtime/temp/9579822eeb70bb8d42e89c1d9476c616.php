<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"D:\upupw\wf_cars\public/../application/manage\view\user\list.html";i:1494557380;s:68:"D:\upupw\wf_cars\public/../application/common\view\layouts\main.html";i:1493366711;}*/ ?>
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
            <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>表格</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title><?php echo !empty($meta_title)?$meta_title.' - ' : ''; ?>_TITLE_</title>
    <style>
        table td.action{
            padding: 6px 10px;
        }
        table td.action a button i{
            padding-right: 4px;
        }
    </style>

</head>
<body>

<fieldset class="layui-elem-field">
    <legend>管理员列表</legend>
    <div class="layui-field-box">
        <form class="layui-form layui-form-pane" action="">
            <div class="layui-form-item">

                <label class="layui-form-label">真实姓名:</label>
                <div class="layui-input-inline">
                    <input type="text" value="<?php echo $key; ?>" name="key" placeholder="真实姓名" class="layui-input">
                </div>

                <?php if($super !== 'true'): ?>
                <label class="layui-form-label">管理员类型</label>
                <div class="layui-input-inline">
                    <select name="type" lay-search >
                        <option value="0">全部</option>
                        <?php if(is_array($typeList) || $typeList instanceof \think\Collection || $typeList instanceof \think\Paginator): $i = 0; $__LIST__ = $typeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <!--<option value="<?php echo $key; ?>" <?php if(in_array(($key), is_array($type)?$type:explode(',',$type))): ?>selected<?php endif; ?>><?php echo $item; ?></option>-->
                        <option value="<?php echo $key; ?>" <?php if($key == $type): ?>selected<?php endif; ?>><?php echo $item; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </div>
                <?php else: ?><input type="hidden" value="<?php echo $type; ?>" name="type"><input type="hidden" value="true" name="super">
                <?php endif; ?>

                <button class="layui-btn" lay-submit="">查询</button>

            </div>
        </form>
    </div>
</fieldset>

<table class="layui-table" lay-even="" lay-skin="row">
    <thead>
    <tr>
        <th>序号</th>
        <th>登录名称</th>
        <th>真实名称</th>
        <th>账号类型</th>
        <!--<th>部门</th>-->
        <th style="width:50px; text-align: center">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($dataProvider) || $dataProvider instanceof \think\Collection || $dataProvider instanceof \think\Paginator): $key = 0; $__LIST__ = $dataProvider;if( count($__LIST__)==0 ) : echo "<tr class='empty'><td colspan='5' style='text-align: center;'> 没有数据 </td> </tr>" ;else: foreach($__LIST__ as $key=>$model): $mod = ($key % 2 );++$key;?>
    <tr data-key="<?php echo $model['id']; ?>">
        <td style="width: 45px;text-align: center;">
            <!--<?php echo $key+($indexOffset); ?>-->
            <?php echo $model['id']; ?>
        </td>
        <td style="width: 100px;"><?php echo $model['getBaseUser']['username']; ?></td>
        <td><?php echo $model['real_name']; ?></td>
        <td> <?php if(isset($model->managerList[$model->manager_type])): ?><?php echo $model->managerList[$model->manager_type]; endif; ?></td>
        <!--<td><?php echo $model['getDepartment']->name; ?></td>-->
        <td style="width: 150px;text-align: center;" class="action">
            <a href="javascript:void(0);">
                <button onclick="showUrl('编辑用户信息','/manage/user/update.html?id=<?php echo $model['id']; ?>&iframe=true','72%','88%','2')" class="layui-btn layui-btn-small">
                    <i class="fa fa-pencil"></i>
                    编辑
                </button>
            </a>
            <a href="javascript:void(0);"><button onclick="showDialog('信息确认','你确定删除此用户吗?',function(){deleteUser(<?php echo $model['id']; ?>);})" class="layui-btn layui-btn-small"><i class="fa fa-remove"></i>删除</button></a>
        </td>
    </tr>
    <?php endforeach; endif; else: echo "<tr class='empty'><td colspan='5' style='text-align: center;'> 没有数据 </td> </tr>" ;endif; ?>
    </tbody>
</table>

<!-- 分页容器 -->
<div id="paging_0124" style="text-align:right;" data-count="<?php echo $count; ?>" data-pages="<?php echo $pages; ?>" data-page=""></div>

<script>
    layui.use([ 'layer','laydate', 'form'], function() {});
    function deleteUser(id) {
        $.ajax({
            type : "post",
            url : '/manage/user/delete?id='+ id,
            dataType : 'json',
            beforeSend : function() {
                loading();
            },
            success : function(data) {
                setTimeout(hide(),500);
                if (data.code == '1'){
                    $('[data-key="'+id+'"]').remove();
                    success('删除成功');
                }
            },
            error : function(data) {
                hide();
                error('删除失败');
            }
        });
    }
</script>
<script>
    $(function() {
        layui.use(['laypage', 'layer'], function(){
            var laypage = layui.laypage,
                element = 'paging_0124',
                _page = $('#'+element),
                currPage = $.getUrlParam('pageNumber'),
                count = _page.attr('data-count'),
                pages = _page.attr('data-pages');
            currPage = currPage > 1 ? currPage : 1;
            count = count ? count : 0;
            _page.attr('data-page',currPage);
            laypage({
                curr:currPage,
                cont: element,
                pages: pages,
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
                            }else {
                                location.href = url.replace("?","?pageNumber="+obj.curr+"&");
                            }
                        }
                    }
                    _page.prepend('<span style="display: inline-block;text-align:center;padding: 0 20px;color: #333;font-size: 14px;"> 数量: '+(count)+' </span>');
                }
            });
        });
    });
</script>

</body></html>
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

