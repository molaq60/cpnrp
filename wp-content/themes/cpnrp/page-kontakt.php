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
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/contact/contact1.jpg' ); ?>"
						alt="<?php esc_attr_e( 'Tým CPNRP', 'cpnrp' ); ?>"
						loading="eager"
						class="kontakt-hero-image"
					>
				</div>

			</div>

			<!-- Contact info row -->
			<dl class="kontakt-info-row">
				<div class="kontakt-info-item">
					<dt><?php esc_html_e( 'Adresa', 'cpnrp' ); ?></dt>
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
								'orderby'        => 'date',
								'order'          => 'ASC',
								'tax_query'      => [ [
									'taxonomy' => 'contact_group',
									'field'    => 'term_id',
									'terms'    => $group->term_id,
								] ],
							] );
							while ( $persons->have_posts() ) :
								$persons->the_post();
								$role  = get_post_meta( get_the_ID(), '_contact_role', true );
								$phone = get_post_meta( get_the_ID(), '_contact_phone', true );
							?>
							<div class="kontakt-person-card" data-name="<?php echo esc_attr( mb_strtolower( get_the_title() ) ); ?>">
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
							</div>
							<?php endwhile; wp_reset_postdata(); ?>
						</div>
					</div>

				<?php endforeach; ?>
			<?php endif; ?>

		</div>
	</section>

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
						<?php if ( $is_open !== null ) : ?>
							<span class="kontakt-office-badge kontakt-office-badge--<?php echo $is_open ? 'open' : 'closed'; ?>">
								<span class="kontakt-office-badge-dot"></span>
								<?php echo $is_open ? esc_html__( 'Otevřeno', 'cpnrp' ) : esc_html__( 'Zavřeno', 'cpnrp' ); ?>
							</span>
						<?php endif; ?>
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

</main>

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


})();
</script>

<?php get_footer(); ?>
