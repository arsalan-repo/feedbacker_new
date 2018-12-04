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
                            <a href="<?php echo site_url('admin/reports/search/') . $country; ?>" class="btn btn-primary pull-right">Back</a>
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
                                    <th>Gender</th>
                                    <th>Country</th>
                                    <th>Birth Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($user_list as $user) { ?>
                                    <tr>
                                        <td><?php echo $user['id'] ?></td>
                                        <td><?php echo $user['name'] ?></td>
                                        <?php if($user['photo']) { ?>
                                            <td><img src="<?php echo S3_CDN . 'uploads/user/thumbs/' . $user['photo']; ?>" width='50' height="50"></td>
                                        <?php } else { ?>
                                            <td><img src="<?php echo ASSETS_URL . 'images/user-avatar.png'; ?>" width='50' height="50"></td>
                                        <?php } ?>
                                        <td><?php echo $user['email'] ?></td>
                                        <td><?php echo $user['gender'] ?></td>
                                        <td><?php echo $user['country'] ?></td>
                                        <td><?php echo ($user['dob']) ? $user['dob'] : 'N/A' ?></td>
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
        $('#search_frm').submit(function () {
            var value = $('#search_keyword').val();
            if (value == '')
                return false;
        });
    });
</script>
<!-- page script -->
<script>
    $(function () {
        $("#example1").DataTable();
    });
</script>
