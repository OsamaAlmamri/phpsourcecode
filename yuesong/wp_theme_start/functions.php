<?php 
// 主题初始化
function ets_setup(){
	/*
	 * 添加主题多语言功能
	 */
	load_theme_textdomain( 'ets', get_template_directory() . '/lang' );

	/*
	 * head feed链接
	 */
	//add_theme_support( 'automatic-feed-links' );

	/*
	 * 支持特色图像
	 */
	add_theme_support( 'post-thumbnails' );

	/*
	 * 注册多条菜单
	 */
	register_nav_menus( array(
		'main_menu' => __( 'main menu', 'est' ),
		'right_menu' => __( 'right menu', 'est' ),
		'footer_menu' => __('footer menu','est')
	) );

	/*
	 * 注册小工具位置
	 */
	register_sidebar( array(
		'name'          => __( 'Home Sidebar', 'ets' ),
		'id'            => 'home_sidebar',
		'description'   => __('display sidebar in home page','ets'),
		'class'         => '',
		'before_widget' => '<div class="panel panel-default sidebar">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="panel-heading"><strong>',
		'after_title'   => '</strong></div>'
	) );
	




	/*
	 * 注册一条菜单
	 */
	register_nav_menu( 'test_menu', __('test menu','est') );

	/*
	 * 启用html5
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * 启用文章格式
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( '_s_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
add_action( 'after_setup_theme', 'ets_setup' );


function some_script(){
	echo "<script>
		$('.sidebar ul').addClass('list-group');
		$('.sidebar ul li').addClass('list-group-item');
	</script>";
}
add_action('wp_footer','some_script');

/*
 *修改默认设置
 */
require get_template_directory() . '/inc/settings.php';

/*
 *定义函数
 */
require get_template_directory() . '/inc/function.php';

/*
 *自定义内容类型
 */
require get_template_directory() . '/inc/post_type.php';

/*
 *自定义小工具
 */
require get_template_directory() . '/inc/widgets.php';

/*
 *主题选项
 */
require get_template_directory() . '/inc/options.php';

/*
 *集成插件
 */
require get_template_directory() . '/inc/plugin.php';