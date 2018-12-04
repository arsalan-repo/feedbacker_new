<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-secion">
    <div class="container creatae-post-content">
      	
		<form method="post">
			<h2><?php echo $this->lang->line('edit_group'); ?>
			  <a style="font-size:12px;" href="<?=site_url('user/settings/5'); ?>" class="pull-right">
			 <?php echo $this->lang->line('view_all_groups'); ?>
			  </a>
			  </h2>	
			<label> <?php echo $this->lang->line('name'); ?><label>
			<input type="text" name="title" value="<?=$group['title']; ?>" required>
			<div class="user-list" style="position:relative; clear:both; margin-bottom:20px;">
				<label style="padding-bottom:10px;"> <?php echo $this->lang->line('add_users'); ?></label>
				<select class="js-data-example-ajax text ui-widget-content ui-corner-all" name="users[]" multiple="true" style="width:100%;" placeholder="<?php echo $this->lang->line('enter_user_name_or_email'); ?>"></select>
			</div>
			
			<label><?php echo $this->lang->line('current_members'); ?>  - <small><?php echo $this->lang->line('select_any_to_remove'); ?></small><label>
			<div class="row" style="width:100%; clear:both; overflow:hidden; margin-bottom:20px;">
			<?php foreach($group_users as $user): ?>
				<div class="user-item post-profile-block" style="margin-bottom:5px;">
					<div class="post-right-arrow">								
						 <label class="label">
							<input  class="label__checkbox" type="checkbox" name="remove_users[]" value="<?=$user['user_id']; ?>" />
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
							<span class="post-name"><?=$user['name']; ?></span>
						</div>
				</div>
			<?php endforeach; ?>
			</div>
			<input type="submit" value="<?php echo $this->lang->line('submit'); ?> " name="submit" style="width:160px; float:right;">
		</form>
    </div>
 </div>




<!-- /.content-wrapper -->
<script type="text/javascript">
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
 });

</script>
