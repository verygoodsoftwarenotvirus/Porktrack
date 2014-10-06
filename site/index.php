<html>  
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon"/>
	<link type="text/css" rel="stylesheet" href="stylesheet.css"/>
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<head>
		<title>Porktrack</title>		
		<meta property="og:site_name" content="Porktrack">
		<meta property="og:url" content="http://www.porktrack.com">
		<meta property="og:title" content="Porktrack">
		<meta property="og:image" content="http://www.porktrack.com/images/avatar.png">
		<meta property="og:description" content="What is your porktrack?">	
	</head>
<body>
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-51264238-1', 'porktrack.com');
	ga('send', 'pageview');
	</script>	
    
	<div id="logo">
		<a href="http://porktrack.com/"><img src="images/logo.svg" id="logo"></a>
	</div>	
    
	<div class="special">
		<script type="text/javascript" language="JavaScript">
			function HidePart(d) { document.getElementById(d).style.display = "none";  }
			function ShowPart(d) { document.getElementById(d).style.display = "inline"; }
			function CheckboxChecked(b,d)
			{  if(b) { ShowPart(d); }
			   else  { HidePart(d); }	}
	        
	        function populate(list_select, year_select){
	          var currentYear = new Date().getFullYear()
	          var list_select = document.getElementById(list_select);
	          var year_select = document.getElementById(year_select);
	          //var minYear = 1945;
	          year_select.innerHTML = "";
	          if(list_select.value == "track"){ minYear = 1959;
	          } else if(list_select.value == "country") { minYear = 1945;
	          } else if(list_select.value == "latin") { minYear = 1987; }
	          
	          for(var i = minYear; i < currentYear; i++){
	              var newOption = document.createElement("option");
	              newOption.value = i.toString();
	              newOption.innerHTML = i.toString();
	              year_select.options.add(newOption);}}         
		</script>
		
		<form method="GET" action="results.php">
			<div class="birthday">
		        Choose a list:
		        <select name="list" id="list" onchange="populate(this.id, 'year');ShowPart('button')">
		            <option value="" selected></option>
		            <option value="track">Hot 100</option>
		            <option value="country">Country</option>
		            <option value="latin">Latin</option>
		        </select>	        
					<br><br>Enter your birthdate:<br> 
					<select name="year" id="year"></select>
					<select name="month">
						<option value="01" >January</option>
						<option value="02" >February</option>
						<option value="03" >March</option>
						<option value="04" >April</option>
						<option value="05" >May</option>
						<option value="06" >June</option>
						<option value="07" >July</option>
						<option value="08" >August</option>
						<option value="09" >September</option>
						<option value="10" >October</option>
						<option value="11" >November</option>
						<option value="12" >December</option>
					</select>
					<select name="day">
						<option>01</option><option>02</option><option>03</option>
						<option>04</option><option>05</option><option>06</option>
		                <option>07</option><option>08</option><option>09</option>
						<option>10</option><option>11</option><option>12</option>
						<option>13</option><option>14</option><option>15</option>
						<option>16</option><option>17</option><option>18</option>
						<option>19</option><option>20</option><option>21</option>
						<option>22</option><option>23</option><option>24</option>
						<option>25</option><option>26</option><option>27</option>
						<option>28</option><option>29</option><option>30</option>
						<option>31</option>
					</select>
			</div>		
     
		<br><br><br>
		<p id="specialCheck"><br><br>
			<input type="checkbox" value="no" onclick="CheckboxChecked(this.checked,'reveal')">
			I had a peculiar birth!
		</p>		

		<div id="reveal" style="display:none">
			<p>
				I was born
				<input type="number" min="0" value="0" max="365" class="birthday" name="offset">
				<select name="timetype">
					<option>days</option>
					<option>weeks</option>
				</select>
				<select name="earlate">
					<option>early</option>
					<option>late</option>
				</select>
			</p>
		</div>		
        
		<div class="submit">
            <input type="submit" id='button' class="btn-style" value="Let's Pork!" style="display:none"/><br><br>
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
			<!-- bottom_main -->
			<ins class="adsbygoogle"
				style="display:inline-block;width:468px;height:60px"
				data-ad-client="ca-pub-4269697371888843"
				data-ad-slot="6409510284"></ins>
			<script>
			(adsbygoogle = window.adsbygoogle || []).push({});
			</script>
        </div>
		</form>
	
		<script type="text/javascript">
			CheckboxChecked(document.myform.checked,'reveal');
		</script>
	</div>
	
	<div class="info">
		<a href="http://www.porktrack.com/faq.php">FAQ</a> &#8226 
		<a href="http://www.twitter.com/porktrack">follow @porktrack</a> &#8226 
		<a href="http://www.twitter.com/literallyelvis">created by</a>
		<a href="https://github.com/LiterallyElvis/Porktrack">
		<img style="position: absolute; bottom: 0; right: 0; border: 0;" src="images/forkme.png" alt="Pork me on GitHub" data-canonical-src="images/forkme.png"></a>
	</div>
