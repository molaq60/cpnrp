<?php
/**
 * CPNRP_Nav_Walker — renders only depth-0 items in the navbar.
 *
 * Top-level items WITH children become <button class="menu-trigger"> that open
 * a megamenu panel.  The panel ID is derived automatically via sanitize_title(),
 * so "Pro rodiny" → data-target="pro-rodiny-panel".
 *
 * Sub-items (depth > 0) are suppressed here; cpnrp_render_megamenu_panels()
 * in helpers.php builds the actual panels from the same menu data.
 */
class CPNRP_Nav_Walker extends Walker_Nav_Menu {

	// Suppress sub-menu wrappers — panels are rendered separately
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth > 0 ) return;

		$has_children = in_array( 'menu-item-has-children', $item->classes, true );
		$li_classes   = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $item->classes ), $item, $args, $depth ) );

		$output .= '<li class="' . esc_attr( $li_classes ) . '">';

		if ( $has_children ) {
			$target = sanitize_title( $item->title ) . '-panel';
			$url    = ! empty( $item->url ) ? $item->url : '';
			$output .= '<a class="menu-link" href="' . esc_url( $url ) . '">' . esc_html( $item->title ) . '</a>';
			$output .= '<button class="menu-trigger" aria-haspopup="true" aria-expanded="false" data-target="' . esc_attr( $target ) . '">';
			$output .= '<svg class="menu-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>';
			$output .= '</button>';
		} else {
			$atts = [
				'href'   => ! empty( $item->url ) ? $item->url : '',
				'title'  => ! empty( $item->attr_title ) ? $item->attr_title : '',
				'target' => ! empty( $item->target ) ? $item->target : '',
				'rel'    => ! empty( $item->xfn ) ? $item->xfn : '',
			];
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attr_str = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value     = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
					$attr_str .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$title   = apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth );
			$output .= '<a' . $attr_str . '>' . $title . '</a>';
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( $depth > 0 ) return;
		$output .= '</li>';
	}
}

/**
 * CPNRP_Mobile_Walker — accordion menu for the offcanvas drawer.
 *
 * Depth 0 with children → <button class="offcanvas-trigger"> (accordion toggle)
 * Depth 0 no children   → <a class="offcanvas-link">
 * Depth 1               → section header inside collapsed panel
 * Depth 2               → sub-link
 */
class CPNRP_Mobile_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '<div class="offcanvas-submenu" hidden><ul class="offcanvas-subgroup">';
		} elseif ( $depth === 1 ) {
			$output .= '<ul class="offcanvas-sublist">';
		}
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( $depth === 0 ) {
			$output .= '</ul></div>';
		} elseif ( $depth === 1 ) {
			$output .= '</ul>';
		}
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$has_children = in_array( 'menu-item-has-children', $item->classes, true );
		$title        = apply_filters( 'the_title', $item->title, $item->ID );

		if ( $depth === 0 ) {
			$output .= '<li class="offcanvas-item">';
			if ( $has_children ) {
				$output .= '<a class="offcanvas-link" href="' . esc_url( $item->url ) . '">' . esc_html( $title ) . '</a>';
				$output .= '<button class="offcanvas-trigger" aria-expanded="false">';
				$output .= '<svg class="offcanvas-chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>';
				$output .= '</button>';
			} else {
				$output .= '<a class="offcanvas-link" href="' . esc_url( $item->url ) . '">' . esc_html( $title ) . '</a>';
			}
		} elseif ( $depth === 1 ) {
			$output .= '<li class="offcanvas-group">';
			if ( ! empty( $item->url ) && '#' !== $item->url ) {
				$output .= '<a class="offcanvas-group-title" href="' . esc_url( $item->url ) . '">' . esc_html( $title ) . '</a>';
			} else {
				$output .= '<span class="offcanvas-group-title">' . esc_html( $title ) . '</span>';
			}
		} elseif ( $depth === 2 ) {
			$output .= '<li>';
			$output .= '<a class="offcanvas-sublink" href="' . esc_url( $item->url ) . '">' . esc_html( $title ) . '</a>';
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}
