<?php
/**
 * Template Name: S kým spolupracujeme
 * Partners page — logo grid grouped by category.
 * Partner data is defined in this template; update here to add/remove partners.
 */

get_header();

$page_id   = get_the_ID();
$parent_id = wp_get_post_parent_id( $page_id );
$ancestors = array_reverse( get_post_ancestors( $page_id ) );
$hero_desc = get_post_meta( $page_id, '_spolupracujeme_hero_desc', true )
	?: 'Naše práce je možná díky důvěře a podpoře desítek partnerů — od měst a krajů po nadace a soukromé firmy.';

$img_base = get_template_directory_uri() . '/assets/images/partners/';

$group_defaults = [
	1 => [
		'eyebrow' => 'Veřejná sféra',
		'title'   => 'Veřejní a institucionální partneři',
		'logos'   => "ustecky-kraj.jpg|Ústecký kraj\nmesto-litomerice.jpg|Město Litoměřice\nmesto-usti-nad-labem.jpg|Město Ústí nad Labem\nzdrave-mesto-litomerice.jpg|Zdravé město Litoměřice\nasociace-dite-a-rodina.png|Asociace dítě a rodina\nrodinny-svaz.png|Rodinný svaz ČR",
	],
	2 => [
		'eyebrow' => 'Filantropie',
		'title'   => 'Nadace a fondy',
		'logos'   => "nadace-sirius.png|Nadace Sirius\nnadace-jt.png|Nadace JT\nnadacni-fond-albert.png|Nadační fond Albert\nnros-pomozte-detem.jpg|Pomozte dětem\nnadace-rhea.jpg|Nadace Rhea\nnadacni-fond-severoceska-voda.jpg|Nadační fond Severočeská voda",
	],
	3 => [
		'eyebrow' => 'Soukromý sektor',
		'title'   => 'Korporátní partneři',
		'logos'   => "orbico.png|Orbico\nsiad.jpg|SIAD Czech\nmondi.jpg|Mondi\nmagna-exteriors.png|Magna Exteriors\nholcim.jpg|Holcim\nceska-podnikatelska-pojistovna.jpg|Česká podnikatelská pojišťovna\nglobus.jpg|Globus\ndecci.png|Decci\namedis.png|Amedis\ncekro.png|Čekro\nnn-konstrukce.png|NN Konstrukce\nab-clima.png|AB Clima",
	],
];

$groups = [];
for ( $i = 1; $i <= 3; $i++ ) {
	$eyebrow = get_post_meta( $page_id, "_spo_group{$i}_eyebrow", true ) ?: $group_defaults[ $i ]['eyebrow'];
	$title   = get_post_meta( $page_id, "_spo_group{$i}_title",   true ) ?: $group_defaults[ $i ]['title'];

	// New structured format (img, name, url)
	$items_meta = get_post_meta( $page_id, "_spo_group{$i}_items", true );

	if ( is_array( $items_meta ) && ! empty( $items_meta ) ) {
		$logos = $items_meta;
	} else {
		// Fall back to legacy textarea format
		$logos_raw = get_post_meta( $page_id, "_spo_group{$i}_logos", true ) ?: $group_defaults[ $i ]['logos'];
		$logos = [];
		foreach ( array_filter( array_map( 'trim', explode( "\n", $logos_raw ) ) ) as $line ) {
			$parts = explode( '|', $line, 2 );
			if ( count( $parts ) === 2 ) {
				$logos[] = [ 'img' => $img_base . trim( $parts[0] ), 'name' => trim( $parts[1] ), 'url' => '' ];
			}
		}
	}

	if ( $logos ) {
		$groups[] = [ 'eyebrow' => $eyebrow, 'title' => $title, 'logos' => $logos ];
	}
}
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<section class="subpage-hero">
		<div class="container">
			<nav class="subpage-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Úvod</a>
				<?php foreach ( $ancestors as $anc_id ) : ?>
					<span>/</span>
					<a href="<?php echo esc_url( get_permalink( $anc_id ) ); ?>"><?php echo esc_html( get_the_title( $anc_id ) ); ?></a>
				<?php endforeach; ?>
				<span>/</span>
				<span><?php the_title(); ?></span>
			</nav>
			<h1 class="subpage-hero-title"><?php the_title(); ?></h1>
			<p class="subpage-hero-desc"><?php echo esc_html( $hero_desc ); ?></p>
		</div>
	</section>

	<!-- ── Partner groups ────────────────────────────────────────── -->
	<section class="spolupracujeme-section">
		<div class="container">

			<?php foreach ( $groups as $group ) : ?>
			<div class="partners-group">
				<p class="partners-group-eyebrow"><?php echo esc_html( $group['eyebrow'] ); ?></p>
				<h2 class="partners-group-title"><?php echo esc_html( $group['title'] ); ?></h2>
				<div class="partners-logos">
					<?php foreach ( $group['logos'] as $logo ) :
						$img  = esc_url( $logo['img']  ?? '' );
						$name = esc_attr( $logo['name'] ?? '' );
						$url  = esc_url( $logo['url']  ?? '' );
					?>
					<div class="partner-logo-item">
						<?php if ( $url ) : ?>
						<a href="<?php echo $url; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo $name; ?>">
						<?php endif; ?>
						<img
							class="partner-logo-img"
							src="<?php echo $img; ?>"
							alt="<?php echo $name; ?>"
							loading="lazy"
						>
						<?php if ( $url ) : ?></a><?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php endforeach; ?>

			<!-- Výzva k partnerství -->
			<div class="spolupracujeme-cta">
				<p class="hub-eyebrow">Chcete se přidat?</p>
				<h2 class="hub-section-title" style="margin-bottom:.75rem">Staňte se naším partnerem</h2>
				<p style="color:var(--color-text-muted);margin-bottom:1.75rem;max-width:540px;margin-inline:auto">
					Hledáme firmy a organizace, které sdílejí naše hodnoty. Partnerství může mít různé formy — finanční podpora, věcné dary, dobrovolnictví nebo odborná spolupráce.
				</p>
				<a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>" class="btn-cta-primary">
					Napište nám
				</a>
			</div>

		</div>
	</section>

	<!-- ── Back button ───────────────────────────────────────────── -->
	<div class="container" style="padding-bottom:4rem">
		<div class="subpage-cta-row">
			<?php if ( $parent_id ) : ?>
			<a href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>" class="btn-subpage-back">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
				</svg>
				<?php printf( esc_html__( 'Zpět na %s', 'cpnrp' ), esc_html( get_the_title( $parent_id ) ) ); ?>
			</a>
			<?php endif; ?>
		</div>
	</div>

</main>

<?php get_footer(); ?>
