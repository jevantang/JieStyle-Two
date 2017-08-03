<?php
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

//禁用自动草稿
add_action('wp_print_scripts','disable_autosave');
function disable_autosave(){  
    wp_deregister_script('autosave'); 
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

//启动友情链接
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//替换Gravatar服务器
function kratos_get_avatar( $avatar ) {
$avatar = preg_replace( "/http:\/\/(www|\d).gravatar.com/","https://cn.gravatar.com",$avatar );
return $avatar;
}
add_filter( 'get_avatar', 'kratos_get_avatar' );

//移除菜单的多余CSS选择器
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
  return is_array($var) ? array() : '';
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

//禁用Emoji表情
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

// 获得热评文章
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

//分页
function pagination($query_string){
global $posts_per_page, $paged;
$my_query = new WP_Query($query_string ."&posts_per_page=-1");
$total_posts = $my_query->post_count;
if(empty($paged))$paged = 1;
$prev = $paged - 1;							
$next = $paged + 1;	
$range = 5; // 分页数设置
$showitems = ($range * 2)+1;
$pages = ceil($total_posts/$posts_per_page);
if(1 != $pages){
	echo "<ul class='pagination'>";
	echo ($paged > 2 && $paged+$range+1 > $pages && $showitems < $pages)? "<li><a href='".get_pagenum_link(1)."'><i class='fa fa-angle-double-left' aria-hidden='true'></i></a></li>":"";
	echo ($paged > 1 && $showitems < $pages)? "<li><a href='".get_pagenum_link($prev)."'><i class='fa fa-angle-left' aria-hidden='true'></i></a></li>":"";		
	for ($i=1; $i <= $pages; $i++){
	if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
	echo ($paged == $i)? "<li class='active'><a href='".get_pagenum_link($i)."'>".$i."<span class='sr-only'>(current)</span></a></li>":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>"; 
	}
	}
	echo ($paged < $pages && $showitems < $pages) ? "<li><a href='".get_pagenum_link($next)."'><i class='fa fa-angle-right' aria-hidden='true'></i></a></li>" :"";
	echo ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) ? "<li><a href='".get_pagenum_link($pages)."'><i class='fa fa-angle-double-right' aria-hidden='true'></i></a></li>":"";
	echo "</ul>\n";
	}
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
return $return;
}
add_filter('get_comment_author_link', 'remove_comment_links');
remove_filter('comment_text', 'make_clickable', 9);

// 百度ping功能
function wpyou_baiping($post_id) {
$baiduXML = 'weblogUpdates.extendedPing' . get_option('blogname') . ' ' . home_url() . ' ' . get_permalink($post_id) . ' ' . get_feed_link() . ' ';
$wp_http_obj = new WP_Http();
$return = $wp_http_obj->post('http://ping.baidu.com/ping/RPC2', array('body' => $baiduXML, 'headers' => array('Content-Type' => 'text/xml')));
if(isset($return['body'])){
if(strstr($return['body'], '0')){
$noff_log='succeeded!';
}
else{
$noff_log='failed!';
}
}else{
$noff_log='failed!';
}
}
add_action('publish_post', 'wpyou_baiping');

// 评论回应邮件通知
function comment_mail_notify($comment_id) {
  $admin_email = get_bloginfo ('admin_email'); // $admin_email 可改为你指定的 e-mail.
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $to = $parent_id ? trim(get_comment($parent_id)->comment_author_email) : '';
  $spam_confirmed = $comment->comment_approved;
  if (($parent_id != '') && ($spam_confirmed != 'spam') && ($to != $admin_email) && ($comment_author_email == $admin_email)) {
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); // no-reply 可改为可用的 e-mail.
    $subject = '您在 [' . get_option("blogname") . '] 的评论有新的回复';
    $message = '
    <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您曾在 [' . get_option("blogname") . '] 的文章 《' . get_the_title($comment->comment_post_ID) . '》 上发表评论:<br />'
       . nl2br(get_comment($parent_id)->comment_content) . '</p>
      <p>' . trim($comment->comment_author) . ' 给您的回复如下:<br />'
       . nl2br($comment->comment_content) . '<br /></p>
      <p>您可以点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回复的完整內容</a></p>
      <p>欢迎再次光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
      <p>(此邮件由系统自动发出,请勿直接回复.)</p>
    </div>';
	$message = convert_smilies($message);
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
    //echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
  }
}
add_action('comment_post', 'comment_mail_notify');

// 颜色选择器
function color_picker_assets() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-color-picker-handle', plugins_url('my-plugin.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'color_picker_assets' );

?>
<?php
$themename = "JieStyle";
$shortname = "tang";
$options = array (
	array(
		"name" => "首页标题 Title",
		"id" => $shortname."_title",
		"type" => "text",
		"std" => "它将显示在首页的 title 标签里"
	),
	array(
		"name" => "首页描述 Description",
		"id" => $shortname."_description",
		"type" => "textarea",
		"std" => "它将显示在首页的 meta 标签的 description 属性里"
	),
	array(
		"name" => "首页关键字 KeyWords",
		"id" => $shortname."_keywords",
		"type" => "textarea",
		"std" => "它将显示在首页的 meta 标签的 keywords 属性里。多个关键字以英文逗号隔开。"
	),
	array(
		"type" => "hr",
	),
	array(
		"name" => "版权年份",
		"id" => $shortname."_years",
		"type" => "text",
		"std" => "2017",
	),
	array(
		"name" => "版权公司",
		"id" => $shortname."_company",
		"type" => "text",
		"std" => "产品经理@唐杰",
	),
	array(
		"type" => "hr",
	),
	array(
		"name" => "导航栏色调",
		"id" => $shortname."_color",
		"type" => "color",
		"std" => "#5bc0eb",
	),
	array(
		"name" => "头像图片链接",
		"id" => $shortname."_avatar",
		"type" => "text",
		"std" => "https://tangjie.me/wp-content/themes/JieStyle-Two/images/avatar.jpg",
	),
	array(
		"name" => "是否显示微信公众号",
		"id" => $shortname."_weixin",
		"type" => "select",
		"std" => "隐藏",
		"options" => array("隐藏", "显示")
	),
	array(
		"name" => "公众号二维码图片",
		"id" => $shortname."_weixin_img",
		"type" => "text",
		"std" => "https://tangjie.me/media/weixin.jpg",
	),
	array(
		"type" => "hr",
	),
	array(
		"name" => "是否显示Weibo",
		"id" => $shortname."_weibo",
		"type" => "select",
		"std" => "隐藏",
		"options" => array("隐藏", "显示")
	),
	array(
		"name" => "Weibo地址",
		"id" => $shortname."_weibo_url",
		"type" => "text",
		"std" => "https://weibo.com/782622",
	),
	array(
		"name" => "是否显示Twitter",
		"id" => $shortname."_twitter",
		"type" => "select",
		"std" => "隐藏",
		"options" => array("隐藏", "显示")
	),
	array(
		"name" => "Twitter地址",
		"id" => $shortname."_twitter_url",
		"type" => "text",
		"std" => "https://twitter.com/JieTangOK",
	),
	array(
		"name" => "是否显示Facebook",
		"id" => $shortname."_facebook",
		"type" => "select",
		"std" => "隐藏",
		"options" => array("隐藏", "显示")
	),
	array(
		"name" => "Facebook地址",
		"id" => $shortname."_facebook_url",
		"type" => "text",
		"std" => "https://www.facebook.com/jietangok",
	),
	array(
		"name" => "是否显示GitHub",
		"id" => $shortname."_github",
		"type" => "select",
		"std" => "隐藏",
		"options" => array("隐藏", "显示")
	),
	array(
		"name" => "GitHub地址",
		"id" => $shortname."_github_url",
		"type" => "text",
		"std" => "https://github.com/Jarvis-Tang",
	),
	array(
		"type" => "hr",
	),
	array(
		"name" => "打赏-是否启用",
		"id" => $shortname."_dashang",
		"type" => "select",
		"std" => "禁用",
		"options" => array("禁用", "启用")
	),
	array(
		"name" => "打赏-描述",
		"id" => $shortname."_dashang_info",
		"type" => "text",
		"std" => "如果觉得我的文章对您有用，请随意打赏。您的支持将鼓励我继续创作！",
		"explain" => "想对读者说的话"
	),
	array(
		"name" => "打赏-支付宝",
		"id" => $shortname."_dashang_alipay",
		"type" => "text",
		"std" => "https://tangjie.me/media/AliPay.png",
	),
	array(
		"name" => "打赏-微信",
		"id" => $shortname."_dashang_wechat",
		"type" => "text",
		"std" => "https://tangjie.me/media/WeixinPay.png",
	),
);
function mytheme_add_admin() {
    global $themename, $shortname, $options;
    if ( $_GET['page'] == basename(__FILE__) ) {
        if ( 'save' == $_REQUEST['action'] ) {
            foreach ($options as $value) {
            update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
            foreach ($options as $value) {
            if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }
            header("Location: themes.php?page=functions.php&saved=true");
            die;
        } else if( 'reset' == $_REQUEST['action'] ) {
            foreach ($options as $value) {
                delete_option( $value['id'] );
                update_option( $value['id'], $value['std'] );
            }
            header("Location: themes.php?page=functions.php&reset=true");
            die;
        }
    }
    add_theme_page($themename." 设置", "$themename 设置", 'edit_themes', basename(__FILE__), 'mytheme_admin');
}
function mytheme_admin() {
    global $themename, $shortname, $options;
	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated notice is-dismissible"><p>'.$themename.' 设置已保存。</p></div>';

?>

<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/bootstrap.min.css">
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/bootstrap.min.js"></script>

<div class="container-fluid">
	<h2 class="text-primary"><?php echo $themename; ?> Two <a href="https://tangjie.me/jiestyle-two" target="_blank" data-toggle="tooltip" data-placement="bottom" title="点击查看更新"><span class="badge">v2.3</span></a></h2>
	<hr class="wp-header-end">
	<hr>
	<form class="form-horizontal" method="post">
	<?php foreach ($options as $value) {
		if ($value['type'] == "text") { ?>
		<div class="form-group">
			<label for="options" class="col-sm-2 control-label"><?php echo $value['name']; ?></label>
			<div class="col-sm-10">
				<input class="form-control" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?>" />
			</div>
		</div>
		<?php } elseif ($value['type'] == "textarea") { ?>
		<div class="form-group">
			<label for="options" class="col-sm-2 control-label"><?php echo $value['name']; ?></label>
			<div class="col-sm-10">
				<textarea class="form-control" rows="3" name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" ><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?></textarea>
			</div>
		</div>
		<?php } elseif ($value['type'] == "color") { ?>
		<div class="form-group">
			<label for="options" class="col-sm-2 control-label"><?php echo $value['name']; ?></label>
			<div class="col-sm-4">
				<input name="<?php echo $value['id']; ?>" id="mail-border-color" type="text"  value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?>" />
			</div>
			<div class="col-sm-4">
				<p class="form-control-static">默认颜色 #5bc0eb</p>
			</div>
		</div>
		<?php } elseif ($value['type'] == "select") { ?>
		<div class="form-group">
			<label for="options" class="col-sm-2 control-label"><?php echo $value['name']; ?></label>
			<div class="col-sm-2">
				<select class="form-control" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
					<?php foreach ($value['options'] as $option) { ?>
					<option value="<?php echo $option;?>" <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>>
						<?php
						if ((empty($option) || $option == '' ) && isset($value['option'])) {
							echo $value['option'];
						} else {
							echo $option;
						}
						?>
					</option>
					<?php } ?>
				</select>
			</div>
		</div>
		<?php } elseif ($value['type'] == "hr") { ?>
		<hr>
		<?php } ?>
	<?php } ?>
	<div class="form-group" style="margin-top:50px;">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-primary" name="save"> 保存 </button>
			<input type="hidden" name="action" value="save" />
		</div>
	</div>
	</form>
</div>

<script>
$(function() {
	$('[data-toggle="tooltip"]').tooltip()
});
$(function () {
	$('#mail-border-color').wpColorPicker();
});
</script>

<?php
}
add_action('admin_menu', 'mytheme_add_admin');
?>
