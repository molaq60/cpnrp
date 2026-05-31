<?php
/**
 * Custom post types.
 */

function cpnrp_register_post_types() {

	// ── Galerie (named albums, legacy system) ───────────────────
	register_post_type( 'galerie', [
		'labels' => [
			'name'               => 'Fotogalerie',
			'singular_name'      => 'Album',
			'add_new'            => 'Přidat album',
			'add_new_item'       => 'Přidat nové album',
			'edit_item'          => 'Upravit album',
			'all_items'          => 'Všechna alba',
			'search_items'       => 'Hledat alba',
			'not_found'          => 'Žádná alba',
			'not_found_in_trash' => 'Žádná alba v koši',
			'menu_name'          => 'Fotogalerie',
		],
		'public'        => true,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'menu_icon'     => 'dashicons-format-gallery',
		'menu_position' => 22,
		'supports'      => [ 'title', 'editor', 'thumbnail' ],
		'has_archive'   => true,
		'rewrite'       => [ 'slug' => 'galerie', 'with_front' => false ],
		'show_in_rest'  => false,
	] );

	register_taxonomy( 'album', 'galerie', [
		'labels' => [
			'name'          => 'Rok',
			'singular_name' => 'Rok',
			'all_items'     => 'Všechny roky',
			'menu_name'     => 'Roky',
		],
		'public'            => true,
		'show_ui'           => true,
		'hierarchical'      => false,
		'show_admin_column' => true,
		'rewrite'           => [ 'slug' => 'album' ],
	] );

	// ── Partner (logo marquee) ──────────────────────────────────
	register_post_type( 'partner', [
		'public'            => false,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'label'             => __( 'Partneři', 'cpnrp' ),
		'menu_icon'         => 'dashicons-groups',
		'supports'          => [ 'title', 'thumbnail' ],
		'show_in_rest'      => false,
		'menu_position'     => 25,
	] );
}
add_action( 'init', 'cpnrp_register_post_types' );

// ── Partner meta box — URL ──────────────────────────────────────

function cpnrp_partner_meta_box() {
	add_meta_box(
		'cpnrp_partner_url',
		__( 'Odkaz (proklik loga)', 'cpnrp' ),
		'cpnrp_partner_url_render',
		'partner',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'cpnrp_partner_meta_box' );

function cpnrp_partner_url_render( $post ) {
	wp_nonce_field( 'cpnrp_partner_url_save', 'cpnrp_partner_url_nonce' );
	$url = get_post_meta( $post->ID, '_partner_url', true );
	?>
	<p>
		<label for="cpnrp_partner_url_field" style="display:block;margin-bottom:4px;font-weight:600;">
			<?php esc_html_e( 'URL webu partnera', 'cpnrp' ); ?>
		</label>
		<input type="url" id="cpnrp_partner_url_field" name="cpnrp_partner_url_field"
		       value="<?php echo esc_url( $url ); ?>"
		       placeholder="https://..."
		       style="width:100%;">
	</p>
	<?php
}

function cpnrp_partner_url_save( $post_id ) {
	if (
		! isset( $_POST['cpnrp_partner_url_nonce'] ) ||
		! wp_verify_nonce( $_POST['cpnrp_partner_url_nonce'], 'cpnrp_partner_url_save' ) ||
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		! current_user_can( 'edit_post', $post_id )
	) {
		return;
	}
	$url = isset( $_POST['cpnrp_partner_url_field'] ) ? esc_url_raw( $_POST['cpnrp_partner_url_field'] ) : '';
	update_post_meta( $post_id, '_partner_url', $url );
}
add_action( 'save_post_partner', 'cpnrp_partner_url_save' );

// ── One-time import of partner logos from theme assets ──────────

function cpnrp_import_partner_logos() {
	if ( get_transient( 'cpnrp_partners_imported_v1' ) ) return;

	$partners = [
		[ 'name' => 'Ústecký kraj',                   'file' => 'ustecky-kraj.jpg' ],
		[ 'name' => 'Město Litoměřice',               'file' => 'mesto-litomerice.jpg' ],
		[ 'name' => 'Město Ústí nad Labem',           'file' => 'mesto-usti-nad-labem.jpg' ],
		[ 'name' => 'Nadační fond Albert',            'file' => 'nadacni-fond-albert.png' ],
		[ 'name' => 'Nadace Sirius',                  'file' => 'nadace-sirius.png' ],
		[ 'name' => 'Nadace J&T',                     'file' => 'nadace-jt.png' ],
		[ 'name' => 'Nadace Rhea',                    'file' => 'nadace-rhea.jpg' ],
		[ 'name' => 'NROS – Pomozte dětem',           'file' => 'nros-pomozte-detem.jpg' ],
		[ 'name' => 'Nadační fond Severočeská voda',  'file' => 'nadacni-fond-severoceska-voda.jpg' ],
		[ 'name' => 'Zdravé město Litoměřice',        'file' => 'zdrave-mesto-litomerice.jpg' ],
		[ 'name' => 'Globus',                         'file' => 'globus.jpg' ],
		[ 'name' => 'Holcim',                         'file' => 'holcim.jpg' ],
		[ 'name' => 'Mondi',                          'file' => 'mondi.jpg' ],
		[ 'name' => 'SIAD',                           'file' => 'siad.jpg' ],
		[ 'name' => 'Magna Exteriors',                'file' => 'magna-exteriors.png' ],
		[ 'name' => 'Orbico',                         'file' => 'orbico.png' ],
		[ 'name' => 'Česká podnikatelská pojišťovna', 'file' => 'ceska-podnikatelska-pojistovna.jpg' ],
		[ 'name' => 'Cekro',                          'file' => 'cekro.png' ],
		[ 'name' => 'Amedis',                         'file' => 'amedis.png' ],
		[ 'name' => 'Decci',                          'file' => 'decci.png' ],
		[ 'name' => 'AB Clima',                       'file' => 'ab-clima.png' ],
		[ 'name' => 'N+N konstrukce',                 'file' => 'nn-konstrukce.png' ],
		[ 'name' => 'Rodinný svaz',                   'file' => 'rodinny-svaz.png' ],
		[ 'name' => 'Asociace Dítě a rodina',         'file' => 'asociace-dite-a-rodina.png' ],
	];

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$theme_logos = get_template_directory() . '/assets/images/partners/';
	$upload      = wp_upload_dir();

	foreach ( $partners as $partner ) {
		// Skip if post with this title already exists
		$existing = get_posts( [
			'post_type'   => 'partner',
			'title'       => $partner['name'],
			'post_status' => 'publish',
			'numberposts' => 1,
			'fields'      => 'ids',
		] );
		if ( $existing ) continue;

		// Create the partner post
		$post_id = wp_insert_post( [
			'post_type'   => 'partner',
			'post_title'  => $partner['name'],
			'post_status' => 'publish',
		] );
		if ( is_wp_error( $post_id ) ) continue;

		// Copy logo to uploads and attach it
		$src = $theme_logos . $partner['file'];
		if ( ! file_exists( $src ) ) continue;

		$filename = wp_unique_filename( $upload['path'], $partner['file'] );
		$dest     = $upload['path'] . '/' . $filename;
		if ( ! copy( $src, $dest ) ) continue;

		$filetype  = wp_check_filetype( $filename );
		$attach_id = wp_insert_attachment( [
			'guid'           => $upload['url'] . '/' . $filename,
			'post_mime_type' => $filetype['type'],
			'post_title'     => $partner['name'],
			'post_status'    => 'inherit',
		], $dest, $post_id );

		if ( ! is_wp_error( $attach_id ) ) {
			wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $dest ) );
			set_post_thumbnail( $post_id, $attach_id );
		}
	}

	set_transient( 'cpnrp_partners_imported_v1', true, YEAR_IN_SECONDS );
}
add_action( 'admin_init', 'cpnrp_import_partner_logos' );

// ══════════════════════════════════════════════════════════════
// CONTACT PERSON CPT
// ══════════════════════════════════════════════════════════════

function cpnrp_register_contact_person_cpt() {
	register_post_type( 'contact_person', [
		'public'            => false,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'label'             => __( 'Kontakty', 'cpnrp' ),
		'menu_icon'         => 'dashicons-id-alt',
		'supports'          => [ 'title', 'thumbnail' ],
		'show_in_rest'      => false,
		'menu_position'     => 26,
	] );

	register_taxonomy( 'contact_group', 'contact_person', [
		'public'            => false,
		'show_ui'           => true,
		'hierarchical'      => false,
		'label'             => __( 'Skupiny', 'cpnrp' ),
		'show_admin_column' => true,
		'rewrite'           => false,
	] );
}
add_action( 'init', 'cpnrp_register_contact_person_cpt' );

// ── Meta box — role + phone ─────────────────────────────────────

function cpnrp_contact_person_meta_box() {
	add_meta_box(
		'cpnrp_contact_fields',
		__( 'Údaje kontaktu', 'cpnrp' ),
		'cpnrp_contact_fields_render',
		'contact_person',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'cpnrp_contact_person_meta_box' );

function cpnrp_contact_fields_render( $post ) {
	wp_nonce_field( 'cpnrp_contact_fields_save', 'cpnrp_contact_fields_nonce' );
	$role  = get_post_meta( $post->ID, '_contact_role', true );
	$phone = get_post_meta( $post->ID, '_contact_phone', true );
	$email = get_post_meta( $post->ID, '_contact_email', true );
	?>
	<table class="form-table" style="margin-top:0">
		<tr>
			<th scope="row"><label for="cpnrp_contact_role"><?php esc_html_e( 'Funkce / role', 'cpnrp' ); ?></label></th>
			<td><input type="text" id="cpnrp_contact_role" name="cpnrp_contact_role" value="<?php echo esc_attr( $role ); ?>" style="width:100%" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="cpnrp_contact_phone"><?php esc_html_e( 'Telefon', 'cpnrp' ); ?></label></th>
			<td><input type="text" id="cpnrp_contact_phone" name="cpnrp_contact_phone" value="<?php echo esc_attr( $phone ); ?>" placeholder="+420 000 000 000" style="width:100%" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="cpnrp_contact_email"><?php esc_html_e( 'E-mail', 'cpnrp' ); ?></label></th>
			<td><input type="email" id="cpnrp_contact_email" name="cpnrp_contact_email" value="<?php echo esc_attr( $email ); ?>" placeholder="jmeno@cpnrp.cz" style="width:100%" /></td>
		</tr>
	</table>
	<?php
}

function cpnrp_contact_fields_save( $post_id ) {
	if (
		! isset( $_POST['cpnrp_contact_fields_nonce'] ) ||
		! wp_verify_nonce( $_POST['cpnrp_contact_fields_nonce'], 'cpnrp_contact_fields_save' ) ||
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		! current_user_can( 'edit_post', $post_id )
	) {
		return;
	}
	update_post_meta( $post_id, '_contact_role',  sanitize_text_field( $_POST['cpnrp_contact_role']  ?? '' ) );
	update_post_meta( $post_id, '_contact_phone', sanitize_text_field( $_POST['cpnrp_contact_phone'] ?? '' ) );
	update_post_meta( $post_id, '_contact_email', sanitize_email( $_POST['cpnrp_contact_email'] ?? '' ) );
}
add_action( 'save_post_contact_person', 'cpnrp_contact_fields_save' );

// ── One-time import of all 25 team members ─────────────────────

function cpnrp_import_contact_persons() {
	if ( get_transient( 'cpnrp_contacts_imported_v1' ) ) return;

	$groups = [
		'Vedení' => [
			[ 'name' => 'Margita Šantavá',             'role' => 'Ředitelka',                                          'phone' => '+420 731 402 414', 'file' => 'santava.jpg'     ],
			[ 'name' => 'Ing. Martina Hrabáčová',       'role' => 'Projektová manažerka, zástupkyně ředitelky',         'phone' => '+420 731 402 405', 'file' => 'hrabacova.jpg'   ],
			[ 'name' => 'Ing. Radka Štolcová',          'role' => 'Ekonomka',                                           'phone' => '+420 731 557 681', 'file' => 'stolcova.jpg'    ],
		],
		'Vzdělávání & programy' => [
			[ 'name' => 'Charlotta Kočí',               'role' => 'Vedoucí vzdělávání & programů pro osvojitele',       'phone' => '+420 771 770 380', 'file' => 'koci.jpg'        ],
			[ 'name' => 'Petra Pištorová',               'role' => 'Koordinátorka vzdělávání a doučování',               'phone' => '+420 771 770 690', 'file' => 'pistorova.jpg'   ],
			[ 'name' => 'Bc. Kateřina Vojanová',        'role' => 'Realizátorka podpůrných aktivit pro NRP',            'phone' => '+420 771 770 310', 'file' => 'vojanova.jpg'    ],
			[ 'name' => 'Michaela Šinfeltová',           'role' => 'Koordinátorka Dětského klubu',                       'phone' => '+420 601 332 884', 'file' => 'sinfeltova.jpg'  ],
			[ 'name' => 'Pavlína Kolářová',              'role' => 'PR, akce a fundraising',                             'phone' => '+420 416 533 554', 'file' => 'kolarova.jpg'    ],
		],
		'Doprovázení' => [
			[ 'name' => 'Mgr. Radka Stryalová',         'role' => 'Vedoucí doprovázení',                                'phone' => '+420 771 770 490', 'file' => 'stryalova.jpg'   ],
			[ 'name' => 'Bc. Kateřina Hladišová',       'role' => 'Regionální vedoucí doprovázení',                     'phone' => '+420 771 770 390', 'file' => 'hladisova.jpg'   ],
			[ 'name' => 'Mgr. Š. Kňourková Horáková',   'role' => 'Regionální vedoucí doprovázení',                     'phone' => '+420 771 770 335', 'file' => 'knourkova.jpg'   ],
			[ 'name' => 'Mgr. Monika Tumová',           'role' => 'Sociální pracovnice',                                'phone' => '+420 771 770 940', 'file' => 'tumova.jpg'      ],
			[ 'name' => 'Bc. Barbora Bienerová',        'role' => 'Sociální pracovnice',                                'phone' => '+420 771 770 330', 'file' => 'bienerova.jpg'   ],
			[ 'name' => 'Mgr. Jana Vernerová Kadlecová','role' => 'Sociální pracovnice',                                'phone' => '+420 739 596 251', 'file' => 'kadlecova.jpg'   ],
			[ 'name' => 'Bc. Yvetta Rychnovská',        'role' => 'Sociální pracovnice',                                'phone' => '+420 771 770 360', 'file' => 'rychnovska.jpeg' ],
			[ 'name' => 'Bc. Pavlína Baumanová',        'role' => 'Sociální pracovnice',                                'phone' => '+420 771 770 340', 'file' => 'baumanova.jpg'   ],
			[ 'name' => 'Bc. Tereza Svitáková',         'role' => 'Sociální pracovnice',                                'phone' => '+420 725 732 716', 'file' => 'svitakova.jpg'   ],
			[ 'name' => 'Bc. Aneta Jonášová',           'role' => 'Sociální pracovnice',                                'phone' => '+420 725 824 000', 'file' => 'jonasova.jpg'    ],
			[ 'name' => 'Mgr. Kamila Frusová',          'role' => 'Sociální pracovnice',                                'phone' => '+420 771 770 560', 'file' => 'frusova.jpg'     ],
			[ 'name' => 'Pavlína Mikšová',              'role' => 'Sociální pracovnice',                                'phone' => '+420 602 422 504', 'file' => 'miksova.jpg'     ],
			[ 'name' => 'Bc. Eliška Rychtářová',        'role' => 'Sociální pracovnice',                                'phone' => '+420 601 345 603', 'file' => 'rychtarova.jpg'  ],
			[ 'name' => 'Mgr. Tereza Dvořáková',        'role' => 'Sociální pracovnice',                                'phone' => '+420 771 770 320', 'file' => 'dvorakova.jpg'   ],
			[ 'name' => 'Andrea Holenda Šustrová, DiS.','role' => 'Sociální pracovnice',                                'phone' => '+420 727 929 361', 'file' => 'sustrova.jpg'    ],
		],
		'Odborné poradenství' => [
			[ 'name' => 'MUDr. Marie Štětinová',        'role' => 'Psychiatr a psychoterapeut',                         'phone' => '',                'file' => 'stetinova.jpg'   ],
			[ 'name' => 'Mgr. Věra Doušová',            'role' => 'Psycholog',                                          'phone' => '',                'file' => 'dousova.jpg'     ],
		],
	];

	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';

	$theme_dir = get_template_directory() . '/assets/images/team/';
	$upload    = wp_upload_dir();

	foreach ( $groups as $group_name => $members ) {
		$term = term_exists( $group_name, 'contact_group' );
		if ( ! $term ) {
			$term = wp_insert_term( $group_name, 'contact_group' );
		}
		$term_id = is_array( $term ) ? $term['term_id'] : $term;

		foreach ( $members as $person ) {
			$existing = get_posts( [
				'post_type'   => 'contact_person',
				'title'       => $person['name'],
				'post_status' => 'publish',
				'numberposts' => 1,
				'fields'      => 'ids',
			] );
			if ( $existing ) continue;

			$post_id = wp_insert_post( [
				'post_type'   => 'contact_person',
				'post_title'  => $person['name'],
				'post_status' => 'publish',
			] );
			if ( is_wp_error( $post_id ) ) continue;

			wp_set_object_terms( $post_id, (int) $term_id, 'contact_group' );
			update_post_meta( $post_id, '_contact_role',  $person['role'] );
			update_post_meta( $post_id, '_contact_phone', $person['phone'] );

			$src = $theme_dir . $person['file'];
			if ( ! file_exists( $src ) ) continue;

			$filename  = wp_unique_filename( $upload['path'], $person['file'] );
			$dest      = $upload['path'] . '/' . $filename;
			if ( ! copy( $src, $dest ) ) continue;

			$filetype  = wp_check_filetype( $filename );
			$attach_id = wp_insert_attachment( [
				'guid'           => $upload['url'] . '/' . $filename,
				'post_mime_type' => $filetype['type'],
				'post_title'     => $person['name'],
				'post_status'    => 'inherit',
			], $dest, $post_id );

			if ( ! is_wp_error( $attach_id ) ) {
				wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $dest ) );
				set_post_thumbnail( $post_id, $attach_id );
			}
		}
	}

	set_transient( 'cpnrp_contacts_imported_v1', true, YEAR_IN_SECONDS );
}
add_action( 'admin_init', 'cpnrp_import_contact_persons' );

// ══════════════════════════════════════════════════════════════
// GALLERY CPT — META BOXES
// ══════════════════════════════════════════════════════════════

add_action( 'add_meta_boxes', function (): void {
	add_meta_box( 'cpnrp_gallery_photos', 'Fotografie alba',   'cpnrp_gallery_photos_metabox', 'gallery', 'normal', 'high' );
	add_meta_box( 'cpnrp_gallery_info',   'Informace o albu',  'cpnrp_gallery_info_metabox',   'gallery', 'side',   'high' );
} );

add_action( 'admin_enqueue_scripts', function ( string $hook ): void {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
	$post = get_post();
	if ( ! $post || $post->post_type !== 'gallery' ) return;
	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
} );

function cpnrp_gallery_photos_metabox( WP_Post $post ): void {
	wp_nonce_field( 'cpnrp_gallery_save', 'cpnrp_gallery_nonce' );
	$ids = json_decode( get_post_meta( $post->ID, '_gallery_photos', true ) ?: '[]', true );
	if ( ! is_array( $ids ) ) $ids = [];
	?>
	<style>
	#cpnrp-gallery-thumbs { display:flex; flex-wrap:wrap; gap:8px; min-height:90px; padding:8px; border:1px solid #dcdcde; border-radius:4px; background:#f9f9f9; margin-bottom:12px; }
	.cpnrp-gthumb { position:relative; width:80px; height:80px; cursor:grab; border-radius:4px; overflow:hidden; border:2px solid #dcdcde; background:#eee; }
	.cpnrp-gthumb img { width:100%; height:100%; object-fit:cover; display:block; pointer-events:none; }
	.cpnrp-gthumb-rm { position:absolute; top:2px; right:2px; width:20px; height:20px; border-radius:50%; background:rgba(201,32,36,0.9); color:#fff; border:none; cursor:pointer; font-size:16px; line-height:1; display:flex; align-items:center; justify-content:center; padding:0; }
	.cpnrp-gthumb-rm:hover { background:#c92024; }
	#cpnrp-gallery-empty { color:#888; font-style:italic; padding:8px; align-self:center; }
	</style>
	<div id="cpnrp-gallery-thumbs">
		<?php if ( empty( $ids ) ) : ?>
			<span id="cpnrp-gallery-empty">Zatím žádné fotografie. Klikněte na "Přidat fotografie" níže.</span>
		<?php else : ?>
			<?php foreach ( $ids as $id ) :
				$url = wp_get_attachment_image_url( (int) $id, 'thumbnail' );
				if ( ! $url ) continue;
			?>
			<div class="cpnrp-gthumb" data-id="<?php echo (int) $id; ?>">
				<img src="<?php echo esc_url( $url ); ?>" alt="">
				<button type="button" class="cpnrp-gthumb-rm" aria-label="Odebrat">×</button>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<input type="hidden" name="gallery_photo_ids" id="gallery-photo-ids" value="<?php echo esc_attr( json_encode( $ids ) ); ?>">
	<button type="button" id="cpnrp-add-photos" class="button button-secondary">+ Přidat fotografie</button>
	<p class="description" style="margin-top:6px">Fotografie lze přeuspořádat přetažením. Klikněte na × pro odebrání.</p>
	<script>
	(function() {
		var ids = <?php echo json_encode( array_values( array_map( 'intval', $ids ) ) ); ?>;
		var container = document.getElementById('cpnrp-gallery-thumbs');
		var hiddenInput = document.getElementById('gallery-photo-ids');

		function sync() {
			ids = Array.from(container.querySelectorAll('.cpnrp-gthumb')).map(function(el){ return parseInt(el.dataset.id); });
			hiddenInput.value = JSON.stringify(ids);
			var empty = document.getElementById('cpnrp-gallery-empty');
			if (empty) empty.style.display = ids.length ? 'none' : '';
		}

		function addThumb(id, src) {
			var empty = document.getElementById('cpnrp-gallery-empty');
			if (empty) empty.style.display = 'none';
			var div = document.createElement('div');
			div.className = 'cpnrp-gthumb'; div.dataset.id = id;
			var img = document.createElement('img'); img.src = src; img.alt = '';
			var btn = document.createElement('button'); btn.type = 'button'; btn.className = 'cpnrp-gthumb-rm'; btn.textContent = '×'; btn.setAttribute('aria-label','Odebrat');
			btn.addEventListener('click', function(){ div.remove(); sync(); });
			div.appendChild(img); div.appendChild(btn);
			container.appendChild(div);
		}

		container.addEventListener('click', function(e) {
			if (e.target.classList.contains('cpnrp-gthumb-rm')) {
				e.target.closest('.cpnrp-gthumb').remove(); sync();
			}
		});

		document.getElementById('cpnrp-add-photos').addEventListener('click', function() {
			var frame = wp.media({ title:'Vybrat fotografie', button:{ text:'Přidat do alba' }, multiple:true });
			frame.on('select', function() {
				frame.state().get('selection').each(function(att) {
					if (ids.indexOf(att.id) === -1) {
						addThumb(att.id, att.attributes.sizes && att.attributes.sizes.thumbnail ? att.attributes.sizes.thumbnail.url : att.attributes.url);
					}
				});
				sync();
			});
			frame.open();
		});

		if (window.jQuery && jQuery.fn.sortable) {
			jQuery(container).sortable({ items:'.cpnrp-gthumb', cursor:'grab', update: sync });
		}
	})();
	</script>
	<?php
}

function cpnrp_gallery_info_metabox( WP_Post $post ): void {
	$date_label = get_post_meta( $post->ID, '_gallery_date', true );
	?>
	<table class="form-table" style="margin:0">
		<tr>
			<th scope="row" style="padding:8px 10px 8px 0;font-weight:600;font-size:12px;text-transform:uppercase;letter-spacing:.04em;color:#50575e;white-space:nowrap">
				Datum / období
			</th>
			<td style="padding:8px 0">
				<input type="text" name="gallery_date" value="<?php echo esc_attr( $date_label ); ?>"
				       placeholder="např. říjen 2025" style="width:100%">
				<p class="description">Zobrazí se pod názvem alba (volitelné).</p>
			</td>
		</tr>
	</table>
	<?php
}

// ══════════════════════════════════════════════════════════════
// GALERIE CPT — META BOXES (photo picker, same as gallery)
// ══════════════════════════════════════════════════════════════

add_action( 'add_meta_boxes', function (): void {
	add_meta_box( 'cpnrp_galerie_photos', 'Fotografie alba', 'cpnrp_galerie_photos_metabox', 'galerie', 'normal', 'high' );
} );

add_action( 'admin_enqueue_scripts', function ( string $hook ): void {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
	$post = get_post();
	if ( ! $post || $post->post_type !== 'galerie' ) return;
	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
} );

function cpnrp_galerie_photos_metabox( WP_Post $post ): void {
	wp_nonce_field( 'cpnrp_galerie_save', 'cpnrp_galerie_nonce' );
	$ids = json_decode( get_post_meta( $post->ID, '_gallery_photos', true ) ?: '[]', true );
	if ( ! is_array( $ids ) ) $ids = [];
	// Count photos referenced in post_content shortcode (read-only indicator)
	preg_match( '/ids="([0-9,]+)"/', $post->post_content, $m );
	$legacy_count = $m ? count( explode( ',', $m[1] ) ) : 0;
	?>
	<?php if ( $legacy_count && empty( $ids ) ) : ?>
	<div style="margin-bottom:10px;padding:8px 12px;background:#fff8e1;border-left:4px solid #f4c430;border-radius:2px;font-size:13px;">
		Album obsahuje <?php echo $legacy_count; ?> fotek z původního systému (propojení se ztratilo). Přidejte fotografie ručně pomocí tlačítka níže.
	</div>
	<?php endif; ?>
	<style>
	#cpnrp-galerie-thumbs{display:flex;flex-wrap:wrap;gap:8px;min-height:90px;padding:8px;border:1px solid #dcdcde;border-radius:4px;background:#f9f9f9;margin-bottom:12px}
	.cpnrp-gthumb{position:relative;width:80px;height:80px;cursor:grab;border-radius:4px;overflow:hidden;border:2px solid #dcdcde;background:#eee}
	.cpnrp-gthumb img{width:100%;height:100%;object-fit:cover;display:block;pointer-events:none}
	.cpnrp-gthumb-rm{position:absolute;top:2px;right:2px;width:20px;height:20px;border-radius:50%;background:rgba(201,32,36,0.9);color:#fff;border:none;cursor:pointer;font-size:16px;line-height:1;display:flex;align-items:center;justify-content:center;padding:0}
	</style>
	<div id="cpnrp-galerie-thumbs">
		<?php if ( empty( $ids ) ) : ?>
			<span id="cpnrp-galerie-empty" style="color:#888;font-style:italic;padding:8px;align-self:center">Zatím žádné fotografie.</span>
		<?php else : ?>
			<?php foreach ( $ids as $id ) :
				$url = wp_get_attachment_image_url( (int) $id, 'thumbnail' );
				if ( ! $url ) continue;
			?>
			<div class="cpnrp-gthumb" data-id="<?php echo (int) $id; ?>">
				<img src="<?php echo esc_url( $url ); ?>" alt="">
				<button type="button" class="cpnrp-gthumb-rm" aria-label="Odebrat">×</button>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<input type="hidden" name="galerie_photo_ids" id="galerie-photo-ids" value="<?php echo esc_attr( json_encode( $ids ) ); ?>">
	<button type="button" id="cpnrp-add-galerie-photos" class="button button-secondary">+ Přidat fotografie</button>
	<script>
	(function(){
		var container=document.getElementById('cpnrp-galerie-thumbs');
		var hiddenInput=document.getElementById('galerie-photo-ids');
		var ids=<?php echo json_encode(array_values(array_map('intval',$ids))); ?>;
		function sync(){ids=Array.from(container.querySelectorAll('.cpnrp-gthumb')).map(function(el){return parseInt(el.dataset.id)});hiddenInput.value=JSON.stringify(ids);var e=document.getElementById('cpnrp-galerie-empty');if(e)e.style.display=ids.length?'none':'';}
		function addThumb(id,src){var e=document.getElementById('cpnrp-galerie-empty');if(e)e.style.display='none';var d=document.createElement('div');d.className='cpnrp-gthumb';d.dataset.id=id;var i=document.createElement('img');i.src=src;i.alt='';var b=document.createElement('button');b.type='button';b.className='cpnrp-gthumb-rm';b.textContent='×';b.setAttribute('aria-label','Odebrat');b.addEventListener('click',function(){d.remove();sync();});d.appendChild(i);d.appendChild(b);container.appendChild(d);}
		container.addEventListener('click',function(e){if(e.target.classList.contains('cpnrp-gthumb-rm')){e.target.closest('.cpnrp-gthumb').remove();sync();}});
		document.getElementById('cpnrp-add-galerie-photos').addEventListener('click',function(){var frame=wp.media({title:'Vybrat fotografie',button:{text:'Přidat do alba'},multiple:true});frame.on('select',function(){frame.state().get('selection').each(function(att){if(ids.indexOf(att.id)===-1){addThumb(att.id,att.attributes.sizes&&att.attributes.sizes.thumbnail?att.attributes.sizes.thumbnail.url:att.attributes.url);}});sync();});frame.open();});
		if(window.jQuery&&jQuery.fn.sortable){jQuery(container).sortable({items:'.cpnrp-gthumb',cursor:'grab',update:sync});}
	})();
	</script>
	<?php
}

add_action( 'save_post_galerie', function ( int $post_id ): void {
	if ( ! isset( $_POST['cpnrp_galerie_nonce'] ) ||
	     ! wp_verify_nonce( $_POST['cpnrp_galerie_nonce'], 'cpnrp_galerie_save' ) ||
	     ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
	     ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['galerie_photo_ids'] ) ) {
		$ids = json_decode( stripslashes( $_POST['galerie_photo_ids'] ), true );
		update_post_meta( $post_id, '_gallery_photos', wp_json_encode( is_array( $ids ) ? array_map( 'intval', $ids ) : [] ) );
	}
} );

add_action( 'save_post_gallery', function ( int $post_id ): void {
	if ( ! isset( $_POST['cpnrp_gallery_nonce'] ) ||
	     ! wp_verify_nonce( $_POST['cpnrp_gallery_nonce'], 'cpnrp_gallery_save' ) ||
	     ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
	     ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['gallery_photo_ids'] ) ) {
		$ids = json_decode( stripslashes( $_POST['gallery_photo_ids'] ), true );
		update_post_meta( $post_id, '_gallery_photos', wp_json_encode( is_array( $ids ) ? array_map( 'intval', $ids ) : [] ) );
	}

	if ( isset( $_POST['gallery_date'] ) ) {
		update_post_meta( $post_id, '_gallery_date', sanitize_text_field( $_POST['gallery_date'] ) );
	}
} );

// ══════════════════════════════════════════════════════════════
// CONTACT PERSON — Bio + druhá fotografie
// ══════════════════════════════════════════════════════════════

// Enqueue editor + media on contact_person edit screen
add_action( 'admin_enqueue_scripts', function ( string $hook ): void {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
	$post = get_post();
	if ( ! $post || $post->post_type !== 'contact_person' ) return;
	wp_enqueue_media();
	wp_enqueue_editor();
} );

add_action( 'add_meta_boxes', function (): void {
	add_meta_box(
		'cpnrp_contact_bio',
		__( 'Bio / Více o mně (popup)', 'cpnrp' ),
		'cpnrp_contact_bio_metabox',
		'contact_person',
		'normal',
		'default'
	);
} );

function cpnrp_contact_bio_metabox( WP_Post $post ): void {
	wp_nonce_field( 'cpnrp_contact_bio_save', 'cpnrp_contact_bio_nonce' );

	$bio    = get_post_meta( $post->ID, '_contact_bio',    true );
	$photo2 = (int) get_post_meta( $post->ID, '_contact_photo2', true );
	$p2_url = $photo2 ? wp_get_attachment_image_url( $photo2, 'large' ) : '';
	?>
	<style>
	.cpnrp-bio-box { display:flex; flex-direction:column; gap:16px; padding:4px 0; }
	.cpnrp-photo-zone {
		position:relative; width:100%; height:120px;
		border-radius:8px; overflow:hidden; cursor:pointer;
		border:2px dashed #c3c4c7; background:#f6f7f7;
		display:flex; flex-direction:row; align-items:center; justify-content:center;
		gap:12px; transition:border-color 200ms,background 200ms;
	}
	.cpnrp-photo-zone:hover { border-color:#2271b1; background:#f0f6fc; }
	.cpnrp-photo-zone.has-photo {
		border-style:solid; border-color:#dcdcde;
		height:auto; min-height:0; background:#000; cursor:default;
	}
	.cpnrp-photo-zone img {
		display:block; max-width:100%; max-height:320px;
		width:auto; height:auto; object-fit:contain; margin:0 auto;
	}
	.cpnrp-photo-zone-label { position:relative;z-index:1;display:flex;flex-direction:row;align-items:center;gap:10px;color:#646970;pointer-events:none; }
	.cpnrp-photo-zone-label-text { display:flex;flex-direction:column;gap:2px; }
	.cpnrp-photo-zone.has-photo .cpnrp-photo-zone-label { display:none; }
	.cpnrp-photo-actions { display:flex; gap:8px; }
	.cpnrp-section-label { font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:#646970;margin:0; }
	</style>

	<div class="cpnrp-bio-box">

		<!-- ── Druhá fotografie ─────────────────────────────────── -->
		<div>
			<p class="cpnrp-section-label" style="margin-bottom:8px">Druhá fotografie</p>
			<input type="hidden" id="cpnrp_contact_photo2" name="cpnrp_contact_photo2"
			       value="<?php echo esc_attr( $photo2 ?: '' ); ?>">
			<div id="cpnrp-photo-zone" class="cpnrp-photo-zone<?php echo $p2_url ? ' has-photo' : ''; ?>">
				<?php if ( $p2_url ) : ?>
					<img id="cpnrp-p2-img" src="<?php echo esc_url( $p2_url ); ?>"
					     alt="">
				<?php else : ?>
					<img id="cpnrp-p2-img" src="" alt="" style="display:none">
				<?php endif; ?>
				<div class="cpnrp-photo-zone-label">
					<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" style="flex-shrink:0">
						<path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
						<circle cx="12" cy="13" r="4"/>
					</svg>
					<div class="cpnrp-photo-zone-label-text">
						<span style="font-size:13px;font-weight:500">Kliknout pro výběr fotografie</span>
						<span style="font-size:11px;color:#9ca3af">Zobrazí se v popupu</span>
					</div>
				</div>
			</div>
			<div class="cpnrp-photo-actions" style="margin-top:8px">
				<button type="button" id="cpnrp-p2-select" class="button button-secondary">
					<?php echo $p2_url ? 'Změnit fotografii' : 'Vybrat fotografii'; ?>
				</button>
				<button type="button" id="cpnrp-p2-remove" class="button"
				        style="color:#cc0000<?php echo $photo2 ? '' : ';display:none'; ?>">
					Odebrat
				</button>
			</div>
		</div>

		<!-- ── Bio editor ──────────────────────────────────────── -->
		<div>
			<p class="cpnrp-section-label" style="margin-bottom:8px">Bio</p>
			<textarea id="cpnrp_bio_field" name="cpnrp_contact_bio"
			          style="width:100%;visibility:hidden;height:0;padding:0;border:0"><?php echo esc_textarea( $bio ?: '' ); ?></textarea>
		</div>

	</div>

	<script>
	(function () {
		// ── Editor ────────────────────────────────────────────────
		jQuery(function () {
			if (typeof wp !== 'undefined' && wp.editor) {
				wp.editor.initialize('cpnrp_bio_field', {
					tinymce: {
						wpautop: true,
						toolbar1: 'formatselect bold italic | bullist numlist | blockquote | alignleft aligncenter alignright | link unlink | removeformat',
						toolbar2: '',
						height: 300,
					},
					quicktags: { buttons: 'strong,em,link,ul,ol,li,close' },
					mediaButtons: false,
				});
			} else {
				var ta = document.getElementById('cpnrp_bio_field');
				if (ta) { ta.style.visibility = ''; ta.style.height = '220px'; }
			}
		});

		// ── Photo picker ──────────────────────────────────────────
		var zone      = document.getElementById('cpnrp-photo-zone');
		var img       = document.getElementById('cpnrp-p2-img');
		var input     = document.getElementById('cpnrp_contact_photo2');
		var selectBtn = document.getElementById('cpnrp-p2-select');
		var removeBtn = document.getElementById('cpnrp-p2-remove');

		function setPhoto(id, url) {
			input.value = id;
			img.src     = url;
			img.style.display = '';
			zone.classList.add('has-photo');
			selectBtn.textContent = 'Změnit fotografii';
			removeBtn.style.display = '';
		}
		function removePhoto() {
			input.value = '';
			img.src = '';
			img.style.display = 'none';
			zone.classList.remove('has-photo');
			selectBtn.textContent = 'Vybrat fotografii';
			removeBtn.style.display = 'none';
		}

		function openPicker() {
			var frame = wp.media({ title: 'Vybrat fotografii', button: { text: 'Použít' }, multiple: false });
			frame.on('select', function () {
				var att = frame.state().get('selection').first().toJSON();
				var url = att.sizes && att.sizes.large ? att.sizes.large.url
				        : att.sizes && att.sizes.medium ? att.sizes.medium.url
				        : att.url;
				setPhoto(att.id, url);
			});
			frame.open();
		}

		if (zone)      zone.addEventListener('click', function(e){ if (!e.target.closest('button')) openPicker(); });
		if (selectBtn) selectBtn.addEventListener('click', openPicker);
		if (removeBtn) removeBtn.addEventListener('click', removePhoto);
	})();
	</script>
	<?php
}

add_action( 'save_post_contact_person', function ( int $post_id ): void {
	if (
		! isset( $_POST['cpnrp_contact_bio_nonce'] ) ||
		! wp_verify_nonce( $_POST['cpnrp_contact_bio_nonce'], 'cpnrp_contact_bio_save' ) ||
		( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
		! current_user_can( 'edit_post', $post_id )
	) return;

	$bio    = isset( $_POST['cpnrp_contact_bio'] ) ? wp_kses_post( wp_unslash( $_POST['cpnrp_contact_bio'] ) ) : '';
	$photo2 = isset( $_POST['cpnrp_contact_photo2'] ) ? (int) $_POST['cpnrp_contact_photo2'] : 0;

	update_post_meta( $post_id, '_contact_bio',    $bio );
	update_post_meta( $post_id, '_contact_photo2', $photo2 > 0 ? $photo2 : '' );
} );
