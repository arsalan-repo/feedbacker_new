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

function showLocation(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
	
    $.ajax({
        type:'POST',
        url:'<?php echo site_url('post/get_location'); ?>',
        data:'latitude='+latitude+'&longitude='+longitude,
        success:function(response){
            if(response){
				var objJSON = JSON.parse(response);
            	$('#location').val(objJSON.location);
				
				$( "#latitude" ).val( latitude );
				$( "#longitude" ).val( longitude );
            }else{
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
</script>
