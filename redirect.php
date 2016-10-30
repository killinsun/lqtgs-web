<?php
session_start();
 
require 'twitteroauth/autoload.php';
require 'env.php';
use Abraham\TwitterOAuth\TwitterOAuth;
 
/* TwitterOAuthを生成 */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
/* Request Token、Request Secretを生成 */
$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
 
/* Request Token、Request Secretをsessionに保存 */
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
/* Twitterと連携を行うURLを生成 */
$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
 
/* Twitterと連携を行うURLにリダイレクト */
header('Location: ' . $url);
