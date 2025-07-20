<?php
/**
 * Taxonomies class file.
 *
 * @package Taskip Templates Showcase
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Taxonomies class
 */
class Taskip_FluentCRM_Api {

    private string $api_base_url = 'https://marketing.xgenious.com/wp-json/fluent-crm/v2/';
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize properties
    }

    // Background FluentCRM processing via Official REST API
    public function taskip_add_to_fluentcrm_background($name, $email, $template_id) {
        // Configuration for FluentCRM REST API

        $username = get_option('taskip_fluentcrm_username', ''); // FluentCRM username
        $password = get_option('taskip_fluentcrm_password', ''); // FluentCRM password/app password

        if (empty($username) || empty($password)) {
            error_log('FluentCRM API credentials not configured. Please set taskip_fluentcrm_username and taskip_fluentcrm_password options.');
            return;
        }

        try {
            // Get template information
            $template_title = get_the_title($template_id);
            $template_url = get_permalink($template_id);
            $source_website = get_site_url();
            $source_domain = parse_url($source_website, PHP_URL_HOST);

            // Prepare contact data according to FluentCRM API documentation
            $contact_data = array(
                'email' => $email,
                'first_name' => $name,
                'status' => 'subscribed',
                'lists' => array(23), // Add to list ID 23 as specified
                'tags' => array('taskip-download', 'template-download', $source_domain),
                'custom_values' => array(
                    'downloaded_template' => $template_title,
                    'template_url' => $template_url,
                    'download_date' => current_time('Y-m-d H:i:s'),
                    'source_website' => $source_website,
                    'source_domain' => $source_domain
                )
            );

            // Create/update contact using FluentCRM REST API
            $response = $this->taskip_fluentcrm_api_request(
                $this->api_base_url . 'subscribers',
                'POST',
                $contact_data,
                $username,
                $password
            );

            if ($response['success']) {
                $contact_id = $response['data']['contact']['id'] ?? null;
                error_log('Contact successfully added to remote FluentCRM: ' . $email . ' (ID: ' . $contact_id . ')');

                // Optionally, you can also explicitly add to list 23 if not already added
                if ($contact_id) {
                    $this->taskip_add_contact_to_list($this->api_base_url, $contact_id, 23, $username, $password);
                }
            } else {
                error_log('Failed to add contact to remote FluentCRM: ' . $response['error']);
            }

        } catch (Exception $e) {
            error_log('Remote FluentCRM Error: ' . $e->getMessage());
        }
    }

    // Add contact to specific list (list ID 23)
    function taskip_add_contact_to_list( $contact_id, $list_id, $username, $password) {
        $list_data = array(
            'contact_ids' => array($contact_id)
        );

        $response = $this->taskip_fluentcrm_api_request(
            $this->api_base_url . 'lists/' . $list_id . '/contacts',
            'POST',
            $list_data,
            $username,
            $password
        );

        if ($response['success']) {
            error_log('Contact ' . $contact_id . ' successfully added to list ' . $list_id);
        } else {
            error_log('Failed to add contact ' . $contact_id . ' to list ' . $list_id . ': ' . $response['error']);
        }

        return $response;
    }

    // FluentCRM API request function using Basic Authentication
    public function taskip_fluentcrm_api_request($url, $method = 'GET', $data = array(), $username = '', $password = '') {
        // Prepare headers with Basic Authentication
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($username . ':' . $password),
            'User-Agent' => 'Taskip-Download-Plugin/1.0'
        );

        // Prepare WordPress HTTP API arguments
        $args = array(
            'method' => $method,
            'headers' => $headers,
            'timeout' => 30,
            'sslverify' => true
        );

        // Add body data for POST/PUT requests
        if (in_array($method, array('POST', 'PUT', 'PATCH')) && !empty($data)) {
            $args['body'] = json_encode($data);
        }

        // Add query parameters for GET requests
        if ($method === 'GET' && !empty($data)) {
            $url = add_query_arg($data, $url);
        }

        // Make the request
        $response = wp_remote_request($url, $args);

        // Handle errors
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'error' => 'HTTP Error: ' . $response->get_error_message()
            );
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);

        // Parse JSON response
        $parsed_response = json_decode($response_body, true);

        if ($response_code >= 200 && $response_code < 300) {
            return array(
                'success' => true,
                'data' => $parsed_response,
                'status_code' => $response_code
            );
        } else {
            $error_message = 'API Error (HTTP ' . $response_code . ')';

            if ($parsed_response && isset($parsed_response['message'])) {
                $error_message .= ': ' . $parsed_response['message'];
            } elseif ($parsed_response && isset($parsed_response['error'])) {
                $error_message .= ': ' . $parsed_response['error'];
            } elseif ($parsed_response && isset($parsed_response['errors'])) {
                $error_message .= ': ' . json_encode($parsed_response['errors']);
            } else {
                $error_message .= ': ' . $response_body;
            }

            return array(
                'success' => false,
                'error' => $error_message,
                'status_code' => $response_code
            );
        }
    }

     // Test function to verify FluentCRM API connection
    public function taskip_test_fluentcrm_connection() {
        $username = get_option('taskip_fluentcrm_username', '');
        $password = get_option('taskip_fluentcrm_password', '');

        if (empty($username) || empty($password)) {
            return array(
                'success' => false,
                'error' => 'FluentCRM credentials not configured'
            );
        }

        // Test with a simple API call to get lists
        $response = $this->taskip_fluentcrm_api_request(
            $this->api_base_url . 'lists',
            'GET',
            array('per_page' => 1),
            $username,
            $password
        );

        if ($response['success']) {
            // Check if list 23 exists
            $lists_response = $this->taskip_fluentcrm_api_request(
                $this->api_base_url . 'lists/23',
                'GET',
                array(),
                $username,
                $password
            );

            if ($lists_response['success']) {
                $list_name = $lists_response['data']['list']['title'] ?? 'Unknown';
                return array(
                    'success' => true,
                    'message' => 'FluentCRM API connection successful! Target list: ' . $list_name . ' (ID: 23)'
                );
            } else {
                return array(
                    'success' => false,
                    'error' => 'API connected but list ID 23 not found: ' . $lists_response['error']
                );
            }
        } else {
            return array(
                'success' => false,
                'error' => 'FluentCRM API connection failed: ' . $response['error']
            );
        }
    }

    // Get specific list information (for debugging)
    public function taskip_get_fluentcrm_list_info($list_id = 23) {
        $username = get_option('taskip_fluentcrm_username', '');
        $password = get_option('taskip_fluentcrm_password', '');

        if (empty($username) || empty($password)) {
            return array('success' => false, 'error' => 'Credentials not configured');
        }

        $response = $this->taskip_fluentcrm_api_request(
            $this->api_base_url . 'lists/' . $list_id,
            'GET',
            array(),
            $username,
            $password
        );

        return $response;
    }

}