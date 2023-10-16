<?php
include('database/php/conn.php');
include('database/php/session.php');

$date = date('Y-m-d');

$req_query = mysqli_query($conn,"SELECT * FROM `requests` WHERE `User_ID` = '$user_id'");
$ReqList ='';
while($get_req = mysqli_fetch_assoc($req_query))
{
	$ReqNo = $get_req['Requisition_No'];
	$ReqType = $get_req['Request_Type'];
	$ReqServ = $get_req['Product/Service'];
	$ReqQty = $get_req['Quantity'];
	$ReqDate = $get_req['Date_Requested'];
	$ReqNDate = $get_req['Date_Needed'];
	$ReqDesc = $get_req['Description'];
	$ReqStat = $get_req['Status'];
	
	$ReqList .='
	
	<tr>
	<td>'.$ReqNo.'</td>
	<td>'.$ReqType.'</td>
	<td>'.$ReqServ.'</td>
	<td>'.$ReqDesc.'</td>
	<td>'.$ReqDate.'</td>
	<td>'.$ReqNDate.'</td>
	<td>'.$ReqStat.'</td>
	<td>
	<a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#trackModal">
		<i class="fas fa-eye"></i> Track
	</a>
	<a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal">
		<i class="fas fa-trash"></i> Delete
	</a>
	</td>
	</tr>
	
	';
}


//Form function
$count = 0;
if(isset($_POST['send_btn']))
{
	$c_date_ex = explode(' ',$date);
	$c_date_s = "$c_date_ex[0]";
	$c_date = date("Y-m-d", strtotime($c_date_s));
	
	$n_date = $_POST['n_date'];
	$n_date_ex = explode(' ',$n_date);
	$n_date_s = "$n_date_ex[0]";
	$n_date = date("Y-m-d", strtotime($n_date_s));
	
	$f_type = $_POST['f_request'];
	$f_service = $_POST['f_service'];
	$f_qty = $_POST['f_qty'];
	$f_notes = $_POST['f_notes'];
	
	$sf_notes = mysqli_real_escape_string($conn, $f_notes);
	$f_notes = htmlspecialchars($sf_notes);
		
	$insert = mysqli_query($conn, "INSERT INTO `requests`(`User_Name`, `User_ID`, `Department`, `Date_Requested`, `Date_Needed`, `Request_Type`, `Product/Service`, `Quantity`, `Description`,`Status`) VALUES ('$name','$user_id','$dept','$c_date','$n_date','$f_type','$f_service','$f_qty','$f_notes','New')");
	
	if(!empty($_POST['indicator'][0]))
	{
		foreach($_POST['request'] as $request)
		{
			
			$req_type = $_POST['request'][$count];
			$req_service = $_POST['service'][$count];
			$req_qty = $_POST['qty'][$count];
			$req_notes = $_POST['notes'][$count];
			
			$s_notes = mysqli_real_escape_string($conn, $req_notes);
			$notes = htmlspecialchars($s_notes);
			
			$insert = mysqli_query($conn, "INSERT INTO `requests`(`User_Name`, `User_ID`, `Department`, `Date_Requested`, `Date_Needed`, `Request_Type`, `Product/Service`, `Quantity`, `Description`,`Status`) VALUES ('$name','$user_id','$dept','$c_date','$n_date','$req_type','$req_service','$req_qty','$notes','New')");
			$count += 1;
		}
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
<style>
.customClass {};
</style>
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
                <a class="nav-link" href="user-dash.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item active">
                <a class="nav-link" href="user-request.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Request Process</span>
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
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#newRequestModal">
                             <i class="fas fa-plus"></i> New Request</button>
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
                                <th>Date Needed</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $ReqList; ?>
                        </tbody>
                    </table>
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
                    <form action='user-request.php' method="POST" enctype="multipart/form-data" id="profileEditForm">
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
                            <select class="form-control" id="department" name="dept" disabled="disabled">
								<option value="" disabled>Select Department</option>
								<option value="Early Childhood Program" <?php if ($dept == 'Early Childhood Program'){echo 'selected="selected"';}?>>Early Childhood Program</option>
								<option value="Elementary Department" <?php if ($dept == 'Elementary Department'){echo 'selected="selected"';}?>>Elementary Department</option>
								<option value="Junior High School" <?php if ($dept == 'Junior High School'){echo 'selected="selected"';}?>>Junior High School Dept.</option>
								<option value="Senior High School" <?php if ($dept == 'Senior High School'){echo 'selected="selected"';}?>>Senior High School Dept.</option>
								<option value="College Department" <?php if ($dept == 'College Department'){echo 'selected="selected"';}?>>College Department</option>
								<option value="Graduate Studies" <?php if ($dept == 'Graduate Studies'){echo 'selected="selected"';}?>>Graduate Studies</option>
								<option value="GSU" <?php if ($dept == 'GSU'){echo 'selected="selected"';}?>>GSU</option>
								<option value="PPA" <?php if ($dept == 'PPA'){echo 'selected="selected"';}?>>PPA</option>
								<option value="Accounts" <?php if ($dept == 'Accounts'){echo 'selected="selected"';}?>>Budget and Financing</option>
								<option value="ICTC" <?php if ($dept == 'ICTC'){echo 'selected="selected"';}?>>ICTC</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" name="role" disabled="disabled">
								<option value="" disabled>Select Role in Institution</option>
                                <option value="School Administration" <?php if ($role == 'School Administration'){echo 'selected="selected"';}?>>School Administration</option>
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
    
    <!-- Tracking Modal -->
    <div class="modal fade" id="trackModal" tabindex="-1" role="dialog" aria-labelledby="trackModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trackModalLabel">Track Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your tracking information here -->
                    <p>Tracking details for the request go here.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- New Request Modal -->
    <div class="modal fade" id="newRequestModal" tabindex="-1" role="dialog" aria-labelledby="newRequestModalLabel" aria-hidden="true">
		<form action="user-request.php" method="POST" id="requestForm">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 1500px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="newRequestModalLabel">Request Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="requestorName">Name</label>
                                <input type="text" class="form-control" id="requestorName" value="<?php echo $name; ?>" disabled="disabled">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="requestDate">Date</label>
                                <input type="date" class="form-control" id="requestDate" value="<?php echo $date; ?>" disabled="disabled">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="dateNeeded">Date Needed</label>
                                <input type="date" class="form-control" id="dateNeeded" name="n_date">
                            </div>
                        </div>
                        <div id="formContainer" class="customClass">
			<div class="form-row">
					<label for="padding">Field 1</label>
					<input type="hidden" id="padding">
			</div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <select class="form-control" id="requestType" name="f_request">
                    <option value="" disabled selected>Request Type</option>
                    <option value="Borrow">Borrow</option>
                    <option value="Repair">Repair</option>
                    <option value="Purchase">Purchase</option>
					<option value="Transfer">Transfer</option>
					<option value="Acquire">Acquire</option>
                </select>
            </div>
            <div class="form-group col-md-4" id="productServiceContainer">
                <select class="form-control" id="productService" name="f_service">
                    <option value="" disabled selected>Product/Service</option>
                    <option value="Consumable">Consumable</option>
                    <option value="Equipment">Equipment</option>
                    <option value="Furnishing/Appliance">Furnishing/Appliance</option>
                    <option value="Other">Others</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <input type="number" class="form-control" id="quantity" placeholder="Quantity" name="f_qty">
            </div>
        </div>
		<!-- Remarks Text Area -->
        <div class="form-group">
		<textarea class="form-control" id="remarks" rows="4" placeholder="Enter Notes Here" name="f_notes"></textarea>
		</div>
                    
    </div>
                </div>
                <div class="modal-footer">
					<button type="button" class="btn btn-success add-field-button">
                        <i class="fas fa-plus"></i>
					</button>
					<button type="button" class="btn btn-danger delete-field-button">
                        <i class="fas fa-trash"></i>
                    </button>
					<button type="submit" class="btn btn-primary" id="sendButton" name="send_btn">Send</button>
                </div>
            </div>
        </div>
		</form>
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
                    <a class="btn btn-primary" href="register.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/admin-dash-2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        $(document).ready(function () {
			var count = 1;
            // Handle "Add Field" button click
            $('.add-field-button').click(function () {
				count += 1;
                $('.customClass').append('\
				<div id="extraFields['+count+']">\
				<div class="form-row">\
					<label for="padding">Field '+count+'</label>\
					<input type="hidden" id="padding" value="extra" name="indicator[]">\
				</div>\
				<div class="form-row">\
            <div class="form-group col-md-4">\
                <select class="form-control" id="requestType" name="request[]">\
                    <option value="" disabled selected>Request Type</option>\
                    <option value="Borrow">Borrow</option>\
                    <option value="Repair">Repair</option>\
                    <option value="Purchase">Purchase</option>\
					<option value="Transfer">Transfer</option>\
					<option value="Acquire">Acquire</option>\
                </select>\
            </div>\
            <div class="form-group col-md-4" id="productServiceContainer">\
			<select class="form-control" id="productService" name="service[]">\
                    <option value="" disabled selected>Product/Service</option>\
                    <option value="Consumable">Consumable</option>\
                    <option value="Equipment">Equipment</option>\
                    <option value="Furnishing/Appliance">Furnishing</option>\
                    <option value="Other">Others</option>\
                </select>\
            </div>\
            <div class="form-group col-md-4">\
                <input type="number" class="form-control" id="quantity" placeholder="Quantity" name="qty[]">\
            </div>\
        </div>\
        <div class="form-group">\
		<textarea class="form-control" id="remarks" rows="4" placeholder="Enter Additional Notes" name="notes[]"></textarea>\
		</div>\
		</div>\
		');
            });

            // Handle "Delete Field" button click
            $('.delete-field-button').click(function () {
                let div = document.getElementById('extraFields['+count+']');
				div.parentNode.removeChild(div);
				count += -1;
            });

        });
    </script>
</body>
</html>
