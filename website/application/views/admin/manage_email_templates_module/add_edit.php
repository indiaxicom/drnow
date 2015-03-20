<?php
$record = NULL;

$this->ckeditor->basePath = base_url().'assets/ckeditor/';
$this->ckeditor->config['width'] = '750px';
$this->ckeditor->config['height'] = '300px'; 

if (!empty($get_all_records)) {
    $record = current($get_all_records);
}
?>

<div class="row">
    <div class="floatleft">
        <h1>Add / Edit Template</h1>
    </div>
    <div class="floatright">
        <a href="<?php echo base_url() . 'admin/' . $current_module ?>" class="black_btn">
            <span>Back To Manage Email Templates</span>
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
            <td align="left"><strong class="upper">Template Type</strong></td>
                <td align="left">
                    <?php
                        $options = $this->config->item('emailTempArr');
                        echo form_dropdown('template_type', $options, !empty($record->template_type) ? $record->template_type : set_value('template_type'), !empty($record->id) ? 'readonly="readonly"' : NULL);
                    ?>
                </td>
        </tr>

        <tr>
            <td align="left"><strong class="upper">Subject</strong></td>
            <td align="left">
                <?php
                $data = array(
                'name' => 'subject',
                'required' => TRUE,
                'value' => !empty($record->subject) ? $record->subject : set_value('subject'),
                'style' => 'width:450px',
                'class' => 'input'
                );
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">From Name</strong></td>
            <td align="left">
                <?php
                $data = array(
                'name' => 'from_name',
                'required' => TRUE,
                'value' => !empty($record->from_name) ? $record->from_name : set_value('from_name'),
                'style' => 'width:450px',
                'class' => 'input'
                );
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">From Email</strong></td>
            <td align="left">
                <?php
                $data = array(
                'name' => 'from_email',
                'required' => TRUE,
                'type' => 'email',
                'value' => !empty($record->from_email) ? $record->from_email : set_value('from_email'),
                'style' => 'width:450px',
                'class' => 'input'
                );
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">Body</strong></td>
            <td align="left">
                <?php echo $this->ckeditor->editor("body", !empty($record->body) ? htmlspecialchars_decode($record->body) : set_value('body')); ?>
            </td>
        </tr>

        <tr>
            <td align="left"><strong class="upper">Status</strong></td>
            <td align="left">
                <?php
                    $options = $this->config->item('userStatusArr');
                    echo form_dropdown('status', $options, !empty($record->status) ? $record->status : set_value('status'));
                ?>
            </td>
        </tr>

        <tr>
            <td align="center">&nbsp;</td>
            <td align="left">
                <div class="black_btn2">
                    <span class="upper">
                        <?php 
                        if (!empty($record->id))
                        {
                            echo form_hidden('id', $record->id);
                        }
                        ?>
                        <?php echo form_submit('submit', 'SUBMIT'); ?>
                    </span>
                </div>
            </td>
        </tr>
    </table>
</div>      
</form>
