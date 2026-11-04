<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bookora
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
	return;
}
?>

<div id="comments" class="blog-details-comments-wrap mb-40">

	<?php
	// You can start editing here -- including this comment!
	if (have_comments()):
		?>
		<div class="post-comments">
			<h5 class="comments-title ftc-heading-1">
				<?php
				$bookora_comment_count = get_comments_number();
				if ('1' === $bookora_comment_count) {
					printf(
						/* translators: 1: title. */
						esc_html__('1 Comment', 'bookora'),
						'<span>' . wp_kses_post(get_the_title()) . '</span>'
					);
				} else {
					printf(
						/* translators: 1: comment count number, 2: title. */
						esc_html(_nx('%1$s Comment', '%1$s Comments', $bookora_comment_count, 'comments title', 'bookora')),
						number_format_i18n($bookora_comment_count), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'<span>' . wp_kses_post(get_the_title()) . '</span>'
					);
				}
				?>
			</h5><!-- .comments-title -->
			<?php the_comments_navigation(); ?>
			<div class="comments-box-wrap mb-20">
				<ol class="comment-list list-unstyled mb-0">
					<?php
					wp_list_comments(
						array(
							'callback' => 'bookora_comments'
						)
					);
					?>
				</ol><!-- .comment-list -->
			</div>


		</div>
		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if (!comments_open()):
			?>
			<p class="no-comments">
				<?php esc_html_e('Comments are closed.', 'bookora'); ?>
			</p>
			<?php
		endif;

	endif; // Check for have_comments().
	?>
</div><!-- #comments -->
<?php
$args = array(
	'comment_notes_after' => '
	<div class="bk-btn1">
		<button type="submit">
			' . esc_attr('Post Comment') . '
		</button>
	</div>'

);
?>
<div class="fx-form-1">
	<?php comment_form($args); ?>
</div>