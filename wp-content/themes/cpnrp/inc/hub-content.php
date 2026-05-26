<?php
/**
 * Hub subpage content setup — runs once on admin_init.
 * Assigns page-podstranka.php template, populates HTML content,
 * and registers child pages in the navigation menu.
 */

add_action( 'admin_init', 'cpnrp_hub_content_setup_v1' );
function cpnrp_hub_content_setup_v1() {
	if ( get_transient( 'cpnrp_hub_content_v1' ) ) return;

	// ── Helpers ──────────────────────────────────────────────────
	// Convert Astro-style content array to HTML
	// **text** → <strong>, • item → <li>, blank → paragraph
	if ( ! function_exists( '_hub_rich' ) ) :
	function _hub_rich( array $lines ): string {
		$html    = '';
		$in_list = false;
		foreach ( $lines as $line ) {
			$line = trim( $line );
			if ( strpos( $line, '• ' ) === 0 ) {
				if ( ! $in_list ) { $html .= '<ul class="subpage-list">'; $in_list = true; }
				$item = mb_substr( $line, 2 );
				$item = preg_replace( '/\*\*(.*?)\*\*/', '<strong>$1</strong>', $item );
				$html .= '<li>' . $item . '</li>';
			} else {
				if ( $in_list ) { $html .= '</ul>'; $in_list = false; }
				if ( preg_match( '/^\*\*(.*?)\*\*$/', $line, $m ) ) {
					$html .= '<h3 class="subpage-heading">' . esc_html( $m[1] ) . '</h3>';
				} elseif ( $line !== '' ) {
					$line = preg_replace( '/\*\*(.*?)\*\*/', '<strong>$1</strong>', $line );
					$line = nl2br( $line );
					$html .= '<p>' . $line . '</p>';
				}
			}
		}
		if ( $in_list ) $html .= '</ul>';
		return $html;
	}
	endif; // _hub_rich

	// Journey step HTML
	if ( ! function_exists( '_hub_step' ) ) :
	function _hub_step( int $n, string $title, string $desc, string $dur, array $we, array $you, string $tip = '', string $tip_author = '' ): string {
		$num  = str_pad( $n, 2, '0', STR_PAD_LEFT );
		$h    = '<div class="journey-step">';
		$h   .= '<div class="journey-step-num">' . $num . '</div>';
		$h   .= '<div class="journey-step-body">';
		$h   .= '<h3 class="journey-step-title">' . esc_html( $title ) . '</h3>';
		if ( $dur ) $h .= '<span class="journey-step-dur">' . esc_html( $dur ) . '</span>';
		$h   .= '<p class="journey-step-desc">' . esc_html( $desc ) . '</p>';
		if ( $we || $you ) {
			$h .= '<div class="journey-step-roles">';
			if ( $we ) {
				$h .= '<div class="journey-step-role"><h4>My uděláme:</h4><ul>';
				foreach ( $we as $w ) $h .= '<li>' . esc_html( $w ) . '</li>';
				$h .= '</ul></div>';
			}
			if ( $you ) {
				$h .= '<div class="journey-step-role"><h4>Vy uděláte:</h4><ul>';
				foreach ( $you as $y ) $h .= '<li>' . esc_html( $y ) . '</li>';
				$h .= '</ul></div>';
			}
			$h .= '</div>';
		}
		if ( $tip ) {
			$h .= '<blockquote class="journey-tip"><p>' . esc_html( $tip ) . '</p>';
			if ( $tip_author ) $h .= '<cite>' . esc_html( $tip_author ) . '</cite>';
			$h .= '</blockquote>';
		}
		$h .= '</div></div>';
		return $h;
	}
	endif; // _hub_step

	// ── Content data ─────────────────────────────────────────────

	// ADOPCE — Jak začít (ID 21804)
	$adopce_jak_zacit = '<p class="subpage-intro">Osvojení (adopce) je forma náhradní rodinné péče, při které vzniká mezi osvojitelem a dítětem právní vztah totožný se vztahem mezi biologickým rodičem a dítětem. Cesta k adopci má devět kroků — provedeme vás všemi.</p><div class="journey-steps">';
	$adopce_jak_zacit .= _hub_step(1,'Podání žádosti','Žádost se podává na obecním úřadě s rozšířenou působností (OSPOD) v místě vašeho trvalého bydliště.','1 den',['Pomůžeme s orientací — co bude úřad chtít a proč','Zodpovíme otázky bez závazku ještě před podáním'],['Vyplnit dotazník na OSPOD','Doložit lékařský posudek od praktického lékaře','Občanský průkaz, výpis z rejstříku trestů']);
	$adopce_jak_zacit .= _hub_step(2,'Sociální šetření','Sociální pracovnice navštíví vaši domácnost a posoudí celkové podmínky pro přijetí dítěte.','1–2 návštěvy',['Připravíme vás na to, co pracovnice sleduje','Sdílíme zkušenosti rodin, které šetřením prošly'],['Být doma s celou rodinou','Být upřímní — nejde o test, ale o porozumění']);
	$adopce_jak_zacit .= _hub_step(3,'Posouzení krajským úřadem','Krajský úřad ověří vaši bezúhonnost, zdravotní způsobilost a celkovou připravenost na adoptivní rodičovství.','1–3 měsíce',['Vysvětlíme, co úřad prověřuje a proč','Pomůžeme s orientací v dokumentech'],['Doložit potvrzení o příjmech a rodinném stavu','Trpělivě počkat na rozhodnutí']);
	$adopce_jak_zacit .= _hub_step(4,'Povinná odborná příprava','Absolvujete přípravný kurz v rozsahu minimálně 48 hodin zaměřený na specifika osvojení.','48 h · ~3 měsíce',['Vedeme přípravu — zkušení lektoři, psychologové, právníci','Setkáte se s rodinami, které osvojením již prošly'],['Aktivní účast na všech blocích','Otevřenost vůči novým pohledům'],'Příprava není zkouška. Je to bezpečný prostor, kde si můžete v klidu rozmyslet, jestli je adopce pro vás cesta. Nikoho nesoudíme — naopak, čím dřív si položíte těžké otázky, tím lépe.','Charlotta Kočí, vedoucí programu pro osvojitele');
	$adopce_jak_zacit .= _hub_step(5,'Psychologické posouzení','Psycholog posoudí vaši motivaci, osobnostní předpoklady a připravenost na přijetí dítěte.','1–2 sezení',['Vysvětlíme, co psycholog obvykle zjišťuje','Pomůžeme zpracovat případnou nervozitu'],['Otevřenost o své motivaci a očekávání','Reflexe vlastní rodinné historie']);
	$adopce_jak_zacit .= _hub_step(6,'Zařazení do evidence','Po úspěšném posouzení vás krajský úřad zařadí do evidence žadatelů o osvojení.','Rozhodnutí do 30 dnů',['Pomůžeme s orientací v evidenci a dalších krocích','Zůstáváme v kontaktu po celou dobu čekání'],['Trpělivost — délka čekání se těžko odhaduje']);
	$adopce_jak_zacit .= _hub_step(7,'Seznámení s dítětem','Když se najde vhodné spojení, proběhne postupné seznamování pod dohledem odborníků.','Týdny až měsíce',['Provedeme vás prvním setkáním a dalšími fázemi','Pomůžeme se zvládnutím radosti i nejistoty'],['Respektovat tempo dítěte','Otevřená komunikace s pracovníky']);
	$adopce_jak_zacit .= _hub_step(8,'Předadopční péče','Zkušební období minimálně 6 měsíců, během kterého dítě žije ve vaší rodině.','Min. 6 měsíců',['Pravidelné konzultace a krizová podpora','Praktická pomoc s adaptací dítěte'],['Trpělivost při adaptaci','Sdílet, jak to jde — i to, co je těžké']);
	$adopce_jak_zacit .= _hub_step(9,'Soudní rozhodnutí','Soud rozhodne o osvojení. Po třech letech od rozhodnutí již osvojení nelze zrušit.','1 jednání',['Připravíme vás na soudní jednání','Doprovodíme — pokud chcete'],['Užít si moment, kdy je rodina právně dovršená']);
	$adopce_jak_zacit .= '</div><div class="journey-postscript"><p>Věkový rozdíl mezi osvojitelem a dítětem musí být minimálně 16 let. Osvojit mohou manželé společně, jednotlivec, nebo jeden z partnerů.</p><p>Rodiče jsou povinni informovat dítě o osvojení nejpozději před nástupem do školy.</p></div>';

	// PP — Jak začít (ID 21811)
	$pp_jak_zacit = '<p class="subpage-intro">Pěstounská péče je forma náhradní rodinné péče, kde pěstoun osobně pečuje o dítě a odpovídá za jeho výchovu. Na rozdíl od osvojení nevzniká příbuzenský vztah — biologičtí rodiče zůstávají zákonnými zástupci dítěte. Cesta k pěstounství má osm kroků a my vás jimi provedeme.</p><div class="journey-steps">';
	$pp_jak_zacit .= _hub_step(1,'Podání žádosti','Žádost podáváte na obecním úřadě s rozšířenou působností (OSPOD) v místě trvalého bydliště.','1 den',['Pomůžeme s orientací — na co se úřad ptá','Zodpovíme otázky bez závazku'],['Vyplnit dotazník na OSPOD','Doložit lékařský posudek','Občanský průkaz, výpis z rejstříku trestů']);
	$pp_jak_zacit .= _hub_step(2,'Sociální šetření','Sociální pracovnice navštíví vaši domácnost a posoudí celkové podmínky pro přijetí dítěte.','1–2 návštěvy',['Připravíme vás na to, co pracovnice sleduje','Sdílíme zkušenosti rodin, které šetřením prošly'],['Být doma s rodinou','Být upřímní — nejde o test, ale o porozumění']);
	$pp_jak_zacit .= _hub_step(3,'Posouzení krajským úřadem','Krajský úřad ověří bezúhonnost, zdravotní způsobilost a celkovou připravenost.','1–3 měsíce',['Vysvětlíme, co úřad prověřuje a proč','Pomůžeme s dokumenty'],['Doložit potvrzení o příjmech, rodinném stavu','Trpělivě počkat']);
	$pp_jak_zacit .= _hub_step(4,'Povinná odborná příprava','Příprava trvá minimálně 48 hodin pro klasické pěstouny a 72 hodin pro pěstouny na přechodnou dobu (PPPD).','48 h (PP) / 72 h (PPPD)',['Vedeme přípravu — psychologové, sociální pracovníci, právníci','Setkáte se s pěstouny, kteří již péči vykonávají'],['Aktivní účast na všech blocích','Otevřenost vůči novým pohledům'],'Příprava není zkouška. Většina lidí si tady ujasní, jestli je pro ně klasická pěstounská péče, PPPD, nebo se rozhodne jinak. Tohle je dobré místo, kde se to dozvědět včas.','Mgr. Radka Strýalová, vedoucí doprovázení');
	$pp_jak_zacit .= _hub_step(5,'Psychologické posouzení','Psycholog posoudí vaši motivaci a osobnostní předpoklady pro pěstounskou roli.','1–2 sezení',['Vysvětlíme, co psycholog obvykle zjišťuje','Pomůžeme zpracovat případnou nervozitu'],['Otevřenost o motivaci','Reflexe vlastní rodinné historie']);
	$pp_jak_zacit .= _hub_step(6,'Zařazení do evidence','Krajský úřad rozhodne o zařazení do evidence žadatelů o pěstounskou péči.','Rozhodnutí do 30 dnů',['Zůstáváme v kontaktu po celou dobu čekání','Pomůžeme s orientací v dalším postupu'],['Trpělivost — délka čekání závisí na mnoha faktorech']);
	$pp_jak_zacit .= _hub_step(7,'Výběr a seznámení','Když se najde vhodné dítě, proběhne postupné seznamování pod dohledem odborníků.','Týdny až měsíce',['Provedeme vás prvním setkáním','Pomůžeme se zvládnutím radosti i nejistoty'],['Respektovat tempo dítěte','Otevřená komunikace s pracovníky']);
	$pp_jak_zacit .= _hub_step(8,'Soudní rozhodnutí','Soud rozhodne o svěření dítěte do pěstounské péče. Tím začíná každodenní život s dítětem — a doprovázení od nás.','1 jednání',['Doprovodíme na soudní jednání, pokud chcete','Hned po rozhodnutí přiřadíme klíčového pracovníka'],['Uzavřít dohodu o výkonu pěstounské péče s doprovázející organizací']);
	$pp_jak_zacit .= '</div><div class="journey-postscript"><p><strong>Formy pěstounské péče:</strong> Zprostředkovaná pěstounská péče (pro děti, u nichž se očekává pobyt mimo biologickou rodinu delší než rok) · Pěstounská péče na přechodnou dobu — PPPD (krizové řešení max. na 1 rok, příprava 72 h) · Nezprostředkovaná pěstounská péče (nejčastěji péče prarodičů nebo příbuzných) · Poručenství (poručník má veškerá rodičovská práva).</p><p>Pěstouni mají nárok na <strong>odměnu pěstouna</strong>, <strong>příspěvek na úhradu potřeb dítěte</strong> a <strong>jednorázový příspěvek</strong> při převzetí. Péče trvá nejdéle do zletilosti dítěte, případně do 26 let při studiu.</p></div>';

	// FAQ content (ID 21822)
	$faqs = [
		['Kdo se může stát pěstounem?', 'Každá osoba, která skýtá záruku řádné péče, má bydliště v ČR a souhlasí se svěřením dítěte. Pěstounem mohou být i jednotlivci, nejen páry. Není stanovena horní věková hranice.'],
		['Jak dlouho celý proces trvá?', 'Od podání žádosti po zařazení do evidence obvykle 6–12 měsíců. Samotné čekání na dítě závisí na mnoha faktorech.'],
		['Jaké finanční dávky pěstouni pobírají?', 'Odměnu pěstouna (měsíční), příspěvek na úhradu potřeb dítěte a jednorázový příspěvek při převzetí dítěte. Výše závisí na věku a počtu dětí.'],
		['Musím mít vlastní bydlení?', 'Vlastní bydlení není podmínkou, ale musíte mít zajištěné stabilní a vhodné bydlení pro dítě.'],
		['Mohu pěstounovat, i když mám vlastní děti?', 'Ano. Mnoho pěstounských rodin má vlastní biologické děti. Při přípravě se věnujeme i dopadu na ostatní členy rodiny.'],
		['Mohu si vybrat věk nebo pohlaví dítěte?', 'Při podání žádosti uvádíte své preference, ale čím širší jsou, tím rychleji může dojít ke zprostředkování.'],
		['Co když to nebude fungovat?', 'Pěstounská péče může být ukončena, pokud to vyžaduje zájem dítěte. Doprovázející organizace vám pomůže řešit obtížné situace dříve, než dojde ke krizi.'],
		['Jaký je rozdíl mezi pěstounem a poručníkem?', 'Poručník má veškerá rodičovská práva a je zákonným zástupcem dítěte. Pěstoun rozhoduje pouze o běžných záležitostech — pro mimořádné věci potřebuje souhlas biologického rodiče nebo soudu.'],
		['Kde začít?', 'Kontaktujte nás na info@cpnrp.cz nebo +420 731 557 681. První konzultace je nezávazná a bezplatná.'],
	];
	$faq_html = '<div class="faq-list">';
	foreach ( $faqs as $faq ) {
		$faq_html .= '<details class="faq-item"><summary class="faq-question"><span>' . esc_html( $faq[0] ) . '</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg></summary><div class="faq-answer"><p>' . esc_html( $faq[1] ) . '</p></div></details>';
	}
	$faq_html .= '</div>';

	// All pages: [ page_id, content_html ]
	$pages_content = [
		// Adopce
		21804 => $adopce_jak_zacit,
		21805 => _hub_rich(['Povinná odborná příprava je nezbytnou součástí procesu osvojení. Kurzy organizuje krajský úřad ve spolupráci s pověřenými organizacemi, jako je CPNRP.','Příprava zahrnuje minimálně **48 hodin** odborného školení a je zaměřena na specifika péče o děti z náhradní rodinné péče.','**Co se v kurzech dozvíte:**','• Psychologie dítěte v náhradní péči — trauma, attachment, vývojové potřeby','• Právní rámec osvojení v České republice','• Komunikace s dítětem o jeho původu a identitě','• Praktické aspekty každodenní péče a výchovy','• Specifika adaptačního období po přijetí dítěte','• Zkušenosti dalších osvojitelů a pěstounů','Kurzy vedou zkušení lektoři — psychologové, sociální pracovníci a právníci. Součástí je také setkání s rodinami, které již osvojením prošly.','Aktuální termíny přípravných kurzů a přihlášení získáte na naší pobočce nebo telefonicky.']),
		21806 => _hub_rich(['Nabízíme komplexní odborné poradenství pro adoptivní rodiny ve všech fázích — od rozhodování přes proces osvojení až po každodenní život s dítětem.','**Psychologické poradenství:** Individuální konzultace s psychologem nebo terapeutem zaměřené na adaptaci dítěte, výchovné obtíže, zpracování traumatu a rodinné vztahy.','**Právní poradenství:** Pomoc s orientací v právním rámci osvojení, zastupování před soudem, řešení právních otázek spojených s kontaktem s biologickou rodinou.','**Sociální poradenství:** Podpora při jednání s úřady (OSPOD, soudy), zprostředkování dalších služeb a pomoc v krizových situacích.','**Poradenství pro děti:** Odborná pomoc psychologa, terapeuta nebo dětského psychiatra pro děti, které procházejí adaptací nebo se potýkají s otázkami identity.','Poradenství je poskytováno na našich pobočkách v Litoměřicích, Ústí nad Labem a Rumburku. V případě potřeby zajišťujeme i terénní konzultace přímo v rodině.']),
		21807 => _hub_rich(['Organizujeme pravidelné vzdělávací semináře a besedy určené osvojitelským rodinám. Semináře vedou zkušení odborníci z oblasti psychologie, pedagogiky a sociální práce.','**Témata seminářů:**','• Jak mluvit s dítětem o osvojení — kdy a jak','• Vývojové potřeby dětí v náhradní péči','• Zvládání náročného chování','• Identita dítěte a otázky původu','• Sourozenecké vztahy v adoptivní rodině','• Dospívání a adolescence v kontextu osvojení','**Skupinová setkávání rodičů:** Pravidelná setkání osvojitelů, kde můžete sdílet zkušenosti, podporovat se navzájem a konzultovat s odborníky.','**Supervize pro rodiče:** Odborně vedená reflexe rodičovské role a výchovných postupů.','Semináře probíhají na našich pobočkách. Aktuální program najdete v kalendáři akcí.']),
		21808 => _hub_rich(['Víkendové pobyty jsou příležitostí pro osvojitelské rodiny k odpočinku, sdílení zkušeností a vzájemné podpoře v neformálním prostředí.','**Co pobyty nabízejí:**','• Program pro děti i dospělé — tvořivé dílny, sportovní aktivity, výlety','• Odborné přednášky a workshopy pro rodiče','• Prostor pro neformální setkávání a sdílení','• Možnost individuálních konzultací s odborníky','• Společné aktivity pro celé rodiny','Pobyty organizujeme několikrát ročně na různých místech Ústeckého kraje. Jsou plně hrazeny z projektu nebo za symbolický poplatek.','Pro rodiny jsou pobyty cennou příležitostí — děti se setkávají s vrstevníky v podobné situaci a rodiče získávají oporu a inspiraci od ostatních.']),
		21809 => _hub_rich(['Pro osvojitelské rodiny a zájemce o osvojení je k dispozici:','**Charlotta Kočí** — vedoucí programu pro osvojitele','Telefon: +420 771 770 380','E-mail: koci@cpnrp.cz','**Kde nás najdete:**','• Litoměřice — Teplická 1672/3, 412 01 (Po–Pá 8:00–16:00)','• Ústí nad Labem — V Jirchářích 60/6, 400 02 (Po, St 8:00–16:00)','Neváhejte se na nás obrátit — první konzultace je nezávazná a bezplatná.']),
		// PP
		21811 => $pp_jak_zacit,
		21812 => _hub_rich(['Každý pěstoun má zákonnou povinnost uzavřít **Dohodu o výkonu pěstounské péče** s doprovázející organizací. CPNRP je jednou z organizací s pověřením k uzavírání těchto dohod v Ústeckém kraji.','**Co doprovázení zahrnuje:**','• Pravidelný kontakt s **klíčovým sociálním pracovníkem**, který rodinu navštěvuje a pomáhá řešit každodenní situace','• Podporu při výchově a péči o svěřené dítě','• Zprostředkování odborných služeb — psycholog, terapeut, právník','• Pomoc při komunikaci s OSPOD, soudy a biologickou rodinou','• Zajištění povinného vzdělávání pěstounů (24 hodin ročně)','• Zprostředkování odlehčovacích služeb','Náš tým tvoří **14 terénních sociálních pracovníků**, kteří působí v celém Ústeckém kraji. Pracovník rodinu pravidelně navštěvuje, je k dispozici telefonicky a pomáhá s orientací v systému péče o děti.','Doprovázení je pro pěstounské rodiny **bezplatné** — je hrazeno ze státního příspěvku na výkon pěstounské péče.']),
		21813 => _hub_rich(['Poskytujeme komplexní odborné poradenství pro pěstounské a poručenské rodiny.','**Psychologické poradenství:** Individuální konzultace zaměřené na adaptaci dítěte, výchovné obtíže, zvládání traumatu, attachment a rodinné vztahy.','**Právní poradenství:** Orientace v právních otázkách pěstounské péče, zastupování při jednáních, pomoc s kontaktem s biologickou rodinou.','**Sociální poradenství:** Podpora při jednání s úřady, zprostředkování dalších služeb, krizová intervence.','**Poradenství pro děti:** Psycholog, terapeut nebo dětský psychiatra pro děti v náhradní péči — zpracování traumatu, otázky identity, vývojové potíže.','Poradenství je dostupné na našich pobočkách:','• **Litoměřice** — Teplická 1672/3 (Po–Pá 8:00–16:00)','• **Ústí nad Labem** — V Jirchářích 60/6 (Po, St 8:00–16:00)','• **Rumburk** — Matušova 982 (St 9:00–16:00)','V případě potřeby zajišťujeme i terénní konzultace přímo v rodině.']),
		21814 => _hub_rich(['Pěstouni mají ze zákona povinnost absolvovat **24 hodin vzdělávání ročně**. CPNRP zajišťuje toto vzdělávání formou seminářů, workshopů a konferencí.','**Témata vzdělávání:**','• Vývojové potřeby dětí v náhradní péči','• Zvládání náročného chování a trauma-informovaný přístup','• Komunikace s biologickou rodinou dítěte','• Právní rámec pěstounské péče — aktuální změny','• Dospívání a adolescence v kontextu NRP','• Sourozenecké vztahy a dynamika pěstounské rodiny','• Sebezkušenostní semináře a prevence syndromu vyhoření','**Formy vzdělávání:**','• Prezenční semináře na pobočkách CPNRP','• Workshopy vedené externími odborníky','• Skupinové supervize','• Konference a odborné besedy','Vzdělávání vedou zkušení lektoři — psychologové, terapeuti, sociální pracovníci a právníci. Účast na vzdělávání je pro pěstouny v doprovázení CPNRP bezplatná.']),
		21815 => _hub_rich(['Pěstouni mají zákonný nárok na odlehčovací služby (respitní péči). Tyto služby slouží jako oddech a načerpání sil pro pěstounské rodiny.','**Rozsah:** Minimálně 4 hodiny, maximálně 14 dní ročně.','**Formy odlehčení:**','• **Akce pořádané CPNRP** — víkendové pobyty, tábory, výlety, kde se o děti starají zkušení pracovníci','• **Individuální odlehčení** — zajištění péče o dítě v době nepřítomnosti pěstouna','• **Krátkodobá pomoc** při zdravotní neschopnosti pěstouna, narození vlastního dítěte nebo úmrtí blízké osoby','Odlehčovací služby musejí být vždy v zájmu dítěte. Péče je zajišťována proškolenými pracovníky nebo ověřenými pečovateli.','V rámci odlehčení organizujeme také **dětské kluby** a **doučování** — pravidelné aktivity, kde se o děti starají převážně studenti středních a vysokých škol pod odborným vedením.','CPNRP disponuje přibližně **50 doučovateli a pečovateli**, kteří působí v rodinách po celém Ústeckém kraji.']),
		21816 => _hub_rich(['Pěstouni mají **zákonnou povinnost** podporovat dítě v kontaktu se svou biologickou rodinou, pokud soud nerozhodne jinak.','**Co asistovaný kontakt zahrnuje:**','• Přípravu dítěte na setkání s biologickými rodiči nebo příbuznými','• Odbornou asistenci během samotného kontaktu — přítomnost sociálního pracovníka','• Zajištění bezpečného a neutrálního prostředí pro setkání','• Podporu pěstounů při zvládání emocí spojených s kontaktem','• Poradenství při jednáních na OSPOD ohledně rozsahu a formy kontaktu','**Proč je kontakt důležitý:**','Děti potřebují znát svůj příběh a mít reálný obraz svých biologických rodičů. Bezpečný a odborně vedený kontakt pomáhá dítěti budovat zdravou identitu.','Kontakt nemusí být vždy osobní — může mít formu dopisů, telefonátů nebo videohovorů. Formu a rozsah vždy přizpůsobujeme potřebám a zájmům dítěte.']),
		21817 => _hub_rich(['Pro pěstounské a poručenské rodiny je k dispozici:','**Mgr. Radka Strýalová** — vedoucí doprovázení','Telefon: +420 771 770 490','E-mail: stryalova@cpnrp.cz','**Naše pobočky:**','• **Litoměřice — Poradna:** Teplická 1672/3, 412 01 (Po–Pá 8:00–16:00), tel: +420 416 533 554','• **Litoměřice — Centrum:** 5. května 76, 412 01 (Po 8:30–16:00), tel: +420 731 557 681','• **Ústí nad Labem:** V Jirchářích 60/6, 400 02 (Po, St 8:00–16:00), tel: +420 771 770 360','• **Rumburk:** Matušova 982, 408 01 (St 9:00–16:00), tel: +420 771 770 360','**Obecný kontakt:** info@cpnrp.cz','Neváhejte se na nás obrátit — jsme tu pro vás.']),
		// Zájemci
		21819 => _hub_rich(['Náhradní rodinná péče (NRP) je souhrnný pojem pro situace, kdy se o dítě starají jiní lidé než jeho biologičtí rodiče. Děti přicházejí do náhradní péče nejčastěji z nefungujících rodin nebo z ústavní péče.','**Formy náhradní rodinné péče v ČR:**','• **Osvojení (adopce)** — vzniká právní vztah totožný s biologickým rodičovstvím. Osvojitelé jsou zapsáni v rodném listu, původní příbuzenské vztahy zanikají. Osvojení je trvalé.','• **Pěstounská péče** — pěstoun pečuje o dítě, ale nevzniká příbuzenský vztah. Biologičtí rodiče zůstávají zákonnými zástupci. Péče trvá nejdéle do zletilosti.','• **Pěstounská péče na přechodnou dobu** — krizové řešení maximálně na 1 rok, než se najde trvalé řešení pro dítě.','• **Poručenství** — poručník má veškerá rodičovská práva a je zákonným zástupcem dítěte.','• **Svěření do péče jiné osoby** — nejčastěji péče prarodičů nebo příbuzných.','Cílem náhradní rodinné péče je zajistit, aby každé dítě vyrůstalo v bezpečném, stabilním a milujícím prostředí. Rodina — i ta náhradní — je pro zdravý vývoj dítěte nenahraditelná.','V České republice stále žijí tisíce dětí v ústavní péči. Každý, kdo se rozhodne otevřít svůj domov dítěti v nouzi, mění jeho život k lepšímu.']),
		21820 => _hub_rich(['Rozhodujete se, co je pro vás vhodnější? Hlavní rozdíl spočívá v právní povaze vztahu a délce péče.','**Adopce (Osvojení):**','• Vzniká trvalý právní vztah — jakoby biologické rodičovství','• Osvoj itelé jsou zapsáni v rodném listu','• Biologické příbuzenské vztahy zanikají','• Nejčastěji pro děti do 3 let','• Péče je trvalá — nelze ji ukončit','**Pěstounská péče:**','• Nevzniká příbuzenský vztah — biologičtí rodiče zůstávají zákonným zástupci','• Pěstoun pečuje o každodenní potřeby dítěte','• Dítě zůstává v kontaktu s biologickou rodinou (pokud to nebrání jeho zájmu)','• Péče trvá nejdéle do zletilosti (26 let při studiu)','• Pěstouni pobírají odměnu a dávky','Nejste si jistí? Obraťte se na nás — bezplatná konzultace vám pomůže zorientovat se.']),
		21821 => _hub_rich(['Povinná odborná příprava je první a nezbytný krok na cestě k pěstounství nebo osvojení. Bez její absolvování nelze být zařazen do evidence žadatelů.','**Rozsah přípravy:**','• **48 hodin** pro žadatele o pěstounskou péči a osvojení','• **72 hodin** pro žadatele o pěstounskou péči na přechodnou dobu','**Co příprava zahrnuje:**','• Právní rámec náhradní rodinné péče v ČR','• Psychologie dítěte — trauma, attachment, vývojové potřeby','• Specifika péče o děti z ústavního prostředí','• Komunikace s dítětem o jeho původu','• Kontakt s biologickou rodinou — jak a proč','• Praktické aspekty každodenní péče','• Setkání s pěstouny a osvojiteli, kteří sdílejí své zkušenosti','Přípravné kurzy organizuje krajský úřad ve spolupráci s pověřenými organizacemi. CPNRP pravidelně zajišťuje přípravu žadatelů v Ústeckém kraji.','Pro aktuální termíny a přihlášení nás kontaktujte telefonicky nebo e-mailem.']),
		21822 => $faq_html,
	];

	// Assign template and content to each child page
	foreach ( $pages_content as $pid => $content ) {
		update_post_meta( $pid, '_wp_page_template', 'page-podstranka.php' );
		wp_update_post( [
			'ID'           => $pid,
			'post_content' => $content,
		] );
	}

	// ── Navigation menu ──────────────────────────────────────────
	// menu term_id = 57
	$menu_id = 57;

	// Items to add: [ page_id, parent_menu_item_id, menu_order ]
	$menu_items = [
		// Adopce children (parent menu item 21856)
		[ 21804, 21856,  8 ],
		[ 21805, 21856,  9 ],
		[ 21806, 21856, 10 ],
		[ 21807, 21856, 11 ],
		[ 21808, 21856, 12 ],
		[ 21809, 21856, 13 ],
		// PP children (parent menu item 21863)
		[ 21811, 21863, 15 ],
		[ 21812, 21863, 16 ],
		[ 21813, 21863, 17 ],
		[ 21814, 21863, 18 ],
		[ 21815, 21863, 19 ],
		[ 21816, 21863, 20 ],
		[ 21817, 21863, 21 ],
		// Zájemci children (parent menu item 21871)
		[ 21819, 21871, 23 ],
		[ 21820, 21871, 24 ],
		[ 21821, 21871, 25 ],
		[ 21822, 21871, 26 ],
	];

	foreach ( $menu_items as $item ) {
		[ $page_id, $parent_item_id, $order ] = $item;

		// Skip if already exists
		$exists = get_posts( [
			'post_type'   => 'nav_menu_item',
			'meta_query'  => [
				[ 'key' => '_menu_item_object_id', 'value' => $page_id ],
				[ 'key' => '_menu_item_menu_item_parent', 'value' => $parent_item_id ],
			],
			'numberposts' => 1,
		] );
		if ( $exists ) continue;

		wp_update_nav_menu_item( $menu_id, 0, [
			'menu-item-object-id'   => $page_id,
			'menu-item-object'      => 'page',
			'menu-item-type'        => 'post_type',
			'menu-item-parent-id'   => $parent_item_id,
			'menu-item-position'    => $order,
			'menu-item-status'      => 'publish',
		] );
	}

	set_transient( 'cpnrp_hub_content_v1', true );
}

// ── v2: Hero popisky pro všechny hub podstránky ───────────────────
add_action( 'admin_init', 'cpnrp_hub_content_setup_v2' );
function cpnrp_hub_content_setup_v2() {
	if ( get_transient( 'cpnrp_hub_content_v2' ) ) return;

	$descs = [
		// Adopce
		21804 => 'Devět kroků k osvojení — provedeme vás celým procesem od první myšlenky po soudní rozhodnutí.',
		21805 => 'Povinná odborná příprava v rozsahu 48 hodin — co vás čeká a jak se na kurzy přihlásit.',
		21806 => 'Psychologické, právní a sociální poradenství pro adoptivní rodiny ve všech fázích.',
		21807 => 'Odborné semináře, workshopy a skupinová setkání určená osvoji telským rodinám.',
		21808 => 'Víkendové pobyty pro osvoji telské rodiny — odpočinek, sdílení zkušeností a odborná podpora.',
		21809 => 'Kontaktní informace a přímé spojení na tým pro žadatele o adopci.',
		// Pěstounská péče
		21811 => 'Osm kroků k pěstounství — od první žádosti přes přípravu až po soudní svěření dítěte.',
		21812 => 'Zákonné doprovázení pěstounských rodin — pravidelná podpora klíčového pracovníka po celou dobu péče.',
		21813 => 'Psychologické, právní a sociální poradenství pro pěstounské a poručenské rodiny.',
		21814 => 'Povinné vzdělávání pěstounů — 24 hodin ročně, semináře a workshopy hrazené v rámci doprovázení.',
		21815 => 'Respitní péče pro pěstouny — zákonný nárok na oddych a načerpání sil.',
		21816 => 'Odborně vedená setkání dětí s biologickou rodinou — bezpečně a vždy v zájmu dítěte.',
		21817 => 'Kontaktní informace a přímé spojení na tým pro pěstounské a poručenské rodiny.',
		// Zájemci
		21819 => 'Přehled všech forem náhradní rodinné péče — adopce, pěstounství, poručenství a jejich rozdíly.',
		21820 => 'Pomůžeme vám zorientovat se — jaký je rozdíl mezi adopcí a pěstounstvím a co je pro vás vhodnější.',
		21821 => 'Co vás čeká na přípravných kurzech — rozsah, obsah a jak se přihlásit.',
		21822 => 'Nejčastější otázky a odpovědi pro zájemce o náhradní rodinnou péči.',
	];

	foreach ( $descs as $pid => $desc ) {
		update_post_meta( $pid, '_subpage_hero_desc', $desc );
	}

	set_transient( 'cpnrp_hub_content_v2', true );
}
