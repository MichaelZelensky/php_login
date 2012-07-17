<?

/*
(c) Michael Zelensky miha.in 2012
AJAX user registration
*/

include_once('../inc/start.inc');

if (isset($_POST['register_email']) && isset($_POST['register_password'])) {
	$email = mysql_real_escape_string($_POST['register_email']);
	$pass = mysql_real_escape_string($_POST['register_pass']);
	$GV['DB']->query("SELECT * FROM `users` WHERE `email`='$email'");
	if ($GV['DB']->num_rows() > 0 ) {
		$R = array('result'=>'error', 'data'=>'email exists');
	} else {
		$confirm_code = random_gen(16);
		$data = array(
			'email' => $email,
			'pass' => md5($pass),
			'confirm_code' => $confirm_code,
			'created_at' => date('Y-m-d H:I:s')
		);
		/* send email here */
		$from = $GV['app_name'] ."<contact@planemo.ru>";
		$headers = "Content-type: text/plain; charset=UTF-8\r\n"
			."From: $from";
		$to = $email;
		$subject = $GV['app_name'] .' registration confirmation';
		$body = "Hello!
		
		You or someone else tried to register on ". $GV['app_name'] .". If it was not you, just ignore this email, otherwise please finish registration by clicking the following link: ". $GV['app_url'] . "/register/confirm.php?code=$confirm_code
		
		Best regards,\r\n
		". $GV['app_name'] ."\r\n\r\n
		". $GV['app_url'];
		//sending mail and saving to DB if OK
		if (mail($to, $subject, $body, $headers)) {
			$GV['DB']->insert('users',$data);
			$R = array('result'=>'ok');
		} else {
			$R = array('result'=>'error', 'data'=>'email could not be sent');			
		}
	}
} else {
	$R = array('result'=>'error', 'data'=>'no data given');			
}
echo json_encode($R);

?>