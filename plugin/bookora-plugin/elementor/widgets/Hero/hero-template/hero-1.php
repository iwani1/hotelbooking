<?php
if (!empty($settings['btn_link']['url'])) {
	$this->add_link_attributes('btn_link', $settings['btn_link']);
}
if (!empty($settings['btn_link2']['url'])) {
	$this->add_link_attributes('btn_link2', $settings['btn_link2']);
}
$this->add_render_attribute('title', 'class', 'elementor-gt-heading hero_title bk_hero_title hero_title_3');
?>
<section id="bk-hero" class="bk-hero-sec position-relative over-hidden">
	<div class="bk-hero-text pb-75 text-center headline">
		<?php if (!empty($settings['subtitle'])): ?>
			<div class="hero-slug"><i></i><?php echo bookora_wp_kses($settings['subtitle']); ?> <i></i></div>
		<?php endif; ?>
		<?php
		printf(
			'<%1$s %2$s>%3$s</%1$s>',
			tag_escape($settings['title_tag']),
			$this->get_render_attribute_string('title'),
			nl2br(bookora_wp_kses($settings['title']))
		);
		?>
		<div class="btn-wrap d-flex justify-content-center flex-wrap">
			<?php if (!empty($settings['btn_label'])): ?>
				<a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?></a>
			<?php endif; ?>
			<?php if (!empty($settings['btn_label2'])): ?>
				<a <?php echo $this->get_render_attribute_string('btn_link2'); ?>><?php echo bookora_wp_kses($settings['btn_label2']); ?></a>
			<?php endif; ?>
		</div>
	</div>
	<div class="bk-hero-filter position-relative over-hidden">
		<?php if (!empty($settings['image']['url'])): ?>
			<div class="item-bg img-parallax img-cover position-absolute">
				<img src="<?php echo esc_url($settings['image']['url']); ?>" alt="<?php if (!empty($settings['image']['alt'])) {
					   echo esc_attr($settings['image']['alt']);
				   } else {
					   esc_attr_e('List', 'ternat-plugin');
				   } ?>">
			</div>
		<?php endif; ?>
		<?php if ($settings['search_hide'] === 'yes'): ?>
			<div class="bk-filter-area">
				<form action="<?php bloginfo('url'); ?>/search-result/" method="get">
					<div class="item-field">
						<i class="fa-regular fa-calendar"></i>
						<?php echo buildSelect('tour_type', $settings['tour_lbl'], 'in_tour_type'); ?>
					</div>
					<div class="item-field">
						<i class="fa-regular fa-user"></i>
						<?php echo buildSelect('at_biz_dir-location', $settings['loc_lbl'], 'in_loc'); ?>
					</div>
					<div class="item-field">
						<i class="fa-regular fa-user"></i>
						<?php echo buildSelect('at_biz_dir-category', $settings['loc_cate'], 'in_cat'); ?>
					</div>
					<div class="item-field">
						<i class="fa-solid fa-house"></i>
						<?php echo buildSelect('at_biz_dir-tags', $settings['tag_lbl'], 'in_tag'); ?>
					</div>
					<button type="submit">Check Now</button>
				</form>
			</div>
		<?php endif; ?>
		<div class="bk-hero-bottom d-flex justify-content-between align-items-end flex-wrap">
			<?php if (!empty($settings['description'])): ?>
				<div class="item-desc">
					<?php echo bookora_wp_kses($settings['description']); ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($settings['video_link']['url']) || !empty($settings['video_text'])): ?>
				<div class="item-play d-flex flex-wrap align-items-center">
					<div class="item-icon">
						<a href="<?php echo esc_url($settings['video_link']['url']); ?>">
							<i class="fa-solid fa-play"></i>
							<span class="video_btn_border border_wrap-1"></span>
						</a>
					</div>
					<div class="item-text">
						<?php echo bookora_wp_kses($settings['video_text']); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if ($settings['animation_hide'] === 'yes'): ?>
		<div class="line_animation">
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
			<div class="line_area"></div>
		</div>
	<?php endif; ?>
</section>