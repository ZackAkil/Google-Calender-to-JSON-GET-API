<?php
	$apiKey = "[google calender api key]";
	$calenderId = "[calenderID]@group.calendar.google.com";
	
	$curl = curl_init();
	date_default_timezone_set("GB");
	$today = rawurlencode (date("Y-m-d\T0:0:0.000\Z"));
	$tommorrow  = new DateTime('tomorrow');
	$tommorrow = rawurlencode ($tommorrow ->format("Y-m-d\TH:m:s.000\Z"));
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'https://www.googleapis.com/calendar/v3/calendars/'.$calenderId.'/events?timeMin='.$today.'&timeMax='.$tommorrow.'&key='.$apiKey,
		CURLOPT_USERAGENT => 'Arduino Calender Fetcher'
	));
	$resp = curl_exec($curl);
	header('Content-Type: application/json');
	//echo ($resp);
	curl_close($curl);
	$events =  json_decode($resp, TRUE);
	$data = array();
	foreach($events['items'] as $item) {
		$startDateTime =  new DateTime($item['start']['dateTime']);
		$endDateTime =  new DateTime($item['end']['dateTime']);
		$arr = array(
			'S' => convertTimeToMinutes($startDateTime),
			'L' => getDiffInMinutes($startDateTime, $endDateTime)
			);
		$data [] = $arr;
	}
	echo json_encode($data, JSON_PRETTY_PRINT);
	
	function getDiffInMinutes($startDateTime, $endDateTime) {
		$interval = $startDateTime->diff($endDateTime);
		$minutes =convertDiffToMinutes($interval);
		return $minutes;
	}
	
	function convertDiffToMinutes($time){
	    	$hours = $time->format("%H");
		$minutes = $time->format("%i");
		return (($hours*60) + $minutes);
		}
		
	function convertTimeToMinutes($time){
	    	$hours = $time->format("H");
		$minutes = $time->format("i");
		return (($hours*60) + $minutes);
		}
?>