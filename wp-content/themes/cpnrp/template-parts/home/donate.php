<?php
/**
 * Homepage — "Podpořte nás" CTA section.
 * Editable via Appearance → Customize → CPNRP Nastavení → Podpořte nás.
 */

$heading      = get_theme_mod( 'cpnrp_donate_heading',      'Vaše pomoc mění životy' );
$desc         = get_theme_mod( 'cpnrp_donate_desc',         'Díky vašim darům můžeme poskytovat odbornou péči a podporu stovkám náhradních rodin. Každý příspěvek pomáhá dětem najít bezpečný domov.' );
$card_heading = get_theme_mod( 'cpnrp_donate_card_heading', 'Darujte s láskou' );
$card_desc    = get_theme_mod( 'cpnrp_donate_card_desc',    'Vyberte si způsob, jakým chcete podpořit náhradní rodiny.' );
$btn1_url     = get_theme_mod( 'cpnrp_donate_btn1_url',     '/podporte-nas' );
$btn2_url     = get_theme_mod( 'cpnrp_donate_btn2_url',     '/podporte-nas#pravidelny-dar' );
$account      = get_theme_mod( 'cpnrp_donate_account',      '35–9706800297/0100' );
// IBAN pro QR kódy — editovatelné přes Customizer
$iban         = get_theme_mod( 'cpnrp_donate_iban',         'CZ4801000000359706800297' );

$tiers = [
	[
		'amount' => get_theme_mod( 'cpnrp_donate_tier1_amount', '200 Kč' ),
		'mod'    => 'donate-tier--teal',
		'num'    => (int) preg_replace( '/\D/', '', get_theme_mod( 'cpnrp_donate_tier1_amount', '200' ) ),
	],
	[
		'amount' => get_theme_mod( 'cpnrp_donate_tier2_amount', '400 Kč' ),
		'mod'    => 'donate-tier--red',
		'num'    => (int) preg_replace( '/\D/', '', get_theme_mod( 'cpnrp_donate_tier2_amount', '400' ) ),
	],
	[
		'amount' => get_theme_mod( 'cpnrp_donate_tier3_amount', '800 Kč' ),
		'mod'    => 'donate-tier--gold',
		'num'    => (int) preg_replace( '/\D/', '', get_theme_mod( 'cpnrp_donate_tier3_amount', '800' ) ),
	],
];

$heart_img = get_template_directory_uri() . '/assets/images/heart-benefice.png';

// Build SPD QR URL — Czech payment standard
function cpnrp_qr_url( string $iban, int $amount, int $size = 140 ): string {
	$spd = 'SPD*1.0*ACC:' . $iban . '*AM:' . $amount . '.00*CC:CZK*MSG:Dar CPNRP';
	return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&ecc=M&data=' . rawurlencode( $spd );
}
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

				<!-- Tier cards — amount + QR, neklikatelné -->
				<div class="donate-tiers">
					<?php foreach ( $tiers as $tier ) : ?>
					<div class="donate-tier <?php echo esc_attr( $tier['mod'] ); ?>">
						<p class="donate-tier-amount"><?php echo esc_html( $tier['amount'] ); ?></p>
						<hr class="donate-tier-divider">
						<button class="donate-qr-btn"
						        data-qr-src="<?php echo esc_url( cpnrp_qr_url( $iban, $tier['num'], 320 ) ); ?>"
						        data-qr-alt="QR kód pro platbu <?php echo esc_attr( $tier['amount'] ); ?>"
						        aria-label="<?php echo esc_attr( 'Zvětšit QR kód pro platbu ' . $tier['amount'] ); ?>">
							<img src="<?php echo esc_url( cpnrp_qr_url( $iban, $tier['num'] ) ); ?>"
							     alt="QR kód pro platbu <?php echo esc_attr( $tier['amount'] ); ?>"
							     width="140" height="140"
							     loading="lazy"
							     class="donate-qr-img">
						</button>
					</div>
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

				<a href="<?php echo esc_url( cpnrp_url( $btn1_url ) ); ?>" class="donate-btn-primary" target="_blank" rel="noopener noreferrer">
					<img src="<?php echo esc_url( $heart_img ); ?>" alt="" aria-hidden="true">
					Podpořte nás
				</a>
<div class="donate-account">
					nebo převodem na účet: <strong><?php echo esc_html( $account ); ?></strong>
				</div>
			</div>

		</div>
	</div>
</section>

<!-- QR Lightbox -->
<div id="qr-lightbox" class="qr-lightbox" role="dialog" aria-modal="true" aria-label="QR kód pro platbu">
	<div class="qr-lightbox-backdrop"></div>
	<div class="qr-lightbox-inner">
		<button class="qr-lightbox-close" aria-label="Zavřít">&times;</button>
		<img src="" alt="" class="qr-lightbox-img" width="320" height="320">
	</div>
</div>

<script>
(function () {
	var lb       = document.getElementById('qr-lightbox');
	var lbImg    = lb.querySelector('.qr-lightbox-img');
	var lbClose  = lb.querySelector('.qr-lightbox-close');
	var backdrop = lb.querySelector('.qr-lightbox-backdrop');

	function open(btn) {
		lbImg.src = btn.dataset.qrSrc;
		lbImg.alt = btn.dataset.qrAlt;
		lb.classList.add('is-open');
		document.body.style.overflow = 'hidden';
		lbClose.focus();
	}

	function close() {
		lb.classList.remove('is-open');
		document.body.style.overflow = '';
	}

	document.querySelectorAll('.donate-qr-btn').forEach(function (btn) {
		btn.addEventListener('click', function () { open(btn); });
	});

	lbClose.addEventListener('click', close);
	backdrop.addEventListener('click', close);
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape' && lb.classList.contains('is-open')) close();
	});
})();
</script>
