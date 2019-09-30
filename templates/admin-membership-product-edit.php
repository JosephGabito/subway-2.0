<?php
/**
 * This file contains the html for the product edit when 
 * you click 'edit' in the membership list table.
 *
 * @since  3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

?>
<?php $id = filter_input( INPUT_GET, 'product', FILTER_VALIDATE_INT); ?>

<?php $membership = new Subway_Memberships_Products(); ?>

<?php $product = $membership->get_product( $id ); ?>

<?php if ( empty( $product ) ): ?>

	<?php $error = new WP_Error( 'broke', __( "Error: Product not found", "subway" ) ); ?>
	
	<h3>

		<?php echo $error->get_error_message(); ?>

	</h3>
	
	<?php return; ?>

<?php endif; ?> 

<div id="subway-edit-product-form">
	
	<?php wp_enqueue_script( 'subway-membership-update-js' ); ?>

	<form autocomplete="off" method="POST" action="">
		
		<div>
			<input type="hidden" name="page" value="subway-membership">
			<input type="hidden" name="new" value="yes">
			<input type="hidden" id="input-id" name="product_id" value="<?php echo esc_attr( $product->id ); ?>">
		</div>

		<div>
			
			<h3>
				<label><?php esc_html_e( 'Product Name', 'subway' ); ?></label>
			</h3>
			
			<p><?php esc_html_e('Enter the product name', 'subway'); ?></p>

			<input value="<?php echo esc_attr( $product->name ); ?>" 
				id="input-title" name="title" type="text" class="widefat" 
					placeholder="<?php esc_attr_e('Add Name', 'subway'); ?>" />
		</div>
		
		<div>
			<h3><label><?php esc_html_e('Product Description', 'subway'); ?></label></h3>
			<p>Enter the product description</p>
			<textarea id="input-description" name="description" class="widefat" rows="5" placeholder="<?php echo esc_attr('Product description', 'subway'); ?>"><?php echo esc_html( $product->description ); ?></textarea>
		</div>

		<div>
			<h3><label>Payment Type</label></h3>
			<p>Select a payment type</p>
			<select id="input-payment-type" name="payment_type">
				<option>One Time Payment</option>
				<option>Recurring</option>
			</select>
		</div>
		
		<hr/>

		<div>
			<input id="update-product" type="submit" class="button button-primary button-large" value="Update" />
		</div>

	</form>
</div><!--#subway-edit-product-form-->
