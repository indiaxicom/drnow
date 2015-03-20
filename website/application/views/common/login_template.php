<!DOCTYPE html>
<html>
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
        
        <?php
        if (!empty($js_script))
        {
            $this->load->view($js_script);
        } 
        ?>
    </head>
    <body class="login-sec">
        <div class="container-login">
            <?php
            if (!empty($page_view['container'])) {
                $page_variables['container'] = !empty($page_variables['container']) ? $page_variables['container'] : array();
                $this->load->view($page_view['container'], $page_variables['container']);
            }
            ?>
        </div>
    </body>
</html>

