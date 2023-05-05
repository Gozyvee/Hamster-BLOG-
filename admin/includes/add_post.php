<?php
if (isset($_POST['create_post'])) {
    $post_title = escape($_POST['title']);
    $post_user = escape($_POST['post_user']);
    $post_category_id = escape($_POST['post_category']);
    $post_status = escape($_POST['post_status']);

    $post_image = escape($_FILES['image']['name']);
    $post_image_temp = escape($_FILES['image']['tmp_name']);

    $post_tags = escape($_POST['post_tags']);
    $post_content = escape($_POST['post_content']);
    $post_date = escape(date('d-m-y'));

    $stmt = mysqli_prepare($connection, "INSERT INTO posts(post_category_id, post_title, post_user, post_date, post_image, post_content, post_tags, post_status) VALUES (?, ?, ?, now(), ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, "issssss", $post_category_id, $post_title, $post_user, $post_image, $post_content, $post_tags, $post_status);

    mysqli_stmt_execute($stmt);

    $the_post_id = mysqli_insert_id($connection);

    move_uploaded_file($post_image_temp, "../images/$post_image");

    echo "<p class='bg-success'>Post Created. <a href='../post.php?p_id={$the_post_id}'>View Posts</a> or <a href='posts.php'>Edit More Posts<a/></p>";
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Post title</label>
        <input type="text" class="form-control" name="title">
    </div>

    <div class="form-group">
        <label for="Categories">Categories</label>
        <select name="post_category" id="">
            <?php
            $stmt = mysqli_prepare($connection, "SELECT cat_id, cat_title FROM categories");

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
                echo "<option value='$cat_id'>{$cat_title}</option>";
            }
            mysqli_stmt_close($stmt);
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="users">Users</label>
        <select name="post_user" id="">
            <?php
            $query = "SELECT user_id, username FROM users";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $user_id = $row['user_id'];
                $username = $row['username'];

                echo "<option value='$username'>{$username}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <select name="post_status" id="">
            <option value="draft">Post Status</option>
            <option value="published">Publish</option>
            <option value="draft">Draft</option>
        </select>
    </div>
    <div class="form-group">
        <label for="title">Post Image</label>
        <input type="file" class="form-control" name="image">
    </div>
    <div class="form-group">
        <label for="title">Post Tags</label>
        <input type="text" class="form-control" name="post_tags">
    </div>
    <div class="form-group">
        <label for="title">Post Content</label>
        <textarea class="form-control" name="post_content" id="body" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="create_post" value="Publish post">
    </div>

</form>