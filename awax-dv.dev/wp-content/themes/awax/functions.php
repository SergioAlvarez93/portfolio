<?php  

add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'style-min', get_template_directory_uri() . '/assets/css/style.min.css', [], filemtime(TEMPLATEPATH . '/assets/css/style.min.css'));
	wp_enqueue_script( 'main-min', get_template_directory_uri() . '/assets/js/main.min.js', array(), filemtime(TEMPLATEPATH . '/assets/js/main.min.js'), true );
});

add_action( 'after_setup_theme', function () {
	register_nav_menu( 'menu', 'navbar' );
});

function add_additional_class_on_li($classes, $item, $args) {
    if(isset($args->add_li_class)) {
        $classes[] = $args->add_li_class;
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);


function filter_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	if ( isset($args->add_a_class) ) {
		$class = 'nav-link';
		$atts['class'] = isset( $atts['class'] ) ? "{$atts['class']} $class" : $class;
	}
	return $atts;
}

add_filter( 'nav_menu_link_attributes', 'filter_nav_menu_link_attributes', 10, 4 );

add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );
add_theme_support( 'custom-logo' );

add_filter( 'upload_mimes', 'svg_upload_allow' );

function svg_upload_allow( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}

add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );

function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ){
	if( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) ){
		$dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
	}
	else {
		$dosvg = ( '.svg' === strtolower( substr( $filename, -4 ) ) );
	}
	if( $dosvg ){
		if( current_user_can('manage_options') ){

			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		else {
			$data['ext']  = false;
			$data['type'] = false;
		}

	}

	return $data;
}

add_filter( 'get_custom_logo', 'change_logo_class' );

function change_logo_class( $html ) {
    $html = str_replace( 'custom-logo-link', 'navbar-brand', $html );
    
    return $html;
}

?>