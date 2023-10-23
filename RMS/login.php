<?php
include 'database/php/conn.php';
include 'database/php/session.php';

$error = '';
$email = '';
if($conn)
{
//Login
	if (isset($_POST['login_btn'])) 
	{
			$email = $_POST['login_email'];
			$password = $_POST['login_password'];

			if (!empty($email) && !empty($password)) 
			{
				// Validate email and password for invalid characters
				$specchars = '"%\'*;<>?^`{|}~/\\#=&';
				$pat = preg_quote($specchars, '/');
				if (preg_match('/['.$pat.']/', $email) || preg_match('/['.$pat.']/', $password)) 
				{
					$error = 'Invalid characters in email or password';
				} 
				
				else 
				{
					$s_email = mysqli_real_escape_string($conn, $email);
					$s_password = mysqli_real_escape_string($conn, $password);

					$check_email = mysqli_query($conn, "SELECT * FROM `users` WHERE `Email` = '$s_email'");
					$row_email = mysqli_num_rows($check_email);

					if ($row_email > 0) {
						$check_active = mysqli_query($conn, "SELECT * FROM `users` WHERE `Email` = '$s_email' AND `Status` = 'Active'");
						$row_active = mysqli_num_rows($check_active);

						if ($row_active > 0) {
							$query = mysqli_query($conn, "SELECT * FROM `users` WHERE `Email` = '$s_email'");
							$info = mysqli_fetch_assoc($query);

							$verify_email = $info['Email'];
							$hash = $info['Password'];
							$user_id = $info['User_ID'];
							$name = $info['Full_Name'];
							$dept = $info['Department'];
							$role = $info['Role'];

							if ($verify_email === $email && password_verify($password, $hash)) {
								session_start();
								$_SESSION['id'] = $user_id;
								$_SESSION['email'] = $verify_email;
								$_SESSION['name'] = $name;
								$_SESSION['dept'] = $dept;
								$_SESSION['role'] = $role;
								$_SESSION['sess'] = TRUE;
								
								if($role == 'Department Head' || $role == 'Academic Head')
								{
									header('Location: head-dash.php');
									exit();
								}
								
								if($role == 'Admin')
								{
									header('Location: admin-dash.php');
									exit();
								}
								
								else
								{
									header('Location: user-dash.php');
									exit();
								}
							} 
							else 
							{
								$error = 'Invalid password';
							}
						} 
						else 
						{
							$error = 'Account still pending approval';
						}
					} 
					else 
					{
						$error = 'Email not registered';
					}
				}
			} 
			else 
			{
				$error = 'Please fill in all fields';
			}
	}
}
else
{
	$error = 'Connection Error';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LOGIN</title>
    <link href="css/register.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <input type="checkbox" id="check">
        <div class="login form">
            <header>LOGIN</header>
			<a><?php echo $error; ?></a>
            <form action="login.php" method="POST">
                <input type="email" name="login_email" <?php if(empty($email)){echo 'placeholder="Enter your email"';} else{echo "value=".$email."";}?>>
                <input type="password" name="login_password" placeholder="Enter your password">
                <button type="submit" id="login" name="login_btn" value="Login">Login</button>
            </form>
            <div class="signup">
                <span class="signup">Don't have an account?
                    <label><a href="register.php">Sign up</a></label>
                </span>
            </div>
        </div>
        </body>
</html>