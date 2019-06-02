<?php
  require_once '/mapper/fileClass.php';
  require_once 'api/UserClass.php';

  if (isset($_GET['id']))
  {
    $id = $_GET['id'];
    $details = $file->file_details($id);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.$details['original_name'].'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $details['size']);
    readfile($details['file_path']);
    exit;
  } else {
    die("Not allowed");
  }

  ?>
