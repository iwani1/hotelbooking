<?php

function bookora_breadcrumb()
{
	$wpbreadcrumb_class = '';
	$breadcrumb_show = 1;
	$id = get_the_ID();

	if (is_front_page() && is_home()) {
		$title = get_the_title();
		$wpbreadcrumb_class = 'tx-front-page';
	} elseif (is_front_page()) {
		$title = get_the_title();
		$breadcrumb_show = 0;
	} elseif (is_home()) {
		if (get_option('page_for_posts')) {
			$id = get_option('page_for_posts');
			$title = get_the_title(get_option('page_for_posts'));
		}
	} elseif (is_single() && 'post' == get_post_type()) {
		$title = get_the_title();
	} elseif (is_search()) {
		$title = esc_html__('Search Results for : ', 'bookora') . get_search_query();
	} elseif (is_404()) {
		return; // ❌ Do not display breadcrumb on 404 pages
	} elseif (function_exists('is_woocommerce') && is_shop()) {
		$breadcrumb_show = 0; // Hide breadcrumb for WooCommerce shop page
	} elseif (function_exists('is_woocommerce') && is_product()) {
		$title = __('Product Details', 'bookora');
	} elseif (function_exists('is_woocommerce') && is_product_tag()) {
		$title = get_the_archive_title();
	} elseif (function_exists('is_woocommerce') && is_product_category()) {
		$title = get_the_archive_title();
	} elseif (is_archive()) {
		$title = get_the_archive_title();
	} else {
		$title = get_the_title();
	}

	// Retrieve page metadata
	$page_meta = get_post_meta($id, 'bookora_page_meta', true);
	$page_meta = is_array($page_meta) ? $page_meta : [];

	// Properly check the 'enable_page_preadcrumb' meta key
	$enable_page_preadcrumb = isset($page_meta['enable_page_preadcrumb']) ? (bool) $page_meta['enable_page_preadcrumb'] : true;

	// Stop breadcrumb rendering if disabled via meta
	if (!$enable_page_preadcrumb || $breadcrumb_show == 0) {
		return;
	}

	$enable_bg_image = isset($page_meta['enable_bg_image']) ? (bool) $page_meta['enable_bg_image'] : true;
	$bg_img_from_page = isset($page_meta['bg_img_from_page']['url']) ? $page_meta['bg_img_from_page']['url'] : '';
	$bg_img_page = isset($page_meta['bg_img_page']['url']) ? $page_meta['bg_img_page']['url'] : '';
	$enable_custom_title = isset($page_meta['enable_custom_title']) ? (bool) $page_meta['enable_custom_title'] : false;
	$page_custom_title = isset($page_meta['page_custom_title']) ? $page_meta['page_custom_title'] : '';

	$breadcrumb_bg = cs_get_option('breadcrumb_bg_img');
	$bg_img = !empty($breadcrumb_bg['url']) ? $breadcrumb_bg['url'] : '';
	$bg_img = $enable_bg_image ? (!empty($bg_img_from_page) ? $bg_img_from_page : $bg_img) : $bg_img;
	$nr_img = $enable_bg_image ? (!empty($bg_img_page) ? $bg_img_page : '') : '';

	$title = $enable_custom_title ? $page_custom_title : $title;

	if (has_nav_menu('main-menu')) {
		$no_menu_class = '';
	} else {
		$no_menu_class = ' pt-200';
	}

	$shape1 = cs_get_option('shape-1');
	$shape2 = cs_get_option('shape-2');
	$shape3 = cs_get_option('shape-3');
	$shape4 = cs_get_option('shape-4');
	$tagline = cs_get_option('breadcrumb_tag_link');

	?>
	<section id="sn-breadcrumb" class="sn-breadcrumb-sec">
		<div class="sn-breadcrumb-wrapper" data-background="<?php echo esc_url($bg_img); ?>">
			<div class="container">
				<div class="sn-breadcrumb-content headline ul-li text-center">
					<h2 class="bread_title"><?php echo wp_kses_post($title); ?></h2>
					<?php echo bookora_breadcrumb_callback(); ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}
add_action('bookora_before_main_content', 'bookora_breadcrumb');



function bookora_breadcrumb_callback()
{
	global $wp_query;
	$queried_object = get_queried_object();
	$breadcrumb = '<ul class="breadcrumb-list">'; // Start breadcrumb list
	$delimiter = '';
	$before = '<li class="item">';
	$after = '</li>';

	if (!is_front_page()) {
		$breadcrumb .= $before . '<a href="' . home_url('/') . '">' . esc_html__('Home', 'bookora') . '</a>' . $after;

		/** If category or single post */
		if (is_category()) {
			$cat_obj = $wp_query->get_queried_object();
			$this_category = get_category($cat_obj->term_id);
			if ($this_category->parent != 0) {
				$parent_category = get_category($this_category->parent);
				$breadcrumb .= get_category_parents($parent_category, true, $delimiter, $before, $after);
			}
			$breadcrumb .= $before . '<a href="' . get_category_link(get_query_var('cat')) . '">' . single_cat_title('', false) . '</a>' . $after;
		} elseif ($wp_query->is_posts_page) {
			$breadcrumb .= $before . $queried_object->post_title . $after;
		} elseif (is_tax()) {
			$breadcrumb .= $before . '<a href="' . get_term_link($queried_object) . '">' . $queried_object->name . '</a>' . $after;
		} elseif (is_page()) { // If WP pages
			global $post;
			if ($post->post_parent) {
				$anc = get_post_ancestors($post->ID);
				foreach ($anc as $ancestor) {
					$breadcrumb .= $before . '<a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a>' . $after;
				}
				$breadcrumb .= $before . get_the_title($post->ID) . $after;
			} else {
				$breadcrumb .= $before . get_the_title() . $after;
			}
		} elseif (is_singular()) {
			if ($category = wp_get_object_terms(get_the_ID(), array('category', 'location', 'tax_feature'))) {
				if (!is_wp_error($category)) {
					$breadcrumb .= $before . '<a href="' . get_term_link(binsro_set($category, '0')) . '">' . binsro_set(binsro_set($category, '0'), 'name') . '</a>' . $after;
					$breadcrumb .= $before . get_the_title() . $after;
				} else {
					$breadcrumb .= $before . get_the_title() . $after;
				}
			} else {
				$breadcrumb .= $before . get_the_title() . $after;
			}
		} elseif (is_tag()) {
			$breadcrumb .= $before . '<a href="' . get_term_link($queried_object) . '">' . single_tag_title('', false) . '</a>' . $after;
		} elseif (is_day()) {
			$breadcrumb .= $before . esc_html__('Archive for ', 'bookora') . get_the_time('F jS, Y') . $after;
		} elseif (is_month()) {
			$breadcrumb .= $before . '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . __('Archive for ', 'bookora') . get_the_time('F, Y') . '</a>' . $after;
		} elseif (is_year()) {
			$breadcrumb .= $before . '<a href="' . get_year_link(get_the_time('Y')) . '">' . __('Archive for ', 'bookora') . get_the_time('Y') . '</a>' . $after;
		} elseif (is_author()) {
			$breadcrumb .= $before . '<a href="' . esc_url(get_author_posts_url(get_the_author_meta("ID"))) . '">' . __('Archive for ', 'bookora') . get_the_author() . '</a>' . $after;
		} elseif (is_search()) {
			$breadcrumb .= $before . esc_html__('Search Results for ', 'bookora') . get_search_query() . $after;
		} elseif (is_404()) {
			$breadcrumb .= $before . esc_html__('404 - Not Found', 'bookora') . $after;
		} elseif (is_post_type_archive('product')) {
			$shop_page_id = wc_get_page_id('shop');
			if (get_option('page_on_front') !== $shop_page_id) {
				$shop_page = get_post($shop_page_id);
				$_name = wc_get_page_id('shop') ? get_the_title(wc_get_page_id('shop')) : '';
				if (!$_name) {
					$product_post_type = get_post_type_object('product');
					$_name = $product_post_type->labels->singular_name;
				}
				if (is_search()) {
					$breadcrumb .= $before . '<a href="' . get_post_type_archive_link('product') . '">' . $_name . '</a>' . $delimiter . esc_html__('Search results for &ldquo;', 'bookora') . get_search_query() . '&rdquo;' . $after;
				} elseif (is_paged()) {
					$breadcrumb .= $before . '<a href="' . get_post_type_archive_link('product') . '">' . $_name . '</a>' . $after;
				} else {
					$breadcrumb .= $before . $_name . $after;
				}
			}
		} else {
			$breadcrumb .= $before . '<a href="' . get_permalink() . '">' . get_the_title() . '</a>' . $after;
		}
	}

	$breadcrumb .= '</ul>'; // Close breadcrumb list
	return $breadcrumb;
}

