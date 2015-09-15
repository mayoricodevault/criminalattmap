<ul id="topsoc">
<li class="twitter">
   <a id="twitter" class="show-tooltip" title="Follow us on Twitter!" href="<?php $twitt = get_option('swt_twitt'); echo $twitt; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/twitter.png" alt="Twitter" /></a>
</li>
<li class="socrss">
   <a id="socrss" class="show-tooltip" title="Subscribe to our feed!" href="<?php $rss = get_option('swt_rss'); echo $rss; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/rss.png"  alt="Rss Feed" /></a>
</li>
<li class="fb">
   <a id="fb" class="show-tooltip" title="Join us on Facebook!" href="<?php $fb = get_option('swt_fb'); echo $fb; ?>"><img src="<?php bloginfo('template_directory'); ?>/images/facebook.png" alt="Facebook Group" /></a>
</li>
</ul>