<?php function redirect($location=null){
     return header("Location:". $location);  
}

function ifItIsMethod($method=null){
    if($_SERVER['REQUEST_METHOD'] == strtoupper($method)){
        return true;
    }
    return false;
}

function isLoggedin($user_role=null){
    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == $user_role){
          // User is authorized, allow access to the content
        return true;
    }
    return false;
}

function checkIfUserIsLoggedInAndRedirect($location=null){
    if(isLoggedin('admin')){
        redirect($location);
    }else{
        exit;
    }
}

function echo1 ($string=null){
    if (isset($string)) {  echo $string; }
    return;
}

function escape($string){
    global $connection;
   return mysqli_real_escape_string($connection, trim(strip_tags($string)));
}

function confirm($result=null)
{
    global $connection;
    if (!$result) {
        die("QUERY FAILED " . mysqli_error($connection));
    }
    return;
}

function insert_categories()
{
    global $connection;

    if (isset($_POST['submit'])) {
        $cat_title = $_POST['cat_title'];

        if ($cat_title == "" || empty($cat_title)) {
            echo "This field should not be empty";
        } else {
            $query = "INSERT INTO categories(cat_title) VALUES(?)";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "s", $cat_title);
            mysqli_stmt_execute($stmt);
            $create_category = mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            
        }
    }
    return;
}

function approve_comment()
{
    global $connection;

    if (isset($_GET['approve'])) {
        $the_comment_id = $_GET['approve'];

        $query = "UPDATE comments SET comment_status = 'approved' WHERE comment_id =  $the_comment_id";
        $approve_query = mysqli_query($connection, $query);
        header("location: comments.php");
    }
}

function disapprove_comment()
{
    global $connection;
    if (isset($_GET['disapprove'])) {
        $the_comment_id = $_GET['disapprove'];

        $query = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id =  $the_comment_id";
        $disapprove_query = mysqli_query($connection, $query);
        header("location: comments.php");
    }
}

function delete()
{
    global $connection;
    if (isset($_GET['delete'])) {
        $the_comment_id = $_GET['delete'];

        $query = "DELETE FROM comments WHERE comment_id = {$the_comment_id}";
        $the_delete_query = mysqli_query($connection, $query);
        header("location: comments.php");
    }
}

function findAllcategories()
{
    global $connection;
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($select_categories)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        echo "<tr>";
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";
        echo "<td><a class='btn btn-danger' href='categories.php?delete={$cat_id}'>X</a></td>";
        echo "<td><a class='btn btn-info' href='categories.php?update={$cat_id}'>Edit</a></td>";
        echo "</tr>";
    }
}

function deleteCategories()
{
    global $connection;
    if (isset($_GET['delete'])) {
        $the_cat_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
        $delete_query = mysqli_query($connection, $query);
        header("Location: categories.php");
    }
}

function update_categories()
{
    if (isset($_GET['update'])) {
        $cat_id = $_GET['update'];
        include "includes/edit_categories.php";
    }
}

function change_to_admin()
{
    global $connection;

    if (isset($_GET['change_to_admin'])) {
        $the_user_id = $_GET['change_to_admin'];

        $query = "UPDATE users SET user_role = 'admin' WHERE user_id =  $the_user_id";
        $admin_query = mysqli_query($connection, $query);
        header("location: users.php");
    }
}

function change_to_sub()
{
    global $connection;

    if (isset($_GET['change_to_sub'])) {
        $the_user_id = $_GET['change_to_sub'];

        $query = "UPDATE users SET user_role = 'subscriber' WHERE user_id =  $the_user_id";
        $sub_query = mysqli_query($connection, $query);
        header("location: users.php");
    }
}
function delete_user()
{
    global $connection;

    if (isset($_GET['delete'])) {
        $the_user_id = $_GET['delete'];

        $query = "DELETE FROM users WHERE user_id = {$the_user_id}";
        $the_delete_query = mysqli_query($connection, $query);
        header("location: users.php");
    }
}

// session authenthication to access admin from login
function is_authenticated() {
    global $connection;
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
  }

//   count in admin home
  function recordCount($tablename=null){
    global $connection;
    $query = "SELECT * FROM " . $tablename;
    $select_all_post = mysqli_query($connection, $query);
    return mysqli_num_rows($select_all_post);
  }
// Counting the records of draft, subscribers and published posts
  function checkStatus($tablename, $column, $status){
    global $connection;
    $query = "SELECT * FROM $tablename WHERE $column = '$status' ";
    $result = mysqli_query($connection, $query);
    return mysqli_num_rows($result);
  }


  function is_admin($username=null){
    global $connection;

    $query = "SELECT user_role FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);

    $row = mysqli_fetch_array($result);

    if($row['user_role'] == 'admin'){
        return true;
    }else{
        return false;
    }
  }

//   checking to see if username is already in database
  function username_exists($username=null){
    global $connection;

    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) > 0) {
        return true;
    }else{
        return false;
    }
  }

//   checking to see if email is already in database
  function email_exists($email=null){
    global $connection;

    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result) > 0) {
        return true;
    }else{
        return false;
    }
  }

function register_user($username=null, $email=null, $password=null){
    global $connection;
        
        $username =  escape($username);
        $password =  escape($password);
        $email    =  escape($email);
        $user_password = password_hash($password, PASSWORD_ARGON2I);

        $query = "INSERT INTO users (username, user_email, user_password, user_role) ";
        $query .= "VALUES('{$username}', '{$email}', '{$user_password}', 'subscriber' )";
        $register_user_query = mysqli_query($connection, $query);
        confirm($register_user_query);
    return;

}

function login_user($username=null, $password=null){
    global $connection;
    $username = escape($_POST['username']);
    $password = escape($_POST['password']);

    $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $row = $result->fetch_assoc();
      $db_user_id = $row['user_id'];
      $db_username = $row['username'];
      $db_user_firstname = $row['user_firstname'];
      $db_user_lastname = $row['user_lastname'];
      $db_user_password = $row['user_password'];
      $db_user_role = $row['user_role'];

      

      if (password_verify($password, $db_user_password)) {
        // login successful, set session variables and redirect to admin page
        $session_token = bin2hex(random_bytes(16));
        $expiry_time = time() + 1800; // Set expiry time to one hour from now
        $stmt = $connection->prepare("INSERT INTO sessions (user_id, session_token, expiry_time) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $db_user_id, $session_token, $expiry_time);
        $stmt->execute();

        $_SESSION['username'] = $db_username;
        $_SESSION['firstname'] = $db_user_firstname;
        $_SESSION['lastname'] = $db_user_lastname;
        $_SESSION['user_role'] = $db_user_role;

        
        redirect("/cms2/admin");
      
        } else {
            return false;
        }
   }
   return true; 
}
?>