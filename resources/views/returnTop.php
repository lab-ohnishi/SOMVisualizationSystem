<?php
session_start();
if(isset($_SESSION["fromName"])){
  $message = 'ばいばい．';
}
else{
  $message = 'セッションがタイムアウトなのです．';
}
$_SESSION = array();
if(ini_get("session.use_cookies")){
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time()-42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="refresh" content="3;url=index.php">
<title>returnTop</title>
</head>
<body>
<div><?php echo "$message";?></div>
<div>3秒後にtopページへ移動します．</div>
<div>移動しない場合は<a href="/">こちら</a>を押してください.</div>
</body>
</html>
