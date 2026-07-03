<?php
/**
 * Template Name: Divadelní benefice
 */

get_header();

// ── Helper ──────────────────────────────────────────────────────────
if ( ! function_exists( 'ben_meta' ) ) {
	function ben_meta( string $key, string $default = '' ): string {
		$val = get_post_meta( get_the_ID(), $key, true );
		return ( $val !== '' && $val !== false ) ? (string) $val : $default;
	}
}

// ── Load meta ────────────────────────────────────────────────────────
$id = get_the_ID();

$edition  = ben_meta( '_ben_edition',  '7. ročník · 2026' );
$lead     = ben_meta( '_ben_lead',     'Charitativní divadelní akce, jejíž výtěžek putuje na podporu dětí v náhradní rodinné péči. Přijďte si užít kulturu a zároveň pomoci.' );
$termin   = ben_meta( '_ben_termin',   'Listopad 2026' );
$pro_koho = ben_meta( '_ben_pro_koho', 'Rodiny · Přátelé divadla · Veřejnost' );
$vytezek  = ben_meta( '_ben_vytezek',  'Podpora dětí v náhradní péči — CPNRP' );
$web_url  = ben_meta( '_ben_web_url',  'https://www.divadelni-benefice.cz' );

// Stats
$stat_defaults = [
	1 => [ 'num' => '7',      'label' => 'úspěšných ročníků',    'note' => '2019 – 2025' ],
	2 => [ 'num' => '4',      'label' => 'města v roce 2026',     'note' => 'Litoměřice · Ústí · Lovosice · Roudnice' ],
	3 => [ 'num' => '100 %',  'label' => 'výtěžku jde na děti',   'note' => 'přímo na podporu náhradních rodin' ],
];
$stats = [];
for ( $i = 1; $i <= 3; $i++ ) {
	$stats[ $i ] = [
		'num'   => ben_meta( "_ben_stat{$i}_num",   $stat_defaults[ $i ]['num'] ),
		'label' => ben_meta( "_ben_stat{$i}_label", $stat_defaults[ $i ]['label'] ),
		'note'  => ben_meta( "_ben_stat{$i}_note",  $stat_defaults[ $i ]['note'] ),
	];
}

// Venues (up to 6)
$venue_defaults = [ 1 => 'Litoměřice', 2 => 'Ústí nad Labem', 3 => 'Lovosice', 4 => 'Roudnice nad Labem', 5 => '', 6 => '' ];
$venues = [];
for ( $i = 1; $i <= 6; $i++ ) {
	$v = ben_meta( "_ben_venue{$i}", $venue_defaults[ $i ] );
	if ( $v ) $venues[] = $v;
}

// Posters
$plakat_1 = ben_meta( '_ben_plakat_1' );
$plakat_2 = ben_meta( '_ben_plakat_2' );

// Gallery
$gallery_title = ben_meta( '_ben_gallery_title' );
$gallery_text  = ben_meta( '_ben_gallery_text' );
$gallery_imgs  = array_filter( array_map( 'trim', explode( "\n", ben_meta( '_ben_gallery_imgs' ) ) ) );

// Sponsors
$sponsors_raw = ben_meta( '_ben_sponsors' );
$sponsors     = [];
if ( $sponsors_raw ) {
	$_sp_decoded = json_decode( $sponsors_raw, true );
	if ( is_array( $_sp_decoded ) ) {
		$sponsors = array_values( array_filter( $_sp_decoded, function ( $s ) {
			return ! empty( $s['img'] );
		} ) );
	}
}

// Contact
$contact_name  = ben_meta( '_ben_contact_name' );
$contact_role  = ben_meta( '_ben_contact_role' );
$contact_email = ben_meta( '_ben_contact_email' );
$contact_phone = ben_meta( '_ben_contact_phone' );

// Assets
$maska_url = get_template_directory_uri() . '/assets/images/maska.png';
$hero_bg   = has_post_thumbnail( $id )
	? get_the_post_thumbnail_url( $id, 'full' )
	: '';
?>

<style>
/* ══ Divadelní benefice — page-specific styles ══════════════════════
   Reuses .beh-* classes from theme-layout.css for hero / stats / sponsors.
   New .ben-* classes cover benefice-specific sections.
   ═════════════════════════════════════════════════════════════════ */

/* override bg-position for benefice hero */
.ben-hero-wrap .beh-hero-bg { background-position: center center; }

/* Floating maska decoration in hero */
.ben-hero-maska {
  position: absolute;
  right: -20px;
  bottom: 0;
  width: clamp(180px, 30vw, 380px);
  opacity: 0.15;
  pointer-events: none;
  user-select: none;
  z-index: 1;
}
@media (min-width: 768px) {
  .ben-hero-maska { opacity: 0.22; right: 3%; }
}

/* ── Venues ──────────────────────────────────────────────────────── */
.ben-venues { padding: 80px 0; background: var(--color-white); }
.ben-venues-inner { max-width: 56rem; margin: 0 auto; }
.ben-venue-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  gap: 16px;
  margin-top: 40px;
}
.ben-venue-card {
  background: var(--color-bg-soft);
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-lg);
  padding: 24px 16px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  text-align: center;
  transition: border-color 200ms ease, box-shadow 200ms ease, transform 200ms ease;
}
.ben-venue-card:hover {
  border-color: var(--color-accent-gold);
  box-shadow: var(--shadow-card);
  transform: translateY(-3px);
}
.ben-venue-icon {
  width: 44px; height: 44px;
  border-radius: 50%;
  background: rgba(244,188,45,0.15);
  display: flex; align-items: center; justify-content: center;
  color: #8a6200;
  flex-shrink: 0;
}
.ben-venue-name {
  font-size: 0.9375rem;
  font-weight: 700;
  color: var(--color-text);
  line-height: 1.4;
  margin: 0;
}

/* ── Plakáty ─────────────────────────────────────────────────────── */
.ben-posters { padding: 80px 0; background: var(--color-bg-soft); }
.ben-posters-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 28px;
  margin-top: 40px;
  justify-content: center;
}
.ben-poster-wrap {
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-card);
  transition: transform 220ms ease, box-shadow 220ms ease;
}
.ben-poster-wrap:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 52px rgba(10,25,40,.22);
}
.ben-poster-wrap img { width: 100%; height: auto; display: block; }

/* ── Galerie & výtěžek ───────────────────────────────────────────── */
.ben-gallery { padding: 80px 0; background: var(--color-white); }
.ben-gallery-layout {
  display: grid;
  grid-template-columns: 1fr;
  gap: 48px;
  margin-top: 40px;
}
@media (min-width: 768px) {
  .ben-gallery-layout { grid-template-columns: 1fr 1fr; align-items: start; }
}
.ben-gallery-text {
  font-size: 1rem;
  line-height: 1.8;
  color: var(--color-text);
}
.ben-gallery-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  gap: 10px;
}
.ben-gallery-grid img {
  width: 100%;
  aspect-ratio: 4/3;
  object-fit: cover;
  border-radius: var(--radius-md);
  display: block;
  transition: transform 200ms ease;
}
.ben-gallery-grid img:hover { transform: scale(1.03); }

/* ── Kontakt ─────────────────────────────────────────────────────── */
.ben-contact-section { padding: 80px 0; background: var(--color-bg-soft); }
.ben-contact-card {
  max-width: 480px;
  margin: 40px auto 0;
  background: var(--color-white);
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-lg);
  padding: 32px;
  box-shadow: var(--shadow-soft);
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.ben-contact-name { font-size: 1.125rem; font-weight: 700; color: var(--color-teal-dark); margin: 0; }
.ben-contact-role { font-size: 0.875rem; color: var(--color-text-muted); margin: 0 0 4px; }
.ben-contact-divider { border: none; border-top: 1px solid var(--color-border); margin: 0; }
.ben-contact-row { display: flex; align-items: center; gap: 10px; font-size: 0.9375rem; color: var(--color-text); }
.ben-contact-row a { color: var(--color-teal); text-decoration: none; }
.ben-contact-row a:hover { text-decoration: underline; }
.ben-contact-icon { flex-shrink: 0; color: var(--color-text-muted); }

/* ── Web CTA ─────────────────────────────────────────────────────── */
.ben-web-cta { padding: 88px 0; background: #101820; color: #fff; text-align: center; }
.ben-web-cta-inner { max-width: 640px; margin: 0 auto; }
.ben-web-cta-label {
  font-size: 0.6875rem; font-weight: 700; letter-spacing: 0.18em;
  text-transform: uppercase; color: var(--color-accent-gold); margin: 0 0 16px;
}
.ben-web-cta-text {
  font-size: clamp(1.125rem, 2.5vw, 1.375rem);
  line-height: 1.6; color: rgba(255,255,255,0.85); margin: 0 0 36px;
}
</style>

<main id="main-content" role="main">

	<!-- ── HERO ─────────────────────────────────────────────────── -->
	<section class="beh-hero ben-hero-wrap">
		<?php if ( $hero_bg ) : ?>
		<div class="beh-hero-bg" style="background-image: url('<?php echo esc_url( $hero_bg ); ?>')"></div>
		<?php endif; ?>
		<div class="beh-hero-overlay"></div>
		<img src="<?php echo esc_url( $maska_url ); ?>" class="ben-hero-maska" alt="" aria-hidden="true">

		<div class="container beh-hero-inner">

			<a href="<?php echo esc_url( home_url( '/podporte-nas' ) ); ?>" class="beh-back-link">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
				<?php esc_html_e( 'Podpořte nás', 'cpnrp' ); ?>
			</a>

			<div class="beh-edition-badge">
				<span class="beh-edition-dot"></span>
				<?php echo esc_html( $edition ); ?>
			</div>

			<h1 class="beh-hero-title">
				<?php esc_html_e( 'Divadelní', 'cpnrp' ); ?><br>
				<span><?php esc_html_e( 'benefice', 'cpnrp' ); ?></span>
			</h1>

			<p class="beh-hero-lead"><?php echo esc_html( $lead ); ?></p>

			<div class="beh-info-badges">
				<div class="beh-info-badge">
					<p class="beh-info-badge-label"><?php esc_html_e( 'Kdy', 'cpnrp' ); ?></p>
					<p class="beh-info-badge-value"><?php echo esc_html( $termin ); ?></p>
				</div>
				<div class="beh-info-badge">
					<p class="beh-info-badge-label"><?php esc_html_e( 'Pro koho', 'cpnrp' ); ?></p>
					<p class="beh-info-badge-value"><?php echo esc_html( $pro_koho ); ?></p>
				</div>
				<div class="beh-info-badge">
					<p class="beh-info-badge-label"><?php esc_html_e( 'Výtěžek pro', 'cpnrp' ); ?></p>
					<p class="beh-info-badge-value"><?php echo esc_html( $vytezek ); ?></p>
				</div>
			</div>

			<div class="beh-hero-cta">
				<a href="<?php echo esc_url( $web_url ); ?>" class="beh-btn beh-btn--gold" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Informace o představeních', 'cpnrp' ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
				</a>
				<?php if ( $venues ) : ?>
				<a href="#mista-konani" class="beh-btn beh-btn--outline-white">
					<?php esc_html_e( 'Místa konání', 'cpnrp' ); ?>
				</a>
				<?php endif; ?>
			</div>

		</div>
	</section>

	<!-- ── POČÍTADLO ────────────────────────────────────────────── -->
	<section class="beh-stats">
		<div class="container">
			<div class="beh-stats-inner">
				<p class="beh-stats-eyebrow"><?php esc_html_e( 'Divadelní benefice v číslech', 'cpnrp' ); ?></p>
				<h2 class="beh-stats-title"><?php esc_html_e( 'Co jsme společně dokázali', 'cpnrp' ); ?></h2>
				<div class="beh-stats-grid">
					<?php
					$stat_colors = [ 1 => 'default', 2 => 'red', 3 => 'teal' ];
					for ( $i = 1; $i <= 3; $i++ ) :
					?>
					<div class="beh-stat beh-stat--<?php echo esc_attr( $stat_colors[ $i ] ); ?>">
						<p class="beh-stat-num"><?php echo esc_html( $stats[ $i ]['num'] ); ?></p>
						<p class="beh-stat-label"><?php echo esc_html( $stats[ $i ]['label'] ); ?></p>
						<p class="beh-stat-note"><?php echo esc_html( $stats[ $i ]['note'] ); ?></p>
					</div>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- ── MÍSTA KONÁNÍ ─────────────────────────────────────────── -->
	<?php if ( $venues ) : ?>
	<section id="mista-konani" class="ben-venues">
		<div class="container">
			<div class="ben-venues-inner">
				<div class="section-heading animate-fade-up">
					<h2 class="section-title"><?php esc_html_e( 'Místa konání', 'cpnrp' ); ?></h2>
					<div class="section-title-bar"></div>
					<p class="section-subtitle"><?php echo esc_html( $termin ); ?></p>
				</div>
				<div class="ben-venue-grid">
					<?php foreach ( $venues as $venue_name ) : ?>
					<div class="ben-venue-card">
						<div class="ben-venue-icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
								<circle cx="12" cy="10" r="3"/>
							</svg>
						</div>
						<p class="ben-venue-name"><?php echo esc_html( $venue_name ); ?></p>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── PLAKÁTY ──────────────────────────────────────────────── -->
	<?php if ( $plakat_1 || $plakat_2 ) : ?>
	<section class="ben-posters">
		<div class="container">
			<div class="section-heading animate-fade-up">
				<h2 class="section-title"><?php esc_html_e( 'Plakát akce', 'cpnrp' ); ?></h2>
				<div class="section-title-bar"></div>
			</div>
			<div class="ben-posters-grid">
				<?php if ( $plakat_1 ) : ?>
				<div class="ben-poster-wrap">
					<img src="<?php echo esc_url( $plakat_1 ); ?>" alt="<?php esc_attr_e( 'Plakát — přední strana', 'cpnrp' ); ?>" loading="lazy">
				</div>
				<?php endif; ?>
				<?php if ( $plakat_2 ) : ?>
				<div class="ben-poster-wrap">
					<img src="<?php echo esc_url( $plakat_2 ); ?>" alt="<?php esc_attr_e( 'Plakát — zadní strana', 'cpnrp' ); ?>" loading="lazy">
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── GALERIE & VÝTĚŽEK ────────────────────────────────────── -->
	<?php if ( $gallery_text || $gallery_imgs ) : ?>
	<section class="ben-gallery">
		<div class="container">
			<?php if ( $gallery_title ) : ?>
			<div class="section-heading animate-fade-up">
				<h2 class="section-title"><?php echo esc_html( $gallery_title ); ?></h2>
				<div class="section-title-bar"></div>
			</div>
			<?php endif; ?>
			<div class="ben-gallery-layout">
				<?php if ( $gallery_text ) : ?>
				<div class="ben-gallery-text">
					<?php echo nl2br( esc_html( $gallery_text ) ); ?>
				</div>
				<?php endif; ?>
				<?php if ( $gallery_imgs ) : ?>
				<div class="ben-gallery-grid">
					<?php foreach ( $gallery_imgs as $img_url ) : ?>
					<img src="<?php echo esc_url( $img_url ); ?>" alt="" loading="lazy">
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── PARTNEŘI ─────────────────────────────────────────────── -->
	<?php if ( $sponsors ) : ?>
	<section class="beh-sponsors">
		<div class="container">
			<p class="beh-sponsors-label"><?php esc_html_e( 'Děkujeme partnerům akce', 'cpnrp' ); ?></p>
			<div class="beh-sponsors-grid">
				<?php foreach ( $sponsors as $s ) : ?>
				<div class="beh-sponsor-item">
					<?php if ( ! empty( $s['url'] ) ) : ?>
					<a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( $s['name'] ?? '' ); ?>">
					<?php endif; ?>
					<img src="<?php echo esc_url( $s['img'] ); ?>" alt="<?php echo esc_attr( $s['name'] ?? '' ); ?>" loading="lazy">
					<?php if ( ! empty( $s['url'] ) ) : ?>
					</a>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── KONTAKT ──────────────────────────────────────────────── -->
	<?php if ( $contact_name || $contact_email || $contact_phone ) : ?>
	<section class="ben-contact-section">
		<div class="container">
			<div class="section-heading animate-fade-up" style="text-align:left;">
				<h2 class="section-title" style="font-size:clamp(1.5rem,3vw,2rem);"><?php esc_html_e( 'Kontakt na organizátora', 'cpnrp' ); ?></h2>
				<div class="section-title-bar" style="margin-left:0;"></div>
			</div>
			<div class="ben-contact-card">
				<?php if ( $contact_name ) : ?>
				<p class="ben-contact-name"><?php echo esc_html( $contact_name ); ?></p>
				<?php endif; ?>
				<?php if ( $contact_role ) : ?>
				<p class="ben-contact-role"><?php echo esc_html( $contact_role ); ?></p>
				<?php endif; ?>
				<?php if ( ( $contact_name || $contact_role ) && ( $contact_email || $contact_phone ) ) : ?>
				<hr class="ben-contact-divider">
				<?php endif; ?>
				<?php if ( $contact_email ) : ?>
				<div class="ben-contact-row">
					<svg class="ben-contact-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
						<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
					</svg>
					<a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
				</div>
				<?php endif; ?>
				<?php if ( $contact_phone ) : ?>
				<div class="ben-contact-row">
					<svg class="ben-contact-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true">
						<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 12 19.79 19.79 0 01.4 3.37 2 2 0 012.38 1h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
					</svg>
					<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $contact_phone ) ); ?>"><?php echo esc_html( $contact_phone ); ?></a>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── ODKAZ NA WEB ─────────────────────────────────────────── -->
	<section class="ben-web-cta">
		<div class="container">
			<div class="ben-web-cta-inner">
				<p class="ben-web-cta-label"><?php esc_html_e( 'Více informací', 'cpnrp' ); ?></p>
				<p class="ben-web-cta-text">
					<?php esc_html_e( 'Informace o konkrétních představeních a prodeji vstupenek najdete zde', 'cpnrp' ); ?>
				</p>
				<a href="<?php echo esc_url( $web_url ); ?>" class="beh-btn beh-btn--gold" target="_blank" rel="noopener noreferrer">
					www.divadelni-benefice.cz
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
				</a>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
