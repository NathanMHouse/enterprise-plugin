<?php // class-admin-functions.php
class Admin_Functions {

	/**
	 * Update Featured Image Metabox
	 *
	 * Updated featured image metabox w/ image type selection select input
	 * for news and resources custom post types.
	 *
	 * @param  str $content Featured image metabox HTML.
	 * @param  int $post_id The post ID.
	 *
	 * @return str $content Filtered featured image metabox HTML.
	 *
	**/
	public function add_featured_image_type_toggle( $content, $post_id ) {
		if (
			'news' !== get_post_type( $post_id )
			&& 'resources' !== get_post_type( $post_id )
		) {
			return $content;
		}

		$field_id    = 'enterprise_plugin_featured_image_type';
		$field_label = esc_html__( 'Select Image Type', 'enterprise-plugin' );
		$field_value = esc_attr( get_post_meta( $post_id, '_enterprise_plugin_featured_image_type', true ) );
		$image_types = array(
			'background',
			'icon',
		);

		$output  = '<p>';
		$output .= '<label for="';
		$output .= $field_id;
		$output .= '">';
		$output .= $field_label;
		$output .= '</label>';
		$output .= '<select id="';
		$output .= preg_replace( '/_/', '-', $field_id );
		$output .= '" ';
		$output .= 'name="';
		$output .= $field_id;
		$output .= '">';
		foreach ( $image_types as $image_type ) {
			$output .= '<option value="';
			$output .= $image_type;
			$output .= '" ';
			$output .= selected( $field_value, $image_type, false );
			$output .= '>';
			$output .= ucwords( $image_type );
			$output .= '</option>';
		}
		$output .= '</select>';
		$output .= wp_nonce_field( 'save_featured_image_type_' . $post_id, 'featured_image_type_nonce', true, false );
		$output .= '</p>';

		return $content .= $output;
	}


	/**
	 * Update Postmeta Values
	 *
	 * Saves associated postmeta values (e.g. featured image type).
	 *
	 * @param int $post_id The post ID.
	 *
	**/
	public function save_post_meta( $post_id ) {

		// Featured image type
		if (
			isset( $_POST['enterprise_plugin_featured_image_type'] ) // input var okay.
			&& isset( $_POST['featured_image_type_nonce'] ) // input var okay.
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['featured_image_type_nonce'] ) ), 'save_featured_image_type_' . $post_id ) // input var okay.
		) { // input var okay.
			update_post_meta(
				$post_id,
				'_enterprise_plugin_featured_image_type',
				wp_strip_all_tags( wp_unslash( $_POST['enterprise_plugin_featured_image_type'] ) ) // input var okay.
			);
		}
	}

	/**
	 * Remove Post Menu Item
	 *
	 * Removes the post menu item from the WordPress dashboard.
	 *
	**/
	public function remove_menus() {
		remove_menu_page( 'edit.php' );
	}
}
