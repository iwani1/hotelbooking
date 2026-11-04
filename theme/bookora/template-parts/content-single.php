<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package bookora
 */
$categories = get_the_category();
$tag_text = cs_get_option('tag_text');
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('fx-blog-details-content'); ?>>
    <div class="sn-b-details-content headline pera-content">
        <?php
        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'bookora'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            )
        );

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'bookora'),
                'after' => '</div>',
            )
        );
        ?>
    </div>

    <div class="art-blog-share-tag pt-30 pb-30 flex-wrap d-flex justify-content-between">
        <?php if (function_exists('bookora_entry_footer')): ?>
            <div class="art-blog-tag">
                <span><?php echo esc_html('Tags'); ?></span>
                <?php bookora_entry_footer(); ?>
            </div>
        <?php endif; ?>

        <?php if (function_exists('bookora_post_share')) {
            bookora_post_share();
        } ?>
    </div>
    <?php bookora_authore_info(); ?>
    <div class="art-b-comment-form headline">
        <?php if (comments_open() || get_comments_number()):
            comments_template();
        endif; ?>
    </div>
</div>