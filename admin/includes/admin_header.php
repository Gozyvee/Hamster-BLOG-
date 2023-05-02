<?php include "../includes/db.php"; ?>
<?php include "functions.php"; ?>
<?php ob_start(); ?>
<?php session_start(); ?>

<?php 

  // Check if the user is logged in and has the admin role
if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
    // User is authorized, allow access to the page
} else {
    // User is not authorized, redirect to login page
    header("Location: ../index.php");
    exit();
}
if (isset($_SESSION['session_token']) && isset($_SESSION['user_id'])) {
    // Get the session token and user ID from the session
    $session_token = $_SESSION['session_token'];
    $user_id = $_SESSION['user_id'];
    
    // Check if the session token exists in the database
    $stmt = $connection->prepare("SELECT * FROM sessions WHERE session_token = ? AND user_id = ?");
    $stmt->bind_param("si", $session_token, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
      // Session token found, check if it has expired
      $row = $result->fetch_assoc();
      $expiry_time = strtotime($row['expiry_time']);
      
      if ($expiry_time > time()) {
        // Session token is valid and has not expired
        return true;
      } else {
          // Session token is invalid or has expired
           return false;
      }
    }
  } 

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> Admin </title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- <script type="text/javascript" src="https://google.com/jsapi"></script>     -->
    <!-- <script src="../js/jquery1.js"></script>     -->
    <link href="css/loader.css" rel="stylesheet">
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>


   <!-- HTML code with the textarea element and CKEditor 5 Classic editor library -->

</head>
<body>

