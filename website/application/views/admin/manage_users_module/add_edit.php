<?php
$user = NULL;

if (!empty($get_all_doctors)) {
    $user = current($get_all_doctors);
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
                'value' => !empty($user->first_name) ? $user->first_name : set_value('first_name'),
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
                'value' => !empty($user->last_name) ? $user->last_name : set_value('last_name'),
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
                'value' => !empty($user->email) ? $user->email : set_value('email'),
                'type' => 'email',
                'style' => 'width:450px',
                'class'	=> 'input'
                );
                if (!empty($user->id))
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
                    echo form_dropdown('status', $options, !empty($user->status) ? $user->status : set_value('status'));
                ?>
            </td>
        </tr>

        <tr>
            <td align="center">&nbsp;</td>
            <td align="left">
                <div class="black_btn2">
                    <span class="upper">
                        <?php 
                        if (!empty($user->id))
                        {
                            echo form_hidden(array('id', $user->id));
                        }
                        echo form_hidden(array('user_type' => USER_ADMIN));
                        ?>
                        <?php echo form_submit('submit', 'SUBMIT'); ?>
                    </span>
                </div>
            </td>
        </tr>
    </table>
</div>		
</form>
