<link rel="stylesheet" href="<?php echo css_url_path('imgareaselect-animated.css'); ?>" type="text/css" />
<script src="<?php echo js_url_path('jquery.imgareaselect.js'); ?>"></script>

<script>
 $(document).ready(function(){
	/*Countdown timer for next or coming appointment*/
	<?php if (!empty($get_next_appointment->start_time)) : ?>
		var url = '<?php echo base_url('appointments/conference/' . encryptor('encrypt', $get_next_appointment->id)) ?>';
		call_alert("<?php echo convert_from_sql_time('d M Y H:i:s', $get_next_appointment->start_time); ?>", "appointment_call_alert", url);
	<?php endif; ?>
});

/* Change Password */
$(document).on('submit', 'form#update_password', function(e)
{
    e.preventDefault();

    $.post(SITE_URL + 'operations/change_password', $(this).serialize(), function(response)
    {
        response = $.parseJSON(response);
        
        if (response.status == '1')
        {
            $('.response_section').addClass('correct_response').removeClass('error_response').html(response.message);
        }
        else
        {
            $('.response_section').addClass('error_response').removeClass('correct_response').html(response.message);
        }
        $('form#update_password')[0].reset();
    });
});
/*----------------------Change Password Ends -------------------------- */

$(document).on('click', '.profile-photo', function()
{
    $('#profile_form input[name=image_type]').val('<?php echo PROFILE_IMAGE ?>');
    $('#profile_form input[type=file]').trigger('click');
});

$(document).on('click', '.signature_pik i', function()
{
    $('#profile_form input[name=image_type]').val('<?php echo SIGNATURE_IMAGE ?>');
    $('#profile_form input[type=file]').trigger('click');
});

$(document).on('change', '#profile_form input[type=file]', function()
{
    var formdata = new FormData($('#profile_form')[0]);

    $.ajax({
        type: 'POST',
        url: '<?php echo base_url() ?>operations/profile_image_upload',
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
    }).done(function(data) {
        if (data.success == true)
        {
            $('.open_popup').attr('href', SITE_URL + 'operations/load_image_popup/' + data.image_name);
            $('.open_popup').trigger('click');
        }
        else
        {
            alertify.alert(data.message);
        }
    })
});

$(window).ready(function(){
    var WinHeight = $('.c-left').height();

    $('.c-right').css('min-height', WinHeight);
});

$(document).on('click', '.edit_details i.fa-edit', function()
{
    var field_element = $(this).closest('li').find('strong');
    var field_name = $(this).closest('span').data('field');

    $(this).removeClass('fa-edit').addClass('fa-check');    
    field_element.html('<input data-field="' + field_name + '" value="' + field_element.text() + '" />');
});

$(document).on('click', '.bio_edit_details i.fa-edit', function()
{
    var field_element = $(this).closest('div').find('p');
    var field_name = $(this).closest('span').data('field');

    $(this).removeClass('fa-edit').addClass('fa-check');
    field_element.html('<textarea data-field="' + field_name + '">' + field_element.text() + '</textarea>');
    field_element.find('textarea').trigger('focus');
});

$(document).on('click', '.edit_details i.fa-check', function()
{
    var field_element = $(this).closest('li').find('strong');
    var field_element_val = field_element.find('input').val();
    var field_name = field_element.find('input').data('field');

    $(this).removeClass('fa-check').addClass('fa-edit');    
    field_element.html(field_element_val);

    $.post(SITE_URL + 'doctors/update_profile_detail', 'field=' + field_name + '&field_val=' + field_element_val);
});

$(document).on('click', '.bio_edit_details i.fa-check', function()
{
    var field_element = $(this).closest('div').find('p');
    var field_element_val = field_element.find('textarea').val();
    var field_name = field_element.find('textarea').data('field');

    $(this).removeClass('fa-check').addClass('fa-edit');
    field_element.html(field_element_val);
    $.post(SITE_URL + 'doctors/update_profile_detail', 'field=' + field_name + '&field_val=' + field_element_val);
});
</script>
