<?php include "includes/header.php"; ?>
<?php include "includes/db.php"; ?>

<!-- Navigation -->
<?php include "includes/navigation.php"; ?>
<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php //Add posts query
           view_published_post(); 
            ?>
    </div>


    <!-- sidebar  -->
    <?php include "includes/sidebar.php"; ?>
    </div>
    <hr>
    <?php include "includes/footer.php"; ?>