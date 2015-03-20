/* Use only this document ready*/

$(document).ready(function()
{
   setInterval('updateClock()', 1000);

    /*Script for stopwatch*/
    $.timer = function(func, time, autostart) {  
        this.set = function(func, time, autostart) {
            this.init = true;
            if(typeof func == 'object') {
                var paramList = ['autostart', 'time'];
                for(var arg in paramList) {if(func[paramList[arg]] != undefined) {eval(paramList[arg] + " = func[paramList[arg]]");}};
                func = func.action;
            }
            if(typeof func == 'function') {this.action = func;}
            if(!isNaN(time)) {this.intervalTime = time;}
            if(autostart && !this.isActive) {
                this.isActive = true;
                this.setTimer();
            }
            return this;
        };
        this.once = function(time) {
            var timer = this;
            if(isNaN(time)) {time = 0;}
            window.setTimeout(function() {timer.action();}, time);
            return this;
        };
        this.play = function(reset) {
            if(!this.isActive) {
                if(reset) {this.setTimer();}
                else {this.setTimer(this.remaining);}
                this.isActive = true;
            }
            return this;
        };
        this.pause = function() {
            if(this.isActive) {
                this.isActive = false;
                this.remaining -= new Date() - this.last;
                this.clearTimer();
            }
            return this;
        };
        this.stop = function() {
            this.isActive = false;
            this.remaining = this.intervalTime;
            this.clearTimer();
            return this;
        };
        this.toggle = function(reset) {
            if(this.isActive) {this.pause();}
            else if(reset) {this.play(true);}
            else {this.play();}
            return this;
        };
        this.reset = function() {
            this.isActive = false;
            this.play(true);
            return this;
        };
        this.clearTimer = function() {
            window.clearTimeout(this.timeoutObject);
        };
        this.setTimer = function(time) {
            var timer = this;
            if(typeof this.action != 'function') {return;}
            if(isNaN(time)) {time = this.intervalTime;}
            this.remaining = time;
            this.last = new Date();
            this.clearTimer();
            this.timeoutObject = window.setTimeout(function() {timer.go();}, time);
        };
        this.go = function() {
            if(this.isActive) {
                this.action();
                this.setTimer();
            }
        };
        
        if(this.init) {
            return new $.timer(func, time, autostart);
        } else {
            this.set(func, time, autostart);
            return this;
        }
    };
    /*-------------Script for stopwatch Ends---------*/
});

function show_popup (show_div, hide_div) 
{
    $('html,body').animate({ scrollTop: 0 }, 'slow');
    $('.'+show_div).show();
    if (hide_div != null)
    {
        $('.'+hide_div).hide();
    }
    return false;
}

function custom_form_submit(form_id)
{
    $('#'+form_id).submit();
}

function goToByScroll(id) 
{
    
    id = id.replace("link", "");
      // Scroll
    $('html,body').animate({
        scrollTop: $("#"+id).offset().top},
        'slow');
}

function validateEmail (sEmail) 
{
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else 
    {
        return false;
    }
}

function show_fav ()
    {
        $( ".fav-icon i").toggleClass("fa-heart");
        $( ".fav-icon i").toggleClass("fa-heart-o");
    }

function confirm_delete(url, msg)
{
    if (msg != '')
    {
        if (confirm(msg))
        {
            window.location.href = url;
        }
    }
    else
    {
        if (confirm('Are you Sure you want to delete ?'))
        {
            window.location.href = url;
        }
    }
    return false;
}

function show_hide(id)
{
    var stat = $('#'+id).toggle('slow');
}


/* Function to calculate character length and display current chracter count
   Author       : Punit
   Created      : 14-10-2014
   @param       : input div, max length for validation, counter div to display current status*/
function cal_length(target, max_length, display_counter_div)
{
    if(target.val().length <= max_length)
    {
        display_counter_div.html('Max '+ (100 - target.val().length) +' symbols');
        return target.val().length;
    }
    else
    {
        display_counter_div.html('Max 100 symbols');
        return false;
    }
}

/* Create and update clock*/ 
function updateClock ( )
{
    var currentTime = calcTime('0'); /*For London*/
    var currentHours = currentTime.getHours ( );
    var currentMinutes = currentTime.getMinutes ( );
    var currentSeconds = currentTime.getSeconds ( );
 
    // Pad the minutes and seconds with leading zeros, if required
    currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
    currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
 
    // Choose either "AM" or "PM" as appropriate
    //var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";
 
    // Convert the hours component to 12-hour format if needed
    //currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
 
    // Convert an hours component of "0" to "12"
    currentHours = ( currentHours == 0 ) ? 12 : currentHours;
    currentHours = ( currentHours < 10 ) ? "0" + currentHours : currentHours;

    // Compose the string for display
    var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds;

    $("span#clock").html(currentTimeString);
         
 }

/*padding a number to two digits*/
function pad_number_2(number) {
     return (number < 10 ? '0' : '') + number
}

 function calcTime(offset) {
    var date = new Date();
    var utc = date.getTime() + (date.getTimezoneOffset() * 60000);
    var newDate = new Date(utc + (3600000 * offset));
    return newDate;
}

function countdown_timer (day, id)
{
    var BigDay = new Date(day);
    var msPerDay = 24 * 60 * 60 * 1000 ;

    var interval = setInterval(function()
    {
        var today = calcTime('+0'); /*For London*/

        var timeLeft = (BigDay.getTime() - today.getTime());

        var e_daysLeft = timeLeft / msPerDay;
        var daysLeft = Math.floor(e_daysLeft);

        var e_hrsLeft = (e_daysLeft - daysLeft)*24;
        var hrsLeft = Math.floor(e_hrsLeft);

        var e_minsLeft = (e_hrsLeft - hrsLeft)*60;
        var minsLeft = Math.floor(e_minsLeft);

        var e_secsLeft = (e_minsLeft - minsLeft)*60;
        var secsLeft = Math.floor(e_secsLeft);

        if (daysLeft == 0 && hrsLeft == 0 && minsLeft == 0 && secsLeft == 0)
        {
            clearInterval(interval);
            $('button.start-call').removeAttr('disabled').trigger('click');

            $.post(SITE_URL + 'operations/get_next_appointment', '', function(response)
            {
                response = $.parseJSON(response);

                if (response.start_time == '0')
                {
                    $('#' + id).text('No more appointments');
                }
                else
                {
                    countdown_timer(response.start_time, id);
                }

            });
        }

        var timeString = pad_number_2(hrsLeft) + " : " + pad_number_2(minsLeft) + " : " + pad_number_2(secsLeft);
        
        timeString = daysLeft == 0 ? timeString : daysLeft + " days : " + timeString;
        $('#' + id).html(timeString);
    }, 1000);
}


function save_appointment_notes()
{
    var form_element = $('form#appointment_notes');
    var form_prescription = $('form#appointment_prescription');
    var form_outcome = $('form#appointment_outcomes');
    
    var unchanged_text = form_element.find('input[name=unchanged_text]').val();
    var response_messages = {
		"icd_response" : "ICD Registered Successfully", 
		"referal_response" : "Referal Added", 
		"outcome_response" : "Appointmnet Conclusion Sent Succefully", 
		"patient_notes_response" : "Patient Notes Sent Successfully", 
		"sign_response" : "Prescription notification sent to patient successfully", 
		
	};

    if(unchanged_text == 1)
    {
        $.ajax
        ({
            type : 'post',
            url : SITE_URL + 'appointments/save_appointment_notes',
            data: form_element.serialize() + '&' + form_prescription.serialize(),
            dataType:'json',
            success: function(data)
            {
                form_element.find('input[name=id]').val(data.presc_id);
                form_outcome.find('input[name=id]').val(data.presc_id);
                form_element.find('input[name=unchanged_text]').val('0');

                if (data.presc_status == true)
                {
					form_outcome.find('input[name=outcome_allowed]').val('1');
                    $('div.save_prescription').find('a').text('Prescription Saved Sucessfully').closest('div').removeClass('save_prescription');
                    alertify.success("Prescription saved successfully");
                }
                $.each(data.app_status, function(index, item) 
                {
					if (item == true)
					{
						alertify.success(response_messages[index]);
					}
				});
            }
        });
    }
}

function countdown_timer_call (day, id)
{
    var BigDay = new Date(day);
    var msPerDay = 24 * 60 * 60 * 1000 ;

    var interval = setInterval(function(){
        var today = calcTime('+0'); /*For London*/

        var timeLeft = (BigDay.getTime() - today.getTime());

        var e_daysLeft = timeLeft / msPerDay;
        var daysLeft = Math.floor(e_daysLeft);

        var e_hrsLeft = (e_daysLeft - daysLeft)*24;
        var hrsLeft = Math.floor(e_hrsLeft);

        var e_minsLeft = (e_hrsLeft - hrsLeft)*60;
        var minsLeft = Math.floor(e_minsLeft);

        var e_secsLeft = (e_minsLeft - minsLeft)*60;
        var secsLeft = Math.floor(e_secsLeft);

        var timeString = pad_number_2(minsLeft) + " : " + pad_number_2(secsLeft);
        $('#' + id).html(timeString);

        if (minsLeft == 0 && secsLeft == 0)
        {
            clearInterval(interval);
            $('button.start-call').removeAttr('disabled');
            $('button.end-call').trigger('click');
            
            setInterval(function(){
				$('.appoint_tab').trigger('click');
			}, 2000);
        }
    }, 1000);
    return interval;
}

/*Popup Script*/
$(document).on('click', '.open_popup', function(event) 
{
    event.preventDefault();
    url = $(this).attr('href');

    $('.popup-otr').fadeIn();
    $('.popup-modal-content').html('<div style="text-align:center"><i class="fa fa-refresh fa-spin fa-3x"></i></div>');

    $.ajax({
        type: "post",
        url: url,
        dataType:'json',
        success: function(json)
        {
            $('.popup-modal-content').html(json.content);
        }
    });
});

$(document).on('click', '.close_popup', function(event) 
{
    event.preventDefault();
    $('.popup-otr').hide('slow');
    $('.popup-modal-content').html('');
});

$(window).load(function() {
    $(".loader").fadeOut("slow");
});

function call_alert (day, id, url)
{
    var BigDay = new Date(day);
    var msPerDay = 24 * 60 * 60 * 1000 ;

    var interval = setInterval(function(){
        var today = calcTime('+0'); /*For London*/

        var timeLeft = (BigDay.getTime() - today.getTime());

        var e_daysLeft = timeLeft / msPerDay;
        var daysLeft = Math.floor(e_daysLeft);

        var e_hrsLeft = (e_daysLeft - daysLeft)*24;
        var hrsLeft = Math.floor(e_hrsLeft);

        var e_minsLeft = (e_hrsLeft - hrsLeft)*60;
        var minsLeft = Math.floor(e_minsLeft);

        var e_secsLeft = (e_minsLeft - minsLeft)*60;
        var secsLeft = Math.floor(e_secsLeft);

        var timeString = pad_number_2(minsLeft) + " : " + pad_number_2(secsLeft);
        $('#' + id).html(timeString);

        if (daysLeft == 0 && hrsLeft == 0 && minsLeft == 0 && secsLeft <= 59)
        {
            clearInterval(interval);
            alertify.confirm("Appointment is about to begin. Go to Appointment section ?", function (e)
			{
				if (e) {
					window.location.href = url;
				}
				else
				{
					alertify.error("Click on the appointments tab to begin appointment");
				}
			});
        }
    }, 1000);
    return interval;
}
