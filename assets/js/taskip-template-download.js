jQuery(document).ready(function($) {
    'use strict';

    // Handle all download buttons on the page
    $(document).on('click', '.taskip-download-btn', function(e) {
        e.preventDefault();

        var $btn = $(this);
        var modalId = $btn.attr('id');
        var $modal = $('#taskip-modal-' + modalId);

        if ($modal.length) {
            $modal.fadeIn(300);
            // Focus on first input
            setTimeout(function() {
                $modal.find('input[type="text"]').first().focus();
            }, 350);
        }
    });

    // Handle close button clicks
    $(document).on('click', '.taskip-close', function() {
        var targetId = $(this).data('target');
        $('#taskip-modal-' + targetId).fadeOut(300);
    });

    // Handle modal background clicks
    $(document).on('click', '.taskip-modal', function(e) {
        if (e.target === this) {
            $(this).fadeOut(300);
        }
    });

    // Handle escape key
    $(document).on('keydown', function(e) {
        if (e.keyCode === 27) { // Escape key
            $('.taskip-modal:visible').fadeOut(300);
        }
    });

    // Handle form submissions
    $(document).on('submit', '.taskip-download-form', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $submitBtn = $form.find('.taskip-submit-btn');
        var $btnText = $submitBtn.find('.taskip-btn-text');
        var $btnLoading = $submitBtn.find('.taskip-btn-loading');
        var formId = $form.attr('id');
        var buttonId = formId.replace('taskip-form-', '');
        var $downloadBtn = $('#' + buttonId);

        // Validate form
        if (!validateForm($form)) {
            return;
        }

        // Show loading state
        $btnText.hide();
        $btnLoading.show();
        $submitBtn.prop('disabled', true);

        // Prepare form data
        var formData = {
            action: 'taskip_process_template_download',
            name: $form.find('[name="name"]').val().trim(),
            email: $form.find('[name="email"]').val().trim(),
            consent: $form.find('[name="consent"]').prop('checked') ? '1' : '0',
            template_id: $downloadBtn.data('template-id'),
            nonce: taskip_ajax.nonce
        };

        // Send AJAX request
        $.ajax({
            url: taskip_ajax.ajax_url,
            type: 'POST',
            data: formData,
            timeout: 30000, // 30 seconds timeout
            success: function(response) {
                if (response.success) {
                    // Close modal with animation
                    $form.closest('.taskip-modal').fadeOut(300);

                    // Show success message
                    showSuccessMessage('Download starting...');

                    // Open download in new tab after short delay
                    setTimeout(function() {
                        window.open(response.data.download_url, '_blank');
                    }, 500);

                    // Reset form
                    $form[0].reset();

                    // Track download event (optional analytics)
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'download', {
                            'event_category': 'Template',
                            'event_label': $downloadBtn.data('template-title'),
                            'value': 1
                        });
                    }
                } else {
                    showErrorMessage(response.data.message || 'An error occurred. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);

                if (status === 'timeout') {
                    showErrorMessage('Request timed out. Please check your connection and try again.');
                } else {
                    showErrorMessage('Network error. Please try again.');
                }
            },
            complete: function() {
                // Hide loading state
                $btnText.show();
                $btnLoading.hide();
                $submitBtn.prop('disabled', false);
            }
        });
    });

    // Form validation function
    function validateForm($form) {
        var isValid = true;
        var $nameField = $form.find('[name="name"]');
        var $emailField = $form.find('[name="email"]');
        var $consentField = $form.find('[name="consent"]');

        // Remove previous error states
        $form.find('.taskip-error').removeClass('taskip-error');
        $form.find('.taskip-error-message').remove();

        // Validate name
        if (!$nameField.val().trim()) {
            showFieldError($nameField, 'Please enter your name');
            isValid = false;
        } else if ($nameField.val().trim().length < 2) {
            showFieldError($nameField, 'Name must be at least 2 characters');
            isValid = false;
        }

        // Validate email
        var email = $emailField.val().trim();
        if (!email) {
            showFieldError($emailField, 'Please enter your email');
            isValid = false;
        } else if (!isValidEmail(email)) {
            showFieldError($emailField, 'Please enter a valid email address');
            isValid = false;
        }

        // Validate consent
        if (!$consentField.prop('checked')) {
            showFieldError($consentField.closest('.taskip-consent'), 'Please accept the consent to continue');
            isValid = false;
        }

        return isValid;
    }

    // Email validation function
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Show field error
    function showFieldError($field, message) {
        $field.addClass('taskip-error');
        $field.closest('.taskip-form-group').append('<div class="taskip-error-message" style="color: #ff4757; font-size: 12px; margin-top: 5px;">' + message + '</div>');
    }

    // Show success message
    function showSuccessMessage(message) {
        showNotification(message, 'success');
    }

    // Show error message
    function showErrorMessage(message) {
        showNotification(message, 'error');
    }

    // Generic notification function
    function showNotification(message, type) {
        var bgColor = type === 'success' ? '#2ecc71' : '#ff4757';
        var $notification = $('<div class="taskip-notification">' + message + '</div>');

        $notification.css({
            position: 'fixed',
            top: '20px',
            right: '20px',
            background: bgColor,
            color: 'white',
            padding: '15px 20px',
            borderRadius: '8px',
            zIndex: 10000,
            boxShadow: '0 4px 15px rgba(0,0,0,0.2)',
            fontSize: '14px',
            fontWeight: '600',
            opacity: 0,
            transform: 'translateX(100%)',
            transition: 'all 0.3s ease'
        });

        $('body').append($notification);

        // Animate in
        setTimeout(function() {
            $notification.css({
                opacity: 1,
                transform: 'translateX(0)'
            });
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(function() {
            $notification.css({
                opacity: 0,
                transform: 'translateX(100%)'
            });
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 5000);

        // Click to dismiss
        $notification.on('click', function() {
            $(this).css({
                opacity: 0,
                transform: 'translateX(100%)'
            });
            setTimeout(function() {
                $notification.remove();
            }, 300);
        });
    }

    // Real-time email validation
    $(document).on('blur', '.taskip-download-form input[type="email"]', function() {
        var $field = $(this);
        var email = $field.val().trim();

        if (email && !isValidEmail(email)) {
            $field.addClass('taskip-error');
        } else {
            $field.removeClass('taskip-error');
        }
    });

    // Remove error state on input
    $(document).on('input', '.taskip-download-form input', function() {
        $(this).removeClass('taskip-error');
        $(this).closest('.taskip-form-group').find('.taskip-error-message').remove();
    });

    // Remove consent error when checked
    $(document).on('change', '.taskip-download-form input[type="checkbox"]', function() {
        $(this).closest('.taskip-consent').removeClass('taskip-error');
        $(this).closest('.taskip-form-group').find('.taskip-error-message').remove();
    });
});