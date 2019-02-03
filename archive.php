<?php get_header(); ?>
<div id="main">
    <div class="row box">
        <div class="col-md-8">
            <?php if ( is_day() ) : ?>
            <h3 class="uptop"><i class="far fa-calendar-alt"></i> <?php printf(__('日期浏览: %s'), get_the_date('Y年n月j日 D') ); ?></h3>
            <?php elseif ( is_month() ) : ?>
            <h3 class="uptop"><i class="far fa-calendar-alt"></i> <?php printf(__('日期浏览: %s'), get_the_date('Y年M') ); ?></h3>
            <?php elseif ( is_year() ) : ?>
            <h3 class="uptop"><i class="far fa-calendar-alt"></i> <?php printf(__('日期浏览: %s'), get_the_date('Y年') ); ?></h3>
            <?php elseif ( is_tag() ) : ?>
            <h3 class="uptop"><i class="fas fa-tags"></i> <?php printf(__('Tag: %s'), single_tag_title('', false ) ); ?></h3>
            <?php else : ?>
            <h3 class="uptop"><?php _e( 'Blog Archives' ); ?></h3>
            <?php endif; ?>
            <?php while ( have_posts() ) : the_post(); ?>
            <article class="article-list-2 clearfix">
                <div class="post-time"><i class="far fa-calendar-alt"></i> <?php the_time('m月d日') ?></div>
                <h1 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
                <div class="post-meta">
                    <span class="meta-span"><i class="far fa-folder"></i> <?php the_category(',') ?></span>
                    <span class="meta-span"><i class="fas fa-comments"></i> <?php comments_popup_link ('没有评论','1条评论','%条评论'); ?></span>
                    <span class="meta-span hidden-xs"><i class="fas fa-tags"></i> <?php the_tags('',',',''); ?></span>
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