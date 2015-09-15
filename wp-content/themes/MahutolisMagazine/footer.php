<div id="footer">
<div class="alignleft">Copyright &copy; <?php echo date('Y'); ?>. All Rights Reserved. Designed by <a href="http://www.simplewpthemes.com">WordPress Themes</a></div>
</div>
<?php
 $code = get_option('swt_custom_analytics_code'); echo stripslashes($code);
?>
<?php wp_footer();?>
</body>
</html>