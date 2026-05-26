<?php
/**
 * Gallery archive — redirects to the galerie archive.
 */
$galerie_url = get_post_type_archive_link( 'galerie' ) ?: home_url( '/galerie/' );
wp_redirect( $galerie_url, 301 );
exit;

$albums = get_posts( [
	'post_type'      => 'gallery',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
	'meta_query'     => [
		'relation' => 'OR',
		[ 'key' => '_gallery_year', 'compare' => 'EXISTS' ],
		[ 'key' => '_gallery_year', 'compare' => 'NOT EXISTS' ],
	],
] );

// Collect available years for filter
$years = [];
foreach ( $albums as $album ) {
	$y = get_post_meta( $album->ID, '_gallery_year', true );
	if ( $y && is_numeric( $y ) ) $years[ (int)$y ] = true;
}
krsort( $years );
$years = array_keys( $years );

get_header();
?>

<main id="main-content" role="main">

<!-- ══ Hero ═════════════════════════════════════════════════════ -->
<section class="events-page-hero">
	<div class="container">
		<nav class="events-page-hero-breadcrumbs" aria-label="<?php esc_attr_e( 'Navigace', 'cpnrp' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
			<span class="sep" aria-hidden="true">/</span>
			<span class="current"><?php esc_html_e( 'Fotogalerie', 'cpnrp' ); ?></span>
		</nav>
		<h1><?php esc_html_e( 'Fotogalerie', 'cpnrp' ); ?></h1>
		<p><?php esc_html_e( 'Nahlédněte do našich akcí, táborů a setkání. Fotografie zachycují radost a společné chvíle našich rodin.', 'cpnrp' ); ?></p>
	</div>
</section>

<!-- ══ Album grid ════════════════════════════════════════════════ -->
<section class="gallery-archive-section">
	<div class="container">

		<?php if ( $albums ) : ?>

		<?php if ( count( $years ) > 1 ) : ?>
		<!-- Year filter -->
		<div class="gallery-year-filter" id="gallery-year-filter" role="group" aria-label="<?php esc_attr_e( 'Filtr podle roku', 'cpnrp' ); ?>">
			<button class="gallery-year-btn is-active" data-year="all" type="button"><?php esc_html_e( 'Vše', 'cpnrp' ); ?></button>
			<?php foreach ( $years as $y ) : ?>
			<button class="gallery-year-btn" data-year="<?php echo esc_attr( $y ); ?>" type="button"><?php echo esc_html( $y ); ?></button>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<div class="gallery-archive-grid" id="gallery-archive-grid">
			<?php foreach ( $albums as $album ) :
				$ids        = json_decode( get_post_meta( $album->ID, '_gallery_photos', true ) ?: '[]', true );
				$count      = is_array( $ids ) ? count( $ids ) : 0;
				$date_label = get_post_meta( $album->ID, '_gallery_date', true );
				$year       = get_post_meta( $album->ID, '_gallery_year', true ) ?: '';
			?>
			<a href="<?php echo esc_url( get_permalink( $album->ID ) ); ?>"
			   class="gallery-album-card"
			   data-year="<?php echo esc_attr( $year ); ?>">
				<div class="gallery-album-thumb">
					<?php if ( has_post_thumbnail( $album->ID ) ) :
						echo get_the_post_thumbnail( $album->ID, 'medium_large', [ 'loading' => 'lazy', 'alt' => esc_attr( $album->post_title ) ] );
					else : ?>
						<div class="gallery-album-placeholder">
							<svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" aria-hidden="true">
								<rect x="3" y="3" width="18" height="18" rx="2"/>
								<circle cx="8.5" cy="8.5" r="1.5"/>
								<polyline points="21 15 16 10 5 21"/>
							</svg>
						</div>
					<?php endif; ?>
				</div>
				<div class="gallery-album-info">
					<h3><?php echo esc_html( $album->post_title ); ?></h3>
					<p>
						<?php if ( $count ) : ?>
							<span><?php echo $count; ?> <?php echo $count === 1 ? 'fotografie' : ( $count < 5 ? 'fotografie' : 'fotografií' ); ?></span><?php if ( $date_label ) : ?> &middot; <?php endif; ?>
						<?php endif; ?>
						<?php if ( $date_label ) : ?><span><?php echo esc_html( $date_label ); ?></span><?php endif; ?>
					</p>
				</div>
			</a>
			<?php endforeach; ?>
		</div>

		<script>
		(function () {
			var btns  = document.querySelectorAll('.gallery-year-btn');
			var cards = document.querySelectorAll('.gallery-album-card[data-year]');

			btns.forEach(function (btn) {
				btn.addEventListener('click', function () {
					var year = this.dataset.year;

					btns.forEach(function (b) { b.classList.remove('is-active'); });
					this.classList.add('is-active');

					cards.forEach(function (card) {
						if (year === 'all' || card.dataset.year === year) {
							card.style.display = '';
						} else {
							card.style.display = 'none';
						}
					});
				});
			});
		})();
		</script>

		<?php else : ?>
		<div class="gallery-empty">
			<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
				<rect x="3" y="3" width="18" height="18" rx="2"/>
				<circle cx="8.5" cy="8.5" r="1.5"/>
				<polyline points="21 15 16 10 5 21"/>
			</svg>
			<h2><?php esc_html_e( 'Galerie se připravuje', 'cpnrp' ); ?></h2>
			<p><?php esc_html_e( 'Brzy zde naleznete fotografie z našich akcí a setkání.', 'cpnrp' ); ?></p>
		</div>
		<?php endif; ?>

	</div>
</section>

</main>

<?php get_footer(); ?>
