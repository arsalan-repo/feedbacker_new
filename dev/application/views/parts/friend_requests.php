<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="friend-requests-list">
<?php foreach($friend_requests as $row): ?>
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
			<button type="button" data-friend="<?=$row['friend_id']; ?>" data-user="<?=$row['user_id']; ?>"   class="btn btn-blue accept_request">Confirm</button>
			<button type="button" data-friend="<?=$row['friend_id']; ?>" id="delete_request<?=$row['friend_id']; ?>" data-user="<?=$row['user_id']; ?>" class="btn btn-default delete_request">Delete Request</button>
		</div>
	</div>
<?php endforeach; ?>
</div>
<script>
$(".accept_request").on('click',function(e){	
	e.preventDefault();
	var $element=$(this);
	var fid=$(this).data('friend');
	$.ajax({
		dataType: 'json',
		type:'POST',
		url: '<?php echo site_url('user/accept_friend_request'); ?>',
		data:{fid:fid}
	}).done(function(data){			
		if(data.success){			
			//$("#friend-id-"+fid).remove();
			$element.removeClass('btn-blue');
			$element.addClass('btn-default');
			$element.text('Request Accepted.');
			$("#delete_request"+fid).hide();
		}		
	});
});
$(".delete_request").on('click',function(e){	
	e.preventDefault();
	var $element=$(this);
	var fid=$(this).data('friend');
	$.ajax({
		dataType: 'json',
		type:'POST',
		url: '<?php echo site_url('user/delete_friend_request'); ?>',
		data:{fid:fid}
	}).done(function(data){			
		if(data.success){			
			$("#friend-id-"+fid).remove();
		}		
	});
});
</script>