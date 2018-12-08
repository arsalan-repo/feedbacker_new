<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                 <?php /*?><img src="<?php echo base_url($frameworks_dir . '/adminlte/img/logo.jpg')?>" class="" alt="MEC"> <?php */?>
            </div>
        </div>

        <ul class="sidebar-menu <?=$admin_role; ?>">
            <!--<li class="header">MAIN NAVIGATION</li>-->

            <!-- Start Dashboard -->
            <li <?php if ($this->uri->segment(2) == 'dashboard' || $this->uri->segment(1) == '') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?> >
                <a href="<?php echo base_url('admin/dashboard'); ?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span> 
                </a>
            </li>
            <!-- End Dashboard -->
            <!--Start Users-->
			<?php if($admin_role=='Admin' || $admin_role=='Super Admin'): ?>
            <li <?php if ($this->uri->segment(2) == 'users') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="<?php echo base_url('admin/users'); ?>">    
                    <i class="fa fa-users"></i><span>Users</span>
                </a>
            </li>
			<li <?php if ($this->uri->segment(2) == 'questionnaire') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="<?php echo base_url('admin/questionnaire'); ?>">    
                    <i class="fa fa-lock"></i><span>Questionnaire</span>
                </a>
            </li>
			<li <?php if ($this->uri->segment(2) == 'encrypted') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="<?php echo base_url('admin/encrypted'); ?>">    
                    <i class="fa fa-lock"></i><span>Encrypted</span>
                </a>
            </li>
			<?php endif; ?>
            <!--End Users-->
            <!--Start Titles-->
            <li <?php if ($this->uri->segment(2) == 'titles') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="<?php echo base_url('admin/titles'); ?>">    
                    <i class="fa fa-terminal"></i><span>Titles</span>
                </a>
            </li>
            <!--End Titles-->
            <!--Start Feedbacks-->
            <li <?php if ($this->uri->segment(2) == 'feedbacks') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="<?php echo base_url('admin/feedbacks'); ?>">    
                    <i class="fa fa-commenting"></i><span>Feedbacks</span>
                </a>
				<ul class="treeview-menu">
					<li <?php if ($this->uri->segment(2) == 'feedbacks') { ?> class="active" <?php } else { ?> class=""   <?php } ?>>
						<a href="<?php echo base_url('admin/feedbacks'); ?>">    
							<i class="fa fa-commenting"></i><span>Feedbacks</span>
						</a>
					</li>
                    <li <?php if ($this->uri->segment(2) == 'feedbacks' && $this->uri->segment(3) == 'report_feedbacks') { ?> class="active" <?php } else { ?> class=""   <?php } ?>>
                        <a href="<?php echo base_url('admin/feedbacks/report_feedbacks'); ?>">
                            <i class="fa fa-file-archive-o"></i><span>Report Feedback</span>
                        </a>
                    </li>
					<li <?php if ($this->uri->segment(2) == 'feedbacks' && $this->uri->segment(3) == 'trash') { ?> class="active" <?php } else { ?> class=""   <?php } ?>>
						<a href="<?php echo base_url('admin/feedbacks/trash'); ?>">    
							<i class="fa fa-trash"></i><span>Trash</span>
						</a>
					</li>
				</ul>
            </li>
		
            <!--End Feedbacks-->
            <!--Start Spam-->
            <li <?php if ($this->uri->segment(2) == 'spam') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="<?php echo base_url('admin/spam'); ?>">    
                    <i class="fa fa-crosshairs"></i><span>Spam</span>
                </a>
            </li>
            <!--End Spam-->
            <!--Start Reports-->
            <li <?php if ($this->uri->segment(1) == 'reports' || $this->uri->segment(1) == '') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="#">
                    <i class="fa fa-bars"></i><span>Reports</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo base_url('admin/reports/search'); ?>"><i class="fa fa-circle-o"></i>Search Log</a></li>
                </ul>
            </li>
            <!--End Reports-->
            <!--Start Manage Ads-->
            <li <?php if ($this->uri->segment(2) == 'ads') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="<?php echo base_url('admin/ads'); ?>">    
                    <i class="fa fa-desktop"></i><span>Ads</span>
                </a>
            </li>
            <!--End Manage Ads-->
			<!--Terms And Conditions-->
            <li <?php if ($this->uri->segment(2) == 'terms') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="<?php echo base_url('admin/terms'); ?>">    
                    <i class="fa fa-hand-o-right"></i><span>Terms And Conditions</span>
                </a>
            </li>
            <!--Terms And Conditions-->
            <!--Start Settings -->
            <li <?php if ($this->uri->segment(2) == 'settings') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?>>
                <a href="<?php echo base_url('admin/settings'); ?>">    
                    <i class="fa fa-cog"></i><span>Settings</span>
                </a>
            </li>
            <!--End Setting-->
            <!--Start Change Password-->
            <li <?php if ($this->uri->segment(2) == 'change_password') { ?> class="active treeview" <?php } else { ?> class="treeview"   <?php } ?> >
               <a href="<?php echo base_url('admin/dashboard/change_password'); ?>">
                   <i class="fa fa-lock"></i> <span>Change Password</span>
               </a>
            </li>
            <!--End Change Password-->

            <!--End of my code-->

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>