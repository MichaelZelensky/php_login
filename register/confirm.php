<?

/*
PHP Login

(c) Michael Zelensky miha.in 2012
*/

include_once ('../inc/start.inc');

$success = false;

$code = mysql_real_escape_string($_GET['code']);

$GV['DB']->query("SELECT * FROM `users` WHERE `confirm_code`='$code'");
if ($GV['DB']->num_rows() > 0) {
	$u = $GV['DB']->next_record();
	$GV['DB']->query("UPDATE `users` SET `confirm_code`=NULL, `confirmed`='1', `confirmed_at`='". date('Y-m-d H:I:s') ."' WHERE `id`='". $u['id'] ."'");
	$success = true;
}

?><!DOCTYPE html>
<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script src="/js/app.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/css/style.css" type="text/css"/>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<h1>PHP Login</h1>
<p><a href="http://www.miha.in/products/php_login">Home page</a> <a href="https://github.com/MichaelZelensky/php_login">PHP Login on GitHUB</a>

<h2>Email confirmation</h2>
<?
if (isset($_SESSION['user_id'])) {
	
?>
<p>You are already registered and logged in as <?=$_SESSION['user_email']?></p>
<? } else if ($success) {
?>
You have successfully registered! Now you can login.
<?	
} else {
?>
Error: confirmation code was not found.
<?
}
?>
<p class="c">(c) 2012 <a href="http://www.miha.in">Michael Zelensky</a></p>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
try {
var pageTracker = _gat._getTracker("UA-2929326-25");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>