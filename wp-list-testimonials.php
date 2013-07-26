<?php

/*
Plugin Name: WP List Testimonials
Plugin URI: http://www.benhuson.co.uk/wordpress-plugins/wp-list-testimonials/
Description: Outputs testimonials based on information from your blogroll.
Author: Ben Huson
Author URI: http://www.benhuson.co.uk
Version: 2.0
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

http://www.gnu.org/licenses/gpl.html
*/

/**
 * WP List Testimonials Class
 */
class WP_List_Testimonials {

	var $is_wp_2_8 = false;

	/**
	 * Constructor
	 */
	function WP_List_Testimonials() {

		// Check WordPress version
		if ( version_compare( get_bloginfo( 'version' ), '2.7.2', '>' ) ) {
			$this->is_wp_2_8 = true;
		}
	}

	/**
	 * Get Testimonials HTML
	 */
	function get_testimonials_html( $args = '' ) {
		extract( wp_parse_args( $args ), EXTR_SKIP );

		$output = '';
		$bookmarks = get_bookmarks( $args );
		$count = 0;

		foreach ( $bookmarks as $bookmark ) {
			if ( $maxlimit > 0 && $count >= $maxlimit )
				break;

			if ( ! empty( $bookmark->link_notes ) ) {
				$output .= '<blockquote class="testimonial testimonial-' . $bookmark->link_id . '">' . "\n";
				$output .= '<p>' . $bookmark->link_notes . '</p>' . "\n";
				if ( ! empty( $bookmark->link_name ) ) {
					$description = '';
					if ( ! empty( $bookmark->link_description ) ) {
						$description = ', <span class="testimonialdescription">' . $bookmark->link_description . '</span>';
					}
					$link_name = $bookmark->link_name;
					if ( ! empty( $bookmark->link_url ) && $bookmark->link_url != '#' ) {
						$link_name = '<a href="' . $bookmark->link_url . '" class="testimonialname">' . $link_name . '</a>';
					} else {
						$link_name = '<span class="testimonialname">' . $link_name . '</span>';
					}
					$output .= '<cite>' . $link_name . $description . '</cite>' . "\n";
				}
				$output .= '</blockquote>' . "\n\n";
				$count++;
			}
		}

		if ( ! empty( $output ) ) {
			$output = '<div class="wp-list-testimonials">' . $output . '</div>';
		}

		echo $output;
	}

	/**
	 * Widgets Init
	 */
	function widgets_init() {
		global $wp_list_testimonials;

		if ( $wp_list_testimonials->is_wp_2_8 ) {
			register_widget( 'WP_List_Testimonials_Widget' );
		}
	}

}

/**
 * Template Tag: wp_list_testimonials
 */
function wp_list_testimonials( $args = '' ) {
	global $wp_list_testimonials;
	$wp_list_testimonials->get_testimonials_html( $args );
}

/**
 * WP List Testimonials Widget
 * (only if using WordPress 2.8+)
 */
if ( class_exists('WP_Widget') ) {

	class WP_List_Testimonials_Widget extends WP_Widget {

		/**
		 * Constructor
		 */
		function WP_List_Testimonials_Widget() {
			$widget_ops = array(
				'classname' => 'widget_testimonials',
				'description' => 'Add testimonials to your sidebar.'
			);
			$this->WP_Widget( 'testimonials', 'Testimonials', $widget_ops );
		}

		/**
		 * Output Widget
		 */
		function widget( $args, $instance ) {
			global $wp_list_testimonials;

			extract( $args, EXTR_SKIP );

			echo $before_widget;
			$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );

			if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}

			$my_args = '';

			if ( $instance['limit'] > 0 ) {
				if ( ! empty( $my_args ) ) {
					$my_args .= '&';
				}
				$my_args .= 'maxlimit=' . $instance['limit'];
			}
			if ( $instance['category'] > 0 ) {
				if ( ! empty( $my_args ) ) {
					$my_args .= '&';
				}
				$my_args .= 'category=' . $instance['category'];
			}
			if ( ! empty( $instance['sort'] ) ) {
				if ( ! empty( $my_args ) ) {
					$my_args .= '&';
				}
				$my_args .= 'orderby=' . $instance['sort'];
			}

			$wp_list_testimonials->get_testimonials_html( $my_args );

			echo $after_widget;
		}

		/**
		 * Update Widget Settings
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			$instance['title']    = strip_tags( $new_instance['title'] );
			$instance['category'] = intval( $new_instance['category'] );
			$instance['sort']     = strip_tags( $new_instance['sort'] );
			$instance['limit']    = intval( $new_instance['limit'] );

			return $instance;
		}

		/**
		 * Widget Settings
		 */
		function form( $instance ) {
			$instance = wp_parse_args( (array)$instance, array( 'title' => '' ) );

			$title    = strip_tags( $instance['title'] );
			$category = strip_tags( $instance['category'] );
			$sort     = strip_tags( $instance['sort'] );
			$limit    = strip_tags( $instance['limit'] );

			$link_cats = get_terms( 'link_category' );

			?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo attribute_escape( $title ); ?>" /></label></p>
			<p>
				<label for="<?php echo $this->get_field_id( 'category' ); ?>">Category:
					<select class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>">
						<option value=""><?php _e('All Links'); ?></option>
						<?php
						foreach ( $link_cats as $link_cat ) {
							echo '<option value="' . intval( $link_cat->term_id ) . '"' . ( $link_cat->term_id == $instance['category'] ? ' selected="selected"' : '' ) . '>' . $link_cat->name . "</option>\n";
						}
						?>
					</select>
				</label>
			<p>
			<p>
				<label for="<?php echo $this->get_field_id( 'sort' ); ?>">Order by:
					<select class="widefat" id="<?php echo $this->get_field_id( 'sort' ); ?>" name="<?php echo $this->get_field_name( 'sort' ); ?>">
						<option value="">Name</option>
						<option value="updated"<?php echo $instance['sort'] == 'updated' ? ' selected="selected"' : ''; ?>>Most Recently Updated</option>
						<option value="rating"<?php echo $instance['sort'] == 'rating' ? ' selected="selected"' : ''; ?>>Highest Rating</option>
						<option value="rand"<?php echo $instance['sort'] == 'rand' ? ' selected="selected"' : ''; ?>>Random</option>
					</select>
				</label>
			</p>
			<p><label for="<?php echo $this->get_field_id( 'limit' ); ?>">Number to show: <input id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo attribute_escape( $limit ); ?>" size="3" /></label></p>
			<?php
		}

	}

}

// Init.
global $wp_list_testimonials;
$wp_list_testimonials = new WP_List_Testimonials();

// Hooks
add_action( 'widgets_init', array( $wp_list_testimonials, 'widgets_init' ) );
