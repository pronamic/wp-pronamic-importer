<div class="wrap">
	<?php screen_icon( 'pronamic_db_importer' ); ?>

	<h2>
		<?php _e( 'Database Importer', 'bakkeveen_importer' ); ?>
	</h2>

	<?php 

	$pdo = Pronamic_DatabaseImporter_Plugin::get_database();
	
	horses_importer_try_import(); ?>

	<h3>
		<?php _e('News', 'horses_importer'); ?>	
	</h3>

	<?php

	$query = "
		SELECT
			news.nws_id ,  
			news.nws_title ,  
			news.nws_url , 
			news.nws_descr , 
			news.nws_timestamp ,
			category.cat_title 
		FROM
			nieuws AS news
				LEFT JOIN
			category AS category
					ON news.nws_category = category.cat_id
		WHERE
			nws_url = ''
				AND
			nws_descr != ''
				AND
			nws_timestamp IS NOT NULL 
		ORDER BY
			news.nws_timestamp ASC
		LIMIT
			0, 250;
	";
	
	$statement = $pdo->prepare($query);
	$statement->execute(array('nieuws'));
	
	$statement->bindColumn(1, $id);
	$statement->bindColumn(2, $title);
	$statement->bindColumn(3, $url);
	$statement->bindColumn(4, $description);
	$statement->bindColumn(5, $timestamp);
	$statement->bindColumn(6, $category);
	
	?>
	<form method="post" action="">

		<?php submit_button( 'Import', 'primary', 'import-bulk' ); ?>

		<table cellspacing="0" class="widefat fixed">
	
			<?php foreach(array('head', 'foot') as $element): ?>
	
			<t<?php echo $element; ?>>
				<tr>
					<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
					<th scope="col"><?php _e('ID', 'bakkeveen_importer'); ?></th>
					<th scope="col"><?php _e('Title', 'bakkeveen_importer'); ?></th>
					<th scope="col"><?php _e('URL', 'bakkeveen_importer'); ?></th>
					<th scope="col"><?php _e('Description', 'bakkeveen_importer'); ?></th>
					<th scope="col"><?php _e('Timestamp', 'bakkeveen_importer'); ?></th>
					<th scope="col"><?php _e('Category', 'bakkeveen_importer'); ?></th>
				</tr>
			</t<?php echo $element; ?>>
	
			<?php endforeach; ?>
			
			<tbody>
	
				<?php while($row = $statement->fetch(PDO::FETCH_BOUND)): ?>
	
				<tr>
					<th scope="row" class="check-column">
						<input name="horses_ids[]" value="<?php echo $id; ?>" type="checkbox" /> 
					</th>
					<td><?php echo $id; ?></td>
					<td><?php echo $title; ?></td>
					<td><?php echo $url; ?></td>
					<td><?php echo htmlspecialchars( stripslashes( $description ) ); ?></td>
					<td><?php echo date_i18n( __('M j, Y @ G:i', 'bakkeveen_importer'), $timestamp ); ?></td>
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
</div>