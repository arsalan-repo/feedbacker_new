<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 

foreach($feedbacks as $row) { ?>
  <div class="post-profile-block">
   <div class="post-right-arrow">
    <?php if(!empty($this->session->userdata['mec_user']) && $row['user_id']==$this->session->userdata['mec_user']): ?>
	<a href="#" class="post-options-btn" rel="toggle" data-feedback="<?=$row['feedback_id']; ?>" data-title="<?=$row['title_id']; ?>"><span></span></a>
   <?php endif; ?>
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
			  <img src="<?=$img['feedback_thumb']; ?>" />
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
            <span class="post-wishlist" id="post-wishlist-<?php echo $row['id']; ?>">
				<?php if ($row['is_liked']) { ?>
					<i class="fa fa-heart" aria-hidden="true" title="<?php echo $this->lang->line('unlike'); ?>"></i> 
				<?php } else { ?>
					<i class="fa fa-heart-o" aria-hidden="true" title="<?php echo $this->lang->line('like'); ?>"></i>
				<?php } ?> 
				<?php echo $row['likes']; ?>
            </span>
			<span class="social-sharing">				
				<a href="#">
					<div class="social-icons-container">
						<div class="sharetastic" data-url="<?=site_url('post/detail/'.$row['id']); ?>" data-title="<?=$row['title']; ?>" data-description=" "></div>	
						<?php //echo str_replace("\\r\\n","\r\n",$this->emoji->Decode($row['feedback'])); ?>
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

