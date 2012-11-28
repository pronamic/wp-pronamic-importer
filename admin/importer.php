<div class="wrap">
	<?php screen_icon( 'pronamic_importer' ); ?>

	<h2>
		<?php _e( 'Database Importer', 'pronamic_importer' ); ?>
	</h2>

	<ul>
		<li>
			<a href="<?php echo add_query_arg( 'view', 'posts' ); ?>">
				<?php _e( 'Posts', 'pronamic_importer' ); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'view', 'users' ); ?>">
				<?php _e( 'Users', 'pronamic_importer' ); ?>
			</a>
		</li>
		<li>
			<a href="<?php echo add_query_arg( 'view', 'comments' ); ?>">
				<?php _e( 'Comments', 'pronamic_importer' ); ?>
			</a>
		</li>
	</ul>

	<?php 

	$view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRING );

	switch ( $view ) {
		case 'posts':
			include 'import-posts.php';

			break;
		case 'users':
			include 'import-users.php';
			
			break;
		case 'comments':
			include 'import-comments.php';
			
			break;
	}

	?>
</div>