<?php // class-public-functions.php
/**
 * Public Functions
 *
 * Public functions (src).
 *
 * @package Enterprise-Plugin
 *
 */


/*--------------------------------------------------------------
>>> TABLE OF CONTENTS:
----------------------------------------------------------------
?.? Define Public Functions
	?.? Set Up Terms Endpoint
	?.? Set Up Terms Endpoint Return
	?.? Set Up Terms Single Redirect
	?.? Set Up External Resources Endpoint
	?.? Set Up External Resources Endpoint Return
	?.? Set Up External Resources Single Redirect
	?.? Set Up Borders Endpoint
	?.? Set Up Borders Endpoint Return
	?.? Set Up Borders Single Redirect
	?.? Set Up Locations Endpoint
	?.? Set Up Locations Endpoint Return
	?.? Set Up Places Endpoint
	?.? Set Up Places Endpoint Return
	?.? Customize Resources/News Query
	?.? Sets Google Maps API Key in Head.
	?.? Creates CTA Within Content.


/*--------------------------------------------------------------
?.? Define Public Functions
--------------------------------------------------------------*/
class Public_Functions {

	/**
	 * ?.? Set Up Terms Endpoint
	 *
	 * Maps terms custom post type endpoint.
	 *
	**/
	public function enterprise_plugin_register_terms_rest_route() {
		register_rest_route(
			'terms/v1',
			'/post/',
			array(
				'method'   => 'GET',
				'callback' => array( $this, 'enterprise_plugin_terms_endpoint' ),
			)
		);
	}


	/**
	 * ?.? Set Up Terms Endpoint Return
	 *
	 * Sets ups the return associated with the terms custom post type
	 * when accessed through its rest route.
	 *
	 * @param  obj $wp_rest_server    Server object.
	 *
	 * @return arr $posts             Array containing terms posts and
	 *                                max pages value.
	 *
	**/
	public function enterprise_plugin_terms_endpoint( $wp_rest_server ) {
		$posts = array(
			'posts'     => array(),
			'max_pages' => '',
		);

		$tax_query = ( $wp_rest_server['term_types'] )
			? array(
				'taxonomy' => 'term_types',
				'field'    => 'slug',
				'terms'    => $wp_rest_server['term_types'],
			)
			: false;

		$search_query = ( $wp_rest_server['s'] ) ?: '';

		$page = ( $wp_rest_server['page'] ) ?: 1;

		$args = array(
			'order'          => 'ASC',
			'orderby'        => 'title',
			'post_type'      => 'terms',
			'posts_per_page' => 9,
			'paged'          => $page,
			's'              => $search_query,
			'tax_query'      => array(
				$tax_query,
			),
		);

		$terms_query = new WP_Query( $args );

		if ( $terms_query->have_posts() ) {
			while ( $terms_query->have_posts() ) {
				$terms_query->the_post();

				$posts['posts'][] = array(
					'id'      => get_the_ID(),
					'title'   => html_entity_decode( get_the_title() ),
					'content' => html_entity_decode( get_the_content() ),
					'acf'     => ( function_exists( 'get_fields' ) ) ? get_fields( get_the_ID() ) : false,
				);
			}
		}

		wp_reset_postdata();

		$posts['max_pages'] = $terms_query->max_num_pages;
		return $posts;
	}


	/**
	 * ?.? Set Up Terms Single Redirect
	 *
	 * Redirects term single pages to the main page.
	 *
	**/
	function enterprise_plugin_create_terms_single_redirect() {

		if ( ! is_single() ) {
			return;
		}

		if ( 'terms' !== get_query_var( 'post_type' ) ) {
			return;
		}

		wp_safe_redirect( 'glossary', 301 );
		exit;
	}


	/**
	 * ?.? Set Up External Resources Endpoint
	 *
	 * Maps external resources custom post type endpoint.
	 *
	**/
	public function enterprise_plugin_register_external_resources_rest_route() {
		register_rest_route(
			'external-resources/v1',
			'/post/',
			array(
				'method'   => 'GET',
				'callback' => array( $this, 'enterprise_plugin_external_resources_endpoint' ),
			)
		);
	}


	/**
	 * ?.? Set Up External Resources Endpoint Return
	 *
	 * Sets ups the return associated w/ the external resources custom post type
	 * when accessed through its rest route.
	 *
	 * @param  obj $wp_rest_server    Server object.
	 *
	 * @return arr $posts             Array containing external resources posts and
	 *                                max pages value.
	 *
	**/
	public function enterprise_plugin_external_resources_endpoint( $wp_rest_server ) {
		$posts = array(
			'posts'     => array(),
			'max_pages' => '',
		);

		$tax_query = ( $wp_rest_server['external_resource_types'] )
			? array(
				'taxonomy' => 'external_resource_types',
				'field'    => 'slug',
				'terms'    => $wp_rest_server['external_resource_types'],
			)
			: false;

		$search_query = ( $wp_rest_server['s'] ) ?: '';

		$page = ( $wp_rest_server['page'] ) ?: 1;

		$args = array(
			'order'          => 'ASC',
			'orderby'        => 'title',
			'post_type'      => 'external_resources',
			'posts_per_page' => 9,
			'paged'          => $page,
			's'              => $search_query,
			'tax_query'      => array(
				$tax_query,
			),
		);

		$terms_query = new WP_Query( $args );

		if ( $terms_query->have_posts() ) {
			while ( $terms_query->have_posts() ) {
				$terms_query->the_post();
				$posts['posts'][] = array(
					'id'      => get_the_ID(),
					'title'   => html_entity_decode( get_the_title() ),
					'content' => html_entity_decode( get_the_content() ),
					'acf'     => ( function_exists( 'get_fields' ) ) ? get_fields( get_the_ID() ) : false,
				);
			}
		}

		wp_reset_postdata();

		$posts['max_pages'] = $terms_query->max_num_pages;
		return $posts;
	}


	/**
	 * ?.? Set Up External Resources Single Redirect
	 *
	 * Redirects external resources single pages to the main page.
	 *
	**/
	function enterprise_plugin_create_external_resources_single_redirect() {

		if ( ! is_single() ) {
			return;
		}

		if ( 'external_resources' !== get_query_var( 'post_type' ) ) {
			return;
		}

		wp_safe_redirect( 'external-resources', 301 );
		exit;
	}


	/**
	 * ?.? Set Up Borders Endpoint
	 *
	 * Maps borders custom post type endpoint.
	 *
	**/
	public function enterprise_plugin_register_borders_rests_route() {
		register_rest_route(
			'borders/v1',
			'/post/',
			array(
				'method'   => 'GET',
				'callback' => array( $this, 'enterprise_plugin_borders_endpoint' ),
			)
		);
	}


	/**
	 * ?.? Set Up Borders Endpoint Return
	 *
	 * Sets ups the return associated w/ the borders custom post type
	 * when accessed through its rest route.
	 *
	 * @param  obj $wp_rest_server    Server object.
	 *
	 * @return arr $posts             Array containing borders posts and
	 *                                max pages value.
	 *
	**/
	public function enterprise_plugin_borders_endpoint( $wp_rest_server ) {
		$posts = array(
			'posts'     => array(),
			'max_pages' => '',
		);

		if (
			$wp_rest_server['origins'] && $wp_rest_server['destinations']
		) {
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'origins',
					'field'    => 'slug',
					'terms'    => $wp_rest_server['origins'],
				),
				array(
					'taxonomy' => 'destinations',
					'field'    => 'slug',
					'terms'    => $wp_rest_server['destinations'],
				),
			);
		} elseif ( $wp_rest_server['origins'] ) {
			$tax_query = array(
				'taxonomy' => 'origins',
				'field'    => 'slug',
				'terms'    => $wp_rest_server['origins'],
			);
		} elseif ( $wp_rest_server['destinations'] ) {
			$tax_query = array(
				'taxonomy' => 'destinations',
				'field'    => 'slug',
				'terms'    => $wp_rest_server['destinations'],
			);
		} else {
			$tax_query = false;
		}

		$search_query = ( $wp_rest_server['s'] ) ?: '';

		$page = ( $wp_rest_server['page'] ) ?: 1;

		$args = array(
			'post_type'      => 'borders',
			'posts_per_page' => 9,
			'paged'          => $page,
			's'              => $search_query,
			'tax_query'      => array(
				$tax_query,
			),
		);

		$borders_query = new WP_Query();

		$borders_query->parse_query( $args );

		// In order to include ACF data w/i the search, execute the query via relevanssi
		if ( function_exists( 'relevanssi_do_query' ) ) {
			$relevanssi_query = relevanssi_do_query( $borders_query );
		}

		foreach ( $borders_query->posts as $post ) {
			$posts['posts'][] = array(
				'id'        => $post->ID,
				'title'     => html_entity_decode( get_the_title( $post->ID ) ),
				'permalink' => get_permalink( $post->ID ),
				'acf'       => ( function_exists( 'get_fields' ) ) ? get_fields( $post->ID ) : false,
			);
		}

		wp_reset_postdata();

		$posts['max_pages'] = $borders_query->max_num_pages;
		return $posts;
	}


	/**
	 * ?.? Set Up Borders Single Redirect
	 *
	 * Redirects border single pages to the main page.
	 *
	**/
	function enterprise_plugin_create_borders_single_redirect() {

		global $post;

		if ( ! is_single() ) {
			return;
		}

		if ( 'borders' !== get_query_var( 'post_type' ) ) {
			return;
		}

		if (
			function_exists( 'get_field' )
			&& get_field( 'show_details', $post->ID )
		) {
			return;
		}

		wp_safe_redirect( 'borders', 301 );
		exit;
	}

	/**
	 * ?.? Set Up Locations Endpoint
	 *
	 * Maps locations custom post type endpoint.
	 *
	**/
	public function enterprise_plugin_register_locations_rests_route() {
		register_rest_route(
			'locations/v1',
			'/post/',
			array(
				'method'   => 'GET',
				'callback' => array( $this, 'enterprise_plugin_locations_endpoint' ),
			)
		);
	}


	/**
	 * ?.? Set Up Locations Endpoint Return
	 *
	 * Sets ups the return associated w/ the locations custom post type
	 * when accessed through its rest route.
	 *
	 * @param  obj $wp_rest_server    Server object.
	 *
	 * @return arr $posts             Array containing location posts.
	 *
	**/
	public function enterprise_plugin_locations_endpoint( $wp_rest_server ) {
		$posts = array(
			'posts' => array(),
		);

		$tax_query = array(
			'relation' => 'AND',
		);

		if ( $wp_rest_server['country'] ) {
			$tax_query[] = array(
				'taxonomy' => 'places',
				'field'    => 'slug',
				'terms'    => $wp_rest_server['country'],
			);
		}

		if ( $wp_rest_server['state_province'] ) {
			$tax_query[] = array(
				'taxonomy' => 'places',
				'field'    => 'slug',
				'terms'    => $wp_rest_server['state_province'],
			);
		}

		if ( $wp_rest_server['city'] ) {
			$tax_query[] = array(
				'taxonomy' => 'places',
				'field'    => 'slug',
				'terms'    => $wp_rest_server['city'],
			);
		}

		if ( $wp_rest_server['location_type'] ) {
			$tax_query[] = array(
				'taxonomy' => 'location_types',
				'field'    => 'slug',
				'terms'    => $wp_rest_server['location_type'],
			);
		}

		$search_query = ( $wp_rest_server['s'] ) ?: '';

		$args = array(
			'post_type'      => 'locations',
			'posts_per_page' => -1,
			's'              => $search_query,
			'tax_query'      => array(
				$tax_query,
			),
		);

		$locations_query = new WP_Query( $args );

		$locations_query->parse_query( $args );

		// In order to include ACF data w/i the search, execute the query via relevanssi
		if ( function_exists( 'relevanssi_do_query' ) ) {

			$relevanssi_query = relevanssi_do_query( $locations_query );
		}

		if ( $locations_query->have_posts() ) {
			while ( $locations_query->have_posts() ) {
				$locations_query->the_post();

				$id = get_the_ID();

				$posts['posts'][] = array(
					'id'        => $id,
					'title'     => html_entity_decode( get_the_title() ),
					'permalink' => get_permalink( $id ),
					'acf'       => ( function_exists( 'get_fields' ) ) ? get_fields( $id ) : false,
				);
			}
		}

		wp_reset_postdata();

		return $posts;
	}


	/**
	 * ?.? Set Up Places Endpoint
	 *
	 * Maps places custom taxonomy endpoint.
	 *
	**/
	public function enterprise_plugin_register_places_rests_route() {
		register_rest_route(
			'places/v1',
			'/terms/',
			array(
				'method'   => 'GET',
				'callback' => array( $this, 'enterprise_plugin_places_endpoint' ),
			)
		);
	}


	/**
	 * ?.? Set Up Places Endpoint Return
	 *
	 * Sets ups the return associated w/ the places custom taxonomy
	 * when accessed through its rest route.
	 *
	 * @param  obj $wp_rest_server    Server object.
	 *
	 * @return arr $posts             Array containing places terms.
	 *
	**/
	public function enterprise_plugin_places_endpoint( $wp_rest_server ) {
		$terms = array(
			'terms' => array(),
		);

		// Parent REST route.
		// Return terms by parent.
		if ( isset( $wp_rest_server['parent'] ) ) {
			$terms_objects = get_terms(
				'places',
				array(
					'parent' => get_term_by( 'slug', $wp_rest_server['parent'], 'places' )->term_id,
				)
			);

			// Sort term objects name.
			usort(
				$terms_objects,
				function( $term_object_a, $term_object_b ) {
					return strcmp(
						$term_object_a->name,
						$term_object_b->name
					);
				}
			);

			foreach ( $terms_objects as $terms_object ) {
				$terms['terms'][] = array(
					'id'   => $terms_object->term_id,
					'name' => $terms_object->name,
					'slug' => $terms_object->slug,
				);
			}

			return $terms;
		}

		// Cities by country REST route.
		// Return cities by country.
		if ( isset( $wp_rest_server['cities_by_country'] ) ) {

			// Get a list of states/provinces associated w/ passed country.
			$state_provinces = get_terms(
				'places',
				array(
					'parent' => get_term_by( 'slug', $wp_rest_server['cities_by_country'], 'places' )->term_id,
					'fields' => 'slugs',
				)
			);

			$cities = array();

			// Loop through each state/province, getting its city values
			// and mergining them w/ the existing array.
			foreach ( $state_provinces as $state_province ) {
				$cities = array_merge(
					$cities,
					get_terms(
						array(
							'taxonomy' => 'places',
							'parent'   => get_term_by( 'slug', $state_province, 'places' )->term_id,
						)
					)
				);
			}

			// Sort cities by name.
			usort(
				$cities,
				function( $city_a, $city_b ) {
					return strcmp(
						$city_a->name,
						$city_b->name
					);
				}
			);

			// Build out terms (output) array.
			foreach ( $cities as $city ) {
				$terms['terms'][] = array(
					'id'   => $city->term_id,
					'name' => $city->name,
					'slug' => $city->slug,
				);
			}

			return $terms;
		}

		// Cities by state/province REST route.
		// Return cities by state/province.
		if ( isset( $wp_rest_server['cities_by_state_province'] ) ) {

			$cities = array();

			$cities = array_merge(
				$cities,
				get_terms(
					array(
						'taxonomy' => 'places',
						'parent'   => get_term_by( 'slug', $wp_rest_server['cities_by_state_province'], 'places' )->term_id,
					)
				)
			);

			// Sort cities by name.
			usort(
				$cities,
				function( $city_a, $city_b ) {
					return strcmp(
						$city_a->name,
						$city_b->name
					);
				}
			);

			// Build out terms (output) array.
			foreach ( $cities as $city ) {
				$terms['terms'][] = array(
					'id'   => $city->term_id,
					'name' => $city->name,
					'slug' => $city->slug,
				);
			}

			return $terms;
		}

		// Level REST route.
		// Return terms by level
		switch ( $wp_rest_server['level'] ) {
			case 1:
				$terms_objects_0 = get_terms( // Contains top-level terms
					'places',
					array(
						'parent' => 0,
					)
				);

				foreach ( $terms_objects_0 as $terms_object_0 ) {
					$terms_objects_1 = get_terms( // Contains 2nd-level terms
						'places',
						array(
							'parent' => $terms_object_0->term_id,
						)
					);

					// Sort our results.
					usort(
						$terms['terms'],
						function( $a, $b ) {
							return strcmp(
								$a['name'],
								$b['name']
							);
						}
					);

					foreach ( $terms_objects_1 as $terms_object_1 ) {
						$terms['terms'][] = array(
							'id'   => $terms_object_1->term_id,
							'name' => $terms_object_1->name,
							'slug' => $terms_object_1->slug,
						);
					}
				}

				// Sort our results.
				usort(
					$terms['terms'],
					function( $a, $b ) {
						return strcmp(
							$a['name'],
							$b['name']
						);
					}
				);
				break;

			case 2:
				$terms_objects_0 = get_terms( // Contains top-level terms
					'places',
					array(
						'parent' => ( $wp_rest_server['parent'] )
							? get_term_by( 'slug', $wp_rest_server['parent'], 'places' )->term_id
							: 0,
					)
				);

				foreach ( $terms_objects_0 as $terms_object_0 ) {
					$terms_objects_1 = get_terms( // Contains 2nd-level terms
						'places',
						array(
							'parent' => $terms_object_0->term_id,
						)
					);

					foreach ( $terms_objects_1 as $terms_object_1 ) {
						$terms_objects_2 = get_terms( // Contains 3rd-level terms
							'places',
							array(
								'parent' => $terms_object_1->term_id,
							)
						);

						foreach ( $terms_objects_2 as $terms_object_2 ) {
							$terms['terms'][] = array(
								'id'   => $terms_object_2->term_id,
								'name' => $terms_object_2->name,
								'slug' => $terms_object_2->slug,
							);
						}
					}
				}

				// Sort our results.
				usort(
					$terms['terms'],
					function( $a, $b ) {
						return strcmp(
							$a['name'],
							$b['name']
						);
					}
				);
				break;

			case 0:
			default:
				$terms_objects_0 = get_terms(
					'places',
					array(
						'parent' => 0,
					)
				);

				foreach ( $terms_objects_0 as $term_object_0 ) {
					$terms['terms'][] = array(
						'id'   => $term_object_0->term_id,
						'name' => $term_object_0->name,
						'slug' => $term_object_0->slug,
					);
				}

				// Sort our results.
				usort(
					$terms['terms'],
					function( $a, $b ) {
						return strcmp(
							$a['name'],
							$b['name']
						);
					}
				);
				break;
		}

		return $terms;
	}


	/**
	 * ?.? Customize Resources/News Query
	 *
	 * Customize custom post type query. Specfically, limit
	 * returned post number to 9.
	 *
	 * @param  obj $query  WordPress query object.
	 *
	**/
	public function enterprise_plugin_cutomize_resources_news_query( $query ) {

		if ( is_admin() ) {
			return;
		}

		if (
			! is_post_type_archive( 'resources' )
			&& ! is_post_type_archive( 'news' )
		) {
			return;
		}

		if (
			'resources' !== $query->query['post_type']
			&& 'news' !== $query->query['post_type']
		) {
			return;
		}

		$query->set( 'posts_per_page', 9 );
	}


	/**
	 * ?.? Sets Google Maps API Key in Head.
	 *
	 * Used w/i React tools (borders and locations).
	 *
	**/
	public function enterprise_plugin_set_google_maps_api_key() {

		if ( ! is_page_template( 'page-templates/template-tools.php' ) ) {
			return;
		}

		if ( ! function_exists( 'get_field' ) ) {
			return;
		}

		if ( ! get_field( 'api_key_google_maps_borders', 'option' ) ) {
			return;
		}

		$output  = '<script>';
		$output .= 'var googleMapsApiKey';
		$output .= ' = "';
		$output .= ( get_field( 'api_key_google_maps_borders', 'option' ) ) ?: '';
		$output .= '" ';
		$output .= '</script>';

		echo wp_kses(
			$output,
			array(
				'script' => array(),
			)
		);
	}

	/**
	 * ?.? Creates CTA Within Content.
	 *
	 * Shortcode used to create CTA within content.
	 *
	**/
	function enterprise_plugin_create_cta( $atts ) {

		$a = shortcode_atts(
			array(
				'button_style' => '',
				'url'          => '',
				'class'        => '',
				'target'       => '',
				'label'        => 'Learn More',
			),
			$atts
		);

		$output  = '<p ';
		$output .= 'class="';
		$output .= 'content-row-ctas';
		$output .= '">';
		$output .= '<a ';
		$output .= 'href="';
		$output .= esc_url( $a['url'] );
		$output .= '" class="';
		$output .= 'btn ';
		$output .= esc_attr( $a['class'] );
		$output .= '" ';
		$output .= 'target="';
		$output .= esc_attr( $a['target'] );
		$output .= '">';
		$output .= $a['label'];
		$output .= '</a>';
		$output .= '</p>';

		return $output;
	}
}
