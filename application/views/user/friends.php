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
          <div class="home-left-profile">
              <div class="home-left-section">
                  <div class="home-left-profile-block">
            	<span class="home-left-profile-thumb">
					<a href="<?php echo site_url('user/profile'); ?>">
					<?php
                    if(isset($user_info['photo'])) {
                        echo '<img src="'.S3_CDN . 'uploads/user/thumbs/' . $user_info['photo'].'" alt="" />';
                    } else {
                        echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
                    }
                    ?>
					</a>
                </span>
                      <span class="home-left-profile-name"><?php echo $user_info['name']; ?></span>
                      <span class="home-left-profile-designation">
				<?php
                $getcountry = $this->common->select_data_by_id('users', 'id', $user_info['id'], 'country', '');
                echo $this->common->getCountries($getcountry[0]['country']); ?></span> </div>
              </div>
          </div>
        <div class="home-left-text-block">
          <h2><span><?php echo $this->lang->line('trends'); ?></span><!-- Change--></h2>
          <?php foreach($trends as $row) {
              echo '<h3><a href="'.site_url('post/title').'/'.$row['title_id'].'">'.$row['title'].'</a></h3>';
              echo '<p>'.$this->common->limitText(str_replace("\\n","<br>",str_replace("\\r\\n","<br>",$this->emoji->Decode(nl2br($row['feedback_cont'])))), 20).'</p>';
          } ?>
        </div>
      </div>
      <div class="middle-content">	
		<div class="middle-content-block" id="friends-wrapper">
			<div class="post-profile-block">
				<div id="find-friends">
					<h2>Find Friends</h2>
					<form method="get" action="" id="find-friends-from">
						<input type="text" placeHolder="Enter name" name="q" id="usersAutocompelte" value="<?=$q; ?>">
						<button type="submit" class="btn btn-blue">Find Friends</button>
					</form>
					
						<div id="users-list">
							<div class="friend-requests-list" id="user-search-list">
							<?php  if(!empty($is_search)): 					
								if (!empty($usersList)) {
								$this->load->view('parts/find_friends_list', $usersList);
								}else{
								  echo '<p>No Match found.</p>';
								}
							 endif; ?>
						  </div>
						</div>
					
				</div>
				<div id="tabs" class="friends-data">
				  <ul>
					<li><a href="#friend-requests">Friend Requests</a></li>
					<li><a href="#friends-list">Friends</a></li>
				  </ul>
				  <div id="friend-requests">
					<?php 
					  if (!empty($friend_requests)) {
						$this->load->view('parts/friend_requests', $friend_requests);
					  } ?>
				  </div>
				  <div id="friends-list">
					<?php 
					  if (!empty($friends)) {
						$this->load->view('parts/friends_list', $friends);
					  } ?>
				  </div>
				</div> 
			</div>	   
		  
        </div>
      </div>
      <div class="right-content">
        <h3><?php echo $this->lang->line('what_tofollow'); ?> <!--<a href="#">View All</a>--></h3>
		<?php if (!empty($to_follow)) { ?>
			<?php foreach($to_follow as $row) { ?>
			<div class="who-follow-block">
				<span>
					<?php
					if (isset($row['user_avatar'])) {
						echo '<img src="'.$row['user_avatar'].'" alt="" />';
					} else {
						echo '<img src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
					}
					?>
				</span>
				<div class="who-follow-text">
					<span><?php echo $row['title']; ?></span> <?php echo $row['name']; ?>
				</div>
				<div class="who-follow-add" id="who-follow-<?php echo $row['feedback_id']; ?>"> <?php echo $this->lang->line('follow'); ?> <i class="fa fa-plus" aria-hidden="true"></i></div>
				<input type="hidden" id="title_id" value="<?php echo $row['title_id']; ?>" />
			</div>
			<?php } ?>
		<?php } ?>
      </div>
    </div>
    <div class="ajax-load text-center" style="display:none">
        <p><img src="<?php echo ASSETS_URL . 'images/loader.gif'; ?>">Loading</p>
    </div>
    
    <script type="text/javascript">		
		function sendFriendRequest(uid){			
			$.ajax({
				dataType: 'json',
				type:'POST',
				url: '<?php echo site_url('user/send_friend_request'); ?>',
				data:{uid:uid}
			}).done(function(data){			
				if(data.success){			
					$("#reqbtn-"+uid).removeClass('btn-blue');
					$("#reqbtn-"+uid).addClass('btn-default');
					$("#reqbtn-"+uid).text('Requeset sent...');
				}
				
			});
		}
		$(function() {
			$( "#tabs" ).tabs({
			  beforeLoad: function( event, ui ) {
				ui.jqXHR.fail(function() {
				  ui.panel.html(
					"Unable to load friends list." );
				});
			  }
			});
			
		
		$("#usersAutocompelte").autocomplete({
							minLength: 2,							
							source: function( request, response ) {
							  $.getJSON( "<?php echo site_url('user/find_friends'); ?>", {
								term: request.term
							  }, response );
							  
							},
							focus: function( event, ui ) {
								return false;
							},
							select: function( event, ui ) {	
								console.log(ui.item);
							  this.value=ui.item.name;
							  var img='https://feedbacker.me/dev/assets/images/user-avatar.png';
								if(ui.item.photo){
									img='https://d1f8jwm5uy46l.cloudfront.net/uploads/user/thumbs/'+ui.item.photo;
								}
								$( "<div/>" ).html( '<div class="post-profile-block friend-request" id="user-id-'+ui.item.user_id+'"><div class="post-img"><img src="'+img+'" alt=""></div><div class="post-profile-content"> <span class="post-designation"><a href="#">'+ui.item.name+'</a>	</span>			<span class="post-name">Jordan</span> 		</div>		<div class="post-buttons">			<button type="button" data-user="'+ui.item.user_id+'" id="reqbtn-'+ui.item.user_id+'" class="btn btn-blue send_friend_request" onclick="sendFriendRequest('+ui.item.user_id+')">Send Friend Request</button></div></div>' ).prependTo("#user-search-list");
							 // $("#find-friends-from").submit();
							  return false;
								
							}
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
			var img='https://feedbacker.me/dev/assets/images/user-avatar.png';
			if(item.photo){
				img='https://d1f8jwm5uy46l.cloudfront.net/uploads/user/thumbs/'+item.photo;
			}
			return $( "<li>" ).append( "<div><img style='width:40px; border-radius:50%; display:inline-block; float:left; margin-right:10px;' src='"+img+"'><span style='display:inline-block; line-height:40px; '>" + item.name + "</span></div>" ).appendTo( ul );
		};
		
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
	
        var page = 1;     
    
       
		
		$('.who-follow-add').off("click").on("click",function(e){
			e.preventDefault();
			
			var element = $(this).attr('id');
			var title_id = $(this).parent().find('#title_id').val();
		
			$.ajax({
				dataType: 'json',
				type:'POST',
				url: '<?php echo site_url('title/follow'); ?>',
				data:{title_id:title_id}
			}).done(function(data){
				// console.log(data);
				if (data.is_followed == 1) {
					$('#'+element).parent().remove();
					toastr.success(data.message, 'Success Alert', {timeOut: 5000});
				}
			});
		});
    </script>
</div>
<!-- /.content-wrapper -->
