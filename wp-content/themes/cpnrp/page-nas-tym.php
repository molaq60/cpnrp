<?php
/**
 * Template Name: Náš tým
 * Editorial page — content managed via WP editor.
 */

get_header();

$page_id   = get_the_ID();
$parent_id = wp_get_post_parent_id( $page_id );
$ancestors = array_reverse( get_post_ancestors( $page_id ) );
$hero_desc = get_post_meta( $page_id, '_nas_tym_hero_desc', true )
	?: 'Náš tým tvoří zkušení sociální pracovníci, psychologové a odborníci, kteří sdílejí společné přesvědčení — každé dítě si zaslouží domov.';
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<section class="subpage-hero">
		<div class="container">
			<nav class="subpage-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Úvod</a>
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

	<!-- ── Content ───────────────────────────────────────────────── -->
	<section class="subpage-section">
		<div class="container">
			<div class="subpage-content">
				<?php the_content(); ?>
			</div>

			<div class="subpage-cta-row">
				<?php if ( $parent_id ) : ?>
				<a href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>" class="btn-subpage-back">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
						<line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
					</svg>
					<?php printf( esc_html__( 'Zpět na %s', 'cpnrp' ), esc_html( get_the_title( $parent_id ) ) ); ?>
				</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>" class="btn-subpage-outline">
					Kontaktujte nás
				</a>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
