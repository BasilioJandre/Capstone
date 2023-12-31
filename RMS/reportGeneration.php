<?php
require('tcpdf/fpdf.php');
include('database/php/conn.php');
include('database/php/session.php');
date_default_timezone_set('Asia/Manila');


$curr_date = date('Y-m-d');
$curr_date_ex = explode(' ',$curr_date);
$curr_date_s = "$curr_date_ex[0]";
$curr_date_x = date("Y-m-d", strtotime($curr_date_s));
$curr_date = date("m/d/Y", strtotime($curr_date_x));

$first_date = $_SESSION['first_date'];
$last_date = $_SESSION['last_date'];
$month_display = $_SESSION['month_display'];

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

class PDF extends FPDF {
    function Header() {

    }

    function Footer() {

    }
}

$pdf = new PDF();
$pdf->AddPage();

$pdf->Image('img/pcc logo.png', 90, 10, 30);

$pdf->SetY(40);
$pdf->SetFont('times', 'B', 16);
$pdf->Cell(0, 10,'Pasig Catholic College', 0, 1, 'C'); 
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 0, 'Justice R. Jabson St., Malinao, Pasig City', 0, 1, 'C'); 
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 15, 'REQUISITION MONTHLY REPORT', 0, 1, 'C'); 
$pdf->SetFont('Arial', '', 12);

$get_department = mysqli_query($conn, "SELECT DISTINCT `Department` FROM `requests` WHERE `Date_Requested` BETWEEN '$first_date' AND '$last_date'");
while($departments = mysqli_fetch_assoc($get_department))
{
	$dept_report = $departments['Department'];
	$get_request = mysqli_query($conn, "SELECT * FROM `requests` WHERE `Department` = '$dept_report' AND `Active` = 'no' AND (`Date_Requested` BETWEEN '$first_date' AND '$last_date')");
	$count_report = mysqli_num_rows($get_request);
	
	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Ln(10);
	$pdf->Cell(80, 10, 'Department: '.$dept_report.'');
	$pdf->Ln(5);
	$pdf->Cell(80, 10, 'Request By Month: '.$month_display.'');
	$pdf->SetX(160);
	$pdf->Cell(160, 10, 'Total Requests: '.$count_report.'');
	$pdf->Ln(10);

	$pdf->SetFont('Arial', 'B', 9);
	$pdf->Cell(17, 5, 'Date', 1);
	$pdf->Cell(23, 5, 'Request Type', 1);
	$pdf->Cell(97, 5, 'Description', 1);
	$pdf->Cell(21, 5, 'Status', 1);
	$pdf->Cell(33, 5, 'Fulfilled By', 1);
	$pdf->Ln();	
	
	while($request = mysqli_fetch_assoc($get_request))
	{
		$req_id = $request['Requisition_No'];
		$get_fulfill = mysqli_query($conn, "SELECT * FROM `track` WHERE `Request_No` = '$req_id'");
		$fulfilled_by_x = mysqli_fetch_assoc($get_fulfill);
		$fulfilled_by = $fulfilled_by_x['Fulfilled_By'];
		
		$date_x = $request['Date_Requested'];
		$date = date("m/d/Y", strtotime($date_x));
		$department = $request['Department'];
		$reqtype = $request['Request_Type'];
		$description = $request['Description'];
		$status = $request['Status'];
		
		$pdf->SetFont('Arial', '', 8);
		$pdf->Cell(17, 5, $date, 1);
		$pdf->Cell(23, 5, $reqtype, 1);
		$pdf->Cell(97, 5, $description, 1);
		$pdf->Cell(21, 5, $status, 1);
		$pdf->Cell(33, 5, $fulfilled_by, 1);
		$pdf->Ln();
	}

}
$get_all_request = mysqli_query($conn, "SELECT * FROM `requests` WHERE `Active` = 'no' AND (`Date_Requested` BETWEEN '$first_date' AND '$last_date')");
$count_request = mysqli_num_rows($get_all_request);

$pdf->Ln(5);
$pdf->Cell(160, 10, 'Date Printed: '.$curr_date.'');
$pdf->SetX(150);
$pdf->Cell(160, 10, 'Over all Request: '.$count_request.'');

// Output PDF content to a variable
$pdfContent = $pdf->Output('', 'S');

// Output the PDF content as a preview
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="Requisition_Report_'.$curr_date.'.pdf"');
echo $pdfContent;