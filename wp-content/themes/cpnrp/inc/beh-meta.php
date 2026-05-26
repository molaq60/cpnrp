<?php
/**
 * Běh pro rodinu — meta boxes for the page template.
 */

// ── Register meta box — pouze na stránce Běh pro rodinu ─────────
add_action( 'add_meta_boxes', function () {
	global $post;
	if ( ! $post ) return;

	$tpl  = get_post_meta( $post->ID, '_wp_page_template', true );
	$slug = $post->post_name;

	if ( $tpl !== 'page-beh-pro-rodinu.php' && $slug !== 'beh-pro-rodinu' ) return;

	add_meta_box(
		'cpnrp_beh_settings',
		__( 'Běh pro rodinu — nastavení', 'cpnrp' ),
		'cpnrp_beh_meta_box_cb',
		'page',
		'normal',
		'high'
	);
} );

// ── Meta box HTML ────────────────────────────────────────────────
function cpnrp_beh_meta_box_cb( $post ) {
	wp_nonce_field( 'cpnrp_beh_save', 'cpnrp_beh_nonce' );

	// Read all stored meta
	$m = [];
	$keys = [
		'_beh_edition', '_beh_lead', '_beh_kdy', '_beh_kde', '_beh_pro_koho',
		'_beh_registrace_url', '_beh_tym_email', '_beh_partner_email',
		'_beh_stat1_num', '_beh_stat1_label', '_beh_stat1_note',
		'_beh_stat2_num', '_beh_stat2_label', '_beh_stat2_note',
		'_beh_stat3_num', '_beh_stat3_label', '_beh_stat3_note',
		'_beh_info1_title', '_beh_info1_text',
		'_beh_info2_title', '_beh_info2_text',
		'_beh_info3_title', '_beh_info3_text',
		'_beh_info4_title', '_beh_info4_text',
		'_beh_sponsors',
		'_beh_cta_title', '_beh_cta_text',
	];
	foreach ( $keys as $k ) {
		$m[ $k ] = get_post_meta( $post->ID, $k, true );
	}

	// Defaults
	$defaults = [
		'_beh_edition'         => '9. ročník · 2026',
		'_beh_lead'            => 'Charitativní běh, jehož výtěžek putuje na doučování dětí v náhradní rodinné péči. Přijďte si zaběhnout, projít s rodinou — nebo nás jen podpořit.',
		'_beh_kdy'             => 'září 2026',
		'_beh_kde'             => 'Střelecký ostrov, Litoměřice',
		'_beh_pro_koho'        => 'Děti · rodiny · běžci · chodci',
		'_beh_registrace_url'  => 'https://irontime.cz/prihlaska3200',
		'_beh_tym_email'       => 'info@cpnrp.cz',
		'_beh_partner_email'   => 'info@cpnrp.cz',
		'_beh_stat1_num'       => '8',
		'_beh_stat1_label'     => 'úspěšných ročníků',
		'_beh_stat1_note'      => '2018 – 2025',
		'_beh_stat2_num'       => 'desítky',
		'_beh_stat2_label'     => 'dětí podpořených doučováním',
		'_beh_stat2_note'      => 'jen v roce 2024',
		'_beh_stat3_num'       => '3 generace',
		'_beh_stat3_label'     => 'na jedné startovní čáře',
		'_beh_stat3_note'      => 'děti, rodiče i prarodiče',
		'_beh_info1_title'     => 'Registrace a prezence',
		'_beh_info1_text'      => 'Registrace na místě od 8:30. Ukončení registrace 30 minut před startem vaší kategorie. Online přihlášku najdete na webu organizátora.',
		'_beh_info2_title'     => 'Místo a parkování',
		'_beh_info2_text'      => 'Střelecký ostrov, Litoměřice. Parkování na vyhrazeném parkovišti. Trasa vede po asfaltové cyklostezce.',
		'_beh_info3_title'     => 'Kam jde výtěžek',
		'_beh_info3_text'      => 'Výtěžek akce putuje na podporu doučování dětí v náhradní rodinné péči v domácím prostředí. V roce 2024 jsme díky běhu zajistili doučování pro desítky dětí v Ústeckém kraji.',
		'_beh_info4_title'     => 'Pořadatelé',
		'_beh_info4_text'      => 'CPNRP, o.p.s. a Rozběháme Česko, z.ú. (za Rozběháme Litoměřicko — Kateřina Salácová, tel. 737 988 474). Každý závodí na vlastní nebezpečí. Občerstvení zajištěno (voda, ovoce).',
		'_beh_sponsors'        => "Město Litoměřice|mesto-litomerice.jpg\nHolcim|holcim.jpg\nCekro|cekro.png\nAmedis|amedis.png\nNadace J&T|nadace-jt.png\nMondi|mondi.jpg\nMagna Exteriors|magna-exteriors.png\nZdravé město Litoměřice|zdrave-mesto-litomerice.jpg",
		'_beh_cta_title'       => 'Připojíte se k 9. ročníku?',
		'_beh_cta_text'        => 'Každý kilometr promění v hodinu doučování pro dítě v náhradní péči.',
	];
	foreach ( $defaults as $k => $v ) {
		if ( $m[ $k ] === '' || $m[ $k ] === false ) $m[ $k ] = $v;
	}

	// Helper: text input row
	$row = function( $label, $key, $type = 'text', $placeholder = '' ) use ( $m ) {
		$id  = esc_attr( ltrim( $key, '_' ) );
		$val = esc_attr( $m[ $key ] );
		echo "<tr><th><label for=\"{$id}\">{$label}</label></th>";
		echo "<td><input type=\"{$type}\" id=\"{$id}\" name=\"{$id}\" value=\"{$val}\" placeholder=\"" . esc_attr( $placeholder ) . "\"></td></tr>";
	};

	// Helper: textarea row
	$textarea = function( $label, $key, $rows = 3, $placeholder = '' ) use ( $m ) {
		$id  = esc_attr( ltrim( $key, '_' ) );
		$val = esc_textarea( $m[ $key ] );
		echo "<tr><th><label for=\"{$id}\">{$label}</label></th>";
		echo "<td><textarea id=\"{$id}\" name=\"{$id}\" rows=\"{$rows}\" placeholder=\"" . esc_attr( $placeholder ) . "\">{$val}</textarea></td></tr>";
	};
	?>
	<style>
		#cpnrp_beh_settings table.form-table th { width: 220px; vertical-align: top; padding-top: 10px; }
		#cpnrp_beh_settings input[type=text],
		#cpnrp_beh_settings input[type=url],
		#cpnrp_beh_settings input[type=email],
		#cpnrp_beh_settings textarea { width: 100%; max-width: 620px; }
		#cpnrp_beh_settings .beh-section { margin-top: 24px; padding-top: 20px; border-top: 1px solid #ddd; }
		#cpnrp_beh_settings .beh-section h4 { margin: 0 0 12px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #1A6080; }
		#cpnrp_beh_settings .description { color: #666; font-style: italic; margin-top: 5px; font-size: 12px; }
	</style>

	<!-- ── Hero ── -->
	<div class="beh-section" style="margin-top:0;padding-top:0;border-top:none;">
		<h4>Hero sekce</h4>
		<p class="description" style="margin-bottom:12px;">Fotografie na pozadí = <strong>Náhledový obrázek stránky</strong> (nastavte vpravo). Nadpis stránky = velký text héro.</p>
		<table class="form-table">
			<?php
			$row( 'Ročník / rok (badge)', '_beh_edition', 'text', '9. ročník · 2026' );
			$textarea( 'Perex (lead text)', '_beh_lead', 3 );
			$row( 'Kdy', '_beh_kdy', 'text', 'září 2026' );
			$row( 'Kde', '_beh_kde', 'text', 'Střelecký ostrov, Litoměřice' );
			$row( 'Pro koho', '_beh_pro_koho', 'text', 'Děti · rodiny · běžci · chodci' );
			$row( 'URL přihlášky', '_beh_registrace_url', 'url', 'https://irontime.cz/…' );
			?>
		</table>
	</div>

	<!-- ── Stats ── -->
	<div class="beh-section">
		<h4>Co jsme dokázali — 3 čísla</h4>
		<table class="form-table">
			<?php
			for ( $i = 1; $i <= 3; $i++ ) {
				$row( "Číslo {$i}", "_beh_stat{$i}_num", 'text', $i === 1 ? '8' : ( $i === 2 ? 'desítky' : '3 generace' ) );
				$row( "Popis {$i}", "_beh_stat{$i}_label" );
				$row( "Poznámka {$i}", "_beh_stat{$i}_note" );
				if ( $i < 3 ) echo '<tr><td colspan="2"><hr style="margin:4px 0;border:none;border-top:1px solid #eee"></td></tr>';
			}
			?>
		</table>
	</div>

	<!-- ── Cards links ── -->
	<div class="beh-section">
		<h4>Karty „Tři způsoby" — kontakty</h4>
		<table class="form-table">
			<?php
			$row( 'URL přihlášky (karta 1)', '_beh_registrace_url', 'url' );
			$row( 'E-mail firemní tým (karta 2)', '_beh_tym_email', 'email', 'info@cpnrp.cz' );
			$row( 'E-mail partnerství (karta 3)', '_beh_partner_email', 'email', 'info@cpnrp.cz' );
			?>
		</table>
	</div>

	<!-- ── Practical info ── -->
	<div class="beh-section">
		<h4>Praktické informace (4 bloky)</h4>
		<table class="form-table">
			<?php
			for ( $i = 1; $i <= 4; $i++ ) {
				$row( "Nadpis bloku {$i}", "_beh_info{$i}_title" );
				$textarea( "Text bloku {$i}", "_beh_info{$i}_text", 3 );
				if ( $i < 4 ) echo '<tr><td colspan="2"><hr style="margin:4px 0;border:none;border-top:1px solid #eee"></td></tr>';
			}
			?>
		</table>
	</div>

	<!-- ── Sponsors ── -->
	<div class="beh-section">
		<h4>Partneři akce — loga</h4>
		<p class="description" style="margin-bottom:12px;">
			Každý řádek: <code>Název partnera|soubor-loga.jpg</code><br>
			Soubory logotypů se nahrávají do <strong>Média → Nahrát</strong> a pak sem zadejte přesný název souboru (hledá se ve složce <code>/wp-content/themes/cpnrp/assets/images/partners/</code>).
		</p>
		<table class="form-table">
			<?php $textarea( 'Partneři (jeden řádek = jeden partner)', '_beh_sponsors', 10, "Město Litoměřice|mesto-litomerice.jpg\nHolcim|holcim.jpg" ); ?>
		</table>
	</div>

	<!-- ── Final CTA ── -->
	<div class="beh-section">
		<h4>Závěrečná CTA sekce</h4>
		<table class="form-table">
			<?php
			$row( 'Nadpis CTA', '_beh_cta_title', 'text', 'Připojíte se k 9. ročníku?' );
			$textarea( 'Text CTA', '_beh_cta_text', 2 );
			?>
		</table>
	</div>

	<?php
}

// ── Save ─────────────────────────────────────────────────────────
add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['cpnrp_beh_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['cpnrp_beh_nonce'], 'cpnrp_beh_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$text_fields = [
		'_beh_edition'        => 'sanitize_text_field',
		'_beh_lead'           => 'sanitize_textarea_field',
		'_beh_kdy'            => 'sanitize_text_field',
		'_beh_kde'            => 'sanitize_text_field',
		'_beh_pro_koho'       => 'sanitize_text_field',
		'_beh_registrace_url' => 'esc_url_raw',
		'_beh_tym_email'      => 'sanitize_email',
		'_beh_partner_email'  => 'sanitize_email',
		'_beh_stat1_num'      => 'sanitize_text_field',
		'_beh_stat1_label'    => 'sanitize_text_field',
		'_beh_stat1_note'     => 'sanitize_text_field',
		'_beh_stat2_num'      => 'sanitize_text_field',
		'_beh_stat2_label'    => 'sanitize_text_field',
		'_beh_stat2_note'     => 'sanitize_text_field',
		'_beh_stat3_num'      => 'sanitize_text_field',
		'_beh_stat3_label'    => 'sanitize_text_field',
		'_beh_stat3_note'     => 'sanitize_text_field',
		'_beh_info1_title'    => 'sanitize_text_field',
		'_beh_info1_text'     => 'sanitize_textarea_field',
		'_beh_info2_title'    => 'sanitize_text_field',
		'_beh_info2_text'     => 'sanitize_textarea_field',
		'_beh_info3_title'    => 'sanitize_text_field',
		'_beh_info3_text'     => 'sanitize_textarea_field',
		'_beh_info4_title'    => 'sanitize_text_field',
		'_beh_info4_text'     => 'sanitize_textarea_field',
		'_beh_sponsors'       => 'sanitize_textarea_field',
		'_beh_cta_title'      => 'sanitize_text_field',
		'_beh_cta_text'       => 'sanitize_textarea_field',
	];

	foreach ( $text_fields as $meta_key => $sanitizer ) {
		$post_key = ltrim( $meta_key, '_' );
		if ( isset( $_POST[ $post_key ] ) ) {
			update_post_meta( $post_id, $meta_key, $sanitizer( $_POST[ $post_key ] ) );
		}
	}
} );
