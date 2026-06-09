<?php
/**
 * Homepage template — hero + page content
 */

get_header();
?>

<main id="main-content" class="main-content home-main" role="main">
<?php while ( have_posts() ) : the_post(); ?>

	<?php
	$hero_img    = get_the_post_thumbnail_url( null, 'large' );

	// Raw manual excerpt (no word-limit filter); fallback to full content stripped of tags
	$raw_excerpt = get_post_field( 'post_excerpt', get_the_ID() );
	$hero_desc   = $raw_excerpt
		? $raw_excerpt
		: wp_strip_all_tags( get_the_content() );

	// Alt text from media library, fallback to site name
	$thumb_id  = get_post_thumbnail_id();
	$hero_alt  = $thumb_id
		? trim( get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) )
		: '';
	if ( ! $hero_alt ) {
		$hero_alt = get_bloginfo( 'name' );
	}

	$pro_rodiny  = get_page_by_path( 'pro-rodiny' );
	$url_rodiny  = $pro_rodiny ? get_permalink( $pro_rodiny ) : home_url( '/pro-rodiny' );
	$url_podpora = get_theme_mod( 'cpnrp_cta_url', home_url( '/podporte-nas' ) );
	?>

	<section class="hero">
		<div class="container">
			<div class="hero-inner">

				<!-- Left: text + buttons -->
				<div class="hero-text">
					<h1 class="animate-fade-up hero-title">
						<?php the_title(); ?>
					</h1>

					<?php if ( $hero_desc ) : ?>
					<p class="animate-fade-up delay-2 hero-description">
						<?php echo esc_html( $hero_desc ); ?>
					</p>
					<?php endif; ?>

					<div class="animate-fade-up delay-3 hero-buttons">
						<a href="<?php echo esc_url( $url_rodiny ); ?>" class="btn-hero btn-hero--teal">
							Hledám informace o NRP
							<svg class="btn-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
								<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
							</svg>
						</a>
						<a href="#podporte-nas-cta" class="btn-hero btn-hero--red">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/heart-benefice.png' ); ?>"
								alt="" aria-hidden="true">
							Podpořit organizaci
						</a>
					</div>
				</div>

				<!-- Right: featured image, desktop only -->
				<?php if ( $hero_img ) : ?>
				<div class="hero-visual">
					<div class="hero-image-wrap">
						<img src="<?php echo esc_url( $hero_img ); ?>"
							alt="<?php echo esc_attr( $hero_alt ); ?>"
							class="hero-image"
							fetchpriority="high"
							loading="eager">
					</div>
				</div>
				<?php endif; ?>

			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home/stats' ); ?>
	<?php get_template_part( 'template-parts/home/entry-cards' ); ?>
	<?php get_template_part( 'template-parts/home/news' ); ?>
	<?php get_template_part( 'template-parts/home/services' ); ?>
	<?php get_template_part( 'template-parts/home/donate' ); ?>
	<?php get_template_part( 'template-parts/home/partners' ); ?>

<?php endwhile; ?>
</main>

<?php get_footer(); ?>
