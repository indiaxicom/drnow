<div class="c-right">
    <h4 class="right-tittle"> Account Settings </h4>

    <div class="form-section">
        <div class="response_section"></div>

         <?php echo form_open('', 'autocomplete="off" id="update_password"') ?>
            <?php
            $data = array(
                'name' => 'old_password',
                'placeholder' => 'Existing Password',
                'type' => 'password',
                'required' => 'required',
            );
            echo form_input($data);
            ?>

            <?php
            $data = array(
                'name' => 'password',
                'placeholder' => 'New Password',
                'type' => 'password',
                'required' => 'required'
            );
            echo form_input($data);
            ?>
            <?php
            $data = array(
                'name' => 'confirm_password',
                'placeholder' => 'Confirm New Password',
                'type' => 'password',
                'required' => 'required'
            );
            echo form_input($data);
            ?>
        
            <?php
            $data = array(
                'name' => 'change_password',
                'class' => 'done-button',
                'value' => 'Change Password'
            );
            echo form_submit($data);
            ?>
        <?php echo form_close(); ?>
    </div>
</div>
