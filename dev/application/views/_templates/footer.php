<script>
/*Autoplay video JQuery when video is in center of my screen*/
$(window).scroll(function(e)
  {
    var offsetRange = $(window).height() / 3,
        offsetTop = $(window).scrollTop() + offsetRange,
        offsetBottom = offsetTop + offsetRange;

    $("video").each(function (index ) { 
      var y1 = $(this).offset().top;
      var y2 = offsetTop;
      if (y1 + $(this).outerHeight(true) < y2 || y1 > offsetBottom) {
	   console.log('pause');
        this.pause(); 
		$("video")[index].pause();	
      } else {  
	  console.log('play');
			
		this.play();
		$("video")[index].play();	
      }
    });
});

 
</script>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<footer>© <?=date('Y'); ?> Feedbacker</footer>
</div>
</body>
</html>
