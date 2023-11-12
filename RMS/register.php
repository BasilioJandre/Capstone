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
    
		if(!empty($_POST['reg_email']) && !empty($_POST['fullname']))
		{
			$email = $_POST['reg_email'];
			$name = $_POST['fullname'];
			
			if(!empty($_POST['department']) && !empty($_POST['role']))
			{
				$dept = $_POST['department'];
				$role = $_POST['role'];
				
				if(!empty($_POST['password']) && !empty($_POST['re_password']))
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
					$error = 'Passwords Must Be Filled';
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
                <input type="text" name="fullname" <?php if(empty($name)){echo 'placeholder="Full Name"';}else{echo 'value="'.$name.'"';}?>>
                <input type="text" name="reg_email" <?php if(empty($email)){echo 'placeholder="Full Name"';}else{echo 'value="'.$email.'"';}?>>
                <select name="department">
						<option value="" disabled selected>Select Department</option>
						<option value = "Office of the President" <?php if($dept == "Office of the President"){echo 'selected';}?>>Office of the President</option>
						<option value="Accounting" <?php if($dept == "Accounting"){echo 'selected';}?>>Accounting</option>
						<option value="Alumni Office" <?php if($dept == "Alumni Office"){echo 'selected';}?>>Alumni Office</option>
						<option value="Aula" <?php if($dept == "Aula"){echo 'selected';}?>>Aula</option>
						<option value="Bookstore" <?php if($dept == "Bookstore"){echo 'selected';}?>>Bookstore</option>
						<option value="Budget and Control" <?php if($dept == "Budget and Control"){echo 'selected';}?>>Budget and Control</option>
						<option value="Finance" <?php if($dept == "Finance"){echo 'selected';}?>>Finance</option>
						<option value="Canteen" <?php if($dept == "Canteen"){echo 'selected';}?>>Canteen</option>
						<option value="Campus Ministry" <?php if($dept == "Campus Ministry"){echo 'selected';}?>>Campus Ministry</option>
						<option value="College Dean" <?php if($dept == "College Dean"){echo 'selected';}?>>College Dean</option>
						<option value="College Faculty" <?php if($dept == "College Faculty"){echo 'selected';}?>>College Faculty</option>
						<option value="College Guidance" <?php if($dept == "College Guidance"){echo 'selected';}?>>College Guidance</option>
						<option value="College Library" <?php if($dept == "College Library"){echo 'selected';}?>>College Library</option>
						<option value="College O.S.A" <?php if($dept == "College O.S.A"){echo 'selected';}?>>College O.S.A</option>
						<option value="Grade School Principal" <?php if($dept == "Grade School Principal"){echo 'selected';}?>>Grade School Principal</option>
						<option value="Grade School Academics" <?php if($dept == "Grade School Academics"){echo 'selected';}?>>Grade School Academics</option>
						<option value="Grade School E.C.E" <?php if($dept == "Grade School E.C.E"){echo 'selected';}?>>Grade School E.C.E</option>
						<option value="Grade School Faculty" <?php if($dept == "Grade School Faculty"){echo 'selected';}?>>Grade School Faculty</option>
						<option value="Grade School Guidance" <?php if($dept == "Grade School Guidance"){echo 'selected';}?>>Grade School Guidance</option>
						<option value="Grade School Library" <?php if($dept == "Grade School Library"){echo 'selected';}?>>Grade School Library</option>
						<option value="Grade School O.S.A" <?php if($dept == "Grade School O.S.A"){echo 'selected';}?>>Grade School O.S.A</option>
						<option value="Junior High School Principal" <?php if($dept == "Junior High School Principal"){echo 'selected';}?>>Junior High School Principal</option>
						<option value="Senior High School Principal" <?php if($dept == "Senior High School Principal"){echo 'selected';}?>>Senior High School Principal</option>
						<option value="High School Academics" <?php if($dept == "High School Academics"){echo 'selected';}?>>High School Academics</option>
						<option value="High School Faculty (BHS)" <?php if($dept == "High School Faculty (BHS)"){echo 'selected';}?>>High School Faculty (BHS)</option>
						<option value="High School Faculty (GHS)" <?php if($dept == "High School Faculty (GHS)"){echo 'selected';}?>>High School Faculty (GHS)</option>
						<option value="High School Guidance" <?php if($dept == "High School Guidance"){echo 'selected';}?>>High School Guidance</option>
						<option value="High School Laboratory" <?php if($dept == "High School Laboratory"){echo 'selected';}?>>High School Laboratory</option>
						<option value="High School Library" <?php if($dept == "High School Library"){echo 'selected';}?>>High School Library</option>
						<option value="High School O.S.A" <?php if($dept == "High School O.S.A"){echo 'selected';}?>>High School O.S.A</option>
						<option value="High School Reading Center" <?php if($dept == "High School Reading Center"){echo 'selected';}?>>High School Reading Center</option>
						<option value="Medical-Dental" <?php if($dept == "Medical-Dental"){echo 'selected';}?>>Medical-Dental</option>
						<option value="Mini Hotel" <?php if($dept == "Mini Hotel"){echo 'selected';}?>>Mini Hotel</option>
						<option value="Pastoral Ministry" <?php if($dept == "Pastoral Ministry"){echo 'selected';}?>>Pastoral Ministry</option>
						<option value="President's Office" <?php if($dept == "President's Office"){echo 'selected';}?>>President's Office</option>
						<option value="Printing" <?php if($dept == "Printing"){echo 'selected';}?>>Printing</option>
						<option value="Purchasing" <?php if($dept == "Purchasing"){echo 'selected';}?>>Purchasing</option>
						<option value="Registrar" <?php if($dept == "Registrar"){echo 'selected';}?>>Registrar</option>
						<option value="Research" <?php if($dept == "Research"){echo 'selected';}?>>Research</option>
						<option value="School of Graduate Studies" <?php if($dept == "School of Graduate Studies"){echo 'selected';}?>>School of Graduate Studies</option>
						<option value="Security Office" <?php if($dept == "Security Office"){echo 'selected';}?>>Security Office</option>
						<option value="Sister Quarter" <?php if($dept == "Sister Quarter"){echo 'selected';}?>>Sister's Quarter</option>
						<option value="SGS Library" <?php if($dept == "SGS Library"){echo 'selected';}?>>SGS Library</option>
						<option value="Stocks" <?php if($dept == "Stocks"){echo 'selected';}?>>Stocks</option>
						<option value="Treasury" <?php if($dept == "Treasury"){echo 'selected';}?>>Treasury</option>
						<option value="TVSD" <?php if($dept == "TVSD"){echo 'selected';}?>>TVSD</option>
						<option value="VPAA" <?php if($dept == "VPAA"){echo 'selected';}?>>VPAA</option>
						<option value="EVP" <?php if($dept == "EVP"){echo 'selected';}?>>EVP</option>
						<option value="HRMO" <?php if($dept == "HRMO"){echo 'selected';}?>>HRMO</option>
						<option value="ICTC" <?php if($dept == "ICTC"){echo 'selected';}?>>ICTC</option>
						<option value="GSU" <?php if($dept == "GSU"){echo 'selected';}?>>GSU</option>
						<option value="PAASCU" <?php if($dept == "PAASCU"){echo 'selected';}?>>PAASCU</option>
						<option value="CFO" <?php if($dept == "CFO"){echo 'selected';}?>>CFO</option>
                </select>
                <select name="role">
                    <option value="" disabled selected>Select Role in Institution</option>
                    <option value="School Administration" <?php if($role == "School Administration"){echo 'selected';}?>>School Administration</option>
                    <option value="Department Head" <?php if($role == "Department Head"){echo 'selected';}?>>Department Head</option>
					<option value="Program Head" <?php if($role == "Program Head"){echo 'selected';}?>>Program Head</option>
                    <option value="Teaching Personnel" <?php if($role == "Teaching Personnel"){echo 'selected';}?>>Teaching Personnel</option>
                    <option value="Non-Teaching Personnel" <?php if($role == "Non-Teaching Personnel"){echo 'selected';}?>>Non-Teaching Personnel</option>
					<option value="Staff" <?php if($role == "Staff"){echo 'selected';}?>>Staff</option>
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