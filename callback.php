<?php
session_start();
 
require 'twitteroauth/autoload.php';
require 'env.php';
use Abraham\TwitterOAuth\TwitterOAuth;
 
/* Request Token、Request Secretをsessionから取得 */
$request_token = array();
$request_token['oauth_token'] = $_SESSION['oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
 
/* Request Tokenが間違っている場合はclearsessions.phpへリダイレクト */
if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
    // 中断する! 何かが違っています。
    $_SESSION['oauth_status'] = 'oldtoken';
    header('Location: ./clearsessions.php');
}
 
/* TwitterOAuthを生成（redirect.phpの時とはパラメータが違う事に注意） */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
 
/* Access Token、Access Secretを生成 */
$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
 
/* Access Token、Access Secretをsessionに保存 */
/* サービスのログインにTwitterアカウントを使用する場合はAccess Token、Access Secretをデータベースに保存する */
$_SESSION['access_token'] = $access_token;
 
/* 以降ではRequest Token、Request Secretは使用しないのでsessionから削除 */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);
 
header('Location: regist_user.php');
