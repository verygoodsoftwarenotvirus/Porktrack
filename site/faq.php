<!DOCTYPE HTML>
<html>
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<!-- mostly borrowed from http://dronade.com/simple-faq-template/ -->
<head>
	<link type="text/css" rel="stylesheet" href="stylesheet.css"/>
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<title>Porktrack FAQ</title>

	<style type="text/css">
	.faq {
		cursor: hand;
		cursor: pointer;
		border: 1px solid #4C4349;
		width: 600px;
		margin-top: 10px;
		margin-left: auto;
		margin-right: auto;
		padding: 10px;
	}
	.ans { 
		display:none;
		margin-top:7px;
		text-align: left;
	}
	</style>
	<script type="text/javascript">
	function toggle(Info) {
	var CState = document.getElementById(Info);
	CState.style.display = (CState.style.display != 'block')
						? 'block' : 'none';
	}
	</script>
</head>

<body>
	<h1>FAQ</h1>

	<div class="faq" onclick="toggle('faq1')">
		What the heck is a porktrack?
		<div id="faq1" class="ans"><hr>
			Simply put, it is the <a href="http://www.billboard.com/charts/hot-100">Billboard Hot 100</a> song for the week of your estimated conception. The idea is that if any song, in the indescribably vast collection of songs human beings have made in our years as a creative species, is likely to be the song your parents <em>did the deed</em> &nbsp to, it's the #1 song in the country at that particular time.
		</div>
	</div>

	<div class="faq" onclick="toggle('faq2')">
		How accurate is it?
		<div id="faq2" class="ans"><hr>
			Not very. The site takes your submitted birthdate, subtracts 40 weeks, then adds or subtracts whatever offset you put in if you check the peculiar birth box. Real doctors can only kind of estimate the date of conception, and they have real, tangible data to work with. This is just a computer somewhere.
		</div>
	</div>

	<div class="faq" onclick="toggle('faq3')">
		Why make this?
		<div id="faq3" class="ans"><hr>
			I really wanted to learn how to make a website. Also it amuses me greatly to see how people react to their porktracks.
		</div>
	</div> 

	<div class="faq" onclick="toggle('faq4')">
			My parents don't listen to this kind of music. What about me?
		<div id="faq4" class="ans"><hr>
			<center><img src="images/iono.gif"></center>
		</div>
	</div>
 
	<div class="faq" onclick="toggle('faq5')">
		Is there an app coming?
	<div id="faq5" class="ans"><hr>
		<iframe width="600" height="358" src="//www.youtube.com/embed/k02kGB7LJY0" frameborder="0" allowfullscreen></iframe>
		</div>
	</div>

</body>
</html>