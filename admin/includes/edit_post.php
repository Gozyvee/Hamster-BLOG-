<?php
if (isset($_GET['p_id'])) {
    $the_post_id = $_GET['p_id'];
}

$stmt = $connection->prepare("SELECT * FROM posts WHERE post_id = ?");
$stmt->bind_param("i", $the_post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $post_id = $row['post_id'];
    $post_user = $row['post_user'];
    $post_title = $row['post_title'];
    $post_category_id = $row['post_category_id'];
    $post_status = $row['post_status'];
    $post_image = $row['post_image'];
    $post_content = $row['post_content'];
    $post_tags = $row['post_tags'];
    $post_comment_count = $row['post_comment_count'];
    $post_date = $row['post_date'];
}

if (isset($_POST['update_post'])) {

    // Define the parameters
    $post_user = escape($_POST['post_user']);
    $post_title = escape($_POST['post_title']);
    $post_category_id = escape($_POST['post_category']);
    $post_status = escape($_POST['post_status']);
    $post_image = escape($_FILES['image']['name']);
    $post_image_temp = escape($_FILES['image']['tmp_name']);
    $post_content = escape($_POST['post_content']);
    $post_tags = escape($_POST['post_tags']);

    // Move the uploaded file to the target directory
    move_uploaded_file($post_image_temp, "../images/$post_image");

    // Prepare the update query with parameter placeholders
    $query = "UPDATE posts SET ";
    $query .= "post_title = ?, ";
    $query .= "post_category_id = ?, ";
    $query .= "post_date = now(), ";
    $query .= "post_user = ?, ";
    $query .= "post_status = ?, ";
    $query .= "post_tags = ?, ";
    $query .= "post_content = ?, ";
    $query .= "post_image = ? ";
    $query .= "WHERE post_id = ? ";

    // Create a prepared statement
    $stmt = mysqli_prepare($connection, $query);

    // Bind the parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "sisssssi", $post_title, $post_category_id, $post_user, $post_status, $post_tags, $post_content, $post_image, $the_post_id);

    // Execute the prepared statement
    $update_post = mysqli_stmt_execute($stmt);

    // Redirect to the appropriate page
    echo "<p class='bg-success'>Post Updated.  <a href='../post.php?p_id={$the_post_id}'> View Posts</a> or <a href='posts.php'>Edit More Posts<a/></p>";
}


?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Post title</label>
        <input value="<?php echo1 ($post_title) ?>" type="text" class="form-control" name="post_title">
    </div>
    <div class="form-group">
        <select name="post_category" id="">
            <?php
            $query = "SELECT cat_id, cat_title FROM categories";
            $stmt = $connection->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];

                echo "<option value='" . htmlspecialchars($cat_id, ENT_QUOTES) . "'>" . htmlspecialchars($cat_title, ENT_QUOTES) . "</option>";
            }


            ?>
        </select>

    </div>
    <div class="form-group">
        <label for="title">Post Author</label>
        <input value="<?php echo1 ($post_user); ?>" type="text" class="form-control" name="post_user">
    </div>

    <div class="form-group">
        <select name="post_status" id="">
            <option value='<?php echo1 ($post_status); ?>'><?php echo1 ($post_status) ?></option>

            <?php
            if ($post_status == 'published') {
                echo " <option value='draft'>Draft</option>";
            } else {
                echo " <option value='published'>Published</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="title">Post Image</label>
        <input src="../images/<?php echo1 ($post_image); ?>" class="form-control" type="file" name="image">
    </div>
    <div class="form-group">
        <label for="title">Post Tags</label>
        <input value="<?php echo1 ($post_tags); ?>" type="text" class="form-control" name="post_tags">
    </div>
    <div class="form-group">
        <label for="title">Post Content</label>
        <textarea class="form-control" name="post_content" id="" cols="30" rows="10"><?php echo htmlspecialchars(strip_tags($post_content)); ?>
        </textarea>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="update_post" value="Edit post">
    </div>
</form>