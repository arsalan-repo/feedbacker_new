<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>
<?php
	if (isset($section_title)) {
		echo $section_title." | Feedbacker";
	} else {
		echo $this->lang->line('lbl_welcome');
	}
	//echo $this->lang->line('lbl_welcome'); 
?>
</title>
<link href="<?php echo base_url().'assets/css/font-awesome.min.css'; ?>" rel="stylesheet" type="text/css" />
<?php 
	if (isset($this->session->userdata['fb_lang']) && $this->session->userdata['fb_lang'] == 'ar') { 
		$style = 'style-rtl.css';
		$responsive = 'responsive-rtl.css';
	} else {
		$style = 'style.css';
		$responsive = 'responsive.css';
	}
?>
<link href="<?php echo base_url().'assets/css/'.$style; ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().'assets/css/'.$responsive; ?>" rel="stylesheet" type="text/css" />
<!-- jQuery 1.12.1 -->
<script src="<?php echo base_url().'assets/js/jquery-1.12.1.min.js';?>"></script>
<!-- jQuery Validate -->
<script src="<?php echo base_url().'assets/js/jquery.validate.min.js';?>"></script>
<script src="<?php echo base_url().'assets/js/additional-methods.js';?>"></script>
</head>

<body>
<div class="login-page" style="overflow:hidden;"> 
	<span class="login-img">
    	<?php /*?><img src="<?php echo base_url().'assets/images/login-img.png'; ?>" alt="" /><?php */?>
    </span> 
    <span class="login-bg">
    	<img src="<?php echo base_url().'assets/images/form-bg.png'; ?>" />
    </span>
	<div class="login-form">
    <div class="login-form-block">
    <?php if ($this->session->flashdata('success')) { ?>
    <div class="callout callout-success">
        <p><?php echo $this->session->flashdata('success'); ?></p>
    </div>
	<?php } ?>
    <?php if ($this->session->flashdata('error')) { ?>  
        <div class="callout callout-danger">
            <p><?php echo $this->session->flashdata('error'); ?></p>
        </div>
    <?php } ?>
      <div class="login-form-fields">
        <div class="logo">
        	<a href="<?php echo site_url(); ?>">
            	<img src="<?php echo base_url().'assets/images/logo.png'; ?>" alt="" />
            </a>
        </div>
		<div class="login-language-text">
			<?php
			$contition_array = array('lang_status' => 1);
			$languages = $this->common->select_data_by_condition('languages', $contition_array, $data = 'lang_code, lang_name');
	
			if(!empty($languages)) {
				foreach($languages as $lang) {  
					// Check for user preferred language
					if($this->input->get('lang') == $lang['lang_code']) {
						$class = 'lang-selected';
					} else {
						$class = '';    
					}
					
					$langArray[] = '<span class="'.$class.'"><a href="'.site_url('signup/language/').'/'.$lang['lang_code'].'/confirm">'.strtoupper($lang['lang_code']).'</a></span>';
				}
				
				echo implode( ' | ', $langArray );
			}
			?>
		</div>
		
		<div class="msg" style="min-height:300px; clear:both; margin-top:50px;">
			<h4><?=$this->lang->line('success_msg_confirm_email'); ?></h4>
		</div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<!-- jQuery Form Validation code -->
<script type="application/javascript">
// When the browser is ready...
$(function() {
	// Set Autocomplete Off
	$("#signup-form").attr('autocomplete', 'off');
	
	// Setup form validation on the #register-form element
	$("#signup-form").validate({
	
		// Specify the validation rules
		rules: {
			name: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			country: {
				required: true
			},
			password: {
				required: true,
				minlength: 5
			},
			confirm_password: {
				equalTo: "#password"
			}
		},
		
		// Specify the validation error messages
		messages: {
			name: {
				required: "Please enter your name"
			},
			email: "Please enter a valid email address",
			country: "Please select your country",
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			},
			confirm_password: "Enter Confirm Password Same as Password"
		},
		
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	$('.callout-danger').delay(3000).hide('700');
    $('.callout-success').delay(3000).hide('700');
});
</script>
