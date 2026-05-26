<?php
/**
 * Blog posts index — shown at /pribehy/
 * WordPress uses home.php when a static Posts Page is configured.
 */

get_header();
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<div class="pribehy-hero">
		<div class="container">
			<span class="pribehy-eyebrow"><?php esc_html_e( 'Naše práce', 'cpnrp' ); ?></span>
			<h1 class="pribehy-title"><?php esc_html_e( 'Příběhy', 'cpnrp' ); ?></h1>
			<p class="pribehy-subtitle">
				<?php esc_html_e( 'Skutečné příběhy rodin, které prošly náhradní rodinnou péčí. Každý je jiný, ale všechny spojuje láska.', 'cpnrp' ); ?>
			</p>
		</div>
	</div>

	<!-- ── Posts grid ────────────────────────────────────────────── -->
	<div class="pribehy-archive">
		<div class="container">

			<?php get_template_part( 'template-parts/posts/filter-bar' ); ?>

			<?php
			$search_q = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
			$active_c = isset( $_GET['cat'] ) ? intval( $_GET['cat'] ) : 0;
			$page_url = get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/pribehy/' );

			if ( $search_q || $active_c ) :
				$found = $wp_query->found_posts;
				?>
				<div class="pribehy-results-header">
					<span>
						<?php
						if ( $search_q ) {
							$label = sprintf(
								_n( 'Nalezen %2$d výsledek pro „%1$s"', 'Nalezeno %2$d výsledků pro „%1$s"', $found, 'cpnrp' ),
								'<strong>' . esc_html( $search_q ) . '</strong>',
								$found
							);
							echo wp_kses( $label, [ 'strong' => [] ] );
						} else {
							$cat_obj = get_category( $active_c );
							if ( $cat_obj ) {
								$label = sprintf(
									_n( '%2$d příspěvek v kategorii „%1$s"', '%2$d příspěvků v kategorii „%1$s"', $found, 'cpnrp' ),
									'<strong>' . esc_html( $cat_obj->name ) . '</strong>',
									$found
								);
								echo wp_kses( $label, [ 'strong' => [] ] );
							}
						}
						?>
					</span>
					<a class="pribehy-results-reset" href="<?php echo esc_url( $page_url ); ?>">
						<?php esc_html_e( '× Zobrazit vše', 'cpnrp' ); ?>
					</a>
				</div>
			<?php endif; ?>

			<?php if ( have_posts() ) : ?>

				<div class="pribehy-grid">
					<?php while ( have_posts() ) : the_post();
						$is_featured = is_sticky();
						$cats        = get_the_category();
						$tags        = get_the_tags();
						$badge_label = $is_featured
							? __( 'Doporučujeme', 'cpnrp' )
							: ( $tags ? $tags[0]->name : '' );
					?>

					<article <?php post_class( 'pribehy-card' . ( $is_featured ? ' pribehy-card--featured' : '' ) ); ?>>

						<!-- Image -->
						<a href="<?php the_permalink(); ?>" class="pribehy-card-image-wrap" tabindex="-1" aria-hidden="true">
							<?php if ( $badge_label ) : ?>
								<span class="pribehy-card-badge"><?php echo esc_html( $badge_label ); ?></span>
							<?php endif; ?>
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large', [ 'class' => 'pribehy-card-image' ] ); ?>
							<?php else : ?>
								<div class="pribehy-card-placeholder"></div>
							<?php endif; ?>
						</a>

						<!-- Body -->
						<div class="pribehy-card-body">

							<!-- Category + date row -->
							<div class="pribehy-card-meta">
								<?php if ( $cats ) : ?>
									<span class="pribehy-card-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
								<?php endif; ?>
								<time class="pribehy-card-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
							</div>

							<h2 class="pribehy-card-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<p class="pribehy-card-excerpt"><?php the_excerpt(); ?></p>

							<a href="<?php the_permalink(); ?>" class="pribehy-card-link">
								<?php esc_html_e( 'Číst dále', 'cpnrp' ); ?>
								<svg class="pribehy-card-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
									<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
								</svg>
							</a>

						</div>
					</article>

					<?php endwhile; ?>
				</div>

				<!-- Pagination -->
				<div class="pribehy-pagination">
					<?php
					the_posts_pagination( [
						'mid_size'  => 2,
						'prev_text' => '&larr; ' . __( 'Starší', 'cpnrp' ),
						'next_text' => __( 'Novější', 'cpnrp' ) . ' &rarr;',
					] );
					?>
				</div>

			<?php else : ?>

				<div class="pribehy-empty">
					<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true" style="color:var(--color-border);margin-bottom:16px">
						<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
					</svg>
					<h2><?php esc_html_e( 'Zatím žádné příběhy', 'cpnrp' ); ?></h2>
					<p><?php esc_html_e( 'Brzy zde přidáme příběhy rodin z Ústeckého kraje.', 'cpnrp' ); ?></p>
				</div>

			<?php endif; ?>

		</div>
	</div>

</main>

<?php get_footer(); ?>
