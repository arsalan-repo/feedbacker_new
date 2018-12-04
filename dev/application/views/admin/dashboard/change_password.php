<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $module_name; ?>
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('admin/dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>
                    Home
                </a>
            </li>
            <li class="active"><?php echo $module_name; ?></li>
        </ol>
    </section>

                       
<div id="content">
    <div class="container">
        
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
                        $form_attr = array('id' => 'change_pass_form', 'class' => 'form-horizontal row-border');
                        echo form_open('admin/dashboard/change_password', $form_attr);
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
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label">Old Password *</label>
                            <div class="col-md-6">
                                <input type="password" name="old_pass" id="old_pass" autocomplete="off" class="form-control" /> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label">New Password *</label>
                            <div class="col-md-6">
                                <input type="password" name="new_pass" id="new_pass" autocomplete="off" class="form-control" /> 
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-3 col-md-3 col-lg-2 control-label">Confirm Password *</label>
                            <div class="col-md-6">
                                <input type="password" name="conf_pass" id="conf_pass" autocomplete="off" class="form-control" /> 
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
<script>
    $(document).ready(function(){
        
       $("#change_pass_form").validate({
            
		rules:{
			
			/*old_pass:{
				required: true,
                                maxlength:30,
				remote:{
                                    url:"<?php echo base_url().'dashboard/check_old_pass' ?>",
                                    type:"post",
                                    data: {
                                    old_pass: function() {
                                        return $("#old_pass").val();
                                    },
                                    '<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>',
                                    },
                                  },
			}, */
                     old_pass:{
                            required: true,
                        },
                        new_pass:{
                            required: true,
                            maxlength:16,
                            minlength:6
                        },
                        conf_pass:{
                            required:true,
                            
                            equalTo:'#new_pass',
                        },
                        
		},
                messages:
                    {
                    old_pass:{
                            required:"Old Password Is Required",
                           // remote:"Old Password Not Match",
                    },
                    new_pass:{
                        required:"New Password Is Required",
                        minlength:"Password is between 6-16 characters long"
                    },
                    conf_pass:{
                        required:"Confirm New Password Is Required",
                        equalTo:"New Password and Confirm New Password Must match"
                    }
                    
                },
		
	});
    });
    </script>
</body>
</html>