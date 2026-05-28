<?php
/**
 * Adopce vs. pěstounství — meta boxes (tabulka + kvíz).
 * Scoped to page-adopce-vs-pestounstvi.php.
 * One-time seed: přepíše hardcoded data do DB při prvním admin_init.
 */

// ── One-time seed ─────────────────────────────────────────────────────────────
add_action( 'admin_init', 'cpnrp_avp_seed_v1' );
function cpnrp_avp_seed_v1() {
	if ( get_transient( 'cpnrp_avp_v1' ) ) return;

	$pages = get_posts( [
		'post_type'      => 'page',
		'post_status'    => 'any',
		'meta_key'       => '_wp_page_template',
		'meta_value'     => 'page-adopce-vs-pestounstvi.php',
		'posts_per_page' => 1,
		'fields'         => 'ids',
	] );

	if ( empty( $pages ) ) {
		set_transient( 'cpnrp_avp_v1', true );
		return;
	}

	$pid = $pages[0];

	if ( ! get_post_meta( $pid, '_avp_rows', true ) ) {
		update_post_meta( $pid, '_avp_rows', [
			[ 'Právní vztah',      'Trvalý — jako mezi biologickým rodičem a dítětem', 'Pěstoun pečuje, ale není zákonný zástupce' ],
			[ 'Délka péče',        'Trvalá (po 3 letech nezrušitelné)',                 'Do zletilosti, případně do 26 let při studiu' ],
			[ 'Příjmení dítěte',   'Mění se na osvojitelovo',                           'Zůstává původní' ],
			[ 'Biologická rodina', 'Vztahy zanikají',                                   'Povinnost podporovat kontakt v zájmu dítěte' ],
			[ 'Finanční podpora',  'Žádné dávky od státu',                              'Odměna pěstouna + příspěvek na potřeby dítěte' ],
			[ 'Vzdělávání',        'Jen úvodní příprava (48 h)',                        '48 h příprava + 24 h ročně povinně' ],
			[ 'Doprovázení',       '— bez doprovázející organizace',                    'Klíčový sociální pracovník po celou dobu' ],
			[ 'Možnost ukončení',  'Po 3 letech nelze zrušit',                          'Lze ukončit, je-li to v zájmu dítěte' ],
		] );
	}

	if ( ! get_post_meta( $pid, '_avp_questions', true ) ) {
		update_post_meta( $pid, '_avp_questions', [
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
		] );
	}

	set_transient( 'cpnrp_avp_v1', true );
}

// ── Registrace meta boxů ──────────────────────────────────────────────────────
add_action( 'add_meta_boxes', 'cpnrp_avp_add_meta_boxes' );
function cpnrp_avp_add_meta_boxes( $post_type ) {
	if ( $post_type !== 'page' ) return;
	add_meta_box( 'cpnrp_avp_table', 'Srovnávací tabulka', 'cpnrp_avp_table_cb', 'page', 'normal', 'high' );
	add_meta_box( 'cpnrp_avp_quiz',  'Kvíz — otázky',      'cpnrp_avp_quiz_cb',  'page', 'normal', 'high' );
}

function cpnrp_avp_is_avp_page( $post ) {
	return get_post_meta( $post->ID, '_wp_page_template', true ) === 'page-adopce-vs-pestounstvi.php';
}

// ── Meta box: tabulka ─────────────────────────────────────────────────────────
function cpnrp_avp_table_cb( $post ) {
	if ( ! cpnrp_avp_is_avp_page( $post ) ) {
		echo '<p style="color:#999">Dostupné pouze pro šablonu „Adopce vs. pěstounství".</p>';
		return;
	}
	wp_nonce_field( 'cpnrp_avp_save', 'cpnrp_avp_nonce' );
	$rows = get_post_meta( $post->ID, '_avp_rows', true ) ?: [];
	?>
	<style>
		.avp-tbl{width:100%;border-collapse:collapse}
		.avp-tbl th{background:#f6f7f7;padding:6px 8px;font-size:12px;text-align:left;border:1px solid #ddd}
		.avp-tbl td{padding:4px;vertical-align:top;border:1px solid #eee}
		.avp-tbl input{width:100%;box-sizing:border-box}
		.avp-rm{background:none;border:none;color:#b00;cursor:pointer;font-size:18px;padding:0 4px;line-height:1}
	</style>
	<table class="avp-tbl">
		<thead><tr>
			<th style="width:20%">Aspekt</th>
			<th style="width:37%">Adopce</th>
			<th style="width:37%">Pěstounská péče</th>
			<th style="width:6%"></th>
		</tr></thead>
		<tbody id="avp-tbl-body">
		<?php foreach ( $rows as $i => $row ) : ?>
			<tr>
				<td><input type="text" name="avp_rows[<?php echo $i; ?>][0]" value="<?php echo esc_attr( $row[0] ); ?>"></td>
				<td><input type="text" name="avp_rows[<?php echo $i; ?>][1]" value="<?php echo esc_attr( $row[1] ); ?>"></td>
				<td><input type="text" name="avp_rows[<?php echo $i; ?>][2]" value="<?php echo esc_attr( $row[2] ); ?>"></td>
				<td><button type="button" class="avp-rm">✕</button></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<p><button type="button" id="avp-add-row" class="button">+ Přidat řádek</button></p>
	<script>
	(function(){
		var body=document.getElementById('avp-tbl-body');
		function reindex(){body.querySelectorAll('tr').forEach(function(tr,i){tr.querySelectorAll('input').forEach(function(inp,j){inp.name='avp_rows['+i+']['+j+']';});});}
		body.addEventListener('click',function(e){if(e.target.classList.contains('avp-rm')){e.target.closest('tr').remove();reindex();}});
		document.getElementById('avp-add-row').addEventListener('click',function(){
			var i=body.querySelectorAll('tr').length;
			var tr=document.createElement('tr');
			tr.innerHTML='<td><input type="text" name="avp_rows['+i+'][0]" value=""></td><td><input type="text" name="avp_rows['+i+'][1]" value=""></td><td><input type="text" name="avp_rows['+i+'][2]" value=""></td><td><button type="button" class="avp-rm">✕</button></td>';
			body.appendChild(tr);
		});
	}());
	</script>
	<?php
}

// ── Meta box: kvíz ───────────────────────────────────────────────────────────
function cpnrp_avp_quiz_cb( $post ) {
	if ( ! cpnrp_avp_is_avp_page( $post ) ) {
		echo '<p style="color:#999">Dostupné pouze pro šablonu „Adopce vs. pěstounství".</p>';
		return;
	}
	$questions = get_post_meta( $post->ID, '_avp_questions', true ) ?: [];
	?>
	<style>
		.avp-qb{border:1px solid #ddd;padding:12px;margin-bottom:10px;border-radius:4px;background:#fafafa;position:relative}
		.avp-qb label{display:block;font-weight:600;font-size:12px;margin-bottom:3px}
		.avp-qb input{width:100%;box-sizing:border-box;margin-bottom:8px}
		.avp-qb .avp-rm{position:absolute;top:8px;right:8px;background:none;border:none;color:#b00;cursor:pointer;font-size:18px;padding:0}
	</style>
	<div id="avp-quiz-body">
	<?php foreach ( $questions as $i => $q ) : ?>
		<div class="avp-qb">
			<button type="button" class="avp-rm">✕</button>
			<label>Otázka <?php echo $i + 1; ?></label>
			<input type="text" name="avp_questions[<?php echo $i; ?>][title]" value="<?php echo esc_attr( $q['title'] ); ?>" placeholder="Text otázky">
			<label>Odpověď A</label>
			<input type="text" name="avp_questions[<?php echo $i; ?>][a]" value="<?php echo esc_attr( $q['a'] ); ?>" placeholder="Odpověď A">
			<label>Odpověď B</label>
			<input type="text" name="avp_questions[<?php echo $i; ?>][b]" value="<?php echo esc_attr( $q['b'] ); ?>" placeholder="Odpověď B">
		</div>
	<?php endforeach; ?>
	</div>
	<p><button type="button" id="avp-add-q" class="button">+ Přidat otázku</button></p>
	<script>
	(function(){
		var body=document.getElementById('avp-quiz-body');
		function reindex(){body.querySelectorAll('.avp-qb').forEach(function(b,i){b.querySelector('label').textContent='Otázka '+(i+1);b.querySelectorAll('input').forEach(function(inp){inp.name=inp.name.replace(/avp_questions\[\d+\]/,'avp_questions['+i+']');});});}
		body.addEventListener('click',function(e){if(e.target.classList.contains('avp-rm')){e.target.closest('.avp-qb').remove();reindex();}});
		document.getElementById('avp-add-q').addEventListener('click',function(){
			var i=body.querySelectorAll('.avp-qb').length;
			var d=document.createElement('div');d.className='avp-qb';
			d.innerHTML='<button type="button" class="avp-rm">✕</button><label>Otázka '+(i+1)+'</label><input type="text" name="avp_questions['+i+'][title]" value="" placeholder="Text otázky"><label>Odpověď A</label><input type="text" name="avp_questions['+i+'][a]" value="" placeholder="Odpověď A"><label>Odpověď B</label><input type="text" name="avp_questions['+i+'][b]" value="" placeholder="Odpověď B">';
			body.appendChild(d);
		});
	}());
	</script>
	<?php
}

// ── Uložení ───────────────────────────────────────────────────────────────────
add_action( 'save_post_page', 'cpnrp_avp_save' );
function cpnrp_avp_save( $post_id ) {
	if ( ! isset( $_POST['cpnrp_avp_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['cpnrp_avp_nonce'], 'cpnrp_avp_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_page', $post_id ) ) return;

	if ( isset( $_POST['avp_rows'] ) ) {
		$rows = [];
		foreach ( $_POST['avp_rows'] as $row ) {
			$rows[] = [
				sanitize_text_field( $row[0] ?? '' ),
				sanitize_text_field( $row[1] ?? '' ),
				sanitize_text_field( $row[2] ?? '' ),
			];
		}
		update_post_meta( $post_id, '_avp_rows', $rows );
	}

	if ( isset( $_POST['avp_questions'] ) ) {
		$qs = [];
		foreach ( $_POST['avp_questions'] as $q ) {
			$qs[] = [
				'title' => sanitize_text_field( $q['title'] ?? '' ),
				'a'     => sanitize_text_field( $q['a']     ?? '' ),
				'b'     => sanitize_text_field( $q['b']     ?? '' ),
			];
		}
		update_post_meta( $post_id, '_avp_questions', $qs );
	}
}
