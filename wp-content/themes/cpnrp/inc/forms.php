<?php
/**
 * Gravity Forms — programmatic setup of the contact form.
 */

// ── Disable default GF styles site-wide (we style it ourselves) ─
add_filter( 'gform_disable_css', '__return_true' );

// ── Inject icon + icon classes dynamically on render ────────────
add_filter( 'gform_pre_render', 'cpnrp_gf_pre_render' );
function cpnrp_gf_pre_render( $form ) {
	$form_id = (int) get_option( 'cpnrp_kontakt_form_id' );
	if ( ! $form_id || (int) $form['id'] !== $form_id ) return $form;

	$icon_map = [
		3 => 'gf-icon-user',
		4 => 'gf-icon-email',
		5 => 'gf-icon-phone',
	];

	foreach ( $form['fields'] as $field ) {
		if ( isset( $icon_map[ $field->id ] ) ) {
			$field->cssClass = trim( $field->cssClass . ' ' . $icon_map[ $field->id ] );
		}
	}

	return $form;
}

// ── Inject clickable link into GDPR checkbox label ──────────────
add_filter( 'gform_field_content', 'cpnrp_gf_gdpr_label_link', 10, 5 );
function cpnrp_gf_gdpr_label_link( $content, $field, $value, $entry_id, $form_id ) {
	$our_id = (int) get_option( 'cpnrp_kontakt_form_id' );
	if ( ! $our_id || (int) $form_id !== $our_id || $field->id !== 8 ) return $content;

	$content = str_replace(
		'zpracováním osobních údajů',
		'<a href="' . esc_url( home_url( '/ochrana-udaju/' ) ) . '" target="_blank" rel="noopener">zpracováním osobních údajů</a>',
		$content
	);

	return $content;
}

// ── Auto-create the contact form on first admin visit ───────────
add_action( 'admin_init', 'cpnrp_create_kontakt_gf_form' );
function cpnrp_create_kontakt_gf_form() {
	if ( ! class_exists( 'GFAPI' ) ) return;
	if ( get_option( 'cpnrp_kontakt_form_id' ) ) return;

	$form = [
		'title'          => 'Kontaktní formulář',
		'description'    => '',
		'labelPlacement' => 'top_label',
		'enableHoneypot' => true,
		'fields'         => [

			// Row 1: Důvod + Region (half-half)
			[
				'type'       => 'select',
				'id'         => 1,
				'label'      => 'Důvod kontaktu',
				'isRequired' => true,
				'cssClass'   => 'gf-half gf-half-left',
				'choices'    => [
					[ 'text' => 'Vyberte',                           'value' => '' ],
					[ 'text' => 'Mám zájem o adopci',                'value' => 'adopce' ],
					[ 'text' => 'Mám zájem o pěstounství',           'value' => 'pestounstvi' ],
					[ 'text' => 'Jsem pěstoun a potřebuji poradit', 'value' => 'podpora-pestouna' ],
					[ 'text' => 'Spolupráce — OSPOD / odborníci',    'value' => 'ospod' ],
					[ 'text' => 'Chci podpořit organizaci',          'value' => 'podpora-org' ],
					[ 'text' => 'Jiné',                              'value' => 'jine' ],
				],
			],
			[
				'type'       => 'select',
				'id'         => 2,
				'label'      => 'Váš region',
				'isRequired' => true,
				'cssClass'   => 'gf-half gf-half-right',
				'choices'    => [
					[ 'text' => 'Vyberte',                 'value' => '' ],
					[ 'text' => 'Litoměřicko',             'value' => 'litomericko' ],
					[ 'text' => 'Ústí nad Labem a okolí',  'value' => 'ustecko' ],
					[ 'text' => 'Děčínsko / Rumburk',      'value' => 'decin-rumburk' ],
					[ 'text' => 'Ústecký kraj — jiný',     'value' => 'usti-jiny' ],
					[ 'text' => 'Mimo Ústecký kraj',       'value' => 'mimo' ],
				],
			],

			// Row 2: Jméno + E-mail + Telefon (thirds)
			[
				'type'                  => 'text',
				'id'                    => 3,
				'label'                 => 'Jméno a příjmení',
				'isRequired'            => true,
				'cssClass'              => 'gf-third gf-third-first',
				'autocompleteAttribute' => 'name',
			],
			[
				'type'                  => 'email',
				'id'                    => 4,
				'label'                 => 'E-mail',
				'isRequired'            => true,
				'cssClass'              => 'gf-third gf-third-middle',
				'autocompleteAttribute' => 'email',
			],
			[
				'type'                  => 'phone',
				'id'                    => 5,
				'label'                 => 'Telefon',
				'isRequired'            => false,
				'cssClass'              => 'gf-third gf-third-last',
				'placeholder'           => '+420 000 000 000',
				'phoneFormat'           => 'standard',
				'autocompleteAttribute' => 'tel',
			],

			// Row 3: Textarea
			[
				'type'        => 'textarea',
				'id'          => 6,
				'label'       => 'Doplňující informace',
				'isRequired'  => false,
				'placeholder' => 'Sem napište svůj dotaz nebo zprávu…',
				'rows'        => 5,
			],

			// GDPR consent checkbox (required)
			[
				'type'       => 'checkbox',
				'id'         => 8,
				'label'      => 'Souhlas se zpracováním osobních údajů',
				'isRequired' => true,
				'cssClass'   => 'gf-gdpr',
				'choices'    => [
					[
						'text'       => 'Souhlasím se zpracováním osobních údajů',
						'value'      => 'souhlas',
						'isSelected' => false,
					],
				],
			],
		],

		'button' => [
			'type'     => 'text',
			'text'     => 'Odeslat zprávu',
			'imageUrl' => '',
		],

		'confirmations' => [
			[
				'id'                => '1',
				'name'              => 'Výchozí potvrzení',
				'isDefault'         => true,
				'type'              => 'message',
				'message'           => '<p>Děkujeme za zprávu! Ozveme se vám zpravidla do 2 pracovních dnů.</p>',
				'disableAutoformat' => false,
			],
		],

		'notifications' => [
			[
				'id'       => '1',
				'name'     => 'Notifikace administrátora',
				'isActive' => true,
				'to'       => '{admin_email}',
				'subject'  => 'Nová zpráva z kontaktního formuláře — {Důvod kontaktu:1}',
				'message'  => "Přišla nová zpráva z webu.\n\nDůvod: {Důvod kontaktu:1}\nRegion: {Váš region:2}\nJméno: {Jméno a příjmení:3}\nE-mail: {E-mail:4}\nTelefon: {Telefon:5}\n\nZpráva:\n{Doplňující informace:6}",
				'from'     => '{admin_email}',
				'fromName' => 'CPNRP web',
				'replyTo'  => '{E-mail:4}',
				'routing'  => [],
			],
			[
				'id'       => '2',
				'name'     => 'Potvrzení odesílateli',
				'isActive' => true,
				'to'       => '{E-mail:4}',
				'toType'   => 'field',
				'subject'  => 'Přijali jsme vaši zprávu — CPNRP',
				'message'  => "Dobrý den, {Jméno a příjmení:3},\n\nvaši zprávu jsme přijali a ozveme se vám zpravidla do 2 pracovních dnů.\n\nS pozdravem\nCentrum pro náhradní rodinnou péči\ninfo@cpnrp.cz | +420 731 557 681",
				'from'     => '{admin_email}',
				'fromName' => 'CPNRP',
				'replyTo'  => 'info@cpnrp.cz',
				'routing'  => [],
			],
		],
	];

	$form_id = GFAPI::add_form( $form );
	if ( ! is_wp_error( $form_id ) ) {
		update_option( 'cpnrp_kontakt_form_id', $form_id );
	}
}

// ── Upgrade existing form: honeypot + GDPR checkbox + placeholder ─
add_action( 'admin_init', 'cpnrp_upgrade_kontakt_gf_form_v2', 20 );
function cpnrp_upgrade_kontakt_gf_form_v2() {
	if ( ! class_exists( 'GFAPI' ) ) return;
	if ( get_option( 'cpnrp_kontakt_form_v2' ) ) return;

	$form_id = (int) get_option( 'cpnrp_kontakt_form_id' );
	if ( ! $form_id ) return;

	$form = GFAPI::get_form( $form_id );
	if ( ! $form ) return;

	// Enable honeypot
	$form['enableHoneypot'] = true;

	$has_gdpr       = false;
	$has_html_note  = false;

	foreach ( $form['fields'] as $field ) {
		if ( $field->id === 8 && $field->type === 'checkbox' ) $has_gdpr      = true;
		if ( $field->id === 7 && $field->type === 'html' )     $has_html_note = true;
		// Set phone placeholder
		if ( $field->id === 5 ) $field->placeholder = '+420 000 000 000';
	}

	// Remove old HTML consent note, add proper checkbox
	if ( $has_html_note && ! $has_gdpr ) {
		$form['fields'] = array_values( array_filter(
			$form['fields'],
			fn( $f ) => $f->id !== 7
		) );

		$form['fields'][] = GF_Fields::create( [
			'type'       => 'checkbox',
			'id'         => 8,
			'label'      => 'Souhlas se zpracováním osobních údajů',
			'isRequired' => true,
			'cssClass'   => 'gf-gdpr',
			'choices'    => [
				[
					'text'       => 'Souhlasím se zpracováním osobních údajů',
					'value'      => 'souhlas',
					'isSelected' => false,
				],
			],
		] );
	}

	GFAPI::update_form( $form );
	update_option( 'cpnrp_kontakt_form_v2', true );
}
