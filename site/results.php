<html>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
	<link type="text/css" rel="stylesheet" href="stylesheet.css"/>
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	
<?php	 
	// Open connection
	$settings = parse_ini_file('../connect.ini');
	$host = $settings['host'];
	$user = $settings['user'];
	$pass = $settings['pass'];
	$db = $settings['db'];
	$con = mysqli_connect($host, $user, $pass, $db) or die ("Error: ". mysqli_error($con));	
	$current_url = "http://www.porktrack.com" . $_SERVER['REQUEST_URI'];
	
	//retrieve user input from previous page
	$modifier = "+/-";
	$month = $_GET["month"];
	$year = $_GET["year"];
	$day = $_GET["day"];	
	if(isset($_GET["list"]))
    { $table = $_GET["list"]; }
    else{ $table = "track";}

	$birthday = $year . "-" . $month . "-" . $day; 
    $offsetnum = $_GET["offset"]; 
	$offsetsize = $_GET['timetype'];
    $offsettype = $_GET["earlate"];  		
	if( $offsettype == "early" ) { $modifier = "-";	 } else { $modifier = "+"; }
    $offset = $modifier . $offsetnum . ' ' . $offsetsize; 
	
	//attempts to prevent user tomfoolery:	
	if (time() < strtotime($birthday))
	{ $birthday = time(); }
	
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
	
	echo '<meta property="og:site_name" content="Porktrack">
    <meta property="og:url" content="' . $current_url . '".>
    <meta property="og:title" content="Porktrack">
    <meta property="og:image" content="https://i1.ytimg.com/vi/' . $vid . '/hqdefault.jpg">
    <meta property="og:description" content="I was (probably) conceived to ' . $title . ' by ' . $artist . '! What\'s your porktrack?">';	 ?>
		
	<head>
	<title>Porktrack</title>
	</head>
	
	<div id="logo">
		<a href="http://porktrack.com/"><img src="images/logo.svg" id="logo"></a>
	</div>
	
<body>
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-51264238-1', 'porktrack.com');
	ga('send', 'pageview');
	</script>

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
	echo '<h1>You were (probably) conceived to "' . $title . '" by ' . $artist . '!</h1>';	
	
	if($songofyear){
		echo '<h3>This song was the #1 single for that year, wow!</h3><br>';
	}
	echo '<iframe width="640" height="390" src="//www.youtube.com/embed/' . $vid . '" frameborder="0" allowfullscreen></iframe><br>';
	
	
	echo '<a href="mailto:porktrack@gmail.com?subject=Video Issue&body=Video for ' . $title . ' by ' . $artist  . ' is broken!">report broken video/other issues</a><br>';
	?>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- bottom_results -->
	<ins class="adsbygoogle"
		style="display:inline-block;width:728px;height:90px"
		data-ad-client="ca-pub-4269697371888843"
		data-ad-slot="3176842285"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>	
	<?php
	echo "<br><br>";
	//Begin sad attempts at virality
	echo 'Share your porktrack:&nbsp;&nbsp;<div class="fb-like" data-href="' . $current_url . '" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div> &nbsp';
	
	echo "<div class=\"g-plus\" data-action=\"share\" data-annotation=\"none\"></div>
		<script type=\"text/javascript\">
		(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/platform.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		})();
		</script>";

	echo "<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-url=\"http://porktrack.com/\" data-text=\"I was (probably) conceived to " . $title . " by " . $artist . "! What's your #porktrack? -\">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script><br><br>";
	
	//Close connection
	mysqli_close($con);	
?>

	<div>
		<a href="http://www.porktrack.com/faq.php">FAQ</a> &#8226 
		<a href="http://www.twitter.com/porktrack">follow @porktrack</a> &#8226 
		<a href="http://www.twitter.com/literallyelvis">created by</a>
	</div>
</body>
</html>
