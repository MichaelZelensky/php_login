<?

/*
php login system

(c) Michael Zelensky miha.in 2012
*/

include_once ('inc/start.inc');

if (isset($_GET['install'])) {

	if($GV['DB']->query("CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email` text,
  `pass` text,
  `confirmed` tinyint(1) NOT NULL default '0',
  `confirm_code` varchar(16) default NULL,
  `confirmed_at` datetime default NULL,
  `password_reset_code` varchar(16) default NULL,
  `created_at` datetime default NULL,
  `updated_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;
	")) {
		$success = true;
	} else {
		$success = false;
	}

}

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
<h2>Installation</h2>
<p>This script will automatically install database. Assuming that you have specified host, user, and password in inc/start.inc file.</p>
<? if (!isset($success)) {?><p style="padding: 20px 0"><a class="form_button" href="?install=yes" id="install_button" style="padding:12px 20px; font-size:xx-large">Install</a>
<?} elseif ($success) { ?>
	<span style="border: 1px solid green; padding: 10px; color:green; font-weight: bold; border-radius: 4px">Database installed!</span>
<? } else {?>
	<span style="border: 1px solid red; padding: 10px; color:red; font-weight: bold; border-radius: 4px">Error: database not installed!</span>;
<? } ?>
</p>
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