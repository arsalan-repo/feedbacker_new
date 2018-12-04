<?php
echo $header;
echo $leftmenu;
?>
<div id="content">
    <div class="container">
        <!-- Breadcrumbs line -->
        <div class="crumbs">
            <ul id="breadcrumbs" class="breadcrumb">
                <li>
                    <i class="icon-home"></i>
                    <a href="<?php echo site_url(); ?>">Dashboard</a>
                </li>

                <li class="current">
                    <a href="#"><?php echo $section_title; ?></a>
                </li>
            </ul>


        </div>
        <!-- /Breadcrumbs line -->

        <!--=== Page Header ===-->
        <div class="page-header">
            <div class="page-title">
                <h3><?php echo $section_title; ?></h3>

            </div>

        </div>
        <!-- /Page Header -->

        <!--=== Page Content ===-->
        <!--=== Statboxes ===-->

        <!-- /Statboxes -->
        <div class="row">
            <!--=== Table with Footer ===-->
            <div class="col-md-12">
                <div class="widget box">
                    <div class="widget-header">
                        <h4><i class="icon-reorder"></i> <?php echo $section_title; ?></h4>
                        <div class="toolbar no-padding">
                            <div class="btn-group">
                                <span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content">
                        <?php
                        $form_attr = array('id' => 'edit_profile', 'class' => 'form-horizontal row-border');
                        echo form_open('dashboard/edit_profile', $form_attr);
                        ?>

                        <!-- Error Message -->

                        <?php if ($this->session->flashdata('success')) { ?>
                            <div class="alert fade in alert-success">
                                <i class="icon-remove close" data-dismiss="alert"></i>
                                <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php } ?>
                        <?php if ($this->session->flashdata('error')) { ?>  
                            <div class="alert fade in alert-danger" >
                                <i class="icon-remove close" data-dismiss="alert"></i>
                                <?php echo $this->session->flashdata('error'); ?>
                            </div>
                        <?php } ?>


                        <div class="form-group">
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label">Name *</label>
                            <div class="col-md-6">
                                <input type="text" name="name" id="name" value="<?php echo $loged_in_user[0]['name'] ?>" class="form-control" /> 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label">Email *</label>
                            <div class="col-md-6">
                                <input type="text" name="email" id="email" value="<?php echo $loged_in_user[0]['email'] ?>" class="form-control" /> 
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label">User Name *</label>
                            <div class="col-md-6">
                                <input type="text" name="user_name" id="user_name" value="<?php echo $loged_in_user[0]['user_name'] ?>" class="form-control" /> 
                            </div>
                        </div>



                        <div class="form-actions">
                            <?php
                            $save_attr = array('id' => 'btn_save', 'name' => 'btn_save', 'value' => 'Save', 'class' => 'btn btn-primary');
                            echo form_submit($save_attr);
                            ?>    
                            <button  type="button" class="btn btn-dark-blue" onclick="window.history.back();">Back</button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <!-- /Table with Footer -->
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /.container -->

</div>
</div>
<script type="text/javascript">
    //validation for edit email formate form
    $(document).ready(function () {


        $("#edit_profile").validate({
            ignore: [],
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email:true,
                    remote: {
                        url: "<?php echo base_url() . 'dashboard/check_email' ?>",
                        type: "post",
                        data: {
                            email: function () {
                                return $("#email").val();
                            },
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                        },
                    },
                },
                user_name: {
                    required: true,
                    remote: {
                        url: "<?php echo base_url() . 'dashboard/check_username' ?>",
                        type: "post",
                        data: {
                            user_name: function () {
                                return $("#user_name").val();
                            },
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                        },
                    },
                },
            },
            messages:
                    {
                        name: {
                            required: "Name is required",
                        },
                        email: {
                            required: "Email is required",
                            email:"Please enter valid email",
                            remote:"Email is already exists"
                        },
                        user_name: {
                            required: "User name is required",
                            remote:"User name is already exists"
                        },
                    },
           
        });

    });
</script>
</body>
</html>