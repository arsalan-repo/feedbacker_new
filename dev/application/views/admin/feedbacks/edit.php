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
                    $form_attr = array('id' => 'edit_feedback_frm', 'enctype' => 'multipart/form-data');
                    echo form_open_multipart('admin/feedbacks/edit', $form_attr);
                    ?>
                    <input type="hidden" name="feedback_id" value="<?php echo $feedback_detail[0]['feedback_id'] ?>">
                    <div class="box-body">
                        <div class="form-group col-sm-10">
                            <label for="feedback_cont" id="page_title">Feedback*</label>
                            <?php /*?><input type="text" class="form-control" name="feedback_cont" id="feedback_cont" value="<?php echo $feedback_detail[0]['feedback_cont'] ?>"><?php */?>
                            <textarea rows="10" class="form-control" name="feedback_cont" id="feedback_cont">
							<?php echo str_replace("\\n","\r\n",str_replace("\\r\\n","\r\n",$this->emoji->Decode($feedback_detail[0]['feedback_cont']))); ?>
							
							</textarea>
                        </div>
                        <!-- feedback image start -->
                        <?php if($feedback_detail[0]['feedback_img']) { ?>
                        <div class="form-group col-sm-10">
                            <label for="feedback_thumb" id="page_title"></label>
                            <a href="<?php echo S3_CDN . 'uploads/feedback/main/' . $feedback_detail[0]['feedback_img'] ?>" target="_blank">
                                <img src="<?php echo S3_CDN . 'uploads/feedback/thumbs/' . $feedback_detail[0]['feedback_thumb'] ?>" alt="<?php echo $feedback_detail[0]['feedback_thumb'] ?>" width="180">
                            </a>    
                            <input type="hidden" name="old_image" value="<?php echo $feedback_detail[0]['feedback_thumb']; ?>" />
                            <a href="<?php echo base_url('admin/feedbacks/remove_photo/' . $feedback_detail[0]['feedback_id']) ?>" title="Remove Photo" style="margin: 0 auto; width: 120px;" >Remove Photo</a>
                        </div>
                        <?php } ?>
                        <?php if($feedback_detail[0]['feedback_video']) { ?>
                        <div class="form-group col-sm-10">
                            <label for="feedback_thumb" id="page_title"></label>
                            <a href="<?php echo S3_CDN . 'uploads/feedback/video/' . $feedback_detail[0]['feedback_video'] ?>" target="_blank">
                                <img src="<?php echo S3_CDN . 'uploads/feedback/thumbs/' . $feedback_detail[0]['feedback_thumb'] ?>" alt="<?php echo $feedback_detail[0]['feedback_thumb'] ?>" width="180">
                            </a>
                            <input type="hidden" name="old_image" value="<?php echo $feedback_detail[0]['feedback_thumb']; ?>" />
                            <a href="<?php echo base_url('admin/feedbacks/remove_video/' . $feedback_detail[0]['feedback_id']) ?>" title="Remove Video" style="margin: 0 auto; width: 120px;" >Remove Video</a>
                        </div>
                        <?php } ?>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <?php
                        $save_attr = array('id' => 'btn_save', 'name' => 'btn_save', 'value' => 'Save', 'class' => 'btn btn-primary');
                        echo form_submit($save_attr);
                        ?>    
                        <button type="button" onclick="window.history.back();" class="btn btn-default">Back</button>
                        <!--<button type="submit" class="btn btn-info pull-right">Sign in</button>-->
                    </div><!-- /.box-footer -->
                    </form>
                </div><!-- /.box -->


            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    //validation for edit email formate form
    $(document).ready(function () {

        $("#edit_feedback_frm").validate({
            rules: {
                feedback_cont: {
                    required: true,
                    minlength: 3,
                    //maxlength: 300
                }
            },
            messages:
            {
                feedback_cont: {
                    required: "Please enter feedback",
                }
            },
        });
    });
</script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
    });
</script>
