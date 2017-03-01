<?php
if ( post_password_required() )
	return;
?>
<?php if ( have_comments() ) : ?>

<div class="comment-head clearfix">
  <div class="pull-left"><?php comments_number(__('没有评论','1条评论','%条评论'));?></div>
  <div class="pull-right"><a href="#respond"><i class="fa fa-pencil"></i> 添加新评论</a></div>
</div>
<ul>
  <?php wp_list_comments( array( 'callback' => 'tangstyle_comment', 'style' => 'ol' ) ); ?>
</ul>
<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
<nav id="comment-nav-below">
  <ul class="pager">
    <li><?php previous_comments_link( __( '上一页', 'tangstyle' ) ); ?></li>
    <li><?php next_comments_link( __( '下一页', 'tangstyle' ) ); ?></li>
  </ul>
</nav>
<?php endif; ?>
<?php elseif ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
<p><?php _e( '评论已关闭!', 'tangstyle' ); ?></p>
<?php endif; ?>
<?php 
		$fields =  array(
   			 'author' => '<div class="comment-form-author form-group has-feedback"><div class="input-group"><div class="input-group-addon"><i class="fa fa-user"></i></div><input class="form-control" placeholder="昵称" id="author" name="author" type="text" value="" ' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /><span class="form-control-feedback required">*</span></div></div>',
   			 'email'  => '<div class="comment-form-email form-group has-feedback"><div class="input-group"><div class="input-group-addon"><i class="fa fa-envelope-o"></i></div><input class="form-control" placeholder="邮箱" id="email" name="email" type="text" value="" ' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /><span class="form-control-feedback required">*</span></div></div>',
   			 'url'  => '<div class="comment-form-url form-group has-feedback"><div class="input-group"><div class="input-group-addon"><i class="fa fa-link"></i></div><input class="form-control" placeholder="网站" id="url" name="url" type="text" value="" ' . esc_attr(  $commenter['comment_author_url'] ) . '" size="30"' . $aria_req . ' /></div></div>',
		);
		$args = array(
			'title_reply_before' => '<h4 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h4>',
			'fields' =>  $fields,
			'class_submit' => 'btn btn-primary',
			'comment_field' =>  '<div class="comment form-group has-feedback"><textarea class="form-control" id="comment" placeholder=" " name="comment" rows="5" aria-required="true" required  onkeydown="if(event.ctrlKey){if(event.keyCode==13){document.getElementById(\'submit\').click();return false}};"></textarea></div>',
		);
		comment_form($args);
	?>
