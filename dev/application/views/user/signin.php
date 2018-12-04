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
</head>

<body>
<div class="login-page">
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
					
					$langArray[] = '<span class="'.$class.'"><a href="'.site_url('signin/language').'/'.$lang['lang_code'].'">'.strtoupper($lang['lang_code']).'</a></span>';
				}
				
				echo implode( ' | ', $langArray );
			}
			?>
		</div>
        <?php
		$attributes = array('class' => '', 'id' => 'signin-form');
		// $hidden = array('username' => 'Joe', 'member_id' => '234');
		echo form_open('signin/auth', $attributes);
		// echo form_open('login/auth', '', $hidden);
		?>
        <ul>
          <li>
            <label><?php echo $this->lang->line('email'); ?></label>
            <input type="text" autocomplete="off" placeholder="" name="email" id="email" />
          </li>
          <li>
            <label><?php echo $this->lang->line('password'); ?></label>
            <input type="password" autocomplete="off" name="password" placeholder="" id="password" />
          </li>
          <li>
            <input type="submit" name="button" id="button" value="<?php echo $this->lang->line('login'); ?>" />
          </li>
          <li> 
          	<span class="forgot-text">
            	<a href="<?php echo site_url('signin/forgot_password'); ?>"><?php echo $this->lang->line('forgot_pass'); ?></a>
            </span> 
            <span class="signup-text">
            	<a href="<?php echo site_url('signup'); ?>"><?php echo $this->lang->line('are_you_new'); ?></a>
            </span>
            <div class="login-with"><span><?php echo $this->lang->line('login_with'); ?></span></div>
            <div class="login-social-icons">
				
	            <?php if(!empty($authUrl)) { ?>
            	<span class="facebook-icon">
                	<a href="<?php echo $authUrl; ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                </span>
                <?php } ?>
                <?php if(!empty($oauthURL)) { ?>
                <span class="twitter-icon">
                	<a href="<?php echo $oauthURL; ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                </span>
                <?php } ?>
            </div>
          </li>
        </ul>
        <?php echo form_close(); ?> 
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
	$("#signin-form").attr('autocomplete', 'off');
	
	// Setup form validation on the #register-form element
	$("#signin-form").validate({
	
		// Specify the validation rules
		rules: {
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 5
			}
		},
		
		// Specify the validation error messages
		messages: {
			email: "Please enter a valid email address",
			password: {
				required: "Please provide a password",
				minlength: "Your password must be at least 5 characters long"
			}
		},
		
		submitHandler: function(form) {
			form.submit();
		}
	});
	
	$('.callout-danger').delay(3000).hide('700');
    $('.callout-success').delay(3000).hide('700');
});
</script>
