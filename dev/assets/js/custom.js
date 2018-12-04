$(document).ready(function(){
	
	//Tab Menu
	$('ul.tabs li').click(function(){
		var tab_id = $(this).attr('data-tab');

		$('ul.tabs li').removeClass('current');
		$('.tab-content').removeClass('current');

		$(this).addClass('current');
		$("#"+tab_id).addClass('current');
	})

	$(".header-profile").click(function(){
		$(".header-profile ul").slideToggle("300");
	})

//	$(".post-follow-back-arrow").click(function(){
//		$(this).parent().parent().parent(".post-profile-block").addClass("show-comment-box").siblings().removeClass("show-comment-box");
//	});

});
