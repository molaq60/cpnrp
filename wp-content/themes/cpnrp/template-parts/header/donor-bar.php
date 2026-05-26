<?php
/**
 * Donor bar — light-blue strip above the header.
 * All text and URLs are editable via Appearance → Customize → CPNRP Nastavení → Donor Bar.
 */

$text     = get_theme_mod( 'cpnrp_donor_bar_text',  __( 'Podpořte rodiny v Ústeckém kraji — každý příspěvek pomáhá dětem najít bezpečný domov.', 'cpnrp' ) );
$btn1_txt = get_theme_mod( 'cpnrp_donor_btn1_text', __( 'Běh pro rodinu', 'cpnrp' ) );
$btn1_url = get_theme_mod( 'cpnrp_donor_btn1_url',  home_url( '/beh-pro-rodinu' ) );
$btn2_txt = get_theme_mod( 'cpnrp_donor_btn2_text', __( 'Podpořte nás', 'cpnrp' ) );
$btn2_url = get_theme_mod( 'cpnrp_donor_btn2_url',  home_url( '/podporte-nas' ) );
?>
<div class="donor-bar">
	<div class="container">
		<div class="donor-bar-inner">

			<p class="donor-bar-text"><?php echo esc_html( $text ); ?></p>

			<div class="donor-bar-actions">
				<a href="<?php echo esc_url( $btn1_url ); ?>" class="btn btn--gold">
					<svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
					</svg>
					<?php echo esc_html( $btn1_txt ); ?>
				</a>
				<a href="<?php echo esc_url( $btn2_url ); ?>" class="btn btn--outline">
					<svg class="btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M12 2L15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2z"/>
					</svg>
					<?php echo esc_html( $btn2_txt ); ?>
				</a>
			</div>

		</div>
	</div>
</div>
