<?php require_once 'head.php'; ?>

<?php
$section = getTopLevelSection();
?>

<body <?php bodyClasses(); ?>>
	<div id="wrapper" class="hfeed">
		<div class="top-header">
			<h2><a href="<?php echo home_url() ?>">
				<img
					src="<?php bloginfo('stylesheet_directory'); ?>/images/OHM Logo Responsive White 1 Small.png"
					alt="Ouachita Hills Ministries"
				/>
			</a></h2>

			<?php get_search_form(); ?>

			<nav class="nav">
			</nav>
		</div>

		<div id="container">