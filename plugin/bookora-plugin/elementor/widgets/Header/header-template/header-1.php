<?php
if (!empty($settings['btn_link']['url'])) {
	$this->add_link_attributes('btn_link', $settings['btn_link']);
}
if (!empty($settings['btn_link2']['url'])) {
	$this->add_link_attributes('btn_link2', $settings['btn_link2']);
}
?>
<header id="bk-header" class="bk-header-section header_style_one txa_sticky_header">
	<div class="bk-header-content">
		<div class="header-top d-flex justify-content-between align-items-center">
			<?php if (!empty($settings['socials'])): ?>
				<div class="top-social d-flex">
					<?php foreach ($settings['socials'] as $item): ?>
						<a target="<?php echo esc_attr($item['link']['is_external'] ? '_blank' : '_self'); ?>"
							rel="<?php echo esc_attr($item['link']['nofollow'] ? 'nofollow' : ''); ?>"
							href="<?php echo $item['link']['url'] ? esc_url($item['link']['url']) : ''; ?>"><?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($settings['infos'])): ?>
				<div class="top-cta d-flex align-items-center">
					<?php foreach ($settings['infos'] as $item): ?>
						<div class="item-cta d-flex align-items-center">
							<div class="item-icon d-flex align-items-center justify-content-center">
								<i><?php \Elementor\Icons_Manager::render_icon($item['icon'], ['aria-hidden' => 'true']); ?></i>
							</div>
							<div class="item-text">
								<?php echo bookora_wp_kses($item['title']); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="header-navigation d-flex align-items-center justify-content-between">
			<div class="logo-navigation d-flex align-items-center">
				<div class="brand-logo">
					<a href="<?php echo esc_url(home_url()); ?>">
						<img class="logo_site-size" src="<?php echo esc_url($settings['rzlogo']['url']); ?>" alt="<?php if (!empty($settings['rzlogo']['alt'])) {
							   echo esc_attr($settings['rzlogo']['alt']);
						   } ?>">
					</a>
				</div>
				<nav class="main-navigation clearfix ul-li">
					<?php
					echo str_replace(['menu-item-has-children', 'sub-menu'], ['dropdown', 'dropdown-menu clearfix'], wp_nav_menu(array(
						'echo' => false,
						'menu' => !empty($settings['choose-menu']) ? $settings['choose-menu'] : 'menu-1',
						'menu_id' => 'main-nav',
						'menu_class' => 'nav navbar-nav clearfix',
						'container' => false,
						'fallback_cb' => 'Navwalker_Class::fallback',
						'walker' => class_exists('Rs_Mega_Menu_Walker') ? new \Rs_Mega_Menu_Walker : '',
					)));
					?>
				</nav>
			</div>
			<div class="cta-btn-wrap d-flex align-items-center">
				<?php if (!empty($settings['btn_label'])): ?>
					<a <?php echo $this->get_render_attribute_string('btn_link'); ?>><?php echo bookora_wp_kses($settings['btn_label']); ?></a>
				<?php endif; ?>
				<?php if (!empty($settings['btn_label2'])): ?>
					<a <?php echo $this->get_render_attribute_string('btn_link2'); ?>><?php echo bookora_wp_kses($settings['btn_label2']); ?></a>
				<?php endif; ?>
				<div class="bk-mobile-menu-btn mobile_menu_button open_mobile_menu">
					<button>
						<span></span>
						<span></span>
						<span></span>
					</button>
				</div>
			</div>
		</div>
	</div>
</header>
<?php $this->mobile_menu(); ?>