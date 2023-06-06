    <?php include "includes/header.php"; ?>
    <!-- Navigation -->
    <?php include "includes/navigation.php"; ?>    
    <!-- Page Content -->
    <div class="container">
        <div class="row">
        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            } else {
                $page = "";
            }
            if ($page == "" || $page == 1) {
                $page_1 = 0;
            } else {
                $page_1 = ($page * 5) - 5;
            }
            ?>

<?php
// Get total number of posts

if (isLoggedin('admin')) {
    $post_query_count = "SELECT COUNT(*) as count FROM posts ";
  } else {
    $post_query_count = "SELECT COUNT(*) as count FROM posts WHERE post_status = 'published'";
  }
$stmt = mysqli_prepare($connection, $post_query_count);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$count = $row['count'];
if($count < 1) {
    echo "<h1>No Posts</h1>";
}else{
$count = ceil($count / 5);

// Get posts for current page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page_1 = ($page - 1) * 5;
$post_query = "SELECT * FROM posts ORDER BY post_date DESC LIMIT ?, 5";
$stmt = mysqli_prepare($connection, $post_query);
mysqli_stmt_bind_param($stmt, "i", $page_1);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Output posts
foreach ($rows as $row) {
    $post_id = $row['post_id'];
    $post_title = $row['post_title'];
    $post_user = $row['post_user'];
    $post_date = $row['post_date'];
    $post_image = $row['post_image'];
    $post_content = substr($row['post_content'], 0, 100);
    $post_status = $row['post_status'];
    ?>
    <h1 class="page-header">
        Page Heading
        <small>Secondary Text</small>
    </h1>
    <!-- First Blog Post -->
    <h2>
 <a href="post/<?php echo htmlspecialchars($post_id); ?>"><?php echo htmlspecialchars($post_title); ?></a>
    </h2>
    <p class="lead">
        by <a href="author.php?user=<?php echo $post_user; ?>&p_id=<?php echo htmlspecialchars($post_id); ?>"> <?php echo htmlspecialchars($post_user); ?></a>
    </p>
    <p><span class="glyphicon glyphicon-time"></span> <?php echo htmlspecialchars($post_date); ?> </p>
    <hr>
    <a href="post/<?php echo htmlspecialchars($post_id); ?>">
        <img class="img-responsive" src="images/<?php echo htmlspecialchars($post_image); ?>" alt="">
    </a>
    <hr>
    <p><?php echo ($post_content); ?></p> 
    <a class="btn btn-primary" href="post/<?php echo htmlspecialchars($post_id);?>">Read More<span class="glyphicon glyphicon-chevron-right"></span></a>
    <hr>
    <?php } } ?>
</div> 
<?php include "includes/sidebar.php"; ?>
</div>
<hr>
        <!-- Pagination -->
    <ul class="pager">
        <?php
        for ($i = 1; $i <= $count; $i++) {
            if ($i == $page) {
                echo "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>";
            } else {
                echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";
            }
        }
        ?>
    </ul>
 <?php include "includes/footer.php"; ?>