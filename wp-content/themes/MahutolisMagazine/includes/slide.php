<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/ui.core.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/ui.tabs.js"></script>
<script type="text/javascript">
$(document).ready(function() {
        // Tabs
        $('#tabs').tabs({
            fx: {
                opacity: 'toggle'
            }
        }).tabs('rotate', 3000);
    });
</script>
<?php $slidecat = get_cat_ID(get_option('swt_slide_category'));
      $slidecount = get_option('swt_slide_count');
?>
       <div id="Container">
            <!-- Tabs -->
            <div id="tabs">
                <?php $my_query = new WP_Query('cat= '. $slidecat .'&showposts='.$slidecount.'');
                while ($my_query->have_posts()) : $my_query->the_post();$do_not_duplicate = $post->ID; $x++; ?>
                <div id="tabs-<?php echo $x; ?>" class="feature">

                 <div class="slwrap">
                  <img class="bigimg" src="<?php echo get_post_meta($post->ID, 'slide', $single = true); ?>" alt="<?php the_title() ?>" width="960" height="330" />
                </div>

                </div>
            <?php endwhile; ?>

                <ul id="tabby">
                <?php $my_query = new WP_Query('cat= '. $slidecat .'&showposts='.$slidecount.'');
                while ($my_query->have_posts()) : $my_query->the_post();$do_not_duplicate = $post->ID; $xb++;?>
                    <li class="item<?php echo $xb; ?>">
                        <a href="#tabs-<?php echo $xb; ?>">
                        <img class="slimage" src="<?php echo get_post_meta($post->ID, 'slide', $single = true); ?>" alt="<?php the_title() ?>" width="128" height="83" />
                        </a>
                    </li>
               <?php endwhile; ?>
                </ul>
            </div>
        </div>
<div style="clear:both"></div>