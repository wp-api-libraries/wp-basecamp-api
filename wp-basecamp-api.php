<?php
/**
 * WP Basecamp API (https://github.com/basecamp/bc3-api)
 *
 * @package WP-Basecamp-API
 */

/*
* Plugin Name: WP Basecamp API
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

include_once( 'wp-api-libraries-base.php' );

/* Check if class exists. */
if ( ! class_exists( 'BasecampAPI' ) ) {

	/**
	 * BasecampAPI class.
	 */
	class BasecampAPI extends BasecampAPIBase {

		/**
		 * Access Token.
		 *
		 * @var string
		 */
		private $access_token;

		/**
		 * Account ID.
		 *
		 * @var string
		 */
		private $account_id;

		/**
		 * BaseAPI Endpoint
		 *
		 * @var string
		 * @access protected
		 */
		protected $base_uri = 'https://3.basecampapi.com/';

		/**
		 * __construct function.
		 *
		 * @access public
		 * @param mixed $account_id
		 * @param mixed $access_token
		 * @return void
		 */
		public function __construct( $account_id, $access_token ){
			$this->account_id   = $account_id;
			$this->base_uri    .= $account_id . '/';
			$this->access_token = $access_token;
		}

		/**
		 * set_headers function.
		 *
		 * @access protected
		 * @return void
		 */
		protected function set_headers(){
			$this->args['headers'] = array(
				'Authorization' => 'Bearer ' . $this->access_token,
				'Content-Type' => 'application/json',
			);
		}

		/**
		 * Private wrapper function (for simpler coding), prepares the request then fetches it.
		 *
		 * @param  [type] $route  [description]
		 * @param  array  $body   [description]
		 * @param  bool   $method The method of the request.
		 * @return [type]         [description]
		 */
		private function run( $route, $body = array(), $method = 'GET' ){
			return $this->build_request( $route . '.json', $body, $method )->fetch();
		}

		/**
		 * Returns a list of currently authenticated users.
		 *
		 * @return object The response.
		 */
		public function check_authentication(){
			$this->build_request( 'authorization.json' );

			$this->base_uri = 'https://launchpad.37signals.com/';

			$response = $this->fetch();

			$this->base_uri = 'https://3.basecampapi.com/';

			return $response;
		}


		/* PROJECTS. */

		public function get_people() {
			return $this->run( 'people' );
		}

		/* BASECAMPS. */

		/* CAMPFIRES. */

		/* CHATBOTS. */

	}

}
