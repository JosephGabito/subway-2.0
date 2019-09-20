<?php

require_once SUBWAY_DIR_PATH . 'classes/subway-membership.php';

class Subway_List_Table_Membership extends WP_List_Table 
{

	var $found_data = array();

	function get_columns()
	{
		$columns = array(
		 	'cb'  => '<input type="checkbox" />',
		    'name' => 'Product Name',
		    'description' => 'Description',
		);
		
		return $columns;
	}

	function prepare_items() 
	{
		$memberships = new Subway_Memberships();
	  	$data = $memberships->get_memberships();

	  	$columns = $this->get_columns();
	 	$hidden = array();
	  	$sortable = $this->get_sortable_columns();
	  	$this->_column_headers = $this->get_column_info();

	  	usort( $data, array( &$this, 'usort_reorder' ) );
  		
  		$per_page = $this->get_items_per_page('membership_per_page', 5);
	  	$current_page = $this->get_pagenum();

	  	$total_items = count( $data );

	  	// only ncessary because we have sample data
	  	$this->found_data = array_slice( $data, ( ( $current_page - 1 ) * $per_page), $per_page );

	  	$this->set_pagination_args( array(
	    	'total_items' => $total_items,                  //WE have to calculate the total number of items
	    	'per_page'    => $per_page                     //WE have to determine how many items to show on a page
	  	) );

	  	$this->items = $this->found_data;
	}

	function get_sortable_columns()
	{
		$sortable_columns = array(
	    	'name'  => array('name',false),
	  	);
	  	return $sortable_columns;
	}

	function usort_reorder( $a, $b ) {
		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
		// If no order, default to asc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		// Determine sort order
		$result = strcmp( $a[$orderby], $b[$orderby] );
		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}


	function column_default( $item, $column_name ) 
	{
	  	switch( $column_name ) { 
	   		case 'name':
	    	case 'description':
	      		return $item[ $column_name ];
	    	default:
	      	return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	  	}
	}

	function column_name($item) {
	  	$actions = array(
	        'edit'      => sprintf('<a href="?page=%s&action=%s&book=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
	        'delete'    => sprintf('<a href="?page=%s&action=%s&book=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
	    );

	  return sprintf('%1$s %2$s', '<a href="#"><strong>'.$item['name'].'</strong></a>', $this->row_actions($actions) );
	}

	function get_bulk_actions() 
	{
		$actions = array(
	    	'delete' => 'Delete'
	  	);
	  	return $actions;
	}

	function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />', $item['ID']
        );    
    }

}

$list_table = new Subway_List_Table_Membership();