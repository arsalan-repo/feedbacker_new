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
		echo form_open_multipart('post/create', $attributes);
		?>
		<h2><?php echo $this->lang->line('create_post'); ?></h2>
		
		<label><?php echo $this->lang->line('write_about'); ?></label>
		<input type="text" name="title" id="title" placeholder="" />
		<label><?php echo $this->lang->line('location'); ?></label>
		<input type="text" name="location" id="location" placeholder="" readonly="" />
		<label><?php echo $this->lang->line('your_feedback'); ?></label>
		<textarea name="feedback_cont" id="feedback_cont" placeholder="" rows="10"></textarea>
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
				<div class="pdf-icon-block">
							<span>Choose File</span>
							<input name="feedback_pdf" id="feedback_pdf" accept="application/pdf" type="file" />
				</div>
				<img src="<?php echo base_url().'assets/images/map-icon.png'; ?>" class="geo-map" alt="" />
			</div>
            <div class="tag-friends">
                <a href="#" class="tag-friends"><i class="fa fa-tags"></i> Tag Friends</a>
            </div>
            <span class="post-btn"><?php echo $this->lang->line('post'); ?></span>

		</div>
		<input type="hidden" name="title_id" id="title_id" value="" />
		<input type="hidden" name="latitude" id="latitude" value="" />
		<input type="hidden" name="longitude" id="longitude" value="" />
		<?php echo form_close(); ?>
		<img id="preview" src="" alt="" height="200" width="200" />
		<div id="preview-wrapper"></div>
		<div id="pdf-preview-wrapper"></div>		
    </div>
</div>
<!-- /.content-wrapper -->
<!-- jQuery Form Validation code -->
<script type="application/javascript">
// When the browser is ready...
$(function() {
	// jQuery Toastr
	if ($.trim($(".div-toastr-error").html()).length > 0) {
		$(".div-toastr-error p").each(function( index ) {
			toastr.error($(this).html(), 'Failure Alert', {timeOut: 5000});
		});
	}

	// Set Autocomplete Off
	$("#create-post-form").attr('autocomplete', 'off');
	
	$("#feedback_img").change(function(){
		imagePreview(this);
	});
	$("#feedback_pdf").change(function(){
		pdfPreview(this);
	});
	$(".geo-map").click(function() {
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showLocation);
		} else { 
			toastr.error('Geolocation is not supported by this browser', 'Failure Alert', {timeOut: 5000});
		}
	});
	
	$("#title").autocomplete({
		minLength: 2,
		source: function( request, response ) {
          $.getJSON( "<?php echo site_url('title/search'); ?>", {
            term: request.term
          }, response );
        },
		focus: function( event, ui ) {
//			$( "#title" ).val( ui.item.title );
			return false;
		},
		select: function( event, ui ) {
			$( "#title" ).val( ui.item.title );
			$( "#title_id" ).val( ui.item.title_id );
			
			return false;
		}
    })
	.autocomplete( "instance" )._renderItem = function( ul, item ) {
		if(item.title.length == 0){
			$( "#title_id" ).val( "" );
			return false;
		}
			
		return $( "<li>" )
		.append( "<div>" + item.title + "</div>" )
		.appendTo( ul );
    };
	
	// Setup form validation on the #register-form element
	var requestRunning = false;
	$(".post-btn").click(function() {
		if (requestRunning) { // don't do anything if an AJAX request is pending
			return;
		}
		$("#create-post-form").submit();
		 requestRunning = true;
	});
	
	$("#create-post-form").validate({
		// Specify the validation rules
		rules: {
			title: {
				required: true
			},
			feedback_cont: {
				required: true
			}
		},
		
		// Specify the validation error messages
		messages: {
			title: "Please enter a title",
			feedback_cont: "Please enter a feedback"
		},
		
		submitHandler: function(form) {
			form.submit();
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
function pdfPreview(input){
	var files = input.files; 
		$("#pdf-preview-wrapper").html('');
		$.each(input.files, function(i, file) {
			var img = document.createElement("img");
			img.id = "pdf"+(i+1);
			img.style.cssText="width:200px; height:200px; margin:5px;";
			img.src = 'https://feedbacker.me/assets/images/pdf-large-icon.png';			
			console.log(file);		
			$("#pdf-preview-wrapper").append(img);
		});
}
function imagePreview(input) {
	/*if (input.files && input.files[0]) {
		var reader = new FileReader();
		
		reader.onload = function (e) {
			$('#preview').attr('src', e.target.result);
		}
		
		reader.readAsDataURL(input.files[0]);
	}*/
	var files = input.files; // FileList object

    // files is a FileList of File objects. List some properties.
    var output = [];
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
    //document.getElementById('preview-box').innerHTML = '<ul>' + output.join('') + '</ul>';
	
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

    $('#location').locationpicker(location_picker_opt);
})
</script>
<script>
    jQuery(function ($) {
        $('.select-friend-tag').hide();
        $('.tagging').hide();
        $('.tag-friends').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            $('.tagging').slideToggle();
        });
        var tagged_friends = [];
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
                    $('.remove-friend').click(function (e) {
                        e.preventDefault();
                        var $liname = $(this).parent();
                        tagged_friends.splice(jQuery.inArray($liname.data('name'), tagged_friends), 1);
                        $liname.remove();
                        console.log(tagged_friends);
                    });
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
    });
</script>
