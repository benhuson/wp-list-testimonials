<?php

/**
 * WPL Testimonial Class
 */
class WPL_Testimonal {

	/**
	 * Post object
	 *
	 * @var  WP_Post
	 */
	private $post = null;

	/**
	 * Constructor
	 *
	 * @param  int|WP_Post  $post  Post ID or object.
	 */
	public function __construct( $post ) {

		$this->post = get_post( $post );

	}

	/**
	 * Get The ID
	 *
	 * @return  int  Post ID.
	 */
	public function get_the_id() {

		return $this->post->ID;

	}

	/**
	 * Get Meta
	 *
	 * @param   string  $key  Meta key (without prefix).
	 * @return  string        Meta value.
	 */
	public function get_meta( $key ) {

		$key = sprintf( '_wpl_testimonials_%s', sanitize_key( $key ) );

		return get_post_meta( $this->get_the_ID(), $key, true );

	}

	/**
	 * Update Meta
	 *
	 * @param   string  $key    Meta key (without prefix).
	 * @param   string  $value  Meta value.
	 */
	public function update_meta( $key, $value ) {

		// Sanitize value
		if ( is_array( $value ) ) {
			$value = array_map( 'sanitize_text_field', $value );
		} else {
			$value = sanitize_text_field( $value );
		}

		$key = sprintf( '_wpl_testimonials_%s', sanitize_key( $key ) );

		return update_post_meta( $this->get_the_ID(), $key, $value );

	}

}
