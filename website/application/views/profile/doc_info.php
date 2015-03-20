<?php
 $get_user_details = !empty($get_user_details) ? current($get_user_details) : array();
?>

<div class="c-left">
    <h4 class="tittle"> Doctor Profile</h4>

    <div class="profile-section">
        <div class="profile-photo">
            <i class="fa fa-camera fa-2x"></i>
            <img src="<?php echo base_url() . PROFILE_IMAGE_PATH . 'cropped/' . session_data('profile_image'); ?>" onerror="this.src='<?php echo image_url_path('user.gif'); ?>'" />
        </div>

        <div class="profile-detail">
            <ul>
                <li>
                    <label>Full Title:</label>
                    <strong>Dr. <?php echo session_data('first_name') . ' ' . session_data('last_name'); ?></strong>
                </li>
                <li>
                    <label>First Name:</label>
                    <span class="pull-right edit_details" data-field="first_name"><i class="fa fa-edit"></i></span>
                    <strong><?php echo session_data('first_name'); ?> </strong>
                </li>
                <li>
                    <label>Last Name:</label>
                    <span class="pull-right edit_details" data-field="last_name"><i class="fa fa-edit"></i></span>
                    <strong><?php echo session_data('last_name'); ?></strong>
                </li>
                <li>
                    <label>Dr. Salutation:</label>
                    <span class="pull-right edit_details" data-field="salutation"><i class="fa fa-edit"></i></span>
                    <strong><?php echo !empty($get_user_details['salutation']) ? $get_user_details['salutation'] : '--'; ?></strong>
                </li>
                <li>
                    <label>GMC Number:</label>
                    <span class="pull-right edit_details" data-field="gmc_no"><i class="fa fa-edit"></i></span>
                    <strong><?php echo !empty($get_user_details['gmc_no']) ? $get_user_details['gmc_no'] : '--'; ?></strong>
                </li>
            </ul>
        </div>
    </div>

    <div class="description-section">
        <div class="bio-detail">
            <h4 class="bio-text">Bio</h4>
            <div class="bio-section sign">
                <p><?php echo !empty($get_user_details['bio']) ? $get_user_details['bio'] : NULL; ?></p>
                <span data-field="bio" class="edit-button bio_edit_details">
                    <i class="fa fa-edit"></i>
                </span>
            </div>
        </div>

        <div class="bio-detail right">
            <h4 class="bio-text">Signature</h4>
            <div class="bio-section sign">
                <?php if (session_data('signature_image') != NULL) : ?>
                    <img onerror="this.src=''" src="<?php echo base_url() . SIGNATURE_IMAGE_PATH . 'cropped/' . session_data('signature_image'); ?>" alt="Tap edit sign to add signature" />
                <?php else : ?>
                    <p>Tap edit sign to add signature</p>
                <?php endif; ?>
                <span class="edit-button signature_pik">
                    <i class="fa fa-edit"></i>
                </span>
            </div>
        </div>
    </div>
</div>
<a class="open_popup" href=""></a>
<?php echo form_open('', 'id="profile_form"') ; ?>
<?php echo form_upload('userfile', '','style="display:none"'); ?>
<?php echo form_hidden('image_type', ''); ?>
<?php echo form_close() ; ?>
