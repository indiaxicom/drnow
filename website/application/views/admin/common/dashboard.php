<?php
$msg = NULL;
if ($this->session->flashdata('flashdata') != NULL)
{
	$msg = $this->session->flashdata('flashdata');
}
?>
<div align="center" class="whitebox mtop15">
	<div style="font-size:14px; font-weight:bold"><?php echo !empty($msg) ? $msg : NULL; ?></div>
	<div style="clear:both"></div>
	<table cellspacing="0" cellpadding="5" border="0" align="center" style="margin-top:70px;">
	  <tr>
		<td valign="top" align="left"><img src="<?php echo image_url_path('dashboard-graphic.png'); ?>" /></td>
		<td valign="top" align="left">
		  <span class="size26">Welcome to <?php echo SITE_NAME ?></span><br /><br />
		  <span class="size14">Please use the navigation links at the top to access different<br />
		  sections of the administration panel.</span></td>
	  </tr>
	</table>
</div>
