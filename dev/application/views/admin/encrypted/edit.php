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
                    echo form_open_multipart('', $form_attr);
                    ?>
                    <input type="hidden" name="title_id" value="<?php echo $title_detail['title_id'] ?>">
                    <div class="box-body">
                        <div class="form-group col-sm-10">
                            <label for="feedback_cont" id="page_title">Title*</label>
                            <input type="text" class="form-control" name="title"  value="<?php echo $title_detail['title'] ?>">
                        </div>
						<div class="form-group col-sm-12">
							 <p><a href="<?=site_url('admin/encrypted/linked_users/'.$title_detail['title_id']); ?>">View linked Users</a></p>
                             <p><a href="<?=site_url('admin/encrypted/feedbacks/'.$title_detail['title_id']); ?>">View Feedbacks</a></p>
							  <p><a href="<?=site_url('admin/encrypted/join_requests/'.$title_detail['title_id']); ?>">View Pending Joining Requests</a></p>
						</div>
                        <div class="modal fade" id="modalPublicForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header text-center">
										<h4 class="modal-title w-100 font-weight-bold">Make Title Public</h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body mx-3">
										<?php $allowed_countries=$title_detail['allowed_countries'];  if(!empty($allowed_countries)) $allowed_countries=explode(",",$allowed_countries); else $allowed_countries=array();?>
										<div class="md-form mb-5">
											<label for="country" id="page_title">Country*</label>
											<select name="country[]" id="country" class="form-control select2" multiple="multiple" style="width: 100%;">
												<option value="">All</option>
												<?php
												foreach ($country_list as $country) {
													?>
													<option value="<?php echo $country['country_code']; ?>" <?php if(in_array($country['country_code'],$allowed_countries)) echo 'selected'; ?>><?php echo $country['country_name']; ?></option>
													<?php
												}
												?>
											</select>
										</div>

									</div>
									<div class="modal-footer d-flex justify-content-center">
										<button class="btn btn-deep-orange">Submit</button>
									</div>
								</div>
							</div>
						</div>
						<?php if(!$title_detail['is_public']): $btn_text='Make public'; ?>
						
						<?php else: $btn_text='Edit Public Country List'; ?>
						
						
						<div class="form-group col-sm-12">
							<label>Allowed Countries</label>
							<?php if(!empty($title_detail['allowed_countries'])): ?>							
							<p><?=$title_detail['allowed_countries']; ?></p>
							<?php else: ?>
							<p>All</p>
							<?php endif; ?>
						</div>
						<?php endif; ?>
						<div class="form-group col-sm-12">							
							<a href="" class="btn btn-primary btn-rounded mb-4" data-toggle="modal" data-target="#modalPublicForm"><?=$btn_text; ?></a>
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
