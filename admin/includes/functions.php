<?php
function echo1 ($string){
    if (isset($string)) {  echo $string; }
    return;
}
function escape($string){
    global $connection;
   return mysqli_real_escape_string($connection, trim(strip_tags($string)));
}

function confirm($result)
{
    global $connection;
    if (!$result) {
        die("QUERY FAILED " . mysqli_error($connection));
    }
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
        echo "<td><a href='categories.php?delete={$cat_id}'>X</a></td>";
        echo "<td><a href='categories.php?update={$cat_id}'>Edit</a></td>";
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


  