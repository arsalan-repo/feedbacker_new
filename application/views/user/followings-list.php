<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- Content Wrapper. Contains page content -->
<?php if ($this->session->flashdata('error')) { ?>  
	<div class="div-toastr-error">
		<?php echo $this->session->flashdata('error'); ?>
	</div>
<?php } ?>
<?php if ($this->session->flashdata('success')) { ?>  
	<div class="div-toastr-success">
		<?php echo $this->session->flashdata('success'); ?>
	</div>
<?php } ?>
<div class="content-secion">
    <div class="container">
      <div class="left-content">
      <?php $this->load->view('parts/user_profile_card', $this->data); ?>
        <div class="home-left-text-block">
          <h2><span><?php echo $this->lang->line('trends'); ?></span><!-- Change--></h2>
          <?php foreach($trends as $row) {
              echo '<h3><a href="'.site_url('post/title').'/'.$row['title_id'].'">'.$row['title'].'</a></h3>';
              echo '<p>'.$this->common->limitText(str_replace("\\n","<br>",str_replace("\\r\\n","<br>",$this->emoji->Decode(nl2br($row['feedback_cont'])))), 20).'</p>';
          } ?>
        </div>
      </div>
      <div class="middle-content" style="width:calc(100% - 260px);">		
        <div class="middle-content-block" id="post-data">
		<div class="profile-listing-block">
         <div class="container">
			  <ul class="followings-items">
			  <?php if (!empty($followings)) { ?>
			  <!-- Loop Starts Here -->
			  <?php foreach($followings as $row) { ?>
				<li>
				  <div class="profile-listing">
					<div class="home-left-profile">
						<div class="home-left-section">
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
							  <span class="home-left-profile-name"><?php echo $row['name']; ?></span>
							  <span class="home-left-profile-designation">
							  <?=!empty($row['country'])? $this->common->getCountries($row['country']):'';	?></span>
								
							</div>
							<div class="listing-post-name-block"> 
								<span class="listing-post-name">
									<a href="<?php echo site_url('post/title').'/'.$row['title_id']; ?>"><?php echo $row['title']; ?></a>
								</span>
								
							</div>			
							<div class="stats-box">
								<span class="listing-post-followers"><?php echo $row['followers']; ?> Followers</span> 
								<span class="listing-post-profile-time"><?php echo $row['time']; ?></span>				
							</div>
							
							<div class="post-listing-follow-btn"> 
								
								<span class="follow-btn-default <?php if($row['is_followed']) echo 'unfollow-btn'; ?> follow-btn-<?php echo $row['title_id']; ?>">
									<?php if ($row['is_followed']) { ?>
										<?php echo $this->lang->line('unfollow'); ?>
									<?php } else { ?>    
										<?php echo $this->lang->line('follow'); ?> <i class="fa fa-plus" aria-hidden="true"></i>
									<?php } ?>
								</span>
								<input type="hidden" id="title_id" value="<?php echo $row['title_id']; ?>" />
							 </div>
					 </div>
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
			</div>
        </div>
      </div>    
    </div>
    <div class="ajax-load text-center" style="display:none">
        <p><img src="<?php echo ASSETS_URL . 'images/loader.gif'; ?>">Loading</p>
    </div>
    
    <script type="text/javascript">
		// When the browser is ready...
		$(function() {
			// jQuery Toastr
			if ($.trim($(".div-toastr-error").html()).length > 0) {
				$(".div-toastr-error p").each(function( index ) {
					toastr.error($(this).html(), 'Failure Alert', {timeOut: 5000});
				});
			}
			
			if ($.trim($(".div-toastr-success").html()).length > 0) {
				$(".div-toastr-success p").each(function( index ) {
					toastr.success($(this).html(), 'Success Alert', {timeOut: 5000});
				});
			}
		});
		
		$('.unfollow-btn').off("click").on("click",function(e){
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
    </script>
</div>
<!-- /.content-wrapper -->
