<?php
/**
 * /lib/phrases.php
 *
 * @package Relevanssi
 * @author  Mikko Saari
 * @license https://wordpress.org/about/gpl/ GNU General Public License
 * @see     https://www.relevanssi.com/
 */

/**
 * Extracts phrases from the search query.
 *
 * Finds all phrases wrapped in quotes (curly or straight) from the search
 * query.
 *
 * @param string $query The search query.
 *
 * @return array An array of phrases (strings).
 */
function relevanssi_extract_phrases( $query ) {
	// iOS uses “” as the default quotes, so Relevanssi needs to understand
	// that as well.
	$normalized_query = str_replace( array( '”', '“' ), '"', $query );
	$pos              = relevanssi_stripos( $normalized_query, '"' );

	$phrases = array();
	while ( false !== $pos ) {
		if ( $pos + 2 > relevanssi_strlen( $normalized_query ) ) {
			$pos = false;
			continue;
		}
		$start = relevanssi_stripos( $normalized_query, '"', $pos );
		$end   = false;
		if ( false !== $start ) {
			$end = relevanssi_stripos( $normalized_query, '"', $start + 2 );
		}
		if ( false === $end ) {
			// Just one " in the query.
			$pos = $end;
			continue;
		}
		$phrase = relevanssi_substr(
			$normalized_query,
			$start + 1,
			$end - $start - 1
		);
		$phrase = trim( $phrase );

		// Do not count single-word phrases as phrases.
		if ( ! empty( $phrase ) && count( explode( ' ', $phrase ) ) > 1 ) {
			$phrases[] = $phrase;
		}
		$pos = $end + 1;
	}

	return $phrases;
}

/**
 * Generates the MySQL code for restricting the search to phrase hits.
 *
 * This function uses relevanssi_extract_phrases() to figure out the phrases in
 * the search query, then generates MySQL queries to restrict the search to the
 * posts containing those phrases in the title, content, taxonomy terms or meta
 * fields.
 *
 * @global array $relevanssi_variables The global Relevanssi variables.
 *
 * @param string $search_query The search query.
 * @param string $operator     The search operator (AND or OR).
 *
 * @return string $queries If not phrase hits are found, an empty string;
 * otherwise MySQL queries to restrict the search.
 */
function relevanssi_recognize_phrases( $search_query, $operator = 'AND' ) {
	global $relevanssi_variables;

	$phrases = relevanssi_extract_phrases( $search_query );

	$all_queries = array();
	if ( 0 === count( $phrases ) ) {
		return $all_queries;
	}

	$custom_fields    = relevanssi_get_custom_fields();
	$taxonomies       = get_option( 'relevanssi_index_taxonomies_list', array() );
	$excerpts         = get_option( 'relevanssi_index_excerpt', 'off' );
	$index_pdf_parent = get_option( 'relevanssi_index_pdf_parent' );

	$phrase_queries = array();
	$queries        = array();

	if (
		isset( $relevanssi_variables['phrase_targets'] ) &&
		is_array( $relevanssi_variables['phrase_targets'] )
		) {
		$non_targeted_phrases = array();
		foreach ( $phrases as $phrase ) {
			if (
				isset( $relevanssi_variables['phrase_targets'][ $phrase ] ) &&
				function_exists( 'relevanssi_targeted_phrases' )
			) {
				$queries = relevanssi_targeted_phrases( $phrase );
			} else {
				$non_targeted_phrases[] = $phrase;
			}
		}
		$phrases = $non_targeted_phrases;
	}

	$queries = array_merge(
		$queries,
		relevanssi_generate_phrase_queries(
			$phrases,
			$taxonomies,
			$custom_fields,
			$excerpts,
			$index_pdf_parent
		)
	);

	$phrase_queries = array();

	foreach ( $queries as $phrase => $p_queries ) {
		$p_queries     = implode( ' OR relevanssi.doc IN ', $p_queries );
		$p_queries     = "(relevanssi.doc IN $p_queries)";
		$all_queries[] = $p_queries;

		$phrase_queries[ $phrase ] = $p_queries;
	}

	$operator = strtoupper( $operator );
	if ( 'AND' !== $operator && 'OR' !== $operator ) {
		$operator = 'AND';
	}

	if ( ! empty( $all_queries ) ) {
		$all_queries = ' AND ( ' . implode( ' ' . $operator . ' ', $all_queries ) . ' ) ';
	}

	return array(
		'and' => $all_queries,
		'or'  => $phrase_queries,
	);
}

/**
 * Generates the phrase queries from phrases.
 *
 * Takes in phrases and a bunch of parameters and generates the MySQL queries
 * that restrict the main search query to only posts that have the phrase.
 *
 * @param array  $phrases          A list of phrases to handle.
 * @param array  $taxonomies       An array of taxonomy names to use.
 * @param array  $custom_fields    A list of custom field names to use.
 * @param string $excerpts         If 'on', include excerpts.
 * @param string $index_pdf_parent If 'on', include PDF parent.
 *
 * @global object $wpdb The WordPress database interface.
 *
 * @return array An array of queries sorted by phrase.
 */
function relevanssi_generate_phrase_queries( $phrases, $taxonomies, $custom_fields, $excerpts, $index_pdf_parent ) {
	global $wpdb;

	$status = relevanssi_valid_status_array();

	// Add "inherit" to the list of allowed statuses to include attachments.
	if ( ! strstr( $status, 'inherit' ) ) {
		$status .= ",'inherit'";
	}

	$phrase_queries = array();

	foreach ( $phrases as $phrase ) {
		$queries = array();
		$phrase  = $wpdb->esc_like( $phrase );
		$phrase  = str_replace( array( '‘', '’', "'", '"', '”', '“', '“', '„', '´' ), '_', $phrase );
		$phrase  = htmlspecialchars( $phrase );
		$phrase  = esc_sql( $phrase );

		$excerpt = '';
		if ( 'on' === $excerpts ) {
			$excerpt = "OR post_excerpt LIKE '%$phrase%'";
		}

		$query = "(SELECT ID FROM $wpdb->posts
			WHERE (post_content LIKE '%$phrase%'
			OR post_title LIKE '%$phrase%' $excerpt)
			AND post_status IN ($status))";

		$queries[] = $query;

		if ( $taxonomies ) {
			$taxonomies_escaped = implode( "','", array_map( 'esc_sql', $taxonomies ) );
			$taxonomies_sql     = "AND s.taxonomy IN ('$taxonomies_escaped')";

			$query = "(SELECT ID FROM
				$wpdb->posts as p,
				$wpdb->term_relationships as r,
				$wpdb->term_taxonomy as s, $wpdb->terms as t
				WHERE r.term_taxonomy_id = s.term_taxonomy_id
				AND s.term_id = t.term_id AND p.ID = r.object_id
				$taxonomies_sql
				AND t.name LIKE '%$phrase%' AND p.post_status IN ($status))";

			$queries[] = $query;
		}

		if ( $custom_fields ) {
			$keys = '';

			if ( is_array( $custom_fields ) ) {
				if ( ! in_array( '_relevanssi_pdf_content', $custom_fields, true ) ) {
					array_push( $custom_fields, '_relevanssi_pdf_content' );
				}

				if ( strpos( implode( ' ', $custom_fields ), '%' ) ) {
					// ACF repeater fields involved.
					$custom_fields_regexp = str_replace( '%', '.+', implode( '|', $custom_fields ) );
					$keys                 = "AND m.meta_key REGEXP ('$custom_fields_regexp')";
				} else {
					$custom_fields_escaped = implode(
						"','",
						array_map(
							'esc_sql',
							$custom_fields
						)
					);
					$keys                  = "AND m.meta_key IN ('$custom_fields_escaped')";
				}
			}

			if ( 'visible' === $custom_fields ) {
				$keys = "AND (m.meta_key NOT LIKE '\_%' OR m.meta_key = '_relevanssi_pdf_content')";
			}

			$query = "(SELECT ID
				FROM $wpdb->posts AS p, $wpdb->postmeta AS m
				WHERE p.ID = m.post_id
				$keys
				AND m.meta_value LIKE '%$phrase%'
				AND p.post_status IN ($status))";

			$queries[] = $query;
		} elseif ( RELEVANSSI_PREMIUM ) {
			$index_post_types = get_option( 'relevanssi_index_post_types', array() );
			if ( in_array( 'attachment', $index_post_types, true ) ) {
				$query = "(SELECT ID
				FROM $wpdb->posts AS p, $wpdb->postmeta AS m
				WHERE p.ID = m.post_id
				AND m.meta_key = '_relevanssi_pdf_content'
				AND m.meta_value LIKE '%$phrase%'
				AND p.post_status IN ($status))";

				$queries[] = $query;
			}
		}

		if ( 'on' === $index_pdf_parent ) {
			$query = "(SELECT parent.ID
			FROM $wpdb->posts AS p, $wpdb->postmeta AS m, $wpdb->posts AS parent
			WHERE p.ID = m.post_id
			AND p.post_parent = parent.ID
			AND m.meta_key = '_relevanssi_pdf_content'
			AND m.meta_value LIKE '%$phrase%'
			AND p.post_status = 'inherit')";

			$queries[] = $query;
		}

		$phrase_queries[ $phrase ] = $queries;
	}

	return $phrase_queries;
}
