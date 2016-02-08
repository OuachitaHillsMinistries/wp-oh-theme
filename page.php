<?php get_header(); ?>
<section id="content" role="main">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	$heroUrl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	if ($heroUrl) echo "<div style=\"background-image: url('$heroUrl');\" class=\"header jumbotron\"></div>";
	?>
	<div class="meta">
		<?php
		$parents = array_reverse(get_post_ancestors($post->ID));
		$sectionParent = get_post($parents[1]);

		echo "<h1 class='entry-title'>$sectionParent->post_title</h1>";

		$children = get_pages(array(
			'child_of' => $sectionParent->ID,
			'parent' => $sectionParent->ID
		));

		if (!empty($children)) {
			$currentId = get_the_ID();
			$items = '';

			foreach ($children as $child) {
				$isActive = $child->ID == $currentId;
				$extraAtts = ($isActive) ? ' class="active"' : '';
				$format = '<li%s><a href="%s">%s</a></li>';
				$items .= sprintf( $format, $extraAtts, get_page_uri($child), $child->post_title );
			}

			echo "<ul class='subpages nav nav-pills nav-stacked'>$items</ul>";
		}
		?>
	</div>
	<section class="entry-content">
		<div class="text">
			<?php the_content(); ?>
			<?php edit_post_link(); ?>
		</div>
		<div class="entry-links"><?php wp_link_pages(); ?></div>
		<?php ohImageGallery(); ?>
	</section>
</article>
<?php if ( ! post_password_required() ) comments_template( '', true ); ?>
<?php endwhile; endif; ?>
</section>
<?php get_footer(); ?>