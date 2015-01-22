<html>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <link type="text/css" rel="stylesheet" href="css/stylesheet.css"/>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
        // Open connection
        $settings = parse_ini_file('../connect.ini');
        $host = $settings['host'];
        $user = $settings['user'];
        $pass = $settings['pass'];
        $db = $settings['db'];
        $con = mysqli_connect($host, $user, $pass, $db) or die ("Error: ". mysqli_error($con));
        $current_url = "http://www.porktrack.com" . $_SERVER['REQUEST_URI'];
        $ip = $_SERVER['REMOTE_ADDR'];
        if($ip == "127.0.0.1"){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

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

        $birthday = $year . "-" . $month . "-" . $day;
        $offsetnum = $_GET["offset"];
        $offsetsize = $_GET['timetype'];
        $offsettype = $_GET["earlate"];
        $modifier = "+/-";
        if( $offsettype == "early" ) { $modifier = "-";     } else { $modifier = "+"; }
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

        echo '<meta property="og:site_name" content="Porktrack">
        <meta property="og:url" content="' . $current_url . '">
        <meta property="og:title" content="Porktrack">
        <meta property="og:image" content="https://i1.ytimg.com/vi/' . $vid . '/hqdefault.jpg">
        <meta property="og:description" content="I was (probably) conceived to ' . $title . ' by ' . $artist . '! What\'s your porktrack?">';
    ?>
    <head>
        <title>Porktrack</title>
        <script>
            function reportBrokenVideo(song_id, ip){
                xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("report").innerHTML = "issue reported!";
                    }
                }
                var url = "broken.php?id=" + song_id + "&ip=" + ip;
                xmlhttp.open("GET", url, true);
                xmlhttp.send();
            };
        </script>
    </head>
    <body>
      <div id="fb-root"></div>
      <script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&appId=429036220578125&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));</script>

      <a href="http://porktrack.com/"><img src="images/logo.svg" class="logo"></a>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-51264238-1', 'porktrack.com');
            ga('send', 'pageview');
        </script>
    <?php
        //Begin results
        echo '<p>You were (probably) conceived to "' . $title . '" by ' . $artist . '!</p>';

        if($songofyear){
            echo '<p><h4>This song was the #1 single for that year, wow!</h4></p>';
        }
        echo '<iframe class="youtube" src="//www.youtube.com/embed/' . $vid . '" frameborder="0" allowfullscreen></iframe><br>';
        echo '<p id="report" onclick="reportBrokenVideo(' . $song_id . ', \'' . $ip . '\')"><u>report video as broken</u></p>';
        echo '<div>
                  <div class="fb-like" data-href="http://www.porktrack.com" data-layout="button_count" data-action="recommend" data-show-faces="false" data-share="true"></div>
                  <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://porktrack.com/" data-text="I was (probably) conceived to ' . $title . ' by ' . $artist . '! What\'s your #porktrack?">Tweet</a>
             </div>';
        ?>
    <script>
        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
        if(!d.getElementById(id)){
            js=d.createElement(s);
            js.id=id;
            js.src=p+'://platform.twitter.com/widgets.js';
            fjs.parentNode.insertBefore(js,fjs);
            }
        }(document, 'script', 'twitter-wjs');
    </script>
        <div class="footer">
            <div class="moneymakers" style="max-height: 2em;">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- Porktrack Responsive -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-4269697371888843"
                     data-ad-slot="2586240680"
                     data-ad-format="auto"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </div>
            <a href="http://www.porktrack.com/faq.php">FAQ</a> &#8226
            <a href="http://www.twitter.com/porktrack">follow @porktrack</a> &#8226
            <a href="http://www.twitter.com/literallyelvis">by @literallyelvis</a>
            <a href="https://github.com/LiterallyElvis/Porktrack">
            <img style="position: absolute; bottom: 0; right: 0; border: 0;" src="images/forkme.png" alt="Pork me on GitHub" class="github" data-canonical-src="images/forkme.png"></a>
        </div>
    </body>
</html>
