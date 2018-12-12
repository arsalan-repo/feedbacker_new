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
        <div class="middle-content-block ">
            <?php $this->load->view('parts/create-title-widget'); ?>
          <?php  if (!empty($titles)) { ?>
<!--			<div class="search-form post-profile-block">-->
<!--				<form role="form" method="post" action="">-->
<!--					<h3>--><?php //echo $this->lang->line('search_title'); ?><!--</h3>-->
<!--					<div class="form-group">-->
<!--						<input type="text" placeholder="--><?php //echo $this->lang->line('type_in_to_search'); ?><!--" name="s" id="s" required="true" class="ui-autocomplete-input" autocomplete="off" aria-required="true" value="--><?//=$s; ?><!--">-->
<!--						<input type="submit" value="--><?php //echo $this->lang->line('search'); ?><!--" name="search" style="width:160px; float:right;">		-->
<!--					</div>-->
<!--				</form>-->
<!--			</div>-->
<!--			--><?php //if($is_search): ?>
<!--				<div class="search-form post-profile-block">-->
<!--					<h2 style="padding:15px 0;">Displaying search results for "<span style="color:#ea9707;">--><?//=$s; ?><!--</span>"</h2>-->
<!--				</div>-->
<!--			--><?php //endif; ?>
			<div class="titles-wrapper" id="post-data" style="width:100%; float:left;">
			<?php foreach($titles as $title):  ?>
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
				   <span class="post-followers-text"><?=$title['linked_users_count']; ?> <?php echo $this->lang->line('followers'); ?>	</span>
				   <span class="post-profile-time-text"><?=$title['feedbacks_count']; ?> <?php echo $this->lang->line('feedbacks'); ?>	</span>	
					<?php if($title['is_title_owner']): ?>
						<a class="delete_title" data-title="<?=$title['title_id']; ?>" style="color:red; font-size:14px;" href="#"><i class="fa fa-times" aria-hidden="true"></i> <?php echo $this->lang->line('delete'); ?></a>	
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
			</div>			
          <?php }else{ ?>
		  <div class="profile-listing">
		  No Data Available
		  </div>
		  <?php } ?>
        </div>
      </div>
      <div class="right-content">
<!--		<div class="header-create-encrypted text-center" style="width:100%; margin:20px auto;">-->
<!--			<a href="--><?php //echo site_url('encrypted/create'); ?><!--">--><?php //echo $this->lang->line('create_encrypted'); ?><!--</a>-->
<!--		</div> -->
          <?php  if (!empty($titles)) { ?>
              <div class="search-form post-profile-block">
                  <form role="form" method="post" action="">
                      <h3><?php echo $this->lang->line('search_title'); ?></h3>
                      <div class="form-group">
                          <input type="text" placeholder="<?php echo $this->lang->line('type_in_to_search'); ?>" name="s" id="s" required="true" class="ui-autocomplete-input" autocomplete="off" aria-required="true" value="<?=$s; ?>">
                          <input type="submit" value="<?php echo $this->lang->line('search'); ?>" name="search" style="width:100%; float:right;">
                      </div>
                  </form>
              </div>
              <?php if($is_search): ?>
                  <div class="search-form post-profile-block">
                      <h2 style="padding:15px 0;">Displaying search results for "<span style="color:#ea9707;"><?=$s; ?></span>"</h2>
                  </div>
              <?php endif; ?>
              <div class="titles-wrapper" id="post-data" style="width:100%; float:left;">
                  <?php foreach($titles as $title):  ?>
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
                              <div class="post-profile-content" style="min-height:40px; width: 100%">
                                  <span class="post-designation"><a href="<?=site_url('encrypted/'); ?><?=$title['title_id']; ?>"><?=$title['title']; ?></a></span>
                                  <span class="post-name"><?=$title['name']; ?></span>
                                  <span class="post-address"><span class="post-profile-time-text"><?=$title['time']; ?></span></span>
                              </div>
                          </div>
                      <?php endif; ?>
                  <?php endforeach; ?>
              </div>
          <?php }else{ ?>
              <div class="profile-listing">
                  No Data Available
              </div>
          <?php } ?>
      </div>
    </div>
<!--    <div class="ajax-load text-center" style="display:none">-->
<!--        <p><img src="--><?php //echo ASSETS_URL . 'images/loader.gif'; ?><!--">Loading</p>-->
<!--    </div>-->
    
    <script type="text/javascript">
		// When the browser is ready...
		var current_url="<?=site_url('user/encrypted_titles');?>";
		$("#dashboard_url").val(current_url);
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
			var dialog;
			var gDialog;
			dialog = $("#confirm").dialog({
				autoOpen: false,
				modal: true,
				resizable: false,
				 buttons: {
				"<?php echo $this->lang->line('confirm'); ?>": function() {
					$("#delete-title-form").submit();
				  $( this ).dialog( "close" );
				},
				"<?php echo $this->lang->line('cancel'); ?>": function() {
				  $( this ).dialog( "close" );
				}
			  }
			});
			gDialog = $("#confirmJoin").dialog({
				autoOpen: false,
				modal: true,
				resizable: false,
				 buttons: {
				"<?php echo $this->lang->line('confirm'); ?>": function() {
					$("#join-title-form").submit();
				  $( this ).dialog( "close" );
				},
				"<?php echo $this->lang->line('cancel'); ?>": function() {
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
			$('.join_title').off("click").on("click",function(e){
					e.preventDefault();
					var title_id=$(this).data("title");
					gDialog.find( "#title_id" ).val(title_id);
					gDialog.dialog( "open" );
					
			});
		});
	
        var page = 1;
        $(window).scroll(function() {
			
			 var hT = $('#post-data').offset().top,
				hH = $('#post-data').outerHeight(),
				wH = $(window).height(),
				wS = $(this).scrollTop();
				if (wS > (hT+hH-wH)){
				   console.log('H1 on the view!');
				   page++;
					loadMoreData(page);
			   }
			/*console.log("scrollTop"+ $(window).scrollTop());
			console.log("height"+ $(window).height());
			console.log("document-height"+ $(document).height());
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                loadMoreData(page);
            }*/
        });
		var paging_has_data=true;
        function loadMoreData(page){
			if(paging_has_data){
				$.ajax(
					{
						url: '?page=' + page,
						type: "get",
						beforeSend: function()
						{
							$('.ajax-load').show();
						}
					})
					.done(function(data)
					{
						console.log(data);
						console.log(paging_has_data);
						if(data == ""){
							paging_has_data=false;
							$('.ajax-load').html("No more records found");
							return;
						}
					   // $('.ajax-load').hide();
						$("#post-data").append(data);
					})
					.fail(function(jqXHR, ajaxOptions, thrownError)
					{
						  console.log('server not responding...');
					});
			}
        }
		
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
<div id="confirm" class="modal hide fade" title="<?php echo $this->lang->line('confirm_delete'); ?>">
	 <form id="delete-title-form" action="<?=site_url('encrypted/delete_title'); ?>" method="post" >
	 <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
		   <?php echo $this->lang->line('confirm_remove_title_msg'); ?></p>
	 <input type="hidden" id="title_id" name="title_id" value="">	
	 <input type="hidden" name="slug" value="<?=$title['slug']; ?>">	 
	 </form> 	  
</div>
<div id="confirmJoin" class="modal hide fade" title="<?php echo $this->lang->line('confirm'); ?>">
	 <form id="join-title-form" action="<?=site_url('encrypted/send_join_request'); ?>" method="post" >
	 <p><span class="ui-icon ui-icon-notice" style="float:left; margin:12px 12px 20px 0;"></span>
		   <?php echo $this->lang->line('confirm_join_title_request_msg'); ?></p>
	 <input type="hidden" id="title_id" name="title_id" value="">	
	 <input type="hidden" name="slug" value="<?=$title['slug']; ?>">	 
	 </form> 	  
</div>
<!-- /.content-wrapper -->
