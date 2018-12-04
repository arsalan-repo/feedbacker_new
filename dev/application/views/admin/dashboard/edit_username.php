<?php echo $header;
echo $leftmenu;
?>
<div id="content">

    <!--<div id="content-header" class="mini">
        <h1>Edit Username</h1>

    </div>
    <div id="breadcrumb">
        <a href="<?php echo base_url(); ?>" title="Go to Dashboard" class="tip-bottom"><i class="fa fa-home"></i> Dashboard</a>
        <a href="<?php echo base_url() . 'dashboard'; ?>" title="Go to Profile" class="tip-bottom"> Dashboard</a>
        <a href="#" class="current">Edit Username</a>
    </div>
-->
    <div class="row">
        <div class="col-xs-12">
            <!--flash messages start-->
<?php if ($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                    <button class="close" data-dismiss="alert">×</button>
                    <strong><?php echo $this->session->flashdata('success'); ?></strong>
                </div>
            <?php } ?>
<?php if ($this->session->flashdata('error')) { ?>
                <div class="alert alert-danger">
                    <button class="close" data-dismiss="alert">×</button>
                    <strong><?php echo $this->session->flashdata('error'); ?></strong>
                </div>
<?php } ?>
            <!--flash messages end-->
            <div class="widget-box">
                <div class="widget-title">
                   <!-- <span class="icon">
                        <i class="fa fa-th"></i>
                    </span>
                   -->
                    <h5>Edit Username</h5>
                </div>
                <div class="widget-content nopadding">

                    <?php
                    $form_attr = array('id' => 'edit_username_form', 'class' => 'form-horizontal');
                    echo form_open('dashboard/edit_username', $form_attr);
                    ?>

                    <div class="form-group">
                        <label class="col-sm-3 col-md-3 col-lg-2 control-label">Username *</label>
                        <div class="col-md-6">
                            <?php
                            $email_attr = array('class' => 'form-control', 'id' => 'email', 'name' => 'email', 'value' => $admin_detail[0]['email'], 'maxlength' => '50');
                            echo form_input($email_attr);
                            ?>
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
    </div>
</div>
<?php echo $footer; ?>
<script>
    $(document).ready(function () {

        $("#edit_username_form").validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo base_url() . 'dashboard/check_unique' ?>",
                        type: "post",
                        data: {
                            email: function () {
                                return $("#email").val();
                            },
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>',
                        },
                    },
                },
            },
            messages:
                    {
                        email: {
                            required: "Username Is Required",
                            email: "Please Enter Valied Email Id",
                            remote: "Email Id Already Exists",
                        },
                    },
            errorClass: "help-inline",
            errorElement: "span",
            highlight: function (element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('has-success').addClass('has-error');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents('.form-group').removeClass('has-error').addClass('has-success');
            }
        });
    });
</script>
