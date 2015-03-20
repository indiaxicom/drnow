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
    setInterval('save_appointment_notes()', 60000);

    /*Countdown timer for next or coming appointment*/
    <?php if (!empty($get_next_appointment->start_time)) : ?>
        countdown_timer("<?php echo convert_from_sql_time('d M Y H:i:s', $get_next_appointment->start_time); ?>", "countdown_appointment");
    <?php endif; ?>
    /*Script for autocomplete prescriptions*/
    function create_formulary_list( message )
    {console.log(message);
        $( "ul.list1" ).prepend( '<li><label>' + message.value + '</label><a href="#" data-prescription_id="' + message.id + '" class="cancel">&nbsp;</a></li>' );
        $( "ul.list1" ).scrollTop( 0 );

        $( "ul.list2" ).prepend( '<li> <p>' + message.value  + ' </p><p>Price / Unit : <strong> ' + message.unit_price + '</strong></p><a class="cancel" data-prescription_id="' + message.id + '" href="#">&nbsp;</a><div class="btn-sec"><input type="hidden" name=prescription[' + message.id + '][nm] value="' + message.value + '" /><input type="text" name=prescription[' + message.id + '][dose] placeholder="Dose" /><input type="text" name=prescription[' + message.id + '][frequency] placeholder="Frequency" /><input name=prescription[' + message.id + '][total] type="text" placeholder="Total" /></div></li>' );
        $( "ul.list2" ).scrollTop( 0 );

        $("form#appointment_notes").find('input[name=unchanged_text]').val('1');
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
});

$(document).on('click', 'button.start-call', function()
{
    alertify.confirm("Call is about to begin Continue ?", function (e)
    {
        if (e) {
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
        }
        else
        {
            alertify.error("You cancelled the operation");
        }
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
            interval = countdown_timer_call('<?php echo convert_from_sql_time('d M Y H:i:s', current($get_apppointment_details)->end_time); ?>', 'stopwatch'); /*Start timer*/
            $('.profile_doc_pik').hide();
            publisher = OT.initPublisher('myPublisher', {name: "<?php echo $this->session->flashdata('name'); ?>", width:180, height:180});
            publish();
        });

        session.connect(token, function(error) {
            if (error)
            {
                if (error.code == 1006)
                {
                    alertify.alert("You are not connected to the internet. Check your network connection.");
                }
                else if (error.code == 1004)
                {
                    alertify.alert('Token Expired');
                    $('button.end-call').trigger('click');
                    return false;
                }
            }
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
                //TIMER_STOPWATCH.Timer.stop();
                clearInterval(interval);
                current_element.removeClass('end-call').addClass('start-call').text('Start Call');
            }
        });
    });

    $(document).on('click', 'button#snapshot', function()
    {
        var imgData = subscriber.getImgData();
        var img = document.createElement("img");
        img.setAttribute("src", "data:image/png;base64," + imgData);
        var imgWin = window.open("about:blank", "Screenshot, width=500, height=500");
        imgWin.document.write("<body></body>");
        imgWin.document.body.appendChild(img);
    });

</script>
<?php endif; ?>
