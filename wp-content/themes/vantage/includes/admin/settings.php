<?php

class VA_Settings_Admin extends APP_Tabs_Page {

	protected $permalink_sections;
	protected $permalink_options;

	function setup() {

		$this->textdomain = APP_TD;

		$this->args = array(
			'page_title' => __( 'Vantage Settings', APP_TD ),
			'menu_title' => __( 'Settings', APP_TD ),
			'page_slug' => 'app-settings',
			'parent' => 'app-dashboard',
			'screen_icon' => 'options-general',
			'admin_action_priority' => 10,
		);

		add_action( 'admin_notices', array( $this, 'admin_tools' ) );
	}

	public function admin_tools() {
		global $va_options;

		if ( isset( $_GET['prune'] ) && $_GET['prune'] == 1 && isset( $_GET['tab'] ) && $_GET['tab'] == 'listings' ) {
			va_prune_expired_listings();
			va_prune_expired_featured();
			echo scb_admin_notice( __( 'Expired listings have been pruned.', APP_TD ) );
		}

		if ( isset( $_GET['va_user_roles_ignore'] ) ) {
			$va_options->default_user_role_update_1_3_2 = true;
		}
	}

	protected function init_tabs() {
		// Remove unwanted query args from urls
		$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'firstrun', 'enabled', 'prune', 'va_user_roles_ignore' ), $_SERVER['REQUEST_URI'] );

		$this->tabs->add( 'general', __( 'General', APP_TD ) );
		$this->tabs->add( 'listings', __( 'Listings', APP_TD ) );

		$this->tab_sections['general']['appearance'] = array(
			'title' => __( 'Appearance', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Theme Customizer', APP_TD ),
					'desc' => sprintf( __( '<a href="%s">Customize Vantage</a> design and settings and see the results real-time without opening or refreshing a new browser window.' , APP_TD), 'customize.php' ),
					'type' => 'text',
					'name' => '_blank',
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip' => __( 'Use the WordPress Theme Customizer to try out different design optoins and other Vantage settings.' ),

				),
				array(
					'title' => __( 'Theme Color', APP_TD ),
					'type' => 'select',
					'name' => 'color',
					'values' => _va_get_color_choices(),
					'tip' => __( 'Choose the overall theme color.', APP_TD ),
				),
				array(
					'title' => __( 'Header Image', APP_TD ),
					'desc' => sprintf( __( 'Set Your Header Image in the <a href="%s">Header</a> settings.', APP_TD ),
						 'themes.php?page=custom-header' ),
					'type' => 'text',
					'name' => '_blank',
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip' => __( 'This is where you can upload/manage your logo that appears in your site\'s header along with settings to control the text below the logo.', APP_TD ),
				),
			),
		);

		$this->tab_sections['listings'][] = array(
			'fields' => array(
				array(
					'title' => __( 'Charge for Listings', APP_TD ),
					'name' => 'listing_charge',
					'type' => 'checkbox',
					'desc' => __( 'Yes', APP_TD ),
					'tip' => sprintf( __( 'Do you want to charge for creating a listing on your site? You can manage your <a href="%s">Payments Settings</a> in the Payments Menu.', APP_TD ), 'admin.php?page=app-payments-settings'),
				),
				array(
					'title' => __( 'Moderate Listings', APP_TD ),
					'type' => 'checkbox',
					'name' => 'moderate_listings',
					'desc' => __( 'Yes', APP_TD ),
					'tip' => __( 'Do you want to moderate new listings before they are displayed live?', APP_TD ),
				),
				array(
					'title' => __( 'Moderate Claimed Listings', APP_TD ),
					'type' => 'checkbox',
					'name' => 'moderate_claimed_listings',
					'desc' => __( 'Yes', APP_TD ),
					'tip' => __( 'Do you want to moderate listing claims before they are transfered to the requesting claimee?', APP_TD ),
				),
			)
		);

		$this->tab_sections['listings']['search'] = array(
			'title' => __( 'Search', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Default Search Result Sort', APP_TD ),
					'name' => 'default_search_sort',
					'type' => 'select',
					'values' => array(
						'default' => __( 'Default', APP_TD ),
						'highest_rating' => __( 'Highest Rating', APP_TD ),
						'most_ratings' => __( 'Most Ratings', APP_TD ),
						'title' => __( 'Alphabetical', APP_TD ),
						'newest' => __( 'Newest', APP_TD ),
						'recently_reviewed' => __( 'Recently Reviewed', APP_TD ),
						'rand' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'The default search result sorting method when search is made without a location entered in search.', APP_TD ),
				),
				array(
					'title' => __( 'Default Location Based Search Result Sort', APP_TD ),
					'name' => 'default_geo_search_sort',
					'type' => 'select',
					'values' => array(
						'distance' => __( 'Closest Distance First', APP_TD ),
						'default' => __( 'Default', APP_TD ),
						'highest_rating' => __( 'Highest Rating', APP_TD ),
						'most_ratings' => __( 'Most Ratings', APP_TD ),
						'title' => __( 'Alphabetical', APP_TD ),
						'newest' => __( 'Newest', APP_TD ),
						'recently_reviewed' => __( 'Recently Reviewed', APP_TD ),
						'rand' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'The default search result sorting method when search is made with a location entered in search.', APP_TD ),
				),
			)
		);

		$this->tab_sections['listings']['appearance'] = array(
			'title' => __( 'Appearance', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Listings Per Page', APP_TD ),
					'type' => 'text',
					'name' => 'listings_per_page',
					'extra' => array( 'size' => 2 ),
					'tip' => __( 'How many listings per page do you want shown?', APP_TD ),
				),
				array(
					'title' => __( 'Default Listing Sort Method', APP_TD ),
					'name' => 'default_listing_sort',
					'type' => 'select',
					'values' => array(
						'default' => __( 'Default', APP_TD ),
						'highest_rating' => __( 'Highest Rating', APP_TD ),
						'most_ratings' => __( 'Most Ratings', APP_TD ),
						'title' => __( 'Alphabetical', APP_TD ),
						'newest' => __( 'Newest', APP_TD ),
						'recently_reviewed' => __( 'Recently Reviewed', APP_TD ),
						'rand' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'The default sorting method for listing lists.', APP_TD ),
				),
				array(
					'title' => __( 'Home Listing Sort Method', APP_TD ),
					'name' => 'default_listing_home_sort',
					'type' => 'select',
					'values' => array(
						'default' => __( 'Default', APP_TD ),
						'highest_rating' => __( 'Highest Rating', APP_TD ),
						'most_ratings' => __( 'Most Ratings', APP_TD ),
						'title' => __( 'Alphabetical', APP_TD ),
						'newest' => __( 'Newest', APP_TD ),
						'recently_reviewed' => __( 'Recently Reviewed', APP_TD ),
						'rand' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'The sorting method for listings on the home page and the listings home.', APP_TD ),
				),
				array(
					'title' => __( 'Featured Listing Sort Method', APP_TD ),
					'type' => 'select',
					'name' => 'listings_featured_sort',
					'values' => array(
						'newest' => __( 'Newest First', APP_TD ),
						'oldest' => __( 'Oldest First', APP_TD ),
						'random' => __( 'Random', APP_TD ),
					),
					'tip' => __( 'How do you want to sort the Featured listings that are displayed?', APP_TD ),
				),
			),
		);

		$this->tab_sections['listings']['integration'] = array(
			'title' => __( 'Integration', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Display ShareThis on Listing Lists', APP_TD ),
					'type' => 'checkbox',
					'desc' => __( 'Yes', APP_TD ),
					'name' => 'listing_sharethis',
					'extra' => ( ! function_exists ( 'sharethis_button' ) ? array ( 'disabled' => 'disabled' ) : '' ),
					'tip' => sprintf( __( 'If you have the <a href="%1$s" target="_blank">ShareThis</a> plugin instaled it will be only visible on single listings. This option enables you to display it on the listings list views also.', APP_TD ) , 'http://wordpress.org/extend/plugins/share-this/' ),
				),
				array(
					'title' => __( 'Display ShareThis on Blog Posts', APP_TD ),
					'type' => 'checkbox',
					'desc' => __( 'Yes', APP_TD ),
					'name' => 'blog_post_sharethis',
					'extra' => ( ! function_exists ( 'sharethis_button' ) ? array ( 'disabled' => 'disabled' ) : '' ),
					'tip' => sprintf( __( 'If you have the <a href="%1$s" target="_blank">ShareThis</a> plugin instaled it will be only visible on single listings. This option enables you to display it on single blog posts also.', APP_TD ) , 'http://wordpress.org/extend/plugins/share-this/' ),
				),
			),

		);

		$this->tab_sections['listings']['item'] = array(
			'title' => __( 'Pricing', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Listing Plans', APP_TD ),
					'name' => '_blank',
					'type' => '',
					'desc' => sprintf( __( 'Set your <a href="%s">Listing Plans</a> in the Payments Menu.', APP_TD ), 'edit.php?post_type=pricing-plan' ),
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip' => __( 'Manage your Listing Plans, which are packages of pricing and feature options that are offered.', APP_TD ),
				),
				array(
					'title' => __( 'Payments Settings', APP_TD ),
					'name' => '_blank',
					'type' => '',
					'desc' => sprintf( __( 'Set your default <a href="%s">Featured Pricing</a> and Payment Gateway settings in the Payments Menu.', APP_TD ), 'admin.php?page=app-payments-settings#featured-pricing' ),
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip' => __( 'Manage default Payments Settings including featured pricing and duration, enable/disable available payment gateways, and manage individual payment gateway\'s settings.', APP_TD ),
				),

			array(
					'title' => __( 'Expired Listings', APP_TD ),
					'name' => '_blank',
					'type' => '',
					'desc' => sprintf( __( 'Prune  <a href="%s">Expired Listings</a> now.', APP_TD ), 'admin.php?page=app-settings&tab=listings&prune=1' ),
					'extra' => array(
						'style' => 'display: none;'
					),
					'tip' => __( 'Run expired listings check.', APP_TD ),
				),
			),
		);

		$this->tab_sections['general']['permalinks'] = array(
			'title' => __( 'Permalinks', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Manage', APP_TD ),
					'desc' => sprintf( __( 'Manage <a href="%s">Vantage Permalinks</a>.', APP_TD ), 'options-permalink.php' ),
					'type' => 'text',
					'name' => '_blank',
					'extra' => array(
						'style' => 'display: none;',
					),
					'tip' => __( 'Manage Vantage\'s permalinks settings for listings, listing categories, listing tags, dashboard pages, etc.', APP_TD )
				),
			),
		);


		$this->tab_sections['general']['category_menu_options'] = array(
			'title' => __( 'Categories Menu Item Options', APP_TD ),
			'fields' => $this->categories_options( 'categories_menu' )
		);

		$this->tab_sections['general']['category_dir_options'] = array(
			'title' => __( 'Categories Page Options', APP_TD ),
			'fields' => $this->categories_options( 'categories_dir' )
		);

		$this->tab_sections['general']['security'] = array(
			'title' => __( 'Security Settings', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Back Office Access', APP_TD ),
					'desc' => '<br />' . sprintf( __( "View the WordPress <a target='_new' href='%s'>Roles and Capabilities</a> for more information.", APP_TD ), 'http://codex.wordpress.org/Roles_and_Capabilities' ),
					'type' => 'select',
					'name' => 'admin_security',
					'values' => array(
						'manage_options' => __( 'Admins Only', APP_TD ),
						'edit_others_posts' => __( 'Admins, Editors', APP_TD ),
						'publish_posts' => __( 'Admins, Editors, Authors', APP_TD ),
						'edit_posts' => __( 'Admins, Editors, Authors, Contributors', APP_TD ),
						'read' => __( 'All Access', APP_TD ),
						'disable' => __( 'Disable', APP_TD ),
					),
					'tip' => __( 'Allows you to restrict access to the WordPress Back Office (wp-admin) by specific role. Keeping this set to admins only is recommended. Select Disable if you have problems with this feature.', APP_TD ),
				),
			),
		);

	}

	private function categories_options( $prefix ) {
		return array(
			array(
				'title' => __( 'Show Category Count', APP_TD ),
				'type' => 'checkbox',
				'name' => array( $prefix, 'count' ),
				'desc' => __( 'Yes', APP_TD ),
				'tip' => __( 'Display the quantity of posts in that category next to the category name?', APP_TD ),
			),
			array(
				'title' => __( 'Hide Empty Sub-Categories', APP_TD ),
				'type' => 'checkbox',
				'name' => array( $prefix, 'hide_empty' ),
				'desc' => __( 'Yes', APP_TD ),
				'tip' => __( 'If a category had no listings, should it be hidden?', APP_TD ),
			),
			array(
				'title' => __( 'Category Depth', APP_TD ),
				'type' => 'select',
				'name' => array( $prefix, 'depth' ),
				'values' => array(
					'999' => __( 'Show All', APP_TD ),
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
				),
				'tip' => __( 'How many levels deep should the category tree traverse?', APP_TD ),
			),
			array(
				'title' => __( 'Number of Sub-Categories', APP_TD ),
				'type' => 'select',
				'name' => array( $prefix, 'sub_num' ),
				'values' => array(
					'999' => __( 'Show All', APP_TD ),
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
					'9' => '9',
					'10' => '10',
				),
				'tip' => __( 'How many sub-categories of each parent category should be shown?', APP_TD ),
			),
		);
	}

	function init_integrated_options() {

		// display additional section on the permalinks page
		$this->permalink_sections();

	}

	function permalink_sections() {

		$option_page = 'permalink';
		$new_section = 'va_options';	// store permalink options on global 'va_options'

		$this->permalink_sections = array(
			'listings' 		=> __( 'Vantage Custom Post Type & Taxonomy URLs', APP_TD ),
			'actions' 		=> __( 'Vantage Actions URLs', APP_TD ),
			'dashboard' 	=> __( 'Vantage Dashboard URLs', APP_TD )
		);

		if ( current_theme_supports( 'app-events' ) ) {
			$this->permalink_sections['events'] = __( 'Vantage Event Post Type & Taxonomy URLs', APP_TD );
			$this->permalink_sections['dashboard_events'] = __( 'Vantage Event Dashboard URLs', APP_TD );
		}

		$this->permalink_options['listings'] = array (
			'listing_permalink' 	  		=> __('Listing Base URL',APP_TD),
			'listing_cat_permalink'	  		=> __('Listing Category Base URL',APP_TD),
			'listing_tag_permalink'   		=> __('Listing Tag Base URL',APP_TD),
		);

		if ( current_theme_supports( 'app-events' ) ) {
			$this->permalink_options['events'] = array (
				'event_permalink' 	  		=> __('Event Base URL',APP_TD),
				'event_cat_permalink'	  		=> __('Event Category Base URL',APP_TD),
				'event_tag_permalink'   		=> __('Event Tag Base URL',APP_TD),
				'event_day_permalink'   		=> __('Event Day Base URL',APP_TD),
			);
		}

		$this->permalink_options['actions'] = array (
			'edit_listing_permalink'  		=> __('Edit Listing Base URL',APP_TD),
			'renew_listing_permalink'  		=> __('Renew Listing Base URL',APP_TD),
			'claim_listing_permalink' 		=> __('Claim Listing Base URL',APP_TD),
			'purchase_listing_permalink'	=> __('Purchase Listing Base URL',APP_TD),
		);

		$this->permalink_options['dashboard'] = array (
			'dashboard_permalink'  		 	=> __('Dashboard Base URL',APP_TD),
			'dashboard_listings_permalink' 	=> __('Dashboard Listing Base URL',APP_TD),
			'dashboard_claimed_permalink' 	=> __('Dashboard Claimed Listings Base URL',APP_TD),
			'dashboard_reviews_permalink' 	=> __('Dashboard Reviews Base URL',APP_TD),
			'dashboard_faves_permalink' 	=> __('Dashboard Favorites Base URL',APP_TD),
		);

		if ( current_theme_supports( 'app-events' ) ) {
			$this->permalink_options['dashboard_events'] = array (
				'dashboard_events_permalink' 	=> __('Dashboard Event Base URL',APP_TD),
				'dashboard_events_attending_permalink' 	=> __('Dashboard Events Attending Base URL',APP_TD),
				'dashboard_event_comments_permalink' 	=> __('Dashboard Event Comments Base URL',APP_TD),
				'dashboard_event_favorites_permalink' 	=> __('Dashboard Event Favorites Base URL',APP_TD),
			);
		}

		register_setting(
			$option_page,
			$new_section,
			array( $this, 'permalink_options_validate')
		);

		foreach ( $this->permalink_sections as $section => $title ) {

			add_settings_section(
				$section,
				$title,
				'__return_false',
				$option_page
			);

			foreach ( $this->permalink_options[$section] as $id => $title ) {

				add_settings_field(
					$new_section.'_'.$id,
					$title,
					array( $this, 'permalink_section_add_option'), 	// callback to output the new options
					$option_page,						   			// options page
					$section,						   				// section
					array( 'id' => $id )							// callback args [ database option, option id ]
				);

			}

		}
	}

	function permalink_section_add_option( $option ) {
		global $va_options;

		echo scbForms::input( array(
			'type'  => 'text',
			'name'  => 'va_options['.$option['id'].']',
			'extra' => array( 'size' => 53 ),
			'value'	=> $va_options->$option['id']
		) );

	}

	// validate/sanitize permalinks
	function permalink_options_validate( $input ) {
		global $va_options;

		$error_html_id = '';

		foreach ( $this->permalink_sections as $section => $title ) {

			foreach ( $this->permalink_options[$section] as $key => $value) {

				if ( empty($input[$key]) ) {
					$error_html_id = $key;
					// set option to previous value
					$input[$key] = $va_options->$key;
				} else {
					if ( !is_array($input[$key]) ) $input[$key] = trim($input[$key]);
					$input[$key] = stripslashes_deep($input[$key]);
				}

			}
		}

		if( $error_html_id ) {

			add_settings_error(
				'va_options',
				$error_html_id,
				__('Vantage custom post type and taxonomy URLs cannot be empty. Empty options will default to previous value.', APP_TD),
				'error'
			);

		}

		return $input;

	}

	function before_rendering_field( $field ) {
		if ( in_array( $field['name'], array( 'listing_price', 'featured_home_price', 'featured_cat_price' ) ) )
			$field['desc'] = APP_Currencies::get_current_symbol();

		if ( 'color' == $field['name'] && apply_filters( 'va_disable_color_stylesheet', false ) ) {
			$field['extra'] = array( 'disabled', true );
			$field['desc'] = '(' . __( 'chosen by child theme', APP_TD ) . ')';
		}

		return $field;
	}

}
