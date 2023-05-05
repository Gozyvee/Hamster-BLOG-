<?php include "includes/admin_header.php" ?>

<div id="wrapper">

<?php include "includes/admin_navigation.php" ?>
<div id="page-wrapper">

<div class="container-fluid">
  <div class="col-lg-12">
        <h1 class="page-header">
            Welcome to admin
             <small><?php echo $_SESSION['username']; ?></small>
        </h1>

        <?php 
            if(isset($_GET['source'])) {
                $source = $_GET['source'];
            }else {
                $source = '';
            }
                switch($source) {

                    default: 
                    include "includes/view_all_comments.php";
                    break;

                
            }

             
        ?>
       
  </div>


</div>
</div>
</div>
<!-- /.container-fluid -->

<?php include "includes/admin_footer.php" ?>