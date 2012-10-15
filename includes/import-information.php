<?php

$id          = get_post_meta( $post->ID, '_import_id', true );
$url         = get_post_meta( $post->ID, '_import_url', true );
$author_id   = get_post_meta( $post->ID, '_import_author_id', true );
$category_id = get_post_meta( $post->ID, '_import_category_id', true );

if ( ! empty( $id ) ): ?>

	<div class="pronamic-import-information">
		<h2><?php _e( 'Import Information', 'pronamic_db_importer' ); ?></h2>

		<dl>
			<dt>
				<?php _e( 'ID', 'pronamic_db_importer' ); ?>
			</dt>
			<dd>
				<?php echo $id; ?>
			</dd>
			<dt>
				<?php _e( 'URL', 'pronamic_db_importer' ); ?>
			</dt>
			<dd>
				<a href="<?php echo esc_attr( $url ); ?>" target="_blank">
					<?php echo $url; ?>
				</a>
			</dd>
			<dt>
				<?php _e( 'Author ID', 'pronamic_db_importer' ); ?>
			</dt>
			<dd>
				<?php echo $author_id; ?>
			</dd>
			<dt>
				<?php _e( 'Category ID', 'pronamic_db_importer' ); ?>
			</dt>
			<dd>
				<?php echo $category_id; ?>
			</dd>
		</dl>
	</div>

<?php endif; ?>
