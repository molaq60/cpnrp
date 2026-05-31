<?php
/**
 * Single event page — tribe_events CPT.
 */

get_header();

$cs_months_short = [ '', 'led', 'úno', 'bře', 'dub', 'kvě', 'čvn', 'čvc', 'srp', 'zář', 'říj', 'lis', 'pro' ];

while ( have_posts() ) :
	the_post();

	$start    = get_post_meta( get_the_ID(), '_EventStartDate', true );
	$end      = get_post_meta( get_the_ID(), '_EventEndDate',   true );
	$all_day  = get_post_meta( get_the_ID(), '_EventAllDay',    true );
	$venue_id = (int) get_post_meta( get_the_ID(), '_EventVenueID', true );
	$ext_url  = get_post_meta( get_the_ID(), '_EventURL',       true );

	$start_ts  = $start ? strtotime( $start ) : 0;
	$end_ts    = $end   ? strtotime( $end )   : 0;
	$has_thumb = has_post_thumbnail();

	$archive_url = get_post_type_archive_link( 'tribe_events' ) ?: home_url( '/kalendar/' );

	// Venue details
	$venue_name    = $venue_id ? get_the_title( $venue_id ) : '';
	$venue_address = $venue_id ? get_post_meta( $venue_id, '_VenueAddress', true ) : '';
	$venue_city    = $venue_id ? get_post_meta( $venue_id, '_VenueCity',    true ) : '';
	$venue_zip     = $venue_id ? get_post_meta( $venue_id, '_VenueZip',     true ) : '';
	$venue_line    = trim( implode( ', ', array_filter( [ $venue_address, $venue_zip . ' ' . $venue_city ] ) ) );

	// Date / time label
	if ( $start_ts ) {
		$d_day   = date_i18n( 'j', $start_ts );
		$d_month = $cs_months_short[ (int) date( 'n', $start_ts ) ];
		$d_year  = date( 'Y', $start_ts );
		$d_full  = date_i18n( 'j. F Y', $start_ts );
		if ( $all_day !== 'yes' ) {
			$time_from = date_i18n( 'G:i', $start_ts );
			$time_to   = $end_ts ? date_i18n( 'G:i', $end_ts ) : '';
			$time_str  = $time_to ? "$time_from – $time_to" : $time_from;
		} else {
			$time_str = __( 'Celodenní akce', 'cpnrp' );
		}
	}
?>

<main id="main-content" role="main">

<!-- ══ Hero ════════════════════════════════════════════════════════ -->
<section class="event-single-hero<?php echo $has_thumb ? ' has-thumb' : ''; ?>"
         <?php if ( $has_thumb ) : ?>
         style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'full' ) ); ?>')"
         <?php endif; ?>>
	<?php if ( $has_thumb ) : ?><div class="event-single-hero-overlay" aria-hidden="true"></div><?php endif; ?>

	<div class="container">
		<div class="event-single-hero-inner">
			<a href="<?php echo esc_url( $archive_url ); ?>" class="event-back-link">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<polyline points="15 18 9 12 15 6"/>
				</svg>
				<?php esc_html_e( 'Zpět na kalendář', 'cpnrp' ); ?>
			</a>

			<span class="pribehy-eyebrow"><?php esc_html_e( 'Akce', 'cpnrp' ); ?></span>
			<h1 class="pribehy-title"><?php the_title(); ?></h1>

			<?php if ( $start_ts ) : ?>
			<div class="event-single-date-badge">
				<span class="event-single-date-day"><?php echo esc_html( $d_day ); ?></span>
				<span class="event-single-date-month"><?php echo esc_html( $d_month ); ?></span>
				<span class="event-single-date-year"><?php echo esc_html( $d_year ); ?></span>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- ══ Body ════════════════════════════════════════════════════════ -->
<div class="event-single-body">
	<div class="container">
		<div class="event-single-layout">

			<!-- Main content -->
			<div class="event-single-content">
				<?php the_content(); ?>
			</div>

			<!-- Sidebar -->
			<aside class="event-single-sidebar">
				<div class="event-sidebar-card">
					<h2 class="event-sidebar-heading"><?php esc_html_e( 'Podrobnosti akce', 'cpnrp' ); ?></h2>

					<?php if ( $start_ts ) : ?>
					<div class="event-sidebar-item">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
							<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
						</svg>
						<div>
							<strong><?php esc_html_e( 'Datum', 'cpnrp' ); ?></strong>
							<span><?php echo esc_html( $d_full ); ?></span>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( isset( $time_str ) ) : ?>
					<div class="event-sidebar-item">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
							<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
						</svg>
						<div>
							<strong><?php esc_html_e( 'Čas', 'cpnrp' ); ?></strong>
							<span><?php echo esc_html( $time_str ); ?></span>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $venue_name ) : ?>
					<div class="event-sidebar-item">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
							<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
							<circle cx="12" cy="9" r="2.5"/>
						</svg>
						<div>
							<strong><?php esc_html_e( 'Místo', 'cpnrp' ); ?></strong>
							<span><?php echo esc_html( $venue_name ); ?></span>
							<?php if ( $venue_line ) : ?>
								<span><?php echo esc_html( $venue_line ); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $ext_url ) : ?>
					<div class="event-sidebar-item">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
							<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
							<path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
						</svg>
						<div>
							<strong><?php esc_html_e( 'Registrace', 'cpnrp' ); ?></strong>
							<a href="<?php echo esc_url( $ext_url ); ?>" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Přihlásit se na akci', 'cpnrp' ); ?>
							</a>
						</div>
					</div>
					<?php endif; ?>

				</div>
			</aside>

		</div>
	</div>
</div>

</main>

<?php
endwhile;

get_footer();
