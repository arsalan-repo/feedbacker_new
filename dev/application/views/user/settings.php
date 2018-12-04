<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-secion">
    <div class="container creatae-post-content">
      <h2><?php echo $this->lang->line('settings'); ?></h2>
      <ul class="tabs">
          <li class="tab-link <?=($active_tab==1)? 'current':''; ?> " data-tab="tab-1"><?php echo $this->lang->line('language'); ?></li>
          <li class="tab-link <?=($active_tab==2)? 'current':''; ?>" data-tab="tab-2"><?php echo $this->lang->line('change_pass'); ?></li>
          <li class="tab-link <?=($active_tab==3)? 'current':''; ?>" data-tab="tab-3"><?php echo $this->lang->line('contact_us'); ?></li>
          <li class="tab-link <?=($active_tab==4)? 'current':''; ?>" data-tab="tab-4"><?php echo $this->lang->line('terms_cond'); ?></li>
		  <li class="tab-link <?=($active_tab==5)? 'current':''; ?>" data-tab="tab-5">User Groups/Lists</li>
		  <li class="tab-link <?=($active_tab==6)? 'current':''; ?>" data-tab="tab-6">Blocked Users</li>
      </ul>
      <div id="tab-1" class="tab-content language-tab <?=($active_tab==1)? 'current':''; ?>">
          <?php
			$attributes = array('id' => 'lang-form');
			echo form_open('user/set_language', $attributes); ?>
            <ul>
              <?php foreach($languages as $lang) { ?>
              <li>
				 <input type="radio" name="lang_id" id="<?php echo $lang['lang_code']; ?>" value="<?php echo $lang['lang_id']; ?>" class="css-checkbox" <?php if($user_info['lang_id'] == $lang['lang_id']) echo 'checked="checked"'; ?>/>
                 <label for="<?php echo $lang['lang_code']; ?>" class="css-label radGroup2"><?php echo $lang['lang_name']; ?></label>
              </li>
			  <?php } ?>
              <li>
			  	<input type="hidden" name="lang_code" id="lang_code" value="<?php echo $lang['lang_code']; ?>" />
                <input type="submit" name="btn_save" id="btn_save" value="<?php echo $this->lang->line('save'); ?>" />
              </li>
            </ul>
          <?php echo form_close(); ?>
      </div>
      <div id="tab-2" class="tab-content change-password-tab <?=($active_tab==2)? 'current':''; ?>">
          <?php
			$attributes = array('id' => 'pass-form',  'autocomplete' => 'off');
			echo form_open('user/change_password', $attributes); ?>
            <ul>
              <li>
                <label><?php echo $this->lang->line('old_pass'); ?></label>
                <input type="password" placeholder="" name="old_pass" id="old_pass" />
              </li>
              <li>
                <label><?php echo $this->lang->line('new_pass'); ?></label>
                <input type="password" placeholder="" name="new_pass" id="new_pass" />
              </li>
              <li>
                <label><?php echo $this->lang->line('confirm_new_pass'); ?></label>
                <input type="password" placeholder="" name="confirm_pass" id="confirm_pass" />
              </li>
              <li>
                <input type="submit" name="btn_save" id="btn_save" value="<?php echo $this->lang->line('save'); ?>" />
              </li>
            </ul>
          <?php echo form_close(); ?>
      </div>
      <div id="tab-3" class="tab-content contact-tab <?=($active_tab==3)? 'current':''; ?>">
          <?php
			$attributes = array('id' => 'contact-form');
			echo form_open('user/contact_us', $attributes); ?>
            <ul>
              <li>
                <label><?php echo $this->lang->line('name'); ?></label>
                <input type="text" placeholder="" name="name" id="name" />
              </li>
              <li>
                <label><?php echo $this->lang->line('email'); ?></label>
                <input type="text" placeholder="" name="email" id="email" />
              </li>
              <li>
                <label><?php echo $this->lang->line('comment'); ?></label>
                <input type="text" placeholder="" name="message" id="message" />
              </li>
              <li>
                <input type="submit" name="btn_save" id="btn_save" value="<?php echo $this->lang->line('send'); ?>" />
              </li>
              <li> </li>
            </ul>
          <?php echo form_close(); ?>
      </div>
      <div id="tab-4" class="tab-content terms-tab <?=($active_tab==4)? 'current':''; ?>">
          <?php echo nl2br($terms[0]['description']); ?>
      </div>
      <div id="tab-5" class="tab-content groups-tab <?=($active_tab==5)? 'current':''; ?>">
       
		  <div id="users-contain" class="ui-widget">
			  <h3>Your User Groups</h3>
			  <table id="users" class="ui-widget ui-widget-content" style="width:100%;">
				<thead>
				  <tr class="ui-widget-header ">
					<th>Name</th>
					<th>Members</th>
					<th>Actions</th>
				  </tr>
				</thead>
				<tbody>
				<?php foreach($groups as $group): ?>
					<tr>
					<td><?=$group['title']; ?></td>
					<td><?=$group['count']; ?></td>		
					<td><a href="<?=site_url('user/edit_group/'.$group['group_id']); ?>" class="edit-group" data-group="<?=$group['group_id']; ?>">Edit</a> | <a href="#" class="delete-group" data-group="<?=$group['group_id']; ?>">Delete</a></td>		
				  </tr>
				<?php endforeach; ?>				  
				</tbody>
			  </table>
			</div>
			<button id="create-user-group">Create new group</button>
      </div>
	  <div id="tab-6" class="tab-content blocked-user-tab <?=($active_tab==6)? 'current':''; ?>">  
			<?php if(!empty($blocked_users) && is_array($blocked_users)): ?>
		  <div id="users-contain" class="ui-widget">
			  <h3>Blocked Users</h3>			  
				<?php foreach($blocked_users as $usr): ?>
					<div class="post-profile-block">
						<div class="post-right-arrow">			  
						   	<a class="unblock_user" data-user="<?=$usr['user_id']; ?>" style="color:blue; font-size:14px;" href="#"><i class="fa fa-times" aria-hidden="true"></i> Unblock</a>	
						</div>
						<div class="post-img">					
							<?php 
							if(isset($usr['photo'])) {
								echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $usr['photo'].'" alt="" />';
							} else {
								echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
							}
							?>				
						</div>
						<div class="post-profile-content" style="min-height:40px;"> 
							<span class="post-name"><?=$usr['name']; ?></span>
							<span class="post-address"><span class="post-profile-time-text"><?=$usr['time']; ?></span></span>
						</div>
					 </div>					
				<?php endforeach; ?>
				
			</div>
			<?php endif; ?>
			<div class="search-form post-profile-block">
				<?php $attributes = array('id' => 'user-search-form');
					echo form_open('user/settings/6', $attributes); ?>
					<h3>Search User</h3>
					<div class="form-group">
						<input type="text" placeholder="Enter name or email" name="s" id="s" required="true" class="ui-autocomplete-input" autocomplete="off" aria-required="true" value="<?=$search_text; ?>">
						<input type="submit" value="search" name="search" style="width:160px; float:right;">		
					</div>
				</form>
			</div>	
			<?php if(!empty($users)): ?>
			<div class="search-results post-profile-block">
			<?php $attributes = array('id' => 'user-block-form');
					echo form_open('user/settings/6', $attributes); ?>
			<?php foreach($users as $user): ?>
				<div class="user-item post-profile-block">
					<div class="post-right-arrow">								
						 <label class="label">
							<input  class="label__checkbox" type="checkbox" name="users[]" value="<?=$user['id']; ?>" />
							<span class="label__text">
							  <span class="label__check">
								<i class="fa fa-check icon"></i>
							  </span>
							</span>
						  </label>
					</div>
					<div class="post-img">					
							<?php 
							if(isset($user['photo'])) {
								echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $user['photo'].'" alt="" />';
							} else {
								echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
							}
							?>				
						</div>
						<div class="post-profile-content" style="min-height:40px;"> 
							<span class="post-name"><?=$user['text']; ?></span>
						</div>
				</div>
			<?php endforeach; ?>
				<div class="form-group">				
						<input type="submit" value="Block Selected Users" name="block_users" style="width:180px; float:right;">		
				</div>
			</form>
			</div>
			<?php endif; ?>
      </div>
    </div>
  </div>
<div id="dialog-add-group" title="Create new User Group">
  <p class="validateTips">All form fields are required.</p> 
  <form id="create_user_group_form" style="position:relative; width:100%;" method="post" action="<?=site_url('user/add_user_group'); ?>">   
      <label for="name">Name</label>
      <input type="text" name="name" id="group-name" value="" class="text ui-widget-content ui-corner-all" required>
	  <div class="user-list" style="position:relative; clear:both;">
			<label style="padding-bottom:10px;">Select Users</label>
			<select class="js-data-example-ajax text ui-widget-content ui-corner-all" name="users[]" multiple="true" style="width:100%;" placeholder="Search by name or email"></select>
		</div>
    
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">   
  </form>
</div>
<div id="dialog-unblock-user" title="Are you sure you want to unblock this user?">
		<form id="unblock-user-form" action="<?=site_url('user/unblock'); ?>" method="post" >
		  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		  This will permanently remove this user from your blocked list. Are you sure?</p>
		  <input type="hidden" id="user_id" name="user_id" value="">	
		</form> 
</div>
<div id="confirm-delete-group" class="modal hide fade" title="<?php echo $this->lang->line('confirm_delete'); ?>">
	 <form id="delete-user-group-form" action="<?=site_url('user/delete_group'); ?>" method="post" >
	 <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		  This will permanently remove this group. Are you sure?</p>
	 <input type="hidden" id="group_id" name="group_id" value="">
	  
	 </form> 	  
	</div>

<!-- /.content-wrapper -->
<script type="text/javascript">
$(document).ready(function() {
	
	// Set Language
	$("#lang-form").submit(function(event) {	
		event.preventDefault();
		
		$.ajax({
			dataType: 'json',
			type:'POST',
			url: this.action,
			data: $(this).serialize()
		}).done(function(data){
			if(data.status == 1) {
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			} else {
				toastr.error(data.message, 'Failure Alert', {timeOut: 5000});
			}
		});
	});
	
	// Change Password
	$("#pass-form").validate({
	
		// Specify the validation rules
		rules: {
			old_pass: {
				required: true,
				minlength: 3,
				maxlength: 15
			},
			new_pass: {
				required: true,
				minlength: 3,
				maxlength: 15
			},
			confirm_pass: {
				required: true,
				equalTo: "#new_pass",
				minlength: 3,
				maxlength: 15
			}
		},
		
		// Specify the validation error messages
		messages: {
			old_pass: {
				required: "Please enter your existing password"
			},
			new_pass: {
				required: "Please enter your new password"
			},
			confirm_pass: {
				required: "Please confirm your new password"
			},
		},
		
		submitHandler: function(form) {
			$.ajax({
				dataType: 'json',
				type:'POST',
				url: form.action,
				data: $("#pass-form").serialize()
			}).done(function(data){
				if(data.status == 1) {
					toastr.success(data.message, 'Success Alert', {timeOut: 5000});
				} else {
					toastr.error(data.message, 'Failure Alert', {timeOut: 5000});
				}
			});
			
			return false;
		}
	});
	
	// Contact Us
	$("#contact-form").validate({
	
		// Specify the validation rules
		rules: {
			name: {
				required: true,
				minlength: 3,
				maxlength: 25
			},
			email: {
				required: true,
				email: true
			},
			message: {
				required: true,
				minlength: 3,
				maxlength: 500
			}
		},
		
		// Specify the validation error messages
		messages: {
			name: {
				required: "Please enter your name"
			},
			email: {
				required: "Please enter your email"
			},
			message: {
				required: "Please enter your message"
			},
		},
		
		submitHandler: function(form) {
			$.ajax({
				dataType: 'json',
				type:'POST',
				url: form.action,
				data: $("#contact-form").serialize()
			}).done(function(data){
				if(data.status == 1) {
					toastr.success(data.message, 'Success Alert', {timeOut: 5000});
				} else {
					toastr.error(data.message, 'Failure Alert', {timeOut: 5000});
				}
			});
			
			return false;
		}
	});
	
});
var base_url="<?=base_url();?>";
var URL=base_url+"user/search_users";
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
 $( function() {	 
	 $('.js-data-example-ajax').select2({
		templateResult: userlist,
		minimumInputLength: 2,
		placeholder: 'Enter user name or email address',
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
		}
	});	
	 var gdialog, gform,
	   name = $( "#name" ),
      allFields = $( [] ).add( name ),
      tips = $( ".validateTips" );
	function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    };
	
	gdialog = $( "#dialog-add-group" ).dialog({
      autoOpen: false,
      height: 400,
      width: 350,
      modal: true,
      buttons: {
        "Create an account": function(){
			$("#create_user_group_form").submit();
			gdialog.dialog( "close" );
		},
        Cancel: function() {
          gdialog.dialog( "close" );
        }
      },
      close: function() {
        gform[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });
 
   /* gform = gdialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      addUser();
	  
    });*/
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
				console.log(group);
				/*var hasOption = $('#user_groups option[value="' + group.group_id + '"]');
				console.log(hasOption);				
				if (hasOption.length == 0) {
					$('#user_groups').append('<option value="'+group.group_id+'" selected="selected">'+group.title+'('+group.count+')'+'</option>');
				}else{
					$('#user_groups option[value="' + group.group_id + '"]').text(group.title+'('+group.count+')');
				}*/
			});	
		  gdialog.dialog( "close" );
	  } else{
		  updateTips("All Fields are required.");
	  }		  
      return valid;
    }
	$( "#create-user-group" ).on( "click", function() {
		
		console.log(gdialog);
      gdialog.dialog( "open" );
    });
	
	var unblockdialog = $( "#dialog-unblock-user" ).dialog({
      autoOpen: false,
	resizable: false,	  
      modal: true,
      buttons: {
        "Confirm": function() {
				$("#unblock-user-form").submit();
			  $( this ).dialog( "close" );
			},
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      }
    });
	$( ".unblock_user" ).on( "click", function(e) {	
		e.preventDefault();
		var uid=$(this).data("user");
		unblockdialog.find( "#user_id" ).val(uid);
      unblockdialog.dialog( "open" );
    });
	var cdialog, form;
	cdialog = $("#confirm-delete-group").dialog({
			autoOpen: false,
			modal: true,
			resizable: false,
			 buttons: {
			"Confirm": function() {
				$("#delete-user-group-form").submit();
			  $( this ).dialog( "close" );
			},
			Cancel: function() {
			  $( this ).dialog( "close" );
			}
		  }
	});
	$('.delete-group').off("click").on("click",function(e){
			e.preventDefault();
			var uid=$(this).data("group");
			cdialog.find( "#group_id" ).val(uid);
			cdialog.dialog( "open" );
			
	});
 });
</script>
