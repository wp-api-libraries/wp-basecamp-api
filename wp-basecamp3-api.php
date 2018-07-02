<?php
/**
 * Library for accessing the Basecamp 3 API on WordPress
 *
 * @link https://github.com/basecamp/bc3-api API Documentation
 * @package WP-API-Libraries\WP-Basecamp-API
 */

/*
 * Plugin Name: Basecamp 3 API
 * Plugin URI: https://wp-api-libraries.com/
 * Description: Perform API requests.
 * Author: WP API Libraries
 * Version: 1.0.0
 * Author URI: https://wp-api-libraries.com
 * GitHub Plugin URI: https://github.com/basecamp/bc3-api
 * GitHub Branch: master
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Basecamp3API' ) ) {

	/**
	 * A WordPress API library for accessing the Basecamp3 API.
	 *
	 * @version 1.1.0
	 * @link https://github.com/basecamp/bc3-api API Documentation
	 * @package WP-API-Libraries\WP-Basecamp3-API
	 * @author Santiago Garza <https://github.com/sfgarza>
	 * @author imFORZA <https://github.com/imforza>
	 */
	class Basecamp3API {

		/**
		 * API Key.
		 *
		 * @var string
		 */
		static protected $api_key;

		/**
		 * Auth Email
		 *
		 * @var string
		 */
		static protected $auth_email;

		/**
		 * User Service Key
		 *
		 * @var string
		 */
		static protected $account_id;

		/**
		 * CloudFlare BaseAPI Endpoint
		 *
		 * @var string
		 * @access protected
		 */
		protected $base_uri = 'https://3.basecampapi.com/';

		/**
		 * Route being called.
		 *
		 * @var string
		 */
		protected $route = '';


		/**
		 * Class constructor.
		 *
		 * @param string $api_key               Cloudflare API Key.
		 * @param string $auth_email            Email associated to the account.
		 * @param string $user_service_key      User Service key.
		 */
		public function __construct( $api_key, $auth_email, $account_id ) {
			static::$api_key = $api_key;
			static::$auth_email = $auth_email;
			static::$account_id = $account_id;
		}

		/**
		 * Prepares API request.
		 *
		 * @param  string $route   API route to make the call to.
		 * @param  array  $args    Arguments to pass into the API call.
		 * @param  array  $method  HTTP Method to use for request.
		 * @return self            Returns an instance of itself so it can be chained to the fetch method.
		 */
		protected function build_request( $route, $args = array(), $method = 'GET' ) {
			// Start building query.
			$this->set_headers();
			$this->args['method'] = $method;
			$this->route = $route;

			// Generate query string for GET requests.
			if ( 'GET' === $method ) {
				$this->route = add_query_arg( array_filter( $args ), $route );
			} elseif ( 'application/json' === $this->args['headers']['Content-Type'] ) {
				$this->args['body'] = wp_json_encode( $args );
			} else {
				$this->args['body'] = $args;
			}

			return $this;
		}


		/**
		 * Fetch the request from the API.
		 *
		 * @access private
		 * @return array|WP_Error Request results or WP_Error on request failure.
		 */
		protected function fetch() {
			// Make the request.
			$response = wp_remote_request( $this->base_uri . $this->route, $this->args );

			// Retrieve Status code & body.
			$code = wp_remote_retrieve_response_code( $response );
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			$this->clear();
			// Return WP_Error if request is not successful.
			if ( ! $this->is_status_ok( $code ) ) {
				return new WP_Error( 'response-error', sprintf( __( 'Status: %d', 'wp-postmark-api' ), $code ), $body );
			}

			return $body;
		}


		/**
		 * Set request headers.
		 */
		protected function set_headers() {
			// Set request headers.
			$this->args['headers'] = array(
					'Content-Type' => 'application/json',
					'X-Auth-Email' => static::$auth_email,
					'X-Auth-Key' => static::$api_key,
			);
		}

		/**
		 * Clear query data.
		 */
		protected function clear() {
			$this->args = array();
			$this->query_args = array();
		}

		/**
		 * Check if HTTP status code is a success.
		 *
		 * @param  int $code HTTP status code.
		 * @return boolean       True if status is within valid range.
		 */
		protected function is_status_ok( $code ) {
			return ( 200 <= $code && 300 > $code );
		}

		/**
		 * Wrapper for $this->build_request()->fetch();, for brevity.
		 *
		 * @param  string $route  The route to hit.
		 * @param  array  $args   (Default: array()) arguments to pass.
		 * @param  string $method (Default: 'GET') the method of the call.
		 * @return mixed          The results of the call.
		 */
		protected function run( $route, $args = array(), $method = 'GET' ) {
			return $this->build_request( $route, $args, $method )->fetch();
		}

		/* PEOPLE. */

		/**
		 * Get All People.
		 *
		 * @api GET
		 * @see https://github.com/basecamp/bc3-api/blob/master/sections/people.md#get-all-people
		 * @access public
		 * @return array  User information.
		 */
		public function get_all_people() {
			return $this->run( 'people.json' );
		}

		public function get_people_on_projects( $project_id ) {
			return $this->run( 'projects/'.$project_id.'/people.json' );
		}

		public function update_project_access( $project_id) {
			return $this->run( 'projects/'.$project_id.'/people/users.json', '', 'PUT' );
		}

		public function get_pingable_people() {
			return $this->run( 'circles/people.json' );
		}

		public function get_person( $user_id ) {
			return $this->run( 'people/'.$user_id.'.json' );
		}

		public function get_my_profile() {
			return $this->run( 'my/profile.json' );
		}


		/* PROJECTS. */

		public function get_projects() {
			return $this->run( 'projects.json' );
		}

		public function get_project( $project_id ) {
			return $this->run( 'projects/'.$project_id.'.json' );
		}

		public function create_project( $name, $description, $args = array() ) {
			return $this->run( 'projects.json', '', 'POST' );
		}

		public function update_project() {

		}

		public function trash_project() {

		}


		/* MESSAGES. */

		public function get_messages( $project_id, $board_id ) {
			return $this->run( '/buckets/'.$project_id.'/message_boards/'.$board_id.'/messages.json' );
		}

		public function get_message( $project_id, $message_id ) {
			return $this->run( '/buckets/'.$project_id.'/messages/'.$message_id.'.json' );
		}

		public function create_message( $project_id, $board_id ) {
			$args = array();
			return $this->run( '/buckets/'.$project_id.'/message_boards/'.$board_id.'/messages.json', $args, 'POST' );
		}

		public function update_message() {

		}

	}
}
