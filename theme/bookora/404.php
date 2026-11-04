<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package bookora
 */

get_header();
$error_code = cs_get_option('error_code');
$error_title = cs_get_option('error_title');
$error_button = cs_get_option('error_button');
$error_img = cs_get_option('error_img');
?>
<section id="sn-error" class="sn-error-sec pt-200 pb-140">
	<div class="container">
		<div class="sn-error-content d-flex justify-content-between align-items-center  pt-20">
			<div class="sn-error-text headline pera-content">
				<h3>
					<?php if (!empty($error_code)) {
						echo bookora_wp_kses($error_code);
					} else {
						esc_html_e('404', 'bookora');
					} ?>
				</h3>
				<p>
					<?php if (!empty($error_title)) {
						echo bookora_wp_kses($error_title);
					} else {
						esc_html_e('Oops!', 'bookora');
					} ?>
				</p>
				<div class="sn-btn1">
					<a href="<?php echo esc_url(home_url('/')); ?>">
						<span>
							<?php if (!empty($error_button)) {
								echo esc_html($error_button);
							} else {
								esc_html_e('Back To Home Page', 'bookora');
							} ?>
						</span>
					</a>
				</div>
			</div>
			<?php if (!empty($error_img['url'])): ?>
				<div class="sn-error-img">
					<img src="<?php echo esc_url($error_img['url']); ?>" alt="">
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
