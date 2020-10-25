<?php
/**
 * 项目：Pixiv每日排行榜Top50小部件
 * 作者：超能小紫(mokeyjay)
 * 博客：http://www.mokeyjay.com
 * 源码：http://git.oschina.net/mokeyjay/Pixiv-daily-top50-widget
 * 可随意修改、二次发布。但请保留上方版权声明及注明出处
 */

/**
 * 配置类
 * Class Conf
 */
class Conf
{
    /**
     * 本项目所在url（必须以/结尾）
     * @var string
     */
    public static $url = 'http://localhost/cloud/pixiv/';

    /**
     * 背景颜色。默认值为ffffff（纯白色）。你也可以通过get方式传参来设置
     * @var string
     */
    public static $background_color = 'ffffff';

    /**
     * 显示&缓存的图片数量限制（范围1-50）
     * 例如将此值设为10则可以做出Top10的效果
     * 也可防止部分辣鸡主机在缓存图片时占用过多资源导致卡死或报警
     * 一般情况下默认的50就行
     * （注意看下面 $service 的说明）
     * @var int
     */
    public static $limit = 50;

    /**
     * 是否对外提供服务
     * 为true时，任何人都可通过url的get参数来临时修改
     * $background_color 和 $limit 的值。但不会对本配置文件造成永久性修改
     * 如果 $download 也为true，将强制缓存50张缩略图。不受 $limit 限制
     * 避免出现你设定的limit小于他人请求的limit的情况
     * @var bool
     */
    public static $service = TRUE;

    /**
     * 将缩略图缓存至服务器本地，加快(?)缩略图的加载速度
     * 此值为false时无需继续填写下面的配置项
     * 如果本项目所在路径没有写入权限的话，则此项强制为 false
     * @var bool
     */
    public static $download = TRUE;

    /**
     * 删除过期的（即今天之前的）缓存缩略图
     * @var bool
     */
    public static $clear_overdue = TRUE;

    /**
     * 缩略图缓存路径
     * @var string
     */
    public static $image_path = 'image';

    /**
     * 缩略图缓存url（必须以/结尾）
     * 也就是 本项目所在url + $image_path
     * @var string
     */
    public static $image_url = 'http://localhost/cloud/pixiv/image/';

    /**
     * 初始化
     */
    public static function init()
    {
        // 是否对外提供服务，是则获取url参数
        if (self::$service){
            if (isset($_GET['color'])) self::$background_color = (string)$_GET['color'];
            if (isset($_GET['limit'])) self::$limit = (int)$_GET['limit'];
        }

        // 本项目路径是否可写。不可写则禁止缓存
        if ( !is_writable(PX_PATH)) self::$download = FALSE;

        if (self::$download){
            // 确保图片缓存路径有效
            if (is_writable(self::$image_path)){
                self::$image_path = realpath(self::$image_path);
                self::$image_path .= DIRECTORY_SEPARATOR;
            } else {
                @mkdir(self::$image_path, 0777, TRUE);
                self::$image_path = realpath(self::$image_path);
                if (self::$image_path === FALSE) exit('Conf::$image_path error !');

                self::$image_path .= DIRECTORY_SEPARATOR;
                if ( !is_writable(self::$image_path)) exit('Conf::$image_path not exists or can\'t write');
            }
        }

        if(self::$limit < 1) exit('Conf::$limit can not less than 1');
    }
}