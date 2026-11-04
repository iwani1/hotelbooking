<?php
if (get_post_meta(get_the_ID(), 'bookora_post_gall_meta', true)) {
    $post_gall_meta = get_post_meta(get_the_ID(), 'bookora_post_gall_meta', true);
} else {
    $post_gall_meta = array();
}

if (array_key_exists('post-gall-item', $post_gall_meta)) {
    $gallerys = $post_gall_meta['post-gall-item'];
} else {
    $gallerys = '';
}
$gallery_ids = explode(',', $gallerys);
?>


<div class="gt-blog-img position-relative">
    <div class="blog-item-img-slide swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($gallery_ids as $gall): ?>
                <div class="swiper-slide">
                    <div class="slide-item-img">
                        <img src="<?php echo esc_url(wp_get_attachment_url($gall)); ?>"
                            alt="<?php esc_attr_e('Gallery', 'bookora'); ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="blog-item-arrow">
            <div
                class="gt-blog-button-prev log-blog-button-prev nav-arrow d-flex justify-content-center align-items-center">
                <i class="fas fa-long-arrow-left"></i>
            </div>
            <div
                class="gt-blog-button-next log-blog-button-next nav-arrow d-flex justify-content-center align-items-center">
                <i class="fas fa-long-arrow-right"></i>
            </div>
        </div>
    </div>
</div>