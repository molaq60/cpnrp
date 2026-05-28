<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Page loader -->
<div id="page-loader" aria-hidden="true">
	<svg class="loader-ring" width="96" height="96" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg">
		<g>
			<path d="m64 7a57 57 0 1 0 57 57 57 57 0 0 0 -57-57zm0 84.972a27.972 27.972 0 1 1 27.972-27.972 27.971 27.971 0 0 1 -27.972 27.972z" fill="#C0392B"/>
			<g fill="#ffffff">
				<path d="m35.908 14.392a57.274 57.274 0 0 0 -21.516 21.516l25.263 14.306a28.123 28.123 0 0 1 10.559-10.559z"/>
				<path d="m92.092 113.608a57.274 57.274 0 0 0 21.516-21.516l-25.263-14.306a28.123 28.123 0 0 1 -10.559 10.559z"/>
				<path d="m113.608 35.908a57.274 57.274 0 0 0 -21.516-21.516l-14.306 25.263a28.123 28.123 0 0 1 10.559 10.559z"/>
				<path d="m14.392 92.092a57.274 57.274 0 0 0 21.516 21.516l14.306-25.263a28.123 28.123 0 0 1 -10.559-10.559z"/>
			</g>
		</g>
	</svg>
</div>

<div class="sticky-header-wrap" id="sticky-header">

	<?php get_template_part( 'template-parts/header/donor-bar' ); ?>

	<header class="header" id="main-header" role="banner">
		<div class="container">
			<div class="header-inner">

				<!-- Logo — set via Appearance → Customize → Site Identity -->
				<div class="logo-area">
					<?php
					$logo_id = get_theme_mod( 'custom_logo' );
					if ( $logo_id ) {
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="logo-link" rel="home">';
						echo wp_get_attachment_image( $logo_id, 'full', false, [ 'class' => 'logo-image' ] );
						echo '</a>';
					} else {
						// Fallback to logo.png in assets
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="logo-link" rel="home">';
						echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/logo.png' ) . '"'
							. ' alt="' . esc_attr( get_bloginfo( 'name' ) ) . '"'
							. ' class="logo-image">';
						echo '</a>';
					}
					?>
				</div>

				<!-- Primary nav — managed via Appearance → Menus → Primary Navigation -->
				<!-- Depth 0: nav items | Depth 1: megamenu column headers | Depth 2: links -->
				<nav class="primary-nav desktop-nav" role="navigation"
					aria-label="<?php esc_attr_e( 'Primary Navigation', 'cpnrp' ); ?>">
					<?php
					wp_nav_menu( [
						'theme_location' => 'primary',
						'menu_class'     => 'navbar-menu',
						'container'      => false,
						'walker'         => new CPNRP_Nav_Walker(),
						'fallback_cb'    => false,
						'depth'          => 3,
					] );
					?>
				</nav>

				<!-- Right side: CTA button + mobile hamburger -->
				<div class="header-right">
					<?php
					$cta_text = get_theme_mod( 'cpnrp_cta_text', __( 'Podpořte nás', 'cpnrp' ) );
					$cta_url  = get_theme_mod( 'cpnrp_cta_url',  home_url( '/podporte-nas' ) );
					?>
					<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--red" target="_blank" rel="noopener noreferrer">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/heart-benefice.png' ); ?>"
							alt="" aria-hidden="true">
						<?php echo esc_html( $cta_text ); ?>
					</a>

					<button class="mobile-menu-toggle"
						aria-label="<?php esc_attr_e( 'Toggle navigation', 'cpnrp' ); ?>"
						aria-expanded="false">
						<span></span>
						<span></span>
						<span></span>
					</button>
				</div>

			</div>
		</div>

		<div class="header-accent-line"></div>
	</header>

</div><!-- /.sticky-header-wrap -->

<!-- Megamenu panels — generated dynamically from the primary menu (depth 1 = columns, depth 2 = links) -->
<?php cpnrp_render_megamenu_panels(); ?>

<!-- ── Offcanvas mobile menu ────────────────────────────────── -->
<div class="offcanvas-overlay" id="offcanvas-overlay" aria-hidden="true"></div>

<div class="offcanvas" id="offcanvas"
	role="dialog"
	aria-label="<?php esc_attr_e( 'Navigace', 'cpnrp' ); ?>"
	aria-modal="true"
	aria-hidden="true">

	<div class="offcanvas-header">
		<?php
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="logo-link">';
			echo wp_get_attachment_image( $logo_id, 'full', false, [ 'class' => 'offcanvas-logo' ] );
			echo '</a>';
		} else {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="logo-link">';
			echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/logo.png' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="offcanvas-logo">';
			echo '</a>';
		}
		?>
		<button class="offcanvas-close" id="offcanvas-close"
			aria-label="<?php esc_attr_e( 'Zavřít menu', 'cpnrp' ); ?>">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
				<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
			</svg>
		</button>
	</div>

	<nav class="offcanvas-nav" aria-label="<?php esc_attr_e( 'Mobilní navigace', 'cpnrp' ); ?>">
		<?php
		wp_nav_menu( [
			'theme_location' => 'primary',
			'menu_class'     => 'offcanvas-menu',
			'container'      => false,
			'walker'         => new CPNRP_Mobile_Walker(),
			'fallback_cb'    => false,
			'depth'          => 3,
		] );
		?>
	</nav>

	<div class="offcanvas-footer">
		<?php
		$cta_text = get_theme_mod( 'cpnrp_cta_text', __( 'Podpořte nás', 'cpnrp' ) );
		$cta_url  = get_theme_mod( 'cpnrp_cta_url',  home_url( '/podporte-nas' ) );
		?>
		<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--red" style="width:100%;justify-content:center;" target="_blank" rel="noopener noreferrer">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/heart-benefice.png' ); ?>"
				alt="" aria-hidden="true">
			<?php echo esc_html( $cta_text ); ?>
		</a>
	</div>

</div>
