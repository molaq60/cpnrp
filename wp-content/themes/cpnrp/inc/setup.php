<?php
/**
 * Theme setup: supports, nav menus, sidebars.
 */

function cpnrp_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'menus' );
	add_theme_support( 'custom-logo', [
		'height'      => 200,
		'width'       => 600,
		'flex-height' => true,
		'flex-width'  => true,
	] );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );

	register_nav_menus( [
		'primary' => __( 'Primary Navigation', 'cpnrp' ),
		'footer'  => __( 'Footer Navigation', 'cpnrp' ),
	] );

	load_theme_textdomain( 'cpnrp', get_template_directory() . '/lang' );
}
add_action( 'after_setup_theme', 'cpnrp_theme_setup' );


// ── Auto-create required pages ────────────────────────────────
// v3 — doplněny stránky footeru; parent musí být před dítětem.
function cpnrp_create_required_pages() {
	if ( get_transient( 'cpnrp_pages_created_v3' ) ) return;

	// [ slug, title, full_parent_path_or_empty ]
	$pages = [
		// Top-level
		[ 'pro-rodiny',        'Pro rodiny',                 ''              ],
		[ 'podporte-nas',      'Podpořte nás',               ''              ],
		[ 'kontakt',           'Kontakt',                    ''              ],
		[ 'o-nas',             'O nás',                      ''              ],
		[ 'pro-odborniky',     'Pro odborníky',              ''              ],
		[ 'fotogalerie',       'Fotogalerie',                ''              ],
		[ 'ochrana-udaju',     'Ochrana osobních údajů',     ''              ],
		[ 'cookies',           'Cookies',                    ''              ],
		// Pro rodiny — děti
		[ 'pestounska-pece',   'Pěstounská péče',            'pro-rodiny'                 ],
		[ 'adopce',            'Adopce',                     'pro-rodiny'                 ],
		[ 'zajemci',           'Zájemci o NRP',              'pro-rodiny'                 ],
		// Pěstounská péče — děti
		[ 'doprovazeni',       'Doprovázení rodin',          'pro-rodiny/pestounska-pece' ],
		[ 'poradenstvi',       'Odborné poradenství',        'pro-rodiny/pestounska-pece' ],
		[ 'vzdelavani',        'Vzdělávání',                 'pro-rodiny/pestounska-pece' ],
		// Zájemci — děti
		[ 'pripravne-kurzy',   'Přípravné kurzy',            'pro-rodiny/zajemci'         ],
		// O nás — děti
		[ 'tym',               'Náš tým',                    'o-nas'                      ],
		[ 'vyrocni-zpravy',    'Výroční zprávy',             'o-nas'                      ],
		[ 'dokumenty',         'Dokumenty ke stažení',       'o-nas'                      ],
		[ 'partneri',          'Partneři',                   'o-nas'                      ],
	];

	foreach ( $pages as [ $slug, $title, $parent_path ] ) {
		$full_path = $parent_path ? "{$parent_path}/{$slug}" : $slug;

		if ( get_page_by_path( $full_path ) ) continue;

		$parent_id = 0;
		if ( $parent_path ) {
			$parent    = get_page_by_path( $parent_path );
			$parent_id = $parent ? $parent->ID : 0;
		}

		wp_insert_post( [
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_status' => 'publish',
			'post_type'   => 'page',
			'post_parent' => $parent_id,
		] );
	}

	set_transient( 'cpnrp_pages_created_v3', true, YEAR_IN_SECONDS );
}
add_action( 'init', 'cpnrp_create_required_pages' );

// ── Auto-configure blog page at /pribehy/ ─────────────────────
function cpnrp_setup_blog_page() {
	if ( get_transient( 'cpnrp_pribehy_done' ) ) return;

	$page = get_page_by_path( 'pribehy' );
	$page_id = $page ? $page->ID : wp_insert_post( [
		'post_title'  => 'Příběhy',
		'post_name'   => 'pribehy',
		'post_status' => 'publish',
		'post_type'   => 'page',
	] );

	if ( $page_id && ! is_wp_error( $page_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_for_posts', $page_id );
	}

	set_transient( 'cpnrp_pribehy_done', true, YEAR_IN_SECONDS );
}
add_action( 'init', 'cpnrp_setup_blog_page' );

// ── Auto-add "Kalendář" to primary menu ──────────────────────
function cpnrp_add_events_to_menu() {
	if ( get_transient( 'cpnrp_events_menu_done_v2' ) ) return;

	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) return;

	$menu_id = $locations['primary'];
	$items   = wp_get_nav_menu_items( $menu_id );
	if ( ! $items ) $items = [];

	// Use the WP post-type archive URL (works even when TEC frontend is disabled)
	$events_url = untrailingslashit( get_post_type_archive_link( 'tribe_events' ) ?: home_url( '/kalendar/' ) );

	foreach ( $items as $item ) {
		if ( untrailingslashit( $item->url ) === $events_url ) {
			set_transient( 'cpnrp_events_menu_done_v2', true, YEAR_IN_SECONDS );
			return;
		}
	}

	wp_update_nav_menu_item( $menu_id, 0, [
		'menu-item-title'  => __( 'Kalendář', 'cpnrp' ),
		'menu-item-url'    => $events_url,
		'menu-item-status' => 'publish',
		'menu-item-type'   => 'custom',
	] );

	set_transient( 'cpnrp_events_menu_done_v2', true, YEAR_IN_SECONDS );
}
add_action( 'init', 'cpnrp_add_events_to_menu', 30 );

// ── Restrict front-end search to posts only ───────────────────
function cpnrp_search_filter( WP_Query $query ) {
	if ( $query->is_search() && ! is_admin() && $query->is_main_query() ) {
		$query->set( 'post_type', 'post' );
	}
}
add_action( 'pre_get_posts', 'cpnrp_search_filter' );
