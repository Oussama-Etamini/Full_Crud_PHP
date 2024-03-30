<?php
require 'function.php';

$select = new Select();

if (!empty($_SESSION["id"])) {
    $user = $select->selectUserById($_SESSION["id"]);
} else {
    header("Location: login.php");
}
?>

<?php
require_once('db_conn.php');
require_once('fpdf/fpdf.php');

$result = "SELECT * FROM `users` ORDER BY id";
$sql = $conn->query($result);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 12);

// Set column widths
$colWidths = array(20, 40, 40, 60, 20);

// Header row
$pdf->SetFillColor(29, 44, 81);
$pdf->SetTextColor(255);
$pdf->Cell($colWidths[0], 10, 'ID', 1, 0, 'C', true);
$pdf->Cell($colWidths[1], 10, 'First Name', 1, 0, 'C', true);
$pdf->Cell($colWidths[2], 10, 'Last Name', 1, 0, 'C', true);
$pdf->Cell($colWidths[3], 10, 'Email', 1, 0, 'C', true);
$pdf->Cell($colWidths[4], 10, 'Gender', 1, 1, 'C', true);

// Data rows
$pdf->SetFont('Arial', '', 10);
$pdf->SetFillColor(255);
$pdf->SetTextColor(0);

while ($row = $sql->fetch_assoc()) {
    $pdf->Cell($colWidths[0], 10, $row['id'], 1, 0, 'C', false);  // No fill for data cells
    $pdf->Cell($colWidths[1], 10, $row['first_name'], 1, 0, 'L', false);
    $pdf->Cell($colWidths[2], 10, $row['last_name'], 1, 0, 'L', false);
    $pdf->Cell($colWidths[3], 10, $row['email'], 1, 0, 'L', false);
    $pdf->Cell($colWidths[4], 10, $row['gender'], 1, 1, 'C', false);
}

// Output the PDF as a file
$pdf->Output();
?>
