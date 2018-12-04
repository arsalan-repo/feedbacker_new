<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>


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
					<?php foreach($row['questions'] as $question): ?>
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
