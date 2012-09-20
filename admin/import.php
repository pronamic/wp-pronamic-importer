<div class="wrap">
	<?php screen_icon( 'pronamic_db_importer' ); ?>

	<h2>
		<?php _e( 'Database Import', 'pronamic_db_importer' ); ?>
	</h2>


	<form method="post" action="options.php">
		<?php settings_fields( 'pronamic_db_importer' ); ?>

		<h3>
			<?php _e( 'Database', 'pronamic_db_importer' ); ?>
		</h3>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="pronamic_db_importer_name"><?php _e( 'Name', 'pronamic_framework' ); ?></label>
				</th>
				<td>
					<input id="pronamic_db_importer_name" name="pronamic_db_importer_name" type="text" value="<?php form_option( 'pronamic_db_importer_name' ) ; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="pronamic_db_importer_user"><?php _e( 'User', 'pronamic_framework' ); ?></label>
				</th>
				<td>
					<input id="pronamic_db_importer_user" name="pronamic_db_importer_user" type="text" value="<?php form_option( 'pronamic_db_importer_user' ) ; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="pronamic_db_importer_password"><?php _e( 'Password', 'pronamic_framework' ); ?></label>
				</th>
				<td>
					<input id="pronamic_db_importer_password" name="pronamic_db_importer_password" type="text" value="<?php form_option( 'pronamic_db_importer_password' ) ; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="pronamic_db_importer_host"><?php _e( 'Host', 'pronamic_framework' ); ?></label>
				</th>
				<td>
					<input id="pronamic_db_importer_host" name="pronamic_db_importer_host" type="text" value="<?php form_option( 'pronamic_db_importer_host' ) ; ?>" />
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>
</div>