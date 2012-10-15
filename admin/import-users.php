<div class="wrap">
	<?php screen_icon( 'pronamic_db_importer' ); ?>

	<h2>
		<?php _e( 'Database Importer', 'pronamic_db_importer' ); ?>
	</h2>

	<?php 

	$pdo = Pronamic_DatabaseImporter_Plugin::get_database();
	
	?>

	<h3>
		<?php _e( 'Users', 'pronamic_db_importer' ); ?>	
	</h3>

	<?php

	$query = "
		SELECT
			user.primary_key,  
			user.user,  
			user.password, 
			user.userlevel, 
			user.usercat,
			user.displayname,
			user.email,
			user.naam
		FROM
			phpsp_users AS user
		LIMIT
			0, 250;
	";
	
	$statement = $pdo->prepare( $query );

	$statement->execute();
	
	$statement->bindColumn( 1, $id );
	$statement->bindColumn( 2, $username );
	$statement->bindColumn( 3, $password );
	$statement->bindColumn( 4, $user_level );
	$statement->bindColumn( 5, $user_category );
	$statement->bindColumn( 6, $display_name );
	$statement->bindColumn( 7, $email );
	$statement->bindColumn( 8, $name );
	
	?>
	<form method="post" action="">

		<?php submit_button( 'Import', 'primary', 'import-bulk' ); ?>

		<table cellspacing="0" class="widefat fixed">
	
			<?php foreach ( array( 'head', 'foot' ) as $element ): ?>
	
				<t<?php echo $element; ?>>
					<tr>
						<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
						<th scope="col"><?php _e( 'ID', 'pronamic_db_importer' ); ?></th>
						<th scope="col"><?php _e( 'Username', 'pronamic_db_importer' ); ?></th>
						<th scope="col"><?php _e( 'Password', 'pronamic_db_importer' ); ?></th>
						<th scope="col"><?php _e( 'Level', 'pronamic_db_importer' ); ?></th>
						<th scope="col"><?php _e( 'Category', 'pronamic_db_importer' ); ?></th>
						<th scope="col"><?php _e( 'Display Name', 'pronamic_db_importer' ); ?></th>
						<th scope="col"><?php _e( 'E-Mail', 'pronamic_db_importer' ); ?></th>
						<th scope="col"><?php _e( 'Name', 'pronamic_db_importer' ); ?></th>
						<th scope="col"><?php _e( 'Import', 'pronamic_db_importer' ); ?></th>
					</tr>
				</t<?php echo $element; ?>>
	
			<?php endforeach; ?>
			
			<tbody>
	
				<?php while ( $row = $statement->fetch( PDO::FETCH_BOUND ) ): ?>
	
					<tr>
						<th scope="row" class="check-column">
							<input name="user_ids[]" value="<?php echo $id; ?>" type="checkbox" /> 
						</th>
						<td><?php echo $id; ?></td>
						<td><?php echo $username; ?></td>
						<td><?php echo $password; ?></td>
						<td><?php echo $user_level; ?></td>
						<td><?php echo $user_category; ?></td>
						<td><?php echo $display_name; ?></td>
						<td><?php echo $email; ?></td>
						<td><?php echo $name; ?></td>
						<td>
							<?php 
							
							$role = 'subscriber';
							
							if ( false ) {
								switch ( $user_level ) {
									case '1':
										$role = 'userlevel_1';
										break;
									case '2':
										$role = 'userlevel_2';
										break;
									case '3':
										$role = 'userlevel_3';
										break;
									case '99':
										$role = 'userlevel_99';
										break;
								}
							}

							$userdata = array(
								'user_pass' => $password,
								'user_login' => $username,
								'user_email' => $email,
								'display_name' => $display_name,
								'nickname' => $name,
								'role' => $role
							); 
							
							$result = wp_insert_user( $userdata );
							
							if ( !is_wp_error( $result ) ) {
								$user_id = $result;
								
								update_user_meta( $user_id, '_import_id', $id );
								
								echo $user_id;
							} else {
								echo $result;
							}
							
							?>
						</td>
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