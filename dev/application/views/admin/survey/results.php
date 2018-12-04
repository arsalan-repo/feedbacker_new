<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="content-wrapper">
	<section class="content-header">
        <h1>
            Questionnaires
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
	 <section class="content">
        <div class="row" >
            <div class="col-xs-12" >
                <?php if ($this->session->flashdata('success')) { ?>
                    <div class="callout callout-success">
                        <p><?php echo $this->session->flashdata('success'); ?></p>
                    </div>
                <?php } ?>
                <?php if ($this->session->flashdata('error')) { ?>  
                    <div class="callout callout-danger">
                        <p><?php echo $this->session->flashdata('error'); ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
		 <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><?php echo $section_title; ?></h3>
                      
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
								<div class="survey-reesults" style="width:100%; clear:both; float:left; margin-top:30px;">
								<h2>Survey Results</h2>			
								<table id="example" class="display" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>#</th>
										<th>IP</th>
										<th>Browser</th>
										<?php foreach($survey['questions'] as $question): ?>
											<th><?=$question['question']; ?></th>
										<?php endforeach; ?>					
									</tr>
								</thead>
								<tbody>
								<?php $i=0; foreach($results as $row): $i++; ?>
									<tr>
										<td><?=$i; ?></td>
									<!--	<td>
										<span class="profile-listing-img-thumb">
										<?php 
											if(isset($row['photo'])) {
												echo '<img width="48" src="'.S3_CDN . 'uploads/user/thumbs/' . $row['photo'].'" alt="" />';
											} else {
												echo '<img width="48"  src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
											}
											?>
											</span>
										<?php if(!empty($row['name'])) echo $row['name']; else echo $row['email']; ?></td>-->
										<td><?=$row['ip']; ?></td>
										<td><?=$row['browser']; ?></td>
										<?php foreach($row['questions'] as $question):?>
											<td align="center">
											
											<?=$question['answer']; ?>
											
											</td>
										<?php endforeach; ?>
									</tr>
								<?php endforeach; ?>				
								</tbody>				
							</table>
							</div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                </tbody>
                <tfoot>

                </tfoot>
                </table>
            </div><!-- /.box -->


        </div><!-- /.col -->
	</section>
</div>
<!-- Content Wrapper. Contains page content -->


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
