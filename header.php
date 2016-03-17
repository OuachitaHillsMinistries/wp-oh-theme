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

            <nav class="ohNavbar">
                <div class="section-selector">
                    <a href="#"><?php echo getTopLevelSection(); ?></a>
                    <ul>
                        <li><a href="<?php echo home_url() ?>">Ministries</a></li>
                        <li><a href="<?php echo getAcademyUrl() ?>">Academy</a></li>
                        <li><a href="<?php echo getCollegeUrl() ?>">College</a></li>
                    </ul>
                </div>

                <ul class="ohNav">
                    <?php echo getNavPageList(); ?>
                </ul>
            </nav>
		</div>

		<div id="container">