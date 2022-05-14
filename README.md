# SpeedTestNetwork
PHP _SpeedTest_With_MysqlDB

First Create User to Access to DB and Table IN MYSQL

	$db   = '????';
  $user = '????';
  $pass = '????'; 
  
and Then Edit DB_Config.php and Correct the config File Based on above INFO

Then Create Table: 

CREATE TABLE speedtest ( ID INT(12) UNSIGNED AUTO_INCREMENT PRIMARY KEY, Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, Token VARCHAR(512) NOT NULL, ref_test VARCHAR(512) NOT NULL, intOlan INT(2) NOT NULL, download VARCHAR(512) NOT NULL, upload VARCHAR(512) NOT NULL, ping VARCHAR(512) NOT NULL, jitter VARCHAR(512) NOT NULL, ip VARCHAR(512) NOT NULL, isp VARCHAR(512) NOT NULL );
