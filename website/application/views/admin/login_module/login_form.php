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
            $("#login_form").validate({
                rules: {
					password: "required",
                    email: {
                        required: true,
                        email: true
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
<!--Admin logn section Start from Here-->

<div id="login-box">
	<div class="white-box" style="width:325px; padding-top:60px;">
		<div class="tl">
			<div class="tr">
				<div class="tm">&nbsp;</div>
			</div>
		</div>
		<div class="ml">
			<div class="mr">
				<div class="middle">
					<div class="lb-data">
						<h1>Administrator Login</h1>
						<p class="top15 gray12">Please enter a valid email and password to gain access to the administration console.</p>

						<?php echo form_open(base_url() . 'admin/login', array('id' => 'login_form')); ?>
						<div class="form_validation_errors">
							<?php if(!empty($errors_list)) : ?>
								<?php foreach ($errors_list as $val) :?>
									<p><?php echo $val; ?></p>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php echo validation_errors(); ?>
						</div>	
						<p class="top30">
							<span class="login_field">
							<?php 
							$data = array(
									'name'			=> 	'email',
									'class'			=>	'inpt',
									'maxlength'		=> 	'100',
									'size'			=> 	'38',
									'placeholder'	=>	'Email'
								);
							echo form_input($data);
							?>
							</span>
						</p>
						<p class="top15">
							<span class="login_field">
								<?php 
								$data = array(
										'name'			=> 	'password',
										'type'			=> 	'password',
										'class'			=>	'inpt',
										'size'			=> 	'38',
										'placeholder'	=>	'Password'
									);
								echo form_input($data);  
								?>
							
							</span>
						</p>
							<div class="top15">
								<?php 
								$data = array(
										'name'		=> 	'remember_me',
										'class'		=>	'checkbox',
										'value'  	=> 	'1',									
									);
								?>

								<div class="floatright">
									<div class="black_btn2"><span class="upper"><?php echo form_submit('submit', 'SUBMIT'); ?></span></div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<div class="bl">
	  <div class="br">
		<div class="bm">&nbsp;</div>
	  </div>
	</div>
	</div>
</div>
    <!--Admin logn section end Here-->
