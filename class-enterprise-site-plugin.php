<?php // class-enterprise-site-plugin.php
class Enterprise_Site_Plugin {

	/**
	 * Custom post types.
	 *
	 * @var $post_types
	 */
	private $post_types;


	/**
	 * Shortcodes.
	 *
	 * @var $shortcodes
	 */
	private $shortcodes;


	/**
	 * Custom taxonomies.
	 *
	 * @var $taxonomies
	 */
	private $taxonomies;


	/**
	 * Admin Functions.
	 *
	 * @var $admin_functions
	 */
	private $admin_functions;


	/**
	 * Admin filters items.
	 *
	 * @var $admin_filters
	 */
	private $admin_filters;


	/**
	 * Admin action items.
	 *
	 * @var $admin_actions
	 */
	private $admin_actions;


	/**
	 * Public Functions.
	 *
	 * @var $public_functions
	 */
	private $public_functions;


	/**
	 * Public filters items.
	 *
	 * @var $public_filters
	 */
	private $public_filters;


	/**
	 * Public action items.
	 *
	 * @var $public_actions
	 */
	private $public_actions;


	/**
	 * Constructor.
	 *
	 * @param array $post_types
	 */
	public function __construct() {
		$this->load_dependencies();

		$this->post_types       = array();
		$this->shortcodes       = array();
		$this->taxonomies       = array();
		$this->admin_functions  = new Admin_Functions;
		$this->admin_actions    = array();
		$this->admin_filters    = array();
		$this->public_functions = new Public_Functions;
		$this->public_actions   = array();
		$this->public_filters   = array();

		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'plugins_loaded', array( $this, 'register_admin_actions' ) );
		add_action( 'plugins_loaded', array( $this, 'register_admin_filters' ) );
		add_action( 'plugins_loaded', array( $this, 'register_public_actions' ) );
		add_action( 'plugins_loaded', array( $this, 'register_public_filters' ) );
	}


	/**
	 * Loads required dependencies.
	 */
	public function load_dependencies() {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin-functions.php';
		require_once plugin_dir_path( __FILE__ ) . 'public/class-public-functions.php';
	}


	/**
	 * Add an admin action item to be registered.
	 *
	 * @param array $admin_actions_array
	 */
	public function add_admin_actions( $admin_actions_array ) {
		if ( ! is_array( $admin_actions_array ) ) {
			return;
		}

		foreach ( $admin_actions_array as $action => $functions ) {
			foreach ( $functions as $function => $function_args ) {
				$this->admin_actions[] = array(
					'action'        => $action,
					'function'      => $function,
					'priority'      => $function_args['priority'],
					'accepted_args' => $function_args['accepted_args'],
				);
			}
		}
	}


	/**
	 * Register admin actions.
	 */
	public function register_admin_actions() {
		foreach ( $this->admin_actions as $hook ) {
			add_action(
				$hook['action'],
				array( $this->admin_functions, $hook['function'] ),
				$hook['priority'],
				$hook['accepted_args']
			);
		}
	}


	/**
	 * Add an public action item to be registered.
	 *
	 * @param array $public_actions_array
	 */
	public function add_public_actions( $public_actions_array ) {
		if ( ! is_array( $public_actions_array ) ) {
			return;
		}

		foreach ( $public_actions_array as $action => $functions ) {
			foreach ( $functions as $function => $function_args ) {
				$this->public_actions[] = array(
					'action'        => $action,
					'function'      => $function,
					'priority'      => $function_args['priority'],
					'accepted_args' => $function_args['accepted_args'],
				);
			}
		}
	}


	/**
	 * Register public actions.
	 */
	public function register_public_actions() {
		foreach ( $this->public_actions as $hook ) {
			add_action(
				$hook['action'],
				array( $this->public_functions, $hook['function'] ),
				$hook['priority'],
				$hook['accepted_args']
			);
		}
	}


	/**
	 * Add an admin filter item to be registered.
	 *
	 * @param array $admin_filters_array
	 */
	public function add_admin_filters( $admin_filters_array ) {
		if ( ! is_array( $admin_filters_array ) ) {
			return;
		}

		foreach ( $admin_filters_array as $filter => $functions ) {
			foreach ( $functions as $function => $function_args ) {
				$this->admin_filters[] = array(
					'filter'        => $filter,
					'function'      => $function,
					'priority'      => ( $function_args['priority'] ) ?: 10,
					'accepted_args' => ( $function_args['accepted_args'] ) ?: 1,
				);
			}
		}
	}


	/**
	 * Register admin filters.
	 */
	public function register_admin_filters() {
		foreach ( $this->admin_filters as $hook ) {
			add_filter(
				$hook['filter'],
				array( $this->admin_functions, $hook['function'] ),
				$hook['priority'],
				$hook['accepted_args']
			);
		}
	}


	/**
	 * Add an public filter item to be registered.
	 *
	 * @param array $public_filters_array
	 */
	public function add_public_filters( $public_filters_array ) {
		if ( ! is_array( $public_filters_array ) ) {
			return;
		}

		foreach ( $public_filters_array as $filter => $functions ) {
			foreach ( $functions as $function => $function_args ) {
				$this->public_filters[] = array(
					'filter'        => $filter,
					'function'      => $function,
					'priority'      => ( $function_args['priority'] ) ?: 10,
					'accepted_args' => ( $function_args['accepted_args'] ) ?: 1,
				);
			}
		}
	}


	/**
	 * Register public filters.
	 */
	public function register_public_filters() {
		foreach ( $this->public_filters as $hook ) {
			add_filter(
				$hook['filter'],
				array( $this->admin_functions, $hook['function'] ),
				$hook['priority'],
				$hook['accepted_args']
			);
		}
	}


	/**
	 * Add a custom post type to be registered.
	 *
	 * @param array $post_types_array
	 */
	public function add_post_types( $post_types_array ) {
		if ( ! is_array( $post_types_array ) ) {
			return;
		}

		foreach ( $post_types_array as $post_type => $args ) {
			$this->post_types[ $post_type ] = $args;
		}
	}


	/**
	 * Registers custom post types.
	 */
	public function register_post_types() {
		foreach ( $this->post_types as $post_type => $args ) {
			$labels         = array(
				'name'                  => $args['plural'],
				'singular_name'         => $args['singular'],
				'menu_name'             => $args['plural'],
				'name_admin_bar'        => $args['singular'],
				'archives'              => $args['plural'] . ' Archives',
				'attributes'            => $args['singular'] . ' Attributes',
				'parent_item_colon'     => 'Parent ' . $args['singular'] . ':',
				'all_items'             => 'All ' . $args['plural'],
				'add_new_item'          => 'Add New ' . $args['singular'],
				'add_new'               => 'Add New',
				'new_item'              => 'New ' . $args['singular'],
				'edit_item'             => 'Edit ' . $args['singular'],
				'update_item'           => 'Update ' . $args['singular'],
				'view_item'             => 'View ' . $args['singular'],
				'view_items'            => 'View ' . $args['plural'],
				'search_items'          => 'Search ' . $args['singular'],
				'not_found'             => 'Not found',
				'not_found_in_trash'    => 'Not found in Trash',
				'featured_image'        => 'Featured Image',
				'set_featured_image'    => 'Set featured image',
				'remove_featured_image' => 'Remove featured image',
				'use_featured_image'    => 'Use as featured image',
				'insert_into_item'      => 'Insert into ' . $args['singular'],
				'uploaded_to_this_item' => 'Uploaded to this ' . $args['singular'],
				'items_list'            => $args['plural'] . ' list',
				'items_list_navigation' => $args['plural'] . ' list navigation',
				'filter_items_list'     => 'Filter ' . $args['singular'] . ' list',
			);
			$args['labels'] = $labels;

			// Set arguments to defaults unless otherwise specified
			$args['supports']            = ( isset( $args['supports'] ) ) ? $args['supports'] : array(
				'title',
				'editor',
				'thumbnail',
				'revisions',
				'page-attributes',
				'post-formats',
				'author',
				'excerpt',
			);
			$args['taxonomies']          = ( isset( $args['taxonomies'] ) ) ? $args['taxonomies'] : array();
			$args['hierarchical']        = ( isset( $args['hierarchical'] ) ) ? $args['hierarchical'] : false;
			$args['public']              = ( isset( $args['public'] ) ) ? $args['public'] : true;
			$args['show_ui']             = ( isset( $args['show_ui'] ) ) ? $args['show_ui'] : true;
			$args['show_in_menu']        = ( isset( $args['show_in_menu'] ) ) ? $args['show_in_menu'] : true;
			$args['menu_position']       = ( isset( $args['menu_position'] ) ) ? $args['menu_position'] : 5;
			$args['menu_icon']           = ( isset( $args['menu_icon'] ) ) ? $args['menu_icon'] : 'dashicons-admin-post';
			$args['show_in_admin_bar']   = ( isset( $args['show_in_admin_bar'] ) ) ? $args['show_in_admin_bar'] : true;
			$args['show_in_nav_menus']   = ( isset( $args['show_in_nav_menus'] ) ) ? $args['show_in_nav_menus'] : true;
			$args['show_in_rest']        = ( isset( $args['show_in_rest'] ) ) ? $args['show_in_rest'] : true;
			$args['can_export']          = ( isset( $args['can_export'] ) ) ? $args['can_export'] : true;
			$args['has_archive']         = ( isset( $args['has_archive'] ) ) ? $args['has_archive'] : true;
			$args['exclude_from_search'] = ( isset( $args['exclude_from_search'] ) ) ? $args['exclude_from_search'] : false;
			$args['publicly_queryable']  = ( isset( $args['publicly_queryable'] ) ) ? $args['publicly_queryable'] : true;
			$args['capability_type']     = ( isset( $args['capability_type'] ) ) ? $args['capability_type'] : 'page';
			$args['rewrite']             = ( isset( $args['rewrite'] ) ) ? $args['rewrite'] : false;

			register_post_type( $post_type, $args );
		}
	}


	/**
	 * Add shortcodes to be registered.
	 *
	 * @param  array  $shortcodes_array
	 */
	public function add_shortcodes( $shortcodes_array ) {
		if ( ! is_array( $shortcodes_array ) ) {
			return;
		}

		foreach ( $shortcodes_array as $tag => $function ) {
			$this->shortcodes[] = array(
				'tag'      => $tag,
				'function' => $function,
			);
		}
	}


	/**
	 * Registers shortcodes.
	 */
	public function register_shortcodes() {
		foreach ( $this->shortcodes as $shortcode ) {
			add_shortcode(
				$shortcode['tag'],
				array( $this->public_functions, $shortcode['function'] )
			);
		}
	}


	/**
	 * Add custom taxonomies to be registered.
	 *
	 * @param array $taxonomies_array
	 */
	public function add_taxonomies( $taxonomies_array ) {
		if ( ! is_array( $taxonomies_array ) ) {
			return;
		}

		foreach ( $taxonomies_array as $taxonomy => $args ) {
			$this->taxonomies[ $taxonomy ] = $args;
		}
	}


	/**
	 * Registers custom taxonomies.
	 */
	public function register_taxonomies() {
		foreach ( $this->taxonomies as $taxonomy => $args ) {
			$labels         = array(
				'name'                       => $args['plural'],
				'singular_name'              => $args['singular'],
				'menu_name'                  => $args['plural'],
				'all_items'                  => 'All ' . $args['plural'],
				'parent_item_colon'          => 'Parent ' . $args['singular'],
				'parent_item_colon'          => 'Parent ' . $args['singular'] . ':',
				'new_item_name'              => 'Parent ' . $args['singular'],
				'add_new_item'               => 'Add New ' . $args['singular'],
				'edit_item'                  => 'Edit ' . $args['singular'],
				'update_item'                => 'Update Item ' . $args['singular'],
				'view_item'                  => 'View ' . $args['singular'],
				'separate_items_with_commas' => 'Separate ' . $args['plural'] . ' with commas',
				'add_or_remove_items'        => 'Add or remove ' . $args['plural'],
				'choose_from_most_used'      => 'Choose from the most used',
				'popular_items'              => 'Popular ' . $args['plural'],
				'search_items'               => 'Search ' . $args['singular'],
				'not_found'                  => 'Not found',
				'no_terms'                   => 'No ' . $args['plural'],
				'items_list'                 => $args['plural'] . ' list',
				'items_list_navigation'      => $args['plural'] . ' list navigation',
			);
			$args['labels'] = $labels;

			$args['hierarchical']      = ( isset( $args['hierarchical'] ) ) ? $args['hierarchical'] : true;
			$args['meta_box_cb']       = ( isset( $args['meta_box_cb'] ) ) ? $args['meta_box_cb'] : 'post_categories_meta_box';
			$args['public']            = ( isset( $args['public'] ) ) ? $args['public'] : true;
			$args['show_in_rest']      = ( isset( $args['show_in_rest'] ) ) ? $args['show_in_rest'] : true;
			$args['rewrite']           = ( isset( $args['rewrite'] ) ) ? $args['rewrite'] : false;
			$args['show_ui']           = ( isset( $args['show_ui'] ) ) ? $args['show_ui'] : true;
			$args['show_admin_column'] = ( isset( $args['show_admin_column'] ) ) ? $args['show_admin_column'] : true;
			$args['show_in_nav_menus'] = ( isset( $args['show_in_nav_menus'] ) ) ? $args['show_in_nav_menus'] : true;
			$args['show_tagcloud']     = ( isset( $args['show_tagcloud'] ) ) ? $args['show_tagcloud'] : false;

			register_taxonomy(
				$taxonomy,
				$args['post_type'],
				$args
			);
		}
	}
}
