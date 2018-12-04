<?php
echo $header;
echo $leftmenu;
?>

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
                    $form_attr = array('id' => 'view_user_frm', 'enctype' => 'multipart/form-data');
                    echo form_open_multipart('users/view', $form_attr);
                    ?>
                    
                    <div class="box-body">
                        <div class="form-group col-sm-10">
                            <label for="first_name" name="first_name" id="page_title">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo $users_detail[0]['first_name']; ?>" readonly="readonly" />
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="last_name" name="last_name" id="page_title">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $users_detail[0]['last_name']; ?>" readonly="readonly" />
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="username" name="username" id="page_title">User Name</label>
                            <input type="text" class="form-control" name="username" id="username" value="<?php echo $users_detail[0]['username']; ?>" readonly="readonly" />
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="email" name="email" id="page_title">Email Address</label>
                            <input type="text" class="form-control" name="email" id="email" value="<?php echo $users_detail[0]['email']; ?>" readonly="readonly" />
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="spouse_email" name="spouse_email" id="page_title">Spouse Email</label>
                            <input type="text" class="form-control" name="spouse_email" id="spouse_email" value="<?php echo $users_detail[0]['spouse_email']; ?>" readonly="readonly" />
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="username" name="user_name" id="page_title">Address</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $users_detail[0]['address']; ?>" readonly="readonly" />
                        </div>

                        <!-- user name end -->
                        <!-- user name start -->
                        <div class="form-group col-sm-10">
                            <label for="username" name="user_name" id="page_title">State</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $users_detail[0]['state']; ?>" readonly="readonly" />
                        </div>
                        <!-- user name end -->
                        <!-- user name start -->
                        <div class="form-group col-sm-10">
                            <label for="username" name="user_name" id="page_title">City</label>
                            <input type="text" class="form-control" name="name" id="name" value="<?php echo $users_detail[0]['city']; ?>" readonly="readonly" />
                        </div>
                        <!-- user name end -->
                        
                        
                       
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" onclick="window.history.back();" class="btn btn-default">Back</button>
                        <!--<button type="submit" class="btn btn-info pull-right">Sign in</button>-->
                    </div><!-- /.box-footer -->
                    </form>
                </div><!-- /.box -->


            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
<?php echo $footer; ?>

<script type="text/javascript">
    //validation for edit email formate form
    $(document).ready(function () {


        $("#edit_user_frm").validate({
            rules: {
                name: {
                    required: true,
                },
                category_id: {
                    required: true,
                },
                manufacture_id: {
                    required: true,
                },
                cost_price: {
                    required: true,
                    digits: true,
                },
                sell_price: {
                    required: true,
                    digits: true,
                },
                stock: {
                    required: true,
                },
                available_for: {
                    required: true,
                },
                bid_time: {
                    required: true,
                },
                description: {
                    required: true,
                }
            },
            messages:
                    {
                        name: {
                            required: "Please enter user name",
                        },
                        category_id: {
                            required: "Please enter category",
                        },
                        manufacture_id: {
                            required: "Please enter manufacturers",
                        },
                        cost_price: {
                            required: "Please enter user price",
                            digits: "User cost price should be numeric",
                        },
                        sell_price: {
                            required: "Please enter user selling price",
                            digits: "User sell price should be numeric",
                        },
                        stock: {
                            required: "Please enter user stock",
                        },
                        available_for: {
                            required: "Please enter user available for",
                        },
                        bid_time: {
                            required: "Please enter user bidding time",
                        },
                        description: {
                            required: "Please enter user description",
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

<script>
    $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        //   CKEDITOR.replace('editor1');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea1").wysihtml5();
    });
</script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
    });
</script>