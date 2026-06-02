<?php
/**
 * Kalendář akcí — archive template for tribe_events CPT.
 * URL: /kalendar/
 *
 * Calendar is generated entirely client-side — no page reload on month navigation.
 * All published events are passed as JSON to JS, keyed by YYYYMMDD.
 */

$archive_url = get_post_type_archive_link( 'tribe_events' ) ?: home_url( '/kalendar/' );

// ── Helpers ───────────────────────────────────────────────────────
$cs_m = [ '', 'led', 'úno', 'bře', 'dub', 'kvě', 'čvn', 'čvc', 'srp', 'zář', 'říj', 'lis', 'pro' ];

if ( ! function_exists( '_ev_color' ) ) {
	function _ev_color( string $c ): string {
		return in_array( $c, [ 'gold', 'red', 'teal', 'light' ], true ) ? $c : 'teal';
	}
}

// ── All events — sorted by start date ────────────────────────────
$list_q = new WP_Query( [
	'post_type'      => 'tribe_events',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'meta_key'       => '_EventStartDate',
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
] );

$list_events = [];
if ( $list_q->have_posts() ) {
	while ( $list_q->have_posts() ) {
		$list_q->the_post();
		$sid = get_the_ID();
		$vid = (int) get_post_meta( $sid, '_EventVenueID', true );
		$list_events[] = [
			'id'       => $sid,
			'title'    => get_the_title(),
			'url'      => get_permalink(),
			'start'    => get_post_meta( $sid, '_EventStartDate', true ),
			'end'      => get_post_meta( $sid, '_EventEndDate',   true ),
			'all_day'  => get_post_meta( $sid, '_EventAllDay',    true ),
			'color'    => get_post_meta( $sid, '_EventColor',     true ) ?: 'teal',
			'venue_id' => $vid,
			'venue'    => $vid ? get_the_title( $vid ) : '',
			'address'  => $vid ? trim( implode( ', ', array_filter( [
				get_post_meta( $vid, '_VenueAddress', true ),
				trim( get_post_meta( $vid, '_VenueZip', true ) . ' ' . get_post_meta( $vid, '_VenueCity', true ) ),
			] ) ) ) : '',
			'excerpt'  => get_the_excerpt(),
			'ext_url'  => get_post_meta( $sid, '_EventURL', true ),
		];
	}
	wp_reset_postdata();
}

// ── Month pills from real event dates ─────────────────────────────
$pill_months = [];
foreach ( $list_events as $_ev ) {
	if ( $_ev['start'] ) {
		$pill_months[ date( 'Ym', strtotime( $_ev['start'] ) ) ] = true;
	}
}
ksort( $pill_months );
$month_pills = [];
foreach ( $pill_months as $key => $_ ) {
	$ts = mktime( 0, 0, 0, (int) substr( $key, 4, 2 ), 1, (int) substr( $key, 0, 4 ) );
	$month_pills[] = [ 'key' => $key, 'label' => date_i18n( 'F Y', $ts ) ];
}

// ── Build JS events map keyed by YYYYMMDD ─────────────────────────
$ev_js_map = [];
foreach ( $list_events as $ev ) {
	if ( ! $ev['start'] ) continue;
	$ts  = strtotime( $ev['start'] );
	$key = date( 'Ymd', $ts );
	$ev_js_map[ $key ][] = [
		'title'   => $ev['title'],
		'url'     => $ev['url'],
		'day'     => (int) date( 'j', $ts ),
		'month'   => $cs_m[ (int) date( 'n', $ts ) ],
		'color'   => _ev_color( $ev['color'] ),
		'time'    => $ev['all_day'] === 'yes'
			? 'Celodenní akce'
			: date_i18n( 'j. F Y · G:i', $ts ),
		'venue'   => $ev['venue'],
		'address' => $ev['address'],
		'excerpt' => $ev['excerpt'],
		'ext_url' => $ev['ext_url'],
	];
}

get_header();
?>

<style>
/* ── List event card ────────────────────────────────────────────── */
.ev-card {
  display: flex; align-items: center; gap: 20px;
  padding: 18px 22px;
  background: var(--color-white);
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-soft);
  cursor: pointer; text-align: left; width: 100%;
  font-family: var(--font-sans); color: inherit;
  transition: transform 200ms var(--ease-expo), box-shadow 200ms var(--ease-expo), border-color 200ms ease;
}
.ev-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-card); border-color: var(--color-teal); }
.ev-badge {
  flex-shrink: 0; width: 60px; height: 60px;
  border-radius: var(--radius-md);
  display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1px;
}
.ev-badge-day   { font-size: 1.5rem; font-weight: 800; line-height: 1; font-family: var(--font-display, var(--font-sans)); }
.ev-badge-month { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; }
.ev-badge--gold  { background: var(--color-accent-gold); color: var(--color-text); }
.ev-badge--red   { background: var(--color-accent-red);  color: #fff; }
.ev-badge--teal  { background: var(--color-teal-dark);   color: #fff; }
.ev-badge--light { background: var(--color-teal-light);  color: #fff; }
.ev-content { flex: 1; min-width: 0; }
.ev-title { font-size: 1rem; font-weight: 700; color: var(--color-teal-dark); margin: 0 0 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: color 150ms ease; }
.ev-card:hover .ev-title { color: var(--color-teal); }
.ev-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 18px; font-size: .82rem; color: var(--color-text-muted); }
.ev-meta-chip { display: inline-flex; align-items: center; gap: 5px; }
.ev-arrow { flex-shrink: 0; color: var(--color-text-muted); transition: transform 220ms var(--ease-expo), color 160ms ease; }
.ev-card:hover .ev-arrow { transform: translateX(5px); color: var(--color-teal-dark); }
.ev-list { display: flex; flex-direction: column; gap: 12px; }
.ev-card[hidden] { display: none; }
.ev-empty { text-align: center; padding: 72px 24px; color: var(--color-text-muted); }
.ev-empty svg { color: var(--color-border); margin-bottom: 16px; }
.ev-empty h2  { font-size: 1.2rem; font-weight: 700; color: var(--color-text); margin: 0 0 6px; }
.ev-empty p   { margin: 0; }
@media (max-width: 480px) {
  .ev-card { gap: 14px; padding: 14px 16px; }
  .ev-badge { width: 50px; height: 50px; }
  .ev-badge-day { font-size: 1.25rem; }
  .ev-arrow { display: none; }
}

/* ── Month nav arrows ───────────────────────────────────────────── */
.ev-nav-btn {
  width: 44px; height: 44px; border-radius: 50%;
  background: var(--color-white);
  border: 1.5px solid var(--color-border);
  display: inline-flex; align-items: center; justify-content: center;
  color: var(--color-teal-dark); flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(39,92,170,.10);
  cursor: pointer; font-size: 0;
  transition: background 200ms ease, border-color 200ms ease, color 200ms ease,
              box-shadow 200ms ease, transform 180ms ease;
}
.ev-nav-btn:hover {
  background: var(--color-teal-dark); border-color: var(--color-teal-dark); color: #fff;
  box-shadow: 0 6px 18px rgba(39,92,170,.28); transform: scale(1.1);
}
.ev-nav-btn:active { transform: scale(.94); }

/* ── Calendar chip as button ────────────────────────────────────── */
button.events-cal-chip {
  border: none; width: 100%; text-align: left;
  font-family: var(--font-sans); cursor: pointer;
}
.events-cal-chip--gold  { background: var(--color-accent-gold) !important; color: var(--color-text) !important; }
.events-cal-chip--red   { background: var(--color-accent-red)  !important; color: #fff !important; }
.events-cal-chip--teal  { background: var(--color-teal-dark)   !important; color: #fff !important; }
.events-cal-chip--light { background: var(--color-teal-light)  !important; color: #fff !important; }

/* ── View toggle buttons ────────────────────────────────────────── */
button.events-view-btn {
  border: none; border-right: 1.5px solid var(--color-border);
  cursor: pointer; font-family: var(--font-sans);
}
button.events-view-btn:last-child { border-right: none; }

/* ── Month pills (galerie-year-pill used via class) ─────────────── */
.ev-month-bar { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 32px; }
button.galerie-year-pill { border: none; cursor: pointer; font-family: var(--font-sans); }

/* ── Modal overlay — lighter ────────────────────────────────────── */
.ev-modal-overlay {
  position: fixed; inset: 0; z-index: 9999;
  background: rgba(10, 25, 40, 0.28);
  backdrop-filter: blur(3px); -webkit-backdrop-filter: blur(3px);
  display: flex; align-items: center; justify-content: center; padding: 16px;
  opacity: 0; pointer-events: none;
  transition: opacity 220ms ease;
}
.ev-modal-overlay.is-open { opacity: 1; pointer-events: auto; }

/* ── Modal card ─────────────────────────────────────────────────── */
.ev-modal {
  background: var(--color-white); border-radius: 20px;
  max-width: 520px; width: 100%; max-height: 92svh; overflow-y: auto;
  box-shadow: 0 20px 60px rgba(10,25,40,.22), 0 0 0 1px rgba(10,25,40,.06);
  transform: scale(.93) translateY(24px);
  transition: transform 300ms cubic-bezier(.22,1,.36,1);
  scrollbar-width: thin;
}
.ev-modal-overlay.is-open .ev-modal { transform: scale(1) translateY(0); }
.ev-modal-head {
  padding: 24px 24px 20px; border-radius: 20px 20px 0 0;
  display: flex; align-items: flex-start; gap: 16px; position: relative;
}
.ev-modal-head--gold  { background: var(--color-accent-gold); }
.ev-modal-head--red   { background: var(--color-accent-red); }
.ev-modal-head--teal  { background: var(--color-teal-dark); }
.ev-modal-head--light { background: var(--color-teal-light); }
.ev-modal-date {
  flex-shrink: 0; width: 54px; height: 54px;
  background: rgba(255,255,255,.18); border-radius: var(--radius-md);
  display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1px; color: #fff;
}
.ev-modal-head--gold .ev-modal-date { color: var(--color-text); }
.ev-modal-date-day   { font-size: 1.375rem; font-weight: 800; line-height: 1; }
.ev-modal-date-month { font-size: .6rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; }
.ev-modal-title-wrap { flex: 1; padding-top: 4px; padding-right: 32px; }
.ev-modal-title { font-size: 1.1rem; font-weight: 800; line-height: 1.25; color: #fff; margin: 0; }
.ev-modal-head--gold .ev-modal-title { color: var(--color-text); }
.ev-modal-close {
  position: absolute; top: 14px; right: 14px;
  width: 30px; height: 30px; border: none; border-radius: 50%;
  background: rgba(255,255,255,.18); color: rgba(255,255,255,.85);
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  transition: background 150ms ease, color 150ms ease; font-size: 0;
}
.ev-modal-close:hover { background: rgba(255,255,255,.35); color: #fff; }
.ev-modal-head--gold .ev-modal-close { color: rgba(0,0,0,.45); }
.ev-modal-head--gold .ev-modal-close:hover { background: rgba(0,0,0,.1); color: rgba(0,0,0,.8); }
.ev-modal-body { padding: 22px 24px 28px; }
.ev-modal-desc { font-size: .9375rem; line-height: 1.65; color: var(--color-text-muted); margin: 0 0 18px; }
.ev-modal-info { display: flex; flex-direction: column; gap: 11px; margin-bottom: 22px; }
.ev-modal-row { display: flex; align-items: flex-start; gap: 10px; font-size: .875rem; color: var(--color-text-muted); }
.ev-modal-row svg { flex-shrink: 0; margin-top: 1px; color: var(--color-teal); }
.ev-modal-row strong { display: block; font-size: .7rem; text-transform: uppercase; letter-spacing: .06em; color: var(--color-text-muted); margin-bottom: 1px; }
.ev-modal-row span  { color: var(--color-text); }
.ev-modal-actions { display: flex; gap: 10px; }
.ev-modal-btn-go {
  flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
  padding: 11px 18px; background: var(--color-teal-dark); color: #fff;
  font-weight: 700; font-size: .875rem; border-radius: var(--radius-btn);
  text-decoration: none; transition: background 150ms ease;
}
.ev-modal-btn-go:hover { background: var(--color-teal); color: #fff; }
.ev-modal-btn-close {
  display: inline-flex; align-items: center; justify-content: center;
  padding: 11px 16px; background: none; border: 1.5px solid var(--color-border);
  color: var(--color-text-muted); font-weight: 600; font-size: .875rem;
  border-radius: var(--radius-btn); cursor: pointer; font-family: var(--font-sans);
  transition: border-color 150ms ease, color 150ms ease;
}
.ev-modal-btn-close:hover { border-color: var(--color-teal-dark); color: var(--color-teal-dark); }
</style>

<main id="main-content" role="main">

<!-- ══ Hero ════════════════════════════════════════════════════════ -->
<section class="events-page-hero">
	<div class="container">
		<nav class="events-page-hero-breadcrumbs" aria-label="<?php esc_attr_e( 'Navigace', 'cpnrp' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
			<span class="sep" aria-hidden="true">/</span>
			<span class="current"><?php esc_html_e( 'Kalendář akcí', 'cpnrp' ); ?></span>
		</nav>
		<div class="events-page-hero-inner">
			<div>
				<h1><?php esc_html_e( 'Kalendář akcí', 'cpnrp' ); ?></h1>
				<p><?php esc_html_e( 'Přijďte se potkat, zapojit a podpořit naši práci. Pořádáme akce pro rodiny, veřejnost i odborníky.', 'cpnrp' ); ?></p>
			</div>
			<nav class="events-view-toggle" aria-label="<?php esc_attr_e( 'Pohled', 'cpnrp' ); ?>">
				<button type="button" class="events-view-btn is-active" data-view="calendar">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
						<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
					</svg>
					<?php esc_html_e( 'Kalendář', 'cpnrp' ); ?>
				</button>
				<button type="button" class="events-view-btn" data-view="list">
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
						<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
						<line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
					</svg>
					<?php esc_html_e( 'Seznam', 'cpnrp' ); ?>
				</button>
			</nav>
		</div>
	</div>
</section>

<!-- ══ LIST view ════════════════════════════════════════════════════ -->
<section class="events-grid-section bg-logo-pattern events-view-panel" data-panel="list">
	<div class="container">

		<?php if ( $month_pills ) : ?>
		<nav class="ev-month-bar" aria-label="<?php esc_attr_e( 'Filtr podle měsíce', 'cpnrp' ); ?>">
			<button type="button" class="galerie-year-pill is-active" data-month="all">
				<?php esc_html_e( 'Vše', 'cpnrp' ); ?>
			</button>
			<?php foreach ( $month_pills as $pill ) : ?>
			<button type="button" class="galerie-year-pill" data-month="<?php echo esc_attr( $pill['key'] ); ?>">
				<?php echo esc_html( $pill['label'] ); ?>
			</button>
			<?php endforeach; ?>
		</nav>
		<?php endif; ?>

		<?php if ( $list_events ) : ?>
		<div class="ev-list" id="ev-list">
			<?php foreach ( $list_events as $ev ) :
				$ts      = strtotime( $ev['start'] );
				$d_day   = date_i18n( 'j', $ts );
				$d_short = $cs_m[ (int) date( 'n', $ts ) ];
				$d_key   = date( 'Ym', $ts );
				$color   = _ev_color( $ev['color'] );
				$time_str = $ev['all_day'] === 'yes'
					? __( 'Celodenní', 'cpnrp' )
					: date_i18n( 'j. F Y · G:i', $ts );
				$modal_data = json_encode( [
					'title'   => $ev['title'], 'url'     => $ev['url'],
					'day'     => $d_day,       'month'   => $d_short,
					'color'   => $color,       'time'    => $time_str,
					'venue'   => $ev['venue'],  'address' => $ev['address'],
					'excerpt' => $ev['excerpt'], 'ext_url' => $ev['ext_url'],
				], JSON_UNESCAPED_UNICODE );
			?>
			<button type="button" class="ev-card"
			        data-month="<?php echo esc_attr( $d_key ); ?>"
			        data-ev="<?php echo esc_attr( $modal_data ); ?>">
				<div class="ev-badge ev-badge--<?php echo esc_attr( $color ); ?>">
					<span class="ev-badge-day"><?php echo esc_html( $d_day ); ?></span>
					<span class="ev-badge-month"><?php echo esc_html( $d_short ); ?></span>
				</div>
				<div class="ev-content">
					<div class="ev-title"><?php echo esc_html( $ev['title'] ); ?></div>
					<div class="ev-meta">
						<span class="ev-meta-chip">
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
								<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
							</svg>
							<?php echo esc_html( date_i18n( 'j. F Y', $ts ) ); ?>
							<?php if ( $ev['all_day'] !== 'yes' ) echo esc_html( ' · ' . date_i18n( 'G:i', $ts ) ); ?>
						</span>
						<?php if ( $ev['venue'] ) : ?>
						<span class="ev-meta-chip">
							<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
								<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
							</svg>
							<?php echo esc_html( $ev['venue'] ); ?>
						</span>
						<?php endif; ?>
					</div>
				</div>
				<svg class="ev-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
				</svg>
			</button>
			<?php endforeach; ?>
		</div>

		<div class="ev-empty" id="ev-list-empty" hidden>
			<svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
				<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
			</svg>
			<h2><?php esc_html_e( 'Žádné akce', 'cpnrp' ); ?></h2>
			<p><?php esc_html_e( 'V tomto měsíci nemáme žádné naplánované akce.', 'cpnrp' ); ?></p>
		</div>

		<nav id="ev-pagination" class="pribehy-pagination" style="margin-top:32px" aria-label="Stránkování akcí"></nav>
		<?php else : ?>
		<div class="ev-empty">
			<svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
				<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
			</svg>
			<h2><?php esc_html_e( 'Žádné nadcházející akce', 'cpnrp' ); ?></h2>
			<p><?php esc_html_e( 'Sledujte nás na sociálních sítích.', 'cpnrp' ); ?></p>
		</div>
		<?php endif; ?>

	</div>
</section>

<!-- ══ CALENDAR view — JS-generated, no page reload ════════════════ -->
<section class="events-grid-section bg-logo-pattern events-view-panel" data-panel="calendar" hidden>
	<div class="container">

		<div class="events-month-nav">
			<button type="button" class="ev-nav-btn" id="ev-cal-prev" aria-label="<?php esc_attr_e( 'Předchozí měsíc', 'cpnrp' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
			</button>
			<span class="events-month-current" id="ev-cal-month-label"></span>
			<button type="button" class="ev-nav-btn" id="ev-cal-next" aria-label="<?php esc_attr_e( 'Následující měsíc', 'cpnrp' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
			</button>
		</div>

		<div class="events-calendar-grid" id="ev-cal-grid" role="grid"></div>
		<div id="ev-cal-list"></div>

	</div>
</section>

</main>

<!-- ══ Modal ════════════════════════════════════════════════════════ -->
<div class="ev-modal-overlay" id="ev-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="ev-modal-title-el">
	<div class="ev-modal" id="ev-modal">
		<div class="ev-modal-head ev-modal-head--teal" id="ev-modal-head">
			<div class="ev-modal-date">
				<span class="ev-modal-date-day"   id="ev-modal-date-day"></span>
				<span class="ev-modal-date-month"  id="ev-modal-date-month"></span>
			</div>
			<div class="ev-modal-title-wrap">
				<h2 class="ev-modal-title" id="ev-modal-title-el"></h2>
			</div>
			<button type="button" class="ev-modal-close" id="ev-modal-close" aria-label="<?php esc_attr_e( 'Zavřít', 'cpnrp' ); ?>">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
				</svg>
			</button>
		</div>
		<div class="ev-modal-body">
			<p class="ev-modal-desc" id="ev-modal-desc"></p>
			<div class="ev-modal-info" id="ev-modal-info"></div>
			<div class="ev-modal-actions">
				<a href="#" class="ev-modal-btn-go" id="ev-modal-btn-go">
					<?php esc_html_e( 'Zobrazit celý detail', 'cpnrp' ); ?>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
						<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
					</svg>
				</a>
				<button type="button" class="ev-modal-btn-close" id="ev-modal-btn-close">
					<?php esc_html_e( 'Zavřít', 'cpnrp' ); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<script>
(function () {
'use strict';

/* ── Data from PHP ──────────────────────────────────────────────── */
var evMap = <?php echo wp_json_encode( $ev_js_map ); ?>;
// evMap keys: "YYYYMMDD" → array of event objects

/* ── Helpers ────────────────────────────────────────────────────── */
function esc(s) {
	return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function pad2(n) { return n < 10 ? '0' + n : '' + n; }
function ymd(y, m, d) { return '' + y + pad2(m) + pad2(d); }

/* ── Calendar state + lookup arrays — must be before applyView ─── */
var today  = new Date();
var calY   = today.getFullYear();
var calM   = today.getMonth() + 1; // 1-indexed
var csDays  = ['Po','Út','St','Čt','Pá','So','Ne'];
var csShort = ['','led','úno','bře','dub','kvě','čvn','čvc','srp','zář','říj','lis','pro'];

/* ── View toggle ────────────────────────────────────────────────── */
var STORE    = 'cpnrp_events_view';
var panels   = document.querySelectorAll('.events-view-panel[data-panel]');
var viewBtns = document.querySelectorAll('button.events-view-btn[data-view]');

function applyView(v) {
	panels.forEach(function(p){ p.hidden = p.dataset.panel !== v; });
	viewBtns.forEach(function(b){ b.classList.toggle('is-active', b.dataset.view === v); });
	try { localStorage.setItem(STORE, v); } catch(e){}
	if (v === 'calendar') buildCalendar();
}
var saved; try { saved = localStorage.getItem(STORE); } catch(e){}
applyView( (saved === 'calendar' || saved === 'list') ? saved : 'calendar' );
viewBtns.forEach(function(b){
	b.addEventListener('click', function(){ applyView(b.dataset.view); });
});

/* ── Month filter + pagination (list view) ──────────────────────── */
var monthBtns   = document.querySelectorAll('.ev-month-bar .galerie-year-pill');
var evCards     = Array.from(document.querySelectorAll('#ev-list .ev-card'));
var listEmpty   = document.getElementById('ev-list-empty');
var evPaginator = document.getElementById('ev-pagination');
var PER_PAGE    = 10;
var curMonth    = 'all';
var curPage     = 1;

function getFiltered() {
	return evCards.filter(function(c) {
		return curMonth === 'all' || c.dataset.month === curMonth;
	});
}

function renderList() {
	var filtered   = getFiltered();
	var totalPages = Math.max(1, Math.ceil(filtered.length / PER_PAGE));
	if (curPage > totalPages) curPage = totalPages;
	var start = (curPage - 1) * PER_PAGE;
	var end   = start + PER_PAGE;

	evCards.forEach(function(c) { c.hidden = true; });
	filtered.slice(start, end).forEach(function(c) { c.hidden = false; });
	if (listEmpty) listEmpty.hidden = filtered.length > 0;

	renderPaginator(totalPages);
}

function renderPaginator(totalPages) {
	if (!evPaginator) return;
	if (totalPages <= 1) { evPaginator.innerHTML = ''; return; }

	var html = '<div class="nav-links">';

	if (curPage > 1) {
		html += '<button type="button" class="page-numbers" data-page="' + (curPage - 1) + '">&larr; Předchozí</button>';
	}

	var from = Math.max(1, curPage - 2);
	var to   = Math.min(totalPages, curPage + 2);
	if (from > 1) html += '<span class="page-numbers dots">&hellip;</span>';
	for (var i = from; i <= to; i++) {
		if (i === curPage) {
			html += '<span class="page-numbers current">' + i + '</span>';
		} else {
			html += '<button type="button" class="page-numbers" data-page="' + i + '">' + i + '</button>';
		}
	}
	if (to < totalPages) html += '<span class="page-numbers dots">&hellip;</span>';

	if (curPage < totalPages) {
		html += '<button type="button" class="page-numbers" data-page="' + (curPage + 1) + '">Další &rarr;</button>';
	}

	html += '</div>';
	evPaginator.innerHTML = html;

	evPaginator.querySelectorAll('button[data-page]').forEach(function(btn) {
		btn.addEventListener('click', function() {
			curPage = parseInt(btn.dataset.page, 10);
			renderList();
			var panel = document.querySelector('[data-panel="list"]');
			if (panel) panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
		});
	});
}

function filterMonth(key) {
	curMonth = key;
	curPage  = 1;
	monthBtns.forEach(function(b) { b.classList.toggle('is-active', b.dataset.month === key); });
	renderList();
}

monthBtns.forEach(function(b) {
	b.addEventListener('click', function() { filterMonth(b.dataset.month); });
});

renderList();

/* ── Calendar ───────────────────────────────────────────────────── */

function monthLabel(y, m) {
	try {
		var s = new Intl.DateTimeFormat('cs', { month: 'long', year: 'numeric' })
			.format(new Date(y, m - 1, 1));
		return s.charAt(0).toUpperCase() + s.slice(1);
	} catch(e) {
		return csShort[m] + ' ' + y;
	}
}

function buildCalendar() {
	var y = calY, m = calM;

	// Update label
	var lbl = document.getElementById('ev-cal-month-label');
	if (lbl) lbl.textContent = monthLabel(y, m);

	var todayY = today.getFullYear(), todayM = today.getMonth() + 1, todayD = today.getDate();
	var daysInMonth = new Date(y, m, 0).getDate();
	var firstDow    = new Date(y, m - 1, 1).getDay(); // 0=Sun
	var startOffset = firstDow === 0 ? 6 : firstDow - 1; // Mo-first

	// Grid
	var html = '';
	csDays.forEach(function(d){ html += '<div class="events-cal-header">' + d + '</div>'; });
	for (var i = 0; i < startOffset; i++) {
		html += '<div class="events-cal-day is-empty" aria-hidden="true"></div>';
	}
	for (var d = 1; d <= daysInMonth; d++) {
		var isToday = (y === todayY && m === todayM && d === todayD);
		var key     = ymd(y, m, d);
		var dayEvs  = evMap[key] || [];
		var cls     = 'events-cal-day' + (isToday ? ' is-today' : '') + (dayEvs.length ? ' has-events' : '');
		html += '<div class="' + cls + '">';
		html += '<span class="events-cal-day-num' + (isToday ? ' is-today' : '') + '">' + d + '</span>';
		dayEvs.slice(0, 3).forEach(function(ev) {
			var chipCls = 'events-cal-chip events-cal-chip--' + (ev.color || 'teal');
			html += '<button type="button" class="' + chipCls + '" title="' + esc(ev.title) + '" data-ev="' + esc(JSON.stringify(ev)) + '">' + esc(ev.title) + '</button>';
		});
		if (dayEvs.length > 3) {
			html += '<span class="events-cal-more">+' + (dayEvs.length - 3) + ' další</span>';
		}
		html += '</div>';
	}
	var grid = document.getElementById('ev-cal-grid');
	if (grid) grid.innerHTML = html;

	// Month list
	var monthEvs = [];
	for (var d = 1; d <= daysInMonth; d++) {
		(evMap[ ymd(y, m, d) ] || []).forEach(function(ev){ monthEvs.push(ev); });
	}
	var listEl = document.getElementById('ev-cal-list');
	if (!listEl) return;
	if (!monthEvs.length) {
		listEl.innerHTML = '<p class="events-month-empty">V tomto měsíci nejsou žádné akce.</p>';
		return;
	}
	var lh = '<div class="events-month-list"><h2 class="events-month-list-heading">Akce — ' + esc(monthLabel(y, m)) + '</h2>';
	monthEvs.forEach(function(ev) {
		lh += '<button type="button" class="events-month-list-item" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;font-family:var(--font-sans);" data-ev="' + esc(JSON.stringify(ev)) + '">';
		lh += '<span class="events-month-list-date"><strong>' + ev.day + '</strong><small>' + esc(ev.month) + '</small></span>';
		lh += '<span class="events-month-list-info"><span class="events-month-list-title">' + esc(ev.title) + '</span>';
		if (ev.venue) lh += '<span class="events-month-list-venue">' + esc(ev.venue) + '</span>';
		lh += '</span></button>';
	});
	lh += '</div>';
	listEl.innerHTML = lh;
}

document.getElementById('ev-cal-prev').addEventListener('click', function(){
	calM--; if (calM < 1) { calM = 12; calY--; } buildCalendar();
});
document.getElementById('ev-cal-next').addEventListener('click', function(){
	calM++; if (calM > 12) { calM = 1; calY++; } buildCalendar();
});

/* ── Modal ──────────────────────────────────────────────────────── */
var overlay  = document.getElementById('ev-modal-overlay');
var head     = document.getElementById('ev-modal-head');
var dateDay  = document.getElementById('ev-modal-date-day');
var dateMon  = document.getElementById('ev-modal-date-month');
var titleEl  = document.getElementById('ev-modal-title-el');
var descEl   = document.getElementById('ev-modal-desc');
var infoEl   = document.getElementById('ev-modal-info');
var btnGo    = document.getElementById('ev-modal-btn-go');
var closeBtns = [document.getElementById('ev-modal-close'), document.getElementById('ev-modal-btn-close')];

function icon(path) {
	return '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">' + path + '</svg>';
}
var IC = {
	clock : icon('<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'),
	pin   : icon('<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>'),
	link  : icon('<path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/>'),
};

function openModal(ev) {
	head.className = 'ev-modal-head ev-modal-head--' + (ev.color || 'teal');
	dateDay.textContent = ev.day   || '';
	dateMon.textContent = ev.month || '';
	titleEl.textContent = ev.title || '';
	descEl.textContent  = ev.excerpt || '';
	descEl.hidden = !ev.excerpt;

	var rows = '';
	if (ev.time)    rows += '<div class="ev-modal-row">' + IC.clock + '<div><strong>Datum a čas</strong><span>' + esc(ev.time) + '</span></div></div>';
	if (ev.venue)   rows += '<div class="ev-modal-row">' + IC.pin   + '<div><strong>Místo</strong><span>' + esc(ev.venue) + (ev.address ? '<br><span style="font-size:.8rem;color:var(--color-text-muted)">' + esc(ev.address) + '</span>' : '') + '</span></div></div>';
	if (ev.ext_url) rows += '<div class="ev-modal-row">' + IC.link  + '<div><strong>Registrace</strong><a href="' + esc(ev.ext_url) + '" target="_blank" rel="noopener" style="color:var(--color-teal)">Přihlásit se na akci</a></div></div>';
	infoEl.innerHTML = rows;
	infoEl.hidden = !rows;

	btnGo.href = ev.url || '#';
	overlay.classList.add('is-open');
	document.body.style.overflow = 'hidden';
	closeBtns[0] && setTimeout(function(){ closeBtns[0].focus(); }, 50);
}
function closeModal() {
	overlay.classList.remove('is-open');
	document.body.style.overflow = '';
}

document.addEventListener('click', function(e) {
	var card = e.target.closest('[data-ev]');
	if (!card) return;
	try { openModal(JSON.parse(card.dataset.ev)); } catch(err){}
});
closeBtns.forEach(function(b){ b && b.addEventListener('click', closeModal); });
overlay && overlay.addEventListener('click', function(e){ if (e.target === overlay) closeModal(); });
document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeModal(); });

})();
</script>

<?php get_footer(); ?>
