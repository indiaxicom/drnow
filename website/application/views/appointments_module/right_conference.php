<?php 
$prescription_id = !empty($get_apppointment_details[USER_DOCTOR]->prescription_id) ? $get_apppointment_details[USER_DOCTOR]->prescription_id : 0;
?>
<div class="c-right app-rght">
	<?php echo form_open('', 'id="appointment_prescription"'); ?>		
		<section class="prescip-sec">
			<h3 class="heading3">Register ICD </h3>
			 <div class="form-sec new-pescrip">
				<input id="icd_list" type="text" class="strok-field" placeholder="Start typing here" />
				<ul class="prescrp-list icd-list"></ul>
			</div>
		</section>

		<section class="prescip-sec">
			<h3 class="heading3">Issue new prescriptions </h3>
			<div class="form-sec new-pescrip">
				<input id="prescription" type="text" class="strok-field" placeholder="Start typing here" />
				<label style="color:#B1B1B1">Repeat Prescription</label>
				<input value="0" name="repeat_prescription" style="width:50%" class="strok-field" min="0" type="number" placeholder="Repeat" />
				<ul class="prescrp-list list2">
					
				</ul>
			</div>
			<?php if (!empty($get_apppointment_details[USER_DOCTOR]->prescription_app_id)) : ?>
				<div class="btn-sec main-btn presc_prepared"><a href="javascript:void(0)" class="buttons">Prescription Already Prepared</a></div>
			<?php else : ?>
				<div class="btn-sec main-btn save_prescription"><a href="javascript:void(0)" class="buttons">Finish and Issue prescription</a></div>
			<?php endif; ?>
		</section>
		<?php echo form_hidden('create_prescription', FALSE); ?>
		<?php echo form_hidden('presc_count', 0); ?>
	<?php echo form_close(); ?>
	
	<?php echo form_open('', array('id' => 'appointment_outcomes', 'disabled' => 'disabled')); ?>
		<section class="prescip-sec">
			<h3 class="heading3">Appointment Outcomes </h3>
			 <div class="form-sec new-pescrip">
				<select name="patient_reference">
					<option value="">Patient Reference</option>
				</select>
				<select name="appointment_conclusion">
					<option value="">Appointment Conclusion</option>
				</select>
			</div>
			<?php if (!empty($get_apppointment_details[USER_DOCTOR]->outcome_description)) : ?>
				<?php $outcome_allowed = '2'; ?>
				<div class="btn-sec main-btn save_outcomes" ><a href="javascript:void(0)" class="buttons">Outcome already Posted</a></div>
			<?php else : ?>
				<?php $outcome_allowed =  !empty($prescription_id) ? 1 : 0; ?>
				<div class="btn-sec main-btn save_outcomes"><a href="javascript:void(0)" class="buttons">Post Appointment Outcomes</a></div>
			<?php endif; ?>
		</section>
		
		<?php 
		echo form_hidden('outcome_allowed', $outcome_allowed); 
		?>
		<?php echo form_hidden('appointment_id', $get_apppointment_details[USER_DOCTOR]->id); ?>
		<?php echo form_hidden('patient_id', $get_apppointment_details[USER_PATIENT]->user_id); ?>
		<?php echo form_hidden('id', $prescription_id); ?>
	<?php echo form_close(); ?>
</div>

<style>
ul.ui-autocomplete { height:300px; overflow:auto;}
form#appointment_outcomes {margin:10px 0 0 0}
</style>
