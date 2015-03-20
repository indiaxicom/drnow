<h4 class="tittle"> Patient Profile</h4>

<div class="profile-section">

    <div class="profile-photo">
        <img id="profile_pik" onerror="this.src='<?php echo image_url_path('short-img.png'); ?>'" src="<?php echo $patient_details->PatientAvatar; ?>" alt="" />
    </div>

    <div class="profile-detail pd-page">
        <ul>
            <li>
                <label>FUll Name:</label>
                <strong><?php echo $patient_details->PatientTitle . ' ' . $patient_details->PatientForename . ' ' . $patient_details->PatientSurname; ?></strong>
            </li>
            <li>
                <label>Sex:</label>
                <strong><?php echo ($patient_details->PatientSex == MALE) ? 'Male' : 'Female'; ?> </strong>
            </li>
            <li>
                <label>DOB:</label>
                <strong>
                    <?php echo !empty($patient_details->PatientDOB) ? $patient_details->PatientDOB : NULL; ?>
                    <?php echo !empty($patient_details->PatientDOB) ? '(' . (date('Y') - date('Y', uk_date_to_stamp($patient_details->PatientDOB))) . ' years old)' : NULL; ?>)</strong>
            </li>
        </ul>
    </div>
</div>

<div class="content-sec-otr left-col live-appointment">
    <div class="col left-col">
        <section>
            <div class="heading bdrTop0"> Dr. Now History</div>
            <div class="content-section">
                <ul class="appoint-histry">
                    <li>
                        <h4>Appointment 1 <span class="date">6 June 2014</span></h4>
                        <p>Seen by Dr. Ad</p>
                    </li>
                    <li>
                        <h4><b>Diagnosis</b></h4>
                        <p>Flu</p>
                        <h4><b>Management</b></h4>
                        <p>Exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </li>
                    <li>
                        <h4>Appointment 2 <span class="date">6 June 2014</span></h4>
                        <p>Seen by Dr. Ad</p>
                    </li>
                    <li>
                        <h4><b>Diagnosis</b></h4>
                        <p>Flu</p>
                        <h4><b>Management</b></h4>
                        <p>Exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    </li>
                </ul>
            </div>
        </section>
    </div> 

    <div class="col left-col">
        <section>
            <div class="heading bdrTop0"> Mediacl History</div>
            <div class="content-section">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
            </div>
        </section>
    </div> 

    <div class="col left-col">
        <section>
            <div class="heading bdrTop0">  Allergies </div>
            <div class="content-section">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>

                <p>Eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
            </div>
        </section>
    </div> 
</div>
