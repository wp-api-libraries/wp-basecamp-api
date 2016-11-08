<?php
/**
 * WP Basecamp 2 API (https://github.com/basecamp/bcx-api)
 *
 * @package WP-Basecamp2-API
 */

/*
* Plugin Name: WP Basecamp2 API
* Plugin URI: https://github.com/wp-api-libraries/wp-basecamp-api
* Description: Perform API requests to Basecamp in WordPress.
* Author: WP API Libraries
* Version: 1.0.0
* Author URI: https://wp-api-libraries.com
* GitHub Plugin URI: https://github.com/wp-api-libraries/wp-basecamp-api
* GitHub Branch: master
*/

/* Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Check if class exists. */
if ( ! class_exists( 'Basecamp2API' ) ) {

	class Basecamp2API {

		/**
		 * Access Token.
		 *
		 * @var string
		 */
		static private $access_token;

		/**
		 * Account ID.
		 *
		 * @var string
		 */
		static private $account_id;

		/**
		 * BaseAPI Endpoint
		 *
		 * @var string
		 * @access protected
		 */
		protected $base_uri;

		/**
		 * __construct function.
		 *
		 * @access public
		 * @param mixed $account_id
		 * @param mixed $access_token
		 * @return void
		 */
		public function __construct( $account_id, $access_token ){


			$base_uri = 'https://basecamp.com/' . $account_id . '/api/v1/';

		}

		/**
		 * Fetch the request from the API.
		 *
		 * @access private
		 * @param mixed $request Request URL.
		 * @return $body Body.
		 */
		private function fetch( $request ) {

			$response = wp_remote_request( $request, $this->args );

			var_dump($response);

			$code = wp_remote_retrieve_response_code($response );
			if ( 200 !== $code ) {
				return new WP_Error( 'response-error', sprintf( __( 'Server response code: %d', 'text-domain' ), $code ) );
			}
			$body = wp_remote_retrieve_body( $response );
			return json_decode( $body );
		}


		/* PROJECTS. */

		public function get_projects() {

		}

		public function get_draft_projects() {

		}

		public function get_archived_projects() {

		}

		public function get_project( $project_id ) {

		}

		public function create_project( $name, $description ) {

		}

		public function create_project_from_template( $project_template_id ) {

		}

		public function publish_project() {

		}

		public function update_project() {

		}

	}

}
