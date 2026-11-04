<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package bookora
 */
add_filter('wpcf7_form_elements', function ($content) {
	$content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
	return $content;
});
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function bookora_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (!is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (!is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'bookora_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function bookora_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'bookora_pingback_header');


function bookora_category_html($links)
{
	// Use a regular expression to match and replace the default list item structure
	$links = preg_replace_callback(
		'/<a href="([^"]+)"[^>]*>([^<]+)<\/a> \((\d+)\)/',
		function ($matches) {
			$category_link = $matches[1];
			$category_name = $matches[2];
			$category_count = $matches[3];

			return '<a class="fd-sidebar-categories-item fd-heading-1" href="' . esc_url($category_link) . '" aria-label="' . esc_attr($category_name) . '" class="fd-sidebar-categories-item fd-heading-1">
                        
                        <span class="text">' . esc_html($category_name) . '</span>
                        <span class="number">' . esc_html($category_count) . '</span>
                    </a>';
		},
		$links
	);

	return $links;
}
add_filter('wp_list_categories', 'bookora_category_html');




function bookora_archive_html($links)
{
	$links = '<div class="category-widget">' . $links; // Add opening div tag
	$links = str_replace('</a>&nbsp;(', '<span class="number">', $links);
	$links = str_replace(')', '</span> <span class="icon-1"></span></a>', $links);
	$links .= '</div>'; // Add closing div tag
	return $links;
}

add_filter('get_archives_link', 'bookora_archive_html');



/**
 * Comment Link Class
 *
 * @param [type] $class
 * @return void
 */
function wpdocs_comment_reply_link_class($class)
{
	$class = str_replace("class='comment-reply-link", "class='comment-reply-link reply-btn", $class);
	return $class;
}

add_filter('comment_reply_link', 'wpdocs_comment_reply_link_class');

/**
 * Comment List Modification
 */

function bookora_comments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment; ?>

	<div <?php comment_class('comments-box-single wow fadeInUp'); ?> id="comment-<?php comment_ID() ?>">
		<?php if (get_avatar($comment)) { ?>
			<div class="person-img">
				<?php echo get_avatar($comment, 80); ?>
			</div>
		<?php } ?>
		<div class="comments-box-author-content">
			<div class="heading-wrap">
				<div class="title-wrap">
					<span class="name">
						<?php comment_author_link() ?>
					</span>
					<span class="date">
						<?php echo esc_html(get_the_time(get_option('date_format'))); ?>
					</span>
				</div>
				<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => wp_kses('Reply', true)))); ?>
			</div>
			<div class="comment-text">
				<?php comment_text(); ?>
			</div>
		</div>
	</div>

	<?php
}

/**
 * Custom Comment Form Layout
 */
function bookora_custom_comment_form($args)
{
	ob_start();
	?>

	<div class="art-contact-form-input">
		<div class="row">
			<div class="col-md-6">
				<div class="input-field">
					<input type="text" name="author" id="author"
						placeholder="<?php esc_attr_e('First Name *', 'bookora'); ?>"
						value="<?php echo esc_attr(wp_get_current_commenter()['comment_author']); ?>" required>
				</div>
			</div>
			<div class="col-md-6">
				<div class="input-field">
					<input type="text" name="lastname" id="lastname"
						placeholder="<?php esc_attr_e('Last Name *', 'bookora'); ?>">
				</div>
			</div>
			<div class="col-md-12">
				<div class="input-field">
					<input type="email" name="email" id="email"
						placeholder="<?php esc_attr_e('Email Address *', 'bookora'); ?>"
						value="<?php echo esc_attr(wp_get_current_commenter()['comment_author_email']); ?>" required>
				</div>
			</div>
			<div class="col-md-12">
				<div class="input-field">
					<textarea name="comment" id="comment"
						placeholder="<?php esc_attr_e('Write a comment here ...', 'bookora'); ?>" required></textarea>
				</div>
			</div>
		</div>
	</div>

	<?php
	$args['comment_field'] = ob_get_clean();

	// Remove default title and notes
	$args['title_reply'] = esc_html__('Leave a Comment', 'bookora');
	$args['comment_notes_before'] = '';
	$args['comment_notes_after'] = '';

	// Disable default fields (we manually render them above)
	$args['fields'] = array();

	return $args;
}
add_filter('comment_form_defaults', 'bookora_custom_comment_form');


// comment Move Field
function bookora_move_comment_field_to_bottom($fields)
{
	$comment_field = $fields['comment'];
	unset($fields['comment']);
	$fields['comment'] = $comment_field;
	return $fields;
}
add_filter('comment_form_fields', 'bookora_move_comment_field_to_bottom');


/**
 * Product Per Page Count
 *
 * @param [type] $per_page
 * @return void
 */


/**
 * Authore Avater
 */
function bookora_main_author_avatars($size)
{
	echo get_avatar(get_the_author_meta('email'), $size);
}

add_action('genesis_entry_header', 'bookora_post_author_avatars');




/**
 * pagination
 */
if (!function_exists('bookora_pagination')) {

	function _bookora_pagi_callback($pagination)
	{
		return $pagination;
	}

	//page navegation
	function bookora_pagination($prev, $next, $pages, $args)
	{
		global $wp_query, $wp_rewrite;
		$menu = '';
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;

		if ($pages == '') {
			global $wp_query;
			$pages = $wp_query->max_num_pages;

			if (!$pages) {
				$pages = 1;
			}

		}

		$pagination = [
			'base' => add_query_arg('paged', '%#%'),
			'format' => '',
			'total' => $pages,
			'current' => $current,
			'prev_text' => $prev,
			'next_text' => $next,
			'type' => 'array',
		];

		//rewrite permalinks
		if ($wp_rewrite->using_permalinks()) {
			$pagination['base'] = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . 'page/%#%/', 'paged');
		}

		if (!empty($wp_query->query_vars['s'])) {
			$pagination['add_args'] = ['s' => get_query_var('s')];
		}

		$pagi = '';
		if (paginate_links($pagination) != '') {
			$paginations = paginate_links($pagination);
			$pagi .= '<ul class="fx-pagination">';
			foreach ($paginations as $key => $pg) {
				$pagi .= '<li class="fx-pagination-item">' . $pg . '</li>';
			}
			$pagi .= '</ul>';
		}

		print _bookora_pagi_callback($pagi);
	}
}


/**
 * Search Widget
 */
function bookora_search_widgets($form)
{
	$form = '<div class="search-widget"><form method="get" id="searchform" class="fx-sidebar-search" action="' . home_url('/') . '" >
    	<input class="fx-sidebar-search-input" placeholder="' . esc_attr__('Search...', 'bookora') . '" type="text"  value="' . get_search_query() . '" name="s" id="s" />
		<button type="submit" class="fx-sidebar-search-btn"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form></div>';
	return $form;
}
add_filter('get_search_form', 'bookora_search_widgets', 100);


/**
 * Authore
 */
function bookora_authore_info()
{

	global $post;
	if (is_object($post)):

		$theme_author_markup = '';
		// Get author's display name
		$display_name = get_the_author_meta('display_name', $post->post_author);

		// If display name is not available then use nickname as display name
		if (empty($display_name))
			$display_name = get_the_author_meta('nickname', $post->post_author);

		// Get author's biographical information or description
		$user_description = get_the_author_meta('user_description', $post->post_author);

		$user_facebook = get_the_author_meta('facebook', $post->post_author);
		$user_twitter = get_the_author_meta('twitter', $post->post_author);
		$user_linkedin = get_the_author_meta('linkedin', $post->post_author);
		$user_instagram = get_the_author_meta('instagram', $post->post_author);
		$user_pinterest = get_the_author_meta('pinterest', $post->post_author);
		$user_youtube = get_the_author_meta('youtube', $post->post_author);

		// Get link to the author archive page
		$user_posts = get_author_posts_url(get_the_author_meta('ID', $post->post_author));
		if (!empty($display_name))
			// Author avatar - - the number 90 is the px size of the image.
			$theme_author_markup .= '<div class="inner-img">' . get_avatar(get_the_author_meta('ID'), 140) . '</div>';
		$theme_author_markup .= '<div class="inner-text headline pera-content">';
		$theme_author_markup .= '<div class="admin-title-social"><h4 class="name fx-heading-1 fx-font-700">' . $display_name . '</h4></div>';
		$theme_author_markup .= '<p class="comment-text fx-para-1 has-opacity-7">' . get_the_author_meta('description') . '</p>';

		$theme_author_markup .= '</div>';

		// Pass all this info to post content 
		echo '<div class="art-blog-details-author flex-wrap d-flex flex-wrap align-items-center">' . $theme_author_markup . '</div>';
	endif;
}

function bookora_wp_kses($val)
{
	$allowed = array(
		'p' => array(
			'class' => array(),
			'style' => array(),
		),
		'img' => array(
			'src' => array(),
			'alt' => array(),
			'class' => array(),
			'style' => array(),
		),
		'span' => array(
			'class' => array(),
			'style' => array(),
		),
		'small' => array(),
		'div' => array(
			'style' => array(),
		),
		'strong' => array(
			'style' => array(),
		),
		'b' => array(
			'style' => array(),
		),
		'br' => array(),
		'h1' => array(
			'style' => array(),
		),
		'i' => array(
			'class' => array(),
			'style' => array(),
		),
		'ul' => array(
			'class' => array(),
			'style' => array(),
			'id' => array(),
		),
		'li' => array(
			'class' => array(),
			'style' => array(),
			'id' => array(),
		),
		'sub' => array(
			'id' => array(),
		),
		'h2' => array(),
		'h3' => array(
			'style' => array(),
		),
		'h4' => array(
			'style' => array(),
		),
		'h5' => array(
			'style' => array(),
		),
		'h6' => array(
			'style' => array(),
		),
		'a' => array(
			'href' => array(),
			'class' => array(),
			'target' => array(),
			'style' => array(),
		),
		'iframe' => array(
			'src' => array(),
			'height' => array(),
			'width' => array(),
		),
	);

	// Sanitize the original value first, then run shortcodes,
	// then sanitize the shortcode output again.
	$val = wp_kses($val, $allowed);
	$val = do_shortcode($val);
	return wp_kses($val, $allowed);
}


/**
 * Product Per Page Count
 *
 * @param [type] $per_page
 * @return void
 */

function bookora_product_count($cols)
{
	$product_count = cs_get_option('product_count');
	$cols = !empty($product_count) ? $product_count : '12';
	return $cols;
}
add_filter('loop_shop_per_page', 'bookora_product_count', 20);
function bookora_custom_class($classes)
{
	$page_meta = get_post_meta(get_the_ID(), 'bookora_page_meta', true) ? get_post_meta(get_the_ID(), 'bookora_page_meta', true) : [];
	$bodycsl = array_key_exists('select-body-cls', $page_meta) ? $page_meta['select-body-cls'] : false;
	$body_class = $bodycsl == true ? $bodycsl : '';
	$classes[] = '' . esc_attr($body_class) . '';
	return $classes;
}
add_filter('body_class', 'bookora_custom_class');
function bookora_dark_class($classes)
{
	$page_meta = get_post_meta(get_the_ID(), 'bookora_page_meta', true) ? get_post_meta(get_the_ID(), 'bookora_page_meta', true) : [];
	$bodycsl = array_key_exists('select-dark-cls', $page_meta) ? $page_meta['select-dark-cls'] : false;
	$body_class = $bodycsl == true ? $bodycsl : '';
	$classes[] = '' . esc_attr($body_class) . '';
	return $classes;
}
add_filter('body_class', 'bookora_dark_class');


function bookora_img_alt_text($img_er_id = null)
{
	if (empty($img_er_id)) {
		return esc_html__('Image Alt Text', 'bookora');
	}

	// Convert URL to attachment ID if necessary
	if (filter_var($img_er_id, FILTER_VALIDATE_URL)) {
		$img_er_id = attachment_url_to_postid($img_er_id);
	}

	$image_alt = get_post_meta($img_er_id, '_wp_attachment_image_alt', true);
	$alt_text = !empty($image_alt) ? $image_alt : get_the_title($img_er_id);

	return $alt_text;
}