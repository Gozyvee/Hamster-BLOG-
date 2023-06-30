<?php include "includes/header.php"; ?>
<!-- Navigation -->
<?php include "includes/navigation.php"; ?>
<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php //Display Categories 

          // Ensure that the category ID is set and is an integer
          if(isset($_GET['category']) && is_numeric($_GET['category'])) {
              $post_category_id = $_GET['category'];

              if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                $query = "SELECT * FROM posts WHERE post_category_id = ?";
              } else {
                $query = "SELECT * FROM posts WHERE post_category_id = ? AND post_status = 'published'";
              }
          
              // Prepare and execute the query with a parameterized statement to prevent SQL injection
             
              $stmt = mysqli_prepare($connection, $query);
              mysqli_stmt_bind_param($stmt, "i", $post_category_id);
              mysqli_stmt_execute($stmt);
          
              $result = mysqli_stmt_get_result($stmt);

              if(mysqli_num_rows($result) < 1) {
                echo "<h1>No Post</h1>";
              }
          
              // Loop through the results and display each post
              while ($row = mysqli_fetch_assoc($result)) {
                  $post_id = $row['post_id'];
                  $post_title = $row['post_title'];
                  $post_author = $row['post_user'];
                  $post_date = $row['post_date'];
                  $post_image = $row['post_image'];
                  $post_content = substr($row['post_content'], 0, 100);
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
                  <img class="img-responsive" src="/cms2/images/<?php echo $post_image; ?>" alt="">
                  <hr>
                  <p><?php echo $post_content ?></p>
                  <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                  <hr>
          
          <?php 
              }
              // Free the result set and close the statement
              mysqli_free_result($result);
              mysqli_stmt_close($stmt);
          } else {
              // Handle the case when the category ID is missing or not valid
              echo "Invalid category ID.";
          }?>
          
    </div>
    <!-- sidebar  -->
    <?php include "includes/sidebar.php"; ?>
    </div>
    <hr>
    <?php include "includes/footer.php"; ?>