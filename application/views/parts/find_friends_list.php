<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php foreach($usersList as $row): ?>
	<div class="post-profile-block friend-request" id="user-id-<?=$row['user_id']; ?>">
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
			<button type="button"  data-user="<?=$row['user_id']; ?>" class="btn btn-blue send_friend_request">Send Friend Request</button>
		</div>
	</div>
<?php endforeach; ?>

<script>
$(".send_friend_request").on('click',function(e){	
	e.preventDefault();
	var $element=$(this);
	var uid=$(this).data('user');
	$.ajax({
		dataType: 'json',
		type:'POST',
		url: '<?php echo site_url('user/send_friend_request'); ?>',
		data:{uid:uid}
	}).done(function(data){			
		if(data.success){			
			$element.removeClass('btn-blue');
			$element.addClass('btn-default');
			$element.text('Requeset sent...');
		}
		/*if (data.is_followed == 1) {
			$('#'+element).parent().remove();
			toastr.success(data.message, 'Success Alert', {timeOut: 5000});
		}*/
	});
});
</script>