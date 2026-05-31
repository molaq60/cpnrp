<?php
/**
 * Template Name: Výroční zprávy
 * Annual-report download list — entries managed from admin meta box.
 */

get_header();

$page_id   = get_the_ID();
$parent_id = wp_get_post_parent_id( $page_id );
$ancestors = array_reverse( get_post_ancestors( $page_id ) );
$hero_desc = get_post_meta( $page_id, '_subpage_hero_desc', true )
	?: 'Výroční zprávy CPNRP ke stažení ve formátu PDF.';

$items = get_post_meta( $page_id, '_vyrocni_zpravy', true );
if ( ! is_array( $items ) ) {
	$items = [];
}

$dl_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true" width="14" height="14"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
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

	<!-- ── Download list ─────────────────────────────────────────── -->
	<section class="subpage-section">
		<div class="container">

			<?php if ( $items ) : ?>
			<ul class="download-list">
				<?php foreach ( $items as $item ) :
					$year  = esc_html( $item['year'] ?? '' );
					$url   = esc_url( $item['url'] ?? '' );
					$label = esc_html( $item['label'] ?? '' ) ?: 'Výroční zpráva CPNRP ' . $year;
					$thumb = esc_url( $item['thumb'] ?? '' );
				?>
				<li class="download-item">
					<?php if ( $thumb ) : ?>
					<div class="download-thumb">
						<img src="<?php echo $thumb; ?>" alt="<?php echo $label; ?>" loading="lazy">
					</div>
					<?php endif; ?>
					<div class="download-info">
						<span class="download-badge"><?php echo $year; ?></span>
						<div>
							<strong class="download-title"><?php echo $label; ?></strong>
							<span class="download-subtitle">PDF</span>
						</div>
					</div>
					<?php if ( $url ) : ?>
					<a href="<?php echo $url; ?>" class="btn-download" target="_blank" rel="noopener noreferrer">
						Stáhnout <?php echo $dl_svg; ?>
					</a>
					<?php else : ?>
					<span class="btn-download" style="opacity:.4;cursor:default;pointer-events:none" aria-disabled="true">
						Brzy k dispozici <?php echo $dl_svg; ?>
					</span>
					<?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<?php else : ?>
			<p style="color:var(--color-text-muted);padding:2rem 0">Výroční zprávy budou brzy k dispozici.</p>
			<?php endif; ?>

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
