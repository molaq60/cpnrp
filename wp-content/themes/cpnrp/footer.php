	<footer class="footer" role="contentinfo">
		<div class="container">
			<div class="footer-grid">

				<!-- Sloupec 1: Logo + popis + sociální sítě -->
				<div class="footer-about">
					<div class="footer-logo-wrap">
						<?php
						$logo_url = get_template_directory_uri() . '/assets/images/logo.png';
						if ( has_custom_logo() ) {
							$logo_id  = get_theme_mod( 'custom_logo' );
							$logo_url = wp_get_attachment_image_url( $logo_id, 'medium' ) ?: $logo_url;
						}
						?>
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" class="footer-logo">
					</div>
					<p class="footer-about-text">
						<?php echo nl2br( esc_html( get_theme_mod( 'cpnrp_footer_about_text', "Centrum pro NRP, o.p.s.\nIČO: 26999234\nSídlo: Teplická 1672/3, 412 01 Litoměřice\nběžný účet: 35 - 9706800297/0100\nsbírkový účet: 107 - 5420340207/0100" ) ) ); ?>
					</p>
					<div class="footer-social">
						<a href="https://www.facebook.com/centrumpronrp" class="footer-social-link" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
								<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
							</svg>
						</a>
						<a href="https://www.instagram.com/centrum_pro_nrp/?hl=cs" class="footer-social-link" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
								<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
							</svg>
						</a>
					</div>
				</div>

				<!-- Sloupec 2: Pro rodiny -->
				<div class="footer-col">
					<h3 class="footer-col-heading">Pro rodiny</h3>
					<ul class="footer-col-menu">
						<li><a href="<?php echo esc_url( home_url( '/pro-rodiny/adopce' ) ); ?>">Adopce</a></li>
						<li><a href="<?php echo esc_url( home_url( '/pro-rodiny/pestounska-pece' ) ); ?>">Pěstounská péče</a></li>
						<li><a href="<?php echo esc_url( home_url( '/pro-rodiny/zajemci' ) ); ?>">Zájemci o NRP</a></li>
						<li><a href="<?php echo esc_url( home_url( '/pribehy' ) ); ?>">Příběhy rodin</a></li>
						<li><a href="<?php echo esc_url( home_url( '/galerie' ) ); ?>">Fotogalerie</a></li>
					</ul>
				</div>

				<!-- Sloupec 3: O organizaci -->
				<div class="footer-col">
					<h3 class="footer-col-heading">O organizaci</h3>
					<ul class="footer-col-menu">
						<li><a href="<?php echo esc_url( home_url( '/o-nas' ) ); ?>">Kdo jsme &amp; poslání</a></li>
						<li><a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>">Náš tým</a></li>
						<li><a href="<?php echo esc_url( home_url( '/o-nas/vyrocni-zpravy' ) ); ?>">Výroční zprávy</a></li>
						<li><a href="<?php echo esc_url( home_url( '/o-nas/dokumenty' ) ); ?>">Dokumenty ke stažení</a></li>
						<li><a href="<?php echo esc_url( home_url( '/o-nas/partneri' ) ); ?>">S kým spolupracujeme</a></li>
						<li><a href="<?php echo esc_url( home_url( '/pro-odborniky' ) ); ?>">Pro odborníky</a></li>
						<li><a href="<?php echo esc_url( home_url( '/podporte-nas' ) ); ?>">Podpořte nás</a></li>
					</ul>
				</div>

				<!-- Sloupec 4: Kontakt -->
				<div class="footer-col">
					<h3 class="footer-col-heading">Kontakt</h3>
					<ul class="footer-contact-list">
						<li class="footer-contact-item">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
							</svg>
							<span>Teplická 1672/3<br>412 01 Litoměřice</span>
						</li>
						<li>
							<a href="tel:+420731557681" class="footer-contact-link">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
									<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
								</svg>
								+420 731 557 681
							</a>
						</li>
						<li>
							<a href="mailto:info@cpnrp.cz" class="footer-contact-link">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
									<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/>
								</svg>
								info@cpnrp.cz
							</a>
						</li>
					</ul>
					<div class="footer-cta">
						<?php
						$cta_text = get_theme_mod( 'cpnrp_cta_text', __( 'Podpořte nás', 'cpnrp' ) );
						$cta_url  = get_theme_mod( 'cpnrp_cta_url',  home_url( '/podporte-nas' ) );
						?>
						<a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--red" target="_blank" rel="noopener noreferrer">
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/heart-benefice.png' ); ?>" alt="" aria-hidden="true">
							<?php echo esc_html( $cta_text ); ?>
						</a>
					</div>
				</div>

			</div><!-- .footer-grid -->

			<!-- Bottom bar -->
			<div class="footer-bottom">
				<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Centrum pro náhradní rodinnou péči, o.p.s. &middot; IČ: 26999234</p>
				<a href="https://molaq.cz" class="footer-credit-link" target="_blank" rel="noopener noreferrer" aria-label="Vytvořil molaq.cz">
					<span class="footer-credit-text">created by</span>
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo_molaQ_yellowQ.png' ); ?>"
					     alt="molaq.cz" class="footer-credit-logo">
				</a>
				<div class="footer-bottom-links">
					<a href="<?php echo esc_url( home_url( '/ochrana-udaju' ) ); ?>">Ochrana osobních údajů</a>
					<a href="<?php echo esc_url( home_url( '/cookies' ) ); ?>">Cookies</a>
				</div>
			</div>

		</div><!-- .container -->

	</footer>

	<?php wp_footer(); ?>
</body>
</html>
