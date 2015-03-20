<div class="col left-col">
    <section>
        <div class="content-section">
            <div class="profile-img">
                <figure>
                    <?php if (!empty($patient_details->PatientAvatar)) : ?>
                        <img id="profile_pik" onerror="this.src='<?php echo image_url_path('short-img.png'); ?>'" src="<?php echo $patient_details->PatientAvatar; ?>" alt="" />
                    <?php else : ?>
                        <img id="profile_pik" src="<?php echo image_url_path('short-img.png'); ?>" alt="" />
                    <?php endif; ?>
                </figure>
                <div class="details">
                    <h2> <?php echo !empty($patient_details->PatientForename) ? $patient_details->PatientForename . ' ' . $patient_details->PatientSurname : NULL; ?></h2>
                    <span>
                        <?php echo !empty($patient_details->PatientDOB) ? $patient_details->PatientDOB : NULL; ?>
                        (<?php echo !empty($patient_details->PatientDOB) ? date('Y') - date('Y', uk_date_to_stamp($patient_details->PatientDOB)) : NULL; ?> years old)
                        <br /> Private Medical Care
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="heading"> Allergies</div>
        <div class="content-section">
            <p><?php echo !empty($patient_details->PatientAllergies) ? $patient_details->PatientAllergies : 'No Allergies'; ?></p>
        </div>
    </section>

    <section>
        <div class="heading"> Medical History </div>
        <div class="content-section">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  </p>
        </div>
    </section>

    <section>
        <div class="heading"> Dr Now History </div>
        <div class="content-section">
            <?php if (!empty($history_appointments)) : ?>
                <?php $i = 1; ?>
                <?php foreach ($history_appointments as $val) : ?>
                    <div class="apoint">
                        <span>Appointment <?php echo $i; ?></span>
                        <span class="right"> <?php echo convert_from_sql_time('d M y', $val->start_time); ?></span><br>
                        <span>Dr. <?php echo $val->first_name . ' ' . $val->last_name ?></span>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                    <?php $i++; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <p> No history Availble </p>
            <?php endif; ?>
        </div>
    </section>

    <section>
        <div class="heading gry-bg"> Prescription(s) </div>
        <div class="content-section">
            <div class="form-section new-pescrip">
                <form action="#">
                    <input id="prescription" type="text" class="strok-field" placeholder="Start typing here" />
                </form>
                <ul class="prescrp-list"></ul>
            </div>
        </div>
    </section>    
</div>

