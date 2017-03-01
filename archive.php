<?php get_header(); ?>

<div id="main">
  <div class="row box">
    <div class="col-md-8">
	  <?php if ( is_day() ) : ?>
      <h3 class="uptop"><i class="fa fa-calendar" aria-hidden="true"></i> <?php printf(__('Daily: %s'), get_the_date() ); ?></h3>
      <?php elseif ( is_month() ) : ?>
      <h3 class="uptop"><i class="fa fa-calendar" aria-hidden="true"></i> <?php printf(__('Monthly: %s'), get_the_date('F Y') ); ?></h3>
      <?php elseif ( is_year() ) : ?>
      <h3 class="uptop"><i class="fa fa-calendar" aria-hidden="true"></i> <?php printf(__('Yearly: %s'), get_the_date('Y') ); ?></h3>
      <?php elseif ( is_tag() ) : ?>
      <h3 class="uptop"><i class="fa fa-tags" aria-hidden="true"></i> <?php printf(__('Tag: %s'), single_tag_title('', false ) ); ?></h3>
      <?php else : ?>
      <h3 class="uptop"><?php _e( 'Blog Archives' ); ?></h3>
      <?php endif; ?>
      <?php while ( have_posts() ) : the_post(); ?>
      <article class="article-list-2 clearfix">
        <div class="post-time"><i class="fa fa-calendar"></i> <?php the_time('m月d日') ?></div>
        <h1 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
        <div class="post-meta">
          <span class="meta-span"><i class="fa fa-folder-open-o"></i> <?php the_category(',') ?></span>
          <span class="meta-span"><i class="fa fa-commenting-o"></i> <?php comments_popup_link ('没有评论','1条评论','%条评论'); ?></span>
          <span class="meta-span hidden-xs"><i class="fa fa-tags" aria-hidden="true"></i> <?php the_tags('',',',''); ?></span>
        </div>
      </article>
      <?php endwhile; ?>
      <nav style="float:right">
        <?php pagination($query_string); ?>
      </nav>
    </div>
    <div class="col-md-4 hidden-xs hidden-sm">
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>