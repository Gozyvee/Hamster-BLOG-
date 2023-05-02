<?php
function users_online(){
    if(isset($_GET['onlineusers'])){

        global $connection;
        if(!$connection) {
          
        
        }
    }
}
users_online();

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
            $query = "INSERT INTO categories(cat_title) ";
            $query .= "VALUES('{$cat_title}')";

            $create_category = mysqli_query($connection, $query);

            if (!$create_category) {
                die("QUERY FAILED" . mysqli_error($connection));
            }
        }
    }
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
function display_comment()
{
    global $connection;
    $query = "SELECT * FROM comments";
    $select_comments = mysqli_query($connection, $query);

    while ($row = mysqli_fetch_assoc($select_comments)) {
        $comment_id = $row['comment_id'];
        $comment_post_id = $row['comment_post_id'];
        $comment_author = $row['comment_author'];
        $comment_email = $row['comment_email'];
        $comment_content = $row['comment_content'];
        $comment_status = $row['comment_status'];
        $comment_date = $row['comment_date'];

        echo "<tr>";
        echo "<td>{$comment_id}</td>";
        echo "<td>{$comment_author}</td>";
        echo "<td>{$comment_content}</td>";

        //  $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
        //  $select_categories_id = mysqli_query($connection, $query);

        //  while ($row = mysqli_fetch_assoc($select_categories_id)) {
        //      $cat_id = $row['cat_id'];
        //      $cat_title = $row['cat_title'];

        //  echo "<td>{$cat_title}</td>";
        //  }

        echo "<td>{$comment_email}</td>";
        echo "<td>{$comment_status}</td>";

        $query = "SELECT * FROM posts WHERE post_id =  $comment_post_id ";
        $select_post_id_query = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($select_post_id_query)) {
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];

            echo "<td> <a href='../post.php?p_id=$post_id'> $post_title</a> </td>";
        }


        echo "<td>{$comment_date}</td>";
        echo "<td> <a href='comments.php?approve={$comment_id}'>Approve</a></td>";
        echo "<td> <a href='comments.php?disapprove={$comment_id}'>Disapprove</a></td>";
        echo "<td> <a href='comments.php?delete={$comment_id}'>Delete</a></td>";
        echo "</tr>";
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


  