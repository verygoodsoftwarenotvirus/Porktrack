<?php	 
	// Open connection
	$settings = parse_ini_file('../connect.ini');
	$host = $settings['host'];
	$user = $settings['user'];
	$pass = $settings['pass'];
	$db = $settings['db'];
	$con = mysqli_connect($host, $user, $pass, $db) or die ("Error: ". mysqli_error($con));	
	$current_url = "http://www.porktrack.com" . $_SERVER['REQUEST_URI'];
	
	//retrieve user input adn validate it
	if(isset($_GET["list"]))
    { $table = $_GET["list"]; }
    else{ $table = "track";}

        if($table == ""){
            $table = "track";
        }

	$day = (int) $_GET["day"];
	if( $day > 31){
		$day = 31;
	} elseif($day < 1){
		$day = 1;
	}

	$month = (int) $_GET["month"];
	if( $month > 12){
		$month = 12;
	} elseif($month < 1){
		$month = 1;
	}

	$current_year = (int) date("Y");
	$year = (int) $_GET["year"];
	if($year > $current_year){
		$year = $current_year;
	} else {
		switch($table){
    	case "track":
    		if($year < 1960){
    			$year = 1960;
    		}    		
    		break;
    	case "country":
    		if($year < 1946){
    			$year = 1946;    			
    		}
    		break;
    	case "latin":
    		if($year < 1988){
    			$year = 1988;
    		}
    		break;
    	}
	}
	
	$birthday = $year . 	 "-" . $month . "-" . $day; 
    $offsetnum = $_GET["offset"]; 
	$offsetsize = $_GET['timetype'];
    $offsettype = $_GET["earlate"];  		
    $modifier = "+/-";
	if( $offsettype == "early" ) { $modifier = "-";	 } else { $modifier = "+"; }
    $offset = $modifier . $offsetnum . ' ' . $offsetsize; 
	
    //shamelessly borrowed from http://stackoverflow.com/questions/7029669/calculate-a-date-with-php
    $bdAsPOSIX = strtotime($birthday);
    $basecon = strtotime('-40 weeks', $bdAsPOSIX);
    $concept = strtotime($offset, $basecon);
    $conception = strftime('%Y/%m/%d', $concept);
	//end borrowing
				
	//retrieve song information	
	$trackquery = "SELECT * FROM `" . $table . "` WHERE `date` <= '" . $conception . "' ORDER BY `date` DESC";
	$trackresult = mysqli_query($con, $trackquery);
	$track = mysqli_fetch_array($trackresult);
	$song_id = $track['songid'];
	$artist = $track['artist'];
	$title = $track['title'];
	if(isset($track['songofyear'])){ $songofyear = $track['songofyear']; }
    else { $songofyear = FALSE; }
	$vid = $track['youtube'];		
	
	?>

	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

	<?php
	//Begin results		
	echo '<h2>You were (probably) conceived to "' . $title . '" by ' . $artist . '!</h2>';	
	
	if($songofyear){
		echo '<h3>This song was the #1 single for that year, wow!</h3><br>';
	}
	echo '<iframe width="640" height="390" src="//www.youtube.com/embed/' . $vid . '" frameborder="0" allowfullscreen></iframe><br>';
	$formatted_url = str_replace("&", "+", $current_url);

	echo '<a href="mailto:porktrack@gmail.com?subject=Video%20Issue&body=Video%20for%20' . $title . '%20by%20' . $artist  . '%20is%20broken!%0D%0A' . $formatted_url . '">report broken video/other issues</a><br>';
	
	//Begin sad attempts at virality
	echo 'Share your porktrack:&nbsp;&nbsp;<div class="fb-like" data-href="' . $current_url . '" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div> &nbsp';
	
	echo "<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-url=\"http://porktrack.com/\" data-text=\"I was (probably) conceived to " . $title . " by " . $artist . "! What's your #porktrack? -\">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script><br><br>"; 
	?>