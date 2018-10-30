<?php

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * @var array $grid_posts An array of all published post objects.
 */

// Create an empty output variable.
$out   = '';
$image = '';

if ( $grid_posts && count( $grid_posts ) > 0 ) {

	// Create an index i to compare the number in the list and check for first and last item.
	$i = 0;

	foreach ( $grid_posts as $key => $posts ) {

		$link = get_term_link( $key, $options['taxonomy'] );
		$term = get_term_by( 'slug', $key, $options['taxonomy'] );

		if ( 0 === $i && ( count( $posts ) !== $i ) ) {
			$out .= '<div class="rpr_tax_container ' . $key . '">';
			$out .= '<h2>' . esc_attr( $term->name ) . '</h2>';
			$out .= '<div class="rpr_tax_items_wrapper">';
		}

		foreach ( $posts as $post ) {

			$image = get_the_post_thumbnail( $post->ID, 'medium', array( 'class' => 'left' ) );

			$out .= '<div class="rpr_tax_item">';
			$out .= '<a href="' . get_permalink( $post->ID ) . '">';
			$out .= '<div class="rpr_tax_item_img">';
			$out .= '' !== $image ? $image : '<div style="width: 100%; height: 100%; background-color: #f7f7f7;"></div>';
			$out .= '</div>';
			$out .= $post->post_title;
			$out .= '</a>';
			$out .= '</div> <!-- .rpr_tax_item -->';

			// increment the counter.
			$i ++;
		}

		if ( count( $posts ) === $i ) {
			$i = 0;

			$out .= '</div> <!-- .rpr_tax_items_wrapper -->';
			$out .= '<a href="' . esc_url( $link ) . '">';
			$out .= '<div class="rpr_tax_link_button">';
			$out .= '<p>' . sprintf( __( 'View more %s recipes', 'recipepress-reloaded' ), $term->name ) . '</p>';
			$out .= '</div>';
			$out .= '</a>';
			$out .= '</div> <!-- .rpr_tax_container -->';
		}
	}
	echo $out;

} else {
	// No recipes.
	echo sprintf( __( 'There are no recipes in the "%s" taxonomy to display.', 'recipepress-reloaded' ), $taxonomy );
}
