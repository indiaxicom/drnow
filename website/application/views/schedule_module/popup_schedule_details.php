<?php
$patient_details = current($patient_details);
?>
<div class="schedule-pop-box">
    <div class="head-con">
        <div class="profile-img">
            <figure>
                <?php if (!empty($patient_details->PatientAvatar)) : ?>
                    <img id="profile_pik" onerror="this.src='<?php echo image_url_path('short-img.png'); ?>'" src="<?php echo $patient_details->PatientAvatar; ?>" alt="" />
                <?php else : ?>
                    <img id="profile_pik" src="<?php echo image_url_path('short-img.png'); ?>" alt="" />
                <?php endif; ?>
            </figure>
            <div class="details">
               <h2> <?php echo !empty($patient_details->PatientForename) ? $patient_details->PatientTitle . ' ' . $patient_details->PatientForename . ' ' . $patient_details->PatientSurname : NULL; ?></h2>
                <span>
                    <?php echo !empty($patient_details->PatientDOB) ? $patient_details->PatientDOB : NULL; ?>
                    <?php echo !empty($patient_details->PatientDOB) && find_age($patient_details->PatientDOB) != FALSE ? '(' . find_age($patient_details->PatientDOB) . ' years old)' : NULL; ?>
                    <br /> Private Medical Care
                </span>
            </div>
        </div>
    </div>

    <div class="schedule-pop-con"> 
        <div class="col left-col">
            <section>
                <div class="heading bdrTop0"> Medical History</div>
                <div class="content-section">
					<p><?php echo !empty($patient_details->PatientPreExisting) ? $patient_details->PatientPreExisting : 'No Medical History'; ?></p>
                </div>
            </section>
            <section>
                <div class="heading"> Allergies </div>
                <div class="content-section">
                    <p><?php echo !empty($patient_details->PatientAllergies) ? $patient_details->PatientAllergies: '--'; ?></p>
                </div>
            </section>
            <section>
                <div class="heading"> Patient Notes </div>
                <div class="content-section">
					<p><?php echo !empty($patient_notes_str) ? $patient_notes_str : '--'; ?></p>
                </div>
            </section>
        </div>

        <div class="col minHgt left-col">
            <section>
                <div class="heading bdrTop0">History</div>
                <div class="content-section">
                    <p><?php echo !empty($get_apppointment_details->history) ? $get_apppointment_details->history : '--';  ?></p>
                </div>
            </section>
            <section>
                <div class="heading"> Exmination </div>
                <div class="content-section">
                    <p><?php echo !empty($get_apppointment_details->examination) ? $get_apppointment_details->examination : '--';  ?></p>
                </div>
            </section>
        </div>

        <div class="col left-col minHgt mrgR0">
            <section>
                <div class="heading bdrTop0">Diagnosis</div>
                <div class="content-section">
                    <ul class="diagno-lst">
                        <li>
                            <div class="left"><?php echo !empty($get_apppointment_details->diagnosis) ? $get_apppointment_details->diagnosis : '--';  ?></div>
                        <li>
                    </ul>
                </div>
            </section>
            <section>
                <div class="heading"> Management </div>
                <div class="content-section">
                    <p><?php echo !empty($get_apppointment_details->management) ? $get_apppointment_details->management : '--';  ?></p>
                </div>
            </section>
        </div>
        <div class="clear">&nbsp;</div>
    </div>
</div>
