<?php
$doctor_apppointment_details = $get_apppointment_details[USER_DOCTOR];
$patient_apppointment_details = $get_apppointment_details[USER_PATIENT];

?>
<div class="live-app-sec">
    <div class="appoint-sec">
        <div class="col left-col">
            <section>
                <div class="content-section">
                    <div class="profile-img">
                        <figure>
                            <?php if (!empty($patient_details->PatientAvatar)) : ?>
                                <img onerror="this.src='<?php echo image_url_path('short-img.png'); ?>'" src="<?php echo $patient_details->PatientAvatar; ?>" alt="" />
                            <?php else : ?>
                                <img src="<?php echo image_url_path('short-img.png'); ?>" alt="" />
                            <?php endif; ?>
                        </figure>
                        <div class="details">
                            <h2> <?php echo !empty($patient_details->PatientForename) ? $patient_details->PatientForename . ' ' . $patient_details->PatientSurname : NULL; ?></h2>
                            <span>
                                <?php echo !empty($patient_details->PatientDOB) ? $patient_details->PatientDOB : NULL; ?>
                                <?php echo !empty($patient_details->PatientDOB) ? '(' . find_age($patient_details->PatientDOB) . ' years old)' : NULL; ?>
                                <br /> Private Medical Care
                            </span>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="heading more_info_toogle"> More Info <a class="arw-dwn" href="javascript:void(0)"><i class="fa fa-chevron-down"></i></a> </div>
            </section>

            <div class="more-info-cont" style="display:none">
                <section>
                    <div class="heading"> Allergies</div>
                    <div class="content-section">
                        <p><?php echo !empty($patient_details->PatientAllergies) ? $patient_details->PatientAllergies : 'No Allergies'; ?></p>
                    </div>
                </section>
                <section>
                    <div class="heading"> Medical History </div>
                    <div class="content-section">
                       <p><?php echo !empty($patient_details->PatientPreExisting) ? $patient_details->PatientPreExisting : 'No Medical History'; ?></p>
                    </div>
                </section>
				<section>
                    <div class="heading"> Patient Notes</div>
                    <div class="content-section">
                        <p><?php echo !empty($patient_notes) ? $patient_notes : 'No notes entered by patient'; ?></p>
                    </div>
                </section>
                <section>
                    <div class="heading"> Dr Now History </div>
                    <div class="content-section">
                        <?php if (!empty($history_appointments)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($history_appointments as $val) : ?>
                                <div class="apoint">
                                    <span><?php echo $i; ?>.  Dr. <?php echo $val->first_name . ' ' . $val->last_name ?></span>
									<span class="right"> <?php echo convert_from_sql_time('d M y', $val->start_time); ?></span>
                                    <span class="right"> <a href="<?php echo base_url() . 'schedule/schedule_details/' . $val->id ?>" class="details-link open_popup"> <i class="fa fa-eye fa-2x"></i> </a></span><br>
                                </div>
                                <p></p>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p> No history Availble </p>
                        <?php endif; ?>
                    </div>
                </section>

            </div>
        </div>
        <div class="col left-col">
            <?php echo form_open('', 'id="appointment_notes"'); ?>
                <section>
                    <div class="heading"> History </div>
                    <div class="content-section">
                        <?php
                        $params = array(
                            'name' => 'history',
                            'class' => 'line-text notes',
                            'placeholder' => 'start typing here...',
                            //'disabled' => 'disabled'
                            'value' => !empty($doctor_apppointment_details->history) ? $doctor_apppointment_details->history : NULL
                        );
                        if (session_data('tokbox_data') != NULL)
                        {
                            unset($params['disabled']);
                        }
                        echo form_textarea($params);
                        ?>
                    </div>
                </section>

                <section>
                    <div class="heading"> Examination </div>
                    <div class="content-section">
                        <?php
                        $params = array(
                            'name' => 'examination',
                            'class' => 'line-text notes',
                            'placeholder' => 'start typing here...',
                            //'disabled' => 'disabled'
							'value' => !empty($doctor_apppointment_details->examination) ? $doctor_apppointment_details->examination : NULL
                        );
                        if (session_data('tokbox_data') != NULL)
                        {
                            unset($params['disabled']);
                        }
                        echo form_textarea($params);
                        ?>
                    </div>
                </section>

                <section>
                    <div class="heading"> Diagnosis </div>
                    <div class="content-section">
                        <?php
                        $params = array(
                            'name' => 'diagnosis',
                            'class' => 'line-text notes',
                            'placeholder' => 'start typing here...',
                            //'disabled' => 'disabled',
                            'value' => !empty($doctor_apppointment_details->diagnosis) ? $doctor_apppointment_details->diagnosis : NULL
                            
                        );
                        if (session_data('tokbox_data') != NULL)
                        {
                            unset($params['disabled']);
                        }
                        echo form_textarea($params);
                        ?>
                    </div>
                </section>
    
                <section>
                    <div class="heading gry-bg"> Management </div>
                    <div class="content-section">
                        <?php
                        $params = array(
                            'name' => 'management',
                            'class' => 'line-text',
                            'placeholder' => 'start typing here...',
                            //'disabled' => 'disabled',
                            'value' => !empty($doctor_apppointment_details->management) ? $doctor_apppointment_details->management : NULL
                        );
                        if (session_data('tokbox_data') != NULL)
                        {
                            unset($params['disabled']);
                        }
                        echo form_textarea($params);
                        ?>
                    </div>
                </section>
                <?php
                $hidden_params = array(
                    'appointment_id' => current($get_apppointment_details)->id,
                    'id' => current($get_apppointment_details)->prescription_id != NULL ? current($get_apppointment_details)->prescription_id : '0',
                    'unchanged_text' => '0',
                    'patient_id' => $patient_id
                );
                if (!empty($patient_details))
                {
                    $patient_details_arr = (array)$patient_details;
                    
                    foreach($patient_details_arr as $key => $val)
                    {
                        $hidden_params[$key] = $val;
                    }
                }
                echo form_hidden($hidden_params);
                ?>
            <?php echo form_close(); ?>
        </div>
    </div>

    <div class="middle-col">
        <div class="big-img">
            <figure>
                <div id="subscriber"></div>
                <?php if (!empty($patient_details->PatientAvatar)) : ?>
                    <img class="profile_patient_pik" onerror="this.src='<?php echo image_url_path('pro-img.jpg'); ?>'"  src="<?php echo $patient_details->PatientAvatar; ?>" alt="" />
                <?php else : ?>
                    <img class="profile_patient_pik" src="<?php echo image_url_path('pro-img.jpg'); ?>" alt="" />
                <?php endif; ?>
            </figure>
            <div class="b-sec">
                <div class="buttons-sec">
					<div id="snapshots_images"></div>
                    <?php if (session_data('tokbox_data') != NULL) : ?>
                        <button class="end-call"> End Call </button>
                    <?php else: ?>
                        <button <?php echo (strtotime($doctor_apppointment_details->start_time) > time() || (strtotime($doctor_apppointment_details->end_time) < time())) ? 'disabled="disabled"' : NULL; ?> class="start-call"> Start Call </button>
                    <?php endif; ?>
                    <button class="cam" id="snapshot" data-patient_id="<?php echo $patient_apppointment_details->user_id; ?>" data-appointment_id="<?php echo $doctor_apppointment_details->id; ?>"></button>
                    <p class="gry-bg">
                        <?php if (!empty($get_next_appointment)) : ?>
                            <?php echo !empty($get_next_appointment->id) && $get_next_appointment->id == $doctor_apppointment_details->id ? 'Appointment starts in' : 'Next Appointment' ?>:
                            <span id="countdown_appointment"></span>
                        <?php else : ?>
                            No more Appointments
                        <?php endif; ?>
                    </p>
                </div>
                <div class="image-sec">
                    <figure>
                        <div id="myPublisher" style="margin-top:5px"></div>
                        <img class="profile_doc_pik" id="profile_pik" src="<?php echo base_url() . PROFILE_IMAGE_PATH . 'cropped/' . session_data('profile_image'); ?>" onerror="this.src='<?php echo image_url_path('user.gif'); ?>'" />
                    </figure> 
                    <p class="gry-bg" id="stopwatch">00:00:00</p>
                </div>
                <?php /*
                <div class="conclusion-sec">
                    <a href="<?php echo base_url() . $this->current_module . '/conclusion/' . $appointment_hash ?>"><p class="gry-bg"> Conclusion</p></a>
                </div>
                */ ?>
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
</div>
