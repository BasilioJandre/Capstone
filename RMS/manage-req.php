<?php
include('database/php/conn.php');
include('database/php/session.php');
date_default_timezone_set('Asia/Manila');
	
$curr_date = date('Y-m-d');
$curr_date_ex = explode(' ',$curr_date);
$curr_date_s = "$curr_date_ex[0]";
$curr_date = date("Y-m-d", strtotime($curr_date_s));

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

//Outgoing Requests
if($dept == 'College Dean')
{
	$Condition = "`Status` = 'Pending' AND (`Department`= 'College Faculty' OR `Department`= 'College Guidance' OR `Department`= 'College Library' OR `Department`= 'College O.S.A')";
}

elseif($dept == 'Junior High School Principal' || $dept == 'Senior High School Principal')
{
	$Condition = "`Status` = 'Pending' AND (`Department`= 'High School Faculty (BHS)' OR `Department`= 'High School Faculty (GHS)' OR `Department`= 'High School Academics' OR `Department`= 'High School Guidance' OR `Department`= 'High School Library' OR `Department`= 'High School Laboratory' OR `Department`= 'High School O.S.A' OR `Department` = 'High School Reading Center')";
}

elseif($dept == 'Grade School Principal')
{
	$Condition = "`Status` = 'Pending' AND (`Department` = 'Grade School Academics' OR `Department` = 'Grade School E.C.E' OR `Department` = 'Grade School Faculty' OR `Department` = 'Grade School Guidance' OR `Department` = 'Grade School Library' OR `Department` = 'Grade School O.S.A')";
}

elseif($dept == 'Finance')
{
	$Condition = "`Status` = 'Pending' AND (`Department` = 'Treasury' OR `Department` = 'Accounting' OR `Department` = 'Budget and Control' OR `Department` = 'Bookstore' OR `Department` = 'Canteen' OR `Department` = 'Printing' OR `Department` = 'Purchasing' OR `Department` = 'Stocks')";
}

elseif($dept == 'CFO')
{
	$Condition = "`Status` = 'Pending' AND (`Department` = 'Sister Quarter' OR `Department` = 'Security Office' OR `Department` = 'Campus Ministry' OR `Department` = 'Pastoral Ministry')";
}

elseif($dept == 'ICTC')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'ICTC'";
}

elseif($dept == 'GSU')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'GSU'";
}

elseif($dept == 'HRMO')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'HRMO'";
}

elseif($dept == 'Registrar')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'Registrar'";
}

elseif($dept == 'Aula')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'Aula'";
}

elseif($dept == 'Alumni Office')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'Alumni Office'";
}

elseif($dept == 'Medical-Dental')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'Medical-Dental'";
}

elseif($dept == 'Mini Hotel')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'Mini Hotel'";
}

elseif($dept == 'PAASCU')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'PAASCU'";
}

elseif($dept == 'School of Graduate Studies' || $dept == 'Research')
{
	$Condition = "`Status` = 'Pending' AND (`Department` = 'SGS Library' OR `Department` = 'School of Graduate Studies' OR `Department` = 'Research')";
}

elseif($dept == 'TVSD')
{
	$Condition = "`Status` = 'Pending' AND `Department` = 'TVSD'";
}

elseif($dept == 'Budget and Control')
{
	$Condition = "(`Forward_To` = 'Budget and Control' AND `Request_Type` != 'Purchase') AND `Active` = 'yes'";
}

elseif($dept == 'VPAA' || $dept == 'EVP' || $dept == 'Office of the President')
{
	$Condition = "(`Forward_To` = 'VPAA Office' OR `Forward_To` = 'EVP Office' OR `Forward_To` = 'Office of the President' OR `Department` = 'EVP' OR `Department` = 'VPAA' OR `Department` = 'Office of the President') AND (`Active` = 'yes' AND `Noted_By_Budget` = '')";
}

else
{
	$Condition = "`Department` = 'NA'";
}

$GetReq = mysqli_query($conn, "SELECT * FROM `requests` WHERE ".$Condition."");
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
	$ReqDate_x = $Req['Date_Requested'];
	$ReqDate = date("m/d/Y", strtotime($ReqDate_x));
	$NeedDate_x = $Req['Date_Needed'];
	$NeedDate = date("m/d/Y", strtotime($NeedDate_x));
	$Status = $Req['Status'];
	$AddNotes = $Req['Additional_Notes'];
	$Active = $Req['Active'];
	
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

if($dept == 'Budget and Control')
{
	$ReqView .= '
		
	<div class="modal fade" id="viewModal'.$ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
		<form action="manage-req.php" method="POST">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="width:750px;">
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
						<p>Description:</p>
						<textarea rows="7" cols="49" disabled>'.$ReqDesc.'</textarea>
						<p>Request Date: '.$ReqDate.'</p>
						<p>Date Needed: '.$NeedDate.'</p>
						<p>Status: '.$Status.'</p>

						<!-- Add a dropdown menu to select the request status -->
						<div class="form-group">
							<label for="requestStatus">Forward To:</label>
							<select class="form-control" id="requestStatus" name="forward" required>
								<option value="Office of the President" selected>Office of the President</option>
								<option value="School of Graduate Studies">School of Graduate Studies</option>
								<option value="Research">Research</option>
								<option value="CFO">CFO</option>
								<option value="Admin $ Gen.Facilities">Admin & Gen.Facilities</option>
								<option value="Registrar">Registrar</option>
								<option value="College">College</option>
								<option value="JHS">JHS</option>
								<option value="SHS">SHS</option>
								<option value="Grade School">Grade School</option>
								<option value="IOSA">IOSA</option>
								<option value="Finance">Finance</option>
								<option value="HRMO">HRMO</option>
								<option value="ICTC">ICTC</option>
								<option value="EVP Office">EVP Office</option>
								<option value="VPAA Office">VPAA Office</option>
								<option value="GSU">GSU</option>
								<option value="Medical-Dental">Medical-Dental</option>
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
						<button type="submit" class="btn btn-danger save-status" name="decline_request">Decline Request</button>
						<button type="submit" class="btn btn-primary save-status" name="send_request">Send Request</button>
					</div>
				</div>
			</div>
		</form>
	</div>
		
	';

}

else
{
	$ReqView .= '
		
	<div class="modal fade" id="viewModal'.$ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
		<form action="manage-req.php" method="POST">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="width:750px;">
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
							<label for="requestStatus">Forward To:</label>
							<select class="form-control" id="requestStatus" name="forward" required>
								<option value="Budget and Control" selected>Budget and Control</option>
								<option value="EVP Office">EVP Office</option>
								<option value="VPAA Office">VPAA Office</option>
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
						<button type="submit" class="btn btn-danger save-status" name="decline_request">Decline Request</button>
						<button type="submit" class="btn btn-primary save-status" name="send_request">Send Request</button>
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
				<form action="manage-req.php" method="POST">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger delete-confirm" name="btn_del">Delete</button>
					<input type="hidden" value="'.$ReqNo.'" name="del_req_id">
                </div>
				</form>
            </div>
        </div>
    </div>
	
	';
}



//Incoming Requests
if($dept == 'College Dean')
{
	$I_Condition = "`Forward_To`= 'College' AND `Active` = 'yes'";
}

elseif($dept == 'Junior High School Principal' || $dept == 'Senior High School Principal')
{
	$I_Condition = "`Forward_To` = 'JHS' OR `Forward_To` = 'SHS' AND `Active` = 'yes'";
}

elseif($dept == 'Grade School Principal')
{
	$I_Condition = "`Forward_To` = 'Grade School' AND `Active` = 'yes'";
}

elseif($dept == 'ICTC')
{
	$I_Condition = "`Forward_To` = 'ICTC' AND `Active` = 'yes'";
}

elseif($dept == 'GSU')
{
	$I_Condition = "`Forward_To` = 'GSU' AND `Active` = 'yes'";
}

elseif($dept == 'CFO')
{
	$I_Condition = "`Forward_To` = 'CFO' AND `Active` = 'yes' OR `Forward_To` = 'Admin $ Gen.Facilities' AND `Active` = 'yes'";
}

elseif($dept == 'HRMO')
{
	$I_Condition = "`Forward_To` = 'HRMO' AND `Active` = 'yes'";
}

elseif($dept == 'Registrar')
{
	$I_Condition = "`Forward_To` = 'Registrar' AND `Active` = 'yes'";
}

elseif($dept == 'IOSA')
{
	$I_Condition = "`Forward_To` = 'IOSA' AND `Active` = 'yes'";
}

elseif($dept == 'Medical-Dental')
{
	$I_Condition = "`Forward_To` = 'Medical-Dental' AND `Active` = 'yes'";
}

elseif($dept == 'School of Graduate Studies' || $dept == 'Research')
{
	$I_Condition = "(`Forward_To` = 'School of Graduate Studies' OR `Forward_To` = 'Research') AND `Active` = 'yes'";
}

elseif($dept == 'TVSD')
{
	$I_Condition = "`Forward_To` = 'TVSD' AND `Active` = 'yes'";
}

elseif($dept == 'Finance')
{
	$I_Condition = "`Forward_To` = 'Finance' AND `Active` = 'yes'";
}

elseif($dept == 'Budget and Control')
{
	$I_Condition = "(`Status` = 'Requires Purchase' OR `Status` = 'Item Purchased' OR `Status` = 'Item Delivered') OR (`Request_Type` = 'Purchase' AND `Forward_To` = 'Budget and Control') AND `Active` = 'yes'";
}
elseif($dept == 'VPAA' || $dept == 'EVP' || $dept == 'Office of the President')
{
	$I_Condition = "(`Forward_To` = 'VPAA Office' OR `Forward_To` = 'EVP Office' OR `Forward_To` = 'Office of the President') AND (`Noted_By_Budget` != '' AND `Active` = 'yes')";
}

else
{
	$I_Condition = "`Forward_To` = 'NA'";
}

$I_GetReq = mysqli_query($conn, "SELECT * FROM `requests` WHERE ".$I_Condition."");
$I_ReqList = '';
$I_ReqView = '';
$I_ReqDel = '';

while($I_Req = mysqli_fetch_assoc($I_GetReq))
{
	$I_ReqNo = $I_Req['Requisition_No'];
	$I_ReqName = $I_Req['User_Name'];
	$I_ReqID = $I_Req['User_ID'];
	$I_ReqDept = $I_Req['Department'];
	$I_ReqType = $I_Req['Request_Type'];
	$I_ReqServ = $I_Req['Product/Service'];
	$I_ReqDesc = $I_Req['Description'];
	$I_ReqDate_x = $I_Req['Date_Requested'];
	$I_ReqDate = date("m/d/Y", strtotime($I_ReqDate_x));
	$I_NeedDate_x = $I_Req['Date_Needed'];
	$I_NeedDate = date("m/d/Y", strtotime($I_NeedDate_x));
	$I_Status = $I_Req['Status'];
	$I_AddNotes = $I_Req['Additional_Notes'];
	
	$I_ReqList .= '
	
	<tr>
	<td>'.$I_ReqNo.'</td>
	<td>'.$I_ReqName.' ('.$I_ReqID.')</td>
	<td>'.$I_ReqDept.'</td>
	<td>'.$I_ReqType.' ('.$I_ReqServ.')</td>
	<td>'.$I_ReqDesc.'</td>
	<td>'.$I_ReqDate.'</td>
	<td>'.$I_NeedDate.'</td>
	<td>'.$I_Status.'</td>
	<td>
	<button class="btn btn-primary view-btn" data-toggle="modal" data-target="#viewModal'.$I_ReqNo.'"><i class="fas fa-eye"></i></button>
	<button class="btn btn-danger delete-btn" data-toggle="modal" data-target="#deleteModal'.$I_ReqNo.'"><i class="fas fa-trash"></i></button>
	</td>
	</tr>
	
	';

if($I_ReqType == 'Repair' && $dept != 'Budget and Control')
{
	$I_ReqView .= '
		
	<div class="modal fade" id="viewModal'.$I_ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
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
						<p>Request No.: '.$I_ReqNo.'</p>
						<p>Requestor: '.$I_ReqName.'</p>
						<p>Department: '.$I_ReqDept.'</p>
						<p>Type of Request: '.$I_ReqType.' ('.$I_ReqServ.')</p>
						<p>Description: '.$I_ReqDesc.'</p>
						<p>Request Date: '.$I_ReqDate.'</p>
						<p>Date Needed: '.$I_NeedDate.'</p>
						<p>Status: '.$I_Status.'</p>

						<!-- Add a dropdown menu to select the request status -->
						<div class="form-group">
							<label for="requestStatus">Status:</label>
							<select class="form-control" id="requestStatus" name="status" required>
								<option value="" selected disabled>Select Status</option>
								<option value="Repaired">Repaired</option>
								<option value="Requires Purchase">Requires Purchase</option>
								<option value="Pending">Pending</option>
							</select>
						</div>
						<div class="form-group">
							<label for="requestStatus">Remarks:</label>
						<div class="fixed-input-box">
						<textarea rows="7" cols="49" name="note_area">'.$I_AddNotes.'</textarea>
						<input type="hidden" value="'.$I_ReqNo.'" name="up_req_id">
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

elseif($dept == 'Budget and Control' )
{
	$I_ReqView .= '
		
	<div class="modal fade" id="viewModal'.$I_ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
		<form action="manage-req.php" method="POST">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="width:550px;">
					<div class="modal-header">
						<h5 class="modal-title" id="viewModalLabel">View Request</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- Add your view content here -->
						<p>Request Details:</p>
						<p>Request No.: '.$I_ReqNo.'</p>
						<p>Requestor: '.$I_ReqName.'</p>
						<p>Department: '.$I_ReqDept.'</p>
						<p>Type of Request: '.$I_ReqType.' ('.$I_ReqServ.')</p>
						<p>Description: '.$I_ReqDesc.'</p>
						<p>Request Date: '.$I_ReqDate.'</p>
						<p>Date Needed: '.$I_NeedDate.'</p>
						<p>Status: '.$I_Status.'</p>

						<!-- Add a dropdown menu to select the request status -->
						<div class="form-group">
							<label for="requestStatus">Status:</label>
							<select class="form-control" id="requestStatus" name="status" required>
								<option value="Item Purchased" selected>Item Purchased</option>
								<option value="Item Delivered">Item Delivered</option>
							</select>
						</div>
						<div class="form-group">
							<label for="requestStatus">Remarks:</label>
						<div class="fixed-input-box">
						<textarea rows="7" cols="49" name="note_area">'.$I_AddNotes.'</textarea>
						<input type="hidden" value="'.$I_ReqNo.'" name="up_req_id">
						</div>
						</div>

						<!-- Add more request details as needed -->
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-danger save-status" name="decline_request">Decline Request</button>
						<button type="submit" class="btn btn-primary save-status" name="item_purchase">Save Status</button>
					</div>
				</div>
			</div>
		</form>
	</div>
		
	';
}

else
{
	$I_ReqView .= '
		
	<div class="modal fade" id="viewModal'.$I_ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
		<form action="manage-req.php" method="POST">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="width:550px;">
					<div class="modal-header">
						<h5 class="modal-title" id="viewModalLabel">View Request</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<!-- Add your view content here -->
						<p>Request Details:</p>
						<p>Request No.: '.$I_ReqNo.'</p>
						<p>Requestor: '.$I_ReqName.'</p>
						<p>Department: '.$I_ReqDept.'</p>
						<p>Type of Request: '.$I_ReqType.' ('.$I_ReqServ.')</p>
						<p>Description: '.$I_ReqDesc.'</p>
						<p>Request Date: '.$I_ReqDate.'</p>
						<p>Date Needed: '.$I_NeedDate.'</p>
						<p>Status: '.$I_Status.'</p>

						<!-- Add a dropdown menu to select the request status -->
						<div class="form-group">
							<label for="requestStatus">Status:</label>
							<select class="form-control" id="requestStatus" name="status" required>
								<option value="" selected disabled>Select Status</option>
								<option value="Approved">Approve</option>
								<option value="Declined">Decline</option>
								<option value="Requires Purchase">Requires Purchase</option>
							</select>
						</div>
						<div class="form-group">
							<label for="requestStatus">Remarks:</label>
						<div class="fixed-input-box">
						<textarea rows="7" cols="49" name="note_area">'.$I_AddNotes.'</textarea>
						<input type="hidden" value="'.$I_ReqNo.'" name="up_req_id">
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
	$I_ReqDel .= '
	
	<div class="modal fade" id="deleteModal'.$I_ReqNo.'" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
				<form action="manage-req.php" method="POST">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger delete-confirm" name="btn_del">Delete</button>
					<input type="hidden" value="'.$I_ReqNo.'" name="del_req_id">
                </div>
				</form>
            </div>
        </div>
    </div>
	
	';
}

//Save Outgoing Status
if(isset($_POST['send_request']))
{
	if($dept == 'Budget and Control')
	{
		$UpReq = $_POST['up_req_id'];
		$AddNotes = $_POST['note_area'];
		$Forward = $_POST['forward'];
		
		$update_req = mysqli_query($conn, "UPDATE `requests` SET `Additional_Notes` = '$AddNotes',`Status` = 'Forwarded', `Forward_To` = '$Forward', `Noted_By_Budget` = '$name($user_id)' WHERE `Requisition_No` = '$UpReq'");
		$update_track = mysqli_query($conn, "UPDATE `track` SET `Forward_Budget` = '$curr_date', `Forward_Budget_To` = '$Forward' WHERE `Request_No` = '$UpReq'");
	}
	
	elseif($dept == 'EVP' || $dept == 'VPAA' || $dept == 'Office of the President')
	{
		$UpReq = $_POST['up_req_id'];
		$AddNotes = $_POST['note_area'];
		$Forward = $_POST['forward'];
		
		$check_noted_by = mysqli_query($conn, "SELECT * FROM `requests` WHERE `Requisition_No` = '$UpReq'");
		$noted_by = mysqli_fetch_assoc($check_noted_by);
		
		if(!empty($noted_by['Noted_By']))
		{
			$additional_noted = $name.'('.$user_id.') / '.$noted_by['Noted_By'];
		}
		
		else
		{
			$additional_noted = $name.'('.$user_id.')';
		}
		
		
		$update_req = mysqli_query($conn, "UPDATE `requests` SET `Additional_Notes` = '$AddNotes',`Status` = 'Forwarded', `Forward_To` = '$Forward', `Noted_By` = '$additional_noted' WHERE `Requisition_No` = '$UpReq'");
		$update_track = mysqli_query($conn, "UPDATE `track` SET `Forward_EVP/VPAA` = '$curr_date' WHERE `Request_No` = '$UpReq'");
	}
	
	else
	{
		$UpReq = $_POST['up_req_id'];
		$AddNotes = $_POST['note_area'];
		$Forward = $_POST['forward'];
		
		$update_req = mysqli_query($conn, "UPDATE `requests` SET `Additional_Notes` = '$AddNotes',`Status` = 'Forwarded', `Forward_To` = '$Forward', `Noted_By` = '$name($user_id)' WHERE `Requisition_No` = '$UpReq'");
		$update_track = mysqli_query($conn, "UPDATE `track` SET `Forward_Head` = '$curr_date', `Forward_Head_To` = '$Forward' WHERE `Request_No` = '$UpReq'");
	}
	if($update_req)
	{
		Header("Refresh:0");
	}
}

//Save Incoming Status
if(isset($_POST['save_status']))
{
	$UpReq = $_POST['up_req_id'];
	$AddNotes = $_POST['note_area'];
	$Status = $_POST['status'];
	
	$update_req = mysqli_query($conn, "UPDATE `requests` SET `Additional_Notes` = '$AddNotes',`Status` = '$Status', `Approved_By` = '$name($user_id)' WHERE `Requisition_No` = '$UpReq'");
	$update_track = mysqli_query($conn, "UPDATE `track` SET `Handled_Date` = '$curr_date', `Request_Status` = '$Status' WHERE `Request_No` = '$UpReq'");
	
	if($Status = 'Declined' || $Status = 'Repaired')
	{
		$update_req = mysqli_query($conn, "UPDATE `requests` SET `Active` = 'no' WHERE `Requisition_No` = '$UpReq'");
	}
	if($update_req)
	{
		Header("Refresh:0");
	}
}

//Item Purchase
if(isset($_POST['item_purchase']))
{
	$UpReq = $_POST['up_req_id'];
	$AddNotes = $_POST['note_area'];
	$purchase_status = $_POST['status'];
	if($purchase_status == 'Item Purchased')
	{
		$update_track = mysqli_query($conn, "UPDATE `track` SET `Purchase_Date` = '$curr_date' WHERE `Request_No` = '$UpReq'");
		$update_req = mysqli_query($conn, "UPDATE `requests` SET `Additional_Notes` = '$AddNotes',`Status` = '$purchase_status' WHERE `Requisition_No` = '$UpReq'");
	}
	if($purchase_status == 'Item Delivered')
	{
		$update_track = mysqli_query($conn, "UPDATE `track` SET `Deliver_Date` = '$curr_date' WHERE `Request_No` = '$UpReq'");
		$update_req = mysqli_query($conn, "UPDATE `requests` SET `Additional_Notes` = '$AddNotes',`Status` = '$purchase_status', `Approved_By` = '$name($user_id)' WHERE `Requisition_No` = '$UpReq'");
	}
	
	if($update_req)
	{
		Header("Refresh:0");
	}
}

//Delete
if(isset($_POST['btn_del']))
{
	$DelReqID = $_POST['del_req_id'];
	$query = mysqli_query($conn,"SELECT * FROM `requests` WHERE `Requisition_No` = '$DelReqID'");
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

//Decline
if(isset($_POST['decline_request']))
{
	$UpReq = $_POST['up_req_id'];
	$AddNotes = $_POST['note_area'];
	
	$update_request = mysqli_query($conn, "UPDATE `requests` SET `Approved_By` = '$name($user_id)', `Status` = 'Declined', `Active` = 'no' WHERE `Requisition_No` = '$UpReq'");
	$update_track = mysqli_query($conn, "UPDATE `track` SET `Handled_Date` = '$curr_date', `Request_Status` = 'Declined' WHERE `Request_No` = '$UpReq'");
	
	if($update_track)
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
			<li class="nav-item">
                <a class="nav-link" href="head-request.php">
                    <i class="fa fa-plus-square"></i>
                    <span>Request Process</span>
                </a>
            </li>
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
                <div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Outgoing Request</h6>
                <div class="input-group" style="width:300px;">
                    <input type="text" class="form-control" id="searchInputOutgoing" placeholder="Search...">
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" id="searchButtonOutgoing">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableOutgoing" width="100%" cellspacing="0">
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
                        <?php echo $ReqList; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Incoming Request</h6>
                <div class="input-group" style="width:300px;">
                    <input type="text" class="form-control" id="searchInputIncoming" placeholder="Search...">
                    <div class="input-group-append">
                        <button class="btn btn-primary btn-sm" id="searchButtonIncoming">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableIncoming" width="100%" cellspacing="0">
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
                       <?php echo $I_ReqList; ?>
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

    <!-- View Modal -->
    <?php echo $ReqView; ?>
	<?php echo $I_ReqView; ?>

    <!-- Delete Modal -->
    <?php echo $ReqDel; ?>
	<?php echo $I_ReqDel; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/admin-dash-2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="js/profilephoto.js"></script>
<script>
    $(document).ready(function () {
        // Outgoing Request Search
        $("#searchButtonOutgoing").click(function () {
            var searchValue = $("#searchInputOutgoing").val().toLowerCase();
            $("#dataTableOutgoing tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
            });
        });

        // Incoming Request Search
        $("#searchButtonIncoming").click(function () {
            var searchValue = $("#searchInputIncoming").val().toLowerCase();
            $("#dataTableIncoming tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1);
            });
        });
    });
</script>
<script>
$(document).ready(function() {
    $("#searchButton").click(function() {
        var searchValue = $("#searchInput").val().toLowerCase();
        $("#dataTable1 tbody tr").each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        $("#dataTable2 tbody tr").each(function() {
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
</body>
</html>