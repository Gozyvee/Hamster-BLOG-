<?php include "includes/header.php";
      include "includes/navigation.php"; 
?>
<!-- Navigation -->
<?php 
    if(isset($_POST['liked'])){
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];
        //fetching the post
        $searchPostQuery = "SELECT * FROM posts WHERE post_id=$post_id";
        $postResult = mysqli_query($connection, $searchPostQuery);
        $post = mysqli_fetch_array($postResult);
        $likes = $post['likes'];

        //updating likes
        mysqli_query($connection, "UPDATE posts SET likes=$likes+1 WHERE post_id=$post_id");

        //create likes for post
        mysqli_query($connection, "INSERT INTO likes(user_id, post_id) VALUES($user_id, $post_id)");
        exit();
    }
    if(isset($_POST['unliked'])){
        $post_id = $_POST['post_id'];
        $user_id = $_POST['user_id'];
        //fetching the post
        $searchPostQuery = "SELECT * FROM posts WHERE post_id=$post_id";
        $postResult = mysqli_query($connection, $searchPostQuery);
        $post = mysqli_fetch_array($postResult);
        $likes = $post['likes'];
        
        //delete likes
        mysqli_query($connection, "DELETE FROM likes WHERE post_id=$post_id AND user_id=$user_id");
       
        //deleting likes
        mysqli_query($connection, "UPDATE posts SET likes=$likes-1 WHERE post_id=$post_id");
        exit();
    }
?>
<!-- Page Content -->
<div class="container">

    <div class="row ">

        <!-- Blog  Entries Column -->
        <div class="col-md-8">

            <?php //Add posts query
            if(isset($_GET['p_id'])){
                $the_post_id = $_GET['p_id'];
            
                $view_post_query = "UPDATE posts SET post_view_count = post_view_count + 1 WHERE post_id = $the_post_id";
                $send_query = mysqli_query($connection, $view_post_query);

                if (isLoggedin('admin')) {
                      $query = "SELECT * FROM posts WHERE post_id = $the_post_id";
                }else{
                    $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published' ";
                }
                $select_posts = mysqli_query($connection, $query);
                if(mysqli_num_rows($select_posts) < 1) {
                    echo "<h1> No Posts Available</h1><small>You need to login to view</small>";
                } else {
                 while ($row = mysqli_fetch_assoc($select_posts)) {
                    $post_title = $row['post_title'];
                    $post_user = $row['post_user'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];

            ?>

                <h1 class="page-header">
                    Posts
                </h1>

                <!-- First Blog Post -->
                <h2>
                    <a href="#"><?php echo $post_title ?></a>
                </h2>
                <p class="lead">
                    by <a href="#"><?php echo $post_user ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?> </p>
                <hr>
                <img class="img-responsive" src="/cms2/images/<?php echo htmlspecialchars($post_image); ?>" alt="">
                <hr>
                <?php if(isLoggedin('admin') || isLoggedin('subscriber')): ?>

                <div class="row">
                    <p class="pull-left ml-3">
                        <a class="<?php echo userLikedThisPost($the_post_id) ? 'unlike' : 'like'; ?>" href="" data-toggle="tooltip"
                            title="<?php echo userLikedThisPost($the_post_id) ? 'you have already liked' : 'Want to like?';?>"> 
                            <span class="glyphicon glyphicon-thumbs-up"><?php echo userLikedThisPost($the_post_id) ? 'Unlike' : 'Like'; ?></span>
                            <span> <?php echo getPostLikes($the_post_id); ?></span>
                       </a>
                    </p>
                </div>
                <div class="clearfix"></div>
                <?php else: ?>

                    <div class="row">
                        <p class="pull_left">
                            You need to <a href="/cms2/login.php">Login</a> to like
                        </p>
                    </div>
                <?php endif; ?>

                <p><?php echo $post_content ?></p>
                <hr>
        
    <?php }
    ?>

    
                <!-- Blog Comments -->
                <?php 
            
                    if(isset($_POST['create_comment'])) {
                                   
                        $the_post_id = $_GET['p_id'];
                        $comment_author = $_POST['comment_author'];
                        $comment_email = $_POST['comment_email'];
                        $comment_content = $_POST['comment_content'];
            
                        if(!empty($comment_author) && !empty($comment_email) && !empty($comment_content)){
                            $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date)";
                            $query .= " VALUES ($the_post_id, '{$comment_author}','{$comment_email}','{$comment_content}', 'unapproved', now() )";
                        
                             $create_comment_query = mysqli_query($connection, $query); 
                        } else {
                            echo "<script>alert('Fields cannot be empty')</script>";
                        }
                        exit();
                    }
                

                ?>

                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form role="form" action="" method="post">
                        <div class="form-group">
                            <label for="Author">Author</label>
                            <input type="text" class="form-control" name="comment_author">
                        </div>
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="email" class="form-control" name="comment_email">
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea name="comment_content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->

                <?php 
                    $query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id}";
                    $query .= " AND comment_status = 'approved'";
                    $query .= " ORDER BY comment_id DESC";
                    $select_comment_query = mysqli_query($connection, $query);
                   if(!$select_comment_query) {
                    die('Query failed' . mysqli_error($connection));
                   }
                    while ($row = mysqli_fetch_array($select_comment_query)) {
                        $comment_date = $row['comment_date'];
                        $comment_content = $row['comment_content'];
                        $comment_author = $row['comment_author'];
                    
                        ?>

                         <!-- Comment -->
                  <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo $comment_author ?>
                            <small><?php echo $comment_date ?></small>
                        </h4>
                        <?php echo $comment_content ?>
                    </div>
                </div>

                    <?php }} } else {
        header("Location: index.php");
    }?>
                 

               
                
    </div>


    <!-- sidebar  -->
    <?php include "includes/sidebar.php"; ?>
    </div>
    <hr>
    <?php include "includes/footer.php"; ?>
    <script>
        $(document).ready(function(){
            var post_id = <?php echo $the_post_id ?>;
            var user_id = <?php echo loggedInUserId() ?>;
            //like
            $('.like').click(function(){
                $.ajax({
                    url: "/cms2/post.php?p_id=<?php echo $the_post_id; ?>",
                    type: 'post',
                    data: {
                        liked: 1,
                        'post_id': post_id,
                        'user_id': user_id 
                    }
                })
            });
            //unlike
            $('.unlike').click(function(){
                $.ajax({
                    url: "/cms2/post.php?p_id=<?php echo $the_post_id; ?>",
                    type: 'post',
                    data: {
                        unliked: 1,
                        'post_id': post_id,
                        'user_id': user_id 
                    }
                })
            });
        });
        $('data-toggle="tooltip"').tooltip();
    </script>