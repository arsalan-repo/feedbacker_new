<link type="text/css" rel="stylesheet" id="SDbase" href="<?=base_url('assets/css/'); ?>survey-base.css">
<style>
.survey-type-options .form-check-inline{ margin-right:15px;}
.survey-type-options .form-check-inline input{ margin:0; margin-right:5px;}
</style>
<script type="text/javascript" src="<?=base_url('assets/js/'); ?>/survey.js"></script>
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
                    
                    <?php
					
                    $form_attr = array('id' => 'edit_feedback_frm', 'enctype' => 'multipart/form-data');
                    echo form_open_multipart('', $form_attr);
                    ?>
                  
                    <div class="box-body">
                        <div class="form-group col-sm-10">
                            <label for="feedback_cont" id="page_title">Title*</label>
                            <input type="text" class="form-control" name="title"  value="">
                        </div>
						
                       <div class="form-group col-sm-10">
                            <label for="feedback_cont" id="page_title">Location*</label>
                            <input type="text" class="form-control" id="location" name="location"  value="">
							<input type="hidden" id="location_lat" name="lat" value="">
							<input type="hidden" id="location_lng" name="lng" value="">
							<input type="hidden" id="location_address" name="address" value="">
							<?php echo $map['html']; ?>
                        </div>
						<?php //print_r($survey); ?>
						<?php  for($i=1; $i<11; $i++): ?>
						<div class="question-wrapper row" id="question-wrapper-<?=$i; ?>" style="border-bottom:1px solid #CCC; margin:0; margin-bottom:20px;">
						<div class="form-group col-sm-10">
                            <label for="feedback_cont" id="page_title">Question <?=$i; ?></label>
                            <input type="text" class="form-control" name="questions[<?=$i; ?>]"  value="">
                        </div>
						<div class="form-group col-sm-12 survey-type-options">
							<div class="form-check-inline" style="display:inline;">
							  <label class="form-check-label">
								<input type="radio" class="form-check-input question_type" data-question="<?=$i; ?>" checked value="mcq" name="qtype[<?=$i; ?>]">MCQs
							  </label>
							</div>
							<div class="form-check-inline" style="display:inline;">
							  <label class="form-check-label">
								<input type="radio" class="form-check-input question_type" data-question="<?=$i; ?>" value="single" name="qtype[<?=$i; ?>]">Single Answer
							  </label>
							</div>
							<div class="form-check-inline" style="display:inline;">
							  <label class="form-check-label">
								<input type="radio" class="form-check-input question_type" data-question="<?=$i; ?>" value="checkbox" name="qtype[<?=$i; ?>]">Checkbox
							  </label>
							</div>	
						</div>
						
						<div class="form-group col-sm-12" id="options-wrapper-<?=$i; ?>">
							<div class="row">
								<div class="form-group col-sm-3"> <input type="text" class="form-control" placeholder="Option 1" name="options[<?=$i; ?>][]"  value=""></div>
								<div class="form-group col-sm-3"><input type="text" class="form-control" placeholder="Option 2" name="options[<?=$i; ?>][]"  value=""></div>
								<div class="form-group col-sm-3"><input type="text" class="form-control" placeholder="Option 3" name="options[<?=$i; ?>][]"  value=""></div>
								<div class="form-group col-sm-3"><input type="text" class="form-control" placeholder="Option 4" name="options[<?=$i; ?>][]"  value=""></div>
								<div class="form-group col-sm-3"><input type="text" class="form-control" placeholder="Option 5" name="options[<?=$i; ?>][]"  value=""></div>
								<div class="form-group col-sm-3"><input type="text" class="form-control" placeholder="Option 6" name="options[<?=$i; ?>][]"  value=""></div>
								<div class="form-group col-sm-3"><input type="text" class="form-control" placeholder="Option 7" name="options[<?=$i; ?>][]"  value=""></div>
								<div class="form-group col-sm-3"><input type="text" class="form-control" placeholder="Option 8" name="options[<?=$i; ?>][]"  value=""></div>
							</div>
                        </div>
						<div class="form-group col-sm-12" id="answer-wrapper-<?=$i; ?>" style="display:none;">
							<div class="row">
								<div class="form-group col-sm-6"> <input type="text" class="form-control" placeholder="Single Answer Lable. etc. years / % / days" name="qtype_label[<?=$i; ?>]"  value=""></div>								
							</div>
                        </div>
						<div class="form-group col-sm-12" id="checkbox-wrapper-<?=$i; ?>" style="display:none;">
							<div id="fields-wrapper-<?=$i; ?>" class="row">	
								<div class="form-group col-sm-10">
									<input type="text" class="form-control" placeholder="Option 1" name="choices[<?=$i; ?>][]">
								</div>								
							</div>
							<div class="button-wrapper col-sm-10" ><a href="#" data-question="<?=$i; ?>" class="add_more_field">Add More</a></div>
                        </div>
						
						</div>
						
						<?php endfor; ?>
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
	function removethis(id){
			var wrapper="#p"+id;
			$(wrapper).remove();
			return false;
		}
    $(document).ready(function () {
		$(".question_type").change(function(){		
			var question=$(this).data("question");
			console.log(question);
			var options_wrapper='#options-wrapper-'+question;
			var answer_wrapper='#answer-wrapper-'+question;
			var emoji_wrapper='#emoji-wrapper-'+question;
			var checkbox_wrapper='#checkbox-wrapper-'+question;
			var tval=$(this).val();
			
			if (tval=='mcq') {
				$(options_wrapper).show();
				$(answer_wrapper).hide();
				$(checkbox_wrapper).hide();
			} else if (tval=='checkbox') {
				$(checkbox_wrapper).show();
				$(answer_wrapper).hide();
				$(options_wrapper).hide();
			} else {
				$(answer_wrapper).show();
				$(options_wrapper).hide();				
				$(checkbox_wrapper).hide();
			}
			
		});
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
		$('.add_more_field').on('click', function(e) { 
				e.preventDefault();
				var qid=$(this).data('question');
				var wrapper="#fields-wrapper-"+qid;	
				var count = $(wrapper+" div.form-group").length;
				count++;				
				var pwrid=qid+""+count;
				
				$(wrapper).append('<div id="p'+pwrid+'" class="form-group col-sm-10"><input type="text" class="form-control" placeholder="Option '+count+'" name="choices['+qid+'][]"> <a href="#" style="float:right;" onclick="return removethis('+pwrid+');" data-question="'+qid+'" class="remove_field">Remove</a></div>');
			});
    });
</script>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
    });
</script>
