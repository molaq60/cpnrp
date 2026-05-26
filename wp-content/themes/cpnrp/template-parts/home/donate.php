<?php
/**
 * Homepage — "Podpořte nás" CTA section above footer.
 * Editable via Appearance → Customize → CPNRP Nastavení → Podpořte nás.
 */

$heading      = get_theme_mod( 'cpnrp_donate_heading',      'Vaše pomoc mění životy' );
$desc         = get_theme_mod( 'cpnrp_donate_desc',         'Díky vašim darům můžeme poskytovat odbornou péči a podporu stovkám náhradních rodin. Každý příspěvek pomáhá dětem najít bezpečný domov.' );
$card_heading = get_theme_mod( 'cpnrp_donate_card_heading', 'Darujte s láskou' );
$card_desc    = get_theme_mod( 'cpnrp_donate_card_desc',    'Vyberte si způsob, jakým chcete podpořit náhradní rodiny.' );
$btn1_url     = get_theme_mod( 'cpnrp_donate_btn1_url',     '/podporte-nas' );
$btn2_url     = get_theme_mod( 'cpnrp_donate_btn2_url',     '/podporte-nas#pravidelny-dar' );
$account      = get_theme_mod( 'cpnrp_donate_account',      '35–9706800297/0100' );

$tiers = [
	[
		'amount' => get_theme_mod( 'cpnrp_donate_tier1_amount', '200 Kč' ),
		'label'  => get_theme_mod( 'cpnrp_donate_tier1_label',  '1 hodina doučování' ),
		'url'    => get_theme_mod( 'cpnrp_donate_tier1_url',    '/podporte-nas?amount=200' ),
		'mod'    => 'donate-tier--teal',
	],
	[
		'amount' => get_theme_mod( 'cpnrp_donate_tier2_amount', '400 Kč' ),
		'label'  => get_theme_mod( 'cpnrp_donate_tier2_label',  '2 hodiny doučování' ),
		'url'    => get_theme_mod( 'cpnrp_donate_tier2_url',    '/podporte-nas?amount=400' ),
		'mod'    => 'donate-tier--red',
	],
	[
		'amount' => get_theme_mod( 'cpnrp_donate_tier3_amount', '800 Kč' ),
		'label'  => get_theme_mod( 'cpnrp_donate_tier3_label',  '1 hodina terapeutického poradenství' ),
		'url'    => get_theme_mod( 'cpnrp_donate_tier3_url',    '/podporte-nas?amount=800' ),
		'mod'    => 'donate-tier--gold',
	],
];

$heart_img = get_template_directory_uri() . '/assets/images/heart-benefice.png';
?>

<section id="podporte-nas-cta" class="donate-section" aria-label="<?php esc_attr_e( 'Podpořte nás', 'cpnrp' ); ?>">
	<div class="container">
		<div class="donate-inner">

			<!-- Levá strana -->
			<div class="donate-left animate-fade-up">
				<span class="donate-badge">
					<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
						<path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
					</svg>
					Podpořte naši práci
				</span>

				<h2 class="donate-heading"><?php echo esc_html( $heading ); ?></h2>
				<p class="donate-desc"><?php echo esc_html( $desc ); ?></p>

				<div class="donate-tiers">
					<?php foreach ( $tiers as $tier ) : ?>
					<a href="<?php echo esc_url( $tier['url'] ); ?>" class="donate-tier <?php echo esc_attr( $tier['mod'] ); ?>">
						<p class="donate-tier-amount"><?php echo esc_html( $tier['amount'] ); ?></p>
						<p class="donate-tier-label"><?php echo esc_html( $tier['label'] ); ?></p>
						<span class="donate-tier-cta">Vybrat →</span>
					</a>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Pravá strana — CTA karta -->
			<div class="donate-card animate-fade-up delay-2">
				<div class="donate-card-icon">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
					</svg>
				</div>

				<h3 class="donate-card-heading"><?php echo esc_html( $card_heading ); ?></h3>
				<p class="donate-card-desc"><?php echo esc_html( $card_desc ); ?></p>

				<a href="<?php echo esc_url( $btn1_url ); ?>" class="donate-btn-primary">
					<img src="<?php echo esc_url( $heart_img ); ?>" alt="" aria-hidden="true">
					Podpořte nás
				</a>
				<a href="<?php echo esc_url( $btn2_url ); ?>" class="donate-btn-secondary">
					Pravidelný dar
				</a>

				<div class="donate-account">
					nebo převodem na účet: <strong><?php echo esc_html( $account ); ?></strong>
				</div>
			</div>

		</div>
	</div>
</section>
