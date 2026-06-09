<?php
/**
 * Template Name: Kontakt
 * Template for the /kontakt/ page.
 */

get_header();

// ── Team groups (sorted) ────────────────────────────────────────
$team_groups = get_terms( [ 'taxonomy' => 'contact_group', 'hide_empty' => true ] );
$group_order = [ 'Vedení', 'Vzdělávání & programy', 'Doprovázení', 'Odborné poradenství' ];
if ( ! is_wp_error( $team_groups ) && $team_groups ) {
	usort( $team_groups, function( $a, $b ) use ( $group_order ) {
		$ai = array_search( $a->name, $group_order );
		$bi = array_search( $b->name, $group_order );
		return ( $ai === false ? 99 : $ai ) <=> ( $bi === false ? 99 : $bi );
	} );
}

// ── Offices from Customizer ─────────────────────────────────────
$allowed_colors = [ 'teal-dark', 'teal-light', 'gold', 'red', 'green' ];

$pobocky_defaults = [
	1 => [ 'name' => 'Litoměřice — Poradna',  'addr' => 'Teplická 1672/3, 412 01 Litoměřice',        'hours' => 'Po — Pá: 8:00 — 16:00',                   'phone1' => '+420 416 533 554', 'phone2' => '+420 771 770 335', 'color' => 'teal-dark',  'map_url' => 'https://frame.mapy.cz/zakladni?x=14.1376&y=50.5334&z=17&source=coor&id=14.1376%2C50.5334' ],
	2 => [ 'name' => 'Litoměřice — Centrum',   'addr' => '5. května 76, 412 01 Litoměřice',           'hours' => 'Pondělí: 8:30 — 16:00',                    'phone1' => '+420 731 557 681', 'phone2' => '',                'color' => 'teal-light', 'map_url' => 'https://frame.mapy.cz/zakladni?x=14.1400&y=50.5357&z=17&source=coor&id=14.1400%2C50.5357' ],
	3 => [ 'name' => 'Ústí nad Labem',         'addr' => 'V Jirchářích 60/6, 400 02 Ústí nad Labem', 'hours' => 'Po, St: 8:00 — 16:00 · Út, Čt, Pá: terén', 'phone1' => '+420 771 770 360', 'phone2' => '+420 771 770 340','color' => 'gold',       'map_url' => 'https://frame.mapy.cz/zakladni?x=14.0515&y=50.6596&z=17&source=coor&id=14.0515%2C50.6596' ],
	4 => [ 'name' => 'Rumburk',                'addr' => 'Matušova 982, 408 01 Rumburk',              'hours' => 'Středa: 9:00 — 16:00',                     'phone1' => '+420 771 770 360', 'phone2' => '',                'color' => 'red',        'map_url' => 'https://frame.mapy.cz/zakladni?x=14.5566&y=50.9536&z=17&source=coor&id=14.5566%2C50.9536' ],
];

// Open/closed schedules built from Customizer — format: 'day' => ['HH:MM open', 'HH:MM close']
$schedule_days    = [ 'mon', 'tue', 'wed', 'thu', 'fri' ];
$schedule_defaults = [
	1 => [ 'mon' => '08:00-16:00', 'tue' => '08:00-16:00', 'wed' => '08:00-16:00', 'thu' => '08:00-16:00', 'fri' => '08:00-16:00' ],
	2 => [ 'mon' => '08:30-16:00', 'tue' => '',             'wed' => '',             'thu' => '',             'fri' => ''             ],
	3 => [ 'mon' => '08:00-16:00', 'tue' => '',             'wed' => '08:00-16:00', 'thu' => '',             'fri' => ''             ],
	4 => [ 'mon' => '',             'tue' => '',             'wed' => '09:00-16:00', 'thu' => '',             'fri' => ''             ],
];
$office_schedules = [];
for ( $si = 1; $si <= 4; $si++ ) {
	$sched = [];
	foreach ( $schedule_days as $day ) {
		$raw = get_theme_mod( "cpnrp_pobocka_{$si}_schedule_{$day}", $schedule_defaults[ $si ][ $day ] ?? '' );
		if ( $raw && preg_match( '/^(\d{2}:\d{2})-(\d{2}:\d{2})$/', trim( $raw ), $m ) ) {
			$sched[ $day ] = [ $m[1], $m[2] ];
		}
	}
	$office_schedules[ $si ] = $sched;
}

$offices = [];
for ( $i = 1; $i <= 4; $i++ ) {
	$def     = $pobocky_defaults[ $i ];
	$name    = get_theme_mod( "cpnrp_pobocka_{$i}_name",    $def['name']   );
	$addr    = get_theme_mod( "cpnrp_pobocka_{$i}_addr",    $def['addr']   );
	$hours   = get_theme_mod( "cpnrp_pobocka_{$i}_hours",   $def['hours']  );
	$phone1  = get_theme_mod( "cpnrp_pobocka_{$i}_phone1",  $def['phone1'] );
	$phone2  = get_theme_mod( "cpnrp_pobocka_{$i}_phone2",  $def['phone2'] );
	$color   = get_theme_mod( "cpnrp_pobocka_{$i}_color",   $def['color']  );
	$map_url = get_theme_mod( "cpnrp_pobocka_{$i}_map_url", $def['map_url'] );
	if ( ! in_array( $color, $allowed_colors, true ) ) $color = 'teal-dark';
	$phones = array_values( array_filter( [ $phone1, $phone2 ] ) );
	$offices[] = compact( 'name', 'addr', 'hours', 'phones', 'color', 'map_url' ) + [ 'schedule' => $office_schedules[ $i ] ?? [] ];
}

// ── Open/closed helper ──────────────────────────────────────────
function cpnrp_is_open( array $schedule ): ?bool {
	if ( empty( $schedule ) ) return null;
	$tz      = new DateTimeZone( 'Europe/Prague' );
	$now     = new DateTime( 'now', $tz );
	$day_map = [ 1=>'mon', 2=>'tue', 3=>'wed', 4=>'thu', 5=>'fri', 6=>'sat', 7=>'sun' ];
	$day     = $day_map[ (int) $now->format('N') ] ?? '';
	if ( ! isset( $schedule[ $day ] ) ) return false;
	$time = $now->format('H:i');
	return $time >= $schedule[$day][0] && $time < $schedule[$day][1];
}

// ── Contact info ────────────────────────────────────────────────
$kontakt_address = get_theme_mod( 'cpnrp_kontakt_address', 'Teplická 1672/3, 412 01 Litoměřice' );
$kontakt_phone   = get_theme_mod( 'cpnrp_kontakt_phone',   '+420 731 557 681' );
$kontakt_email   = get_theme_mod( 'cpnrp_kontakt_email',   'info@cpnrp.cz' );
$kontakt_hours   = get_theme_mod( 'cpnrp_kontakt_hours',   'Po — Pá: 9:00 — 17:00' );
?>

<main id="main-content" role="main">

	<!-- ── Hero ──────────────────────────────────────────────────── -->
	<section class="kontakt-hero">
		<div class="container">
			<div class="kontakt-hero-grid">

				<div class="kontakt-hero-text">
					<nav class="kontakt-breadcrumb" aria-label="Breadcrumb">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
						<span>/</span>
						<span><?php esc_html_e( 'Kontakt', 'cpnrp' ); ?></span>
					</nav>
					<h1 class="kontakt-hero-title"><?php esc_html_e( 'Kontakt', 'cpnrp' ); ?></h1>
					<p class="kontakt-hero-lead"><?php esc_html_e( 'Neváhejte se na nás obrátit. Jsme tu pro vás.', 'cpnrp' ); ?></p>
				</div>

				<div class="kontakt-hero-image-wrap">
					<img
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/contact/kolaz.jpg' ); ?>"
						alt="<?php esc_attr_e( 'Tým CPNRP', 'cpnrp' ); ?>"
						loading="eager"
						class="kontakt-hero-image"
					>
				</div>

			</div>

			<!-- Contact info row -->
			<dl class="kontakt-info-row">
				<div class="kontakt-info-item">
					<dt><?php esc_html_e( 'Sídlo organizace', 'cpnrp' ); ?></dt>
					<dd><?php echo nl2br( esc_html( str_replace( ',', ",\n", $kontakt_address ) ) ); ?></dd>
				</div>
				<div class="kontakt-info-item">
					<dt><?php esc_html_e( 'Telefon', 'cpnrp' ); ?></dt>
					<dd><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $kontakt_phone ) ); ?>"><?php echo esc_html( $kontakt_phone ); ?></a></dd>
				</div>
				<div class="kontakt-info-item">
					<dt><?php esc_html_e( 'E-mail', 'cpnrp' ); ?></dt>
					<dd><a href="mailto:<?php echo esc_attr( $kontakt_email ); ?>"><?php echo esc_html( $kontakt_email ); ?></a></dd>
				</div>
				<div class="kontakt-info-item">
					<dt><?php esc_html_e( 'Úřední hodiny', 'cpnrp' ); ?></dt>
					<dd><?php echo esc_html( $kontakt_hours ); ?></dd>
				</div>
			</dl>
		</div>
	</section>

	<!-- ── Team directory ────────────────────────────────────────── -->
	<section id="kontakt-tym" class="kontakt-team">
		<div class="container">

			<div class="section-heading text-center animate-fade-up">
				<h2 class="section-title"><?php esc_html_e( 'Náš tým', 'cpnrp' ); ?></h2>
				<div class="section-title-bar"></div>
				<p class="section-subtitle"><?php esc_html_e( 'Poznejte lidi, kteří stojí za naší prací', 'cpnrp' ); ?></p>
			</div>

			<!-- Team search filter -->
			<div class="kontakt-team-search">
				<div class="kontakt-team-search-wrap">
					<svg class="kontakt-team-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
						<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
					</svg>
					<input
						type="search"
						id="kontakt-team-filter"
						class="kontakt-team-search-input"
						placeholder="<?php esc_attr_e( 'Hledat osobu…', 'cpnrp' ); ?>"
						autocomplete="off"
					>
					<button type="button" class="kontakt-team-search-clear" id="kontakt-team-clear" aria-label="Vymazat hledání" hidden>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
							<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
						</svg>
					</button>
				</div>
			</div>

			<!-- No results message -->
			<p class="kontakt-team-noresults" id="kontakt-team-noresults" hidden>
				<?php esc_html_e( 'Žádná shoda. Zkuste jiné jméno.', 'cpnrp' ); ?>
			</p>

			<?php if ( ! is_wp_error( $team_groups ) && $team_groups ) : ?>
				<?php foreach ( $team_groups as $group ) : ?>

					<div class="kontakt-group" data-group>
						<div class="kontakt-group-divider">
							<span><?php echo esc_html( $group->name ); ?></span>
						</div>

						<div class="kontakt-person-grid">
							<?php
							$persons = new WP_Query( [
								'post_type'      => 'contact_person',
								'post_status'    => 'publish',
								'posts_per_page' => -1,
								'orderby'        => 'meta_value_num',
								'meta_key'       => '_contact_order',
								'order'          => 'ASC',
								'tax_query'      => [ [
									'taxonomy' => 'contact_group',
									'field'    => 'term_id',
									'terms'    => $group->term_id,
								] ],
							] );
							while ( $persons->have_posts() ) :
								$persons->the_post();
								$pid   = get_the_ID();
								$role  = get_post_meta( $pid, '_contact_role',  true );
								$phone = get_post_meta( $pid, '_contact_phone', true );
								$email = get_post_meta( $pid, '_contact_email', true );
								$bio   = get_post_meta( $pid, '_contact_bio',   true );
								$p2id  = (int) get_post_meta( $pid, '_contact_photo2', true );
								$has_popup = $bio || $p2id;
							?>
							<div class="kontakt-person-card<?php echo $has_popup ? ' has-popup' : ''; ?>"
							     data-name="<?php echo esc_attr( mb_strtolower( get_the_title() ) ); ?>">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'thumbnail', [ 'class' => 'kontakt-person-photo', 'alt' => esc_attr( get_the_title() ) ] ); ?>
								<?php else : ?>
									<div class="kontakt-person-photo kontakt-person-photo--placeholder">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
											<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
										</svg>
									</div>
								<?php endif; ?>
								<h4 class="kontakt-person-name"><?php the_title(); ?></h4>
								<?php if ( $role ) : ?>
									<p class="kontakt-person-role"><?php echo esc_html( $role ); ?></p>
								<?php endif; ?>
								<?php if ( $phone ) : ?>
									<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>" class="kontakt-person-phone">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
											<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
										</svg>
										<?php echo esc_html( $phone ); ?>
									</a>
								<?php else : ?>
									<p class="kontakt-person-no-phone"><?php esc_html_e( 'Kontakt přes vedoucí služeb', 'cpnrp' ); ?></p>
								<?php endif; ?>
								<?php if ( $email ) : ?>
									<a href="mailto:<?php echo esc_attr( $email ); ?>" class="kontakt-person-email">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
											<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/>
										</svg>
										<?php echo esc_html( $email ); ?>
									</a>
								<?php endif; ?>
								<?php if ( $has_popup ) : ?>
									<button type="button" class="kontakt-person-bio-btn"
									        data-person="<?php echo esc_attr( $pid ); ?>"
									        aria-haspopup="dialog">
										<?php esc_html_e( 'Více o mně', 'cpnrp' ); ?>
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true" width="13" height="13">
											<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
										</svg>
									</button>
								<?php endif; ?>
							</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>

				<?php endforeach; ?>
			<?php endif; ?>

		</div>
	</section>

	<!-- ── Person bio modals ────────────────────────────────────────── -->
	<?php
	$bio_persons = get_posts( [
		'post_type'      => 'contact_person',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_query'     => [ [
			'relation' => 'OR',
			[ 'key' => '_contact_bio',    'value' => '', 'compare' => '!=' ],
			[ 'key' => '_contact_photo2', 'value' => '', 'compare' => '!=' ],
		] ],
	] );
	foreach ( $bio_persons as $bp ) :
		$bp_bio   = get_post_meta( $bp->ID, '_contact_bio',    true );
		$bp_p2    = (int) get_post_meta( $bp->ID, '_contact_photo2', true );
		$bp_role  = get_post_meta( $bp->ID, '_contact_role',  true );
		$bp_phone = get_post_meta( $bp->ID, '_contact_phone', true );
		$bp_email = get_post_meta( $bp->ID, '_contact_email', true );
		$bp_p1_url = get_the_post_thumbnail_url( $bp->ID, 'medium' );
		$bp_p2_url = $bp_p2 ? wp_get_attachment_image_url( $bp_p2, 'large' ) : '';
	?>
	<div class="person-modal-overlay" id="person-modal-<?php echo esc_attr( $bp->ID ); ?>"
	     role="dialog" aria-modal="true" style="display:none"
	     aria-labelledby="person-modal-title-<?php echo esc_attr( $bp->ID ); ?>">
		<div class="person-modal">

			<button type="button" class="person-modal-close"
			        aria-label="<?php esc_attr_e( 'Zavřít', 'cpnrp' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
				</svg>
			</button>

			<!-- Portrait photo column -->
			<?php $portrait = $bp_p2_url ?: $bp_p1_url; ?>
			<div class="person-modal-photo-col<?php echo $portrait ? '' : ' person-modal-photo-col--placeholder'; ?>">
				<?php if ( $portrait ) : ?>
					<div class="person-modal-photo-bg"
					     style="background-image:url('<?php echo esc_url( $portrait ); ?>')"></div>
					<img src="<?php echo esc_url( $portrait ); ?>"
					     alt="<?php echo esc_attr( $bp->post_title ); ?>">
				<?php else : ?>
					<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" aria-hidden="true">
						<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
					</svg>
				<?php endif; ?>
			</div>

			<!-- Right: name + role + bio -->
			<div class="person-modal-content">
				<div class="person-modal-identity">
					<h2 class="person-modal-name"
					    id="person-modal-title-<?php echo esc_attr( $bp->ID ); ?>">
						<?php echo esc_html( $bp->post_title ); ?>
					</h2>
					<?php if ( $bp_role ) : ?>
						<p class="person-modal-role"><?php echo esc_html( $bp_role ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( $bp_bio ) : ?>
				<div class="person-modal-body">
					<?php echo wp_kses_post( $bp_bio ); ?>
				</div>
				<?php endif; ?>
			</div>

		</div>
	</div>
	<?php endforeach; ?>

	<!-- ── Offices ───────────────────────────────────────────────── -->
	<section id="kontakt-pobocky" class="kontakt-offices">
		<div class="container">

			<div class="section-heading text-center animate-fade-up">
				<h2 class="section-title"><?php esc_html_e( 'Naše pobočky', 'cpnrp' ); ?></h2>
				<div class="section-title-bar"></div>
				<p class="section-subtitle"><?php esc_html_e( 'Kde nás najdete', 'cpnrp' ); ?></p>
			</div>

			<div class="kontakt-offices-grid">
				<?php foreach ( $offices as $office ) :
					$is_open = cpnrp_is_open( $office['schedule'] );
				?>
				<div class="kontakt-office-card kontakt-office-card--<?php echo esc_attr( $office['color'] ); ?>">

					<div class="kontakt-office-header">
						<h3 class="kontakt-office-name"><?php echo esc_html( $office['name'] ); ?></h3>
						</div>

					<div class="kontakt-office-details">
						<?php if ( $office['addr'] ) : ?>
						<div class="kontakt-office-row">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
							</svg>
							<span><?php echo esc_html( $office['addr'] ); ?></span>
						</div>
						<?php endif; ?>
						<?php if ( $office['hours'] ) : ?>
						<div class="kontakt-office-row">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
								<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
							</svg>
							<span><?php echo esc_html( $office['hours'] ); ?></span>
						</div>
						<?php endif; ?>
						<?php if ( $office['phones'] ) : ?>
						<div class="kontakt-office-row">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
								<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
							</svg>
							<div class="kontakt-office-phones">
								<?php foreach ( $office['phones'] as $phone ) : ?>
									<a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
								<?php endforeach; ?>
							</div>
						</div>
						<?php endif; ?>
					</div>

					<?php if ( $office['map_url'] ) : ?>
					<iframe
						class="kontakt-office-map"
						src="<?php echo esc_url( $office['map_url'] ); ?>"
						title="<?php echo esc_attr( $office['name'] ); ?>"
						loading="lazy"
						allowfullscreen
					></iframe>
					<?php endif; ?>

				</div>
				<?php endforeach; ?>
			</div>

		</div>
	</section>

	<!-- ── Contact form (Gravity Forms) ─────────────────────────── -->
	<section id="kontakt-form" class="kontakt-form-section">
		<div class="container">
			<div class="kontakt-form-wrap">
				<p class="kontakt-eyebrow"><?php esc_html_e( 'Napište nám', 'cpnrp' ); ?></p>
				<h2 class="kontakt-form-title"><?php esc_html_e( 'Pošlete nám zprávu', 'cpnrp' ); ?></h2>
				<p class="kontakt-form-lead"><?php esc_html_e( 'Odpovíme zpravidla do 2 pracovních dnů.', 'cpnrp' ); ?></p>

				<div class="kontakt-form kontakt-gf-wrap">
					<?php
					$form_id = get_option( 'cpnrp_kontakt_form_id' );
					if ( $form_id && class_exists( 'GFAPI' ) ) {
						echo do_shortcode( '[gravityforms id="' . absint( $form_id ) . '" ajax="true"]' );
					}
					?>
				</div>
			</div>
		</div>
	</section>

</main>

<style>
/* ── Více o mně button ──────────────────────────────────────────── */
.kontakt-person-bio-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  margin-top: 14px;
  padding: 7px 14px;
  background: none;
  border: 1.5px solid var(--color-teal-dark);
  color: var(--color-teal-dark);
  border-radius: var(--radius-btn);
  font-size: .9rem;
  font-weight: 600;
  font-family: var(--font-sans);
  cursor: pointer;
  transition: background 160ms ease, color 160ms ease;
  width: 100%;
  justify-content: center;
}
.kontakt-person-bio-btn:hover {
  background: var(--color-teal-dark);
  color: #fff;
}
.kontakt-person-card.has-popup { padding-bottom: 16px; }

/* ── Modal overlay ──────────────────────────────────────────────── */
.person-modal-overlay {
  position: fixed; inset: 0; z-index: 9999;
  background: rgba(10,25,40,.28);
  backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
  display: flex; align-items: center; justify-content: center; padding: 16px;
  opacity: 0; visibility: hidden; pointer-events: none;
  transition: opacity 220ms ease, visibility 220ms ease;
}
.person-modal-overlay.is-open { opacity: 1; visibility: visible; pointer-events: auto; }

/* ── Modal card ─────────────────────────────────────────────────── */
.person-modal {
  position: relative;
  background: var(--color-white);
  border-radius: 20px;
  max-width: 780px;
  width: 100%;
  max-height: 92svh;
  overflow: hidden;
  box-shadow: 0 24px 80px rgba(10,25,40,.22), 0 0 0 1px rgba(10,25,40,.06);
  transform: scale(.93) translateY(24px);
  transition: transform 300ms cubic-bezier(.22,1,.36,1);
  display: flex;
}
.person-modal-overlay.is-open .person-modal { transform: scale(1) translateY(0); }

/* ── Close button ───────────────────────────────────────────────── */
.person-modal-close {
  position: absolute; top: 14px; right: 14px; z-index: 10;
  width: 32px; height: 32px; border: none; border-radius: 50%;
  background: rgba(255,255,255,.88); color: var(--color-text-muted);
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  box-shadow: 0 2px 8px rgba(10,25,40,.15);
  transition: background 150ms ease, color 150ms ease; font-size: 0;
}
.person-modal-close:hover { background: #fff; color: var(--color-teal-dark); }

/* ── Portrait photo column ──────────────────────────────────────── */
.person-modal-photo-col {
  width: 320px;
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
  background: #111;
  border-radius: 20px 0 0 20px;
}
/* Blurred background — fills the column regardless of photo ratio */
.person-modal-photo-bg {
  position: absolute;
  inset: -12%;
  background-size: cover;
  background-position: center;
  filter: blur(20px) brightness(.6) saturate(.7);
}
/* Sharp photo on top, fully visible */
.person-modal-photo-col img {
  position: relative;
  z-index: 1;
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}
.person-modal-photo-col--placeholder {
  display: flex; align-items: center; justify-content: center;
  background: var(--color-bg-light); color: var(--color-border);
}

/* ── Right content column ───────────────────────────────────────── */
.person-modal-content {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  overflow-y: auto;
  scrollbar-width: thin;
}
.person-modal-identity {
  padding: 36px 24px 18px;
  padding-right: 52px; /* space for close btn */
  border-bottom: 1px solid var(--color-border);
  flex-shrink: 0;
}
.person-modal-name {
  font-size: 1.2rem; font-weight: 800;
  color: var(--color-teal-dark);
  margin: 0 0 5px; line-height: 1.2;
}
.person-modal-role {
  font-size: .85rem; color: var(--color-text-muted);
  margin: 0; line-height: 1.4;
}

/* ── Bio body ───────────────────────────────────────────────────── */
.person-modal-body {
  padding: 20px 24px 28px;
  flex: 1;
  font-size: .9375rem; line-height: 1.72; color: var(--color-text);
}
.person-modal-body h2, .person-modal-body h3 { color: var(--color-teal-dark); margin: 16px 0 6px; }
.person-modal-body p  { margin: 0 0 12px; }
.person-modal-body ul,
.person-modal-body ol { padding-left: 20px; margin-bottom: 12px; }
.person-modal-body a  { color: var(--color-teal); }
.person-modal-body a:hover { color: var(--color-teal-dark); }

/* ── Tablet — zúžit foto sloupec ────────────────────────────────── */
@media (max-width: 700px) and (min-width: 521px) {
  .person-modal-photo-col { width: 220px; }
  .person-modal { max-width: calc(100vw - 32px); }
}

/* ── Mobile — vertikální stack ──────────────────────────────────── */
@media (max-width: 520px) {
  .person-modal { flex-direction: column; max-height: 95svh; max-width: calc(100vw - 32px); }
  .person-modal-photo-col { width: 100%; height: 200px; border-radius: 20px 20px 0 0; }
  .person-modal-photo-bg { inset: -8%; }
  .person-modal-content { overflow-y: auto; }
  .person-modal-identity { padding-top: 20px; padding-right: 52px; }
}
</style>

<script>
(function () {

	// ── Team filter ──────────────────────────────────────────────
	var input  = document.getElementById('kontakt-team-filter');
	var clear  = document.getElementById('kontakt-team-clear');
	var noRes  = document.getElementById('kontakt-team-noresults');
	var groups = document.querySelectorAll('[data-group]');

	if (input) {
		function filterTeam(q) {
			q = q.trim().toLowerCase();
			clear.hidden = q === '';
			var totalVisible = 0;
			groups.forEach(function(group) {
				var visible = 0;
				group.querySelectorAll('.kontakt-person-card').forEach(function(card) {
					var match = q === '' || (card.dataset.name || '').includes(q);
					card.hidden = !match;
					if (match) visible++;
				});
				group.hidden = visible === 0;
				totalVisible += visible;
			});
			noRes.hidden = totalVisible > 0 || q === '';
		}
		input.addEventListener('input', function() { filterTeam(this.value); });
		clear.addEventListener('click', function() { input.value = ''; input.focus(); filterTeam(''); });
	}

	// ── Person bio popup ─────────────────────────────────────────
	var activeModal = null;

	function openPersonModal(id) {
		var overlay = document.getElementById('person-modal-' + id);
		if (!overlay) return;
		if (activeModal) closePersonModal(activeModal, false);
		activeModal = overlay;
		overlay.style.display = 'flex';
		requestAnimationFrame(function() {
			overlay.classList.add('is-open');
		});
		document.body.style.overflow = 'hidden';
		var closeBtn = overlay.querySelector('.person-modal-close');
		if (closeBtn) setTimeout(function(){ closeBtn.focus(); }, 60);
	}

	function closePersonModal(overlay, restoreFocus) {
		if (!overlay) return;
		overlay.classList.remove('is-open');
		document.body.style.overflow = '';
		activeModal = null;
		setTimeout(function() {
			if (!overlay.classList.contains('is-open')) {
				overlay.style.display = 'none';
			}
		}, 230);
	}

	// Open on card button click
	document.querySelectorAll('.kontakt-person-bio-btn').forEach(function(btn) {
		btn.addEventListener('click', function() { openPersonModal(btn.dataset.person); });
	});

	// Close on overlay backdrop click
	document.querySelectorAll('.person-modal-overlay').forEach(function(overlay) {
		overlay.addEventListener('click', function(e) {
			if (e.target === overlay) closePersonModal(overlay, true);
		});
		overlay.querySelector('.person-modal-close').addEventListener('click', function() {
			closePersonModal(overlay, true);
		});
	});

	// Close on Escape
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape' && activeModal) closePersonModal(activeModal, true);
	});

})();
</script>

<?php get_footer(); ?>
