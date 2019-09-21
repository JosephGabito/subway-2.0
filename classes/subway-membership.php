<?php

class Subway_Memberships_Products
{
	public function get_products()
	{
		global $wpdb;

		$stmt = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}memberships_products", array());

		$results = $wpdb->get_results( $stmt, ARRAY_A );

		return $results;
	}

	public function add()
	{

	}

	public function delete( $product_id )
	{
		global $wpdb;

		$is_deleted = $wpdb->delete( $wpdb->prefix . 'memberships_products', 
			array( 'id' => $product_id ), array( '%d' ) );
		
		return $is_deleted;
	}

	public function update()
	{

	}
}