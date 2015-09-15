<div id="main">

<?php the_post(); ?>

<?php do_action( 'appthemes_notices' ); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Event">
	<?php the_listing_image_gallery(); ?>

	<?php appthemes_before_post_title( VA_EVENT_PTYPE ); ?>
	<h1 class="entry-title" itemprop="name"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	<p class="vcard author"><?php printf( __( 'Added by %s', APP_TD ), '<span class="fn">'. va_get_the_author_events_link() .'</span>' ); ?> </p>

	<p class="categories"><?php the_event_categories(); ?></p>
	<?php appthemes_after_post_title( VA_EVENT_PTYPE ); ?>

	<?php $website = get_post_meta( get_the_ID(), 'website', true ); ?>
	<?php $phone = get_post_meta( get_the_ID(), 'phone', true ); ?>
	<?php $facebook = get_post_meta( get_the_ID(), 'facebook', true ); ?>
	<?php $twitter = get_post_meta( get_the_ID(), 'twitter', true ); ?>

	<div id="event-days">
		<ul>
		<?php
		global $va_locale;
		$days = va_get_the_event_days(); 

		$i = 0;
		$len = count($days);

		foreach( $days as $date_U => $term ) { ?>
			<?php $date = $term->slug; ?>
			<?php $display_date = $va_locale->date( apply_filters( 'va_single_event_dates_date_format', get_option( 'date_format' ) ), strtotime( $date ) );?>
			<?php $times = va_get_the_event_day_times( $date ); ?>
			<li><?php echo html_link( va_event_day_get_term_link( date( 'Y-m-d', strtotime( $date ) ) ), $display_date ); ?><?php echo va_get_the_event_day_time( $times, ' - ', ' @ ' ); 
			if ($i == 0) { ?>
				<meta itemprop="startDate" content="<?php echo date( 'c', strtotime( $date ) ); ?>" />
			<?php } ?>
			</li>
		<?php $i++; } ?>
		</ul>
	</div>

	<?php if( $address = get_the_event_address() ) { ?> 
	<div id="event-address" itemprop="location" itemscope itemtype="http://schema.org/Place">
		<div itemprop="address"><?php echo $address; ?></div>
		<?php 
		$coord = appthemes_get_coordinates( $post->ID ); 
		if ( 0 < $coord->lat ) {
		?>
		<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
			<meta itemprop="latitude" content="<?php echo esc_attr( $coord->lat ); ?>" />
			<meta itemprop="longitude" content="<?php echo esc_attr( $coord->lng ); ?>" />
		</div>
		<?php } ?>
		
	</div>
	<?php } ?>
	
	<ul>
	<?php if ( $cost = get_the_event_cost() ) : ?> 
		<li class="cost" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><strong><?php printf( __( 'Cost: %s', APP_TD ), '<span itemprop="price">'. $cost .'</span>' ); ?></strong></li>
	<?php endif; ?>
	<?php if ( $phone) : ?>
		<li class="phone"><strong><?php echo esc_html( get_post_meta( get_the_ID(), 'phone', true ) ); ?></strong></li>
	<?php endif; ?>
	<?php if ( $website ) : ?>
		<li id="event-website"><a href="<?php echo esc_url( $website ); ?>" title="<?php _e( 'Website', APP_TD ); ?>" target="_blank"><?php echo esc_html( $website ); ?></a></li>
	<?php endif; ?>
	</ul>

	<?php if ( $facebook or $twitter ) : ?>
		<div id="event-follow">
			<p><?php _e( 'Follow:', APP_TD ); ?></p>
			<?php if ( $facebook ) : ?>
			<a href="<?php echo esc_url( 'http://facebook.com/' . $facebook ); ?>" title="<?php _e( 'Facebook', APP_TD ); ?>" target="_blank"><div class="facebook-icon">Facebook</div></a>
			<?php endif; ?>
			<?php if ( $twitter ) : ?>
			<a href="<?php echo esc_url( 'http://twitter.com/' . $twitter ); ?>" title="<?php _e( 'Twitter', APP_TD ); ?>" target="_blank"><div class="twitter-icon">Twitter -</div> <span class="twitter-handle">@<?php echo esc_html( $twitter ); ?></span></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="listing-fields event-fields">
		<?php the_event_fields(); ?>
	</div>

	<div class="single-listing single-event listing-faves event-faves">
		<?php the_event_faves_link(); ?>
	</div>

	<div class="listing-actions event-actions">
		<?php the_event_edit_link(); ?>
		<?php the_event_purchase_link(); ?>
		<?php the_contact_event_organizer_button(); ?>
	</div>

	<div class="listing-share">
		<?php if ( function_exists( 'sharethis_button' ) ) sharethis_button(); ?>
	</div>

	<hr />
	<div class="tags"><?php the_event_tags( '<span>' . __( 'Tags:', APP_TD ) . '</span> ' ); ?></div>
	<div class="added" style="display:none;"><?php _e( 'Updated:', APP_TD ); ?> <span class="date updated"><?php the_modified_time('M j, Y'); ?></span></div>

	<?php va_the_files_list(); ?>

	<div id="event-tabs">
		<div class="tabs">
			<a id="overview-tab" class="active-tab rounded-t first" href="#overview"><?php _e( 'Overview', APP_TD ); ?></a>
			<a id="comments-tab" class="rounded-t" href="#comments"><?php _e( 'Comments', APP_TD ); ?></a>

			<br class="clear" />
		</div>

		<section id="overview" class="tab" itemprop="description">
			<?php appthemes_before_post_content( VA_EVENT_PTYPE ); ?>
			<?php the_content(); ?>
			<?php appthemes_after_post_content( VA_EVENT_PTYPE ); ?>
		</section>

		<section id="comments" class="tab">
			<?php comments_template( '/comments-event.php', true ); ?>
		</section>
	</div>

	<div class="section-head">
		<a id="add-comment" name="add-comment"></a>
		<h2 id="left-hanger-add-comment"><?php _e( 'Leave a Comment', APP_TD ); ?></h2>
	</div>
	<div id="event-comment-form">
		<?php appthemes_before_comments_form(); ?>
	
		<?php va_event_comment_form(); ?>
	
		<?php appthemes_after_comments_form(); ?>
	</div>
</article>

</div><!-- /#main -->

<div id="sidebar">
<?php get_sidebar( 'single-event' ); ?>
</div>