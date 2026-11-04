<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package bookora
 */

get_header();
$bookoraPostClass = '';
if (is_active_sidebar('sidebar-1')) {
	$bookoraPostClass = 'col-xxl-8 col-xl-8 col-lg-8';
} else {
	$bookoraPostClass = 'col-lg-10 offset-lg-1';
}
?>
<section id="sn-b-thumbnail" class="sn-b-thumbnail-section headline">
	<div class="b-thumbnail-wrap">
		<?php
		// Get featured image or fallback
		$thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
		if (!$thumb_url) {
			$thumb_url = get_template_directory_uri() . '/assets/img/blog/b-thumb.jpg';
		}

		// Get author info safely
		$author_id = get_post_field('post_author', get_the_ID());
		$author_name = get_the_author_meta('display_name', $author_id);
		$author_url = get_author_posts_url($author_id);
		?>

		<div class="b-thumbnail position-relative" data-background="<?php echo esc_url($thumb_url); ?>">
			<h3><?php the_title(); ?></h3>
			<div class="b-thumb-meta position-absolute text-center">
				<a href="<?php echo esc_url($author_url); ?>">
					<i class="fas fa-user-circle"></i>
					<?php echo esc_html($author_name ? $author_name : 'Admin'); ?>
				</a>
				<a href="<?php comments_link(); ?>">
					<i class="fas fa-comments"></i>
					<?php
					printf(
						esc_html__('Comments (%s)', 'bookora'),
						number_format_i18n(get_comments_number())
					);
					?>
				</a>
				<a href="#">
					<i class="fas fa-calendar-alt"></i>
					<?php echo esc_html(get_the_date('F j, Y')); ?>
				</a>
			</div>
		</div>
	</div>
</section>


<section id="sn-b-details" class="sn-b-details-section pt-80 pb-120">
	<div class="container">
		<?php bookora_single_post_loop(); ?>
	</div>
</section>

<?php
get_footer();
