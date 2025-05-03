/**
 * Taskip Templates Showcase Admin Scripts
 */
(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        // Initialize media uploader for taxonomy images
        initTaxonomyImageUploader();

        // Initialize template features meta box
        initTemplateFeaturesMetaBox();
    });

    /**
     * Initialize media uploader for taxonomy images
     */
    function initTaxonomyImageUploader() {
        // Media uploader for taxonomy image
        var mediaUploader;

        // Upload button click
        $(document).on('click', '.taxonomy-image-upload', function(e) {
            e.preventDefault();

            var button = $(this);
            var imageField = button.siblings('.taxonomy-image-field');
            var imagePreview = $('#taxonomy-image-preview');

            // If the media uploader already exists, open it
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Create the media uploader
            mediaUploader = wp.media({
                title: 'Select Category Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            // When an image is selected, run a callback
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                imageField.val(attachment.id);

                if (imagePreview.length) {
                    if (imagePreview.find('img').length) {
                        imagePreview.find('img').attr('src', attachment.url);
                    } else {
                        imagePreview.html('<img src="' + attachment.url + '" alt="" style="max-width: 100%; height: auto;">');
                    }
                    imagePreview.show();
                    button.siblings('.taxonomy-image-remove').show();
                }
            });

            // Open the uploader dialog
            mediaUploader.open();
        });

        // Remove button click
        $(document).on('click', '.taxonomy-image-remove', function(e) {
            e.preventDefault();

            var button = $(this);
            var imageField = button.siblings('.taxonomy-image-field');
            var imagePreview = $('#taxonomy-image-preview');

            imageField.val('');
            imagePreview.empty();
            button.hide();
        });
    }

    /**
     * Initialize template features meta box
     */
    function initTemplateFeaturesMetaBox() {
        var $featuresBox = $('#taskip_template_features');

        if ($featuresBox.length) {
            // Add feature button
            $featuresBox.after('<div class="taskip-meta-buttons"><button type="button" class="button button-secondary taskip-add-feature">Add Feature</button></div>');

            // Add feature click
            $('.taskip-add-feature').on('click', function(e) {
                e.preventDefault();
                var currentFeatures = $featuresBox.val();
                var newFeature = 'New feature';

                if (currentFeatures) {
                    $featuresBox.val(currentFeatures + '\n' + newFeature);
                } else {
                    $featuresBox.val(newFeature);
                }

                // Focus and position cursor at the end
                $featuresBox.focus();
                var textLength = $featuresBox.val().length;
                $featuresBox[0].setSelectionRange(textLength, textLength);
            });
        }
    }

})(jQuery);