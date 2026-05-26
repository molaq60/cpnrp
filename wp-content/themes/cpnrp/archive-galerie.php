<?php
/**
 * Fotogalerie archive — year pill bar + card grid.
 */

$year_terms = get_terms( [
	'taxonomy'   => 'album',
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'DESC',
] );
if ( is_wp_error( $year_terms ) ) $year_terms = [];

$active_year = isset( $_GET['rok'] ) ? sanitize_text_field( $_GET['rok'] ) : 'all';

$query_args = [
	'post_type'      => 'galerie',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
];
if ( $active_year !== 'all' ) {
	$query_args['tax_query'] = [ [
		'taxonomy' => 'album',
		'field'    => 'slug',
		'terms'    => $active_year,
	] ];
}
$albums = get_posts( $query_args );

$archive_url = get_post_type_archive_link( 'galerie' ) ?: home_url( '/galerie/' );

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

<!-- ══ Archive ═══════════════════════════════════════════════════ -->
<section class="galerie-section">
	<div class="container">

		<?php if ( $year_terms ) : ?>
		<!-- Year pill bar -->
		<div class="galerie-year-bar" role="navigation" aria-label="<?php esc_attr_e( 'Filtr podle roku', 'cpnrp' ); ?>">
			<a href="<?php echo esc_url( $archive_url ); ?>"
			   class="galerie-year-pill<?php echo ( $active_year === 'all' ) ? ' is-active' : ''; ?>">
				<?php esc_html_e( 'Vše', 'cpnrp' ); ?>
			</a>
			<?php foreach ( $year_terms as $term ) : ?>
			<a href="<?php echo esc_url( add_query_arg( 'rok', $term->slug, $archive_url ) ); ?>"
			   class="galerie-year-pill<?php echo ( $active_year === $term->slug ) ? ' is-active' : ''; ?>">
				<?php echo esc_html( $term->name ); ?>
			</a>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<!-- Card grid -->
		<div class="galerie-grid" id="galerie-grid">
			<?php if ( $albums ) : ?>
				<?php foreach ( $albums as $album ) :

					$cover_url = '';
					if ( has_post_thumbnail( $album->ID ) ) {
						$cover_url = get_the_post_thumbnail_url( $album->ID, 'medium_large' );
					}
					if ( ! $cover_url ) {
						$photos_raw = get_post_meta( $album->ID, '_gallery_photos', true );
						$photo_ids  = json_decode( $photos_raw ?: '[]', true );
						if ( empty( $photo_ids ) ) {
							preg_match( '/ids="([0-9,]+)"/', $album->post_content, $m );
							if ( ! empty( $m[1] ) ) {
								$photo_ids = array_filter( array_map( 'intval', explode( ',', $m[1] ) ) );
							}
						}
						if ( ! empty( $photo_ids ) ) {
							$first_id  = reset( $photo_ids );
							$cover_url = wp_get_attachment_image_url( $first_id, 'medium_large' );
						}
					}

					$photos_raw  = get_post_meta( $album->ID, '_gallery_photos', true );
					$photo_ids   = json_decode( $photos_raw ?: '[]', true );
					if ( ! is_array( $photo_ids ) || empty( $photo_ids ) ) {
						preg_match( '/ids="([0-9,]+)"/', $album->post_content, $m );
						$photo_count = ! empty( $m[1] ) ? count( explode( ',', $m[1] ) ) : 0;
					} else {
						$photo_count = count( $photo_ids );
					}

					$year_term = wp_get_post_terms( $album->ID, 'album' );
					$year      = $year_term && ! is_wp_error( $year_term ) ? $year_term[0]->name : '';
				?>
				<a href="<?php echo esc_url( get_permalink( $album->ID ) ); ?>"
				   class="galerie-card">
					<div class="galerie-card-thumb">
						<?php if ( $cover_url ) : ?>
							<img src="<?php echo esc_url( $cover_url ); ?>"
							     alt="<?php echo esc_attr( $album->post_title ); ?>"
							     loading="lazy">
						<?php else : ?>
							<div class="galerie-card-placeholder">
								<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" aria-hidden="true">
									<rect x="3" y="3" width="18" height="18" rx="2"/>
									<circle cx="8.5" cy="8.5" r="1.5"/>
									<polyline points="21 15 16 10 5 21"/>
								</svg>
							</div>
						<?php endif; ?>
						<?php if ( $year ) : ?>
							<span class="galerie-card-year"><?php echo esc_html( $year ); ?></span>
						<?php endif; ?>
					</div>
					<div class="galerie-card-info">
						<span class="galerie-card-title"><?php echo esc_html( $album->post_title ); ?></span>
						<?php if ( $photo_count ) : ?>
							<span class="galerie-card-count">
								<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
									<rect x="3" y="3" width="18" height="18" rx="2"/>
									<circle cx="8.5" cy="8.5" r="1.5"/>
									<polyline points="21 15 16 10 5 21"/>
								</svg>
								<?php echo $photo_count; ?> foto
							</span>
						<?php endif; ?>
					</div>
				</a>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="gallery-empty" style="grid-column:1/-1">
					<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
						<rect x="3" y="3" width="18" height="18" rx="2"/>
						<circle cx="8.5" cy="8.5" r="1.5"/>
						<polyline points="21 15 16 10 5 21"/>
					</svg>
					<h2><?php esc_html_e( 'Žádná alba nenalezena', 'cpnrp' ); ?></h2>
				</div>
			<?php endif; ?>
		</div>

	</div>
</section>

</main>

<?php get_footer(); ?>
