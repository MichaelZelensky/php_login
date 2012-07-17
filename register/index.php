<?

/*
PHP Login
(c) Michael Zelensky miha.in 2012
*/

include_once ('../inc/start.inc');

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

<h2>Register</h2>
<?
if (isset($_SESSION['user_id'])) {
	
?>
<p>You are already registered and logged in as <?=$_SESSION['user_email']?></p>
<? } else {
?>
<div id="registration_div">
	<form id="register_form">
		<table>
			<tr><td>Email:</td><td><input type="text" name="register_email"></td><td id="email_check"></td></tr>
			<tr><td>Password:</td><td><input type="password" name="register_password"></td><td id="password_check"></td></tr>
			<tr><td>Repeat password:</td><td><input type="password" name="register_password_repeat"></td><td id="repeat_password_check"></td></tr>
			<tr><td></td><td colspan="2"><input type="checkbox" id="user_agreement"> I have read and agree to the <a href="/terms.html">terms</a> of use</td></tr>
			<tr><td></td><td colspan="2"><a href="#" id="register_submit" class="form_button">Submit</a></td></tr>
		</table>
	</form>
</div>
<div style="display:none" id="confirmation_sent">
	An email with confirmation code was sent to you. Please wait for the email and finish the registration process by following the instructions in the email.
</div>
<?	
}
?>
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