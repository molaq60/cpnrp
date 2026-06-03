<?php
/**
 * WordPress Customizer — editable settings for the header area.
 *
 * Appearance → Customize → CPNRP Nastavení
 *   └─ Donor Bar (horní pruh)
 *   └─ Hlavní CTA tlačítko
 *   └─ Megamenu
 */

function cpnrp_customize_register( $wp_customize ) {

	// ── Main panel ──────────────────────────────────────────────────────────
	$wp_customize->add_panel( 'cpnrp_panel', [
		'title'    => __( 'CPNRP Nastavení', 'cpnrp' ),
		'priority' => 130,
	] );

	// ── Donor Bar ───────────────────────────────────────────────────────────
	$wp_customize->add_section( 'cpnrp_donor_bar', [
		'title'       => __( 'Donor Bar (horní pruh)', 'cpnrp' ),
		'description' => __( 'Světle modrý pruh nad hlavičkou s akcemi.', 'cpnrp' ),
		'panel'       => 'cpnrp_panel',
	] );

	$donor_bar_settings = [
		'cpnrp_donor_bar_text'  => [
			'label'   => __( 'Text proužku', 'cpnrp' ),
			'default' => 'Přijďte nás podpořit na nadcházejících benefičních akcích — každý účastník pomáhá rodinám v Ústeckém kraji.',
			'type'    => 'text',
		],
		'cpnrp_donor_btn1_text' => [
			'label'   => __( 'Tlačítko 1 — popisek', 'cpnrp' ),
			'default' => 'Běh pro rodinu',
			'type'    => 'text',
		],
		'cpnrp_donor_btn1_url'  => [
			'label'   => __( 'Tlačítko 1 — URL', 'cpnrp' ),
			'default' => '/beh-pro-rodinu',
			'type'    => 'url',
		],
		'cpnrp_donor_btn2_text' => [
			'label'   => __( 'Tlačítko 2 — popisek', 'cpnrp' ),
			'default' => 'Divadelní benefice',
			'type'    => 'text',
		],
		'cpnrp_donor_btn2_url'  => [
			'label'   => __( 'Tlačítko 2 — URL', 'cpnrp' ),
			'default' => '/pribehy',
			'type'    => 'url',
		],
	];

	foreach ( $donor_bar_settings as $id => $cfg ) {
		$wp_customize->add_setting( $id, [
			'default'           => $cfg['default'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );
		$wp_customize->add_control( $id, [
			'label'   => $cfg['label'],
			'section' => 'cpnrp_donor_bar',
			'type'    => $cfg['type'],
		] );
	}

	// ── Header CTA button ───────────────────────────────────────────────────
	$wp_customize->add_section( 'cpnrp_header_cta', [
		'title' => __( 'Hlavní CTA tlačítko', 'cpnrp' ),
		'panel' => 'cpnrp_panel',
	] );

	$cta_settings = [
		'cpnrp_cta_text' => [
			'label'   => __( 'Popisek tlačítka', 'cpnrp' ),
			'default' => 'Podpořte nás',
			'type'    => 'text',
		],
		'cpnrp_cta_url'  => [
			'label'   => __( 'URL', 'cpnrp' ),
			'default' => '/podporte-nas',
			'type'    => 'url',
		],
	];

	foreach ( $cta_settings as $id => $cfg ) {
		$wp_customize->add_setting( $id, [
			'default'           => $cfg['default'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );
		$wp_customize->add_control( $id, [
			'label'   => $cfg['label'],
			'section' => 'cpnrp_header_cta',
			'type'    => $cfg['type'],
		] );
	}

	// ── Megamenu extras ─────────────────────────────────────────────────────
	// Info box text is keyed by menu-item slug, e.g. "pro-rodiny".
	// Add one setting per top-level item that needs an info box.
	$wp_customize->add_section( 'cpnrp_megamenu', [
		'title'       => __( 'Megamenu', 'cpnrp' ),
		'description' => __( 'Volitelné info boxy pod sloupci megamenu.', 'cpnrp' ),
		'panel'       => 'cpnrp_panel',
	] );

	// ── Homepage stats / počítadlo ─────────────────────────────────────────────
	$wp_customize->add_section( 'cpnrp_stats', [
		'title'       => __( 'Počítadlo (Homepage)', 'cpnrp' ),
		'description' => __( 'Čtyři statistiky pod hero sekcí. Každá má hodnotu pro animaci, zobrazovaný formát a popisek.', 'cpnrp' ),
		'panel'       => 'cpnrp_panel',
	] );

	$stat_defaults = [
		1 => [ 'value' => '1520', 'format' => '1 520', 'label' => 'Rodin, kterým pomáháme' ],
		2 => [ 'value' => '124',  'format' => '124',   'label' => 'Odborných konzultací' ],
		3 => [ 'value' => '850',  'format' => '850',   'label' => 'Dětí v náhradní péči' ],
		4 => [ 'value' => '15',   'format' => '15',    'label' => 'Let zkušeností' ],
	];

	foreach ( $stat_defaults as $n => $def ) {
		foreach ( [
			"cpnrp_stat_{$n}_value"  => [ 'label' => "Stat {$n} — číslo pro animaci", 'default' => $def['value'] ],
			"cpnrp_stat_{$n}_format" => [ 'label' => "Stat {$n} — zobrazovaný formát", 'default' => $def['format'] ],
			"cpnrp_stat_{$n}_label"  => [ 'label' => "Stat {$n} — popisek",            'default' => $def['label'] ],
		] as $id => $cfg ) {
			$wp_customize->add_setting( $id, [
				'default'           => $cfg['default'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			] );
			$wp_customize->add_control( $id, [
				'label'   => $cfg['label'],
				'section' => 'cpnrp_stats',
				'type'    => 'text',
			] );
		}
	}

	// ── Entry cards (homepage tiles) ───────────────────────────────────────────
	$wp_customize->add_section( 'cpnrp_entry_cards', [
		'title'       => __( 'Vstupní dlaždice (Homepage)', 'cpnrp' ),
		'description' => __( 'Tři karty pod počítadlem — Adopce, Pěstounská péče, Zájemci o NRP.', 'cpnrp' ),
		'panel'       => 'cpnrp_panel',
	] );

	$card_defaults = [
		1 => [ 'title' => 'Adopce',           'desc' => 'Chci zjistit, jak proces probíhá a co mě čeká.',                              'url' => '/pro-rodiny/adopce' ],
		2 => [ 'title' => 'Pěstounská péče',  'desc' => 'Doprovázení, vzdělávání, poradenství a podpora pro pěstounské rodiny.',        'url' => '/pro-rodiny/pestounska-pece' ],
		3 => [ 'title' => 'Zájemci o NRP',    'desc' => 'Co je náhradní péče? Jak začít? Přípravné kurzy a nejčastější otázky.',        'url' => '/pro-rodiny/zajemci' ],
	];

	foreach ( $card_defaults as $n => $def ) {
		foreach ( [
			"cpnrp_card_{$n}_title" => [ 'label' => "Karta {$n} — nadpis",  'default' => $def['title'], 'type' => 'text' ],
			"cpnrp_card_{$n}_desc"  => [ 'label' => "Karta {$n} — popisek", 'default' => $def['desc'],  'type' => 'textarea' ],
			"cpnrp_card_{$n}_url"   => [ 'label' => "Karta {$n} — URL",     'default' => $def['url'],   'type' => 'url' ],
		] as $id => $cfg ) {
			$wp_customize->add_setting( $id, [
				'default'           => $cfg['default'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			] );
			$wp_customize->add_control( $id, [
				'label'   => $cfg['label'],
				'section' => 'cpnrp_entry_cards',
				'type'    => $cfg['type'],
			] );
		}
	}

	// ── Naše služby (homepage) ───────────────────────────────────────────────
	$wp_customize->add_section( 'cpnrp_services', [
		'title'       => __( 'Naše služby (Homepage)', 'cpnrp' ),
		'description' => __( 'Šest karet služeb pod novinkami. Každá má nadpis, popisek, URL a barvu pruhu.', 'cpnrp' ),
		'panel'       => 'cpnrp_panel',
	] );

	$service_colors = [
		'teal-dark'  => 'Tmavý teal',
		'teal'       => 'Teal',
		'teal-light' => 'Světlý teal',
		'gold'       => 'Zlatá',
		'green'      => 'Zelená',
		'red'        => 'Červená',
	];

	$service_defaults = [
		1 => [ 'title' => 'Doprovázení rodin',   'desc' => 'Pravidelná podpora a poradenství pro pěstounské rodiny ve všech fázích péče.',          'url' => '/pro-rodiny/pestounska-pece/doprovazeni',    'color' => 'teal-dark'  ],
		2 => [ 'title' => 'Přípravné kurzy',      'desc' => 'Komplexní příprava pro zájemce o pěstounství a osvojení dětí.',                          'url' => '/pro-rodiny/zajemci/pripravne-kurzy',        'color' => 'teal-light' ],
		3 => [ 'title' => 'Odborné poradenství',  'desc' => 'Psychologické, právní a sociální poradenství pro náhradní rodiny.',                       'url' => '/pro-rodiny/pestounska-pece/poradenstvi',    'color' => 'gold'       ],
		4 => [ 'title' => 'Podpůrné skupiny',     'desc' => 'Pravidelná setkávání pěstounů pro sdílení zkušeností a vzájemnou podporu.',               'url' => '/pro-rodiny/pestounska-pece',                'color' => 'green'      ],
		5 => [ 'title' => 'Vzdělávání',           'desc' => 'Semináře, workshopy a konference pro odborníky i náhradní rodiče.',                       'url' => '/pro-rodiny/pestounska-pece/vzdelavani',     'color' => 'teal'       ],
		6 => [ 'title' => 'Krizová pomoc',        'desc' => 'Okamžitá podpora v náročných životních situacích a krizových momentech.',                 'url' => '/kontakt',                                   'color' => 'red'        ],
	];

	foreach ( $service_defaults as $n => $def ) {
		// title
		$wp_customize->add_setting( "cpnrp_service_{$n}_title", [
			'default'           => $def['title'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );
		$wp_customize->add_control( "cpnrp_service_{$n}_title", [
			'label'   => "Služba {$n} — nadpis",
			'section' => 'cpnrp_services',
			'type'    => 'text',
		] );

		// description
		$wp_customize->add_setting( "cpnrp_service_{$n}_desc", [
			'default'           => $def['desc'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );
		$wp_customize->add_control( "cpnrp_service_{$n}_desc", [
			'label'   => "Služba {$n} — popisek",
			'section' => 'cpnrp_services',
			'type'    => 'textarea',
		] );

		// URL
		$wp_customize->add_setting( "cpnrp_service_{$n}_url", [
			'default'           => $def['url'],
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		] );
		$wp_customize->add_control( "cpnrp_service_{$n}_url", [
			'label'   => "Služba {$n} — URL",
			'section' => 'cpnrp_services',
			'type'    => 'url',
		] );

		// color
		$wp_customize->add_setting( "cpnrp_service_{$n}_color", [
			'default'           => $def['color'],
			'sanitize_callback' => 'sanitize_key',
			'transport'         => 'refresh',
		] );
		$wp_customize->add_control( "cpnrp_service_{$n}_color", [
			'label'   => "Služba {$n} — barva pruhu",
			'section' => 'cpnrp_services',
			'type'    => 'select',
			'choices' => $service_colors,
		] );
	}

	// ── Podpořte nás — sekce nad patičkou ───────────────────────────────────
	$wp_customize->add_section( 'cpnrp_donate', [
		'title'       => __( 'Podpořte nás (sekce Homepage)', 'cpnrp' ),
		'description' => __( 'CTA sekce nad patičkou — nadpis, popis, tři donation tiers a CTA karta.', 'cpnrp' ),
		'panel'       => 'cpnrp_panel',
	] );

	$donate_settings = [
		// Levá strana
		'cpnrp_donate_heading' => [
			'label'   => 'Nadpis',
			'default' => 'Vaše pomoc mění životy',
			'type'    => 'text',
		],
		'cpnrp_donate_desc' => [
			'label'   => 'Popis',
			'default' => 'Díky vašim darům můžeme poskytovat odbornou péči a podporu stovkám náhradních rodin. Každý příspěvek pomáhá dětem najít bezpečný domov.',
			'type'    => 'textarea',
		],
		// Tier 1 (teal)
		'cpnrp_donate_tier1_amount' => [ 'label' => 'Tier 1 — částka', 'default' => '200 Kč', 'type' => 'text' ],
		// Tier 2 (red — zvýrazněný)
		'cpnrp_donate_tier2_amount' => [ 'label' => 'Tier 2 — částka', 'default' => '400 Kč', 'type' => 'text' ],
		// Tier 3 (gold)
		'cpnrp_donate_tier3_amount' => [ 'label' => 'Tier 3 — částka', 'default' => '800 Kč', 'type' => 'text' ],
		// CTA karta (pravá strana)
		'cpnrp_donate_card_heading' => [ 'label' => 'Karta — nadpis',              'default' => 'Darujte s láskou',                                    'type' => 'text'     ],
		'cpnrp_donate_card_desc'    => [ 'label' => 'Karta — popis',               'default' => 'Vyberte si způsob, jakým chcete podpořit náhradní rodiny.', 'type' => 'textarea' ],
		'cpnrp_donate_btn1_url'     => [ 'label' => 'Karta — URL tlačítka "Podpořte nás"',  'default' => '/podporte-nas',          'type' => 'url'      ],
		'cpnrp_donate_btn2_url'     => [ 'label' => 'Karta — URL tlačítka "Pravidelný dar"', 'default' => '/podporte-nas#pravidelny-dar', 'type' => 'url' ],
		'cpnrp_donate_account'      => [ 'label' => 'Číslo účtu (zobrazený text)',  'default' => '35–9706800297/0100',                                  'type' => 'text'     ],
		'cpnrp_donate_iban'         => [ 'label' => 'IBAN pro QR kódy',             'default' => 'CZ4801000000359706800297',                             'type' => 'text'     ],
	];

	foreach ( $donate_settings as $id => $cfg ) {
		$wp_customize->add_setting( $id, [
			'default'           => $cfg['default'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );
		$wp_customize->add_control( $id, [
			'label'   => $cfg['label'],
			'section' => 'cpnrp_donate',
			'type'    => $cfg['type'],
		] );
	}

	// ── Kontakt — hlavní údaje ──────────────────────────────────────────────
	$wp_customize->add_section( 'cpnrp_kontakt_info', [
		'title' => __( 'Kontakt — hlavní údaje', 'cpnrp' ),
		'panel' => 'cpnrp_panel',
	] );

	$kontakt_info_settings = [
		'cpnrp_kontakt_address' => [
			'label'   => __( 'Adresa (řádky oddělte čárkou)', 'cpnrp' ),
			'default' => 'Teplická 1672/3, 412 01 Litoměřice',
			'type'    => 'text',
		],
		'cpnrp_kontakt_phone' => [
			'label'   => __( 'Telefon', 'cpnrp' ),
			'default' => '+420 731 557 681',
			'type'    => 'text',
		],
		'cpnrp_kontakt_email' => [
			'label'   => __( 'E-mail', 'cpnrp' ),
			'default' => 'info@cpnrp.cz',
			'type'    => 'text',
		],
		'cpnrp_kontakt_hours' => [
			'label'   => __( 'Úřední hodiny', 'cpnrp' ),
			'default' => 'Po — Pá: 9:00 — 17:00',
			'type'    => 'text',
		],
	];

	foreach ( $kontakt_info_settings as $id => $cfg ) {
		$wp_customize->add_setting( $id, [
			'default'           => $cfg['default'],
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'refresh',
		] );
		$wp_customize->add_control( $id, [
			'label'   => $cfg['label'],
			'section' => 'cpnrp_kontakt_info',
			'type'    => $cfg['type'],
		] );
	}

	// ── Kontakt — pobočky ────────────────────────────────────────────────────
	$wp_customize->add_section( 'cpnrp_pobocky', [
		'title' => __( 'Kontakt — pobočky', 'cpnrp' ),
		'panel' => 'cpnrp_panel',
	] );

	$pobocky_defaults = [
		1 => [
			'name'     => 'Litoměřice — Poradna',
			'addr'     => 'Teplická 1672/3, 412 01 Litoměřice',
			'hours'    => 'Po — Pá: 8:00 — 16:00',
			'phone1'   => '+420 416 533 554',
			'phone2'   => '+420 771 770 335',
			'color'    => 'teal-dark',
			'schedule' => [ 'mon' => '08:00-16:00', 'tue' => '08:00-16:00', 'wed' => '08:00-16:00', 'thu' => '08:00-16:00', 'fri' => '08:00-16:00' ],
		],
		2 => [
			'name'     => 'Litoměřice — Centrum',
			'addr'     => '5. května 76, 412 01 Litoměřice',
			'hours'    => 'Pondělí: 8:30 — 16:00',
			'phone1'   => '+420 731 557 681',
			'phone2'   => '',
			'color'    => 'teal-light',
			'schedule' => [ 'mon' => '08:30-16:00', 'tue' => '', 'wed' => '', 'thu' => '', 'fri' => '' ],
		],
		3 => [
			'name'     => 'Ústí nad Labem',
			'addr'     => 'V Jirchářích 60/6, 400 02 Ústí nad Labem',
			'hours'    => 'Po, St: 8:00 — 16:00 · Út, Čt, Pá: terén',
			'phone1'   => '+420 771 770 360',
			'phone2'   => '+420 771 770 340',
			'color'    => 'gold',
			'schedule' => [ 'mon' => '08:00-16:00', 'tue' => '', 'wed' => '08:00-16:00', 'thu' => '', 'fri' => '' ],
		],
		4 => [
			'name'     => 'Rumburk',
			'addr'     => 'Matušova 982, 408 01 Rumburk',
			'hours'    => 'Středa: 9:00 — 16:00',
			'phone1'   => '+420 771 770 360',
			'phone2'   => '',
			'color'    => 'red',
			'schedule' => [ 'mon' => '', 'tue' => '', 'wed' => '09:00-16:00', 'thu' => '', 'fri' => '' ],
		],
	];

	$color_choices = [
		'teal-dark'  => __( 'Tmavá tyrkysová', 'cpnrp' ),
		'teal-light' => __( 'Světlá tyrkysová', 'cpnrp' ),
		'gold'       => __( 'Zlatá', 'cpnrp' ),
		'red'        => __( 'Červená', 'cpnrp' ),
		'green'      => __( 'Zelená', 'cpnrp' ),
	];

	foreach ( $pobocky_defaults as $i => $def ) {
		$fields = [
			"cpnrp_pobocka_{$i}_name"    => [ 'label' => "#{$i} Název",                      'type' => 'text',   'default' => $def['name']  ],
			"cpnrp_pobocka_{$i}_addr"    => [ 'label' => "#{$i} Adresa",                     'type' => 'text',   'default' => $def['addr']  ],
			"cpnrp_pobocka_{$i}_hours"   => [ 'label' => "#{$i} Otevírací hodiny",           'type' => 'text',   'default' => $def['hours'] ],
			"cpnrp_pobocka_{$i}_phone1"  => [ 'label' => "#{$i} Telefon 1",                  'type' => 'text',   'default' => $def['phone1']],
			"cpnrp_pobocka_{$i}_phone2"  => [ 'label' => "#{$i} Telefon 2",                  'type' => 'text',   'default' => $def['phone2']],
			"cpnrp_pobocka_{$i}_color"   => [ 'label' => "#{$i} Barva pruhu",                'type' => 'select', 'default' => $def['color'], 'choices' => $color_choices ],
			"cpnrp_pobocka_{$i}_map_url" => [ 'label' => "#{$i} Mapa — embed URL (z Google Maps → Sdílet → Vložit)", 'type' => 'text', 'default' => '' ],
		];

		foreach ( $fields as $id => $cfg ) {
			$wp_customize->add_setting( $id, [
				'default'           => $cfg['default'],
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			] );

			$control_args = [
				'label'   => $cfg['label'],
				'section' => 'cpnrp_pobocky',
				'type'    => $cfg['type'],
			];
			if ( isset( $cfg['choices'] ) ) {
				$control_args['choices'] = $cfg['choices'];
			}
			$wp_customize->add_control( $id, $control_args );
		}

		// Schedule fields (for open/closed badge) — format: HH:MM-HH:MM, empty = closed
		$day_labels = [
			'mon' => "#{$i} Pondělí",
			'tue' => "#{$i} Úterý",
			'wed' => "#{$i} Středa",
			'thu' => "#{$i} Čtvrtek",
			'fri' => "#{$i} Pátek",
		];
		foreach ( $day_labels as $day_key => $day_label ) {
			$field_id = "cpnrp_pobocka_{$i}_schedule_{$day_key}";
			$default  = $def['schedule'][ $day_key ] ?? '';
			$wp_customize->add_setting( $field_id, [
				'default'           => $default,
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'refresh',
			] );
			$wp_customize->add_control( $field_id, [
				'label'       => $day_label . ' — od:do (prázdné = zavřeno)',
				'description' => 'Formát: 08:00-16:00',
				'section'     => 'cpnrp_pobocky',
				'type'        => 'text',
			] );
		}
	}

	$wp_customize->add_setting( 'cpnrp_megamenu_pro-rodiny_info', [
		'default'           => 'Teprve se rozhodujete? <a href="' . esc_url( home_url( '/kontakt' ) ) . '">Napište nám</a> — rádi odpovíme na všechny otázky bez závazku.',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'cpnrp_megamenu_pro-rodiny_info', [
		'label'   => __( 'Info box — "Pro rodiny"', 'cpnrp' ),
		'section' => 'cpnrp_megamenu',
		'type'    => 'textarea',
	] );
	// ── Patička — text o organizaci ─────────────────────────────────────────
	$wp_customize->add_section( 'cpnrp_footer_info', [
		'title'    => __( 'Patička — informace o organizaci', 'cpnrp' ),
		'panel'    => 'cpnrp_panel',
		'priority' => 200,
	] );

	$wp_customize->add_setting( 'cpnrp_footer_about_text', [
		'default'           => "Centrum pro NRP, o.p.s.\nIČO: 26999234\nSídlo: Teplická 1672/3, 412 01 Litoměřice\nbežný účet: 35 - 9706800297/0100\nsbírkový účet: 107 - 5420340207/0100",
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'refresh',
	] );
	$wp_customize->add_control( 'cpnrp_footer_about_text', [
		'label'   => __( 'Text v patičce (pod logem)', 'cpnrp' ),
		'section' => 'cpnrp_footer_info',
		'type'    => 'textarea',
	] );
}
add_action( 'customize_register', 'cpnrp_customize_register' );
