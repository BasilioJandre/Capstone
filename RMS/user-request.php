<?php
include('database/php/conn.php');
include('database/php/session.php');
date_default_timezone_set('Asia/Manila');


$date = date('Y-m-d');

//Tracking and Request History
$req_query = mysqli_query($conn,"SELECT * FROM `requests` WHERE `User_ID` = '$user_id'");
$ReqList ='';
$TrackList='';
$DelList='';
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
	$Req_Forward = $get_req['Forward_To'];
	
	$track_query = mysqli_query($conn,"SELECT * FROM `track` WHERE `Request_No` = '$ReqNo'");
	$tracking = mysqli_fetch_assoc($track_query);
	
	if(!empty($tracking['Forward_Head']))
	{
	$forwarded_head = $tracking['Forward_Head'];
	}
	
	if(!empty($tracking['Forward_Head_To']))
	{
	$forward_head_to = $tracking['Forward_Head_To'];
	}
	
	if(!empty($tracking['Forward_EVP/VPAA']))
	{
	$forwarded_evp_vpaa = $tracking['Forward_EVP/VPAA'];
	}
	
	if(!empty($tracking['Forward_Budget']))
	{
	$forwarded_budget = $tracking['Forward_Budget'];
	}
	
	if(!empty($tracking['Forward_Budget_To']))
	{
	$forward_budget_to = $tracking['Forward_Budget_To'];
	}
	
	if(!empty($tracking['Handled_Date']))
	{
	$handled_date = $tracking['Handled_Date'];
	}
	
	if(!empty($tracking['Request_Status']))
	{
	$request_status = $tracking['Request_Status'];
	}
	
	if(!empty($tracking['Purchase_Date']))
	{
	$p_date = $tracking['Purchase_Date'];
	}
	
	if(!empty($tracking['Deliver_Date']))
	{
	$d_date = $tracking['Deliver_Date'];
	}
	
	$tracking_list = '';
	
	if(!empty($forwarded_head))
	{
		$tracking_list .= '
		
		<li>
		<div class="status">('.$forwarded_head.') Request has been Forwarded</div>
		<div class="location">Your Department Head has forwarded your request to '.$forward_head_to.'</div>
		</li>
		
		';
	}
	
	if(!empty($forwarded_evp_vpaa))
	{
		
		$tracking_list .='
		
		<li>
		<div class="status">('.$forwarded_evp_vpaa.') Request has been Forwarded</div>
		<div class="location">Request has been forwarded to Budget and Control</div>
		</li>
		
		';
	}
	
	if(!empty($forwarded_budget))
	{
		
		$tracking_list .='
		
		<li>
		<div class="status">('.$forwarded_budget.') Request has been Forwarded</div>
		<div class="location">Request has been received by '.$forward_budget_to.'</div>
		</li>
		
		';
	}
	
	if(!empty($handled_date))
	{
		
		if($request_status == 'Requires Purchase')
		{
			$tracking_list .='
			
			<li>
			<div class="status">('.$handled_date.') Request has been returned to Budget and Control</div>
			<div class="location">Request '.$request_status.'</div>
			</li>
			
			';
		}
		else
		{
			$tracking_list .='
			
			<li>
			<div class="status">('.$handled_date.')Request '.$request_status.'</div>
			<div class="location"></div>
			</li>
			
			';
		}
	}
	
	if(!empty($p_date))
	{
		
		$tracking_list .='
		
		<li>
		<div class="status">('.$p_date.') Item has been Purchased</div>
		<div class="location">The item will be delivered shortly</div>
		</li>
		
		';
	}
	
	if(!empty($d_date))
	{
		
		$tracking_list .='
		
		<li>
		<div class="status">('.$d_date.') Item has been Delivered</div>
		<div class="date"></div>
		</li>
		
		<li>
		<div class="status">Request Closed</div>
		</li>
		
		';
	}
	
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
	<a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#trackModal'.$ReqNo.'">
		<i class="fas fa-eye"></i> Track
	</a>
	<a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal'.$ReqNo.'">
		<i class="fas fa-trash"></i> Delete
	</a>
	</td>
	</tr>
	
	';
	
	$DelList .='
	
    <div class="modal fade" id="deleteModal'.$ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
	<form action="user-request.php" method="POST">
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
                    <button type="submit" class="btn btn-danger" name="btn_del">Delete</button>
					<input type="hidden" value="'.$ReqNo.'" name="del_req_id">
                </div>
            </div>
        </div>
	</form>
    </div>
	
	';
	
	$TrackList .='
	<div class="modal fade" id="trackModal'.$ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="trackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trackModalLabel">Track Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tracking-status">
                    <div class="tracking-number">
						<h5>
                        <strong>Request Number:</strong>
                        <span id="requestNumber">'.$ReqNo.'</span>
						</h5>
                    </div>
                    <div class="tracking-date">
						<h5>
                        <strong>Date Made:</strong>
                        <span id="dateMade">'.$ReqDate.'</span>
						</h5>
					</div>
                    <div class="tracking-date">
						<h5>
                        <strong>Date Needed:</strong>
                        <span id="dateNeeded">'.$ReqNDate.'</span>
						</h5>
					</div>
                </div>
                <div class="tracking-history">
                    <h5>Tracking History</h5>
                    <ul>
                        <li>
                            <div class="status">('.$ReqDate.') Request Sent to your Department Head</div>
							<div class="location">your request shall be reviewed by the Department Head</div>
                        </li>
						'.$tracking_list.'
					</ul>
                </div>
            </div>
        </div>
    </div>
	</div>
	
	';
}


//Form Function
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
		
	$insert = mysqli_query($conn, "INSERT INTO `requests`(`User_Name`, `User_ID`, `Department`, `Date_Requested`, `Date_Needed`, `Request_Type`, `Product/Service`, `Quantity`, `Description`,`Status`,`Active`) VALUES ('$name','$user_id','$dept','$c_date','$n_date','$f_type','$f_service','$f_qty','$f_notes','Pending','yes')");
	
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
			
			$insert = mysqli_query($conn, "INSERT INTO `requests`(`User_Name`, `User_ID`, `Department`, `Date_Requested`, `Date_Needed`, `Request_Type`, `Product/Service`, `Quantity`, `Description`,`Status`,`Active`) VALUES ('$name','$user_id','$dept','$c_date','$n_date','$req_type','$req_service','$req_qty','$notes','Pending','yes')");
			$count += 1;
		}
		
	}
	if($insert)
	{
		Header("Refresh:0");
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

//Delete
if(isset($_POST['btn_del']))
{
	$DelReqID = $_POST['del_req_id'];
	$query = mysqli_query($conn,"SELECT * FROM `requests` WHERE `Requisition_No` = $DelReqID");
	$assign_value = mysqli_fetch_assoc($query);
	
	$a_req_id = $assign_value['Requisition_No'];
	$a_req_user = $assign_value['User_Name'];
	$a_req_uid = $assign_value['User_ID'];
	$a_req_dept = $assign_value['Department'];
	$a_req_rdate = $assign_value['Date_Requested'];
	$a_req_ndate = $assign_value['Date_Needed'];
	$a_req_type = $assign_value['Request_Type'];
	$a_req_serv = $assign_value['Product/Service'];
	$a_req_qty = $assign_value['Quantity'];
	$a_req_desc = $assign_value['Description'];
	$a_req_notes = $assign_value['Additional_Notes'];
	
	$archive = mysqli_query($conn, "INSERT INTO `archive`(`Requisition_No`, `User_Name`, `User_ID`, `Department`, `Date_Requested`, `Date_Needed`, `Request_Type`, `Product/Service`, `Quantity`, `Description`, `Additional_Notes`) VALUES ('$a_req_id','$a_req_user','$a_req_uid','$a_req_dept','$a_req_rdate','$a_req_ndate','$a_req_type','$a_req_serv','$a_req_qty','$a_req_desc','$a_req_notes')");
	
	if($archive)
	{
		$delete = mysqli_query($conn, "DELETE FROM `requests` WHERE `Requisition_No` = '$DelReqID'");
		Header("Refresh:0");
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
    
<?php echo $TrackList; ?>
<?php echo $DelList; ?>

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
                    <a class="btn btn-primary" href="logout.php">Logout</a>
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
	<script src="js/profilephoto.js"></script>
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

            $('.delete-field-button').click(function () {
                let div = document.getElementById('extraFields['+count+']');
				div.parentNode.removeChild(div);
				count += -1;
            });

        });
    </script>
</body>
</html>
