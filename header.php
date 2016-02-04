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
						<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
						<li><a href="#">Link</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="#">Action</a></li>
								<li><a href="#">Another action</a></li>
								<li><a href="#">Something else here</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="#">Separated link</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="#">One more separated link</a></li>
							</ul>
						</li>
					</ul>
					<form class="navbar-form navbar-right" role="search">
						<?php get_search_form(); ?>
					</form>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>

		<header id="header" role="banner">
			<section id="branding">
				<div id="site-title"><?php if ( is_front_page() || is_home() || is_front_page() && is_home() ) { echo '<h1>'; } ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>" rel="home"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a><?php if ( is_front_page() || is_home() || is_front_page() && is_home() ) { echo '</h1>'; } ?></div>
				<div id="site-description"><?php bloginfo( 'description' ); ?></div>
			</section>
			<nav id="menu" role="navigation">
				<div id="search">

				</div>
				<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
			</nav>
		</header>
		<div id="container">