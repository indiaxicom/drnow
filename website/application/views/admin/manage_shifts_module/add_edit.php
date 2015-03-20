<?php
if (!empty($get_all_records)) {
    $record = $get_all_records;
}
?>
<script>
$(document).ready(function()
{
    $('.datetimepicker_start').datetimepicker(
    {
        format:'H:i',
        datepicker: false,
        onShow:function( ct ){
            this.setOptions({
                minTime: $('.datepicker').val() == '<?php echo date('d/m/Y'); ?>' ? '<?php echo date('H:i'); ?>' : false
            })
        },
        onChangeDateTime:function() {
            $('.datetimepicker_end').val('');
        }
    });
    $('.datetimepicker_end').datetimepicker(
    {
        format:'H:i',
        datepicker: false,
        onShow:function( ct ){
            this.setOptions({
                minTime:$('.datetimepicker_start').val() ? $('.datetimepicker_start').val() : false
            })
        }
    });
    $('.datepicker').datetimepicker(
    {
        format:'d/m/Y',
        timepicker: false,
        minDate: '<?php echo date('Y/m/d'); ?>',
        maxDate:'<?php echo date('Y/m/d', strtotime('+ 6 months')); ?>',
        closeOnDateSelect:true,
        onChangeDateTime:function() {
            $('.datetimepicker_end, .datetimepicker_start').val('');
        }
    });
});    
</script>

<div class="row">
    <div class="floatleft">
        <h1>Add / Edit Shifts</h1>
    </div>
    <div class="floatright">
        <a href="<?php echo base_url() . 'admin/' . $current_module ?>" class="black_btn">
            <span>Back To Manage Shifts</span>
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
            <td align="left"><strong class="upper"><span class="required">*</span> Shift Date</strong></td>
            <td align="left">
                <?php
                $data = array(
                    'name' => 'shift_date',
                    'required' => TRUE,
                    'value' => !empty($record->start_time) ? convert_from_sql_time('d/m/Y', $record->start_time) : set_value('shift_date'),
                    'style' => 'width:450px',
                    'class' => "input datepicker",
                    'readonly' => TRUE
                );
                
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper"><span class="required">*</span> Start Time</strong></td>
            <td align="left">
                <?php
                $data = array(
                    'name' => 'start_time',
                    'required' => TRUE,
                    'value' => !empty($record->start_time) ? convert_from_sql_time('H:i', $record->start_time) : set_value('start_time'),
                    'style' => 'width:450px',
                    'class' => 'input datetimepicker_start',
                );
                echo form_input($data);
                ?>
            </td>
        </tr>

        <tr>
            <td align="left"><strong class="upper"><span class="required">*</span> End Time</strong></td>
            <td align="left">
                <?php
                $data = array(
                    'name' => 'end_time',
                    'required' => TRUE,
                    'value' => !empty($record->end_time) ? convert_from_sql_time('H:i', $record->end_time) : set_value('end_time'),
                    'style' => 'width:450px',
                    'class' => 'input datetimepicker_end',
                );
                
                echo form_input($data);
                ?>
            </td>
        </tr>
        <tr>
            <td align="left"><strong class="upper">Assign a doctor</strong></td>
            <td align="left">
                <?php
                $options = array('' => 'Select');

                if (!empty($get_all_doctors))
                {
                    foreach($get_all_doctors as $val)
                    {
                        $options[$val->id] = 'Dr. ' . $val->first_name . ' ' . $val->last_name;
                    }
                }
                echo form_dropdown('doctor_id', $options, !empty($record->doctor_id) ? $record->doctor_id : set_value('doctor_id'), !empty($record->doctor_id) ? 'disabled=disabled' : NULL);
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
                        <?php echo form_submit('submit', 'Save Shift'); ?>
                    </span>
                </div>
                <div class="black_btn2">
                    <span class="upper">
                        <?php echo form_submit('submit_new', 'Save And Create New Shift'); ?>
                    </span>
                </div>
            </td>
        </tr>
    </table>
</div>      
</form>
