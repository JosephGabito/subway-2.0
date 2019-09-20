<?php
class Subway_List_Table_Membership extends WP_List_Table 
{

	var $example_data = array(
		array('ID' => 1,'membership_name' => 'Starter', 'membership_description' => 'A Starter plan for $49.00/year'),
		array('ID' => 2,'membership_name' => 'Professional', 'membership_description' => 'A Professional plan for $89.00/year'),
		array('ID' => 3,'membership_name' => 'Business', 'membership_description' => 'A Business plan for $149.00/year'),
	);

	var $found_data = array();

	function get_columns()
	{
		 $columns = array(
		 	'cb'  => '<input type="checkbox" />',
		    'membership_name' => 'Product Name',
		    'membership_description' => 'Description',
		  );
		  return $columns;
	}

	function prepare_items() 
	{
	  	$columns = $this->get_columns();
	 	$hidden = array();
	  	$sortable = $this->get_sortable_columns();
	  	$this->_column_headers = $this->get_column_info();

	  	usort( $this->example_data, array( &$this, 'usort_reorder' ) );
  		
  		$per_page = $this->get_items_per_page('membership_per_page', 5);;
	  	$current_page = $this->get_pagenum();

	  	$total_items = count($this->example_data);

	  	// only ncessary because we have sample data
	  	$this->found_data = array_slice( $this->example_data, ( ( $current_page - 1 ) * $per_page), $per_page );

	  	$this->set_pagination_args( array(
	    	'total_items' => $total_items,                  //WE have to calculate the total number of items
	    	'per_page'    => $per_page                     //WE have to determine how many items to show on a page
	  	) );

	  	$this->items = $this->found_data;
	}

	function get_sortable_columns()
	{
		$sortable_columns = array(
	    	'membership_name'  => array('membership_name',false),
	  	);
	  	return $sortable_columns;
	}

	function usort_reorder( $a, $b ) {
		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'membership_name';
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
	   		case 'membership_name':
	    	case 'membership_description':
	      		return $item[ $column_name ];
	    	default:
	      	return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	  	}
	}

	function column_membership_name($item) {
	  	$actions = array(
	        'edit'      => sprintf('<a href="?page=%s&action=%s&book=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
	        'delete'    => sprintf('<a href="?page=%s&action=%s&book=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
	    );

	  return sprintf('%1$s %2$s', '<a href="#"><strong>'.$item['membership_name'].'</strong></a>', $this->row_actions($actions) );
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