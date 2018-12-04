<style>
.survey-type-options .form-check-inline{ margin-right:15px;}
.survey-type-options .form-check-inline input{ margin:0; margin-right:5px;}
</style>
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
					<div class="form-group col-sm-12">
							 
                             <p><a class="btn btn-primary btn-rounded mb-4" href="<?=site_url('admin/questionnaire/results/'.$survey['survey_id']); ?>">View Results</a>
							 <a class="btn btn-primary btn-rounded mb-4" href="<?=site_url('admin/questionnaire/qrcode/'.$survey['survey_id']); ?>">Print QRcode</a>
							 <a class="btn btn-primary btn-rounded mb-4" target="_blank" href="<?=site_url('questionnaire/'.md5($survey['survey_id'])); ?>">Preview</a>
							 </p>
							
					</div>
                    <?php
					
                    $form_attr = array('id' => 'edit_feedback_frm', 'enctype' => 'multipart/form-data');
                    echo form_open_multipart('', $form_attr);
                    ?>
                    <input type="hidden" name="survey_id" value="<?php echo $survey['survey_id'] ?>">
                    <div class="box-body">
                        <div class="form-group col-sm-10">
                            <label for="feedback_cont" id="page_title">Title*</label>
                            <input type="text" class="form-control" name="title"  value="<?php echo $survey['title'] ?>">
                        </div>
						
                       <div class="form-group col-sm-10">
                            <label for="feedback_cont" id="page_title">Location*</label>
                            <input type="text" class="form-control" id="location" name="location"  value="<?php echo $survey['location'] ?>">
							<input type="hidden" id="location_lat" name="lat" value="<?php echo $survey['lat'] ?>">
							<input type="hidden" id="location_lng" name="lng" value="<?php echo $survey['lng'] ?>">
							<input type="hidden" id="location_address" name="address" value="<?php echo $survey['address'] ?>">
							<?php echo $map['html']; ?>
                        </div>
						
						<?php $i=1; foreach($survey['questions'] as $question):  ?>
						<div class="question-wrapper row" id="question-wrapper-<?=$i; ?>" style="border-bottom:1px solid #CCC; margin:0; margin-bottom:20px;">
							<div class="form-group col-sm-10">
								<label for="feedback_cont" id="page_title">Question <?=$i++; ?></label>
								<input type="text" class="form-control" name="question[<?php echo $question['question_id'] ?>]"  value="<?php echo $question['question'] ?>">
							</div>
							<div class="form-group col-sm-10 survey-type-options">
								<div class="form-check-inline" style="display:inline;">
								  <label class="form-check-label">
									<input type="radio" class="form-check-input question_type" data-question="<?php echo $question['question_id'] ?>" <?php if($question['qtype']=='mcq' ) echo 'checked'; ?> value="mcq" name="qtype[<?php echo $question['question_id'] ?>]">MCQs
								  </label>
								</div>
								<div class="form-check-inline" style="display:inline;">
								  <label class="form-check-label">
									<input type="radio" class="form-check-input question_type" data-question="<?php echo $question['question_id'] ?>" value="single" name="qtype[<?php echo $question['question_id'] ?>]" <?php if($question['qtype']=='single' ) echo 'checked'; ?>>Single Answer
								  </label>
								</div>
								<div class="form-check-inline" style="display:inline;">
								  <label class="form-check-label">
									<input type="radio" class="form-check-input question_type" data-question="<?php echo $question['question_id'] ?>" value="checkbox" name="qtype[<?php echo $question['question_id'] ?>]" <?php if($question['qtype']=='checkbox' ) echo 'checked'; ?>>Checkbox
								  </label>
								</div>	
							</div>
							
							<div class="form-group col-sm-10" id="options-wrapper-<?php echo $question['question_id'] ?>" style="<?php if($question['qtype']=='mcq' ) echo 'display:block'; else echo 'display:none'; ?>">
								<div class="row">
									<div class="form-group col-sm-3"> <input type="text" class="question-option form-control" placeholder="Option 1" name="option[<?=$question['question_id']; ?>][]"  value="<?=$this->emoji->Decode($question['option1']); ?>"></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 2" name="option[<?=$question['question_id']; ?>][]"  value="<?=$question['option2']; ?>"></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 3" name="option[<?=$question['question_id']; ?>][]"  value="<?=$question['option3']; ?>"></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 4" name="option[<?=$question['question_id']; ?>][]"  value="<?=$question['option4']; ?>"></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 5" name="option[<?=$question['question_id']; ?>][]"  value="<?=$question['option5']; ?>"></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 6" name="option[<?=$question['question_id']; ?>][]"  value="<?=$question['option6']; ?>"></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 7" name="option[<?=$question['question_id']; ?>][]"  value="<?=$question['option7']; ?>"></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 8" name="option[<?=$question['question_id']; ?>][]"  value="<?=$question['option8']; ?>"></div>
								</div>
							</div>
							<div class="form-group col-sm-10" id="answer-wrapper-<?php echo $question['question_id'] ?>" style="<?php if($question['qtype']=='single' ) echo 'display:block'; else echo 'display:none'; ?>">
								<div class="row">
									
									<div class="form-group col-sm-6"> <input type="text" class="form-control" placeholder="Single Answer Lable. etc. years / % / days" name="qtype_label[<?php echo $question['question_id'] ?>]"  value="<?php echo $question['answer_label'] ?>"></div>								
								</div>
							</div>
							<div class="form-group col-sm-10" id="checkbox-wrapper-<?php echo $question['question_id'] ?>" style="<?php if($question['qtype']=='checkbox' ) echo 'display:block'; else echo 'display:none'; ?>">
								<div class="row" id="fields-wrapper-<?=$question['question_id']; ?>">
								<?php if(!empty($question['choices']) && is_array($question['choices'])): 
								$ch=0;foreach($question['choices'] as $choice): $ch++;
								?>
								<div class="form-group col-sm-10" id="p<?=$question['question_id'].$ch; ?>"> <input type="text" class="form-control" placeholder="" name="choices[<?php echo $question['question_id'] ?>][<?=$choice['id'] ?>]"  value="<?php echo $choice['option'] ?>"><a href="#" style="float:right;" onclick="return removethis(<?=$question['question_id'].$ch; ?>);" data-question="<?=$question['question_id']; ?>" class="remove_field">Remove</a></div>
									<?php endforeach; else:?>									
									<div class="form-group col-sm-10"> <input type="text" class="form-control" placeholder="" name="choices[<?php echo $question['question_id'] ?>][]"  value=""></div>
									<?php endif;?>									
								</div>								
								<div class="button-wrapper col-sm-10" ><a href="#" data-question="<?=$question['question_id']; ?>" class="add_more_field">Add More</a></div>
							</div>
						</div>
						<?php endforeach; ?>
						<?php $ij=$i; while($i<11):  ?>
						<div class="question-wrapper row" id="question-wrapper-<?=$i; ?>" style="border-bottom:1px solid #CCC; margin:0; margin-bottom:20px; display:none;">
							<div class="form-group col-sm-10">
								<label for="feedback_cont" id="page_title">Question <?=$i; ?></label>
								<input type="text" class="form-control" name="questions[<?=$i; ?>]"  value="">
							</div>
							<div class="form-group col-sm-10 survey-type-options">
									<div class="form-check-inline" style="display:inline;">
									  <label class="form-check-label">
										<input type="radio" class="form-check-input question_type" data-question="<?=$i; ?>" checked value="mcq" name="qtypes[<?=$i; ?>]">MCQs
									  </label>
									</div>
									<div class="form-check-inline" style="display:inline;">
									  <label class="form-check-label">
										<input type="radio" class="form-check-input question_type" data-question="<?=$i; ?>" value="single" name="qtypes[<?=$i; ?>]">Single Answer
									  </label>
									</div>	
									<div class="form-check-inline" style="display:inline;">
									  <label class="form-check-label">
										<input type="radio" class="form-check-input question_type" data-question="<?=$i; ?>" value="checkbox" name="qtypes[<?=$i; ?>]">Checkbox
									  </label>
									</div>	
								</div>
							<div class="form-group col-sm-10" id="options-wrapper-<?=$i; ?>">
								<div class="row">
									<div class="form-group col-sm-3"> <input type="text" class="question-option form-control" placeholder="Option 1" name="options[<?=$i; ?>][]"  value=""></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 2" name="options[<?=$i; ?>][]"  value=""></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 3" name="options[<?=$i; ?>][]"  value=""></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 4" name="options[<?=$i; ?>][]"  value=""></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 5" name="options[<?=$i; ?>][]"  value=""></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 6" name="options[<?=$i; ?>][]"  value=""></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 7" name="options[<?=$i; ?>][]"  value=""></div>
									<div class="form-group col-sm-3"><input type="text" class="question-option form-control" placeholder="Option 8" name="options[<?=$i; ?>][]"  value=""></div>
								</div>
							</div>
							<div class="form-group col-sm-10" id="answer-wrapper-<?=$i; ?>" style="display:none;">
								<div class="row">
									<div class="form-group col-sm-6"> <input type="text" class="form-control" placeholder="Single Answer Lable. etc. years / % / days" name="qtype_labels[<?=$i; ?>]"  value=""></div>								
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
						<?php $i++; endwhile; ?>
						<?php if($ij<11): ?>
						<div class=""><a href="#" data-question="<?=$ij; ?>" class="show-question pull-right btn btn-default btn-rounded mb-4" onclick="return ShowQuestion(<?=$ij; ?>);">Add Another Question</a></div>
						<?php endif; ?>
						
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
<link rel="stylesheet" type="text/css" href="https://feedbacker.me/assets/css/jquery.emojipicker.css">
<link rel="stylesheet" type="text/css" href="https://feedbacker.me/assets/css/jquery.emojipicker.tw.css">
<script src="https://feedbacker.me/assets/js/jquery.emojipicker.js"></script>
<script src="https://feedbacker.me/assets/js/jquery.emojis.js"></script>
<script type="text/javascript">
    //validation for edit email formate form
	function removethis(id){
			var wrapper="#p"+id;
			$(wrapper).remove();
			return false;
	}
	function ShowQuestion(id){
		
		return false;
	}
    $(document).ready(function () {
		$(".show-question").on('click',function(e){
			e.preventDefault();
			var qid=$(this).data('question');
			var wrapper="#question-wrapper-"+qid;
			$(wrapper).show();
			qid++;
			$(this).data('question',qid);
			if(qid>10)
				$(this).hide();
		});
	$(".question_type").change(function(){		
			var question=$(this).data("question");
			console.log(question);
			var options_wrapper='#options-wrapper-'+question;
			var answer_wrapper='#answer-wrapper-'+question;
			var checkbox_wrapper='#checkbox-wrapper-'+question;
			
			if($(this).val()=='mcq'){
				$(options_wrapper).show();
				$(answer_wrapper).hide();
				$(checkbox_wrapper).hide();
			}else if($(this).val()=='checkbox'){
				$(options_wrapper).hide();
				$(answer_wrapper).hide();
				$(checkbox_wrapper).show();
			}else{
				$(options_wrapper).hide();
				$(checkbox_wrapper).hide();
				$(answer_wrapper).show();
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
		$('#feedback_cont').emojiPicker();
        $('.callout-danger').delay(3000).hide('700');
        $('.callout-success').delay(3000).hide('700');
    });
</script>
