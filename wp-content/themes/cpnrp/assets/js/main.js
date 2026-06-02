/**
 * CPNRP Theme - Main JavaScript
 */

(function() {
	'use strict';

	function init() {
		initPageLoader();
		initScrollBehavior();
		initMobileMenu();
		initMenuDropdowns();
		initSmoothScroll();
		initAnimations();
		initCounters();
	}

	// ======================================================================
	// 0. Page loader
	// ======================================================================

	function initPageLoader() {
		var loader = document.getElementById( 'page-loader' );
		if ( !loader ) return;

		function showLoader() {
			loader.classList.remove( 'is-hidden' );
			loader.classList.add( 'is-active' );
		}

		function hideLoader() {
			var el = document.getElementById( 'page-loader' ) || loader;
			if ( !el ) return;
			el.classList.remove( 'is-active' );
			el.classList.add( 'is-hidden' );
			setTimeout( function() {
				if ( el.parentNode ) el.parentNode.removeChild( el );
			}, 400 );
		}

		// Current page: show if not yet fully loaded, hide when done
		if ( document.readyState === 'complete' ) {
			if ( loader.parentNode ) loader.parentNode.removeChild( loader );
		} else {
			showLoader();
			window.addEventListener( 'load', hideLoader );
			setTimeout( hideLoader, 8000 ); // safety fallback
		}

		// bfcache restore (browser back/forward) — loader may still be active
		window.addEventListener( 'pageshow', function( e ) {
			if ( e.persisted ) hideLoader();
		} );

		// Navigation: show on link click — syncs with browser tab spinner
		document.addEventListener( 'click', function( e ) {
			var link = e.target.closest( 'a[href]' );
			if ( !link ) return;

			var href = link.getAttribute( 'href' );
			// Skip: anchors, mailto/tel, blank target, external, download
			if ( !href || href.charAt(0) === '#' || /^(mailto|tel|javascript):/.test( href ) ) return;
			if ( link.target === '_blank' || link.hasAttribute( 'download' ) ) return;
			if ( link.hostname && link.hostname !== window.location.hostname ) return;
			// Skip WP admin links
			if ( href.indexOf( '/wp-admin' ) !== -1 || href.indexOf( '/wp-login' ) !== -1 ) return;
			// Skip TEC navigation — handled via fetch, page never actually reloads
			if ( link.closest( '.tribe-events' ) || link.closest( '.tribe-common' ) ) return;

			var nav = document.getElementById( 'page-loader' );
			if ( nav ) {
				showLoader();
			} else {
				// Loader was already removed — recreate it for this navigation
				var fresh = document.createElement( 'div' );
				fresh.id = 'page-loader';
				fresh.setAttribute( 'aria-hidden', 'true' );
				fresh.innerHTML = '<svg class="loader-ring" width="96" height="96" viewBox="0 0 128 128" xmlns="http://www.w3.org/2000/svg"><g><path d="m64 7a57 57 0 1 0 57 57 57 57 0 0 0 -57-57zm0 84.972a27.972 27.972 0 1 1 27.972-27.972 27.971 27.971 0 0 1 -27.972 27.972z" fill="#C0392B"/><g fill="#ffffff"><path d="m35.908 14.392a57.274 57.274 0 0 0 -21.516 21.516l25.263 14.306a28.123 28.123 0 0 1 10.559-10.559z"/><path d="m92.092 113.608a57.274 57.274 0 0 0 21.516-21.516l-25.263-14.306a28.123 28.123 0 0 1 -10.559 10.559z"/><path d="m113.608 35.908a57.274 57.274 0 0 0 -21.516-21.516l-14.306 25.263a28.123 28.123 0 0 1 10.559 10.559z"/><path d="m14.392 92.092a57.274 57.274 0 0 0 21.516 21.516l14.306-25.263a28.123 28.123 0 0 1 -10.559-10.559z"/></g></g></svg>';
				document.body.appendChild( fresh );
				requestAnimationFrame( function() { fresh.classList.add( 'is-active' ); } );
			}
		} );
	}

	// ======================================================================
	// 1. Scroll-shrink header + shadow reveal
	// ======================================================================

	function initScrollBehavior() {
		const wrap = document.getElementById( 'sticky-header' );
		if ( !wrap ) return;

		// Hysteresis: add .scrolled at 90px, remove only when back below 20px.
		// Prevents feedback loop where header shrinking changes scrollY and
		// toggles the class repeatedly at the threshold boundary.
		const THRESHOLD_ON  = 90;
		const THRESHOLD_OFF = 2;   /* rozbalí se jen při návratu na úplný vrch */
		let ticking = false;

		function update() {
			const y         = window.scrollY;
			const isScrolled = wrap.classList.contains( 'scrolled' );

			if ( !isScrolled && y > THRESHOLD_ON ) {
				wrap.classList.add( 'scrolled' );
			} else if ( isScrolled && y < THRESHOLD_OFF ) {
				wrap.classList.remove( 'scrolled' );
			}
			ticking = false;
		}

		window.addEventListener( 'scroll', function() {
			if ( !ticking ) {
				requestAnimationFrame( update );
				ticking = true;
			}
		}, { passive: true } );
	}

	// ======================================================================
	// 2. Offcanvas mobile menu
	// ======================================================================

	function initMobileMenu() {
		const toggle  = document.querySelector( '.mobile-menu-toggle' );
		const overlay = document.getElementById( 'offcanvas-overlay' );
		const drawer  = document.getElementById( 'offcanvas' );
		const closeBtn = document.getElementById( 'offcanvas-close' );

		if ( !toggle || !drawer ) return;

		function openDrawer() {
			document.body.classList.add( 'offcanvas-open' );
			toggle.setAttribute( 'aria-expanded', 'true' );
			drawer.setAttribute( 'aria-hidden', 'false' );
			if ( overlay ) overlay.setAttribute( 'aria-hidden', 'false' );
		}

		function closeDrawer() {
			document.body.classList.remove( 'offcanvas-open' );
			toggle.setAttribute( 'aria-expanded', 'false' );
			drawer.setAttribute( 'aria-hidden', 'true' );
			if ( overlay ) overlay.setAttribute( 'aria-hidden', 'true' );
		}

		toggle.addEventListener( 'click', function() {
			document.body.classList.contains( 'offcanvas-open' ) ? closeDrawer() : openDrawer();
		} );

		if ( overlay )  overlay.addEventListener( 'click', closeDrawer );
		if ( closeBtn ) closeBtn.addEventListener( 'click', closeDrawer );

		// Escape key closes drawer
		document.addEventListener( 'keydown', function( e ) {
			if ( e.key === 'Escape' && document.body.classList.contains( 'offcanvas-open' ) ) {
				closeDrawer();
			}
		} );

		// Accordion — toggle sub-menus inside drawer
		drawer.querySelectorAll( '.offcanvas-trigger' ).forEach( function( btn ) {
			btn.addEventListener( 'click', function() {
				const isOpen  = this.getAttribute( 'aria-expanded' ) === 'true';
				const submenu = this.nextElementSibling;

				// Close all open panels first
				drawer.querySelectorAll( '.offcanvas-trigger[aria-expanded="true"]' ).forEach( function( b ) {
					b.setAttribute( 'aria-expanded', 'false' );
					const sm = b.nextElementSibling;
					if ( sm ) sm.hidden = true;
				} );

				// Open this one if it was closed
				if ( !isOpen && submenu ) {
					this.setAttribute( 'aria-expanded', 'true' );
					submenu.hidden = false;
				}
			} );
		} );

		// Close drawer when any link inside is clicked
		drawer.querySelectorAll( 'a' ).forEach( function( link ) {
			link.addEventListener( 'click', closeDrawer );
		} );
	}

	// ======================================================================
	// 3. Megamenu dropdowns
	// ======================================================================

	function initMenuDropdowns() {
		const header  = document.getElementById( 'sticky-header' );
		const triggers = document.querySelectorAll( '.menu-trigger' );
		let closeTimeout = null;
		let activePanel  = null;
		let activeChevron = null;

		function openPanel( panel, chevron ) {
			if ( closeTimeout ) { clearTimeout( closeTimeout ); closeTimeout = null; }

			if ( activePanel && activePanel !== panel ) {
				activePanel.classList.remove( 'active' );
				if ( activeChevron ) activeChevron.style.transform = '';
			}

			if ( header ) {
				panel.style.top = header.getBoundingClientRect().bottom + 'px';
			}

			panel.classList.add( 'active' );
			if ( chevron ) chevron.style.transform = 'rotate(180deg)';
			activePanel   = panel;
			activeChevron = chevron;
		}

		function closePanel() {
			if ( activePanel )  activePanel.classList.remove( 'active' );
			if ( activeChevron ) activeChevron.style.transform = '';
			activePanel   = null;
			activeChevron = null;
		}

		function scheduleClose() {
			closeTimeout = setTimeout( closePanel, 300 );
		}

		triggers.forEach( function( trigger ) {
			const panel   = document.getElementById( trigger.getAttribute( 'data-target' ) );
			const chevron = trigger.querySelector( '.menu-chevron' );
			const li      = trigger.parentElement; // <li> covers full header height → no gap
			if ( !panel ) return;

			li.addEventListener( 'mouseenter', function() { openPanel( panel, chevron ); } );
			li.addEventListener( 'mouseleave', scheduleClose );

			panel.addEventListener( 'mouseenter', function() {
				if ( closeTimeout ) { clearTimeout( closeTimeout ); closeTimeout = null; }
			} );
			panel.addEventListener( 'mouseleave', scheduleClose );

			trigger.addEventListener( 'click', function() {
				panel.classList.contains( 'active' ) ? scheduleClose() : openPanel( panel, chevron );
			} );
		} );

		document.addEventListener( 'click', function( e ) {
			if ( !e.target.closest( '.menu-trigger' ) && !e.target.closest( '.megamenu-panel' ) ) {
				closePanel();
			}
		} );

		document.addEventListener( 'keydown', function( e ) {
			if ( e.key === 'Escape' ) closePanel();
		} );
	}

	// ======================================================================
	// 4. Smooth Scroll
	// ======================================================================

	function initSmoothScroll() {
		document.querySelectorAll( 'a[href^="#"]' ).forEach( function( link ) {
			link.addEventListener( 'click', function( e ) {
				const href   = this.getAttribute( 'href' );
				if ( href === '#' ) return;
				const target = document.querySelector( href );
				if ( target ) {
					e.preventDefault();
					target.scrollIntoView( { behavior: 'smooth' } );
				}
			} );
		} );
	}

	// ======================================================================
	// 5. Scroll-reveal animations (IntersectionObserver)
	// ======================================================================

	function initAnimations() {
		const observer = new IntersectionObserver( function( entries ) {
			entries.forEach( function( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					observer.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.1 } );

		document.querySelectorAll( '.animate-fade-up, .animate-fade-in, .animate-scale-in' ).forEach( function( el ) {
			observer.observe( el );
		} );
	}

	// ======================================================================
	// 6. Count-up animation for stats
	// ======================================================================

	function initCounters() {
		var counters = document.querySelectorAll( '[data-count-target]' );
		if ( !counters.length ) return;

		var reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		if ( reduced ) {
			counters.forEach( function( el ) {
				el.textContent = el.dataset.countFormat || el.dataset.countTarget;
			} );
			return;
		}

		var formatNum = function( n ) {
			return n.toLocaleString( 'cs-CZ' ).replace( /,/g, ' ' );
		};

		var animateCounter = function( el ) {
			var target   = Number( el.dataset.countTarget || 0 );
			var duration = 1800;
			var start    = performance.now();

			var tick = function( now ) {
				var t     = Math.min( 1, ( now - start ) / duration );
				var eased = t * t;          /* quadratic ease-in — pomalý start, zrychluje */
				el.textContent = formatNum( Math.floor( target * eased ) );
				if ( t < 1 ) {
					requestAnimationFrame( tick );
				} else {
					el.textContent = el.dataset.countFormat || formatNum( target );
				}
			};
			requestAnimationFrame( tick );
		};

		var io = new IntersectionObserver( function( entries ) {
			entries.forEach( function( entry ) {
				if ( entry.isIntersecting ) {
					setTimeout( function() {       /* nula viditelná 500ms před startem */
						animateCounter( entry.target );
					}, 500 );
					io.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.4 } );

		counters.forEach( function( el ) { io.observe( el ); } );
	}

	// ======================================================================
	// Boot
	// ======================================================================

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}

})();
