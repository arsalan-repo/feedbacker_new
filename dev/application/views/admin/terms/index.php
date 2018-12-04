<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $module_name; ?>
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

    <!-- Content Header (Page header) -->

    <!-- Main content -->
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
                        <h3 class="box-title">Terms And Conditions</h3>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tbl-terms" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>                                    
                                    <th>Description</th>
                                    <th>Language</th>
									<th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($terms as $row) { ?>
                                    <tr>
                                        <td><?php echo $row['name'] ?></td>
                                        <td>
										<?php 
											$pos = strpos($row['description'], ' ', 500);
											echo substr($row['description'], 0, $pos);
										?>
										</td>
                                        <td><?php echo $row['lang_name'] ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/terms/edit/' . $row['lang_code']); ?>" id="edit_btn" title="Edit Terms">
                                                <button type="button" class="btn btn-primary" style="margin-top: 3px;"><i class="icon-pencil"></i> <i class="fa fa-pencil-square-o"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
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
</div><!-- /.row -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function () {
		$("#tbl-terms").DataTable();
	
        $('#search_frm').submit(function () {
            var value = $('#search_keyword').val();
            if (value == '')
                return false;
        });
		
		$('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
    });
</script>
<!-- page script -->
