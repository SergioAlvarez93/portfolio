<?php  
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '5.0.2');
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/css/slick.css', array(), '1.4');
	wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css', [], filemtime(TEMPLATEPATH . '/style.css'));
	wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css', [], filemtime(TEMPLATEPATH . '/css/responsive.css'));
	wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery-core', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js');
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'maskedinput', get_template_directory_uri() . '/js/jquery.maskedinput.min.js', array('jquery'), '1.4.1', true );
	wp_enqueue_script( 'match-heigh', get_template_directory_uri() . '/js/jquery.matchHeight-min.js', array('jquery'), '0.7.2', true );
	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/js/bootstrap.bundle.min.js', array('jquery'), '5.0.2', true );
	wp_enqueue_script( 'slick-js', get_template_directory_uri() . '/js/slick.min.js', array('jquery'), '1.4', true );
	wp_enqueue_script( 'main-js', get_template_directory_uri() . '/js/main.js', array('jquery'), filemtime(TEMPLATEPATH . '/js/main.js'), true );
});

add_action( 'after_setup_theme', function () {
	register_nav_menu( 'menu', 'navbar' );
});

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

function make_shortcode() {
    return '<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-form">Записаться на пробное занятие</button>';
}
add_shortcode('modal_btn', 'make_shortcode');

// h1 title, breadCrumbs, kama excerpt

function tBreadcrumbs($echo = true) {
	$crumbs = '';
	$crumbsArr = array();

	$formats = array(
		'home' => '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
		<a href="%s" itemprop="item">
		<span itemprop="name">Главная</span>
		</a>
		</span>',
		'link' => '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
		<a href="%s" itemprop="item">
		<span itemprop="name">%s</span>
		</a>
		</span>',
		'item' => '<span>%s</span>',
		'last' => '<span class="current">%s</span>',
		'sep' => PHP_EOL . '<span class="navigation-pipe"> / </span><span class="navigation-pipe-svg"><svg class="icon" width="24px" height="24px"><use xlink:href="' . get_bloginfo('template_directory') . '/icons/sprite.svg#arrow-right"></use></svg></span>' . PHP_EOL
	);

	function termRec($curTerm = null, $addCur = true) {
		$result = array();
		if ($curTerm) {
			if ($parent = $curTerm->parent) {
				$termParent = get_term_by('id', $parent, $curTerm->taxonomy);
				if ($termParent) {
					$parents = termRec($termParent);
					$result = $parents + $result;
				}
			}

			if ($addCur) {
				$curTerm->link = get_term_link($curTerm->term_id, $curTerm->taxonomy);
				$curTerm->anchor = $curTerm->name;
				$result[$curTerm->term_id] = $curTerm;
			}
		}

		return $result;
	}
	function pageRec($curPage = null, $addCur = true) {
		$result = array();
		if ($curPage) {
			if (!$parent = $curPage->post_parent) {
				$parent = get_post_meta($curPage->ID, 'page_parent', true);
				if (is_array($parent)) {
					$parent = $parent[0];
				}
			}
			if ($parent) {
				if ($pageParent = get_post($parent)) {
					$parents = pageRec($pageParent);
					$result = $parents + $result;
				}
			}

			if ($addCur) {
				$curPage->link = get_permalink($curPage->ID);
				$curPage->anchor = $curPage->post_title;
				$result[$curPage->ID] = $curPage;
			}
		}

		return $result;
	}
	function buildCrumbs($crumbs = null, $formats = array()) {
		$result = array();

		$result[] = sprintf($formats['home'], get_bloginfo('url'));
		$count = count($crumbs);
		foreach ((array) $crumbs as $num => $crumb) {
			if (is_array($crumb)) $crumb = (object) $crumb;
			$last = $num == $count - 1;
			$link = (!empty($crumb->link)) ? $crumb->link : null;
			$anchor = (!empty($crumb->anchor)) ? $crumb->anchor : null;

			if ($last) {
				$result[] = sprintf($formats['last'], $anchor);
			} elseif ($link) {
				$result[] = sprintf($formats['link'], $link, $anchor);
			} else {
				$result[] = sprintf($formats['item'], $anchor);
			}
		}

		return $result;
	}

	$object = get_queried_object();
	if (is_singular()) {
		$type = $object->post_type;
		$object->anchor = $object->post_title;
		switch ($type) {
			case 'courses':
			$terms = null;
			$crumbsArr[] = (object) [
				'link' => get_permalink(79),
				'anchor' => get_the_title(79),
			];
			break;

			case 'post':
			$terms = get_the_terms($object->ID, 'category');
			$term = array_shift($terms);
			$crumbsArr = array_merge($crumbsArr, termRec($term));
			break;

			case 'page':
			$crumbsArr = array_merge($crumbsArr, pageRec($object, false));
			break;


			case 'attachment':
			$crumbsArr = array_merge($crumbsArr, pageRec($object, false));
			break;

			default:
			$crumbsArr = array_merge($crumbsArr, pageRec($object, false));
			break;
		}
		$crumbsArr[] = $object;
	} elseif(is_tax() || is_category() || is_tag()) {
		$object->anchor = $object->name;

		$crumbsArr = array_merge($crumbsArr, termRec($object, false));

		$crumbsArr[] = $object;
	} elseif (is_search()) {
		$crumbsArr[] = array('anchor' => 'Результат поиска');
	} elseif (is_404()) {
		$crumbsArr[] = array('anchor' => 'Страница не найдена');
	}
	

	$crumbsArr = buildCrumbs($crumbsArr, $formats);

	$crumbs = '<div id="tBreadrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';
	$crumbs .= implode($formats['sep'], $crumbsArr);
	$crumbs .= '</div>';

	if ($echo) {
		echo $crumbs;
	} else {
		return $crumbs;
	}
}

function h1_title() {
	global $wp_query;
	if (is_singular()) {
		if ($h1=get_field('h1')) 
			echo $h1;
		else 
			the_title();
	} 	
	elseif (is_archive()) {
		$object = get_queried_object();
		if ($h1=get_field('h1', "{$object->taxonomy}_{$object->term_id}")) 
			echo $h1; 
		elseif (is_day())
			printf( __( 'Daily Archives: %s', 'triada' ), get_the_date() );
		elseif (is_month())
			printf( __( 'Monthly Archives: %s', 'triada' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'triada' ) ) );
		elseif (is_year())
			printf( __( 'Yearly Archives: %s', 'triada' ), get_the_date( _x( 'Y', 'yearly archives date format', 'triada' ) ) );
		else
			echo $object->name;
	}
	elseif (is_search())
		printf( __( 'Результат поиска для: %s' ), get_search_query() );
	elseif (is_404()) 
		echo 'Страница не найдена';
}

function kama_excerpt( $args = '' ){
	global $post;

	if( is_string( $args ) ){
		parse_str( $args, $args );
	}

	$rg = (object) array_merge( [
		'maxchar'           => 350,
		'text'              => '',
		'autop'             => true,
		'more_text'         => 'Читать дальше...',
		'ignore_more'       => false,
		'save_tags'         => '<strong><b><a><em><i><var><code><span>',
		'sanitize_callback' => static function( string $text, object $rg ){
			return strip_tags( $text, $rg->save_tags );
		},
	], $args );

	$rg = apply_filters( 'kama_excerpt_args', $rg );

	if( ! $rg->text ){
		$rg->text = $post->post_excerpt ?: $post->post_content;
	}

	$text = $rg->text;
	// strip content shortcodes: [foo]some data[/foo]. Consider markdown
	$text = preg_replace( '~\[([a-z0-9_-]+)[^\]]*\](?!\().*?\[/\1\]~is', '', $text );
	// strip others shortcodes: [singlepic id=3]. Consider markdown
	$text = preg_replace( '~\[/?[^\]]*\](?!\()~', '', $text );
	// strip direct URLs
	$text = preg_replace( '~(?<=\s)https?://.+\s~', '', $text );
	$text = trim( $text );

	// <!--more-->
	if( ! $rg->ignore_more && strpos( $text, '<!--more-->' ) ){

		preg_match( '/(.*)<!--more-->/s', $text, $mm );

		$text = trim( $mm[1] );

		$text_append = sprintf( ' <a href="%s#more-%d">%s</a>', get_permalink( $post ), $post->ID, $rg->more_text );
	}
	// text, excerpt, content
	else {

		$text = call_user_func( $rg->sanitize_callback, $text, $rg );
		$has_tags = false !== strpos( $text, '<' );

		// collect html tags
		if( $has_tags ){
			$tags_collection = [];
			$nn = 0;

			$text = preg_replace_callback( '/<[^>]+>/', static function( $match ) use ( & $tags_collection, & $nn ){
				$nn++;
				$holder = "~$nn";
				$tags_collection[ $holder ] = $match[0];

				return $holder;
			}, $text );
		}

		// cut text
		$cuted_text = mb_substr( $text, 0, $rg->maxchar );
		if( $text !== $cuted_text ){

			// del last word, it not complate in 99%
			$text = preg_replace( '/(.*)\s\S*$/s', '\\1...', trim( $cuted_text ) );
		}

		// bring html tags back
		if( $has_tags ){
			$text = strtr( $text, $tags_collection );
			$text = force_balance_tags( $text );
		}
	}

	// add <p> tags. Simple analog of wpautop()
	if( $rg->autop ){

		$text = preg_replace(
			[ "/\r/", "/\n{2,}/", "/\n/" ],
			[ '', '</p><p>', '<br />' ],
			"<p>$text</p>"
		);
	}

	$text = apply_filters( 'kama_excerpt', $text, $rg );

	if( isset( $text_append ) ){
		$text .= $text_append;
	}

	return $text;
}