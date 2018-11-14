<?php

trait RPR_Cleaner {
	/**
	 * Really long running process
	 * @param int $pause
	 * @return int
	 */
	public function delay_next_task( $pause = 5 ) {
		return sleep( $pause );
	}

	/**
	 * Log
	 *
	 * @param string $message
	 */
	public function log( $message ) {
		error_log( $message );
	}

	/**
	 * Get and clean each item
	 *
	 * @param string $recipe
	 *
	 * @return string
	 */
	protected function clean_item( $recipe ) {
		$content = array(
			'ID' => $recipe,
			'post_content' => stripslashes_deep( get_post_field( 'post_content', $recipe ) ),
		);

		$post_id = wp_update_post( $content, true );

		if ( is_wp_error( $post_id ) ) {
			$errors = $post_id->get_error_messages();
			foreach ( $errors as $error ) {
				echo $error;
			}
		}

		return $post_id;
	}
}
