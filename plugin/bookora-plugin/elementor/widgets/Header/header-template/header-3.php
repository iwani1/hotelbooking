<header id="bk-header" class="bk-header-section header_style_one ver_2 txa_sticky_header">
    <div class="container">
        <div class="bk-header-content">
            <div class="header-navigation d-flex align-items-center justify-content-between">
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
    </div>
</header><?php $this->mobile_menu(); ?>