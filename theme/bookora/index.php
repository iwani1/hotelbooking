<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bookora
 */

get_header();
$bookoraPostClass = '';
if (is_active_sidebar('sidebar-1')) {
	$bookoraPostClass = 'col-lg-8';
} else {
	$bookoraPostClass = 'col-lg-10 offset-lg-1';
}
?>
<section id="sn-blog-list" class="sn-blog-list-sec pt-130 pb-110">
	<div class="container">
		<div class="row">
			<div class="<?php echo esc_attr($bookoraPostClass); ?>">
				<?php bookora_post_loop(); ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</section>

<?php
get_footer();
