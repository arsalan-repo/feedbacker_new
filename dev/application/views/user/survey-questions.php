<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Content Wrapper. Contains page content -->
<?php if ($this->session->flashdata('error')) { ?>  
	<div class="div-toastr-error">
		<?php echo $this->session->flashdata('error'); ?>
	</div>
<?php } ?>
<?php if ($this->session->flashdata('success')) { ?>  
	<div class="div-toastr-success">
		<?php echo $this->session->flashdata('success'); ?>
	</div>
<?php } ?>
<div class="content-secion">
    <div class="container">
		<div class="post-detail-left">
			
			<div class="profile-listing">
			<?php if(!empty($title)){ ?>   
				<div class="listing-post-name-block">
					<span class="listing-post-name"><a href="<?=site_url('encrypted/'.$title['title_id']); ?>"><?=$title['title']; ?></a></span>
					<span class="listing-post-followers"><?=$title['linked_users_count']; ?> <?php echo $this->lang->line('followers'); ?>	</span>
				</div>
				<div class="profile-listing-img-thumb-block">
					<div class="profile-listing-img-thumb">
						<?php 
						if(isset($title['photo'])) {
							echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $title['photo'].'" alt="" />';
						} else {
							echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
						}
						?>
						</div>
					<span class="listing-post-profile-name"><?=$title['name']; ?></span> <span class="listing-post-profile-time"><?=$title['time']; ?></span> 
				</div>
				<div class="post-listing-follow-btn">					
					<?php if($title['is_title_linked_user']): ?>
						<span class="follow-btn-default unfollow-btn follow-btn-2425">Leave/Unfollow</span>
					<?php endif; ?>					
					<!--<span class="wishlist" id="post-wishlist-<?php echo $title['title_id']; ?>">
						<?php if ($title['is_liked']) { ?>
							<i class="fa fa-heart" aria-hidden="true" title="<?php echo $this->lang->line('unlike'); ?>"></i> 
						<?php } else { ?>
							<i class="fa fa-heart-o" aria-hidden="true" title="<?php echo $this->lang->line('like'); ?>"></i>
						<?php } ?> 
						<?php echo $title['likes']; ?>
					</span>-->
				 </div>
				 <div class="question-list">
					<h2><?php echo $this->lang->line('questions'); ?></h2>
				 <?php $question_count=1; foreach($questions as $question):  ?>
					<div class="question-item">
						<h4><?=$question_count; ?>.  <?=$question['question']; ?>
						<a class="pull-right red delete_question" title="Delete This Question" style="color:red;" data-question="<?=$question['question_id']; ?>" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
						</h4>
						<?php if(count($question['images'])>0): ?>						
							<?php  if(count($question['images'])>1) { ?>
								<div class="flexslider">
									  <ul class="slides">
									  <?php foreach($question['images'] as $img): ?>
									  <li>
										  <img src="<?=$img['photo']; ?>" />
										</li>
									  <?php endforeach; ?>			
									  </ul>
								</div>
							<?php } else { ?>
								<div class="post-reply-img">
									<img src="<?php echo $question['images'][0]['photo']; ?>" alt="" />	
								</div>
							<?php } ?>	
							
						<?php endif; ?>
						<div class="clearfix" style="clear:both;"></div>
						<div class="question-options">
							<?php if(!empty($question['first_p'])): ?>
								<span class="question-option <?php if(1==$question['correct']) echo 'correct'; ?>"  ><input type="radio" name="answer[<?=$question['question_id']; ?>]" value="<?=$question['first_p']; ?>"><?=$question['first_p']; ?></span>
							<?php endif; ?>
							<?php if(!empty($question['second_p'])): ?>
								<span class="question-option <?php if(2==$question['correct']) echo 'correct'; ?>"><input type="radio" name="answer[<?=$question['question_id']; ?>]" value="<?=$question['second_p']; ?>"><?=$question['second_p']; ?></span>
							<?php endif; ?>
							<?php if(!empty($question['third_p'])): ?>
								<span class="question-option <?php if(3==$question['correct']) echo 'correct'; ?>" ><input type="radio" name="answer[<?=$question['question_id']; ?>]" value="<?=$question['third_p']; ?>"><?=$question['third_p']; ?></span>
							<?php endif; ?>
							<?php if(!empty($question['fourth_p'])): ?>
								<span class="question-option <?php if(4==$question['correct']) echo 'correct'; ?>" ><input type="radio" name="answer[<?=$question['question_id']; ?>]" value="<?=$question['fourth_p']; ?>"><?=$question['fourth_p']; ?></span>
							<?php endif; ?>
						</div>
						
					</div>
				 <?php $question_count++; endforeach; ?>
				 </div>
				 <?php if($question_count<101): ?>
				 <div class="post-detail-question-form" style="float:left; width:100%; margin-top:20px; margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #eaeef1;">
				  <h3><?php echo $this->lang->line('add_questions'); ?></h3>
				 <?php
					$attributes = array('id' => 'reply-post-form', 'enctype' => 'multipart/form-data');
					echo form_open_multipart(current_url(), $attributes);
					?>
					<div id="questions-container">
						<div class="question-wrapper">
							<h4><?php echo $this->lang->line('question'); ?> <span class="question_number"><?=$question_count; ?></span></h4>
							<label><?php echo $this->lang->line('question'); ?></label>
							<textarea name="question[<?=$question_count; ?>]" placeholder="<?php echo $this->lang->line('write_question_here'); ?>" rows="4"></textarea>
							<div class="" style="width:100%; float:left; margin-top:20px;">						
								<div class="" style="width:50%; float:left; padding-right:15px;">
									<label><?php echo $this->lang->line('option'); ?> 1</label>
									<input type="text" name="first_p[<?=$question_count; ?>]">
									<label><?php echo $this->lang->line('option'); ?> 2</label>
									<input type="text" name="second_p[<?=$question_count; ?>]">
								</div>
								<div class="" style="width:50%; float:left; padding-right:15px;">
									<label><?php echo $this->lang->line('option'); ?> 3</label>
									<input type="text" name="third_p[<?=$question_count; ?>]">
									<label><?php echo $this->lang->line('option'); ?> 4</label>
									<input type="text" name="fourth_p[<?=$question_count; ?>]">
								</div>
							</div>							
							<label><?php echo $this->lang->line('correct_option'); ?></label>
							<select name="correct[<?=$question_count; ?>]" class="input-select">
								<option value=""><?php echo $this->lang->line('not_applicable'); ?></option>
								<option value="1"><?php echo $this->lang->line('option'); ?> 1</option>
								<option value="2"><?php echo $this->lang->line('option'); ?> 2</option>
								<option value="3"><?php echo $this->lang->line('option'); ?> 3</option>
								<option value="4"><?php echo $this->lang->line('option'); ?> 4</option>
							</select>
							
							<div class="post-btn-block" style="clear:both;">
								<div class="camera-map-icon">
									<div class="camera-icon-block">
										<span>Choose File</span>
										<input data-wrapper="preview-wrapper-<?=$question_count; ?>" class="question_images" name="question_<?=$question_count; ?>_images[]" multiple type="file" />
									</div>	
								</div>			
							</div>
							<div class="preview-wrapper" id="preview-wrapper-<?=$question_count; ?>" style="display:inline-block;"></div>
						</div>
					</div>
					<?php if($question_count<100): ?>
					<div class="post-btn-block" style="clear:both;">
						<a href="#" id="question-add-more" class="btn"><i class="fa fa-plus-square" aria-hidden="true" ></i> <?php echo $this->lang->line('add_another_question'); ?></a>	
					</div>
					<?php endif; ?>
					<div class="post-btn-block">
						<input type="hidden" name="title_id" value="<?=$title['title_id']; ?>">					
						<button type="submit" id="save-questions" name="save-questions" class="post-btn"> <?php echo $this->lang->line('save_questions'); ?></button>
					</div>
					</form>
				</div>
				<?php endif; ?>
			<?php } else{ 
				echo $no_record_found; 
				 } ?>				
			</div>
		</div>
		<div class="post-detail-rgt">
			 <!--<div class="home-left-profile">
			  <div class="home-left-section" style="width:100%;">
				<div class="home-left-profile-block"> 
					<span class="home-left-profile-thumb">
						<a href="<?php echo site_url('user/profile'); ?>">
						<?php 
						if(isset($user_info['photo'])) {
							echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $user_info['photo'].'" alt="" />';
						} else {
							echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
						}
						?>
						</a>
					</span> 
					<span class="home-left-profile-name"><?php echo $user_info['name']; ?></span> 
					<span class="home-left-profile-designation">
					<?php
					$getcountry = $this->common->select_data_by_id('users', 'id', $user_info['id'], 'country', '');
					echo $this->common->getCountries($getcountry[0]['country']); ?></span> </div>
			  </div>
			</div>-->
			<?php if($is_title_owner): ?>
			<div class="sidebar-widget">
				<h3><span><?php echo $this->lang->line('link_new_user'); ?></span></h3>
				<div class="who-follow-block">					
					<?php $attributes = array('id' => 'link-user-form');
					echo form_open('encrypted/link_users', $attributes);		?>
					<div class="user-list" style="position:relative; margin-bottom:20px;">
					<label><?php echo $this->lang->line('link_more_users'); ?></label>
					<select class="js-data-example-ajax form-control" name="users[]" required multiple="true" style="width:100%;" placeholder="<?php echo $this->lang->line('enter_user_name_or_email'); ?>"></select>
					</div>
					<input type="hidden" name="title_id" value="<?=$title['title_id']; ?>">
					<input type="hidden" name="slug" value="<?=$title['slug']; ?>">
					<div class="form-group">
						<button type="submit" class="post-btn" name="linkuser" style="border:none; outline:none;"><?php echo $this->lang->line('link_now'); ?></button>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<div class="sidebar-widget">
				<h3><span><?php echo $this->lang->line('invite_your_friends'); ?></span></h3>
				<div class="who-follow-block">
					<?php
					$attributes = array('id' => 'invite-friends-form', 'enctype' => 'multipart/form-data');
					echo form_open_multipart('encrypted/send_invitaion', $attributes);
					?>
					<div class="form-group">
						<h4><?php echo $this->lang->line('email_address'); ?></h4>
						<input type="text" id="invite_email" name="invite_email" class="form-control" required value="">
					</div>			
					
					<div class="form-group" style="width:100%; float:left; margin-top:20px; ">
						<input type="hidden" name="title_id" value="<?=$title['title_id']; ?>">
						<input type="hidden" name="slug" value="<?=$title['slug']; ?>">
						<span class="post-btn" id="send_invitation-btn"><?php echo $this->lang->line('send_invitation'); ?></span>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
			<?php endif; ?>
				
			<?php if (!empty($linked_users)) { ?>
				<div class="sidebar-widget">
				<h3><span><?php echo $this->lang->line('linked_users'); ?></span></h3>
				<?php foreach($linked_users as $row) { ?>
				<div class="who-follow-block" id="link-user-row-<?=$row['id']; ?>">
					<span>
						<?php
						if (isset($row['photo'])) {
							echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $row['photo'].'" alt="" />';
						} else {
							echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
						}
						?>
					</span>
					<div class="who-follow-text">
						<span><?php echo $row['name']; ?> 
						<?php if($title['is_title_owner']): ?>
						<a class="pull-right red remove_linked_user" title="Remove This User" style="color:red;" data-user="<?=$row['id']; ?>" data-title="<?=$title['title_id']; ?>" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
						<?php endif; ?>
						</span> <?php	echo $this->common->getCountries($row['country']); ?> - <small><?php echo $row['time']; ?></small>
					</div>
					
					<!--<div class="who-follow-add" id="who-follow-<?php echo $row['id']; ?>"> 
						Remove <i class="fa fa-ban" aria-hidden="true"></i>
					</div>-->
					
					
					
				</div>
				<?php } ?>
				</div>
			<?php } ?>			
		</div>      
     
    </div>
	<div id="confirm-question-delete" class="modal hide fade" title="Confirm Delete?">
	 <form id="question-delete-form" action="<?=site_url('survey/delete_question'); ?>" method="post" >
	 <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		  This will permanently delete this question from this survey. Are you sure?</p>
	 <input type="hidden" id="user_id" name="user_id" value="">
	 <input type="hidden" id="question_id" name="question_id" value="">
	 <input type="hidden" id="title_id" name="title_id" value="<?=$title['title_id']; ?>">	
	 <input type="hidden" name="slug" value="<?=$title['slug']; ?>">	 
	 </form> 	  
	</div>
	<div id="confirm" class="modal hide fade" title="Confirm Delete?">
	 <form id="remove-user-form" action="<?=site_url('encrypted/remove_user'); ?>" method="post" >
	 <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		  This will permanently remove this user from this title. Are you sure?</p>
	 <input type="hidden" id="user_id" name="user_id" value="">
	 <input type="hidden" id="title_id" name="title_id" value="<?=$title['title_id']; ?>">	
	 <input type="hidden" name="slug" value="<?=$title['slug']; ?>">	 
	 </form> 	  
	</div>
	
	<div id="unfollow-confirm" title="Are you sure you want to unfollw this title?">
		<form id="unfollow-title-form" action="<?=site_url('encrypted/leave_title'); ?>" method="post" >
		  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		<?php echo $this->lang->line('confirm_remove_title_msg'); ?></p>
		  <input type="hidden" id="user_id" name="user_id" value="<?=$this->user['id']; ?>">
		 <input type="hidden" id="title_id" name="title_id" value="<?=$title['title_id']; ?>">
		 <input type="hidden" id="title_slug" name="slug" value="<?=$title['slug']; ?>">	
		</form> 
	</div>	 
    <div class="ajax-load text-center" style="display:none">
        <p><img src="<?php echo ASSETS_URL . 'images/loader.gif'; ?>">Loading</p>
    </div>
    <script>
	  $( function() {
		  $('#invite_email').multiple_emails({checkDupEmail: true,
			theme: "fontawesome",
			position: "top"
			});

		  $(".unfollow-btn").click(function(){
			 confirm_dialog.dialog( "open" ); 
		  });
		var confirm_dialog=$( "#unfollow-confirm" ).dialog({
		  resizable: false,
		  autoOpen: false,
		  height: "auto",
		  width: 400,
		  modal: true,
		  buttons: {
			"Confirm": function() {
				$("#unfollow-title-form").submit();
			  $( this ).dialog( "close" );
			},
			Cancel: function() {
			  $( this ).dialog( "close" );
			}
		  }
		});
	  } );
	  </script>
    <script type="text/javascript">
		// When the browser is ready...
		$(function() {
			// jQuery Toastr
			if ($.trim($(".div-toastr-error").html()).length > 0) {
				$(".div-toastr-error p").each(function( index ) {
					toastr.error($(this).html(), 'Failure Alert', {timeOut: 5000});
				});
			}
			
			if ($.trim($(".div-toastr-success").html()).length > 0) {
				$(".div-toastr-success p").each(function( index ) {
					toastr.success($(this).html(), 'Success Alert', {timeOut: 5000});
				});
			}
		});
	
        var page = 1;
       /* $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        });*/
    
        function loadMoreData(page){
          $.ajax(
                {
                    url: '?page=' + page,
                    type: "get",
                    beforeSend: function()
                    {
                        $('.ajax-load').show();
                    }
                })
                .done(function(data)
                {
                    if(data == " "){
                        $('.ajax-load').html("No more records found");
                        return;
                    }
                    $('.ajax-load').hide();
                    $("#post-data").append(data);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError)
                {
                      alert('server not responding...');
                });
        }
		
	
    </script>
</div>
<!-- /.content-wrapper -->

<script type="application/javascript">

var current_url="<?=site_url('encrypted/title/'.$title['title_id']);?>";
 $("#dashboard_url").val(current_url);
var base_url="<?=base_url();?>";
var title_id="<?=$title['title_id'];?>";
var URL=base_url+"user/search_users";
//console.log(base_url+"user/search_users");
function userlist (state) {
	if(state.id==state.text)
		return;
	if(!state.photo){
		var photo_url="https://feedbacker.me/dev/assets/images/user-avatar.png";
	}else{
		var photo_url="https://d1f8jwm5uy46l.cloudfront.net/uploads/user/thumbs/"+state.photo;
	}
	var $item = $(
		'<div class="select2-list-item-with-image user-'+state.id+'" style="clear:both; float:none; line-height:32px;"><img style="width:32px; float:left; margin-right:8px;" src="'+photo_url+'" class="img-flag" /> ' + state.text + '</div>'
		);
	return $item;
};

$(function() {
	var sharedialog, shareform;
	sharedialog = $("#confirm-share").dialog({
			autoOpen: false,
			modal: true,
			width: 400,
			resizable: false,
			 buttons: {
			"Confirm": function() {
				//$("#feedback-share-form").submit();
				$.ajax({
					dataType: 'json',
					type:'POST',
					url: shareform[0].action,
					data: new FormData(shareform[0]),
					processData: false,
					contentType: false
				}).done(function(data){
					 console.log(data);
					if (data.success) {
						toastr.success('Feedback has been shared to the selected titles.', 'Success Alert', {timeOut: 5000});
					}				
					shareform[0].reset();	
				}).complete(function(data){
					// console.log(data);					
				});
			  $( this ).dialog( "close" );
			},
			Cancel: function() {
			  $( this ).dialog( "close" );
			}
		  }
	});
	shareform = sharedialog.find( "form" );
	$('.share-feedback').off("click").on("click",function(e){
			e.preventDefault();
			var fid=$(this).data("feedback");
			sharedialog.find( "#feedback_id" ).val(fid);
			sharedialog.dialog( "open" );
			
	});
	var dialog, form;
	dialog = $("#confirm").dialog({
			autoOpen: false,
			modal: true,
			resizable: false,
			 buttons: {
			"Confirm": function() {
				$("#remove-user-form").submit();
			  $( this ).dialog( "close" );
			},
			Cancel: function() {
			  $( this ).dialog( "close" );
			}
		  }
	});
	var dqdialog, dqform;
	dqdialog = $("#confirm-question-delete").dialog({
			autoOpen: false,
			modal: true,
			resizable: false,
			 buttons: {
			"Confirm": function() {
				$("#question-delete-form").submit();
			  $( this ).dialog( "close" );
			},
			Cancel: function() {
			  $( this ).dialog( "close" );
			}
		  }
	});
	$('.delete_question').off("click").on("click",function(e){
			e.preventDefault();
			var question_id=$(this).data("question");
			dqdialog.find( "#question_id" ).val(question_id);
			dqdialog.dialog( "open" );
			
	});
	$('.remove_linked_user').off("click").on("click",function(e){
			e.preventDefault();
			var uid=$(this).data("user");
			dialog.find( "#user_id" ).val(uid);
			dialog.dialog( "open" );
			
	});
	
	$('.js-data-example-ajax').select2({
		templateResult: userlist,
		minimumInputLength: 2,
		placeholder: '<?php echo $this->lang->line('enter_user_name_or_email_address'); ?>',
		tags: [],
		ajax: {
			url: URL,
			dataType: 'json',
			type: "POST",
			async: false,
			quietMillis: 50,
			data: function (term) {
				return {
					s: term,
					title_id:title_id
				};
			},
			processResults: function (data) {
				//console.log(data);				
			  return {
                  results: $.map(data.users, function(index, val) {					 
                      return {
                          id: index.id,
                          text: index.text,
						  photo:index.photo
                      }
                  })
              };
			},
			results: function (data) {
				//console.log(data);
			},
		}
	});	
	$("#post-comment").click(function() {
		$("#reply-post-form").submit();
	});
	$(".question_images").change(function(){
		imagePreview(this);
	});
	$("#feedback_files").change(function(){
		pdfPreview(this);
	});
	$("#reply-post-form").validate({
		rules: {
			feedback_cont: {
				required: true
			}
		},
		messages: {
			feedback_cont: "Please enter a feedback"
		},
		
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	$("#send_invitation-btn").click(function() {
	if($('#invite_email').val()=="" || $('#invite-friends-form .multiple_emails-input').hasClass('multiple_emails-error')){
			 $('#invite-friends-form .multiple_emails-input').addClass('multiple_emails-error');
		}else{
			$("#invite-friends-form").submit();
		}
	});
	
	
	
	$('.flexslider').flexslider({
			animation: "slide"
		  });
});
function pdfPreview(input){
	var files = input.files; 
		$("#pdf-preview-wrapper").html('');
		$("#visibility-wrapper").show();
		$.each(input.files, function(i, file) {
			var span = document.createElement("div");
			span.innerHTML=file.name;
			$("#pdf-preview-wrapper").append(span);
			/*var img = document.createElement("img");
			img.id = "pdf"+(i+1);
			img.style.cssText="width:200px; height:200px; margin:5px;";
			img.src = 'https://feedbacker.me/assets/images/pdf-large-icon.png';			
			console.log(file);		
			$("#pdf-preview-wrapper").append(img);*/
		});
}
function imagePreview(input) {
	var files = input.files; 		
	/*$wrapper=input.dataset.wrapper;*/
		$("#"+input.dataset.wrapper).html('');
		 $.each(input.files, function(i, file) {
				
				var img = document.createElement("img");
				img.id = "image"+(i+1);
				img.style.cssText="width:200px; height:200px; margin:5px;";
				var reader = new FileReader();
				reader.onloadend = function () {
					img.src = reader.result;
				}
				reader.readAsDataURL(file);
				$("#image"+i).after(img);
				$("#"+input.dataset.wrapper).append(img);
				
		  });
}
var question_count = <?php echo $question_count; ?>;
$(document).ready(function(){
    
	var next = question_count+1;
	
    $("#question-add-more").click(function(e){
        e.preventDefault();
		var questionhtml='<div class="question-wrapper"><h4><?php echo $this->lang->line("question"); ?> <span class="question_number">'+next+'</span></h4><label><?php echo $this->lang->line("question"); ?></label><textarea name="question['+next+']" placeholder="<?php echo $this->lang->line("write_question_here"); ?>" rows="4"></textarea><div class="pull-left" style="width:100%; margin-top:20px;">	<div class="" style="width:50%; float:left; padding-right:15px;"><label><?php echo $this->lang->line("option"); ?> 1</label><input type="text" name="first_p['+next+']"><label><?php echo $this->lang->line("option"); ?> 2</label><input type="text" name="second_p['+next+']"></div><div class="pull-left" style="width:50%; padding-right:15px;"><label><?php echo $this->lang->line("option"); ?> 3</label><input type="text" name="third_p['+next+']"><label><?php echo $this->lang->line("option"); ?> 4</label><input type="text" name="fourth_p['+next+']"></div></div><label><?php echo $this->lang->line('correct_option'); ?></label><select name="correct['+next+']" class="input-select"><option value=""><?php echo $this->lang->line('not_applicable'); ?></option><option value="1"><?php echo $this->lang->line('option'); ?> 1</option><option value="2"><?php echo $this->lang->line('option'); ?> 2</option><option value="3"><?php echo $this->lang->line('option'); ?> 3</option><option value="4"><?php echo $this->lang->line('option'); ?> 4</option></select><div class="post-btn-block" style="clear:both;"><div class="camera-map-icon"><div class="camera-icon-block"><span>Choose File</span><input data-wrapper="preview-wrapper-'+next+'" class="question_images" name="question_'+next+'_images[]" multiple type="file" onchange="imagePreview(this)"/></div></div></div><div class="preview-wrapper" id="preview-wrapper-'+next+'" style="display:inline-block;"></div></div>';
		$('#questions-container').append(questionhtml);
		next = next + 1;
		if(next>100)
			$("#question-add-more").hide();
       /* var addRemove = "#field" + (next);
        next = next + 1;
        var newIn = '<input autocomplete="off" class="input form-control" id="field' + next + '" name="field' + next + '" type="text">';
        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button></div><div id="field">';
        var removeButton = $(removeBtn);
        $(addto).after(newInput);
        $(addRemove).after(removeButton);
        $("#field" + next).attr('data-source',$(addto).attr('data-source'));
        $("#count").val(next);  
        $('.remove-me').click(function(e){
                e.preventDefault();
                var fieldNum = this.id.charAt(this.id.length-1);
                var fieldID = "#field" + fieldNum;
                $(this).remove();
                $(fieldID).remove();
        });*/
    });   
});
</script>

