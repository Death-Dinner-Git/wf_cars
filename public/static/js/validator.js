/**

 @Name：自定义validator
 @Author：Dinner

 */

layui.define('layer', function(exports){
    "use strict";

    var $ = layui.jquery,
        layer = parent.layer === undefined ? layui.layer : parent.layer,
		hint = layui.hint(),
		device = layui.device(),
		MOD_NAME = 'validator',
		Form = function(){
            this.rule = {
                verify: {
                    required: [
                        /[\S]+/
                        ,'必填项不能为空'
                    ]
                    ,phone: [
                        /^1\d{10}$/
                        ,'请输入正确的手机号'
                    ]
                    ,email: [
                        /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
                        ,'邮箱格式不正确'
                    ]
                    ,url: [
                        /(^#)|(^http(s*):\/\/[^\s]+\.[^\s]+)/
                        ,'链接格式不正确'
                    ]
                    ,number: [
                        /^\d+$/
                        ,'只能填写数字'
                    ]
                    ,date: [
                        /^(\d{4})[-\/](\d{1}|0\d{1}|1[0-2])([-\/](\d{1}|0\d{1}|[1-2][0-9]|3[0-1]))*$/
                        ,'日期格式不正确'
                    ]
                    ,identity: [
                        /(^\d{15}$)|(^\d{17}(x|X|\d)$)/
                        ,'请输入正确的身份证号'
                    ],
                    //验证数字(double类型) [可以包含负号和小数点]
                    IsNumber: [
                        /^-?\d+$|^(-?\d+)(\.\d+)?$/
                        ,'只能填写数值'

                    ],
                    //验证整数
                    IsInteger:[
                        /^-?\d+$/
                        ,'只能是整数'
                    ],
                    //验证非负整数
                    IsIntegerNotNagtive: [
                        '/^\d+$/',
                        '只能是非负整数'
                    ],
                    //验证正整数
                    IsIntegerPositive: [
                        /^[0-9]*[1-9][0-9]*$/
                        ,'只能是正整数'
                    ],
                    //验证小数
                    IsDecimal: [
                        /^([-+]?[1-9]\d*\.\d+|-?0\.\d*[1-9]\d*)$/
                        ,'需要带有小数'
                    ],
                    //验证只包含英文字母
                    IsEnglishCharacter: [
                        /^[A-Za-z]+$/
                        ,'只包含英文字母'
                    ],
                    //验证只包含数字和英文字母
                    IsIntegerAndEnglishCharacter: [
                        /^[0-9A-Za-z]+$/
                        ,'只包含数字和英文字母'
                    ],
                    //验证只包含汉字
                    IsChineseCharacter: [
                        /^[\u4e00-\u9fa5]+$/
                        ,'只包含汉字'
                    ],
                    //验证固定电话号码 [3位或4位区号；区号可以用小括号括起来；区号可以省略；区号与本地号间可以用减号或空格隔开；可以有3位数的分机号，分机号前要加减号]
                    IsTelePhoneNumber: [
                        /^(((0\d2|0\d{2})[- ]?)?\d{8}|((0\d3|0\d{3})[- ]?)?\d{7})(-\d{3})?$/
                        ,'固定电话号码不合法'
                    ],
                    //验证手机号码 [可匹配"(+86)013325656352"，括号可以省略，+号可以省略，(+86)可以省略，11位手机号前的0可以省略；11位手机号第二位数可以是3、4、5、8中的任意一个]
                    IsMobilePhoneNumber: [
                        /^((\+)?86|((\+)?86)?)0?1[3458]\d{9}$/
                        ,'手机号码不合法'
                    ],
                    //验证电话号码（可以是固定电话号码或手机号码）
                    IsPhoneNumber: [
                        /^((\+)?86|((\+)?86)?)0?1[3458]\d{9}$|^(((0\d2|0\d{2})[- ]?)?\d{8}|((0\d3|0\d{3})[- ]?)?\d{7})(-\d{3})?$/
                        ,'电话号码不合法'
                    ],
                    //验证邮政编码
                    IsZipCode: [
                        /^\d{6}$/
                        ,'验证邮政编码'
                    ],
                    //验证电子邮箱 [@字符前可以包含字母、数字、下划线和点号；@字符后可以包含字母、数字、下划线和点号；@字符后至少包含一个点号且点号不能是最后一个字符；最后一个点号后只能是字母或数字]
                    //邮箱名以数字或字母开头；邮箱名可由字母、数字、点号、减号、下划线组成；邮箱名（@前的字符）长度为3～18个字符；邮箱名不能以点号、减号或下划线结尾；不能出现连续两个或两个以上的点号、减号。
                    //var regex = /^[a-zA-Z0-9]((?<!(\.\.|--))[a-zA-Z0-9\._-]){1,16}[a-zA-Z0-9]@([0-9a-zA-Z][0-9a-zA-Z-]{0,62}\.)+([0-9a-zA-Z][0-9a-zA-Z-]{0,62})\.?|((25[0-5]|2[0-4]\d|[01]?\d\d?)\.){3}(25[0-5]|2[0-4]\d|[01]?\d\d?)$/;
                    IsEmail: [
                        /^([\w\_\-\.]+)@([\w\_\-\.]+)(\.[a-zA-Z0-9]+)$/
                        ,'电子邮箱不合法'
                    ],
                    //验证网址（可以匹配IPv4地址但没对IPv4地址进行格式验证；IPv6暂时没做匹配）[允许省略"://"；可以添加端口号；允许层级；允许传参；域名中至少一个点号且此点号前要有内容]
                    ////每级域名由字母、数字和减号构成（第一个字母不能是减号），不区分大小写，单个域长度不超过63，完整的域名全长不超过256个字符。在DNS系统中，全名是以一个点“.”来结束的，例如“www.nit.edu.cn.”。没有最后的那个点则表示一个相对地址。
                    ////没有例如"http://"的前缀，没有传参的匹配
                    //var regex = /^([0-9a-zA-Z][0-9a-zA-Z-]{0,62}\.)+([0-9a-zA-Z][0-9a-zA-Z-]{0,62})\.?$/;
                    //var regex = /^(((file|gopher|news|nntp|telnet|http|ftp|https|ftps|sftp)://)|(www\.))+(([a-zA-Z0-9\._-]+\.[a-zA-Z]{2,6})|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(/[a-zA-Z0-9\&%_\./-~-]*)?$/;
                    IsURL: [
                        /^([a-zA-Z]+:\/\/)?([\w\_\-\.]+)(\.[a-zA-Z0-9]+)(:\d{0,5})?\/?([\w\_\-\/]*)\.?([a-zA-Z]*)\??(([\w-]*=[\w%]*&?)*)$/
                        ,'网站地址不合法'
                    ],
                    //验证IPv4地址 [第一位和最后一位数字不能是0或255；允许用0补位]
                    IsIPv4: [
                        /^(25[0-4]|2[0-4]\d]|[01]?\d{2}|[1-9])\.(25[0-5]|2[0-4]\d]|[01]?\d?\d)\.(25[0-5]|2[0-4]\d]|[01]?\d?\d)\.(25[0-4]|2[0-4]\d]|[01]?\d{2}|[1-9])$/
                        ,'TPv4不合法'
                    ],
                    //验证IPv6地址 [可用于匹配任何一个合法的IPv6地址]
                    IsIPv6: [
                        /^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/
                        ,'IPv6不合法'
                    ],
                    //验证经度
                    IsLongitude: [
                        /^[-\+]?((1[0-7]\d{1}|0?\d{1,2})\.\d{1,5}|180\.0{1,5})$/
                        ,'经度不合法'
                    ],
                    //验证纬度
                    IsLatitude: [
                        /^[-\+]?([0-8]?\d{1}\.\d{1,5}|90\.0{1,5})$/
                        ,'纬度不合法'
                    ],
                    //验证日期 [只能验证日期，不能验证时间]
                    IsDateTime: function(input,item) {
                        var regex = /((19|20)[0-9]{2})-(0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01])/;
                        var message = '日期不符合';
                        if(regex.test(input)) {
                            var ymd = input.match(/(\d{4})-(\d+)-(\d+).*/);
                            var year = parseInt(ymd[1]);
                            var month = parseInt(ymd[2]);
                            var day = parseInt(ymd[3]);
                            if(day > 28) {
                                //获取当月的最后一天
                                var lastDay = new Date(year, month, 0).getDate();
                                return message;
                            }
                            return;
                        } else {
                            return message;
                        }
                    },
                    //验证数字长度范围（数字前端的0计长度）[若要验证固定长度，可传入相同的两个长度数值]
                    IsIntegerLength: function(input, item) {
                        var message = "数值超出规定范围";
                        var lengthBegin = $(item).attr('lay-begin');
                        var lengthEnd = $(item).attr('lay-end');
                        if (!lengthBegin || !lengthEnd){
                            return '配置错';
                        }
                        var pattern = '^\\d{' + lengthBegin + ',' + lengthEnd + '}$';
                        var regex = new RegExp(pattern);
                        if(input.match(regex)) {
                            return;
                        } else {
                            return message;
                        }
                    },
                    //验证字符串包含内容
                    IsStringInclude: function(input, item) {
                        var message = '包含无效字符';
                        var withEnglishCharacter = false;
                        var withNumber = false;
                        var withChineseCharacter = false ;
                        var format = $(item).attr('lay-format').split('|');
                        for (var i=0;i<format.length;i++){
                            if (format[i] === 'English'){
                                withEnglishCharacter = true;
                            }else if (format[i] === 'Number'){
                                withNumber = true;
                            }else if (format[i] === 'Chinese'){
                                withChineseCharacter =true;
                            }
                        }
                        if(!Boolean(withEnglishCharacter) && !Boolean(withNumber) && !Boolean(withChineseCharacter)) {
                            return message; //如果英文字母、数字和汉字都没有，则返回false
                        }
                        var pattern = '^[';
                        if(Boolean(withEnglishCharacter)) {
                            pattern += 'a-zA-Z';
                        }
                        if(Boolean(withNumber)) {
                            pattern += '0-9';
                        }
                        if(Boolean(withChineseCharacter)) {
                            pattern += '\\u4E00-\\u9FA5';
                        }
                        pattern += ']+$';
                        var regex = new RegExp(pattern);
                        if(input.match(regex)) {
                            return;
                        } else {
                            return message;
                        }
                    },
                    //验证字符串长度范围 [若要验证固定长度，可传入相同的两个长度数值]
                    IsStringLength: function(input, item, LengthBegin, LengthEnd) {
                        var message = '字符串超出规定范围';
                        var pattern = '^.{' + lengthBegin + ',' + lengthEnd + '}$';
                        var regex = new RegExp(pattern);
                        if(input.match(regex)) {
                            return;
                        } else {
                            return message;
                        }
                    },
                    //验证字符串长度范围（字符串内只包含数字和/或英文字母）[若要验证固定长度，可传入相同的两个长度数值]
                    IsStringLengthOnlyNumberAndEnglishCharacter: function(input, item, LengthBegin, LengthEnd) {
                        var message = '字符串超出规定范围';
                        var pattern = '^[0-9a-zA-z]{' + lengthBegin + ',' + lengthEnd + '}$';
                        var regex = new RegExp(pattern);
                        if(input.match(regex)) {
                            return;
                        } else {
                            return message;
                        }
                    },
                    //验证字符串长度范围 [若要验证固定长度，可传入相同的两个长度数值]
                    IsStringLengthByInclude: function(input, item, withEnglishCharacter, withNumber, withChineseCharacter, lengthBegin, lengthEnd) {
                        var message = '字符串超出规定范围';
                        if(!withEnglishCharacter && !withNumber && !withChineseCharacter) {
                            return '无效的字符串'; //如果英文字母、数字和汉字都没有，则返回false
                        }
                        var pattern = '^[';
                        if(Boolean(withEnglishCharacter))
                            pattern += 'a-zA-Z';
                        if(Boolean(withNumber))
                            pattern += '0-9';
                        if(Boolean(withChineseCharacter))
                            pattern += '\\u4E00-\\u9FA5';
                        pattern += ']{' + lengthBegin + ',' + lengthEnd + '}$';
                        var regex = new RegExp(pattern);
                        if(input.match(regex)) {
                            return;
                        } else {
                            return message;
                        }
                    },
                    //验证字符串字节数长度范围 [若要验证固定长度，可传入相同的两个长度数值；每个汉字为两个字节长度]
                    IsStringByteLength: function(input, item, lengthBegin, lengthEnd) {
                        var message = '字符串字节数超出规定范围';
                        var regex = /[^\x00-\xff]/g;
                        var byteLength = input.replace(regex, 'ok').length;
                        if(byteLength >= lengthBegin && byteLength <= lengthEnd) {
                            return;
                        } else {
                            return message;
                        }
                    },
                    //验证身份证号 [可验证一代或二代身份证]
                    IsIDCard: function(input, item) {
                        var message = '无效身份证号码';
                        input = input.toUpperCase();
                        //验证身份证号码格式 [一代身份证号码为15位的数字；二代身份证号码为18位的数字或17位的数字加字母X]
                        if(!(/(^\d{15}$)|(^\d{17}([0-9]|X)$)/i.test(input))) {
                            return message;
                        }
                        //验证省份
                        var arrCity = {
                            11: '北京',
                            12: '天津',
                            13: '河北',
                            14: '山西',
                            15: '内蒙古',
                            21: '辽宁',
                            22: '吉林',
                            23: '黑龙江 ',
                            31: '上海',
                            32: '江苏',
                            33: '浙江',
                            34: '安徽',
                            35: '福建',
                            36: '江西',
                            37: '山东',
                            41: '河南',
                            42: '湖北',
                            43: '湖南',
                            44: '广东',
                            45: '广西',
                            46: '海南',
                            50: '重庆',
                            51: '四川',
                            52: '贵州',
                            53: '云南',
                            54: '西藏',
                            61: '陕西',
                            62: '甘肃',
                            63: '青海',
                            64: '宁夏',
                            65: '新疆',
                            71: '台湾',
                            81: '香港',
                            82: '澳门',
                            91: '国外'
                        };
                        if(arrCity[parseInt(input.substr(0, 2))] == null) {
                            return message;
                        }
                        //验证出生日期
                        var regBirth, birthSplit, birth;
                        var len = input.length;
                        if(len == 15) {
                            regBirth = new RegExp(/^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/);
                            birthSplit = input.match(regBirth);
                            birth = new Date('19' + birthSplit[2] + '/' + birthSplit[3] + '/' + birthSplit[4]);
                            if(!(birth.getYear() == Number(birthSplit[2]) && (birth.getMonth() + 1) == Number(birthSplit[3]) && birth.getDate() == Number(birthSplit[4]))) {
                                return message;
                            }
                            return;
                        } else if(len == 18) {
                            regBirth = new RegExp(/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/i);
                            birthSplit = input.match(regBirth);
                            birth = new Date(birthSplit[2] + '/' + birthSplit[3] + '/' + birthSplit[4]);
                            if(!(birth.getFullYear() == Number(birthSplit[2]) && (birth.getMonth() + 1) == Number(birthSplit[3]) && birth.getDate() == Number(birthSplit[4]))) {
                                return message;
                            }
                            //验证校验码
                            var valnum;
                            var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                            var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                            var nTemp = 0,
                                i;
                            for(i = 0; i < 17; i++) {
                                nTemp += input.substr(i, 1) * arrInt[i];
                            }
                            valnum = arrCh[nTemp % 11];
                            if(valnum != input.substr(17, 1)) {
                                return message;
                            }
                            return;
                        }
                        return message;
                    }
                }
            }
        };

    // NOTE: If you change any of these defaults
    var defaults = {
        ELEM:'.layui-form',
        THIS:'layui-this',
        SHOW:'layui-show',
        HIDE:'layui-hide',
        DISABLED:'layui-disabled',
        // 获取需要校验的元素
        layVerify: '*[lay-verify]',
        // 获取过滤器
        layFilter: 'lay-filter',
        // whether to encode the error summary
        encodeErrorSummary: true,
        // the jQuery selector for the error summary
        errorSummary: '.error-summary',
        // whether to perform validation before submitting the form.
        validateOnSubmit: true,
        // the container CSS class representing the corresponding attribute has validation error
        errorCssClass: 'has-error',
        // the container CSS class representing the corresponding attribute passes validation
        successCssClass: 'has-success',
        // the container CSS class representing the corresponding attribute is being validated
        validatingCssClass: 'validating',
        // the GET parameter name indicating an AJAX-based validation
        ajaxParam: 'ajax',
        // the type of data that you're expecting back from the server
        ajaxDataType: 'json',
        // the URL for performing AJAX-based validation. If not set, it will use the the form's action
        validationUrl: undefined,
        // whether to scroll to first visible error after validation.
        scrollToError: true,
    };

    var submitDefer;

    /**
	 * 全局设置
     * @param options
     * @returns {Form}
     */
    Form.prototype.setRule = function(options){
        var that = this;
        $.extend(true, that.rule, options);
        return that;
    };

    /**
	 * 表单验证配置
     * @param _config
     * @returns {Form}
     */
    Form.prototype.set = function(_config){
        var that = this;
        $.extend(true, defaults, _config);
        return that;
    };

    /**
	 * 验证规则设定
     * @param settings
     * @returns {Form}
     */
    Form.prototype.verify = function(settings){
        var that = this;
        $.extend(true, that.rule.verify, settings);
        return that;
    };

    /**
	 * 表单事件监听
     * @param events
     * @param callback
     */
    Form.prototype.on = function(events, callback){
        return layui.onevent(MOD_NAME, events, callback);
    };

    /**
	 * 表单控件渲染
     * @param type
     * @returns {Form}
     */
    Form.prototype.render = function(type){
        var that = this,
			items = {

            //下拉选择框
            select: function(){
                var TIPS = '请选择', CLASS = 'layui-form-select', TITLE = 'layui-select-title'
                    ,NONE = 'layui-select-none', initValue = '', thatInput

                    ,selects = $(defaults.ELEM).find('select'), hide = function(e, clear){
                    if(!$(e.target).parent().hasClass(TITLE) || clear){
                        $('.'+CLASS).removeClass(CLASS+'ed');
                        thatInput && initValue && thatInput.val(initValue);
                    }
                    thatInput = null;
                }

                    ,events = function(reElem, disabled, isSearch){
                    var select = $(this)
                        ,title = reElem.find('.' + TITLE)
                        ,input = title.find('input')
                        ,dl = reElem.find('dl')
                        ,dds = dl.children('dd');


                    if(disabled) return;

                    //展开下拉
                    var showDown = function(){
                        reElem.addClass(CLASS+'ed');
                        dds.removeClass(defaults.HIDE);
                    }, hideDown = function(){
                        reElem.removeClass(CLASS+'ed');
                        input.blur();

                        notOption(input.val(), function(none){
                            if(none){
                                initValue = dl.find('.'+defaults.THIS).html();
                                input && input.val(initValue);
                            }
                        });
                    };

                    //点击标题区域
                    title.on('click', function(e){
                        reElem.hasClass(CLASS+'ed') ? (
                                hideDown()
                            ) : (
                                hide(e, true),
                                    showDown()
                            );
                        dl.find('.'+NONE).remove();
                    });

                    //点击箭头获取焦点
                    title.find('.layui-edge').on('click', function(){
                        input.focus();
                    });

                    //键盘事件
                    input.on('keyup', function(e){
                        var keyCode = e.keyCode;
                        //Tab键
                        if(keyCode === 9){
                            showDown();
                        }
                    }).on('keydown', function(e){
                        var keyCode = e.keyCode;
                        //Tab键
                        if(keyCode === 9){
                            hideDown();
                        } else if(keyCode === 13){ //回车键
                            e.preventDefault();
                        }
                    });

                    //检测值是否不属于select项
                    var notOption = function(value, callback, origin){
                        var num = 0;
                        layui.each(dds, function(){
                            var _this = $(this)
                                ,text = _this.text()
                                ,not = text.indexOf(value) === -1;
                            if(value === '' || (origin === 'blur') ? value !== text : not) num++;
                            origin === 'keyup' && _this[not ? 'addClass' : 'removeClass'](defaults.HIDE);
                        });
                        var none = num === dds.length;
                        return callback(none), none;
                    };

                    //搜索匹配
                    var search = function(e){
                        var value = this.value, keyCode = e.keyCode;

                        if(keyCode === 9 || keyCode === 13
                            || keyCode === 37 || keyCode === 38
                            || keyCode === 39 || keyCode === 40
                        ){
                            return false;
                        }

                        notOption(value, function(none){
                            if(none){
                                dl.find('.'+NONE)[0] || dl.append('<p class="'+ NONE +'">无匹配项</p>');
                            } else {
                                dl.find('.'+NONE).remove();
                            }
                        }, 'keyup');

                        if(value === ''){
                            dl.find('.'+NONE).remove();
                        }
                    };
                    if(isSearch){
                        input.on('keyup', search).on('blur', function(e){
                            thatInput = input;
                            initValue = dl.find('.'+defaults.THIS).html();
                            setTimeout(function(){
                                notOption(input.val(), function(none){
                                    if(none && !initValue){
                                        input.val('');
                                    }
                                }, 'blur');
                            }, 200);
                        });
                    }

                    //选择
                    dds.on('click', function(){
                        var _this = $(this), value = _this.attr('lay-value');
                        var filter = select.attr('lay-filter'); //获取过滤器

                        if(_this.hasClass(defaults.DISABLED)) return false;

                        select.val(value).removeClass('layui-form-danger'), input.val(_this.text());
                        _this.addClass(defaults.THIS).siblings().removeClass(defaults.THIS);
                        layui.event.call(this, MOD_NAME, 'select('+ filter +')', {
                            elem: select[0]
                            ,value: value
                            ,_this: reElem
                        });

                        hideDown();

                        return false;
                    });

                    reElem.find('dl>dt').on('click', function(e){
                        return false;
                    });

                    //关闭下拉
                    $(document).off('click', hide).on('click', hide);
                }

                selects.each(function(index, select){
                    var _this = $(this), hasRender = _this.next('.'+CLASS), disabled = this.disabled;
                    var value = select.value, selected = $(select.options[select.selectedIndex]); //获取当前选中项

                    if(typeof _this.attr('lay-ignore') === 'string') return _this.show();

                    var isSearch = typeof _this.attr('lay-search') === 'string';

                    //替代元素
                    var reElem = $(['<div class="layui-unselect '+ CLASS + (disabled ? ' layui-select-disabled' : '') +'">'
                        ,'<div class="'+ TITLE +'"><input type="text" placeholder="'+ (select.options[0].innerHTML ? select.options[0].innerHTML : TIPS) +'" value="'+ (value ? selected.html() : '') +'" '+ (isSearch ? '' : 'readonly') +' class="layui-input layui-unselect'+ (disabled ? (' '+defaults.DISABLED) : '') +'">'
                        ,'<i class="layui-edge"></i></div>'
                        ,'<dl class="layui-anim layui-anim-upbit'+ (_this.find('optgroup')[0] ? ' layui-select-group' : '') +'">'+ function(options){
                            var arr = [];
                            layui.each(options, function(index, item){
                                if(index === 0 && !item.value) return;
                                if(item.tagName.toLowerCase() === 'optgroup'){
                                    arr.push('<dt>'+ item.label +'</dt>');
                                } else {
                                    arr.push('<dd lay-value="'+ item.value +'" class="'+ (value === item.value ?  defaults.THIS : '') + (item.disabled ? (' '+defaults.DISABLED) : '') +'">'+ item.innerHTML +'</dd>');
                                }
                            });
                            return arr.join('');
                        }(_this.find('*')) +'</dl>'
                        ,'</div>'].join(''));

                    hasRender[0] && hasRender.remove(); //如果已经渲染，则Rerender
                    _this.after(reElem);
                    events.call(this, reElem, disabled, isSearch);
                });
            }
            //复选框/开关
            ,checkbox: function(){
                var CLASS = {
                    checkbox: ['layui-form-checkbox', 'layui-form-checked', 'checkbox']
                    ,_switch: ['layui-form-switch', 'layui-form-onswitch', 'switch']
                }
                    ,checks = $(defaults.ELEM).find('input[type=checkbox]')

                    ,events = function(reElem, RE_CLASS){
                    var check = $(this);

                    //勾选
                    reElem.on('click', function(){
                        var filter = check.attr('lay-filter') //获取过滤器
                            ,text = (check.attr('lay-text')||'').split('|');

                        if(check[0].disabled) return;

                        check[0].checked ? (
                                check[0].checked = false
                                    ,reElem.removeClass(RE_CLASS[1]).find('em').text(text[1])
                            ) : (
                                check[0].checked = true
                                    ,reElem.addClass(RE_CLASS[1]).find('em').text(text[0])
                            );

                        layui.event.call(check[0], MOD_NAME, RE_CLASS[2]+'('+ filter +')', {
                            elem: check[0]
                            ,value: check[0].value
                            ,_this: reElem
                        });
                    });
                }

                checks.each(function(index, check){
                    var _this = $(this), skin = _this.attr('lay-skin')
                        ,text = (_this.attr('lay-text')||'').split('|'), disabled = this.disabled;
                    if(skin === 'switch') skin = '_'+skin;
                    var RE_CLASS = CLASS[skin] || CLASS.checkbox;

                    if(typeof _this.attr('lay-ignore') === 'string') return _this.show();

                    //替代元素
                    var hasRender = _this.next('.' + RE_CLASS[0]);
                    var reElem = $(['<div class="layui-unselect '+ RE_CLASS[0] + (
                        check.checked ? (' '+RE_CLASS[1]) : '') + (disabled ? ' layui-checkbox-disbaled '+defaults.DISABLED : '') +'" lay-skin="'+ (skin||'') +'">'
                        ,{
                            _switch: '<em>'+ ((check.checked ? text[0] : text[1])||'') +'</em><i></i>'
                        }[skin] || ((check.title.replace(/\s/g, '') ? ('<span>'+ check.title +'</span>') : '') +'<i class="layui-icon">'+ (skin ? '&#xe605;' : '&#xe618;') +'</i>')
                        ,'</div>'].join(''));

                    hasRender[0] && hasRender.remove(); //如果已经渲染，则Rerender
                    _this.after(reElem);
                    events.call(this, reElem, RE_CLASS);
                });
            }
            //单选框
            ,radio: function(){
                var CLASS = 'layui-form-radio', ICON = ['&#xe643;', '&#xe63f;']
                    ,radios = $(defaults.ELEM).find('input[type=radio]')

                    ,events = function(reElem){
                    var radio = $(this), ANIM = 'layui-anim-scaleSpring';

                    reElem.on('click', function(){
                        var name = radio[0].name, forms = radio.parents(defaults.ELEM);
                        var filter = radio.attr('lay-filter'); //获取过滤器
                        var sameRadio = forms.find('input[name='+ name.replace(/(\.|#|\[|\])/g, '\\$1') +']'); //找到相同name的兄弟

                        if(radio[0].disabled) return;

                        layui.each(sameRadio, function(){
                            var next = $(this).next('.'+CLASS);
                            this.checked = false;
                            next.removeClass(CLASS+'ed');
                            next.find('.layui-icon').removeClass(ANIM).html(ICON[1]);
                        });

                        radio[0].checked = true;
                        reElem.addClass(CLASS+'ed');
                        reElem.find('.layui-icon').addClass(ANIM).html(ICON[0]);

                        layui.event.call(radio[0], MOD_NAME, 'radio('+ filter +')', {
                            elem: radio[0]
                            ,value: radio[0].value
                            ,_this: reElem
                        });
                    });
                };

                radios.each(function(index, radio){
                    var _this = $(this), hasRender = _this.next('.' + CLASS), disabled = this.disabled;

                    if(typeof _this.attr('lay-ignore') === 'string') return _this.show();

                    //替代元素
                    var reElem = $(['<div class="layui-unselect '+ CLASS + (radio.checked ? (' '+CLASS+'ed') : '') + (disabled ? ' layui-radio-disbaled '+defaults.DISABLED : '') +'">'
                        ,'<i class="layui-anim layui-icon">'+ ICON[radio.checked ? 0 : 1] +'</i>'
                        ,'<span>'+ (radio.title||'未命名') +'</span>'
                        ,'</div>'].join(''));

                    hasRender[0] && hasRender.remove(); //如果已经渲染，则Rerender
                    _this.after(reElem);
                    events.call(this, reElem);
                });
            }
        };
        type ? (
                items[type] ? items[type]() : hint.error('不支持的'+ type + '表单渲染')
            ) : layui.each(items, function(index, item){
                item();
            });
        return that;
    };

    //表单提交校验
    var submit = function(){
        var button = $(this), verify = form.rule.verify, stop = null
            ,DANGER = 'layui-form-danger', field = {} ,elem = button.parents(defaults.ELEM)

            ,verifyElem = elem.find(defaults.layVerify) //获取需要校验的元素
            ,formElem = $(defaults.ELEM)[0] || button.parents('form')[0] //获取当前所在的form元素，如果存在的话
            ,fieldElem = elem.find('input,select,textarea') //获取所有表单域
            ,filter = button.attr(defaults.layFilter); //获取过滤器

        //开始校验
        layui.each(verifyElem, function(_, item){
            var _this = $(this), ver = _this.attr('lay-verify').split('|');
            var tips = '', value = _this.val();
            _this.removeClass(DANGER);
            layui.each(ver, function(_, thisVer){
                var isFn = typeof verify[thisVer] === 'function';
                if(verify[thisVer] && (isFn ? (typeof (tips = verify[thisVer](value, item)) === 'string') : !(tips = verify[thisVer][0].test(value))) ){
                    tips = tips || verify[thisVer][1] || '未定义提示信息';
                    layer.msg(tips, {
                        icon: 5
                        ,shift: 6
                    });
                    //非移动设备自动定位焦点
                    if(!device.android && !device.ios){
                        item.focus();
                    }
                    _this.addClass(DANGER);
                    return stop = true;
                }
            });
            if(stop) {return stop}
        });

        if(stop) {return false}

        layui.each(fieldElem, function(_, item){
            if(!item.name){ return;}
            if(/^checkbox|radio$/.test(item.type) && !item.checked) { return;}
            field[item.name] = item.value;
        });

        //获取字段
        return layui.event.call(this, MOD_NAME, 'submit('+ filter +')', {
            elem: this
            ,form: formElem
            ,field: field
        });
    };

    //已经绑定事件时跳过，避免事件重复绑定;

    var onChange = function () {

    };

    var onBlur = function () {

    };

    //自动完成渲染
    var form = new Form(), dom = $(document);
    form.render();

    //表单reset重置渲染
    dom.on('reset', defaults.ELEM, function(){
        setTimeout(function(){
            form.render();
        }, 50);
    });

    /**
     * 初始化
     */
    Form.prototype.init = function(_config) {

    };

    //防止表单按钮双击提交两次;
    //表单提交事件
    // dom.on('submit', defaults.ELEM, submit).on('click', '*[lay-submit]', submit);
    dom.on('submit', defaults.ELEM, submit);

    exports(MOD_NAME, function(options){
        return form.set(options);
    });
});