<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"D:\upupw\wf_cars\public/../application/manage\view\datacount\count.html";i:1493019747;s:68:"D:\upupw\wf_cars\public/../application/common\view\layouts\main.html";i:1493366711;}*/ ?>
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
        .layui-table th, .layui-table td{
            text-align: center;
            vertical-align: middle;
        }
    </style>

</head>
<body>

<fieldset class="layui-elem-field">
    <legend>数据概览</legend>
    <div class="layui-field-box">
        <table class="layui-table" lay-even="" lay-skin="row">
            <thead>
            <tr>
                <th>时间</th>
                <th>发单数量</th>
                <th>出车数量</th>
                <th>行驶公里数</th>
                <th>行驶时间</th>
                <th>抢单数量</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($dataProvider) || $dataProvider instanceof \think\Collection || $dataProvider instanceof \think\Paginator): $i = 0; $__LIST__ = $dataProvider;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$model): $mod = ($i % 2 );++$i;?>
            <tr data-key="<?php echo $key; ?>">
                <td><?php echo $model['name']; ?></td>
                <td><?php echo $model['takeCarOrderCount']; ?></td>
                <td><?php echo $model['outCarCount']; ?></td>
                <td><?php echo $model['outCarMileage']; ?></td>
                <td><?php echo $model['outCarTime']; ?></td>
                <td><?php echo $model['grabSingleCount']; ?></td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
</fieldset>


<fieldset class="layui-elem-field">
    <legend>出车情况</legend>
    <div class="layui-field-box">
        <div class="layui-form-item" style="display: flex;width:100%;overflow: hidden;">
            <div style="width:62.8%;margin: auto">
                <div class="layui-input-inline">
                    <input type="text" value="" name="startTime" id="start" placeholder="开始时间" class="layui-input"  onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                </div>
                <span style="float: left;display: block;padding: 9px 9px 9px 0px;">--</span>
                <div class="layui-input-inline">
                    <input type="text" value="" name="endTime" id="end" placeholder="截止时间" class="layui-input"  onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                </div>
                <button class="layui-btn" lay-search="car">查询</button>
            </div>
        </div>
        <div style="display: flex;width:100%;min-height: 400px;overflow: hidden; padding:15px;">
            <div id="chartCar" style="width:95%;height:400px;margin: auto"></div>
        </div>
    </div>
</fieldset>

<script>
    layui.use([ 'laydate', 'form'], function() {});
</script>
<script type="text/javascript" src="_PLUGINS_/echarts/echarts.js"></script>
<script>
    var alertMsg = function (msg,tip) {
        var config = {icon: 5,shift: 6};
        if (tip){
            config.icon = 6;
        }
        layui.use(['layer'],function () {
            layer.msg(msg,config);
        });
    };

    //出车情况
    var _baseUrl = '/manage/datacount/chart',_getOutCarUrl = '', _dataOutCar = {
        title: '出车情况',
        series: [],
        xAxis: [],
        yAxis: [],
        yDataCount: [],
        max: 0,
        start: '',
        end: '',
    }, preOutCar = [];
    function getOutCar() {
        var myChartOutCar = echarts.init(document.getElementById('chartCar'));
        myChartOutCar.showLoading();
        if (_getOutCarUrl == ''){_getOutCarUrl = _baseUrl;}
        $.get(_getOutCarUrl, function (data) {
            if (data.code == '1') {
                if (data.options.title) {
                    _dataOutCar.title = data.options.title;
                }
                if (data.options.dataCount) {
                    _dataOutCar.yDataCount = data.options.dataCount;
                }
                if (data.options.xAxis) {
                    _dataOutCar.xAxis = data.options.xAxis;
                }
                if (data.options.yAxis) {
                    _dataOutCar.yAxis = data.options.yAxis;
                }
                if (data.options.max) {
                    _dataOutCar.max = data.options.max;
                }
                if (data.options.start) {
                    _dataOutCar.start = data.options.start;
                }
                if (data.options.end) {
                    _dataOutCar.end = data.options.end;
                }
                _dataOutCar.series = data.options.series;
                var _tmpPre = {
                    name: _dataOutCar.start + '_' + _dataOutCar.end,
                    preData: _dataOutCar,
                }
                var _hasPre = false;
                for (var j = 0; j < preOutCar.length; j++) {
                    if (preOutCar[j].name == (_dataOutCar.start + '_' + _dataOutCar.end)) {
                        _hasPre = true;
                        preOutCar[j] = _tmpPre;
                    }
                }
                if (!_hasPre) {
                    preOutCar.push(_tmpPre);
                }
                exportOutCar();
            }
        });
        function exportOutCar() {
            var _outCarOption = {
                baseOption: {
                    title: {
                        text: _dataOutCar.title,
                        subtext: new Date(),
                        x: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: function (params) {
                            var res = params.name + '<br/>';
                            var _value = params.value;
                            var _series = _outCarOption.baseOption.series;
                            var _sum = 0;
                            for (var i = 0; i < _series.length; i++) {
                                for (var j = 0; j < _series[i].data.length; j++) {
                                    _sum += parseInt(_series[i].data[j].value);
                                }
                            }
                            res += '数量 ：' + _value + '<br/>';
                            res += '总数 ：' + _sum + '<br/>';
                            var _percent = parseInt(_value / _sum * 10000) / 100;
                            if (isNaN(_percent)) {
                                _percent = 0;
                            }
                            res += params.name + '占的比例 ： ' + _percent + '%  <br/>';
                            return res;
                        }
                    },
                    toolbox: {
                        show: true,
                        left: '20%',
                        feature: {
                            mark: {show: true},
                            dataView: {show: true, readOnly: true},
                            magicType: {
                                show: true,
                                type: ['pie', 'funnel']
                            },
                            restore: {show: true},
                            saveAsImage: {show: true},
                        }
                    },
                    xAxis: {
                        type: 'category',
                        data: _dataOutCar.xAxis
                    },
                    yAxis: {
                        type: 'value',
                        max:_dataOutCar.max,
                    },
                    series: [
                        {
                            type:'bar',
                            data:_dataOutCar.yDataCount,
                            label: {
                                normal: {show:true,position:'top',formatter: '{c}'},
                            },
                            markPoint : {
                                data : [
                                    {type : 'max', name: '最大值',symbolSize:[80,80]},
                                    {type : 'min', name: '最小值'}
                                ]
                            },
                            markLine : {
                                data : [
                                    {type : 'average', name: '平均值'}
                                ]
                            }
                        }
                    ]
                },

            };
            myChartOutCar.hideLoading();
            if (_outCarOption && typeof _outCarOption === "object") {
                myChartOutCar.setOption(_outCarOption, true);
            }
            $('[lay-search="car"]').unbind('click').on('click',function () {
                getOtherData();
            });

            function getOtherData() {
                var start = $('#start').val(),end = $('#end').val();
                if (!start || !end || start == '' || end == ''){
                    alertMsg('查询条件未满足');
                    return;
                }
                if (start>end){
                    var tmp = start;
                    start = end;
                    end = tmp;
                }
                for (var k = 0; k < preOutCar.length; k++) {
                    if (preOutCar[k].name == start + '_'+ end) {
                        _dataOutCar = preOutCar[k].preData;
                        exportOutCar();
                        return true;
                    }
                }
                _getOutCarUrl = _baseUrl + '?start=' + start + '&end=' + end;
                $('#chartCar').html('');
                getOutCar();
            }
        }
    }
    getOutCar();
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

