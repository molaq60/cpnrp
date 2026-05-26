<?php
/**
 * Template Name: O nás
 * About hub page — mission, stats, links to all O nás subpages.
 */

get_header();

$page_id = get_the_ID();

$hero_desc  = get_post_meta( $page_id, '_o_nas_hero_desc', true )
	?: 'Jsme tým odborníků, který od roku 2002 pomáhá dětem najít domov a rodinám ho udržet.';
$mission    = get_post_meta( $page_id, '_o_nas_mission', true )
	?: 'Věříme, že každé dítě má právo vyrůstat v láskyplné rodině. Naším posláním je toto právo naplňovat — doprovázením pěstounů, podporou žadatelů o adopci a vzděláváním všech, kdo se o náhradní péči zajímají.';
$blockquote = get_post_meta( $page_id, '_o_nas_blockquote', true )
	?: '„Chcete se dozvědět více o naší práci nebo s námi spolupracovat? Budeme rádi."';
$bq_link    = get_post_meta( $page_id, '_o_nas_blockquote_link', true )
	?: 'Napište nám';

$children = get_pages( [
	'parent'      => $page_id,
	'sort_column' => 'menu_order',
	'sort_order'  => 'ASC',
] );
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<section class="o-nas-hero">
		<div class="container">
			<nav class="subpage-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Úvod</a>
				<span>/</span>
				<span><?php the_title(); ?></span>
			</nav>
			<h1 class="o-nas-hero-title"><?php the_title(); ?></h1>
			<p class="o-nas-hero-desc"><?php echo esc_html( $hero_desc ); ?></p>
		</div>
	</section>

	<!-- ── Poslání + statistiky ──────────────────────────────────── -->
	<section class="o-nas-mission-section">
		<div class="container">
			<div class="o-nas-mission-wrap">

				<div class="o-nas-mission-col">
					<p class="hub-eyebrow">Naše poslání</p>
					<h2 class="hub-section-title">Pomáháme dětem najít domov</h2>
					<p class="o-nas-mission-text"><?php echo esc_html( $mission ); ?></p>
					<ul class="o-nas-values">
						<li class="o-nas-value">
							<span class="o-nas-value-dot" aria-hidden="true"></span>
							<span>Odborné doprovázení pěstounských rodin</span>
						</li>
						<li class="o-nas-value">
							<span class="o-nas-value-dot" aria-hidden="true"></span>
							<span>Podpora žadatelů o adopci na každém kroku</span>
						</li>
						<li class="o-nas-value">
							<span class="o-nas-value-dot" aria-hidden="true"></span>
							<span>Vzdělávání a osvěta v oblasti NRP</span>
						</li>
						<li class="o-nas-value">
							<span class="o-nas-value-dot" aria-hidden="true"></span>
							<span>Komunitní aktivity a propojování rodin</span>
						</li>
					</ul>
				</div>

				<div class="o-nas-stats-col">
					<?php
					$stat_defaults = [
						1 => [ 'num' => '22+',  'label' => 'let pomáháme rodinám' ],
						2 => [ 'num' => '400+', 'label' => 'rodin ročně v doprovázení' ],
						3 => [ 'num' => '25',   'label' => 'odborných pracovníků' ],
						4 => [ 'num' => '3',    'label' => 'pobočky v Ústeckém kraji' ],
					];
					?>
					<div class="o-nas-stats">
						<?php for ( $i = 1; $i <= 4; $i++ ) :
							$num   = get_post_meta( $page_id, "_o_nas_stat{$i}_num", true ) ?: $stat_defaults[ $i ]['num'];
							$label = get_post_meta( $page_id, "_o_nas_stat{$i}_label", true ) ?: $stat_defaults[ $i ]['label'];
						?>
						<div class="o-nas-stat">
							<div class="o-nas-stat-number"><?php echo esc_html( $num ); ?></div>
							<div class="o-nas-stat-label"><?php echo esc_html( $label ); ?></div>
						</div>
						<?php endfor; ?>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- ── Navigace na podstránky ────────────────────────────────── -->
	<?php if ( $children ) : ?>
	<section class="o-nas-nav-section">
		<div class="container">
			<p class="hub-eyebrow">Poznejte nás blíže</p>
			<h2 class="hub-section-title">Více o CPNRP</h2>
			<div class="o-nas-nav">
				<?php foreach ( $children as $i => $child ) :
					$num     = str_pad( $i + 1, 2, '0', STR_PAD_LEFT );
					$excerpt = get_the_excerpt( $child->ID );
				?>
				<a href="<?php echo esc_url( get_permalink( $child->ID ) ); ?>" class="o-nas-nav-card">
					<div class="o-nas-nav-card-num"><?php echo esc_html( $num ); ?></div>
					<h3 class="o-nas-nav-card-title"><?php echo esc_html( $child->post_title ); ?></h3>
					<?php if ( $excerpt ) : ?>
					<p class="o-nas-nav-card-desc"><?php echo esc_html( $excerpt ); ?></p>
					<?php endif; ?>
					<span class="o-nas-nav-card-arrow" aria-hidden="true">
						Více
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" width="14" height="14">
							<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
						</svg>
					</span>
				</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<!-- ── CTA ───────────────────────────────────────────────────── -->
	<section class="o-nas-cta-section">
		<div class="container">
			<blockquote class="pro-rodiny-blockquote">
				<p><?php echo esc_html( $blockquote ); ?></p>
				<p class="pro-rodiny-blockquote-action">
					<a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>" class="pro-rodiny-blockquote-link">
						<?php echo esc_html( $bq_link ); ?>
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
