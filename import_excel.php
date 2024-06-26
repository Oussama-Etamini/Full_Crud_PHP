<?php
require 'vendor/autoload.php';
include 'db_conn.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_POST['save_data'])) {
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    $allowed_ext = ['xls', 'csv', 'xlsx'];
    if (in_array($file_ext, $allowed_ext)) {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();
        foreach ($data as $row) {
            $firstName = $row['0'];
            $lastName  = $row['1'];
            $email     =            $row['2'];
            $gender    = $row['3'];

            $user = "INSERT INTO users (first_name, last_name, email, gender) VALUES ('$firstName', '$lastName', '$email', '$gender')";
            $result = mysqli_query($conn, $user);

            if (!$result) {
                header('location:index.php?msg=Data not Imported');
                exit(0);
            }
        }
        header('location:index.php?msg=Data Imported Successfully');
        exit(0);
    } else {
        header('location:index.php?msg=File not allowed');
        exit(0);
    }
}
