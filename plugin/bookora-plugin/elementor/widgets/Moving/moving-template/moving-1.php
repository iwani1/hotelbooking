<div class="jr-texts-1">
    <div class="jr-texts-wrap marquee-left headline">
        <?php foreach ($settings['movings'] as $item): ?>
            <h2><?php echo bookora_wp_kses($item['title']); ?></h2>
            <i class="fa-solid fa-minus"></i>
        <?php endforeach; ?>
    </div>
</div>