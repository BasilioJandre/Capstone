<?php
include('database/php/conn.php');
include('database/php/session.php');
date_default_timezone_set('Asia/Manila');

$curr_date = date('Y-m-d');
$curr_date_ex = explode(' ',$curr_date);
$curr_date_s = "$curr_date_ex[0]";
$curr_year = date("Y", strtotime($curr_date_s));

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

//Year Dropdown Function
$yearlist = '';
$endyear = 2223; //Change the value to add more years
for($startyear = 2023; $startyear <= $endyear; $startyear++)
{
	$yearlist .= '
	
	<option value="'.$startyear.'">'.$startyear.'</option>
	
	';
}

$reportlist = '';
$reportmodal = '';

$getreport = mysqli_query($conn, "SELECT * FROM `requests`");

while($reports = mysqli_fetch_assoc($getreport))
{
	$req_no = $reports['Requisition_No'];
	$req_date = $reports['Date_Requested'];
	$req_need = $reports['Date_Needed'];
	$req_name = $reports['User_Name'];
	$req_id = $reports['User_ID'];
	$req_desc = $reports['Description'];
	$req_type = $reports['Request_Type'];
	$service = $reports['Product/Service'];
	$req_dept = $reports['Department'];
	$req_status = $reports['Status'];
	$req_notes = $reports['Additional_Notes'];
	
	$reportlist .= '
	
	<tr>
	<td>'.$req_no.'</td>
	<td>'.$req_name.' ('.$req_id.')</td>
	<td>'.$req_dept.'</td>
	<td>'.$req_desc.'</td>
	<td>'.$req_status.'</td>
	<td>
	<button class="btn btn-primary" data-toggle="modal" data-target="#viewModal'.$req_no.'"><i class="fas fa-eye"></i>View</button>
	</td>
	</tr>
	
	';
	
	$reportmodal .='
		<div class="modal fade" id="viewModal'.$req_no.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="viewModalLabel">View Request Details</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>Request Number: '.$req_no.'</p>
						<p>Date: '.$req_date.'</p>
						<p>Date Needed: '.$req_need.'</p>
						<p>Requestor: '.$req_name.' ('.$req_id.')</p>
						<p>Department: '.$req_dept.'</p>
						<p>Request: '.$req_type.' ('.$service.')</p>
						<p>Description: '.$req_desc.'</p>
						<p>Notes: '.$req_notes.'</p>
						<p>Status: '.$req_status.'</p>
					</div>
					</div>
				</div>
			</div>
		</div>
	';
}

//Generate Report
$month = '';
$year = '';
if(isset($_POST['generate_report']))
{
	$month = $_POST['selectMonth'];
	$year = $_POST['selectYear'];
	
	if(empty($year))
	{
		$year = $curr_year;
	}
	if(empty($month))
	{
		$first_date = date(''.$year.'-01-01', strtotime(''.$year.'-01-01'));
		$last_date = date(''.$year.'-12-t', strtotime(''.$year.'-12-01'));
		
	}
	else
	{
		$first_date = date(''.$year.'-'.$month.'-01', strtotime(''.$year.'-01-01'));
		$last_date = date(''.$year.'-'.$month.'-t', strtotime(''.$year.'-12-01'));
	}
	
	$_SESSION['first_date'] = $first_date;
	$_SESSION['last_date'] = $last_date;
	
	Header("Location: reportGeneration.php");
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
            <li class="nav-item">
                <a class="nav-link" href="admin-manage.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Manage User</span>
                </a>
            </li>
            <li class="nav-item active">
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
                                <img class="img-profile rounded-circle" src="<?php if ($check_picture > 0) {echo 'data:image/jpg;charset=utf8;base64,'; echo base64_encode($user_picture['User_Picture']);} else {echo 'img/undraw_profile.svg';} ?>">
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
	<form action="admin-report.php" method="POST">
		<button type="submit "class="btn btn-primary mb-3" id="printButton" name="generate_report">
			<i class="fas fa-print"></i> Generate Report
		</button>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">REPORT</h6>
                <div class="d-flex align-items-center ml-auto">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-sm" id="searchButton">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    <div class="input-group" style="width: 200px; margin-left: 10px;">
                        <select class="form-control" id="selectMonth" name="selectMonth">
                            <option value ="" selected disabled>Select Month</option>
							<option value="">All Months</option>
                            <option value=1>January</option>
                            <option value=2>February</option>
							<option value=3>March</option>
							<option value=4>April</option>
							<option value=5>May</option>
							<option value=6>June</option>
							<option value=7>July</option>
							<option value=8>August</option>
							<option value=9>September</option>
							<option value=10>October</option>
							<option value=11>November</option>
							<option value=12>December</option>
                            <!-- Add more month options -->
                        </select>
                    </div>
                    <div class="input-group" style="width: 200px; margin-left: 10px;">
                        <select class="form-control" id="selectYear" name="selectYear">
						<option value="" selected disabled>Select Year</option>
                            <?php echo $yearlist; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
		</div>
	</form>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>REQUEST NO.</th>
                            <th>REQUESTOR</th>
                            <th>DEPARTMENT</th>
                            <th>REQUEST DESCRIPTION</th>
                            <th>STATUS</th>
                            <th></th>
                        </tr>
                    </thead>
						<tbody>
							<?php echo $reportlist; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
</div>

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
				<form action='admin-report.php' method="POST" enctype="multipart/form-data" id="profileEditForm">
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
	
    <?php echo $reportmodal; ?>

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
	<script>
$(document).ready(function() {
    $("#searchButton").click(function() {
        var searchValue = $("#searchInput").val().toLowerCase();
        $("#dataTable tbody tr").each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
	</script>
</html>
