<script>
    $(document).on('change', '.shifts_left_checks, .shifts_right_checks', function()
    {
        var shift_id = $(this).data('shift_id');
        var date = $(this).data('date');
        var start_time = $(this).data('start_time');
        var end_time = $(this).data('end_time');

        if ($(this).is(':checked'))
        {
            var html = '<li><input data-shift_id = ' + shift_id + ' checked="checked" class="shifts_right_checks" id="hd-check_' + shift_id + '" type="checkbox"><label for="hd-check_' + shift_id + '"><span></span></label><b class="date">' + date + '</b><span class="time">' + start_time + ' - ' + end_time  + '</span></li>';
            $('ul.appointment-ul').append(html);
        }
        else
        {
            $('#hd-check_' + shift_id).closest('li').remove();
            $('#check_' + shift_id).removeAttr('checked');
        }

        $(".shifts_left_checks:checked").length > 0 ? $('div.confirm').show() : $('div.confirm').hide();
    });

    $(document).on('click', 'a.confirm-btn', function(e)
    {
        e.preventDefault();
        var form_element = $('#claim_form');
        var button_element = $(this);
        var if_error = false;

        $('.shifts_right_checks').each(function()
        {
            var current_element = $(this);
            form_element.append('<input type="hidden" name="shift_id" value="' + $(this).data('shift_id') + '" />');
            $.ajax(
            {
                type: 'post',
                data: form_element.serialize(),
                url: SITE_URL + 'shifts/claim_shift',
                async: false,
                dataType:'json',
                success: function(response) {
                    if (response.success == true)
                    {
                        current_element.closest('li').append(' ' + response.message).css('color', '#5EAE4F');
                    }
                    else
                    {
                        if_error = true
                        current_element.closest('li').append(' ' + response.message).css('color', '#DA1739');
                    }
                }
            });
        });
        if (if_error == true)
        {
            button_element.removeClass('confirm-btn').attr('href', SITE_URL + 'schedule').text('Go to Calendar');
        }
        else
        {
            window.location.href = SITE_URL + 'schedule';
        }
    });

    $(window).ready(function(){
        var WinHeight = $('.schedule-left').height();
        $('.schedule-right').css('max-height', WinHeight)
    });
    
    $(document).ready(function(){
		/*Countdown timer for next or coming appointment*/
		<?php if (!empty($get_next_appointment->start_time)) : ?>
			var url = '<?php echo base_url('appointments/conference/' . encryptor('encrypt', $get_next_appointment->id)) ?>';
			call_alert("<?php echo convert_from_sql_time('d M Y H:i:s', $get_next_appointment->start_time); ?>", "appointment_call_alert", url);
		<?php endif; ?>
    });
    
</script>
