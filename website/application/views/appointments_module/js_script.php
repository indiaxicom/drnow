<script>
var TIMER_STOPWATCH = new (function() {
var $stopwatch, // Stopwatch element on the page
    incrementTime = 70, // Timer speed in milliseconds
    currentTime = 0, // Current time in hundredths of a second
    updateTimer = function() {
        $stopwatch.html(formatTime(currentTime));
        currentTime += incrementTime / 10;
    },
    init = function() {
        $stopwatch = $('#stopwatch');
        TIMER_STOPWATCH.Timer = $.timer(updateTimer, incrementTime, false);
    };
    this.resetStopwatch = function() {
        currentTime = 0;
        this.Timer.stop().once();
    };
    $(init);
});

function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}

/*Formatting Time*/
function formatTime(time) {
    var min = parseInt(time / 6000),
        sec = parseInt(time / 100) - (min * 60),
        hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
}

$(document).ready(function()
{
    /*Auto saving of prescription in 60 seconds*/
    //setInterval('save_appointment_notes()', 60000);

    /*Countdown timer for next or coming appointment*/
    <?php if (!empty($get_next_appointment->start_time)) : ?>
        countdown_timer("<?php echo convert_from_sql_time('d M Y H:i:s', $get_next_appointment->start_time); ?>", "countdown_appointment");
    <?php endif; ?>
    
    /*Script for autocomplete prescriptions*/
    function create_formulary_list( message )
    {
        var list_element = $('form#appointment_prescription input[name=presc_count]');
		var curr_val = list_element.val();
		var list_length = parseInt(curr_val) + 1;

        $( "ul.list2" ).prepend( '<li> <p>' + message.value  + ' </p><p>Price / Unit : <strong> <?php echo $this->config->item('currency_symobol')?> <span class="unit-price">' + message.unit_price + '</span></strong></p><a class="cancel" data-prescription_id="' + list_length + '" href="#">&nbsp;</a><div class="btn-sec"><input type="hidden" name=prescription[' + list_length + '][nm] value="' + message.value + '" /><input type="text" name=prescription[' + list_length + '][dose] placeholder="Dose" /><input type="text" name=prescription[' + list_length + '][frequency] placeholder="Frequency" /><input data-unit_price="' + message.unit_price + '" class="price_box" name=prescription[' + list_length + '][total] value="1" min="1" type="number" placeholder="Total" /><input type="hidden" name=prescription[' + list_length + '][vpid] value="' + message.id + '" /></div></li>' );
        
        $( "ul.list2" ).scrollTop( 0 );

        $("form#appointment_notes").find('input[name=unchanged_text]').val('1');
        list_element.val(list_length);
    }

    $( "#prescription" ).autocomplete({
        search  : function() { $(this).addClass('loading'); },
        open    : function() { $(this).removeClass('loading'); },
        source: function(request, response) {
            $.ajax({
                url: '<?php echo base_url() . $current_module . '/get_prescription' ?>',
                data: request,
                dataType: "json",
                success: function(data, status) {
                    response(data);
                },
                error: function() {
                    response([]);
                },
                complete: function() {
                    $('#prescription').removeClass('loading');
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
            create_formulary_list( ui.item);
            $(this).val('');
            return false;
        }
    }).data( "ui-autocomplete")._renderItem = function( ul, item ) {
        return $( "<li>" ).append( $( '<a style="font-size:13px">' ).html('<i class="fa fa-stethoscope"></i> ' + item.value ) ).appendTo( ul )
    };
    /* Prescription script ends */

    /*Script for autocomplete prescriptions*/
    function create_icd_list( message )
    {
        $( "ul.icd-list" ).html( '<li> ' + message.value  + '<a class="cancel" data-prescription_id="' + message.id + '" href="#">&nbsp;</a><div class="btn-sec"><input type="hidden" name=icd_list[key] value="' + message.id + '" /><input type="hidden" name=icd_list[value] value="' + message.value + '" /></div></li>' );
        $( "ul.icd-list" ).scrollTop( 0 );

        $("form#appointment_notes").find('input[name=unchanged_text]').val('1');
    }

    $( "#icd_list" ).autocomplete({
        search  : function() { $(this).addClass('loading'); },
        open    : function() { $(this).removeClass('loading'); },
        source: function(request, response) {
            $.ajax({
                url: '<?php echo base_url() . $current_module . '/get_icd_list' ?>',
                data: request,
                dataType: "json",
                success: function(data, status) {
                    response(data);
                },
                error: function() {
                    response([]);
                },
                complete: function() {
                    $('#icd_list').removeClass('loading');
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
            create_icd_list( ui.item);
            $(this).val('');
            return false;
        }
    }).data( "ui-autocomplete")._renderItem = function( ul, item ) {
        return $( "<li>" ).append( $( '<a style="font-size:13px">' ).html('<i class="fa fa-stethoscope"></i> ' + item.value ) ).appendTo( ul )
    };
    /* Prescription script ends */

    $(document).on('click', 'a.cancel', function(e)
    {
        e.preventDefault();
        $(this).closest('li').remove();
        $('ul.list2, ul.list1').find('a[data-prescription_id="' + $(this).data('prescription_id') + '"]').trigger('click');
        $("form#appointment_notes").find('input[name="prescription[' + $(this).data('prescription_id') + ']"]').remove();
    });
    
    /*Filling of outcome dropdowns*/
    $.getJSON(SITE_URL + 'appointments/get_outcome_list/da1', null, function(data)
    {
		var select_element = $('form#appointment_outcomes select[name=patient_reference]');
		
		$.each(data, function(index, item) 
		{
			select_element.append( 
				$("<option></option>")
					.text(item.outcome)
					.val(item.idoutcomedefinitions)
			);
		});
	});
    $.getJSON(SITE_URL + 'appointments/get_outcome_list/da2', null, function(data)
    {
		var select_element = $('form#appointment_outcomes select[name=appointment_conclusion]');

		try
		{
			$.each(data, function(index, item) 
			{
				select_element.append( 
					$("<option></option>")
						.text(item.outcome)
						.val(item.idoutcomedefinitions)
				);
			});
		}
		catch(err)
		{
			
		}
	});
});

$(document).on('click', 'button.start-call', function()
{
    alertify.alert("Call is about to begin Continue ?", function (e)
    {
		$.ajax(
		{
			type : 'post',
			url : SITE_URL + '<?php echo $this->current_module ?>/start_call',
			data: $('form#tokbox_details').serialize(),
			dataType:'json',
			success: function(data)
			{
				window.location.reload();
			}
		});
    });
});

$(document).on('keydown', 'form#appointment_notes', function()
{
    $(this).find('input[name=unchanged_text]').val('1');
});

$(document).on('click', 'div.more_info_toogle', function()
{
    $('div.more-info-cont').slideToggle();
    $(this).find('i').toggleClass("fa-chevron-down fa-chevron-up");
});

/* Final Saving of prescription on clicking save button */
$(document).on('click', 'div.save_prescription a', function(e)
{
    e.preventDefault();
    alertify.confirm("Are you sure you want send the prescription ?", function (e)
    {
        if (e) {
            $('form#appointment_notes').find('input[name=unchanged_text]').val('1');
            $('form#appointment_prescription').find('input[name=create_prescription]').val('1');
            save_appointment_notes();
        }
        else
        {
            alertify.error("You cancelled the operation");
        }
    });
});

$(document).on('change', 'input.price_box', function(e)
{
	var curr_element = $(this);
	curr_element.closest('li').find('span.unit-price').text(curr_element.val() * curr_element.data('unit_price'));
});

$(document).on('click', '.presc_prepared', function(e)
{
	
	alertify.alert("Prescription is already prepared for this appointment");
});

$(document).on('click', '.appoint_tab', function(e)
{
	var href = $(this).attr('href');
	window.location.href = href;
});

$(document).on('click', '.save_outcomes', function(e)
{
	var form_element = $('form#appointment_outcomes');
	var response_messages = {
		"outcome_response" : "Appointmnet Conclusion Sent Succefully", 
		"referal_response" : "Referal Added"
	};

	if (form_element.find('input[name=outcome_allowed]').val() == '0')
	{
		alertify.alert('Appointment outcome entry is not allowed at this time');
		return false;
	}
	else if (form_element.find('input[name=outcome_allowed]').val() == '2')
	{
		alertify.alert('Appointment outcome already posted');
		return false;
	}
	
	$.ajax
	({
		type : 'post',
		url : SITE_URL + 'appointments/save_appointment_outcomes',
		data: form_element.serialize(),
		dataType:'json',
		success: function(data)
		{
			$('div.save_outcomes').find('a').text('Outcome Posted Sucessfully').closest('div').removeClass('save_outcomes');
			
			$.each(data.app_status, function(index, item) 
			{
				if (item == true)
				{
					alertify.success(response_messages[index]);
				}
			});
		}
	});
});
</script>
<!--------------------Tok box related functionalities --------------------------->

<?php if (session_data('tokbox_data') != NULL) : ?>
    <script src="//static.opentok.com/webrtc/v2.2/js/opentok.min.js" ></script>

    <script type="text/javascript">
        var apiKey    = "<?php echo $this->config->item('tokbox_api_key'); ?>";
        var sessionId = "<?php echo session_data('tokbox_data')['tokbox_session_key']; ?>";
        var token     = "<?php echo session_data('tokbox_data')['tokbox_token']; ?>";
        var publisher;
        var subscriber;
        var interval;
        var session = OT.initSession(apiKey, sessionId);
     
        session.on("streamCreated", function(event) {
            subscriber = session.subscribe(event.stream, 'subscriber', {width:610, height:410});
            start_archieve();
            $('.profile_patient_pik').hide();
        });

        session.connect(token, function(error) {
            if (error)
            {
                if (error.code == 1006)
                {
                    alertify.alert("You are not connected to the internet. Check your network connection.");
                    return false;
                }
                else if (error.code == 1004)
                {
                    alertify.alert('Token Expired');
                    $('button.end-call').trigger('click');
                    return false;
                }
            }
            $('.profile_doc_pik').hide();
			interval = countdown_timer_call('<?php echo convert_from_sql_time('d M Y H:i:s', current($get_apppointment_details)->end_time); ?>', 'stopwatch'); /*Start timer*/
            publisher = OT.initPublisher('myPublisher', {name: "Tony", width:180, height:180});
			publish();
			
        });
        
        session.on("streamDestroyed", function(event) {
            $('button.end-call').trigger('click');
        });

    function unpublish() {
        session.unpublish(publisher);
    }
    function publish() {
        session.publish(publisher);
    }
    function start_archieve() {
        $.ajax(
        {
            type : 'post',
            url : SITE_URL + '<?php echo $this->current_module ?>/start_archieve',
            data: $('form#tokbox_details').serialize(),
            dataType:'json',
            success: function(data)
            {
                $('form#tokbox_details input[name=archieve_id]').val(data.archieve_id);
            }
        });
    }
    
    function stop_archieve() {
        $.ajax(
        {
            type : 'post',
            url : SITE_URL + '<?php echo $this->current_module ?>/stop_archieve',
            data: $('form#tokbox_details').serialize(),
            dataType:'json',
            success: function(data)
            {

            }
        });
    }
    function unsubscribe() {
        session.unsubscribe()
    }
    function disconnect() {
        session.disconnect();
    }

    $(document).on('click', 'button.end-call', function()
    {
        var current_element = $(this);

        $.ajax(
        {
            type : 'post',
            url : SITE_URL + '<?php echo $this->current_module ?>/end_call',
            data: $('form#tokbox_details').serialize(),
            dataType:'json',
            success: function(data)
            {
                disconnect();
                unpublish();
                //unsubscribe();
                
                stop_archieve();
                $('.profile_doc_pik').show();
                $('.profile_patient_pik').show();
                //TIMER_STOPWATCH.Timer.stop();
                //clearInterval(interval);
                current_element.removeClass('end-call').addClass('start-call').text('Start Call');
            }
        });
    });

    $(document).on('click', 'button#snapshot', function()
    {
        var imgData = subscriber.getImgData();
        
        var post_data = {'image_data' : imgData, 'appointment_id' : $(this).data('appointment_id'), 'patient_id' : $(this).data('patient_id')};

        $.post(SITE_URL + 'operations/save_image_from_data', post_data, function(response)
        {
			response = $.parseJSON(response);
			$('#snapshots_images').append('<img src="' + response.url + '" />');
		});
        //var img = document.createElement("img");
        //img.setAttribute("src", "data:image/png;base64," + imgData);
        //var imgWin = window.open("about:blank", "Screenshot, width=500, height=500");
        //imgWin.document.write("<body></body>");
        //imgWin.document.body.appendChild(img);
    });

</script>
<?php endif; ?>
