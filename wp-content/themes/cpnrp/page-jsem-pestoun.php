<?php
/**
 * Template Name: Jsem pěstoun
 * Numbered service-card page for current foster parents.
 * Meta boxes: _jsem_pestoun_hero_desc / _eyebrow / _section_heading / _cta_heading / _cta_desc
 */

get_header();

$page_id  = get_the_ID();
$parent_id = wp_get_post_parent_id( $page_id );
$ancestors = array_reverse( get_post_ancestors( $page_id ) );

$hero_desc       = get_post_meta( $page_id, '_jsem_pestoun_hero_desc', true )
	?: 'Podporujeme vás na každém kroku vaší pěstounské cesty. Nabízíme komplexní služby pro celou rodinu.';
$eyebrow         = get_post_meta( $page_id, '_jsem_pestoun_eyebrow', true ) ?: 'Co pro vás máme';
$section_heading = get_post_meta( $page_id, '_jsem_pestoun_section_heading', true ) ?: 'Naše služby pro pěstouny';
$cta_heading     = get_post_meta( $page_id, '_jsem_pestoun_cta_heading', true ) ?: 'Máte zájem o naše služby?';
$cta_desc        = get_post_meta( $page_id, '_jsem_pestoun_cta_desc', true ) ?: 'Kontaktujte nás a domluvíme se na prvním setkání.';
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<section class="subpage-hero">
		<div class="container">
			<nav class="subpage-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
				<?php foreach ( $ancestors as $anc_id ) : ?>
					<span>/</span>
					<a href="<?php echo esc_url( get_permalink( $anc_id ) ); ?>"><?php echo esc_html( get_the_title( $anc_id ) ); ?></a>
				<?php endforeach; ?>
				<span>/</span>
				<span><?php the_title(); ?></span>
			</nav>
			<h1 class="subpage-hero-title"><?php the_title(); ?></h1>
			<p class="subpage-hero-desc"><?php echo esc_html( $hero_desc ); ?></p>
		</div>
	</section>

	<!-- ── Services ──────────────────────────────────────────────── -->
	<section class="jsem-pestoun-section">
		<div class="container">
			<div class="jsem-pestoun-wrap">
				<p class="jsem-pestoun-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<h2 class="jsem-pestoun-heading"><?php echo esc_html( $section_heading ); ?></h2>
				<div class="jsem-pestoun-services">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</section>

	<!-- ── CTA ───────────────────────────────────────────────────── -->
	<section class="jsem-pestoun-cta-section">
		<div class="container">
			<div class="jsem-pestoun-cta-inner">
				<h2 class="jsem-pestoun-cta-heading"><?php echo esc_html( $cta_heading ); ?></h2>
				<p class="jsem-pestoun-cta-desc"><?php echo esc_html( $cta_desc ); ?></p>
				<div class="jsem-pestoun-cta-btns">
					<a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>" class="btn-cta-primary">
						<?php esc_html_e( 'Kontaktujte nás', 'cpnrp' ); ?>
					</a>
					<a href="tel:+420731557681" class="jsem-pestoun-tel">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
							<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
						</svg>
						+420 731 557 681
					</a>
				</div>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
