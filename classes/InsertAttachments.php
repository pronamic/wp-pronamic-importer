<?php

class InsertAttachments extends ImportAction {
	public static function mimeToExt($mime) {
		$sem = strpos($mime, ';');
		if($sem !== false) {
			$mime = substr($mime, 0, $sem);
		}

		$ext = array_search($mime, get_allowed_mime_types());

		if($ext !== false) {
			$sep = strpos($ext, '|');

			if($sep !== false) {
				$ext = substr($ext, 0, $sep);
			}
		}

		return $ext;
	}

	public function process(ImportInfo $import) {
		$date = $import->getDate();
		$postId = $import->getPostId();

		foreach($import->media as $i => $attachment) {
			$url = $attachment->getUrl();

			$import->log(sprintf('Determining filename from URL: "<strong>%s</strong>"', $url));
	
			// wp_unique_filename
			// wp_handle_upload
			// wp_unique_filename
			// media_sideload_image
			// wp_handle_sideload
			// wp_read_image_metadata
			// sanitize_title
			$url = new Url($url);
			$path = $url->getPath();
			$name = basename($path);
			
			// Make sure that %20 encoded spaces are decoded to a space character
			$name = urldecode($name);

			$ext = pathinfo($name, PATHINFO_EXTENSION);
			
			if(isset($attachment->attachmentFileName)) {
				$name = $attachment->attachmentFileName;
			}
			
			if ( empty( $ext ) || in_array( $ext, array( 'php' ) ) ) {
				$finfo = new finfo(FILEINFO_MIME);

				$mime = $finfo->file($attachment->file);

				$ext_new = 	self::mimeToExt($mime);

				if ( $ext_new !== false ) {
					$replace_extensions = array(
						'.' . $ext_new
					);
					
					if ( ! empty( $ext ) )
						$replace_extensions[] = '.' . $ext;
					
					$name = str_replace( $replace_extensions, '', $name );
					$name .= '.' . $ext_new;
				}
			}
	
			$import->log(sprintf('Determined filename: <strong>%s</strong>', $name));
	
			/**
			 * The function wp_upload_bits() uses the wp_upload_dir($time) function
			 * to determine the directory where store the specified bits. The third
			 * parameter of wp_upload_bits() is directly passed to wp_upload_dir().
			 * 
			 * The functin wp_upload_bits() uses the wp_unique_filename() function
			 * to determine a unique filename in the upload directory determined by 
			 * wp_upload_dir(). 
			 * 
			 * The function wp_unique_filename() uses sanitize_file_name() so we 
			 * don't have to worry about invalid charachters in the file name from 
			 * the URL.
			 */
			$import->log(sprintf('Storing "<strong>%s</strong>" in upload directory (time: "<strong>%s</strong>")', $name, $date->format('Y/m')));
	
			if (empty($attachment->file)) {
				$bits = file_get_contents(Pronamic_Importer_Plugin::get_placeholder());
			} else {
				$bits = file_get_contents($attachment->file);
			}
			
			$result = wp_upload_bits($name, null, $bits, $date->format('Y/m'));
			
			if($result['error'] === false) { // no error
				$import->log(sprintf('Stored file: <a href="%s">%s</a>', $result['url'], $result['url']));

				$import->log(sprintf('Checking filetype of file: "<strong>%s</strong>"', $result['file']));

				$fileType = wp_check_filetype($result['file']);

				$import->log(sprintf('Found filetype: "<strong>%s</strong>"', $fileType['type']));
				$import->log(sprintf('Found extension: "<strong>%s</strong>"', $fileType['ext']));

				$import->log('Creating WordPress attachment array &hellip;');

				$attachmentTitle = $attachment->getPostData('post_title');
				if(empty($attachmentTitle)) {
					$attachment->setPostData('post_title', $import->getPostData('post_title'));
				}

				/**
				 * The wp_insert_attachment() function does not automatically fill in the
				 * 'post_date_gmt' value. So it is important that we define this value, 
				 * otherwise the current time is used.
				 */
				$attachment->setPostData('menu_order', $i + 1); 
				$attachment->setPostData('post_mime_type', $fileType['type']);
				$attachment->setPostData('guid', $result['url']);
				$attachment->setPostData('post_parent', $postId);
				$attachment->setPostData('post_date', $date->format('Y-m-d H:i:s'));
				$attachment->setPostData('post_date_gmt', get_gmt_from_date($date->format('Y-m-d H:i:s')));

				// Merge?
				// $attachment = array_merge($attachment, $post_data);

				$import->log(sprintf('Created WordPress attachment array with <strong>%d</strong> fields', count($attachment)));

				$import->log('Inserting WordPress attachment &hellip;');

				$attachmentId = wp_insert_attachment($attachment->post, $result['file'], $postId);

				if($import->thumbnail->getUrl() == $url) {
					var_dump($import->thumbnail);exit;
					$import->setPostMeta('_thumbnail_id', $attachmentId);
				}

				$attachment->setPostId($attachmentId);

				$import->log(sprintf('Inserted WordPress attachment with ID %s', $attachmentId));

				$import->log('Generating attachment meta data &hellip;');

				$metaData = wp_generate_attachment_metadata($attachmentId, $result['file']);

				$import->log(sprintf('Generated attachment meta data with %d fields', count($metaData)));

				if(isset($metaData['width'])) {
					$import->log(sprintf('Found attachment width of <strong>%d</strong> pixels', $metaData['width']));
				}

				if(isset($metaData['height'])) {
					$import->log(sprintf('Found attachment height of <strong>%d</strong> pixels', $metaData['height']));
				}

				$import->log(sprintf('Updating attachment meta data &hellip;', count($metaData)));

				$updated = wp_update_attachment_metadata($attachmentId, $metaData);

				if($updated) {
					$import->log(sprintf('Succesfully updated attachment meta data &hellip;'));
				} else {
					$import->log(sprintf('Failed updating attachment meta data'));
				}
				
				do_action( 'pronamic_importer_after_insert_attachment', $postId, $result, $attachment );
				
				// Meta
				$action = new AddPostMeta();
				$action->process($attachment);

				$import->log(sprintf('Succesfully imported attachment'));
			} else {
				echo '<pre>';
				var_dump($result);
				echo '</pre>';
			}
		}
	
		$import->log(sprintf('Succesfully downloaded attachments'));

		$this->next($import);
	}
}
