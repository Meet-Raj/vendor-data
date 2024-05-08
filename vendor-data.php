<?php
/*
Plugin Name: Custom List
Description: Custom List management plugin.
Version: 1.0
Author: Nomad Academy
*/

// Activation Hook: Create Database Table


function activate_custom_vendor_plugin() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'vendor_data';

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        country varchar(255) NOT NULL,
        area_of_practice varchar(255) NOT NULL,
        company varchar(255) NOT NULL,
        contact_information text NOT NULL,
        contact_person text NOT NULL,
        status varchar(255) NOT NULL,
        attitude text NOT NULL,
        issues text NOT NULL,
        additional_comments text NOT NULL,
        referral_code varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Table 2: Banned Vendor Data
$table_name2 = $wpdb->prefix . 'banned_vendor_data';
$sql2 = "CREATE TABLE $table_name2 (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    person_company varchar(255) NOT NULL,
    persons_name text NOT NULL,
    company text NOT NULL,
    contact_details text NOT NULL,
    reason text NOT NULL,
    PRIMARY KEY (id)
) $charset_collate;";
dbDelta($sql2);

$table_name3 = $wpdb->prefix . 'competitors_data';
$sql3 = "CREATE TABLE $table_name3 (
    id int(11) NOT NULL AUTO_INCREMENT,
    competitor_url text NOT NULL,
    company_type varchar(255) NOT NULL,
    domain_authority text NOT NULL,
    organic_traffic text NOT NULL,
    keyword text NOT NULL,
    one_year_trend varchar(255) NOT NULL,
    avg_visit_duration text NOT NULL,
    PRIMARY KEY (id)
) $charset_collate;";
dbDelta($sql3);


}
register_activation_hook(__FILE__, 'activate_custom_vendor_plugin');


function register_vendor_data_post_type() {
    register_post_type('vendor_data', array(
        'labels' => array(
            'name' => __('Vendor Data'),
            'singular_name' => __('Vendor Data'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor'), // Adjust supports as needed
        'register_meta_box_cb' => 'add_vendor_data_meta_box',
    ));

    // Custom Post Type: Another Post Type
   register_post_type('banned_vendor_data', array(
        'labels' => array(
            'name' => __('Banned Vendor Data'),
            'singular_name' => __('Banned Vendor Data'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor'), // Adjust supports as needed
        'register_meta_box_cb' => 'add_banned_vendor_data_meta_box', ));


   register_post_type('competitors_data', array(
        'labels' => array(
            'name' => __('Competitors Data'),
            'singular_name' => __('Competitors Data'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor'),     
        'register_meta_box_cb' => 'add_competitors_data_meta_box',));

}
add_action('init', 'register_vendor_data_post_type');

// Add Meta Box for Vendor Data
function add_vendor_data_meta_box() {
    add_meta_box(
        'vendor_data_fields',
        __('Vendor Data Fields'),
        'display_vendor_data_fields',
        'vendor_data',
        'normal',
        'default'
    );
}

// Display Fields in Meta Box
function display_vendor_data_fields($post) {
    // Retrieve values if they exist
    $country = get_post_meta($post->ID, 'country', true);
    $area_of_practice = get_post_meta($post->ID, 'area_of_practice', true);
    $company = get_post_meta($post->ID, 'company', true);
    $contact_information = get_post_meta($post->ID, 'contact_information', true);
    $contact_person = get_post_meta($post->ID, 'contact_person', true);
    $status = get_post_meta($post->ID, 'status', true);
    $attitude = get_post_meta($post->ID, 'attitude', true);
    $issues = get_post_meta($post->ID, 'issues', true);
    $additional_comments = get_post_meta($post->ID, 'additional_comments', true);
    $referral_code = get_post_meta($post->ID, 'referral_code', true);
     // Edit and Delete Links
    echo '<tr>';
    echo '<td colspan="2">';
    echo '</td>';
    echo '</tr>';

    // Country dropdown with flags
    $countries_with_flags = array(
       'AF' => 'Afghanistan🇦🇫',
    'AL' => 'Albania🇦🇱',
    'DZ' => 'Algeria🇩🇿',
    'AD' => 'Andorra🇦🇩',
    'AO' => 'Angola🇦🇴',
    'AG' => 'Antigua🇦🇬',
    'AR' => 'Argentina🇦🇷',
    'AM' => 'Armenia🇦🇲',
    'AU' => 'Australia🇦🇺',
    'AT' => 'Austria🇦🇹',
    'AZ' => 'Azerbaijan🇦🇿',
    'BS' => 'Bahamas🇧🇸',
    'BH' => 'Bahrain🇧🇭',
    'BD' => 'Bangladesh🇧🇩',
    'BB' => 'Barbados🇧🇧',
    'BY' => 'Belarus🇧🇾',
    'BE' => 'Belgium🇧🇪',
    'BZ' => 'Belize🇧🇿',
    'BJ' => 'Benin🇧🇯',
    'BT' => 'Bhutan🇧🇹',
    'BO' => 'Bolivia🇧🇴',
    'BA' => 'Bosnia and Herzegovina🇧🇦',
    'BW' => 'Botswana🇧🇼',
    'BR' => 'Brazil🇧🇷',
    'BN' => 'Brunei🇧🇳',
    'BG' => 'Bulgaria🇧🇬',
    'BF' => 'Burkina Faso🇧🇫',
    'BI' => 'Burundi🇧🇮',
    'KH' => 'Cambodia🇰🇭',
    'CM' => 'Cameroon🇨🇲',
    'CA' => 'Canada🇨🇦',
    'CV' => 'Cape Verde🇨🇻',
    'CF' => 'Central African Republic🇨🇫',
    'TD' => 'Chad🇹🇩',
    'CL' => 'Chile🇨🇱',
    'CN' => 'China🇨🇳',
    'CO' => 'Colombia🇨🇴',
    'KM' => 'Comoros🇰🇲',
    'CG' => 'Congo(Congo-Brazzaville)🇨🇬',
    'CD' => 'Congo(Congo-Kinshasa)🇨🇩',
    'CR' => 'Costa Rica🇨🇷',
    'HR' => 'Croatia🇭🇷',
    'CU' => 'Cuba🇨🇺',
    'CY' => 'Cyprus🇨🇾',
    'CZ' => 'Czechia(Czech Republic)🇨🇿',
    'DK' => 'Denmark🇩🇰',
    'DJ' => 'Djibouti🇩🇯',
    'DM' => 'Dominica🇩🇲',
    'DO' => 'Dominican Republic🇩🇴',
    'EC' => 'Ecuador🇪🇨',
    'EG' => 'Egypt🇪🇬',
    'SV' => 'El Salvador🇸🇻',
    'GQ' => 'Equatorial Guinea🇬🇶',
    'ER' => 'Eritrea🇪🇷',
    'EE' => 'Estonia🇪🇪',
    'ET' => 'Ethiopia🇪🇹',
    'FJ' => 'Fiji🇫🇯',
    'FI' => 'Finland🇫🇮',
    'FR' => 'France🇫🇷',
    'GA' => 'Gabon🇬🇦',
    'GM' => 'Gambia🇬🇲',
    'GE' => 'Georgia🇬🇪',
    'DE' => 'Germany🇩🇪',
    'GH' => 'Ghana🇬🇭',
    'GR' => 'Greece🇬🇷',
    'GD' => 'Grenada🇬🇩',
    'GT' => 'Guatemala🇬🇹',
    'GN' => 'Guinea🇬🇳',
    'GW' => 'Guinea-Bissau🇬🇼',
    'GY' => 'Guyana🇬🇾',
    'HT' => 'Haiti🇭🇹',
    'HN' => 'Honduras🇭🇳',
    'HU' => 'Hungary🇭🇺',
    'IS' => 'Iceland🇮🇸',
    'IN' => 'India🇮🇳',
    'ID' => 'Indonesia🇮🇩',
    'IR' => 'Iran🇮🇷',
    'IQ' => 'Iraq🇮🇶',
    'IE' => 'Ireland🇮🇪',
    'IL' => 'Israel🇮🇱',
    'IT' => 'Italy🇮🇹',
    'CI' => 'Ivory Coast🇨🇮',
    'JM' => 'Jamaica🇯🇲',
    'JP' => 'Japan🇯🇵',
    'JO' => 'Jordan🇯🇴',
    'KZ' => 'Kazakhstan🇰🇿',
    'KE' => 'Kenya🇰🇪',
    'KI' => 'Kiribati🇰🇮',
    'KP' => 'Korea North🇰🇵',
    'KR' => 'Korea South🇰🇷',
    'KW' => 'Kuwait🇰🇼',
    'KG' => 'Kyrgyzstan🇰🇬',
    'LA' => 'Laos🇱🇦',
    'LV' => 'Latvia🇱🇻',
    'LB' => 'Lebanon🇱🇧',
    'LS' => 'Lesotho🇱🇸',
    'LR' => 'Liberia🇱🇷',
    'LY' => 'Libya🇱🇾',
    'LI' => 'Liechtenstein🇱🇮',
    'LT' => 'Lithuania🇱🇹',
    'LU' => 'Luxembourg🇱🇺',
    'MK' => 'Macedonia🇲🇰',
    'MG' => 'Madagascar🇲🇬',
    'MW' => 'Malawi🇲🇼',
    'MY' => 'Malaysia🇲🇾',
    'MV' => 'Maldives🇲🇻',
    'ML' => 'Mali🇲🇱',
    'MT' => 'Malta🇲🇹',
    'MH' => 'Marshall Islands🇲🇭',
    'MR' => 'Mauritania🇲🇷',
    'MU' => 'Mauritius🇲🇺',
    'MX' => 'Mexico🇲🇽',
    'FM' => 'Micronesia🇫🇲',
    'MD' => 'Moldova🇲🇩',
    'MC' => 'Monaco🇲🇨',
    'MN' => 'Mongolia🇲🇳',
    'ME' => 'Montenegro🇲🇪',
    'MA' => 'Morocco🇲🇦',
    'MZ' => 'Mozambique🇲🇿',
    'MM' => 'Myanmar(formerly Burma)🇲🇲',
    'NA' => 'Namibia🇳🇦',
    'NR' => 'Nauru🇳🇷',
    'NP' => 'Nepal🇳🇵',
    'NL' => 'Netherlands🇳🇱',
    'NZ' => 'New Zealand🇳🇿',
    'NI' => 'Nicaragua🇳🇮',
    'NE' => 'Niger🇳🇪',
    'NG' => 'Nigeria🇳🇬',
    'NO' => 'Norway🇳🇴',
    'OM' => 'Oman🇴🇲',
    'PK' => 'Pakistan🇵🇰',
    'PW' => 'Palau🇵🇼',
    'PS' => 'Palestine State🇵🇸',
    'PA' => 'Panama🇵🇦',
    'PG' => 'Papua New Guinea🇵🇬',
    'PY' => 'Paraguay🇵🇾',
    'PE' => 'Peru🇵🇪',
    'PH' => 'Philippines🇵🇭',
    'PL' => 'Poland🇵🇱',
    'PT' => 'Portugal🇵🇹',
    'QA' => 'Qatar🇶🇦',
    'RO' => 'Romania🇷🇴',
    'RU' => 'Russia🇷🇺',
    'RW' => 'Rwanda🇷🇼',
    'KN' => 'Saint Kitts and Nevis🇰🇳',
    'LC' => 'Saint Lucia🇱🇨',
    'VC' => 'Saint Vincent and the Grenadines🇻🇨',
    'WS' => 'Samoa🇼🇸',
    'SM' => 'San Marino🇸🇲',
    'ST' => 'Sao Tome and Principe🇸🇹',
    'SA' => 'Saudi Arabia🇸🇦',
    'SN' => 'Senegal🇸🇳',
    'RS' => 'Serbia🇷🇸',
    'SC' => 'Seychelles🇸🇨',
    'SL' => 'Sierra Leone🇸🇱',
    'SG' => 'Singapore🇸🇬',
    'SK' => 'Slovakia🇸🇰',
    'SI' => 'Slovenia🇸🇮',
    'SB' => 'Solomon Islands🇸🇧',
    'SO' => 'Somalia🇸🇴',
    'ZA' => 'South Africa🇿🇦',
    'ES' => 'Spain🇪🇸',
    'LK' => 'Sri Lanka🇱🇰',
    'SD' => 'Sudan',
    'SR' => 'Suriname🇸🇷',
    'SZ' => 'Swaziland🇸🇿',
    'SE' => 'Sweden🇸🇪',
    'CH' => 'Switzerland🇨🇭',
    'SY' => 'Syria🇸🇾',
    'TW' => 'Taiwan🇹🇼',
    'TJ' => 'Tajikistan🇹🇯',
    'TZ' => 'Tanzania🇹🇿',
    'TH' => 'Thailand🇹🇭',
    'TL' => 'Timor-Leste🇹🇱',
    'PR' => 'Puerto Rico🇵🇷',
    'TG' => 'Togo🇹🇬',
    'TO' => 'Tonga🇹🇴',
    'TT' => 'Trinidad and Tobago🇹🇹',
    'TN' => 'Tunisia🇹🇳',
    'TR' => 'Turkey🇹🇷',
    'TM' => 'Turkmenistan🇹🇲',
    'TV' => 'Tuvalu🇹🇻',
    'UG' => 'Uganda🇺🇬',
    'UA' => 'Ukraine🇺🇦',
    'AE' => 'United Arab Emirates🇦🇪',
    'GB' => 'United Kingdom🇬🇧',
    'US' => 'USA🇺🇸',
    'UY' => 'Uruguay🇺🇾',
    'UZ' => 'Uzbekistan🇺🇿',
    'VU' => 'Vanuatu🇻🇺',
    'VA' => 'Vatican City🇻🇦',
    'VE' => 'Venezuela🇻🇪',
    'VN' => 'Vietnam🇻🇳',
    'YE' => 'Yemen🇾🇪',
    'ZM' => 'Zambia🇿🇲',
    'ZW' => 'Zimbabwe🇿🇼',

    );
    ?>
    <?php
$country_name_with_flag = $countries_with_flags[$country];
$combined_data = get_post_meta($post->ID, 'country', true) ?: $countries_with_flags['US']; 
list($country, $country_name_with_flag) = explode('|', $combined_data, 2);
 ?>

    <table class="form-table">
        <tr>
            <th><label for="country">Country</label></th>
         <td>
           <select id="country" name="country">
                <?php
                foreach ($countries_with_flags as $code => $name) {
                    $combined_option = $name; 
                    echo '<option value="' . esc_attr($combined_option) . '" ' . selected($combined_option, $country, false) . '>' . esc_html($name) . '</option>';
                }
                ?>
            </select>
</td>
        </tr>
          <tr>
            <th><label for="area_of_practice">Area Of Practice</label></th>
            <td><input type="text" id="area_of_practice" name="area_of_practice" value="<?php echo esc_attr($area_of_practice); ?>" size="50" /></td>
        </tr>
        <tr>
            <th><label for="company">Company</label></th>
            <td><input type="text" id="company" name="company" value="<?php echo esc_attr($company); ?>" size="50" /></td>
        </tr>
        <tr>
            <th><label for="contact_information">Contact Information</label></th>
            <td><textarea id="contact_information" name="contact_information" rows="4" cols="50"><?php echo esc_textarea($contact_information); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="contact_person">Contact Person</label></th>
            <td><input type="text" id="contact_person" name="contact_person" value="<?php echo esc_attr($contact_person); ?>" size="50" /></td>
        </tr>
        <tr>
            <th><label for="status">Status</label></th>
            <td>
               <select id="status" name="status">
    <option value="ACTIVE" <?php selected($status, 'ACTIVE'); ?>>Active</option>
    <option value="PROBLEM" <?php selected($status, 'PROBLEM'); ?>>Problem</option>
    <option value="BACKUP" <?php selected($status, 'BACKUP'); ?>>Backup</option>
</select>

            </td>
        </tr>
        <tr>
            <th><label for="attitude">Attitude</label></th>
            <td><textarea id="attitude" name="attitude" rows="4" cols="50"><?php echo esc_textarea($attitude); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="issues">Issues</label></th>
            <td><textarea id="issues" name="issues" rows="4" cols="50"><?php echo esc_textarea($issues); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="additional_comments">Additional Comments</label></th>
            <td><textarea id="additional_comments" name="additional_comments" rows="4" cols="50"><?php echo esc_textarea($additional_comments); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="referral_code">Referral Code</label></th>
            <td><input type="text" id="referral_code" name="referral_code" value="<?php echo esc_attr($referral_code); ?>" size="50" /></td>
        </tr>
    </table>

    <?php
}

// Save Meta Box Data
function save_vendor_data_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    global $wpdb;
    $table_name = $wpdb->prefix . 'vendor_data';
    $existing_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $post_id));

    $fields = array(
        'country',
        'area_of_practice',
        'company',
        'contact_information',
        'contact_person',
        'status',
        'attitude',
        'issues',
        'additional_comments',
        'referral_code',
    );

    $data = array();

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $data[$field] = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $field, $data[$field]);
        }
    }

    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;
    $compare_user_roles = array('administrator', 'portal_manager');

   foreach ($user_roles as $user_role) {
        if (in_array($user_role, $compare_user_roles)) {
            if ($existing_data) {
                if (!empty($data) && !empty($table_name)) {
                    $wpdb->update($table_name, $data, array('id' => $post_id), $format = null, $where_format = null);
                }
            } else {
                if (!empty($data) && !empty($table_name)) {
                    $data['id'] = $post_id;
                    $wpdb->insert($table_name, $data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
                }
            }
        }
    }
}

add_action('save_post', 'save_vendor_data_meta_box');


function enqueue_vendor_search_script() {
    wp_enqueue_script('vendor-search-script', plugin_dir_url(__FILE__) . 'js/vendor-search-script.js', array('jquery'), '1.0', true);
    wp_enqueue_script('competitorslist', plugin_dir_url(__FILE__) . 'js/competitorslist.js', array('jquery'), '1.0', true);
    wp_enqueue_style('bootstrap-css', plugins_url('bootstrap-4.0.0-dist/css/bootstrap.min.css', __FILE__), array(), '4.0.0');
    wp_enqueue_style('vendor-data-css', plugin_dir_url(__FILE__) . 'css/vendor-data.css', array(), '1.0');
    wp_enqueue_script('popper',  plugin_dir_url(__FILE__) . 'js/popper.min.js', array(), null, true);
    wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('popper'), null, true);
    wp_add_inline_script('vendor-search-script', 'var ajaxurl = "' . admin_url('admin-ajax.php') . '";');
    wp_add_inline_script('vendor-search-script', 'var frontendajax = { 
        ajaxurl: "' . admin_url('admin-ajax.php') . '", 
        fetch_nonce: "' . wp_create_nonce('fetch_banned_vendor_nonce') . '",
        update_nonce: "' . wp_create_nonce('update_vendor_nonce') . '",
        update__com_nonce: "' . wp_create_nonce('update_competitors_data_nonce') . '",
        fetchlist_nonce: "' . wp_create_nonce('fetch_vendorlist_nonce') . '",
        updatelist_nonce: "' . wp_create_nonce('update_vendorlisting_nonce') . '",
        add_nonce: "' . wp_create_nonce('add_new_vendor_nonce') . '",
        fetch_com_nonce: "' . wp_create_nonce('fetch_competitors_data_nonce') . '",
        add_com_nonce: "' . wp_create_nonce('add_competitors_data_nonce') . '"
    };');
}

add_action('wp_enqueue_scripts', 'enqueue_vendor_search_script');


function enqueue_memberpress_styles_nav() {
    wp_enqueue_style('bootstrap-css', plugins_url('bootstrap-4.0.0-dist/css/bootstrap.min.css', __FILE__), array(), '4.0.0');
    wp_enqueue_style('vendor-data-css', plugin_dir_url(__FILE__) . 'css/vendor-data.css', array(), '1.0');
    wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css', array(), '1.13.7');
    wp_enqueue_script('datatables-js', 'https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js', array('jquery'), '1.13.7', true);
}
add_action('mepr_account_nav', 'enqueue_memberpress_styles_nav');



function mepr_add_some_tabs($user) {
  $support_active = (isset($_GET['action']) && $_GET['action'] == 'vendor-information')?'mepr-active-nav-tab':'';
  ?>
    <span class="mepr-nav-item vendor-information <?php echo $support_active; ?>">
      <a href="<?php echo esc_url(home_url('/account/?action=vendor-information')); ?>">Vendor Information</a>
    </span>
  <?php
}
add_action('mepr_account_nav', 'mepr_add_some_tabs','130');


function mepr_add_tabs_content($action) {
    if ($action == 'vendor-information') {
        
     $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $compare_user_roles = array( 'administrator', 'portal_manager');
        foreach( $user_roles as $user_role ){
         if( in_array( $user_role, $compare_user_roles ) ){ ?>
            <button id="add-vendor-button" class="btn-green"><span class="mr-2"><i class="fas fa-plus"></i></span> Add Vendor</button>
            <button id="updateVendorlistingData" class="btn-green" style="display:none;">Update Vendor Data</button>

            <div id="add-vendor-popup" style="display: none;" class="custom-popup-modal">
                <a class="closeModal"><i class="fas fa-times"></i></a>
                <form id="add-vendor-form">
                    <a href="javascript:void(0);" class="closeModal" id="closeButton"><i class="fas fa-times"></i></a>

                    <select id="country" name="country" required>
                        <option value="Afghanistan🇦🇫">Afghanistan🇦🇫</option>
                        <option value="Albania🇦🇱">Albania🇦🇱</option>
                        <option value="Algeria🇩🇿">Algeria🇩🇿</option>
                        <option value="Andorra🇦🇩">Andorra🇦🇩</option>
                        <option value="Angola🇦🇴">Angola🇦🇴</option>
                        <option value="Antigua🇦🇬">Antigua🇦🇬</option>
                        <option value="Argentina🇦🇷">Argentina🇦🇷</option>
                        <option value="Armenia🇦🇲">Armenia🇦🇲</option>
                        <option value="Australia🇦🇺">Australia🇦🇺</option>
                        <option value="Austria🇦🇹">Austria🇦🇹</option>
                        <option value="Azerbaijan🇦🇿">Azerbaijan🇦🇿</option>
                        <option value="Bahamas🇧🇸">Bahamas🇧🇸</option>
                        <option value="Bahrain🇧🇭">Bahrain🇧🇭</option>
                        <option value="Bangladesh🇧🇩">Bangladesh🇧🇩</option>
                        <option value="Barbados🇧🇧">Barbados🇧🇧</option>
                        <option value="Belarus🇧🇾">Belarus🇧🇾</option>
                        <option value="Belgium🇧🇪">Belgium🇧🇪</option>
                        <option value="Belize🇧🇿">Belize🇧🇿</option>
                        <option value="Benin🇧🇯">Benin🇧🇯</option>
                        <option value="Bhutan🇧🇹">Bhutan🇧🇹</option>
                        <option value="Bolivia🇧🇴">Bolivia🇧🇴</option>
                        <option value="Bosnia and Herzegovina🇧🇦">Bosnia and Herzegovina🇧🇦</option>
                        <option value="Botswana🇧🇼">Botswana🇧🇼</option>
                        <option value="Brazil🇧🇷">Brazil🇧🇷</option>
                        <option value="Brunei🇧🇳">Brunei🇧🇳</option>
                        <option value="Bulgaria🇧🇬">Bulgaria🇧🇬</option>
                        <option value="Burkina Faso🇧🇫">Burkina Faso🇧🇫</option>
                        <option value="Burundi🇧🇮">Burundi🇧🇮</option>
                        <option value="Cambodia🇰🇭">Cambodia🇰🇭</option>
                        <option value="Cameroon🇨🇲">Cameroon🇨🇲</option>
                        <option value="Canada🇨🇦">Canada🇨🇦</option>
                        <option value="Cape Verde🇨🇻">Cape Verde🇨🇻</option>
                        <option value="Central African Republic🇨🇫">Central African Republic🇨🇫</option>
                        <option value="Chad🇹🇩">Chad🇹🇩</option>
                        <option value="Chile🇨🇱">Chile🇨🇱</option>
                        <option value="China🇨🇳">China🇨🇳</option>
                        <option value="Colombia🇨🇴">Colombia🇨🇴</option>
                        <option value="Comoros🇰🇲">Comoros🇰🇲</option>
                        <option value="Congo (Congo-Brazzaville)🇨🇬">Congo (Congo-Brazzaville)🇨🇬</option>
                        <option value="Congo (Congo-Kinshasa)🇨🇩">Congo (Congo-Kinshasa)🇨🇩</option>
                        <option value="Costa Rica🇨🇷">Costa Rica🇨🇷</option>
                        <option value="Croatia🇭🇷">Croatia🇭🇷</option>
                        <option value="Cuba🇨🇺">Cuba🇨🇺</option>
                        <option value="Cyprus🇨🇾">Cyprus🇨🇾</option>
                        <option value="Czechia (Czech Republic)🇨🇿">Czechia (Czech Republic)🇨🇿</option>
                        <option value="Denmark🇩🇰">Denmark🇩🇰</option>
                        <option value="Djibouti🇩🇯">Djibouti🇩🇯</option>
                        <option value="Dominica🇩🇲">Dominica🇩🇲</option>
                        <option value="Dominican Republic🇩🇴">Dominican Republic🇩🇴</option>
                        <option value="Ecuador🇪🇨">Ecuador🇪🇨</option>
                        <option value="Egypt🇪🇬">Egypt🇪🇬</option>
                        <option value="El Salvador🇸🇻">El Salvador 🇸🇻</option>
                        <option value="Equatorial Guinea🇬🇶">Equatorial Guinea 🇬🇶</option>
                        <option value="Eritrea🇪🇷">Eritrea 🇪🇷</option>
                        <option value="Estonia🇪🇪">Estonia 🇪🇪</option>
                        <option value="Ethiopia🇪🇹">Ethiopia 🇪🇹</option>
                        <option value="Fiji🇫🇯">Fiji 🇫🇯</option>
                        <option value="Finland🇫🇮">Finland 🇫🇮</option>
                        <option value="France🇫🇷">France 🇫🇷</option>
                        <option value="Gabon🇬🇦">Gabon 🇬🇦</option>
                        <option value="Gambia🇬🇲">Gambia 🇬🇲</option>
                        <option value="Georgia🇬🇪">Georgia 🇬🇪</option>
                        <option value="Germany🇩🇪">Germany 🇩🇪</option>
                        <option value="Ghana🇬🇭">Ghana 🇬🇭</option>
                        <option value="Greece🇬🇷">Greece 🇬🇷</option>
                        <option value="Grenada🇬🇩">Grenada 🇬🇩</option>
                        <option value="Guatemala🇬🇹">Guatemala 🇬🇹</option>
                        <option value="Guinea🇬🇳">Guinea 🇬🇳</option>
                        <option value="Guinea-Bissau🇬🇼">Guinea-Bissau 🇬🇼</option>
                        <option value="Guyana🇬🇾">Guyana 🇬🇾</option>
                        <option value="Haiti🇭🇹">Haiti 🇭🇹</option>
                        <option value="Honduras🇭🇳">Honduras 🇭🇳</option>
                        <option value="Hungary🇭🇺">Hungary 🇭🇺</option>
                        <option value="Iceland🇮🇸">Iceland 🇮🇸</option>
                        <option value="India🇮🇳">India 🇮🇳</option>
                        <option value="Indonesia🇮🇩">Indonesia 🇮🇩</option>
                        <option value="Iran🇮🇷">Iran 🇮🇷</option>
                        <option value="Iraq🇮🇶">Iraq 🇮🇶</option>
                        <option value="Ireland🇮🇪">Ireland 🇮🇪</option>
                        <option value="Israel🇮🇱">Israel 🇮🇱</option>
                        <option value="Italy🇮🇹">Italy 🇮🇹</option>
                        <option value="Ivory Coast🇨🇮">Ivory Coast 🇨🇮</option>
                        <option value="Jamaica🇯🇲">Jamaica 🇯🇲</option>
                        <option value="Japan🇯🇵">Japan 🇯🇵</option>
                        <option value="Jordan🇯🇴">Jordan 🇯🇴</option>
                        <option value="Kazakhstan🇰🇿">Kazakhstan 🇰🇿</option>
                        <option value="Kenya 🇰🇪">Kenya 🇰🇪</option>
                        <option value="Kiribati🇰🇮">Kiribati 🇰🇮</option>
                        <option value="Korea North🇰🇵">Korea North 🇰🇵</option>
                        <option value="Korea South🇰🇷">Korea South 🇰🇷</option>
                        <option value="Kuwait🇰🇼">Kuwait 🇰🇼</option>
                        <option value="Kyrgyzstan🇰🇬">Kyrgyzstan 🇰🇬</option>
                        <option value="Laos🇱🇦">Laos 🇱🇦</option>
                        <option value="Latvia🇱🇻">Latvia 🇱🇻</option>
                        <option value="Lebanon🇱🇧">Lebanon 🇱🇧</option>
                        <option value="Lesotho🇱🇸">Lesotho 🇱🇸</option>
                        <option value="Liberia🇱🇷">Liberia 🇱🇷</option>
                        <option value="Libya🇱🇾">Libya 🇱🇾</option>
                        <option value="Liechtenstein🇱🇮">Liechtenstein 🇱🇮</option>
                        <option value="Lithuania🇱🇹">Lithuania 🇱🇹</option>
                        <option value="Luxembourg🇱🇺">Luxembourg 🇱🇺</option>
                        <option value="Macedonia🇲🇰">Macedonia 🇲🇰</option>
                        <option value="Madagascar🇲🇬">Madagascar 🇲🇬</option>
                        <option value="Malawi🇲🇼">Malawi 🇲🇼</option>
                        <option value="Malaysia🇲🇾">Malaysia 🇲🇾</option>
                        <option value="Maldives🇲🇻">Maldives 🇲🇻</option>
                        <option value="Mali🇲🇱">Mali 🇲🇱</option>
                        <option value="Malta🇲🇹">Malta 🇲🇹</option>
                        <option value="Marshall Islands🇲🇭">Marshall Islands 🇲🇭</option>
                        <option value="Mauritania🇲🇷">Mauritania 🇲🇷</option>
                        <option value="Mauritius🇲🇺">Mauritius 🇲🇺</option>
                        <option value="Mexico🇲🇽">Mexico 🇲🇽</option>
                        <option value="Micronesia🇫🇲">Micronesia 🇫🇲</option>
                        <option value="Moldova🇲🇩">Moldova 🇲🇩</option>
                        <option value="Monaco🇲🇨">Monaco 🇲🇨</option>
                        <option value="Mongolia🇲🇳">Mongolia 🇲🇳</option>
                        <option value="Montenegro🇲🇪">Montenegro 🇲🇪</option>
                        <option value="Morocco🇲🇦">Morocco 🇲🇦</option>
                        <option value="Mozambique🇲🇿">Mozambique 🇲🇿</option>
                        <option value="Myanmar (formerly Burma)🇲🇲">Myanmar (formerly Burma) 🇲🇲</option>
                        <option value="Namibia🇳🇦">Namibia 🇳🇦</option>
                        <option value="Nauru🇳🇷">Nauru 🇳🇷</option>
                        <option value="Nepal🇳🇵">Nepal 🇳🇵</option>
                        <option value="Netherlands🇳🇱">Netherlands 🇳🇱</option>
                        <option value="New Zealand🇳🇿">New Zealand 🇳🇿</option>
                        <option value="Nicaragua🇳🇮">Nicaragua 🇳🇮</option>
                        <option value="Niger🇳🇪">Niger 🇳🇪</option>
                        <option value="Nigeria🇳🇬">Nigeria 🇳🇬</option>
                        <option value="Norway🇳🇴">Norway 🇳🇴</option>
                        <option value="Oman🇴🇲">Oman 🇴🇲</option>
                        <option value="Pakistan🇵🇰">Pakistan 🇵🇰</option>
                        <option value="Palau🇵🇼">Palau 🇵🇼</option>
                        <option value="Palestine State🇵🇸">Palestine State 🇵🇸</option>
                        <option value="Panama🇵🇦">Panama 🇵🇦</option>
                        <option value="Papua New Guinea🇵🇬">Papua New Guinea 🇵🇬</option>
                        <option value="Paraguay🇵🇾">Paraguay 🇵🇾</option>
                        <option value="Peru🇵🇪">Peru 🇵🇪</option>
                        <option value="Philippines🇵🇭">Philippines 🇵🇭</option>
                        <option value="Poland🇵🇱">Poland 🇵🇱</option>
                        <option value="Portugal🇵🇹">Portugal 🇵🇹</option>
                        <option value="Qatar🇶🇦">Qatar 🇶🇦</option>
                        <option value="Romania🇷🇴">Romania 🇷🇴</option>
                        <option value="Russia🇷🇺">Russia 🇷🇺</option>
                        <option value="Rwanda🇷🇼">Rwanda 🇷🇼</option>
                        <option value="Saint Kitts and Nevis🇰🇳">Saint Kitts and Nevis 🇰🇳</option>
                        <option value="Saint Lucia🇱🇨">Saint Lucia 🇱🇨</option>
                        <option value="Saint Vincent and the Grenadines🇻🇨">Saint Vincent and the Grenadines 🇻🇨</option>
                        <option value="Samoa🇼🇸">Samoa 🇼🇸</option>
                        <option value="San Marino🇸🇲">San Marino 🇸🇲</option>
                        <option value="Sao Tome and Principe🇸🇹">Sao Tome and Principe 🇸🇹</option>
                        <option value="Saudi Arabia🇸🇦">Saudi Arabia 🇸🇦</option>
                        <option value="Senegal🇸🇳">Senegal 🇸🇳</option>
                        <option value="Serbia🇷🇸">Serbia 🇷🇸</option>
                        <option value="Seychelles🇸🇨">Seychelles 🇸🇨</option>
                        <option value="Sierra Leone🇸🇱">Sierra Leone 🇸🇱</option>
                        <option value="Singapore🇸🇬">Singapore 🇸🇬</option>
                        <option value="Slovakia🇸🇰">Slovakia 🇸🇰</option>
                        <option value="Slovenia🇸🇮">Slovenia 🇸🇮</option>
                        <option value="Solomon Islands🇸🇧">Solomon Islands 🇸🇧</option>
                        <option value="Somalia🇸🇴">Somalia 🇸🇴</option>
                        <option value="South Africa🇿🇦">South Africa 🇿🇦</option>
                        <option value="Spain🇪🇸">Spain 🇪🇸</option>
                        <option value="Sri Lanka🇱🇰">Sri Lanka 🇱🇰</option>
                        <option value="Sudan🇸🇩">Sudan 🇸🇩</option>
                        <option value="Suriname🇸🇷">Suriname 🇸🇷</option>
                        <option value="Swaziland🇸🇿">Swaziland 🇸🇿</option>
                        <option value="Sweden🇸🇪">Sweden 🇸🇪</option>
                        <option value="Switzerland🇨🇭">Switzerland 🇨🇭</option>
                        <option value="Syria🇸🇾">Syria 🇸🇾</option>
                        <option value="Taiwan🇹🇼">Taiwan 🇹🇼</option>
                        <option value="Tajikistan🇹🇯">Tajikistan 🇹🇯</option>
                        <option value="Tanzania🇹🇿">Tanzania 🇹🇿</option>
                        <option value="Thailand🇹🇭">Thailand 🇹🇭</option>
                        <option value="Timor-Leste🇹🇱">Timor-Leste 🇹🇱</option>
                        <option value="Togo🇹🇬">Togo 🇹🇬</option>
                        <option value="Tonga🇹🇴">Tonga 🇹🇴</option>
                        <option value="Trinidad and Tobago🇹🇹">Trinidad and Tobago 🇹🇹</option>
                        <option value="Tunisia🇹🇳">Tunisia 🇹🇳</option>
                        <option value="Turkey🇹🇷">Turkey 🇹🇷</option>
                        <option value="Turkmenistan🇹🇲">Turkmenistan 🇹🇲</option>
                        <option value="Tuvalu🇹🇻">Tuvalu 🇹🇻</option>
                        <option value="Uganda🇺🇬">Uganda 🇺🇬</option>
                        <option value="Ukraine🇺🇦">Ukraine 🇺🇦</option>
                        <option value="United Arab Emirates🇦🇪">United Arab Emirates 🇦🇪</option>
                        <option value="United Kingdom🇬🇧">United Kingdom 🇬🇧</option>
                        <option value="USA🇺🇸">USA🇺🇸</option>
                        <option value="Uruguay🇺🇾">Uruguay 🇺🇾</option>
                        <option value="Uzbekistan🇺🇿">Uzbekistan 🇺🇿</option>
                        <option value="Vanuatu🇻🇺">Vanuatu 🇻🇺</option>
                        <option value="Vatican City🇻🇦">Vatican City 🇻🇦</option>
                        <option value="Venezuela🇻🇪">Venezuela 🇻🇪</option>
                        <option value="Vietnam🇻🇳">Vietnam 🇻🇳</option>
                        <option value="Puerto Rico🇵🇷">Puerto Rico🇵🇷</option>
                        <option value="Yemen🇾🇪">Yemen 🇾🇪</option>
                        <option value="Zambia🇿🇲">Zambia 🇿🇲</option>
                        <option value="Zimbabwe🇿🇼">Zimbabwe 🇿🇼</option>
                    </select>
                    
                    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

                    <tr>
                        <th><label for="multiple-select-field">Area Of Practice</label></th>
                        <td>
                           <select class="form-select" id="multiple-select-field" name="area_of_practice" data-placeholder="Choose anything" multiple>
                                        <?php
                                        $options = array(
                                            "CBI",
                                            "Tax Residence",
                                            "Digital Nomad",
                                            "Intellectual Property",
                                            "Personal Taxes",
                                            "Corporate Taxes",
                                            "Global Health Insurance",
                                            "Private Cllients",
                                            "Unknown",
                                            "IrelandIPP",
                                            "SEZC",
                                            "MM2H",
                                            "Labuan co",
                                            "Brokerage Account Opening",
                                            "Company License",
                                            "Company Incorporation",
                                            "Estate and Tax Planning",
                                            "Accounting and PR Tax Incentives",
                                            "Relocation",
                                            "Finance",
                                            "Real Estate",
                                            "Accounting Services",
                                            "Bookkeeping",
                                            "Tax Preparation",
                                            "Thailand Elite Visa",
                                            "Apostille USA",
                                            "Renunciation",
                                            "E-2 visa",
                                            "RenunciationGC",
                                            "Relinquishment",
                                            "Outsourcing",
                                            "Residence",
                                            "CBD",
                                            "CBE",
                                            "Banking",
                                            "Golden Visa",
                                            "Vital Documents",
                                            "Company Formation",
                                            "Taxes Non-Residence",
                                            "Offshore IRA",
                                            "Trust Fund Formation",
                                            "Citizenship"
                                            // Add more options as needed
                                        );
                                    foreach ($options as $option) {
                                     $trimmedOption = trim($option); // Trim leading and trailing spaces
                                     $escapedOption = htmlspecialchars($trimmedOption, ENT_QUOTES, 'UTF-8');
                                     echo '<option value="' . $escapedOption .  '">' . $escapedOption . '</option>';
                                    }
                                        ?>
                                    </select>
                        </td>
                    </tr>
                    <script>
                         document.addEventListener('DOMContentLoaded', function () {
                         var closeButton = document.getElementById('closeButton');
                         closeButton.addEventListener('click', function () {
                         location.reload();
                          }); });
                    </script>

                    <script>
                         $(document).ready(function() {
                         var options = <?php echo json_encode($options); ?>;
                         options = options.map(function(option) {
                         return { id: option.trim(), text: option.trim() };});
                         $('.form-select').select2({
                         width: "100%",
                         data: options,
                         escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.text;
        },
        templateSelection: function(data) {
            return data.text;
        }
    });

    $('.closeModal').on('click', function() {
        $('.custom-popup-modal').fadeOut();
    });
});

                    </script>

                    <label for="vendor-company">Company:</label>
                    <input type="text" id="vendor-company" name="company" required>

                    <label for="vendor-contact-info">Contact Information:</label>
                    <textarea id="vendor-contact-info" name="contact_information"></textarea>

                    <label for="vendor-contact-person">Contact Person:</label>
                    <textarea id="vendor-contact-person" name="contact_person" required></textarea>

                    <label for="status">Status:</label>
                     <select id="status" name="status" required>
                         <option value="ACTIVE">ACTIVE</option>
                         <option value="PROBLEM">PROBLEM</option>
                         <option value="BACKUP">BACKUP</option>
                    </select>
                    <label for="vendor-attitude">Attitude:</label>
                    <textarea id="vendor-attitude" name="attitude"></textarea>

                    <label for="vendor-issues">Issues:</label>
                    <textarea id="vendor-issues" name="issues"></textarea>

                    <label for="vendor-additional-comment">Additional Comment:</label>
                    <textarea id="vendor-additional-comment" name="additional_comments"></textarea>

                    <label for="vendor-referral-code">Referral Code:</label>
                    <input type="text" id="vendor-referral-code" name="referral_code">
                         <input type="hidden" name="post_ID" class="post_ID" id="post_ID">

                    <button type="submit" id="submitvendorlist">Save Vendor</button>
                    <button type="button" value="Update" style="display:none;" id="vendorlistupdate">Update</button>

                </form>
                <!-- End Form for adding a vendor -->
            </div>
        <?php } ?>  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"crossorigin="anonymous" referrerpolicy="no-referrer" />    <?php }?>
       <div class="vendor-form-wrap">
        <div id="vendor-search-form" class="form-group" style="max-width: 300px;">
            <label for="vendor-search" >Search Vendor Data:</label>
            <div class="input-group custom-input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-magnifying-glass"></i></span>
                </div>
                <input type="text" id="vendor-search" name="vendor-search" placeholder="Search">
            </div>
        </div>
        </div>

        <div id="vendor-search-results">
        </div>
       <?php  }
}
add_action('mepr_account_nav_content', 'mepr_add_tabs_content');
add_action('wp_ajax_vendor_data_search', 'vendor_data_search');
add_action('wp_ajax_nopriv_vendor_data_search', 'vendor_data_search');

function vendor_data_search() {
    $searchTerm = sanitize_text_field($_POST['searchTerm']);
    $searchFields = array(
        'country',
        'area_of_practice',
        'company',
        'contact_information',
        'contact_person',
        'status',
        'attitude',
    );

    // Perform the search query to retrieve matching vendor data
    $results = get_vendor_data_by_search($searchTerm, $searchFields);

// Output the search results
if (!empty($results)) {
    echo '<div class="card-tbl"><div class="tbl-responsive"><table id="vendor-table" class="vendor-table table table-hover custom-tbl">';

    echo '<thead class="thead-dark">';
    echo '<tr class="vendor-tr-class cus_vendor_data">';
    echo '<th class="vendor-th-class"></th>'; 

    // Add Edit and Delete headers conditionally for portal_manager role
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $compare_user_roles = array( 'administrator', 'portal_manager');
        foreach( $user_roles as $user_role ){
         if( in_array( $user_role, $compare_user_roles ) ){
        echo '<th class="vendor-th-class">Edit</th>';
        echo '<th class="vendor-th-class">Delete</th>';
    }
  }

    // Specify the fields you want to display
    $fieldsToDisplay = array(
        'country',
        'area_of_practice',
        'company',
        'contact_information',
        'contact_person',
        'status',
        'attitude',
        'issues',
        'additional_comments',
        'referral_code',
    );

    foreach ($fieldsToDisplay as $field) {
        echo '<th class="vendor-th-class ' . esc_html($field) . '">' . esc_html($field) . '</th>';
    }

    echo '</tr>';
    echo '</thead>';

    foreach ($results as $result) {
        echo '<tr class="vendor-tr-class cus_vendor_data">';
        echo '<td class="vendor-td-class details-control"><i id="new_play" class="fas fa-play"></i></td>';
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $compare_user_roles = array( 'administrator', 'portal_manager');
        foreach( $user_roles as $user_role ){
         if( in_array( $user_role, $compare_user_roles ) ){
            echo '<td class="vendor-td-class"><a href="#" class="edit-vendor" data-post-id="' . esc_attr($result->ID) . '"><i class="far fa-pen-to-square"></i> Edit</a></td>';
            echo '<td class="vendor-td-class"><a href="#" class="delete-vendor" data-post-id="' . esc_attr($result->ID) . '"><i class="far fa-trash-can"></i>Delete</a></td>';
        }
    }

        foreach ($fieldsToDisplay as $field) {
            echo '<td class="vendor-td-class ' . esc_html($field) . '" data-title="">' . esc_html(get_post_meta($result->ID, $field, true)) . '</td>';
        }


        echo '</tr>';
    }

    echo '</table></div></div></div>';
} else {
    echo '<p>No results found.</p>';
}
wp_die();
}

function get_vendor_data_by_search($searchTerm, $searchFields) {
    $args = array(
        'post_type' => 'vendor_data',
        'posts_per_page' => -1, 
        'meta_query' => array(
            'relation' => 'OR',
        ),
    );

    foreach ($searchFields as $field) {
        $args['meta_query'][] = array(
            'key' => $field,
            'value' => $searchTerm,
            'compare' => 'LIKE',
        );
    }

    $query = new WP_Query($args);
    return $query->posts;
}


// AJAX handler for fetching vendor data by post ID
function fetch_vendor_data() {
    check_ajax_referer('fetch_vendorlist_nonce', 'nonce');

    $post_id = intval($_POST['post_id']);
    $data = array(
        'country' => get_post_meta($post_id, 'country', true),
        'area_of_practice' => get_post_meta($post_id, 'area_of_practice', true),
        'company' => get_post_meta($post_id, 'company', true),
        'contact_information' => get_post_meta($post_id, 'contact_information', true),
        'contact_person' => get_post_meta($post_id, 'contact_person', true),
        'status' => get_post_meta($post_id, 'status', true),
        'attitude' => get_post_meta($post_id, 'attitude', true),
        'issues' => get_post_meta($post_id, 'issues', true),
        'additional_comments' => get_post_meta($post_id, 'additional_comments', true),
        'referral_code' => get_post_meta($post_id, 'referral_code', true),
    );

    echo json_encode($data);
    wp_die();
}

add_action('wp_ajax_fetch_vendor_data', 'fetch_vendor_data');

// Update Vendor Data
add_action('wp_ajax_update_vendor_data', 'update_vendor_data');
function update_vendor_data() {
    check_ajax_referer('update_vendorlisting_nonce', 'nonce');

    parse_str($_POST['form_data'], $form_data);
    $post_id = intval($_POST['post_id']);

    $areas = $_POST['area_of_practice'];
    $area_of_practice = implode(', ', $areas);

    if (current_user_can('edit_post', $post_id)) {
        $updated_post = array(
            'ID'           => $post_id,
            'post_title'   => sanitize_text_field($form_data['contact_person']),
            'post_status'  => 'publish',
            'post_type'    => 'vendor_data',
        );

        $updated_post_id = wp_update_post($updated_post);

        // Update post meta fields
        update_post_meta($post_id, 'country', $form_data['country']);
        update_post_meta($post_id, 'area_of_practice', $area_of_practice);
        update_post_meta($post_id, 'company', $form_data['company']);
        update_post_meta($post_id, 'contact_information', $form_data['contact_information']);
        update_post_meta($post_id, 'contact_person', $form_data['contact_person']);
        update_post_meta($post_id, 'status', $form_data['status']);
        update_post_meta($post_id, 'attitude', $form_data['attitude']);
        update_post_meta($post_id, 'issues', $form_data['issues']);
        update_post_meta($post_id, 'additional_comments', $form_data['additional_comments']);
        update_post_meta($post_id, 'referral_code', $form_data['referral_code']);

        if ($updated_post_id) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('error' => 'Error updating vendor data'));
        }
    } else {
        echo json_encode(array('error' => 'Unauthorized'));
    }

    wp_die();
}


add_action('wp_ajax_delete_vendor_data_frontend', 'delete_vendor_data_frontend');

function delete_vendor_data_frontend() {
    $postId = intval($_POST['post_id']);
    error_log('Attempting to delete post with ID: ' . $postId);

         $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $compare_user_roles = array( 'administrator', 'portal_manager');
        foreach( $user_roles as $user_role ){
         if( in_array( $user_role, $compare_user_roles ) ){
        $result = wp_delete_post($postId, true);
        if ($result) {
            error_log('Post deleted successfully.');
        } else {
            error_log('Error deleting post.');
        }
    }
}
    wp_die();
}

// Add AJAX action for adding vendor data
add_action('wp_ajax_add_vendor_data', 'add_vendor_data');
add_action('wp_ajax_nopriv_add_vendor_data', 'add_vendor_data');

function add_vendor_data() {
    $country = sanitize_text_field($_POST['country']);
    $area_of_practice = isset($_POST['area_of_practice']) ? array_map('sanitize_text_field', explode(', ', $_POST['area_of_practice'])) : array();
    $company = sanitize_text_field($_POST['company']);
    $contact_info = sanitize_text_field($_POST['contact_info']);
    $contact_person = sanitize_text_field($_POST['contact_person']);
    $status = sanitize_text_field($_POST['status']);
    $attitude = sanitize_text_field($_POST['attitude']);
    $additional_comments = sanitize_text_field($_POST['comments']);
    $issues = sanitize_text_field($_POST['issues']);
    $referral_code = sanitize_text_field($_POST['referral_code']);

    // Create an array of data to be inserted into the database
    $vendor_data = array(
        'country' => $country,
        'area_of_practice' => $area_of_practice,
        'company' => $company,
        'contact_information' => $contact_info,
        'contact_person' => $contact_person,
        'status' => $status,
        'attitude' => $attitude,
        'additional_comments' => $additional_comments,
        'issues' => $issues,
        'referral_code' => $referral_code,
    );

    // Insert the data into the database
    $inserted = wp_insert_post(array(
        'post_type' => 'vendor_data',
        'post_status' => 'publish', // Adjust the post status as needed
        'post_title' => $company, // You can set the post title as the company name
        'meta_input' => $vendor_data,
    ));

    if ($inserted) {
        echo json_encode(array('success' => true, 'message' => 'Vendor data added successfully.'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error adding vendor data.'));
    }
    wp_die();
}


// Add Meta Box for Banned Vendor Data
function add_banned_vendor_data_meta_box() {
    add_meta_box(
        'banned_vendor_data_meta_box',
        __('Banned Vendor Data Details'),
        'render_banned_vendor_data_meta_box',
        'banned_vendor_data',
        'normal',
        'default'
    );
}


function render_banned_vendor_data_meta_box($post) {
    
    wp_nonce_field('banned_vendor_data_nonce', 'banned_vendor_data_nonce');
    $person_company = get_post_meta($post->ID, 'person_company', true);
    $persons_name = get_post_meta($post->ID, 'persons_name', true);
    $b_company = get_post_meta($post->ID, 'company', true);
    $contact_details = get_post_meta($post->ID, 'contact_details', true);
    $reason = get_post_meta($post->ID, 'reason', true);
    ?>
    <p>
        <label for="person_company">Person/Company:</label>
        <select name="person_company" id="person_company">
            <option value="Person" <?php selected($person_company, 'Person'); ?>>Person</option>
            <option value="Company" <?php selected($person_company, 'Company'); ?>>Company</option>
        </select>
    </p>
    <p>
        <label for="persons_name">Person's Name:</label>
        <input type="text" name="persons_name" id="persons_name" value="<?php echo esc_attr($persons_name); ?>">
    </p>
    <p>
        <label for="company">Company:</label>
        <textarea name="company" id="company"><?php echo esc_textarea($b_company); ?></textarea>
    </p>
    <p>
        <label for="contact_details">Contact Details:</label>
        <textarea name="contact_details" id="contact_details"><?php echo esc_textarea($contact_details); ?></textarea>
    </p>
    <p>
        <label for="reason">Reason:</label>
        <textarea name="reason" id="reason"><?php echo esc_textarea($reason); ?></textarea>
    </p>
    <?php
}

// Save Meta Box Data
function save_banned_vendor_data_meta($post_id) {
    // Check if it's an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!isset($_POST['banned_vendor_data_nonce']) || !wp_verify_nonce($_POST['banned_vendor_data_nonce'], 'banned_vendor_data_nonce')) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if ('banned_vendor_data' !== get_post_type($post_id)) {
        return;
    }

    // Update or insert data in the table
    global $wpdb;
    $table_name2 = $wpdb->prefix . 'banned_vendor_data';

    $banned_data = array(
        'person_company' => isset($_POST['person_company']) ? sanitize_text_field($_POST['person_company']) : '', // Ensure the value is set
        'persons_name' => sanitize_text_field($_POST['persons_name']),
        'company' => sanitize_text_field($_POST['company']),
        'contact_details' => sanitize_text_field($_POST['contact_details']),
        'reason' => sanitize_text_field($_POST['reason']),
    );

    $banned_existing_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name2 WHERE id = %d", $post_id));

     update_post_meta($post_id, 'person_company', $_POST['person_company']);
     update_post_meta($post_id, 'persons_name', $_POST['persons_name']);
     update_post_meta($post_id, 'company', $_POST['company']);
     update_post_meta($post_id, 'contact_details', $_POST['contact_details']);
     update_post_meta($post_id, 'reason', $_POST['reason']);

    if ($banned_existing_data) {
        $wpdb->update($table_name2, $banned_data, array('id' => $post_id), $format = null, $where_format = null);
    } else {
        $banned_data['id'] = $post_id;
        $wpdb->insert($table_name2, $banned_data, array('%d', '%s', '%s', '%s', '%s', '%s'));
    }
}

add_action('save_post', 'save_banned_vendor_data_meta');

function custom_banned_vendor_tabs($user) {
    $bannervendor_active = (isset($_GET['action']) && $_GET['action'] == 'banned-vendor') ? 'mepr-active-nav-tab' : '';
    ?>
    <span class="mepr-nav-item custom-vendor-information <?php echo $bannervendor_active; ?>">
        <a href="<?php echo esc_url(home_url('/?action=banned-vendor')); ?>">Banned List</a>
    </span>
    <?php
}
add_action('mepr_account_nav', 'custom_banned_vendor_tabs');

function custom_mepr_banned_vendor_tabs_content($action) {
    if ($action == 'banned-vendor') :
         $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $compare_user_roles = array( 'administrator', 'portal_manager');
        foreach( $user_roles as $user_role ){
         if( in_array( $user_role, $compare_user_roles ) ){ 
        ?>
<button id="openVendorForm" class="btn-green"><span class="mr-2"><i class="fas fa-plus"></i></span> Add Banned Data </button>
<button id="updateVendorData" class="btn-green" style="display:none;">Update Vendor Data</button>

<div id="vendorFormContainer" style="display:none;" class="custom-popup-modal">
    <form id="vendorForm">
        <a href="javascript:void(0);" class="closeModal" id="closeButton"><i class="fas fa-times"></i></a>
         <label for="personCompany">Person/Company:</label>
    <select name="person_company" id="personCompany">
        <option value="Person">Person</option>
        <option value="Company">Company</option>
    </select>

    <label for="personsName">Person's Name:</label>
    <input type="text" name="persons_name" id="personsName">

    <label for="company">Company:</label>
    <textarea name="company" id="company"></textarea>

    <label for="contactDetails">Contact Details:</label>
    <textarea name="contact_details" id="contactDetails"></textarea>

    <label for="reason">Reason:</label>
    <textarea name="reason" id="reason"></textarea>
     <input type="hidden" name="post_ID" class="post_ID" id="post_ID">
     <button type="button" value="Submit" id="submitVendorForm">Submit</button>

    <button type="button" value="Update" style="display:none;" id="updateVendorDataButton">Update</button>

    </form>
</div>

<!-- Popup for Success Message -->
<div id="successPopup" style="display:none;">
    <p id="successMessage"></p>
</div>
 <script>
                         document.addEventListener('DOMContentLoaded', function () {
                         var closeButton = document.getElementById('closeButton');
                         closeButton.addEventListener('click', function () {
                         location.reload();
                          }); });
                    </script>
<script>
    jQuery(document).ready(function ($) {
        // Show/hide form on button click
        $('#openVendorForm').on('click', function () {
            $('#vendorFormContainer').toggle();
        });
           $('.closeModal').on( 'click', function (){
            $('.custom-popup-modal').fadeOut();
        });
           

        // Handle form submission
        $('#submitVendorForm').on('click', function () {
            var formData = $('#vendorForm').serialize();

            $.ajax({
                type: 'POST',
                url: frontendajax.ajaxurl,
                data: {
                    action: 'add_new_vendor_data',
                    nonce: '<?php echo wp_create_nonce('add_new_vendor_nonce'); ?>',
                    form_data: formData,
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Close the form
                        $('#vendorFormContainer').hide();

                        // Show success popup
                        location.reload();
                        $('#successMessage').text('Your banned data is added.');
                        $('#successPopup').show();

                        // Hide success popup after 5 seconds
                        setTimeout(function () {
                            $('#successPopup').hide();
                        }, 5000);
                    } else {
                        // Handle error if needed
                        console.log('Error adding vendor data');

                    }
                }
            });
        });

        // Close popup on button click
        $('#closePopup').on('click', function () {
            $('#successPopup').hide();
        });
    });
</script>
<script>
    var frontendajax = {
        ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>'
    };
</script>

        <?php } } ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"crossorigin="anonymous" referrerpolicy="no-referrer" />
        <div class="custom-vendor-form-wrap">
            <div id="custom-vendor-search-form" class="form-group" style="max-width: 300px;">
                <label for="custom-vendor-search">Banned List:</label>
                <div class="input-group custom-input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-magnifying-glass"></i></span>
                    </div>
                    <input type="text" id="custom-vendor-search" name="custom-vendor-search" placeholder="Search">
                </div>
            </div>
        </div>

        <div id="custom-vendor-search-results"></div>

       <script>
            jQuery(document).ready(function ($) {
                var typingTimer;
                var doneTypingInterval = 1000; // 1 second

                $('#custom-vendor-search').on('input', function () {
                    clearTimeout(typingTimer);
                    typingTimer = setTimeout(function () {
                        var searchTerm = $('#custom-vendor-search').val();
                        if (searchTerm.length >= 4) {
                            $('#custom-vendor-search-results').html('<div class="loading-spinner"></div>');
                            $.ajax({
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                type: 'POST',
                                data: {
                                    action: 'search_banned_vendor_in_post_type',
                                    search_term: searchTerm,
                                    nonce: '<?php echo wp_create_nonce('search_banned_vendor_nonce'); ?>'
                                },
                                success: function (response) {
                                    $('#custom-vendor-search-results').html(response);

                                    // Add confirmation popup for delete action
                                    $('.delete-vendor').on('click', function (e) {
                                        e.preventDefault();
                                        var postId = $(this).data('post-id');
                                        var confirmDelete = confirm('Are you sure you want to delete this entry?');
                                        if (confirmDelete) {
                                            // Proceed with the delete action
                                            $.ajax({
                                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                                type: 'POST',
                                                data: {
                                                    action: 'delete_banned_vendor_entry',
                                                    post_id: postId,
                                                    nonce: '<?php echo wp_create_nonce('delete_banned_vendor_nonce'); ?>'
                                                },
                                                success: function (deleteResponse) {
                                                    // Handle success, e.g., remove the deleted row from the table
                                                    alert('Entry deleted successfully.');
                                                                location.reload();

                                                    // You may need to update the UI to reflect the deletion.
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        } else {
                            $('#custom-vendor-search-results').html('Enter at least 4 characters.');
                        }
                    }, doneTypingInterval);
                });
            });
        </script>
        <?php
    endif;
}
add_action('mepr_account_nav_content', 'custom_mepr_banned_vendor_tabs_content');

// AJAX handler for searching banned vendors in custom post type
function search_banned_vendor_in_post_type() {
    check_ajax_referer('search_banned_vendor_nonce', 'nonce');

    $search_term = sanitize_text_field($_POST['search_term']);
    if (strlen($search_term) >= 4) {
        $query_args = array(
            'post_type' => 'banned_vendor_data',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'person_company',
                    'value' => $search_term,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'persons_name',
                    'value' => $search_term,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'company',
                    'value' => $search_term,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'contact_details',
                    'value' => $search_term,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'reason',
                    'value' => $search_term,
                    'compare' => 'LIKE',
                ),
            ),
        );

        $results = new WP_Query($query_args);

        if ($results->have_posts()) {
            echo '<div class="card-tbl"><div class="tbl-responsive"><table class="banned-vendor-table table table-hover custom-tbl">';
            echo '<thead class="thead-dark">';            
            echo '<tr class="vendor-tr-class">';
           $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $compare_user_roles = array( 'administrator', 'portal_manager');
        foreach( $user_roles as $user_role ){
         if( in_array( $user_role, $compare_user_roles ) ){
            echo '<th class="vendor-th-class">Edit</th>';
            echo '<th class="vendor-th-class">Delete</th>'; }}
            echo '<th>Person/Company</th>';
            echo '<th>Person\'s Name</th>';
            echo '<th>Company</th>';
            echo '<th>Contact Details</th>';
            echo '<th>Reason</th>';
            echo '</tr>';
            echo '</head>';

            echo '<tbody>';
            while ($results->have_posts()) {
                $results->the_post();
                $post_id = get_the_ID();
                 $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $compare_user_roles = array( 'administrator', 'portal_manager');
        foreach( $user_roles as $user_role ){
         if( in_array( $user_role, $compare_user_roles ) ){
                    echo '<tr class="banned-vendor-row">';
                    echo '<td class="vendor-td-class"><a href="#" class="edit-vendor-banned" data-post-id="' . esc_attr($post_id) . '"><i class="far fa-pen-to-square"></i> Edit</a></td>';
                    echo '<td class="vendor-td-class"><a href="#" class="delete-vendor" data-post-id="' . esc_attr($post_id) . '"><i class="far fa-trash-can"></i>Delete</a></td>';
                } else {
                    // If the user is not a portal_manager, do not show edit and delete links
                    echo '<tr class="banned-vendor-row">';
                   
                } }

                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'person_company', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'persons_name', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'company', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'contact_details', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'reason', true)) . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table></div></div>';
            wp_reset_postdata();
        } else {
            echo 'No results found.';
        }
    } else {
        echo 'Enter at least 4 characters.';
    }

    wp_die();
}
add_action('wp_ajax_search_banned_vendor_in_post_type', 'search_banned_vendor_in_post_type');
add_action('wp_ajax_nopriv_search_banned_vendor_in_post_type', 'search_banned_vendor_in_post_type'); 

function delete_banned_vendor_entry() {
    check_ajax_referer('delete_banned_vendor_nonce', 'nonce');

    $post_id = intval($_POST['post_id']);
    if (current_user_can('delete_post', $post_id)) {
        wp_delete_post($post_id, true);
        echo 'success';
    } else {
        echo 'error';
    }

    wp_die();
}

add_action('wp_ajax_delete_banned_vendor_entry', 'delete_banned_vendor_entry');
add_action('wp_ajax_nopriv_delete_banned_vendor_entry', 'delete_banned_vendor_entry');


// AJAX handler for fetching banned vendor data by post ID
function fetch_banned_vendor_data() {
    check_ajax_referer('fetch_banned_vendor_nonce', 'nonce');

    $post_id = intval($_POST['post_id']);
    $data = array(
        'person_company' => get_post_meta($post_id, 'person_company', true),
        'persons_name' => get_post_meta($post_id, 'persons_name', true),
        'company' => get_post_meta($post_id, 'company', true),
        'contact_details' => get_post_meta($post_id, 'contact_details', true),
        'reason' => get_post_meta($post_id, 'reason', true),
    );

    echo json_encode($data);
    wp_die();
}

add_action('wp_ajax_fetch_banned_vendor_data', 'fetch_banned_vendor_data');

// Update Banned Vendor Data
add_action('wp_ajax_update_banned_vendor_data', 'update_banned_vendor_data');
function update_banned_vendor_data() {

    check_ajax_referer('update_vendor_nonce', 'nonce');
    
  //  $form_data = json_encode($_POST['form_data']);
    parse_str($_POST['form_data'], $form_data);
  
    $post_id = intval($_POST['post_id']);


    if (current_user_can('edit_post', $post_id)) {
        //parse_str($form_data, $form_values);

        $updated_post = array(
            'ID'           => $post_id,
            'post_title'   => sanitize_text_field($form_data['persons_name']),
            'post_status'  => 'publish',
            'post_type'    => 'banned_vendor_data',
        );

        $updated_post_id = wp_update_post($updated_post);

     update_post_meta($post_id, 'person_company', $form_data['person_company']);
     update_post_meta($post_id, 'persons_name', $form_data['persons_name']);
     update_post_meta($post_id, 'company', $form_data['company']);
     update_post_meta($post_id, 'contact_details', $form_data['contact_details']);
     update_post_meta($post_id, 'reason', $form_data['reason']);

        if ($updated_post_id) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('error' => 'Error updating vendor data'));
        }
    } else {
        echo json_encode(array('error' => 'Unauthorized'));
    }

    wp_die();
}



// Handle Form Submission on the Server Side
add_action('wp_ajax_add_new_vendor_data', 'add_new_vendor_data');
function add_new_vendor_data() {
    check_ajax_referer('add_new_vendor_nonce', 'nonce');
    $form_data = $_POST['form_data'];
    parse_str($form_data, $form_values);
    $post_title = sanitize_text_field($form_values['persons_name']);
    $post_id = wp_insert_post(array(
        'post_type' => 'banned_vendor_data',
        'post_title' => $post_title,  
        'post_status' => 'publish',
    ));
    if ($post_id) {
        foreach ($form_values as $key => $value) {
            update_post_meta($post_id, $key, sanitize_text_field($value));
        }
       echo json_encode(array('success' => true));
    } else {
       echo json_encode(array('error' => 'Error adding vendor data'));
    }
    wp_die();
}

include plugin_dir_path(__FILE__) . 'competitors.php';
