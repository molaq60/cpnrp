<?php
/**
 * Homepage — services section (Naše služby).
 * Editable via Appearance → Customize → CPNRP Nastavení → Naše služby.
 */

$service_defaults = [
	1 => [ 'title' => 'Doprovázení rodin',   'desc' => 'Pravidelná podpora a poradenství pro pěstounské rodiny ve všech fázích péče.',        'url' => '/pro-rodiny/pestounska-pece/doprovazeni',  'color' => 'teal-dark'  ],
	2 => [ 'title' => 'Přípravné kurzy',      'desc' => 'Komplexní příprava pro zájemce o pěstounství a osvojení dětí.',                        'url' => '/pro-rodiny/zajemci/pripravne-kurzy',      'color' => 'teal-light' ],
	3 => [ 'title' => 'Odborné poradenství',  'desc' => 'Psychologické, právní a sociální poradenství pro náhradní rodiny.',                     'url' => '/pro-rodiny/pestounska-pece/poradenstvi',  'color' => 'gold'       ],
	4 => [ 'title' => 'Podpůrné skupiny',     'desc' => 'Pravidelná setkávání pěstounů pro sdílení zkušeností a vzájemnou podporu.',             'url' => '/pro-rodiny/pestounska-pece',              'color' => 'green'      ],
	5 => [ 'title' => 'Vzdělávání',           'desc' => 'Semináře, workshopy a konference pro odborníky i náhradní rodiče.',                     'url' => '/pro-rodiny/pestounska-pece/vzdelavani',   'color' => 'teal'       ],
	6 => [ 'title' => 'Krizová pomoc',        'desc' => 'Okamžitá podpora v náročných životních situacích a krizových momentech.',               'url' => '/kontakt',                                 'color' => 'red'        ],
];

$services = [];
foreach ( $service_defaults as $n => $def ) {
	$color_raw = get_theme_mod( "cpnrp_service_{$n}_color", $def['color'] );
	$allowed   = [ 'teal-dark', 'teal', 'teal-light', 'gold', 'green', 'red' ];
	$color     = in_array( $color_raw, $allowed, true ) ? $color_raw : $def['color'];

	$services[] = [
		'title' => get_theme_mod( "cpnrp_service_{$n}_title", $def['title'] ),
		'desc'  => get_theme_mod( "cpnrp_service_{$n}_desc",  $def['desc']  ),
		'url'   => get_theme_mod( "cpnrp_service_{$n}_url",   $def['url']   ),
		'color' => $color,
	];
}

$delays = [ '', ' delay-2', ' delay-3', '', ' delay-2', ' delay-3' ];
?>

<section id="sluzby" class="home-services" aria-label="<?php esc_attr_e( 'Naše služby', 'cpnrp' ); ?>">
	<div class="container">

		<div class="section-heading animate-fade-up">
			<h2 class="section-title">Naše služby</h2>
			<div class="section-title-bar" aria-hidden="true"></div>
			<p class="section-subtitle">Dětem, které vyrůstají v náhradních rodinách, poskytujeme tyto služby</p>
		</div>

		<div class="services-grid">
			<?php foreach ( $services as $i => $service ) : ?>
			<a href="<?php echo esc_url( cpnrp_url( $service['url'] ) ); ?>"
			   class="service-card animate-fade-up<?php echo $delays[ $i ]; ?>">
				<div class="service-stripe service-stripe--<?php echo esc_attr( $service['color'] ); ?>"></div>
				<div class="service-body">
					<h3 class="service-title"><?php echo esc_html( $service['title'] ); ?></h3>
					<p class="service-desc"><?php echo esc_html( $service['desc'] ); ?></p>
					<span class="service-link service-link--<?php echo esc_attr( $service['color'] ); ?>">
						Zjistit více
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
							<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
						</svg>
					</span>
				</div>
			</a>
			<?php endforeach; ?>
		</div>

		<div class="services-cta">
			<?php
			$_pro_rodiny     = get_page_by_path( 'pro-rodiny' );
			$_url_pro_rodiny = $_pro_rodiny ? get_permalink( $_pro_rodiny ) : home_url( '/pro-rodiny' );
			?>
			<a href="<?php echo esc_url( $_url_pro_rodiny ); ?>" class="btn-outline-teal">
				Zobrazit všechny služby
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
				</svg>
			</a>
		</div>

	</div>
</section>
