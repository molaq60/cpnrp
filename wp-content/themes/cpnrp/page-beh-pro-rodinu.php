<?php
/**
 * Template Name: Běh pro rodinu
 */

get_header();

$id = get_the_ID();

// ── Helper: load meta with fallback ─────────────────────────────
function beh_meta( $key, $default = '' ) {
	$val = get_post_meta( get_the_ID(), $key, true );
	return ( $val !== '' && $val !== false ) ? $val : $default;
}

// ── Load all meta ────────────────────────────────────────────────
$edition        = beh_meta( '_beh_edition',        '9. ročník · 2026' );
$lead           = beh_meta( '_beh_lead',            'Charitativní běh, jehož výtěžek putuje na doučování dětí v náhradní rodinné péči. Přijďte si zaběhnout, projít s rodinou — nebo nás jen podpořit.' );
$kdy            = beh_meta( '_beh_kdy',             'září 2026' );
$kde            = beh_meta( '_beh_kde',             'Střelecký ostrov, Litoměřice' );
$pro_koho       = beh_meta( '_beh_pro_koho',        'Děti · rodiny · běžci · chodci' );
$reg_url        = beh_meta( '_beh_registrace_url',  'https://irontime.cz/prihlaska3200' );
$tym_email      = beh_meta( '_beh_tym_email',       'info@cpnrp.cz' );
$partner_email  = beh_meta( '_beh_partner_email',   'info@cpnrp.cz' );

$stats = [];
$stat_defaults = [
	1 => [ 'num' => '8',          'label' => 'úspěšných ročníků',             'note' => '2018 – 2025' ],
	2 => [ 'num' => 'desítky',    'label' => 'dětí podpořených doučováním',   'note' => 'jen v roce 2024' ],
	3 => [ 'num' => '3 generace', 'label' => 'na jedné startovní čáře',       'note' => 'děti, rodiče i prarodiče' ],
];
for ( $i = 1; $i <= 3; $i++ ) {
	$stats[$i] = [
		'num'   => beh_meta( "_beh_stat{$i}_num",   $stat_defaults[$i]['num'] ),
		'label' => beh_meta( "_beh_stat{$i}_label", $stat_defaults[$i]['label'] ),
		'note'  => beh_meta( "_beh_stat{$i}_note",  $stat_defaults[$i]['note'] ),
	];
}

$info_defaults = [
	1 => [ 'title' => 'Registrace a prezence',  'text' => 'Registrace na místě od 8:30. Ukončení registrace 30 minut před startem vaší kategorie. Online přihlášku najdete na webu organizátora.' ],
	2 => [ 'title' => 'Místo a parkování',       'text' => 'Střelecký ostrov, Litoměřice. Parkování na vyhrazeném parkovišti. Trasa vede po asfaltové cyklostezce.' ],
	3 => [ 'title' => 'Kam jde výtěžek',         'text' => 'Výtěžek akce putuje na podporu doučování dětí v náhradní rodinné péči v domácím prostředí. V roce 2024 jsme díky běhu zajistili doučování pro desítky dětí v Ústeckém kraji.' ],
	4 => [ 'title' => 'Pořadatelé',              'text' => 'CPNRP, o.p.s. a Rozběháme Česko, z.ú. (za Rozběháme Litoměřicko — Kateřina Salácová, tel. 737 988 474). Každý závodí na vlastní nebezpečí. Občerstvení zajištěno (voda, ovoce).' ],
];
$infos = [];
for ( $i = 1; $i <= 4; $i++ ) {
	$infos[$i] = [
		'title' => beh_meta( "_beh_info{$i}_title", $info_defaults[$i]['title'] ),
		'text'  => beh_meta( "_beh_info{$i}_text",  $info_defaults[$i]['text'] ),
	];
}

$cta_title = beh_meta( '_beh_cta_title', 'Připojíte se k 9. ročníku?' );
$cta_text  = beh_meta( '_beh_cta_text',  'Každý kilometr promění v hodinu doučování pro dítě v náhradní péči.' );

// ── Parse sponsors ────────────────────────────────────────────────
$sponsors_raw     = beh_meta( '_beh_sponsors', "Město Litoměřice|mesto-litomerice.jpg\nHolcim|holcim.jpg\nCekro|cekro.png\nAmedis|amedis.png\nNadace J&T|nadace-jt.png\nMondi|mondi.jpg\nMagna Exteriors|magna-exteriors.png\nZdravé město Litoměřice|zdrave-mesto-litomerice.jpg" );
$partners_img_url = get_template_directory_uri() . '/assets/images/partners/';
$sponsors = [];
foreach ( array_filter( array_map( 'trim', explode( "\n", $sponsors_raw ) ) ) as $line ) {
	$parts = explode( '|', $line, 2 );
	if ( count( $parts ) === 2 ) {
		$sponsors[] = [ 'name' => trim( $parts[0] ), 'file' => trim( $parts[1] ) ];
	}
}

$plakat_url = beh_meta( '_beh_plakat' );

// ── Hero background ───────────────────────────────────────────────
$hero_bg = has_post_thumbnail( $id )
	? get_the_post_thumbnail_url( $id, 'full' )
	: get_template_directory_uri() . '/assets/images/beh-foto.jpg';
?>

<main id="main-content" role="main">

	<!-- ── HERO ─────────────────────────────────────────────────── -->
	<section class="beh-hero">
		<div class="beh-hero-bg" style="background-image: url('<?php echo esc_url( $hero_bg ); ?>')"></div>
		<div class="beh-hero-overlay"></div>

		<div class="container beh-hero-inner">

			<a href="<?php echo esc_url( home_url( '/pribehy' ) ); ?>" class="beh-back-link">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
				<?php esc_html_e( 'Příběhy rodin', 'cpnrp' ); ?>
			</a>

			<div class="beh-edition-badge">
				<span class="beh-edition-dot"></span>
				<?php echo esc_html( $edition ); ?>
			</div>

			<h1 class="beh-hero-title">
				<?php esc_html_e( 'Běžíme', 'cpnrp' ); ?><br>
				<span><?php esc_html_e( 'pro náhradní rodiny', 'cpnrp' ); ?></span>
			</h1>

			<p class="beh-hero-lead"><?php echo esc_html( $lead ); ?></p>

			<!-- Info badges -->
			<div class="beh-info-badges">
				<div class="beh-info-badge">
					<p class="beh-info-badge-label"><?php esc_html_e( 'Kdy', 'cpnrp' ); ?></p>
					<p class="beh-info-badge-value"><?php echo esc_html( $kdy ); ?></p>
				</div>
				<div class="beh-info-badge">
					<p class="beh-info-badge-label"><?php esc_html_e( 'Kde', 'cpnrp' ); ?></p>
					<p class="beh-info-badge-value"><?php echo esc_html( $kde ); ?></p>
				</div>
				<div class="beh-info-badge">
					<p class="beh-info-badge-label"><?php esc_html_e( 'Pro koho', 'cpnrp' ); ?></p>
					<p class="beh-info-badge-value"><?php echo esc_html( $pro_koho ); ?></p>
				</div>
			</div>

			<!-- CTA buttons -->
			<div class="beh-hero-cta">
				<a href="<?php echo esc_url( $reg_url ); ?>" class="beh-btn beh-btn--gold" target="_blank" rel="noopener">
					<?php esc_html_e( 'Přihlásit se online', 'cpnrp' ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
				</a>
				<a href="#zapojit-se" class="beh-btn beh-btn--outline-white">
					<?php esc_html_e( 'Jiné formy podpory', 'cpnrp' ); ?>
				</a>
			</div>

		</div>
	</section>

	<!-- ── CO JSME DOKÁZALI ──────────────────────────────────────── -->
	<section class="beh-stats">
		<div class="container">
			<div class="beh-stats-inner">
				<p class="beh-stats-eyebrow"><?php esc_html_e( 'Tradice od roku 2018', 'cpnrp' ); ?></p>
				<h2 class="beh-stats-title"><?php esc_html_e( 'Co jsme společně dokázali', 'cpnrp' ); ?></h2>

				<div class="beh-stats-grid">
					<?php
					$stat_colors = [ 1 => 'default', 2 => 'red', 3 => 'teal' ];
					for ( $i = 1; $i <= 3; $i++ ) :
					?>
					<div class="beh-stat beh-stat--<?php echo esc_attr( $stat_colors[$i] ); ?>">
						<p class="beh-stat-num"><?php echo esc_html( $stats[$i]['num'] ); ?></p>
						<p class="beh-stat-label"><?php echo esc_html( $stats[$i]['label'] ); ?></p>
						<p class="beh-stat-note"><?php echo esc_html( $stats[$i]['note'] ); ?></p>
					</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- ── TŘI ZPŮSOBY ──────────────────────────────────────────── -->
	<section id="zapojit-se" class="beh-ways">
		<div class="container">

			<div class="section-heading animate-fade-up">
				<h2 class="section-title"><?php esc_html_e( 'Tři způsoby, jak se zapojit', 'cpnrp' ); ?></h2>
				<div class="section-title-bar"></div>
				<p class="section-subtitle"><?php esc_html_e( 'Vyberte si, co vám nejvíc sedí — každá forma pomáhá', 'cpnrp' ); ?></p>
			</div>

			<div class="beh-ways-grid">

				<!-- Přijďte si zaběhnout -->
				<div class="beh-way-card">
					<div class="beh-way-card-bar beh-way-card-bar--gold"></div>
					<div class="beh-way-card-body">
						<div class="beh-way-icon beh-way-icon--gold">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<circle cx="13" cy="4" r="2"/>
								<path d="M7 22l2-7 5-3-3-5-3 4-3 1"/>
								<path d="M14 17l3 5"/>
							</svg>
						</div>
						<h3><?php esc_html_e( 'Přijďte si zaběhnout', 'cpnrp' ); ?></h3>
						<p><?php esc_html_e( 'Sami, s rodinou nebo s kamarády. Trasy od 100 m pro nejmenší až po 8 km pro závodníky. Závodně i v klidu. Každý běžec pomáhá.', 'cpnrp' ); ?></p>
						<a href="<?php echo esc_url( $reg_url ); ?>" class="beh-way-link" target="_blank" rel="noopener">
							<?php esc_html_e( 'Přihláška online', 'cpnrp' ); ?>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
						</a>
					</div>
				</div>

				<!-- Sestavte firemní tým -->
				<div class="beh-way-card">
					<div class="beh-way-card-bar beh-way-card-bar--red"></div>
					<div class="beh-way-card-body">
						<div class="beh-way-icon beh-way-icon--red">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
								<circle cx="9" cy="7" r="4"/>
								<path d="M23 21v-2a4 4 0 00-3-3.87"/>
								<path d="M16 3.13a4 4 0 010 7.75"/>
							</svg>
						</div>
						<h3><?php esc_html_e( 'Sestavte firemní tým', 'cpnrp' ); ?></h3>
						<p><?php esc_html_e( 'Skvělý teambuilding s reálným dopadem. Vyběhněte za svou firmu a každý kilometr promění v doučování pro dítě.', 'cpnrp' ); ?></p>
						<a href="mailto:<?php echo esc_attr( $tym_email ); ?>?subject=Firemn%C3%AD%20t%C3%BDm%20%E2%80%94%20B%C4%9Bh%20pro%20rodinu" class="beh-way-link beh-way-link--red">
							<?php esc_html_e( 'Napište nám', 'cpnrp' ); ?>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
						</a>
					</div>
				</div>

				<!-- Staňte se partnerem -->
				<div class="beh-way-card">
					<div class="beh-way-card-bar beh-way-card-bar--teal"></div>
					<div class="beh-way-card-body">
						<div class="beh-way-icon beh-way-icon--teal">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M12 2L15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2z"/>
							</svg>
						</div>
						<h3><?php esc_html_e( 'Staňte se partnerem', 'cpnrp' ); ?></h3>
						<p><?php esc_html_e( 'Logo na trati, v komunikaci akce a propojení s lokální komunitou v Litoměřicích. Rádi probereme možnosti spolupráce.', 'cpnrp' ); ?></p>
						<a href="mailto:<?php echo esc_attr( $partner_email ); ?>?subject=Partnerstv%C3%AD%20%E2%80%94%20B%C4%9Bh%20pro%20rodinu" class="beh-way-link beh-way-link--teal">
							<?php esc_html_e( 'Domluvit schůzku', 'cpnrp' ); ?>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
						</a>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- ── KATEGORIE A STARTOVNÉ ─────────────────────────────────── -->
	<section class="beh-categories">
		<div class="container">
			<div class="beh-categories-inner">

				<div class="section-heading" style="text-align:left;">
					<h2 class="section-title" style="font-size:clamp(1.5rem,3vw,2rem);"><?php esc_html_e( 'Kategorie a startovné', 'cpnrp' ); ?></h2>
					<div class="section-title-bar" style="margin-left:0;"></div>
				</div>

				<div class="beh-cat-grid">

					<div class="beh-cat-card">
						<div class="beh-cat-card-bar beh-cat-card-bar--gold"></div>
						<div class="beh-cat-card-body">
							<h3><?php esc_html_e( 'Děti (startovné 50 Kč)', 'cpnrp' ); ?></h3>
							<ul class="beh-cat-list">
								<li>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
									<?php esc_html_e( '1 km — děti 9–13 let', 'cpnrp' ); ?>
								</li>
								<li>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
									<?php esc_html_e( '500 m — děti 5–8 let', 'cpnrp' ); ?>
								</li>
								<li>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
									<?php esc_html_e( '100 m — děti 2–4 roky', 'cpnrp' ); ?>
								</li>
							</ul>
							<p class="beh-cat-note"><?php esc_html_e( 'Oceněny budou všechny zúčastněné děti.', 'cpnrp' ); ?></p>
						</div>
					</div>

					<div class="beh-cat-card">
						<div class="beh-cat-card-bar beh-cat-card-bar--teal"></div>
						<div class="beh-cat-card-body">
							<h3><?php esc_html_e( 'Dospělí (300 Kč online / 350 Kč na místě)', 'cpnrp' ); ?></h3>
							<ul class="beh-cat-list beh-cat-list--teal">
								<li>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
									<?php esc_html_e( '8 km — muži / ženy', 'cpnrp' ); ?>
								</li>
								<li>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
									<?php esc_html_e( '4 km — muži / ženy', 'cpnrp' ); ?>
								</li>
								<li>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
									<?php esc_html_e( '1 km — chodci s/bez kočárku', 'cpnrp' ); ?>
								</li>
							</ul>
							<p class="beh-cat-note"><?php esc_html_e( 'Oceněni první 3 ženy a 3 muži v kategorii běh a první 3 chodci.', 'cpnrp' ); ?></p>
						</div>
					</div>

				</div>
			</div>
		</div>
	</section>

	<!-- ── PRAKTICKÉ INFORMACE ───────────────────────────────────── -->
	<section class="beh-practical">
		<div class="container">
			<div class="beh-practical-inner">

				<div class="section-heading" style="text-align:left;">
					<h2 class="section-title" style="font-size:clamp(1.5rem,3vw,2rem);"><?php esc_html_e( 'Praktické informace', 'cpnrp' ); ?></h2>
					<div class="section-title-bar" style="margin-left:0;"></div>
				</div>

				<div class="beh-info-list">
					<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
					<div class="beh-info-block">
						<h3><?php echo esc_html( $infos[$i]['title'] ); ?></h3>
						<p><?php echo esc_html( $infos[$i]['text'] ); ?></p>
					</div>
					<?php endfor; ?>
				</div>

			</div>
		</div>
	</section>

	<!-- ── PLAKÁT ───────────────────────────────────────────────── -->
	<?php if ( $plakat_url ) : ?>
	<section class="beh-plakat-section">
		<div class="container">
			<div class="beh-plakat-inner">
				<div class="section-heading animate-fade-up">
					<h2 class="section-title">Plakát akce</h2>
					<div class="section-title-bar"></div>
				</div>
				<div class="beh-plakat-wrap">
					<img src="<?php echo esc_url( $plakat_url ); ?>" alt="Plakát — Běh pro rodinu" loading="lazy" class="beh-plakat-img">
					<a href="<?php echo esc_url( $plakat_url ); ?>" download class="beh-plakat-download" target="_blank" rel="noopener">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
						Stáhnout plakát
					</a>
				</div>
			</div>
		</div>
	</section>
	<style>
	.beh-plakat-section { padding: 72px 0; background: var(--color-gray-light, #f5f5f5); }
	.beh-plakat-inner   { display: flex; flex-direction: column; align-items: center; gap: 32px; }
	.beh-plakat-wrap    { display: flex; flex-direction: column; align-items: center; gap: 20px; }
	.beh-plakat-img     { max-width: min(420px, 100%); width: 100%; border-radius: 8px; box-shadow: 0 8px 32px rgba(0,0,0,.15); display: block; }
	.beh-plakat-download {
		display: inline-flex; align-items: center; gap: 8px;
		padding: 10px 24px; border-radius: 6px;
		background: var(--color-teal-dark, #1A6080); color: #fff;
		font-weight: 600; font-size: .95rem; text-decoration: none;
		transition: background .2s;
	}
	.beh-plakat-download:hover { background: var(--color-teal-light, #2a88b0); color: #fff; }
	</style>
	<?php endif; ?>

	<!-- ── PARTNEŘI AKCE ─────────────────────────────────────────── -->
	<?php if ( $sponsors ) : ?>
	<section class="beh-sponsors">
		<div class="container">
			<p class="beh-sponsors-label"><?php esc_html_e( 'Děkujeme partnerům akce', 'cpnrp' ); ?></p>
			<div class="beh-sponsors-grid">
				<?php foreach ( $sponsors as $s ) : ?>
				<div class="beh-sponsor-item">
					<img
						src="<?php echo esc_url( $partners_img_url . $s['file'] ); ?>"
						alt="<?php echo esc_attr( $s['name'] ); ?>"
						loading="lazy"
					>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── ZÁVĚREČNÁ CTA ─────────────────────────────────────────── -->
	<section class="beh-cta">
		<div class="container">
			<div class="beh-cta-inner">
				<h2 class="beh-cta-title"><?php echo esc_html( $cta_title ); ?></h2>
				<p class="beh-cta-text"><?php echo esc_html( $cta_text ); ?></p>
				<div class="beh-hero-cta">
					<a href="<?php echo esc_url( $reg_url ); ?>" class="beh-btn beh-btn--gold" target="_blank" rel="noopener">
						<?php esc_html_e( 'Přihlásit se online', 'cpnrp' ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
					</a>
					<a href="<?php echo esc_url( home_url( '/pribehy' ) ); ?>" class="beh-btn beh-btn--outline-white">
						← <?php esc_html_e( 'Příběhy rodin', 'cpnrp' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
