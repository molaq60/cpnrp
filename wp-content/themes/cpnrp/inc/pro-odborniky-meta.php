<?php
/**
 * Meta boxes for Pro odborníky page template.
 */

add_action( 'add_meta_boxes', function () {
	global $post;
	if ( ! $post ) return;
	$tpl  = get_post_meta( $post->ID, '_wp_page_template', true );
	$slug = $post->post_name;
	if ( $tpl !== 'page-pro-odborniky.php' && $slug !== 'pro-odborniky' ) return;

	add_meta_box(
		'cpnrp_odb_settings',
		__( 'Pro odborníky — nastavení', 'cpnrp' ),
		'cpnrp_odb_meta_box_cb',
		'page',
		'normal',
		'high'
	);
} );

// ── Meta box HTML ────────────────────────────────────────────────
function cpnrp_odb_meta_box_cb( $post ) {
	wp_nonce_field( 'cpnrp_odb_save', 'cpnrp_odb_nonce' );

	$keys = [
		'_odb_hero_desc', '_odb_eyebrow', '_odb_section_heading',
		'_odb_service1_title', '_odb_service1_desc',
		'_odb_service2_title', '_odb_service2_desc',
		'_odb_service3_title', '_odb_service3_desc',
		'_odb_service4_title', '_odb_service4_desc',
		'_odb_legal_eyebrow', '_odb_legal_text1', '_odb_legal_text2',
		'_odb_cta_quote', '_odb_cta_email', '_odb_cta_phone',
	];
	$m = [];
	foreach ( $keys as $k ) {
		$m[ $k ] = get_post_meta( $post->ID, $k, true );
	}

	$defaults = [
		'_odb_hero_desc'       => 'Nabízíme služby a spolupráci pro orgány sociálně-právní ochrany dětí (OSPOD), soudy, školy a další odborníky v oblasti péče o ohrožené děti.',
		'_odb_eyebrow'         => 'Spolupráce s OSPOD',
		'_odb_section_heading' => 'Co nabízíme odborné veřejnosti',
		'_odb_service1_title'  => 'Facilitace případových konferencí',
		'_odb_service1_desc'   => 'Odborná facilitace případových konferencí pro řešení situace ohrožených dětí. Facilitátor vede strukturovaný dialog mezi všemi zúčastněnými stranami — rodinou, OSPOD, školou a dalšími odborníky.',
		'_odb_service2_title'  => 'Vzdělávání pro OSPOD',
		'_odb_service2_desc'   => 'Odborná školení a semináře pro pracovníky OSPOD zaměřené na aktuální témata náhradní rodinné péče, komunikaci s pěstounskými rodinami a legislativní změny.',
		'_odb_service3_title'  => 'Konzultace a metodická podpora',
		'_odb_service3_desc'   => 'Konzultace pro sociální pracovníky při řešení konkrétních případů, metodická podpora a sdílení dobré praxe v oblasti NRP.',
		'_odb_service4_title'  => 'Sociálně aktivizační služby',
		'_odb_service4_desc'   => 'Sociálně aktivizační služby pro rodiny s dětmi v péči jiné osoby, které se ocitly v obtížné nebo krizové sociální situaci.',
		'_odb_legal_eyebrow'   => 'Právní rámec',
		'_odb_legal_text1'     => 'Činnost CPNRP vychází z pověření k výkonu sociálně-právní ochrany dětí dle zákona č. 359/1999 Sb. Organizace má pověření k uzavírání dohod o výkonu pěstounské péče a k poskytování odborného poradenství v oblasti NRP.',
		'_odb_legal_text2'     => 'Spolupracujeme s krajskými úřady, obecními úřady obcí s rozšířenou působností, soudy a dalšími institucemi v systému péče o ohrožené děti v Ústeckém kraji.',
		'_odb_cta_quote'       => '„Máte zájem o spolupráci? Ozvěte se — domluvíme termín, který se hodí oběma stranám."',
		'_odb_cta_email'       => 'info@cpnrp.cz',
		'_odb_cta_phone'       => '+420 731 557 681',
	];
	foreach ( $defaults as $k => $v ) {
		if ( $m[ $k ] === '' || $m[ $k ] === false ) $m[ $k ] = $v;
	}

	$row = function( $label, $key, $type = 'text', $placeholder = '' ) use ( $m ) {
		$id  = esc_attr( ltrim( $key, '_' ) );
		$val = esc_attr( $m[ $key ] );
		echo "<tr><th><label for=\"{$id}\">{$label}</label></th>";
		echo "<td><input type=\"{$type}\" id=\"{$id}\" name=\"{$id}\" value=\"{$val}\" placeholder=\"" . esc_attr( $placeholder ) . "\"></td></tr>";
	};

	$textarea = function( $label, $key, $rows = 2, $placeholder = '' ) use ( $m ) {
		$id  = esc_attr( ltrim( $key, '_' ) );
		$val = esc_textarea( $m[ $key ] );
		echo "<tr><th><label for=\"{$id}\">{$label}</label></th>";
		echo "<td><textarea id=\"{$id}\" name=\"{$id}\" rows=\"{$rows}\" placeholder=\"" . esc_attr( $placeholder ) . "\">{$val}</textarea></td></tr>";
	};
	?>
	<style>
		#cpnrp_odb_settings table.form-table th { width: 200px; vertical-align: top; padding-top: 10px; }
		#cpnrp_odb_settings input[type=text],
		#cpnrp_odb_settings input[type=email],
		#cpnrp_odb_settings input[type=tel],
		#cpnrp_odb_settings textarea { width: 100%; max-width: 620px; }
		#cpnrp_odb_settings .odb-section { margin-top: 24px; padding-top: 20px; border-top: 1px solid #ddd; }
		#cpnrp_odb_settings .odb-section h4 { margin: 0 0 12px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #1A6080; }
	</style>

	<!-- Hero -->
	<div class="odb-section" style="margin-top:0;padding-top:0;border-top:none;">
		<h4>Hero sekce</h4>
		<table class="form-table">
			<?php $textarea( 'Popis pod nadpisem', '_odb_hero_desc', 2 ); ?>
		</table>
	</div>

	<!-- Services -->
	<div class="odb-section">
		<h4>Sekce služeb</h4>
		<table class="form-table">
			<?php
			$row( 'Eyebrow text', '_odb_eyebrow', 'text', 'Spolupráce s OSPOD' );
			$row( 'Nadpis sekce (H2)', '_odb_section_heading', 'text', 'Co nabízíme odborné veřejnosti' );
			?>
		</table>
	</div>

	<!-- Individual services -->
	<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
	<div class="odb-section">
		<h4>Služba <?php echo $i; ?></h4>
		<table class="form-table">
			<?php
			$row( 'Nadpis', "_odb_service{$i}_title" );
			$textarea( 'Popis', "_odb_service{$i}_desc", 3 );
			?>
		</table>
	</div>
	<?php endfor; ?>

	<!-- Legal -->
	<div class="odb-section">
		<h4>Právní rámec</h4>
		<table class="form-table">
			<?php
			$row( 'Eyebrow text', '_odb_legal_eyebrow', 'text', 'Právní rámec' );
			$textarea( 'Text 1 (HTML: <strong>)</strong>', '_odb_legal_text1', 3 );
			$textarea( 'Text 2', '_odb_legal_text2', 3 );
			?>
		</table>
	</div>

	<!-- CTA -->
	<div class="odb-section">
		<h4>Kontaktní výzva</h4>
		<table class="form-table">
			<?php
			$textarea( 'Citát / výzva', '_odb_cta_quote', 2 );
			$row( 'E-mail', '_odb_cta_email', 'email', 'info@cpnrp.cz' );
			$row( 'Telefon', '_odb_cta_phone', 'tel', '+420 731 557 681' );
			?>
		</table>
	</div>
	<?php
}

// ── Save ─────────────────────────────────────────────────────────
add_action( 'save_post_page', function ( $post_id ) {
	if ( ! isset( $_POST['cpnrp_odb_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['cpnrp_odb_nonce'], 'cpnrp_odb_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_page', $post_id ) ) return;

	$text_fields = [
		'_odb_hero_desc'      => 'sanitize_textarea_field',
		'_odb_eyebrow'        => 'sanitize_text_field',
		'_odb_section_heading'=> 'sanitize_text_field',
		'_odb_service1_title' => 'sanitize_text_field',
		'_odb_service1_desc'  => 'sanitize_textarea_field',
		'_odb_service2_title' => 'sanitize_text_field',
		'_odb_service2_desc'  => 'sanitize_textarea_field',
		'_odb_service3_title' => 'sanitize_text_field',
		'_odb_service3_desc'  => 'sanitize_textarea_field',
		'_odb_service4_title' => 'sanitize_text_field',
		'_odb_service4_desc'  => 'sanitize_textarea_field',
		'_odb_legal_eyebrow'  => 'sanitize_text_field',
		'_odb_legal_text1'    => 'sanitize_textarea_field',
		'_odb_legal_text2'    => 'sanitize_textarea_field',
		'_odb_cta_quote'      => 'sanitize_textarea_field',
		'_odb_cta_email'      => 'sanitize_email',
		'_odb_cta_phone'      => 'sanitize_text_field',
	];

	foreach ( $text_fields as $meta_key => $sanitizer ) {
		$post_key = ltrim( $meta_key, '_' );
		if ( isset( $_POST[ $post_key ] ) ) {
			update_post_meta( $post_id, $meta_key, $sanitizer( $_POST[ $post_key ] ) );
		}
	}
} );
