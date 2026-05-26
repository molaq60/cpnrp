<?php
/**
 * Search results — uses the same pribehy card design.
 */

get_header();
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<div class="pribehy-hero">
		<div class="container">
			<span class="pribehy-eyebrow"><?php esc_html_e( 'Výsledky hledání', 'cpnrp' ); ?></span>
			<h1 class="pribehy-title">
				<?php
				printf(
					/* translators: %s search term */
					esc_html__( '„%s"', 'cpnrp' ),
					get_search_query()
				);
				?>
			</h1>
			<p class="pribehy-subtitle">
				<?php
				$found = $wp_query->found_posts;
				printf(
					esc_html( _n( 'Nalezen %d výsledek', 'Nalezeno %d výsledků', $found, 'cpnrp' ) ),
					$found
				);
				?>
			</p>
		</div>
	</div>

	<!-- ── Posts grid ────────────────────────────────────────────── -->
	<div class="pribehy-archive">
		<div class="container">

			<?php get_template_part( 'template-parts/posts/filter-bar' ); ?>

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

						<div class="pribehy-card-body">
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
						<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
					</svg>
					<h2>
						<?php
						printf(
							esc_html__( 'Žádné výsledky pro „%s"', 'cpnrp' ),
							get_search_query()
						);
						?>
					</h2>
					<p><?php esc_html_e( 'Zkuste upravit klíčová slova nebo prohlédněte všechny příběhy.', 'cpnrp' ); ?></p>
					<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/pribehy/' ) ); ?>" class="btn btn--teal" style="margin-top:24px">
						<?php esc_html_e( 'Všechny příběhy', 'cpnrp' ); ?>
					</a>
				</div>

			<?php endif; ?>

		</div>
	</div>

</main>

<?php get_footer(); ?>
