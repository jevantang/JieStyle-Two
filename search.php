<?php get_header(); ?>
<div id="main">
    <div class="row box">
        <div class="col-md-8">
            <h2 class="uptop"><i class="fas fa-search"></i> <?php the_search_query(); ?></h2>
            <?php while (have_posts()) {
    the_post(); ?>
            <article class="article-list-2 clearfix">
                <div class="post-time"><i class="far fa-calendar-alt"></i> <?php the_time('m月d日') ?></div>
                <h1 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
                <div class="post-meta">
                    <span class="meta-span"><i class="far fa-folder"></i> <?php the_category(',') ?></span>
                    <span class="meta-span"><i class="fas fa-comments"></i> <?php comments_popup_link('没有评论', '1条评论', '%条评论'); ?></span>
                </div>
            </article>
            <?php
} ?>
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