<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bookora
 */
$categories = get_the_category();
$blog_btn_text = cs_get_option('blog_btn_text');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('sn-blog-list-feed'); ?>>
	<div class="item-img">
		<?php get_template_part('template-parts/post-formats/content'); ?>
	</div>
	<div class="blog-item-meta">
		<!-- Author -->
		<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
			<i>
				<?php
				// Display author avatar dynamically
				echo get_avatar(get_the_author_meta('ID'), 32);
				?>
			</i>
			<?php the_author(); ?>
		</a>

		<!-- Comments -->
		<a href="<?php comments_link(); ?>">
			<i class="fas fa-comments"></i>
			<?php
			comments_number(
				esc_html__('No Comments', 'bookora'),
				esc_html__('1 Comment', 'bookora'),
				esc_html__('% Comments', 'bookora')
			);
			?>
		</a>

		<!-- Date -->
		<a href="<?php echo esc_url(get_day_link(get_the_time('Y'), get_the_time('m'), get_the_time('d'))); ?>">
			<i class="fas fa-calendar-alt"></i>
			<?php echo esc_html(get_the_date()); ?>
		</a>

		<!-- Categories -->
		<?php if (!empty($categories)): ?>
			<a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>">
				<i class="fas fa-folder-open"></i>
				<?php echo esc_html($categories[0]->name); ?>
			</a>
		<?php endif; ?>
	</div>
	<div class="item-text headline pera-content">
		<h3 class="blog_title href-underline">
			<a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
				<?php the_title(); ?>
			</a>
		</h3>
		<?php the_excerpt(); ?>
		<a class="read_more text-uppercase" href="<?php the_permalink(); ?>"><span>
				<?php if (!empty($blog_btn_text)) {
					echo esc_html($blog_btn_text);
				} else {
					esc_html_e('Read More', 'bookora');
				} ?>
				<i class="fa-solid fa-arrow-right-long"></i></span></a>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->