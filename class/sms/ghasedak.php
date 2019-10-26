<?php 
function ggn_send_sms($receptor , $message , $sender = "30005006002282"){
	global $f3;
	$curl = curl_init();
	curl_setopt_array(
		$curl, 
		array(
			CURLOPT_URL => "http://api.smsapp.ir/v2/sms/send/simple",
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "message=".$message."&sender=".$sender."&Receptor=".$receptor,
			CURLOPT_HTTPHEADER => array(
			"apikey:".$f3->get('sms_api_key'),
			)
		)
	);
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
		return $err;
	} else {
		return $response;
	}
}