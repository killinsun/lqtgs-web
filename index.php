<?php
    session_start();
     
    require 'twitteroauth/autoload.php';
    require 'env.php';
    use Abraham\TwitterOAuth\TwitterOAuth;
     
     
    /* Access Token、Access Secretをsessionから取得 */
    if(!empty($_SESSION['access_token'])){
        $access_token = $_SESSION['access_token'];
        /* TwitterOAuthを生成（またまたパラメータが違う...パラメータによって使用できる関数を制御しています） */
        $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
         
        /* ユーザー情報の取得 */
        $user = $connection->get("account/verify_credentials");
        $screen_name= $user->screen_name;
        $image_url= $user->profile_image_url_https;
        try {
            $pdo = new PDO($dsn,$db_user,$db_pass);
            $stmt = $pdo ->query("SELECT POINT FROM USER_TABLE WHERE USER_ID='${screen_name}'");
            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                $point = $row['POINT'];
            };

        }catch (PDOException $e){
            header('Content-Type: text/plain; charset=UTF-8', true,500);
            echo "ERROR";
        };
    };
?>
<!DOCTYPE HTML>
<!--
	Tessellate by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>LinuQuest GiraffeStyle.</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
	</head>
	<body>
        <!-- Header -->
            <section id="menu">
                <div id="left">
                    <a href="./" class="scrolly"><img src="./images/logo_short_white.png" height=50px></a>
                    <a href="#first" class="scrolly">ABOUT</a>
                    <a href="#quest" class="scrolly">QUEST</a>
                    <a href="#ranking" class="scrolly">RANKING</a>
                    <a href="#rule" class="scrolly">RULE</a>
                </div>
                <div id="right">
                    <?php
                        if(!empty($access_token)){
                            echo "<div>";
                            echo "<ul id='profile'>";
                            echo "<li><a href='logout.php'>Logout</a></li>";
                            echo "<li>${point}pt</li>";
                            echo "<li><a href='https://twitter.com/${screen_name}'>";
                            echo "<img src='${image_url}'>";
                            echo "@${screen_name}";
                            echo "</a></li>";
                            echo "</ul>";
                            echo "</div>";
                        };
                    ?>    
                   
                </div>
            </section>

		<!-- Main Title -->
			<section id="title" class="dark">
				<header>
                    <img src="./images/logo_full_white.png">
					<p>Can you defeat me?</p>
				</header>
				<footer>
					<a href="#quest" class="button scrolly">Start now.</a>
				</footer>
			</section>

		<!-- First -->
			<section id="first" class="main">
				<header>
					<div class="container">
						<h2>Welcome to your QUEST.</h2>
						<p>"LinuQuest Giraffe Style" wants a person with confidence in the your IT skill. <br />
                            We prepared some quest.</br>
                            There are every one that are issue of our experience.</br>
                            Can you solve and defeat me?:)</br>
					</div>
				</header>
				<div id="quest" class="content dark style1 featured">
					<div class="container">
						<div class="row">
							<div class="12u">
                                <header>
                                    QUEST
                                </header>
								<footer>
                                    <?php if(!empty($access_token)){
                                            $stmt = $pdo ->query("SELECT * FROM QUEST_TABLE");
                                            echo "<table>";
                                            echo "<tr>";
                                            echo "<th>N0.</th>";
                                            echo "<th>CATEGORY</th>";
                                            echo "<th>QUEST NAME</th>";
                                            echo "<th>CLEAR POINT</th>";
                                            echo "<th>BONUS POINT</th>";
                                            echo "<th>WINNER</th>";
                                            echo "</tr>";
                                            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                                                echo "<tr>";
                                                echo "<td>${row['QUEST_ID']}</td>";
                                                echo "<td>${row['CATEGORY']}</td>";
                                                echo "<td><a href='${row['URL']}'>${row['QUEST_NAME']}</a></td>";
                                                echo "<td>${row['CLEAR_POINT']}</td>";
                                                echo "<td>${row['BONUS_POINT']}</td>";
                                               
                                                echo "<td>";
                                                $stmt2 = $pdo -> query("SELECT USER_TABLE.IMAGE_URL,USER_TABLE.USER_ID FROM USER_TABLE,QUEST_STATUS_TABLE WHERE QUEST_STATUS_TABLE.QUEST_ID=${row['QUEST_ID']} AND QUEST_STATUS_TABLE.IS_CLEARED=true");
                                                while($r = $stmt2 -> fetch(PDO::FETCH_ASSOC)){
                                                    $usr = $r['USER_ID'];
                                                    $img = $r['IMAGE_URL'];
                                                    echo "<a href='https://twitter.com/$usr'>";
                                                    echo "<img src='$img' class='winner_img'>";
                                                    echo "</a>";
                                                 };
                                                echo "</td>";
                                                echo "</tr>";
                                            };
                                            echo "</table>";
                                        }else{
                                            echo "<a href='redirect.php' class='button'>Signin with Twitter</a>";
                                        };
                                    ?>    
								</footer>
							</div>
						</div>
					</div>
				</div>
			</section>
		<!-- Ranking -->
			<section id="ranking" class="main">
				<div class="content dark style2 featured">
					<div class="container">
						<div class="row">
							<div class="12u">
                                <header>
                                    Ranking
                                </header>
								<footer>
                                <?php
                                    $pdo = new PDO($dsn,$db_user,$db_pass);
                                    $stmt = $pdo ->query("SELECT  * FROM USER_TABLE ORDER BY POINT DESC");
                                ?>

                                    <table>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name.</th>
                                            <th>Point</th>
                                        </tr>
                                        <?php
                                            $number=0;
                                            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                                                $number = $number +1;
                                                echo "<tr>";
                                                echo "<td>${number}</td>";
                                                echo "<td><a href='https://twitter.com/${row['USER_ID']}'><img src='${row['IMAGE_URL']}'>@${row['USER_ID']}</a></td>";
                                                echo "<td>${row['POINT']}</td>";
                                                echo "</tr>";
                                            };
                                        ?>
                                    </table> 
								</footer>
							</div>
						</div>
					</div>
				</div>
			</section>

            <!-- Rule -->
            <section id="rule" class="main">
                    <div class="content dark style3 featured">
                      <div class="container">
                        <p>Rules</p>
                      </div>
                      <footer>
                            <ul>
                                <li>Don't share the flag.</li>
                                <li>Don't brute force attack the server or problems.</li>
                            </ul>
                      </footer>
                    </div>
            </section>
                        

		<!-- Footer -->
			<section id="footer">
				<ul class="icons">
					<li><a href="https://twitter.com/Kill_In_Sun" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
					<li><a href="https://www.facebook.com/Sefinannnnn" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
				</ul>
				<div class="copyright">
					<ul class="menu">
						<li>&copy; kill_in_sun. All rights reserved.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>
				</div>
			</section>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
