<?php
$doctor_apppointment_details = $get_apppointment_details[USER_DOCTOR];
?>
<div class="middle-col">
    <div class="big-img">
        <figure>
            <div id="subscriber"></div>
            <?php if (!empty($patient_details->PatientAvatar)) : ?>
                <img id="profile_pik" onerror="this.src='<?php echo image_url_path('pro-img.jpg'); ?>'"  src="<?php echo $patient_details->PatientAvatar; ?>" alt="" />
            <?php else : ?>
                <img id="profile_pik" src="<?php echo image_url_path('pro-img.jpg'); ?>" alt="" />
            <?php endif; ?>
        </figure>
        <div class="b-sec">
            <div class="buttons-sec">
                <?php if (session_data('tokbox_data') != NULL) : ?>
                    <button class="end-call"> End Call </button>
                <?php else: ?>
                    <button class="start-call"> Start Call </button>
                <?php endif; ?>
                <button class="cam" id="snapshot"></button>
                <p class="gry-bg">
                    <?php echo !empty($get_next_appointment->id) && $get_next_appointment->id == $doctor_apppointment_details->id ? 'Starts' : 'Next' ?> :
                    <span id="countdown_appointment"></span>
                </p>
            </div>
            <div class="image-sec">
                <figure>
                    <div id="myPublisher"></div>
                    <img id="profile_pik" src="<?php echo base_url() . PROFILE_IMAGE_PATH . 'cropped/' . session_data('profile_image'); ?>" onerror="this.src='<?php echo image_url_path('user.gif'); ?>'" />
                </figure> 
                <p class="gry-bg" id="stopwatch">00:00:00</p>
            </div>
            <div class="conclusion-sec">
                <a href="<?php echo base_url() . $this->current_module . '/conclusion/' . $appointment_hash ?>"><p class="gry-bg"> Conclusion</p></a>
            </div>
        </div>
    </div>

    <?php
    echo form_open('', 'id="tokbox_details"');
    $data = array(
      'tokbox_session_key' => $doctor_apppointment_details->tokbox_session_key,
      'tokbox_token' => $doctor_apppointment_details->token,
      'appointment_id' => $doctor_apppointment_details->id,
      'archieve_id' => NULL,
    );
    echo form_hidden($data);
    echo form_close();
    ?>
</div>
