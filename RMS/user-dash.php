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
if ($role == 'Department Head' || $role == 'Admin')
{
    header("Location: logout.php");
    exit;
}

// Update Profile
if (isset($_POST['save_btn'])) {
    $tempname = $_FILES['profileImage']['tmp_name'];

    if (!empty($tempname)) {
        $image_content = addslashes(file_get_contents($tempname));
        $check = mysqli_query($conn, "SELECT * FROM `image` WHERE `User_ID` = '$user_id'");
        $count = mysqli_num_rows($check);

        if ($count <= 0) {
            $insert = mysqli_query($conn, "INSERT INTO `image` (`User_ID` , `User_Picture` ) VALUES ('$user_id' , '$image_content')");
        }

        if ($count > 0) {
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
	
    if (!preg_match('/[' . $pat . ']/', $_POST['email'])) {

        if (!preg_match('/[' . $pat . ']/', $_POST['name'])) {
            $update_user = mysqli_query($conn, "UPDATE `users` SET `Full_Name` = '$new_name' , `Email` = '$new_email' WHERE `User_ID` = '$user_id'");

            if ($update_user) {
                $_SESSION['name'] = $new_name;
                $_SESSION['email'] = $new_email;

                Header("Refresh:0");
            } else {
            }
        } else {
        }
    }
}

$reqlist = '';
$get_req = mysqli_query($conn, "SELECT * FROM `requests` WHERE `User_ID` = '$user_id'");
while($req = mysqli_fetch_assoc($get_req))
{
    $reqno = $req['Requisition_No'];
    $reqtype = $req['Request_Type'];
    $prod = $req['Product/Service'];
    $desc = $req['Description'];
    $date_x = $req['Date_Requested'];
    $date = date("m/d/Y", strtotime($date_x));
    $stat = $req['Status'];

    $reqlist .= '
    <tr>
    <td>'.$reqno.'</td>
    <td>'.$reqtype.'</td>
    <td>'.$prod.'</td>
    <td>'.$desc.'</td>
    <td>'.$date.'</td>
    <td>'.$stat.'</td>
    </tr>
    ';
}

$p_req_check = mysqli_query($conn, "SELECT * FROM `requests` WHERE `User_ID` = '$user_id' AND `Status` = 'Pending'");
$p_req_count = mysqli_num_rows($p_req_check);

$c_req_check = mysqli_query($conn, "SELECT * FROM `requests` WHERE `User_ID` = '$user_id' AND `Active` = 'no'");
$c_req_count = mysqli_num_rows($c_req_check);

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
                <a class="nav-link" href="user-dash.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <!-- Nav Item - Charts -->

            <li class="nav-item">
                <a class="nav-link" href="user-request.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Request Process</span>
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
                                <img class="img-profile rounded-circle" src="<?php if ($check_picture > 0) {echo 'data:image/jpg;charset=utf8;base64,'; echo base64_encode($user_picture['User_Picture']);} else {echo 'img/undraw_profile.svg';} ?>">
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
                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>
                <div class="container-fluid">
                    <div class="card mb-4" style="width: 100%; margin-bottom: 0;">
                        <div class="card-body" style="background-image: url('img/dash.jpg'); background-position: right top; background-size: cover; background-color: rgba(255, 255, 255, 0.5); color: #ffff; text-align: left;">
                            <h5 class="card-title">WELCOME!</h5>
                            <h6><span style="font-size: 50px;"><?php echo $name; ?></span></h6>
                            <p class="card-text" style="font-size: 14px; text-align: left;">
                                Greetings! Welcome to Requisition Management System,<br>
                                where efficient procurement is just a click away.<br>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-80 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pending Request</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $p_req_count; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-80 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed Request</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $c_req_count; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-square fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">REQUEST HISTORY</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Request No.</th>
                                                    <th>Request Type</th>
                                                    <th>Product/Service</th>
                                                    <th>Request Description</th>
                                                    <th>Request Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               <?php echo $reqlist; ?>
                                            </tbody>
                                        </table>
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
                                <form action='user-dash.php' method="POST" enctype="multipart/form-data" id="profileEditForm">
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
                                        <input type="file" class="form-control-file" id="profileImage" name="profileImage" value="" />
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