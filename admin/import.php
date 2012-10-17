<div class="wrap">
	<?php screen_icon( 'pronamic_importer' ); ?>

	<h2>
		<?php _e( 'Database Import', 'pronamic_importer' ); ?>
	</h2>


	<form method="post" action="options.php">
		<?php settings_fields( 'pronamic_importer' ); ?>

		<h3>
			<?php _e( 'Database', 'pronamic_importer' ); ?>
		</h3>

		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="pronamic_importer_db_name"><?php _e( 'Name', 'pronamic_framework' ); ?></label>
				</th>
				<td>
					<input id="pronamic_importer_db_name" name="pronamic_importer_db_name" type="text" value="<?php form_option( 'pronamic_importer_db_name' ) ; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="pronamic_importer_db_user"><?php _e( 'User', 'pronamic_framework' ); ?></label>
				</th>
				<td>
					<input id="pronamic_importer_db_user" name="pronamic_importer_db_user" type="text" value="<?php form_option( 'pronamic_importer_db_user' ) ; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="pronamic_importer_db_password"><?php _e( 'Password', 'pronamic_framework' ); ?></label>
				</th>
				<td>
					<input id="pronamic_importer_db_password" name="pronamic_importer_db_password" type="text" value="<?php form_option( 'pronamic_importer_db_password' ) ; ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="pronamic_importer_db_host"><?php _e( 'Host', 'pronamic_framework' ); ?></label>
				</th>
				<td>
					<input id="pronamic_importer_db_host" name="pronamic_importer_db_host" type="text" value="<?php form_option( 'pronamic_importer_db_host' ) ; ?>" />
				</td>
			</tr>
		</table>

		<?php submit_button(); ?>
	</form>
</div>