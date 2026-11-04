<div class="tc-case-bg  img-cover position-relative">
    <video autoplay="" muted="" loop="" playsinline="" preload="metadata">
        <source src="
        <?php
        if ($settings['video_type'] == 'exu') {
            echo esc_url($settings['video_link']['url']);
        } else {
            echo esc_url($settings['video_upload']['url']);
        }
        ?>
        " type="video/mp4">
    </video>
    <?php if (!empty($settings['title'])): ?>
        <div class="item-text top_view_2 headline">
            <h2><?php echo bookora_wp_kses($settings['title']); ?></h2>
        </div>
    <?php endif; ?>
</div>