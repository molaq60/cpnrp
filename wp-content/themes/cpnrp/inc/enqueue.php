<?php
/**
 * Enqueue styles and scripts.
 * Version = time() in debug mode → always fresh, no cache issues.
 */

function cpnrp_enqueue_assets() {
	$dir = get_template_directory_uri();
	$ver = WP_DEBUG ? time() : wp_get_theme()->get( 'Version' );

	// Google Fonts — Figtree: warm geometric sans-serif, ideal for non-profit/charity sites
	wp_enqueue_style( 'cpnrp-fonts',
		'https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap',
		[], null
	);

	// Main stylesheet (design tokens + reset)
	wp_enqueue_style( 'cpnrp-main', $dir . '/assets/css/main.css', [], $ver );

	// Component/layout stylesheet (depends on main for CSS vars)
	wp_enqueue_style( 'cpnrp-layout', $dir . '/assets/css/theme-layout.css', [ 'cpnrp-main' ], $ver );

	// Main script (deferred via true = footer)
	wp_enqueue_script( 'cpnrp-main', $dir . '/assets/js/main.js', [], $ver, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_localize_script( 'cpnrp-main', 'cpnrpData', [
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'cpnrp_nonce' ),
		'themeUrl' => $dir,
	] );
}
add_action( 'wp_enqueue_scripts', 'cpnrp_enqueue_assets' );
