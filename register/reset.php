<?

/*
PHP Login

(c) Michael Zelensky miha.in 2012
*/

include_once ('../inc/start.inc');

$success = false;
$reset = false;

$code = mysql_real_escape_string($_GET['code']);

$GV['DB']->query("SELECT * FROM `users` WHERE `password_reset_code`='$code'");
if ($GV['DB']->num_rows() > 0) {
	$success = true;
	if (isset($_POST['password_reset'])) {
		$u = $GV['DB']->next_record();
		$pass = md5($_POST['password_reset']);
		$GV['DB']->query("UPDATE `users` SET `pass`='$pass', `password_reset_code`=NULL WHERE `id`='". $u['id'] ."'");
		$reset = true;
	}
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

<h2>Reset password</h2>
<?
if (isset($_SESSION['user_id'])) {
?>
<p>You are already registered and logged in as <?=$_SESSION['user_email']?></p>
<? } else if ($reset) {
?> Your password has changed. You can login.
<? } else if ($success) {
?>
<form method="POST" id="form_password_reset">
	<table>
		<tr>
			<td>New password:</td>
			<td><input type="password" name="password_reset"></td>
		</tr>
		<tr>
			<td>Repeat new password:</td>
			<td><input type="password" name="password_reset_repeat"></td>
		</tr>
		<tr>
			<td></td>
			<td><a href="#" id="password_reset_submit" class="form_button">Submit</a></td>
		</tr>
	</table>
</form>
<?	
} else {
?>
<span style="color:red">Error: password reset code was not found.</span>
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