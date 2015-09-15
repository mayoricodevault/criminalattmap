<?php

add_action('admin_init', 'va_events_pricing_setup_settings_tab_init' );

function va_events_pricing_setup_settings_tab_init() {
	global $admin_page_hooks;

	add_action( 'tabs_'.$admin_page_hooks['app-payments'].'_page_app-payments-settings', array( 'VA_Events_Pricing_Settings_Tab', 'init' ) );
	add_action( 'tabs_'.$admin_page_hooks['app-payments'].'_page_app-payments-settings_page_content', array( 'VA_Events_Pricing_Settings_Tab', 'page_content' ), 10 );
}

class VA_Events_Pricing_Settings_Tab {

	private static $page;

	static function page_content( $page ) {
		global $va_options;

		if ( false === $va_options->get('events_enabled') ) {
			$page->tab_sections['events'] = wp_array_slice_assoc( $page->tab_sections['events'], array('events_enabled'));
		} else if( false === $va_options->get('event_charge') ) {
			$page->tab_sections['events'] = wp_array_slice_assoc( $page->tab_sections['events'], array('event_charge'));
		} else {
			unset( $page->tab_sections['events']['events_enabled'] );
			unset( $page->tab_sections['events']['event_charge'] );
		}
	}

	static function init( $page ) {

		self::$page = $page;

		$page->tabs->add_after( 'listings', 'events', __( 'Events', APP_TD ) );

		$page->tab_sections['events']['events_enabled'] = array(
			'fields' => array(
				array(
					'title' => __( 'Events not Enabled', APP_TD ),
					'name' => '_blank',
					'type' => '',
					'desc' => sprintf( __( 'Events are not currently enabled, go to the events settings to <a href="%s">enable events</a> now.', APP_TD ), 'admin.php?page=app-settings&tab=events' ),
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip' => __( 'Events are not currently enabled, you must first enable events on the events settings page.', APP_TD ),
				),
			),
		);

		$page->tab_sections['events']['event_charge'] = array(
			'fields' => array(
				array(
					'title' => __( 'Charge for Events not Enabled', APP_TD ),
					'name' => '_blank',
					'type' => '',
					'desc' => sprintf( __( 'Charging for Events ia not currently enabled, go to the events settings to <a href="%s">enable charging for events</a> now.', APP_TD ), 'admin.php?page=app-settings&tab=events' ),
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip' => __( 'Charging for Events is not currently enabled, you must first enable "Charge for Events" on the events settings page.', APP_TD ),
				),
			),
		);

		$page->tab_sections['events']['pricing'] = array(
			'title' => __( 'Pricing', APP_TD ),
			'fields' => array (
				array(
					'title' => __( 'Price', APP_TD ),
					'type' => 'text',
					'name' => 'event_price',
					'extra' => array( 'size' => 2),
					'sanitize' => 'appthemes_absfloat',
					'desc' => APP_Currencies::get_current_currency('code'),
					'tip' => __( 'The price you are charging for an event.', APP_TD ),
				),
				array(
					'title' => __( 'Enable Featured on Home', APP_TD ),
					'type' => 'checkbox',
					'name' => 'event_featured-home_enabled',
				),
				array(
					'title' => __( 'Featured on Home - Price', APP_TD ),
					'type' => 'text',
					'name' => 'event_featured-home_price',
					'extra' => array( 'size' => 2),
					'sanitize' => 'appthemes_absfloat',
					'desc' => APP_Currencies::get_current_currency('code'),
					'tip' => __( 'The price you are charging to feature an event on the home page.', APP_TD ),
				),
				array(
					'title' => __( 'Enable Featured on Category', APP_TD ),
					'type' => 'checkbox',
					'name' => 'event_featured-cat_enabled',
				),
				array(
					'title' => __( 'Featured on Category - Price', APP_TD ),
					'type' => 'text',
					'name' => 'event_featured-cat_price',
					'extra' => array( 'size' => 2),
					'sanitize' => 'appthemes_absfloat',
					'desc' => APP_Currencies::get_current_currency('code'),
					'tip' => __( 'The price you are charging to feature an event on category pages.', APP_TD ),
				),
				array(
					'title' => __( 'Categories Included', APP_TD ),
					'type' => 'text',
					'name' => 'event_included_categories',
					'desc' => __( ' ( 0 = Infinite )', APP_TD),
					'extra' => array( 'size' => 2 ),
					'sanitize' => 'absint',
					'tip' => __( 'The maximum quantity of categories a user can choose to associate with their event.', APP_TD ),
				),
			),
		);

	}
}	