<?php
/**
 * Single post template
 */

get_header();
?>

<main id="main-content" class="main-content" role="main">
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
			<header class="post-header section">
				<div class="container">
					<h1 class="post-title"><?php the_title(); ?></h1>
					<div class="post-meta">
						<?php
						printf(
							esc_html__( 'Posted on %s by %s in %s', 'cpnrp' ),
							'<time class="post-date" datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>',
							'<span class="post-author">' . esc_html( get_the_author() ) . '</span>',
							get_the_category_list( ', ' )
						);
						?>
					</div>
				</div>
			</header>

			<?php
			if ( has_post_thumbnail() ) {
				?>
				<div class="post-thumbnail section">
					<div class="container">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				</div>
				<?php
			}
			?>

			<div class="post-content section">
				<div class="container">
					<div class="post-text">
						<?php
						the_content();
						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cpnrp' ),
							'after'  => '</div>',
						) );
						?>
					</div>

					<?php
					// Tags
					if ( has_tag() ) {
						?>
						<footer class="post-footer">
							<div class="post-tags">
								<?php the_tags( '<span class="tag-label">' . esc_html__( 'Tags:', 'cpnrp' ) . '</span> ', ', ', '' ); ?>
							</div>
						</footer>
						<?php
					}
					?>
				</div>
			</div>
		</article>

		<?php
		// Post navigation
		the_post_navigation( array(
			'prev_text' => esc_html__( 'Previous: %title', 'cpnrp' ),
			'next_text' => esc_html__( 'Next: %title', 'cpnrp' ),
		) );

		// Comments section
		if ( comments_open() || get_comments_number() ) {
			?>
			<div class="comments-section section section--light">
				<div class="container">
					<?php comments_template(); ?>
				</div>
			</div>
			<?php
		}
	}
	?>
</main>

<?php
get_footer();
