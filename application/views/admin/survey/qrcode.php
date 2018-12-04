<html>
<head>
<title>QR Code - Questionnaire</title>
<style>
body{margin:0; padding:0;}
.header {
    background: #181a53;
    width: 100%;
    height: 54px;
    padding: 15px 0;
}
.container{ margin:0 auto; width:96%;}
@media print {
   .button-wrapper {
       display: none;
    }
    .header , .logo, .logo img{
       display: block;
    }
	.header{background: #181a53;
    width: 100%;
    height: 54px;
    padding: 15px 0;}
}
</style>
</head>
<body>
<div class="wrapper" style="">
<div class="header" style="background:#181a53;">
  <div class="container">
      <div class="logo">	  
	  <img src="https://feedbacker.me/assets/images/white-logo.png" alt="" >
	  </div>
	   
	</div>
</div>
   

    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content" style="padding:50px; text-align:center;">
		
        <div class="container">	
			 <h1>
            <?php echo $module_name; ?>
           
			</h1>
			<div class="row">
				<div class="col-xs-12">
					<div class="box text-center">
						
				   <img src="<?=base_url('qrcode/'.$qrcode_name); ?>">
					
					</div><!-- /.box -->
					<div class="button-wrapper">
						<input type="button" onclick="printQRCODE()" value="Print QR Code" />
					</div>

				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->



<script type="text/javascript">
function printQRCODE(){
	window.print();
}

</script>
</body></html>
