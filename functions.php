<?php

add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
	load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	global $contentWidth;

	if ( ! isset( $contentWidth ) ) {
		$contentWidth = 640;
	}

	register_nav_menus(
		array( 'main-menu' => __( 'Main Menu', 'blankslate' ) )
	);

	add_image_size( 'mediumLarge', 600, 600 );
}

add_filter ( 'wp_prepare_attachment_for_js', 'ohMakeCustomSizesAccessibleToJs', 10, 3  );
function ohMakeCustomSizesAccessibleToJs( $response, $attachment, $meta ){

	$size_array = array( 'mediumLarge') ;

	foreach ( $size_array as $size ):

		if ( isset( $meta['sizes'][ $size ] ) ) {
			$attachment_url = wp_get_attachment_url( $attachment->ID );
			$base_url = str_replace( wp_basename( $attachment_url ), '', $attachment_url );
			$size_meta = $meta['sizes'][ $size ];

			$response['sizes'][ $size ] = array(
				'height'        => $size_meta['height'],
				'width'         => $size_meta['width'],
				'url'           => $base_url . $size_meta['file'],
				'orientation'   => $size_meta['height'] > $size_meta['width'] ? 'portrait' : 'landscape',
			);
		}

	endforeach;

	return $response;
}

add_action( 'wp_enqueue_scripts', 'ohEnqueueFrontEndScripts' );
function ohEnqueueFrontEndScripts() {
	$themeDir = esc_url( get_template_directory_uri() );

	wp_enqueue_style( 'unslider', "$themeDir/includes/unslider/dist/css/unslider.css" );
	wp_enqueue_style( 'bootstrap', "$themeDir/includes/bootstrap/bootstrap.min.css" );
	wp_enqueue_style( 'lightbox2', "$themeDir/includes/lightbox2/css/lightbox.min.css" );
	wp_enqueue_style( 'ohCss', get_stylesheet_uri() );

	wp_enqueue_script( 'unslider', "$themeDir/includes/unslider/src/js/unslider.js", array( 'jquery') );
	wp_enqueue_script( 'bootstrap', "$themeDir/includes/bootstrap/bootstrap.min.js", array( 'jquery' ) );
	wp_enqueue_script(
		'lightbox2',
		"$themeDir/includes/lightbox2/js/lightbox.min.js",
		array( 'jquery' ),
		false,
		true
	);
	wp_enqueue_script( 'ohMain', "$themeDir/main.js", array( 'jquery', 'unslider' ) );

	wp_enqueue_style( 'dashicons', get_stylesheet_directory_uri(), array('dashicons'), '1.0' );
}

add_action( 'admin_enqueue_scripts', 'ohEnqueueAdminScripts' );
function ohEnqueueAdminScripts() {
	$themeDir = esc_url( get_template_directory_uri() );

	wp_enqueue_style( 'oh-jquery-ui', "$themeDir/includes/jquery-ui-1.11.4.custom/jquery-ui.min.css" );
	wp_enqueue_style( 'ohAdmin', "$themeDir/admin.css" );

	wp_enqueue_script(
		'oh-jquery-ui',
		"$themeDir/includes/jquery-ui-1.11.4.custom/jquery-ui.min.js",
		array( 'jquery' )
	);
	wp_enqueue_script( 'ohAdmin', "$themeDir/admin.js", array( 'jquery', 'oh-jquery-ui' ) );
}

add_action( 'wp_ajax_save_featured_image_position', 'ohSaveFeaturedImagePosition' );

function ohSaveFeaturedImagePosition() {
	$result = update_post_meta($_POST['post'],'_ohHeroPosition',$_POST['percent']);
	echo ($result) ? 'success' : 'error';
	wp_die(); // this is required to terminate immediately and return a proper response
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
	}

	return $title;
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
		$commentsByType = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );

		return count( $commentsByType['comment'] );
	}

	return $count;
}

add_editor_style( 'style.css' );

function bodyClasses() {
	$classes = 'no-js';
	if ( isCollege() && !isAcademy() ) {
		$classes .= ' college';
	} elseif ( isAcademy() && !isCollege() ) {
		$classes .= ' academy';
	}
	if (isAcademyHome()) {
		$classes .= ' academyHome';
	}
	if (isCollegeHome()) {
		$classes .= ' collegeHome';
	}
	if (is_home()) {
		$classes .= ' home';
	}
	body_class($classes);
}

/* === Image Galleries === */

function ohImageGallery() {
	if ( is_callable( 'twp_the_post_images' ) ) {
		$images = twp_the_post_images();
		if ( $images ) {
			$galleryList = makeImageList( $images, 'page_gallery', 'thumbnail', 'thumbnail' );
			$gallery     = "<div class='gallery'><ul>$galleryList</ul></div>";
			echo "<div class='images'>$gallery</div>";
		}
	}
}

function makeImageList( $images, $lightboxPrefix, $thumbSize, $classes ) {
	$postId       = get_the_ID();
	$lightboxData = $lightboxPrefix . $postId;
	$imageList    = '';

	foreach ( $images as $image ) {
		$url    = wp_get_attachment_image_src( $image->id, 'large' )[0];
		$src    = wp_get_attachment_image_src( $image->id, $thumbSize )[0];
		$format = '<li><a href="%s" data-lightbox="%s" class="%s"><img src="%s" /></a></li>';
		$imageList .= sprintf( $format, $url, $lightboxData, $classes, $src );
	}

	return $imageList;
}

/* === Recent Posts === */

function ohRecentPosts($category) {
	$catId = get_category_id($category);
	$posts = wp_get_recent_posts(array(
		'numberposts' => 6,
		'category' => $catId
	));
	$linkList = '';
	foreach ($posts as $post) {
		$url = get_permalink($post);
		$img = get_the_post_thumbnail($post['ID'], 'thumbnail');
		$img = ($img) ? $img : '<span class="dashicons dashicons-camera"></span>';
		$title = $post['post_title'];
		$format = '<li><a href="%s"><span class="thumbnail">%s</span><span class="title">%s</span></a></li>';
		$linkList .= sprintf( $format, $url, $img, $title );
	}
	$categoryUrl = get_category_link($catId);
	$linkList .= "<li><a href='$categoryUrl' class=\"more\">More News...</a></li>";
	$posts = "<ul>$linkList</ul>";
	echo sprintf('<div class="recent-posts"><h2>News</h2>%s</div>', $posts);
}

function get_category_id($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->term_id;
}

/* === Front Page News Ticker === */

function newsTicker() {
	$posts = get_posts( array(
		'post_type'        => 'ohnewsbulletin',
		'post_status'      => 'publish'
	) );
        
        if ($posts) {
            $items = '';
            foreach ($posts as $post) {
		$items .= "<li>{$post->post_title}</li>";
            }
            $atts = (count($posts) > 1) ? 'class="slider"' : 'class="slider single"';
            echo "<div class='news'><div $atts><ul>$items</ul></div></div>";
        }
	
}

/* News Ticker Custom Post Type */

function registerNewsTickerPostType() {

	$labels = array(
		'name'                  => 'News Bulletins',
		'singular_name'         => 'News Bulletin',
		'menu_name'             => 'News Bulletins',
		'name_admin_bar'        => 'News Bulletins',
		'archives'              => 'Item Archives',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Items',
		'add_new_item'          => 'Add New Item',
		'add_new'               => 'Add New',
		'new_item'              => 'New Item',
		'edit_item'             => 'Edit Item',
		'update_item'           => 'Update Item',
		'view_item'             => 'View Item',
		'search_items'          => 'Search Item',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Items list',
		'items_list_navigation' => 'Items list navigation',
		'filter_items_list'     => 'Filter items list',
	);
	$args = array(
		'label'                 => 'News Bulletin',
		'description'           => 'Updates for the home page news ticker',
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-pressthis',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'ohNewsBulletin', $args );

}
add_action( 'init', 'registerNewsTickerPostType', 0 );

/* === Navigation === */

function subpageNav( $parentId ) {
	$children = get_pages( array(
		'child_of' => $parentId,
		'parent'   => $parentId
	) );

	$isChildren    = ! empty( $children );
	$isSectionPage = isCollegeHome() || isAcademyHome();

	if ( $isChildren && ! $isSectionPage ) {
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

function isAcademyHome() {
	$post = get_post();

	return $post->post_title == "Academy";
}

function isCollegeHome() {
	$post = get_post();

	return $post->post_title == "College";
}

function getNavPageList() {
	$parentId = ( isAcademy() && ! isCollege() ) ? getIdByTitle( 'Academy' ) : 0;
	$parentId = ( isCollege() && ! isAcademy() ) ? getIdByTitle( 'College' ) : $parentId;
	$depth = ($parentId == 0) ? 2 : 3;

	$pagesHtml = wp_list_pages( array(
		'child_of' => $parentId,
		'depth'    => $depth,
		'title_li' => null,
		'echo'     => false
	) );

	return ( $parentId === 0 ) ? $pagesHtml : $pagesHtml . getGlobalPagesHtml();
}

function getHomeNavPageList() {
	$pagesHtml = wp_list_pages( array(
		'depth'    => 1,
		'title_li' => null,
		'echo'     => false
	) );

	return $pagesHtml . getGlobalPagesHtml();
}

function getGlobalPagesHtml() {
	return wp_list_pages( array(
		'depth'     => 2,
		'title_li'  => null,
		'echo'      => false,
		'post_type' => 'ohglobalpage'
	) );
}

function getTopLevelSection() {
	if ( isAcademy() && ! isCollege() ) {
		return 'Academy';
	} else if ( isCollege() && ! isAcademy() ) {
		return 'College';
	}

	return 'Ministries';
}

function isAcademy() {
	if ( is_category( 'College' ) || is_home() || is_search() || ! relatesToCategory( 'Academy' ) ) {
		return false;
	}

	return true;
}

function isCollege() {
	if ( is_category( 'Academy' ) || is_home() || is_search() || ! relatesToCategory( 'College' ) ) {
		return false;
	}

	return true;
}

function relatesToCategory( $category ) {
	return topParent()->post_title == $category || in_category( $category ) || is_category( $category );
}

function topParent() {
	$parents = get_post_ancestors( getPostId() );

	return get_post( end( $parents ) );
}

function getPostId() {
	global $post;

	return $post->ID;
}

function getCollegeUrl() {
	return getUrlByTitle( 'College' );
}

function getAcademyUrl() {
	return getUrlByTitle( 'Academy' );
}

function getIdByTitle( $title ) {
	$page = get_page_by_title( $title );

	return $page->ID;
}

function getUrlByTitle( $title ) {
	$postId = getIdByTitle( $title );

	return get_permalink( $postId );
}

/* Global Page Custom Post Type */

function registerGlobalPagePostType() {

	$labels = array(
		'name'                  => 'Global Pages',
		'singular_name'         => 'Global Page',
		'menu_name'             => 'Global Pages',
		'name_admin_bar'        => 'Global Pages',
		'archives'              => 'Item Archives',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Items',
		'add_new_item'          => 'Add New Item',
		'add_new'               => 'Add New',
		'new_item'              => 'New Item',
		'edit_item'             => 'Edit Item',
		'update_item'           => 'Update Item',
		'view_item'             => 'View Item',
		'search_items'          => 'Search Item',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Items list',
		'items_list_navigation' => 'Items list navigation',
		'filter_items_list'     => 'Filter items list',
	);
	$args   = array(
		'label'               => 'Global Page',
		'description'         => 'Pages always displayed in the nav bar.',
		'labels'              => $labels,
		'supports'            => array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'comments',
			'revisions',
			'custom-fields',
			'page-attributes',
		),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-admin-page',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'ohGlobalPage', $args );

}

add_action( 'init', 'registerGlobalPagePostType', 0 );

/* Bootstrap Pills Walker */

class bootstrapPillsWalker extends Walker_Page {
	public function start_el( &$output, $page, $depth = 0, $args = array(), $currentPage = 0 ) {
		if ( $depth ) {
			$indent = str_repeat( "\t", $depth );
		} else {
			$indent = '';
		}

		$cssClass = array( 'page_item', 'page-item-' . $page->ID );

		if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
			$cssClass[] = 'page_item_has_children';
		}

		if ( ! empty( $currentPage ) ) {
			$_currentPage = get_post( $currentPage );
			if ( $_currentPage && in_array( $page->ID, $_currentPage->ancestors ) ) {
				$cssClass[] = 'current_page_ancestor';
			}
			if ( $page->ID == $currentPage ) {
				$cssClass[] = 'current_page_item';
				$cssClass[] = 'active';
			} elseif ( $_currentPage && $page->ID == $_currentPage->post_parent ) {
				$cssClass[] = 'current_page_parent';
			}
		} elseif ( $page->ID == get_option( 'page_for_posts' ) ) {
			$cssClass[] = 'current_page_parent';
		}

		/**
		 * Filter the list of CSS classes to include with each page item in the list.
		 *
		 * @since 2.8.0
		 *
		 * @see wp_list_pages()
		 *
		 * @param array $cssClass An array of CSS classes to be applied
		 *                             to each list item.
		 * @param WP_Post $page Page data object.
		 * @param int $depth Depth of page, used for padding.
		 * @param array $args An array of arguments.
		 * @param int $currentPage ID of the current page.
		 */
		$css_classes = implode( ' ', apply_filters( 'page_css_class', $cssClass, $page, $depth, $args, $currentPage ) );

		if ( '' === $page->post_title ) {
			/* translators: %d: ID of a post */
			$page->post_title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
		$args['link_after']  = empty( $args['link_after'] ) ? '' : $args['link_after'];

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

			$dateFormat = empty( $args['date_format'] ) ? '' : $args['date_format'];
			$output .= " " . mysql2date( $dateFormat, $time );
		}
	}
}

/* === Dependencies Management === */

require_once( 'php-utils/wp_bootstrap_navwalker.php' );

require_once( 'php-utils/class-tgm-plugin-activation.php' );
add_action( 'tgmpa_register', 'ohThemeRegisterRequiredPlugins' );

function ohThemeRegisterRequiredPlugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		array(
			'name'     => 'Attach Post Images',
			'slug'     => 'attach-post-images',
			'required' => true,
		),

		array(
			'name'     => 'Shortcodes Ultimate',
			'slug'     => 'shortcodes-ultimate',
			'required' => false,
		),

		array(
			'name'     => 'Paste as Plain Text',
			'slug'     => 'paste-as-plain-text',
			'required' => false,
		),

		array(
			'name'     => 'Unobtrusive Admin Bar',
			'slug'     => 'unobtrusive-admin-bar',
			'required' => false,
		),

		/*		array(
					'name'      => 'WP Search Suggest',
					'slug'      => 'wp-search-suggest',
					'required'  => false,
				),*/


		array(
			'name'     => 'Advanced Responsive Video Embedder',
			'slug'     => 'advanced-responsive-video-embedder',
			'required' => true,
		),

		array(
			'name'               => 'Gravity Forms',
			// The plugin name.
			'slug'               => 'gravityforms',
			// The plugin slug (typically the folder name).
			'source'             => get_stylesheet_directory() . '/bundled-plugins/gravityforms_1.9.16.3.zip',
			// The plugin source.
			'required'           => true,
			// If false, the plugin is only 'recommended' instead of required.
			'version'            => '',
			// E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false,
			// If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => false,
			// If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '',
			// If set, overrides default API URL and points to an external URL.
			'is_callable'        => '',
			// If set, this callable will be be checked for availability to determine if a plugin is active.
		),
            
                array(
                        'name'      => 'GitHub Updater',
                        'slug'      => 'github-updater',
                        'source'    => 'https://github.com/afragen/github-updater/archive/master.zip',
                ),

	);

	$config = array(
		'id'           => 'tgmpa',
		// Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',
		// Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins',
		// Menu slug.
		'parent_slug'  => 'themes.php',
		// Parent menu slug.
		'capability'   => 'edit_theme_options',
		// Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,
		// Show admin notices or not.
		'dismissable'  => true,
		// If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',
		// If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,
		// Automatically activate plugins after installation or not.
		'message'      => '',
		// Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}