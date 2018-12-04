<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-secion profile-page-wrapper"> <span class="edit-profile-popup-overlay">fas</span>
	<div class="profile-page">
		<div class="profile-image-block">
			<div class="container">
				<?php 
				if(isset($user_data['photo'])) {
				echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $user_data['photo'].'" alt="" />';
				} else {
				echo '<img src="'.ASSETS_URL . 'images/user-avatar-big.png" alt="" />';
				}
				?>
				<h3><?php echo $user_data['name']; ?></h3>
				<h4><?php echo $this->common->getCountries($user_data['country']); ?></h4>
				<span class="edit-profile-btn"><i class="fa fa-pencil" aria-hidden="true"></i><?php echo $this->lang->line('edit_profile'); ?></span>
				<div class="edit-feedback-btn-block"> 
					<a href="javascript:void(0)" id="show-feedbacks" class="blue-btn" title="Feedbacks"><?php echo $this->lang->line('feedbacks'); ?></a> 
					<a href="javascript:void(0)" id="show-followings" class="normal-btn" title="Followings"><?php echo $this->lang->line('followings'); ?></a>
				</div>
			</div>
		</div>
		<div class="profile-listing-block">
			<!-- Load by Ajax -->
		</div>
	</div>
</div>
<div class="login-form edit-profile-form">
  <div class="login-form-block">
    <div class="login-form-fields">
      <h3><?php echo $this->lang->line('edit_profile'); ?> <span class="close-edit-popup"><img src="<?php echo base_url().'assets/images/close-icon.png'; ?>" alt="" /></span></h3>
      <?php
      $attributes = array('id' => 'edit-profile-form', 'enctype' => 'multipart/form-data');
      echo form_open_multipart('user/profile', $attributes);
      ?>
      <div class="login-form-block-edit-profile">
        <div class="edit-profile-popup-pic">
		<?php 
		if(isset($user_data['photo'])) {
			echo '<img id="profile" src="'.S3_CDN . 'uploads/user/thumbs/' . $user_data['photo'].'" alt="" />';
		} else {
			echo '<img id="profile" src="'.ASSETS_URL . 'images/user-avatar-big.png" alt="" />';
		} ?>
		</div>
        <div class="fileUpload update-pic-btn">
          <span><?php echo $this->lang->line('upload_picture'); ?></span>
          <input type="file" name="photo" id="photo" class="upload" />
        </div>
        <ul>
          <li>
            <label><?php echo $this->lang->line('name'); ?></label>
            <input type="text" name="name" id="name" value="<?php echo $user_data['name']; ?>" />
          </li>
          <li class="gender">
			<label><?php echo $this->lang->line('gender'); ?></label>
			<div class="radio-block">
			  <span><input type="radio" name="gender" value="Male" <?php if($user_data['gender'] == 'Male') echo ' checked="checked"'; ?> /> <?php echo $this->lang->line('male'); ?></span>
			  <span><input type="radio" name="gender" value="Female" <?php if($user_data['gender'] == 'Female') echo ' checked="checked"'; ?> /> <?php echo $this->lang->line('female'); ?></span>
			  <span><input type="radio" name="gender" value="Other" <?php if($user_data['gender'] == 'Other') echo ' checked="checked"'; ?> /> <?php echo $this->lang->line('other'); ?></span>
			</div>
		  </li>
		  <li>
			<label><?php echo $this->lang->line('birth_date'); ?></label>
			<input type="text" name="dob" id="dob" placeholder="" value="<?php echo $user_data['dob']; ?>" />
		  </li>
		  <li class="country-select">
            <label>Country</label>
            <select name="country" id="country" class="form-control select2">
                <option value="" disabled="disabled" selected="selected">Select Country</option>
                <?php
                foreach ($country_list as $country) {
                    ?>
                    <option value="<?php echo $country['country_code']; ?>" <?php if($user_data['country'] == $country['country_code']) echo 'selected="selected"'; ?>><?php echo $country['country_name']; ?></option>
                    <?php
                }
                ?>
            </select>
          </li>
		  <li class="searchable gender">
			<label>Privacy</label>
			<div class="radio-block">
			  <span><input type="radio" name="searchable" value="1" <?php if($user_data['searchable'] == '1') echo ' checked="checked"'; ?> /> Other user can see you in search results.</span>
			  <span><input type="radio" name="searchable" value="0" <?php if($user_data['searchable'] == '0') echo ' checked="checked"'; ?> /> Hide from search results. </span>
			 
			</div>
		  </li>
		  <li>
			<input type="submit" name="btn_save" id="btn_save" value="<?php echo $this->lang->line('save'); ?>" />
		  </li>
		</ul>
      </div>
	  <?php echo form_close(); ?>
	</div>
  </div>
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">
$(document).ready(function() {
	
	// Get Feedbacks and Followings
	var element = $('.profile-listing-block');
	
	$.ajax({
		type:'POST',
		url: '<?php echo site_url('user/feedbacks'); ?>'
	}).done(function(data){
		element.html(data);
	});
			
	// Load Datepicker
	$("#dob").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat:'yy-mm-dd',
		maxDate: '0',
		yearRange: "-100:+0", // last hundred years
	});
	
	// Form validations
	$("#edit-profile-form").validate({
	
		// Specify the validation rules
		rules: {
			name: {
				required: true
			},
			country: {
				required: true
			}
		},
		
		// Specify the validation error messages
		messages: {
			name: {
				required: "Please enter your name"
			},
			country: "Please select your country"
		},
		
		submitHandler: function(form) {
			$(".wrapper").removeClass("edit-profile-popup-open");

			$.ajax({
				dataType: 'json',
				type:'POST',
				url: form.action,
				data: new FormData(form),
				processData: false,
				contentType: false
			}).done(function(data){
				console.log(data);
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			}).fail(function(data){
				console.log(data);				
			}).success(function(data){
				console.log(data);				
			});
			
			return false;
		}
	});
	
	$(".edit-profile-btn").click(function(){
		$(".wrapper").addClass("edit-profile-popup-open");
	});
	
	$(".close-edit-popup").click(function(){
		$(".wrapper").removeClass("edit-profile-popup-open");
	});
	
	$('#show-feedbacks').click(function(e) {
		e.preventDefault();
		
		$.ajax({
			type:'POST',
			url: '<?php echo site_url('user/feedbacks'); ?>'
		}).done(function(data){
			element.html(data);
			$('#show-followings').removeClass("blue-btn").addClass("normal-btn");
			$('#show-feedbacks').removeClass("normal-btn").addClass("blue-btn");
		});
	});
	
	$('#show-followings').click(function(e) {
		e.preventDefault();
		
		$.ajax({
			type:'POST',
			url: '<?php echo site_url('user/followings'); ?>'
		}).done(function(data){
			element.html(data);
			$('#show-feedbacks').removeClass("blue-btn").addClass("normal-btn");
			$('#show-followings').removeClass("normal-btn").addClass("blue-btn");
		});
	});
	
	$("#photo").change(function(){
		console.log('heredf');
		if (this.files && this.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function (e) {
				$('#profile').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(this.files[0]);
		}
		//imagePreview(this);
	});
	
	
});

function imagePreview(input) {
	console.log(input);
	console.log('in');
	/*if (input.files && input.files[0]) {
		var reader = new FileReader();
		
		reader.onload = function (e) {
			$('#profile').attr('src', e.target.result);
		}
		
		reader.readAsDataURL(input.files[0]);
	}*/
}
</script>
