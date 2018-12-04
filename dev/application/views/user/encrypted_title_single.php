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
					<span class="listing-post-followers"><?=$title['linked_users_count']; ?> <?php echo $this->lang->line('followers'); ?>	</span>
				</div>
				<div class="profile-listing-img-thumb-block">
					<div class="profile-listing-img-thumb">
						<?php 
						
						if(isset($title['photo'])) {
							echo '<img src="'.$title['photo'].'" alt="" />';
						} else {
							echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
						}
						?>
						</div>
					<span class="listing-post-profile-name"><?=$title['name']; ?></span> <span class="listing-post-profile-time"><?=$title['time']; ?></span> 
				</div>
				<div class="post-listing-follow-btn">					
					<?php if($title['is_title_linked_user']): ?>
						<span class="follow-btn-default unfollow-btn follow-btn-<?=$title['title_id']; ?>"><?php echo $this->lang->line('leave'); ?>/<?php echo $this->lang->line('unfollow'); ?></span>
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
				 <div class="post-detail-comment-form" style="float:left; width:100%; margin-top:20px; margin-bottom:20px; padding-bottom:20px; border-bottom:1px solid #eaeef1;">
				  <h2><?php echo $this->lang->line('write_your_feedback'); ?></h2>
				 <?php
				 $form_action=isset($form_action)? $form_action: 'encrypted/reply';
					$attributes = array('id' => 'reply-post-form', 'enctype' => 'multipart/form-data');
					echo form_open_multipart($form_action, $attributes);
					?>
					<label><?php echo $this->lang->line('comment'); ?></label>
					<textarea name="feedback_cont" id="feedback_cont" placeholder="<?php echo $this->lang->line('write_comment_here'); ?>" rows="10"></textarea>
					
					<div class="" id="visibility-wrapper" style="margin-bottom:10px; float:left; width:100%;">
						<label style="padding-bottom:10px;"><?php echo $this->lang->line('visibility'); ?></label>
						<span style="display:inline-block; font-size:14px; color:#8a8a98;"><input type="radio" name="privacy" style="width:auto; margin:4px;" value="1"><?php echo $this->lang->line('admin_only'); ?></span>
						<span style="display:inline-block; font-size:14px; color:#8a8a98;"><input type="radio" name="privacy" style="width:auto; margin:4px;" value="0" checked ><?php echo $this->lang->line('all_linked_user'); ?></span>
					</div>
					
					<div class="post-btn-block">
						<div class="camera-map-icon">
							<!--<div class="camera-icon-block">
								<span>Choose File</span>
								<input name="feedback_img[]" id="feedback_img" multiple type="file" />
							</div>-->
							<!--<div class="pdf-icon-block">
								<span>Choose File</span>
								<input name="feedback_pdf" id="feedback_pdf" accept="application/pdf" type="file" />
							</div>-->
							
							<div class="doc-icon-block">
								<span>Choose files to upload</span>
								<input name="feedback_files[]" id="feedback_files" multiple type="file" />
							</div>
							
						</div>
						<span id="post-comment" class="post-btn"><?php echo $this->lang->line('post'); ?></span>
					</div>
					
					<input type="hidden" name="id" id="id" value="<?=$title['title_id'];?>">
					<input type="hidden" name="slug" id="slug" value="<?=$title['slug'];?>">
					<input type="hidden" name="latitude" id="latitude" value="">
					<input type="hidden" name="longitude" id="longitude" value="">
					<?php echo form_close(); ?>
					<img id="preview" src="" alt="" height="200" width="200">
					<div id="preview-wrapper"></div>
					<div id="pdf-preview-wrapper"></div>
					
				</div>
				<div class="search-form post-profile-block">
					<form role="form" method="post" action="">
						<h3><?php echo $this->lang->line('search_feedbacks'); ?></h3>
						<div class="form-group">
							<input type="text" placeholder="<?php echo $this->lang->line('type_in_to_search'); ?>" name="s" id="s" required="true" class="ui-autocomplete-input" autocomplete="off" aria-required="true" value="<?=$s; ?>">
							<input type="submit" value="<?php echo $this->lang->line('search'); ?>" name="search" style="width:160px; float:right;">		
						</div>
					</form>
				</div>
				 <?php //print_r($feedbacks); ?>
				 <?php if(count($feedbacks)>0): ?>
				 <h3><?php echo $this->lang->line('comments'); ?></h3>
					<?php foreach($feedbacks as $row):  ?>	
					<?php if(!empty($row['ads'])):  ?>
					<div class="post-profile-block">
					   <div class="post-right-arrow">
						   <span class="post-profile-time-text">Promoted</span>
						  </div>
						<div class="post-img">
							<a href="" target="_blank"><img src="<?=$row['user_avatar']; ?>" alt=""> </a>
						</div>
						<div class="post-profile-content"> 
							<span class="post-designation">
								<a href="<?=$row['ads_url']; ?>"></a>
							</span> 
							<span class="post-name"><?=$row['name']; ?></span> 
							<span class="post-address"></span>
							<p><span class="more"><?=$row['feedback']; ?></span></p>
										<div class="post-large-img">
													<a href="<?=$row['ads_url']; ?>" target="_blank">
											<img src="<?=$row['feedback_img']; ?>" alt="" >
										</a>
											</div>
										</div>
					  </div>
					<?php else: ?>
						<?php if($row['privacy']!=1 || $is_title_owner || $row['is_feedback_owner']): ?>
						 <div class="profile-listing-img-thumb-block">
							<div class="profile-listing-img-thumb">
								<?php
								if(isset($row['user_avatar'])) {
									echo '<img src="'.$row['user_avatar'].'" alt="" />';
								} else {
									echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
								}
								?>
							</div>
							<span class="listing-post-profile-name">
							<?php echo $row['name']; ?>
							<?php if($row['is_feedback_owner'] || $is_title_owner): ?>
							<span class="listing-post-profile-time">
							<a class="pull-right red delete_feedback" title="Delete Feedback" style="color:red;" data-feedback="<?php echo $row['feedback_id']; ?>" data-title="<?php echo $row['title_id']; ?>" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
							</span>
							<?php endif; ?>
							</span> 
							 <span style="font-size:12px;" class="post-address"><?php echo $this->common->getCountries($row['country']); ?>
							 <span class="listing-post-profile-time"><?php echo $row['time']; ?></span>
							 </span>
							 <div class="action-bar">
							 <?php/* if($row['is_feedback_owner']):*/ ?>
								<a title="Share to other title" class="pull-right share-feedback" href="#" data-feedback="<?php echo $row['feedback_id']; ?>">
									<!--<i class="fa fa-share-square" aria-hidden="true" title="Share to other Encrypted Title"></i>-->
									<img src="<?=base_url(); ?>assets/images/upload.png">
								</a>
								
								<?php /*endif; */?>
							</div>
							  							 
						</div>
						<div class="comment-description">
							<p> <?php echo str_replace("\\n","<br>",str_replace("\\r\\n","<br>",$this->emoji->Decode(nl2br($row['feedback_cont'])))); ?></p>
							<?php 
							
						  if(count($row['images'])>1){
							  ?>
							  <div class="flexslider">
								  <ul class="slides">
								  <?php foreach($row['images'] as $img): ?>
								  <li>
									  <img src="<?=$img['photo']; ?>" />
									</li>
								  <?php endforeach; ?>			
								  </ul>
								</div>
							  <?php
						  }else if(count($row['images'])>0){ ?>
							<div class="post-reply-img">
								<img src="<?php echo $row['images'][0]['photo']; ?>" alt="" />	
							</div>
						  <?php } else if(!empty($row['feedback_img'])) { ?>
							<div class="post-reply-img">
								<img src="<?php echo $row['feedback_img']; ?>" alt="" />	
							</div>
						  <?php } ?>
						  <?php
							if (!empty($row['feedback_pdf'])) {
								$pdf_name=empty($row['feedback_pdf_name'])? 'View attached PDF document.': $row['feedback_pdf_name'];
								echo '<div class="post-reply-pdf"><a href="'.$row['feedback_pdf'].'"><img src="'.ASSETS_URL . 'images/pdf-icon.png" alt="" />'.$pdf_name.' </a></div>';
							}
							?>
							<?php if(count($row['files'])>0): ?>
							<?php foreach($row['files'] as $file):  ?>
							<?php 	echo '<div class="post-reply-pdf"><a href="'.$file['url'].'"><img style="width:16px; margin-right:4px;" src="'.ASSETS_URL . 'images/file-icon.png" alt="" />'.$file['name'].' </a></div>'; ?>
							<?php endforeach; ?>
							<?php endif; ?>
							
						</div>
						<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
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
			<div class="sidebar-widget">
				<h3><span>Invite Group </span>  <span class="pull-right" style="display:block;" id="create-user-group"><?php echo $this->lang->line('add_new_list'); ?></span></h3>
				<div class="who-follow-block">
					<?php
					$attributes = array('id' => 'link-group-form', 'enctype' => 'multipart/form-data');
					echo form_open_multipart('encrypted/link_group', $attributes);
					?>
					<div class="form-group">
						<h4><?php echo $this->lang->line('select_from_list'); ?></h4>
						<select class="select2-single form-control" name="user_groups" id="user_groups" style="width:100%; padding:10px;" placeholder="Select List">
						 <option value=""><?php echo $this->lang->line('select_group'); ?></option>
						 <?php foreach($groups as $group): ?>
						 <option value="<?=$group['group_id']; ?>"><?=$group['title']; ?>(<?=$group['count']; ?>)</option>
						 <?php endforeach; ?>
						</select>
					</div>			
					<label style="padding-bottom:10px;">  </label>	
					<div class="form-group">
						<input type="hidden" name="title_id" value="<?=$title['title_id']; ?>">
						<input type="hidden" name="slug" value="<?=$title['slug']; ?>">
						<button type="submit" class="post-btn" name="linkuser" style="border:none; outline:none;"><?php echo $this->lang->line('link_now'); ?></button>
					</div>
								
				
					<?php echo form_close(); ?>
				</div>
			</div>	
			<?php if (!empty($reacted_users)) { ?>
				<div class="sidebar-widget">
				<h3><span><?php echo $this->lang->line('read_by'); ?> </span>  <a class="pull-right" href="<?=site_url('encrypted/readby/'.$title['slug']); ?>">View All</a></h3>
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
			<?php endif; ?>
					
			<?php if (!empty($linked_users)) { ?>
				<div class="sidebar-widget">
				<h3><span><?php echo $this->lang->line('linked_users'); ?> </span> <a class="pull-right" href="<?=site_url('encrypted/linked_users/'.$title['slug']); ?>">View All</a></h3>
				<?php foreach($linked_users as $row) { ?>
				<div class="who-follow-block" id="link-user-row-<?=$row['id']; ?>">
					<span>
						<?php
						if (isset($row['photo'])) {
							echo '<img src="'.$row['photo'].'" alt="" />';
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
	<div id="confirm" class="modal hide fade" title="Confirm Delete?">
	 <form id="remove-user-form" action="<?=site_url('encrypted/remove_user'); ?>" method="post" >
	 <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		  This will permanently remove this user from this title. Are you sure?</p>
	 <input type="hidden" id="user_id" name="user_id" value="">
	 <input type="hidden" id="title_id" name="title_id" value="<?=$title['title_id']; ?>">	
	 <input type="hidden" name="slug" value="<?=$title['slug']; ?>">	 
	 </form> 	  
	</div>
	<div id="confirm-delete-feedback" class="modal hide fade" title="Confirm Delete?">
	 <form id="remove-feedback-form" action="<?=site_url('encrypted/delete_feedback'); ?>" method="post" >
	 <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		  This will permanently delete this feedback from this title. Are you sure?</p>
	 <input type="hidden" id="user_id" name="user_id" value="">
	 <input type="hidden" id="title_id" name="title_id" value="<?=$title['title_id']; ?>">	
	 <input type="hidden" id="feedback_id" name="feedback_id" value="">	
	 <input type="hidden" name="slug" value="<?=$title['slug']; ?>">	 
	 </form> 	  
	</div>
	<div id="confirm-share" class="modal hide fade" title="Confirm Share?">		
		 <form id="feedback-share-form" action="<?=site_url('encrypted/share_feedback'); ?>" method="post" >
		 <style>
		 .share-elements{display:none;}
		 .select2-container--default .select2-search--inline .select2-search__field{width:100% !important;}
		 .select2-container--default .select2-selection--multiple .select2-selection__rendered li{width:100%;}
		 </style>
		 <div class="form-group">
			<h4><span><input type="radio" name="target" class="target" value="user" style="width:auto; margin:5px;"></span>Share to Other People</h4>
			<div class="share-elements" id="people-elements">
				<p>Select the user(s) you want to share the documents?</p>
				<div class="" style="width:100%;">						
					<div class="user-list" style="position:relative; margin-bottom:20px; width:100%;">
						<label><?php echo $this->lang->line('email_address'); ?></label>
						<select class="js-data-example2-ajax form-control" name="users[]" required multiple="true" style="width:100%;" placeholder="<?php echo $this->lang->line('enter_email'); ?>"></select>
					</div>
				</div>
			</div>
		 </div>
		 <div class="form-group">
			<h4><span><input type="radio" class="target" name="target" value="title" style="width:auto; margin:5px;"></span>Share to Encrypted Title</h4>
			<div class="share-elements" id="title-elements">
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
			</div>
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
<div id="dialog-add-group" title="<?php echo $this->lang->line('create_new_user_group'); ?>">
  <p class="validateTips"><?php echo $this->lang->line('all_fields_are_required'); ?></p> 
  <form style="position:relative; width:100%;" action="<?=site_url('user/create_user_group'); ?>">   
      <label for="name"><?php echo $this->lang->line('name'); ?></label>
      <input type="text" name="name" id="group-name" value="" class="text ui-widget-content ui-corner-all" required>
	  <div class="user-list" style="position:relative; clear:both;">
			<label style="padding-bottom:10px;"><?php echo $this->lang->line('select_user'); ?></label>
			<select class="js-data-example-ajax text ui-widget-content ui-corner-all" id="group-users" name="users[]" multiple="true" style="width:100%;" placeholder="<?php echo $this->lang->line('enter_user_name_or_email'); ?>"></select>
		</div>
    
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">   
  </form>
</div>	
    <div class="ajax-load text-center" style="display:none">
        <p><img src="<?php echo ASSETS_URL . 'images/loader.gif'; ?>">Loading</p>
    </div>
    <script>
	  $( function() {
		  $(".target").click(function(event){					
				if($(this).val()=='user'){
					$("#people-elements").show();
					$("#title-elements").hide();
				}else if($(this).val()=='title'){
					$("#title-elements").show();
					$("#people-elements").hide();
				}			 
		  });
		  
		  $('#invite_email').multiple_emails({checkDupEmail: true,
			theme: "fontawesome",
			position: "top"
			});

		  $(".unfollow-btn").click(function(){
			 confirm_dialog.dialog( "open" ); 
		  });
		$(".delete_feedback").click(function(e){
			e.preventDefault();
			var fid=$(this).data("feedback");
			confirm_delete_feedback.find( "#feedback_id" ).val(fid);
			confirm_delete_feedback.dialog( "open" ); 
		  });
		var confirm_delete_feedback=$( "#confirm-delete-feedback" ).dialog({
		  resizable: false,
		  autoOpen: false,
		  height: "auto",
		  width: 400,
		  modal: true,
		  buttons: {
			"Confirm": function() {
				$("#remove-feedback-form").submit();
			  $( this ).dialog( "close" );
			},
			Cancel: function() {
			  $( this ).dialog( "close" );
			}
		  }
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
	var gdialog, gform,
	   name = $( "#name" ),
      allFields = $( [] ).add( name ),
      tips = $( ".validateTips" );
	$( "#create-user-group" ).on( "click", function() {
		console.log(gdialog);
      gdialog.dialog( "open" );
    });
	gdialog = $( "#dialog-add-group" ).dialog({
      autoOpen: false,
      height: 400,
      width: 350,
      modal: true,
      buttons: {
        "<?php echo $this->lang->line('create_group'); ?>": addUser,
        "<?php echo $this->lang->line('cancel'); ?>": function() {
          gdialog.dialog( "close" );
        }
      },
      close: function() {
        gform[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
	 gform = gdialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      addUser();
	  
    });
	 function addUser() {		 
      var valid = true;
	  allFields.removeClass( "ui-state-error" );
	  var group_name=$('#group-name').val();
	  if(group_name=="")
		  valid=false;
	  if ( valid ) {
		  $.ajax({
				dataType: 'json',
				type:'POST',
				url: gform[0].action,
				data: new FormData(gform[0]),
				processData: false,
				contentType: false
			}).done(function(group){
				
				var hasOption = $('#user_groups option[value="' + group.group_id + '"]');
				console.log(hasOption);				
				if (hasOption.length == 0) {
					$('#user_groups').append('<option value="'+group.group_id+'" selected="selected">'+group.title+'('+group.count+')'+'</option>');
				}else{
					$('#user_groups option[value="' + group.group_id + '"]').text(group.title+'('+group.count+')');
				}
				$('#group-users').val(null).trigger('change');
				gform[ 0 ].reset();
				gdialog.dialog( "close" );
			});
			
	  } else{
		  updateTips("All Fields are required.");
	  }		  
      return valid;
    }
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
						toastr.success('Feedback has been shared.', 'Success Alert', {timeOut: 5000});
					}				
					shareform[0].reset();	
				}).complete(function(data){
					console.log(data);					
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
	$('.js-data-example2-ajax').select2({
		templateResult: userlist,
		minimumInputLength: 5,
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
					s: term
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
				required:{
					depends: function(element) {
						if(document.getElementById("feedback_files").files.length<1)
							return true;
						else
							return false;
						
					}
				}
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

