<?php
function logToFile($stringData)
{
    $myFile = 'log/speedTest.log';
    $fh = fopen($myFile, 'a+');
    $stringData = "[" . strftime('%Y-%m-%d %H:%M:%S', strtotime('now')) . "],\t". $stringData ."\n";
    fwrite($fh, $stringData);
    fclose($fh);
}

//GET DATA From index

	$isp = preg_split('/-/', json_decode($_POST['ispinfo'], true)['processedString'])[1];
    $ip       = $_SERVER['REMOTE_ADDR'];
    $ispinfo  = $_POST['ispinfo'];
    $download = $_POST['dl'];
    $upload   = $_POST['ul'];
    $ping     = $_POST['ping'];
    $jitter   = $_POST['jitter'];
	$Token    = $_POST['extra'];
	$ref_test = $_GET['ref_test'];
	$intOlan = 1; // 1 => LAN - 0 => internet
	
	if (!empty($Token)){
		try {
			require_once('DB_Config.php');
			$qur ="INSERT INTO `speedtest`(`Token`, `ref_test`, `intOlan`, `download`, `upload`, `ping`, `jitter`, `ip` , `isp`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $pdo -> prepare ($qur);
			$stmt->bindParam(1, $Token);
			$stmt->bindParam(2, $ref_test);
			$stmt->bindParam(3, $intOlan);
			$stmt->bindParam(4, $download);
			$stmt->bindParam(5, $upload);
			$stmt->bindParam(6, $ping);
			$stmt->bindParam(7, $jitter);
			$stmt->bindParam(8, $ip);
			$stmt->bindParam(9, $isp);
			$stmt -> execute();
			} catch (Exception $e) {
				logToFile($e);
				die("Oh noes! There's an error in the query!");
			}
		unset($pdo);
		
		$log_str = $_SERVER['REMOTE_ADDR'] .', ispinfo:'.$isp.", dl:".$_POST['dl'].", ul:".$_POST['ul'].", ping:".$_POST['ping'].", jitter:".$_POST['jitter'].", refNum:".$ref_test.", TOKEN:".$Token;
		logToFile($log_str );
		
	}

?>