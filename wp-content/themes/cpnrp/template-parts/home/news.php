<?php
/**
 * Homepage — news / novinky section.
 * Pulls latest 3 posts; first is highlighted as "Doporučujeme".
 */

$pribehy = get_page_by_path( 'pribehy' );
$url_all = $pribehy ? get_permalink( $pribehy ) : home_url( '/pribehy' );

$news_query = new WP_Query( [
	'post_type'      => 'post',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
	'no_found_rows'  => true,
] );

if ( ! $news_query->have_posts() ) {
	return;
}
?>

<section id="novinky" class="home-news" aria-label="<?php esc_attr_e( 'Novinky a příběhy', 'cpnrp' ); ?>">
	<div class="container">

		<div class="section-heading animate-fade-up">
			<h2 class="section-title">Novinky a příběhy</h2>
			<div class="section-title-bar" aria-hidden="true"></div>
			<p class="section-subtitle">Co je u nás nového</p>
		</div>

		<div class="news-grid">
			<?php
			$is_first  = true;
			$card_idx  = 0;
			$delays    = [ '', ' delay-2', ' delay-3' ];
			while ( $news_query->have_posts() ) :
				$news_query->the_post();
				$categories = get_the_category();
				$cat_name   = $categories ? esc_html( $categories[0]->name ) : '';
				$thumb_url  = get_the_post_thumbnail_url( null, 'medium_large' );
				$delay_cls  = $delays[ $card_idx ] ?? '';
			?>
			<a href="<?php the_permalink(); ?>"
			   class="news-card animate-fade-up<?php echo $delay_cls; ?><?php echo $is_first ? ' news-card--featured' : ''; ?>">

				<?php if ( $is_first ) : ?>
				<span class="news-card-badge">Doporučujeme</span>
				<?php endif; ?>

				<div class="news-card-image">
					<?php if ( $thumb_url ) : ?>
					<img src="<?php echo esc_url( $thumb_url ); ?>"
					     alt="<?php echo esc_attr( get_the_title() ); ?>"
					     loading="lazy">
					<?php else : ?>
					<div class="news-card-image-placeholder"></div>
					<?php endif; ?>
				</div>

				<div class="news-card-body">
					<div class="news-card-meta">
						<?php if ( $cat_name ) : ?>
						<span class="news-card-category"><?php echo $cat_name; ?></span>
						<?php endif; ?>
						<span class="news-card-date"><?php echo get_the_date( 'j. n. Y' ); ?></span>
					</div>
					<h3 class="news-card-title"><?php the_title(); ?></h3>
					<p class="news-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '…' ) ); ?></p>
					<span class="news-card-link">
						Číst dále
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
							<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
						</svg>
					</span>
				</div>

			</a>
			<?php $is_first = false; $card_idx++; endwhile; wp_reset_postdata(); ?>
		</div>

		<div class="news-cta">
			<a href="<?php echo esc_url( $url_all ); ?>" class="btn-outline-teal">
				Všechny články
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
				</svg>
			</a>
		</div>

	</div>
</section>
