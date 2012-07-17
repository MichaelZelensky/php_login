<?

/*
PHP Login

(c) Michael Zelensky miha.in 2012
*/

include_once ('../inc/start.inc');

if (isset($_POST['email'])) {
	$error_code = 0; //0 - ok; 1 - no email; 2 - not confirmed; 3 - errors while sending mail (system)
	$email = mysql_real_escape_string($_POST['email']);
	$GV['DB']->query("SELECT * FROM `users` WHERE `email`='$email'");
	if ($GV['DB']->num_rows() > 0) {
		$u = $GV['DB']->next_record();
		if ($u['confirmed']) {
			$error_code = 0;
			$from = $GV['app_name'] ."<contact@planemo.ru>";
			$headers = "Content-type: text/plain; charset=UTF-8\r\n"
				."From: $from";
			$to = $email;
			$subject = $GV['app_name']. " password reset instructions";
			$code = random_gen(16);
			$body = "Hello!\r\n\r\nTo reset your password, please follow the link: ". $GV['app_url'] ."/register/reset.php?code=$code";
			if (mail($to, $subject, $body, $headers)) {
				$GV['DB']->query("UPDATE `users` SET `password_reset_code`='$code' WHERE `id`=". $u['id']);
			} else {
				$error_code = 3;
			}
		} else {
			$error_code = 2;
		}
	} else {
		$error_code = 1; //no user
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

<h2>Password reminder</h2>
<?
if (isset($_SESSION['user_id'])) {
?>
<p>You are already registered and logged in as <?=$_SESSION['user_email']?></p>
<?	
} else {
	if (!isset($error_code) || $error_code > 0) {
?>
<p>Please specify your email address that you used for registration, and you will receive an email with instructions for setting a new password.</p>
<form method="post" id="form_reminder">
<table>
	<tr>
		<td>Email:</td>
		<td><input type="text" name="email" value="<?=htmlspecialchars($_POST['email'])?>"><?
			if ($error_code == 1) {
				echo '<br><span style="color:red">Email not found!<span>';
			} elseif ($error_code == 2) {
				echo '<br><span style="color:red">Email was not confirmed! Complete registration by email confirmation. <a href="/register/resend.php">Resend confirmation code</a><span>';
			} elseif ($error_code == 3) {
				echo '<br><span style="color:red">System error: email cannot be sent.<span>';
			}
		?></td>
	</tr>
	<tr>
		<td></td>
		<td><a href="#" id="reminder_submit" class="form_button">Submit</a> <img src="/img/ajax-loader.gif" style="display:none" id="ajax-loader-gif"></td>
	</tr>
</table>
</form>
<?
	} elseif ($error_code == 0){
		echo '<p>An email with instructions for setting a new password was sent to your email address. Follow the instructions.</p>';
	} 
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