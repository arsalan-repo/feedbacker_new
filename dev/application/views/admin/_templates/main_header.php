<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<header class="main-header">
    <a href="<?php echo site_url('admin/dashboard'); ?>" class="logo">
        <span class="logo-mini"><b>A</b><?php echo $title_mini; ?></span>
        <span class="logo-lg"><b>Admin</b><?php echo $title_lg; ?></span>
    </a>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?php echo base_url($avatar_dir . '/m_001.png'); ?>" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?=$admin_data['role']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="<?php echo base_url($avatar_dir . '/m_001.png'); ?>" class="img-circle" alt="User Image">
                            <p><?=$admin_data['first_name']; ?> <?=$admin_data['last_name']; ?></p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo site_url('admin/profile/1'); ?>" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo site_url('admin/dashboard/logout'); ?>" class="btn btn-default btn-flat">Sign Out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
