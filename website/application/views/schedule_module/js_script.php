<script type="text/javascript" src="<?php echo js_url_path('moment.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo js_url_path('fullcalendar.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        /*------------------Code for tooltip starts here---------------*/
        $( document ).tooltip({
            content: function(callback)
            {
                var current_element = $(this);
                var TTtmr = setTimeout( function()
                {
                    var date = current_element.data('date');

                    if (current_element.data('shifts') == '1')
                    {
                        callback( '<i class="fa fa-circle-o-notch fa-spin"></i>' );
                        $.post(SITE_URL + 'shifts/get_shifts_by_date', 'date=' + date, function(response)
                        {
                            response = $.parseJSON(response);
                            var str = '<div align="center">Shifts</div>';

                            $.each(response, function(key, value)
                            {
                                str += '<div>' + value + '</div>';
                            });
                            callback( str );
                        });
                    }
                }, 800 );
                current_element.mouseleave( function() { clearTimeout( TTtmr ); } );
            }
        });
        /*------------------Code for tooltip ends here-----------------*/
        
        /*Countdown timer for next or coming appointment*/
		<?php if (!empty($get_next_appointment->start_time)) : ?>
			var url = '<?php echo base_url('appointments/conference/' . encryptor('encrypt', $get_next_appointment->id)) ?>';
			call_alert("<?php echo convert_from_sql_time('d M Y H:i:s', $get_next_appointment->start_time); ?>", "appointment_call_alert", url);
		<?php endif; ?>

        /*---------------Code for calendar starts here----------------*/
        var calendar = $('#calendar');
        var today = $.fullCalendar.moment( calendar.fullCalendar( 'getDate' ) ).format('YYYY-MM-DD');
        var available_shift_dates = <?php echo json_encode(array_keys($available_shift_dates)); ?>;

        calendar.fullCalendar({
            header: {
                left: '',
                right: 'prev, title, next'
            },
            selectable: true,
            timezone: 'UTC',
            dayRender: function (date, cell)
            {
                var format_date = $.fullCalendar.moment( date );

                if ($.inArray(format_date.format('YYYY-MM-DD'), available_shift_dates) > -1)
                {
                    $('.fc-day-number[data-date="' + format_date.format('YYYY-MM-DD') + '"]').attr('data-shifts', '1').attr('title', '');
                    cell.html('<span class="icon_calendar"><i class="fa fa-clock-o"></i></span>');
                }
                else
                {
                    cell.attr('data-shifts', '0');
                }

                if (format_date.format('YYYY-MM-DD') < today)
                {
                    cell.css('background-color', '#E5E5E5');
                }
            },
            select: function(start, end) {
                var eventData = {
                        id : 55,
                        title: 'Sami',
                        start: start,
                        end: end
                    };
            },
            eventLimit: true,
            slotDuration:'00:10:00',
            events: <?php echo json_encode($appointments_calendar_events); ?>,
            timeFormat: 'H:mm' ,
            dayClick: function(date, jsEvent, view) {
                /*These are events when a user hits on date square */
                var format_date = $.fullCalendar.moment( date ).format('YYYY-MM-DD');

                list_appointments(date); /*List events on right side*/
                
                $('.fc-day').removeClass('selected_date_calendar');

                /* This is to change the color of the selected date*/
                $('.fc-day[data-date="' + format_date + '"]').addClass('selected_date_calendar');
            },
            eventClick: function(calEvent, jsEvent, view) {
                //calendar.fullCalendar( 'removeEvents', calEvent.id );
                //calendar.fullCalendar('unselect');
            }
        });

        /* Setting Custom text on header */
        $('.fc-left').html('<h2>Schedule</h2>');
    });
    /*------------------Code for calendar ends here--------------------*/

    /*List appointments on clicking next and previous button*/
    $(document).on('click', '.fc-next-button, .fc-prev-button', function()
    {
        var calendar = $('#calendar');
        var date = calendar.fullCalendar( 'getDate' );
        list_appointments(date);
    });

    /*Listing Appointments on a particular date */
    function list_appointments(date)
    {
        date = $.fullCalendar.moment( date ).format('YYYY-MM-DD');

        $.post(SITE_URL + '<?php echo $this->current_module;?>/list_appointments/' + date, '', function(response)
        {
            response = $.parseJSON(response);
            $('div.schedule-right').replaceWith(response);
        });
    }

    $(window).ready(function(){
        var WinHeight = $('.schedule-left').height();
        $('.schedule-right').css('max-height', WinHeight)
    });
    
</script>
