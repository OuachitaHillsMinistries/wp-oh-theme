<?php get_header(); ?>
<section id="content" role="main">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	$heroUrl = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	if ($heroUrl) echo "<div style=\"background-image: url('$heroUrl');\" class=\"header jumbotron\"></div>";
	?>
	<div class="meta">
		<h1 class="entry-title"><?php the_title(); ?></h1> <?php edit_post_link(); ?>
	</div>
	<section class="entry-content">
		<?php /*if ( has_post_thumbnail() ) { the_post_thumbnail(); }*/ ?>
		<div class="text"><?php the_content(); ?></div>
		<div class="entry-links"><?php wp_link_pages(); ?></div>
		<?php ohImageGallery(); ?>
	</section>
</article>
<?php if ( ! post_password_required() ) comments_template( '', true ); ?>
<?php endwhile; endif; ?>
</section>
<?php get_footer(); ?>