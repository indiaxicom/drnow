<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Doctor Now Administration Suite | Dashboard</title>

		<link href="<?php echo css_url_path('jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">
		<link href="<?php echo css_url_path('admin_style.css'); ?>" rel="stylesheet" type="text/css">

		<script src="<?php echo js_url_path('jquery-1.11.0.js'); ?>"></script>
		<script src="<?php echo js_url_path('jquery-ui.min.js'); ?>"></script>
		<script src="<?php echo js_url_path('jquery.validate.min.js'); ?>"></script>

		<?php if (!empty($custom_script)) : ?>
			<?php $this->load->view($custom_script)?>
		<?php endif; ?>
	</head>
<body>
<!--Wrapper Start from Here-->
<div id="wrapper">
	<!--Header Start from Here-->
	<?php $page_variables['header'] = !empty($page_variables['header']) ? $page_variables['header'] : array(); ?>
	<?php $page_view['header'] = !empty($page_view['header']) ? $page_view['header'] : 'admin/common/header' ?>
    <?php $this->load->view($page_view['header'], $page_variables['header'])?>
  
	<!--Header End  Here-->
	
	<!--Container Start from Here-->
	<div id="container">
		<?php if ($this->session->flashdata('flash_message') != NULL) : ?>
			<div class="flash_message"><?php echo $this->session->flashdata('flash_message'); ?></div>
		<?php endif; ?>

		<?php if(!empty($page_view['container'])) : ?>

			<?php
				$page_variables['container'] = !empty($page_variables['container']) ? $page_variables['container'] : array();
				$this->load->view($page_view['container'], $page_variables['container']);
			?>		
		<?php endif; ?>
		
		<!--Footer Start from Here-->
		<?php $page_variables['footer'] = !empty($page_variables['footer']) ? $page_variables['footer'] : array(); ?>
		<?php $page_view['footer'] = !empty($page_view['footer']) ? $page_view['footer'] : 'admin/common/footer' ?>
		<?php $this->load->view($page_view['footer'], $page_variables['footer'])?>
		<!--Footer End  Here-->

	</div>
	<!--Container end Here-->
  
</div>
<!--Wrapper End from Here-->
</body>
</html>
