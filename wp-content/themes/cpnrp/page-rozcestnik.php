<?php
/**
 * Template Name: Rozcestník
 * Shared hub template — Adopce, Pěstounská péče, Zájemci o NRP.
 * Items = child pages ordered by menu_order.
 */

get_header();

$page_id    = get_the_ID();
$eyebrow    = get_post_meta( $page_id, '_hub_eyebrow',         true );
$sec_title  = get_post_meta( $page_id, '_hub_section_title',   true );
$card_show  = get_post_meta( $page_id, '_hub_card_show',       true );
$card_text  = get_post_meta( $page_id, '_hub_card_text',       true );
$card_url   = get_post_meta( $page_id, '_hub_card_url',        true );
$bq_show    = get_post_meta( $page_id, '_hub_blockquote_show', true );
$bq_text    = get_post_meta( $page_id, '_hub_blockquote_text', true );
$bq_link    = get_post_meta( $page_id, '_hub_blockquote_link_text', true );
$bq_url     = get_post_meta( $page_id, '_hub_blockquote_link_url',  true );

if ( ! $eyebrow )   $eyebrow   = 'Co nabízíme';
if ( ! $sec_title ) $sec_title = get_the_title();
if ( ! $card_text ) $card_text = 'Adopce, nebo pěstounská péče? Zjistěte, co vám sedí.';
if ( ! $bq_link )   $bq_link   = 'Domluvit konzultaci';

$hero_desc = get_the_excerpt();

// Child pages as hub items (sorted by menu_order)
$items = get_pages( [
	'parent'      => $page_id,
	'sort_column' => 'menu_order',
	'sort_order'  => 'ASC',
	'post_status' => 'publish',
] );

// Breadcrumb ancestors
$ancestors = array_reverse( get_post_ancestors( $page_id ) );
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<section class="hub-hero">
		<div class="container">
			<div class="hub-hero-inner<?php echo has_post_thumbnail() ? ' hub-hero-inner--with-image' : ''; ?>">

				<div>
					<nav class="hub-breadcrumb" aria-label="Breadcrumb">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
						<?php foreach ( $ancestors as $anc_id ) : ?>
							<span>/</span>
							<a href="<?php echo esc_url( get_permalink( $anc_id ) ); ?>"><?php echo esc_html( get_the_title( $anc_id ) ); ?></a>
						<?php endforeach; ?>
						<span>/</span>
						<span><?php the_title(); ?></span>
					</nav>

					<h1 class="hub-hero-title"><?php the_title(); ?></h1>

					<?php if ( $hero_desc ) : ?>
						<p class="hub-hero-desc"><?php echo esc_html( $hero_desc ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="hub-hero-image-wrap">
						<?php the_post_thumbnail( 'large', [
							'class' => 'hub-hero-image',
							'alt'   => esc_attr( get_the_title() ),
						] ); ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</section>

	<!-- ── Hub items ─────────────────────────────────────────────── -->
	<section class="hub-section">
		<div class="container">
			<div class="hub-content">

				<p class="hub-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<h2 class="hub-section-title"><?php echo esc_html( $sec_title ); ?></h2>

				<!-- Optional gold decision card -->
				<?php if ( $card_show && $card_url ) : ?>
					<a href="<?php echo esc_url( $card_url ); ?>" class="hub-decision-card">
						<div>
							<p class="hub-decision-card-eyebrow"><?php esc_html_e( 'Nejste si jistí?', 'cpnrp' ); ?></p>
							<p class="hub-decision-card-text"><?php echo esc_html( $card_text ); ?></p>
						</div>
						<svg class="hub-decision-card-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
							<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
						</svg>
					</a>
				<?php endif; ?>

				<!-- Numbered item list (child pages) -->
				<?php if ( $items ) : ?>
					<ul class="hub-list">
						<?php foreach ( $items as $idx => $item ) :
							$item_desc = $item->post_excerpt
								?: wp_trim_words( wp_strip_all_tags( $item->post_content ), 25, '…' );
						?>
							<li class="hub-list-item">
								<a href="<?php echo esc_url( get_permalink( $item ) ); ?>" class="hub-item-link">
									<span class="hub-item-number"><?php echo str_pad( $idx + 1, 2, '0', STR_PAD_LEFT ); ?></span>
									<div class="hub-item-body">
										<h3 class="hub-item-title"><?php echo esc_html( get_the_title( $item ) ); ?></h3>
										<?php if ( $item_desc ) : ?>
											<p class="hub-item-desc"><?php echo esc_html( $item_desc ); ?></p>
										<?php endif; ?>
										<span class="hub-item-more">
											<?php esc_html_e( 'Zjistit více', 'cpnrp' ); ?>
											<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
												<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
											</svg>
										</span>
									</div>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<p class="hub-empty"><?php esc_html_e( 'Zatím žádné položky. Přidejte podřazené stránky.', 'cpnrp' ); ?></p>
				<?php endif; ?>

				<!-- Optional blockquote -->
				<?php if ( $bq_show && $bq_text ) : ?>
					<blockquote class="hub-blockquote">
						<p><?php echo esc_html( $bq_text ); ?></p>
						<?php if ( $bq_url && $bq_link ) : ?>
							<p>
								<a href="<?php echo esc_url( $bq_url ); ?>" class="hub-blockquote-link">
									<?php echo esc_html( $bq_link ); ?>
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true">
										<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
									</svg>
								</a>
							</p>
						<?php endif; ?>
					</blockquote>
				<?php endif; ?>

			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
