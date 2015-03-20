<?php if (is_doctor() == TRUE) : ?>
    <div class="notification gry-bg">
        <?php if (!empty($get_next_appointment)) : ?>
            <span>
                <a target="_blank" href="<?php echo base_url() . 'appointments/conference/' . encryptor('encrypt', $get_next_appointment->id); ?>">
                    Next appointment: <?php echo convert_from_sql_time('d/m/Y | H:i', $get_next_appointment->start_time); ?>
                </a>
            </span>
        <?php else : ?>
            No Appointments 
        <?php endif; ?>
    </div>
<?php endif; ?>
