<?php
if (isset($_POST['create_user'])) {
    $user_lastname = escape($_POST['user_lastname']);
    $user_firstname = escape($_POST['user_firstname']);
    $username = escape($_POST['username']);
    $user_email = escape($_POST['user_email']);
    $user_password = escape($_POST['user_password']);
    $user_role = escape($_POST['user_role']);

    $password = password_hash($user_password, PASSWORD_ARGON2I);

    $query = "INSERT INTO users( user_firstname, user_lastname, user_role, username, user_email, user_password) ";
    $query .= "VALUES(?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $user_firstname, $user_lastname, $user_role, $username, $user_email, $password);
    $create_user_query = mysqli_stmt_execute($stmt);

    // Use session tokens to prevent session hijacking attacks
    session_regenerate_id();

    // echo "User Created: " . " " . "<a href='users.php'>View Users</a>";

    // Redirect to a secure page after successful login
    header("Location: users.php");
    exit();
}
?>



<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Firstname</label>
        <input type="text" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="title">Lastname</label>
        <input type="text" class="form-control" name="user_lastname">
    </div>

    <div class="form-group">
        <select name="user_role">
            <option value="subscriber">Select options</option>
            <option value="admin">Admin</option>
            <option value="subscriber">Subscriber</option>
        </select>
    </div>

    <div class="form-group">
        <label for="Username">Username</label>
        <input type="text" class="form-control" name="username">
    </div>
    <div class="form-group">
        <label for="Email">Email</label>
        <input type="email" class="form-control" name="user_email">
    </div>
    <div class="form-group">
        <label for="Password">Password</label>
        <input type="password" class="form-control" name="user_password">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="create_user" value="Add user">
    </div>

</form>