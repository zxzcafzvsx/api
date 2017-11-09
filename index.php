<html>
<head>
<title>Check AWB</title>
<style type="text/css">
BODY, TD, INPUT {
	font-family:Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	
}
INPUT.submit {
	border: 1px solid #FF99FF;
	background-color: #FFCCFF;
	padding: 2px;
}
TABLE {
	margin: 1px;
	border: 1px solid #DDD;
}
TD {
	padding: 2px;
}
TD.content {
	background-color:#fff;
}
TR.trackH {
	background-color:#FF99FF;
	font-weight:bold;
}
TR.trackC {
	background-color:#FFCCFF;
}
.subtitle {
	background-color:#FF99FF;
	font-size: 16px;
	font-weight:bold;
}
</style>
</head>
<body>
<center>
<?php
$awb = preg_replace('/[\\D]/', '', $_REQUEST['awb']);
if(strlen($awb) == 12) {
	$grab = ""; 
	$sock = @fsockopen("www.tiki-online.com", 80, $errno, $errstr, 30); 
	if ($sock)  {
	
		$data = "TxtCon=" . $awb . "&Sbm=Track"; 
		
		fputs($sock, "POST /trackcon1.asp HTTP/1.0\r\n");  
		fputs($sock, "Host: www.tiki-online.com\r\n"); 
		fputs($sock, "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.18) Gecko/20081029 Firefox/2.0.0.18\r\n"); 
		fputs($sock, "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5\r\n"); 
		fputs($sock, "Keep-Alive: 300\r\n");
		fputs($sock, "Connection: keep-alive\r\n");
		fputs($sock, "Content-Type: application/x-www-form-urlencoded\r\n");
		fputs($sock, "Content-length: " . strlen($data) . "\r\n"); 
		fputs($sock, "Referer: http://www.tiki-online.com/trackcon1.asp\r\n\r\n");
		fputs($sock, $data . "\r\n\r\n");
 		
		$headers = ""; 
		while ($str = trim(fgets($sock, 4096))) 
		 	$headers .= "$str\n"; 
		
		while (!feof($sock)) 
			$grab .= fgets($sock, 4096); 
		
		fclose($sock); 
		//echo("GRAB: " . htmlentities($grab));
		if (preg_match('/<!-- Tabel ISI Data-->([\\w\\W]*?)<\/font><\/p>/sim', $grab, $regs)) {
			$result = $regs[1];
		} else {
			$result = "Data tidak ditemukan. Periksa kembali Connote anda.";
		}
	} else {
		$result = "TIKI Service is temporarily unavailable. Please try again later.";
	}
} else if(strlen($awb) == 13){
	$grab = @file_get_contents("http://www.jne.co.id/index.php?mib=tracking.detail&awb=". $awb ."&awb_list=". $awb .",");
	//$grab = file_get_contents("firefox.htm");
	
	if (preg_match('/<td width="557" align="left" valign="top" class="bg4">([\\w\\W]*?)<p>/i', $grab, $regs)) {
		$result = $regs[1];
	} else {
		$result = "Data tidak ditemukan. Periksa kembali AWB anda";
	}
} else {
	$result = "Masukkan Airway Bill/Consignment Note anda. Panjang angka yang valid adalah 13 untuk JNE dan 12 untuk TIKI.";
}
$trans = array(' bgcolor="#fff"' => '', ' bgcolor=#97bc4e' => ' bgcolor="#FF99FF"', ' bgcolor=#cfea9c' => ' bgcolor="#f9d2bc"', ' bgcolor=#e8f7cc' => ' bgcolor="#fdeae0"');
if(empty($result)) {
	echo('Data salah atau data belum masuk sistem. Periksa kembali AWB/Connote anda.');
} else {
	echo strtr($result, $trans);
}
?>
<form name="awbreq" method="post">Masukkan nomor Airway Bill/Consignment Note lainnya : <input type="text" name="awb" value="<?=$awb?>" /> <input type="submit" name="submit" value="awb" class="submit" /></form>
</body></html>
