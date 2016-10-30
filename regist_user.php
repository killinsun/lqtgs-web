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

        /* データベースに登録する。登録してたらスキップ */
         /* ユーザー情報の取得 */
        $token = $access_token['oauth_token'];
        $user = $connection->get("account/verify_credentials");
        $twitter_id = $user->screen_name;
        $twitter_image = $user->profile_image_url_https;

        try {
            $pdo = new PDO($dsn,$db_user,$db_pass);
            $stmt = $pdo->query("SELECT * FROM USER_TABLE WHERE USER_ID='${twitter_id}';"); 
            $count = $stmt -> rowCount();
            if($count == 0){
                $stmt = $pdo -> prepare("INSERT INTO USER_TABLE(USER_ID,ACCESS_TOKEN,IMAGE_URL) VALUES (:twitter_id,:access_token,:twitter_image)");
                $stmt -> bindParam(':twitter_id',$twitter_id, PDO::PARAM_STR);
                $stmt -> bindParam(':access_token',$token, PDO::PARAM_STR);
                $stmt -> bindParam(':twitter_image',$twitter_image, PDO::PARAM_STR);
                $stmt -> execute();

                #
                # OpenStack環境を作成するansibleを蹴る
                #
                require "kickAnsible/create_user_platform.php";
                $os_password = kick_ansible($twitter_id);
                $_SESSION['os_password'] = $os_password;
            };
        }catch (PDOException $e){
            header('Content-Type: text/plain; charset=UTF-8', true,500);
            echo "ERROR";
        }
         
    }
    header('Location: index.php');
?>

