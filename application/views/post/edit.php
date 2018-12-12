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
        <label>Location</label>
        <input type="text" name="location" id="location" placeholder="" />
        <label><?php echo $this->lang->line('your_feedback'); ?></label>
		<textarea name="feedback_cont" id="feedback_cont" placeholder="" rows="10" required>
			<?php echo str_replace("\\n","\r\n",str_replace("\\r\\n","\r\n",$this->emoji->Decode($feedback_detail[0]['feedback_cont']))); ?>
		</textarea>
        <label>Post Status</label>
        <select name="feedback_status">
            <option value="public">Public</option>
            <option value="friends">Friends</option>
            <option value="me">Only Me</option>
        </select>
        <br/>
        <br/>
        <br/>
        <div class="tagging">
            <label>
                Tag Friends
            </label>
            <br/>
            <br/>
            <ul class="select-friend-tag">
                <?php
                    if(!empty($friends_details)){
                        foreach ($friends_details as $v){
                ?>
                <li data-name="<?=$v['name']  ?>"><i class="fa fa-remove remove-friend"></i><?=$v['name']  ?><input type="hidden" name="tagged_friends[]" value="<?= $v['id'] ?>"></li>
                <?php }} ?>
            </ul>
            <input type="text" placeHolder="Enter name" name="q" id="usersAutocompelte" value="<?=$q; ?>">
            <br/>
            <br/>
        </div>
		<div class="post-btn-block">
			<div class="camera-map-icon">
				<div class="camera-icon-block">
					<span>Choose File</span>
					<input name="feedback_img[]" multiple id="feedback_img" type="file" />
				</div>
				<img src="<?php echo base_url().'assets/images/map-icon.png'; ?>" class="geo-map" alt="" />
			</div>
            <div class="tag-friends">
                <a href="#" class="tag-friends"><i class="fa fa-tags"></i> Tag Friends</a>
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
            <input type="hidden" name="latitude" id="latitude" value="<?= $feedback_detail[0]['latitude'] ?>" />
            <input type="hidden" name="longitude" id="longitude" value="<?= $feedback_detail[0]['longitude'] ?>" />
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
<script>
    $(document).ready(function () {
        $(".geo-map").click(function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showLocation);
            } else {
                toastr.error('Geolocation is not supported by this browser', 'Failure Alert', {timeOut: 5000});
            }
        });
    });
    var location_field_id = null;

    function showCurrentLocationField(lat, lng) {
        var $old_location = $(location_field_id != null ? location_field_id : "#location");
        var rand = Math.floor(Math.random() * 99999999);
        var rand_id = 'location-' + rand;
        var $location = $('<input type="text" name="location" id="' + rand_id + '" placeholder="Enter your location"/>');

        location_field_id = "#" + rand_id;

        $location.insertAfter($old_location);
        $old_location.remove();

        var location_picker_opt1 = {
            location: {
                latitude: lat,
                longitude: lng
            },
            inputBinding: {
                locationNameInput: jQuery('#' + rand_id),
                latitudeInput: jQuery('#latitude'),
                longitudeInput: jQuery('#longitude'),
            },
            enableAutocomplete: true,
        };

        console.log(location_picker_opt1);
        $("#" + rand_id).locationpicker(location_picker_opt1);
    }
    function showLocation(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        showCurrentLocationField(latitude, longitude);

        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('post/get_location'); ?>',
            data: 'latitude=' + latitude + '&longitude=' + longitude,
            success: function (response) {
                if (response) {
                    var objJSON = JSON.parse(response);
                    $('#location').val(objJSON.location);

                    $("#latitude").val(latitude);
                    $("#longitude").val(longitude);

                    $("#latitude").trigger('change');
                    $("#longitude").trigger('change');
                } else {
                    toastr.error('Error getting location. Try later!', 'Failure Alert', {timeOut: 5000});
                }
            }
        });
    }
    jQuery(function () {

        var location_picker_opt = {
            inputBinding: {
                locationNameInput: jQuery('#location'),
                latitudeInput: jQuery('#latitude'),
                longitudeInput: jQuery('#longitude'),
            },
            enableAutocomplete: true,
        };

        // $('#location').locationpicker(location_picker_opt);
        showCurrentLocationField(<?= $feedback_detail[0]['latitude'] ?>, <?= $feedback_detail[0]['longitude'] ?>);
    })
</script>
<script>
    jQuery(function ($) {
        <?php if(!empty($feedback_detail[0]['tagged_friends'])) {?>
        $('.select-friend-tag').show();
        <?php }else {?>
        $('.select-friend-tag').hide();
        <?php } ?>
        $('.tagging').hide();
        $('.tag-friends').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            $('.tagging').slideToggle();
        });
        var tagged_friends = <?= (!empty($feedback_detail[0]['tagged_friends']) ? $feedback_detail[0]['tagged_friends'] : '[]') ?>;
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

                if(jQuery.inArray(ui.item.name, tagged_friends) <= -1){
                    console.log(ui.item);
                    var $tags = $('.select-friend-tag');
                    $tags.show();
                    var $li = $('<li data-name="'+ui.item.name+'"><i class="fa fa-remove remove-friend"></i>'+ui.item.name+'<input type="hidden" name="tagged_friends[]" value="'+ui.item.user_id+'" /></li>');
                    $tags.append($li);
                    tagged_friends.push(ui.item.name);
                    setTimeout(function () {
                        $('#usersAutocompelte').val('');
                    }, 100);
                }else {
                    alert('Already exists');
                }

                this.value=ui.item.name;
                var img='https://feedbacker.me/dev/assets/images/user-avatar.png';
                if(ui.item.photo){
                    img='https://d1f8jwm5uy46l.cloudfront.net/uploads/user/thumbs/'+ui.item.photo;
                }
                $( "<div/>" ).html( '<div class="post-profile-block friend-request" id="user-id-'+ui.item.user_id+'"><div class="post-img"><img src="'+img+'" alt=""></div><div class="post-profile-content"> <span class="post-designation"><a href="#">'+ui.item.name+'</a>	</span>			<span class="post-name">Jordan</span> 		</div>		<div class="post-buttons">			<button type="button" data-user="'+ui.item.user_id+'" id="reqbtn-'+ui.item.user_id+'" class="btn btn-blue send_friend_request" onclick="sendFriendRequest('+ui.item.user_id+')">Send Friend Request</button></div></div>' ).prependTo("#user-search-list");

                return false;

            }
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            var img='https://feedbacker.me/dev/assets/images/user-avatar.png';
            if(item.photo){
                img='https://d1f8jwm5uy46l.cloudfront.net/uploads/user/thumbs/'+item.photo;
            }
            return $( "<li>" ).append( "<div><img style='width:40px; border-radius:50%; display:inline-block; float:left; margin-right:10px;' src='"+img+"'><span style='display:inline-block; line-height:40px; '>" + item.name + "</span></div>" ).appendTo( ul );
        };
        $('.remove-friend').click(function (e) {
            e.preventDefault();
            var $liname = $(this).parent();
            tagged_friends.splice(jQuery.inArray($liname.data('name'), tagged_friends), 1);
            $liname.remove();
            console.log(tagged_friends);
        });
    });
</script>
