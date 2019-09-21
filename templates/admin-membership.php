<?php
/**
 * 
 */
?>
<?php $new = filter_input( INPUT_GET, 'new', FILTER_SANITIZE_STRING );?>
<?php $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );?>
<?php $product_id = filter_input( INPUT_GET, 'product', FILTER_SANITIZE_NUMBER_INT );?>

<?php if ( "delete" === $action ): ?>
	<?php $members_products = new Subway_Memberships_Products(); ?>
	<?php $members_products->delete( $product_id ); ?>
<?php endif; ?>

<?php global $SubwayListTableMembership; ?>

<?php if ( "yes" === $new ): ?>

	<div class="wrap">
		<h1 class="wp-heading-inline">Add New Product</h1>
		<?php require_once SUBWAY_DIR_PATH . 'templates/admins-membership-add-new.php'; ?>
	</div>

<?php else: ?>
	
	<div class="wrap">

		<h1 class="wp-heading-inline">Products</h1>

		<a href="?page=subway-membership&new=yes" class="page-title-action">Add New</a>

		<?php $SubwayListTableMembership->prepare_items(); ?>

		<form method="post">
			<input type="hidden" name="page" value="my_list_test" />
			<?php $SubwayListTableMembership->search_box('search', 'search_id'); ?>
		</form>

		<?php $SubwayListTableMembership->display(); ?>

	</div>

<?php endif; ?>
