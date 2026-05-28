<?php
/**
 * Meta boxes for O nás page templates:
 *   page-o-nas.php           → _o_nas_hero_desc, _o_nas_mission, _o_nas_blockquote, _o_nas_blockquote_link
 *   page-nas-tym.php         → _nas_tym_hero_desc
 *   page-spolupracujeme.php  → _spolupracujeme_hero_desc
 *   page-slovo-reditelky.php → _subpage_hero_desc (shared), _slovo_jmeno, _slovo_titul, _slovo_photo
 */

// ── Enqueue media scripts for image pickers ───────────────────────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
	global $post;
	if ( ! $post || $post->post_type !== 'page' ) return;
	if ( get_post_meta( $post->ID, '_wp_page_template', true ) === 'page-spolupracujeme.php' ) {
		wp_enqueue_media();
	}
} );

add_action( 'add_meta_boxes', function () {
	global $post;
	if ( ! $post ) return;
	$tpl = get_post_meta( $post->ID, '_wp_page_template', true );

	$map = [
		'page-o-nas.php'           => [ 'cpnrp_o_nas_settings',          'Nastavení stránky O nás',           'cpnrp_o_nas_meta_cb'          ],
		'page-nas-tym.php'         => [ 'cpnrp_nas_tym_settings',         'Nastavení stránky Náš tým',         'cpnrp_nas_tym_meta_cb'        ],
		'page-spolupracujeme.php'  => [ 'cpnrp_spolupracujeme_settings',  'Nastavení stránky Partneři',        'cpnrp_spolupracujeme_meta_cb' ],
		'page-slovo-reditelky.php' => [ 'cpnrp_slovo_reditelky_settings', 'Nastavení stránky Slovo ředitelky', 'cpnrp_slovo_reditelky_meta_cb'],
	];

	if ( isset( $map[ $tpl ] ) ) {
		[ $id, $title, $cb ] = $map[ $tpl ];
		add_meta_box( $id, $title, $cb, 'page', 'normal', 'high' );
	}
} );

// ── Visibility helper ─────────────────────────────────────────────

function _cpnrp_o_nas_meta_show( $box_id ) {
	global $post;
	if ( ! $post ) return false;
	$tpl = get_post_meta( $post->ID, '_wp_page_template', true );
	$map = [
		'cpnrp_o_nas_settings'          => 'page-o-nas.php',
		'cpnrp_nas_tym_settings'        => 'page-nas-tym.php',
		'cpnrp_spolupracujeme_settings' => 'page-spolupracujeme.php',
		'cpnrp_slovo_reditelky_settings'=> 'page-slovo-reditelky.php',
	];
	return isset( $map[ $box_id ] ) && $tpl === $map[ $box_id ];
}

// ── Render: O nás ─────────────────────────────────────────────────

function cpnrp_o_nas_meta_cb( $post ) {
	if ( ! _cpnrp_o_nas_meta_show( 'cpnrp_o_nas_settings' ) ) {
		echo '<p style="color:#999;font-style:italic">Aktivní pouze pro šablonu <strong>O nás</strong>.</p>';
		return;
	}
	wp_nonce_field( 'cpnrp_o_nas_save', 'cpnrp_o_nas_nonce' );
	$fields = [
		'_o_nas_hero_desc'       => [ 'Popis v hero',              'textarea' ],
		'_o_nas_s1_eyebrow'      => [ 'Sekce 1 — eyebrow',         'input' ],
		'_o_nas_s1_heading'      => [ 'Sekce 1 — nadpis',          'input' ],
		'_o_nas_mission'         => [ 'Text poslání (mission)',     'textarea' ],
		'_o_nas_value1'          => [ 'Hodnota 1 (bullet)',         'input' ],
		'_o_nas_value2'          => [ 'Hodnota 2 (bullet)',         'input' ],
		'_o_nas_value3'          => [ 'Hodnota 3 (bullet)',         'input' ],
		'_o_nas_value4'          => [ 'Hodnota 4 (bullet)',         'input' ],
		'_o_nas_s2_eyebrow'      => [ 'Sekce 2 — eyebrow',         'input' ],
		'_o_nas_s2_heading'      => [ 'Sekce 2 — nadpis',          'input' ],
		'_o_nas_blockquote'      => [ 'Citát / výzva (dole)',       'textarea' ],
		'_o_nas_blockquote_link' => [ 'Text odkazu citátu',         'input' ],
	];
	_cpnrp_render_fields( $post, $fields, [
		'_o_nas_hero_desc'       => 'Jsme tým odborníků, který od roku 2002 pomáhá dětem najít domov a rodinám ho udržet.',
		'_o_nas_s1_eyebrow'      => 'Naše poslání',
		'_o_nas_s1_heading'      => 'Pomáháme dětem najít domov',
		'_o_nas_mission'         => 'Věříme, že každé dítě má právo vyrůstat v láskyplné rodině. Naším posláním je toto právo naplňovat — doprovázením pěstounů, podporou žadatelů o adopci a vzděláváním všech, kdo se o náhradní péči zajímají.',
		'_o_nas_value1'          => 'Odborné doprovázení pěstounských rodin',
		'_o_nas_value2'          => 'Podpora žadatelů o adopci na každém kroku',
		'_o_nas_value3'          => 'Vzdělávání a osvěta v oblasti NRP',
		'_o_nas_value4'          => 'Komunitní aktivity a propojování rodin',
		'_o_nas_s2_eyebrow'      => 'Poznejte nás blíže',
		'_o_nas_s2_heading'      => 'Více o CPNRP',
		'_o_nas_blockquote'      => '„Chcete se dozvědět více o naší práci nebo s námi spolupracovat? Budeme rádi."',
		'_o_nas_blockquote_link' => 'Napište nám',
	] );

	// ── Stats ────────────────────────────────────────────────────────
	$stat_defaults = [
		1 => [ '22+',  'let pomáháme rodinám' ],
		2 => [ '400+', 'rodin ročně v doprovázení' ],
		3 => [ '25',   'odborných pracovníků' ],
		4 => [ '3',    'pobočky v Ústeckém kraji' ],
	];
	echo '<div style="margin-top:20px;padding-top:20px;border-top:1px solid #ddd">';
	echo '<h4 style="margin:0 0 12px;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#1A6080">Statistiky (4 čísla)</h4>';
	echo '<table class="form-table">';
	for ( $i = 1; $i <= 4; $i++ ) {
		$num   = get_post_meta( $post->ID, "_o_nas_stat{$i}_num",   true ) ?: $stat_defaults[ $i ][0];
		$label = get_post_meta( $post->ID, "_o_nas_stat{$i}_label", true ) ?: $stat_defaults[ $i ][1];
		echo "<tr><th>Statistika {$i}</th><td>";
		echo "<input type='text' name='o_nas_stat{$i}_num'   value='" . esc_attr( $num )   . "' style='width:80px;margin-right:10px' placeholder='22+'>";
		echo "<input type='text' name='o_nas_stat{$i}_label' value='" . esc_attr( $label ) . "' style='width:320px' placeholder='let pomáháme rodinám'>";
		echo "</td></tr>";
	}
	echo '</table></div>';
}

// ── Render: Náš tým ───────────────────────────────────────────────

function cpnrp_nas_tym_meta_cb( $post ) {
	if ( ! _cpnrp_o_nas_meta_show( 'cpnrp_nas_tym_settings' ) ) {
		echo '<p style="color:#999;font-style:italic">Aktivní pouze pro šablonu <strong>Náš tým</strong>.</p>';
		return;
	}
	wp_nonce_field( 'cpnrp_nas_tym_save', 'cpnrp_nas_tym_nonce' );
	$fields = [
		'_nas_tym_hero_desc' => [ 'Popis v hero', 'textarea' ],
	];
	_cpnrp_render_fields( $post, $fields, [
		'_nas_tym_hero_desc' => 'Náš tým tvoří zkušení sociální pracovníci, psychologové a odborníci, kteří sdílejí společné přesvědčení — každé dítě si zaslouží domov.',
	] );
	echo '<p class="description" style="margin-top:1rem">Text stránky editujte v hlavním editoru obsahu výše.</p>';
}

// ── Render: Partneři ──────────────────────────────────────────────

function cpnrp_spolupracujeme_meta_cb( $post ) {
	if ( ! _cpnrp_o_nas_meta_show( 'cpnrp_spolupracujeme_settings' ) ) {
		echo '<p style="color:#999;font-style:italic">Aktivní pouze pro šablonu <strong>S kým spolupracujeme</strong>.</p>';
		return;
	}
	wp_nonce_field( 'cpnrp_spolupracujeme_save', 'cpnrp_spolupracujeme_nonce' );

	_cpnrp_render_fields( $post, [
		'_spolupracujeme_hero_desc' => [ 'Popis v hero', 'textarea' ],
	], [
		'_spolupracujeme_hero_desc' => 'Naše práce je možná díky důvěře a podpoře desítek partnerů — od měst a krajů po nadace a soukromé firmy.',
	] );

	$group_meta_defaults = [
		1 => [ 'eyebrow' => 'Veřejná sféra',    'title' => 'Veřejní a institucionální partneři' ],
		2 => [ 'eyebrow' => 'Filantropie',       'title' => 'Nadace a fondy'                     ],
		3 => [ 'eyebrow' => 'Soukromý sektor',   'title' => 'Korporátní partneři'                ],
	];

	echo '<style>
		.spo-section{margin-top:20px;padding-top:20px;border-top:1px solid #ddd}
		.spo-section h4{margin:0 0 12px;font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#1A6080}
		.spo-section .form-table td input[type=text]{width:100%;max-width:480px}
		.spo-logo-wrap{display:flex;flex-direction:column;align-items:flex-start;gap:6px}
		.spo-logo-thumb{width:90px;height:54px;border:1px solid #ddd;border-radius:3px;background:#f9f9f9;display:flex;align-items:center;justify-content:center;overflow:hidden}
		.spo-logo-thumb img{max-width:88px;max-height:52px;object-fit:contain}
		.spo-logo-thumb .spo-no-img{font-size:11px;color:#aaa}
		#spo-rows-1 td, #spo-rows-2 td, #spo-rows-3 td{vertical-align:top;padding:8px 6px}
	</style>';

	for ( $i = 1; $i <= 3; $i++ ) {
		$eyebrow = get_post_meta( $post->ID, "_spo_group{$i}_eyebrow", true ) ?: $group_meta_defaults[ $i ]['eyebrow'];
		$title   = get_post_meta( $post->ID, "_spo_group{$i}_title",   true ) ?: $group_meta_defaults[ $i ]['title'];
		$items   = get_post_meta( $post->ID, "_spo_group{$i}_items",   true );
		if ( ! is_array( $items ) ) $items = [];

		echo "<div class='spo-section'><h4>Skupina {$i}</h4>";
		echo "<table class='form-table'>";
		echo "<tr><th>Eyebrow</th><td><input type='text' name='spo_group{$i}_eyebrow' value='" . esc_attr( $eyebrow ) . "' style='max-width:360px'></td></tr>";
		echo "<tr><th>Nadpis skupiny</th><td><input type='text' name='spo_group{$i}_title' value='" . esc_attr( $title ) . "' style='max-width:460px'></td></tr>";
		echo "</table>";

		echo "<p style='margin:10px 0 6px;font-size:13px;color:#555'>Loga partnerů — nahrajte obrázek a volitelně přidejte odkaz na web partnera.</p>";
		echo "<table style='width:100%;border-collapse:collapse'>
			<thead><tr style='border-bottom:2px solid #ddd'>
				<th style='text-align:left;padding:6px 8px;width:110px'>Logo</th>
				<th style='text-align:left;padding:6px 8px'>Název (alt)</th>
				<th style='text-align:left;padding:6px 8px'>URL webu partnera</th>
				<th style='width:46px'></th>
			</tr></thead>
			<tbody id='spo-rows-{$i}'>";

		foreach ( $items as $item ) {
			$img  = esc_url( $item['img']  ?? '' );
			$name = esc_attr( $item['name'] ?? '' );
			$url  = esc_url( $item['url']  ?? '' );
			echo "<tr class='spo-row' style='border-bottom:1px solid #eee'>";
			echo "<td><div class='spo-logo-wrap'>";
			echo   "<div class='spo-logo-thumb'>" . ( $img ? "<img src='{$img}'>" : "<span class='spo-no-img'>bez loga</span>" ) . "</div>";
			echo   "<input type='hidden' name='spo_group{$i}_img[]' class='spo-img-url' value='{$img}'>";
			echo   "<button type='button' class='button spo-pick' data-group='{$i}'>Vybrat logo</button>";
			echo "</div></td>";
			echo "<td><input type='text' name='spo_group{$i}_name[]' value='{$name}' placeholder='Název partnera' style='width:100%;padding:5px 6px'></td>";
			echo "<td><input type='text' name='spo_group{$i}_url[]'  value='{$url}'  placeholder='https://partner.cz' style='width:100%;padding:5px 6px'></td>";
			echo "<td style='text-align:center'><button type='button' class='button spo-remove' style='color:#b00'>✕</button></td>";
			echo "</tr>";
		}

		echo "</tbody></table>";
		echo "<button type='button' class='button button-secondary spo-add-row' data-group='{$i}' style='margin-top:8px'>+ Přidat partnera</button>";
		echo "</div>";
	}
	?>
	<script>
	(function ($) {
		// Image picker
		$(document).on('click', '.spo-pick', function (e) {
			e.preventDefault();
			var $btn   = $(this);
			var $row   = $btn.closest('tr');
			var frame  = wp.media({
				title: 'Vyberte logo partnera',
				button: { text: 'Vybrat tento obrázek' },
				library: { type: 'image' },
				multiple: false
			});
			frame.on('select', function () {
				var att = frame.state().get('selection').first().toJSON();
				$row.find('.spo-img-url').val(att.url);
				var $thumb = $row.find('.spo-logo-thumb');
				$thumb.html('<img src="' + att.url + '" style="max-width:88px;max-height:52px;object-fit:contain">');
			});
			frame.open();
		});

		// Add row
		$(document).on('click', '.spo-add-row', function () {
			var g = $(this).data('group');
			var row = '<tr class="spo-row" style="border-bottom:1px solid #eee">'
				+ '<td><div class="spo-logo-wrap">'
				+   '<div class="spo-logo-thumb"><span class="spo-no-img">bez loga</span></div>'
				+   '<input type="hidden" name="spo_group' + g + '_img[]" class="spo-img-url" value="">'
				+   '<button type="button" class="button spo-pick" data-group="' + g + '">Vybrat logo</button>'
				+ '</div></td>'
				+ '<td><input type="text" name="spo_group' + g + '_name[]" placeholder="Název partnera" style="width:100%;padding:5px 6px"></td>'
				+ '<td><input type="text" name="spo_group' + g + '_url[]"  placeholder="https://partner.cz" style="width:100%;padding:5px 6px"></td>'
				+ '<td style="text-align:center"><button type="button" class="button spo-remove" style="color:#b00">✕</button></td>'
				+ '</tr>';
			$('#spo-rows-' + g).append(row);
		});

		// Remove row
		$(document).on('click', '.spo-remove', function () {
			$(this).closest('.spo-row').remove();
		});
	}(jQuery));
	</script>
	<?php
}

// ── Render: Slovo ředitelky ───────────────────────────────────────

function cpnrp_slovo_reditelky_meta_cb( $post ) {
	if ( ! _cpnrp_o_nas_meta_show( 'cpnrp_slovo_reditelky_settings' ) ) {
		echo '<p style="color:#999;font-style:italic">Aktivní pouze pro šablonu <strong>Slovo ředitelky</strong>.</p>';
		return;
	}
	wp_nonce_field( 'cpnrp_slovo_reditelky_save', 'cpnrp_slovo_reditelky_nonce' );
	$fields = [
		'_subpage_hero_desc' => [ 'Popis v hero',               'textarea' ],
		'_slovo_jmeno'       => [ 'Jméno (podpis)',              'input' ],
		'_slovo_titul'       => [ 'Titul / funkce (podpis)',     'input' ],
		'_slovo_photo'       => [ 'Název souboru fotky', 'input' ],
	];
	_cpnrp_render_fields( $post, $fields, [
		'_slovo_jmeno'  => 'Mgr. Jana Rychnovská',
		'_slovo_titul'  => 'Ředitelka CPNRP',
		'_slovo_photo'  => 'rychnovska.jpeg',
	] );
	// List available team photos
	$team_dir = get_template_directory() . '/assets/images/team/';
	$photos   = array_map( 'basename', glob( $team_dir . '*.{jpg,jpeg,png}', GLOB_BRACE ) );
	if ( $photos ) {
		echo '<p class="description" style="margin-top:.5rem">Dostupné fotky v team složce: <code>' . implode( '</code>, <code>', array_map( 'esc_html', $photos ) ) . '</code></p>';
	}
	echo '<p class="description">Chcete nahrát novou fotku? Použijte <strong>Média → Nahrát</strong>, pak soubor přesuňte do složky <code>/wp-content/themes/cpnrp/assets/images/team/</code> nebo požádejte vývojáře.</p>';
	echo '<p class="description" style="margin-top:.5rem">Text dopisu se edituje ve standardním editoru obsahu stránky níže.</p>';
}

// ── Shared: field renderer ────────────────────────────────────────

function _cpnrp_render_fields( $post, array $fields, array $defaults = [] ) {
	echo '<table class="form-table">';
	foreach ( $fields as $key => [ $label, $type ] ) {
		$val = get_post_meta( $post->ID, $key, true );
		if ( ( $val === '' || $val === false ) && isset( $defaults[ $key ] ) ) $val = $defaults[ $key ];
		$id  = ltrim( $key, '_' );
		echo '<tr><th><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label></th><td>';
		if ( $type === 'textarea' ) {
			echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" rows="2" style="width:100%">' . esc_textarea( $val ) . '</textarea>';
		} else {
			echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( $val ) . '" style="width:100%">';
		}
		echo '</td></tr>';
	}
	echo '</table>';
}

// ── Save ──────────────────────────────────────────────────────────

add_action( 'save_post_page', 'cpnrp_o_nas_meta_save' );
function cpnrp_o_nas_meta_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_page', $post_id ) ) return;

	// O nás
	if ( isset( $_POST['cpnrp_o_nas_nonce'] ) && wp_verify_nonce( $_POST['cpnrp_o_nas_nonce'], 'cpnrp_o_nas_save' ) ) {
		$textarea_fields = [ 'o_nas_hero_desc' => '_o_nas_hero_desc', 'o_nas_mission' => '_o_nas_mission', 'o_nas_blockquote' => '_o_nas_blockquote' ];
		foreach ( $textarea_fields as $f => $k ) {
			update_post_meta( $post_id, $k, sanitize_textarea_field( $_POST[ $f ] ?? '' ) );
		}
		$text_fields = [
			'o_nas_s1_eyebrow'      => '_o_nas_s1_eyebrow',
			'o_nas_s1_heading'      => '_o_nas_s1_heading',
			'o_nas_value1'          => '_o_nas_value1',
			'o_nas_value2'          => '_o_nas_value2',
			'o_nas_value3'          => '_o_nas_value3',
			'o_nas_value4'          => '_o_nas_value4',
			'o_nas_s2_eyebrow'      => '_o_nas_s2_eyebrow',
			'o_nas_s2_heading'      => '_o_nas_s2_heading',
			'o_nas_blockquote_link' => '_o_nas_blockquote_link',
		];
		foreach ( $text_fields as $f => $k ) {
			update_post_meta( $post_id, $k, sanitize_text_field( $_POST[ $f ] ?? '' ) );
		}
		for ( $i = 1; $i <= 4; $i++ ) {
			update_post_meta( $post_id, "_o_nas_stat{$i}_num",   sanitize_text_field( $_POST[ "o_nas_stat{$i}_num" ]   ?? '' ) );
			update_post_meta( $post_id, "_o_nas_stat{$i}_label", sanitize_text_field( $_POST[ "o_nas_stat{$i}_label" ] ?? '' ) );
		}
	}

	// Náš tým
	if ( isset( $_POST['cpnrp_nas_tym_nonce'] ) && wp_verify_nonce( $_POST['cpnrp_nas_tym_nonce'], 'cpnrp_nas_tym_save' ) ) {
		update_post_meta( $post_id, '_nas_tym_hero_desc', sanitize_textarea_field( $_POST['nas_tym_hero_desc'] ?? '' ) );
	}

	// Partneři
	if ( isset( $_POST['cpnrp_spolupracujeme_nonce'] ) && wp_verify_nonce( $_POST['cpnrp_spolupracujeme_nonce'], 'cpnrp_spolupracujeme_save' ) ) {
		update_post_meta( $post_id, '_spolupracujeme_hero_desc', sanitize_textarea_field( $_POST['spolupracujeme_hero_desc'] ?? '' ) );
		for ( $i = 1; $i <= 3; $i++ ) {
			update_post_meta( $post_id, "_spo_group{$i}_eyebrow", sanitize_text_field( $_POST[ "spo_group{$i}_eyebrow" ] ?? '' ) );
			update_post_meta( $post_id, "_spo_group{$i}_title",   sanitize_text_field( $_POST[ "spo_group{$i}_title"   ] ?? '' ) );

			$imgs  = array_values( $_POST[ "spo_group{$i}_img"  ] ?? [] );
			$names = array_values( $_POST[ "spo_group{$i}_name" ] ?? [] );
			$urls  = array_values( $_POST[ "spo_group{$i}_url"  ] ?? [] );
			$items = [];
			foreach ( $names as $j => $name ) {
				$name = sanitize_text_field( $name );
				$img  = esc_url_raw( $imgs[ $j ] ?? '' );
				if ( $name === '' && $img === '' ) continue;
				$items[] = [
					'img'  => $img,
					'name' => $name,
					'url'  => esc_url_raw( $urls[ $j ] ?? '' ),
				];
			}
			update_post_meta( $post_id, "_spo_group{$i}_items", $items );
		}
	}

	// Slovo ředitelky
	if ( isset( $_POST['cpnrp_slovo_reditelky_nonce'] ) && wp_verify_nonce( $_POST['cpnrp_slovo_reditelky_nonce'], 'cpnrp_slovo_reditelky_save' ) ) {
		update_post_meta( $post_id, '_subpage_hero_desc', sanitize_textarea_field( $_POST['subpage_hero_desc'] ?? '' ) );
		update_post_meta( $post_id, '_slovo_jmeno',       sanitize_text_field( $_POST['slovo_jmeno'] ?? '' ) );
		update_post_meta( $post_id, '_slovo_titul',       sanitize_text_field( $_POST['slovo_titul'] ?? '' ) );
		update_post_meta( $post_id, '_slovo_photo',       sanitize_text_field( $_POST['slovo_photo'] ?? '' ) );
	}
}
