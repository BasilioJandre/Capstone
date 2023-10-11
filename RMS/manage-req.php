<?php
include('database/php/conn.php');
include('database/php/session.php');

$dept = $_SESSION['dept'];

// Checks if Logged In
if ($sess != TRUE)
{
	session_unset();
    session_destroy();
    header("Location: register.php");
    exit;
}

// Checks for User type
if ($role != 'Department Head')
{
	session_unset();
    session_destroy();
    header("Location: register.php");
    exit;
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
	$new_dept = $_POST['dept'];
	$new_role = $_POST['role'];
	
	$specchars = '"%\'*;<>?^`{|}~/\\#=&';
	$pat = preg_quote($specchars, '/');
	
	if(!preg_match('/['.$pat.']/',$_POST['email']))
	{
		
		if (!preg_match('/['.$pat.']/',$_POST['name']))
		{
			$update_user = mysqli_query($conn, "UPDATE `users` SET `Full_Name` = '$new_name' , `Email` = '$new_email' , `Department` = '$new_dept' , `Role` = '$new_role' WHERE `User_ID` = '$user_id'");
			
			if($update_user)
			{
				$_SESSION['name'] = $new_name;
				$_SESSION['email'] = $new_email;
				$_SESSION['dept'] = $new_dept;
				$_SESSION['role'] = $new_role;
				
				Header("Refresh:0");
			}
		}
		
		else
		{
		
		}
	}
	
	else
	{

	}
}
//Takes User's Profile Picture
$retrieve_image = mysqli_query($conn, "SELECT `User_Picture` FROM `image` WHERE `User_ID` = '$user_id'");
$user_picture = mysqli_fetch_assoc($retrieve_image);
$count_image = mysqli_query($conn, "SELECT * FROM `image` WHERE `User_ID` = '$user_id'");
$check_picture = mysqli_num_rows($count_image);

$GetReq = mysqli_query($conn, "SELECT * FROM `requests`");;
if($dept == 'ICTC')
{
	$GetReq = mysqli_query($conn, "SELECT * FROM `requests` WHERE `Product/Service` = 'Equipment'");
}

if($dept == 'GSU')
{
	$GetReq = mysqli_query($conn, "SELECT * FROM `requests` WHERE `Product/Service` = 'Furniture/Appliance'");
}

if($dept == 'Accounts')
{
	$GetReq = mysqli_query($conn, "SELECT * FROM `requests` WHERE `Request_Type` = 'Purchase'");
}


$ReqList = '';
$ReqView = '';
$ReqDel = '';

while($Req = mysqli_fetch_assoc($GetReq))
{
	$ReqNo = $Req['Requisition_No'];
	$ReqName = $Req['User_Name'];
	$ReqID = $Req['User_ID'];
	$ReqDept = $Req['Department'];
	$ReqType = $Req['Request_Type'];
	$ReqServ = $Req['Product/Service'];
	$ReqDesc = $Req['Description'];
	$ReqDate = $Req['Date_Requested'];
	$NeedDate = $Req['Date_Needed'];
	$Status = $Req['Status'];
	$AddNotes = $Req['Additional_Notes'];
	
	$ReqList .= '
	
	<tr>
	<td>'.$ReqNo.'</td>
	<td>'.$ReqName.' ('.$ReqID.')</td>
	<td>'.$ReqDept.'</td>
	<td>'.$ReqType.' ('.$ReqServ.')</td>
	<td>'.$ReqDesc.'</td>
	<td>'.$ReqDate.'</td>
	<td>'.$NeedDate.'</td>
	<td>'.$Status.'</td>
	<td>
	<button class="btn btn-primary view-btn" data-toggle="modal" data-target="#viewModal'.$ReqNo.'"><i class="fas fa-eye"></i></button>
	<button class="btn btn-danger delete-btn" data-toggle="modal" data-target="#deleteModal'.$ReqNo.'"><i class="fas fa-trash"></i></button>
	</td>
	</tr>
	
	';
	
	if($dept == 'Accounts')
	{
		$ReqView .= '
		
		<div class="modal fade" id="viewModal'.$ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
		<form action="manage-req.php" method="POST">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="viewModalLabel">View Request</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- Add your view content here -->
						<p>Request Details:</p>
						<p>Request No.: '.$ReqNo.'</p>
						<p>Requestor: '.$ReqName.'</p>
						<p>Department: '.$ReqDept.'</p>
						<p>Type of Request: '.$ReqType.' ('.$ReqServ.')</p>
						<p>Description: '.$ReqDesc.'</p>
						<p>Request Date: '.$ReqDate.'</p>
						<p>Date Needed: '.$NeedDate.'</p>
						<p>Status: '.$Status.'</p>

						<!-- Add a dropdown menu to select the request status -->
						<div class="form-group">
							<label for="requestStatus">Request Status:</label>
							<select class="form-control" id="requestStatus" name="status">
								<option value="approve">Select Status</option>
								<option value="Approve">Approve</option>
								<option value="Decline">Decline</option>
								<option value="Pending">Pending</option>
							</select>
						</div>
						<div class="form-group">
							<label for="requestStatus">Remarks:</label>
						<div class="fixed-input-box">
						<textarea rows="7" cols="49" name="note_area">'.$AddNotes.'</textarea>
						<input type="hidden" value="'.$ReqNo.'" name="up_req_id">
						</div>
						</div>

						<!-- Add more request details as needed -->
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary save-status" name="save_status">Save Status</button>
					</div>
				</div>
			</div>
		</form>
		</div>
		
		';
	}
	
	if($dept == 'ICTC' || 'GSU')
	{
		$ReqView .= '
		
		<div class="modal fade" id="viewModal'.$ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
		<form action="manage-req.php" method="POST">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="viewModalLabel">View Request</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- Add your view content here -->
						<p>Request Details:</p>
						<p>Request No.: '.$ReqNo.'</p>
						<p>Requestor: '.$ReqName.'</p>
						<p>Department: '.$ReqDept.'</p>
						<p>Type of Request: '.$ReqType.' ('.$ReqServ.')</p>
						<p>Description: '.$ReqDesc.'</p>
						<p>Request Date: '.$ReqDate.'</p>
						<p>Date Needed: '.$NeedDate.'</p>
						<p>Status: '.$Status.'</p>

						<!-- Add a dropdown menu to select the request status -->
						<div class="form-group">
							<label for="requestStatus">Request Status:</label>
							<select class="form-control" id="requestStatus" name="status">
								<option value="approve">Select Status</option>
								<option value="Approve">Approve</option>
								<option value="Decline">Decline</option>
								<option value="Pending">Pending</option>
								<option value="Requires Purchase">Requires Purchase</option>
							</select>
						</div>
						<div class="form-group">
							<label for="requestStatus">Remarks:</label>
						<div class="fixed-input-box">
						<textarea rows="7" cols="49" name="note_area">'.$AddNotes.'</textarea>
						<input type="hidden" value="'.$ReqNo.'" name="up_req_id">
						</div>
						</div>

						<!-- Add more request details as needed -->
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary save-status" name="save_status">Save Status</button>
					</div>
				</div>
			</div>
		</form>
		</div>
		
		';
	}
	
	$ReqDel .= '
	
	<div class="modal fade" id="deleteModal'.$ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your delete confirmation message here -->
                    <p>Are you sure you want to delete this request?</p>
                </div>
				<form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger delete-confirm">Delete</button>
					<input type="hidden" value="'.$ReqNo.'" name="del_req_id">
                </div>
				</form>
            </div>
        </div>
    </div>
	
	';
}

if(isset($_POST['save_status']))
{
	$UpReq = $_POST['up_req_id'];
	$AddNotes = $_POST['note_area'];
	$Status = $_POST['status'];
	
	$update_req = mysqli_query($conn, "UPDATE `requests` SET `Additional_Notes` = '$AddNotes',`Status` = '$Status' WHERE `Requisition_No` = $UpReq");
	
	if($update_req)
	{
		Header("Refresh:0");
	}
}

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
    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/admin-dash-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
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
            <li class="nav-item">
                <a class="nav-link" href="head-dash.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Nav Item - Charts -->
            <li class="nav-item active">
                <a class="nav-link" href="manage-req.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Manage Request</span></a>
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
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>
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
                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">MANAGE REQUEST</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                        <th>Request No.</th>
                                            <th>Requestor</th>
                                            <th>Department</th>
                                            <th>Type of Request</th>
                                            <th>Description</th>
                                            <th>Request Date</th>
                                            <th>Date Needed</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $ReqList;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
	
	<!--Edit Profile-->
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
                    <form action='manage-req.php' method="POST" enctype="multipart/form-data" id="profileEditForm">
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
                            <select class="form-control" id="department" name="dept">
								<option value="" disabled>Select Department</option>
								<option value="Early Childhood Program" <?php if ($dept == 'Early Childhood Program'){echo 'selected="selected"';}?>>Early Childhood Program</option>
								<option value="Elementary Department" <?php if ($dept == 'Elementary Department'){echo 'selected="selected"';}?>>Elementary Department</option>
								<option value="Junior High School" <?php if ($dept == 'Junior High School'){echo 'selected="selected"';}?>>Junior High School Dept.</option>
								<option value="Senior High School" <?php if ($dept == 'Senior High School'){echo 'selected="selected"';}?>>Senior High School Dept.</option>
								<option value="College Department" <?php if ($dept == 'College Department'){echo 'selected="selected"';}?>>College Department</option>
								<option value="Graduate Studies" <?php if ($dept == 'Graduate Studies'){echo 'selected="selected"';}?>>Graduate Studies</option>
								<option value="GSU" <?php if ($dept == 'GSU'){echo 'selected="selected"';}?>>GSU</option>
								<option value="Accounts" <?php if ($dept == 'Accounts'){echo 'selected="selected"';}?>>Budget and Financing</option>
								<option value="ICTC" <?php if ($dept == 'ICTC'){echo 'selected="selected"';}?>>ICTC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role">
								<option value="" disabled>Select Role in Institution</option>
                                <option value="Department Head" <?php if ($role == 'Department Head'){echo 'selected="selected"';}?>>Department Head</option>
                                <option value="Dean's Team" <?php if ($role == "Dean's Team"){echo 'selected="selected"';}?>>Dean's Team</option>
                                <option value="Teaching Personnel" <?php if ($role == 'Teaching Personnel'){echo 'selected="selected"';}?>>Teaching Personnel</option>
                                <option value="Non-Teaching Personnel" <?php if ($role == 'Non-Teaching Personnel'){echo 'selected="selected"';}?>>Non-Teaching Personnel</option>
								<option value="Admin" <?php if ($role == 'Admin'){echo 'selected="selected"';}?>>Administrator</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="profileImage">Profile Image</label>
                            <input type="file" class="form-control-file" id="profileImage" name="profileImage" value=""/>
                        </div>
                        <div class="form-group">
                            <img id="previewImage" src="<?php if($check_picture > 0){echo 'data:image/jpg;charset=utf8;base64,'; echo base64_encode($user_picture['User_Picture']);} else {echo 'img/undraw_profile.svg';} ?>" alt="Profile Image" class="img-fluid rounded-circle" style="max-width: 100px;">
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
                    <a class="btn btn-primary" href="register.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <?php echo $ReqView; ?>

    <!-- Delete Modal -->
    <?php echo $ReqDel; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/admin-dash-2.min.js"></script>

    <script>
        // JavaScript to handle modal actions
        $(document).ready(function () {
            // Handle the delete button click
            $('.delete-btn').click(function () {
                var requestNo = $(this).closest('tr').find('td:first').text();
                $('#deleteModal').data('requestNo', requestNo);
            });

            // Handle the delete confirmation button click
            $('.delete-confirm').click(function () {
                var requestNo = $('#deleteModal').data('requestNo');
                // Send an AJAX request to delete the request
                console.log('Deleting request with Request No.: ' + requestNo);
                // Close the delete modal
                $('#deleteModal').modal('hide');
            });

            // Handle the save status button click in the view modal
            $('.save-status').click(function () {
                var requestNo = $('#viewModal').find('p:contains("Request No.")').text().trim().split(':')[1].trim();
                var selectedStatus = $('#requestStatus').val();
                
                // Send an AJAX request to update the request status based on requestNo and selectedStatus
                console.log('Updating status for Request No. ' + requestNo + ' to: ' + selectedStatus);
                // Close the view modal
                $('#viewModal').modal('hide');
            });
        });
    </script>
	<script>
        $(document).ready(function() {
            $("#profileImage").change(function() {
                readURL(this);
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    </script>
</body>
</html>