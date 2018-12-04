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
                        <h3 class="box-title">Users</h3>
                        <div class=" pull-right">
                            <a href="<?php echo site_url('admin/users/add'); ?>" class="btn btn-primary pull-right">Add User</a>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Photo</th>
                                    <th>Email Address</th>
                                    <th>Status</th>
                                    <th>Gender</th>
                                    <th>Country</th>
                                    <th>Total Feedbacks</th>
                                    <th>Birth Date</th>
                                    <th>Last Login</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($user_list as $user) {
                                    if ($user['status'] == 1) {
                                        $user_status = 'Active';
                                    } elseif ($user['status'] == 0) {
                                        $user_status = 'In Active';
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $user['id'] ?></td>
                                        <td><?php echo $user['name'] ?></td>
                                        <?php if($user['photo']) { ?>
                                            <td><img src="<?php echo S3_CDN . 'uploads/user/thumbs/' . $user['photo']; ?>" width='50' height="50"></td>
                                        <?php } else { ?>
                                            <td><img src="<?php echo ASSETS_URL . 'images/user-avatar.png'; ?>" width='50' height="50"></td>
                                        <?php } ?>
                                        <td><?php echo $user['email'] ?></td>
                                        <td><a href="<?php echo base_url('admin/users/change_status/' . $user['id'] . '/' . $user['status']); ?>" id="edit_btn">
                                                <?php echo $user_status ?> </a></td>
                                        <td><?php echo $user['gender'] ?></td>
                                        <td><?php echo $user['country'] ?></td>
                                        <?php if ($user['total_feedback'] > 0) { ?>
                                        <td><a href="<?php echo base_url('admin/users/feedbacks/' . $user['id']); ?>"><?php echo $user['total_feedback']; ?></a></td>
                                        <?php } else { ?>
                                        <td><?php echo $user['total_feedback']; ?></td>
                                        <?php } ?>
                                        <td><?php echo ($user['dob']) ? $user['dob'] : 'N/A' ?></td>
                                        <td><?php echo $user['last_login']; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/users/edit/' . $user['id']); ?>" id="edit_btn" title="Edit User">
                                                <button type="button" class="btn btn-primary" style="margin-top: 3px;"><i class="icon-pencil"></i> <i class="fa fa-pencil-square-o"></i></button>
                                            </a>
                                            <!-- <a href="<?php echo base_url('admin/users/reset_password/' . $user['id']); ?>" id="edit_btn" title="Reset Password">
                                                <button type="button" class="btn btn-primary" style="margin-top: 3px;"><i class="icon-pencil"></i> <i class="fa fa-exchange"></i></button>
                                            </a> -->
                                            <a data-href="<?php echo base_url('admin/users/delete/' . $user['id']); ?>" id="delete_btn" data-toggle="modal" data-target="#confirm-delete" href="#" title="Delete User">
                                                <button type="button" class="btn btn-primary" style="margin-top: 3px;"><i class="icon-trash"></i> <i class="fa fa-ban"></i></button>
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
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="frm_title">Delete Conformation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $('#confirm-delete').on('show.bs.modal', function (e) {
            $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
        });

        $('#search_frm').submit(function () {
            var value = $('#search_keyword').val();
            if (value == '')
                return false;
        });


        $('#checkedall').click(function (service) {
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

        $('.deletes').click(function (service) {
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
<!-- page script -->
<script>
    $(function () {
      //  $("#example1").DataTable();
		$('#example1').DataTable( {
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
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
    });
</script>