<?php
/**
 * Enqueue all styles and scripts
 *
 * Learn more about enqueue_script: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_script}
 * Learn more about enqueue_style: {@link https://codex.wordpress.org/Function_Reference/wp_enqueue_style }
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

if ( ! function_exists( 'foundationpress_scripts' ) ) :
	function foundationpress_scripts() {

	// Enqueue the main Stylesheet.
	wp_enqueue_style( 'main-stylesheet', get_stylesheet_directory_uri() . '/assets/stylesheets/foundation.css', array(), '2.3.0', 'all' );
    
    // owl carousel actus homepage css
    if (is_home() || is_front_page()) {
    wp_enqueue_style( 'owl-stylesheet', get_stylesheet_directory_uri() . '/assets/stylesheets/owl.carousel.css', array(), '1.0', 'all' );
    }

	// Deregister the jquery version bundled with WordPress.
	wp_deregister_script( 'jquery' );

	// CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
	wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js', array(), '2.1.0', false );

	// If you'd like to cherry-pick the foundation components you need in your project, head over to gulpfile.js and see lines 35-54.
	// It's a good idea to do this, performance-wise. No need to load everything if you're just going to use the grid anyway, you know :)
	wp_enqueue_script( 'foundation', get_template_directory_uri() . '/assets/javascript/foundation.js', array('jquery'), '2.3.0', true );
        
    // ascometal geolocalisation pour téléphone nav header
    wp_enqueue_script( 'geolocalisation', get_template_directory_uri() . '/assets/javascript/custom/geolocalisation.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'googlemapapi', '//maps.google.com/maps/api/js?sensor=true', array('jquery'), '', true );
    
    
    // owl carousel actus homepage js
    if (is_home() || is_front_page()) {
	wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/assets/javascript/custom/owl.carousel.min.js', array('jquery'), '1.0', true );
    }

	// Add the comment-reply library on pages where it is necessary
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	}

	add_action( 'wp_enqueue_scripts', 'foundationpress_scripts' );
endif;
