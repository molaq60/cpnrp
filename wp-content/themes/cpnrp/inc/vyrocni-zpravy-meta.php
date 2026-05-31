<?php
/**
 * Výroční zprávy — admin meta box with WP media-library PDF picker.
 * Scoped to page-vyrocni-zpravy.php template only.
 */

// ── Enqueue media scripts only on this template's edit screen ────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
	global $post;
	if ( ! $post || $post->post_type !== 'page' ) return;
	if ( get_post_meta( $post->ID, '_wp_page_template', true ) === 'page-vyrocni-zpravy.php' ) {
		wp_enqueue_media();
	}
} );

// ── Register meta box ─────────────────────────────────────────────
add_action( 'add_meta_boxes', function () {
	global $post;
	if ( ! $post ) return;
	if ( get_post_meta( $post->ID, '_wp_page_template', true ) !== 'page-vyrocni-zpravy.php' ) return;
	add_meta_box(
		'cpnrp_vyrocni_zpravy',
		'Výroční zprávy',
		'cpnrp_vyrocni_zpravy_cb',
		'page', 'normal', 'high'
	);
} );

function cpnrp_vyrocni_zpravy_cb( $post ) {
	wp_nonce_field( 'cpnrp_vyrocni_zpravy_save', 'cpnrp_vz_nonce' );

	$items = get_post_meta( $post->ID, '_vyrocni_zpravy', true );
	if ( ! is_array( $items ) || empty( $items ) ) {
		$items = [
			[ 'year' => '2023', 'label' => '', 'url' => '', 'thumb' => '' ],
			[ 'year' => '2022', 'label' => '', 'url' => '', 'thumb' => '' ],
			[ 'year' => '2021', 'label' => '', 'url' => '', 'thumb' => '' ],
			[ 'year' => '2020', 'label' => '', 'url' => '', 'thumb' => '' ],
			[ 'year' => '2019', 'label' => '', 'url' => '', 'thumb' => '' ],
		];
	}
	?>
	<p style="color:#555;margin-bottom:12px">
		Přidejte každý rok jako jeden řádek. Klikněte <strong>Vybrat PDF</strong> pro nahrání nebo výběr souboru z Knihovny médií.
		Pole <em>Název</em> je nepovinné — pokud ho necháte prázdné, použije se automaticky „Výroční zpráva CPNRP [rok]".
	</p>

	<table style="width:100%;border-collapse:collapse;margin-bottom:8px">
		<thead>
			<tr style="border-bottom:2px solid #ddd">
				<th style="text-align:left;padding:6px 8px;width:90px">Rok</th>
				<th style="text-align:left;padding:6px 8px;width:200px">Název (nepovinné)</th>
				<th style="text-align:left;padding:6px 8px">PDF soubor</th>
				<th style="text-align:left;padding:6px 8px;width:90px">Náhled</th>
				<th style="width:50px"></th>
			</tr>
		</thead>
		<tbody id="vz-rows">
			<?php foreach ( $items as $item ) :
				$thumb = esc_url( $item['thumb'] ?? '' );
			?>
			<tr class="vz-row" style="border-bottom:1px solid #eee">
				<td style="padding:8px 6px;vertical-align:middle">
					<input type="text" name="vz_year[]"
						value="<?php echo esc_attr( $item['year'] ?? '' ); ?>"
						placeholder="<?php echo date( 'Y' ); ?>"
						style="width:76px;padding:5px 6px">
				</td>
				<td style="padding:8px 6px;vertical-align:middle">
					<input type="text" name="vz_label[]"
						value="<?php echo esc_attr( $item['label'] ?? '' ); ?>"
						placeholder="Výroční zpráva CPNRP …"
						style="width:100%;padding:5px 6px">
				</td>
				<td style="padding:8px 6px;vertical-align:middle">
					<div style="display:flex;align-items:center;gap:6px">
						<input type="text" name="vz_url[]" class="vz-url"
							value="<?php echo esc_url( $item['url'] ?? '' ); ?>"
							placeholder="https://… nebo nechte prázdné"
							style="flex:1;padding:5px 6px">
						<button type="button" class="button vz-pick">Vybrat PDF</button>
					</div>
				</td>
				<td style="padding:8px 6px;vertical-align:middle;width:90px">
					<input type="hidden" name="vz_thumb[]" class="vz-thumb" value="<?php echo $thumb; ?>">
					<div style="display:flex;align-items:center;gap:6px">
						<img src="<?php echo $thumb; ?>" class="vz-thumb-preview"
						     style="width:36px;height:48px;object-fit:cover;border-radius:3px;border:1px solid #ddd;flex-shrink:0;<?php echo $thumb ? '' : 'display:none'; ?>">
						<div style="display:flex;flex-direction:column;gap:3px">
							<button type="button" class="button vz-pick-thumb" style="font-size:11px;padding:3px 6px">Obrázek</button>
							<a href="#" class="vz-remove-thumb" style="font-size:11px;color:#b00;<?php echo $thumb ? '' : 'display:none'; ?>">✕ Odebrat</a>
						</div>
					</div>
				</td>
				<td style="padding:8px 6px;vertical-align:middle;text-align:center">
					<button type="button" class="button vz-remove" style="color:#b00">✕</button>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<button type="button" class="button button-secondary" id="vz-add-row">+ Přidat rok</button>

	<script>
	(function ($) {
		// Open WP media library filtered to PDFs
		$(document).on('click', '.vz-pick', function (e) {
			e.preventDefault();
			var $urlInput = $(this).siblings('.vz-url');
			var frame = wp.media({
				title: 'Vyberte PDF výroční zprávy',
				button: { text: 'Vybrat tento soubor' },
				library: { type: 'application/pdf' },
				multiple: false
			});
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				$urlInput.val(attachment.url);
			});
			frame.open();
		});

		// Open WP media library for thumbnail image
		$(document).on('click', '.vz-pick-thumb', function (e) {
			e.preventDefault();
			var $td      = $(this).closest('td');
			var $input   = $td.find('.vz-thumb');
			var $preview = $td.find('.vz-thumb-preview');
			var $remove  = $td.find('.vz-remove-thumb');
			var frame = wp.media({
				title: 'Vyberte náhledový obrázek',
				button: { text: 'Vybrat obrázek' },
				library: { type: 'image' },
				multiple: false
			});
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				$input.val(attachment.url);
				$preview.attr('src', attachment.url).show();
				$remove.show();
			});
			frame.open();
		});

		// Remove thumbnail
		$(document).on('click', '.vz-remove-thumb', function (e) {
			e.preventDefault();
			var $td = $(this).closest('td');
			$td.find('.vz-thumb').val('');
			$td.find('.vz-thumb-preview').hide().attr('src', '');
			$(this).hide();
		});

		// Add new row
		$('#vz-add-row').on('click', function () {
			var year = new Date().getFullYear();
			var row = '<tr class="vz-row" style="border-bottom:1px solid #eee">'
				+ '<td style="padding:8px 6px;vertical-align:middle"><input type="text" name="vz_year[]" placeholder="' + year + '" style="width:76px;padding:5px 6px"></td>'
				+ '<td style="padding:8px 6px;vertical-align:middle"><input type="text" name="vz_label[]" placeholder="Výroční zpráva CPNRP …" style="width:100%;padding:5px 6px"></td>'
				+ '<td style="padding:8px 6px;vertical-align:middle"><div style="display:flex;align-items:center;gap:6px"><input type="text" name="vz_url[]" class="vz-url" placeholder="https://… nebo nechte prázdné" style="flex:1;padding:5px 6px"><button type="button" class="button vz-pick">Vybrat PDF</button></div></td>'
				+ '<td style="padding:8px 6px;vertical-align:middle;width:90px"><input type="hidden" name="vz_thumb[]" class="vz-thumb" value=""><div style="display:flex;align-items:center;gap:6px"><img src="" class="vz-thumb-preview" style="width:36px;height:48px;object-fit:cover;border-radius:3px;border:1px solid #ddd;flex-shrink:0;display:none"><div style="display:flex;flex-direction:column;gap:3px"><button type="button" class="button vz-pick-thumb" style="font-size:11px;padding:3px 6px">Obrázek</button><a href="#" class="vz-remove-thumb" style="font-size:11px;color:#b00;display:none">✕ Odebrat</a></div></div></td>'
				+ '<td style="padding:8px 6px;vertical-align:middle;text-align:center"><button type="button" class="button vz-remove" style="color:#b00">✕</button></td>'
				+ '</tr>';
			$('#vz-rows').append(row);
		});

		// Remove row
		$(document).on('click', '.vz-remove', function () {
			$(this).closest('.vz-row').remove();
		});
	}(jQuery));
	</script>
	<?php
}

// ── Save ──────────────────────────────────────────────────────────
add_action( 'save_post_page', 'cpnrp_vyrocni_zpravy_save' );
function cpnrp_vyrocni_zpravy_save( $post_id ) {
	if ( ! isset( $_POST['cpnrp_vz_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['cpnrp_vz_nonce'], 'cpnrp_vyrocni_zpravy_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$years  = array_values( $_POST['vz_year']  ?? [] );
	$labels = array_values( $_POST['vz_label'] ?? [] );
	$urls   = array_values( $_POST['vz_url']   ?? [] );
	$thumbs = array_values( $_POST['vz_thumb'] ?? [] );

	$items = [];
	foreach ( $years as $i => $year ) {
		$year = sanitize_text_field( $year );
		if ( $year === '' ) continue;
		$items[] = [
			'year'  => $year,
			'label' => sanitize_text_field( $labels[ $i ] ?? '' ),
			'url'   => esc_url_raw( $urls[ $i ] ?? '' ),
			'thumb' => esc_url_raw( $thumbs[ $i ] ?? '' ),
		];
	}

	update_post_meta( $post_id, '_vyrocni_zpravy', $items );
}
