<?php 

    if(isset($_GET['edit_user'])) {
       $the_user_id = $_GET['edit_user'];

        $query = "SELECT * FROM users WHERE user_id = $the_user_id";
        $select_users_query = mysqli_query($connection, $query);

        while ($row = mysqli_fetch_assoc($select_users_query)) {

            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_password = $row['user_password'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_image = $row['user_image'];
            $user_role = $row['user_role'];
        
    }
}

if(isset($_GET['edit_user'])) {
    $the_user_id = $_GET['edit_user'];

    // Use prepared statements to prevent SQL injection
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
}

if(isset($_POST['edit_user'])){

    $user_firstname = mysqli_real_escape_string($connection, $_POST['user_firstname']); // Sanitize input to prevent XSS attacks
    $user_lastname = mysqli_real_escape_string($connection, $_POST['user_lastname']);
    $user_role = mysqli_real_escape_string($connection, $_POST['user_role']);
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $user_email = mysqli_real_escape_string($connection, $_POST['user_email']);
    $user_password = mysqli_real_escape_string($connection, $_POST['user_password']);

    // Use password_hash() function with a strong hash algorithm like bcrypt or Argon2 to securely store passwords
    $user_password = password_hash($user_password, PASSWORD_ARGON2I);

    $query = "UPDATE users SET ";
    $query .= "user_firstname = ?, ";
    $query .= "user_lastname = ?, ";
    $query .= "username = ?, ";
    $query .= "user_email = ?, ";
    $query .= "user_password = ? ";
    $query .= "WHERE user_id = ? ";

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $user_firstname, $user_lastname, $username, $user_email, $user_password, $the_user_id);
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
        <input type="text" value="<?php echo $user_firstname ?>" class="form-control" name="user_firstname">
    </div>
    <div class="form-group">
        <label for="title">Lastname</label>
        <input type="text" value="<?php echo $user_lastname ?>" class="form-control" name="user_lastname">
    </div>

    <!-- <div class="form-group">
        <select name="user_role" >
            <option value="subscriber"><?php echo $user_role; ?></option>
            <?php 
                if($user_role == 'Admin') {
                    echo " <option value='Subscriber'>Subscriber</option>";
                } else {
                    echo " <option value='Admin'>Admin</option>";
                }
             ?>
          
          
        </select>
    </div>     -->
     
    <!-- <div class="form-group">
        <label for="title">Post Image</label>
        <input type="file" class="form-control" name="">
    </div> -->

    <div class="form-group">
        <label for="Username">Username</label>
        <input type="text" value ="<?php echo $username?>" class="form-control" name="username">
    </div>
    <div class="form-group">
        <label for="Email">Email</label>
        <input type="email" value="<?php echo $user_email?>" class="form-control" name="user_email">
    </div>
    <div class="form-group">
        <label for="Password">Password</label>
        <input type="password" value="<?php echo $user_password?>" class="form-control" name="user_password">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="edit_user" value="Edit user">
    </div>
   
</form>