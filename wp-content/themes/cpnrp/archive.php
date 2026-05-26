<?php
/**
 * Category / tag / date archive — uses the same pribehy card design.
 * Events are handled by archive-tribe_events.php via WP template hierarchy.
 */

get_header();
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<div class="pribehy-hero">
		<div class="container">
			<span class="pribehy-eyebrow"><?php esc_html_e( 'Naše práce', 'cpnrp' ); ?></span>
			<?php the_archive_title( '<h1 class="pribehy-title">', '</h1>' ); ?>
			<?php the_archive_description( '<p class="pribehy-subtitle">', '</p>' ); ?>
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
						<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
					</svg>
					<h2><?php esc_html_e( 'Žádné příspěvky', 'cpnrp' ); ?></h2>
					<p><?php esc_html_e( 'V této kategorii zatím nejsou žádné příspěvky.', 'cpnrp' ); ?></p>
				</div>

			<?php endif; ?>

		</div>
	</div>

</main>

<?php get_footer(); ?>
