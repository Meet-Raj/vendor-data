jQuery(document).ready(function($) {
    function handleSearch() {
        var searchTerm = $('#vendor-search').val();
        if (searchTerm.length >= 4) {
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'vendor_data_search',
                    searchTerm: searchTerm,
                },
                success: function(response) {
                   $('#vendor-search-results').html(response);
                   $('.delete-vendor').click(function(e) {
    e.preventDefault();
    var postId = $(this).data('post-id');
    var confirmDelete = confirm('Are you sure you want to delete this vendor?');
    if (confirmDelete) {
        deleteVendorData(postId);
    }
});
                }
            });
        }
    }

function openVendorlistForm(postId) {
    $.ajax({
        type: 'POST',
        url: frontendajax.ajaxurl,
        data: {
            action: 'fetch_vendor_data',
            post_id: postId,
            nonce: frontendajax.fetchlist_nonce,
        },
        success: function (response) {

            var data = JSON.parse(response);
            var area = data.area_of_practice;
            var responseArray = area.split(', ');

            jQuery('#multiple-select-field option').each(function() {
                var optionValue = jQuery(this).val();
                
                if (responseArray.includes(optionValue)) {
                    jQuery(this).prop('selected', true);
                } else {
                    jQuery(this).prop('selected', false);
                }
            });

            var formatedResponse = responseArray;
            $('#country').val(data.country).trigger('change');
            $('#multiple-select-field').trigger('change');
            $('#vendor-company').val(data.company);
            $('#vendor-contact-info').val(data.contact_information);
            $('#vendor-contact-person').val(data.contact_person);
            $('#status').val(data.status);
            $('#vendor-attitude').val(data.attitude);
            $('#vendor-issues').val(data.issues);
            $('#vendor-additional-comment').val(data.additional_comments);
            $('#vendor-referral-code').val(data.referral_code);

            // Show the popup form
            $('#add-vendor-popup').show();

            // Set the postID value for reference during update
            $('#postID').val(postId);

            // Hide the submit button and show the update button
            $('#submitvendorlist').hide();
            $('#vendorlistupdate').show();
        }
    });
}

$(document).ready(function ($) {
$(document).on('click', '#new_play', function (e) {
    var table = $('#vendor-table').DataTable();
    


    // Check if DataTable is already initialized
    if (!table || !$.fn.DataTable.isDataTable('#vendor-table')) {
        
        table = $('#vendor-table').DataTable({
            "searching": false,
            "paging": false,
            "columnDefs": [
                {
                    "targets": [9, 10, 11, 12],
                    "visible": false,
                }
            ]
        });
    }



    $('#vendor-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });

    function format(data) {
        var iconClass = '';

        return '<div class="details-container">' +
            '<p><i class="' + iconClass + '"></i> ATTITUDE: ' + data[9] + '</p>' +
            '<p><i class="' + iconClass + '"></i> ISSUES: ' + data[10] + '</p>' +
            '<p><i class="' + iconClass + '"></i> ADDITIONAL_COMMENTS: ' + data[11] + '</p>' +
            '<p><i class="' + iconClass + '"></i> REFERRAL_CODE: ' + data[12] + '</p>' +
            '</div>';
    }
});


    $(document).on('click', '.edit-vendor', function (e) {
        e.preventDefault();
        var postId = $(this).data('post-id');
        $('#post_ID').val(postId);
        openVendorlistForm(postId);
    });

    $('#vendorlistupdate').on('click', function () {
        var formData = $('#add-vendor-form').serialize();
        var postId = $('#post_ID').val();
        var area_of_practice = $("#multiple-select-field").val();

        $.ajax({
            type: 'POST',
            url: frontendajax.ajaxurl,
            data: {
                action: 'update_vendor_data',
                nonce: frontendajax.updatelist_nonce,
                post_id: postId,
                form_data: formData,
                area_of_practice : area_of_practice,
            },
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#add-vendor-popup').hide();
                     location.reload();
                    $('#successMessage').text('Your vendor data is updated.');
                    $('#successPopup').show();
                    setTimeout(function () {
                        $('#successPopup').hide();
                    }, 5000);
                    location.reload();

                } else {
                    console.log('Error updating vendor data');
                }
            }
        });
    });
});


   function deleteVendorData(postId) {
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            action: 'delete_vendor_data_frontend',
            post_id: postId,
        },
        success: function(response) {
            console.log(response);
            location.reload();
        }
    });
}

    $('#vendor-search').on('input', function() {
        setTimeout(handleSearch, 500);
    });
});


jQuery(document).ready(function ($) {
    // Show the add vendor popup on button click
    $('#add-vendor-button').click(function () {
        $('#add-vendor-popup').fadeIn();
    });


    // Submit the form via AJAX
    $('#add-vendor-form').submit(function (e) {
        e.preventDefault();

        var area_of_practice_values = $('#multiple-select-field').val();
        var area_of_practice_string = area_of_practice_values.join(', ');

        var formData = $(this).serialize() + '&area_of_practice=' + area_of_practice_string;

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: formData + '&action=add_vendor_data',
            success: function (response) {
                // Handle success (e.g., close the popup, show a success message)
                console.log(response);
                $('#add-vendor-popup').fadeOut();
                location.reload();

            }
        });
    });
});
  
// Function to open the form and populate fields
function openVendorForm(postId) {
    // AJAX request to fetch data for the selected row
    $.ajax({
        type: 'POST',
        url: frontendajax.ajaxurl,
        data: {
            action: 'fetch_banned_vendor_data',
            post_id: postId,
            nonce: frontendajax.fetch_nonce,
        },
        success: function (response) {
            var data = JSON.parse(response);

            // Populate the form fields with fetched data
            $('#personCompany').val(data.person_company);
            $('#personsName').val(data.persons_name);
            $('#company').val(data.company);
            $('#contactDetails').val(data.contact_details);
            $('#reason').val(data.reason);

            // Show the popup form
            $('#vendorFormContainer').show();

            // Set the postID value for reference during update
            $('#postID').val(postId); // Ensure 'postID' is lowercase

            // Hide the submit button and show the update button
            $('#submitVendorForm').hide();
            $('#updateVendorDataButton').show();
        }
    });
}

// Add this inside the document ready block
$(document).on('click', '.edit-vendor-banned', function (e) {
    e.preventDefault();
    var postId = $(this).data('post-id');
    $('#post_ID').val(postId);
    openVendorForm(postId);
});


// Add this inside the document ready block
$('#updateVendorDataButton').on('click', function () {
    var formData = $('#vendorForm').serialize();

    // Retrieve postID from the form
    var postId = $('#post_ID').val();
   

$.ajax({
    type: 'POST',
    url: frontendajax.ajaxurl,
    data: {
        action: 'update_banned_vendor_data',
        nonce: frontendajax.update_nonce,
        post_id: postId, // Ensure 'post_id' is lowercase
        form_data: formData,
    },
    success: function (response) {
        var data = JSON.parse(response);
        if (data.success) {
            // Close the form
            $('#vendorFormContainer').hide();

            // Show success popup
             location.reload();
            $('#successMessage').text('Your Banned data is updated.');
            $('#successPopup').show();

            // Hide success popup after 5 seconds
            setTimeout(function () {
                $('#successPopup').hide();
            }, 5000);
           // location.reload();
        } else {
            // Handle error if needed
            console.log('Error updating Banned data');
        }
    }
});
});





