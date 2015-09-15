<div id="sidebar">

<?php if (get_option('swt_fcats') == 'Hide') { ?>
<?php { echo ''; } ?>
<?php } else { include(TEMPLATEPATH . '/includes/featured-cats.php'); } ?>

<?php if (get_option('swt_banners') == 'Hide') { ?>
<?php { echo ''; } ?>
<?php } else { include(TEMPLATEPATH . '/includes/banners.php'); } ?>

    <?php if (!function_exists('dynamic_sidebar')
	|| !dynamic_sidebar()) : ?>

    <div class="side-widget">
    <h3>Pages</h3>
    <ul><?php wp_list_pages('title_li=' ); ?></ul>
    </div>

    <div class="side-widget">
    <h3>Categories</h3>
    <ul>
    <?php wp_list_categories('title_li=' ); ?>
    </ul>
    </div>


    <div class="side-widget">
    <h3>Search</h3>
    <?php get_search_form(); ?>
    </div>


    <div class="side-widget tagcloud">
    <h3>Blog Tags</h3>
    <?php wp_tag_cloud('number=14'); ?>
    </div>

    <?php endif; ?>
</div>
<div style="clear:both"></div>
</div> <!-- END MAINWRAP -->
<div id="sidebar-bottom">
<div id="sidebar2">
<div id="footer1">

<?php if (get_option('swt_aboutcheck') == 'Hide') { ?>
<?php { echo ''; } ?>
<?php } else { include(TEMPLATEPATH . '/includes/aboutus.php'); } ?>

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(2) ) : else : ?>

<?php endif; ?>
</div>

<div id="footer2">

<?php if (get_option('swt_flickr') == 'Hide') { ?>
<?php { echo ''; } ?>
<?php } else { include(TEMPLATEPATH . '/includes/flickr.php'); } ?>

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(3) ) : else : ?>

<?php endif; ?>
</div>

<div id="footer3">

<?php if (get_option('swt_feedburner') == 'Hide') { ?>
<?php { echo ''; } ?>
<?php } else { include(TEMPLATEPATH . '/includes/feedburner.php'); } ?>

<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar(4) ) : else : ?>

<?php endif; ?>
</div>
</div>
</div>