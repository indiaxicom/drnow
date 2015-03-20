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
        <script type="text/javascript" src="<?php echo js_url_path('alertify.min.js') ?>"></script>
        <script type="text/javascript" src="<?php echo js_url_path('base_javascript.js') ?>"></script>
        
        <?php
        if (!empty($js_script))
        {
            $this->load->view($js_script);
        } 
        ?>
    </head>
    <body>
        <div class="loader"></div>
        <div class="container">
            <div class="header-section">
                <?php $page_variables['header'] = !empty($page_variables['header']) ? $page_variables['header'] : array(); ?>
                <?php $page_view['header'] = !empty($page_view['header']) ? $page_view['header'] : 'common/header' ?>
                <?php $this->load->view($page_view['header'], $page_variables['header']) ?>
            </div>
            <div class="contant-area <?php echo !empty($extra_wrapper_class) ? $extra_wrapper_class : NULL; ?>">
                <?php if ($this->session->flashdata('flash_message') != NULL) : ?>
                    <div class="flash_message"><?php echo $this->session->flashdata('flash_message'); ?></div>
                <?php endif; ?>

                <?php
                /* Notification View */
                if (empty($hide_notification_bar) || ($hide_notification_bar !== TRUE))
                {
                    $page_variables['notification_container'] = !empty($page_variables['notification_container']) ? $page_variables['notification_container'] : array();
                    $this->load->view('common/notification', $page_variables['notification_container']);
                }
                ?>

                <div class="<?php echo !empty($extra_container_class) ? $extra_container_class : NULL; ?>">
                    <?php  
                    if (!empty($page_view['left_container'])) {
                        $page_variables['left_container'] = !empty($page_variables['left_container']) ? $page_variables['left_container'] : array();
                        $this->load->view($page_view['left_container'], $page_variables['left_container']);
                    }

                    if (!empty($page_view['container'])) {
                        $page_variables['container'] = !empty($page_variables['container']) ? $page_variables['container'] : array();
                        $this->load->view($page_view['container'], $page_variables['container']);
                    }

                    if (!empty($page_view['right_container'])) {
                        $page_variables['right_container'] = !empty($page_variables['right_container']) ? $page_variables['right_container'] : array();
                        $this->load->view($page_view['right_container'], $page_variables['right_container']);                           
                    }
                    ?>
                </div>

                <?php /* Popup Box */ ?>
                <div class="popup-otr">
                    <div class="p-overlay"></div>
                    <div class="p-con">
                        <a href="#" class="cancel-btn close_popup">&nbsp;</a>
                        <div class="p-con-box popup-modal-content"> </div>
                    </div>
                </div>
            </div>
        </div>
        <span id="appointment_call_alert"></span>
    </body>
</html>

