<?php
/**
 * CPNRP Theme — functions entry point.
 * Each concern lives in its own file under inc/.
 */

$cpnrp_includes = [
	'inc/setup.php',       // Theme supports, nav menus, sidebars
	'inc/enqueue.php',     // Styles & scripts
	'inc/walkers.php',     // CPNRP_Nav_Walker
	'inc/customizer.php',  // Customizer panels (donor bar, CTA, megamenu)
	'inc/helpers.php',     // cpnrp_render_megamenu_panels() + excerpt filters
	'inc/post-types.php',  // Custom post types (event, gallery)
	'inc/forms.php',       // Gravity Forms — kontaktní formulář
	'inc/hub-meta.php',    // Rozcestník — meta boxes for hub page template
	'inc/hub-content.php',       // Podstránky — one-time content + menu setup
	'inc/pro-rodiny-meta.php',   // Pro rodiny templates — meta boxes
	'inc/pro-rodiny-content.php', // Pro rodiny — one-time content + template setup
	'inc/o-nas-meta.php',        // O nás templates — meta boxes
	'inc/o-nas-content.php',     // O nás — one-time content + template setup
	'inc/beh-meta.php',          // Běh pro rodinu — meta boxes
	'inc/pro-odborniky-meta.php',  // Pro odborníky — meta boxes
	'inc/vyrocni-zpravy-meta.php', // Výroční zprávy — meta box s PDF uploadem
	'inc/dokumenty-meta.php',      // Dokumenty ke stažení — meta box s PDF uploadem
	'inc/legal-content.php',       // Ochrana osobních údajů + Cookies — obsah stránek
];

foreach ( $cpnrp_includes as $file ) {
	require get_template_directory() . '/' . $file;
}

// Redirect old /fotogalerie/ URL to /galerie/
add_action( 'template_redirect', function () {
	if ( strpos( $_SERVER['REQUEST_URI'], '/fotogalerie' ) !== false ) {
		wp_redirect( home_url( '/galerie/' ), 301 );
		exit;
	}
} );
