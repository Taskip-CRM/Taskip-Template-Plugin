/**
 * Taskip Templates Block Editor Scripts
 */
(function(wp) {
    'use strict';

    const { registerBlockStyle, unregisterBlockStyle } = wp.blocks;
    const { registerPlugin } = wp.plugins;
    const { PluginDocumentSettingPanel } = wp.editPost;
    const { PanelBody, PanelRow, TextControl, ToggleControl, Button } = wp.components;
    const { useSelect, useDispatch } = wp.data;
    const { useState, useEffect } = wp.element;
    const { addFilter } = wp.hooks;

    // Register custom block styles when DOM is ready
    wp.domReady(function() {
        // Add custom button style
        registerBlockStyle('core/button', {
            name: 'taskip',
            label: 'Taskip Button',
        });

        // Add custom paragraph style for features
        registerBlockStyle('core/paragraph', {
            name: 'taskip-feature',
            label: 'Template Feature',
        });

        // Add custom group style for template sections
        registerBlockStyle('core/group', {
            name: 'taskip-section',
            label: 'Template Section',
        });

        // Register custom block patterns
        if (wp.blocks.registerBlockPattern) {
            // Features section pattern
            wp.blocks.registerBlockPattern(
                'taskip/template-features',
                {
                    title: 'Template Features Section',
                    description: 'Adds a section to highlight template features',
                    categories: ['taskip-template-blocks'],
                    content: `
                    <!-- wp:group {"className":"taskip-features-section","style":{"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"},"margin":{"top":"30px","bottom":"30px"}},"border":{"radius":"8px"}},"backgroundColor":"light-gray"} -->
                    <div class="wp-block-group taskip-features-section has-light-gray-background-color has-background" style="border-radius:8px;margin-top:30px;margin-bottom:30px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px">
                        <!-- wp:heading {"level":3} -->
                        <h3>Key Features</h3>
                        <!-- /wp:heading -->
                        
                        <!-- wp:list -->
                        <ul>
                            <li>Feature one description</li>
                            <li>Feature two description</li>
                            <li>Feature three description</li>
                        </ul>
                        <!-- /wp:list -->
                        
                        <!-- wp:paragraph -->
                        <p>Add more details about your template features here.</p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                    `,
                }
            );

            // Call-to-action pattern
            wp.blocks.registerBlockPattern(
                'taskip/template-cta',
                {
                    title: 'Template Call to Action',
                    description: 'Adds a call-to-action section for your template',
                    categories: ['taskip-template-blocks'],
                    content: `
                    <!-- wp:group {"className":"taskip-cta-section","style":{"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"},"margin":{"top":"40px","bottom":"40px"}},"border":{"radius":"8px"},"color":{"background":"#f0f5ff"}}} -->
                    <div class="wp-block-group taskip-cta-section has-background" style="background-color:#f0f5ff;border-radius:8px;margin-top:40px;margin-bottom:40px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px">
                        <!-- wp:heading {"textAlign":"center"} -->
                        <h2 class="has-text-align-center">Ready to Use This Template?</h2>
                        <!-- /wp:heading -->
                        
                        <!-- wp:paragraph {"align":"center"} -->
                        <p class="has-text-align-center">Get started with Taskip today and make your business documents look professional.</p>
                        <!-- /wp:paragraph -->
                        
                        <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                        <div class="wp-block-buttons">
                            <!-- wp:button {"className":"is-style-taskip"} -->
                            <div class="wp-block-button is-style-taskip"><a class="wp-block-button__link">Get Started</a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
                    `,
                }
            );
        }
    });

    // Create a custom sidebar plugin to manage template metadata
    const TemplateMetadataPanel = () => {
        // Get post meta using the WordPress data API
        const metaValues = useSelect((select) => {
            const editor = select('core/editor');
            return {
                previewUrl: editor.getEditedPostAttribute('meta')._taskip_preview_url || '',
                demoUrl: editor.getEditedPostAttribute('meta')._taskip_demo_url || '',
                version: editor.getEditedPostAttribute('meta')._taskip_template_version || '',
                features: editor.getEditedPostAttribute('meta')._taskip_template_features || ''
            };
        }, []);

        // Setup the dispatch function to update post meta
        const { editPost } = useDispatch('core/editor');

        // Update meta values
        const updateMetaValue = (key, value) => {
            editPost({ meta: { [key]: value } });
        };

        // Parse features string to array for easier editing
        const [featuresArray, setFeaturesArray] = useState([]);

        useEffect(() => {
            if (metaValues.features) {
                setFeaturesArray(metaValues.features.split('\n').filter(line => line.trim() !== ''));
            }
        }, [metaValues.features]);

        // Add a new feature
        const addFeature = () => {
            const newFeatures = [...featuresArray, 'New feature'];
            setFeaturesArray(newFeatures);
            updateMetaValue('_taskip_template_features', newFeatures.join('\n'));
        };

        // Update a feature
        const updateFeature = (index, value) => {
            const newFeatures = [...featuresArray];
            newFeatures[index] = value;
            setFeaturesArray(newFeatures);
            updateMetaValue('_taskip_template_features', newFeatures.join('\n'));
        };

        // Remove a feature
        const removeFeature = (index) => {
            const newFeatures = featuresArray.filter((_, i) => i !== index);
            setFeaturesArray(newFeatures);
            updateMetaValue('_taskip_template_features', newFeatures.join('\n'));
        };

        return (
            <PluginDocumentSettingPanel
                name="taskip-template-metadata"
                title="Template Details"
                className="taskip-template-metadata"
            >
                <PanelRow>
                    <TextControl
                        label="Preview URL"
                        value={metaValues.previewUrl}
                        onChange={(value) => updateMetaValue('_taskip_preview_url', value)}
                        help="URL for template preview image (alternative to featured image)"
                    />
                </PanelRow>

                <PanelRow>
                    <TextControl
                        label="Demo URL"
                        value={metaValues.demoUrl}
                        onChange={(value) => updateMetaValue('_taskip_demo_url', value)}
                        help="URL to view a live demo of the template"
                    />
                </PanelRow>

                <PanelRow>
                    <TextControl
                        label="Version"
                        value={metaValues.version}
                        onChange={(value) => updateMetaValue('_taskip_template_version', value)}
                        help="Version number of the template"
                    />
                </PanelRow>

                <PanelBody title="Template Features" initialOpen={false}>
                    {featuresArray.map((feature, index) => (
                        <div key={index} style={{ marginBottom: '10px', display: 'flex' }}>
                            <TextControl
                                value={feature}
                                onChange={(value) => updateFeature(index, value)}
                                style={{ flex: 1 }}
                            />
                            <Button
                                isDestructive
                                onClick={() => removeFeature(index)}
                                icon="trash"
                            />
                        </div>
                    ))}

                    <Button
                        isPrimary
                        onClick={addFeature}
                    >
                        Add Feature
                    </Button>
                </PanelBody>
            </PluginDocumentSettingPanel>
        );
    };

    // Register the plugin
    registerPlugin('taskip-template-metadata', {
        render: TemplateMetadataPanel,
        icon: 'media-document'
    });

})(window.wp);