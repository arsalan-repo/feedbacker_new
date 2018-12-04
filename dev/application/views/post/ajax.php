<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 

foreach($feedbacks as $row) { ?>
  <div class="post-profile-block" id="post<?=$row['id']; ?>">
   <div class="post-right-arrow">
   
   <!--<i class="fa fa-angle-down" aria-hidden="true"></i>-->
   
   <?php if(isset($row['ads'])) { ?>
    <span class="post-profile-time-text">Promoted</span>
   <?php } else { ?>
    <span class="post-followers-text">
	<?php 
	if(isset($user_info['language']) && $user_info['language'] == 'ar') {
		echo $this->lang->line('followers')." ".$row['followers'];
	} else {
		echo $row['followers']." ".$this->lang->line('followers');
	} ?>
	</span>
    <span class="post-profile-time-text"><?php echo $row['time']; ?></span>            
   <?php } ?>
   <?php if(!empty($this->session->userdata['mec_user']['id'])): ?>
	<span class="post-options-wrapper"><a href="#" onclick="return toggleActions(<?=$row['id']; ?>);" class="post-options-btn" rel="toggle" data-feedback="<?=$row['id']; ?>" data-title="<?=$row['title_id']; ?>"></a></span>
   <div class="actions-bar" id="actions-bar-<?=$row['id']; ?>" style="display:none;">
		<ul>
			<li><a href="<?=site_url('user/hide_title/'.$row['title_id']); ?>" data-action="hide_title" data-title="<?=$row['title_id']; ?>" class="option-item delete-option"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hide This Title</a></li>
			<?php if($row['user_id']!=$this->session->userdata['mec_user']['id']): ?>
			<li><a href="<?=site_url('user/hideuserfeedbacks/'.$row['user_id']); ?>" data-action="hide_feedbacks" data-user="<?=$row['user_id']; ?>" class="option-item delete-option"><i class="fa fa-eye-slash" aria-hidden="true"></i> Hide <?php echo $row['name']; ?> Feedbacks</a></li>
			<?php endif; ?>
			<li><a href="#" data-action="share_title" data-title="<?=$row['title_id']; ?>" class="option-item delete-option"><i class="fa fa-eye-slash" aria-hidden="true"></i> Share On Private Title</a></li>
			
			<li><a href="#" data-action="report" data-user="<?=$row['user_id']; ?>" class="option-item delete-option"><i class="fa fa-eye-slash" aria-hidden="true"></i> Report this Feedback</a></li>
			 <?php if($row['user_id']==$this->session->userdata['mec_user']['id']): ?>
			<li><hr></li>
			<li><a href="#" data-action="delete" data-feedback="<?=$row['id']; ?>" class="option-item delete-option"><i class="fa fa-remove" aria-hidden="true"></i> Delete</a></li>
			<li><a href="<?=site_url('post/edit/'.$row['id']); ?>" data-action="edit" class="edit-option"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></li>
			<?php endif; ?>
			
		</ul>
   </div>
   <?php endif; ?>
   </div>
    <div class="post-img">
        <?php
            if(isset($row['ads'])) {
                echo '<a href="'.$row['ads_url'].'" target="_blank">';
            } else {
                echo '<a href="'.site_url('post/detail').'/'.$row['id'].'">';	
            }
        ?>
        <?php
        if(isset($row['user_avatar'])) {
            echo '<img src="'.$row['user_avatar'].'" alt="" />';
        } else {
            echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
        }
        ?>
        </a>
    </div>
    <div class="post-profile-content"> 
        <span class="post-designation">
            <a href="<?php echo site_url('post/title').'/'.$row['title_id']; ?>"><?php echo $row['title']; ?></a>
        </span> 
        <span class="post-name"><?php echo $row['name']; ?></span> 
        <span class="post-address"><?php echo $row['location']; ?></span>
        <p><span class="more"><?php echo str_replace("\\n","<br>",str_replace("\\r\\n","<br>",$this->emoji->Decode(nl2br($row['feedback'])))); ?></span></p>
		<?php if(isset($row['feedback_images']) && count($row['feedback_images'])>1){ ?>
		<div class="flexslider">
		  <ul class="slides">
		  <?php foreach($row['feedback_images'] as $img): ?>
		  <li>
			<a href="<?=$img['feedback_img']; ?>">
			  <img src="<?=$img['feedback_img']; ?>" />
			  </a>
			</li>
		  <?php endforeach; ?>			
		  </ul>
		</div>
        <?php }else if (!empty($row['feedback_thumb'])) { ?>
            <div class="post-large-img">
            <?php 
				if(isset($row['ads'])) { ?>
					<a href="<?php echo $row['ads_url']; ?>" target="_blank">
						<img src="<?php echo $row['feedback_thumb']; ?>" alt="" />
					</a>
            <?php 
				} elseif (!empty($row['feedback_video'])) {
					echo '<video width="500" height="375" controls poster="'.$row['feedback_thumb'].'">
					<source src="'.$row['feedback_video'].'" type="video/mp4">
					Your browser does not support the video tag.
					</video>';
				} else {
					echo '<img src="'.$row['feedback_thumb'].'" alt="" />';
				} 
			?>
            </div>
        <?php } ?>
        <?php if(!isset($row['ads'])) { ?>
        <div class="post-follow-block"> 
			<?php if(!empty($this->session->userdata['mec_user'])): ?>
            <span class="post-follow-back-arrow post-comment-button" id="reply-btn-<?php echo $row['id']; ?>">
                <img src="<?php echo ASSETS_URL.'images/reply-arrow.png'; ?>" alt="" title="<?php echo $this->lang->line('reply'); ?>" />
            </span>
            <span class="follow-btn-default follow-button <?php if($row['is_followed']) echo 'unfollow-btn'; ?> follow-btn-<?php echo $row['title_id']; ?>">
                <?php if ($row['is_followed']) { ?>
                    <?php echo $this->lang->line('unfollow'); ?>
                <?php } else { ?>    
                    <?php echo $this->lang->line('follow'); ?> <i class="fa fa-plus" aria-hidden="true"></i>
                <?php } ?>
            </span>
            <span class="post-wishlist post-like-button" id="post-wishlist-<?php echo $row['id']; ?>">
				<?php if ($row['is_liked']) { ?>
					<i class="fa fa-heart" aria-hidden="true" title="<?php echo $this->lang->line('unlike'); ?>"></i> 
				<?php } else { ?>
					<i class="fa fa-heart-o" aria-hidden="true" title="<?php echo $this->lang->line('like'); ?>"></i>
				<?php } ?> 
				<?php echo $row['likes']; ?>
            </span>
			
			<?php else: ?>
				<a class="post-follow-back-arrow" href="<?=site_url('post/detail/'.$row['id']); ?>" id="reply-btn-<?php echo $row['id']; ?>">
                <img src="<?php echo ASSETS_URL.'images/reply-arrow.png'; ?>" alt="" title="<?php echo $this->lang->line('reply'); ?>" />
				</a>
				<a href="<?=site_url('post/detail/'.$row['id']); ?>" class="follow-btn-default <?php if($row['is_followed']) echo 'unfollow-btn'; ?> follow-btn-<?php echo $row['title_id']; ?>">
                <?php if ($row['is_followed']) { ?>
                    <?php echo $this->lang->line('unfollow'); ?>
                <?php } else { ?>    
                    <?php echo $this->lang->line('follow'); ?> <i class="fa fa-plus" aria-hidden="true"></i>
                <?php } ?>
				</a>
				<a href="<?=site_url('post/detail/'.$row['id']); ?>" class="post-wishlist" id="post-wishlist-<?php echo $row['id']; ?>">
					<?php if ($row['is_liked']) { ?>
						<i class="fa fa-heart" aria-hidden="true" title="<?php echo $this->lang->line('unlike'); ?>"></i> 
					<?php } else { ?>
						<i class="fa fa-heart-o" aria-hidden="true" title="<?php echo $this->lang->line('like'); ?>"></i>
					<?php } ?> 
					<?php echo $row['likes']; ?>
				</a>
			<?php endif;   ?>
			<span class="social-sharing">				
				<a href="#">
					<div class="social-icons-container">
						<div class="sharetastic" data-url="<?=site_url('post/detail/'.$row['id']); ?>" data-title="<?=$row['title']; ?>" data-description=" "></div>	
						<?php //echo strip_tags(str_replace("\\r\\n","\r\n",$this->emoji->Decode($row['feedback']))); ?>
					</div>
					<img src="<?=base_url('assets/images/share.png'); ?>"></a>
			</span>
			<?php if(!empty($row['feedback_pdf'])): ?>
			<span class="post-pdf-icon" id="reply-pdf-<?php echo $row['id']; ?>">
                <a href="<?=$row['feedback_pdf']; ?>"><img src="<?php echo ASSETS_URL.'images/pdf-icon.png'; ?>" alt="" title="PDF File" /></a>
            </span>
			<?php endif; ?>
			<input type="hidden" id="feedback_id" value="<?php echo $row['id']; ?>" />
			<input type="hidden" id="totl_likes" value="<?php echo $row['likes']; ?>" />			
            <input type="hidden" id="title_id" value="<?php echo $row['title_id']; ?>" />
        </div>
        <?php } ?>
    </div>
  </div>
<?php } ?>
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
				<input name="feedback_img[]" multiple id="feedback_img" type="file" />
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
	<div id="preview-wrapper"></div>
</div>

<script type="application/javascript">
	
	$(function(){
		baguetteBox.run('.slides');
		// Show More
		var showChar = 100;  // How many characters are shown by default
		var ellipsestext = "...";
		var moretext = "Read More";
		var lesstext = "Read Less";
		/*$('.post-options-btn').on('click',function(e){
			e.preventDefault();
			var fid=$(this).data('feedback');
			$("#actions-bar-"+fid).toggle();
		});*/
		
		/*$('.actions-bar .option-item').on('click',function(e){
			e.preventDefault();
			var fid=$(this).data('feedback');
			$.ajax({
				type:'POST',
				url:"<?php echo site_url('ajax/delete_feedback'); ?>",
				data: {fid:fid},
			}).done(function(data){
				
				$("#post"+fid).remove();
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			}).fail(function(data){
				console.log(data);
			}).always(function(data){
				
			});
			
		});*/
		
		$('.more').each(function() {
			var content = $(this).html();
	 
			if(content.length > showChar) {
	 
				var c = content.substr(0, showChar);
				var h = content.substr(showChar, content.length - showChar);
	 
				var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
	 
				$(this).html(html);
			}
	 
		});
	 
		$(".morelink").off("click").on("click",function(e){
			if($(this).hasClass("less")) {
				$(this).removeClass("less");
				$(this).html(moretext);
			} else {
				$(this).addClass("less");
				$(this).html(lesstext);
			}
			$(this).parent().prev().toggle();
			$(this).prev().toggle();
			return false;
		});
		
		// Reply Dialog
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
			console.log(form[0].action);
			console.log(new FormData(form[0]));
			$.ajax({
				dataType: 'json',
				type:'POST',
				url: form[0].action,
				data: new FormData(form[0]),
				processData: false,
				contentType: false
			}).done(function(data){
				console.log(data);
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			}).fail(function(data){
				console.log(data);
			}).always(function(data){
				console.log(data);
			});
			
			//dialog.dialog( "close" );
			return false;
		});
		
		$('.post-comment-button').off("click").on("click",function(e){
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

	$('.follow-button').off("click").on("click",function(e){
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
	
	$('.post-like-button').off("click").on("click",function(e){
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
<script>
	$(document).ready(function() {
		 $('.sharetastic').sharetastic(); 
		 $(document).mouseup(function(e) {
			var container = $(".social-icons-container");
			if (!container.is(e.target) && container.has(e.target).length === 0) 
			{
				container.hide();
			}
		});
		$('.social-sharing a').click(function(e){
				e.preventDefault();
				var $iconwraper=$(this).find('.social-icons-container');
				$iconwraper.hide();
				//$iconwraper.css({'top':e.pageY-50,'left':e.pageX-260, 'position':'absolute', 'padding':'5px'});
				$iconwraper.css({'bottom':'10px','right':'0', 'position':'absolute', 'padding':'5px'});
				$iconwraper.show();
		});
	});
	</script>