<?php
    if (isset($_GET['edit_user'])) {
        $the_user_id = $_GET['edit_user'];

        $query = "SELECT * FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $the_user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_password = $row['user_password'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_image = $row['user_image'];
            $user_role = $row['user_role'];
        }
    }else{
        header("Location: index.php");
    }


    if (isset($_POST['edit_user'])) {
        // Sanitized input 
        $user_firstname = escape($_POST['user_firstname']); 
        $user_lastname = escape($_POST['user_lastname']);
        $user_role = escape($_POST['user_role']);
        $username = escape($_POST['username']);
        $user_email = escape($_POST['user_email']);
        $user_password = escape($_POST['user_password']);
        $user_password = password_hash($user_password, PASSWORD_ARGON2I);

        $query = "UPDATE users SET ";
        $query .= "user_firstname = ?, ";
        $query .= "user_lastname = ?, ";
        $query .= "user_role = ?, ";
        $query .= "username = ?, ";
        $query .= "user_email = ?, ";
        $query .= "user_password = ? ";
        $query .= "WHERE user_id = ? ";

        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ssssssi", $user_firstname, $user_lastname, $user_role, $username, $user_email, $user_password, $the_user_id);
        mysqli_stmt_execute($stmt);

        // Use session tokens to prevent session hijacking attacks
        session_regenerate_id();

        // Redirect to a secure page after successful login
        header("Location: users.php");
        exit();
    }
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Firstname</label>
        <input type="text" value="<?php echo1($user_firstname) ?>" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="title">Lastname</label>
        <input type="text" value="<?php echo1($user_lastname) ?>" class="form-control" name="user_lastname">
    </div>

    <div class="form-group">
        <select name="user_role">
            <option value="<?php echo1 ($user_role); ?>"><?php echo1($user_role) ?></option>
            <?php
            if ($user_role == 'admin') {
                echo " <option value='subscriber'>Subscriber</option>";
            } else {
                echo " <option value='admin'>Admin</option>";
            }
            ?>

        </select>
    </div>

    <div class="form-group">
        <label for="Username">Username</label>
        <input type="text" value="<?php echo1($username) ?>" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="Email">Email</label>
        <input type="email" value="<?php echo1($user_email) ?>" class="form-control" name="user_email">
    </div>
    <div class="form-group">
        <label for="Password">Password</label>
        <input type="password" value="" class="form-control" name="user_password">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="edit_user" value="Edit user">
    </div>

</form>