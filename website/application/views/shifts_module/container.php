<div class="notification gry-bg">
    <?php if (!empty($get_next_appointment)) : ?>
            <span class="appoint-date">
                <a target="_blank" href="<?php echo base_url() . 'appointments/conference/' . encryptor('encrypt', $get_next_appointment->id); ?>">
                    Next appointment: <?php echo convert_from_sql_time('d/m/Y | H:i', $get_next_appointment->start_time); ?>
                </a>
            </span>
        <?php else : ?>
            <span class="appoint-date">No Appointments</span> 
        <?php endif; ?>
    <div class="f-right"> Shifts  </div>
</div>

<div class="c-left">
    <div class="shift-table-cont">
        <table class="shift-shedule">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Start</th>
                    <th> End</th>
                    <th>claim</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($get_all_shifts)) : ?>
                    <?php foreach($get_all_shifts as $val) : ?>
                        <tr>
                            <td><?php echo convert_from_sql_time('d/m/Y', $val->start_time); ?></td>
                            <td><?php echo convert_from_sql_time('H:i', $val->start_time); ?></td>
                            <td><?php echo convert_from_sql_time('H:i', $val->end_time); ?></td>
                            <td>
                                <?php
                                $data = array(
                                    'class' => 'shifts_left_checks',
                                    'data-shift_id' => $val->id,
                                    'data-date' => convert_from_sql_time('d/m/Y', $val->start_time),
                                    'data-start_time' => convert_from_sql_time('H:i', $val->start_time),
                                    'data-end_time' => convert_from_sql_time('H:i', $val->end_time),
                                    'id' => 'check_' . $val->id,
                                );
                                echo form_checkbox($data);
                                ?>
                                <label for="check_<?php echo $val->id; ?>"><span></span></label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td>No Shifts Available</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
