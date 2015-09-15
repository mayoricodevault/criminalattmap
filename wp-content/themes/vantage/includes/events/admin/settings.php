<?php

add_action('admin_init', 'va_events_setup_settings_tab_init' );

function va_events_setup_settings_tab_init() {
	add_action( 'tabs_vantage_page_app-settings', array( 'VA_Events_Settings_Tab', 'init' ) );
	add_action( 'tabs_vantage_page_app-settings_page_content', array( 'VA_Events_Settings_Tab', 'page_content' ), 10 );
	add_action( 'admin_notices', array( 'VA_Events_Settings_Tab', 'prune_events' ) );
	add_action( 'admin_notices', array( 'VA_Events_Settings_Tab', 'events_enabled' ) );
}

class VA_Events_Settings_Tab {

	private static $page;

	static function prune_events() {
		if( isset( $_GET['prune'] ) && $_GET['prune'] == 1 && isset( $_GET['tab'] ) && $_GET['tab'] == 'events' ) {
			va_prune_expired_events();
			echo scb_admin_notice( 'Expired Events have been pruned.' );
		}
	}

	static function events_enabled() {
		if( isset( $_GET['enabled'] ) && $_GET['enabled'] == 1 && isset( $_GET['tab'] ) && $_GET['tab'] == 'events' ) {
			echo scb_admin_notice( 'Events have been enabled' );
		}
	}

	static function page_content( $page ) {
		global $va_options;

		if ( false === $va_options->get('events_enabled') )
			$page->tab_sections['events'] = wp_array_slice_assoc( $page->tab_sections['events'], array('events_enabled')) ;

	}

	static function events_first_run( $page ) {
		global $va_options;

		if ( true === $va_options->events_enabled && !empty( $_POST['events_enabled'] ) ) {
			remove_action( 'admin_init', 'appthemes_update_redirect' );
			remove_action( 'appthemes_first_run', 'appthemes_updated_version_notice', 999 );
			do_action( 'appthemes_first_run' );
			do_action( 'va_events_first_run' );
			echo html( 'script', 'location.href="' . admin_url( 'admin.php?page=app-settings&tab=events&enabled=1' ) . '"' );
		}
	}

	static function init( $page ) {
		self::$page = $page;

		$page->tabs->add( 'events', __( 'Events', APP_TD ) );

		$page->tab_sections['events']['events_enabled'] = array(
			'fields' => array(
				array(
					'title' => __( 'Enable Events', APP_TD ),
					'type' => 'checkbox',
					'name' => 'events_enabled',
					'value' => 1,
					'desc' => __( 'Yes', APP_TD ),
					'tip' => __( 'Should events functionality be enabled?', APP_TD ),
				),
			),
		);

		$page->tab_sections['events']['general'] = array(
			'title' => __( 'General', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Charge for Events', APP_TD ),
					'name' => 'event_charge',
					'type' => 'checkbox',
					'desc' => __( 'Yes', APP_TD ),
					'tip' => sprintf( __( 'Do you want to charge for creating an event on your site? You can manage your <a href="%s">Payments Settings</a> in the Payments Menu.', APP_TD ), 'admin.php?page=app-payments-settings&tab=events'),
				),
				array(
					'title' => __( 'Moderate Events', APP_TD ),
					'type' => 'checkbox',
					'name' => 'moderate_events',
					'desc' => __( 'Yes', APP_TD ),
					'tip' => __( 'Do you want to moderate new events before they are displayed live?', APP_TD ),
				),
				array(
					'title' => __( 'Event Expiration', APP_TD ),
					'type' => 'text',
					'name' => 'event_expiration',
					'extra' => array( 'size' => 2),
					'sanitize' => 'absint',
					'desc' => __( ' days after event date. (0 = Does not expire after event date)' ),
					'tip' => __( 'The amount of days after the event date after which the event will expire and be no longer publicly visible.', APP_TD ),
				),
				array(
					'title' => __( 'Expired Events', APP_TD ),
					'name' => '_blank',
					'type' => '',
					'desc' => sprintf( __( 'Prune  <a href="%s">Expired Events</a> now.', APP_TD ), 'admin.php?page=app-settings&tab=events&prune=1' ),
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip' => __( 'Run expired events check.', APP_TD ),
				),
			)
		);

		$page->tab_sections['events']['search'] = array(
			'title' => __( 'Search', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Default Search Result Sort', APP_TD ),
					'name' => 'default_event_search_sort',
					'type' => 'select',
					'values' => array(
						'default' => __( 'Default', APP_TD ),
						'event_date' => __( 'Event Date', APP_TD ),
						'popular' => __( 'Popular', APP_TD ),
						'most_comments' => __( 'Most Comments', APP_TD ),
						'newest' => __( 'Newest First', APP_TD ),
						'title' => __( 'Alphabetical', APP_TD ),
						'recently_discussed' => __( 'Recently Discussed', APP_TD ),
						'rand' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'The default search result sorting method when search is made without a location entered in search.', APP_TD ),
				),
				array(
					'title' => __( 'Default Location Based Search Result Sort', APP_TD ),
					'name' => 'default_event_geo_search_sort',
					'type' => 'select',
					'values' => array(
						'distance' => __( 'Closest Distance First', APP_TD ),
						'default' => __( 'Default', APP_TD ),
						'event_date' => __( 'Event Date', APP_TD ),
						'popular' => __( 'Popular', APP_TD ),
						'most_comments' => __( 'Most Comments', APP_TD ),
						'newest' => __( 'Newest First', APP_TD ),
						'title' => __( 'Alphabetical', APP_TD ),
						'recently_discussed' => __( 'Recently Discussed', APP_TD ),
						'rand' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'The default search result sorting method when search is made with a location entered in search.', APP_TD ),
				),
			)
		);

		$page->tab_sections['events']['appearance'] = array(
			'title' => __( 'Appearance', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Events Per Page', APP_TD ),
					'type' => 'text',
					'name' => 'events_per_page',
					'extra' => array( 'size' => 2 ),
					'tip' => __( 'How many events per page do you want shown?', APP_TD ),
				),
				array(
					'title' => __( 'Default Event Sort Method', APP_TD ),
					'name' => 'default_event_sort',
					'type' => 'select',
					'values' => array(
						'default' => __( 'Default', APP_TD ),
						'event_date' => __( 'Event Date', APP_TD ),
						'popular' => __( 'Popular', APP_TD ),
						'most_comments' => __( 'Most Comments', APP_TD ),
						'newest' => __( 'Newest First', APP_TD ),
						'title' => __( 'Alphabetical', APP_TD ),
						'recently_discussed' => __( 'Recently Discussed', APP_TD ),
						'rand' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'The default sorting method for event lists.', APP_TD ),
				),
				array(
					'title' => __( 'Home Event Sort Method', APP_TD ),
					'name' => 'default_event_home_sort',
					'type' => 'select',
					'values' => array(
						'default' => __( 'Default', APP_TD ),
						'event_date' => __( 'Event Date', APP_TD ),
						'popular' => __( 'Popular', APP_TD ),
						'most_comments' => __( 'Most Comments', APP_TD ),
						'newest' => __( 'Newest First', APP_TD ),
						'title' => __( 'Alphabetical', APP_TD ),
						'recently_discussed' => __( 'Recently Discussed', APP_TD ),
						'rand' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'The sorting method for events on the home page and the events home.', APP_TD ),
				),
				array(
					'title' => __( 'Featured Event Sort Method', APP_TD ),
					'type' => 'select',
					'name' => 'events_featured_sort',
					'values' => array(
						'newest' => __( 'Newest First', APP_TD ),
						'oldest' => __( 'Oldest First', APP_TD ),
						'random' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'How do you want to sort the Featured events that are displayed?', APP_TD ),
				),				
			),
		);

		$page->tab_sections['events']['integration'] = array(
			'title' => __( 'Integration', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Display ShareThis on event lists', APP_TD ),
					'type' => 'checkbox',
					'desc' => __( 'Yes', APP_TD ),
					'name' => 'event_sharethis',
					'extra' => ( ! function_exists ( 'sharethis_button' ) ? array ( 'disabled' => 'disabled' ) : '' ),
					'tip' => sprintf( __( 'If you have the <a href="%1$s" target="_blank">ShareThis</a> plugin instaled it will be only visible on single events. This option enables you to display it on the events list views also.', APP_TD ) , 'http://wordpress.org/extend/plugins/share-this/' ),
				),
			),
		);

		$page->tab_sections['events']['item'] = array(
			'title' => __( 'Pricing', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Payments Settings', APP_TD ),
					'name' => '_blank',
					'type' => '',
					'desc' => sprintf( __( 'Set your <a href="%s">Pricing</a> settings in the Payments Menu.', APP_TD ), 'admin.php?page=app-payments-settings&tab=events' ),
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip' => __( 'Manage Payments Settings including featured pricing and duration', APP_TD ),
				),
			),
		);

	}

}