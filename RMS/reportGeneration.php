<?php
require('tcpdf/fpdf.php');
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
$pdf->Cell(0, 0, 'Malinao, Pasig City', 0, 1, 'C'); 
$pdf->SetFont('Helvetica', 'B', 16);
$pdf->Cell(0, 10, 'REQUISITION MONTHLY REPORT', 0, 1, 'C'); 
$pdf->SetFont('Arial', '', 12);

$pdf->Ln(10);
$pdf->Cell(80, 10, 'Department:');
$pdf->Cell(80, 10, 'Request By Month:');
$pdf->Ln(10);
$pdf->Cell(160, 10, 'Total Requests: ');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Date', 1);
$pdf->Cell(40, 10, 'Department', 1);
$pdf->Cell(40, 10, 'Request Type', 1);
$pdf->Cell(70, 10, 'Description', 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);

$get_request = mysqli_query($conn,"SELECT * FROM `requests`");
while($request = mysqli_fetch_assoc($get_request))
{
	$date = $request['Date_Requested'];
	$department = $request['Department'];
	$reqtype = $request['Request_Type'];
	$description = $request['Description'];
	
	$pdf->Cell(40, 10, $date, 1);
	$pdf->Cell(40, 10, $department, 1);
	$pdf->Cell(40, 10, $reqtype, 1);
	$pdf->Cell(70, 10, $description, 1);
	$pdf->Ln();
}
// Output PDF content to a variable
$pdfContent = $pdf->Output('', 'S');

// Output the PDF content as a preview
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="requisition_report.pdf"');
echo $pdfContent;
