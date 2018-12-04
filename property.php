<?php
//print_r($_SERVER['REQUEST_URI']);
 $id=$_REQUEST['id'];
include('config.php');
require_once('models/property_model.php');	
$model=new Property();
$property=$model->get_property($id);
//var_dump($property);
$featured_image='images/realfinder.png';
if(count($property->images)>0)
	$featured_image=$property->images[0]->url;
//echo $featured_image;
 ?>
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0, maximum-scale=1.0" />
    <title><?=$property->title;?></title>
	
	<meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content='<?=$property->title;?>'>
    <meta name="twitter:description" content='<?=$property->description;?>'>
    <meta name="twitter:image:src" content='<?=$featured_image; ?>'>
    <meta name="twitter:domain" content="realfinder.net">
    <meta name="twitter:app:url:iphone" content="">
    <meta name="twitter:app:url:ipad" content="">
    <meta name="twitter:app:url:googleplay" content="">
    <meta name="twitter:app:id:iphone" content="id1251416201">
    <meta name="twitter:app:id:ipad" content="id1251416201">
    <meta name="twitter:app:id:googleplay" content="com.realfinder.android">
    <meta name="twitter:site" content="realfinder">
    <meta name="twitter:creator" content="PrimeMinistry">
	
    <!-- AppLinks for Deep Linking -->
    <meta property="al:ios:app_store_id" content="1251416201" />
    <meta property="al:ios:url" content="realfinder://property/<?=$id; ?>" />
    <meta property="al:ios:app_name" content="realfinder" />

    <meta property="al:android:package" content="com.realfinder.android" />
    <meta property="al:android:url" content="realfinder://property/<?=$id; ?>" />
    <meta property="al:android:app_name" content="realfinder" />

    <meta property="al:web:should_fallback" content="false" />
	
	<!-- Facebook MetaTag Headers -->
    <meta property="og:title" content="<?=$property->title;?>" />
    <meta property="og:type" content="article"/>
    <meta property="og:image" content="<?=$featured_image; ?>"/>
     <meta property="og:url" content="http://www.realfindernet/property.php?id=<?=$id; ?>"/>
    <meta property="og:site_name" content="Realfinder"/>
    <meta property="og:description" content="<?=$property->description;?>"/>   
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>@media screen and (max-device-width:480px){body{-webkit-text-size-adjust:none}}
	/* Desktops and laptops ----------- */
	
		.nb-mobile-page-icons {
			width: 190px;
			float: none !important;
		}
		.description{margin:20px 0;}
		.image-box{padding:5px; position:relative; }
		.image-box img{position:relative; width:100%;}
		.nb-download-link-div {
			background: #0088cc;
			text-align: center;
			color: white;
			padding-bottom: 10px;
			font-size: 17px;
			font-family: "HelveticaNeueLTArabic-Bold";
			width: 100% !important;
		}
		.nb-mobile-page-icons {
			width: 190px;
			background: none;
			border: none;
		}
	
	</style>
 
    <!-- implement javascript on web page that first first tries to open the deep link
        1. if user has app installed, then they would be redirected to open the app to specified screen
        2. if user doesn't have app installed, then their browser wouldn't recognize the URL scheme
        and app wouldn't open since it's not installed. In 1 second (1000 milliseconds) user is redirected
        to download app from app store.
		https://itunes.apple.com/us/app/realfinder/id1251416201?mt=8
     -->
    <script>
  /*  window.onload = function() {
    <!-- Deep link URL for existing users with app already installed on their device -->
        window.location = 'http://www.realfinder.net/property.php?id=<?=$id; ?>';
    <!-- Download URL (TUNE link) for new users to download the app -->
        setTimeout("window.location = 'https://play.google.com/store/apps/details?id=com.realfinder.android&hl=en';", 1000);
    }*/
    </script>
</head>
<body>
 <div class="site-wrapper">
 <div class="col-sm nb-download-link-div container">        
        <div class="col-lg-12  col-xs-12  nb-download-link-div text-center">
                <a href="https://itunes.apple.com/us/app/realfinder/id1251416201?mt=8" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail nb-mobile-page-icons" src="images/itunes_link.png">
                </a>
                <a class="d-block mb-4 h-100" href="https://play.google.com/store/apps/details?id=com.realfinder.android&hl=en">
                    <img class="img-fluid img-thumbnail nb-mobile-page-icons" src="images/android_link.png">
                </a>
        </div>
   </div>
  <div class="container">
		<div class="page_title text-center"><h1><?=$property->title;?></h1></div>
		<div class="description"><?=$property->description;?></div>
		<div class="images row">
		<?php foreach($property->images as $img): ?>
			<div class="col-md-3">
				<div class="image-box">
					<img src="<?=$img->url ?>">
				</div>
			</div>
		<?php endforeach; ?>
		</div>
  </div>
  <div class="col-sm nb-download-link-div container">        
        <div class="col-lg-12  col-xs-12  nb-download-link-div text-center">
                <a href="https://itunes.apple.com/us/app/realfinder/id1251416201?mt=8" class="d-block mb-4 h-100">
                    <img class="img-fluid img-thumbnail nb-mobile-page-icons" src="images/itunes_link.png">
                </a>
                <a class="d-block mb-4 h-100" href="https://play.google.com/store/apps/details?id=com.realfinder.android&hl=en">
                    <img class="img-fluid img-thumbnail nb-mobile-page-icons" src="images/android_link.png">
                </a>
        </div>
   </div> 
</div> 
</body>
</html>