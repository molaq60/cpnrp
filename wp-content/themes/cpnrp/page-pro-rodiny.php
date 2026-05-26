<?php
/**
 * Template Name: Pro rodiny
 * Main hub — dynamically renders Adopce / PP / Zájemci sections with their child links.
 */

get_header();

$page_id  = get_the_ID();
$hero_desc       = get_post_meta( $page_id, '_pro_rodiny_hero_desc', true )
	?: 'Ať už jste pěstoun, uvažujete o pěstounství, nebo chcete adoptovat — jsme tu pro vás.';
$blockquote      = get_post_meta( $page_id, '_pro_rodiny_blockquote', true )
	?: '„Teprve se rozhodujete? Napište nám — rádi odpovíme na všechny otázky bez závazku."';
$blockquote_link = get_post_meta( $page_id, '_pro_rodiny_blockquote_link', true )
	?: 'Domluvit konzultaci';

// Hub pages = direct children that use the Rozcestník template
$children = get_pages( [
	'parent'      => $page_id,
	'sort_column' => 'menu_order',
	'sort_order'  => 'ASC',
] );
$hubs = array_values( array_filter( $children, function ( $p ) {
	return get_post_meta( $p->ID, '_wp_page_template', true ) === 'page-rozcestnik.php';
} ) );
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<section class="pro-rodiny-hero">
		<div class="container">
			<nav class="subpage-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
				<span>/</span>
				<span><?php the_title(); ?></span>
			</nav>
			<h1 class="pro-rodiny-hero-title"><?php the_title(); ?></h1>
			<p class="pro-rodiny-hero-desc"><?php echo esc_html( $hero_desc ); ?></p>
		</div>
	</section>

	<!-- ── Hub sections ──────────────────────────────────────────── -->
	<section class="pro-rodiny-section">
		<div class="container">
			<div class="pro-rodiny-hubs">

				<?php foreach ( $hubs as $i => $hub ) :
					$hub_items = get_pages( [
						'parent'      => $hub->ID,
						'sort_column' => 'menu_order',
						'sort_order'  => 'ASC',
					] );
					$num = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
				?>
				<div class="pro-rodiny-hub">

					<p class="pro-rodiny-hub-label"><?php echo esc_html( $num ); ?> · Tematický okruh</p>

					<a href="<?php echo esc_url( get_permalink( $hub->ID ) ); ?>" class="pro-rodiny-hub-heading-link">
						<h2 class="pro-rodiny-hub-title"><?php echo esc_html( $hub->post_title ); ?></h2>
					</a>

					<?php if ( $hub->post_excerpt ) : ?>
					<p class="pro-rodiny-hub-desc"><?php echo esc_html( $hub->post_excerpt ); ?></p>
					<?php endif; ?>

					<?php if ( $hub_items ) : ?>
					<ul class="pro-rodiny-items">
						<?php foreach ( $hub_items as $item ) : ?>
						<li>
							<a href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>" class="pro-rodiny-item-link">
								<span class="pro-rodiny-item-bar" aria-hidden="true"></span>
								<?php echo esc_html( $item->post_title ); ?>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>

					<a href="<?php echo esc_url( get_permalink( $hub->ID ) ); ?>" class="pro-rodiny-hub-more">
						<?php printf( esc_html__( 'Vše o %s', 'cpnrp' ), esc_html( $hub->post_title ) ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
							<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
						</svg>
					</a>
				</div>
				<?php endforeach; ?>

			</div>

			<!-- Blockquote CTA -->
			<blockquote class="pro-rodiny-blockquote">
				<p><?php echo esc_html( $blockquote ); ?></p>
				<p class="pro-rodiny-blockquote-action">
					<a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>" class="pro-rodiny-blockquote-link">
						<?php echo esc_html( $blockquote_link ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
							<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
						</svg>
					</a>
				</p>
			</blockquote>

		</div>
	</section>

</main>

<?php get_footer(); ?>
