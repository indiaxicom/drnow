<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <?php
        $title = !empty($title) ? $title : SITE_NAME;
        $description = !empty($description) ? $description : SITE_NAME;
        $keywords = !empty($keywords) ? $keywords : SITE_NAME;
        ?>

        <title><?php echo $title; ?></title>
        <meta name="description" content="<?php echo $description; ?>">
        <meta name="keywords" content="<?php echo $keywords; ?>">
        
        <link href="<?php echo css_url_path('jquery-ui.min.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo css_url_path('reset.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo css_url_path('style.css'); ?>" rel="stylesheet" type="text/css">
        <link href="<?php echo css_url_path('font-awesome.css'); ?>" rel="stylesheet" type="text/css">

        <script> var SITE_URL = '<?php echo base_url(); ?>'; </script>
        <script type="text/javascript" src="<?php echo js_url_path('jquery-1.11.0.js') ?>"></script>
        <script type="text/javascript" src="<?php echo js_url_path('jquery-ui.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo js_url_path('base_javascript.js') ?>"></script>
    </head>

    <body class="login-sec">

        <div class="container-login">
            <div class="login">
              <span class="logo-img"><img src="<?php echo image_url_path('logo-img.png'); ?>"></span>
              
                <?php if ($this->session->flashdata('flash_message') != NULL) : ?>
                    <div class="flash_message"><?php echo $this->session->flashdata('flash_message'); ?></div>
                <?php endif; ?>
                
                <?php echo form_open() ?>
                    <span>
                        <?php
                        $data = array(
                            'name' => 'email',
                            'placeholder' => 'Email',
                            'type' => 'email',
                            'required' => 'required'
                        );
                        echo form_input($data);
                        ?>
                    </span>
                    <span>
                        <?php
                        $data = array(
                            'name' => 'password',
                            'placeholder' => 'Password',
                            'type' => 'password',
                            'required' => 'required'
                        );
                        echo form_input($data);
                        ?>
                    </span>
                    <span>
                        <?php
                        $data = array(
                            'name' => 'login',
                            'class' => 'log-btn',
                            'value' => 'Login'
                        );
                        echo form_submit($data);
                        ?>
                    </span>
                <?php echo form_close(); ?>
            </div>
        </div>
    </body>
</html>
