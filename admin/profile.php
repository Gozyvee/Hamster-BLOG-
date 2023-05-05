<?php include "includes/admin_header.php" ?>
<?php

    if (isset($_SESSION['username'])) {
          $username = $_SESSION['username'];

         $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
         $stmt->bind_param("s", $username);
         $stmt->execute();
         $result = $stmt->get_result();

        if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $user_id = $row['user_id'];
              $username = $row['username'];
              $user_firstname = $row['user_firstname'];
              $user_lastname = $row['user_lastname'];
              $user_email = $row['user_email'];
              $user_role = $row['user_role'];
        }
    }

    if (isset($_POST['edit_user'])) {
         $user_firstname = $_POST['user_firstname'];
         $user_lastname = $_POST['user_lastname'];
         $username = $_POST['username'];
         $user_email = $_POST['user_email'];

        // Check if user wants to change the password
        if (!empty($_POST['user_password'])) {
              $user_password = $_POST['user_password'];
              $stmt = $connection->prepare("UPDATE users SET user_firstname = ?, user_lastname = ?, username = ?, user_email = ?, user_password = ? WHERE username = ?");
              $stmt->bind_param("ssssss", $user_firstname, $user_lastname, $username, $user_email, $user_password, $username);
        } else {
            $stmt = $connection->prepare("UPDATE users SET user_firstname = ?, user_lastname = ?, username = ?, user_email = ? WHERE username = ?");
            $stmt->bind_param("sssss", $user_firstname, $user_lastname, $username, $user_email, $username);
        }
        $stmt->execute();
    }

    ?>

    <div id="wrapper">

    <?php include "includes/admin_navigation.php" ?>

    <div id="page-wrapper">
    <div class="container-fluid">
    <div class="col-lg-12">
    <h1 class="page-header">
        Welcome to admin
        <small><?php echo htmlspecialchars($username); ?></small>
    </h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Firstname</label>
            <input type="text" value="<?php echo htmlspecialchars($user_firstname); ?>" class="form-control" name="user_firstname">
        </div>
        <div class="form-group">
            <label for="title">Lastname</label>
            <input type="text" value="<?php echo htmlspecialchars($user_lastname); ?>" class="form-control" name="user_lastname">
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" value="<?php echo htmlspecialchars($username); ?>" class="form-control" name="username">
        </div>

        <div class="form-group">
            <label for="user_password">Password</label>
            <input autocomplete="off" type="password" value="" class="form-control" name="user_password">
        </div>

        <div class="form-group">
            <label for="user_email">Email</label>
            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>" name="user_email" ;>
        </div>


        <div class="form-group">
            <input type="submit" class="btn btn-primary mt-2" name="edit_user" value="Edit profile">
        </div>
    </form>
<?php include "includes/admin_footer.php" ?>