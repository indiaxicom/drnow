<?php
$doctor = NULL;

if (!empty($get_all_doctors)) {
    $doctor = current($get_all_doctors);
    
    $user_info = !empty($user_info) ? current($user_info) : array();
}
?>

<div class="row">
    <div class="floatleft">
        <h1>Add / Edit Doctor</h1>
    </div>
    <div class="floatright">
        <a href="<?php echo base_url() . 'admin/' . $current_module ?>" class="black_btn">
            <span>Back To Manage <?php echo $this->user_text; ?>s</span>
        </a>
    </div>
</div>
<?php echo form_open('', array('id' => 'add_template')); ?>
<div class="form_validation_errors">
    <?php echo validation_errors(); ?>
</div>

<div align="center" class="whitebox mtop15">
    <table cellspacing="0" cellpadding="7" border="0" align="center">
        <tr>
            <td align="left"><strong class="upper">First Name</strong></td>
            <td align="left">
                <?php
                $data = array(
                'name' => 'first_name',
                'value' => !empty($doctor->first_name) ? $doctor->first_name : set_value('first_name'),
                'style' => 'width:450px',
                'required' => TRUE,
                'class'	=> 'input'
                );
                echo form_input($data);
                ?>
            </td>
        </tr>

        <tr>
            <td align="left"><strong class="upper">Last Name</strong></td>
            <td align="left">
                <?php
                $data = array(
                'name' => 'last_name',
                'required' => TRUE,
                'value' => !empty($doctor->last_name) ? $doctor->last_name : set_value('last_name'),
                'style' => 'width:450px',
                'class'	=> 'input'
                );
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">Email</strong></td>
            <td align="left">
                <?php
                $data = array(
                'name' => 'email',
                'required' => TRUE,
                'value' => !empty($doctor->email) ? $doctor->email : set_value('email'),
                'type' => 'email',
                'style' => 'width:450px',
                'class'	=> 'input'
                );
                if (!empty($doctor->id))
                {
                    $data['readonly'] = TRUE;
                }
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">Password</strong></td>
            <td align="left">
                <?php
                $data = array(
                    'name' => 'password',
                    'style' => 'width:450px',
                    'class'	=> 'input',
                    'min_length' => '8'
                );
                echo form_password($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">Confirm Password</strong></td>
            <td align="left">
                <?php
                $data = array(
                'name' => 'confirm_password',
                'style' => 'width:450px',
                'class'	=> 'input'
                );
                echo form_password($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">Gender</strong></td>
            <td align="left">
                <?php
                    $options = array();
                    $options[MALE] = 'Male';
                    $options[FEMALE] = 'Female';
                    echo form_dropdown('gender', $options, !empty($doctor->gender) ? $doctor->gender : set_value('gender'));
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">Status</strong></td>
            <td align="left">
                <?php
                    $options = $this->config->item('userStatusArr');
                    echo form_dropdown('status', $options, !empty($doctor->status) ? $doctor->status : set_value('status'));
                ?>
            </td>
        </tr>
        <tr><td colspan="3" class="upper"><strong>Doctor Details<hr></strong></td></tr>
        <tr>
            <td align="left"><strong class="upper">Salutation</strong></td>
            <td align="left">
                <?php
                $data = array(
                    'name' => 'extra_details[salutation]',
                    'style' => 'width:450px',
                    'class' => 'input',
                    'value' => !empty($user_info['salutation']) ? $user_info['salutation'] : NULL
                );
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">GMC No</strong></td>
            <td align="left">
                <?php
                $data = array(
                'name' => 'extra_details[gmc_no]',
                'style' => 'width:450px',
                'class'	=> 'input',
                'value' => !empty($user_info['gmc_no']) ? $user_info['gmc_no'] : NULL
                );
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">Bio</strong></td>
            <td align="left">
                <?php
                $data = array(
                    'name' => 'extra_details[bio]',
                    'style' => 'width:450px',
                    'class'	=> 'input',
                    'value' => !empty($user_info['bio']) ? $user_info['bio'] : NULL
                );
                echo form_textarea($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="center">&nbsp;</td>
            <td align="left">
                <div class="black_btn2">
                    <span class="upper">

                        <?php 
                        if (!empty($doctor->id))
                        {
                            echo form_hidden('id', $doctor->id);
                            echo form_hidden('app_user_id', $doctor->app_user_id);
                        }
                        echo form_hidden(array('user_type' => USER_DOCTOR));
                        ?>
                        <?php echo form_submit('submit', 'SUBMIT'); ?>
                    </span>
                </div>
            </td>
        </tr>
    </table>
</div>		
</form>
