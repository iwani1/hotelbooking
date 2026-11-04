<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bookora
 */
if (get_post_meta(get_the_ID(), 'bookora_page_meta', true)) {
	$footer_meta = get_post_meta(get_the_ID(), 'bookora_page_meta', true);
} else {
	$footer_meta = array();
}

if (array_key_exists('page_footer_disable', $footer_meta)) {
	$page_footer_disable = $footer_meta['page_footer_disable'];
} else {
	$page_footer_disable = false;
}
?>
</div>
</div><!-- #page -->
<?php if ($page_footer_disable != true) {
	Bookora_Helper::bookora_footer_template();
} ?>
<?php wp_footer(); ?>

</body>

</html>