<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<style>
#create-post-modal .tools-wrapper{margin:10px 0;     border-top: 1px solid #e9ebee;
    margin: 0 12px;
    padding: 8px 0;}
#create-post-modal .tools-wrapper .tool-item i{background-image:url(/dev/assets/icons/icon-strip.png);background-size: auto;
    background-repeat: no-repeat;  display: inline-block; width:20px; height:20px;  position:absolute; left:8px; top:6px;}
#create-post-modal .tools-wrapper .tool-item.friends i{background-position:0 -921px;}
#create-post-modal .tools-wrapper .tool-item.media i{background-position:0 -1467px;}
#create-post-modal .tools-wrapper .tool-item.media.pdf i{background-image:url(/dev/assets/icons/icon-pdf.png); background-position:0 0;}

#create-post-modal .tools-wrapper .tool-item.media input{position:absolute; left:0; top:0; opacity:0;}
#create-post-modal .tools-wrapper .tool-item.location i{background-position:0 -1488px;}
#create-post-modal .tools-wrapper .tool-item > div{width:100%; padding:0 15px 0 35px; line-height:32px; height:32px;}
#create-post-modal .tools-wrapper .tool-item {position:relative; overflow:hidden; background: #f5f6f7;
    border-radius: 18px;
    cursor: pointer;
    }
#create-post-modal .tools-wrapper li{display:inline-block; margin-right:6px;}
#create-post-modal .content-wrapper .avatar{float:left; margin:20px 10px 0 15px;}
#create-post-modal .content-wrapper .avatar img{width:40px; border-radius:50%;}
#create-post-modal .content-wrapper .fields{width:100%; padding:10px 10px 10px 70px;}
#create-post-modal .panel-header{
background: #f5f6f7; border:none;
    border-bottom: 1px solid #dddfe2;       font-weight: bold;    padding: 8px 6px;	border-radius:10px 10px 0 0;}
#create-post-modal{padding:0;}
#create-post-modal .button-wrapper{clear:both; position:relative; padding:0 20px; }
#create-post-modal .button-wrapper .post-btn{width:100px;}
#create-post-modal .group-fields{position:relative; overflow:hidden; padding:0 8px;}
#create-post-modal .field-group{position:relative; overflow:hidden; border:#c3c8d1 solid 1px; margin-bottom:10px; display:none;}
#create-post-modal .field-group input{ width:100%; border:none; margin:0; padding-left:50px;}
#create-post-modal .field-group span{display:inline-block; background: #e2e8f6;    border-right: 1px dotted #dddfe2;   
    line-height: 16px;    margin-right: 6px;    min-width: 29px;
    padding: 8px 6px 6px 8px;    position: relative;
    text-align: center;    white-space: nowrap;
    z-index: 1; position:absolute;}
#create-post-modal #location-input-wrapper input{padding-left:64px;}
.circle {
	width: 60px;
    margin: 6px;
    display: inline-block;
    position: absolute;
    text-align: center;
	vertical-align: top;
left:16px; top:16px;
	
}
.file-item{float:left; width:100px; height:100px; position:relative; overflow:hidden; margin-right:5px;}
.circle canvas{width:100% !important;}
.file-item button{display:none; 
    background-image: url(/assets/images/cross.png);
    background-repeat: no-repeat;
    background-size: auto;
    background-position: 0 -153px;
	background-color: transparent;
    border: 0 none;
    cursor: pointer;
    font-size: 0 !important;
    overflow: hidden;
    padding: 0;
    vertical-align: middle;
	height: 12px;
    width: 12px;
	position:absolute;
	right:5px; rop:5px;
}
.file-item.uploaded button{display:block;}
.circle strong{position: absolute;
		top: 8px;
		left: 0;
		width: 100%;
		text-align: center;
		line-height: 45px;
		font-size: 15px; color:#FFF;}
#files-preview-wrapper,#files-preview-items{position:relative; overflow:hidden;}
#files-preview-wrapper{margin-bottom:10px;}
</style>
<?php
if(!isset($this->session->userdata['mec_user'])){
	defined('BASEPATH') OR exit('You need to logged in to use this.');
}else{
	$user=$this->session->userdata['mec_user'];
	//print_r($user);
	
}
?>
<div class="middle-content-block" id="create-post-block" style="position:relative;">
			<div class="post-profile-block ui-widget" id="create-post-modal" title="Create Post/Feedback">
				<div class="panel-header section-title">Create Post</div>
				<div class="panel-content ">
					<form action="<?=site_url('post/create'); ?>" method="post" enctype="multipart/form-data" >
					<div class="content-wrapper">
						<div class="avatar">							
							<?php if(isset($user['photo'])) {
      echo '<img width="40" src="'.S3_CDN . 'uploads/user/thumbs/' . $user['photo'].'" alt="" />';
    } else {
       echo '<img  width="40" src="'.ASSETS_URL . 'images/user-avatar.png" alt="" />';
    } ?>
						</div>
						<div class="fields">
							<input type="hidden" name="title_id" value="" id="title_id">
							<input type="text" name="title" id="title" placeholder="Type Title" required>
							<textarea name="feedback_cont" rows="6" placeholder="What is on your mind?" required></textarea>
						</div>					
					</div>
					<div class="tools-wrapper">
						<div class="files-preview-wrapper" id="files-preview-wrapper">
							<div id="files-preview-items"></div>
						</div>
						<div class="group-fields">
							<div class="field-group" id="friends-input-wrapper">
								<span>With</span>
								<input type="text" name="friends" id="friends" placeholder="Who are you with?">
							</div>
							<div class="field-group" id="location-input-wrapper">
								<span>Where</span>
								<input type="text" name="location" id="location" placeholder="Where are you?">
								<input type="hidden" name="latitude" id="latitude" value="">
								<input type="hidden" name="longitude" id="longitude" value="">
							</div>
						</div>
						<ul>
							<li>
							<div class="tool-item media">
							<i class="" alt=""></i>
							<div title="Upload Photo/Video" data-tooltip-delay="500" data-tooltip-display="overflow" data-tooltip-content="Photo/Video" data-hover="tooltip" class="_2aha">Photo/Video</div>
							<input accept="video/*,  video/x-m4v, video/webm, video/x-ms-wmv, video/x-msvideo, video/3gpp, video/flv, video/x-flv, video/mp4, video/quicktime, video/mpeg, video/ogv, .ts, .mkv, image/*" = multiple="" name="feedback_files[]" display="inline" role="button" tabindex="0" type="file" class="" id="feedback_files">
							</div>
							</li>
							<li>
								<div class="tool-item media pdf">
								<i class="" alt=""></i>
								<div data-tooltip-delay="500" data-tooltip-display="overflow" data-tooltip-content="Photo/Video" data-hover="tooltip" class="_2aha">PDF</div>
								<input accept="application/pdf" = multiple="" name="feedback_pdf[]" display="inline" role="button" tabindex="0" type="file" class="" id="feedback_pdf">
								</div>
							</li>
							<li>
							<div class="tool-item friends tool-item-link" data-target="friends">
								
								<i class="" alt=""></i>
								<div data-tooltip-delay="500" data-tooltip-display="overflow" data-tooltip-content="Tag Friends" data-hover="tooltip" class="_2aha">Tag Friends</div>
								
							</div>
							</li>
							<li>
							<div class="tool-item location tool-item-link" data-target="location">
								
								<i class="" alt=""></i>
								<div data-tooltip-delay="500" data-tooltip-display="overflow" data-tooltip-content="Tag Friends" data-hover="tooltip" class="_2aha">Check In</div>
								
							</div>
							</li>
							
						</ul>
					</div>
					<div class="button-wrapper">
						<input type="submit" name="post" class="post-btn" value="Post">
					</div>
					</form>
				</div>				
			</div>
		</div>
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDktEtV_Y5KnUIOEzTYs9UoLKGyinVdLQw&libraries=places&callback=initMap"
        async defer></script>
<script>
            var autocomplete;
			
            function initMap() {
              autocomplete = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */(document.getElementById('location')),
                  { types: ['geocode'] });
              google.maps.event.addListener(autocomplete, 'place_changed', function() {
				var lat=autocomplete.getPlace().geometry.location.lat();
				var lng=autocomplete.getPlace().geometry.location.lng();
				$("#longitude").val(lng);
				$("#latitude").val(lat);
              });
            }
        </script> 
<script src="<?=base_url('assets/js/circle-progress.min.js');?>"></script>

<script>
var uploadedFiles=[];
function fileupload(formdata,index,file_type){
	$.ajax({
		url : "<?=site_url('ajax/upload_file'); ?>",
		type: "POST",
		data : formdata,
		contentType: false,
		cache: false,
		processData:false,
		xhr: function(){
			//upload Progress
			var xhr = $.ajaxSettings.xhr();
			if (xhr.upload) {
				xhr.upload.addEventListener('progress', function(event) {
					var percent = 0;
					var percent2 = 0;
					var position = event.loaded || event.position;
					var total = event.total;
					if (event.lengthComputable) {
						percent = Math.ceil(position / total * 100);
						percent2 = position / total ;
					}
					
					$("#circle-"+index).circleProgress({'size':60,'value': percent2.toFixed(2),'animationStartValue':percent2.toFixed(2)}); 
					$("#circle-"+index).find('strong').html(Math.round(percent) + '<i>%</i>');
					if(percent==100){
						$("#circle-"+index).hide();
						$("#file-item-"+index).addClass('uploaded');
					}
					
				}, true);
			}
			return xhr;
		},
		mimeType:"multipart/form-data"
	}).done(function(res){ 
		$obj=$.parseJSON(res);
		if($obj.success){			
			uploadedFiles[index]=$obj.file;
			var fileJson = JSON.stringify($obj.file);
			$("<input type='hidden' name='files_json[]' id='files_json"+index+"' value='"+fileJson+"'>").appendTo( "#files-preview-wrapper");
			if(file_type=='image'){
				$("<input type='hidden' name='images[]' id='file"+index+"' value='"+$obj.file.file_name+"'>").appendTo( "#files-preview-wrapper");
				
			}else if(file_type=='video'){
				$("<input type='hidden' name='vidoes[]' id='file"+index+"' value='"+$obj.file.file_name+"'>").appendTo( "#files-preview-wrapper");
			}else if(file_type=='pdf'){
				$("<input type='hidden' name='pdf[]' id='file"+index+"' value='"+$obj.file.file_name+"'>").appendTo( "#files-preview-wrapper");
			}
		}
		//console.log(res);
		console.log(uploadedFiles);
		//$(my_form_id)[0].reset(); //reset form
//$(result_output).html(res); //output response from server
		//submit_btn.val("Upload").prop( "disabled", false); //enable submit button once ajax is done
	});
}
var progressBarOptions = {	
	size: 60,
    value: 0,
    fill: {
		color: '#0681c4'
	}
};

var files_counter=0;
var ValidImageTypes = ["image/gif", "image/jpeg", "image/png","image/jpg"];
var ValidVideoTypes = ["video/x-m4v", "video/webm", "video/x-ms-wmv", "video/x-msvideo", "video/3gpp", "video/flv", "video/x-flv", "video/mp4", "video/quicktime", "video/mpeg", "video/ogv"];
$("#feedback_files, #feedback_pdf").on('change',function(e){
	var files=this.files;
	
	$.each(files,function(i,file){
		
		console.log(files_counter);
		var fileType = file["type"];
		console.log(fileType );
		var formMediaData= new FormData();
		formMediaData.append('file',files[i]);
		
		var img=document.createElement("img");		
		img.id="image"+files_counter;
		img.style.cssText="width:100px; height:100px;";
		var imgDiv = document.createElement('div');
		imgDiv.id="file-item-"+files_counter;
		imgDiv.classList.add("file-item");
	//	imgDiv.style.cssText="float:left; width:100px; height:100px; position:relative; overflow:hidden;";		
		var para = document.createElement("div"); 
		var removeButton = document.createElement("button");
		removeButton.type="button";
		removeButton.setAttribute("data-file",files_counter);
		removeButton.setAttribute("onclick","removeFile("+files_counter+")");
		var strong = document.createElement("strong"); 
		para.id="circle-"+files_counter;
		para.classList.add("circle");	
		para.appendChild(strong);
		imgDiv.appendChild(removeButton);
		imgDiv.appendChild(para);		
        $("#circle-"+files_counter).circleProgress(progressBarOptions).on('circle-animation-progress', upddateprogress);
		imgDiv.appendChild(img);
		var file_type='image';
		if ($.inArray(fileType, ValidImageTypes) > 0) {
			var reader=new FileReader();
			reader.onloadend=function(){img.src=reader.result;}
			reader.readAsDataURL(file);
			file_type='image';
		}else if(fileType=='application/pdf'){
			img.src="/assets/images/pdf-file-icon.png";
			file_type='pdf';
		}else if($.inArray(fileType, ValidVideoTypes) > 0) {
			img.src="/assets/images/video-file-icon.png";
			file_type='video';
		}else{
			img.src="/assets/images/files-icon.png";
			file_type='file';
		}		
			
		$("#files-preview-items").append(imgDiv);
		fileupload(formMediaData,files_counter,file_type);
		files_counter++;
	});
	
	
});
function removeFile(fid){
	console.log(fid);
	console.log($("#file-item-"+fid));
		
	var deleteFile=uploadedFiles[fid];
	var file_name=deleteFile.file_name;
	$("#file-item-"+fid).remove();
	$("#file"+fid).remove();
	//console.log(deleteFile)
	delete uploadedFiles[fid];
	$.ajax({
		url : '/dev/ws/v1/delete_file',
		type: "POST",
		data : {file:file_name},
		contentType: false,
		cache: false,
		processData:false,		
	}).done(function(res){ 		
		console.log(res);		
	});
}
$(".file-item > button").on('click',function(e){
	console.log('clicked');
	var fid=$(this).data('file');
	
	
	
});
function upddateprogress(event, progress, stepValue){
	
	$(this).find('strong').html(Math.round(100 * progress) + '<i>%</i>');
}
$(".tool-item-link").on('click',function(e){
	e.preventDefault();
	$target=$(this).data('target');
	var $wrapper=$target+"-input-wrapper";
	$("#"+$wrapper).toggle();
	
});
function split( val ) {
      return val.split( /,\s*/ );
    }
  function extractLast( term ) {
      return split( term ).pop();
   }
$("#friends").autocomplete({
					minLength: 2,
					source: function( request, response ) {
					  $.getJSON( "<?php echo site_url('/ajax/get_friends'); ?>", {
						term: extractLast( request.term )
					  }, response );
					  
					},
					focus: function( event, ui ) {
						return false;
					},
					select: function( event, ui ) {
						var terms = split( this.value );					  
					  terms.pop();					  
					  terms.push( ui.item.name );					 
					  terms.push( "" );
					  this.value = terms.join( ", " );
					  $("<input type='hidden' name='friend_list[]' value='"+ui.item.user_id+"'>").appendTo( "#files-preview-wrapper");
					  return false;
						/*$( "#friends" ).val( ui.item.name );
						return false;*/
					}
}).autocomplete( "instance" )._renderItem = function( ul, item ) {
	var img='https://feedbacker.me/dev/assets/images/user-avatar.png';
	if(item.photo){
		img='https://d1f8jwm5uy46l.cloudfront.net/uploads/user/thumbs/'+item.photo;
	}
	return $( "<li>" ).append( "<div><img style='width:40px; border-radius:50%; display:inline-block; float:left; margin-right:10px;' src='"+img+"'><span style='display:inline-block; line-height:40px; '>" + item.name + "</span></div>" ).appendTo( ul );
};
$("#title").autocomplete({
					minLength: 2,
					source: function( request, response ) {
					  $.getJSON( "<?php echo site_url('title/search'); ?>", {
						term: request.term
					  }, response );
					},
					focus: function( event, ui ) {
						return false;
					},
					select: function( event, ui ) {
						$( "#title" ).val( ui.item.title );
						$( "#title_id" ).val( ui.item.title_id );
						return false;
					}
}).autocomplete( "instance" )._renderItem = function( ul, item ) {
	return $( "<li>" ).append( "<div>" + item.title + "</div>" ).appendTo( ul );
};

</script>
