<?php
$selected_date = !empty($selected_date) ? $selected_date : date('Y-m-d');

if (!empty($get_all_appointments))
{
    foreach($get_all_appointments as $val)
    {
        $appointments_arr[convert_from_sql_time('Y-m-d', $val->start_time)][] = $val;
    }
}
?>
<div class="schedule-right">
    <?php if (!empty($appointments_arr[$selected_date])) : ?>
        <div class="notification dark-bg">Appointments : <?php echo convert_from_sql_time('d/m/Y', $selected_date); ?></div>
        <ul class="appointments">
            <?php foreach($appointments_arr[$selected_date] as $val) : ?>
                <li>
                    <h2><?php echo (!empty($patient_details) && !empty($patient_details[$val->patient_id])) ? current($patient_details[$val->patient_id])['PatientForename'] . ' ' . current($patient_details[$val->patient_id])['PatientSurname'] : 0; ?></h2>
                    <p>Appointment time: <?php echo convert_from_sql_time('H:i', $val->start_time) ?></p>
                    <a href="<?php echo base_url() . 'schedule/schedule_details/' . $val->id ?>" class="details-link open_popup">Details</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
         <div class="notification dark-bg"> Appointments <?php echo convert_from_sql_time('d/m/Y', $selected_date); ?> </div>
        <ul class="appointments">
             <li>
                <h2>No Appointments</h2>
            </li>
        </ul>
    <?php endif; ?>
</div>
