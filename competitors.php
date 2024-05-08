<?php
function add_competitors_data_meta_box() {
    add_meta_box(
        'competitors_data_meta_box',
        __('Competitors Data Details'),
        'render_competitors_data_meta_box',
        'competitors_data',
        'normal',
        'default'
    );
}
function render_competitors_data_meta_box($post)
 {
    wp_nonce_field('competitors_data_nonce', 'competitors_data_nonce');
    $competitor_url = get_post_meta($post->ID, 'competitor_url', true);
    $company_type = get_post_meta($post->ID, 'company_type', true);
    $domain_authority = get_post_meta($post->ID, 'domain_authority', true);
    $organic_traffic = get_post_meta($post->ID, 'organic_traffic', true);
    $keyword = get_post_meta($post->ID, 'keyword', true);
    $one_year_trend = get_post_meta($post->ID, 'one_year_trend', true);
    $avg_visit_duration = get_post_meta($post->ID, 'avg_visit_duration', true); ?>

        <p><label for="competitor_url">Competitor URL:</label>
        <input type="text" name="competitor_url" id="competitor_url" value="<?php echo esc_attr($competitor_url); ?>"></p>
       <p><label for="company_type">Company Type:</label>
    <select name="company_type" id="company_type">
        <option value="Expat" <?php selected($company_type, 'Expat'); ?>>Expat</option>
        <option value="Immigration" <?php selected($company_type, 'Immigration'); ?>>Immigration</option>
        <option value="Information" <?php selected($company_type, 'Information'); ?>>Information</option>
        <option value="Offshore Services" <?php selected($company_type, 'Offshore Services'); ?>>Offshore Servces</option>
        <option value="offshore services" <?php selected($company_type, 'offshore services'); ?>>Offshore Services</option>
        <option value="RCBI" <?php selected($company_type, 'RCBI'); ?>>RCBI</option>
        <option value="Real Estate" <?php selected($company_type, 'Real Estate'); ?>>Real Estate</option>
        <option value="Tax Consultancy" <?php selected($company_type, 'Tax Consultancy'); ?>>Tax Consultancy</option>
        <option value="tax services" <?php selected($company_type, 'Tax Services'); ?>>Tax Services</option>
    </select>
</p>

        <p><label for="domain_authority">Domain Authority:</label>
        <input type="text" name="domain_authority" id="domain_authority" value="<?php echo esc_attr($domain_authority); ?>"></p>
        <p><label for="organic_traffic">Organic Traffic:</label>
        <input type="text" name="organic_traffic" id="organic_traffic" value="<?php echo esc_attr($organic_traffic); ?>"></p>
        <p><label for="keyword">keyword:</label>
        <input type="text" name="keyword" id="keyword" value="<?php echo esc_attr($keyword); ?>"></p>
        <p><label for="one_year_trend">One Year Trend:</label>
        <select name="one_year_trend" id="one_year_trend">
            <option value="Down" <?php selected($one_year_trend, 'Down'); ?>>Down</option>
            <option value="n/a" <?php selected($one_year_trend, 'n/a'); ?>>n/a</option>
            <option value="Stable" <?php selected($one_year_trend, 'Stable'); ?>>Stable</option>
            <option value="Up" <?php selected($one_year_trend, 'Up'); ?>>Up</option>
        </select></p>
	   <p><label for="avg_visit_duration">Avg. Visit Duration:</label>
        <input type="text" name="avg_visit_duration" id="avg_visit_duration" value="<?php echo esc_attr($avg_visit_duration); ?>"></p><?php
}

function save_competitors_data_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!isset($_POST['competitors_data_nonce']) || !wp_verify_nonce($_POST['competitors_data_nonce'], 'competitors_data_nonce')) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if ('competitors_data' !== get_post_type($post_id)) {
        return;
    }
    global $wpdb;
    $table_name3 = $wpdb->prefix . 'competitors_data';
    $competitors_data = array(
    	'competitor_url' => sanitize_text_field($_POST['competitor_url']),
        'company_type' => isset($_POST['company_type']) ? sanitize_text_field($_POST['company_type']) : '',
        'domain_authority' => sanitize_text_field($_POST['domain_authority']),
        'organic_traffic' => sanitize_text_field($_POST['organic_traffic']),
        'keyword' => sanitize_text_field($_POST['keyword']),
        'one_year_trend' => isset($_POST['one_year_trend']) ? sanitize_text_field($_POST['one_year_trend']) : '',
        'avg_visit_duration' => sanitize_text_field($_POST['avg_visit_duration']),
    );

    $competitors_existing_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name3 WHERE id = %d", $post_id));

     update_post_meta($post_id, 'competitor_url', $_POST['competitor_url']);
     update_post_meta($post_id, 'company_type', $_POST['company_type']);
     update_post_meta($post_id, 'domain_authority', $_POST['domain_authority']);
     update_post_meta($post_id, 'organic_traffic', $_POST['organic_traffic']);
     update_post_meta($post_id, 'keyword', $_POST['keyword']);
     update_post_meta($post_id, 'one_year_trend', $_POST['one_year_trend']);
     update_post_meta($post_id, 'avg_visit_duration', $_POST['avg_visit_duration']);

    if ($competitors_existing_data) {
        $wpdb->update($table_name3, $competitors_data, array('id' => $post_id), $format = null, $where_format = null);
    } else {
        $table_name3['id'] = $post_id;
        $wpdb->insert($table_name3, $competitors_data, array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
    }
}
add_action('save_post', 'save_competitors_data_meta');

function competitors_tab($user) {
    $competitors_tab_active = (isset($_GET['action']) && $_GET['action'] == 'competitors-data') ? 'mepr-active-nav-tab' : '';
    ?>
    <span class="mepr-nav-item competitors-data <?php echo $competitors_tab_active; ?>">
        <a href="<?php echo esc_url(home_url('/?action=competitors-data')); ?>">Competitors List</a>
    </span>
    <?php
}
add_action('mepr_account_nav', 'competitors_tab');

function competitors_tabs_content($action) {
    if ($action == 'competitors-data') :
         $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $compare_user_roles = array( 'administrator', 'portal_manager');
        foreach( $user_roles as $user_role ){
         if( in_array( $user_role, $compare_user_roles ) ){ 
        ?>
<button id="opencompetitorsform" class="btn-green"><span class="mr-2"><i class="fas fa-plus"></i></span> Add Competitors Data</button>
<button id="updatecompetitors" class="btn-green" style="display:none;">Update Competitors Data</button>

<div id="CompetitorsFormContainer" style="display:none;" class="custom-popup-modal">
    <form id="CompetitorsForm">
        <a href="javascript:void(0);" class="closeModal" id="closeButtonn"><i class="fas fa-times"></i></a>        
         <label for="competitor_url">Competitor URL:</label>
   		 <input type="text" name="competitor_url" id="competitor_url">

         <label for="company_type">Company Type:</label>
  	  <select name="company_type" id="company_type">
  	  		<option value="Expat">Expat</option>
            <option value="Immigration">Immigration</option>
            <option value="Information">Information</option>
            <option value="Offshore Servces">Offshore Servces</option>
            <option value="Offshore Services">Offshore Services</option>
            <option value="RCBI">RCBI</option>
            <option value="Real Estate">Real Estate</option>
            <option value="Tax Consultancy">Tax Consultancy</option>
            <option value="Tax Services">Tax Services</option>
   	 </select>

    <label for="domain_authority">Domain Authority:</label>
    <input type="text" name="domain_authority" id="domain_authority">

    <label for="organic_traffic">Organic Traffic:</label>
    <input type="text" name="organic_traffic" id="organic_traffic">

    <label for="keyword">keyword:</label>
    <input type="text" name="keyword" id="keyword">

    <label for="one_year_trend">One year Trend:</label>
  	  <select name="one_year_trend" id="one_year_trend">
          <option value="Down">Down</option>
            <option value="n/a">n/a</option>
            <option value="Stable">Stable</option>
            <option value="Up">Up</option>
   	 </select>

   	<label for="avg_visit_duration">Avg Visit Duration:</label>
    <input type="text" name="avg_visit_duration" id="avg_visit_duration">

     <input type="hidden" name="post_ID" class="post_ID" id="post_ID">
     <button type="button" value="Submit" id="submitCompetitorsForm">Submit</button>
    <button type="button" value="Update" style="display:none;" id="updatecompetitorsButton">Update</button>
    </form>
</div>

<div id="successPopup" style="display:none;">
    <p id="successMessage"></p>
</div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
    var closeButtonn = document.getElementById('closeButtonn');
    closeButtonn.addEventListener('click', function () {
    location.reload();
    }); });
 </script>
  <script>
    jQuery(document).ready(function ($) {
        $('#opencompetitorsform').on('click', function () {
            $('#CompetitorsFormContainer').toggle();
        });
           $('.closeModal').on( 'click', function (){
            $('.custom-popup-modal').fadeOut();
        });
           
        $('#submitCompetitorsForm').on('click', function () {
            var formData = $('#CompetitorsForm').serialize();
            $.ajax({
                type: 'POST',
                url: frontendajax.ajaxurl,
                data: {
                    action: 'add_competitors_data',
                    nonce: '<?php echo wp_create_nonce('add_competitors_data_nonce'); ?>',
                    form_data: formData,
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        // Close the form
                        $('#CompetitorsFormContainer').hide();
                        location.reload();
                        $('#successMessage').text('Your Competitor is added.');
                        $('#successPopup').show();
                        setTimeout(function () {
                            $('#successPopup').hide();
                        }, 5000);
                    } else {
                        console.log('Error adding Competitor data');}
                }
            });
        });
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
            <div id="competitor-data-search-form" class="form-group" style="max-width: 300px;">
                <label for="competitor-data-search">Competitor List:</label>
                <div class="input-group custom-input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-magnifying-glass"></i></span>
                    </div>
                    <input type="text" id="competitor-data-search" name="competitor-data-search" placeholder="Search">
                </div>
            </div>
        </div>

        <div id="competitor-data-search-results"></div>
       <script>
           jQuery(document).ready(function ($) {
                    var typingTimer;
                    var doneTypingInterval = 1000; // 1 second

                    $('#competitor-data-search').on('input', function () {
                        clearTimeout(typingTimer);
                        typingTimer = setTimeout(function () {
                            var searchTerm = $('#competitor-data-search').val();
                            if (searchTerm.length >= 4) {
                                $('#competitor-data-search-results').html('<div class="loading-spinner"></div>');
                                $.ajax({
                                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                    type: 'POST',
                                    data: {
                                        action: 'search_competitor_in_post_type',
                                        search_term_competitor: searchTerm, // Fix the parameter name
                                        nonce: '<?php echo wp_create_nonce('search_competitor_nonce'); ?>'
                                    },
                                    success: function (response) {
                                        $('#competitor-data-search-results').html(response);

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
                                $('#competitor-data-search-results').html('Enter at least 4 characters.');
                            }
                        }, doneTypingInterval);
                    });
                });
        </script>
        <?php
    endif;
}
add_action('mepr_account_nav_content', 'competitors_tabs_content');

function search_competitor_in_post_type() {
    check_ajax_referer('search_competitor_nonce', 'nonce');
    $search_term_competitor= sanitize_text_field($_POST['search_term_competitor']);
    if (strlen($search_term_competitor) >= 4) {
        $query_args_competitor = array(
            'post_type' => 'competitors_data',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'competitor_url',
                    'value' => $search_term_competitor,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'company_type',
                    'value' => $search_term_competitor,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'domain_authority',
                    'value' => $search_term_competitor,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'organic_traffic',
                    'value' => $search_term_competitor,
                    'compare' => 'LIKE',
                ),
                array(
                    'key' => 'keyword',
                    'value' => $search_term_competitor,
                    'compare' => 'LIKE',
                ),
                    array(
                    'key' => 'one_year_trend',
                    'value' => $search_term_competitor,
                    'compare' => 'LIKE',
                ),
                     array(
                    'key' => 'avg_visit_duration',
                    'value' => $search_term_competitor,
                    'compare' => 'LIKE',
                ),
            ),
        );
        $results = new WP_Query($query_args_competitor);
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
            echo '<th>Competitor URL</th>';
            echo '<th>Company Type</th>';
            echo '<th>Domain Authority</th>';
            echo '<th>Organic Traffic</th>';
            echo '<th>Keyword</th>';
            echo '<th>One Year Trend</th>';
            echo '<th>Avg Visit Duration</th>';
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
                    echo '<tr class="banned-vendor-row">';} }
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'competitor_url', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'company_type', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'domain_authority', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'organic_traffic', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'keyword', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'one_year_trend', true)) . '</td>';
                echo '<td class="vendor-td-class">' . esc_html(get_post_meta($post_id, 'avg_visit_duration', true)) . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table></div></div>';
            wp_reset_postdata();} else { echo 'No results found.'; }} else {
        echo 'Enter at least 4 characters.';    }
    wp_die();}
  add_action('wp_ajax_search_competitor_in_post_type', 'search_competitor_in_post_type');
  add_action('wp_ajax_nopriv_search_competitor_in_post_type', 'search_competitor_in_post_type');

add_action('wp_ajax_add_competitors_data', 'add_competitors_data');
function add_competitors_data() {
    check_ajax_referer('add_competitors_data_nonce', 'nonce');
    $form_data = $_POST['form_data'];
    parse_str($form_data, $form_values);
    $post_title = sanitize_text_field($form_values['competitor_url']);
    $post_id = wp_insert_post(array(
        'post_type' => 'competitors_data',
        'post_title' => $post_title,  
        'post_status' => 'publish',
    ));
    if ($post_id) {
        foreach ($form_values as $key => $value) {
            update_post_meta($post_id, $key, sanitize_text_field($value));
        }
       echo json_encode(array('success' => true));
    } else {
       echo json_encode(array('error' => 'Error adding Competitor data'));
    }
    wp_die();
}

function fetch_competitor_data() {
    check_ajax_referer('fetch_competitors_data_nonce', 'nonce');
    $post_id = intval($_POST['post_id']);
    $data = array(
        'competitor_url' => get_post_meta($post_id, 'competitor_url', true),
        'company_type' => get_post_meta($post_id, 'company_type', true),
        'domain_authority' => get_post_meta($post_id, 'domain_authority', true),
        'organic_traffic' => get_post_meta($post_id, 'organic_traffic', true),
        'keyword' => get_post_meta($post_id, 'keyword', true),
        'one_year_trend' => get_post_meta($post_id, 'one_year_trend', true),
        'avg_visit_duration' => get_post_meta($post_id, 'avg_visit_duration', true),
    );
    echo json_encode($data);
    wp_die();
}
add_action('wp_ajax_fetch_competitor_data', 'fetch_competitor_data');
add_action('wp_ajax_update_competitor_data', 'update_competitor_data');
function update_competitor_data() {
    check_ajax_referer('update_competitors_data_nonce', 'nonce');
    parse_str($_POST['form_data'], $form_data);
    $post_id = intval($_POST['post_id']);
    if (current_user_can('edit_post', $post_id)) {
        $updated_post = array(
            'ID'           => $post_id,
            'post_title'   => sanitize_text_field($form_data['competitor_url']),
            'post_status'  => 'publish',
            'post_type'    => 'competitors_data',
        );

        $updated_post_id = wp_update_post($updated_post);

     update_post_meta($post_id, 'competitor_url', $form_data['competitor_url']);
     update_post_meta($post_id, 'company_type', $form_data['company_type']);
     update_post_meta($post_id, 'domain_authority', $form_data['domain_authority']);
     update_post_meta($post_id, 'organic_traffic', $form_data['organic_traffic']);
     update_post_meta($post_id, 'keyword', $form_data['keyword']);
     update_post_meta($post_id, 'one_year_trend', $form_data['one_year_trend']);
     update_post_meta($post_id, 'avg_visit_duration', $form_data['avg_visit_duration']);


        if ($updated_post_id) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('error' => 'Error updating competitor data'));
        }
    } else {
        echo json_encode(array('error' => 'Unauthorized'));
    }

    wp_die();
}
