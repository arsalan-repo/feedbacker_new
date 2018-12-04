<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>
	<?php
		if (isset($section_title)) {
			echo $section_title." | Feedbacker";
		} else {
			echo $this->lang->line('lbl_welcome');
		}
		//echo $this->lang->line('lbl_welcome'); 
	?>
	</title>
	<link href="<?php echo base_url().'assets/css/font-awesome.min.css'; ?>" rel="stylesheet" type="text/css" />
	<!-- Country Flags -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/dd.css'; ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/sprite.css'; ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/flags.css'; ?>" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/multiple-emails.css'; ?>" />
	
	<?php
	$style = 'style.css';
	$responsive = 'responsive.css';	
	?>    
	<link href="<?php echo base_url().'assets/css/flexslider.css'; ?>" rel="stylesheet" />
	<link href="<?php echo base_url().'assets/css/'.$style; ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url().'assets/css/'.$responsive; ?>" rel="stylesheet" type="text/css" />
	<!-- jQuery 1.12.1 -->
	
	<script src="<?php echo base_url().'assets/js/jquery-1.12.4.min.js';?>"></script>
	
	<!-- jQuery Validate -->
	<script src="<?php echo base_url().'assets/js/jquery.validate.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/additional-methods.js';?>"></script>
	<!-- jQuery Toastr -->
	<script src="<?php echo base_url().'assets/js/toastr.min.js';?>"></script>
	<link href="<?php echo base_url().'assets/css/toastr.min.css'; ?>" rel="stylesheet" />
	<!-- Country Flags -->
	<script src="<?php echo base_url().'assets/js/jquery.dd.js'; ?>"></script>
	<script src="<?php echo base_url().'assets/js/custom.js'; ?>"></script>
	<!-- jQuery UI -->
	<script src="<?php echo base_url().'assets/js/jquery-ui.js'; ?>"></script>
	<link href="<?php echo base_url().'assets/css/jquery-ui.css'; ?>" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.full.js"></script>
	<script src="<?php echo base_url().'assets/js/jquery.flexslider-min.js'; ?>"></script>
	<script src="<?php echo base_url().'assets/js/multiple-emails.js'; ?>"></script>
	
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
	

	<script type="application/javascript">
	$(document).ready(function(e) {		
		// Country Flags
		try {
			var countries = $("#countries").msDropdown({on:{change:function(data, ui) {
				var url = $("#dashboard_url").val();
				var val = data.value;
				
				if(val != "")
					window.location = url+"/"+val;
			}}}).data("dd");
		} catch(e) {
			//console.log(e);	
		}
		populateNotifications();
		setInterval(function(){
		  populateNotifications();
		}, 5000);		
		});
		function populateNotifications(){
			$.getJSON('<?php echo site_url('user/notification_count'); ?>', function(data){				
				if(data.unread_count>0){
					$('#notification-count').addClass('show');
					$('#notification-count').removeClass('hide');
				}else{
					$('#notification-count').addClass('hide');
					$('#notification-count').removeClass('show');
				}
				$('#notification-count').html(data.unread_count);
			});
			
		  }
	</script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-115737090-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-115737090-1');
	</script>

</head>

<body>
	<div class="wrapper <?=current_url(); ?> <?=$_REQUEST['q']; ?>">
	
<!-- Content Wrapper. Contains page content -->
<div class="content-secion">
    <div class="container creatae-post-content">
		<div class="listing-post-name-block">
					<span class="listing-post-name"><a href="<?=site_url('title/'.$title['slug']); ?>"><?=$title['title']; ?></a></span>
					<span class="listing-post-followers"><?=$title['linked_users_count']; ?> Followers	</span>
		</div>
		<div class="profile-listing-img-thumb-block">
			<div class="profile-listing-img-thumb">
						<?php 
						if(isset($title['photo'])) {
							echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $title['photo'].'" alt="" />';
						} else {
							echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
						}
						?>
			</div>
			<span class="listing-post-profile-name"><?=$title['name']; ?></span> <span class="listing-post-profile-time"><?=$title['time']; ?></span> 
		</div>
		<div class="survey-reesults" style="width:100%; clear:both; float:left; margin-top:30px;">
			<h2>Survey Results</h2>			
			<table id="example" class="display" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th>User</th>
					<?php foreach($questions as $question): ?>
						<th><?=$question['question']; ?></th>
					<?php endforeach; ?>					
				</tr>
			</thead>
			<tbody>
			<?php $i=0; foreach($results as $row): $i++; ?>
				<tr>
					<td><?=$i; ?></td>
					<td>
					<span class="profile-listing-img-thumb">
					<?php 
						if(isset($row['photo'])) {
							echo '<img w src="'.S3_CDN . 'uploads/user/thumbs/' . $row['photo'].'" alt="" />';
						} else {
							echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
						}
						?>
						</span>
					<?=$row['name']; ?></td>
					<?php foreach($row['questions'] as $question):  ?>
						<td align="center"><?=$question['answer']; ?></td>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>				
			</tbody>				
		</table>
		</div>
    </div>
  </div>

<!-- /.content-wrapper -->
<script type="text/javascript">
var base_url="<?=base_url();?>";
$(document).ready(function() {
	 $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [            
            {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    {
						extend: 'print',
						orientation: 'landscape',
						messageTop:null,
						customize: function ( win ) {
							$(win.document.body)
								.css( 'font-size', '10pt' )
								.prepend(
									'<div class="header" style="background: #181a53; width: 100%; height: 80px; margin-bottom:20px; padding: 15px 0;"><img src="https://feedbacker.me/dev/assets/images/white-logo.png"></div>'
								);
		 
							$(win.document.body).find( 'table' )
								.addClass( 'compact' )
								.css( 'font-size', 'inherit' );
						}
					},
                    {
						extend: 'pdf',
						orientation: 'landscape',
						messageBottom: function () {	 
							return '<?=date("Y-m-d"); ?>';							
						},
					}
                ]
            }
        ]
    } );
	/* $('#example').DataTable( {
		responsive: true,
		"pageLength": 25,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
			{
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }				
        ]
    } );*/
	
});
</script>


	<footer>© <?=date('Y'); ?> Feedbacker</footer>
	</div>
</body>
</html>

