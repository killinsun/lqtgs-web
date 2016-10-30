<?php
 
/* sessionsをクリア */
session_start();
session_destroy();
  
/* connect.phpへリダイレクト */
header('Location: ./index.php');
