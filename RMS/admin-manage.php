<?php
include('database/php/conn.php');
include('database/php/session.php');

// Checks if Logged In
if ($sess != TRUE)
{
    header("Location: logout.php");
    exit;
}

// Checks for User type
if ($role != 'Admin')
{
    header("Location: logout.php");
    exit;
}


$userlist = '';
$deletelist = '';
$approvelist = '';
$adminlist = '';

$getuser = mysqli_query($conn, "SELECT * FROM `users` WHERE `User_ID` != '$user_id' ORDER BY `Status` DESC, `Role` ASC");

while($users = mysqli_fetch_assoc($getuser))
{
	$id = $users['User_ID'];
	$fullname = $users['Full_Name'];
	$user_email = $users['Email'];
	$user_dept = $users['Department'];
	$user_role = $users['Role'];
	$user_stat = $users['Status'];
	
	if($user_stat == 'Pending')
	{
		$buttons = '
		<button type="" class="btn btn-primary" data-toggle="modal" data-target="#adminModal'.$id.'" name=""><i class="fas fa-user"></i></button>
		<button type="" class="btn btn-primary" data-toggle="modal" data-target="#approveModal'.$id.'" name=""><i class="fas fa-check"></i></button>
		<button type="" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal'.$id.'" name=""><i class="fas fa-trash"></i></button>
		';
	}
	elseif($user_stat == 'Active')
	{
		$buttons = '
		<button type="" class="btn btn-primary" data-toggle="modal" data-target="#adminModal'.$id.'" name=""><i class="fas fa-user"></i></button>
		<button type="" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal'.$id.'" name=""><i class="fas fa-trash"></i></button>
		';
	}
	//List all users
	$userlist .= '
	
	<tr>
	<td>'.$id.'</td>
	<td>'.$fullname.'</td>
	<td>'.$user_email.'</td>
	<td>'.$user_dept.'</td>
	<td>'.$user_role.'</td>
	<td>
	'.$buttons.'
	</td>
	</tr>
	';
	
	//Create modal for each user
	$deletelist.='
	
	<div class="modal fade" id="deleteModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
	<form action="admin-manage.php" method="POST">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your delete confirmation message here -->
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
				
                    <button type="submit" class="btn btn-danger" name="deleteuser">Delete</button>
					<input type="hidden" value="'.$id.'" name="del_user_id">
                
				</div>
            </div>
        </div>
	</form>
    </div>
	
	';
	
	$approvelist.='
	
	<div class="modal fade" id="approveModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
	<form action="admin-manage.php" method="POST">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Approve User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your delete confirmation message here -->
                    <p>Confirm this user?</p>
                </div>
                <div class="modal-footer">
				
                    <button type="submit" class="btn btn-primary" name="approveuser">Approve</button>
					<input type="hidden" value="'.$id.'" name="app_user_id">
                
				</div>
            </div>
        </div>
	</form>
    </div>
	
	';
	
	$adminlist.='
	
	<div class="modal fade" id="adminModal'.$id.'" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
	<form action="admin-manage.php" method="POST">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Admin User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your delete confirmation message here -->
                    <p>Make this user a System Administrator?</p>
                </div>
                <div class="modal-footer">
				
                    <button type="submit" class="btn btn-primary" name="adminuser">Approve</button>
					<input type="hidden" value="'.$id.'" name="adm_user_id">
                
				</div>
            </div>
        </div>
	</form>
    </div>
	
	';
	
}

//Delete Function
if(isset($_POST["deleteuser"]))
{
	$del_user_id = $_POST['del_user_id'];
	$query = mysqli_query($conn, "DELETE FROM `users` WHERE `User_ID` = '$del_user_id'");
	if($query)
	{
		header('Refresh:0');
	}
}

//Approve Function
if(isset($_POST['approveuser']))
{
	$app_user_id = $_POST['app_user_id'];
	$query = mysqli_query($conn, "UPDATE `users` SET `Status` = 'Active' WHERE `User_ID` = '$app_user_id'");
	
	if($query)
	{
		header('Refresh:0');
	}
}

//Admin Function
if(isset($_POST['adminuser']))
{
	$adm_user_id = $_POST['adm_user_id'];
	$query = mysqli_query($conn, "UPDATE `users` SET `Status` = 'Active', `Role` = 'Admin' WHERE `User_ID` = '$adm_user_id'");
	
	if($query)
	{
		header('Refresh:0');
	}
}

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
	$count_email = mysqli_num_rows($check_email);
	
	if($count_email > 0)
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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/admin-dash-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon">
                    <img src="img/pcc logo.png" alt="Logo" style="max-width: 80%; height: auto;">
                </div>
                <div class="sidebar-brand-text mx-3">Requisition Management System</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="admin-dash.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item active">
                <a class="nav-link" href="admin-manage.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Manage User</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin-report.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Report</span>
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="admin-archive.php">
                    <i class="fas fa-fw fa-file-archive"></i>
                    <span>Archive</span>
                </a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
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
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $name; ?> <br> <?php echo $role; ?></span>
                                <img class="img-profile rounded-circle" src="<?php if($check_picture > 0){echo 'data:image/jpg;charset=utf8;base64,'; echo base64_encode($user_picture['User_Picture']);} else {echo 'img/undraw_profile.svg';} ?>">
                            </a>
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
                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">MANAGE USER</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>USER ID</th>
                                            <th>FULL NAME</th>
                                            <th>EMAIL ADDRESS</th>
                                            <th>DEPARTMENT</th>
                                            <th>ROLE</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                          <?php echo $userlist;?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Modal -->
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
				<form action='admin-manage.php' method="POST" enctype="multipart/form-data" id="profileEditForm">
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
	
	
    <!-- Delete Confirmation Modal -->
	
    <?php echo $deletelist; ?>
	<?php echo $approvelist; ?>
	<?php echo $adminlist; ?>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
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
