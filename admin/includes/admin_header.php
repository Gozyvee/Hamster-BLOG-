<?php include "../includes/db.php"; ?>
<?php include "functions.php"; ?>
<?php ob_start(); ?>
<?php session_start(); ?>

<?php

// Check if the user is logged in and has the admin role

if (isset($_SESSION['session_token']) && isset($_SESSION['user_id'])) {
  if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
    // User is authorized, allow access to the page
  } else {
    // User is not authorized, redirect to login page
    header("Location: ../index.php");
    exit();
  } 
}else{
  exit();
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
  <link href="css/loader.css" rel="stylesheet">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <!-- HTML code with the textarea element and CKEditor 5 Classic editor library -->
  <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>
  <script src="js/jquery.js"></script>
</head>

<body>