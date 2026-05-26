<?php
/**
 * Homepage — partners marquee.
 * Partners managed via Partneři CPT (logo = featured image, URL = meta).
 */

$partners_query = new WP_Query( [
	'post_type'      => 'partner',
	'post_status'    => 'publish',
	'posts_per_page' => -1,
	'orderby'        => 'date',
	'order'          => 'ASC',
	'no_found_rows'  => true,
] );

if ( ! $partners_query->have_posts() ) {
	return;
}

$partners = [];
while ( $partners_query->have_posts() ) {
	$partners_query->the_post();
	$partners[] = [
		'name'    => get_the_title(),
		'logo'    => get_the_post_thumbnail_url( null, 'medium' ),
		'url'     => get_post_meta( get_the_ID(), '_partner_url', true ),
	];
}
wp_reset_postdata();
?>

<section class="home-partners" aria-label="<?php esc_attr_e( 'Naši partneři', 'cpnrp' ); ?>">
	<div class="partners-top-line" aria-hidden="true"></div>

	<p class="partners-label">Děkujeme našim partnerům za podporu</p>

	<div class="partners-track-wrap">
		<div class="partners-fade partners-fade--left"  aria-hidden="true"></div>
		<div class="partners-fade partners-fade--right" aria-hidden="true"></div>

		<div class="partners-viewport">
			<?php foreach ( [ false, true ] as $is_duplicate ) : ?>
			<div class="partners-strip"<?php echo $is_duplicate ? ' aria-hidden="true"' : ''; ?>>
				<?php foreach ( $partners as $partner ) :
					$has_link = ! empty( $partner['url'] );
					$tag      = $has_link ? 'a' : 'div';
					$attrs    = $has_link
						? ' href="' . esc_url( $partner['url'] ) . '" target="_blank" rel="noopener noreferrer"'
						: '';
				?>
				<<?php echo $tag; ?> class="partner-logo-wrap"<?php echo $attrs; ?>>
					<?php if ( $partner['logo'] ) : ?>
					<img src="<?php echo esc_url( $partner['logo'] ); ?>"
					     alt="<?php echo esc_attr( $partner['name'] ); ?>"
					     loading="lazy">
					<?php endif; ?>
				</<?php echo $tag; ?>>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
