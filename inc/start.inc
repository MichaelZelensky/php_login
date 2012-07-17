<?
/*

PHP Application start file, included in every php script
lists all includes and does all standard logic

NO OUTPUT, NO HTML, NO TEMPLATES here!

(c) Michael Zelensky 2012 
www.miha.in

*/

//includes
include_once ('mysql.class.php');
include_once ('func.inc');

//global vars
$GV = array(
	'app_name'=> 'PHP login',
	'app_url'=> 'http://miha.in',
	'db_host' => 'localhost',
	'db_name' => 'dbname',
	'db_user' => 'dbuser',
	'db_pass' => 'dbpass',
	'DB' => null
);

//starting session
if (isset($_REQUEST[session_name()])) session_start();

//connecting DB
$DB = new DB($GV['db_host'], $GV['db_name'], $GV['db_user'], $GV['db_pass']);
$DB->connect();
$GV['DB'] = $DB;

/* EOS =) */
?>