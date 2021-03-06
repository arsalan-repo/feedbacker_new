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
					<span class="listing-post-name"><?=$title['title']; ?></span>
					<span class="listing-post-followers"><?=$title['linked_users_count']; ?> <?php echo $this->lang->line('followers'); ?>		</span>
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
						<span class="follow-btn-default unfollow-btn follow-btn-2425"><?php echo $this->lang->line('leave'); ?>/<?php echo $this->lang->line('unfollow'); ?></span>
						<span title="Block owner of the title" class="follow-btn-default block-user follow-btn-<?=$title['title_id']; ?>"><?php echo $this->lang->line('block'); ?></span>
						<span title="Block user and leave this title" class="follow-btn-default block-user-and-leave follow-btn-<?=$title['title_id']; ?>"><?php echo $this->lang->line('block_and_leave'); ?></span>
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
				
				 	<?php if (!empty($reacted_users)) { ?>
					<div class="sidebar-widget">
					<h3><span><?php echo $this->lang->line('read_by'); ?> </span>  </h3>
					<?php foreach($reacted_users as $row) { ?>
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
							</span> <?php	echo $this->common->getCountries($row['country']); ?> - <small><?php echo $row['time']; ?></small>
						</div>		
						
					</div>
					<?php } ?>
					</div>
				<?php } ?>
				
			<?php } else{ 
				echo $no_record_found; 
				 } ?>				
			</div>
		</div>
		<div class="post-detail-rgt">
			 <?php if($is_title_owner): ?>
			 <div class="sidebar-widget" style="text-align:center; padding:20px">				
				<a class="post-btn" href="<?=site_url('survey-questions/'.$title['slug']); ?>" style="float:none; display:block; clear:both; margin:10px auto 20px auto;"><?php echo $this->lang->line('manage_questions'); ?></a>
				<a class="post-btn" href="<?=site_url('survey/results/'.$title['slug']); ?>" style="float:none; display:block; clear:both; margin:10px auto 20px auto;"><?php echo $this->lang->line('view_results'); ?></a>
			</div>
			 <?php endif; ?>
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
					
				
		</div>      
     
    </div>
	<div id="confirm" class="modal hide fade" title="<?php echo $this->lang->line('confirm_delete'); ?>">
	 <form id="remove-user-form" action="<?=site_url('encrypted/remove_user'); ?>" method="post" >
	 <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		  This will permanently remove this user from this title. Are you sure?</p>
	 <input type="hidden" id="user_id" name="user_id" value="">
	 <input type="hidden" id="title_id" name="title_id" value="<?=$title['title_id']; ?>">	
	 <input type="hidden" name="slug" value="<?=$title['slug']; ?>">	 
	 </form> 	  
	</div>
	<div id="confirm-share" class="modal hide fade" title="Confirm Share?">
		 <form id="feedback-share-form" action="<?=site_url('encrypted/share_feedback'); ?>" method="post" >
		 <p>Select the title(s) from the list, on which you want to share this feedback?</p>
		 <div class="title-list" style="max-height:240px; overflow-y: auto;">
			<ul style="width:100%; position:relative;">
				<?php foreach($user_titles as $ut): if($ut['title_id']!=$title['title_id']): ?>
				<li style="display:block; width:100%; float:left;">
				 <input type="checkbox" name="titles[]" value="<?=$ut['title_id']; ?>" class="css-checkbox" style="width:auto; float:left; margin:5px; ">
                 <label class="css-label radGroup2" style="width:auto;"><?=$ut['title']; ?></label>
				</li>
				<?php endif; endforeach; ?>
			</ul>			
		 </div>
		 <input type="hidden" id="feedback_id" name="feedback_id" value="">
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
	<div id="block-confirm" title="Are you sure you want to block this user?">
		<form id="block-user-form" action="<?=site_url('encrypted/block'); ?>" method="post" >
		  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		  This will permanently block this user to link you to the encrypted titles. Are you sure?</p>
		  <input type="hidden" id="user_id" name="user_id" value="<?=$title['user_id']; ?>">
		  <input type="hidden" id="blocked_by" name="blocked_by" value="<?=$this->user['id']; ?>">
		 <input type="hidden" id="title_id" name="title_id" value="<?=$title['title_id']; ?>">
		 <input type="hidden" id="title_slug" name="slug" value="<?=$title['slug']; ?>">
		 <input type="hidden" id="block_and_leave" name="block_and_leave" value="0">
		  <input type="hidden" id="referral_url" name="referral_url" value="<?=current_url(); ?>">
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
		$(".block-user").click(function(){
			 block_dialog.dialog( "open" ); 
		  });
		  $(".block-user-and-leave").click(function(){
			  block_dialog.find( "#block_and_leave" ).val(1);
			 block_dialog.dialog( "open" ); 
		  });
		var block_dialog=$( "#block-confirm" ).dialog({
		  resizable: false,
		  autoOpen: false,
		  height: "auto",
		  width: 400,
		  modal: true,
		  buttons: {
			"Confirm": function() {
				$("#block-user-form").submit();
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
	$("#feedback_img").change(function(){
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
		$("#preview-wrapper").html('');
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
				$("#preview-wrapper").append(img);
				
		  });
}
</script>

