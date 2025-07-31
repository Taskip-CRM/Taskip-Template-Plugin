jQuery(document).ready(function ($) {
    'use strict';

        // Store unique ID for this instance
        var uniqueId = 'template_____download_now_button';

        // Button click handler taskip_template_____download_now_button
        $('#taskip_' + uniqueId).on('click', function () {
            $('#taskip-modal-' + uniqueId).fadeIn(300);
        });

        // Close modal handler
        $('.taskip-close[data-target="' + uniqueId + '"]').on('click', function () {
            $('#taskip-modal-' + uniqueId).fadeOut(300);
        });

        // Close modal when clicking outside
        $('#taskip-modal-' + uniqueId).on('click', function (e) {
            if (e.target === this) {
                $(this).fadeOut(300);
            }
        });

        // Form submission handler
        $('#taskip-form-' + uniqueId).on('submit', function (e) {
            e.preventDefault();

            var $form = $(this);
            var $submitBtn = $form.find('.taskip-submit-btn');
            var $btnText = $submitBtn.find('.taskip-btn-text');
            var $btnLoading = $submitBtn.find('.taskip-btn-loading');

            // Show loading state
            $btnText.hide();
            $btnLoading.show();
            $submitBtn.prop('disabled', true);

            // Get form data
            var formData = {
                action: 'taskip_process_template_download',
                name: $form.find('[name="name"]').val(),
                email: $form.find('[name="email"]').val(),
                consent: $form.find('[name="consent"]').prop('checked') ? '1' : '0',
                template_id: $('#taskip_' + uniqueId).data('template-id'),
                nonce: taskip_ajax.nonce
            };

            // Send AJAX request
            $.ajax({
                url: taskip_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        // Close modal
                        $('#taskip-modal-' + uniqueId).fadeOut(300);

                        // Open download in new tab
                        window.open(response.data.download_url, '_blank');

                        // Reset form
                        $form[0].reset();

                        // Show success message (optional)
                        if (response.data.message) {
                            // alert(response.data.message);
                        }
                    } else {
                        alert('Error: ' + response.data.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('An error occurred. Please try again.');
                },
                complete: function () {
                    // Hide loading state
                    $btnText.show();
                    $btnLoading.hide();
                    $submitBtn.prop('disabled', false);
                }
            });
        });
});