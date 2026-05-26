<?php
/**
 * Page template
 */

get_header();
?>

<main id="main-content" class="main-content" role="main">
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
			<?php
			// Page hero section with featured image
			if ( has_post_thumbnail() ) {
				?>
				<div class="page-hero">
					<?php the_post_thumbnail( 'full' ); ?>
					<div class="page-hero-overlay">
						<div class="container">
							<h1 class="page-title"><?php the_title(); ?></h1>
						</div>
					</div>
				</div>
				<?php
			} else {
				?>
				<div class="page-header section">
					<div class="container">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
				</div>
				<?php
			}
			?>

			<div class="page-body section">
				<div class="container">
					<div class="page-content-inner">
						<?php
						the_content();
						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'cpnrp' ),
							'after'  => '</div>',
						) );
						?>
					</div>
				</div>
			</div>
		</article>

		<?php
		// Comments section (if enabled)
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
