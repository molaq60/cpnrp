<?php
/**
 * Template Name: Podpořte nás
 */

get_header();

$donate_url = get_theme_mod( 'cpnrp_darovat_url', 'https://www.donio.cz' );
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<section class="podp-hero">
		<div class="container">
			<nav class="podp-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
				<span>/</span>
				<span><?php esc_html_e( 'Podpořte nás', 'cpnrp' ); ?></span>
			</nav>
			<h1 class="podp-hero-title"><?php esc_html_e( 'Podpořte nás', 'cpnrp' ); ?></h1>
			<p class="podp-hero-lead"><?php esc_html_e( 'Vaše podpora mění životy. Díky dárcům můžeme pomáhat stovkám rodin po celé České republice.', 'cpnrp' ); ?></p>
		</div>
	</section>

	<!-- ── Tři způsoby podpory ───────────────────────────────────── -->
	<section class="podp-ways">
		<div class="container">

			<div class="section-heading animate-fade-up">
				<h2 class="section-title"><?php esc_html_e( 'Jak nás můžete podpořit', 'cpnrp' ); ?></h2>
				<div class="section-title-bar"></div>
				<p class="section-subtitle"><?php esc_html_e( 'Vyberte si způsob, jakým chcete pomoci', 'cpnrp' ); ?></p>
			</div>

			<div class="podp-ways-grid">

				<!-- Finanční dar -->
				<div class="podp-card podp-card--red">
					<div class="podp-card-icon podp-card-icon--red">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
						</svg>
					</div>
					<h3 class="podp-card-title"><?php esc_html_e( 'Finanční dar', 'cpnrp' ); ?></h3>
					<p class="podp-card-desc"><?php esc_html_e( 'Jednorázový nebo pravidelný finanční příspěvek na podporu našich služeb.', 'cpnrp' ); ?></p>
					<a href="<?php echo esc_url( $donate_url ); ?>" class="podp-btn podp-btn--red" target="_blank" rel="noopener">
						<?php esc_html_e( 'Darovat online', 'cpnrp' ); ?>
					</a>
					<p class="podp-card-note">
						<?php esc_html_e( 'Bankovní účet:', 'cpnrp' ); ?>
						<strong>35–9706800297/0100</strong>
					</p>
				</div>

				<!-- Pravidelný příspěvek -->
				<div class="podp-card" id="pravidelny-dar">
					<div class="podp-card-icon podp-card-icon--gold">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M12 2L15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2z"/>
						</svg>
					</div>
					<h3 class="podp-card-title"><?php esc_html_e( 'Pravidelný příspěvek', 'cpnrp' ); ?></h3>
					<p class="podp-card-desc"><?php esc_html_e( 'Staňte se pravidelným dárcem a pomozte nám plánovat dlouhodobé projekty.', 'cpnrp' ); ?></p>
					<a href="<?php echo esc_url( $donate_url ); ?>" class="podp-btn podp-btn--teal" target="_blank" rel="noopener">
						<?php esc_html_e( 'Nastavit trvalý příkaz', 'cpnrp' ); ?>
					</a>
					<p class="podp-card-note">
						<?php esc_html_e( 'Již od', 'cpnrp' ); ?>
						<strong>100 Kč <?php esc_html_e( 'měsíčně', 'cpnrp' ); ?></strong>
					</p>
				</div>

				<!-- Dobrovolnictví -->
				<div class="podp-card">
					<div class="podp-card-icon podp-card-icon--teal">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
							<circle cx="9" cy="7" r="4"/>
							<path d="M23 21v-2a4 4 0 00-3-3.87"/>
							<path d="M16 3.13a4 4 0 010 7.75"/>
						</svg>
					</div>
					<h3 class="podp-card-title"><?php esc_html_e( 'Dobrovolnictví', 'cpnrp' ); ?></h3>
					<p class="podp-card-desc"><?php esc_html_e( 'Zapojte se jako dobrovolník na akcích, táborech nebo v kanceláři.', 'cpnrp' ); ?></p>
					<a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>" class="podp-btn podp-btn--outline">
						<?php esc_html_e( 'Kontaktujte nás', 'cpnrp' ); ?>
					</a>
					<p class="podp-card-note">
						<?php esc_html_e( 'Hledáme pomoc s', 'cpnrp' ); ?>
						<strong><?php esc_html_e( 'akcemi i administrativou', 'cpnrp' ); ?></strong>
					</p>
				</div>

			</div>
		</div>
	</section>

	<!-- ── Naši dárci ────────────────────────────────────────────── -->
	<section class="podp-donors">
		<div class="container">
			<div class="podp-donors-inner">
				<div class="section-heading animate-fade-up">
					<h2 class="section-title"><?php esc_html_e( 'Naši dárci', 'cpnrp' ); ?></h2>
					<div class="section-title-bar"></div>
					<p class="section-subtitle"><?php esc_html_e( 'Děkujeme všem, kteří nás podporují', 'cpnrp' ); ?></p>
				</div>
				<p class="podp-donors-text">
					<?php esc_html_e( 'Seznam dárců za rok 2025 a předchozí roky naleznete v našich', 'cpnrp' ); ?>
					<a href="<?php echo esc_url( home_url( '/o-nas' ) ); ?>#vyrocni-zpravy">
						<?php esc_html_e( 'výročních zprávách', 'cpnrp' ); ?>
					</a>.
				</p>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
