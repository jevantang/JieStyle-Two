<?php
/*
Theme Name: JieStyle Two
Theme URI: https://tangjie.me/jiestyle-two
Author: Jarvis Tang
Author URI: https://tangjie.me/
Description: A responsible theme for WordPress.
Version: v2.6.1
License: GNU General Public License v3.0
*/
function tangstyle_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'tangstyle_page_menu_args' );

if ( ! function_exists( 'tangstyle_content_nav' ) ) :
register_nav_menus(array('header-menu' => __( 'JieStyle导航菜单' ),));

//禁用修订版本
add_filter( 'wp_revisions_to_keep', 'specs_wp_revisions_to_keep', 10, 2 );
function specs_wp_revisions_to_keep( $post ) {
    return 0;
}

//去除头部无用代码
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'locale_stylesheet' );
remove_action( 'wp_head', 'noindex', 1 );
remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'wp_head', 'wp_oembed_add_host_js');
remove_action( 'wp_head', 'wp_resource_hints', 2 );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
remove_action( 'wp_footer', 'wp_print_footer_scripts' );
remove_action( 'publish_future_post', 'check_and_publish_future_post', 10, 1 );
remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
remove_action( 'rest_api_init', 'wp_oembed_register_route');
remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10);
remove_filter( 'oembed_response_data', 'get_oembed_response_data_rich', 10, 4);
add_filter('rest_enabled', '__return_false');
add_filter('rest_jsonp_enabled', '__return_false');

//禁用 Emoji 表情选项
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );
function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

//移除菜单的多余 CSS 选择器
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
    return is_array($var) ? array() : '';
}

//替换 Gravatar 服务器
function replace_gravatar($avatar) {
    $avatar = str_replace(array("//gravatar.com/", "//secure.gravatar.com/", "//www.gravatar.com/", "//0.gravatar.com/", "//1.gravatar.com/", "//2.gravatar.com/", "//cn.gravatar.com/"), "//sdn.geekzu.org/", $avatar);
    return $avatar;
}
add_filter( 'get_avatar', 'replace_gravatar' );

//启动友情链接
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//获得热评文章
function tangstyle_get_most_viewed($posts_num=10, $days=180){
    global $wpdb;
    $sql = "SELECT ID , post_title , comment_count FROM $wpdb->posts WHERE post_type = 'post' AND TO_DAYS(now()) - TO_DAYS(post_date) < $days AND ($wpdb->posts.`post_status` = 'publish' OR $wpdb->posts.`post_status` = 'inherit') ORDER BY comment_count DESC LIMIT 0 , $posts_num ";
    $posts = $wpdb->get_results($sql);
    $output = "";
    foreach ($posts as $post){
        $output .= "\n<li><a href=\"".get_permalink($post->ID)."\" target=\"_blank\" >".$post->post_title."</a></li>";
    }
    echo $output;
}

//彩色标签云
function colorCloud($text) {
    $text = preg_replace_callback('|<a (.+?)>|i', 'colorCloudCallback', $text);
    return $text;
}
function colorCloudCallback($matches) {
    $text = $matches[1];
    $color = dechex(rand(0,16777215));
    $pattern = '/style=(\'|\")(.*)(\'|\")/i';
    $text = preg_replace($pattern, "style=\"color:#{$color};$2;\"", $text);
    return "<a $text>";
}
add_filter('wp_tag_cloud', 'colorCloud', 1);

//新窗口打开评论里的链接
function remove_comment_links() {
    global $comment;
    $url = get_comment_author_url();
    $author = get_comment_author();
    if ( empty( $url ) || 'http://' == $url )
        $return = $author;
    else
        $return = "<a href='$url' rel='nofollow' target='_blank'>$author</a>";
        //带跳转路径，必须根目录有 go.php 解析文件
        //$return = "<a href='/go.php?url=$url' rel='nofollow' target='_blank'>$author</a>";
    return $return;
}
add_filter('get_comment_author_link', 'remove_comment_links');
remove_filter('comment_text', 'make_clickable', 9);

//分页
function pagination($query_string){
    global $posts_per_page, $paged;
    $my_query = new WP_Query($query_string ."&posts_per_page=-1");
    $total_posts = $my_query->post_count;
    if(empty($paged))$paged = 1;
    $prev = $paged - 1;
    $next = $paged + 1;
    $range = 5;  //分页数设置
    $showitems = ($range * 2)+1;
    $pages = ceil($total_posts/$posts_per_page);
    if(1 != $pages){
        echo "<ul class='pagination'>";
        echo ($paged > 2 && $paged+$range+1 > $pages && $showitems < $pages)? "<li><a href='".get_pagenum_link(1)."'><i class='fas fa-angle-double-left'></i></a></li>":"";
        echo ($paged > 1 && $showitems < $pages)? "<li><a href='".get_pagenum_link($prev)."'><i class='fas fa-angle-left'></i></a></li>":"";
        for ($i=1; $i <= $pages; $i++){
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
                echo ($paged == $i)? "<li class='active'><a href='".get_pagenum_link($i)."'>".$i."<span class='sr-only'>(current)</span></a></li>":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>";
            }
        }
    echo ($paged < $pages && $showitems < $pages) ? "<li><a href='".get_pagenum_link($next)."'><i class='fas fa-angle-right'></i></a></li>" :"";
    echo ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) ? "<li><a href='".get_pagenum_link($pages)."'><i class='fas fa-angle-double-right'></i></a></li>":"";
    echo "</ul>";
    }
}

//评论模板
function tangstyle_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
    case 'pingback' :
    case 'trackback' :
?>
<li id="comment-<?php comment_ID(); ?>" class="comment_li">
    <?php _e( 'Pingback:', 'tangstyle' ); ?>
    <?php comment_author_link(); ?>
    <?php edit_comment_link( __( '(Edit)', 'tangstyle' ), '<span class="edit-link">', '</span>' ); ?>
<?php
    break;
    default :
    global $post;
?>
<li id="comment-li-<?php comment_ID(); ?>" class="comment_li">
    <div id="comment-<?php comment_ID(); ?>">
        <div class="comment_top clearfix">
            <div class="comment_avatar"><?php echo get_avatar( $comment, 40 );?></div>
            <div class="pull-left">
                <p class="comment_author"><?php printf(__('%s'), get_comment_author_link()) ?></p>
                <p class="comment_time"><?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></p>
            </div>
            <div class="pull-right"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( '回复TA', 'tangstyle' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?> <?php edit_comment_link( __( '编辑', 'tangstyle' ), '<span class="edit_link">', '</span>' ); ?></div>
        </div>
        <div class="comment_text"><?php comment_text(); ?></div>
        <?php if ( '0' == $comment->comment_approved ) : ?>
        <p style="color:#F00;"><?php _e( '您的评论正在等待审核。', 'tangstyle' ); ?></p>
        <?php endif; ?>
    </div>
<?php
    break;
    endswitch;
}
endif;

//主题设置
function JieStyle_customize_register( $wp_customize ) {

    //SEO 设置
    $wp_customize->add_section('setting_seo', array(
        'title' => 'SEO 设置',
        'priority' => 30,
        'capability' => 'edit_theme_options'
    ));
    $wp_customize->add_setting('tang_title', array(
        'default' => '它将显示在首页的 title 标签里',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_title', array(
        'label' => '首页标题 Title',
        'section' => 'setting_seo',
        'type' => 'text'
    ));
    $wp_customize->add_setting('tang_description', array(
        'default' => '它将显示在首页的 meta 标签的 description 属性里',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_description', array(
        'label' => '首页描述 Description',
        'section' => 'setting_seo',
        'type' => 'textarea'
    ));
    $wp_customize->add_setting('tang_keywords', array(
        'default' => '它将显示在首页的 meta 标签的 keywords 属性里，多个关键字以英文逗号隔开',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_keywords', array(
        'label' => '首页关键字 KeyWords',
        'section' => 'setting_seo',
        'type' => 'textarea'
    ));

    //风格设置
    $wp_customize->add_section('setting_style', array(
        'title' => '风格设置',
        'priority' => 31,
        'capability' => 'edit_theme_options'
    ));
    $wp_customize->add_setting('tang_avatar', array(
        'default' => 'https://tangjie.me/media/avatar.jpg',
        'type' => 'option'
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tang_avatar', array(
        'label' => '头像图片链接',
        'section' => 'setting_style'
    )));
    $wp_customize->add_setting('tang_color', array(
        'default' => '#5bc0eb',
        'type' => 'option'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tang_color', array(
        'label' => '主题风格色调',
        'section' => 'setting_style'
    )));
    $wp_customize->add_setting('tang_color_hover', array(
        'default' => '#2980b9',
        'type' => 'option'
    ));
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tang_color_hover', array(
        'label' => '链接二态颜色',
        'section' => 'setting_style'
    )));

    //社交媒体设置
    $wp_customize->add_section('setting_interactive', array(
        'title' => '社交媒体设置',
        'priority' => 32,
        'capability' => 'edit_theme_options'
    ));
    $wp_customize->add_setting('tang_rss', array(
        'default' => '隐藏',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_rss', array(
        'label' => '是否显示 RSS 订阅源',
        'section' => 'setting_interactive',
        'type' => 'select',
        'choices' => array(
            '隐藏' => __( '隐藏' ),
            '显示' => __( '显示' )
        )
    ));
    $wp_customize->add_setting('tang_weixin', array(
        'default' => '隐藏',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_weixin', array(
        'label' => '是否显示微信公众号',
        'section' => 'setting_interactive',
        'type' => 'select',
        'choices' => array(
            '隐藏' => __( '隐藏' ),
            '显示' => __( '显示' )
        )
    ));
    $wp_customize->add_setting('tang_weixin_img', array(
        'default' => 'https://tangjie.me/media/wechat/pmtangjie.jpg',
        'type' => 'option'
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tang_weixin_img', array(
        'label' => '公众号二维码图片',
        'section' => 'setting_interactive',
    )));
    $wp_customize->add_setting('tang_weibo', array(
        'default' => '隐藏',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_weibo', array(
        'label' => '是否显示微博',
        'section' => 'setting_interactive',
        'type' => 'select',
        'choices' => array(
            '隐藏' => __( '隐藏' ),
            '显示' => __( '显示' )
        )
    ));
    $wp_customize->add_setting('tang_weibo_url', array(
        'default' => 'https://weibo.com',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_weibo_url', array(
        'label' => '微博地址',
        'section' => 'setting_interactive',
        'type' => 'url'
    ));
    $wp_customize->add_setting('tang_twitter', array(
        'default' => '隐藏',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_twitter', array(
        'label' => '是否显示 Twitter',
        'section' => 'setting_interactive',
        'type' => 'select',
        'choices' => array(
            '隐藏' => __( '隐藏' ),
            '显示' => __( '显示' )
        )
    ));
    $wp_customize->add_setting('tang_twitter_url', array(
        'default' => 'https://twitter.com',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_twitter_url', array(
        'label' => 'Twitter 地址',
        'section' => 'setting_interactive',
        'type' => 'url'
    ));
    $wp_customize->add_setting('tang_facebook', array(
        'default' => '隐藏',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_facebook', array(
        'label' => '是否显示 Facebook',
        'section' => 'setting_interactive',
        'type' => 'select',
        'choices' => array(
            '隐藏' => __( '隐藏' ),
            '显示' => __( '显示' )
        )
    ));
    $wp_customize->add_setting('tang_facebook_url', array(
        'default' => 'https://facebook.com',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_facebook_url', array(
        'label' => 'Facebook 地址',
        'section' => 'setting_interactive',
        'type' => 'url'
    ));
    $wp_customize->add_setting('tang_github', array(
        'default' => '隐藏',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_github', array(
        'label' => '是否显示 GitHub',
        'section' => 'setting_interactive',
        'type' => 'select',
        'choices' => array(
            '隐藏' => __( '隐藏' ),
            '显示' => __( '显示' )
        )
    ));
    $wp_customize->add_setting('tang_github_url', array(
        'default' => 'https://github.com',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_github_url', array(
        'label' => 'GitHub 地址',
        'section' => 'setting_interactive',
        'type' => 'url'
    ));

    //赞赏设置
    $wp_customize->add_section('setting_dashang', array(
        'title' => '赞赏设置',
        'priority' => 33,
        'capability' => 'edit_theme_options'
    ));
    $wp_customize->add_setting('tang_dashang', array(
        'default' => '停用',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_dashang', array(
        'label' => '是否启用',
        'section' => 'setting_dashang',
        'type' => 'select',
        'choices' => array(
            '停用' => __( '停用' ),
            '启用' => __( '启用' )
        )
    ));
    $wp_customize->add_setting('tang_dashang_info', array(
        'default' => '如果觉得我的文章对您有用，请随意赞赏。您的支持将鼓励我继续创作！',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_dashang_info', array(
        'label' => '想对读者说的话',
        'section' => 'setting_dashang',
        'type' => 'textarea'
    ));
    $wp_customize->add_setting('tang_dashang_alipay', array(
        'default' => 'https://tangjie.me/media/AliPay.png',
        'type' => 'option'
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tang_dashang_alipay', array(
        'label' => '支付宝收款二维码',
        'section' => 'setting_dashang',
    )));
    $wp_customize->add_setting('tang_dashang_wechat', array(
        'default' => 'https://tangjie.me/media/WeixinPay.png',
        'type' => 'option'
    ));
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tang_dashang_wechat', array(
        'label' => '微信收款二维码',
        'section' => 'setting_dashang',
    )));

    //版权信息
    $wp_customize->add_section('setting_copy', array(
        'title' => '版权信息',
        'priority' => 34,
        'capability' => 'edit_theme_options'
    ));
    $wp_customize->add_setting('tang_years', array(
        'default' => '2011-2021',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_years', array(
        'label' => '版权年份',
        'section' => 'setting_copy',
        'type' => 'text'
    ));
    $wp_customize->add_setting('tang_company', array(
        'default' => '唐杰',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_company', array(
        'label' => '版权公司',
        'section' => 'setting_copy',
        'type' => 'text'
    ));
    $wp_customize->add_setting('tang_icp', array(
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_icp', array(
        'label' => 'ICP 备案号',
        'section' => 'setting_copy',
        'type' => 'text'
    ));
    $wp_customize->add_setting('tang_beian', array(
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_beian', array(
        'label' => '公安备案号',
        'section' => 'setting_copy',
        'type' => 'text'
    ));

    //统计代码
    $wp_customize->add_section('setting_tongji', array(
        'title' => '统计代码',
        'priority' => 34,
        'capability' => 'edit_theme_options'
    ));
    $wp_customize->add_setting('tang_tongji', array(
        'default' => '代码在 body 页面底部或者 head 中，统计标识不会显示，但不影响统计效果',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_tongji', array(
        'label' => '统计代码',
        'section' => 'setting_tongji',
        'type' => 'textarea'
    ));
    $wp_customize->add_setting('tang_tongji_position', array(
        'default' => 'body',
        'type' => 'option'
    ));
    $wp_customize->add_control('tang_tongji_position', array(
        'label' => '放置位置',
        'section' => 'setting_tongji',
        'type' => 'select',
        'choices' => array(
            'body' => __( 'body' ),
            'head' => __( 'head' )
        )
    ));
}
add_action( 'customize_register', 'jieStyle_customize_register' );

?>