<?php
include('database/php/conn.php');
include('database/php/session.php');

$dept = $_SESSION['dept'];

// Checks if Logged In
if ($sess != TRUE)
{
    header("Location: logout.php");
    exit;
}

// Checks for User type
if ($role == 'Department Head' || $role == 'Academic Head')
{
//Do Nothing
}
else
{
    header("Location: logout.php");
    exit;
}

//Conditions
if($dept == 'College Dean')
{
	$Condition = "`Department`= 'College Faculty' OR `Department`= 'College Guidance' OR `Department`= 'College Library' OR `Department`= 'College O.S.A'";
	$I_Condition = "`Forward_To`= 'College' AND `Active` = 'yes'";
}

elseif($dept == 'Junior High School Principal' || $dept == 'Senior High School Principal')
{
	$Condition = "`Department`= 'High School Faculty (BHS)' OR `Department`= 'High School Faculty (GHS)' OR `Department`= 'High School Academics' OR `Department`= 'High School Guidance' OR `Department`= 'High School Library' OR `Department`= 'High School Laboratory' OR `Department`= 'High School O.S.A'";
	$I_Condition = "`Forward_To` = 'JHS' OR `Forward_To` = 'SHS' AND `Active` = 'yes'";
}

elseif($dept == 'IOSA')
{
	$Condition = "`Department` = 'IOSA'";
	$I_Condition = "`Forward_To` = 'IOSA' AND `Active` = 'yes'";
}

elseif($dept == 'Grade School Principal')
{
	$Condition = "`Department` = 'Grade School Academics' OR `Department` = 'Grade School E.C.E' OR `Department` = 'Grade School Faculty' OR `Department` = 'Grade School Guidance' OR `Department` = 'Grade School Library' OR `Department` = 'Grade School O.S.A'";
	$I_Condition = "`Forward_To` = 'Grade School' AND `Active` = 'yes'";
}

elseif($dept == 'Finance')
{
	$Condition = "`Department` = 'Treasury' OR `Department` = 'Accounting' OR `Department` = 'Budget and Control' OR `Department` = 'Bookstore' OR `Department` = 'Canteen' OR `Department` = 'Printing' OR `Department` = 'Purchasing' OR `Department` = 'Stocks'";
	$I_Condition = "`Forward_To` = 'Finance' AND `Active` = 'yes'";
}

elseif($dept == 'CFO')
{
	$Condition = "`Department` = 'Sister Quarter' OR `Department` = 'Security Office' OR `Department` = 'Campus Ministry' OR `Department` = 'Pastoral Ministry'";
	$I_Condition = "`Forward_To` = 'CFO' AND `Active` = 'yes' OR `Forward_To` = 'Admin $ Gen.Facilities' AND `Active` = 'yes'";
}

elseif($dept == 'ICTC')
{
	$Condition = "`Department` = 'ICTC'";
	$I_Condition = "`Forward_To` = 'ICTC' AND `Active` = 'yes'";
}

elseif($dept == 'GSU')
{
	$Condition = "`Department` = 'GSU'";
	$I_Condition = "`Forward_To` = 'GSU' AND `Active` = 'yes'";
}

elseif($dept == 'HRMO')
{
	$Condition = "`Department` = 'HRMO'";
	$I_Condition = "`Forward_To` = 'HRMO' AND `Active` = 'yes'";
}

elseif($dept == 'Registrar')
{
	$Condition = "`Department` = 'Registrar'";
	$I_Condition = "`Forward_To` = 'Registrar' AND `Active` = 'yes'";
}

elseif($dept == 'Aula')
{
	$Condition = "`Department` = 'Aula'";
}

elseif($dept == 'Alumni Office')
{
	$Condition = "`Department` = 'Alumni Office'";
}

elseif($dept == 'Medical-Dental')
{
	$Condition = "`Department` = 'Medical-Dental'";
	$I_Condition = "`Forward_To` = 'Medical-Dental' AND `Active` = 'yes'";
}

elseif($dept == 'Mini Hotel')
{
	$Condition = "`Department` = 'Mini Hotel'";
}

elseif($dept == 'PAASCU')
{
	$Condition = "`Department` = 'PAASCU'";
}

elseif($dept == 'School of Graduate Studies' || $dept == 'Research')
{
	$Condition = "`Status` = 'Pending' AND (`Department` = 'SGS Library' OR `Department` = 'School of Graduate Studies' OR `Department` = 'Research')";
	$I_Condition = "(`Forward_To` = 'School of Graduate Studies' OR `Forward_To` = 'Research') AND `Active` = 'yes'";
}

elseif($dept == 'TVSD')
{
	$Condition = "`Department` = 'TVSD'";
	$I_Condition = "`Forward_To` = 'TVSD' AND `Active` = 'yes'";
}

elseif($dept == 'Budget and Control')
{
	$Condition = "`Forward_To` = 'Budget and Control' OR `Request_Type` = 'Purchase'";
	$I_Condition = "(`Status` = 'Requires Purchase' OR `Status` = 'Item Purchased' OR `Status` = 'Item Delivered') OR (`Request_Type` = 'Purchase' AND `Forward_To` = 'Budget and Control') AND `Active` = 'yes'";
}

elseif($dept == 'VPAA' || $dept == 'EVP' || $dept == 'Office of the President')
{
	$Condition = "`Forward_To` = 'VPAA Office' OR `Forward_To` = 'EVP Office' OR `Forward_To` = 'Office of the President' OR `Department` = 'EVP' OR `Department` = 'VPAA' OR `Department` = 'Office of the President'";
	$I_Condition = "(`Forward_To` = 'VPAA Office' OR `Forward_To` = 'EVP Office' OR `Forward_To` = 'Office of the President') AND (`Noted_By_Budget` != '' AND `Active` = 'yes')";
}

else
{
	$Condition = "`Department` = 'NA'";
	$I_Condition = "`Forward_To` = 'NA'";
}

//Get number of pending requests
$p_request_check = mysqli_query($conn, "SELECT * FROM `requests` WHERE (".$Condition.") AND `Status` = 'Pending'");
$p_request_count = mysqli_num_rows($p_request_check);

// Get total number of requests
$n_request_check = mysqli_query($conn, "SELECT * FROM `requests` WHERE (".$I_Condition.") AND `Status` = 'Forwarded'");	
$n_request_count = mysqli_num_rows($n_request_check);

// Get total number of completed requests
$c_request_check = mysqli_query($conn, "SELECT * FROM `requests` WHERE (".$Condition.") AND `Active` = 'no'");	
$c_request_count = mysqli_num_rows($c_request_check);

// Update Profile
if(isset($_POST['save_btn']))
{
	$tempname = $_FILES['profileImage']['tmp_name'];
	
	if(!empty($tempname))
	{
		$image_content = addslashes(file_get_contents($tempname));
		$check = mysqli_query($conn, "SELECT * FROM `image` WHERE `User_ID` = '$user_id'");
		$count = mysqli_num_rows($check);
		
		if($count <= 0)
		{
			$insert = mysqli_query($conn, "INSERT INTO `image` (`User_ID` , `User_Picture` ) VALUES ('$user_id' , '$image_content')");
		}
		
		if($count > 0)
		{
			$update = mysqli_query($conn, "UPDATE `image` SET `User_Picture` = '$image_content' WHERE `User_ID` = '$user_id'");
		}
	}
	
	$new_name = $_POST['name'];
	$new_email = $_POST['email'];
	
	$specchars = '"%\'*;<>?^`{|}~/\\#=&';
	$pat = preg_quote($specchars, '/');
	
	$check_email = mysqli_query($conn, "SELECT * FROM `users` WHERE `Email` = '$new_email'");
	$row_email = mysqli_num_rows($check_email);
	
	if($row_email == 0)
	{
		$new_email = $_POST['email'];
	}
	elseif($row_email == 1)
	{
		$new_email = $email;
	}
	
	if(!preg_match('/['.$pat.']/',$_POST['email']))
	{
		
		if (!preg_match('/['.$pat.']/',$_POST['name']))
		{
		$update_user = mysqli_query($conn, "UPDATE `users` SET `Full_Name` = '$new_name' , `Email` = '$new_email' WHERE `User_ID` = '$user_id'");
				
			if($update_user)
			{
				$_SESSION['name'] = $new_name;
				$_SESSION['email'] = $new_email;
					
				Header("Refresh:0");
			
			}
			
			else
			{
			
			}
		}
	
		else
		{

		}
	}
}
//Takes User's Profile Picture
$retrieve_image = mysqli_query($conn, "SELECT `User_Picture` FROM `image` WHERE `User_ID` = '$user_id'");
$user_picture = mysqli_fetch_assoc($retrieve_image);
$count_image = mysqli_query($conn, "SELECT * FROM `image` WHERE `User_ID` = '$user_id'");
$check_picture = mysqli_num_rows($count_image);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Requisition Management System</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/admin-dash-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
            <div class="sidebar-brand-icon">
                    <img src="img/pcc logo.png" alt="Logo" style="max-width: 80%; height: auto;">
                </div>
                <div class="sidebar-brand-text mx-3">Requisition Management System</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="head-dash.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <!-- Nav Item - Charts -->

            <li class="nav-item">
                <a class="nav-link" href="head-request.php">
                    <i class="fa fa-plus-square"></i>
                    <span>Request Process</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage-req.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Manage Request</span>
                </a>
            </li>
            
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $name; ?> <br> <?php echo $role; ?></span>
                                <img class="img-profile rounded-circle" src="<?php if($check_picture > 0){echo 'data:image/jpg;charset=utf8;base64,'; echo base64_encode($user_picture['User_Picture']);} else {echo 'img/undraw_profile.svg';} ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editProfileModal">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Forwarded Request</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $n_request_count;?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exclamation fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pending Request</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $p_request_count;?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completed Request</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $c_request_count;?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
				</div>
			<div class="modal-body">
				<form action='head-dash.php' method="POST" enctype="multipart/form-data" id="profileEditForm">
					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
					</div>
					<div class="form-group">
						<label for="email">Email Address</label>
						<input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
					</div>
					<div class="form-group">
						<label for="department">Department</label>
						<input type="text" class="form-control" id="name" value="<?php echo $dept; ?>" disabled>
					</div>
					<div class="form-group">
						<label for="role">Role</label>
						<input type="text" class="form-control" id="name" value="<?php echo $role; ?>" disabled>
					</div>
					<div class="form-group">
						<label for="profileImage">Profile Image</label>
						<input type="file" class="form-control-file" id="profileImage" name="profileImage" value="">
					</div>
					<div class="form-group">
						<img id="previewImage" src="<?php if ($check_picture > 0) {echo 'data:image/jpg;charset=utf8;base64,';echo base64_encode($user_picture['User_Picture']);} else {echo 'img/undraw_profile.svg';} ?>" alt="Profile Image" class="img-fluid rounded-circle" style="max-width: 100px;">
					</div>
			</div>
					<button class="btn btn-primary" type="submit" id="saveProfileButton" name="save_btn">Save</button>
				</form>
			</div>
		</div>
	</div>
    </div>
	
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/admin-dash-2.min.js"></script>
	<script src="js/profilephoto.js"></script>
</body>
</html>
