<?php if(isset($user_info['name'])): ?>
    <div class="home-left-profile">
        <div class="home-left-section">
            <div class="home-left-profile-block"> 
            	<span class="home-left-profile-thumb">
					<?php 
                    if(isset($user_info['photo'])) {
                        echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $user_info['photo'].'" alt="" />';
                    } else {
                        echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
                    }
                    ?>
                </span> 
                <span class="home-left-profile-name"><?php echo $user_info['name']; ?></span> 
                <span class="home-left-profile-designation">
				<?php
				$getcountry = $this->common->select_data_by_id('users', 'id', $user_info['id'], 'country', '');
				echo $this->common->getCountries($getcountry[0]['country']); 
				
				?>
				</span> 
			</div>
			<div class="profile-card-stats">
				<ul class="profile-card-stats-list">
					<li>
						<a class="profile-card-stats-stat-link " href="<?=site_url('user/feedbacks'); ?>">
							<span class="profile-card-stats-stat-label u-block">Posts</span>
							<span class="profile-card-stats-stat-value feedbacks_count" id="feedbacks_count"><?=$this->common->user_feedbacks_count($user_info['id']); ?></span>
						</a>
					</li>
					<li>
						<a class="profile-card-stats-stat-link " href="<?=site_url('user/followings'); ?>">
							<span class="profile-card-stats-stat-label u-block">Followings</span>
							<span class="profile-card-stats-stat-value followings_count" id="followings_count"><?=$this->common->user_following_count($user_info['id']); ?></span>
						</a>
					</li>					
					<li>
						<a class="profile-card-stats-stat-link " href="<?=site_url('user/friends'); ?>">
							<span class="profile-card-stats-stat-label u-block">Friends
							<span id="frequests-count" class="frequests-count notification-count"></span>
							</span>
							<span class="profile-card-stats-stat-value friends_count" id="friends_count"><?=$this->common->user_friends_count($user_info['id']); ?></span>
						</a>
					</li>
				</ul>				
			</div>
        </div>
   </div>
<?php endif; ?>