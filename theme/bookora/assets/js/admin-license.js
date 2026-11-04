/* Bookora theme license administration. */
(function ($) {
    'use strict';
    if (!$ || !window.theme_license_ajax) return;
    var config = window.theme_license_ajax;
    var message = $('#license-activation-message');
    function post(action, data) {
        message.text('Working…');
        return $.ajax({
            url: config.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: $.extend({action: action, nonce: config.nonce}, data || {})
        }).done(function (response) {
            if (response && response.success) {
                window.location.reload();
            } else {
                message.text(response && typeof response.data === 'string' ? response.data : 'Request failed.');
            }
        }).fail(function () {
            message.text('Request failed. Please try again.');
        });
    }
    $(document).on('submit', '#theme-license-activate-form', function (event) {
        event.preventDefault();
        var key = $(this).find('[name="license_key"]').val().trim();
        if (!key) {
            message.text('Please enter a license key.');
            return;
        }
        post('activate_license', {license_key: key});
    });
    $(document).on('click', '#deactivate-license', function () {
        if (window.confirm('Deactivate this license on this site?')) post('deactivate_license');
    });
})(window.jQuery);
