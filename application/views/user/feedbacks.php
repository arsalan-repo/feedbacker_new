<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="container">
  <ul class="masonry">
  <?php if (!empty($feedbacks)) { ?>
  <!-- Loop Starts Here -->
  <?php foreach($feedbacks as $row) { ?>
	<li class="item">
	  <div class="profile-listing">
		<div class="profile-listing-img-thumb-block">
		  <div class="profile-listing-img-thumb">
			<a href="<?php echo site_url('post/detail').'/'.$row['id']; ?>">
			<?php
			if(isset($row['user_avatar'])) {
				echo '<img src="'.$row['user_avatar'].'" alt="" />';
			} else {
				echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
			}
			?>
			</a>
		  </div>
		  <span class="listing-post-profile-name"><?php echo $row['name']; ?></span> 
		  <span class="listing-post-profile-time"><?php echo $row['time']; ?></span> 
		</div>
		<div class="listing-post-name-block"> 
			<span class="listing-post-name">
				<a href="<?php echo site_url('post/title').'/'.$row['title_id']; ?>"><?php echo $row['title']; ?></a>
			</span>
			<span class="listing-post-followers"><?php echo $row['followers']; ?> Followers</span> 
		</div>
		<?php if(count($row['feedback_images'])>1){ ?>
			<div class="listing-post-slider" style="width:100%; clear:both;">
			<div class="flexslider">
			  <ul class="slides">
			  <?php foreach($row['feedback_images'] as $img): ?>
			  <li>
				  <img src="<?=$img['feedback_thumb']; ?>" />
				</li>
			  <?php endforeach; ?>			
			  </ul>
			</div>
			</div>
		<?php }else{ ?>
			<div class="listing-post-img">
			<?php
				if(!empty($row['feedback_img'])) {
					echo '<img src="'.$row['feedback_img'].'" alt="" />';
				} else {
					echo '<img src="'.base_url().'assets/images/feedback-placeholder-img.jpg" alt="" />';
				} ?>    
			</div>
		<?php } ?>		
		<p class="user-feedbacks">
		<?php //echo str_replace("\\n","<br>",str_replace("\\r\\n","<br>",$this->emoji->Decode(nl2br($row['feedback'])))); ?>
		<?php echo str_replace("\\n","<br>",str_replace("\\r\\n","<br>",$this->emoji->Decode(nl2br($row['feedback'])))); ?>
		
		<a href="<?=site_url('post/edit/'.$row['id']); ?>" class=""><i class="fa fa-pencil" aria-hidden="true" title="<?php echo $this->lang->line('edit'); ?>"></i></a> 
		<a href="<?=site_url('post/delete_feedback/'.$row['id']); ?>" class=""><i class="fa fa-remove" aria-hidden="true" title="<?php echo $this->lang->line('delete'); ?>"></i></a>
		
		</p>
		<div class="post-listing-follow-btn"> 
			<span class="post-follow-back-arrow" id="reply-btn-<?php echo $row['id']; ?>">
				<img src="<?php echo ASSETS_URL.'images/reply-arrow.png'; ?>" alt="" title="<?php echo $this->lang->line('reply'); ?>" />
			</span> 
			<span class="follow-btn-default <?php if($row['is_followed']) echo 'unfollow-btn'; ?> follow-btn-<?php echo $row['title_id']; ?>">
                <?php if ($row['is_followed']) { ?>
                    <?php echo $this->lang->line('unfollow'); ?>
                <?php } else { ?>    
                    <?php echo $this->lang->line('follow'); ?> <i class="fa fa-plus" aria-hidden="true"></i>
                <?php } ?>
            </span>
            <span class="wishlist" id="post-wishlist-<?php echo $row['id']; ?>">
				<?php if ($row['is_liked']) { ?>
					<i class="fa fa-heart" aria-hidden="true" title="<?php echo $this->lang->line('unlike'); ?>"></i> 
				<?php } else { ?>
					<i class="fa fa-heart-o" aria-hidden="true" title="<?php echo $this->lang->line('like'); ?>"></i>
				<?php } ?> 
				<?php echo $row['likes']; ?>
            </span>
			<input type="hidden" id="feedback_id" value="<?php echo $row['id']; ?>" />
			<input type="hidden" id="totl_likes" value="<?php echo $row['likes']; ?>" />			
            <input type="hidden" id="title_id" value="<?php echo $row['title_id']; ?>" />
		 </div>
	  </div>
	</li>
  <?php } ?>
  <!-- Loop Ends Here -->
  <?php } else { ?>
  <?php echo $no_record_found; ?>
  <?php } ?>  
  </ul>
</div>
<div class="post-detail-comment-form" title="<?php echo $this->lang->line('write_comment'); ?>">
  <?php
	$attributes = array('id' => 'reply-post-form', 'enctype' => 'multipart/form-data');
	echo form_open_multipart('post/reply', $attributes);
	?>
	<label><?php echo $this->lang->line('comment'); ?></label>
	<textarea name="feedback_cont" id="feedback_cont" placeholder="<?php echo $this->lang->line('comment_here'); ?>" rows="10"></textarea>
	<input type="text" name="location" id="location" placeholder="<?php echo $this->lang->line('location'); ?>" readonly="" />
  
	<div class="post-btn-block">
		<div class="camera-map-icon">
			<div class="camera-icon-block">
				<span>Choose File</span>
				<input name="feedback_img" id="feedback_img" type="file" />
			</div>
			<img src="<?php echo base_url().'assets/images/map-icon.png'; ?>" class="geo-map" alt="" />
		</div>
		<span class="post-btn"><?php echo $this->lang->line('post'); ?></span>
	</div>
	<input type="hidden" name="id" id="id" value="" />
	<input type="hidden" name="latitude" id="latitude" value="" />
	<input type="hidden" name="longitude" id="longitude" value="" />
	<?php echo form_close(); ?>
	<img id="preview" src="" alt="" height="200" width="200" />
</div>
<script type="application/javascript">
	$(function(){
		var dialog, form;
		
		if ($(".post-detail-comment-form").hasClass('ui-dialog-content')) {
			$(".ui-dialog-content").remove();
		}
		
		dialog = $(".post-detail-comment-form").dialog({
			autoOpen: false,
			width: 680,
			modal: true,
			resizable: false,
			buttons: false,
			close: function() {
				form[0].reset();
				
				$("#feedback_cont-error").remove();
				$("#feedback_cont").removeClass( "error" );
			}
		});
		
		form = dialog.find( "form" );
		
		dialog.find( ".post-btn" ).on( "click", function( event ) {
			event.preventDefault();
			
			$("#feedback_cont-error").remove();
			$("#feedback_cont").removeClass( "error" );
			
			if ($("#feedback_cont").val().length == 0) {
				$("#feedback_cont").addClass( "error" );
				$('<label id="feedback_cont-error" class="error" for="feedback_cont">Please enter a feedback</label>').insertAfter("#feedback_cont");
				
				return false;
			}
			
			$.ajax({
				dataType: 'json',
				type:'POST',
				url: form[0].action,
				data: new FormData(form[0]),
				processData: false,
				contentType: false
			}).done(function(data){
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			});
			
			dialog.dialog( "close" );
			return true;
		});
		
		$('.post-follow-back-arrow').off("click").on("click",function(e){
			var feedback_id = $(this).parent().find('#feedback_id').val();
			dialog.find( "#id" ).val(feedback_id);
			dialog.dialog( "open" );
		});
		
		$("#feedback_img").change(function(){
			imagePreview(this);
		});
		
		$(".geo-map").click(function() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showLocation);
			} else { 
				toastr.error('Geolocation is not supported by this browser', 'Failure Alert', {timeOut: 5000});
			}
		});
		$('.flexslider').flexslider({
			animation: "slide"
		  });
	});
	
	function showLocation(position) {
		var latitude = position.coords.latitude;
		var longitude = position.coords.longitude;
		
		$.ajax({
			type:'POST',
			url:'<?php echo site_url('post/get_location'); ?>',
			data:'latitude='+latitude+'&longitude='+longitude,
			success:function(response){
				if(response){
					var objJSON = JSON.parse(response);
					$('#location').val(objJSON.location);
					
					$( "#latitude" ).val( latitude );
					$( "#longitude" ).val( longitude );
				}else{
					toastr.error('Error getting location. Try later!', 'Failure Alert', {timeOut: 5000});
				}
			}
		});
	}
	
	function imagePreview(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function (e) {
				$('#preview').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]);
		}
	}

	$('.follow-btn-default').off("click").on("click",function(e){
		e.preventDefault();
		
		var title_id = $(this).parent().find('#title_id').val();
		
		$.ajax({
			dataType: 'json',
			type:'POST',
			url: '<?php echo site_url('title/follow'); ?>',
			data:{title_id:title_id}
		}).done(function(data){
			// console.log(data);
			if (data.is_followed == 1) {
				$('.follow-btn-'+title_id).each(function() {
					$(this).addClass('unfollow-btn');
					$(this).html('<?php echo $this->lang->line('unfollow'); ?>');
				});
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			}
			else
			{
				$('.follow-btn-'+title_id).each(function() {
					$(this).removeClass('unfollow-btn');
					$(this).html('<?php echo $this->lang->line('follow'); ?> <i class="fa fa-plus" aria-hidden="true"></i>');
				});
				toastr.warning(data.message, 'Success Alert', {timeOut: 5000});
			}
		});
	});
	
	$('.wishlist').off("click").on("click",function(e){
		e.preventDefault();
		
		var element = $(this).attr('id');
		var totl_likes = $(this).parent().find('#totl_likes').val();
		var feedback_id = $(this).parent().find('#feedback_id').val();
	
		$.ajax({
			dataType: 'json',
			type:'POST',
			url: '<?php echo site_url('post/like'); ?>',
			data:{feedback_id:feedback_id, totl_likes:totl_likes}
		}).done(function(data){
			// console.log(data);
			if (data.is_liked == 1) {
				var totl = parseInt(data.likes) + 1;	
				$('#'+element).parent().find('#totl_likes').val(totl);
							
				$('#'+element).html('<i class="fa fa-heart" aria-hidden="true"></i><span class="total-likes"> '+totl+'</span>');
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			}
			else
			{
				var totl = parseInt(data.likes) - 1;
				$('#'+element).parent().find('#totl_likes').val(totl);
				
				$('#'+element).html('<i class="fa fa-heart-o" aria-hidden="true"></i><span class="total-likes"> '+totl+'</span>');
				toastr.warning(data.message, 'Success Alert', {timeOut: 5000});
			}
		});
	});
</script>
