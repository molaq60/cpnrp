<?php
/**
 * Template Name: Pro odborníky
 * Services page for OSPOD, courts, schools and other professionals.
 */

get_header();

$page_id = get_the_ID();

function odb_meta( $key, $default = '' ) {
	$val = get_post_meta( get_the_ID(), $key, true );
	return ( $val !== '' && $val !== false ) ? $val : $default;
}

$hero_desc      = odb_meta( '_odb_hero_desc', 'Nabízíme služby a spolupráci pro orgány sociálně-právní ochrany dětí (OSPOD), soudy, školy a další odborníky v oblasti péče o ohrožené děti.' );
$eyebrow        = odb_meta( '_odb_eyebrow', 'Spolupráce s OSPOD' );
$section_h2     = odb_meta( '_odb_section_heading', 'Co nabízíme odborné veřejnosti' );
$legal_eyebrow  = odb_meta( '_odb_legal_eyebrow', 'Právní rámec' );
$legal_text1    = odb_meta( '_odb_legal_text1', 'Činnost CPNRP vychází z <strong>pověření k výkonu sociálně-právní ochrany dětí</strong> dle zákona č. 359/1999 Sb. Organizace má pověření k uzavírání dohod o výkonu pěstounské péče a k poskytování odborného poradenství v oblasti NRP.' );
$legal_text2    = odb_meta( '_odb_legal_text2', 'Spolupracujeme s krajskými úřady, obecními úřady obcí s rozšířenou působností, soudy a dalšími institucemi v systému péče o ohrožené děti v Ústeckém kraji.' );
$cta_quote      = odb_meta( '_odb_cta_quote', '„Máte zájem o spolupráci? Ozvěte se — domluvíme termín, který se hodí oběma stranám."' );
$cta_email      = odb_meta( '_odb_cta_email', 'info@cpnrp.cz' );
$cta_phone      = odb_meta( '_odb_cta_phone', '+420 731 557 681' );
$cta_phone_raw  = preg_replace( '/\s+/', '', $cta_phone );

$service_defaults = [
	1 => [ 'title' => 'Facilitace případových konferencí', 'desc' => 'Odborná facilitace případových konferencí pro řešení situace ohrožených dětí. Facilitátor vede strukturovaný dialog mezi všemi zúčastněnými stranami — rodinou, OSPOD, školou a dalšími odborníky.' ],
	2 => [ 'title' => 'Vzdělávání pro OSPOD',              'desc' => 'Odborná školení a semináře pro pracovníky OSPOD zaměřené na aktuální témata náhradní rodinné péče, komunikaci s pěstounskými rodinami a legislativní změny.' ],
	3 => [ 'title' => 'Konzultace a metodická podpora',    'desc' => 'Konzultace pro sociální pracovníky při řešení konkrétních případů, metodická podpora a sdílení dobré praxe v oblasti NRP.' ],
	4 => [ 'title' => 'Sociálně aktivizační služby',       'desc' => 'Sociálně aktivizační služby pro rodiny s dětmi v péči jiné osoby, které se ocitly v obtížné nebo krizové sociální situaci.' ],
];

$services = [];
for ( $i = 1; $i <= 4; $i++ ) {
	$title = odb_meta( "_odb_service{$i}_title", $service_defaults[ $i ]['title'] );
	$desc  = odb_meta( "_odb_service{$i}_desc",  $service_defaults[ $i ]['desc'] );
	if ( $title ) {
		$services[] = [ 'num' => $i, 'title' => $title, 'desc' => $desc ];
	}
}
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────────── -->
	<section class="subpage-hero">
		<div class="container">
			<nav class="subpage-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
				<span>/</span>
				<span><?php the_title(); ?></span>
			</nav>
			<h1 class="subpage-hero-title"><?php the_title(); ?></h1>
			<p class="subpage-hero-desc"><?php echo esc_html( $hero_desc ); ?></p>
		</div>
	</section>

	<!-- ── Services ──────────────────────────────────────────────────── -->
	<section class="odb-services-section">
		<div class="container">
			<div class="odb-services-inner">

				<?php if ( $eyebrow ) : ?>
				<p class="odb-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<?php endif; ?>

				<h2 class="odb-section-heading"><?php echo esc_html( $section_h2 ); ?></h2>

				<div class="odb-services-list">
					<?php foreach ( $services as $s ) : ?>
					<div class="odb-service-item">
						<div class="odb-service-num">
							<span><?php echo str_pad( $s['num'], 2, '0', STR_PAD_LEFT ); ?></span>
						</div>
						<div class="odb-service-body">
							<h3 class="odb-service-title"><?php echo esc_html( $s['title'] ); ?></h3>
							<p class="odb-service-desc"><?php echo esc_html( $s['desc'] ); ?></p>
						</div>
					</div>
					<?php endforeach; ?>
				</div>

				<!-- Legal frame -->
				<div class="odb-legal">
					<?php if ( $legal_eyebrow ) : ?>
					<p class="odb-eyebrow"><?php echo esc_html( $legal_eyebrow ); ?></p>
					<?php endif; ?>
					<p class="odb-legal-text"><?php echo wp_kses( $legal_text1, [ 'strong' => [] ] ); ?></p>
					<?php if ( $legal_text2 ) : ?>
					<p class="odb-legal-text"><?php echo esc_html( $legal_text2 ); ?></p>
					<?php endif; ?>
				</div>

				<!-- Blockquote / CTA -->
				<blockquote class="odb-cta-quote">
					<p class="odb-cta-quote-text"><?php echo esc_html( $cta_quote ); ?></p>
					<p class="odb-cta-contacts">
						<a href="mailto:<?php echo esc_attr( $cta_email ); ?>" class="odb-cta-link"><?php echo esc_html( $cta_email ); ?></a>
						<span class="odb-cta-sep">·</span>
						<a href="tel:<?php echo esc_attr( $cta_phone_raw ); ?>" class="odb-cta-link"><?php echo esc_html( $cta_phone ); ?></a>
					</p>
				</blockquote>

			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
