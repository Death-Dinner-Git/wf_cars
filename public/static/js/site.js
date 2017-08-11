var layer = top.window.layer ? top.window.layer : window.layer;
// var _width = document.documentElement.clientWidth;//获取页面可见宽度
// var _height = document.documentElement.clientHeight;//获取页面可见高度
var config = {
    shade:['0.372','#000'],
    width:{
        max:'800px',
        small:'700px',
        min:'600px'
    },
    height:{
        max:'500px',
        small:'400px',
        min:'300px'
    }
};

$(function() {
    //layui
    layui.config({
        base: '/static/js/',
        version:new Date().getTime()
    }).use(['jquery','element','layer', 'util', 'code', 'form','laydate'],function(){
        window.jQuery = window.$ = layui.jquery;
        window.layer = layui.layer;
        var element = layui.element(),
            $ = layui.jquery,
            util = layui.util,
            layer = layui.layer,
            form = layui.form(),
            device = layui.device();
        //阻止IE7以下访问
        if(device.ie && device.ie < 8){
            layer.alert('Layui最低支持ie8，您当前使用的是古老的 IE'+ device.ie + '，依旧怀旧');
        }
        //手机设备的简单适配
        var treeMobile = $('.site-tree-mobile');
        var shadeMobile = $('.site-mobile-shade');

        treeMobile.on('click', function(){
            $('body').addClass('site-mobile');
        });

        shadeMobile.on('click', function(){
            $('body').removeClass('site-mobile');
        });
    });
});

function loadScript(url) {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = url;
    document.body.appendChild(script);
}

function showUrl(title,url,width,height,type,parentWin,maxmin,ele,shade,scroll,shadeClose,refresh) {
    var content = '',stop = true;
    var myLayer = window.layer ? window.layer : top.window.layer;
    if(!myLayer) {
        myLayer = layer;
    }
    if (parentWin){
        myLayer = top.window.layer ? top.window.layer : window.layer;
    }
    type = type || 1;
    type = parseInt(type);
    if (type != 2){
        $.post(url,{},function(data) {
            content = data;
            stop = false;
        },"html");
    }else {
        scroll = scroll === true ? 'yes' : 'no';
        content = [url, scroll];
        stop = false;
    }
    if (stop){return;}
    width = width || config.width.max;
    height = height || config.height.max;
    maxmin = maxmin !== undefined && maxmin === true && type == 2;
    if (shade === true){
        shade = config.shade;
    }
    shade = shade || (maxmin !== undefined && maxmin === true && type == 2 ? 0 : 0.3);
    shadeClose = shadeClose || false;
    refresh = refresh || false;
    myLayer.open({
        type: type,
        area: [width,height],
        maxmin: maxmin,
        shade: shade,
        shadeClose:shadeClose,
        refresh:refresh,
        id: ele,
        title:'<p style="text-align: center;">'+title+'</p>',
        content: content
    });
}

function msg(content,parentWin) {
    var myLayer = window.layer ? window.layer : top.window.layer;
    if(!myLayer) {
        myLayer = layer;
    }
    if (parentWin){
        myLayer = top.window.layer ? top.window.layer : window.layer;
    }
    myLayer.msg(content, {time: 2000});
}

function wait(content,parentWin) {
    var myLayer = window.layer ? window.layer : top.window.layer;
    if(!myLayer) {
        myLayer = layer;
    }
    if (parentWin){
        myLayer = top.window.layer ? top.window.layer : window.layer;
    }
    if(!content) {
        content = "请稍后....";
    }
    myLayer.msg(content,{shade:0.3,time: 10000});
}

function loading(icon,parentWin) {
    var myLayer = top.window.layer ? top.window.layer : window.layer;
    if (parentWin === false){
        myLayer = window.layer ? window.layer : top.window.layer;
    }
    if(!myLayer) {
        myLayer = layer;
    }
    icon = icon || 1;
    myLayer.load(icon,{type: 3,icon: icon,shade:0.3});
}

function load(url,content,parentWin) {
    var myLayer = window.layer ? window.layer : top.window.layer;
    if(!myLayer) {
        myLayer = layer;
    }
    if (parentWin){
        myLayer = top.window.layer ? top.window.layer : window.layer;
    }
    if(!content) {
        content = "加载中....";
    }
    myLayer.msg(content,{shade:0.3,time: 1000});
    setTimeout(function () {
        window.location.href=url;
    },1000);
}

function loadFrame(title,url,width,height,ele,btn) {
    var content = '';
    var myLayer = window.layer ? window.layer : top.window.layer;
    if(!myLayer) {
        myLayer = layer;
    }
    title = title || '标题';
    content = [url, 'no'];
    width = width || config.width.max;
    height = height || config.height.min;
    btn = btn ===true ? ['全部关闭'] : undefined;

    //多窗口模式，层叠置顶
    myLayer.open({
        type: 2 //此处以iframe举例
        ,title: title
        ,area: [width, height]
        ,shade: 0
        ,shadeClose:true
        ,maxmin: true
        ,id:ele
        ,content: content
        ,btn: btn //只是为了演示
        ,yes: function(){
            layer.closeAll();
        }
        ,zIndex: myLayer.zIndex //重点1
    });
}

function hide() {
    var myLayer = top.window.layer ? top.window.layer : window.layer;
    if(!myLayer) {
        myLayer = layer;
    }
    myLayer.closeAll();
    myLayer = window.layer ? window.layer : top.window.layer;
    myLayer.closeAll();
}

function error(content,parentWin) {
    var myLayer = window.layer ? window.layer : top.window.layer;
    if(!myLayer) {
        myLayer = layer;
    }
    if (parentWin){
        myLayer = top.window.layer ? top.window.layer : window.layer;
    }
    myLayer.msg(content, {icon: 2,time:2000});
}

function success(content,parentWin) {
    var myLayer =  window.layer ? window.layer : top.window.layer;
    if(!myLayer) {
        myLayer = layer;
    }
    if (parentWin){
        myLayer = top.window.layer ? top.window.layer : window.layer;
    }
    myLayer.msg(content, {icon: 1,time:2000});
}

function tip(content,parentWin) {
    var myLayer = top.window.layer ? top.window.layer : window.layer;
    if(!myLayer) {
        myLayer = layer;
    }
    if (parentWin){
        myLayer = top.window.layer ? top.window.layer : window.layer;
    }
    myLayer.msg(content, {icon: 0,time:2000});
}

//确认对话框
function confirm(url,msg,parentWin,width,height,shade) {
    var myLayer = layer;
    if (parentWin){
        myLayer = top.window.layer ? top.window.layer : window.layer;
    }
    width = width || config.width.min;
    height = height || config.height.min;
    if (shade === true){
        shade = config.shade;
    }
    shade = shade || config.shade;
    myLayer.open({
        area: [width,height],
        shade: shade,
        shadeClose:true,
        content:msg,
        yes:function() {
            window.location.href=url;
            return false;
        }
    });
}

function showDialog(title,msg,callBack,parentWin,width,height,shade) {
    var myLayer = layer;
    if (parentWin){
        myLayer = top.window.layer ? top.window.layer : window.layer;
    }
    width = width || config.width.min;
    height = height || config.height.min;
    if (shade === true){
        shade = config.shade;
    }
    shade = shade || config.shade;
    myLayer.open({
        title:title,
        area: [width,height],
        shade: shade,
        shadeClose:true,
        content:msg,
        yes:function(index) {
            myLayer.close(index);
            if(callBack && (typeof callBack ==="function" )) {
                callBack();
            }
            return false;
        }
    });
}

function addTab(ele) {
    var $this = $(ele);
    var event = top;
}

function tab(options,parentWin,width,height,shade) {
    var myLayer = top.window.layer ? top.window.layer : window.layer;
    if (parentWin === false){
        myLayer = window.layer ? window.layer : top.window.layer;
    }
    if(!myLayer) {
        myLayer = layer;
    }

    options = options || [{title: 'TAB1', content: '内容1'}, {title: 'TAB2', content: '内容2'}];

    width = width || config.width.min;
    height = height || config.height.min;
    if (shade === true){
        shade = config.shade;
    }
    shade = shade || config.shade;
    myLayer.tab({
        area: [width, height],
        shade: shade,
        shadeClose:true,
        tab: options
    });
}

function imgLoading(ele) {
    var $this = $(ele);
    if ($this ===undefined || $this.attr('lay-src') === undefined || $this.attr('lay-filter') !== 'loading'){
        return;
    }

    var _width = layui.getStyle(ele,'width'),_height = layui.getStyle(ele,'height');

    var img = '<div style="display: flex;width: '+_width+';height: '+_height+';"><div style="margin: auto;"><img src="/static/images/loading-2.gif"></div></div>';
    $this.html(img);

    var _src = $this.attr('lay-src'),
        _class = $this.attr('lay-class') || '',
        _title = $this.attr('lay-title') || '',
        _alt = $this.attr('lay-alt') || '',
        _error = $this.attr('lay-error') || '/static/images/lockscreenbg.png';
    var attr = '';
    if (_class !== ''){
        attr +=  ' class="'+_class+'"';
    }
    if (_title !== ''){
        attr +=  ' title="'+_title+'"';
    }
    if (_alt !== ''){
        attr +=  ' alt="'+_alt+'"';
    }
    setTimeout(function () {
        layui.img(_src, function () {
            img = '<img src="'+_src+'" '+ attr +'>';
            $this.html(img);
        }, function () {
            img = '<img src="'+_error+'" '+ attr +' style="height: 100%;width: 100%;overflow: hidden;">';
            $this.html(img);
        })
    },800);
}

function photos(type,target,json,parentWin,width,height,shade) {
    var myLayer = top.window.layer ? top.window.layer : window.layer;
    if (parentWin === false){
        myLayer = window.layer ? window.layer : top.window.layer;
    }
    if(!myLayer) {
        myLayer = layer;
    }

    type = type || '1';
    width = width || config.width.min;
    height = height || config.height.min;
    if (shade === true){
        shade = config.shade;
    }
    shade = shade || config.shade;

    if (type == '1'){
        $.ajax({
            type : "post",
            url : target,
            dataType : 'json',
            beforeSend : function() {
                loading();
            },
            success : function(data) {
                /**
                 *  data 返回格式
                         {
                             "title": "", //相册标题
                             "id": 123, //相册id
                             "start": 0, //初始显示的图片序号，默认0
                             "data": [   //相册包含的图片，数组格式
                                 {
                                     "alt": "图片名",
                                     "pid": 666, //图片id
                                     "src": "", //原图地址
                                     "thumb": "" //缩略图地址
                                 }
                             ]
                         }
                 */

                setTimeout(hide(),500);
                myLayer.photos({
                    photos: data,
                    tab: function(pic, layero){
                        console.log(pic) //当前图片的一些信息
                    },
                    anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                });
            },
            error : function(data) {
                hide();
                error('加载失败',true);
            }
        });
    }else {
        /**
         //HTML示例
         <div id="layer-photos-demo" class="layer-photos-demo">
         <img layer-pid="图片id，可以不写" layer-src="大图地址" src="缩略图" alt="图片名">
         <img layer-pid="图片id，可以不写" layer-src="大图地址" src="缩略图" alt="图片名">
         </div>
         */

        myLayer.photos({
            photos: target,
            area: [width, height],
            shade: shade,
            shadeClose:true,
            tab: function(pic, layero){
                console.log(pic) //当前图片的一些信息
            },
            anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
        });

    }
}

function resizeShowTab() {
    if(window.parent) {
        window.parent.$(".layui-show").find("iframe").load();
    }
    else {
        $(".layui-show").find("iframe").load();
    }
}

setTimeout(function () {
    $('[lay-filter="loading"]').each(function () {
        imgLoading(this);
    });
},200);

$.getUrlParam = function (name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
};
