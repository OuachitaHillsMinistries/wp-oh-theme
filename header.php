<?php include 'head.php'; ?>

<body <?php body_class(); ?>>
	<div id="wrapper" class="hfeed">
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="dropdown navbar-brand">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ouachita Hills Academy <span class="caret"></span></a>
						<ul class="dropdown-menu">
							<li><a href="#">Home</a></li>
							<li><a href="#">Academy</a></li>
							<li><a href="#">College</a></li>
							<li><a href="#">Store</a></li>
							<li><a href="#">Live</a></li>
						</ul>
					</div>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<?php
						wp_list_pages( array(
								'depth'             => 2,
								'title_li'          => null,
								'walker'            => new wp_bootstrap_navwalker()
						));
						?>
					</ul>
					<?php get_search_form(); ?>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>

		<div id="container">