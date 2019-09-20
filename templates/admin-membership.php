<?php $new = filter_input( INPUT_GET, 'new', FILTER_SANITIZE_STRING );?>

<?php if ( "yes" === $new ): ?>

	<div class="wrap">
		<h1 class="wp-heading-inline">Add New Product</h1>
		<?php require_once SUBWAY_DIR_PATH . 'templates/admins-membership-add-new.php'; ?>
	</div>

<?php else: ?>
	
	<div class="wrap">

		<h1 class="wp-heading-inline">Products</h1>

		<a href="?page=subway-membership&new=yes" class="page-title-action">Add New</a>
		
		<?php global $SubwayListTableMembership; ?>

		<?php $SubwayListTableMembership->prepare_items(); ?>

		<form method="post">
			<input type="hidden" name="page" value="my_list_test" />
			<?php $SubwayListTableMembership->search_box('search', 'search_id'); ?>
		</form>

		<?php $SubwayListTableMembership->display(); ?>

	</div>

<?php endif; ?>
