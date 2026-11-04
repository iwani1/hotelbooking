<div class="tc-why-c-list mt-35 ul-li">
    <ul>
        <?php foreach ($settings['lists'] as $item): ?>
            <li>
                <i><img src="<?php echo esc_url($item['img']['url']); ?>" alt="<?php if (!empty($item['img']['alt'])) {
                       echo esc_attr($item['img']['alt']);
                   } else {
                       esc_attr_e('List', 'bookora-plugin');
                   } ?>"></i>
                <?php echo bookora_wp_kses($item['title']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>