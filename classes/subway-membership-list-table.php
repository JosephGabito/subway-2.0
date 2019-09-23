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
		
		$memberships = new Subway_Memberships_Products();
		// Process bulk actions.
		$this->process_bulk_action( $memberships );

	  	$columns = $this->get_columns();
	 	$hidden = array();
	  	$sortable = $this->get_sortable_columns();
	  	$this->_column_headers = $this->get_column_info();

  		$per_page = $this->get_items_per_page('products_per_page', 5);
	  	$current_page = $this->get_pagenum();

	  	$order = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_SPECIAL_CHARS );

	  	$offset = 0;
	  	//Manually determine page query offset (offset + current page (minus one) x posts per page).
	  	$page_offset = $offset + ( $current_page - 1 ) * $per_page;

	  	$data = $memberships->get_products( array(
	  		'orderby' => 'name', 
	  		'direction' => $order,
	  		'offset' => $page_offset,
	  		'limit' => $per_page
	  	) );


	  	$total_items = absint( get_option( 'subway_products_count' ) );

	  	$this->set_pagination_args( array(
	    	'total_items' => $total_items,                  //WE have to calculate the total number of items
	    	'per_page'    => $per_page                     //WE have to determine how many items to show on a page
	  	) );

	  	$this->items = $data;

	  	return $this;

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
	    	return;
	      	// return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
	  	}
	}

	function column_name( $item ) {
	  	
	  	$delete_url = wp_nonce_url(
	  		sprintf('?page=%s&action=%s&product=%s', $_REQUEST['page'], 'delete', $item['id']), 
	  		sprintf('trash_product_%s', $item['id']), 
	  		'_wpnonce');

	  	$edit_url = wp_nonce_url(
	  		sprintf('?page=%s&edit=%s&product=%s', $_REQUEST['page'],'yes',$item['id'] ),
	  		sprintf('edit_product_%s', $item['id']),
	  		'_wpnonce'
	  		);

	  	$actions = array(
	        'edit'      => sprintf('<a href="%s">Edit</a>', esc_url( $edit_url ) ),
	        'delete'    => sprintf('<a href="%s">Trash</a>', esc_url( $delete_url ) ),
	    );

	  return sprintf('%1$s %2$s', '<a href="#"><strong>'.$item['name'].'</strong></a>', $this->row_actions($actions) );

	}

	function get_bulk_actions() 
	{
		$actions = array(
	    	'delete' => __('Delete', 'subway')
	  	);
	  	return $actions;
	}

	function process_bulk_action( $membership )
	{


		if ( 'delete' ===  $this->current_action() ) 
		{
			check_admin_referer( 'bulk-' . $this->_args['plural'] );
			$product_ids = filter_input( INPUT_POST, 'product_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			if ( ! empty( $product_ids ) )
			{
				foreach ( $product_ids as $id ) {
					$membership->delete($id);
				}
			}
		}
	}

	function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="product_ids[]" value="%s" />', $item['id']
        );    
    }

}

$list_table = new Subway_List_Table_Membership();