<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php if (is_home()) {
	echo bloginfo('name');
} elseif (is_404()) {
	echo '404 Not Found';
} elseif (is_category()) {
	echo 'Category:'; wp_title('');
} elseif (is_search()) {
	echo 'Search Results';
} elseif ( is_day() || is_month() || is_year() ) {
	echo 'Archives:'; wp_title('');
} else {
	echo bloginfo('name'); echo wp_title('');
}
?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery-1.4.2.min.js"></script>
</head>
<body>
<div id="top"><!-- PAGE MENU, OR PRIMARY CUSTOM MENU -->
<div id="topin">
    <?php if ( has_nav_menu( 'primary-menu' ) ) {
          wp_nav_menu( array( 'menu_class' => 'sf-menu', 'theme_location' => 'primary-menu' ) );
	} else {
	?>
        <ul class="sf-menu">
            <?php $ex = get_option('swt_pages');  ?>
        	<li class="page_item <?php if(is_home()): ?>current-menu-item<?php endif ?>"><a href="<?php echo get_option('home'); ?>/">Home</a></li>
        	<?php wp_list_pages("sort_column=menu_order&depth=1&exclude=$ex;&title_li=");?>
    	</ul>
    <?php } ?>
<?php if (get_option('swt_follow') == 'Hide') { ?>
<?php { echo ''; } ?>
<?php } else { include(TEMPLATEPATH . '/includes/follow.php'); } ?>
</div><!-- END TOPIN -->
</div><!-- END PAGE MENU -->
<div id="header">
 <div id="blogtitle">
       <h1><a href="<?php echo get_option('home'); ?>/"><img class="logo" src="<?php bloginfo('template_directory'); ?>/images/logo.png" alt="<?php bloginfo('sitename'); ?>" /></a></h1>
 </div>
        <?php if (get_option('swt_topbanner') == 'Hide') { ?>
        <?php { echo ''; } ?>
        <?php } else { include(TEMPLATEPATH . '/includes/topbanner.php'); } ?>
 </div>
<div id="mainwrap">
 <div id="nav2"><!-- CATEGORY MENU -->
    <?php if ( has_nav_menu( 'secondary-menu' ) ) {   //checks if custom menu has been created
          wp_nav_menu( array( 'menu_class' => 'sf-menu', 'theme_location' => 'secondary-menu' ) );
	} else {// if not, old fashioned category menu will be loaded
	?>
        <ul class="sf-menu">
            <?php $cat = get_option('swt_categories');  ?>
            <li class="category_item <?php if(is_home()) { echo 'current-menu-item'; } ?>"><a href="<?php bloginfo('home'); ?>" id="home">Home</a></li>
        	<?php wp_list_categories("title_li=&depth=1&exclude=$cat" ); ?>
        </ul>
   <?php } ?>

 </div><!-- END CATEGORY MENU -->