/**
 * jquery.singlePageNav.js
 *
 * Minimal smooth-scroll single-page navigation for menu anchors.
 * Handles elements carrying the `is-one-page` class and honours the
 * optional `data-onepage-offset` attribute (in px).
 */
(function ($) {
    'use strict';

    if (!$) {
        return;
    }

    $(document).ready(function () {
        $('html, body').css({ 'scroll-behavior': 'smooth' });

        $(document).on('click', '.is-one-page', function (e) {
            var $link = $(this);
            var target = $link.attr('href');

            if (!target || target.charAt(0) !== '#' || target === '#') {
                return;
            }

            var $section = $(target);
            if (!$section.length) {
                return;
            }

            e.preventDefault();

            var offset = parseInt($link.attr('data-onepage-offset'), 10);
            if (isNaN(offset) || offset < 0) {
                offset = 0;
            }

            $('html, body').animate({
                scrollTop: $section.offset().top + offset
            }, 1000, function () {
                if (window.history && window.history.pushState) {
                    window.history.pushState(null, '', target);
                } else {
                    window.location.hash = target;
                }
            });
        });
    });
})(window.jQuery || window.Zepto);
