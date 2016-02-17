<?php include 'head.php'; ?>

	<body class="home">
		<div class="wrapper">

		    <div class="start">
		        <h1>Ouachita Hills Ministries</h1>
				<ul>
					<?php echo getHomeNavPageList(); ?>
				</ul>
		    </div>

		    <?php newsTicker(); ?>

		</div>

		<?php wp_footer(); ?>
	</body>

</html>