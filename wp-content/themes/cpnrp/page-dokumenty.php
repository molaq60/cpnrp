<?php
/**
 * Template Name: Dokumenty ke stažení
 * Two-section download page — entries managed from admin meta box.
 */

get_header();

$page_id   = get_the_ID();
$parent_id = wp_get_post_parent_id( $page_id );
$ancestors = array_reverse( get_post_ancestors( $page_id ) );
$hero_desc = get_post_meta( $page_id, '_subpage_hero_desc', true )
	?: 'Ke stažení — formuláře, žádosti a informační příručky.';

$formulare = get_post_meta( $page_id, '_dokumenty_formulare', true );
$prirucky  = get_post_meta( $page_id, '_dokumenty_prirucky',  true );
if ( ! is_array( $formulare ) ) $formulare = [];
if ( ! is_array( $prirucky  ) ) $prirucky  = [];

$dl_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true" width="14" height="14"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';

function cpnrp_doc_list( $items, $dl_svg ) {
	if ( ! $items ) {
		echo '<p style="color:var(--color-text-muted)">Dokumenty budou brzy k dispozici.</p>';
		return;
	}
	echo '<ul class="download-list">';
	foreach ( $items as $item ) {
		$name = esc_html( $item['name'] ?? '' );
		$type = esc_html( $item['type'] ?? 'PDF' );
		$url  = esc_url( $item['url']  ?? '' );
		echo '<li class="download-item">';
		echo '<div class="download-info">';
		echo '<span class="download-badge">' . $type . '</span>';
		echo '<div><strong class="download-title">' . $name . '</strong></div>';
		echo '</div>';
		if ( $url ) {
			echo '<a href="' . $url . '" class="btn-download" target="_blank" rel="noopener noreferrer">Stáhnout ' . $dl_svg . '</a>';
		} else {
			echo '<span class="btn-download" style="opacity:.4;cursor:default;pointer-events:none" aria-disabled="true">Brzy k dispozici ' . $dl_svg . '</span>';
		}
		echo '</li>';
	}
	echo '</ul>';
}
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

	<!-- ── Document sections ─────────────────────────────────────── -->
	<section class="subpage-section">
		<div class="container">

			<p class="hub-eyebrow">Formuláře</p>
			<h2 class="hub-section-title" style="margin-bottom:1.5rem">Formuláře a žádosti</h2>
			<?php cpnrp_doc_list( $formulare, $dl_svg ); ?>

			<p class="hub-eyebrow" style="margin-top:3rem">Informační materiály</p>
			<h2 class="hub-section-title" style="margin-bottom:1.5rem">Příručky a brožury</h2>
			<?php cpnrp_doc_list( $prirucky, $dl_svg ); ?>

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
