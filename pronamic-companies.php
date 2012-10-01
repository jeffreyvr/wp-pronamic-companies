<?php
/*
Plugin Name: Pronamic Companies
Plugin URI: http://pronamic.eu/wordpress/companies/
Description: This plugin add some basic company directory functionality to WordPress

Version: 0.1.2
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: pronamic_companies
Domain Path: /languages/

License: GPL

GitHub URI: https://github.com/pronamic/wp-pronamic-companies
*/

class Pronamic_Companies_Plugin {
	/**
	 * The plugin file
	 * 
	 * @var string
	 */
	public static $file;

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap( $file ) {
		self::$file = $file;

		add_action( 'init',           array( __CLASS__, 'init' ) );
		add_action( 'admin_init',     array( __CLASS__, 'admin_init' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() {
		// Text domain
		$rel_path = dirname( plugin_basename( self::$file ) ) . '/languages/';
	
		load_plugin_textdomain( 'pronamic_companies', false, $rel_path );
	
		// Require
		require_once dirname( self::$file ) . '/includes/taxonomy.php';
		require_once dirname( self::$file ) . '/includes/gravityforms.php';
	
		// Post types
		$slug = get_option( 'pronamic_company_base' );
		$slug = empty( $slug ) ? _x( 'companies', 'slug', 'pronamic_companies' ) : $slug;
	
		register_post_type( 'pronamic_company', array(
			'labels'             => array(
				'name'               => _x( 'Companies', 'post type general name', 'pronamic_companies' ), 
				'singular_name'      => _x( 'Company', 'post type singular name', 'pronamic_companies' ), 
				'add_new'            => _x( 'Add New', 'company', 'pronamic_companies' ), 
				'add_new_item'       => __( 'Add New Company', 'pronamic_companies' ), 
				'edit_item'          => __( 'Edit Company', 'pronamic_companies' ), 
				'new_item'           => __( 'New Company', 'pronamic_companies' ), 
				'view_item'          => __( 'View Company', 'pronamic_companies' ), 
				'search_items'       => __( 'Search Companies', 'pronamic_companies' ), 
				'not_found'          => __( 'No companies found', 'pronamic_companies' ), 
				'not_found_in_trash' => __( 'No companies found in Trash', 'pronamic_companies' ),  
				'parent_item_colon'  => __( 'Parent Company:', 'pronamic_companies' ), 
				'menu_name'          => __( 'Companies', 'pronamic_companies' )
			) , 
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'rewrite'            => array( 'slug' => $slug ), 
			'menu_icon'          => plugins_url( 'admin/icons/company.png', __FILE__ ), 
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields' ) 
		));
	
		pronamic_companies_create_taxonomies();
	}

	/**
	 * Admin initialize
	 */
	public static function admin_init() {
		// Un we can't add the permalink options to permalink settings page
		// @see http://core.trac.wordpress.org/ticket/9296
		add_settings_section(
			'pronamic_copmanies_permalink', // id
			__( 'Permalinks', 'pronamic_companies' ), // title
			'pronamic_copmanies_settings_section_permalink', // callback
			'pronamic_companies' // page
		);
	
		add_settings_field( 
			'pronamic_company_base', // id
			__( 'Company base', 'pronamic_companies' ), // title
			'pronamic_companies_slug_base_input',  // callback
			'pronamic_companies', // page
			'pronamic_copmanies_permalink', // section 
			array( 'label_for' => 'pronamic_company_base' ) // args 
		);
	
		add_settings_field( 
			'pronamic_company_category_base', // id
			__( 'Category base', 'pronamic_companies' ), // title
			'pronamic_companies_slug_base_input',  // callback
			'pronamic_companies', // page
			'pronamic_copmanies_permalink', // section 
			array( 'label_for' => 'pronamic_company_category_base' ) // args 
		);
	
		add_settings_field( 
			'pronamic_company_character_base', // id
			__( 'Character base', 'pronamic_companies' ), // title
			'pronamic_companies_slug_base_input',  // callback
			'pronamic_companies', // page
			'pronamic_copmanies_permalink', // section 
			array( 'label_for' => 'pronamic_company_character_base' ) // args 
		);
	
		add_settings_field( 
			'pronamic_company_region_base', // id
			__( 'Region base', 'pronamic_companies' ), // title
			'pronamic_companies_slug_base_input',  // callback
			'pronamic_companies', // page
			'pronamic_copmanies_permalink', // section 
			array( 'label_for' => 'pronamic_company_region_base' ) // args 
		);
	
		add_settings_field( 
			'pronamic_company_keyword_base', // id
			__( 'Keyword base', 'pronamic_companies' ), // title
			'pronamic_companies_slug_base_input',  // callback
			'pronamic_companies', // page
			'pronamic_copmanies_permalink', // section 
			array( 'label_for' => 'pronamic_company_keyword_base' ) // args 
		);
	
		add_settings_field( 
			'pronamic_company_brand_base', // id
			__( 'Brand base', 'pronamic_companies' ), // title
			'pronamic_companies_slug_base_input',  // callback
			'pronamic_companies', // page
			'pronamic_copmanies_permalink', // section 
			array( 'label_for' => 'pronamic_company_brand_base' ) // args 
		);
	
		add_settings_field( 
			'pronamic_company_type_base', // id
			__( 'Type base', 'pronamic_companies' ), // title
			'pronamic_companies_slug_base_input',  // callback
			'pronamic_companies', // page
			'pronamic_copmanies_permalink', // section 
			array( 'label_for' => 'pronamic_company_type_base' ) // args 
		);
	
		register_setting( 'pronamic_companies', 'pronamic_company_base' );
		register_setting( 'pronamic_companies', 'pronamic_company_category_base' );
		register_setting( 'pronamic_companies', 'pronamic_company_character_base' );
		register_setting( 'pronamic_companies', 'pronamic_company_region_base' );
		register_setting( 'pronamic_companies', 'pronamic_company_keyword_base' );
		register_setting( 'pronamic_companies', 'pronamic_company_brand_base' );
		register_setting( 'pronamic_companies', 'pronamic_company_type_base' );
	}
}

Pronamic_Companies_Plugin::bootstrap( __FILE__ );

/**
 * Flush data
 */
function pronamic_companies_rewrite_flush() {
    pronamic_companies_init();

    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'pronamic_companies_rewrite_flush' );

////////////////////////////////////////////////////////////

function pronamic_copmanies_settings_section_permalink() {
	
}

function pronamic_companies_slug_base_input( $args ) {
	printf(
		'<input name="%s" id="%s" type="text" value="%s" class="%s" />', 
		esc_attr( $args['label_for'] ),
		esc_attr( $args['label_for'] ),
		esc_attr( get_option( $args['label_for'] ) ),
		'regular-text code'
	);
}

////////////////////////////////////////////////////////////

function pronamic_companies_admin_enqueue(){
	wp_enqueue_style( 'pronamic_companies', plugins_url('/assets/css/admin.css', __FILE__ ) );
}

add_action( 'admin_enqueue_scripts', 'pronamic_companies_admin_enqueue' );

/**
 * Meta boxes
 */
add_action( 'add_meta_boxes', 'pronamic_companies_add_information_box' );

/* Add metaboxes */
function pronamic_companies_add_information_box() {
    add_meta_box( 
        'pronamic_companies_meta_box' , // id
        __( 'Company information', 'pronamic_companies') , // title
        'pronamic_companies_information_box' , // callback
        'pronamic_company' , // post_type
        'normal' , // context
        'high' // priority
    );
}

/**
 * Print metaboxes
 */
function pronamic_companies_information_box( $post ) {
	include 'admin/meta-box.php';
}

/**
 * Save metaboxes
 */
function pronamic_companies_save_post( $post_id ) {
	global $post;

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	if( ! isset( $_POST['pronamic_companies_nonce'] ) )
		return;

	if( ! wp_verify_nonce( $_POST['pronamic_companies_nonce'], 'pronamic_companies_save_post' ) )
		return;

	if( ! current_user_can( 'edit_post', $post->ID ) )
		return;
		
	// Save data
	$data = filter_input_array( INPUT_POST, array(
		// Visiting Address
		'_pronamic_company_address' => FILTER_SANITIZE_STRING , 
		'_pronamic_company_postal_code' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_city' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_country' => FILTER_SANITIZE_STRING ,
		// Mailing Address
		'_pronamic_company_mailing_address' => FILTER_SANITIZE_STRING , 
		'_pronamic_company_mailing_postal_code' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_mailing_city' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_mailing_country' => FILTER_SANITIZE_STRING ,
		// Phone
		'_pronamic_company_phone_number' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_fax_number' => FILTER_SANITIZE_STRING ,
		// Online
		'_pronamic_company_email' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_website' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_rss' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_video' => FILTER_SANITIZE_STRING ,
		// Social Networks
		'_pronamic_company_twitter' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_facebook' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_linkedin' => FILTER_SANITIZE_STRING ,
		'_pronamic_company_google_plus' => FILTER_SANITIZE_STRING 
	));

	foreach( $data as $key => $value ) {
		update_post_meta( $post_id, $key, $value );
	}

	// Google Maps address
	$address  = '';

	$address .= $data['_pronamic_company_address'] . "\r\n";
	$address .= $data['_pronamic_company_postal_code'] . ' ' . $data['_pronamic_company_city'] . "\r\n";
	$address .= $data['_pronamic_company_country'];

	update_post_meta( $post_id, '_pronamic_google_maps_address', $address );
}


add_action( 'save_post', 'pronamic_companies_save_post', 50 );

/**
 * Columns
 * 
 * @param array $columns
 */
function pronamic_companies_set_columns( $columns ) {
	$new_columns = array();

	if( isset( $columns['cb'] ) ) {
		$new_columns['cb'] = $columns['cb'];
	}

	// $newColumns['thumbnail'] = __('Thumbnail', 'pronamic_companies');

	if( isset( $columns['title'] ) ) {
		$new_columns['title'] = __( 'Company', 'pronamic_companies' );
	}

	if( isset( $columns['author'] ) ) {
		$new_columns['author'] = $columns['author'];
	}

	$new_columns['pronamic_company_address'] = __( 'Address', 'pronamic_companies' );

	$new_columns['pronamic_company_categories'] = __( 'Categories', 'pronamic_companies' );

	if( isset( $columns['comments'] ) ) {
		$new_columns['comments'] = $columns['comments'];
	}

	if( isset( $columns['date'] ) ) {
		$new_columns['date'] = $columns['date'];
	}
	
	return $new_columns;
}

add_filter( 'manage_edit-pronamic_company_columns' , 'pronamic_companies_set_columns' );

function pronamic_companies_custom_columns( $column, $post_id ) {
	switch( $column ) {
		case 'pronamic_company_address':
			echo get_post_meta( $post_id, '_pronamic_company_address', true ), '<br />';
			echo get_post_meta( $post_id, '_pronamic_company_postal_code', true ), ' ', get_post_meta( $post_id, '_pronamic_company_city', true );

			break;
		case 'pronamic_company_categories':
			$terms = get_the_term_list( $post_id, 'pronamic_company_category' , '' , ', ' , '' );

			if( is_string( $terms ) ) {
				echo $terms;
			} else {
				echo __( 'No Category', 'pronamic_companies' );
			}

			break;
	}
}

add_action( 'manage_posts_custom_column' , 'pronamic_companies_custom_columns', 10, 2 );

/**
 * Posts 2 Posts
 */
function pronamic_companies_p2p() {
	if ( function_exists( 'p2p_register_connection_type' ) ) {
		p2p_register_connection_type( array(
			'name' => 'posts_to_pronamic_companies',
			'from' => 'post',
			'to'   => 'pronamic_company'
		) );
	}
}

add_action( 'wp_loaded', 'pronamic_companies_p2p' );

/**
 * Admin menu
 */
function pronamic_companies_admin_menu() {
	add_submenu_page( 
		'edit.php?post_type=pronamic_company' , // parent_slug
		__( 'Companies Export', 'pronamic_companies' ) , // page_title
		__( 'Export', 'pronamic_companies' ), // menu_title
		'read' , // capability
		'pronamic_companies_export' , // menu_slug
		'pronamic_companies_page_export' // function 
	);

	add_submenu_page( 
		'edit.php?post_type=pronamic_company' , // parent_slug
		__( 'Companies Settings', 'pronamic_companies' ) , // page_title
		__( 'Settings', 'pronamic_companies' ), // menu_title
		'read' , // capability
		'pronamic_companies_settings' , // menu_slug
		'pronamic_companies_page_settings' // function 
	);
}

add_action( 'admin_menu', 'pronamic_companies_admin_menu' );

/**
 * Page export
 */
function pronamic_companies_page_export() {
	include 'admin/export.php';
}

/**
 * Page settings
 */
function pronamic_companies_page_settings() {
	include 'admin/settings.php';
}
