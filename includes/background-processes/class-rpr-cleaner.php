<?php

trait RPR_Cleaner {
	/**
	 * Delay the next process so as not to strain the server.
	 * @param int $pause
	 * @return int
	 */
	public function delay_next_task( $pause = 5 ) {
		return sleep( $pause );
	}

	/**
	 * Log the status of our process.
	 *
	 * @param string $message
	 */
	public function log( $message ) {
		error_log( $message );
	}

	/**
	 * Get and clean each item
	 *
	 * @param int $recipe The recipe's post ID.
	 *
	 * @return string
	 */
	protected function clean_item( $recipe ) {
		$content = array(
			'ID'                => $recipe,
			'post_content'      => stripslashes_deep( get_post_field( 'post_content', $recipe ) ),
			'post_excerpt'      => stripslashes_deep( get_post_field( 'post_excerpt', $recipe ) ),
			// Do not change the current 'post modified' dates.
			'post_modified'     => get_post_field( 'post_modified', $recipe ),
			'post_modified_gmt' => get_post_field( 'post_modified_gmt', $recipe ),
		);

		add_filter( 'wp_insert_post_data', array( $this, 'do_not_update_last_modified' ), 99, 2 );
		$post_id = wp_update_post( $content, true );
		remove_filter( 'wp_insert_post_data', array( $this, 'do_not_update_last_modified' ) );

		if ( is_wp_error( $post_id ) ) {
			$errors = $post_id->get_error_messages();
			foreach ( $errors as $error ) {
				return 'Recipe ID ' . $recipe . ': ' . $error;
			}
		}

		return 'Recipe ID ' . $post_id . ' has been updated.';
	}

	/**
	 * @see https://core.trac.wordpress.org/ticket/36595
	 *
	 * @param $data
	 * @param $postarr
	 *
	 * @return array
	 */
	public function do_not_update_last_modified( $data , $postarr ) {
		if ( ! empty($postarr['post_modified'] ) && ! empty( $postarr['post_modified_gmt'] ) ) {
			$data['post_modified']     = $postarr['post_modified'];
			$data['post_modified_gmt'] = $postarr['post_modified_gmt'];
		}

		return $data;
	}
}
