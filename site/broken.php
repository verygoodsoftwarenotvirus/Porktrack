<?php
	// Open connection
	$settings = parse_ini_file('../connect.ini');
	$host = $settings['host'];
	$user = $settings['user'];
	$pass = $settings['pass'];
	$db = $settings['db'];
	$con = mysqli_connect($host, $user, $pass, $db) or die ("Error: ". mysqli_error($con));

	$song_id = $_GET["id"];
	$ip = $_GET["ip"];
	$date = date('Y-m-d H:i:s');

	$query = 'INSERT INTO `broken`(`song_id`, `time`, `IP_address`) VALUES ("' . $song_id . '", "' . $date . '", "' . $ip . '")';
	mysqli_query($con, $query);
	mysqli_close($con);	
?>