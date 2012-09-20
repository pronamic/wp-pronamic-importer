<?php

global $post;

?>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<?php _e( 'ID', 'pronamic_db_importer' ); ?>
			</th>
			<td>
				<?php echo get_post_meta( $post->ID, '_import_id', true ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e( 'URL', 'pronamic_db_importer' ); ?>
			</th>
			<td>
				<?php $url = get_post_meta( $post->ID, '_import_url', true ); ?>

				<a href="<?php echo esc_attr( $url ); ?>" target="_blank"><?php echo $url; ?></a>
			</td>
		</tr>
	</tbody>
</table>