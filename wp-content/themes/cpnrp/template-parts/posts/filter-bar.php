<?php
/**
 * Search + category filter bar for /pribehy/
 */

$cats = get_categories( [ 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ] );

// Active category — supports ?cat=ID (WP default) or ?category_name=slug
$active_cat = 0;
if ( is_category() ) {
	$active_cat = get_queried_object_id();
} elseif ( isset( $_GET['cat'] ) && intval( $_GET['cat'] ) ) {
	$active_cat = intval( $_GET['cat'] );
}

$search_query = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

$archive_url = get_post_type_archive_link( 'post' );
if ( ! $archive_url ) {
	$page = get_option( 'page_for_posts' );
	$archive_url = $page ? get_permalink( $page ) : home_url( '/pribehy/' );
}
?>

<div class="pribehy-filters">

	<!-- Search -->
	<form class="pribehy-search-form" role="search" method="get" action="<?php echo esc_url( $archive_url ); ?>">
		<div class="pribehy-search-wrap">
			<button type="submit" class="pribehy-search-icon" aria-label="<?php esc_attr_e( 'Vyhledat', 'cpnrp' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
					<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
				</svg>
			</button>
			<input
				class="pribehy-search-input"
				type="search"
				name="s"
				value="<?php echo esc_attr( $search_query ); ?>"
				placeholder="<?php esc_attr_e( 'Hledat v příbězích…', 'cpnrp' ); ?>"
				aria-label="<?php esc_attr_e( 'Hledat příspěvky', 'cpnrp' ); ?>"
			>
			<?php if ( $active_cat ) : ?>
				<input type="hidden" name="cat" value="<?php echo esc_attr( $active_cat ); ?>">
			<?php endif; ?>
			<?php if ( $search_query ) : ?>
				<a class="pribehy-search-clear" href="<?php echo esc_url( $active_cat ? add_query_arg( 'cat', $active_cat, $archive_url ) : $archive_url ); ?>" aria-label="<?php esc_attr_e( 'Zrušit vyhledávání', 'cpnrp' ); ?>">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
						<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
					</svg>
				</a>
			<?php endif; ?>
		</div>
	</form>

	<!-- Category pills -->
	<?php if ( $cats ) : ?>
	<nav class="pribehy-cat-nav" aria-label="<?php esc_attr_e( 'Filtr kategorií', 'cpnrp' ); ?>">
		<a class="pribehy-cat-pill<?php echo ( ! $active_cat ) ? ' is-active' : ''; ?>"
		   href="<?php echo esc_url( $search_query ? add_query_arg( 's', $search_query, $archive_url ) : $archive_url ); ?>">
			<?php esc_html_e( 'Vše', 'cpnrp' ); ?>
		</a>
		<?php foreach ( $cats as $cat ) : ?>
			<?php
			$url = get_category_link( $cat->term_id );
			if ( $search_query ) {
				$url = add_query_arg( 's', $search_query, $url );
			}
			?>
			<a class="pribehy-cat-pill<?php echo ( $active_cat === $cat->term_id ) ? ' is-active' : ''; ?>"
			   href="<?php echo esc_url( $url ); ?>">
				<?php echo esc_html( $cat->name ); ?>
			</a>
		<?php endforeach; ?>
	</nav>
	<?php endif; ?>

</div>
