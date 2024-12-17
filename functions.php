<?php

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'parallax_enqueue_scripts_styles', 1000 );
function parallax_enqueue_scripts_styles() {
// Styles
	wp_enqueue_style( 'icomoon-fonts', get_stylesheet_directory_uri() . '/icomoon.css', array() );
	wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/style.css', array() );

// Scripts
wp_enqueue_script( 'custom-scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array() );
}

// Removes Query Strings from scripts and styles
function remove_script_version( $src ){
    if ( strpos( $src, 'uploads/bb-plugin' ) !== false || strpos( $src, 'uploads/bb-theme' ) !== false ) {
      return $src;
    }
    else {
      $parts = explode( '?ver', $src );
      return $parts[0];
    }
  }
  add_filter( 'script_loader_src', 'remove_script_version', 15, 1 );
  add_filter( 'style_loader_src', 'remove_script_version', 15, 1 );
  
  // Add Additional Image Sizes
  add_image_size( 'news-thumb', 260, 150, false );
  add_image_size( 'news-full', 800, 300, false );
  add_image_size( 'sidebar-thumb', 200, 150, false );
  add_image_size( 'blog-thumb', 333, 167, true );
  add_image_size( 'mailchimp', 564, 9999, false );
  add_image_size( 'amp', 600, 9999, false );
  add_image_size( 'home-news', 385, 227, true );
  add_image_size( 'subpage-header', 536, 221, true );
  add_image_size( 'service-full', 473, 444, true );
  add_image_size( 'woo-thumb', 235, 235, true );
  add_image_size( 'woo-full', 416, 416, true );
  
  // Gravity Forms confirmation anchor on all forms
  add_filter( 'gform_confirmation_anchor', '__return_true' );
  
  //Sets the number of revisions for all post types
  add_filter( 'wp_revisions_to_keep', 'revisions_count', 10, 2 );
  function revisions_count( $num, $post ) {
      $num = 3;
      return $num;
  }
  
  // Enable Featured Images in RSS Feed and apply Custom image size so it doesn't generate large images in emails
  function featuredtoRSS($content) {
  global $post;
  if ( has_post_thumbnail( $post->ID ) ){
  $content = '<div>' . get_the_post_thumbnail( $post->ID, 'mailchimp', array( 'style' => 'margin-bottom: 15px;' ) ) . '</div>' . $content;
  }
  return $content;
  }
   
  add_filter('the_excerpt_rss', 'featuredtoRSS');
  add_filter('the_content_feed', 'featuredtoRSS');


//Attributes shortcode callback
function thrive_attributes_shortcode() {
  	global $product; 
	$html = '';
	// Get product attributes
	$attributes = $product->get_attributes();
if ( ! $attributes ) {
    echo "No attributes";
}
foreach ( $attributes as $attribute ) {
		$attributeTermsDetails = get_the_terms( get_the_ID() , $attribute['name']);
		$attributes_li = '';
		$attributeName = str_replace("pa_","",$attribute['name']);
		$attributeName = str_replace("-"," ",$attributeName);
	
		foreach ( $attributeTermsDetails as $attributeTermDetails ) {
			$attributes_li .= '<li>' . $attributeTermDetails->name . '</li>';
        }
		$html .= '<div class="attribute-box '.$attribute['name'].'">';
			$html .= '<p>'.$attributeName.'</p>';
			$html .= '<ul>';
			$html .= $attributes_li;
			$html .= '</ul>';
		$html .= '</div>';
}
  return $html;
}
add_shortcode( 'display_attributes', 'thrive_attributes_shortcode' );

//Remove Gutenberg Block Library CSS from loading on the frontend
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
} 
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );

add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'font-awesome' ); // FontAwesome 4
    wp_enqueue_style( 'font-awesome-5' ); // FontAwesome 5

    //wp_dequeue_style( 'jquery-magnificpopup' );
    //wp_dequeue_script( 'jquery-magnificpopup' );

    wp_dequeue_script( 'bootstrap' );
    //wp_dequeue_script( 'imagesloaded' );
    wp_dequeue_script( 'jquery-fitvids' );
    //wp_dequeue_script( 'jquery-throttle' );
    wp_dequeue_script( 'jquery-waypoints' );

    wp_dequeue_style( 'SFSImainCss' );
    wp_dequeue_style( 'SFSIforumnotification' );
    wp_dequeue_script( 'SFSIjqueryModernizr' );
    wp_dequeue_script( 'SFSIjqueryShuffle' );
    wp_dequeue_script( 'SFSIjqueryrandom-shuffle' );
    wp_dequeue_script( 'SFSICustomJs' );
}, 9999 );

/* Site Optimization - Removing several assets from Home page that we dont need */

// Remove Assets from HOME page only
function remove_home_assets() {
  if (is_front_page()) {
      
	  wp_dequeue_style('yoast-seo-adminbar');
	  wp_dequeue_style('font-awesome');
	  wp_dequeue_style('wpautoterms_css');
    wp_dequeue_style('wc-blocks-vendors-style');
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('woocommerce-layout');
    wp_dequeue_style('woocommerce-smallscreen');
    wp_dequeue_style('woocommerce-general');
    wp_dequeue_style('prdctfltr');
	  
	  wp_dequeue_script('wpautoterms_base');
	  wp_dequeue_script('wc-cart-fragments');
  }
  
};
add_action( 'wp_enqueue_scripts', 'remove_home_assets', 9999 );


//Removing unused Default Wordpress Emoji Script - Performance Enhancer
function disable_emoji_dequeue_script() {
    wp_dequeue_script( 'emoji' );
}
add_action( 'wp_print_scripts', 'disable_emoji_dequeue_script', 100 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 ); 
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Removes Emoji Scripts 
add_action('init', 'remheadlink');
function remheadlink() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_shortlink_header', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
}