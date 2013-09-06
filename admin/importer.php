<div class="wrap">
	<?php screen_icon( 'pronamic_importer' ); ?>

	<h2>
		<?php _e( 'Database Importer', 'pronamic_importer' ); ?>
	</h2>

	<ul>
		<?php foreach ( Pronamic_Importer_ImportingFactory::all() as $importer ) : ?>
		<li>
			<a href="<?php echo add_query_arg( 'view', $importer ); ?>">
				<?php echo $importer; ?>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>

	<?php if ( filter_has_var( INPUT_GET, 'view' ) ) : ?>
		<?php 
		pronamic_importer_try_import();
		// Passed variables
		$view = filter_input( INPUT_GET, 'view', FILTER_SANITIZE_STRING ); 
		$limit = ( filter_has_var( INPUT_GET, 'limit' ) ? filter_input( INPUT_GET, 'limit', FILTER_VALIDATE_INT ) : 500 );
		$offset = ( filter_has_var( INPUT_GET, 'offset' ) ? filter_input( INPUT_GET, 'offset', FILTER_VALIDATE_INT ) : 0 );
		
		$importer = Pronamic_Importer_ImportingFactory::get( $view );
		
		$rows = $importer->get_all( $limit, $offset );
		
		$first_key = key( $rows );
		$array_keys = array_keys( $rows[$first_key] );
		
		?>
		<form method="POST">
			<?php submit_button( 'Import', 'primary', 'import-bulk' ); ?>
			<table cellspacing="0" class="widefat fixed">
				<thead>
					<tr>
						<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
						<?php foreach ( $array_keys as $array_key ) : ?>
							<th scope="col"><?php echo $array_key; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<?php foreach ( $rows as $row_id => $row ) : ?>
				<tr>
					<th scope="row" class="check-column">
						<input name="ids[]" value="<?php echo $row_id; ?>" type="checkbox" />
					</th>
					<?php foreach ( $array_keys as $array_key ) : ?>
					<td>
						<?php echo make_clickable( $row[$array_key] ); ?>
					</td>
					<?php endforeach; ?>
				</tr>
				<?php endforeach; ?>
			</table>
		</form>
	<?php endif; ?>
</div>