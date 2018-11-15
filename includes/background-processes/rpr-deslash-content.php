<?php

class RPR_Deslash_Content extends WP_Background_Process {

	use RPR_Cleaner;

	/**
	 * @var string
	 */
	protected $action = 'deslash_content';

	/**
	 * @var array
	 */
	protected $all_recipes;

	/**
	 * @return array
	 */
	public function get_all_recipes() {

		$this->all_recipes = get_posts(array(
			'fields'         => 'ids', // Only get post IDs.
			'posts_per_page' => -1, // Get all posts.
			'post_type'      => 'rpr_recipe',
			'post_status'    => 'any'
		));

		return $this->all_recipes;
	}

	public function clean_each_recipe( $recipe ) {


	}

	/**
	 * The admin message to display for initiating this process.
	 *
	 * @return string
	 */
	public function admin_message() {
		$msg = '';

		if ( ! get_option( 'rpr_content_cleaned' ) ) {
			$msg .= '<div class="notice notice-warning is-dismissible">';
			$msg .= '<p>';
			$msg .= __( 'The RecipePress Reloaded database needs to upgraded. Make sure you have a backup before you proceed.', 'recipepress-reloaded' );
			$msg .= ' | <a href="' . wp_nonce_url( admin_url( '?deslash_content=all' ), 'deslash_content' ) . '">' . __( 'Update', 'recipepress-reloaded' ) . '</a>';
			$msg .= '</p>';
			$msg .= '</div>';
		}

		return $msg;
	}

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		$message = $this->clean_item( $item );
		$this->delay_next_task( 1 );
		$this->log( $message );

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
		update_option( 'rpr_content_cleaned', 2, false );
	}
}
