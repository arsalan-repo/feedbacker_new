<!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo S3_CDN . $frameworks_dir . '/bootstrap/css/bootstrap.min.css'?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo S3_CDN . $frameworks_dir . '/adminlte/css/AdminLTE.css'; ?>">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo S3_CDN . $plugins_dir . '/iCheck/square/blue.css';?>">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
      </head>
      <body class="hold-transition login-page">
        <div class="login-box">
          <div class="login-logo">
            <a href="javascript:void(0)">
                <!--Monika's Eyebrow-->
                <img src="<?php echo S3_CDN . $frameworks_dir . '/adminlte/img/logo.jpg'; ?>"/>
            </a>
          </div><!-- /.login-logo -->


           <?php if ($this->session->flashdata('success')) { ?>
                <div class="alert fade in alert-success myalert">
                    <i class="icon-remove close" data-dismiss="alert"></i>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?>
            <?php if ($this->session->flashdata('error')) { ?>  
                     <?php echo $this->session->flashdata('error'); ?>
            <?php } ?>

        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            <?php echo form_open('admin/login/authenticate',array('name'=>'login_frm','id'=>'login_frm','method'=>'POST'));  ?>
            <div class="form-group has-feedback">
                <input name="admin_user" id="admin_user" type="text" class="form-control" placeholder="Username">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                <label class="user_err error" for="admin_user" style="color:red;display: none;">Username Required</label>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="admin_password" id="admin_password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" id="submit"  class="submit btn btn-primary btn-block btn-flat">Sign In</button>
                </div><!-- /.col -->
            </div>

            </form>
            <!--<a href="javascript:void(0);" data-toggle="modal" data-target="#myModal">Forgot Password?</a>-->
            <a href="javascript:void(0);">Forgot Password?</a>

            <br>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <!-- jQuery 2.1.4 -->
        <script src="<?php echo S3_CDN . $plugins_dir . '/jQuery/jQuery-2.1.4.min.js';?>"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo S3_CDN . $frameworks_dir . '/bootstrap/js/bootstrap.min.js';?>"></script>
        <!-- iCheck -->
        <script src="<?php echo S3_CDN . $plugins_dir . '/iCheck/icheck.min.js';?>"></script>
         <!-- jQuery validation -->
        <script type="text/javascript" src="<?php echo S3_CDN . $plugins_dir . '/validation/jquery.validate.min.js'; ?>"></script>

    </body>
</html>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">forgot Password</h4>
        </div>
        <div class="modal-body">
            <?php echo form_open('login/forgotpass',array('name'=>'forgot_frm_user','id'=>'forgot_frm','method'=>'POST'));  ?>

            <div class="row">
                <div class="form-group col-sm-10">
                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                    </div>
                </div>

                 <div class="col-sm-3   ">
                     <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat">send</button>
                </div><!-- /.col -->
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo S3_CDN . $plugins_dir . '/validation/jquery.validate.min.js'; ?>"></script>
<script type="text/javascript">
    //validation for edit email formate form
    $(document).ready(function () {

        $("#login_frm").validate({
            rules: {
                admin_user: {
                    required: true,
                },
                admin_password: {
                    required: true,
                }
            },
            messages: {
                admin_user: {
                    required: "Email Id Is Required.",
                },
                admin_password: {
                    required: "Password Is Required",
                }

            },
        });

    });

    //validation for edit email formate form
    $(document).ready(function () {

        $("#forgot_password").validate({
            rules: {
                forgot_email: {
                    required: true,
                    email:true
                }
            },
            messages: {
                forgot_email: {
                    required: "Email Address Required",
                    email:"Please Enter Valid Email ID"
                }
            },
        });
    });
</script>

<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');    
    });
</script>
