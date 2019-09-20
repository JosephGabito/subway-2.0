<div id="subway-new-product-form">
	
	<?php wp_enqueue_script( 'subway-wp-api' ); ?>

	<form autocomplete="off" method="POST" action="http://multisite.local/nibble/wp-json/subway/v1/membership/new-product">
		
		<div>
			<input type="hidden" name="page" value="subway-membership">
			<input type="hidden" name="new" value="yes">
		</div>

		<div>
			<h3><label>Product Title</label></h3>
			<p>Enter the product title</p>
			<input id="input-title" name="title" type="text" class="widefat" placeholder="Add Name">
		</div>
		
		<div>
			<h3><label>Product Description</label></h3>
			<p>Enter the product description</p>
			<textarea id="input-description" name="description" class="widefat" rows="5" placeholder="Product description"></textarea>
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
			<input id="publish-product" type="submit" class="button button-primary button-large" value="Publish Product" />
		</div>

	</form>
</div>
