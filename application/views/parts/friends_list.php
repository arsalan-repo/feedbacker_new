<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="friend-requests-list">
<?php foreach($friends as $row): ?>
	<div class="post-profile-block friend-request" id="friend-id-<?=$row['friend_id']; ?>">
		<div class="post-img">			
			<?php
			if(isset($row['photo'])) {
                echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $row['photo'].'" alt="" />';
            } else {
                echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
			}
			?>			
		</div>
		<div class="post-profile-content"> 
			<span class="post-designation">
				<a href="#"><?php echo $row['name']; ?></a>
			</span> 
			<span class="post-name"><?php echo $this->common->getCountries($row['country']); ?></span> 
		</div>
		<div class="post-buttons">		
			<button type="button" data-friend="<?=$row['friend_id']; ?>" data-user="<?=$row['user_id']; ?>" class="btn btn-default unfriend">Unfriend</button>
		</div>
	</div>
<?php endforeach; ?>
</div>
 <script type="text/javascript">
 $(function() {
	 $(".unfriend").on('click',function(e){	
		e.preventDefault();
		var $element=$(this);
		var fid=$(this).data('friend');
		$.ajax({
			dataType: 'json',
			type:'POST',
			url: '<?php echo site_url('user/unfriend'); ?>',
			data:{fid:fid}
		}).done(function(data){
			
			if(data.success){	
				$("#friend-id-"+fid).remove()	
				$element.removeClass('btn-blue');
				$element.addClass('btn-default');
				$element.text('Removed from friend list.');
			}
			/*if (data.is_followed == 1) {
				$('#'+element).parent().remove();
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			}*/
		});
	});
 });
 </script>