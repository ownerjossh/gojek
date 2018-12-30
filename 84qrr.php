<?php
	function curl($api,$post,$cookie,$header =  false,$httpheaders = null){
		$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $api);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			curl_setopt($ch, CURLOPT_HEADER, $header);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array($httpheaders));
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
		$response = curl_exec($ch);
		return $response;
	}

	function registerotp($nohp){
		$otp 		= curl("http://wgansexp.000webhostapp.com/req/otpreg.php","bmdhcGFpbiBkaWRlY29kZSBnYW4gOnYgdmFrZWtvaw=a3dvd29rd29rdw==&nohape=".$nohp."",false);
		return $otp;
	}

	function register($otptoken,$otp){
		$register  	= curl("https://wgansexp.000webhostapp.com/req/register.php","bmdhcGFpbiBkaWRlY29kZSBnYW4gOnYgdmFrZWtvaw=a3dvd29rd29rdw==&token=".$otptoken."&otp=".$otp."",false);
		return $register;
	}

	function otppin($accesstoken,$pin){
		$otppin    	= curl("https://wgansexp.000webhostapp.com/req/pinotp.php","bmdhcGFpbiBkaWRlY29kZSBnYW4gOnYgdmFrZWtvaw=a3dvd29rd29rdw==&accesstoken=".$accesstoken."&pincode=".$pin."",false);
		return $otppin;
	}

	function setpin($accesstoken,$otp,$pin){
		$setpin 	= curl("https://wgansexp.000webhostapp.com/req/setpin.php","bmdhcGFpbiBkaWRlY29kZSBnYW4gOnYgdmFrZWtvaw=a3dvd29rd29rdw==&accesstoken=".$accesstoken."&otp=".$otp."&pin=".$pin."",false);
		return $setpin;
	}


echo "\nMasukkan Nomor HP (Without + CountryCODExx)  : ";
$nomorhp 			= trim(fgets(STDIN));
if(empty($nomorhp)){
	exit("\nData Tidak Lengkap!");
}
$otpregg 			= registerotp($nomorhp);
@$otp_token 			= json_decode($otpregg)->otp_token;
if(@json_decode($otpregg)->success == "false"){
	exit("\nFailed ! => ".json_decode($otpregg)->message."");
}elseif(@json_decode($otpregg)->success == "true"){
	echo "\nMessage => ".json_decode($otpregg)->message."";
}

echo "\nMasukkan OTP dari nomor (".$nomorhp.") : ";
$otpregister 			= trim(fgets(STDIN));
$register 			= register($otp_token,$otpregister);
@$accesstoken 			= json_decode($register)->access_token;
@$pincode 			= json_decode($register)->Pin;
if(@json_decode($register)->success == "false"){
	exit("\nFailed ! => ".json_decode($register)->message."");
}elseif(@json_decode($register)->success == "true"){
	echo "\nSuccess Register! => ".json_decode($register)->NomorHp."|".json_decode($register)->Email."|".json_decode($register)->Nama."|".json_decode($register)->Pin."|".json_decode($register)->access_token."";
}

echo "\nPlease Wait. . . ";
sleep(2);
echo "\n";
print_r(otppin($accesstoken,$pincode));
sleep(2);
echo "\nMasukkan OTP PIN  : ";
$otp_pin 			= trim(fgets(STDIN));
$setpinn 			= setpin($accesstoken,$otp_pin,$pincode);
if(@json_decode($setpinn)->success == "false"){
	exit("\nFailed ! => ".json_decode($setpinn)->message."");
}elseif(@json_decode($setpinn)->success == "true"){
	echo "\n".json_decode($setpinn)->message."";
}
?>	