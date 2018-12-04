<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo $section_title; ?>
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    
    <!--//for flash message-->
        <div class="row" >
            <div class="col-xs-12" >
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="alert fade in alert-success myalert">
                        <i class="icon-remove close" data-dismiss="alert"></i>
                        <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php } ?>
                <?php if ($this->session->flashdata('error')) { ?>  
                    <div class="alert fade in alert-danger myalert" >
                        <i class="icon-remove close" data-dismiss="alert"></i>
                        <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php } ?>
            </div>
        </div>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- start Users box -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo $users_count; ?></h3>
                        <p>Users</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="<?php echo base_url('admin/users')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- end Users box -->
            
            <!-- start Titles box -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo $titles_count; ?></h3>
                        <p>Titles</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-terminal"></i>
                    </div>
                    <a href="<?php echo base_url('admin/titles')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- end Titles box -->
            
            <!-- start Feedbacks box -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo $feedbacks_count; ?></h3>
                        <p>Feedbacks</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-commenting"></i>
                    </div>
                    <a href="<?php echo base_url('admin/feedbacks')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- end Feedbacks box -->
            <?php
            /*
            ?>
            <!-- start Setting box -->    
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><?php echo $product_count?></h3>
                        <p>Setting</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cogs"></i>
                    </div>
                    <a href="<?php echo base_url('setting')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
           <!-- end Setting box -->
            <!-- start Change Password box -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><?php echo $product_count?></h3>
                        <p>Change Password</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-lock"></i>
                    </div>
                    <a href="<?php echo base_url('change_password')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- end Change Password box -->
            <?php
            */
            ?>
        </div><!-- /.row -->
        <!-- Main row -->
       

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
