<?php


/**
 * Preloader
 *
 * @return  [type]  [return description]
 */
function bookora_preloader()
{
    $preloader_enable = cs_get_option('preloader_enable');
    $preloader_logo = cs_get_option('preloader_logo');
    $preloader_logo_shape = cs_get_option('preloader_logo_shape');

    if ($preloader_enable == true):
        ?>
        <div id="preloader">
            <div class="preloader-logo">
                <?php if (!empty($preloader_logo['url'])): ?>
                    <img src="<?php echo esc_url($preloader_logo['url']); ?>"
                        alt="<?php esc_attr_e('Preloader Logo', 'appriver'); ?>">
                <?php else: ?>
                    <svg width="241" height="250" viewBox="0 0 241 250" xmlns="http://www.w3.org/2000/svg">
                        <path color="#004040"
                            d="M41.5961 109.386V142.389L102.3 204.904L146.087 250H0V204.904H43.8893L41.5961 202.542L0 159.704V2.52051L40.5922 44.3237L41.596 45.3584L52.5212 56.6084L95.7674 101.147L124.262 130.492L167.507 175.027L196.517 204.904L240.305 250H178.134L134.345 204.904L108.74 178.534C108.739 178.534 108.739 178.532 108.739 178.532L49.9039 117.943L49.9021 117.941L41.5961 109.386Z" />

                        <path color="#C29D68"
                            d="M220.793 91.0934C220.793 120.429 207.503 146.526 186.861 163.192L152.228 127.526C166.141 120.379 175.715 105.437 175.715 88.1703C175.715 63.9545 156.895 44.3238 133.676 44.3238H71.4376L63.6191 36.2723L41.5917 13.5859L28.3984 0H135.843C166.691 0.876226 193.547 18.4271 208.422 44.3238C216.279 57.9951 220.793 73.9938 220.793 91.0934Z" />
                    </svg>
                <?php endif; ?>
            </div>
        </div>
        <?php
    endif;
}

function bookora_scroll_up_btn()
{
    $scroll_up_btn = cs_get_option('scroll_up_btn');

    if ($scroll_up_btn == true):
        ?>
        <div class="bk-up">
            <a href="#" class="bk-scrollup text-center"><?php esc_html_e('TOP', 'bookora') ?></a>
        </div>
        <?php
    endif;
}

/**
 * bookora Post Loop
 *
 * @return  [type]  [return description]
 */
function bookora_post_loop()
{
    if (have_posts()):

        /* Start the Loop */
        while (have_posts()):
            the_post();

            /*
             * Include the Post-Type-specific template for the content.
             * If you want to override this in a child theme, then include a file
             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
             */
            get_template_part('template-parts/content', get_post_type());

        endwhile; ?>
        <!-- pagination -->
        <div class="sn-pagination text-center ul-li">
            <?php
            bookora_pagination(
                '
                    <i class="fa-solid fa-angles-left"></i>',
                '<i class="fa-solid fa-angles-right"></i>',
                '',
                ['class' => '']
            );
            ?>
        </div>



    <?php else:

        get_template_part('template-parts/content', 'none');

    endif;
}

/**
 * Single Post Loop
 *
 * @return  [type]  [return description]
 */
function bookora_single_post_loop()
{
    while (have_posts()):
        the_post();

        get_template_part('template-parts/content', 'single');
    endwhile; // End of the loop.
}

/**
 * Archive Loop
 *
 * @return  [type]  [return description]
 */
function bookora_post_archive_loop()
{
    if (have_posts()):
        /* Start the Loop */
        while (have_posts()):
            the_post();

            /*
             * Include the Post-Type-specific template for the content.
             * If you want to override this in a child theme, then include a file
             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
             */
            get_template_part('template-parts/content', get_post_type());

        endwhile; ?>

        <div class="ct-pagination">
            <?php
            bookora_pagination(
                '
                    <i class="fa-solid fa-angles-left feh-pagination-icon"></i>',
                '<i class="fa-solid fa-angles-right feh-pagination-icon"></i>',
                '',
                ['class' => '']
            );
            ?>
        </div>

    <?php else:

        get_template_part('template-parts/content', 'none');

    endif;
}

/**
 * Search Loop
 *
 * @return  [type]  [return description]
 */
function bookora_search_loop()
{
    if (have_posts()):

        /* Start the Loop */
        while (have_posts()):
            the_post();

            /**
             * Run the loop for the search to output the results.
             * If you want to overload this in a child theme then include a file
             * called content-search.php and that will be used instead.
             */
            get_template_part('template-parts/content', 'search');

        endwhile; ?>
        <div class="ct-pagination">
            <?php
            bookora_pagination(
                '
                    <i class="fa-solid fa-angles-left feh-pagination-icon"></i>',
                '<i class="fa-solid fa-angles-right feh-pagination-icon"></i>',
                '',
                ['class' => '']
            );
            ?>
        </div>

    <?php else:

        get_template_part('template-parts/content', 'none');

    endif;
}

/**
 * Page Loop
 *
 * @return  [type]  [return description]
 */
function bookora_page_loop()
{
    while (have_posts()):
        the_post();

        get_template_part('template-parts/content', 'page');

        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()):
            comments_template();
        endif;

    endwhile; // End of the loop.
}
