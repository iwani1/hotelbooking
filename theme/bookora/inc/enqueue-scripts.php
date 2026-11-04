<?php

/**
 *Google Font Load
 */
if (!function_exists('bookora_fonts_url')):

	function bookora_fonts_url(): mixed
	{
		$fonts_url = '';
		$font_families = array();
		$subsets = 'latin';

		if ('off' !== _x('on', 'Inter: on or off', 'bookora')) {
			$font_families[] = 'Inter:100,100i,200,200i,300,300i,400,400i,500,500i,700,700i,800,800i,900,900i';
		}
		if ('off' !== _x('on', 'Public Sans: on or off', 'bookora')) {
			$font_families[] = 'Public Sans:100,100i,200,200i,300,300i,400,400i,500,500i,700,700i,800,800i,900,900i';
		}

		if ($font_families) {
			$fonts_url = add_query_arg(
				array(
					'family' => urlencode(implode('|', $font_families)),
					'subset' => urlencode($subsets),
				),
				'https://fonts.googleapis.com/css'
			);
		}

		return esc_url_raw($fonts_url);
	}
endif;


/**
 * Enqueue scripts and styles.
 */
function bookora_scripts()
{

	wp_enqueue_style('bookora-google-fonts', bookora_fonts_url(), array(), null);

	wp_enqueue_style('bootstrap', BOOKORA_CSS_PATH . '/bootstrap.min.css');
	wp_enqueue_style('e-animations', BOOKORA_CSS_PATH . '/animate.css');
	wp_enqueue_style('flatpickr-bk', BOOKORA_CSS_PATH . '/flatpickr.min.css');
	wp_enqueue_style('fontawesome', BOOKORA_CSS_PATH . '/fontawesome.css');
	wp_enqueue_style('magnific-popup', BOOKORA_CSS_PATH . '/magnific-popup.css');
	wp_enqueue_style('nice-select', BOOKORA_CSS_PATH . '/nice-select.css');
	wp_enqueue_style('video', BOOKORA_CSS_PATH . '/video.min.css');
	wp_enqueue_style('odometer', BOOKORA_CSS_PATH . '/odometer.min.css');
	wp_enqueue_style('swiper-bookora', BOOKORA_CSS_PATH . '/swiper.min.css');
	wp_enqueue_style('bookora-main', BOOKORA_CSS_PATH . '/style.css');

	$your_curnt_lang = apply_filters('wpml_current_language', NULL);
	if (is_rtl() && $your_curnt_lang != 'en') {
		wp_enqueue_style('bookora-rtl', BOOKORA_CSS_PATH . '/rtl.css');
	}

	wp_enqueue_style('bookora-style', get_stylesheet_uri(), array());

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('masonry');
	wp_enqueue_script('imagesloaded');

	wp_enqueue_script('bootstrap-bundle', BOOKORA_JS_PATH . '/bootstrap.bundle.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('swiper-bundle', BOOKORA_JS_PATH . '/swiper-bundle.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('wow', BOOKORA_JS_PATH . '/wow.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('flatpickr-bk', BOOKORA_JS_PATH . '/flatpickr.js', array('jquery'), '1.0', true);
	wp_enqueue_script('appear', BOOKORA_JS_PATH . '/appear.js', array('jquery'), '1.0', true);
	wp_enqueue_script('SplitText', BOOKORA_JS_PATH . '/SplitText.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('split-type', BOOKORA_JS_PATH . '/split-type.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('odometer', BOOKORA_JS_PATH . '/odometer.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('gsap', BOOKORA_JS_PATH . '/gsap.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('magnific-popup', BOOKORA_JS_PATH . '/jquery.magnific-popup.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('CustomEase', BOOKORA_JS_PATH . '/CustomEase.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('ScrollTrigger', BOOKORA_JS_PATH . '/ScrollTrigger.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('counterup', BOOKORA_JS_PATH . '/jquery.counterup.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('waypoints', BOOKORA_JS_PATH . '/waypoints.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('marquee', BOOKORA_JS_PATH . '/jquery.marquee.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('lenis', BOOKORA_JS_PATH . '/lenis.min.js', array('jquery'), '1.0', true);
	wp_enqueue_script('script', BOOKORA_JS_PATH . '/script.js', array('jquery', 'masonry', 'imagesloaded'), '1.0', true);

	$your_curnt_lang = apply_filters('wpml_current_language', NULL);
	if (is_rtl() && $your_curnt_lang != 'en') {
		wp_enqueue_script('bookora-rtl', BOOKORA_JS_PATH . '/rtl.js', array('jquery'), '1.0', true);
	}


	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'bookora_scripts');