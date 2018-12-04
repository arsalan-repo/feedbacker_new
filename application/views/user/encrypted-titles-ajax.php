<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php foreach($titles as $title): ?>
	<?php if(!empty($title['ads'])):  //print_r($title);?>
			<div class="post-profile-block">
			   <div class="post-right-arrow">
				   <span class="post-profile-time-text">Promoted</span>
				  </div>
				<div class="post-img">
					<a href="" target="_blank"><img src="<?=$title['user_avatar']; ?>" alt=""> </a>
				</div>
				<div class="post-profile-content"> 
					<span class="post-designation">
						<a href="<?=$title['ads_url']; ?>"></a>
					</span> 
					<span class="post-name"><?=$title['name']; ?></span> 
					<span class="post-address"></span>
					<p><span class="more"><?=$title['feedback']; ?></span></p>
								<div class="post-large-img">
											<a href="<?=$title['ads_url']; ?>" target="_blank">
									<img src="<?=$title['feedback_img']; ?>" alt="" >
								</a>
									</div>
								</div>
			  </div>
			<?php else: ?>
			<div class="post-profile-block">
				<div class="post-right-arrow">			  
				   <span class="post-followers-text"><?=$title['linked_users_count']; ?>  <?php echo $this->lang->line('followers'); ?>	</span>
				   <span class="post-profile-time-text"><?=$title['feedbacks_count']; ?>  <?php echo $this->lang->line('feedbacks'); ?>	</span>
				   <?php if($title['is_title_owner']): ?>
						<a class="delete_title" data-title="<?=$title['title_id']; ?>" style="color:red; font-size:14px;" href="#"><i class="fa fa-times" aria-hidden="true"></i>  <?php echo $this->lang->line('delete'); ?></a>	
					<?php endif; ?>
					<?php if($title['is_public']==1 && $title['is_title_linked_user']==false && $title['is_request_pending']==false): ?>
						<a class="join_title" data-title="<?=$title['title_id']; ?>" style="color:blue; font-size:14px;" href="#"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo $this->lang->line('join'); ?></a>					
					<?php endif; ?>
					<?php if($title['is_public']==1 && $title['is_title_linked_user']==false && $title['is_request_pending']==true): ?>
						<a class="joined_title" data-title="<?=$title['title_id']; ?>" style="color:#7d7d88; font-size:14px;" href="#"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo $this->lang->line('pending_request'); ?></a>					
					<?php endif; ?>         
				</div>
				<div class="post-img" style="position:relative;">	
					<?php if($title['unread_count']>0): ?>
					<span class="notification-count"><?=$title['unread_count']; ?></span>
					<?php endif; ?>
					<?php
					if(isset($row['user_avatar'])) {
						echo '<img src="'.$row['user_avatar'].'" alt="" />';
					} else {
						echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
					}
					?>					
				</div>
				<div class="post-profile-content" style="min-height:40px;"> 
					<span class="post-designation"><a href="<?=site_url('encrypted/'); ?><?=$title['title_id']; ?>"><?=$title['title']; ?></a></span> 	
					<span class="post-name"><?=$title['name']; ?></span>
					<span class="post-address"><span class="post-profile-time-text"><?=$title['time']; ?></span></span>
				</div>
			  </div>
			  <?php endif; ?>
<?php endforeach; ?>
<?php if(!empty($titles) && count($titles)>0): ?>
 <script type="text/javascript">
 $(function() {
			
			var dialog;
			dialog = $("#confirm").dialog({
				autoOpen: false,
				modal: true,
				resizable: false,
				 buttons: {
				" <?php echo $this->lang->line('confirm'); ?>": function() {
					$("#delete-title-form").submit();
				  $( this ).dialog( "close" );
				},
				Cancel: function() {
				  $( this ).dialog( "close" );
				}
			  }
			});
			$('.delete_title').off("click").on("click",function(e){
					e.preventDefault();
					var title_id=$(this).data("title");
					dialog.find( "#title_id" ).val(title_id);
					dialog.dialog( "open" );
					
			});
		});
 </script>
<?php endif; ?>