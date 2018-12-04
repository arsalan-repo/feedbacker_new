<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $module_name; ?>
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>
                    Home
                </a>
            </li>
            <li class="active"><?php echo $module_name; ?></li>
        </ol>
    </section>
    <section class="content-header">
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="callout callout-success">
                <p><?php echo $this->session->flashdata('success'); ?></p>
            </div>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>  
            <div class="callout callout-danger" >
                <p><?php echo $this->session->flashdata('error'); ?></p>
            </div>
        <?php } ?>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $section_title; ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php
                    $form_attr = array('id' => 'add_user_frm', 'enctype' => 'multipart/form-data');
                    echo form_open_multipart('admin/users/add', $form_attr);
                    ?>
                    <div class="box-body">
                        <div class="form-group col-sm-10">
                            <label for="name" id="page_title">Name*</label>
                            <input type="text" class="form-control" name="name" id="name" value="">
                        </div>
                        <!-- User image start -->
                        <div class="form-group col-sm-10">
                            <label for="photo" id="page_title">Photo</label>
                            <input type="file" class="form-control" name="photo" id="photo" value="" style="border: none;">
                        </div>
                        <!-- User image end -->
                        <div class="form-group col-sm-5">
                            <label for="gender" id="page_title">Gender</label>
                            <select name="gender" id="gender" class="form-control select2">
                            	<option value="None">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-10 date form_datetime">
                            <label for="dob" id="page_title">Date of birth</label>
                            <input type="text" class="form-control" name="dob" id="dob" value="">
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="country" id="page_title">Country*</label>
                            <select name="country" id="country" class="form-control select2" style="width: 100%;">
                                <option value="">Select Country</option>
                                <?php
                                foreach ($country_list as $country) {
                                    ?>
                                    <option value="<?php echo $country['country_code']; ?>"><?php echo $country['country_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="email" id="page_title">Email*</label>
                            <input type="text" class="form-control" name="email" id="email" value="">
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="password" id="page_title">Password*</label>
                            <input type="password" class="form-control" name="password" id="password" value="">
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="confirm_password" id="page_title">Confirm Password*</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" value="">
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <?php
                        $save_attr = array('id' => 'btn_save', 'name' => 'btn_save', 'value' => 'Save', 'class' => 'btn btn-primary');
                        echo form_submit($save_attr);
                        ?>    
                        <button type="button" onclick="window.history.back();" class="btn btn-default">Back</button>
                        <!--<button type="submit" class="btn btn-info pull-right">Sign in</button>-->
                    </div><!-- /.box-footer -->
                    </form>
                </div><!-- /.box -->


            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    //validation for edit email formate form
    $(document).ready(function () {

        $("#add_user_frm").validate({
            rules: {
                name: {
                    required: true,
                    //nospecialchar: true,
                    minlength: 3,
                    maxlength: 50
                },
                password: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                confirm_password: {
                    required: true,
                    equalTo : "#password"
                },
                email: {
                    required: true,
                    emailregex: true,
                },
				country: {
                    required: true,
				}
            },
            messages:
            {
                name: {
                    required: "Please enter name",
                },
                password: {
                    required: "Please enter password",
                },
                confirm_password: {
                    required: "Please confirm password",
                    equalTo : "Please enter the same password again",
                },
                email: {
                    required: "Please enter email address",
                },
                country: {
                    required: "Please select country",
                }
            },
        });
    });
</script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
		
		$(".form_datetime").datepicker({
			format: 'yyyy-mm-dd',
			autoclose: true,
		});
    });
</script>
