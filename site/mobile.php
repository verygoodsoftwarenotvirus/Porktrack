<?php	 
	// Open connection
	$settings = parse_ini_file('../connect.ini');
	$host = $settings['host'];
	$user = $settings['user'];
	$pass = $settings['pass'];
	$db = $settings['db'];
	$con = mysqli_connect($host, $user, $pass, $db) or die ("Error: ". mysqli_error($con));

	// array for JSON response
	$response = array();
 	
	//retrieve user input from previous page
	$modifier = "+/-";
	$month = $_GET["month"];
	$year = $_GET["year"];
	$day = $_GET["day"];	
	$table = "track"; 

	$birthday = $year . "-" . $month . "-" . $day; 
    $offsetnum = $_GET["offset"]; 
	$offsetsize = $_GET['timetype'];
    $offsettype = $_GET["earlate"];  		
	if( $offsettype == "early" ) { $modifier = "-"; } else { $modifier = "+"; }
    $offset = $modifier . $offsetnum . ' ' . $offsetsize; 
		
    //shamelessly borrowed from http://stackoverflow.com/questions/7029669/calculate-a-date-with-php
    $bdAsPOSIX = strtotime($birthday);
    $basecon = strtotime('-40 weeks', $bdAsPOSIX);
    $concept = strtotime($offset, $basecon);
    $perfect = strftime('%m/%d/%Y', $basecon);
    $conception = strftime('%Y/%m/%d', $concept);
	//end borrowing
				
	//retrieve song information	
	$trackquery = "SELECT * FROM `" . $table . "` WHERE `date` <= '" . $conception . "' ORDER BY `date` DESC";
	$trackresult = mysqli_query($con, $trackquery);
	$track = mysqli_fetch_array($trackresult);
	$response["artist"] = $track['artist'];
	$response["title"] = $track['title'];
	$response["songofyear"] = $track['songofyear'];	
	$response["vid"] = $track['youtube'];		
	
	echo json_encode($response);
	
	//Close connection
	mysqli_close($con);	
?>