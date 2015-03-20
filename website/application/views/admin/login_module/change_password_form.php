<script src="<?php echo base_url().'js/jquery.validate.min.js' ?>"></script>
<script type="text/javascript">
(function($,W,D)
{
    var JQUERY4U = {};
    JQUERY4U.UTIL =
    {
        setupFormValidation: function()
        {
            //form validation rules
            $("#change_password").validate({
                rules: {
					current_password: "required",
                    new_password: {
                        required: true,
                        minlength: 6,
                        maxlength: 15,
                    },
                    confirm_new_password: {
                        required: true,
                        minlength: 6,
                        maxlength: 15,
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        }
    }
    //when the dom has loaded setup form validation rules
    $(D).ready(function($) {
        JQUERY4U.UTIL.setupFormValidation();
    });
})(jQuery, window, document);
</script>
    
    <?php echo form_open(base_url().'admin/change-password', array('id' => 'change_password')); ?>
		<div align="center" class="whitebox mtop15">
			<div class="form_validation_errors">
				<?php if(!empty($errors_list)) : ?>
					<?php foreach ($errors_list as $val) :?>
						<p><?php echo $val; ?></p>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php echo validation_errors(); ?>
			</div>		
			<table cellspacing="0" cellpadding="7" border="0" align="center">
				<tr>
					<td align="left"><strong class="upper">Current Password</strong></td>
					<td align="left">
						<?php 
						$data = array(
								'name'			=> 	'current_password',
								'type'			=>	'password',
								'class'			=>	'input',
								'size'			=> 	'38',
								'style'			=>	'width: 450px;'
								
							);
						echo form_input($data);
						?>
					</td>
				</tr>
							
				<tr>
					<td align="left"><strong class="upper">New Password</strong></td>
					<td align="left">
						<?php 
						$data = array(
								'name'			=> 	'new_password',
								'type'			=>	'password',
								'class'			=>	'input',
								'size'			=> 	'38',
								'style'			=>	'width: 450px;'
								
							);
						echo form_input($data);
						?>
					</td>
				</tr>
				
				<tr>
					<td align="left"><strong class="upper">Confirm New Password</strong></td>
					<td align="left">
						<?php 
						$data = array(
								'name'			=> 	'confirm_new_password',
								'type'			=>	'password',
								'class'			=>	'input',
								'size'			=> 	'38',
								'style'			=>	'width: 450px;'						
							);
						echo form_input($data);
						?>
					</td>
				</tr>	
			
				<tr>
					<td align="center">&nbsp;</td>
					<td align="left">
						<div class="black_btn2">
							<span class="upper">
								<?php echo form_submit('submit', 'SUBMIT'); ?>
							</span>
						</div>
					</td>
				</tr>
		  </table>
	</div>		
</form>
    
    
