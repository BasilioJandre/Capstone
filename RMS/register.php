<?php
include 'database/php/conn.php';
include 'database/php/session.php';

session_unset();
session_destroy();


$error = '';
$email = '';
$name = '';
$dept = '';
$role = '';
if($conn)
{
	if(isset($_POST['signup_btn']))
	{
    $email = $_POST['reg_email'];
    $name = $_POST['fullname'];
	$dept = $_POST['department'];
	$role = $_POST['role'];
		if(!empty($email) && !empty($name) && !empty($_POST['password']) && !empty($_POST['re_password']))
		{
			if(!empty($dept) && !empty($role))
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

										$record = mysqli_query($conn, "INSERT INTO `users`(`User_ID`,`Full_Name`,`Email`,`Department`,`Role`,`Password`,`Status`) VALUES ('$id','$name','$email','$dept','$role','$encrypt_password','Pending')");

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
    <title>SIGNUP</title>
    <link href="css/register.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <input type="checkbox" id="check">
        <div class="signup form">
            <header>SIGNUP</header>
			<a><?php echo $error; ?></a>
            <form action="register.php" method="POST">
                <input type="text" name="fullname" placeholder="Full Name">
                <input type="text" name="reg_email" placeholder="Email Address">
                <select name="department">
						<option value="" disabled selected>Select Department</option>
						<option value = "Office of the President">Office of the President</option>
						<option value="Accounting">Accounting</option>
						<option value="Alumni Office">Alumni Office</option>
						<option value="Aula">Aula</option>
						<option value="Bookstore">Bookstore</option>
						<option value="Budget and Control">Budget and Control</option>
						<option value="Finance">Finance</option>
						<option value="Canteen">Canteen</option>
						<option value="Campus Ministry">Campus Ministry</option>
						<option value="College Dean">College Dean</option>
						<option value="College Faculty">College Faculty</option>
						<option value="College Guidance">College Guidance</option>
						<option value="College Library">College Library</option>
						<option value="College O.S.A">College O.S.A</option>
						<option value="Grade School Principal">Grade School Principal</option>
						<option value="Grade School Academics">Grade School Academics</option>
						<option value="Grade School E.C.E">Grade School E.C.E</option>
						<option value="Grade School Faculty">Grade School Faculty</option>
						<option value="Grade School Guidance">Grade School Guidance</option>
						<option value="Grade School Library">Grade School Library</option>
						<option value="Grade School O.S.A">Grade School O.S.A</option>
						<option value="Junior High School Principal">Junior High School Principal</option>
						<option value="Senior High School Principal">Senior High School Principal</option>
						<option value="High School Academics">High School Academics</option>
						<option value="High School Faculty (BHS)">High School Faculty (BHS)</option>
						<option value="High School Faculty (GHS)">High School Faculty (GHS)</option>
						<option value="High School Guidance">High School Guidance</option>
						<option value="High School Laboratory">High School Laboratory</option>
						<option value="High School Library">High School Library</option>
						<option value="High School O.S.A">High School O.S.A</option>
						<option value="High School Reading Center">High School Reading Center</option>
						<option value="Medical-Dental">Medical-Dental</option>
						<option value="Mini Hotel">Mini Hotel</option>
						<option value="Pastoral Ministry">Pastoral Ministry</option>
						<option value="President's Office">President's Office</option>
						<option value="Printing">Printing</option>
						<option value="Purchasing">Purchasing</option>
						<option value="Registrar">Registrar</option>
						<option value="Research">Research</option>
						<option value="School of Graduate Studies">School of Graduate Studies</option>
						<option value="Security Office">Security Office</option>
						<option value="Sister Quarter">Sister's Quarter</option>
						<option value="SGS Library">SGS Library</option>
						<option value="Stocks">Stocks</option>
						<option value="Treasury">Treasury</option>
						<option value="TVSD">TVSD</option>
						<option value="VPAA">VPAA</option>
						<option value="EVP">EVP</option>
						<option value="HRMO">HRMO</option>
						<option value="ICTC">ICTC</option>
						<option value="GSU">GSU</option>
						<option value="PAASCU">PAASCU</option>
						<option value="CFO">CFO</option>
                </select>
                <select name="role">
                    <option value="" disabled selected>Select Role in Institution</option>
                    <option value="School Administration">School Administration</option>
                    <option value="Department Head">Department Head</option>
					<option value="Program Head">Program Head</option>
                    <option value="Teaching Personnel">Teaching Personnel</option>
                    <option value="Non-Teaching Personnel">Non-Teaching Personnel</option>
				</select>
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="re_password" placeholder="Confirm Password">

                <button type="submit" id="login" name="signup_btn" value="Signup">Sign Up</button>
            </form>
            <div class="signup">
                <span class="signup">Already have an account?
                    <label><a href="login.php">Login</a></label>
                </span>
            </div>
        </div>
    </div>
    </div>
    
</body>
</html>