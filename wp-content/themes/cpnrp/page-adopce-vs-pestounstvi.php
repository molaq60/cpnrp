<?php
/**
 * Template Name: Adopce vs. pěstounství
 * Comparison table + interactive 5-question quiz.
 * Meta box: _avp_hero_desc
 */

get_header();

$page_id   = get_the_ID();
$parent_id = wp_get_post_parent_id( $page_id );
$ancestors = array_reverse( get_post_ancestors( $page_id ) );

$hero_desc = get_post_meta( $page_id, '_avp_hero_desc', true )
	?: 'Obě formy mají společný cíl — poskytnout dítěti milující rodinu. Liší se právním rámcem, trvalostí a povinnostmi.';

$rows = [
	[ 'Právní vztah',       'Trvalý — jako mezi biologickým rodičem a dítětem', 'Pěstoun pečuje, ale není zákonný zástupce' ],
	[ 'Délka péče',         'Trvalá (po 3 letech nezrušitelné)',                  'Do zletilosti, případně do 26 let při studiu' ],
	[ 'Příjmení dítěte',    'Mění se na osvojitelovo',                            'Zůstává původní' ],
	[ 'Biologická rodina',  'Vztahy zanikají',                                    'Povinnost podporovat kontakt v zájmu dítěte' ],
	[ 'Finanční podpora',   'Žádné dávky od státu',                               'Odměna pěstouna + příspěvek na potřeby dítěte' ],
	[ 'Vzdělávání',         'Jen úvodní příprava (48 h)',                         '48 h příprava + 24 h ročně povinně' ],
	[ 'Doprovázení',        '— bez doprovázející organizace',                     'Klíčový sociální pracovník po celou dobu' ],
	[ 'Možnost ukončení',   'Po 3 letech nelze zrušit',                           'Lze ukončit, je-li to v zájmu dítěte' ],
];

$questions = [
	[
		'title' => 'Co od náhradní péče očekáváte především?',
		'a'     => 'Stát se „opravdovým" rodičem dítěte — natrvalo, bez návratu.',
		'b'     => 'Poskytnout dítěti bezpečí, dokud ho potřebuje — i když to nemusí být napořád.',
	],
	[
		'title' => 'Jak vnímáte biologickou rodinu dítěte?',
		'a'     => 'Po převzetí by už neměla hrát roli — chci dítěti dát čistý nový start.',
		'b'     => 'Rád/a bych podpořil/a zdravý vztah s bio rodinou, pokud je to v zájmu dítěte.',
	],
	[
		'title' => 'Co pro vás znamená „rodičovství"?',
		'a'     => 'Plná právní rodičovská role — zákonné zastoupení, příjmení, dědictví.',
		'b'     => 'Pečovatelská role — být dítěti jistotou, nemusím být zákonným zástupcem.',
	],
	[
		'title' => 'Pravidelná podpora a vzdělávání:',
		'a'     => 'Po prvotní přípravě bych chtěl/a „normální" rodinný život bez dohledu.',
		'b'     => 'Vyhovuje mi mít po ruce klíčového pracovníka a pravidelně se vzdělávat.',
	],
	[
		'title' => 'Finanční stránka:',
		'a'     => 'Dítě bych zaopatřil/a sám/a, jako vlastní — bez státních dávek.',
		'b'     => 'Oceňuji, že stát finančně podporuje péči (odměna pěstouna + příspěvky).',
	],
];
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<section class="subpage-hero">
		<div class="container">
			<nav class="subpage-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
				<?php foreach ( $ancestors as $anc_id ) : ?>
					<span>/</span>
					<a href="<?php echo esc_url( get_permalink( $anc_id ) ); ?>"><?php echo esc_html( get_the_title( $anc_id ) ); ?></a>
				<?php endforeach; ?>
				<span>/</span>
				<span><?php the_title(); ?></span>
			</nav>
			<h1 class="subpage-hero-title"><?php the_title(); ?></h1>
			<p class="subpage-hero-desc"><?php echo esc_html( $hero_desc ); ?></p>
		</div>
	</section>

	<!-- ── Content ───────────────────────────────────────────────── -->
	<section class="avp-section">
		<div class="container">
			<div class="avp-wrap">

				<p class="avp-intro">Obě formy mají společný cíl — poskytnout dítěti milující rodinu. Liší se však právním rámcem, trvalostí a povinnostmi. Níže najdete přehlednou tabulku — a pokud si stále nejste jistí, vyplňte krátký test.</p>

				<!-- Comparison table -->
				<div class="avp-table-wrap">
					<table class="avp-table">
						<thead>
							<tr>
								<th class="avp-th avp-th--aspect">Aspekt</th>
								<th class="avp-th avp-th--adopce">Adopce</th>
								<th class="avp-th avp-th--pp">Pěstounská péče</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $rows as $row ) : ?>
							<tr class="avp-tr">
								<td class="avp-td avp-td--aspect"><?php echo esc_html( $row[0] ); ?></td>
								<td class="avp-td"><?php echo esc_html( $row[1] ); ?></td>
								<td class="avp-td"><?php echo esc_html( $row[2] ); ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<!-- Quiz -->
				<div class="avp-quiz-wrap">
					<div class="avp-quiz-header">
						<p class="avp-quiz-label">5 otázek · 1 minuta</p>
						<h2 class="avp-quiz-title">Která forma vám sedí?</h2>
						<p class="avp-quiz-sub">U každé otázky vyberte odpověď, která je vám blíž. Doporučení uvidíte hned po páté otázce.</p>
					</div>

					<form id="nrp-quiz" class="avp-quiz-form" novalidate>
						<?php foreach ( $questions as $qi => $q ) :
							$qn = $qi + 1;
						?>
						<fieldset class="avp-fieldset">
							<legend class="avp-legend">
								<span class="avp-qnum"><?php echo $qn; ?></span>
								<?php echo esc_html( $q['title'] ); ?>
							</legend>
							<div class="avp-choices">
								<button type="button" class="quiz-btn avp-btn avp-btn--a" data-q="<?php echo $qn; ?>" data-answer="A">
									<span class="avp-btn-letter">A</span>
									<span><?php echo esc_html( $q['a'] ); ?></span>
								</button>
								<button type="button" class="quiz-btn avp-btn avp-btn--b" data-q="<?php echo $qn; ?>" data-answer="B">
									<span class="avp-btn-letter">B</span>
									<span><?php echo esc_html( $q['b'] ); ?></span>
								</button>
							</div>
						</fieldset>
						<?php endforeach; ?>
					</form>

					<!-- Results (hidden until all 5 answered) -->
					<div id="quiz-result" hidden class="avp-result">

						<div id="result-adoption" hidden class="avp-result-card avp-result-card--teal">
							<p class="avp-result-label">Vaše doporučení</p>
							<h3 class="avp-result-heading">Adopce je pro vás pravděpodobně přirozenější</h3>
							<p class="avp-result-desc">Vaše odpovědi naznačují, že hledáte trvalou, plnou rodičovskou roli s pevnými hranicemi. Adopce právě toto přináší — dítě se právně i fakticky stává součástí vaší rodiny natrvalo.</p>
							<div class="avp-result-btns">
								<a href="<?php echo esc_url( home_url( '/pro-rodiny/adopce' ) ); ?>" class="avp-rbtn avp-rbtn--teal">Více o adopci</a>
								<a href="<?php echo esc_url( home_url( '/pro-rodiny/adopce/jak-zacit' ) ); ?>" class="avp-rbtn avp-rbtn--outline-teal">Jak začít</a>
							</div>
						</div>

						<div id="result-foster" hidden class="avp-result-card avp-result-card--gold">
							<p class="avp-result-label avp-result-label--dark">Vaše doporučení</p>
							<h3 class="avp-result-heading">Pěstounská péče vám sedí lépe</h3>
							<p class="avp-result-desc">Vaše odpovědi ukazují na otevřenost, ochotu sdílet roli s biologickou rodinou a přijmout podporu klíčového pracovníka. Pěstounská péče je flexibilnější forma, kde dítě dostává bezpečné zázemí.</p>
							<div class="avp-result-btns">
								<a href="<?php echo esc_url( home_url( '/pro-rodiny/pestounska-pece' ) ); ?>" class="avp-rbtn avp-rbtn--gold">Více o pěstounské péči</a>
								<a href="<?php echo esc_url( home_url( '/pro-rodiny/pestounska-pece/jak-zacit' ) ); ?>" class="avp-rbtn avp-rbtn--outline-gold">Jak začít</a>
							</div>
						</div>

						<div id="result-mixed" hidden class="avp-result-card avp-result-card--red">
							<p class="avp-result-label avp-result-label--red">Vaše doporučení</p>
							<h3 class="avp-result-heading">Vidíme znaky obou forem — pojďme to probrat</h3>
							<p class="avp-result-desc">Vaše odpovědi se rozdělují mezi adopci i pěstounskou péči. To je úplně v pořádku — mnoho lidí stojí na rozcestí. Nezávazná konzultace s námi vám pomůže ujasnit, co přesně hledáte.</p>
							<div class="avp-result-btns">
								<a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>" class="avp-rbtn avp-rbtn--red">Domluvit konzultaci</a>
								<button type="button" id="quiz-reset" class="avp-rbtn avp-rbtn--reset">Vyplnit znovu</button>
							</div>
						</div>

					</div><!-- #quiz-result -->
				</div><!-- .avp-quiz-wrap -->

			</div><!-- .avp-wrap -->
		</div><!-- .container -->
	</section>

	<!-- ── Back / CTA row ───────────────────────────────────────── -->
	<section class="subpage-section" style="padding-top:0">
		<div class="container">
			<div class="subpage-cta-row">
				<?php if ( $parent_id ) : ?>
				<a href="<?php echo esc_url( get_permalink( $parent_id ) ); ?>" class="btn-subpage-back">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
						<line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
					</svg>
					<?php printf( esc_html__( 'Zpět na %s', 'cpnrp' ), esc_html( get_the_title( $parent_id ) ) ); ?>
				</a>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>" class="btn-subpage-outline">
					<?php esc_html_e( 'Mám zájem o konzultaci', 'cpnrp' ); ?>
				</a>
			</div>
		</div>
	</section>

</main>

<script>
(function () {
	var quiz      = document.getElementById('nrp-quiz');
	var resultEl  = document.getElementById('quiz-result');
	var resAdopt  = document.getElementById('result-adoption');
	var resFoster = document.getElementById('result-foster');
	var resMixed  = document.getElementById('result-mixed');
	var resetBtn  = document.getElementById('quiz-reset');
	if ( ! quiz || ! resultEl ) return;

	var answers = {};

	function setSelected( btn, answer ) {
		btn.classList.add( 'is-selected' );
		if ( answer === 'A' ) {
			btn.classList.add( 'is-selected--a' );
		} else {
			btn.classList.add( 'is-selected--b' );
		}
	}

	function clearSelected( btn ) {
		btn.classList.remove( 'is-selected', 'is-selected--a', 'is-selected--b' );
	}

	function showResult() {
		var aCount = Object.values( answers ).filter( function(v) { return v === 'A'; } ).length;
		resAdopt.hidden  = aCount < 4;
		resFoster.hidden = aCount > 1;
		resMixed.hidden  = ( aCount >= 4 || aCount <= 1 );
		resultEl.hidden  = false;
		resultEl.scrollIntoView( { behavior: 'smooth', block: 'start' } );
	}

	quiz.querySelectorAll( '.quiz-btn' ).forEach( function( btn ) {
		btn.addEventListener( 'click', function() {
			var q      = btn.dataset.q;
			var answer = btn.dataset.answer;
			quiz.querySelectorAll( '.quiz-btn[data-q="' + q + '"]' ).forEach( clearSelected );
			setSelected( btn, answer );
			answers[ q ] = answer;
			if ( Object.keys( answers ).length === 5 ) {
				showResult();
			}
		} );
	} );

	if ( resetBtn ) {
		resetBtn.addEventListener( 'click', function() {
			answers = {};
			quiz.querySelectorAll( '.quiz-btn' ).forEach( clearSelected );
			resultEl.hidden  = true;
			resAdopt.hidden  = true;
			resFoster.hidden = true;
			resMixed.hidden  = true;
			quiz.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		} );
	}
}());
</script>

<?php get_footer(); ?>
