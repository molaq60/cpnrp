<?php
/**
 * O nás section — one-time content + template assignment.
 * Runs on admin_init, guarded by transient 'cpnrp_o_nas_v1'.
 *
 * Pages:
 *   21795  O nás               → page-o-nas.php        + meta
 *   21823  Náš tým             → page-nas-tym.php       + meta
 *   21824  Slovo ředitelky     → page-slovo-reditelky.php + meta + content
 *   21825  Výroční zprávy      → page-podstranka.php    + content
 *   21826  Dokumenty ke stažení→ page-podstranka.php    + content
 *   21827  S kým spolupracujeme→ page-spolupracujeme.php + meta
 */

add_action( 'admin_init', 'cpnrp_o_nas_setup_v1' );
function cpnrp_o_nas_setup_v1() {
	if ( get_transient( 'cpnrp_o_nas_v1' ) ) return;

	// ── Slovo ředitelky — content ────────────────────────────────

	$slovo_content = '<p>Vážení přátelé, pěstouni a osvoji telé, partneři a příznivci,</p>'
		. '<p>dovolte mi, abych vás přivítala na stránkách Centra pro náhradní rodinnou péči.</p>'
		. '<p>Naše organizace vznikla z přesvědčení, že každé dítě — bez ohledu na to, v jaké rodině se narodilo — si zaslouží bezpečí, péči a lásku. Od roku 2002 stojíme po boku rodin, které se rozhodly otevřít svůj domov dítěti v nouzi. Viděli jsme radost z prvních setkání, náročnost adaptace, bolest i nevýslovnou hrdost na každý malý krok vpřed.</p>'
		. '<p>Za více než dvě desetiletí naší práce jsme doprovázeli stovky pěstounských a adoptivních rodin v Ústeckém kraji. Každý příběh je jiný — a každý si zaslouží naši plnou pozornost. Proto se snažíme být nablízku nejen v krizových momentech, ale po celou dobu, kdy je dítě v rodině.</p>'
		. '<p>Naše práce by nebyla možná bez důvěry rodin, které nás oslovují, bez partnerství s Ústeckým krajem a bez neuvěřitelného odhodlání celého týmu CPNRP. Jsem nesmírně vděčná za každého z nich.</p>'
		. '<p>Věřím, že pěstounství a adopce mohou být krásným životním naplněním — pokud jsou k dispozici správní průvodci na cestě. A my tady jsme právě pro to.</p>'
		. '<p>S úctou a vděčností,</p>';

	// ── Výroční zprávy — content ─────────────────────────────────

	$dl_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true" width="14" height="14"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';

	$zpravy_content = '<ul class="download-list">';
	foreach ( [ '2023', '2022', '2021', '2020', '2019' ] as $year ) {
		$zpravy_content .= '<li class="download-item">'
			. '<div class="download-info"><span class="download-badge">' . esc_html( $year ) . '</span>'
			. '<div><strong class="download-title">Výroční zpráva CPNRP ' . esc_html( $year ) . '</strong>'
			. '<span class="download-subtitle">PDF</span></div></div>'
			. '<a href="#" class="btn-download">Stáhnout ' . $dl_svg . '</a>'
			. '</li>';
	}
	$zpravy_content .= '</ul>';

	// ── Dokumenty ke stažení — content ──────────────────────────

	$docs_content = '<p class="hub-eyebrow">Formuláře</p>'
		. '<h2 class="hub-section-title" style="margin-bottom:1.5rem">Formuláře a žádosti</h2>'
		. '<ul class="download-list">';
	$formulare = [
		[ 'Přihláška na přípravné kurzy',             'PDF' ],
		[ 'Žádost o zprostředkování náhradní rodinné péče', 'PDF' ],
		[ 'Čestné prohlášení žadatele',               'PDF' ],
		[ 'Souhlas se zpracováním osobních údajů',    'PDF' ],
	];
	foreach ( $formulare as [ $title, $type ] ) {
		$docs_content .= '<li class="download-item">'
			. '<div class="download-info"><span class="download-badge">' . esc_html( $type ) . '</span>'
			. '<div><strong class="download-title">' . esc_html( $title ) . '</strong></div></div>'
			. '<a href="#" class="btn-download">Stáhnout ' . $dl_svg . '</a>'
			. '</li>';
	}
	$docs_content .= '</ul>';

	$docs_content .= '<p class="hub-eyebrow" style="margin-top:3rem">Informační materiály</p>'
		. '<h2 class="hub-section-title" style="margin-bottom:1.5rem">Příručky a brožury</h2>'
		. '<ul class="download-list">';
	$prirucky = [
		[ 'Průvodce procesem pěstounství',               'PDF' ],
		[ 'Průvodce procesem adopce',                    'PDF' ],
		[ 'Co je náhradní rodinná péče — přehled forem', 'PDF' ],
		[ 'Práva a povinnosti pěstounů',                 'PDF' ],
		[ 'Finanční dávky pro pěstouny',                 'PDF' ],
	];
	foreach ( $prirucky as [ $title, $type ] ) {
		$docs_content .= '<li class="download-item">'
			. '<div class="download-info"><span class="download-badge">' . esc_html( $type ) . '</span>'
			. '<div><strong class="download-title">' . esc_html( $title ) . '</strong></div></div>'
			. '<a href="#" class="btn-download">Stáhnout ' . $dl_svg . '</a>'
			. '</li>';
	}
	$docs_content .= '</ul>';

	// ── Apply templates + meta ────────────────────────────────────

	// O nás
	update_post_meta( 21795, '_wp_page_template', 'page-o-nas.php' );
	update_post_meta( 21795, '_o_nas_hero_desc', 'Jsme tým odborníků, který od roku 2002 pomáhá dětem najít domov a rodinám ho udržet.' );
	update_post_meta( 21795, '_o_nas_mission', 'Věříme, že každé dítě má právo vyrůstat v láskyplné rodině. Naším posláním je toto právo naplňovat — doprovázením pěstounů, podporou žadatelů o adopci a vzděláváním všech, kdo se o náhradní péči zajímají.' );
	update_post_meta( 21795, '_o_nas_blockquote', '„Chcete se dozvědět více o naší práci nebo s námi spolupracovat? Budeme rádi."' );
	update_post_meta( 21795, '_o_nas_blockquote_link', 'Napište nám' );

	// Náš tým
	update_post_meta( 21823, '_wp_page_template', 'page-nas-tym.php' );
	update_post_meta( 21823, '_nas_tym_hero_desc', 'Náš tým tvoří zkušení sociální pracovníci, psychologové a odborníci, kteří sdílejí společné přesvědčení — každé dítě si zaslouží domov.' );

	// Slovo ředitelky
	update_post_meta( 21824, '_wp_page_template', 'page-slovo-reditelky.php' );
	update_post_meta( 21824, '_subpage_hero_desc', 'Slovo na úvod od ředitelky CPNRP.' );
	update_post_meta( 21824, '_slovo_jmeno', 'Mgr. Jana Rychnovská' );
	update_post_meta( 21824, '_slovo_titul', 'Ředitelka CPNRP' );
	update_post_meta( 21824, '_slovo_photo', 'rychnovska.jpeg' );
	wp_update_post( [ 'ID' => 21824, 'post_content' => $slovo_content ] );

	// Výroční zprávy
	update_post_meta( 21825, '_wp_page_template', 'page-podstranka.php' );
	update_post_meta( 21825, '_subpage_hero_desc', 'Výroční zprávy CPNRP ke stažení ve formátu PDF.' );
	wp_update_post( [ 'ID' => 21825, 'post_content' => $zpravy_content ] );

	// Dokumenty ke stažení
	update_post_meta( 21826, '_wp_page_template', 'page-podstranka.php' );
	update_post_meta( 21826, '_subpage_hero_desc', 'Ke stažení — formuláře, žádosti a informační příručky.' );
	wp_update_post( [ 'ID' => 21826, 'post_content' => $docs_content ] );

	// S kým spolupracujeme
	update_post_meta( 21827, '_wp_page_template', 'page-spolupracujeme.php' );
	update_post_meta( 21827, '_spolupracujeme_hero_desc', 'Naše práce je možná díky důvěře a podpoře desítek partnerů — od měst a krajů po nadace a soukromé firmy.' );

	// Set excerpts for O nás nav cards
	$excerpts = [
		21823 => 'Poznejte lidi za CPNRP — sociální pracovníky, psychology a koordinátory, kteří denně pomáhají rodinám.',
		21824 => 'Osobní pohled ředitelky organizace na smysl naší práce a výzvy, se kterými se každodenně setkáváme.',
		21825 => 'Přehled naší činnosti, hospodaření a výsledků v jednotlivých letech — ke stažení v PDF.',
		21826 => 'Formuláře, žádosti a informační příručky pro pěstouny, žadatele i odbornou veřejnost.',
		21827 => 'Přehled institucí, nadací a firem, které podporují naši práci v Ústeckém kraji.',
	];
	foreach ( $excerpts as $pid => $exc ) {
		wp_update_post( [ 'ID' => $pid, 'post_excerpt' => $exc ] );
	}

	set_transient( 'cpnrp_o_nas_v1', true );
}

// ── v2: Náš tým → WP editor content, update excerpt ─────────────
add_action( 'admin_init', 'cpnrp_o_nas_setup_v2' );
function cpnrp_o_nas_setup_v2() {
	if ( get_transient( 'cpnrp_o_nas_v2' ) ) return;

	$tym_content =
		'<p>Náš tým tvoří přes 30 odborníků — sociálních pracovníků, psychologů, koordinátorů a terénních pracovníků. Všichni sdílí jedno přesvědčení: každé dítě si zaslouží domov a každá rodina zaslouží oporu.</p>'
		. '<h2>Vedení organizace</h2>'
		. '<p>Organizaci vede <strong>Mgr. Jana Rychnovská</strong> (ředitelka, <a href="mailto:rychnovska@cpnrp.cz">rychnovska@cpnrp.cz</a>). Odborné oddělení doprovázení řídí <strong>Mgr. Radka Strýalová</strong> (+420 771 770 490), program pro osvoji tele vede <strong>Charlotta Kočí</strong> (+420 771 770 380).</p>'
		. '<h2>Kontakty na celý tým</h2>'
		. '<p>Kompletní seznam pracovníků včetně kontaktů najdete na stránce <a href="' . esc_url( home_url( '/kontakt' ) ) . '">Kontakt</a>.</p>'
		. '<h2>Terénní tým</h2>'
		. '<p>Jádro naší práce tvoří terénní sociální pracovníci, kteří pravidelně navštěvují pěstounské rodiny v celém Ústeckém kraji, poskytují poradenství a pomáhají řešit každodenní situace. Dále spolupracujeme s externími odborníky — lektory, psychology a terapeuty — a desítkami doučovatelů a pečovatelů z řad studentů.</p>';

	wp_update_post( [ 'ID' => 21823, 'post_content' => $tym_content ] );
	wp_update_post( [ 'ID' => 21823, 'post_excerpt' => 'Kdo stojí za CPNRP — vedení, odborní pracovníci a terénní tým, který každý den pomáhá rodinám.' ] );

	set_transient( 'cpnrp_o_nas_v2', true );
}

// ── v3: Výroční zprávy → dedicated template + initial PDF entries ─
add_action( 'admin_init', 'cpnrp_o_nas_setup_v3' );
function cpnrp_o_nas_setup_v3() {
	if ( get_transient( 'cpnrp_o_nas_v3' ) ) return;

	// Switch Výroční zprávy (21825) to dedicated template
	update_post_meta( 21825, '_wp_page_template', 'page-vyrocni-zpravy.php' );
	update_post_meta( 21825, '_subpage_hero_desc', 'Výroční zprávy CPNRP ke stažení ve formátu PDF.' );

	// Pre-populate with placeholder entries (URL empty = "Brzy k dispozici")
	$existing = get_post_meta( 21825, '_vyrocni_zpravy', true );
	if ( ! is_array( $existing ) || empty( $existing ) ) {
		update_post_meta( 21825, '_vyrocni_zpravy', [
			[ 'year' => '2023', 'label' => '', 'url' => '' ],
			[ 'year' => '2022', 'label' => '', 'url' => '' ],
			[ 'year' => '2021', 'label' => '', 'url' => '' ],
			[ 'year' => '2020', 'label' => '', 'url' => '' ],
			[ 'year' => '2019', 'label' => '', 'url' => '' ],
		] );
	}

	set_transient( 'cpnrp_o_nas_v3', true );
}

// ── v4: Výroční zprávy → generate demo PDFs + register in media ──
add_action( 'admin_init', 'cpnrp_o_nas_setup_v4' );
function cpnrp_o_nas_setup_v4() {
	if ( get_transient( 'cpnrp_o_nas_v4' ) ) return;

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload    = wp_upload_dir();
	$dir_path  = trailingslashit( $upload['basedir'] ) . 'cpnrp-demo/';
	$dir_url   = trailingslashit( $upload['baseurl'] ) . 'cpnrp-demo/';

	wp_mkdir_p( $dir_path );

	$years = [ '2023', '2022', '2021', '2020', '2019' ];
	$items = [];

	foreach ( $years as $year ) {
		$filename = "vyrocni-zprava-cpnrp-{$year}.pdf";
		$filepath = $dir_path . $filename;
		$fileurl  = $dir_url  . $filename;

		// Write the demo PDF to disk
		file_put_contents( $filepath, cpnrp_generate_demo_pdf( "Vyrocni zprava CPNRP {$year}" ) );

		// Register as WP attachment (skip if already exists)
		$existing = get_posts( [
			'post_type'      => 'attachment',
			'meta_key'       => '_cpnrp_demo_pdf_year',
			'meta_value'     => $year,
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
		] );

		if ( $existing ) {
			$att_id = $existing[0]->ID;
		} else {
			$att_id = wp_insert_attachment( [
				'post_title'     => "Výroční zpráva CPNRP {$year} (demo)",
				'post_status'    => 'inherit',
				'post_mime_type' => 'application/pdf',
				'guid'           => $fileurl,
			], $filepath, 21825, true );

			if ( ! is_wp_error( $att_id ) ) {
				update_post_meta( $att_id, '_cpnrp_demo_pdf_year', $year );
			}
		}

		$items[] = [
			'year'  => $year,
			'label' => '',
			'url'   => is_wp_error( $att_id ) ? '' : $fileurl,
		];
	}

	update_post_meta( 21825, '_vyrocni_zpravy', $items );

	set_transient( 'cpnrp_o_nas_v4', true );
}

/**
 * Generate a minimal valid single-page PDF with a text title.
 * Byte offsets in the xref table are computed dynamically.
 */
function cpnrp_generate_demo_pdf( $title ) {
	// Strip characters unsafe in a PDF literal string
	$safe   = preg_replace( '/[()\\\\]/', '', $title );
	$stream = "BT /F1 16 Tf 50 790 Td ({$safe}) Tj\n/F1 11 Tf 0 -30 Td (Demo soubor — bude nahrazen skutecnou zpravou.) Tj ET";
	$len    = strlen( $stream );

	$pdf  = "%PDF-1.4\n";
	$off  = [];

	$off[1] = strlen( $pdf );
	$pdf   .= "1 0 obj\n<</Type /Catalog /Pages 2 0 R>>\nendobj\n";

	$off[2] = strlen( $pdf );
	$pdf   .= "2 0 obj\n<</Type /Pages /Kids [3 0 R] /Count 1>>\nendobj\n";

	$off[3] = strlen( $pdf );
	$pdf   .= "3 0 obj\n<</Type /Page /Parent 2 0 R /MediaBox [0 0 595 842]"
	        . " /Contents 4 0 R /Resources <</Font <</F1 5 0 R>>>>>>\nendobj\n";

	$off[4] = strlen( $pdf );
	$pdf   .= "4 0 obj\n<</Length {$len}>>\nstream\n{$stream}\nendstream\nendobj\n";

	$off[5] = strlen( $pdf );
	$pdf   .= "5 0 obj\n<</Type /Font /Subtype /Type1 /BaseFont /Helvetica>>\nendobj\n";

	$xref_pos = strlen( $pdf );
	$pdf .= "xref\n0 6\n";
	$pdf .= "0000000000 65535 f \n";
	foreach ( [ 1, 2, 3, 4, 5 ] as $n ) {
		$pdf .= sprintf( "%010d 00000 n \n", $off[ $n ] );
	}
	$pdf .= "trailer\n<</Size 6 /Root 1 0 R>>\n";
	$pdf .= "startxref\n{$xref_pos}\n%%EOF\n";

	return $pdf;
}

// ── v5: Dokumenty ke stažení → dedicated template + demo PDFs ────
add_action( 'admin_init', 'cpnrp_o_nas_setup_v5' );
function cpnrp_o_nas_setup_v5() {
	if ( get_transient( 'cpnrp_o_nas_v5' ) ) return;

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	update_post_meta( 21826, '_wp_page_template', 'page-dokumenty.php' );
	update_post_meta( 21826, '_subpage_hero_desc', 'Ke stažení — formuláře, žádosti a informační příručky.' );

	$upload   = wp_upload_dir();
	$dir_path = trailingslashit( $upload['basedir'] ) . 'cpnrp-demo/';
	$dir_url  = trailingslashit( $upload['baseurl'] ) . 'cpnrp-demo/';
	wp_mkdir_p( $dir_path );

	$groups = [
		'formulare' => [
			[ 'name' => 'Přihláška na přípravné kurzy',                   'type' => 'PDF', 'slug' => 'prihlaska-kurzy' ],
			[ 'name' => 'Žádost o zprostředkování náhradní rodinné péče', 'type' => 'PDF', 'slug' => 'zadost-nrp' ],
			[ 'name' => 'Čestné prohlášení žadatele',                     'type' => 'PDF', 'slug' => 'cestne-prohlaseni' ],
			[ 'name' => 'Souhlas se zpracováním osobních údajů',          'type' => 'PDF', 'slug' => 'souhlas-gdpr' ],
		],
		'prirucky' => [
			[ 'name' => 'Průvodce procesem pěstounství',                  'type' => 'PDF', 'slug' => 'pruvodce-pestounstvi' ],
			[ 'name' => 'Průvodce procesem adopce',                       'type' => 'PDF', 'slug' => 'pruvodce-adopce' ],
			[ 'name' => 'Co je náhradní rodinná péče — přehled forem',   'type' => 'PDF', 'slug' => 'prehled-forem-nrp' ],
			[ 'name' => 'Práva a povinnosti pěstounů',                    'type' => 'PDF', 'slug' => 'prava-pestounu' ],
			[ 'name' => 'Finanční dávky pro pěstouny',                    'type' => 'PDF', 'slug' => 'financni-davky' ],
		],
	];

	foreach ( $groups as $group_key => $docs ) {
		$items = [];
		foreach ( $docs as $doc ) {
			$filename = $doc['slug'] . '-demo.pdf';
			$filepath = $dir_path . $filename;
			$fileurl  = $dir_url  . $filename;

			file_put_contents( $filepath, cpnrp_generate_demo_pdf( $doc['name'] ) );

			$existing = get_posts( [
				'post_type'      => 'attachment',
				'meta_key'       => '_cpnrp_demo_pdf_slug',
				'meta_value'     => $doc['slug'],
				'post_status'    => 'inherit',
				'posts_per_page' => 1,
			] );

			if ( $existing ) {
				// already registered
			} else {
				$att_id = wp_insert_attachment( [
					'post_title'     => $doc['name'] . ' (demo)',
					'post_status'    => 'inherit',
					'post_mime_type' => 'application/pdf',
					'guid'           => $fileurl,
				], $filepath, 21826, true );

				if ( ! is_wp_error( $att_id ) ) {
					update_post_meta( $att_id, '_cpnrp_demo_pdf_slug', $doc['slug'] );
				}
			}

			$items[] = [
				'name' => $doc['name'],
				'type' => $doc['type'],
				'url'  => $fileurl,
			];
		}
		update_post_meta( 21826, "_dokumenty_{$group_key}", $items );
	}

	set_transient( 'cpnrp_o_nas_v5', true );
}

// ── v6: S kým spolupracujeme → naplnit loga ze složky theme ──────
add_action( 'admin_init', 'cpnrp_o_nas_setup_v6' );
function cpnrp_o_nas_setup_v6() {
	if ( get_transient( 'cpnrp_o_nas_v6' ) ) return;

	$base = get_template_directory_uri() . '/assets/images/partners/';

	$groups = [
		1 => [
			'eyebrow' => 'Veřejná sféra',
			'title'   => 'Veřejní a institucionální partneři',
			'items'   => [
				[ 'img' => $base . 'ustecky-kraj.jpg',            'name' => 'Ústecký kraj',               'url' => '' ],
				[ 'img' => $base . 'mesto-litomerice.jpg',        'name' => 'Město Litoměřice',           'url' => '' ],
				[ 'img' => $base . 'mesto-usti-nad-labem.jpg',    'name' => 'Město Ústí nad Labem',       'url' => '' ],
				[ 'img' => $base . 'zdrave-mesto-litomerice.jpg', 'name' => 'Zdravé město Litoměřice',    'url' => '' ],
				[ 'img' => $base . 'asociace-dite-a-rodina.png',  'name' => 'Asociace dítě a rodina',     'url' => '' ],
				[ 'img' => $base . 'rodinny-svaz.png',            'name' => 'Rodinný svaz ČR',            'url' => '' ],
			],
		],
		2 => [
			'eyebrow' => 'Filantropie',
			'title'   => 'Nadace a fondy',
			'items'   => [
				[ 'img' => $base . 'nadace-sirius.png',                'name' => 'Nadace Sirius',                  'url' => '' ],
				[ 'img' => $base . 'nadace-jt.png',                    'name' => 'Nadace JT',                      'url' => '' ],
				[ 'img' => $base . 'nadacni-fond-albert.png',          'name' => 'Nadační fond Albert',            'url' => '' ],
				[ 'img' => $base . 'nros-pomozte-detem.jpg',           'name' => 'Pomozte dětem',                  'url' => '' ],
				[ 'img' => $base . 'nadace-rhea.jpg',                  'name' => 'Nadace Rhea',                    'url' => '' ],
				[ 'img' => $base . 'nadacni-fond-severoceska-voda.jpg','name' => 'Nadační fond Severočeská voda', 'url' => '' ],
			],
		],
		3 => [
			'eyebrow' => 'Soukromý sektor',
			'title'   => 'Korporátní partneři',
			'items'   => [
				[ 'img' => $base . 'orbico.png',                          'name' => 'Orbico',                          'url' => '' ],
				[ 'img' => $base . 'siad.jpg',                            'name' => 'SIAD Czech',                      'url' => '' ],
				[ 'img' => $base . 'mondi.jpg',                           'name' => 'Mondi',                           'url' => '' ],
				[ 'img' => $base . 'magna-exteriors.png',                 'name' => 'Magna Exteriors',                 'url' => '' ],
				[ 'img' => $base . 'holcim.jpg',                          'name' => 'Holcim',                          'url' => '' ],
				[ 'img' => $base . 'ceska-podnikatelska-pojistovna.jpg',  'name' => 'Česká podnikatelská pojišťovna',  'url' => '' ],
				[ 'img' => $base . 'globus.jpg',                          'name' => 'Globus',                          'url' => '' ],
				[ 'img' => $base . 'decci.png',                           'name' => 'Decci',                           'url' => '' ],
				[ 'img' => $base . 'amedis.png',                          'name' => 'Amedis',                          'url' => '' ],
				[ 'img' => $base . 'cekro.png',                           'name' => 'Čekro',                           'url' => '' ],
				[ 'img' => $base . 'nn-konstrukce.png',                   'name' => 'NN Konstrukce',                   'url' => '' ],
				[ 'img' => $base . 'ab-clima.png',                        'name' => 'AB Clima',                        'url' => '' ],
			],
		],
	];

	foreach ( $groups as $i => $group ) {
		update_post_meta( 21827, "_spo_group{$i}_eyebrow", $group['eyebrow'] );
		update_post_meta( 21827, "_spo_group{$i}_title",   $group['title']   );
		update_post_meta( 21827, "_spo_group{$i}_items",   $group['items']   );
	}

	set_transient( 'cpnrp_o_nas_v6', true );
}
