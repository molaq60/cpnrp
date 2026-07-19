<?php
/**
 * Divadelní benefice — meta boxes for the page template.
 */

// ── Register meta box — pouze na stránce Divadelní benefice ─────────
add_action( 'add_meta_boxes', function () {
	global $post;
	if ( ! $post ) return;

	$tpl  = get_post_meta( $post->ID, '_wp_page_template', true );
	$slug = $post->post_name;

	if ( $tpl !== 'page-divadelni-benefice.php' && $slug !== 'divadelni-benefice' ) return;

	add_meta_box(
		'cpnrp_ben_settings',
		__( 'Divadelní benefice — nastavení', 'cpnrp' ),
		'cpnrp_ben_meta_box_cb',
		'page',
		'normal',
		'high'
	);
} );

// ── Meta box HTML ─────────────────────────────────────────────────────
function cpnrp_ben_meta_box_cb( $post ) {
	wp_nonce_field( 'cpnrp_ben_save', 'cpnrp_ben_nonce' );
	wp_enqueue_media();

	// Load all stored meta
	$keys = [
		'_ben_edition', '_ben_lead', '_ben_termin', '_ben_pro_koho', '_ben_vytezek', '_ben_web_url',
		'_ben_stat1_num', '_ben_stat1_label', '_ben_stat1_note',
		'_ben_stat2_num', '_ben_stat2_label', '_ben_stat2_note',
		'_ben_stat3_num', '_ben_stat3_label', '_ben_stat3_note',
		'_ben_venue1', '_ben_venue2', '_ben_venue3', '_ben_venue4', '_ben_venue5', '_ben_venue6',
		'_ben_plakat_1', '_ben_plakat_2',
		'_ben_gallery_title', '_ben_gallery_text', '_ben_gallery_imgs',
		'_ben_sponsors',
		'_ben_contact_name', '_ben_contact_role', '_ben_contact_email', '_ben_contact_phone',
	];
	$m = [];
	foreach ( $keys as $k ) {
		$m[ $k ] = get_post_meta( $post->ID, $k, true );
	}

	// Defaults
	$defaults = [
		'_ben_edition'      => '7. ročník · 2026',
		'_ben_lead'         => 'Charitativní divadelní akce, jejíž výtěžek putuje na podporu dětí v náhradní rodinné péči. Přijďte si užít kulturu a zároveň pomoci.',
		'_ben_termin'       => 'Listopad 2026',
		'_ben_pro_koho'     => 'Rodiny · Přátelé divadla · Veřejnost',
		'_ben_vytezek'      => 'Podpora dětí v náhradní péči — CPNRP',
		'_ben_web_url'      => 'https://www.divadelni-benefice.cz',
		'_ben_stat1_num'    => '7',      '_ben_stat1_label' => 'úspěšných ročníků',    '_ben_stat1_note' => '2019 – 2025',
		'_ben_stat2_num'    => '4',      '_ben_stat2_label' => 'města v roce 2026',     '_ben_stat2_note' => 'Litoměřice · Ústí · Lovosice · Roudnice',
		'_ben_stat3_num'    => '100 %',  '_ben_stat3_label' => 'výtěžku jde na děti',   '_ben_stat3_note' => 'přímo na podporu náhradních rodin',
		'_ben_venue1'       => 'Litoměřice',
		'_ben_venue2'       => 'Ústí nad Labem',
		'_ben_venue3'       => 'Lovosice',
		'_ben_venue4'       => 'Roudnice nad Labem',
	];
	foreach ( $defaults as $k => $v ) {
		if ( $m[ $k ] === '' || $m[ $k ] === false ) $m[ $k ] = $v;
	}

	// ── Helpers ───────────────────────────────────────────────────────
	$row = function( $label, $key, $type = 'text', $placeholder = '' ) use ( $m ) {
		$id  = esc_attr( ltrim( $key, '_' ) );
		$val = esc_attr( $m[ $key ] );
		echo "<tr><th><label for=\"{$id}\">{$label}</label></th>";
		echo "<td><input type=\"{$type}\" id=\"{$id}\" name=\"{$id}\" value=\"{$val}\" placeholder=\"" . esc_attr( $placeholder ) . "\"></td></tr>";
	};

	$textarea = function( $label, $key, $rows = 3, $placeholder = '' ) use ( $m ) {
		$id  = esc_attr( ltrim( $key, '_' ) );
		$val = esc_textarea( $m[ $key ] );
		echo "<tr><th><label for=\"{$id}\">{$label}</label></th>";
		echo "<td><textarea id=\"{$id}\" name=\"{$id}\" rows=\"{$rows}\" placeholder=\"" . esc_attr( $placeholder ) . "\">{$val}</textarea></td></tr>";
	};

	$image_picker = function( $label, $key ) use ( $m ) {
		$id  = ltrim( $key, '_' );
		$val = esc_url( $m[ $key ] ?? '' );
		?>
		<tr>
			<th style="vertical-align:top;padding-top:12px;"><label><?php echo esc_html( $label ); ?></label></th>
			<td>
				<div style="display:flex;align-items:flex-start;gap:12px;flex-wrap:wrap;">
					<img id="<?php echo esc_attr( $id ); ?>_preview"
					     src="<?php echo $val; ?>"
					     style="max-width:100px;max-height:120px;border-radius:4px;border:1px solid #ddd;<?php echo $val ? '' : 'display:none;'; ?>">
					<div style="display:flex;flex-direction:column;gap:6px;padding-top:4px;">
						<input type="hidden"
						       id="<?php echo esc_attr( $id ); ?>"
						       name="<?php echo esc_attr( $id ); ?>"
						       value="<?php echo $val; ?>">
						<button type="button" class="button ben-pick-img" data-target="<?php echo esc_attr( $id ); ?>">
							<?php echo $val ? esc_html__( 'Změnit obrázek', 'cpnrp' ) : esc_html__( 'Vybrat obrázek', 'cpnrp' ); ?>
						</button>
						<?php if ( $val ) : ?>
						<a href="#" class="ben-remove-img" data-target="<?php echo esc_attr( $id ); ?>" style="font-size:12px;color:#a00;">
							✕ <?php esc_html_e( 'Odebrat', 'cpnrp' ); ?>
						</a>
						<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>
		<?php
	};
	?>

	<style>
		#cpnrp_ben_settings table.form-table th { width: 210px; vertical-align: top; padding-top: 10px; }
		#cpnrp_ben_settings input[type=text],
		#cpnrp_ben_settings input[type=url],
		#cpnrp_ben_settings input[type=email],
		#cpnrp_ben_settings textarea { width: 100%; max-width: 620px; }
		#cpnrp_ben_settings .ben-section { margin-top: 24px; padding-top: 20px; border-top: 1px solid #ddd; }
		#cpnrp_ben_settings .ben-section h4 { margin: 0 0 12px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; color: #8a6200; }
		#cpnrp_ben_settings p.description { color: #666; font-style: italic; margin-top: 5px; font-size: 12px; }
	</style>

	<!-- ── Hero sekce ── -->
	<div class="ben-section" style="margin-top:0;padding-top:0;border-top:none;">
		<h4>Hero sekce</h4>
		<p class="description" style="margin-bottom:12px;">Fotografie na pozadí = <strong>Náhledový obrázek stránky</strong> (nastavte v pravém sloupci). Maska / divadelní motiv se zobrazí automaticky.</p>
		<table class="form-table">
			<?php
			$row( 'Ročník / rok (badge)', '_ben_edition', 'text', '7. ročník · 2026' );
			$textarea( 'Perex (lead text)', '_ben_lead', 3 );
			$row( 'Termín konání', '_ben_termin', 'text', 'Listopad 2026' );
			$row( 'Pro koho', '_ben_pro_koho', 'text', 'Rodiny · Přátelé divadla · Veřejnost' );
			$row( 'Výtěžek pro', '_ben_vytezek', 'text', 'Podpora dětí v náhradní péči — CPNRP' );
			$row( 'URL webu benefice', '_ben_web_url', 'url', 'https://www.divadelni-benefice.cz' );
			?>
		</table>
	</div>

	<!-- ── Počítadlo ── -->
	<div class="ben-section">
		<h4>Počítadlo — 3 čísla</h4>
		<table class="form-table">
			<?php
			for ( $i = 1; $i <= 3; $i++ ) {
				$row( "Číslo {$i}", "_ben_stat{$i}_num" );
				$row( "Popis {$i}", "_ben_stat{$i}_label" );
				$row( "Poznámka {$i}", "_ben_stat{$i}_note" );
				if ( $i < 3 ) echo '<tr><td colspan="2"><hr style="margin:4px 0;border:none;border-top:1px solid #eee"></td></tr>';
			}
			?>
		</table>
	</div>

	<!-- ── Místa konání ── -->
	<div class="ben-section">
		<h4>Místa konání (až 6 měst)</h4>
		<p class="description" style="margin-bottom:12px;">Nevyplněná pole se na stránce nezobrazí. Zapište název divadla a/nebo města.</p>
		<table class="form-table">
			<?php
			$venue_ph = [ 1 => 'Litoměřice', 2 => 'Ústí nad Labem', 3 => 'Lovosice', 4 => 'Roudnice nad Labem', 5 => '', 6 => '' ];
			for ( $i = 1; $i <= 6; $i++ ) {
				$row( "Místo {$i}", "_ben_venue{$i}", 'text', $venue_ph[ $i ] );
			}
			?>
		</table>
	</div>

	<!-- ── Plakáty ── -->
	<div class="ben-section">
		<h4>Plakáty — přední a zadní strana</h4>
		<p class="description" style="margin-bottom:12px;">Nahrajte plakáty přes <strong>Média → Nahrát</strong>, pak klikněte „Vybrat obrázek".</p>
		<table class="form-table">
			<?php
			$image_picker( 'Plakát — strana 1', '_ben_plakat_1' );
			$image_picker( 'Plakát — strana 2', '_ben_plakat_2' );
			?>
		</table>
	</div>

	<!-- ── Galerie & výtěžek ── -->
	<div class="ben-section">
		<h4>Fotogalerie &amp; výtěžek <small style="font-weight:400;text-transform:none;letter-spacing:0;">(sekce se zobrazí jen když je vyplněno)</small></h4>
		<table class="form-table">
			<?php
			$row( 'Nadpis sekce', '_ben_gallery_title', 'text', 'Z proběhlé benefice' );
			$textarea( 'Text (předání výtěžku, poděkování…)', '_ben_gallery_text', 5 );
			?>
		</table>
		<div style="margin:14px 0 6px;font-weight:600;font-size:13px;">Fotografie</div>
		<div id="ben-gallery-list" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:10px;">
			<?php
			$_gi_json = get_post_meta( $post->ID, '_ben_gallery_imgs', true );
			$_gi_list = [];
			if ( $_gi_json ) {
				$_gi_dec = json_decode( $_gi_json, true );
				if ( is_array( $_gi_dec ) ) $_gi_list = array_values( array_filter( $_gi_dec ) );
			}
			foreach ( $_gi_list as $_gi_url ) :
				$_gi_url = esc_url( $_gi_url );
			?>
			<div class="ben-gallery-item" style="position:relative;border:1px solid #ddd;border-radius:4px;overflow:hidden;background:#f9f9f9;width:120px;">
				<img src="<?php echo $_gi_url; ?>" class="ben-gallery-img-preview" style="width:120px;height:80px;object-fit:cover;display:block;">
				<input type="hidden" class="ben-gallery-img-url" value="<?php echo $_gi_url; ?>">
				<div style="padding:4px;display:flex;gap:4px;justify-content:center;">
					<button type="button" class="button button-small ben-gallery-change">Změnit</button>
					<button type="button" class="button button-small ben-gallery-remove" style="color:#a00;">✕</button>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button button-secondary" id="ben-add-gallery-photo">+ Přidat foto</button>
		<input type="hidden" name="ben_gallery_imgs" id="ben-gallery-imgs-json" value="<?php echo esc_attr( $_gi_json ?: '[]' ); ?>">
	</div>

	<!-- ── Partneři ── -->
	<div class="ben-section">
		<h4>Partneři / sponzoři</h4>
		<div id="ben-sponsors-list" style="margin-bottom:10px;">
			<?php
			$_sp_json  = get_post_meta( $post->ID, '_ben_sponsors', true );
			$_sp_list  = [];
			if ( $_sp_json ) {
				$_decoded = json_decode( $_sp_json, true );
				if ( is_array( $_decoded ) ) $_sp_list = $_decoded;
			}
			foreach ( $_sp_list as $_sp ) :
				$_sp_img  = esc_url( $_sp['img']  ?? '' );
				$_sp_name = esc_attr( $_sp['name'] ?? '' );
				$_sp_url  = esc_url( $_sp['url']  ?? '' );
			?>
			<div class="ben-sponsor-row" style="display:flex;align-items:center;gap:12px;margin-bottom:10px;padding:10px;background:#f9f9f9;border:1px solid #ddd;border-radius:4px;">
				<div style="flex-shrink:0;">
					<img src="<?php echo $_sp_img; ?>" class="ben-sponsor-preview" style="max-width:80px;max-height:50px;display:<?php echo $_sp_img ? 'block' : 'none'; ?>;">
				</div>
				<div style="flex:1;display:flex;flex-direction:column;gap:6px;">
					<input type="hidden" class="ben-sponsor-img" value="<?php echo $_sp_img; ?>">
					<div style="display:flex;gap:8px;align-items:center;">
						<button type="button" class="button ben-sponsor-pick"><?php echo $_sp_img ? 'Změnit logo' : 'Vybrat logo'; ?></button>
						<?php if ( $_sp_img ) : ?><a href="#" class="ben-sponsor-remove-img" style="font-size:12px;color:#a00;">✕ Odebrat logo</a><?php endif; ?>
					</div>
					<input type="text" class="ben-sponsor-name large-text" placeholder="Název partnera" value="<?php echo $_sp_name; ?>">
					<input type="url"  class="ben-sponsor-url  large-text" placeholder="https://… (web partnera)" value="<?php echo $_sp_url; ?>">
				</div>
				<div style="flex-shrink:0;">
					<button type="button" class="button ben-sponsor-delete" style="color:#a00;">Odebrat</button>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button button-secondary" id="ben-add-sponsor">+ Přidat partnera</button>
		<input type="hidden" name="ben_sponsors" id="ben-sponsors-json" value="<?php echo esc_attr( $_sp_json ?: '[]' ); ?>">
	</div>

	<!-- ── Pořadatelé ── -->
	<div class="ben-section">
		<h4>Pořadatelé <small style="font-weight:400;text-transform:none;letter-spacing:0;">(sekce se zobrazí jen když je vyplněno)</small></h4>
		<table class="form-table">
			<?php $textarea( 'Text — kdo se podílí na pořádání', '_ben_organizers', 5, "CPNRP, o.p.s.\nDivadlo na Mostě, Litoměřice" ); ?>
		</table>
	</div>

	<!-- ── Kontakt ── -->
	<div class="ben-section">
		<h4>Kontakt na organizátora <small style="font-weight:400;text-transform:none;letter-spacing:0;">(sekce se zobrazí jen když je vyplněno)</small></h4>
		<table class="form-table">
			<?php
			$row( 'Jméno', '_ben_contact_name', 'text' );
			$row( 'Funkce / role', '_ben_contact_role', 'text' );
			$row( 'E-mail', '_ben_contact_email', 'email', 'info@cpnrp.cz' );
			$row( 'Telefon', '_ben_contact_phone', 'text', '+420 XXX XXX XXX' );
			?>
		</table>
	</div>

	<script>
	(function ($) {
		$(document).on('click', '.ben-pick-img', function (e) {
			e.preventDefault();
			var btn    = $(this);
			var target = btn.data('target');
			var frame  = wp.media({ title: 'Vybrat obrázek', multiple: false, library: { type: 'image' } });
			frame.on('select', function () {
				var att = frame.state().get('selection').first().toJSON();
				$('#' + target).val(att.url);
				var $preview = $('#' + target + '_preview');
				$preview.attr('src', att.url).show();
				btn.text('Změnit obrázek');
				if (!btn.siblings('.ben-remove-img').length) {
					btn.after('<a href="#" class="ben-remove-img" data-target="' + target + '" style="font-size:12px;color:#a00;">✕ Odebrat</a>');
				}
			});
			frame.open();
		});

		$(document).on('click', '.ben-remove-img', function (e) {
			e.preventDefault();
			var target = $(this).data('target');
			$('#' + target).val('');
			$('#' + target + '_preview').attr('src', '').hide();
			$(this).siblings('.ben-pick-img').text('Vybrat obrázek');
			$(this).remove();
		});

		// ── Sponsors ──────────────────────────────────────────────────────────
		function benSponsorRowHtml() {
			return '<div class="ben-sponsor-row" style="display:flex;align-items:center;gap:12px;margin-bottom:10px;padding:10px;background:#f9f9f9;border:1px solid #ddd;border-radius:4px;">' +
				'<div style="flex-shrink:0;"><img src="" class="ben-sponsor-preview" style="max-width:80px;max-height:50px;display:none;"></div>' +
				'<div style="flex:1;display:flex;flex-direction:column;gap:6px;">' +
					'<input type="hidden" class="ben-sponsor-img" value="">' +
					'<div style="display:flex;gap:8px;align-items:center;"><button type="button" class="button ben-sponsor-pick">Vybrat logo</button></div>' +
					'<input type="text" class="ben-sponsor-name large-text" placeholder="Název partnera" value="">' +
					'<input type="url"  class="ben-sponsor-url  large-text" placeholder="https://… (web partnera)" value="">' +
				'</div>' +
				'<div style="flex-shrink:0;"><button type="button" class="button ben-sponsor-delete" style="color:#a00;">Odebrat</button></div>' +
			'</div>';
		}

		$('#ben-add-sponsor').on('click', function () {
			$('#ben-sponsors-list').append(benSponsorRowHtml());
		});

		$(document).on('click', '.ben-sponsor-delete', function () {
			$(this).closest('.ben-sponsor-row').remove();
		});

		$(document).on('click', '.ben-sponsor-pick', function (e) {
			e.preventDefault();
			var $row  = $(this).closest('.ben-sponsor-row');
			var $btn  = $(this);
			var frame = wp.media({ title: 'Vybrat logo partnera', multiple: false, library: { type: 'image' } });
			frame.on('select', function () {
				var att = frame.state().get('selection').first().toJSON();
				$row.find('.ben-sponsor-img').val(att.url);
				$row.find('.ben-sponsor-preview').attr('src', att.url).show();
				$btn.text('Změnit logo');
				if (!$row.find('.ben-sponsor-remove-img').length) {
					$btn.after('<a href="#" class="ben-sponsor-remove-img" style="font-size:12px;color:#a00;margin-left:6px;">✕ Odebrat logo</a>');
				}
			});
			frame.open();
		});

		$(document).on('click', '.ben-sponsor-remove-img', function (e) {
			e.preventDefault();
			var $row = $(this).closest('.ben-sponsor-row');
			$row.find('.ben-sponsor-img').val('');
			$row.find('.ben-sponsor-preview').attr('src', '').hide();
			$row.find('.ben-sponsor-pick').text('Vybrat logo');
			$(this).remove();
		});

		$('form#post').on('submit', function () {
			var list = [];
			$('.ben-sponsor-row').each(function () {
				var img = $(this).find('.ben-sponsor-img').val().trim();
				if (!img) return;
				list.push({
					img:  img,
					name: $(this).find('.ben-sponsor-name').val().trim(),
					url:  $(this).find('.ben-sponsor-url').val().trim()
				});
			});
			$('#ben-sponsors-json').val(JSON.stringify(list));
		});

		// ── Gallery images ─────────────────────────────────────────────────────
		function benGalleryItemHtml(url) {
			return '<div class="ben-gallery-item" style="position:relative;border:1px solid #ddd;border-radius:4px;overflow:hidden;background:#f9f9f9;width:120px;">' +
				'<img src="' + url + '" class="ben-gallery-img-preview" style="width:120px;height:80px;object-fit:cover;display:block;">' +
				'<input type="hidden" class="ben-gallery-img-url" value="' + url + '">' +
				'<div style="padding:4px;display:flex;gap:4px;justify-content:center;">' +
					'<button type="button" class="button button-small ben-gallery-change">Změnit</button>' +
					'<button type="button" class="button button-small ben-gallery-remove" style="color:#a00;">✕</button>' +
				'</div>' +
			'</div>';
		}

		$('#ben-add-gallery-photo').on('click', function () {
			var frame = wp.media({ title: 'Vybrat fotografie', multiple: true, library: { type: 'image' } });
			frame.on('select', function () {
				frame.state().get('selection').each(function (att) {
					$('#ben-gallery-list').append(benGalleryItemHtml(att.toJSON().url));
				});
			});
			frame.open();
		});

		$(document).on('click', '.ben-gallery-change', function (e) {
			e.preventDefault();
			var $item = $(this).closest('.ben-gallery-item');
			var frame = wp.media({ title: 'Změnit fotografii', multiple: false, library: { type: 'image' } });
			frame.on('select', function () {
				var url = frame.state().get('selection').first().toJSON().url;
				$item.find('.ben-gallery-img-url').val(url);
				$item.find('.ben-gallery-img-preview').attr('src', url);
			});
			frame.open();
		});

		$(document).on('click', '.ben-gallery-remove', function () {
			$(this).closest('.ben-gallery-item').remove();
		});

		$('form#post').on('submit', function () {
			var giList = [];
			$('.ben-gallery-img-url').each(function () {
				var url = $(this).val().trim();
				if (url) giList.push(url);
			});
			$('#ben-gallery-imgs-json').val(JSON.stringify(giList));
		});
	}(jQuery));
	</script>
	<?php
}

// ── Save ──────────────────────────────────────────────────────────────
add_action( 'save_post', function ( $post_id ) {
	if ( ! isset( $_POST['cpnrp_ben_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['cpnrp_ben_nonce'], 'cpnrp_ben_save' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	$fields = [
		'_ben_edition'       => 'sanitize_text_field',
		'_ben_lead'          => 'sanitize_textarea_field',
		'_ben_termin'        => 'sanitize_text_field',
		'_ben_pro_koho'      => 'sanitize_text_field',
		'_ben_vytezek'       => 'sanitize_text_field',
		'_ben_web_url'       => 'esc_url_raw',
		'_ben_stat1_num'     => 'sanitize_text_field',
		'_ben_stat1_label'   => 'sanitize_text_field',
		'_ben_stat1_note'    => 'sanitize_text_field',
		'_ben_stat2_num'     => 'sanitize_text_field',
		'_ben_stat2_label'   => 'sanitize_text_field',
		'_ben_stat2_note'    => 'sanitize_text_field',
		'_ben_stat3_num'     => 'sanitize_text_field',
		'_ben_stat3_label'   => 'sanitize_text_field',
		'_ben_stat3_note'    => 'sanitize_text_field',
		'_ben_venue1'        => 'sanitize_text_field',
		'_ben_venue2'        => 'sanitize_text_field',
		'_ben_venue3'        => 'sanitize_text_field',
		'_ben_venue4'        => 'sanitize_text_field',
		'_ben_venue5'        => 'sanitize_text_field',
		'_ben_venue6'        => 'sanitize_text_field',
		'_ben_plakat_1'      => 'esc_url_raw',
		'_ben_plakat_2'      => 'esc_url_raw',
		'_ben_gallery_title' => 'sanitize_text_field',
		'_ben_gallery_text'  => 'sanitize_textarea_field',
		'_ben_organizers'    => 'sanitize_textarea_field',
		'_ben_contact_name'  => 'sanitize_text_field',
		'_ben_contact_role'  => 'sanitize_text_field',
		'_ben_contact_email' => 'sanitize_email',
		'_ben_contact_phone' => 'sanitize_text_field',
	];

	foreach ( $fields as $meta_key => $sanitizer ) {
		$post_key = ltrim( $meta_key, '_' );
		if ( isset( $_POST[ $post_key ] ) ) {
			update_post_meta( $post_id, $meta_key, $sanitizer( $_POST[ $post_key ] ) );
		}
	}

	// Gallery images — saved as JSON array of URLs
	if ( isset( $_POST['ben_gallery_imgs'] ) ) {
		$decoded = json_decode( stripslashes( $_POST['ben_gallery_imgs'] ), true );
		if ( is_array( $decoded ) ) {
			$clean = array_values( array_filter( array_map( 'esc_url_raw', $decoded ) ) );
			update_post_meta( $post_id, '_ben_gallery_imgs', json_encode( $clean, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
		}
	}

	// Sponsors — saved as JSON from the repeatable UI
	if ( isset( $_POST['ben_sponsors'] ) ) {
		$decoded = json_decode( stripslashes( $_POST['ben_sponsors'] ), true );
		if ( is_array( $decoded ) ) {
			$clean = array_values( array_filter( array_map( function ( $s ) {
				if ( empty( $s['img'] ) ) return null;
				return [
					'img'  => esc_url_raw( $s['img'] ),
					'name' => sanitize_text_field( $s['name'] ?? '' ),
					'url'  => esc_url_raw( $s['url'] ?? '' ),
				];
			}, $decoded ) ) );
			update_post_meta( $post_id, '_ben_sponsors', json_encode( $clean, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) );
		}
	}
} );
