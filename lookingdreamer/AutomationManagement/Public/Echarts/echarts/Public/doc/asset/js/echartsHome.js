var version = '2.0.2';
var curPage = location.href.match(/(\w*).html/) ? location.href.match(/(\w*).html/)[1] : 'index';
var activeClass = {};
var loc = {};
var forkWidth = 149;
var base_local = '/doc/';
var base_concat = echart_base_url.concat(base_local);
switch (curPage) {
    case 'index' :
        activeClass[curPage] = 'active';
        loc[curPage] = echart_base_url;
        loc.feature = base_concat;
        loc.example = base_concat;
        loc.doc = base_concat;
        loc.about = base_concat;
        loc.changelog = base_concat;
        loc.start = base_concat;
        loc.img = base_concat;
        break;
    case 'feature' :
    case 'example' :
    case 'doc' :
    case 'about' :
    case 'changelog' :
    case 'start' :
        activeClass[curPage] = 'active';
        loc.index = '..';
        break;
    default :
        forkWidth = 60;
        activeClass['example'] = 'active';
        loc.index = echart_base_url;
        loc.feature = base_concat;
        loc.example = base_concat;
        loc.doc = base_concat;
        loc.about = base_concat;
        loc.changelog = base_concat;
        loc.start = base_concat;
        loc.img = base_concat;
        break;
}

$('#head')[0].innerHTML =
    '<div class="container">'
        + '<div class="navbar-header">'
        + '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">'
        + '<span class="sr-only">Toggle navigation</span>'
        + '<span class="icon-bar"></span>'
        + '<span class="icon-bar"></span>'
        + '<span class="icon-bar"></span>'
        + '</button>'
        + '<a class="navbar-brand" href="http://echarts.baidu.com">ECharts</a>'
        + '</div>'
        + '<div class="navbar-collapse collapse" id="nav-wrap">'
        + '<ul class="nav navbar-nav navbar-right" id="nav" style="max-width:100%;display:none">'
        + '<li class="' + (activeClass.index || '') + '"><a href="' + (loc.index || '.') + '/index.html">首页</a></li>'
        + '<li class="' + (activeClass.feature || '') + '"><a href="' + (loc.feature || '.') + '/feature.html">特性</a></li>'
        + '<li class="' + (activeClass.example || '') + '"><a href="' + (loc.example || '.') + '/example.html">系统连接</a></li>'
        + '<li class="' + (activeClass.doc || '') + '"><a href="' + (loc.doc || '.') + '/doc.html">应用健康</a></li>'
        /*
         + '<li class="dropdown">'
         + '<a href="#" class="dropdown-toggle" data-toggle="dropdown">教学 <b class="caret"></b></a>'
         + '<ul class="dropdown-menu">'
         + '<li><a href="#">初学教程</a></li>'
         + '<li class="divider"></li>'
         + '<li class="dropdown-header">外部资源</li>'
         + '<li><a href="#"></a></li>'
         + '<li><a href="#"></a></li>'
         + '</ul>'
         + '</li>'
         */
        + '<li class="dropdown">'
        + '<a href="#" class="dropdown-toggle" data-toggle="dropdown"　id="choice" >业务数据<b class="caret"></b></a>'
        + '</li>'
        //+ '<li><a href="http://echarts.baidu.com/build/echarts-' + version + '.rar">下载</a></li>'
        + '<li class="' + (activeClass.about || '') + '"><a href="' + (loc.about || '.') + '/about.html">关于我们</a></li>'
        + '</ul>'
        + '</div><!--/.nav-collapse -->'
        + '</div>';

function back2Top() {
    $("body,html").animate({scrollTop: 0}, 1000);
    return false;
}
$('#footer')[0].style.marginTop = '50px';
$('#footer')[0].innerHTML =
    '<div class="container">'
        + '<p class="pull-right"><a href="javascript:void(0)" onclick="back2Top()" >Back to top</a></p>'
        + '<p>&copy; 2014-09-05 <a href="http://www.baoxian.com/" target="_blank">保网运维部</a></p>'
        + '</div>';


if (document.location.href.indexOf('local') == -1) {
    var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
    document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fb78830c9a5dad062d08b90b2bc0cf5da' type='text/javascript'%3E%3C/script%3E"));
}

function fixFork() {
    var navMarginRight = 0;
    var bodyWidth = document.body.offsetWidth;
    var contnetWidth = $('#nav-wrap')[0].offsetWidth;
    if (bodyWidth < 1440) {
        navMarginRight = 150 - (bodyWidth - contnetWidth) / 2;
    }
    $('#nav')[0].style.marginRight = navMarginRight + 'px';
    //$('#fork-image')[0].style.width = (document.body.offsetWidth < 768 ? 1 : forkWidth) + 'px';
};
fixFork();
$(window).on('resize', fixFork);