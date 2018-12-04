<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<!-- Content Wrapper. Contains page content -->
<?php if ($this->session->flashdata('error')) { ?>  
	<div class="div-toastr-error">
		<?php echo $this->session->flashdata('error'); ?>
	</div>
<?php } ?>
<div class="content-secion">
    <div class="container creatae-post-content ui-widget">
	<?php
		$attributes = array('id' => 'create-post-form', 'enctype' => 'multipart/form-data');
		echo form_open_multipart('post/edit/'.$feedback_detail[0]['feedback_id'], $attributes);
		?>
		 <input type="hidden" name="feedback_id" id="feedback_id" value="<?php echo $feedback_detail[0]['feedback_id'] ?>">
		<h2><?php echo $section_title; ?></h2>
		<label><?php echo $this->lang->line('your_feedback'); ?></label>
		<textarea name="feedback_cont" id="feedback_cont" placeholder="" rows="10" required>
			<?php echo str_replace("\\n","\r\n",str_replace("\\r\\n","\r\n",$this->emoji->Decode($feedback_detail[0]['feedback_cont']))); ?>
		</textarea>
		<div class="post-btn-block">
			<div class="camera-map-icon">
				<div class="camera-icon-block">
					<span>Choose File</span>
					<input name="feedback_img[]" multiple id="feedback_img" type="file" />
				</div>
				<img src="<?php echo base_url().'assets/images/map-icon.png'; ?>" class="geo-map" alt="" />
			</div>
			
		</div>
		<div id="preview-wrapper"></div>
		<div class="feedback-images" style="clear:both; overflow:hidden;">
		<?php 
		if(isset($feedback_detail[0]['images']) && count($feedback_detail[0]['images'])>0){
			foreach($feedback_detail[0]['images'] as $img){
				
				?>
				<div class="form-group" style="width:16%; float:left; margin:4px;" id="image<?=$img['image_id']; ?>">				 
					 <a href="<?php echo S3_CDN . 'uploads/feedback/main/' . $img['feedback_img'] ?>" target="_blank">
					   <img src="<?php echo S3_CDN . 'uploads/feedback/thumbs/' . $img['feedback_thumb'] ?>" alt="<?php echo $img['feedback_thumb']; ?>" width="180">
						</a> 
						 <a class="ajax_delete" data-image_id="<?=$img['image_id']; ?>" data-feedback_id="<?php echo $feedback_detail[0]['feedback_id'] ?>" href="<?php echo base_url('post/remove_feedback_photo/' . $img['image_id']) ?>" title="Remove Photo" style="margin: 0 auto; width: 120px;" >Remove Photo</a>
				</div>
				<?php
			}
		}          
     ?>
	 </div>
		  <?php if($feedback_detail[0]['feedback_video']) { ?>
                        <div class="form-group col-sm-10">
                            <label for="feedback_thumb" id="page_title"></label>
                            <a href="<?php echo S3_CDN . 'uploads/feedback/video/' . $feedback_detail[0]['feedback_video'] ?>" target="_blank">
                                <img src="<?php echo S3_CDN . 'uploads/feedback/thumbs/' . $feedback_detail[0]['feedback_thumb'] ?>" alt="<?php echo $feedback_detail[0]['feedback_thumb'] ?>" width="180">
                            </a>
                            <input type="hidden" name="old_image" value="<?php echo $feedback_detail[0]['feedback_thumb']; ?>" />
                            <a href="<?php echo base_url('admin/feedbacks/remove_video/' . $feedback_detail[0]['feedback_id']) ?>" title="Remove Video" style="margin: 0 auto; width: 120px;" >Remove Video</a>
                        </div>
                        <?php } ?>
		    <div class="post-btn-block">			
				<button type="submit" class="post-btn">Update</button>				 
			</div>   
		<?php echo form_close(); ?>		
    </div>
</div>
<!-- /.content-wrapper -->
<!-- jQuery Form Validation code -->
<script type="application/javascript">
// When the browser is ready...
$(function() {
	if ($.trim($(".div-toastr-error").html()).length > 0) {
		$(".div-toastr-error p").each(function( index ) {
			toastr.error($(this).html(), 'Failure Alert', {timeOut: 5000});
		});
	}
	$('.ajax_delete').click(function(e){		
		var feedback_id=$('#feedback_id').val();
		var image_id=$(this).data('image_id');
		var action=$(this).attr('href');
		console.log(feedback_id);
		console.log(image_id);
		$.ajax({
		  method: "POST",
		  url: action,
		  dataType:'json',
		  data: { feedback_id: feedback_id, image_id: image_id }
		})
		  .done(function( data ) {
			if(data.success){
				$('#image'+image_id).remove();	
			}
		  });
		e.preventDefault();
		
		
	});
	$("#feedback_img").change(function(){
		imagePreview(this);
	});	
});

function imagePreview(input) {
	var files = input.files; // FileList object
	$("#preview-wrapper").html('');
     $.each(input.files, function(i, file) {
            var img = document.createElement("img");
            img.id = "image"+(i+1);
			img.style.cssText="width:200px; height:200px; margin:5px;";
            var reader = new FileReader();
            reader.onloadend = function () {
                img.src = reader.result;
            }
            reader.readAsDataURL(file);
            $("#image"+i).after(img);
			$("#preview-wrapper").append(img);
			
      });
}
</script>
