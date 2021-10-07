<?php get_header(); ?>

<div id="main">
<?php if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
    <article class="col-md-8 col-md-offset-2 view clearfix">
        <h1 class="view-title" style="border-bottom:1px dashed #5bc0eb;padding-bottom:10px;margin-bottom:30px;"><?php the_title(); ?></h1>
        <div class="view-content">
<?php the_content(); ?>
        </div>
        <section id="comments">
            <?php comments_template(); ?>
        </section>
    </article>
<?php
    }
} else { ?>
<?php } ?>
</div>

<?php get_footer(); ?>