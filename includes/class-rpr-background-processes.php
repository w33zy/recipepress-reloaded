<?php

class RPR_Background_Processes {

	/**
	 * @var RPR_Deslash_Content
	 */
	protected $deslash_content;

	/**
	 * RPR_Background_Processes constructor.
	 */
	public function __construct() {

	}

	/**
	 * Init
	 */
	public function init() {
		require_once plugin_dir_path( __FILE__ ) . 'background-processes/class-rpr-cleaner.php';
		require_once plugin_dir_path( __FILE__ ) . 'background-processes/rpr-deslash-content.php';

		$this->deslash_content = new RPR_Deslash_Content();
	}

	public function admin_notices() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$out = '';
		$out .= $this->deslash_content->admin_message();

		echo $out;
	}

	/**
	 * Process handler
	 */
	public function process_handler() {
		if ( isset( $_GET['deslash_content'] ) && isset( $_GET['_wpnonce'] ) ) {
			if ( wp_verify_nonce( $_GET['_wpnonce'], 'deslash_content') ) {
				if ( 'all' === $_GET['deslash_content'] ) {
					add_option( 'rpr_content_cleaned', 1, false );
					$this->add_deslash_content_to_queue();
				}
			}
		}
	}

	/**
	 * Add all content to be deslashed to process queue
	 */
	protected function add_deslash_content_to_queue() {
		if ( (int) get_option( 'rpr_content_cleaned' ) === 1 ) {

			$recipes = $this->deslash_content->get_all_recipes();

			foreach ( $recipes as $recipe ) {
				$this->deslash_content->push_to_queue( $recipe );
			}

			$this->deslash_content->save()->dispatch();
		}
	}
}
