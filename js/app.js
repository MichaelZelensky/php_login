$(function(){

	//login input text helpers
	var val1 = 'email';
	var val2 = 'password';
	$('input[name=login_email]')
		.val(val1)
		.focus(function(){
			$this = $(this);
			if ($this.val()==val1) {
				$this.removeClass('login_inactive').addClass('login_active');
				$this.val('');
			}
		})
		.blur(function(){
			if (!$this.val()){
				$this.removeClass('login_active').addClass('login_inactive');
				$this.val(val1);
			}
		});
	$('input[name=login_password]')
		.val(val2)
		.focus(function(){
			$this = $(this);
			if ($this.val()==val2) {
				$this.removeClass('login_inactive').addClass('login_active');
				$this.val('');
			}
		})
		.blur(function(){
			if (!$this.val()){
				$this.removeClass('login_active').addClass('login_inactive');
				$this.val(val2);
			}
		});
		
	//login submit
	$('#form_login_submit').click(function(){
		var $email = $('input[name=login_email]');
		var $password = $('input[name=login_password]');
		if ($email.val() && $email.val()!=val1 && $password.val() && $password.val()!=val2){
			$('#login_loader').show();
			$.ajax({
				type: "POST",
				url: "/ajax/login.php",
				data: $('#form_login').serialize(),
				success: function(data) {
					var jsondata = jQuery.parseJSON(data);
					if (jsondata.result=='ok') {
						$('#login').html($('#loggedin').html());
						$('#user_email').html(jsondata.email);
						ul();
					} else if(jsondata.data == 'not confirmed') {
						$('#not_confirmed_error').show();
						$('#login_error').hide();
					} else {
						$('#not_confirmed_error').hide();
						$('#login_error').show();
					}
					$('#login_loader').hide();
				}
			});
		}
	});
	ul();
	//register
	var loader_img = '<img src="/img/ajax-loader.gif">';
	var check_ok = '<span style="color:green">OK</span>';
	var check_noemail = '<span style="color:red">Must be an email!</span>';
	var check_fail = '<span style="color:red">Already exists! <a href="/register/resend.php">Resend</a> confirmation?</span>';
	var check_badpass = '<span style="color:red">Not the same!</span>';
	var old_email_val = '';
	var old_pass_val = '';
	var old_repeat_pass_val = '';
	//check password
	$('input[name=register_password]').blur(function(){
		//changed?
		if (this.value == old_pass_val) {
			return;
		} else {
			old_pass_val = this.value;
		}
		if (this.value) {
			$('#password_check').html(check_ok);
		} else {
			$('#password_check').html('');
		}
		if ($('input[name=register_password_repeat]').val()) {
			if (old_repeat_pass_val == this.value) {
				$('#repeat_password_check').html(check_ok);
			} else {
				$('#repeat_password_check').html(check_badpass);
			}
		}
	});
	//check password repeat
	$('input[name=register_password_repeat]').blur(function(){
		//changed?
		if (this.value == old_repeat_pass_val) {
			return;
		} else {
			old_repeat_pass_val = this.value;
		}
		if (this.value == old_pass_val) {
			$('#repeat_password_check').html(check_ok);
		} else {
			$('#repeat_password_check').html(check_badpass);
		}
	});
	//check email value
	$('input[name=register_email]').blur(function(){
		//changed?
		if (this.value == old_email_val) {
			return;
		} else {
			old_email_val = this.value;
		}
		//email?
		if(checkemail(this)){
			$('#email_check').html(loader_img);
			//exists already?
			$.ajax({
				type: 'POST',
				url: '/ajax/check.php',
				data: 'email=' + this.value,
				success: function(data){
					var jdata = jQuery.parseJSON(data);
					if (jdata.result=='ok') {
						$('#email_check').html(check_ok);
					} else {
						$('#email_check').html(check_fail);
					}
				}
			});
		} else {
			$('#email_check').html(check_noemail);
		};
	});
	//register submit
	$('#register_submit').click(function(){
		if (
			$('#user_agreement').is(':checked') &&
			$('#email_check').html() == check_ok &&
			$('#repeat_password_check').html() == check_ok 
		){
			$.ajax({
				type: 'POST',
				url: '/ajax/register.php',
				data: $('#register_form').serialize(),
				success: function(data){
					var jdata = jQuery.parseJSON(data);
					if (jdata.result=='ok') $('#registration_div').html($('#confirmation_sent').html());
				}
			});
		}
	});
	$('#reminder_submit').click(function(){$('#ajax-loader-gif').show();$('#form_reminder').submit()});
	$('#password_reset_submit').click(function(){$('#form_password_reset').submit()});
	$('#confirmation_resend_submit').click(function(){$('#form_confirmation_resend').submit()});
	$('h1').click(function(){location.href='/'});
})
function ul(){
	//logout
	$('#user_logout').click(function(){
		$.ajax({
			url: "/ajax/logout.php",
			success: function(){
				document.location.reload();
			}
		});
	});
}
function checkemail(obj){
	var str=obj.value;
	var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	if (filter.test(str)) {
		testresults=true;
	} else{
		testresults=false
	}
	return (testresults)
}