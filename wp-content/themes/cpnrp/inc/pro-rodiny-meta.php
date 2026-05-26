<?php
/**
 * Meta boxes for Pro rodiny page templates:
 *   page-pro-rodiny.php       → _pro_rodiny_*
 *   page-jsem-pestoun.php     → _jsem_pestoun_*
 *   page-adopce-vs-pestounstvi.php → _avp_hero_desc
 *   page-podstranka.php (extended) → _subpage_hero_desc
 */

add_action( 'add_meta_boxes', function () {
	global $post;
	if ( ! $post ) return;
	$tpl = get_post_meta( $post->ID, '_wp_page_template', true );

	$map = [
		'page-pro-rodiny.php'            => [ 'cpnrp_pro_rodiny_settings',  'Nastavení stránky Pro rodiny',            'cpnrp_pro_rodiny_meta_cb'  ],
		'page-jsem-pestoun.php'          => [ 'cpnrp_jsem_pestoun_settings','Nastavení stránky Jsem pěstoun',          'cpnrp_jsem_pestoun_meta_cb'],
		'page-adopce-vs-pestounstvi.php' => [ 'cpnrp_avp_settings',         'Adopce vs. pěstounství — hero popis',    'cpnrp_avp_meta_cb'         ],
		'page-podstranka.php'            => [ 'cpnrp_subpage_hero_desc',     'Popis v hero (podstránka)',               'cpnrp_subpage_hero_desc_cb'],
	];

	if ( isset( $map[ $tpl ] ) ) {
		[ $id, $title, $cb ] = $map[ $tpl ];
		add_meta_box( $id, $title, $cb, 'page', 'normal', 'high' );
	}
} );

// ── Render: visibility helpers ────────────────────────────────────

function _cpnrp_meta_show( $meta_box_id ) {
	global $post;
	if ( ! $post ) return;
	$tpl = get_post_meta( $post->ID, '_wp_page_template', true );
	$map = [
		'cpnrp_pro_rodiny_settings'  => 'page-pro-rodiny.php',
		'cpnrp_jsem_pestoun_settings' => 'page-jsem-pestoun.php',
		'cpnrp_avp_settings'         => 'page-adopce-vs-pestounstvi.php',
		'cpnrp_subpage_hero_desc'    => 'page-podstranka.php',
	];
	return isset( $map[ $meta_box_id ] ) && $tpl === $map[ $meta_box_id ];
}

// ── Render: Pro rodiny ────────────────────────────────────────────

function cpnrp_pro_rodiny_meta_cb( $post ) {
	if ( ! _cpnrp_meta_show( 'cpnrp_pro_rodiny_settings' ) ) {
		echo '<p style="color:#999;font-style:italic">Toto pole je aktivní pouze pro šablonu <strong>Pro rodiny</strong>.</p>';
		return;
	}
	wp_nonce_field( 'cpnrp_pro_rodiny_save', 'cpnrp_pro_rodiny_nonce' );

	$hero_desc  = get_post_meta( $post->ID, '_pro_rodiny_hero_desc', true )
		?: 'Ať už jste pěstoun, uvažujete o pěstounství, nebo chcete adoptovat — jsme tu pro vás.';
	$blockquote = get_post_meta( $post->ID, '_pro_rodiny_blockquote', true )
		?: '„Teprve se rozhodujete? Napište nám — rádi odpovíme na všechny otázky bez závazku."';
	$bq_link    = get_post_meta( $post->ID, '_pro_rodiny_blockquote_link', true )
		?: 'Domluvit konzultaci';
	?>
	<table class="form-table">
		<tr>
			<th><label for="pro_rodiny_hero_desc">Popis v hero</label></th>
			<td><textarea id="pro_rodiny_hero_desc" name="pro_rodiny_hero_desc" rows="2" style="width:100%"><?php echo esc_textarea( $hero_desc ); ?></textarea></td>
		</tr>
		<tr>
			<th><label for="pro_rodiny_blockquote">Citát / výzva (dole)</label></th>
			<td><textarea id="pro_rodiny_blockquote" name="pro_rodiny_blockquote" rows="2" style="width:100%"><?php echo esc_textarea( $blockquote ); ?></textarea></td>
		</tr>
		<tr>
			<th><label for="pro_rodiny_blockquote_link">Text odkazu citátu</label></th>
			<td><input type="text" id="pro_rodiny_blockquote_link" name="pro_rodiny_blockquote_link" value="<?php echo esc_attr( $bq_link ); ?>" style="width:100%" /></td>
		</tr>
	</table>
	<?php
}

// ── Render: Jsem pěstoun ──────────────────────────────────────────

function cpnrp_jsem_pestoun_meta_cb( $post ) {
	if ( ! _cpnrp_meta_show( 'cpnrp_jsem_pestoun_settings' ) ) {
		echo '<p style="color:#999;font-style:italic">Toto pole je aktivní pouze pro šablonu <strong>Jsem pěstoun</strong>.</p>';
		return;
	}
	wp_nonce_field( 'cpnrp_jsem_pestoun_save', 'cpnrp_jsem_pestoun_nonce' );

	$fields = [
		'_jsem_pestoun_hero_desc'       => [ 'Popis v hero',          'textarea' ],
		'_jsem_pestoun_eyebrow'         => [ 'Eyebrow text',           'input' ],
		'_jsem_pestoun_section_heading' => [ 'Nadpis sekce (H2)',      'input' ],
		'_jsem_pestoun_cta_heading'     => [ 'CTA nadpis',             'input' ],
		'_jsem_pestoun_cta_desc'        => [ 'CTA popis',              'textarea' ],
	];
	$defaults = [
		'_jsem_pestoun_hero_desc'       => 'Podporujeme vás na každém kroku vaší pěstounské cesty. Nabízíme komplexní služby pro celou rodinu.',
		'_jsem_pestoun_eyebrow'         => 'Co pro vás máme',
		'_jsem_pestoun_section_heading' => 'Naše služby pro pěstouny',
		'_jsem_pestoun_cta_heading'     => 'Máte zájem o naše služby?',
		'_jsem_pestoun_cta_desc'        => 'Kontaktujte nás a domluvíme se na prvním setkání.',
	];
	echo '<table class="form-table">';
	foreach ( $fields as $key => [ $label, $type ] ) {
		$val = get_post_meta( $post->ID, $key, true );
		if ( ( $val === '' || $val === false ) && isset( $defaults[ $key ] ) ) $val = $defaults[ $key ];
		$id  = ltrim( $key, '_' );
		echo '<tr><th><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label></th><td>';
		if ( $type === 'textarea' ) {
			echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" rows="2" style="width:100%">' . esc_textarea( $val ) . '</textarea>';
		} else {
			echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $id ) . '" value="' . esc_attr( $val ) . '" style="width:100%" />';
		}
		echo '</td></tr>';
	}
	echo '</table>';
}

// ── Render: Adopce vs. pěstounství ───────────────────────────────

function cpnrp_avp_meta_cb( $post ) {
	if ( ! _cpnrp_meta_show( 'cpnrp_avp_settings' ) ) {
		echo '<p style="color:#999;font-style:italic">Toto pole je aktivní pouze pro šablonu <strong>Adopce vs. pěstounství</strong>.</p>';
		return;
	}
	wp_nonce_field( 'cpnrp_avp_save', 'cpnrp_avp_nonce' );
	$val = get_post_meta( $post->ID, '_avp_hero_desc', true )
		?: 'Obě formy mají společný cíl — poskytnout dítěti milující rodinu. Liší se právním rámcem, trvalostí a povinnostmi.';
	?>
	<table class="form-table">
		<tr>
			<th><label for="avp_hero_desc">Popis v hero</label></th>
			<td><textarea id="avp_hero_desc" name="avp_hero_desc" rows="2" style="width:100%"><?php echo esc_textarea( $val ); ?></textarea></td>
		</tr>
	</table>
	<?php
}

// ── Render: Subpage hero description ─────────────────────────────

function cpnrp_subpage_hero_desc_cb( $post ) {
	if ( ! _cpnrp_meta_show( 'cpnrp_subpage_hero_desc' ) ) {
		echo '<p style="color:#999;font-style:italic">Toto pole je aktivní pouze pro šablonu <strong>Podstránka</strong>.</p>';
		return;
	}
	wp_nonce_field( 'cpnrp_subpage_hero_save', 'cpnrp_subpage_hero_nonce' );
	$val = get_post_meta( $post->ID, '_subpage_hero_desc', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="subpage_hero_desc">Popis pod nadpisem v hero</label></th>
			<td><textarea id="subpage_hero_desc" name="subpage_hero_desc" rows="2" style="width:100%"><?php echo esc_textarea( $val ); ?></textarea>
			<p class="description">Nepovinné — pokud je prázdné, popis se nezobrazí.</p></td>
		</tr>
	</table>
	<?php
}

// ── Save ─────────────────────────────────────────────────────────

add_action( 'save_post_page', 'cpnrp_pro_rodiny_meta_save' );
function cpnrp_pro_rodiny_meta_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_page', $post_id ) ) return;

	// Pro rodiny
	if ( isset( $_POST['cpnrp_pro_rodiny_nonce'] ) && wp_verify_nonce( $_POST['cpnrp_pro_rodiny_nonce'], 'cpnrp_pro_rodiny_save' ) ) {
		foreach ( [ 'pro_rodiny_hero_desc' => '_pro_rodiny_hero_desc', 'pro_rodiny_blockquote' => '_pro_rodiny_blockquote', 'pro_rodiny_blockquote_link' => '_pro_rodiny_blockquote_link' ] as $field => $meta_key ) {
			update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $_POST[ $field ] ?? '' ) );
		}
	}

	// Jsem pěstoun
	if ( isset( $_POST['cpnrp_jsem_pestoun_nonce'] ) && wp_verify_nonce( $_POST['cpnrp_jsem_pestoun_nonce'], 'cpnrp_jsem_pestoun_save' ) ) {
		$jsem_fields = [
			'jsem_pestoun_hero_desc'       => '_jsem_pestoun_hero_desc',
			'jsem_pestoun_eyebrow'         => '_jsem_pestoun_eyebrow',
			'jsem_pestoun_section_heading' => '_jsem_pestoun_section_heading',
			'jsem_pestoun_cta_heading'     => '_jsem_pestoun_cta_heading',
			'jsem_pestoun_cta_desc'        => '_jsem_pestoun_cta_desc',
		];
		foreach ( $jsem_fields as $field => $meta_key ) {
			update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $_POST[ $field ] ?? '' ) );
		}
	}

	// Adopce vs. pěstounství
	if ( isset( $_POST['cpnrp_avp_nonce'] ) && wp_verify_nonce( $_POST['cpnrp_avp_nonce'], 'cpnrp_avp_save' ) ) {
		update_post_meta( $post_id, '_avp_hero_desc', sanitize_textarea_field( $_POST['avp_hero_desc'] ?? '' ) );
	}

	// Subpage hero desc
	if ( isset( $_POST['cpnrp_subpage_hero_nonce'] ) && wp_verify_nonce( $_POST['cpnrp_subpage_hero_nonce'], 'cpnrp_subpage_hero_save' ) ) {
		update_post_meta( $post_id, '_subpage_hero_desc', sanitize_textarea_field( $_POST['subpage_hero_desc'] ?? '' ) );
	}
}
