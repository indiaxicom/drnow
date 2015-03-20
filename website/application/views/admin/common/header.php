<div id="header">
    <div id="head_lt">
    <!--Logo Start from Here-->
    <span class="floatleft">
        <a href="<?php echo base_url() . 'admin' ?>">
            
            <img width="113" src="<?php echo image_url_path('logo_img_admin.png') ?>" alt="<?php echo SITE_NAME; ?>" />
        </a>
    </span>
    <span style="margin-left:10px" class="slogan">administration suite</span>
    <!--Logo end  Here-->
    </div>
    <?php if (is_admin() == TRUE) : ?>
        <div id="head_rt">Welcome <span><?php echo session_data('first_name') . ' ' . session_data('last_name'); ?></span>&nbsp;&nbsp;|&nbsp;&nbsp; <?php echo date('d M, Y h:i A'); ?></div>
    <?php endif; ?>
</div>

<?php if (is_admin() == TRUE) : ?>
    <div class="menubg">
        <div class="nav">
            <ul id="navigation">
                <li onmouseout="this.className=''" onmouseover="this.className='hov'"><a href="<?php echo base_url() . 'admin' ?>">Dashboard</a></li>
                                
                <li onmouseout="this.className=''" onmouseover="this.className='hov'"><a href="#">Users</a>
                    <div class="sub">
                        <ul>
                            <li>
                                <a href="<?php echo base_url().'admin/manage_doctors'; ?>">Manage Doctors</a>
                            </li>
                            <li>
                                <a href="<?php echo base_url().'admin/manage_users'; ?>">Manage Admins</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li onmouseout="this.className=''" onmouseover="this.className='hov'"><a href="<?php echo base_url() . 'admin/manage_shifts' ?>">Shifts</a></li>
                <li onmouseout="this.className=''" onmouseover="this.className='hov'"><a href="#">Site</a>
                    <div class="sub">
                        <ul>
                            <li>
                                <a href="<?php echo base_url().'admin/manage_email_templates'; ?>">Manage Email Templates</a>
                                <a href="<?php echo base_url().'admin/manage_content'; ?>">Manage Site Content</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="logout"><a href="<?php echo base_url().'admin/logout'; ?>"><img src="<?php echo base_url(); ?>assets/images/logout.gif" /></a></div>
    </div>
<?php endif; ?>
