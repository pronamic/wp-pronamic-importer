<?php 

$pdo = Pronamic_Importer_Plugin::get_database();

?>

<h3>
	<?php _e( 'Comments', 'pronamic_importer' ); ?>	
</h3>

<?php

$query = file_get_contents( dirname( __FILE__ ) . '/../includes/sql/project-2/select-comments.sql' );

$statement = $pdo->prepare( $query );
$statement->execute();

$comments = $statement->fetchAll( PDO::FETCH_OBJ );

$query = sprintf( 'UPDATE %s SET wordpress_imported = TRUE WHERE %s = :import_id;', 'reactions', 'id' );
							
$statement_update = $pdo->prepare( $query );

?>
<form method="post" action="">

	<?php submit_button( 'Import', 'primary', 'import-bulk' ); ?>

	<table cellspacing="0" class="widefat fixed">

		<?php foreach ( array( 'head', 'foot' ) as $element ) : ?>

			<t<?php echo $element; ?>>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
					<th scope="col"><?php _e( 'ID', 'pronamic_importer' ); ?></th>
					<th scope="col"><?php _e( 'Date', 'pronamic_importer' ); ?></th>
					<th scope="col"><?php _e( 'Content', 'pronamic_importer' ); ?></th>
					<th scope="col"><?php _e( 'Post ID', 'pronamic_importer' ); ?></th>
					<th scope="col"><?php _e( 'Author', 'pronamic_importer' ); ?></th>
					<th scope="col"><?php _e( 'IP Address', 'pronamic_importer' ); ?></th>
					<th scope="col"><?php _e( 'Import', 'pronamic_importer' ); ?></th>
				</tr>
			</t<?php echo $element; ?>>

		<?php endforeach; ?>
		
		<tbody>

			<?php foreach ( $comments as $comment ) : ?>

				<tr>
					<th scope="row" class="check-column">
						<input name="comment_ids[]" value="<?php echo $comment->import_id; ?>" type="checkbox" /> 
					</th>
					<td><?php echo $comment->import_id; ?></td>
					<td><?php echo $comment->import_date; ?></td>
					<td><?php echo $comment->import_content; ?></td>
					<td><?php echo $comment->import_post_id; ?></td>
					<td><?php echo $comment->import_author; ?></td>
					<td><?php echo $comment->import_ip; ?></td>
					<td>
						<?php 

						if ( true ) {
							$date = Pronamic_Importer_Util_DateTime::convert_timestamp( $comment->import_date, 'UTC', 'Europe/Amsterdam' );
	
							$comment_content = $comment->import_content;
							$comment_content = stripslashes( $comment_content );

							$commentdata = array(
								'comment_author'    => $comment->import_author,
								'comment_content'   => $comment_content,
								'comment_author_IP' => $comment->import_ip,
								'comment_date'      => $date->format( 'Y-m-d H:i:s' ),
								'comment_date_gmt'  => get_gmt_from_date( $date->format( 'Y-m-d H:i:s' ) )
							);
							
							$comment_id = wp_insert_comment( $commentdata );
	
							if ( $comment_id ) {
								update_comment_meta( $comment_id, '_import_id', $comment->import_id );
								update_comment_meta( $comment_id, '_import_post_id', $comment->import_post_id );
								
								echo $comment_id;
	
								$result = $statement_update->execute( array( ':import_id' => $comment->import_id ) );
							}
						}
						
						?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

	<?php submit_button( 'Import', 'primary', 'import-bulk' ); ?>
</form>

<?php

$statement = null;

?>