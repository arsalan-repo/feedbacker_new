<?php

  define( 'API_ACCESS_KEY', 'AIzaSyDavF74uaoIqreMHMT-O4dLlgVYODJ6WWs' );
    $registrationIds = 'd4j7Dunv1-8:APA91bFRCWx8BeQwR2I_pg9ZObUXSROofWzk_a_rBy-QR1QcvC0BEhCsb5RZFW22ZNX0nHK7hlaAcbTAhR253VwXo2kX4Tp2G2d557vXi4vg0_Un5ptFMY1y-dBUB01Kna6TZjAE7AE_';
	$msg = array
          (
		'body' 	=> 'Body  Of Notification',
		'title'	=> 'Title Of Notification',
             	'icon'	=> 'myicon',/*Default Icon*/
              	'sound' => 'mySound'/*Default sound*/
          );
	$fields = array
			(
				'to'		=> $registrationIds,
				'notification'	=> $msg
			);
	
	
	$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
#Echo Result Of FireBase Server
echo $result;
	/****************************************************/
define( 'API_ACCESS_KEY_NEW', 'AIzaSyDI53cBRTUvpowMbY5BIf3W95lhjM6Ssww' );
$registrationIds = array( "d4j7Dunv1-8:APA91bFRCWx8BeQwR2I_pg9ZObUXSROofWzk_a_rBy-QR1QcvC0BEhCsb5RZFW22ZNX0nHK7hlaAcbTAhR253VwXo2kX4Tp2G2d557vXi4vg0_Un5ptFMY1y-dBUB01Kna6TZjAE7AE_" );
// prep the bundle
$msg = array(
        'body'  => "Teset Message",
        'title'     => "Feedbacker",
        'vibrate'   => 1,
        'sound'     => 1,
    );
	$data = array(
        'mtitle'  => "test",
        'mdesc'     => "Feed replied",
		'title_id' => 640,
        'feedback_id' => 5039
    );
$fields = array(
            'registration_ids'  => $registrationIds,
            'notification'      => $msg,
			'data'      => $data
        );

$headers = array(
            'Authorization: key=' . API_ACCESS_KEY_NEW,
            'Content-Type: application/json'
        );

$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
var_dump($result);
?>