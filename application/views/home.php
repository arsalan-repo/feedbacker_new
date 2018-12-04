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
<div class="content-secion" style="min-height:530px;">
    <div class="container">
      
      <div class="post-detail-left">
        <div class="middle-content-block" id="search-form">
			<div class="profile-listing" style="padding:140px 80px;"> 
			<?php if($is_search): ?>
			 <div class="middle-content-block" id="post-data">
			  <?php 
			  if (!empty($feedbacks)) {
				$this->load->view('post/ajax-search', $feedbacks);
			  } else {
				echo $no_record_found;
			  } ?>
			</div>
			<?php else: ?>
			<form method="get" action="<?=site_url('search'); ?>">
				<h1 style="text-align:center; margin-bottom:50px;">Search into Feedbacker</h1>
				<div class="form-group">
				<input type="text" value="" name="qs" placeholder="Type in to search feedback">
				</div>
				<div class="form-group" style="text-align:center; margin-top:100px;">
				
				<input type="submit" value="Search Feedbacks" style="max-width:250px; float:none; ">
				</div>
			</form>	
			<?php endif; ?>
					
			</div>        
        </div>
      </div>
      <div class="post-detail-rgt">
       
			<div class="login-page">
				
			  <div class="login-form" style="width:100%;">
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
	   
      </div>
    </div>
    <div class="ajax-load text-center" style="display:none">
        <p><img src="<?php echo ASSETS_URL . 'images/loader.gif'; ?>">Loading</p>
    </div>
    
    <script type="text/javascript">
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
		
    </script>
</div>