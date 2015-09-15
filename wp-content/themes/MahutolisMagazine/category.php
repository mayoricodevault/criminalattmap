<?php get_header(); ?>

<?php if (get_option('swt_slider') == 'Hide') { ?>
<?php { echo ''; } ?>
<?php } else { include(TEMPLATEPATH . '/includes/slide.php'); } ?>

<div id="contentwrap">
<div class="inside">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
				<h2 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

				<div class="entry">
                <?php if ( function_exists( 'get_the_image' ) ) {
                    get_the_image( array( 'custom_key' => array( 'post_thumbnail' ), 'default_size' => 'full', 'image_class' => 'alignleft', 'width' => '198', 'height' => '166' ) ); } ?>
                    <p><?php truncate_post(380, true); ?></p>
				</div>
                <div class="meta">
                 <span class="time">Posted on <?php the_time('F d, Y'); ?></span><a class="more-link" href="<?php the_permalink() ?>#more">Read More</a>
                </div>

			</div>

		<?php endwhile; ?>

        <div class="navigation clearfix">
         <div class="alignleft older">
            <?php next_posts_link(__('Older Entries')) ?>
          </div>
          <div class="alignright newer">
            <?php previous_posts_link(__('Newer Entries')) ?>
          </div>
        </div>

	<?php endif; ?>
</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
