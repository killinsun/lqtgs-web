<?php
    #クエスト識別番号
    $quest_id = 0;
    session_start();
     
    require '../twitteroauth/autoload.php';
    require '../env.php';
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
            
            $stmt = $pdo ->query("SELECT USE_HINT,IS_CLEARED FROM QUEST_STATUS_TABLE WHERE USER_ID='${screen_name}' AND QUEST_ID=${quest_id}");
            $row = $stmt -> fetch(PDO::FETCH_ASSOC);
            $is_cleared = $row['IS_CLEARED'];
            $use_hint = $row['USE_HINT'];

            if($use_hint == 1){
                $use_hint = "<p class='notice'>You Used HINT. Apply Demerit point.</p>";
            }else{
                $use_hint = null;
            };
        }catch (PDOException $e){
            header('Content-Type: text/plain; charset=UTF-8', true,500);
            echo "ERROR";
        };
    }else{
        header("Location:../logout.php");
    };
?>
<!DOCTYPE HTML>
<!--
	Tessellate by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
    <?php include('header.php'); ?>

		<!-- First -->
			<section id="first" class="main">
				<header>
					<div class="container">
                        <div id="quest_info" class="content style4 ">
                        <?php
                                $stmt = $pdo ->query("SELECT * FROM QUEST_TABLE WHERE QUEST_ID=${quest_id}");
                                $row = $stmt -> fetch(PDO::FETCH_ASSOC);
                                echo "<h2>${row['QUEST_NAME']}</h2>${use_hint}";
                                if(!empty($_SESSION['result'])){
                                    if($_SESSION['result'] > 0){
                                        echo "Congraturation!!!</br>";
                                    }else{
                                        echo "zannnen</br>";
                                    };
                                    $_SESSION['result'] = null;
                                };
                                include($row['BODY']);
                        ?>
                        </div>

                        <div id="ans" class="content style4 featured">
                            <div class="container 50%">
                                <form class="ans_form" method="POST" action="check.php">
                                    <?php 
                                    if(!empty($is_cleared)){
                                        echo "<p class='clear_text'>Congraturation. QUEST CLEARED.</p>";
                                    }else{
                                        echo "<input type='text' name='answer' placeholder='Input answer'>";
                                        echo "<input type='submit'  value='Send answer'>";
                                        echo "<input type='hidden' name='QUEST_ID' value='${quest_id}'>";
                                    };?>
                                </form>
                            </div>
                        </div>

                        <div id="winner" class="content style4">
                            <h2>WINNER</h2>
                            <?php
                                $counter = 0;
                                $stmt = $pdo ->query("SELECT USER_TABLE.USER_ID,USER_TABLE.IMAGE_URL FROM USER_TABLE,QUEST_STATUS_TABLE WHERE QUEST_STATUS_TABLE.QUEST_ID=${quest_id} AND QUEST_STATUS_TABLE.IS_CLEARED=true");
                                while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
                                    $counter = $counter + 1;
                                    echo "${counter}.";
                                    echo "<a href='https://twitter.com/${row['USER_ID']}'>";
                                    echo "<img src='${row['IMAGE_URL']}'>";
                                    echo "@${row['USER_ID']}";
                                    echo "</a></br>";
                                };

                            ?>
                        </div>
                    </div>
                </header>
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


	</body>
</html>
