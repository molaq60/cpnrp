<?php
/**
 * Pro rodiny section — one-time content + template assignment.
 * Runs on admin_init, guarded by transient 'cpnrp_pro_rodiny_v1'.
 *
 * Pages handled:
 *   21794  Pro rodiny          → page-pro-rodiny.php   + meta
 *   21847  Chci adoptovat      → page-podstranka.php   + hero_desc + content
 *   21848  Chci pěstounovat    → page-podstranka.php   + hero_desc + content
 *   21849  Jsem pěstoun        → page-jsem-pestoun.php + meta + content
 *   21820  Adopce nebo PP?     → page-adopce-vs-pestounstvi.php + hero_desc
 */

add_action( 'admin_init', 'cpnrp_pro_rodiny_setup_v1' );
function cpnrp_pro_rodiny_setup_v1() {
	if ( get_transient( 'cpnrp_pro_rodiny_v1' ) ) return;

	// ── Simple journey step (no roles, no tips) ─────────────────

	if ( ! function_exists( '_jp_step' ) ) :
	function _jp_step( int $n, string $title, string $desc ): string {
		$num = str_pad( $n, 2, '0', STR_PAD_LEFT );
		return '<div class="journey-step">'
			. '<div class="journey-step-num">' . $num . '</div>'
			. '<div class="journey-step-body">'
			. '<h3 class="journey-step-title">' . esc_html( $title ) . '</h3>'
			. '<p class="journey-step-desc">' . esc_html( $desc ) . '</p>'
			. '</div></div>';
	}
	endif;

	if ( ! function_exists( '_jp_service' ) ) :
	function _jp_service( int $n, string $title, string $desc ): string {
		$num = str_pad( $n, 2, '0', STR_PAD_LEFT );
		return '<div class="jp-service">'
			. '<div class="jp-service-num">' . $num . '</div>'
			. '<div class="jp-service-body">'
			. '<h3 class="jp-service-title">' . esc_html( $title ) . '</h3>'
			. '<p class="jp-service-desc">' . esc_html( $desc ) . '</p>'
			. '</div></div>';
	}
	endif;

	// ── Chci adoptovat — content ────────────────────────────────

	$adopce_steps  = '<p class="subpage-intro">Osvojení je forma náhradní rodinné péče, při které vzniká mezi osvojitelem a dítětem právní vztah obdobný vztahu mezi rodičem a vlastním dítětem. Osvojením zanikají právní vazby dítěte k biologické rodině.</p>';
	$adopce_steps .= '<p class="subpage-intro">Na rozdíl od pěstounství je osvojení trvalé řešení. Osvojitelé mají plná rodičovská práva a povinnosti, dítě přebírá příjmení osvojitelů a je zapsáno do rodného listu jako jejich dítě.</p>';
	$adopce_steps .= '<p class="hub-eyebrow" style="margin-top:2.5rem">Proces osvojení</p>';
	$adopce_steps .= '<h2 class="hub-section-title" style="margin-bottom:2rem">Šest kroků k novému domovu pro dítě</h2>';
	$adopce_steps .= '<div class="journey-steps">';
	$adopce_steps .= _jp_step( 1, 'Podání žádosti', 'Žádost se podává na obecním úřadě obce s rozšířenou působností v místě trvalého bydliště.' );
	$adopce_steps .= _jp_step( 2, 'Odborné posouzení', 'Psychologické vyšetření, sociální šetření v domácnosti a posouzení zdravotního stavu žadatelů.' );
	$adopce_steps .= _jp_step( 3, 'Přípravné kurzy', 'Povinná příprava pro zájemce o osvojení. Kurzy se zaměřují na specifika adopce a potřeby dětí.' );
	$adopce_steps .= _jp_step( 4, 'Zařazení do evidence', 'Po úspěšném posouzení krajský úřad vydá rozhodnutí o zařazení do evidence žadatelů.' );
	$adopce_steps .= _jp_step( 5, 'Seznámení s dítětem', 'Zprostředkování a postupné seznamování s dítětem pod dohledem odborníků.' );
	$adopce_steps .= _jp_step( 6, 'Soudní rozhodnutí', 'Osvojení je dokončeno pravomocným rozhodnutím soudu. Před tím probíhá tříměsíční předadopční péče.' );
	$adopce_steps .= '</div>';

	// ── Chci pěstounovat — content ──────────────────────────────

	$pp_steps  = '<p class="hub-eyebrow">Cesta k pěstounství</p>';
	$pp_steps .= '<h2 class="hub-section-title" style="margin-bottom:2rem">Pět kroků, kterými vás provedeme</h2>';
	$pp_steps .= '<div class="journey-steps">';
	$pp_steps .= _jp_step( 1, 'Prvotní zájem', 'Kontaktujte nás nebo příslušný OSPOD. Rádi vám zodpovíme první otázky a vysvětlíme, co pěstounství obnáší.' );
	$pp_steps .= _jp_step( 2, 'Přípravné kurzy', 'Absolvujete povinnou přípravu v rozsahu 48–72 hodin. Kurzy vedou zkušení lektoři a zaměřují se na specifika náhradní rodinné péče.' );
	$pp_steps .= _jp_step( 3, 'Posouzení žádosti', 'Krajský úřad posoudí vaši žádost. Součástí je psychologické vyšetření, sociální šetření a posouzení bytových podmínek.' );
	$pp_steps .= _jp_step( 4, 'Zařazení do evidence', 'Po schválení jste zařazeni do evidence žadatelů. Čekací doba závisí na mnoha faktorech, včetně vašich preferencí.' );
	$pp_steps .= _jp_step( 5, 'Přijetí dítěte', 'Když se najde vhodné spojení, proběhne postupné seznamování. Naši pracovníci vás celým procesem provedou.' );
	$pp_steps .= '</div>';

	// FAQ for chci-pestounovat
	$pp_faqs = [
		[ 'Kdo se může stát pěstounem?', 'Pěstounem se může stát každý zletilý občan, který je zdravotně, osobnostně a bytově způsobilý. Pěstounem mohou být i jednotlivci, nejen páry.' ],
		[ 'Jak dlouho trvá příprava?', 'Přípravné kurzy trvají zpravidla 2–3 měsíce (48–72 hodin). Celý proces od podání žádosti po zařazení do evidence trvá přibližně 6–12 měsíců.' ],
		[ 'Jakou finanční podporu pěstouni dostávají?', 'Pěstouni mají nárok na odměnu pěstouna, příspěvek na úhradu potřeb dítěte, příspěvek při převzetí dítěte a příspěvek na zakoupení motorového vozidla.' ],
		[ 'Mohu pěstounovat, i když mám vlastní děti?', 'Ano, mnoho pěstounských rodin má i vlastní biologické děti. Při přípravě se věnujeme i tomu, jak přijetí nového člena rodiny ovlivní ostatní děti.' ],
	];
	$pp_steps .= '<p class="hub-eyebrow" style="margin-top:3rem">FAQ</p>';
	$pp_steps .= '<h2 class="hub-section-title" style="margin-bottom:1.5rem">Nejčastější otázky</h2>';
	$pp_steps .= '<div class="faq-list">';
	foreach ( $pp_faqs as $faq ) {
		$pp_steps .= '<details class="faq-item"><summary class="faq-question"><span>' . esc_html( $faq[0] ) . '</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg></summary><div class="faq-answer"><p>' . esc_html( $faq[1] ) . '</p></div></details>';
	}
	$pp_steps .= '</div>';

	// ── Jsem pěstoun — service cards content ────────────────────

	$services = [
		[ 'Odborné doprovázení',               'Každá pěstounská rodina má přiděleného klíčového pracovníka, který rodinu pravidelně navštěvuje, poskytuje poradenství a pomáhá řešit každodenní situace spojené s péčí o svěřené dítě.' ],
		[ 'Vzdělávání a semináře',             'Povinné i nadstavbové vzdělávání — 24 hodin ročně dle zákona. Témata zahrnují vývojovou psychologii, zvládání náročného chování a komunikaci s biologickou rodinou.' ],
		[ 'Podpůrné skupiny',                  'Pravidelná setkávání pěstounů pro sdílení zkušeností a vzájemnou podporu. Skupiny vedou zkušení facilitátoři a jsou bezpečným prostorem pro otevřenou diskusi.' ],
		[ 'Psychologické a právní poradenství', 'Individuální konzultace s psychologem nebo právníkem pro řešení konkrétních situací. Pomáháme s kontaktem s OSPOD, soudy i biologickou rodinou dítěte.' ],
		[ 'Krizová pomoc',                     'V náročných situacích jsme k dispozici i mimo běžnou pracovní dobu. Nabízíme krizovou intervenci, respitní péči a okamžitou odbornou pomoc.' ],
		[ 'Aktivity pro rodiny',               'Organizujeme výlety, tábory, víkendové pobyty a komunitní akce pro pěstounské rodiny. Děti i dospělí se mohou potkávat s dalšími rodinami v neformálním prostředí.' ],
	];
	$jsem_content = '';
	foreach ( $services as $i => [ $title, $desc ] ) {
		$jsem_content .= _jp_service( $i + 1, $title, $desc );
	}

	// ── Apply templates ──────────────────────────────────────────

	// Pro rodiny main
	update_post_meta( 21794, '_wp_page_template', 'page-pro-rodiny.php' );
	update_post_meta( 21794, '_pro_rodiny_hero_desc', 'Ať už jste pěstoun, uvažujete o pěstounství, nebo chcete adoptovat — jsme tu pro vás.' );
	update_post_meta( 21794, '_pro_rodiny_blockquote', '„Teprve se rozhodujete? Napište nám — rádi odpovíme na všechny otázky bez závazku."' );
	update_post_meta( 21794, '_pro_rodiny_blockquote_link', 'Domluvit konzultaci' );

	// Chci adoptovat
	update_post_meta( 21847, '_wp_page_template', 'page-podstranka.php' );
	update_post_meta( 21847, '_subpage_hero_desc', 'Osvojení (adopce) je právní akt, kterým se dítě stává plnoprávným členem nové rodiny. Pomůžeme vám celým procesem.' );
	wp_update_post( [ 'ID' => 21847, 'post_content' => $adopce_steps ] );

	// Chci pěstounovat
	update_post_meta( 21848, '_wp_page_template', 'page-podstranka.php' );
	update_post_meta( 21848, '_subpage_hero_desc', 'Uvažujete o pěstounství? Provedeme vás celým procesem od prvního kroku až po přijetí dítěte.' );
	wp_update_post( [ 'ID' => 21848, 'post_content' => $pp_steps ] );

	// Jsem pěstoun
	update_post_meta( 21849, '_wp_page_template', 'page-jsem-pestoun.php' );
	update_post_meta( 21849, '_jsem_pestoun_hero_desc', 'Podporujeme vás na každém kroku vaší pěstounské cesty. Nabízíme komplexní služby pro celou rodinu.' );
	update_post_meta( 21849, '_jsem_pestoun_eyebrow', 'Co pro vás máme' );
	update_post_meta( 21849, '_jsem_pestoun_section_heading', 'Naše služby pro pěstouny' );
	update_post_meta( 21849, '_jsem_pestoun_cta_heading', 'Máte zájem o naše služby?' );
	update_post_meta( 21849, '_jsem_pestoun_cta_desc', 'Kontaktujte nás a domluvíme se na prvním setkání.' );
	wp_update_post( [ 'ID' => 21849, 'post_content' => $jsem_content ] );

	// Adopce nebo pěstounství? (already under Zájemci)
	update_post_meta( 21820, '_wp_page_template', 'page-adopce-vs-pestounstvi.php' );
	update_post_meta( 21820, '_avp_hero_desc', 'Obě formy mají společný cíl — poskytnout dítěti milující rodinu. Liší se právním rámcem, trvalostí a povinnostmi.' );

	set_transient( 'cpnrp_pro_rodiny_v1', true );
}
