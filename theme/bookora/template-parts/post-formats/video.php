<?php

if (get_post_meta(get_the_ID(), 'bookora_post_format_meta', true)) {
    $post_video_meta = get_post_meta(get_the_ID(), 'bookora_post_format_meta', true);
} else {
    $post_video_meta = array();
}

if (array_key_exists('video_link', $post_video_meta)) {
    $video_link = $post_video_meta['video_link'];
} else {
    $video_link = '';
}

?>
<div class="item-img position-relative">
    <div class="inner-img">
        <?php the_post_thumbnail('bookora-img-size-1', ['class' => 'img-fluid']); ?>
    </div>
    <div class="sn-vd5-play">
        <div class="video-play-btn">
            <a class="sn_play_btn video_box" href="<?php echo esc_url($video_link); ?>">
                <i class="fa-solid fa-play"></i>
                <span class="video_btn_border border_wrap-1"></span>
                <span class="video_btn_border border_wrap-2"></span>
                <span class="video_btn_border border_wrap-3"></span>
            </a>
        </div>
    </div>
</div>