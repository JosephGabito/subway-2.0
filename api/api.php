<?php

class Subway_Membership_Route extends WP_REST_Controller 
{

	public function register_routes()
	{
		$version = 1;
		$namespace = 'subway/v'.$version;
		$base = 'membership';

		// New Product.
		register_rest_route( $namespace, '/' . $base . '/new-product', array(
			array(
		        'methods'             => WP_REST_Server::CREATABLE,
		        'callback'            => array( $this, 'add_product' ),
		        'permission_callback' => array( $this, 'permission_check' ),
		        'args'                => $this->get_endpoint_args_for_item_schema( false ),
		    ),
		));

		// Edit Product.
		register_rest_route( $namespace, '/' . $base . '/update-product', array(
			array(
		        'methods'             => WP_REST_Server::CREATABLE,
		        'callback'            => array( $this, 'update_product' ),
		        'permission_callback' => array( $this, 'permission_check' ),
		        'args'                => $this->get_endpoint_args_for_item_schema( false ),
		    ),
		));
		
	}

	public function add_product( $request )
	{
		global $wpdb;

		$title = $request->get_param('title');
		$desc = $request->get_param('description');
		$table = $this->get_table_name();

		$data = array(
				'name' => $title, 
				'description' => $desc
			);

		if ( empty( $title ) )
		{
			return new WP_REST_Response( 
				array(
					'is_error' => true,
					'message' => 'Title is required.'
				), 200
			);
		}

		$format = array('%s', '%s');

		$inserted = $wpdb->insert( $table, $data, $format );

		if ( $inserted ) 
		{
			// Update the total membership count.
			$current_total = get_option('subway_products_count', 0);
			update_option( 'subway_products_count', absint( $current_total ) + 1 );
		}

		return new WP_REST_Response( 
			array(
				'is_error' => false,
				'message' => 'Successfully added new product',
				'data' => array(
						'title' => $title,
						'description' => $desc
					)
			), 200 );
	}

	public function update_product( $request )
	{

		require_once SUBWAY_DIR_PATH . '/classes/subway-membership.php';

		$id = $request->get_param('id');
		$title = $request->get_param('title');
		$desc = $request->get_param('description');

		$membership = new Subway_Memberships_Products();

		$membership->update( ['id' => $id, 'title' => $title,'description' => $desc] );

		return new WP_REST_Response( 
			array(
				'is_error' => false,
				'message' => 'Successfully updated product',
				'data' => array(
						'title' => $title,
						'description' => $desc
					)
			), 200 );

	}

	public function permission_check( $request ) {
		// Testing purposes no need permission check for now.
    	return true;
  	}

  	public function get_table_name(){
  		global $wpdb;
  		return $wpdb->prefix . 'memberships_products';
  	}

  	public function register_scripts()
  	{
  		
  		wp_register_script( 'subway-admin-js', SUBWAY_DIR_URL . 'assets/js/admin.js', array('jquery') );

  		wp_register_script( 'subway-membership-add-js', SUBWAY_DIR_URL . 'assets/js/membership-new.js', array('jquery', 'subway-admin-js') );
  		wp_register_script( 'subway-membership-update-js', SUBWAY_DIR_URL . 'assets/js/membership-update.js', array('jquery', 'subway-admin-js') );

  		wp_localize_script( 'subway-admin-js', 'subway_api_settings', array(
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
