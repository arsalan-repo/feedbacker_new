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
              echo '<p>'.$this->common->limitText(str_replace("\\n","<br>",str_replace("\\r\\n","<br>",$this->emoji->Decode($row['feedback_cont']))), 20).'</p>';
          } ?>
        </div>
      </div>
      <div class="middle-content">
		<?php  $this->load->view('parts/create-post-widget', $this->data); ?>
        <div class="middle-content-block" id="post-data">
		
		<?php 
		  if (!empty($feedbacks)) {
			$this->load->view('post/ajax', $feedbacks);
		  } else { ?>
			<div class="search-result-page"> <img src="<?php echo base_url().'assets/images/serch-result-img.png'; ?>" alt="" />
				<h3><?php echo $this->lang->line('no_results'); ?></h3>
				<p>“<?php echo $qs; ?>” not found! <br />
				<?php echo $this->lang->line('tap_to_create'); ?></p>
				<span class="normal-btn"><?php echo $this->lang->line('create'); ?></span> 
			</div>
		<?php } ?>
        </div>
      </div>
      <div class="right-content">
        <h3><?php echo $this->lang->line('what_tofollow'); ?> <!--<a href="#">View All</a>--></h3>
        <?php foreach($to_follow as $row) { ?>
        <div class="who-follow-block">
        	<span>
            	<?php
				if(isset($row['user_avatar'])) {
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
			<input type="hidden" id="user_id" value="<?php if(!empty($user_info['id'])) echo $user_info['id']; else echo '0'; ?>" />
        </div>
        <?php } ?>
      </div>
    </div>
</div>
<!-- /.content-wrapper -->
<script type="application/javascript">
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

	var page = 1;
	$(window).scroll(function() {
		if($(window).scrollTop() + $(window).height() >= $(document).height()) {
			page++;
			
			var qs = GetQueryStringParams('qs');
			loadMoreData(page, qs);
		}
	});

	function loadMoreData(page, qs){
		  if( typeof(qs) == 'undefined' ){
			  qs='';
		  }
	  $.ajax(
			{
				url: '/search?qs=' + qs + '&page=' + page,
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
	
	function GetQueryStringParams(sParam){
		var sPageURL = window.location.search.substring(1);
		var sURLVariables = sPageURL.split('&');
		
		for (var i = 0; i < sURLVariables.length; i++)
		{
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam)
			{
				return sParameterName[1];
			}
		}
	}

	$('.who-follow-add').off("click").on("click",function(e){
		e.preventDefault();
		
		var element = $(this).attr('id');
		var title_id = $(this).parent().find('#title_id').val();
		var user_id = $(this).parent().find('#user_id').val();		
	
		$.ajax({
			dataType: 'json',
			type:'POST',
			url: '<?php echo site_url('title/follow'); ?>',
			data:{title_id:title_id, user_id:user_id}
		}).done(function(data){
			// console.log(data);
			if (data.is_followed == 1) {
				$('#'+element).parent().remove();
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			}
		});
	});
	
	$('.post-wishlist').off("click").on("click",function(e){
		e.preventDefault();
		
		var element = $(this).attr('id');
		var totl_likes = $(this).parent().find('#totl_likes').val();
		var feedback_id = $(this).parent().find('#feedback_id').val();
		var user_id = $(this).parent().find('#user_id').val();		
	
		$.ajax({
			dataType: 'json',
			type:'POST',
			url: '<?php echo site_url('post/like'); ?>',
			data:{feedback_id:feedback_id, user_id:user_id, totl_likes:totl_likes}
		}).done(function(data){
			// console.log(data);
			if (data.is_liked == 1) {
				var totl = parseInt(data.likes) + 1;	
				$('#'+element).parent().find('#totl_likes').val(totl);
							
				$('#'+element).html('<i class="fa fa-heart" aria-hidden="true"></i><span class="total-likes"> '+totl+'</span>');
				toastr.success(data.message, 'Success Alert', {timeOut: 5000});
			}
			else
			{
				var totl = parseInt(data.likes) - 1;
				$('#'+element).parent().find('#totl_likes').val(totl);
				
				$('#'+element).html('<i class="fa fa-heart-o" aria-hidden="true"></i><span class="total-likes"> '+totl+'</span>');
				toastr.warning(data.message, 'Success Alert', {timeOut: 5000});
			}
		});
	});
	/*var dialog = $( "#create-post-modal" ).dialog({
      autoOpen: true,
	  appendTo: "#create-post-block",
      height: 200,   
	width:$("#create-post-block").width(),
      modal: false,      
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });*/
</script>
