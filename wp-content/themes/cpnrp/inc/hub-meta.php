<?php
/**
 * Hub / Rozcestník — meta boxes for the "Rozcestník" page template.
 * Hub items are child pages (set Parent Page in Attributes).
 * Item description = page Excerpt.
 */

// Enable Excerpt for Pages so admins can set hero description and item descriptions
add_action( 'init', function () {
	add_post_type_support( 'page', 'excerpt' );
} );

// ── One-time setup: template, meta, child pages ──────────────────
add_action( 'admin_init', 'cpnrp_hub_setup_v1' );
function cpnrp_hub_setup_v1() {
	if ( get_transient( 'cpnrp_hub_setup_v2' ) ) return;

	$hubs = [
		[
			'title'   => 'Adopce',
			'slugs'   => [ 'adopce', 'pro-rodiny/adopce' ],
			'excerpt' => 'Osvojení je trvalá forma náhradní rodinné péče. Pomůžeme vám celým procesem — od prvního zájmu až po život s dítětem.',
			'meta'    => [
				'_hub_eyebrow'       => 'Co nabízíme',
				'_hub_section_title' => 'Služby pro adoptivní rodiny',
				'_hub_card_show'     => '1',
				'_hub_card_text'     => 'Adopce, nebo pěstounská péče? Zjistěte, co vám sedí.',
				'_hub_card_url'      => '/pro-rodiny/zajemci/adopce-nebo-pestounstvi',
			],
			'items' => [
				[ 'title' => 'Jak začít — první kroky',     'slug' => 'jak-zacit',       'desc' => 'Devět kroků procesu osvojení, od podání žádosti až po soudní rozhodnutí.' ],
				[ 'title' => 'Přípravné kurzy pro žadatele','slug' => 'pripravne-kurzy', 'desc' => 'Povinná příprava v rozsahu 48 hodin zaměřená na specifika adopce a potřeby dětí v náhradní péči.' ],
				[ 'title' => 'Odborné poradenství',         'slug' => 'poradenstvi',     'desc' => 'Psychologické, právní a sociální poradenství pro adoptivní rodiny — ve všech fázích procesu.' ],
				[ 'title' => 'Vzdělávací semináře',         'slug' => 'seminare',        'desc' => 'Semináře a workshopy zaměřené na výchovu, komunikaci a specifika adoptivního rodičovství.' ],
				[ 'title' => 'Víkendové pobyty',            'slug' => 'pobyty',          'desc' => 'Společné pobyty pro adoptivní rodiny — odpočinek, sdílení a vzájemná podpora.' ],
				[ 'title' => 'Kontakt na pracovníka',       'slug' => 'kontakt',         'desc' => 'Spojte se přímo s odborníkem, který vás procesem provede.' ],
			],
		],
		[
			'title'   => 'Pěstounská péče',
			'slugs'   => [ 'pestounska-pece', 'pro-rodiny/pestounska-pece' ],
			'excerpt' => 'Podporujeme pěstounské rodiny na každém kroku — od přípravy přes každodenní péči až po krizové situace.',
			'meta'    => [
				'_hub_eyebrow'       => 'Co nabízíme',
				'_hub_section_title' => 'Služby pro pěstounské rodiny',
				'_hub_card_show'     => '1',
				'_hub_card_text'     => 'Adopce, nebo pěstounská péče? Zjistěte, co vám sedí.',
				'_hub_card_url'      => '/pro-rodiny/zajemci/adopce-nebo-pestounstvi',
			],
			'items' => [
				[ 'title' => 'Jak začít — první kroky', 'slug' => 'jak-zacit',           'desc' => 'Osm kroků na cestě k pěstounství. Co obnáší pěstounská péče a jak se do ní zapojit.' ],
				[ 'title' => 'Doprovázení rodin',       'slug' => 'doprovazeni',          'desc' => 'Pravidelná podpora klíčového sociálního pracovníka — od každodenních otázek po krizové situace.' ],
				[ 'title' => 'Odborné poradenství',     'slug' => 'poradenstvi',          'desc' => 'Psychologické, právní a sociální poradenství pro pěstounské rodiny.' ],
				[ 'title' => 'Vzdělávání pěstounů',     'slug' => 'vzdelavani',           'desc' => 'Povinné i nadstavbové vzdělávání — 24 hodin ročně. Semináře, workshopy a konference.' ],
				[ 'title' => 'Odlehčovací služby',      'slug' => 'odlehcovaci-sluzby',  'desc' => 'Respitní péče a víkendové pobyty, které pěstounům umožní odpočinek a načerpání sil.' ],
				[ 'title' => 'Asistovaný kontakt',      'slug' => 'asistovany-kontakt',  'desc' => 'Zprostředkování a doprovázení kontaktu dítěte s biologickou rodinou v bezpečném prostředí.' ],
				[ 'title' => 'Kontakt na pracovníka',   'slug' => 'kontakt',             'desc' => 'Spojte se přímo s klíčovým pracovníkem nebo vedoucí doprovázení.' ],
			],
		],
		[
			'title'   => 'Zájemci o NRP',
			'slugs'   => [ 'zajemci-o-nrp', 'zajemci', 'pro-rodiny/zajemci-o-nrp', 'pro-rodiny/zajemci' ],
			'excerpt' => 'Teprve se rozhodujete? Jsme tu, abychom vám pomohli zorientovat se. Žádný závazek, žádný tlak.',
			'meta'    => [
				'_hub_eyebrow'              => 'Začněte zde',
				'_hub_section_title'        => 'O čem si přečíst nejdřív',
				'_hub_card_show'            => '',
				'_hub_blockquote_show'      => '1',
				'_hub_blockquote_text'      => 'Teprve se rozhodujete? Napište nám — rádi odpovíme na všechny otázky bez závazku.',
				'_hub_blockquote_link_text' => 'Domluvit konzultaci',
				'_hub_blockquote_link_url'  => '/kontakt',
			],
			'items' => [
				[ 'title' => 'Co je náhradní péče?',    'slug' => 'co-je-nrp',               'desc' => 'Základní přehled forem náhradní rodinné péče v České republice.' ],
				[ 'title' => 'Adopce nebo pěstounství?','slug' => 'adopce-nebo-pestounstvi', 'desc' => 'Jaký je rozdíl? Která forma je vhodná právě pro vás? Krátký přehled pomůže s rozhodnutím.' ],
				[ 'title' => 'Přípravné kurzy',         'slug' => 'pripravne-kurzy',         'desc' => 'Informace o povinné přípravě pro zájemce — rozsah, obsah, termíny.' ],
				[ 'title' => 'Nejčastější otázky',      'slug' => 'faq',                     'desc' => 'Odpovědi na nejčastější dotazy o náhradní rodinné péči.' ],
			],
		],
	];

	global $wpdb;

	foreach ( $hubs as $hub ) {
		// 1. Find hub page: try slug paths first, then fall back to title search
		$page = null;
		foreach ( $hub['slugs'] as $path ) {
			$page = get_page_by_path( $path );
			if ( $page ) break;
		}
		if ( ! $page ) {
			$found_id = $wpdb->get_var( $wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_title = %s AND post_type = 'page' AND post_status = 'publish' LIMIT 1",
				$hub['title']
			) );
			if ( $found_id ) $page = get_post( $found_id );
		}
		if ( ! $page ) continue;

		$page_id = $page->ID;

		// 2. Assign Rozcestník template
		update_post_meta( $page_id, '_wp_page_template', 'page-rozcestnik.php' );

		// 3. Set excerpt (hero description)
		wp_update_post( [ 'ID' => $page_id, 'post_excerpt' => $hub['excerpt'] ] );

		// 4. Set meta fields
		foreach ( $hub['meta'] as $key => $val ) {
			update_post_meta( $page_id, $key, $val );
		}

		// 5. Create child pages — skip if slug already exists under this parent
		foreach ( $hub['items'] as $order => $item ) {
			$exists = $wpdb->get_var( $wpdb->prepare(
				"SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_parent = %d AND post_type = 'page' LIMIT 1",
				$item['slug'],
				$page_id
			) );
			if ( $exists ) continue;

			wp_insert_post( [
				'post_title'   => $item['title'],
				'post_excerpt' => $item['desc'],
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_parent'  => $page_id,
				'post_name'    => $item['slug'],
				'menu_order'   => $order + 1,
			] );
		}
	}

	set_transient( 'cpnrp_hub_setup_v2', true );
}

// ── Meta box registration — pouze na stránkách s Rozcestník šablonou ──
add_action( 'add_meta_boxes', function () {
	global $post;
	if ( ! $post ) return;

	$tpl = get_post_meta( $post->ID, '_wp_page_template', true );
	if ( $tpl !== 'page-rozcestnik.php' ) return;

	add_meta_box(
		'cpnrp_hub_settings',
		__( 'Rozcestník — nastavení', 'cpnrp' ),
		'cpnrp_hub_meta_box_cb',
		'page',
		'normal',
		'high'
	);
} );

// ── Meta box HTML ────────────────────────────────────────────────
function cpnrp_hub_meta_box_cb( $post ) {
	wp_nonce_field( 'cpnrp_hub_save', 'cpnrp_hub_nonce' );

	$eyebrow   = get_post_meta( $post->ID, '_hub_eyebrow',              true );
	$sec_title = get_post_meta( $post->ID, '_hub_section_title',        true );
	$card_show = get_post_meta( $post->ID, '_hub_card_show',            true );
	$card_text = get_post_meta( $post->ID, '_hub_card_text',            true );
	$card_url  = get_post_meta( $post->ID, '_hub_card_url',             true );
	$bq_show   = get_post_meta( $post->ID, '_hub_blockquote_show',      true );
	$bq_text   = get_post_meta( $post->ID, '_hub_blockquote_text',      true );
	$bq_link   = get_post_meta( $post->ID, '_hub_blockquote_link_text', true );
	$bq_url    = get_post_meta( $post->ID, '_hub_blockquote_link_url',  true );
	?>
	<style>
		.cpnrp-hub-meta table.form-table th { width: 200px; }
		.cpnrp-hub-meta .hub-meta-section { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #ddd; }
		.cpnrp-hub-meta .hub-meta-section:first-child { margin-top: 0; padding-top: 0; border-top: none; }
		.cpnrp-hub-meta label.block { display: block; margin-bottom: 4px; font-weight: 600; }
		.cpnrp-hub-meta input[type=text],
		.cpnrp-hub-meta input[type=url],
		.cpnrp-hub-meta textarea { width: 100%; max-width: 600px; margin-top: 4px; }
		.cpnrp-hub-meta .description { color: #666; font-style: italic; margin-top: 6px; }
	</style>
	<div class="cpnrp-hub-meta">

		<p class="description" style="margin-bottom:1.5em;">
			Tato pole platí pouze pro šablonu <strong>Rozcestník</strong>.<br>
			<strong>Položky seznamu</strong> = podřazené stránky (nastavte <em>Rodičovská stránka</em> v Atributech stránky).<br>
			Popis každé položky = <strong>Výňatek</strong> té podřazené stránky.<br>
			Pořadí položek = pole <strong>Pořadí</strong> v Atributech stránky.
		</p>

		<!-- Eyebrow & H2 -->
		<div class="hub-meta-section">
			<h4 style="margin-bottom:1rem;">Texty sekce</h4>
			<table class="form-table">
				<tr>
					<th><label for="hub_eyebrow">Eyebrow (nad nadpisem)</label></th>
					<td>
						<input type="text" id="hub_eyebrow" name="hub_eyebrow"
							value="<?php echo esc_attr( $eyebrow ); ?>"
							placeholder="Co nabízíme">
					</td>
				</tr>
				<tr>
					<th><label for="hub_section_title">Nadpis sekce (H2)</label></th>
					<td>
						<input type="text" id="hub_section_title" name="hub_section_title"
							value="<?php echo esc_attr( $sec_title ); ?>"
							placeholder="Ponechat prázdné = název stránky">
					</td>
				</tr>
			</table>
		</div>

		<!-- Decision card -->
		<div class="hub-meta-section">
			<h4 style="margin-bottom:1rem;">Zlatá karta „Nejste si jistí?" <span style="font-weight:normal;color:#666;">(zobrazí se nad seznamem)</span></h4>
			<table class="form-table">
				<tr>
					<th>Zobrazit kartu</th>
					<td>
						<label>
							<input type="checkbox" name="hub_card_show" value="1" <?php checked( $card_show, '1' ); ?>>
							Ano, zobrazit kartu
						</label>
					</td>
				</tr>
				<tr>
					<th><label for="hub_card_text">Text karty</label></th>
					<td>
						<input type="text" id="hub_card_text" name="hub_card_text"
							value="<?php echo esc_attr( $card_text ); ?>"
							placeholder="Adopce, nebo pěstounská péče? Zjistěte, co vám sedí.">
					</td>
				</tr>
				<tr>
					<th><label for="hub_card_url">URL karty</label></th>
					<td>
						<input type="url" id="hub_card_url" name="hub_card_url"
							value="<?php echo esc_attr( $card_url ); ?>"
							placeholder="https://…">
					</td>
				</tr>
			</table>
		</div>

		<!-- Blockquote -->
		<div class="hub-meta-section">
			<h4 style="margin-bottom:1rem;">Citát / blockquote <span style="font-weight:normal;color:#666;">(zobrazí se pod seznamem)</span></h4>
			<table class="form-table">
				<tr>
					<th>Zobrazit citát</th>
					<td>
						<label>
							<input type="checkbox" name="hub_blockquote_show" value="1" <?php checked( $bq_show, '1' ); ?>>
							Ano, zobrazit citát
						</label>
					</td>
				</tr>
				<tr>
					<th><label for="hub_blockquote_text">Text citátu</label></th>
					<td>
						<textarea id="hub_blockquote_text" name="hub_blockquote_text" rows="3"><?php echo esc_textarea( $bq_text ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th><label for="hub_blockquote_link_text">Text odkazu</label></th>
					<td>
						<input type="text" id="hub_blockquote_link_text" name="hub_blockquote_link_text"
							value="<?php echo esc_attr( $bq_link ); ?>"
							placeholder="Domluvit konzultaci">
					</td>
				</tr>
				<tr>
					<th><label for="hub_blockquote_link_url">URL odkazu</label></th>
					<td>
						<input type="url" id="hub_blockquote_link_url" name="hub_blockquote_link_url"
							value="<?php echo esc_attr( $bq_url ); ?>"
							placeholder="https://…">
					</td>
				</tr>
			</table>
		</div>

	</div>
	<?php
}

// ── Save ─────────────────────────────────────────────────────────
add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['cpnrp_hub_nonce'] ) || ! wp_verify_nonce( $_POST['cpnrp_hub_nonce'], 'cpnrp_hub_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$text_fields = [
		'_hub_eyebrow'              => 'sanitize_text_field',
		'_hub_section_title'        => 'sanitize_text_field',
		'_hub_card_text'            => 'sanitize_text_field',
		'_hub_card_url'             => 'esc_url_raw',
		'_hub_blockquote_text'      => 'sanitize_textarea_field',
		'_hub_blockquote_link_text' => 'sanitize_text_field',
		'_hub_blockquote_link_url'  => 'esc_url_raw',
	];

	foreach ( $text_fields as $meta_key => $sanitizer ) {
		$post_key = ltrim( $meta_key, '_' );
		if ( isset( $_POST[ $post_key ] ) ) {
			update_post_meta( $post_id, $meta_key, $sanitizer( $_POST[ $post_key ] ) );
		}
	}

	// Checkboxes
	update_post_meta( $post_id, '_hub_card_show',       isset( $_POST['hub_card_show'] )       ? '1' : '' );
	update_post_meta( $post_id, '_hub_blockquote_show', isset( $_POST['hub_blockquote_show'] ) ? '1' : '' );
} );
