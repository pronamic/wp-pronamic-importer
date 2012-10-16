<?php

class Pronamic_Importer_Importer extends WP_Importer {
	public function dispatch() {
		include Pronamic_Importer_Plugin::$dirname . '/admin/importer.php';
	}
}
