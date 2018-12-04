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
            <!-- Filter Country -->
            <div class="col-sm-6">
                <label for="country" id="page_title">Country</label>
                <select name="country" id="country" class="form-control select2">
                    <option value="">Select</option>
					<?php foreach ($country_list as $country) { ?>

                    <option value="<?php echo $country['country_code']; ?>" <?php if($selected == $country['country_code']) echo "selected"; ?>><?php echo $country['country_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Keywords</h3>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Search Keyword</th>
                                    <th>Search Count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($search_log as $log) {
                                    ?>
                                    <tr>
                                        <td><?php echo $log['slog_id'] ?></td>
                                        <td><?php echo $log['search_keyword'] ?></td>
                                        <td><?php echo $log['search_count']; ?></td>
                                        <td>
                                            <a href="<?php echo base_url('admin/reports/users/' . $log['slog_id'] . '/'. $selected); ?>" id="edit_btn" title="View Users">
                                                <button type="button" class="btn btn-primary" style="margin-top: 3px;"><i class="icon-pencil"></i> <i class="fa fa-users"></i></button>
                                            </a>
                                            <a data-href="<?php echo base_url('admin/reports/delete/' . $log['slog_id']); ?>" id="delete_btn" data-toggle="modal" data-target="#confirm-delete" href="#" title="Delete Entry">
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
                Are you sure want to delete this log entry?
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

        $('#country').change(function(){
            window.location.href = "<?php echo base_url('admin/reports/search/'); ?>" + $(this).val();
        });
    });
</script>
<!-- page script -->
<script>
    $(function () {
        $("#example1").DataTable();
    });
</script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
    });
</script>
