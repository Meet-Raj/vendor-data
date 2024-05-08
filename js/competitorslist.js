function openCompetitorForm(postId) {
    $.ajax({
        type: 'POST',
        url: frontendajax.ajaxurl,
        data: {
            action: 'fetch_competitor_data',
            post_id: postId,
            nonce: frontendajax.fetch_com_nonce,
        },
        success: function (response) {
            var data = JSON.parse(response);
            $('#competitor_url').val(data.competitor_url);
            $('#company_type').val(data.company_type);
            $('#domain_authority').val(data.domain_authority);
            $('#organic_traffic').val(data.organic_traffic);
            $('#keyword').val(data.keyword);
            $('#one_year_trend').val(data.one_year_trend);
            $('#avg_visit_duration').val(data.avg_visit_duration);
            $('#CompetitorsFormContainer').show();
            $('#postID').val(postId); 
            $('#submitCompetitorsForm').hide();
            $('#updatecompetitorsButton').show();
        }
    });
}

$(document).on('click', '.edit-vendor-banned', function (e) {
    e.preventDefault();
    var postId = $(this).data('post-id');
    $('#post_ID').val(postId);
    openCompetitorForm(postId);
});    

$('#updatecompetitorsButton').on('click', function () {
    var formData = $('#CompetitorsForm').serialize();

    // Retrieve postID from the form
    var postId = $('#post_ID').val();

    $.ajax({
    type: 'POST',
    url: frontendajax.ajaxurl,
    data: {
        action: 'update_competitor_data',
        nonce: frontendajax.update__com_nonce,
        post_id: postId, 
        form_data: formData,
    },
    success: function (response) {
        var data = JSON.parse(response);
        if (data.success) {
            $('#CompetitorsFormContainer').hide();
             location.reload();
            $('#successMessage').text('Your Competitor data is updated.');
            $('#successPopup').show();
            setTimeout(function () {
                $('#successPopup').hide();
            }, 5000);
           // location.reload();
        } else {
            // Handle error if needed
            console.log('Error updating Competitor data');
        }
    }
});
});