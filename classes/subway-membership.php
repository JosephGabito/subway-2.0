<?php
/**
 * 
 */
class Subway_Memberships_Products
{
	var $table = '';

	public function __construct()
	{
		global $wpdb;

		$this->table = $wpdb->prefix . 'memberships_products';
	}

	public function get_products( $args )
	{
		global $wpdb;

		$defaults = array(
				'limit' => 60,
				'offset' => 0,
				'orderby' => 'id',
				'direction' => 'DESC'
			);

		$args = wp_parse_args( $args, $defaults );

		$orderby = $args['orderby'];
		$direction = strtoupper( $args['direction'] );

		$stmt = $wpdb->prepare("SELECT id, name, description FROM $this->table 
			ORDER BY $orderby $direction LIMIT %d, %d", 
			array( $args['offset'], $args['limit'] ) );

		$results = $wpdb->get_results( $stmt, ARRAY_A );
		
		$total = get_option('subway_products_count', 0);

		return $results;

	}

	
	public function get_product( $id )
	{
		global $wpdb;

		$stmt = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}memberships_products 
			WHERE id = %d", absint( $id ) );

		$result = $wpdb->get_row( $stmt, OBJECT );

		return $result;
	}

	public function add()
	{

	}

	public function delete( $product_id )
	{
		global $wpdb;

		$is_deleted = $wpdb->delete( $wpdb->prefix . 'memberships_products', 
			array( 'id' => $product_id ), array( '%d' ) );

		// Update the total membership count.
		if ( $is_deleted )
		{
			$current_total = get_option('subway_products_count', 0);
			
			if ( $current_total !== 0 )
			{
				update_option( 'subway_products_count', absint( $current_total ) - 1 );
			}
		}

		return $is_deleted;
	}

	public function update( $args = array() )
	{
		global $wpdb;
		
		$data = array(
			'name' => $args['title'],
			'description' => $args['description']
		);

		$table = $this->table;

		$where = array( 'id' => $args['id'] );
		$format = array( '%s', '%s');
		$where_format = array( '%d' );

		return $wpdb->update( $table, $data, $where, $format, $where_format );

	}
}