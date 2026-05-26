<?php
/**
 * 404 error page template
 */

get_header();
?>

<main id="main-content" class="main-content" role="main">
	<div class="error-404 section">
		<div class="container text-center">
			<h1 class="error-title"><?php esc_html_e( '404', 'cpnrp' ); ?></h1>
			<h2 class="error-subtitle"><?php esc_html_e( 'Page Not Found', 'cpnrp' ); ?></h2>
			<p class="error-message"><?php esc_html_e( 'Sorry, the page you are looking for does not exist or has been moved.', 'cpnrp' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--teal"><?php esc_html_e( 'Back to Home', 'cpnrp' ); ?></a>
		</div>
	</div>
</main>

<?php
get_footer();
