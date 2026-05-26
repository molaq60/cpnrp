<?php
/**
 * Single galerie album — photo grid with lightbox.
 */

$post_id     = get_the_ID();
$archive_url = get_post_type_archive_link( 'galerie' ) ?: home_url( '/galerie/' );

// Try _gallery_photos meta first
$photos_raw = get_post_meta( $post_id, '_gallery_photos', true );
$photo_ids  = json_decode( $photos_raw ?: '[]', true );

// Fall back to [gallery ids="..."] shortcode in post_content
if ( empty( $photo_ids ) ) {
	global $post;
	if ( preg_match( '/ids="([0-9,]+)"/', $post->post_content ?? '', $m ) ) {
		$photo_ids = array_filter( array_map( 'intval', explode( ',', $m[1] ) ) );
	}
}
if ( ! is_array( $photo_ids ) ) $photo_ids = [];

// Build photo data
$photos = [];
foreach ( $photo_ids as $att_id ) {
	$full  = wp_get_attachment_image_url( $att_id, 'full' );
	$thumb = wp_get_attachment_image_url( $att_id, 'medium_large' ) ?: $full;
	if ( ! $full ) continue;
	$photos[] = [
		'src'   => $full,
		'thumb' => $thumb,
		'alt'   => get_the_title( $att_id ) ?: get_the_title( $post_id ),
	];
}

// Year term
$year_terms  = wp_get_post_terms( $post_id, 'album' );
$year        = $year_terms && ! is_wp_error( $year_terms ) ? $year_terms[0]->name : '';
$year_url    = $year ? add_query_arg( 'rok', $year, $archive_url ) : $archive_url;
$photo_count = count( $photos );

get_header();
?>

<main id="main-content" role="main">

<!-- ══ Hero ═════════════════════════════════════════════════════ -->
<section class="events-page-hero">
	<div class="container">
		<nav class="events-page-hero-breadcrumbs" aria-label="<?php esc_attr_e( 'Navigace', 'cpnrp' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Úvod', 'cpnrp' ); ?></a>
			<span class="sep" aria-hidden="true">/</span>
			<a href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Fotogalerie', 'cpnrp' ); ?></a>
			<?php if ( $year ) : ?>
			<span class="sep" aria-hidden="true">/</span>
			<a href="<?php echo esc_url( $year_url ); ?>"><?php echo esc_html( $year ); ?></a>
			<?php endif; ?>
			<span class="sep" aria-hidden="true">/</span>
			<span class="current"><?php the_title(); ?></span>
		</nav>
		<h1><?php the_title(); ?></h1>
		<?php if ( $photo_count ) : ?>
		<p><?php echo $photo_count; ?> <?php echo $photo_count === 1 ? 'fotografie' : 'fotografií'; ?><?php if ( $year ) echo ' &middot; ' . esc_html( $year ); ?></p>
		<?php endif; ?>
	</div>
</section>

<!-- ══ Photo grid ════════════════════════════════════════════════ -->
<section class="gallery-photos-section">
	<div class="container">
		<?php if ( ! empty( $photos ) ) : ?>

		<div class="gallery-photos-grid" id="gallery-photos">
			<?php foreach ( $photos as $i => $photo ) : ?>
			<button type="button" class="gallery-photo-item" data-index="<?php echo $i; ?>"
			        aria-label="<?php echo esc_attr( $photo['alt'] ); ?>">
				<img src="<?php echo esc_url( $photo['thumb'] ); ?>" loading="lazy"
				     alt="<?php echo esc_attr( $photo['alt'] ); ?>">
			</button>
			<?php endforeach; ?>
		</div>

		<!-- Lightbox -->
		<div id="gallery-lb" class="gallery-lb" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Prohlížeč fotografií', 'cpnrp' ); ?>">
			<button class="gallery-lb-close" id="gallery-lb-close" type="button" aria-label="<?php esc_attr_e( 'Zavřít', 'cpnrp' ); ?>">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
				</svg>
			</button>
			<button class="gallery-lb-nav gallery-lb-prev" id="gallery-lb-prev" type="button" aria-label="<?php esc_attr_e( 'Předchozí fotografie', 'cpnrp' ); ?>">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<polyline points="15 18 9 12 15 6"/>
				</svg>
			</button>
			<div class="gallery-lb-stage">
				<img id="gallery-lb-img" src="" alt="" draggable="false">
			</div>
			<button class="gallery-lb-nav gallery-lb-next" id="gallery-lb-next" type="button" aria-label="<?php esc_attr_e( 'Další fotografie', 'cpnrp' ); ?>">
				<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<polyline points="9 18 15 12 9 6"/>
				</svg>
			</button>
			<div class="gallery-lb-counter" id="gallery-lb-counter"></div>
		</div>

		<script>
		(function(){
			var photos=<?php echo wp_json_encode($photos); ?>;
			var lb=document.getElementById('gallery-lb');
			var lbImg=document.getElementById('gallery-lb-img');
			var lbCounter=document.getElementById('gallery-lb-counter');
			var current=0;
			function show(idx){if(idx<0)idx=photos.length-1;if(idx>=photos.length)idx=0;current=idx;lbImg.style.opacity='0';setTimeout(function(){lbImg.src=photos[idx].src;lbImg.alt=photos[idx].alt;lbImg.style.opacity='1';},100);lbCounter.textContent=(idx+1)+' / '+photos.length;}
			function open(idx){lb.classList.add('is-open');document.body.style.overflow='hidden';show(idx);document.getElementById('gallery-lb-close').focus();}
			function close(){lb.classList.remove('is-open');document.body.style.overflow='';}
			document.querySelectorAll('.gallery-photo-item').forEach(function(btn){btn.addEventListener('click',function(){open(parseInt(this.dataset.index));});});
			document.getElementById('gallery-lb-close').addEventListener('click',close);
			document.getElementById('gallery-lb-prev').addEventListener('click',function(){show(current-1);});
			document.getElementById('gallery-lb-next').addEventListener('click',function(){show(current+1);});
			lb.addEventListener('click',function(e){if(e.target===lb)close();});
			document.addEventListener('keydown',function(e){if(!lb.classList.contains('is-open'))return;if(e.key==='ArrowLeft'||e.key==='ArrowUp'){e.preventDefault();show(current-1);}if(e.key==='ArrowRight'||e.key==='ArrowDown'){e.preventDefault();show(current+1);}if(e.key==='Escape')close();});
		})();
		</script>

		<?php else : ?>
		<div class="gallery-empty">
			<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true">
				<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
			</svg>
			<h2><?php esc_html_e( 'Fotografie se připravují', 'cpnrp' ); ?></h2>
			<p><?php esc_html_e( 'Brzy zde naleznete fotografie z tohoto alba.', 'cpnrp' ); ?></p>
		</div>
		<?php endif; ?>

		<div class="gallery-back-link">
			<a href="<?php echo esc_url( $year ? $year_url : $archive_url ); ?>" class="btn btn--outline">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
					<polyline points="15 18 9 12 15 6"/>
				</svg>
				<?php echo $year ? esc_html( "Zpět na $year" ) : esc_html__( 'Zpět na fotogalerie', 'cpnrp' ); ?>
			</a>
		</div>
	</div>
</section>

</main>

<?php get_footer(); ?>
