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
}

add_action( 'wp_enqueue_scripts', 'blankslate_load_scripts' );
function blankslate_load_scripts() {
	$themeDir = esc_url( get_template_directory_uri() );

	wp_enqueue_style( 'ohUnsliderCss', "$themeDir/css/unslider.css" );
	wp_enqueue_style( 'ohBootstrapCss', "$themeDir/css/bootstrap.min.css" );
	wp_enqueue_style('ohLightboxCss',"$themeDir/js/lightbox2/css/lightbox.min.css");
	wp_enqueue_style( 'ohCss', get_stylesheet_uri() );

	wp_enqueue_script( 'ohBootstrap', "$themeDir/js/bootstrap.min.js", array( 'jquery' ) );
	wp_enqueue_script( 'ohUnslider', "$themeDir/js/main.js", array( 'jquery' ) );
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

function ohImageGallery() {
	if (is_callable('twp_the_post_images')) {
		$images = twp_the_post_images();
		if ($images) {
			$galleryList = makeImageList( $images, 'page_gallery' );
			$sliderList = makeImageList( $images, 'page_slider' );
			$gallery = "<div class='gallery'><ul>$galleryList</ul></div>";
			$slider = "<div class='slider'><ul>$sliderList</ul></div>";
			echo "<div class='images'>$gallery $slider</div>";
		}
	}
}

function makeImageList( $images, $lightboxPrefix ) {
	$postId       = get_the_ID();
	$lightboxData = $lightboxPrefix . $postId;
	$imageList    = '';

	foreach ( $images as $image ) {
		$url    = wp_get_attachment_image_src( $image->id, 'large' )[0];
		$src    = $image->url;
		$format = '<li><a href="%s" data-lightbox="%s"><img src="%s" /></a></li>';
		$imageList .= sprintf( $format, $url, $lightboxData, $src );
	}

	return $imageList;
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