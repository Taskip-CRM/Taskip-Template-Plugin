/**
 * Taskip Templates Showcase Frontend Scripts
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Initialize template filters
        initTemplateFilters();

        // Initialize image lightbox
        initImageLightbox();
    });

    /**
     * Initialize template filters
     */
    function initTemplateFilters() {
        var $filterForm = $('.taskip-filter-form');

        if ($filterForm.length) {
            // Handle filter change
            $filterForm.find('select').on('change', function() {
                $filterForm.submit();
            });

            // Handle mobile filter toggle
            $('.taskip-filter-toggle').on('click', function(e) {
                e.preventDefault();
                $('.taskip-templates-filters').slideToggle();
                $(this).toggleClass('active');
            });
        }
    }

    /**
     * Initialize image lightbox
     */
    function initImageLightbox() {
        // Create lightbox container if it doesn't exist
        if ($('#taskip-lightbox').length === 0) {
            $('body').append(
                '<div id="taskip-lightbox" class="taskip-lightbox">' +
                '<div class="taskip-lightbox-backdrop"></div>' +
                '<div class="taskip-lightbox-content">' +
                '<img src="" class="taskip-lightbox-image" alt="Template Preview">' +
                '<button type="button" class="taskip-lightbox-close">&times;</button>' +
                '</div>' +
                '</div>'
            );

            // Add CSS for lightbox
            var lightboxStyles =
                '.taskip-lightbox { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; }' +
                '.taskip-lightbox-backdrop { position: fixed; width: 100%; height: 100%; background-color: rgba(0,0,0,0.85); }' +
                '.taskip-lightbox-content { position: relative; max-width: 90%; max-height: 90vh; margin: 2% auto; }' +
                '.taskip-lightbox-image { display: block; width: 100%; height: auto; max-height: 90vh; object-fit: contain; }' +
                '.taskip-lightbox-close { position: absolute; top: -40px; right: 0; color: #fff; background: none; border: none; font-size: 30px; cursor: pointer; }';

            $('<style>').text(lightboxStyles).appendTo('head');
        }

        // Click event for template images
        $('.taskip-main-image, .taskip-template-thumb').on('click', function(e) {
            e.preventDefault();

            var imgSrc = $(this).attr('src');
            $('.taskip-lightbox-image').attr('src', imgSrc);
            $('#taskip-lightbox').fadeIn();
        });

        // Close lightbox
        $(document).on('click', '.taskip-lightbox-close, .taskip-lightbox-backdrop', function() {
            $('#taskip-lightbox').fadeOut();
        });

        // Close lightbox on ESC key
        $(document).keyup(function(e) {
            if (e.key === "Escape") {
                $('#taskip-lightbox').fadeOut();
            }
        });
    }

})(jQuery);