<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?= $title; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo S3_CDN. $frameworks_dir . '/bootstrap/css/bootstrap.min.css'; ?>">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"> 
        <!-- Ionicons -->
        <!--  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo S3_CDN . $frameworks_dir . '/adminlte/css/AdminLTE.css'; ?>">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo S3_CDN . $frameworks_dir . '/adminlte/css/skins/_all-skins.min.css'; ?>">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo S3_CDN . $plugins_dir . '/iCheck/flat/blue.css'; ?>">
        <!-- Morris chart -->
        <!--<link rel="stylesheet" href="<?php // echo base_url('plugins/morris/morris.css');   ?>">-->

       
        <!-- Date Picker -->
        <link rel="stylesheet" href="<?php echo S3_CDN . $plugins_dir . '/datepicker/datepicker3.css'; ?>">
        <!-- Daterange picker -->
        <!--<link rel="stylesheet" href="<?php echo S3_CDN . $plugins_dir . '/daterangepicker/daterangepicker-bs3.css'; ?>">-->
        <!-- bootstrap wysihtml5 - text editor -->
        <!--<link rel="stylesheet" href="<?php echo S3_CDN . $plugins_dir . '/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'; ?>">-->

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!--ALL JS Start Here-->
        <!-- jQuery 2.1.4 -->
        <script src="<?php echo S3_CDN . $plugins_dir . '/jQuery/jQuery-2.1.4.min.js'; ?>"></script>
        <!-- jQuery UI 1.11.4 -->
         <!--<script src="<?php // echo base_url('plugins/jQuery/jquery-ui.min.1.11.4.js');   ?>"></script>-->

        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo S3_CDN . $frameworks_dir . '/bootstrap/js/bootstrap.min.js'; ?>"></script>
        <!-- Morris.js charts -->
     <!--   <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> // Ankit   -->
    <!--    <script src="<?php //echo base_url('plugins/morris/morris.min.js');   ?>"></script>-->
        <!-- Sparkline -->
        <!-- jvectormap -->
        <!-- jQuery Knob Chart -->
        <script src="<?php echo S3_CDN . $plugins_dir . '/knob/jquery.knob.js'; ?>"></script>
        <!-- daterangepicker -->
    <!---     <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script> // Ankit  -->  
        <!--<script src="<?php echo S3_CDN . $plugins_dir . '/daterangepicker/daterangepicker.js'; ?>"></script>-->
        <!-- datepicker -->
        <script src="<?php echo S3_CDN . $plugins_dir . '/datepicker/bootstrap-datepicker.js'; ?>"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <!--<script src="<?php echo S3_CDN . $plugins_dir . '/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'; ?>"></script>-->

        <!-- Slimscroll -->
        <script src="<?php echo S3_CDN .$plugins_dir . '/slimScroll/jquery.slimscroll.min.js'; ?>"></script>
        <!-- FastClick -->
        <script src="<?php echo S3_CDN .$plugins_dir . '/fastclick/fastclick.min.js'; ?>"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo S3_CDN . $frameworks_dir . '/adminlte/js/app.min.js'; ?>"></script>
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <!--<script src="<?php echo S3_CDN .$frameworks_dir . '/adminlte/js/pages/dashboard.js'; ?>"></script>-->
        <!-- AdminLTE for demo purposes -->
        <script src="<?php echo S3_CDN . $frameworks_dir . '/adminlte/js/demo.js'; ?>"></script>
        <!-- CK Editor -->
        <script src="<?php echo S3_CDN . $plugins_dir . '/ckeditor/ckeditor.js'; ?>"></script>
        <!--<script src="https://cdn.ckeditor.com/4.4.3/standard/ckeditor.js"></script>-->
        <!--for arrow of sorting-->
        <!-- Form Validation -->
        <script type="text/javascript" src="<?php echo S3_CDN . $plugins_dir . '/validation/jquery.validate.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo S3_CDN . $plugins_dir . '/validation/jquery.validate.methods.js'; ?>"></script>
		
		<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"/>
	
	<link href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css" rel="stylesheet"/>
	<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet"/>
	<link href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css" rel="stylesheet"/>

	<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

	<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
	
    <!-- DataTables -->
	<!--
    <script src="<?php echo S3_CDN . $plugins_dir . '/datatables/jquery.dataTables.min.js'; ?>"></script>
    <script src="<?php echo S3_CDN . $plugins_dir . '/datatables/dataTables.bootstrap.min.js'; ?>"></script>
    <link rel="stylesheet" href="<?php echo S3_CDN .$plugins_dir . '/datatables/dataTables.bootstrap.css'; ?>">
	-->

         <!-- Date And Times -->
        <!--<script src="<?php echo S3_CDN . $frameworks_dir . '/bootstrap/js/bootstrap-datetimepicker.min.js'; ?>"></script>
        <link rel="stylesheet" href="<?php echo S3_CDN .$frameworks_dir . '/bootstrap/css/bootstrap-datetimepicker.min.css'; ?>">-->
        <!-- Date And Times -->
        

        <!--          Resolve conflict in jQuery UI tooltip with Bootstrap tooltip 
            <script>
              $.widget.bridge('uibutton', $.ui.button);
            </script>-->


        <!--for multiple delete-->
        <script type="text/javascript">

            $(document).ready(function () {
                $('#confirm-delete').on('show.bs.modal', function (e) {
                    $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
                });
                $('#search_btn').click(function () {
                    $('#search_frm').submit();
                });
                $('#checkedall').click(function (event) {
                    if (this.checked) {
                        // Iterate each checkbox
                        $('.deletes').each(function () {
                            this.checked = true;
                        });
                    }
                    else {
                        $('.deletes').each(function () {
                            this.checked = false;
                        });
                    }
                });
                $('.deletes').click(function (event) {
                    var flag = 0;
                    $('.deletes').each(function () {
                        if (this.checked == false) {
                            flag++;
                        }
                    });
                    if (flag) {
                        $('.checkedall').prop('checked', false);
                    }
                    else {
                        $('.checkedall').prop('checked', true);
                    }
                });
            });
        </script>
        
<!--        <link rel="stylesheet" type="text/css" href="<?php echo S3_CDN .$plugins_dir . '/clockpicker/bootstrap-clockpicker.min.css'; ?>">
        <script type="text/javascript" src="<?php echo S3_CDN .$plugins_dir . '/clockpicker/bootstrap-clockpicker.min.js'; ?>"></script>-->

        <!-- For multiselect checkboxes -->
        <link rel="stylesheet" type="text/css" href="<?php echo S3_CDN .$plugins_dir . '/multiselect/jquery.multiselect.css'; ?>">
        <script type="text/javascript" src="<?php echo S3_CDN . $plugins_dir . '/multiselect/jquery.multiselect.js'; ?>"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        <script>
		$(document).ready(function() {
			$('.select2').select2();
		});
		</script>
		<?php if(!empty($map['js'])){ echo $map['js']; } ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
