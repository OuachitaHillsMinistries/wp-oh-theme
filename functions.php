<?php

add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
	load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	global $content_width;

	if ( ! isset( $content_width ) ) {
		$content_width = 640;
	}

	register_nav_menus(
		array( 'main-menu' => __( 'Main Menu', 'blankslate' ) )
	);

	add_image_size('medium-large',600,600);
}

add_action( 'wp_enqueue_scripts', 'blankslate_load_scripts' );
function blankslate_load_scripts() {
	$themeDir = esc_url( get_template_directory_uri() );

	wp_enqueue_style( 'ohUnsliderCss', "$themeDir/css/unslider.css" );
	wp_enqueue_style( 'ohBootstrapCss', "$themeDir/css/bootstrap.min.css" );
	wp_enqueue_style('ohLightboxCss',"$themeDir/js/lightbox2/css/lightbox.min.css");
	wp_enqueue_style( 'ohCss', get_stylesheet_uri() );

	wp_enqueue_script( 'ohBootstrap', "$themeDir/js/bootstrap.min.js", array( 'jquery' ) );
	wp_enqueue_script( 'ohUnslider', "$themeDir/main.js", array( 'jquery' ) );
	wp_enqueue_script(
		'ohLightboxJs',
		"$themeDir/js/lightbox2/js/lightbox.min.js",
		array('jquery'),
		false,
		true
	);
	wp_enqueue_script( 'ohMain', "$themeDir/unslider/src/js/unslider.js", array( 'jquery', 'ohUnslider' ) );
}

add_action( 'comment_form_before', 'blankslate_enqueue_comment_reply_script' );
function blankslate_enqueue_comment_reply_script() {
	if ( get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_filter( 'the_title', 'blankslate_title' );
function blankslate_title( $title ) {
	if ( $title == '' ) {
		return '&rarr;';
	} else {
		return $title;
	}
}

add_filter( 'wp_title', 'blankslate_filter_wp_title' );
function blankslate_filter_wp_title( $title ) {
	return $title . esc_attr( get_bloginfo( 'name' ) );
}

add_action( 'widgets_init', 'blankslate_widgets_init' );
function blankslate_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar Widget Area', 'blankslate' ),
		'id'            => 'primary-widget-area',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget'  => "</li>",
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

function blankslate_custom_pings( $comment ) {
	$GLOBALS['comment'] = $comment;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
	<?php
}

add_filter( 'get_comments_number', 'blankslate_comments_number' );
function blankslate_comments_number( $count ) {
	if ( ! is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );

		return count( $comments_by_type['comment'] );
	} else {
		return $count;
	}
}

add_editor_style( 'style.css' );

/* === Image Galleries === */

function ohImageGallery() {
	if (is_callable('twp_the_post_images')) {
		$images = twp_the_post_images();
		if ($images) {
			$galleryList = makeImageList( $images, 'page_gallery', 'thumbnail' );
			$sliderList = makeImageList( $images, 'page_slider', 'medium-large' );
			$gallery = "<div class='gallery'><ul>$galleryList</ul></div>";
			$slider = "<div class='slider'><ul>$sliderList</ul></div>";
			echo "<div class='images'>$gallery $slider</div>";
		}
	}
}

function makeImageList( $images, $lightboxPrefix, $thumbSize ) {
	$postId       = get_the_ID();
	$lightboxData = $lightboxPrefix . $postId;
	$imageList    = '';

	foreach ( $images as $image ) {
		$url    = wp_get_attachment_image_src( $image->id, 'large' )[0];
		$src    = wp_get_attachment_image_src( $image->id, $thumbSize )[0];
		$format = '<li><a href="%s" data-lightbox="%s" class="thumbnail"><img src="%s" /></a></li>';
		$imageList .= sprintf( $format, $url, $lightboxData, $src );
	}

	return $imageList;
}

/* === Navigation === */

function subpageNav( $parentId ) {
	$children = get_pages( array(
		'child_of' => $parentId,
		'parent'   => $parentId
	) );

	if ( ! empty( $children ) ) {
		$currentId = get_the_ID();
		$items     = '';

		foreach ( $children as $child ) {
			$isActive  = $child->ID == $currentId;
			$extraAtts = ( $isActive ) ? ' class="active"' : '';
			$format    = '<li%s><a href="%s">%s</a></li>';
			$items .= sprintf( $format, $extraAtts, get_page_uri( $child ), $child->post_title );
		}

		echo "<ul class='subpages nav nav-pills nav-stacked'>$items</ul>";
	}
}

function getTopNavPageList() {
	if (isAcademy() && !isCollege()) {
		$academyId = getIdByTitle('Academy');
		return wp_list_pages( array(
			'child_of' => $academyId,
			'depth'    => 3,
			'title_li' => null,
			'walker'   => new wp_bootstrap_navwalker(),
			'echo'     => false
		) );
	} else if (isCollege() && !isAcademy()) {
		$collegeId = getIdByTitle('College');
		return wp_list_pages( array(
			'child_of' => $collegeId,
			'depth'    => 3,
			'title_li' => null,
			'walker'   => new wp_bootstrap_navwalker(),
			'echo'     => false
		) );
	} else {
		return wp_list_pages( array(
			'depth'    => 2,
			'title_li' => null,
			'walker'   => new wp_bootstrap_navwalker(),
			'echo'     => false
		) );
	}
}

function getTopLevelSection() {
	if (isAcademy() && !isCollege()) {
		return 'Academy';
	} else if (isCollege() && !isAcademy()) {
		return 'College';
	} else {
		return 'Ministries';
	}
}

function isAcademy() {
	if (is_category('College') || is_home() || is_search()) {
		return False;
	} else if (relatesToCategory('Academy')) {
		return True;
	} else {
		return False;
	}
}

function isCollege() {
	if (is_category('Academy') || is_home() || is_search()) {
		return False;
	} else if (relatesToCategory('College')) {
		return True;
	} else {
		return False;
	}
}

function relatesToCategory($category) {
	return topParent()->post_title == $category || in_category($category) || is_category($category);
}

function topParent() {
	$parents = get_post_ancestors( postID() );
	return get_post(end($parents));
}

function postID() {
	$url = explode('?', 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	$ID = url_to_postid($url[0]);
	return $ID;
}

function getCollegeUrl() {
	return getUrlByTitle('College');
}

function getAcademyUrl() {
	return getUrlByTitle('Academy');
}

function getIdByTitle($title)  {
	$page = get_page_by_title( $title );
	return $page->ID;
}

function getUrlByTitle( $title ) {
	$id = getIdByTitle($title);
	return get_permalink( $id );
}

/* Bootstrap Pills Walker */

class bootstrap_pills_walker extends Walker_Page {
	public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
		if ( $depth ) {
			$indent = str_repeat( "\t", $depth );
		} else {
			$indent = '';
		}

		$css_class = array( 'page_item', 'page-item-' . $page->ID );

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$css_class[] = 'page_item_has_children';
		}

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
				$css_class[] = 'current_page_ancestor';
			}
			if ( $page->ID == $current_page ) {
				$css_class[] = 'current_page_item';
				$css_class[] = 'active';
			} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
				$css_class[] = 'current_page_parent';
			}
		} elseif ( $page->ID == get_option('page_for_posts') ) {
			$css_class[] = 'current_page_parent';
		}

		/**
		 * Filter the list of CSS classes to include with each page item in the list.
		 *
		 * @since 2.8.0
		 *
		 * @see wp_list_pages()
		 *
		 * @param array   $css_class    An array of CSS classes to be applied
		 *                             to each list item.
		 * @param WP_Post $page         Page data object.
		 * @param int     $depth        Depth of page, used for padding.
		 * @param array   $args         An array of arguments.
		 * @param int     $current_page ID of the current page.
		 */
		$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

		if ( '' === $page->post_title ) {
			/* translators: %d: ID of a post */
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];

		/** This filter is documented in wp-includes/post-template.php */
		$output .= $indent . sprintf(
				'<li class="%s"><a href="%s">%s%s%s</a>',
				$css_classes,
				get_permalink( $page->ID ),
				$args['link_before'],
				apply_filters( 'the_title', $page->post_title, $page->ID ),
				$args['link_after']
			);

		if ( ! empty( $args['show_date'] ) ) {
			if ( 'modified' == $args['show_date'] ) {
				$time = $page->post_modified;
			} else {
				$time = $page->post_date;
			}

			$date_format = empty( $args['date_format'] ) ? '' : $args['date_format'];
			$output .= " " . mysql2date( $date_format, $time );
		}
	}
}

/* === Dependencies Management === */

require_once( 'php-utils/wp_bootstrap_navwalker.php' );

require_once( 'php-utils/class-tgm-plugin-activation.php' );
add_action( 'tgmpa_register', 'ohThemeRegisterRequiredPlugins' );

function  ohThemeRegisterRequiredPlugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'      => 'Attach Post Images',
			'slug'      => 'attach-post-images',
			'required'  => true,
		),

		array(
			'name'      => 'Shortcodes Ultimate',
			'slug'      => 'shortcodes-ultimate',
			'required'  => false,
		),

		array(
			'name'      => 'Paste as Plain Text',
			'slug'      => 'paste-as-plain-text',
			'required'  => false,
		),

		array(
			'name'      => 'Unobtrusive Admin Bar',
			'slug'      => 'unobtrusive-admin-bar',
			'required'  => false,
		),

/*		array(
			'name'      => 'WP Search Suggest',
			'slug'      => 'wp-search-suggest',
			'required'  => false,
		),*/


		array(
			'name'      => 'Advanced Responsive Video Embedder',
			'slug'      => 'advanced-responsive-video-embedder',
			'required'  => true,
		),

		array(
			'name'               => 'Gravity Forms', // The plugin name.
			'slug'               => 'gravityforms', // The plugin slug (typically the folder name).
			'source'             => get_stylesheet_directory() . '/bundled-plugins/gravityforms_1.9.16.3.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),

	);

	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}