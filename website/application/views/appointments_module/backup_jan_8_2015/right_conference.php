<div class="col right-col">
    <?php echo form_open('', 'id="appointment_notes"'); ?>
        <section>
            <div class="heading"> History </div>
            <div class="content-section">
                <?php
                $params = array(
                    'name' => 'history',
                    'class' => 'line-text notes',
                    'placeholder' => 'start typing here...',
                    'disabled' => 'disabled'
                );
                if (session_data('tokbox_data') != NULL)
                {
                    unset($params['disabled']);
                }
                echo form_textarea($params);
                ?>
            </div>
        </section>

        <section>
            <div class="heading"> Examination </div>
            <div class="content-section">
                <?php
                $params = array(
                    'name' => 'examination',
                    'class' => 'line-text notes',
                    'placeholder' => 'start typing here...',
                    'disabled' => 'disabled'
                );
                if (session_data('tokbox_data') != NULL)
                {
                    unset($params['disabled']);
                }
                echo form_textarea($params);
                ?>
            </div>
        </section>

        <section>
            <div class="heading"> Diagnosis </div>
            <div class="content-section">
                <?php
                $params = array(
                    'name' => 'diagnosis',
                    'class' => 'line-text notes',
                    'placeholder' => 'start typing here...',
                    'disabled' => 'disabled'
                );
                if (session_data('tokbox_data') != NULL)
                {
                    unset($params['disabled']);
                }
                echo form_textarea($params);
                ?>
            </div>
        </section>

        <section>
            <div class="heading gry-bg"> Management </div>
            <div class="content-section">
                <?php
                $params = array(
                    'name' => 'management',
                    'class' => 'line-text',
                    'placeholder' => 'start typing here...',
                    'disabled' => 'disabled'
                );
                if (session_data('tokbox_data') != NULL)
                {
                    unset($params['disabled']);
                }
                echo form_textarea($params);
                ?>
            </div>
        </section>
        <?php echo form_hidden('appointment_id', current($get_apppointment_details)->id); ?>
        <?php echo form_hidden('id', '0'); ?>
        <?php echo form_hidden('unchanged_text', '0'); ?>
    <?php echo form_close(); ?>
</div>

