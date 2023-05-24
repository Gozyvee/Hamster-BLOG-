<?php
include("delete_modal.php");   
if (isset($_POST['checkBoxArray'])) {
    $checkbox = $_POST['checkBoxArray'];

    foreach ($checkbox as $key) {
        $bulk_options = $_POST['bulk_options'];
        switch ($bulk_options) {
            case 'published':
                $query = "UPDATE posts SET post_status = ? WHERE post_id = ?";
                $statement = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($statement, "si", $bulk_options, $key);
                mysqli_stmt_execute($statement);
                break;

            case 'draft':
                $query = "UPDATE posts SET post_status = ? WHERE post_id = ?";
                $statement = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($statement, "si", $bulk_options, $key);
                mysqli_stmt_execute($statement);
                break;

            case 'delete':
                $query = "DELETE FROM posts WHERE post_id = ?";
                $statement = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($statement, "i", $key);
                mysqli_stmt_execute($statement);
                break;

            case 'clone';
                $query = "SELECT * FROM posts WHERE post_id = ?";
                $clone_stmt = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($clone_stmt, "i", $key);
                mysqli_stmt_execute($clone_stmt);
                $result = mysqli_stmt_get_result($clone_stmt);

                while ($row = mysqli_fetch_array($result)) {
                    $post_title = $row['post_title'];
                    $post_category_id = $row['post_category_id'];
                    $post_date = $row['post_date'];
                    $post_author = $row['post_user'];
                    $post_status = $row['post_status'];
                    $post_image = $row['post_image'];
                    $post_tags = $row['post_tags'];
                    $post_content = $row['post_content'];

                    $query = "INSERT INTO posts(post_category_id, post_title, post_user, post_date, post_image, post_content, post_tags, post_status) 
                              VALUES(?, ?, ?, now(), ?, ?, ?, ?)";
                    $copy_stmt = mysqli_prepare($connection, $query);
                    mysqli_stmt_bind_param($copy_stmt, "issssss", $post_category_id, $post_title, $post_author, $post_image, $post_content, $post_tags, $post_status);
                    $copy_query = mysqli_stmt_execute($copy_stmt);
                    confirm($copy_query);
                }
                header("Location: posts.php");
                break;
        }
    }
}
?>
<form action="" method="post">

    <table class="table table-bordered table-hover">
        <div id="bulkOptionsContainer" class="col-xs-4">
            <select class="form-control" name="bulk_options" id="">
                <option value="">Select Options</option>
                <option value="published">Publish</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
            </select>
        </div>
        <div class="col-xs-4">
            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a href="posts.php?source=add_post" class="btn btn-primary">Add New</a>
        </div>
        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th>id</th>
                <th>Users</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Comments</th>
                <th>Date</th>
                <th>View post</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Views</th>
            </tr>
        </thead>
        <tbody>
            <?php  //display post query
            $query = "SELECT * FROM posts ORDER BY post_id DESC";
            $select_posts = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($select_posts)) {
                $post_id = $row['post_id'];
                $post_user = $row['post_user'];
                $post_title = $row['post_title'];
                $post_category_id = $row['post_category_id'];
                $post_status = $row['post_status'];
                $post_image = $row['post_image'];
                $post_tags = $row['post_tags'];
                $post_comment_count = $row['post_comment_count'];
                $post_date = $row['post_date'];
                $post_view = $row['post_view_count'];

                echo "<tr>";

            ?>
                <td><input class='CheckBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id ?>'></td>
            <?php

                echo "<td>{$post_id}</td>";

                if (isset($post_author) || !empty($post_author)) {
                    echo "<td>{$post_author}</td>";
                } elseif (isset($post_user) || !empty($post_user)) {
                    echo "<td>{$post_user}</td>";
                }

                echo "<td>{$post_title}</td>";

                $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
                $select_categories_id = mysqli_query($connection, $query);

                while ($row = mysqli_fetch_assoc($select_categories_id)) {
                    $cat_id = $row['cat_id'];
                    $cat_title = $row['cat_title'];

                    echo "<td>{$cat_title}</td>";
                }

                echo "<td>{$post_status}</td>";
                echo "<td><img src='../images/$post_image' width='100' alt='image'></td>";
                echo "<td>{$post_tags}</td>";

                $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                $send_comment = mysqli_query($connection, $query);
                $count_comments = mysqli_num_rows($send_comment);

                if ($row = mysqli_fetch_array($send_comment)) {
                    $comment_id = $row['comment_id'];
                } else {
                    // Handle the case where there are no comments for this post
                    $comment_id = null;
                }

                echo "<td><a href='post_comments.php?id=$post_id'>$count_comments</a></td>";

                echo "<td>{$post_date}</td>";
                echo "<td> <a href='../post.php?p_id={$post_id} '>View Post</a></td>";
                echo "<td> <a href='posts.php?source=edit_post&p_id=$post_id'>Edit</a></td>";
                echo "<td> <a href='javascript:void(0)' class='delete_link' rel='{$post_id}' >Delete</a></td>";
                echo "<td><a href='posts.php?reset=$post_id'>{$post_view}</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</form>

<?php
if (isset($_GET['delete'])) {
    $the_post_id = $_GET['delete'];

    $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";
    $the_delete_query = mysqli_query($connection, $query);
    header("location: posts.php");
}
if (isset($_GET['reset'])) {
    $the_post_id = $_GET['reset'];

    $query = "UPDATE posts SET post_view_count = 0 WHERE post_id=" . mysqli_real_escape_string($connection, $the_post_id) . "";
    $the_delete_query = mysqli_query($connection, $query);
    header("location: posts.php");
}

?>

<script>
    $(document).ready(function(){
        $('.delete_link').on('click', function(){
            var id = $(this).attr("rel");
            var delete_url = "posts.php?delete=" + id +" ";
            $(".modal_delete_link").attr("href", delete_url);
            $("#myModal").modal('show');
        });
    });
</script>