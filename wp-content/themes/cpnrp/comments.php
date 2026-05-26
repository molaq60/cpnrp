<?php
/**
 * Comments template
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php
	if ( have_comments() ) {
		?>
		<h2 class="comments-title">
			<?php
			$comment_count = get_comments_number();
			if ( 1 === $comment_count ) {
				esc_html_e( 'One thought on this', 'cpnrp' );
			} else {
				printf( esc_html__( '%d thoughts on this', 'cpnrp' ), $comment_count );
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 50,
			) );
			?>
		</ol>

		<?php
		the_comments_pagination( array(
			'prev_text' => esc_html__( 'Older Comments', 'cpnrp' ),
			'next_text' => esc_html__( 'Newer Comments', 'cpnrp' ),
		) );
	}

	if ( comments_open() ) {
		comment_form( array(
			'label_submit' => esc_html__( 'Post Comment', 'cpnrp' ),
		) );
	}
	?>
</div>
