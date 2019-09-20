<?php

class Subway_Memberships
{
	public function get_memberships()
	{
		global $wpdb;

		$stmt = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}memberships_products", array());
		
		$results = $wpdb->get_results( $stmt, ARRAY_A );

		return $results;
	}

	public function add_membership()
	{

	}

	public function delete_membership()
	{

	}

	public function update_membership()
	{

	}
}