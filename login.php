<!DOCTYPE html>
<html lang="en">
<head>
  <?php echo md5("password");?>
	<title>mtng</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style.css">
	<script language="javascript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script language="javascript" type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js"></script>
	<script>
		$(function() {
			$("#login").validate({
				rules: {
					email: {
						required: true,
						email: true
					},
					password: {
						required: true,
					},
				},
				messages: {
					email: {
						required: "Please enter your email"
					},
          password: {
            required: "Please enter your password",
          }
				}
			});

			$("#signup").validate({
				rules: {
					first_name: "required",
					last_name: "required",
					phone: {
						required: true,
						intphone: true
					},
					email: {
						required: true,
						email: true
					},
					password: {
						required: true,
						minlength: 6
					},
          password_check: {
            required: true,
            equalTo: "#password"
          }
				},
				messages: {
					first_name: "Please enter your first name",
					last_name: "Please enter your last name",
					phone: {
						required: "Please enter your phone number"
					},
					email: {
						required: "Please enter your email"
					},
          password: {
            required: "Please enter a password",
            minlength: jQuery.format("At least {0} characters required!")
          },
          password_check: {
            required: "Please confirm your password",
            equalTo: "Your password and confirmation didn't match"
          }
				}
			});

	    $.validator.addMethod("intphone",
	            function(value, element) {
	                    return /^\++[0-9]/.test(value);
	            },
	    "Enter your phone number with the country code like this: +33632831832"
	    );
		});
	</script>
</head>

<body>
  <!-- login -->
	<div id="box">
		<h1>Login</h1>

		<form action='login-exec.php' id="login" method='post'>
      <input class="text_field" type="text" name="email" id="email" placeholder="email"></input>
      <input class="text_field" type="text" name="password" id="password" placeholder="password"></input>
      <input name="redirect" type="hidden" value="<?php echo $_GET['r'];?>">
      <input class='button' type='submit' value='login'></input>
		</form>
	</div>

  <!-- sign up -->
	<div id="box">
		<h1>or signup</h1>

		<form action='signup-exec.php' id="signup" method='post'>
      <input class="text_field" type="text" name="email" id="email" placeholder="email"></input>
      <input class="text_field" type="text" name="password" id="password" placeholder="password"></input>
      <input class="text_field" type="text" name="password_validation" id="password_validation" placeholder="confirm password"></input>
			<input class='text_field' type='text' name='first_name' placeholder='Your first name'></input>
			<input class='text_field' type='text' name='last_name' placeholder='Your last name'></input>
			<input class='text_field' type='text' name='phone' placeholder='Your phone number'></input>
      <input name="redirect" type="hidden" value="<?php echo $_GET['r'];?>">
      <input class='button' type='submit' value='sign up'></input>
		</form>
	</div>

</body>
</html>