<?php

/**
 * WPL Testimonials Admin Class
 */
class WPL_Testimonals_Admin {

	/**
	 * Load
	 */
	public static function load() {

		add_action( 'add_meta_boxes', array( 'WPL_Testimonals_Admin', 'add_meta_boxes' ) );
		add_action( 'save_post', array( 'WPL_Testimonals_Admin', 'save_post' ) );

	}

	/**
	 * Add Meta Boxes
	 */
	public static function add_meta_boxes() {

		add_meta_box( 'wpl_testimonials_client', __( 'Testimonial Author Details', 'wpl_testimonials' ), array( 'WPL_Testimonals_Admin', 'testimonial_author_details_meta_box' ), 'wpl_testimonial', 'normal', 'high' );

	}

	/**
	 * Testimonial Author Details Meta Box
	 */
	public static function testimonial_author_details_meta_box() {

		wp_nonce_field( plugin_basename( __FILE__ ), '_nonce_wpl_testimonials_client' );

		$jobtitle = get_post_meta( get_the_ID(), '_wpl_testimonials_jobtitle', true );
		$company  = get_post_meta( get_the_ID(), '_wpl_testimonials_company', true );
		$location = get_post_meta( get_the_ID(), '_wpl_testimonials_location', true );
		$link     = get_post_meta( get_the_ID(), '_wpl_testimonials_link', true );

		?>

		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="wpl_testimonials_jobtitle"><?php esc_html_e( 'Job Title', 'wpl_testimonials' ); ?></label></th>
					<td><input name="wpl_testimonials[jobtitle]" id="wpl_testimonials_jobtitle" type="text" value="<?php esc_attr_e( $jobtitle ); ?>" class="large-text"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wpl_testimonials_company"><?php esc_html_e( 'Company', 'wpl_testimonials' ); ?></label></th>
					<td><input name="wpl_testimonials[company]" id="wpl_testimonials_company" type="text" value="<?php esc_attr_e( $company ); ?>" class="large-text"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wpl_testimonials_location"><?php esc_html_e( 'Location', 'wpl_testimonials' ); ?></label></th>
					<td><input name="wpl_testimonials[location]" id="wpl_testimonials_location" type="text" value="<?php esc_attr_e( $location ); ?>" class="regular-text"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="wpl_testimonials_link"><?php esc_html_e( 'Link', 'wpl_testimonials' ); ?></label></th>
					<td><input name="wpl_testimonials[link]" id="wpl_testimonials_link" type="url" value="<?php esc_attr_e( $link ); ?>" class="large-text" placeholder="http://"></td>
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
			'location' => ''
		) );

		update_post_meta( $post_id, '_wpl_testimonials_jobtitle', $values['jobtitle'] );
		update_post_meta( $post_id, '_wpl_testimonials_company', $values['company'] );
		update_post_meta( $post_id, '_wpl_testimonials_location', $values['location'] );
		update_post_meta( $post_id, '_wpl_testimonials_link', $values['link'] );

		return;

	}

}
