<?php
/**
 * Legal pages — one-time content setup.
 * Populates: /ochrana-udaju  (Privacy policy / GDPR)
 *            /cookies        (Cookie notice intro)
 */

add_action( 'admin_init', 'cpnrp_legal_content_v1' );
function cpnrp_legal_content_v1() {
	if ( get_transient( 'cpnrp_legal_v1' ) ) return;

	// ── Ochrana osobních údajů ────────────────────────────────────
	$gdpr = '
<p class="subpage-intro">Ochrana vašich osobních údajů je pro nás důležitá. Tento dokument vysvětluje, jaké osobní údaje zpracováváme, proč a jaká máte práva.</p>

<h2>Správce osobních údajů</h2>
<p>
	<strong>CPNRP o.p.s.</strong> (Centrum pro náhradní rodinnou péči)<br>
	Teplická 1672/3, 412 01 Litoměřice<br>
	E-mail: <a href="mailto:info@cpnrp.cz">info@cpnrp.cz</a><br>
	GDPR kontakt: <a href="mailto:gdpr@cpnrp.cz">gdpr@cpnrp.cz</a>
</p>
<p>Zpracování osobních údajů provádíme v souladu s nařízením Evropského parlamentu a Rady (EU) 2016/679 (GDPR) a zákonem č. 110/2019 Sb., o zpracování osobních údajů.</p>

<h2>Jaké osobní údaje zpracováváme</h2>
<ul class="subpage-list">
	<li><strong>Identifikační a kontaktní údaje</strong> — jméno, příjmení, adresa, telefon, e-mail. Získáváme je prostřednictvím kontaktního formuláře, e-mailové korespondence nebo při osobní návštěvě.</li>
	<li><strong>Rodinné a sociální údaje</strong> — informace o složení rodiny, bytové situaci a zdravotním stavu, které jsou nezbytné pro poskytování doprovázení, poradenství nebo přípravných kurzů.</li>
	<li><strong>Zákonná agenda</strong> — údaje zpracovávané v rámci výkonu pěstounské péče a doprovázení na základě zákona č. 359/1999 Sb., o sociálně-právní ochraně dětí.</li>
	<li><strong>Technické údaje webu</strong> — IP adresa, typ prohlížeče, čas návštěvy — anonymizovaně pro statistické účely.</li>
</ul>

<h2>Účely a právní základ zpracování</h2>
<ul class="subpage-list">
	<li><strong>Plnění zákonné povinnosti</strong> (čl. 6 odst. 1 písm. c) GDPR) — vedení dokumentace pěstounských rodin a výkon doprovázení dle zákona o SPOD.</li>
	<li><strong>Plnění smlouvy</strong> (čl. 6 odst. 1 písm. b) GDPR) — uzavření a plnění dohody o výkonu pěstounské péče nebo jiné dohody o poskytování služeb.</li>
	<li><strong>Oprávněný zájem</strong> (čl. 6 odst. 1 písm. f) GDPR) — bezpečnost a provoz webových stránek, zasílání informací o aktivitách CPNRP stávajícím klientům.</li>
	<li><strong>Souhlas</strong> (čl. 6 odst. 1 písm. a) GDPR) — zasílání newsletteru, použití fotografií nebo příběhů na webu a v publikacích. Souhlas lze kdykoli odvolat.</li>
</ul>

<h2>Příjemci osobních údajů</h2>
<p>Osobní údaje neprodáváme ani nepředáváme třetím stranám za obchodními účely. Údaje mohou být sdíleny pouze:</p>
<ul class="subpage-list">
	<li>se státními orgány (OSPOD, krajský úřad, soudy) v rozsahu vyžadovaném zákonem,</li>
	<li>s externími odborníky (psychologové, terapeuti) vázanými mlčenlivostí,</li>
	<li>s poskytovateli technických služeb (hosting, e-mailový systém) na základě smlouvy o zpracování osobních údajů.</li>
</ul>

<h2>Doba uchovávání osobních údajů</h2>
<ul class="subpage-list">
	<li><strong>Dokumentace pěstounských rodin</strong> — po dobu trvání dohody a dále dle skartačního řádu organizace (zpravidla 10 let).</li>
	<li><strong>Kontaktní dotazy a korespondence</strong> — po dobu nezbytnou k vyřízení, nejdéle 3 roky.</li>
	<li><strong>Souhlas se zasíláním informací</strong> — do odvolání souhlasu.</li>
	<li><strong>Technická data webu</strong> — anonymizovaně, maximálně 26 měsíců.</li>
</ul>

<h2>Vaše práva</h2>
<p>V souvislosti se zpracováním osobních údajů máte tato práva:</p>
<ul class="subpage-list">
	<li><strong>Právo na přístup</strong> — kdykoli nás požádat o informaci, jaké údaje o vás zpracováváme.</li>
	<li><strong>Právo na opravu</strong> — požádat o opravu nepřesných nebo neúplných údajů.</li>
	<li><strong>Právo na výmaz</strong> — požádat o smazání údajů, které již nejsou potřebné nebo jsou zpracovávány protiprávně.</li>
	<li><strong>Právo na omezení zpracování</strong> — požádat o dočasné omezení zpracování v zákonem stanovených případech.</li>
	<li><strong>Právo na přenositelnost</strong> — obdržet své údaje ve strukturovaném, strojově čitelném formátu.</li>
	<li><strong>Právo vznést námitku</strong> — vznést námitku proti zpracování na základě oprávněného zájmu.</li>
	<li><strong>Právo odvolat souhlas</strong> — kdykoli odvolat souhlas se zpracováním bez dopadu na zákonnost předchozího zpracování.</li>
</ul>
<p>Žádost uplatněte e-mailem na <a href="mailto:gdpr@cpnrp.cz">gdpr@cpnrp.cz</a> nebo poštou na adresu sídla organizace. Na žádost odpovíme nejpozději do 30 dnů.</p>

<h2>Stížnost u dozorového orgánu</h2>
<p>Máte právo podat stížnost u <strong>Úřadu pro ochranu osobních údajů</strong>:<br>
Pplk. Sochora 27, 170 00 Praha 7 | <a href="https://www.uoou.cz" target="_blank" rel="noopener noreferrer">www.uoou.cz</a> | <a href="mailto:posta@uoou.cz">posta@uoou.cz</a></p>

<h2>Zabezpečení</h2>
<p>Přijali jsme technická a organizační opatření k ochraně osobních údajů před neoprávněným přístupem, ztrátou nebo zničením. Přístup k citlivým údajům mají pouze oprávnění pracovníci vázaní mlčenlivostí.</p>

<h2>Změny tohoto dokumentu</h2>
<p>Zásady ochrany osobních údajů můžeme průběžně aktualizovat. Aktuální verze je vždy k dispozici na této stránce. Při zásadních změnách vás informujeme e-mailem.</p>
<p><em>Dokument je účinný od 25. května 2018 a byl naposledy aktualizován v roce 2024.</em></p>
';

	// ── Cookies ───────────────────────────────────────────────────
	$cookies = '
<p class="subpage-intro">Naše webové stránky používají cookies — malé textové soubory ukládané ve vašem prohlížeči. Níže vysvětlujeme, jaké typy cookies používáme a jak svůj souhlas spravovat.</p>

<h2>Co jsou cookies</h2>
<p>Cookies jsou krátké textové soubory, které webový server ukládá do vašeho zařízení při návštěvě stránky. Slouží k zapamatování vašich preferencí, měření návštěvnosti a zajištění správného fungování webu.</p>

<h2>Typy cookies, které používáme</h2>
<ul class="subpage-list">
	<li><strong>Nezbytné cookies</strong> — zajišťují základní funkce webu (přihlášení do administrace, bezpečnostní tokeny formulářů). Bez nich web nelze provozovat. Nevyžadují souhlas.</li>
	<li><strong>Analytické cookies</strong> — pomáhají nám zjistit, jak návštěvníci web používají (počet návštěv, nejnavštěvovanější stránky). Sbíráme je pouze anonymizovaně a s vaším souhlasem.</li>
	<li><strong>Funkční cookies</strong> — zapamatují vaše preference (např. nastavení jazyka). Aktivní jen s vaším souhlasem.</li>
</ul>

<h2>Správa souhlasu</h2>
<p>Při první návštěvě webu se zobrazí lišta, kde si vyberete, které cookies povolíte. Svůj souhlas můžete kdykoli změnit nebo odvolat kliknutím na tlačítko níže.</p>
<p>Odmítnutí analytických nebo funkčních cookies nemá vliv na funkčnost webu.</p>

<h2>Cookies třetích stran</h2>
<p>Náš web může obsahovat obsah nebo nástroje od třetích stran (například sociální sítě). Tyto strany mohou ukládat vlastní cookies dle svých zásad ochrany soukromí, které nemáme pod kontrolou.</p>

<h2>Jak cookies spravovat v prohlížeči</h2>
<p>Kromě správy souhlasu na tomto webu můžete cookies spravovat přímo v nastavení vašeho prohlížeče:</p>
<ul class="subpage-list">
	<li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener noreferrer">Google Chrome</a></li>
	<li><a href="https://support.mozilla.org/cs/kb/povoleni-zakazani-cookies" target="_blank" rel="noopener noreferrer">Mozilla Firefox</a></li>
	<li><a href="https://support.apple.com/cs-cz/guide/safari/sfri11471/mac" target="_blank" rel="noopener noreferrer">Safari</a></li>
	<li><a href="https://support.microsoft.com/cs-cz/microsoft-edge/cookies-v-aplikaci-microsoft-edge" target="_blank" rel="noopener noreferrer">Microsoft Edge</a></li>
</ul>

<h2>Kontakt</h2>
<p>Dotazy k cookies nebo ochraně osobních údajů zasílejte na <a href="mailto:gdpr@cpnrp.cz">gdpr@cpnrp.cz</a>.<br>
Podrobnější informace o zpracování osobních údajů najdete v naší <a href="/ochrana-udaju">Ochraně osobních údajů</a>.</p>
';

	// ── Insert into pages ─────────────────────────────────────────
	$map = [
		'ochrana-udaju' => $gdpr,
		'cookies'       => $cookies,
	];

	foreach ( $map as $slug => $content ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) continue;

		update_post_meta( $page->ID, '_wp_page_template', 'page-podstranka.php' );
		update_post_meta( $page->ID, '_subpage_hero_desc', $slug === 'ochrana-udaju'
			? 'Informace o tom, jak zpracováváme vaše osobní údaje v souladu s nařízením GDPR.'
			: 'Informace o cookies používaných na tomto webu a jak spravovat svůj souhlas.'
		);

		wp_update_post( [
			'ID'           => $page->ID,
			'post_content' => $content,
			'post_status'  => 'publish',
		] );
	}

	set_transient( 'cpnrp_legal_v1', true );
}
