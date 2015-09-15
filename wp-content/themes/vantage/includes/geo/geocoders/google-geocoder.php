<?php

/**
 * Geocoder using Google Maps API v3
 */
class APP_Google_Geocoder extends APP_Geocoder {

	private $api_url = 'http://maps.googleapis.com/maps/api/geocode/json';

	/**
	 * Sets up the gateway
	 */
	public function __construct() {
		parent::__construct( 'google', array(
			'dropdown' => __( 'Google', APP_TD ),
			'admin' => __( 'Google', APP_TD )
		) );
	}

	public function has_required_vars() {

		if ( empty( $this->options['geo_region'] ) && empty( $_POST['geocoder_settings']['google']['geo_region'] ) ) {
			return __( 'Region', APP_TD );
		}

		if ( empty( $this->options['geo_language'] ) && empty( $_POST['geocoder_settings']['google']['geo_language'] ) ) {
			return __( 'Language', APP_TD );
		}

		if ( empty( $this->options['geo_unit'] ) && empty( $_POST['geocoder_settings']['google']['geo_unit'] ) ) {
			return __( 'Unit', APP_TD );
		}

		return true;
	}

	public function geocode_address( $address ) {
		$args = array(
			'address' => urlencode( $address )
		);

		return $this->geocode_api( $args );
	}

	public function geocode_lat_lng( $lat, $lng ) {
		$args = array(
			'latlng' => (float) $lat . ',' . (float) $lng,
		);

		return $this->geocode_api( $args );
	}

	public function geocode_api( $args ) {

		$defaults = array(
			'geo_region' => 'US',
			'geo_language' => 'en',
			'geo_unit' => 'mi',
			'api_key' => '',
		);

		$options = wp_parse_args( $this->options, $defaults );

		$params = array(
			'sensor' => 'false',
			'region' => $options['geo_region'],
			'language' => $options['geo_language'],
		);

		$args = wp_parse_args( $args, $params );

		$api_url = add_query_arg( $args, $this->api_url );
		if ( ! empty( $options['geo_client_id'] ) && ! empty( $options['geo_private_key'] ) ) {
			$api_url = $this->sign_url( $api_url );
		} else if ( ! empty( $options['api_key'] ) ) {
			// calls with key specified needs to be made via SSL url
			$api_url = str_ireplace( 'http://', 'https://', $api_url );
			$api_url = add_query_arg( array( 'key' => $options['api_key'] ), $api_url );
		}

		$response = wp_remote_get( $api_url );

		if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		$this->geocode_results = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! $this->geocode_results || 'OK' != $this->geocode_results['status'] ) {
			return false;
		}

		$this->process_geocode();
	}

	public function set_response_code() {
		if ( isset( $this->geocode_results['status'] ) ) {
			$this->_set_response_code( $this->geocode_results['status'] );
		}
	}

	public function set_bounds() {

		if ( isset( $this->geocode_results['results'][0]['geometry'] ) ) {

			$geometry = $this->geocode_results['results'][0]['geometry'];

			// bounds are not always returned, so fall back to viewport
			$bounds_type = isset( $geometry['bounds'] ) ? 'bounds' : 'viewport';

			$this->_set_bounds(
				$geometry[ $bounds_type ]['northeast']['lat'],
				$geometry[ $bounds_type ]['northeast']['lng'],
				$geometry[ $bounds_type ]['southwest']['lat'],
				$geometry[ $bounds_type ]['southwest']['lng']
			);
		}
	}

	public function set_coords() {

		if ( isset( $this->geocode_results['results'][0]['geometry']['location'] ) ) {
			$point = $this->geocode_results['results'][0]['geometry']['location'];

			$this->_set_coords( $point['lat'], $point['lng'] );
		}
	}

	public function set_address() {
		if ( isset( $this->geocode_results['results'][0]['formatted_address'] ) ) {
			$formatted_address = $this->geocode_results['results'][0]['formatted_address'];
			$this->_set_address( $formatted_address );
		}
	}

	/**
	 * Signs a URL with a cryptographic key for Google Business.
	 * URL Signing Debugger: https://m4b-url-signer.appspot.com/
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	public function sign_url( $url ) {
		if ( ! function_exists( 'hash_hmac' ) ) {
			return $url;
		}

		$url = add_query_arg( 'client', $this->options['geo_client_id'], $url );

		$parsed_url = parse_url( $url );
		$url_to_sign = $parsed_url['path'] . '?' . $parsed_url['query'];

		// Decode the private key into its binary format
		$decoded_key = base64_decode( str_replace( array( '-', '_' ), array( '+', '/' ), $this->options['geo_private_key'] ) );

		// Create a signature using the private key and the URL-encoded
		// string using HMAC SHA1. This signature will be binary.
		$signature = hash_hmac( 'sha1', $url_to_sign, $decoded_key, true );
		$encoded_signature = str_replace( array( '+', '/' ), array( '-', '_' ), base64_encode( $signature ) );

		// Note: add_query_arg() malformed signature
		return $url . '&signature=' . $encoded_signature;
	}

	public function form() {

		$settings = array(
			array(
				'title' => __( 'General Information', APP_TD ),
				'fields' => array(
					array(
						'title' => __( 'Region Biasing', APP_TD ),
						'desc' => sprintf( __( 'Find your two-letter ccTLD region code <a href="%s" target="_blank">here</a>.', APP_TD ), 'http://en.wikipedia.org/wiki/List_of_Internet_top-level_domains#Country_code_top-level_domains' ),
						'type' => 'text',
						'name' => 'geo_region',
						'extra' => array( 'size' => 2 ),
						'tip' => __( "When a user enters 'Florence' in the location search field, you can let Google know that they probably meant 'Florence, Italy' rather than 'Florence, Alabama'.", APP_TD ),
					),
					array(
						'title' => __( 'Language', APP_TD ),
						'desc' => sprintf( __( 'Find your two-letter language code <a href="%s" target="_blank">here</a>.', APP_TD ), 'https://spreadsheets.google.com/pub?key=p9pdwsai2hDMsLkXsoM05KQ&gid=1' ),
						'type' => 'text',
						'name' => 'geo_language',
						'extra' => array( 'size' => 2 ),
						'tip' => __( 'Used to let Google know to use this language in the formatting of addresses and for the map controls.', APP_TD ),
					),
					array(
						'title' => __( 'Distance Unit', APP_TD ),
						'type' => 'radio',
						'name' => 'geo_unit',
						'values' => array(
							'km' => __( 'Kilometers', APP_TD ),
							'mi' => __( 'Miles', APP_TD ),
						),
						'tip' => __( 'Use Kilometers or Miles for your site\'s unit of measure for distances.', APP_TD ),
					),
					array(
						'title' => __( 'API Key', APP_TD ),
						'desc' => sprintf( __( 'Instruction on how to <a href="%s" target="_blank">Obtain an API Key</a>.', APP_TD ), 'https://developers.google.com/maps/documentation/geocoding/index#api_key' ),
						'type' => 'text',
						'name' => 'api_key',
						'tip' => __( 'Enter your "Geocoding API" (Key for server apps) API key. This field is optional.', APP_TD ),
					),
				)
			),
			array(
				'title' => __( 'Business Geocoding', APP_TD ),
				'fields' => array(
					array(
						'title' => __( 'Client ID', APP_TD ),
						'desc' => __( 'Client IDs begin with a "gme-" prefix.', APP_TD ),
						'type' => 'text',
						'name' => 'geo_client_id',
						'tip' => __( 'Your client ID identifies you as a Maps API for Business customer and enables support and purchased quota for your application.', APP_TD ),
					),
					array(
						'title' => __( 'Private key', APP_TD ),
						'type' => 'text',
						'name' => 'geo_private_key',
						'tip' => __( 'Your cryptographic URL-signing key will be issued with your client ID and is a "secret shared key" between you and Google.', APP_TD ),
					),
				)
			),
		);


		return $settings;
	}
}

appthemes_register_geocoder( 'APP_Google_Geocoder' );
