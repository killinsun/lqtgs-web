<?php
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

        }catch (PDOException $e){
            header('Content-Type: text/plain; charset=UTF-8', true,500);
            echo "ERROR";
        };

        /* 送信されたデータの取得 */
        $quest_id = $_POST['QUEST_ID'];
        $answer = $_POST['answer'];

        #POSTでIDと答え、ユーザIDを受け取る
        #DBのIDを基に答えを照らし合わせ、一致していればOK
        #ついでにポイント類も取得する 
        $stmt = $pdo ->prepare("SELECT * FROM QUEST_TABLE WHERE QUEST_ID=:QUEST_ID and ANSWER=:answer");
        $stmt -> bindParam(":QUEST_ID",$quest_id, PDO::PARAM_STR);
        $stmt -> bindParam(":answer",$answer, PDO::PARAM_STR);
        $stmt -> execute();
        $count = $stmt -> rowCount();
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
        $clear_point   = $row['CLEAR_POINT'];
        $bonus_point   = $row['BONUS_POINT'];
        $demerit_point = $row['DEMERIT_POINT'];
        
        if($count > 0 ){
        #成功
            #成功ステータスを格納
            #ヒント使ってて既にテーブルにある場合はそのデータを編集する
            $stmt = $pdo -> prepare("INSERT INTO QUEST_STATUS_TABLE(QUEST_ID,USER_ID,IS_CLEARED) VALUES (:quest_id,:screen_name,true)");
            $stmt -> bindParam(':quest_id',$quest_id, PDO::PARAM_STR);
            $stmt -> bindParam(':screen_name',$screen_name, PDO::PARAM_STR);
            $stmt -> execute();


            #現在のポイントに集計
            $point = $point + $clear_point;
            
            #集計後のポイントを反映
            $stmt = $pdo -> prepare("UPDATE USER_TABLE SET POINT=:point WHERE USER_ID=:screen_name");
            $stmt -> bindParam(':point',$point, PDO::PARAM_STR);
            $stmt -> bindParam(':screen_name',$screen_name, PDO::PARAM_STR);
            $stmt -> execute();

            $_SESSION['result'] = $count;
        }else{
        #失敗
            $_SESSION['result'] = "失敗したやで";
        }; 
        $ref_url = "./q${quest_id}.php";
        echo $quest_id;
        echo $ref_url;
        header("Location:${ref_url}");
    };

?>
