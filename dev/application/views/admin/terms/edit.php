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
    <section class="content-header">
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="callout callout-success">
                <p><?php echo $this->session->flashdata('success'); ?></p>
            </div>
        <?php } ?>
        <?php if ($this->session->flashdata('error')) { ?>  
            <div class="callout callout-danger" >
                <p><?php echo $this->session->flashdata('error'); ?></p>
            </div>
        <?php } ?>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-12">

                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $section_title; ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php
                    $form_attr = array('id' => 'edit_terms_frm');
                    echo form_open('admin/terms/edit', $form_attr);
                    ?>
                    <input type="hidden" name="id" value="<?php echo $terms[0]['id'] ?>">
                    <div class="box-body">
                        <div class="form-group col-sm-10">
                            <label for="name" id="page_title">Title</label>
                            <input type="text" disabled="disabled" class="form-control" name="name" id="name" value="<?php echo $terms[0]['name'] ?>">
                        </div>
                        <div class="form-group col-sm-10">
                            <label for="description" id="page_title">Description (<?php echo $terms[0]['lang_name'] ?>)*</label>
                            <textarea rows="10" class="form-control" name="description" id="description"><?php echo $terms[0]['description'] ?></textarea>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <?php
                        $save_attr = array('id' => 'btn_save', 'name' => 'btn_save', 'value' => 'Save', 'class' => 'btn btn-primary');
                        echo form_submit($save_attr);
                        ?>    
                        <button type="button" onclick="window.history.back();" class="btn btn-default">Back</button>
                        <!--<button type="submit" class="btn btn-info pull-right">Sign in</button>-->
                    </div><!-- /.box-footer -->
                    <?php echo form_close(); ?>
                </div><!-- /.box -->


            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    //validation for edit email formate form
    $(document).ready(function () {
        $("#edit_terms_frm").validate({
            rules: {
                description: {
                    required: true,
					minlength: 100
                }
            },
            messages:
            {
                description: {
                    required: "Please enter Terms And Conditions",
                }
            },
        });
		
		$('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
    });
</script>

