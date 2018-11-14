<?php

require_once 'class-rpr-content-cleanup-process.php';

class RPR_Content_Cleanup_Process extends WP_Background_Process {

	protected $action = 'rpr_content_cleanup';

	protected function task( $item ) {
		$item = $item . '5555555555555555';
		sleep( 5 );
		error_log( $item );

		return false;
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		parent::complete();
	}
}

