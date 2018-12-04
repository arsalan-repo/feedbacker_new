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
      <div class="middle-content">
		<?php  $this->load->view('parts/create-post-widget', $this->data); ?>
        <div class="middle-content-block" id="post-data">
          <?php 
		  if (!empty($feedbacks)) {
		  	$this->load->view('post/ajax', $feedbacks);
          } else {
          	echo $no_record_found;
          } ?>
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
		// When the browser is ready...
		function toggleActions(fid){
			console.log(fid+"actions");	
			$(".actions-bar:not(#actions-bar-"+fid+")").hide();
			$("#actions-bar-"+fid).toggle();
			
			return false;
		}
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
		
	/*$('.post-options-btn').on('click',function(e){
			e.preventDefault();
			var fid=$(this).data('feedback');
			$("#actions-bar-"+fid).toggle();
		});
		$('.actions-bar .option-item').on('click',function(e){
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
        var page = 1;
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() >= $(document).height()) {
                page++;
                loadMoreData(page);
            }
        });
    
        function loadMoreData(page){
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
                    if(data == " "){
                        $('.ajax-load').html("No more records found");
                        return;
                    }
                    $('.ajax-load').hide();
                    $("#post-data").append(data);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError)
                {
                      alert('server not responding...');
                });
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
<!-- /.content-wrapper -->
