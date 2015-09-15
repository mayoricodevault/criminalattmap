<div class="widget">
<h3 id="feedb">Subscribe To Our Blog</h3>
<p id="signup">Get the latest news from us, on your email!</p>
<form id="subscribet" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo get_option('swt_femail'); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
        <div id="swrap">
        <input type="text" value="enter your email..." id="subbox" onfocus="if (this.value == 'enter your email...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'enter your email...';}" name="email"/>
        <input type="hidden" value="<?php echo get_option('swt_femail'); ?>" name="uri"/>
        <input type="hidden" name="loc" value="en_US"/>
        <input id="subm" type="image" src="<?php bloginfo('template_url'); ?>/images/submit.png" style="border:0; vertical-align: top;" />
        </div>
</form>
</div>