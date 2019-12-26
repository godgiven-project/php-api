<?php 
function ggn_send_mail($mail_to , $subject , $message , $from = "" , $Reply = "" ){
	// send mail
	if($from){
		$header	 = "From: $from\n";
	}
	if($Reply){
		$header .= "Reply-To: $Reply\n";
	}
	$header		.= "Content-Type: text/html; charset=UTF-8\n";
	$subject 	 = '=?UTF-8?B?'.base64_encode($subject).'?=';
	$email_to 	 = $mail_to;
	$message 	 = $message ;
	$send_mail 	 = @mail($email_to, $subject ,$message ,$header ) ;
	if($send_mail){
		return $send_mail;
	}
	else{
		$errorMessage = error_get_last()['message'];
		if($errorMessage) return $errorMessage;
	}
}
?>