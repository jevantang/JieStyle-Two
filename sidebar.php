
<aside class="widget clearfix">
  <form id="searchform" action="<?php bloginfo('siteurl'); ?>">
    <div class="input-group">
      <input type="search" class="form-control" placeholder="搜索…" value="<?php the_search_query(); ?>" name="s">
      <span class="input-group-btn">
      <button class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
      </span> </div>
  </form>
</aside>
<aside class="widget clearfix">
  <h4 class="widget-title">文章分类</h4>
  <div class="widget-cat">
    <ul>
      <?php wp_list_categories('depth=1&title_li=0&orderby=id&show_count=1'); ?>
    </ul>
  </div>
</aside>
<aside class="widget clearfix">
  <h4 class="widget-title">热门文章</h4>
  <ul class="widget-hot">
    <?php tangstyle_get_most_viewed(); ?>
  </ul>
</aside>
<aside class="widget clearfix">
  <h4 class="widget-title">随机推荐</h4>
  <ul class="widget-hot">
    <?php $rand_posts = get_posts('numberposts=10&orderby=rand');  foreach( $rand_posts as $post ) : ?>
    <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
	<?php endforeach; ?>
  </ul>
</aside>
<aside class="widget clearfix">
  <h4 class="widget-title">标签云</h4>
  <div class="widget-tags">
    <?php wp_tag_cloud();?>
  </div>
</aside>
<?php if (is_home() || is_front_page()) { ?>
<aside class="widget clearfix">
  <h4 class="widget-title">友情链接</h4>
  <ul class="widget-links">
    <?php wp_list_bookmarks('title_li=0&categorize=0&orderby=rating&order=desc'); ?>
  </ul>
</aside>
<?php } ?>
