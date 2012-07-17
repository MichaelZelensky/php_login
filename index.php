<?

/*
php login system

(c) Michael Zelensky miha.in 2012
*/

include_once ('inc/start.inc');

?><!DOCTYPE html>
<html>
<head>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
	<script src="/js/app.js" type="text/javascript"></script>
	<link rel="stylesheet" href="/css/style.css" type="text/css" />
</head>
<body>
<h1>PHP Login</h1>
<p><a href="http://www.miha.in/products/php_login">Home page</a> <a href="https://github.com/MichaelZelensky/php_login">PHP Login on GitHUB</a>

<?
if (!isset($_SESSION['user_id'])) {
	$display = "display:none";
?>
<div id="login">
	Login: 
	<br>
	<form id="form_login">
		<input type="text" name="login_email" class="login_inactive"/>
		<input type="password" name="login_password" class="login_inactive"/>
		<a href="#" id="form_login_submit" class="form_button">Submit</a> <img src="/img/ajax-loader.gif" style="display:none" id="login_loader">
		<div id="login_error" style="display:none" class="login_error">Wrong email/password!</div>
		<div id="not_confirmed_error" style="display:none" class="login_error">Registration not complete! <a href="/register/resend.php">Resend confirmation email</a></div>
		<br>
		<a href="/register/reminder.php">Forgot password?</a> <a href="/register/">Register</a>
	</form>
</div>
<? } else {
	$display = "";
}

?>
<div id="loggedin" style="<?=$display?>">
	Welcome, <a href="#" id="user_email"><?=$_SESSION['user_email']?></a>! <a href="#" id="user_logout" class="logout_link">Logout</a>
</div>
<p class="c">(c) 2012 <a href="http://www.miha.in">Michael Zelensky</a></p>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-2929326-25");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>