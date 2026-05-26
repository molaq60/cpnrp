<?php
/**
 * Template Name: Slovo ředitelky
 * Two-column editorial: director photo on left, letter text (post_content) on right.
 */

get_header();

$page_id   = get_the_ID();
$parent_id = wp_get_post_parent_id( $page_id );
$ancestors = array_reverse( get_post_ancestors( $page_id ) );

$hero_desc = get_post_meta( $page_id, '_subpage_hero_desc', true );
$jmeno     = get_post_meta( $page_id, '_slovo_jmeno', true ) ?: 'Mgr. Jana Rychnovská';
$titul     = get_post_meta( $page_id, '_slovo_titul', true ) ?: 'Ředitelka CPNRP';
$photo     = get_post_meta( $page_id, '_slovo_photo', true ) ?: 'rychnovska.jpeg';

$photo_url = get_template_directory_uri() . '/assets/images/team/' . $photo;
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
			<?php if ( $hero_desc ) : ?>
			<p class="subpage-hero-desc"><?php echo esc_html( $hero_desc ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- ── Letter ────────────────────────────────────────────────── -->
	<section class="subpage-section">
		<div class="container">
			<div class="slovo-reditelky-wrap">

				<div class="slovo-reditelky-photo-col">
					<?php if ( $photo && file_exists( get_template_directory() . '/assets/images/team/' . $photo ) ) : ?>
					<img
						class="slovo-reditelky-photo"
						src="<?php echo esc_url( $photo_url ); ?>"
						alt="<?php echo esc_attr( $jmeno ); ?>"
						loading="lazy"
					>
					<?php else : ?>
					<div class="slovo-reditelky-photo slovo-reditelky-photo--placeholder">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" aria-hidden="true">
							<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
						</svg>
					</div>
					<?php endif; ?>
					<div class="slovo-reditelky-signature">
						<strong><?php echo esc_html( $jmeno ); ?></strong>
						<span><?php echo esc_html( $titul ); ?></span>
					</div>
				</div>

				<div class="slovo-reditelky-text">
					<?php the_content(); ?>
				</div>

			</div>

			<!-- CTA row -->
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
