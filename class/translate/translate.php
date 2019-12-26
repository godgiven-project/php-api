<?php
global $f3;
//require_once("streams.php");
//require_once("gettext.php");
function load_textdomain($url , $textdomain = "main"){
	global $f3;
	$locale_lang  = $f3->get('ggn_LANGUAGE');
    $locale_fetch = array();
	if(file_exists($url."$textdomain-$locale_lang.json")){
		$locale_file  = file_get_contents($url."$textdomain-$locale_lang.json");
		$locale_fetch = json_decode($locale_file, true);
	}
	$get_locale_fetch = array();
	$get_locale_fetch[$textdomain] = $locale_fetch;
	$old_locale_fetch = $f3->get('I18n');
	$new_locale_fetch = array_merge($old_locale_fetch , $get_locale_fetch);
	return $f3->set('I18n' , $new_locale_fetch);
}
load_textdomain('app/languages/');
function __($text,$textdomain = "main"){
	global $f3;
	$old_locale_fetch = $f3->get('I18n')[$textdomain];
	if (array_key_exists($text, $old_locale_fetch)){
		return $old_locale_fetch[$text];
	}
	else {
		return $text;
	}
}
// Print the translates string
function _e($text,$textdomain = "main"){
	global $f3;
	$old_locale_fetch = $f3->get('I18n')[$textdomain];
	if (array_key_exists($text, $old_locale_fetch)){
		return $old_locale_fetch[$text];
	}
	else {
		return $text;
	}
}