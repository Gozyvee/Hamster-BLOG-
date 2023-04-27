<?php 
    function Categories (){
        global $connection;
        $query = "SELECT * FROM categories";
        $select_all_categories = mysqli_query($connection, $query);
    
        while($row = mysqli_fetch_assoc($select_all_categories)) {
        $cat_title = $row['cat_title'];
    
         echo "<li><a href ='#'>{$cat_title}</a></li>";
         }  
    }

    function view_published_post(){
        global $connection;
        $query = "SELECT * FROM posts WHERE post_status = 'published' ";
        $select_posts = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($select_posts)) {
            $post_id = $row['post_id'];
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = substr($row['post_content'], 0, 100);
            $post_status = $row['post_status'];

            if($post_status == 'published') {
                
        ?>

            <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1>

            <!-- First Blog Post -->
            <h2>
                <a href="post.php?p_id=<?php echo $post_id ?>"><?php echo $post_title ?></a>
            </h2>
            <p class="lead">
                by <a href="index.php"><?php echo $post_author ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?> </p>
            <hr>
            <a href="post.php?p_id=<?php echo $post_id ?>">
            <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
            </a>
            <hr>
            <p><?php echo $post_content ?></p>
            <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
            <hr>
    
<?php } }
    }

   
    function create_comment() {
        global $connection;

        if(isset($_POST['create_comment'])) {
                       
            $the_post_id = $_GET['p_id'];
            $comment_author = $_POST['comment_author'];
            $comment_email = $_POST['comment_email'];
            $comment_content = $_POST['comment_content'];

            if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)){
                $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date)";
                $query .= " VALUES ($the_post_id, '{$comment_author}','{$comment_email}','{$comment_content}', 'unapproved', now() )";
            
                 $create_comment_query = mysqli_query($connection, $query);
                  // if(!$create_comment_query) {
                  //     die('QUERY FAILED ' . mysqli_error($connection));
                 // }
            
                    $query = "UPDATE posts SET  post_comment_count = post_comment_count + 1 ";
                    $query .= "WHERE post_id = $the_post_id ";
                    $update_comment_count = mysqli_query($connection, $query);

            } else {
                echo "<script>alert('Fields cannot be empty')</script>";
            }

            
        }
    }
    function search (){
        global $connection;

        if (isset($_POST['submit'])) {
            $search = $_POST['search'];

            $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search%' ";
            $search_query = mysqli_query($connection, $query);

            if (!$search_query) {
                die("QUERY FAILED" . mysqli_error($connection));
            }
            $count = mysqli_num_rows($search_query);
            if ($count == 0) {
                echo "<h1> NO RESULT </h1>";
            } else {
                
                while ($row = mysqli_fetch_assoc($search_query)) {
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];

        ?>

                    <h1 class="page-header">
                        Page Heading
                        <small>Secondary Text</small>
                    </h1>

                    <!-- First Blog Post -->
                    <h2>
                        <a href="#"><?php echo $post_title ?></a>
                    </h2>
                    <p class="lead">
                        by <a href="index.php"><?php echo $post_author ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?> </p>
                    <hr>
                    <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                    <hr>
                    <p><?php echo $post_content ?></p>
                    <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <hr>

        <?php }
            }}
    }
    
?>