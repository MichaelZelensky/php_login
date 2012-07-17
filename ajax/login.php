<?
/*
(c) Michael Zelensky miha.in 2012
check login ajax script
*/

include_once('../inc/start.inc');	

if (isset($_POST['login_email']) && isset($_POST['login_password'])) {
	 $email = mysql_real_escape_string($_POST['login_email']);
	 $pass = md5(mysql_real_escape_string($_POST['login_password']));
	$GV['DB']->query("SELECT * FROM `users` WHERE `email`='$email' AND `pass`='$pass'");
	if ($GV['DB']->num_rows() > 0 ) {
		$user = $GV['DB']->next_record();
		if ($user['confirmed']) {
			session_start();
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['user_email'] = $user['email'];
			$R = array('result'=>'ok','email'=>$email);
		} else {
			$R = array('result'=>'error', 'data'=>'not confirmed');
		}
	} else {
		$R = array('result'=>'error', 'data'=>'wrong email/password');		
	}
} else {
	$R = array('result'=>'error', 'data'=>'no data given');			
}
echo json_encode($R);
?>