<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$user_lang='en';
if(isset($this->session->userdata['user_lang'])){
		$user_lang = $this->session->userdata['user_lang'];		
}
if(isset($meta['type']) && $meta['type']=='video'){
	$og_type='video.other';
}else{
	$og_type='article';
}
if($user_lang=='ar'){
	if(empty($meta['description'])){
		$meta['description']='فييدباكر هي الطريقة المبتكره والجديده لإبداء رأيك بتجاربك، سواءً كان موضوع بيهمك، مكان رحته، خدمه تلقيتها، أو أي شيء اخر حاب إنك تحكي فيه.
فييدباكر بيمكنك من إنك تبدأ الحوار ببساطه شديده عن طريق وضع عنوان وكتابة مشاركتك أو رأيك في الموضوع اللي بيهمك. تستطيع بعدها إنك تتابع العنوان اللي اخترته حتى تعرف رأي الأخرين أو إنك تبحث عن عنوان بيهمك حتى تشوف إيش بينحكى عن هذا الموضوع.
فييدباكر بوفر طريقة سهلة وجديدة للمشاركة في كتابة ومتابعة وإيجاد ردود أفعال. نحن ندعوك للانضمام معنا لإنشاء مجتمع فعال وهادف وبنّاء للنهوض بالخدمات المقدمة للجميع.';
	}
}else{
	if(empty($meta['description'])){
		$meta['description']='Feedbacker is the new social way for sharing your opinions and reviews about your experiences easily and for FREE. Effective and timely feedback is a critical component to improvement for everyone no matter what they do. Start the conversation simply by WRITING a title and your opinion. You can then FOLLOW the title and see what others think about the same subject. You can also SEARCH for a title that is interesting to you and sees what others have been talking about. We invite you to join us and build this community to share our experiences.';
	}
}

if(empty($meta['image'])){
	$meta['image']='https://feedbacker.me/assets/images/feedbacker.jpeg';
}
list($og_width, $og_height, $og_image_type, $of_attr) = getimagesize($meta['image']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title><?php
		if (isset($section_title)) {
			echo $section_title." | Feedbacker";
		} else {
			echo $this->lang->line('lbl_welcome');
		}
		//echo $this->lang->line('lbl_welcome'); 
	?>
	</title>
	<meta name="keywords" content="Feedbacker,social,sharing,timely feedback,feedback,conversation,opinion,follow,writting,interesting,build community,search" />
	<meta name="description" content="<?=strip_tags_with_whitespace($meta['description']); ?>">	
	<meta property="og:site_name" content="Feedbacker" />
	<meta property="og:type" content="<?=$og_type; ?>" />
	<meta property="fb:app_id" content="1771197583195502" />
    <meta property="og:title" content="<?=!empty($section_title)? $section_title." | Feedbacker" : $this->lang->line('lbl_welcome'); ?>" />
    <meta property="og:description" content="<?=strip_tags_with_whitespace($meta['description']); ?>" />
    <meta property="og:url" content="<?=current_url(); ?>" />
    <meta property="og:image" content="<?=$meta['image']; ?>" />
	<meta property="og:image:width" content="<?=$og_width; ?>" />
	<meta property="og:image:height" content="<?=$og_height; ?>" />
	
	
	<link rel="stylesheet" href="<?=base_url('assets/css/sharetastic.css'); ?>"/>
	<link href="<?php echo base_url().'assets/css/font-awesome.min.css'; ?>" rel="stylesheet" type="text/css" />
	<!-- Country Flags -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/dd.css'; ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/sprite.css'; ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/flags.css'; ?>" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/multiple-emails.css'; ?>" />
	
	<?php 
	if(isset($this->session->userdata['user_lang'])){
		$user_lang = $this->session->userdata['user_lang'];
		if ($user_lang == 'ar') { 
			$style = 'style-rtl.css';
			$responsive = 'responsive-rtl.css';
		} else {
			$style = 'style.css';
			$responsive = 'responsive.css';
		}
	}else{
		$style = 'style.css';
		$responsive = 'responsive.css';
	}
	
	?> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.0/baguetteBox.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.0/baguetteBox.min.js" async></script>	
	
	<link href="<?php echo base_url().'assets/css/flexslider.css'; ?>" rel="stylesheet" />
	
	<link href="<?php echo base_url().'assets/css/'.$style; ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url().'assets/css/'.$responsive; ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo base_url().'assets/css/common.css' ?>" rel="stylesheet" type="text/css" />
	<!-- jQuery 1.12.1 -->
	
	<script src="<?php echo base_url().'assets/js/jquery-1.12.4.min.js';?>"></script>
	
	<!-- jQuery Validate -->
	<script src="<?php echo base_url().'assets/js/jquery.validate.min.js';?>"></script>
    <script src="<?php echo base_url().'assets/js/additional-methods.js';?>"></script>
	<!-- jQuery Toastr -->
	<script src="<?php echo base_url().'assets/js/toastr.min.js';?>"></script>
	<link href="<?php echo base_url().'assets/css/toastr.min.css'; ?>" rel="stylesheet" />
	<!-- Country Flags -->
	<script src="<?php echo base_url().'assets/js/jquery.dd.js'; ?>"></script>
	<script src="<?php echo base_url().'assets/js/custom.js'; ?>"></script>
	<!-- jQuery UI -->
	<script src="<?php echo base_url().'assets/js/jquery-ui.js'; ?>"></script>
	<link href="<?php echo base_url().'assets/css/jquery-ui.css'; ?>" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.full.js"></script>
	<script src="<?php echo base_url().'assets/js/jquery.flexslider-min.js'; ?>"></script>
	<script src="<?php echo base_url().'assets/js/multiple-emails.js'; ?>"></script>
	
	<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"/>
	
	<link href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css" rel="stylesheet"/>
	<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet"/>
	<link href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css" rel="stylesheet"/>

	<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

	<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
	<script src="<?=base_url('assets/js/sharetastic.js'); ?>"></script>

	<script type="application/javascript">
	$(document).ready(function(e) {		
		// Country Flags
		baguetteBox.run('.slides');
		try {
			var countries = $("#countries").msDropdown({on:{change:function(data, ui) {
				var url = $("#dashboard_url").val();
				var val = data.value;
				
				if(val != "")
					window.location = url+"/"+val;
			}}}).data("dd");
		} catch(e) {
			//console.log(e);	
		}
		populateNotifications();
		setInterval(function(){
		  populateNotifications();
		}, 5000);		
		});
		function populateNotifications(){
			$.getJSON('<?php echo site_url('user/notification_count'); ?>', function(data){				
				if(data.unread_count>0){
					$('#notification-count').addClass('show');
					$('#notification-count').removeClass('hide');
				}else{
					$('#notification-count').addClass('hide');
					$('#notification-count').removeClass('show');
				}
				$('#notification-count').html(data.unread_count);
			});
			
		  }
	</script>
	<script>
	$(document).ready(function() {
		 $('.sharetastic').sharetastic(); 
		 $(".social-icons-container").hide();
		 $(document).mouseup(function(e) {
			var container = $(".social-icons-container");
			if (!container.is(e.target) && container.has(e.target).length === 0) 
			{
				container.hide();
			}
		});
		$('.social-sharing a').click(function(e){
				e.preventDefault();
				var $iconwraper=$(this).find('.social-icons-container');
				//$iconwraper.hide();
				//$iconwraper.css({'top':e.pageY-50,'left':e.pageX-260, 'position':'absolute', 'padding':'5px'});
				$iconwraper.css({'bottom':'10px','right':'0', 'position':'absolute', 'padding':'5px'});
				$iconwraper.toggle();
		});
	});
	</script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-115737090-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-115737090-1');
	</script>

</head>

<body>
	<div class="wrapper <?=current_url(); ?> <?=isset($_REQUEST['q'])? $_REQUEST['q']:''; ?>">
