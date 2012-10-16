<?php 

$pdo = Pronamic_DatabaseImporter_Plugin::get_database();

pronamic_db_importer_try_import(); 

$query = file_get_contents( dirname( __FILE__ ) . '/../includes/sql/project-2/select-posts.sql' );
$query = file_get_contents( dirname( __FILE__ ) . '/../includes/sql/project-2/select-history-posts.sql' );

$statement = $pdo->prepare( $query );

$result = $statement->execute();

$statement->bindColumn( 1, $id );
$statement->bindColumn( 2, $title );
$statement->bindColumn( 3, $url );
$statement->bindColumn( 4, $description );
$statement->bindColumn( 5, $timestamp );
$statement->bindColumn( 6, $post_type );
$statement->bindColumn( 7, $category );

?>
<form method="post" action="">

	<?php submit_button( 'Import', 'primary', 'import-bulk' ); ?>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach(array('head', 'foot') as $element): ?>

			<t<?php echo $element; ?>>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
					<th scope="col"><?php _e( 'ID', 'pronamic_db_importer' ); ?></th>
					<th scope="col"><?php _e( 'Title', 'pronamic_db_importer' ); ?></th>
					<th scope="col"><?php _e( 'URL', 'pronamic_db_importer' ); ?></th>
					<th scope="col"><?php _e( 'Description', 'pronamic_db_importer' ); ?></th>
					<th scope="col"><?php _e( 'Date', 'pronamic_db_importer' ); ?></th>
					<th scope="col"><?php _e( 'Post Type', 'pronamic_db_importer' ); ?></th>
					<th scope="col"><?php _e( 'Category', 'pronamic_db_importer' ); ?></th>
				</tr>
			</t<?php echo $element; ?>>

		<?php endforeach; ?>
		
		<tbody>

			<?php while($row = $statement->fetch(PDO::FETCH_BOUND)): ?>

				<tr>
					<th scope="row" class="check-column">
						<input name="pronamic_ids[]" value="<?php echo $id; ?>" type="checkbox" /> 
					</th>
					<td><?php echo $id; ?></td>
					<td><?php echo $title; ?></td>
					<td>
						<a href="<?php echo $url; ?>" target="_blank">
							<?php echo $url; ?>
						</a>
					</td>
					<td><?php echo htmlspecialchars( stripslashes( $description ) ); ?></td>
					<td><?php echo date_i18n( __( 'M j, Y @ G:i', 'pronamic_db_importer' ), $timestamp ); ?></td>
					<td><?php echo $post_type; ?></td>
					<td><?php echo $category; ?></td>
				</tr>

			<?php endwhile; ?>

		</tbody>
	</table>

	<?php submit_button( 'Import', 'primary', 'import-bulk' ); ?>
</form>

<?php

$statement = null;

?>