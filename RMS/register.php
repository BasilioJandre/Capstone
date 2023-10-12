<?php
include 'database/php/conn.php';
include 'database/php/session.php';

//Signup
if($conn)
{
	$error = '';
	$email = '';
	$name = '';
	$dept = '';
	$role = '';
	if(isset($_POST['signup_btn']))
	{
    $email = $_POST['reg_email'];
    $name = $_POST['fullname'];

		if(!empty($_POST['department']))
		{
			$dept = $_POST['department'];	
		}

		if(!empty($_POST['reg_email']) && !empty($_POST['fullname']) && !empty($_POST['password']) && !empty($_POST['re_password']))
		{
			if(!empty($_POST['department']) && !empty($_POST['role']))
			{
				$specchars = '"%\'*;<>?^`{|}~/\\#=&';
				$pat = preg_quote($specchars, '/');

				if(!preg_match('/['.$pat.']/',$_POST['reg_email']))
				{
					if(!preg_match('/['.$pat.']/',$_POST['fullname']))
					{
						if(!preg_match('/['.$pat.']/',$_POST['password']) && !preg_match('/['.$pat.']/',$_POST['re_password']))
						{
							$s_email = mysqli_real_escape_string($conn, $_POST['reg_email']);
							$s_name = mysqli_real_escape_string($conn, $_POST['fullname']);
							$s_password = mysqli_real_escape_string($conn, $_POST['password']);
							$s_re_pass = mysqli_real_escape_string($conn, $_POST['re_password']);

							$email = htmlspecialchars($s_email);
							$name = htmlspecialchars($s_name);
							$password = htmlspecialchars($s_password);
							$re_pass = htmlspecialchars($s_re_pass);

							if(strlen($password) >= 8)
							{
								if($password == $re_pass)
								{
									$check_email = mysqli_query($conn, "SELECT * FROM `users` WHERE `email` = '$email'");
									$row_email = mysqli_num_rows($check_email);

									if($row_email == 0)
									{
										$check_name = mysqli_query($conn, "SELECT * FROM `users` WHERE `Full_Name` = '$name'");
										$row_name = mysqli_num_rows($check_name);

										if($row_name == 0)
										{
											$encrypt_password = password_hash($password, PASSWORD_BCRYPT, array('cost'=>15));

											function generateid($length = 8)
											{
												$idchars = '123456789';
												$id = '';

												for($x=0; $x < $length; $x++)
												{
													$id .= $idchars[rand(0, strlen($idchars)-1)];
												}
												return $id;
											}

											redo:
											$idx = generateid();
											$check_id = mysqli_query($conn, "SELECT * FROM `users` WHERE `User_ID` = '$idx'");
											$row_id = mysqli_num_rows($check_id);

											if($row_id == 0)
											{
												$id = $idx;
											}
											else
											{
												goto redo;
											}

											$record = mysqli_query($conn, "INSERT INTO `users`(`User_ID`,`Full_Name`,`Email`,`Department`,`Role`,`Password`,`Status`) VALUES ('$id','$name','$email','$dept','$role','$encrypt_password','Active')");

											if($record == TRUE)
											{
												$error = 'Registration Successful!';
												$email = '';
												$name = '';
												$dept = '';
												$role = '';
											}
										}
										else
										{
											$error = 'Name Already Registered';
										}
									}
									else
									{
										$error = 'Email Already Registered';
									}
								}
								else
								{
									$error = 'Passwords Do Not Match';
								}
							}
							else
							{
								$error = 'Password Must Be At Least 8 Characters Long';
							}
						}
						else
						{
							$error = 'Invalid Characters in Password';
						}
					}
					else
					{
						$error = 'Invalid Characters in Name';
					}
				}
				else
				{
					$error = 'Invalid Characters in Email';
				}
			}
			else
			{
				$error = 'All Fields Must Be Filled';
			}
		}
		else
		{
			$error = 'All Fields Must Be Filled';
		}
	}


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
							} else {
								$error = 'Invalid password';
							}
						} else {
							$error = 'Account still pending approval';
						}
					} else {
						$error = 'Email not registered';
					}
				}
			} else {
				$error = 'Please fill in all fields';
			}
		}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login & Registration Form</title>
    <link href="css/register.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <input type="checkbox" id="check">
        <div class="login form">
            <header>LOGIN</header>
			<center><a><?php echo $error; ?></a></center>
            <form action="register.php" method="POST">
                <input type="text" name="login_email" placeholder="Enter your email">
                <input type="password" name="login_password" placeholder="Enter your password">
                <button type="submit" id="login" name="login_btn" value="Login">Login</button>
            </form>
            <div class="signup">
                <span class="signup">Don't have an account?
                    <label for="check">Sign up</label>
                </span>
            </div>
        </div>
        <div class="registration form">
            <header>SIGNUP</header>
            <form action="register.php" method="post">
                <input type="text" name="fullname" placeholder="Full Name">
                <input type="text" name="reg_email" placeholder="Email Address">
                <select name="department">
                    <option value="" disabled selected>Select Department</option>
                    <option value="hr">Early Childhood Program</option>
                    <option value="marketing">Elementary Department</option>
                    <option value="finance">Junior High School Dept.</option>
                    <option value="finance">Senior High School Dept.</option>
                    <option value="finance">College Department</option>
                    <option value="finance">Graduate Studies</option>
                    <option value="manager">GSU</option>
                    <option value="developer">PPA</option>
                    <option value="designer">Budget and Financing</option>
                    <option value="designer">ICTC</option>
                </select>
                <select name="role">
                    <option value="" disabled selected>Select Role in Institution</option>
                    <option value="designer">School Administration</option>
                    <option value="designer">Dean's Team</option>
                    <option value="finance">Teaching Personnel</option>
                    <option value="finance">Non-Teaching Personnel</option>
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="confirm_password" placeholder="Confirm Password">
                </select>

                <button type="submit" id="login" name="signup_btn" value="Signup">Sign Up</button>
            </form>
            <div class="signup">
                <span class="signup">Already have an account?
                    <label for="check">Login</label>
                </span>
            </div>
        </div>
    </div>
</body>
</html>
