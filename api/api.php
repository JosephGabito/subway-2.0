<?php

class Subway_Membership_Route extends WP_REST_Controller 
{

	public function register_routes()
	{
		$version = 1;
		$namespace = 'subway/v'.$version;
		$base = 'membership';

		register_rest_route( $namespace, '/' . $base . '/new-product', array(
			array(
		        'methods'             => WP_REST_Server::CREATABLE,
		        'callback'            => array( $this, 'create_item' ),
		        'permission_callback' => array( $this, 'create_item_permissions_check' ),
		        'args'                => $this->get_endpoint_args_for_item_schema( false ),
		    ),
		));

		register_rest_route( $namespace, '/' . $base . '/schema', array(
	      'methods'  => WP_REST_Server::READABLE,
	      'callback' => array( $this, 'get_public_item_schema' ),
	    ) );
	}

	public function create_item( $request )
	{
		global $wpdb;

		$title = $request->get_param('title');
		$desc = $request->get_param('description');
		$table = $this->get_table_name();

		$data = array(
			'name' => $title, 
			'description' => $desc
			);

		$format = array('%s', '%s');

		$inserted = $wpdb->insert( $table, $data, $format );

		return new WP_REST_Response( 
			array(
				'title' => $title,
				'description' => $desc
			), 200 );
	}

	public function create_item_permissions_check( $request ) {
		// Testing purposes no need permission check for now.
    	return true;
  	}

  	public function get_table_name(){
  		global $wpdb;
  		return $wpdb->prefix . 'memberships_products';
  	}

  	public function register_scripts(){
  		wp_register_script( 'subway-wp-api', SUBWAY_DIR_URL . 'assets/js/membership-new.js', array('jquery') );
  		wp_localize_script( 'subway-wp-api', 'subway_api_settings', array(
            'root' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' )
        ) );
        return;
  	}

}

add_action( 'admin_enqueue_scripts', function(){
	$route = new Subway_Membership_Route();
	$route->register_scripts();
});

add_action( 'rest_api_init', function () {
	$route = new Subway_Membership_Route();
	$route->register_routes();
} );
