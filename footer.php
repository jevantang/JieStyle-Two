<footer id="footer">
  <div class="copyright">
    <p><i class="fa fa-copyright" aria-hidden="true"></i> <?php echo get_option('tang_years'); ?> <b><?php bloginfo('name'); ?></b></p>
    <p>Powered by <b>WordPress</b>. Theme by <a href="http://tangjie.me/jiestyle" title="JieStyle" target="_blank"><b>JieStyle Two</b></a> | <?php echo get_option( 'zh_cn_l10n_icp_num' );?></p>
  </div>
  <div style="display:none;"> <?php echo stripslashes(get_option('tang_tongji')); ?> </div>
</footer>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/skel.min.js"></script> 
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/util.min.js"></script> 
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/nav.js"></script>
<?php wp_footer(); ?>
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