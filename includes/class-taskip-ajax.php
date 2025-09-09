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
class Taskip_Ajax {

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize properties
    }

    /**
     * Initialize taxonomies
     */
    public function initialize() {
        add_action('wp_ajax_taskip_process_template_download', [$this,'taskip_process_template_download']);
        add_action('wp_ajax_nopriv_taskip_process_template_download', [$this,'taskip_process_template_download']);
    }
    function taskip_process_template_download() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'taskip_download_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed'));
        }

        // Sanitize input
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $consent = sanitize_text_field($_POST['consent']);
        $template_id = (int)$_POST['template_id'];

        // Validate required fields
        if (empty($name) || empty($email) || $consent !== '1') {
            wp_send_json_error(array('message' => 'Please fill in all required fields and accept the consent.'));
        }

        // Validate email
        if (!is_email($email)) {
            wp_send_json_error(array('message' => 'Please enter a valid email address.'));
        }

        // Check for spam/temporary email
        if ($this->taskip_is_spam_email($email)) {
            wp_send_json_error(array('message' => 'Please use a valid business email address.'));
        }

        // Get file URL from template meta

        $file_url = $this->taskip_get_template_download_url($template_id,'docx');//get_post_meta($template_id, '_taskip_preview_url', true);

        if (empty($file_url)) {
            wp_send_json_error(array('message' => 'Download file not found.'));
        }


        //todo:: work on download url

        /* Need to reimplement fluent crm background process  */
        $taskip_flutent_crm = new Taskip_FluentCRM_Api();
        $taskip_flutent_crm->taskip_add_to_fluentcrm_background($name, $email, $template_id);


        // Send immediate response with download URL
        wp_send_json_success(array(
            'download_url' => $file_url,
            'message' => 'Download started successfully!'
        ));

    }

    // Email spam filter
    public function taskip_is_spam_email($email) {
        // Common temporary email domains
        $temp_domains = array(
            '10minutemail.com',
            'guerrillamail.com',
            'mailinator.com',
            'tempmail.org',
            'temp-mail.org',
            'throwaway.email',
            'yopmail.com',
            '0-mail.com',
            'fake.email',
            'dispostable.com',
            'trashmail.com',
            'maildrop.cc',
            'sharklasers.com',
            'guerrillamail.info',
            'guerrillamail.biz',
            'guerrillamail.com',
            'guerrillamail.de',
            'guerrillamail.net',
            'guerrillamail.org',
            'spam4.me',
            'grr.la',
            'guerrillamail.biz',
            'guerrillamailblock.com',
            'pokemail.net',
            'spamgourmet.com',
            'suremail.info',
            'trbvm.com',
            'vomoto.com',
            'vzmail.com',
            'armyspy.com',
            'cuvox.de',
            'dayrep.com',
            'einrot.com',
            'fleckens.hu',
            'gustr.com',
            'jourrapide.com',
            'mailzilla.com',
            'superrito.com',
            'teleworm.us',
            'rhyta.com',
            'mburger.sk',
            'freemail.hu',
            'op.pl',
            'onet.pl',
            'interia.pl',
            'gazeta.pl',
            'poczta.fm',
            'tlen.pl',
            'siteground.com',
            'wpengine.com',
            'hosting.com',
            'hostgator.com',
            'godaddy.com',
            'bluehost.com',
            'example.com',
            'test.com',
            'demo.com',
            'localhost',
            'admin.com',
            'webmaster.com',
            'info.com',
            'support.com',
            'sales.com',
            'marketing.com',
            'contact.com',
            'help.com',
            'service.com',
            'noreply.com',
            'donotreply.com',
            'no-reply.com',
            'office.com',
            'business.com',
            'company.com',
            'mail.com',
            'email.com',
            'inbox.com',
            'mailbox.com',
            'postbox.com',
            'mymail.com',
            'yourdomain.com',
            'domain.com',
            'website.com',
            'site.com',
            'web.com',
            'online.com',
            'net.com',
            'org.com',
            'gov.com',
            'edu.com',
            'school.com',
            'university.com',
            'college.com',
            'student.com',
            'teacher.com',
            'staff.com',
            'faculty.com',
            'admin.edu',
            'student.edu',
            'webmail.com',
            'freemail.com',
            'freeemail.com',
            'email.net',
            'mail.net',
            'webmail.net',
            'freemail.net',
            'email.org',
            'mail.org',
            'webmail.org',
            'freemail.org',
            'nesopf.com',
            'enotj.com',
            'dextrago.com',
            'powerscrews.com',
            'bwmyga.com',
            'rapidletter.net',
            'cyclelove.cc',
            'mailshan.com',
            'necub.com',
            'photobrex.com',
            'radiant-flow.org',
            'mailsac.com',
            'dripzgaming.com',
            'binich.com',
            'haben-wir.com',
            'assurmail.net',
            'jetable.org',
            'sindwir.com',
            'vertexinbox.com'
        );

        // Get domain from email
        $domain = strtolower(substr(strrchr($email, "@"), 1));

        // Check if domain is in temp email list
        if (in_array($domain, $temp_domains)) {
            return true;
        }

        // Check for suspicious patterns
        $suspicious_patterns = array(
            '/^\d+@/',           // Starts with numbers
            '/^test@/',          // Starts with test
            '/^admin@/',         // Starts with admin
            '/^noreply@/',       // Starts with noreply
            '/^no-reply@/',      // Starts with no-reply
            '/^donotreply@/',    // Starts with donotreply
            '/^fake@/',          // Starts with fake
            '/^spam@/',          // Starts with spam
            '/^temp@/',          // Starts with temp
            '/^temporary@/',     // Starts with temporary
            '/^throwaway@/',     // Starts with throwaway
            '/^disposable@/',    // Starts with disposable
            '/^example@/',       // Starts with example
            '/^demo@/',          // Starts with demo
            '/^sample@/',        // Starts with sample
            '/^placeholder@/',   // Starts with placeholder
            '/^dummy@/',         // Starts with dummy
            '/^mock@/',          // Starts with mock
            '/^testing@/',       // Starts with testing
            '/^trial@/',         // Starts with trial
            '/^preview@/',       // Starts with preview
            '/^sandbox@/',       // Starts with sandbox
            '/^development@/',   // Starts with development
            '/^staging@/',       // Starts with staging
            '/^beta@/',          // Starts with beta
            '/^alpha@/',         // Starts with alpha
            '/^debug@/',         // Starts with debug
            '/^localhost@/',     // Starts with localhost
            '/^root@/',          // Starts with root
            '/^nobody@/',        // Starts with nobody
            '/^null@/',          // Starts with null
            '/^void@/',          // Starts with void
            '/^empty@/',         // Starts with empty
            '/^blank@/',         // Starts with blank
            '/^invalid@/',       // Starts with invalid
            '/^error@/',         // Starts with error
            '/^fail@/',          // Starts with fail
            '/^false@/',         // Starts with false
            '/^true@/',          // Starts with true
            '/^none@/',          // Starts with none
            '/^undefined@/',     // Starts with undefined
            '/^unknown@/',       // Starts with unknown
            '/^anonymous@/',     // Starts with anonymous
            '/^guest@/',         // Starts with guest
            '/^visitor@/',       // Starts with visitor
            '/^user@/',          // Starts with user
            '/^member@/',        // Starts with member
            '/^client@/',        // Starts with client
            '/^customer@/',      // Starts with customer
            '/^buyer@/',         // Starts with buyer
            '/^seller@/',        // Starts with seller
            '/^owner@/',         // Starts with owner
            '/^manager@/',       // Starts with manager
            '/^director@/',      // Starts with director
            '/^ceo@/',           // Starts with ceo
            '/^president@/',     // Starts with president
            '/^vice@/',          // Starts with vice
            '/^assistant@/',     // Starts with assistant
            '/^secretary@/',     // Starts with secretary
            '/^clerk@/',         // Starts with clerk
            '/^agent@/',         // Starts with agent
            '/^representative@/', // Starts with representative
            '/^consultant@/',    // Starts with consultant
            '/^advisor@/',       // Starts with advisor
            '/^specialist@/',    // Starts with specialist
            '/^expert@/',        // Starts with expert
            '/^professional@/',  // Starts with professional
            '/^technician@/',    // Starts with technician
            '/^developer@/',     // Starts with developer
            '/^programmer@/',    // Starts with programmer
            '/^designer@/',      // Starts with designer
            '/^analyst@/',       // Starts with analyst
            '/^engineer@/',      // Starts with engineer
            '/^architect@/',     // Starts with architect
            '/^coordinator@/',   // Starts with coordinator
            '/^supervisor@/',    // Starts with supervisor
            '/^lead@/',          // Starts with lead
            '/^head@/',          // Starts with head
            '/^chief@/',         // Starts with chief
            '/^senior@/',        // Starts with senior
            '/^junior@/',        // Starts with junior
            '/^intern@/',        // Starts with intern
            '/^trainee@/',       // Starts with trainee
            '/^volunteer@/',     // Starts with volunteer
            '/^freelancer@/',    // Starts with freelancer
            '/^contractor@/',    // Starts with contractor
            '/^vendor@/',        // Starts with vendor
            '/^supplier@/',      // Starts with supplier
            '/^partner@/',       // Starts with partner
            '/^affiliate@/',     // Starts with affiliate
            '/^associate@/',     // Starts with associate
            '/^colleague@/',     // Starts with colleague
            '/^teammate@/',      // Starts with teammate
            '/^coworker@/',      // Starts with coworker
            '/^employee@/',      // Starts with employee
            '/^staff@/',         // Starts with staff
            '/^worker@/',        // Starts with worker
            '/^operator@/',      // Starts with operator
            '/^handler@/',       // Starts with handler
            '/^processor@/',     // Starts with processor
            '/^reviewer@/',      // Starts with reviewer
            '/^approver@/',      // Starts with approver
            '/^validator@/',     // Starts with validator
            '/^checker@/',       // Starts with checker
            '/^monitor@/',       // Starts with monitor
            '/^observer@/',      // Starts with observer
            '/^watcher@/',       // Starts with watcher
            '/^tracker@/',       // Starts with tracker
            '/^reporter@/',      // Starts with reporter
            '/^recorder@/',      // Starts with recorder
            '/^logger@/',        // Starts with logger
            '/^auditor@/',       // Starts with auditor
            '/^inspector@/',     // Starts with inspector
            '/^examiner@/',      // Starts with examiner
            '/^evaluator@/',     // Starts with evaluator
            '/^assessor@/',      // Starts with assessor
            '/^appraiser@/',     // Starts with appraiser
            '/^estimator@/',     // Starts with estimator
            '/^calculator@/',    // Starts with calculator
            '/^counter@/',       // Starts with counter
            '/^measurer@/',      // Starts with measurer
            '/^surveyor@/',      // Starts with surveyor
            '/^researcher@/',    // Starts with researcher
            '/^investigator@/',  // Starts with investigator
            '/^detective@/',     // Starts with detective
            '/^explorer@/',      // Starts with explorer
            '/^discoverer@/',    // Starts with discoverer
            '/^finder@/',        // Starts with finder
            '/^seeker@/',        // Starts with seeker
            '/^hunter@/',        // Starts with hunter
            '/^gatherer@/',      // Starts with gatherer
            '/^collector@/',     // Starts with collector
            '/^accumulator@/',   // Starts with accumulator
            '/^aggregator@/',    // Starts with aggregator
            '/^compiler@/',      // Starts with compiler
            '/^assembler@/',     // Starts with assembler
            '/^builder@/',       // Starts with builder
            '/^creator@/',       // Starts with creator
            '/^maker@/',         // Starts with maker
            '/^producer@/',      // Starts with producer
            '/^manufacturer@/',  // Starts with manufacturer
            '/^fabricator@/',    // Starts with fabricator
            '/^constructor@/',   // Starts with constructor
            '/^installer@/',     // Starts with installer
            '/^implementer@/',   // Starts with implementer
            '/^executor@/',      // Starts with executor
            '/^performer@/',     // Starts with performer
            '/^player@/',        // Starts with player
            '/^participant@/',   // Starts with participant
            '/^contributor@/',   // Starts with contributor
            '/^supporter@/',     // Starts with supporter
            '/^helper@/',        // Starts with helper
            '/^assistant@/',     // Starts with assistant
            '/^aide@/',          // Starts with aide
            '/^backup@/',        // Starts with backup
            '/^substitute@/',    // Starts with substitute
            '/^replacement@/',   // Starts with replacement
            '/^alternative@/',   // Starts with alternative
            '/^option@/',        // Starts with option
            '/^choice@/',        // Starts with choice
            '/^selection@/',     // Starts with selection
            '/^preference@/',    // Starts with preference
            '/^favorite@/',      // Starts with favorite
            '/^top@/',           // Starts with top
            '/^best@/',          // Starts with best
            '/^optimal@/',       // Starts with optimal
            '/^perfect@/',       // Starts with perfect
            '/^ideal@/',         // Starts with ideal
            '/^ultimate@/',      // Starts with ultimate
            '/^final@/',         // Starts with final
            '/^last@/',          // Starts with last
            '/^latest@/',        // Starts with latest
            '/^newest@/',        // Starts with newest
            '/^current@/',       // Starts with current
            '/^present@/',       // Starts with present
            '/^existing@/',      // Starts with existing
            '/^available@/',     // Starts with available
            '/^accessible@/',    // Starts with accessible
            '/^usable@/',        // Starts with usable
            '/^functional@/',    // Starts with functional
            '/^operational@/',   // Starts with operational
            '/^active@/',        // Starts with active
            '/^live@/',          // Starts with live
            '/^online@/',        // Starts with online
            '/^connected@/',     // Starts with connected
            '/^linked@/',        // Starts with linked
            '/^attached@/',      // Starts with attached
            '/^joined@/',        // Starts with joined
            '/^combined@/',      // Starts with combined
            '/^merged@/',        // Starts with merged
            '/^integrated@/',    // Starts with integrated
            '/^unified@/',       // Starts with unified
            '/^consolidated@/',  // Starts with consolidated
            '/^centralized@/',   // Starts with centralized
            '/^concentrated@/',  // Starts with concentrated
            '/^focused@/',       // Starts with focused
            '/^targeted@/',      // Starts with targeted
            '/^directed@/',      // Starts with directed
            '/^guided@/',        // Starts with guided
            '/^controlled@/',    // Starts with controlled
            '/^managed@/',       // Starts with managed
            '/^supervised@/',    // Starts with supervised
            '/^overseen@/',      // Starts with overseen
            '/^monitored@/',     // Starts with monitored
            '/^watched@/',       // Starts with watched
            '/^observed@/',      // Starts with observed
            '/^tracked@/',       // Starts with tracked
            '/^followed@/',      // Starts with followed
            '/^pursued@/',       // Starts with pursued
            '/^chased@/',        // Starts with chased
            '/^hunted@/',        // Starts with hunted
            '/^sought@/',        // Starts with sought
            '/^searched@/',      // Starts with searched
            '/^explored@/',      // Starts with explored
            '/^investigated@/',  // Starts with investigated
            '/^researched@/',    // Starts with researched
            '/^studied@/',       // Starts with studied
            '/^examined@/',      // Starts with examined
            '/^inspected@/',     // Starts with inspected
            '/^reviewed@/',      // Starts with reviewed
            '/^analyzed@/',      // Starts with analyzed
            '/^evaluated@/',     // Starts with evaluated
            '/^assessed@/',      // Starts with assessed
            '/^appraised@/',     // Starts with appraised
            '/^estimated@/',     // Starts with estimated
            '/^calculated@/',    // Starts with calculated
            '/^computed@/',      // Starts with computed
            '/^processed@/',     // Starts with processed
            '/^handled@/',       // Starts with handled
            '/^managed@/',       // Starts with managed
            '/^operated@/',      // Starts with operated
            '/^run@/',           // Starts with run
            '/^executed@/',      // Starts with executed
            '/^performed@/',     // Starts with performed
            '/^completed@/',     // Starts with completed
            '/^finished@/',      // Starts with finished
            '/^accomplished@/',  // Starts with accomplished
            '/^achieved@/',      // Starts with achieved
            '/^attained@/',      // Starts with attained
            '/^reached@/',       // Starts with reached
            '/^obtained@/',      // Starts with obtained
            '/^acquired@/',      // Starts with acquired
            '/^gained@/',        // Starts with gained
            '/^earned@/',        // Starts with earned
            '/^won@/',           // Starts with won
            '/^secured@/',       // Starts with secured
            '/^captured@/',      // Starts with captured
            '/^caught@/',        // Starts with caught
            '/^grabbed@/',       // Starts with grabbed
            '/^seized@/',        // Starts with seized
            '/^taken@/',         // Starts with taken
            '/^received@/',      // Starts with received
            '/^accepted@/',      // Starts with accepted
            '/^approved@/',      // Starts with approved
            '/^confirmed@/',     // Starts with confirmed
            '/^verified@/',      // Starts with verified
            '/^validated@/',     // Starts with validated
            '/^certified@/',     // Starts with certified
            '/^authorized@/',    // Starts with authorized
            '/^permitted@/',     // Starts with permitted
            '/^allowed@/',       // Starts with allowed
            '/^enabled@/',       // Starts with enabled
            '/^activated@/',     // Starts with activated
            '/^initiated@/',     // Starts with initiated
            '/^started@/',       // Starts with started
            '/^begun@/',         // Starts with begun
            '/^launched@/',      // Starts with launched
            '/^introduced@/',    // Starts with introduced
            '/^presented@/',     // Starts with presented
            '/^displayed@/',     // Starts with displayed
            '/^shown@/',         // Starts with shown
            '/^demonstrated@/',  // Starts with demonstrated
            '/^exhibited@/',     // Starts with exhibited
            '/^revealed@/',      // Starts with revealed
            '/^exposed@/',       // Starts with exposed
            '/^uncovered@/',     // Starts with uncovered
            '/^disclosed@/',     // Starts with disclosed
            '/^published@/',     // Starts with published
            '/^released@/',      // Starts with released
            '/^issued@/',        // Starts with issued
            '/^distributed@/',   // Starts with distributed
            '/^delivered@/',     // Starts with delivered
            '/^sent@/',          // Starts with sent
            '/^transmitted@/',   // Starts with transmitted
            '/^transferred@/',   // Starts with transferred
            '/^moved@/',         // Starts with moved
            '/^shifted@/',       // Starts with shifted
            '/^changed@/',       // Starts with changed
            '/^modified@/',      // Starts with modified
            '/^altered@/',       // Starts with altered
            '/^adjusted@/',      // Starts with adjusted
            '/^adapted@/',       // Starts with adapted
            '/^converted@/',     // Starts with converted
            '/^transformed@/',   // Starts with transformed
            '/^evolved@/',       // Starts with evolved
            '/^developed@/',     // Starts with developed
            '/^improved@/',      // Starts with improved
            '/^enhanced@/',      // Starts with enhanced
            '/^upgraded@/',      // Starts with upgraded
            '/^updated@/',       // Starts with updated
            '/^revised@/',       // Starts with revised
            '/^corrected@/',     // Starts with corrected
            '/^fixed@/',         // Starts with fixed
            '/^repaired@/',      // Starts with repaired
            '/^restored@/',      // Starts with restored
            '/^recovered@/',     // Starts with recovered
            '/^retrieved@/',     // Starts with retrieved
            '/^returned@/',      // Starts with returned
            '/^brought@/',       // Starts with brought
            '/^carried@/',       // Starts with carried
            '/^transported@/',   // Starts with transported
            '/^conveyed@/',      // Starts with conveyed
            '/^communicated@/',  // Starts with communicated
            '/^informed@/',      // Starts with informed
            '/^notified@/',      // Starts with notified
            '/^alerted@/',       // Starts with alerted
            '/^warned@/',        // Starts with warned
            '/^advised@/',       // Starts with advised
            '/^recommended@/',   // Starts with recommended
            '/^suggested@/',     // Starts with suggested
            '/^proposed@/',      // Starts with proposed
            '/^offered@/',       // Starts with offered
            '/^provided@/',      // Starts with provided
            '/^supplied@/',      // Starts with supplied
            '/^furnished@/',     // Starts with furnished
            '/^equipped@/',      // Starts with equipped
            '/^prepared@/',      // Starts with prepared
            '/\+\d+@/',          // Contains +numbers (like email+123@domain.com)
            '/\d{10,}@/',        // Contains 10+ consecutive digits
        );

        foreach ($suspicious_patterns as $pattern) {
            if (preg_match($pattern, $email)) {
                return true;
            }
        }

        // Check for very short domains (less than 3 characters)
        if (strlen($domain) < 3) {
            return true;
        }

        // Check for domains without TLD
        if (!strpos($domain, '.')) {
            return true;
        }

        return false;
    }

// Background FluentCRM processing
    function taskip_add_to_fluentcrm_background($name, $email, $template_id) {
        // Check if FluentCRM is active
        if (!function_exists('FluentCrm\App\Api\Api')) {
            error_log('FluentCRM is not installed or activated.');
            return;
        }

        try {
            // Get FluentCRM API
            $contactApi = FluentCrm\App\Api\Api::contact();

            // Prepare contact data
            $contact_data = array(
                'email' => $email,
                'first_name' => $name,
                'status' => 'subscribed'
            );

            // Add template information as custom field
            $template_title = get_the_title($template_id);
            if ($template_title) {
                $contact_data['custom_values'] = array(
                    'downloaded_template' => $template_title,
                    'download_date' => current_time('mysql')
                );
            }

            // Check if contact already exists
            $existing_contact = $contactApi->getInstance()->where('email', $email)->first();

            if ($existing_contact) {
                // Update existing contact
                $contact = $contactApi->update($existing_contact->id, $contact_data);
            } else {
                // Create new contact
                $contact = $contactApi->create($contact_data);
            }

            // Add to specific list
            if ($contact) {
                $listApi = FluentCrm\App\Api\Api::list();

                // Try to find the list by name
                $list = $listApi->getInstance()->where('title', 'taskip-potential-client')->first();

                if (!$list) {
                    // Create the list if it doesn't exist
                    $list_data = array(
                        'title' => 'taskip-potential-client',
                        'slug' => 'taskip-potential-client',
                        'description' => 'Taskip potential clients from template downloads'
                    );

                    $list = $listApi->create($list_data);
                }

                if ($list) {
                    // Add contact to list (if not already in list)
                    if (!$contact->lists()->where('id', $list->id)->exists()) {
                        $contact->lists()->attach($list->id);
                    }

                    error_log('Contact successfully added to FluentCRM: ' . $email);
                } else {
                    error_log('Failed to create or find FluentCRM list: taskip-potential-client');
                }
            } else {
                error_log('Failed to create/update contact in FluentCRM: ' . $email);
            }

        } catch (Exception $e) {
            error_log('FluentCRM Error: ' . $e->getMessage());
        }
    }

    // Convert template URL to downloadable URL
    private function taskip_convert_to_download_url($template_url, $download_type = 'docx') {
        // Parse the URL to get components
        $parsed_url = parse_url($template_url);

        if (!$parsed_url) {
            return $template_url; // Return original if parsing fails
        }

        // Extract the path
        $path = $parsed_url['path'] ?? '';

        // Parse existing query parameters
        $query_params = array();
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $query_params);
        }

        // Build the new download URL
        $download_path = rtrim($path, '/') . '/download';

        // Add download type to query parameters
        $query_params['dtype'] = $download_type;

        // Rebuild the URL
        $download_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];

        // Add port if specified
        if (isset($parsed_url['port'])) {
            $download_url .= ':' . $parsed_url['port'];
        }

        // Add the new path
        $download_url .= $download_path;

        // Add query parameters
        if (!empty($query_params)) {
            $download_url .= '?' . http_build_query($query_params);
        }

        return $download_url;
    }

// Get download URL from template meta with automatic conversion
    public function taskip_get_template_download_url($template_id, $download_type = 'docx') {

        // If no direct download URL, get the template URL and convert it
        $template_url = get_post_meta($template_id, '_taskip_preview_url', true);

        if (empty($template_url)) {
            // If no template URL in meta, try to construct from template permalink
            $template_url = get_permalink($template_id);

            // Add default query parameters if it's a template page
            if (get_post_type($template_id) === 'taskip_template') {
                $template_url = add_query_arg('type', 'document', $template_url);
            }
        }

        // Convert to download URL
        return $this->taskip_convert_to_download_url($template_url, $download_type);
    }

}