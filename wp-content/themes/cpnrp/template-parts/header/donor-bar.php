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
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/medaile.png' ); ?>" alt="" aria-hidden="true" class="btn-icon" width="18" height="18">
					<?php echo esc_html( $btn1_txt ); ?>
				</a>
				<a href="<?php echo esc_url( $btn2_url ); ?>" class="btn btn--outline" target="_blank" rel="noopener noreferrer">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/maska.png' ); ?>" alt="" aria-hidden="true" class="btn-icon" width="18" height="18">
					<?php echo esc_html( $btn2_txt ); ?>
				</a>
			</div>

		</div>
	</div>
</div>
