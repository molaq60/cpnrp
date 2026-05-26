<?php
/**
 * Homepage entry cards — 3 tiles linking to main service areas.
 * Editable via Appearance → Customize → CPNRP Nastavení → Vstupní dlaždice.
 */

$cards = [
	[
		'title'  => get_theme_mod( 'cpnrp_card_1_title', 'Adopce' ),
		'desc'   => get_theme_mod( 'cpnrp_card_1_desc',  'Chci zjistit, jak proces probíhá a co mě čeká.' ),
		'url'    => get_theme_mod( 'cpnrp_card_1_url',   '/pro-rodiny/adopce' ),
		'stripe' => 'entry-card-stripe--green',
		'link'   => 'entry-card-link--green',
	],
	[
		'title'  => get_theme_mod( 'cpnrp_card_2_title', 'Pěstounská péče' ),
		'desc'   => get_theme_mod( 'cpnrp_card_2_desc',  'Doprovázení, vzdělávání, poradenství a podpora pro pěstounské rodiny.' ),
		'url'    => get_theme_mod( 'cpnrp_card_2_url',   '/pro-rodiny/pestounska-pece' ),
		'stripe' => 'entry-card-stripe--teal',
		'link'   => 'entry-card-link--teal',
	],
	[
		'title'  => get_theme_mod( 'cpnrp_card_3_title', 'Zájemci o NRP' ),
		'desc'   => get_theme_mod( 'cpnrp_card_3_desc',  'Co je náhradní péče? Jak začít? Přípravné kurzy a nejčastější otázky.' ),
		'url'    => get_theme_mod( 'cpnrp_card_3_url',   '/pro-rodiny/zajemci' ),
		'stripe' => 'entry-card-stripe--gold',
		'link'   => 'entry-card-link--gold',
	],
];
?>

<section class="entry-cards">
	<div class="container">
		<div class="entry-cards-grid">
			<?php foreach ( $cards as $card ) : ?>
			<a href="<?php echo esc_url( $card['url'] ); ?>" class="entry-card animate-fade-up">
				<div class="entry-card-stripe <?php echo esc_attr( $card['stripe'] ); ?>"></div>
				<div class="entry-card-body">
					<h3 class="entry-card-title"><?php echo esc_html( $card['title'] ); ?></h3>
					<p class="entry-card-desc"><?php echo esc_html( $card['desc'] ); ?></p>
					<span class="entry-card-link <?php echo esc_attr( $card['link'] ); ?>">
						Zjistit více
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
							<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
						</svg>
					</span>
				</div>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
