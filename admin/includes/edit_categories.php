<form action="#" method="post">
    <div class="form-group">
        <label for="cat_title">Edit Category</label>

        <?php //update query

        if (isset($_GET['update'])) {
            $cat_id = $_GET['update'];

            $stmt = $connection->prepare("SELECT * FROM categories WHERE cat_id = ?");
            $stmt->bind_param("i", $cat_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
            }


        ?>

        <?php } ?>
        <?php
        if (isset($_POST['update_category'])) {
            $the_cat_title = $_POST['cat_title'];

            $query = "UPDATE categories SET cat_title = ? WHERE cat_id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("si", $the_cat_title, $cat_id);
            $update_query = $stmt->execute();
            confirm($update_query);
            header("Location: categories.php");
        }
        ?>
        <input value="<?php echo1($cat_title) ?>" type="text" class="form-control" name="cat_title">
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_category" value="Edit category">
    </div>
</form>