/**
 * Bookora - Theme Customizer live preview
 */
(function ($) {
    'use strict';

    if (!$ || !window.wp || !window.wp.customize) {
        return;
    }

    wp.customize('blogname', function (value) {
        value.bind(function (to) {
            $('.site-title a, .site-title').text(to);
        });
    });

    wp.customize('blogdescription', function (value) {
        value.bind(function (to) {
            $('.site-description').text(to);
        });
    });
})(window.jQuery);
