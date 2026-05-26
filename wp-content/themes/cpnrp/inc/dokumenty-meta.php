<?php
/**
 * Dokumenty ke stažení — admin meta box with two repeatable sections.
 * Scoped to page-dokumenty.php template only.
 */

// ── Enqueue media scripts ─────────────────────────────────────────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
	global $post;
	if ( ! $post || $post->post_type !== 'page' ) return;
	if ( get_post_meta( $post->ID, '_wp_page_template', true ) === 'page-dokumenty.php' ) {
		wp_enqueue_media();
	}
} );

// ── Register meta box ─────────────────────────────────────────────
add_action( 'add_meta_boxes', function () {
	global $post;
	if ( ! $post ) return;
	if ( get_post_meta( $post->ID, '_wp_page_template', true ) !== 'page-dokumenty.php' ) return;
	add_meta_box(
		'cpnrp_dokumenty',
		'Dokumenty ke stažení',
		'cpnrp_dokumenty_cb',
		'page', 'normal', 'high'
	);
} );

function cpnrp_dokumenty_cb( $post ) {
	wp_nonce_field( 'cpnrp_dokumenty_save', 'cpnrp_dok_nonce' );

	$default_formulare = [
		[ 'name' => 'Přihláška na přípravné kurzy',                          'type' => 'PDF', 'url' => '' ],
		[ 'name' => 'Žádost o zprostředkování náhradní rodinné péče',        'type' => 'PDF', 'url' => '' ],
		[ 'name' => 'Čestné prohlášení žadatele',                            'type' => 'PDF', 'url' => '' ],
		[ 'name' => 'Souhlas se zpracováním osobních údajů',                 'type' => 'PDF', 'url' => '' ],
	];
	$default_prirucky = [
		[ 'name' => 'Průvodce procesem pěstounství',                         'type' => 'PDF', 'url' => '' ],
		[ 'name' => 'Průvodce procesem adopce',                              'type' => 'PDF', 'url' => '' ],
		[ 'name' => 'Co je náhradní rodinná péče — přehled forem',           'type' => 'PDF', 'url' => '' ],
		[ 'name' => 'Práva a povinnosti pěstounů',                           'type' => 'PDF', 'url' => '' ],
		[ 'name' => 'Finanční dávky pro pěstouny',                           'type' => 'PDF', 'url' => '' ],
	];

	$formulare = get_post_meta( $post->ID, '_dokumenty_formulare', true );
	$prirucky  = get_post_meta( $post->ID, '_dokumenty_prirucky',  true );
	if ( ! is_array( $formulare ) || empty( $formulare ) ) $formulare = $default_formulare;
	if ( ! is_array( $prirucky  ) || empty( $prirucky  ) ) $prirucky  = $default_prirucky;

	cpnrp_dok_render_table( 'formulare', 'Formuláře a žádosti', $formulare );
	echo '<hr style="margin:2rem 0">';
	cpnrp_dok_render_table( 'prirucky', 'Příručky a brožury', $prirucky );
	?>
	<script>
	(function ($) {
		// Media picker
		$(document).on('click', '.dok-pick', function (e) {
			e.preventDefault();
			var $url = $(this).siblings('.dok-url');
			var frame = wp.media({
				title: 'Vyberte soubor',
				button: { text: 'Vybrat tento soubor' },
				multiple: false
			});
			frame.on('select', function () {
				$url.val( frame.state().get('selection').first().toJSON().url );
			});
			frame.open();
		});

		// Add row
		$(document).on('click', '.dok-add', function () {
			var group = $(this).data('group');
			var row = '<tr class="dok-row" style="border-bottom:1px solid #eee">'
				+ '<td style="padding:8px 6px"><input type="text" name="dok_' + group + '_name[]" placeholder="Název dokumentu" style="width:100%;padding:5px 6px"></td>'
				+ '<td style="padding:8px 6px"><input type="text" name="dok_' + group + '_type[]" placeholder="PDF" value="PDF" style="width:64px;padding:5px 6px"></td>'
				+ '<td style="padding:8px 6px;display:flex;align-items:center;gap:6px"><input type="text" name="dok_' + group + '_url[]" class="dok-url" placeholder="https://… nebo prázdné" style="flex:1;padding:5px 6px"><button type="button" class="button dok-pick">Vybrat soubor</button></td>'
				+ '<td style="padding:8px 6px;text-align:center"><button type="button" class="button dok-remove" style="color:#b00">✕</button></td>'
				+ '</tr>';
			$('#dok-rows-' + group).append(row);
		});

		// Remove row
		$(document).on('click', '.dok-remove', function () {
			$(this).closest('.dok-row').remove();
		});
	}(jQuery));
	</script>
	<?php
}

function cpnrp_dok_render_table( $group, $heading, $items ) {
	?>
	<h3 style="margin:0 0 10px;font-size:14px"><?php echo esc_html( $heading ); ?></h3>
	<p style="color:#555;margin-bottom:10px;font-size:13px">
		Klikněte <strong>Vybrat soubor</strong> pro nahrání nebo výběr z Knihovny médií.
	</p>
	<table style="width:100%;border-collapse:collapse;margin-bottom:6px">
		<thead>
			<tr style="border-bottom:2px solid #ddd">
				<th style="text-align:left;padding:6px 8px">Název dokumentu</th>
				<th style="text-align:left;padding:6px 8px;width:80px">Typ</th>
				<th style="text-align:left;padding:6px 8px">Soubor</th>
				<th style="width:50px"></th>
			</tr>
		</thead>
		<tbody id="dok-rows-<?php echo esc_attr( $group ); ?>">
			<?php foreach ( $items as $item ) : ?>
			<tr class="dok-row" style="border-bottom:1px solid #eee">
				<td style="padding:8px 6px">
					<input type="text" name="dok_<?php echo esc_attr( $group ); ?>_name[]"
						value="<?php echo esc_attr( $item['name'] ?? '' ); ?>"
						placeholder="Název dokumentu"
						style="width:100%;padding:5px 6px">
				</td>
				<td style="padding:8px 6px">
					<input type="text" name="dok_<?php echo esc_attr( $group ); ?>_type[]"
						value="<?php echo esc_attr( $item['type'] ?? 'PDF' ); ?>"
						placeholder="PDF"
						style="width:64px;padding:5px 6px">
				</td>
				<td style="padding:8px 6px;display:flex;align-items:center;gap:6px">
					<input type="text" name="dok_<?php echo esc_attr( $group ); ?>_url[]"
						class="dok-url"
						value="<?php echo esc_url( $item['url'] ?? '' ); ?>"
						placeholder="https://… nebo prázdné"
						style="flex:1;padding:5px 6px">
					<button type="button" class="button dok-pick">Vybrat soubor</button>
				</td>
				<td style="padding:8px 6px;text-align:center">
					<button type="button" class="button dok-remove" style="color:#b00">✕</button>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<button type="button" class="button button-secondary dok-add" data-group="<?php echo esc_attr( $group ); ?>">
		+ Přidat dokument
	</button>
	<?php
}

// ── Save ──────────────────────────────────────────────────────────
add_action( 'save_post_page', 'cpnrp_dokumenty_save' );
function cpnrp_dokumenty_save( $post_id ) {
	if ( ! isset( $_POST['cpnrp_dok_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['cpnrp_dok_nonce'], 'cpnrp_dokumenty_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	foreach ( [ 'formulare', 'prirucky' ] as $group ) {
		$names = array_values( $_POST[ "dok_{$group}_name" ] ?? [] );
		$types = array_values( $_POST[ "dok_{$group}_type" ] ?? [] );
		$urls  = array_values( $_POST[ "dok_{$group}_url"  ] ?? [] );
		$items = [];
		foreach ( $names as $i => $name ) {
			$name = sanitize_text_field( $name );
			if ( $name === '' ) continue;
			$items[] = [
				'name' => $name,
				'type' => sanitize_text_field( $types[ $i ] ?? 'PDF' ),
				'url'  => esc_url_raw( $urls[ $i ] ?? '' ),
			];
		}
		update_post_meta( $post_id, "_dokumenty_{$group}", $items );
	}
}
