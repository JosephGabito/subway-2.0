<?php

global $memberships_products_db_version;

$memberships_products_db_version = '1.0';

function memberships_migrate() 
{
   	global $wpdb;
   	global $memberships_products_db_version;

   	$table_name = $wpdb->prefix . "memberships_products"; 

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
	  	name tinytext NOT NULL,
	  	description text NOT NULL,
	  	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  	PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );

	add_option( "memberships_products_db_version", $memberships_products_db_version );
}
