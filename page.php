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
		$categoricalParent = get_post($parents[1]);
		$title = get_the_title();
		$firstParentChildren = get_children(array(
			'post_parent' => $categoricalParent->ID,
			'post_type' => 'page'
		));
		echo "<h1 class='entry-title'>$categoricalParent->post_title</h1>";
		if (!empty($firstParentChildren)) {
			$childLinks = wp_list_pages(array(
				'child_of' => $categoricalParent->ID,
				'title_li' => '',
				'echo'     => false,
				'depth'    => 2,
				'walker'   => new bootstrap_pills_walker()
			));
			echo "<ul class='subpages nav nav-pills nav-stacked'>$childLinks</ul>";
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