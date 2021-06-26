<div class="footer_search visible-xs visible-sm">
    <form id="searchform" action="<?php bloginfo('siteurl'); ?>">
        <div class="input-group">
            <input type="search" class="form-control" placeholder="搜索…" value="<?php the_search_query(); ?>" name="s">
            <span class="input-group-btn"><button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button></span>
        </div>
    </form>
</div>
<footer id="footer">
    <div class="copyright">
        <p><i class="far fa-copyright"></i> <?php echo get_option('tang_years'); ?> <b><?php echo get_option('tang_company'); ?></b></p>
        <p>Powered by <b>WordPress</b>. Theme by <a href="https://tangjie.me/jiestyle-two" data-toggle="tooltip" data-placement="top" title="WordPress 主题模板" target="_blank"><b>JieStyle Two</b></a> | <?php echo get_option( 'tang_icp' );?> <?php echo get_option( 'tang_beian' );?></p>
    </div>
    <?php if (get_option('tang_tongji_position') == 'body') { ?>
        <div style="display:none;"><?php echo stripslashes(get_option('tang_tongji')); ?></div>
    <?php } ?>
</footer>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/skel.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/util.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/nav.js"></script>
<?php wp_footer(); ?>
<script>
$(function() {
    $('[data-toggle="tooltip"]').tooltip()
});
</script>
<script>
(function(){
    var bp = document.createElement('script');
    var curProtocol = window.location.protocol.split(':')[0];
    if (curProtocol === 'https') {
        bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
    }
    else {
        bp.src = 'http://push.zhanzhang.baidu.com/push.js';
    }
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(bp, s);
})();
</script>
</body>
</html>