<?php
/*
Plugin Name: Enterprise Site Plugin
Plugin URI: https://enterprise-site.test
Description: A plugin containing data functionality related to the core operation of the site.
Version: 1.0.0
Author: Nathan M. House
Author URI: https://nathanmhouse.com
License: GPLv3
*/

require_once plugin_dir_path( __FILE__ ) . 'class-enterprise-site-plugin.php';
$enterprise_site_plugin = new Enterprise_Site_Plugin;

// Register custom post types
$enterprise_site_plugin->add_post_types(
	array(
		'resources'          => array(
			'singular'      => 'Resource',
			'plural'        => 'Resources',
			'description'   => 'An informative, useful, and educational resource material.',
			'menu_position' => 4,
			'menu_icon'     => 'dashicons-archive',
			'rewrite'       => array(
				'slug'       => 'resources',
				'with_front' => false,
			),
		),
		'news'               => array(
			'singular'    => 'News',
			'plural'      => 'News',
			'description' => 'Noteworthy information, especially about recent or important events.',
			'menu_icon'   => 'dashicons-media-document',
			'rewrite'     => array(
				'slug'       => 'news',
				'with_front' => false,
			),
		),
		'terms'              => array(
			'singular'            => 'Term',
			'plural'              => 'Terms',
			'description'         => 'Industry terms for use within the glossary tool.',
			'menu_icon'           => 'dashicons-book-alt',
			'rewrite'             => array(
				'slug'       => 'glossary',
				'with_front' => false,
			),
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
		),
		'external_resources' => array(
			'singular'            => 'External Resource',
			'plural'              => 'External Resources',
			'description'         => 'External resources (e.g. websites) for use within the external trade resource tool.',
			'menu_icon'           => 'dashicons-admin-links',
			'rewrite'             => array(
				'slug'       => 'external-resources',
				'with_front' => false,
			),
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
		),
		'borders'            => array(
			'singular'            => 'Border',
			'plural'              => 'Borders',
			'description'         => 'Crossing that is used as a point of interchange in the traffic of goods (not necessarily a geographic border).',
			'menu_icon'           => 'dashicons-admin-site',
			'rewrite'             => array(
				'slug'       => 'borders',
				'with_front' => false,
			),
			'has_archive'         => false,
			'exclude_from_search' => false,
		),
		'locations'          => array(
			'singular'            => 'Location',
			'plural'              => 'Locations',
			'description'         => 'Enterprise Site location (e.g. office, site, border outpost etc.',
			'menu_icon'           => 'dashicons-location',
			'rewrite'             => array(
				'slug'       => 'locations',
				'with_front' => false,
			),
			'has_archive'         => false,
			'exclude_from_search' => false,
		),
	)
);

// Register custom taxonomies
$enterprise_site_plugin->add_taxonomies(
	array(

		// Resource Types (Resources)
		'resource_types'          => array(
			'singular'  => 'Type',
			'plural'    => 'Types',
			'post_type' => array(
				'resources',
			),
			'rewrite'   => array(
				'slug'       => 'resources/resource-types',
				'with_front' => false,
			),
		),

		// Regions (Resources)
		'regions'                 => array(
			'singular'  => 'Region',
			'plural'    => 'Regions',
			'post_type' => array(
				'resources',
			),
			'rewrite'   => array(
				'slug'       => 'resources/regions',
				'with_front' => false,
			),
		),

		// Needs (Resources)
		'needs'                   => array(
			'singular'  => 'Need',
			'plural'    => 'Needs',
			'post_type' => array(
				'resources',
			),
			'rewrite'   => array(
				'slug'       => 'resources/needs',
				'with_front' => false,
			),
		),

		// Industries (Resources)
		'industries'              => array(
			'singular'  => 'Industry',
			'plural'    => 'Industries',
			'post_type' => array(
				'resources',
			),
			'rewrite'   => array(
				'slug'       => 'resources/industries',
				'with_front' => false,
			),
		),

		// News Types (News)
		'news_types'              => array(
			'singular'  => 'Type',
			'plural'    => 'Types',
			'post_type' => array(
				'news',
			),
			'rewrite'   => array(
				'slug'       => 'news/news-types',
				'with_front' => false,
			),
		),

		// Term Types (Terms)
		'term_types'              => array(
			'singular'  => 'Type',
			'plural'    => 'Types',
			'post_type' => array(
				'terms',
			),
		),

		// External Resources Types (External Resources)
		'external_resource_types' => array(
			'singular'  => 'Type',
			'plural'    => 'Types',
			'post_type' => array(
				'external_resources',
			),
		),

		// Border Origins (Borders)
		'origins'                 => array(
			'singular'  => 'Origin',
			'plural'    => 'Origins',
			'post_type' => array(
				'borders',
			),
		),

		// Border Destinations (Borders)
		'destinations'            => array(
			'singular'  => 'Destination',
			'plural'    => 'Destinations',
			'post_type' => array(
				'borders',
			),
		),

		// Location Places (Locations)
		'places'                  => array(
			'singular'  => 'Place',
			'plural'    => 'Places',
			'post_type' => array(
				'locations',
			),
		),

		// Location Types (Locations)
		'location_types'          => array(
			'singular'  => 'Type',
			'plural'    => 'Types',
			'post_type' => array(
				'locations',
			),
		),
	)
);

$enterprise_site_plugin->add_admin_filters(
	array(
		'admin_post_thumbnail_html' => array(
			'add_featured_image_type_toggle' => array(
				'priority'      => 10,
				'accepted_args' => 2,
			),
		),
	)
);

$enterprise_site_plugin->add_admin_actions(
	array(

		'save_post'  => array(
			'save_post_meta' => array(
				'priority'      => 9999,
				'accepted_args' => 1,
			),
		),

		'admin_menu' => array(
			'remove_menus' => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
		),
	)
);

$enterprise_site_plugin->add_public_actions(
	array(
		'rest_api_init'     => array(
			'enterprise_plugin_register_terms_rest_route'              => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
			'enterprise_plugin_register_external_resources_rest_route' => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
			'enterprise_plugin_register_borders_rests_route'           => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
			'enterprise_plugin_register_locations_rests_route'         => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
			'enterprise_plugin_register_places_rests_route'            => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
		),
		'wp_head'           => array(
			'enterprise_plugin_set_google_maps_api_key' => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
		),
		'template_redirect' => array(
			'enterprise_plugin_create_terms_single_redirect'              => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
			'enterprise_plugin_create_external_resources_single_redirect' => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
			'enterprise_plugin_create_borders_single_redirect'            => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
		),
		'pre_get_posts'     => array(
			'enterprise_plugin_cutomize_resources_news_query'             => array(
				'priority'      => 10,
				'accepted_args' => 1,
			),
		),
	)
);

$enterprise_site_plugin->add_shortcodes(
	array(
		'create_cta' => 'enterprise_plugin_create_cta',
	)
);
