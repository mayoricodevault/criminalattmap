<?php

class VA_Dashboard extends APP_Dashboard {

	public function __construct() {

		parent::__construct( array(
			'page_title' => __( 'Vantage Dashboard', APP_TD ),
			'menu_title' => __( 'Vantage', APP_TD ),
			'icon_url' => appthemes_locate_template_uri( 'images/admin-menu.png' ),
		) );

		add_filter( 'post_caluses', array( $this, 'filter_past_days' ), 10, 2 );

		$stats_icon = $this->box_icon( 'chart-bar.png' );
		$stats = array( 'stats', $stats_icon .  __( 'Snapshot', APP_TD ), 'normal' );
		array_unshift( $this->boxes, $stats );

	}

	public function stats_box() {

		$users = array();
		$users_stats = $this->get_user_counts();

		$users[ __( 'New Registrations Today', APP_TD ) ] = $users_stats['today'];
		$users[ __( 'New Registrations Yesterday', APP_TD ) ] = $users_stats['yesterday'];

		$users[ __( 'Total Users', APP_TD ) ] = array(
			'text' => $users_stats['total_users'],
			'url' => 'users.php',
		);

		$this->output_list( $users, '<ul style="float: right; width: 45%">' );

		$stats = array();
		$listings = $this->get_listing_counts();

		$stats[ __( 'New Listings (24 hours)', APP_TD ) ] = $listings['new'];
		// Published
		if ( isset( $listings['publish'] ) ) {
			$stats[ __( 'Published Listings', APP_TD ) ] = array(
				'text' => $listings['publish'],
				'url' => add_query_arg( array( 'post_type' => VA_LISTING_PTYPE, 'post_status' => 'publish' ), admin_url( 'edit.php' ) ),
			);
		}
		// Pending
		if ( isset( $listings['pending'] ) ) {
			$stats[ __( 'Pending Listings', APP_TD ) ] = array(
				'text' => $listings['pending'],
				'url' => add_query_arg( array( 'post_type' => VA_LISTING_PTYPE, 'post_status' => 'pending' ), admin_url( 'edit.php' ) ),
			);
		}
		// Pending Claimed
		if ( isset( $listings['pending_claimed'] ) ) {
			$stats[ __( 'Claimed Listings', APP_TD ) ] = array(
				'text' => $listings['pending_claimed'],
				'url' => add_query_arg( array( 'post_type' => VA_LISTING_PTYPE, 'post_status' => 'pending_claimed' ), admin_url( 'edit.php' ) ),
			);
		}
		// Total
		$stats[ __( 'Total Listings', APP_TD ) ] = array(
			'text' => $listings['all'],
			'url' => add_query_arg( array( 'post_type' => VA_LISTING_PTYPE ), admin_url( 'edit.php' ) ),
		);

		$this->output_list( $stats );

		if ( current_theme_supports( 'app-payments' ) ) {
			$date_week_ago = date( 'Y-m-d', strtotime( '-7 days', current_time( 'timestamp' ) ) );
			$revenue_info = array();
			$revenue_info[ __( 'Revenue (7 days)', APP_TD ) ] = appthemes_get_price( appthemes_get_orders_revenue( $date_week_ago ) );
			$revenue_info[ __( 'Total Revenue', APP_TD ) ] = appthemes_get_price( appthemes_get_orders_revenue() );

			$this->output_list( $revenue_info );
		}

		$product_info = array();
		$product_info[ __( 'Product Version', APP_TD ) ] = VA_VERSION;
		$product_info[ __( 'Product Support', APP_TD ) ] = html( 'a', array( 'href' => 'http://forums.appthemes.com' ), __( 'Forum', APP_TD ) );
		$product_info[ __( 'Product Support', APP_TD ) ] .= ' | ' . html( 'a', array( 'href' => 'http://docs.appthemes.com/' ), __( 'Documentation', APP_TD ) );

		$this->output_list( $product_info );
	}

	private function output_list( $array, $begin = '<ul>', $end = '</ul>', $echo = true ) {

		$html = '';
		foreach ( $array as $title => $value ) {
			if ( is_array( $value ) ) {
				$html .= '<li>' . $title . ': <a href="' . $value['url'] . '">' . $value['text'] . '</a></li>';
			} else {
				$html .= '<li>' . $title . ': ' . $value . '</li>';
			}
		}

		if ( $echo ) {
			echo $begin . $html . $end;
		} else {
			return $begin . $html . $end;
		}
	}

	private function get_user_counts() {
		global $wpdb;

		$users = (array) count_users();

		$capabilities_meta = $wpdb->prefix . 'capabilities';
		$date_today = date( 'Y-m-d', current_time( 'timestamp' ) );
		$date_yesterday = date( 'Y-m-d', strtotime( '-1 days', current_time( 'timestamp' ) ) );

		$users['today'] = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $wpdb->users INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id WHERE $wpdb->usermeta.meta_key = %s AND ($wpdb->usermeta.meta_value NOT LIKE %s) AND $wpdb->users.user_registered >= %s", $capabilities_meta, '%administrator%', $date_today ) );
		$users['yesterday'] = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $wpdb->users INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id WHERE $wpdb->usermeta.meta_key = %s AND ($wpdb->usermeta.meta_value NOT LIKE %s) AND $wpdb->users.user_registered BETWEEN %s AND %s", $capabilities_meta, '%administrator%', $date_yesterday, $date_today ) );

		return $users;
	}

	private function get_listing_counts() {

		$listings = (array) wp_count_posts( VA_LISTING_PTYPE );

		$all = 0;
		foreach ( (array) $listings as $type => $count ) {
			$all += $count;
		}
		$listings['all'] = $all;

		$yesterday_posts = new WP_Query( array(
			'past_days' => 7
		) );
		$listings['new'] = $yesterday_posts->post_count;

		return $listings;
	}

	public function filter_past_days( $clauses, $wp_query ) {
		global $wp_query;

		$past_days = intval( $wp_query->get( 'past_days' ) );
		if ( $past_days ) {
			$clauses['where'] .= ' AND post_data > \'' . date( 'Y-m-d', strtotime( '-' . $past_days .' days' ) ) . '\'';
		}

		return $clauses;
	}


}
