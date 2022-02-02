<?php
/**
 * /lib/compatibility/oxygen.php
 *
 * Oxygen Builder compatibility features.
 *
 * @package Relevanssi
 * @author  Mikko Saari
 * @license https://wordpress.org/about/gpl/ GNU General Public License
 * @see     https://www.relevanssi.com/
 */

add_filter( 'relevanssi_custom_field_value', 'relevanssi_oxygen_compatibility', 10, 3 );
add_filter( 'relevanssi_index_custom_fields', 'relevanssi_add_oxygen' );
add_filter( 'pre_option_relevanssi_index_fields', 'relevanssi_oxygen_fix_none_setting' );
/**
 * Cleans up the Oxygen Builder custom field for Relevanssi consumption.
 *
 * Splits up the big custom field content from ct_builder_shortcodes into
 * sections ([ct_section] tags). Each section can be processed with filters
 * defined with `relevanssi_oxygen_section_filters`, for example to remove
 * sections based on their "nicename" or "ct_category" values. After that the
 * section is passed through the `relevanssi_oxygen_section_content` filter.
 * Finally all shortcode tags are removed, leaving just the content.
 *
 * @param array  $value   An array of custom field values.
 * @param string $field   The name of the custom field. This function only looks
 * at `ct_builder_shortcodes` fields.
 * @param int    $post_id The post ID.
 *
 * @return array|null An array of custom field values, null if no value exists.
 */
function relevanssi_oxygen_compatibility( $value, $field, $post_id ) {
	if ( 'ct_builder_shortcodes' === $field ) {
		if ( empty( $value ) ) {
			return null;
		}

		$content_tags = explode( '[ct_section', $value[0] );
		$page_content = '';

		foreach ( $content_tags as $content ) {
			if ( empty( $content ) ) {
				continue;
			}
			if ( '[' !== substr( $content, 0, 1 ) ) {
				$content = '[ct_section' . $content;
			}
			/**
			 * Allows defining filters to remove Oxygen Builder sections.
			 *
			 * The filters are arrays, with the array key defining the key and
			 * the value defining the value. If the filter array is
			 * array( 'nicename' => 'Hero BG' ), Relevanssi will look for
			 * sections that have "nicename":"Hero BG" in their settings and
			 * will remove those.
			 *
			 * @param array An array of filtering rules, defaults empty.
			 *
			 * @return array
			 */
			$filters = apply_filters(
				'relevanssi_oxygen_section_filters',
				array()
			);
			array_walk(
				$filters,
				function( $filter ) use ( &$content ) {
					foreach ( $filter as $key => $value ) {
						if ( stristr( $content, '"' . $key . '":"' . $value . '"' ) !== false ) {
							$content = '';
						}
					}
				}
			);

			$content = preg_replace(
				'/\[\/?ct_.*?\]/',
				' ',
				/**
				 * Filters the Oxygen Builder section content before the
				 * Oxygen Builder shortcode tags are removed.
				 *
				 * @param string $content The single section content.
				 * @param int    $post_id The post ID.
				 *
				 * @return string
				 */
				apply_filters(
					'relevanssi_oxygen_section_content',
					$content,
					$post_id
				)
			);

			$page_content .= $content;
		}
		if ( 'on' === get_option( 'relevanssi_expand_shortcodes' ) ) {
			$page_content = do_shortcode( $page_content );
		} else {
			$page_content = strip_shortcodes( $page_content );
		}
		$value[0] = $page_content;
	}
	return $value;
}

/**
 * Adds the `ct_builder_shortcodes` to the list of indexed custom fields.
 *
 * @param array|boolean $fields An array of custom fields to index, or false.
 *
 * @return array An array of custom fields, including `ct_builder_shortcodes`.
 */
function relevanssi_add_oxygen( $fields ) {
	if ( ! is_array( $fields ) ) {
		$fields = array();
	}
	$fields[] = 'ct_builder_shortcodes';
	return $fields;
}

/**
 * Makes sure the Oxygen builder shortcode is included in the index, even when
 * the custom field setting is set to 'none'.
 *
 * @param string $value The custom field indexing setting value.
 *
 * @return string If value is undefined, it's set to 'ct_builder_shortcodes'.
 */
function relevanssi_oxygen_fix_none_setting( $value ) {
	if ( ! $value ) {
		$value = 'ct_builder_shortcodes';
	}
	return $value;
}
