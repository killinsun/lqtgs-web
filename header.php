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
    }else if($_SERVER["REQUEST_URI"]!= "/index.php"){
        header('Location: /index.php');
    };
?> 
        
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
