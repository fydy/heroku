<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('top_nav_no')) { ?>
<header id="masthead" class="site-header site-header-h">
<?php } else { ?>
<header id="masthead" class="site-header site-header-s">
<?php } ?>
<?php if (!zm_get_option('menu_m') || (zm_get_option("menu_m") == 'menu_d')){ ?>
	<div id="header-main" class="header-main">
<?php } ?>
<?php if (zm_get_option('menu_m') == 'menu_n'){ ?>
	<div id="header-main-n" class="header-main-n">
<?php } ?>
<?php if (zm_get_option('menu_m') == 'menu_g'){ ?>
	<div id="header-main-g" class="header-main-g">
<?php } ?>
		<nav id="top-header">
			<div class="top-nav">
				<?php if (zm_get_option('profile')) { ?>
					<?php get_template_part( 'inc/users/user-profile' ); ?>
				<?php } ?>

				<?php
					wp_nav_menu( array(
						'theme_location'	=> 'header',
						'menu_class'		=> 'top-menu',
						'fallback_cb'		=> 'default_menu'
					) );
				?>
			</div>
		</nav><!-- #top-header -->

		<div id="menu-box">
			<div id="top-menu">
				<span class="nav-search"></span>
				<?php if ( zm_get_option('mobile_login') ) { ?>
					<?php if (zm_get_option('user_l')) { ?>
						<?php
						global $user_identity,$user_level;
						wp_get_current_user();
						if ($user_identity) { ?>
							<span class="mobile-login"><a href="#login" id="login-mobile" ><i class="be be-timerauto"></i></a></span>
						<?php } else { ?>
							<span class="mobile-login"><a href="<?php echo wp_login_url(  home_url() ); ?>" title="Login"><i class="be be-timerauto"></i></a></span>
						<?php } ?>
					<?php } else { ?>
						<span class="mobile-login"><a href="#login" id="login-mobile" ><i class="be be-timerauto"></i></a></span>
					<?php } ?>
				<?php } ?>
				<?php if (zm_get_option("logo_css")) { ?>
				<div class="logo-site">
				<?php } else { ?>
				<div class="logo-sites">
				<?php } ?>
					<?php
						if ( is_front_page() || is_category() || is_home() ) : ?>
							<?php if (zm_get_option('logos')) { ?>
							<h1 class="site-title">
								<?php if ( zm_get_option('logo') ) { ?>
									<a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo zm_get_option('logo'); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" rel="home" /><span class="site-name"><?php bloginfo( 'name' ); ?></span></a>
								<?php } ?>
							</h1>
							<?php } else { ?>
							<?php if ( zm_get_option('logo_small') ) { ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="logo-small"><img src="<?php echo zm_get_option('logo_small_b'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></span></a><?php } ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<?php
							$description = get_bloginfo( 'description', 'display' );

							if ( $description || is_customize_preview() ) :
							?>
								<p class="site-description"><?php echo $description; ?></p>
							<?php endif; ?>
						<?php } ?>
						<?php else : ?>
						<?php if (zm_get_option('logos')) { ?>
							<p class="site-title">
								<?php if ( zm_get_option('logo') ) { ?>
									<a href="<?php echo esc_url( home_url('/') ); ?>"><img src="<?php echo zm_get_option('logo'); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" rel="home" /><span class="site-name"><?php bloginfo( 'name' ); ?></span></a>
								<?php } ?>
							</p>
						<?php } else { ?>
							<?php if ( zm_get_option('logo_small') ) { ?><span class="logo-small"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo zm_get_option('logo_small_b'); ?>" alt="<?php bloginfo( 'name' ); ?>" /></span></a><?php } ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
							<?php
							$description = get_bloginfo( 'description', 'display' );

							if ( $description || is_customize_preview() ) :
							?>
								<p class="site-description"><?php echo $description; ?></p>
							<?php endif; ?>
						<?php } ?>
						<?php endif;
					?>
				</div><!-- .logo-site -->

				<div id="site-nav-wrap">
					<div id="sidr-close"><a href="#sidr-close" class="toggle-sidr-close">×</a></div>
					<div id="sidr-menu"><div class="toggle-sidr-menu">MENU</a></div></div>
					<nav id="site-nav" class="main-nav">
					<?php if (zm_get_option('nav_no')) { ?>
						<span class="nav-mobile"><a href="<?php echo get_permalink( zm_get_option('nav_url') ); ?>"><i class="be be-menu"></i></a></span>
					<?php } else { ?>
						<?php if (zm_get_option('m_nav')) { ?>
							<?php if ( wp_is_mobile() ) { ?>
								<span class="nav-mobile"><i class="be be-menu"></i></span>
							<?php } else { ?>
								<a href="#sidr-main" id="navigation-toggle" class="bars"><i class="be be-menu"></i></a>
							<?php } ?>
						<?php } else { ?>
							<a href="#sidr-main" id="navigation-toggle" class="bars"><i class="be be-menu"></i></a>
						<?php } ?>
					<?php } ?>
						<?php
							wp_nav_menu( array(
								'theme_location'	=> 'primary',
								'menu_class'		=> 'down-menu nav-menu',
								'fallback_cb'		=> 'default_menu'
							) ); 
						?>
					</nav><!-- #site-nav -->
				</div><!-- #site-nav-wrap -->
				<div class="clear"></div>
			</div><!-- #top-menu -->
		</div><!-- #menu-box -->
	</div><!-- #menu-box -->
</header><!-- #masthead -->
<?php get_template_part( 'template/search-main' ); ?>