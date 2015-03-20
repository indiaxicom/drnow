<div class="f-left">
    <!--START logo-section-->
    <div class="logo">
        <a href="<?php echo base_url(); ?>">
            <img src="<?php echo image_url_path('logo.png') ?>" />
        </a>
    </div>
    <!--END logo-section-->
    <!--START top-navigation-->
    <ul class="top-nevigation">
        <li>
            <a class="<?php echo !empty($current_module) && $current_module == 'schedule' ? 'active' : NULL; ?>" href="<?php echo base_url() . 'schedule' ?>">Schedule</a>
        </li>
        <li>
            <a class="<?php echo !empty($current_module) && $current_module == 'shifts' ? 'active' : NULL; ?>" href="<?php echo base_url() . 'shifts' ?>">Shifts</a>
        </li>
        <li>
        <?php if (!empty($get_current_appointment)) : ?>
            <a class="<?php echo !empty($current_module) && $current_module == 'appointments' ? 'active' : NULL; ?> appoint_tab" href="<?php echo base_url() . 'appointments/conference/' . encryptor('encrypt', $get_current_appointment->id); ?>">Appointments</a>
        <?php else : ?>
            <a class="<?php echo !empty($current_module) && $current_module == 'appointments' ? 'active' : NULL; ?> appoint_tab" href="<?php echo base_url() . 'appointments'; ?>">Appointments</a>
        <?php endif; ?>
        </li>
    </ul>
</div>
<div class="f-right">
    <?php if (is_doctor() == TRUE) : ?>
        <div class="description">
            <div class="dr-pik">
                <a href="<?php echo base_url() . 'profile' ?>">
                    <img src="<?php echo base_url() . PROFILE_IMAGE_PATH . 'cropped/' . session_data('profile_image'); ?>" onerror="this.src='<?php echo image_url_path('user.gif'); ?>'" />
                </a>
            </div>
            <div class="dr-name">
                <h4>
                    <a href="<?php echo base_url() . 'profile' ?>">
                        Dr <?php echo session_data('first_name') . ' ' . session_data('last_name') ?>
                    </a>
                </h4>
                <span id="clock"></span>
            </div>
        </div>
        <a class="sign-out" href="<?php echo base_url('logout') ?>">Sign Out</a>
    <?php else : ?>
        <a class="sign-out" href="<?php echo base_url('login') ?>">Login</a>
    <?php endif; ?>
</div>
