<?php include "includes/header.php"; ?>
<?php include "includes/db.php"; ?>

<!-- Navigation -->
<?php include "includes/navigation.php"; ?>
<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php //search bar query
            search();
            ?>


        </div>


        <!-- sidebar  -->
        <?php include "includes/sidebar.php"; ?>
    </div>
    <hr>
    <?php include "includes/footer.php"; ?>