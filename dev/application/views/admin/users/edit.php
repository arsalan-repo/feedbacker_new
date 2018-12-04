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
                    $form_attr = array('id' => 'edit_user_frm', 'enctype' => 'multipart/form-data');
                    echo form_open_multipart('admin/users/edit', $form_attr);
                    ?>
                    <input type="hidden" name="user_id" value="<?php echo $user_detail[0]['id'] ?>">
                    <div class="box-body">
                        <div class="form-group col-sm-10">
                            <label for="name" id="page_title">Name*</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $user_detail[0]['name'] ?>">
                        </div>
                        <!-- User image start -->
                        <div class="form-group col-sm-10">
                            <label for="photo" id="page_title">Photo</label>
                            <input type="file" class="form-control" name="photo" id="photo" value="" style="border: none;">
                        </div>
                        <?php if($user_detail[0]['photo']) { ?>
                        <div class="form-group col-sm-10">
                            <label for="photo" id="page_title"></label>
                            <img src="<?php echo S3_CDN . 'uploads/user/thumbs/' . $user_detail[0]['photo']; ?>" alt="<?php echo $user_detail[0]['photo'] ?>" width="180">
                            <input type="hidden" name="old_image" value="<?php echo $user_detail[0]['photo']; ?>" />
                            <a href="<?php echo base_url('admin/users/remove_photo/' . $user_detail[0]['id']) ?>" title="Remove Photo" style="margin: 0 auto; width: 120px;" >Remove Photo</a>
                        </div>
                        <?php } ?>
                        <!-- User image end -->
                        <div class="form-group col-sm-5">
                            <label for="gender" id="page_title">Gender</label>
                            <select name="gender" id="gender" class="form-control select2">
                            	<option value="None" <?php if($user_detail[0]['gender'] == "None") echo "selected"; ?>>Select</option>
                                <option value="Male" <?php if($user_detail[0]['gender'] == "Male") echo "selected"; ?>>Male</option>
                                <option value="Female" <?php if($user_detail[0]['gender'] == "Female") echo "selected"; ?>>Female</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-10 date form_datetime">
                            <label for="dob" id="page_title">Date of birth</label>
                            <input type="text" class="form-control" name="dob" id="dob" value="<?php echo $user_detail[0]['dob'] ?>">
                            <span class="add-on"><i class="icon-th"></i></span>
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="country" id="page_title">Country*</label>
                            <select name="country" id="country" class="form-control select2" style="width: 100%;">
                                <option value="">Select Country</option>
                                <?php
                                foreach ($country_list as $country) {
                                    ?>
                                    <option value="<?php echo $country['country_code']; ?>" <?php if($user_detail[0]['country'] == $country['country_code']) echo "selected"; ?>><?php echo $country['country_name']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="email" id="page_title">Email*</label>
                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $user_detail[0]['email'] ?>">
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="password" id="page_title">Reset Password</label>
                            <input type="password" class="form-control" name="reset_password" id="reset_password" value="">
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="confirm_password" id="page_title">Confirm Password</label>
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


        $("#edit_user_frm").validate({
            rules: {
                name: {
                    required: true,
                    //nospecialchar: true,
                    minlength: 3,
                    maxlength: 50
                },
                reset_password: {
                    minlength: 3,
                    maxlength: 50
                },
                confirm_password: {
                    equalTo : "#reset_password"
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
                confirm_password: {
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


        //var available_for = document.getElementsByName("available_for");
        //alert(available_for);
        var available = $('input[name=available_for]:checked').val();
        if (available== 'buy')
        {
            $(".bidding").hide();
        }
        if (available == 'bid')
        {
            $(".bidding").show();
        }
        $(".available_for").click(function () {
            var available_for = this.value;
            if (available_for == 'buy')
            {
                $(".bidding").hide();
            }
            if (available_for == 'bid')
            {
                $(".bidding").show();
            }
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
