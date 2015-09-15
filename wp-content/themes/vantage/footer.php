<div id="footer" class="container">
	<div class="row">
		<?php appthemes_before_sidebar_widgets( 'va-footer' ); ?>

		<?php dynamic_sidebar( 'va-footer' ); ?>

		<?php appthemes_after_sidebar_widgets( 'va-footer' ); ?>
	</div>
</div>
<div id="post-footer" class="container">
	<div class="row">
		<?php wp_nav_menu( array(
			'container' => false,
			'theme_location' => 'footer',
			'fallback_cb' => false
		) ); ?>

		<div id="theme-info">Vantage &ndash; a <a target="_blank" href="http://www.appthemes.com/themes/vantage/" title="WordPress Directory Theme"><?php _e( 'WordPress Directory Theme', APP_TD ); ?></a> powered by <a href="http://www.wordpress.org" target="_blank">WordPress</a>.</div>
	</div>
</div>
