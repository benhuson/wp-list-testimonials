<?php

/**
 * WPL Testimonials Admin Class
 */
class WPL_Testimonals_Admin {

	/**
	 * Load
	 */
	public static function load() {

		add_action( 'edit_form_after_title', array( 'WPL_Testimonals_Admin', 'edit_form_after_title' ) );
		add_filter( 'teeny_mce_buttons', array( 'WPL_Testimonals_Admin', 'teeny_mce_buttons' ) );
		add_action( 'add_meta_boxes', array( 'WPL_Testimonals_Admin', 'add_meta_boxes' ) );
		add_action( 'save_post', array( 'WPL_Testimonals_Admin', 'save_post' ) );

	}

	/**
	 * Edit Form After Title
	 */
	public static function edit_form_after_title() {

		global $post;

		if ( get_post_type( $post ) == WPL_Testimonals::post_type() ) {

			$content = $post->post_content;
			$editor_id = 'content';

			wp_editor( $content, $editor_id, array(
				'teeny'         => true,
				'textarea_rows' => 10
			) );

		}

	}

	/**
	 * Teeny MCE Buttons
	 *
	 * @param   array  $buttons  Tiny MCE buttons.
	 * @return  array            Filtered Tiny MCE buttons.
	 */
	public static function teeny_mce_buttons( $buttons ) {

		$remove_buttons = apply_filters( 'wpl_testimonals_remove_teeny_mce_buttons', array( 'blockquote', 'fullscreen' ) );

		$buttons = array_diff( $buttons, $remove_buttons );

		return $buttons;

	}

	/**
	 * Add Meta Boxes
	 */
	public static function add_meta_boxes() {

		add_meta_box( 'wpl_testimonials_client', __( 'Testimonial Author Details', 'wpl_testimonials' ), array( 'WPL_Testimonals_Admin', 'testimonial_author_details_meta_box' ), 'wpl_testimonial', 'normal', 'high' );

	}

	/**
	 * Testimonial Author Details Meta Box
	 *
	 * @param  WP_Post  $post  Post object.
	 */
	public static function testimonial_author_details_meta_box( $post ) {

		wp_nonce_field( plugin_basename( __FILE__ ), '_nonce_wpl_testimonials_client' );

		$testimonial = new WPL_Testimonal( $post->ID );

		?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="wpl_testimonials_jobtitle"><?php esc_html_e( 'Job Title', 'wpl_testimonials' ); ?></label></th>
					<td><input name="wpl_testimonials[jobtitle]" id="wpl_testimonials_jobtitle" type="text" value="<?php esc_attr_e( $testimonial->get_meta( 'jobtitle' ) ); ?>" class="large-text"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wpl_testimonials_company"><?php esc_html_e( 'Company', 'wpl_testimonials' ); ?></label></th>
					<td><input name="wpl_testimonials[company]" id="wpl_testimonials_company" type="text" value="<?php esc_attr_e( $testimonial->get_meta( 'company' ) ); ?>" class="large-text"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wpl_testimonials_location"><?php esc_html_e( 'Location', 'wpl_testimonials' ); ?></label></th>
					<td><input name="wpl_testimonials[location]" id="wpl_testimonials_location" type="text" value="<?php esc_attr_e( $testimonial->get_meta( 'location' ) ); ?>" class="regular-text"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wpl_testimonials_link"><?php esc_html_e( 'Link', 'wpl_testimonials' ); ?></label></th>
					<td><input name="wpl_testimonials[link]" id="wpl_testimonials_link" type="url" value="<?php esc_attr_e( $testimonial->get_meta( 'link' ) ); ?>" class="large-text" placeholder="http://"></td>
				</tr>
			</tbody>
		</table>

		<?php

	}

	/**
	 * Save Post
	 *
	 * @param   int  $post_id  Post ID.
	 * @return
	 */
	public static function save_post( $post_id ) {

		global $wpdb;

		// Verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check permissions
		if ( ! isset( $_POST['post_type'] ) || 'wpl_testimonial' != $_POST['post_type'] ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['_nonce_wpl_testimonials_client'], plugin_basename( __FILE__ ) ) ) {
			return;
		}
		if ( ! isset( $_POST['wpl_testimonials'] ) ) {
			return; 
		}

		$values = wp_parse_args( $_POST['wpl_testimonials'], array(
			'jobtitle' => '',
			'company'  => '',
			'location' => '',
			'link'     => ''
		) );

		$testimonial = new WPL_Testimonal( $post_id );

		$testimonial->update_meta( 'jobtitle', $values['jobtitle'] );
		$testimonial->update_meta( 'company', $values['company'] );
		$testimonial->update_meta( 'location', $values['location'] );
		$testimonial->update_meta( 'link', $values['link'] );

		return;

	}

}
