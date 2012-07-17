<?

/*
(c) Michael Zelensky miha.in 2012
AJAX check email
*/

include_once('../inc/start.inc');

if (isset($_POST['email'])) {
	$email = mysql_real_escape_string($_POST['email']);
	$GV['DB']->query("SELECT * FROM `users` WHERE `email`='$email'");
	if ($GV['DB']->num_rows() > 0 ) {
		$R = array('result'=>'error', 'data'=>'email exists');		
	} else {
		$R = array('result'=>'ok', 'email'=>$email);
	}
} else {
	$R = array('result'=>'error', 'data'=>'no data given');			
}
echo json_encode($R);

?>